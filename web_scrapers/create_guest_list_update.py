#!/usr/bin/python3
# -*- coding: utf-8 -*-


# A script that imports JSON files describing the guest lists of members
# of parliament, checks if those guests are already in the database,
# and creates an updating SQL script accordingly.

# Created by Markus Roth in February 2017 (maroth@gmail.com)
# Licenced via Affero GPL v3

import json
import MySQLdb
import sys
import collections
import re
from datetime import datetime

database = MySQLdb.connect(
    user="lobbywatch", passwd="lobbywatch", host="10.0.0.2", db="csvimsne_lobbywatch")


def run():
    sync_data(database, "zutrittsberechtigte-nr.json", "NR")
    sync_data(database, "zutrittsberechtigte-sr.json", "SR")
    database.close()


def get_canton_id(canton_abbreviation):
    with database.cursor() as cursor:
        cursor.execute(
            "SELECT id FROM kanton WHERE abkuerzung = '{}'".format(canton_abbreviation))
        canton_id = cursor.fetchone()[0]
    return canton_id


def escape_string(string):
    result = string.replace("'", "''")
    return result


def get_party_id(party_abbreviation, member_of_parliament):
    if not party_abbreviation:
        return None
    with database.cursor() as cursor:
        cursor.execute("SELECT id from partei WHERE abkuerzung = '{0}' OR abkuerzung_fr = '{0}'".format(
            party_abbreviation))
        party_id = cursor.fetchone()

        if party_id is None:
            print("\n\n DATA INTEGRITY FAILURE: party '{}' referenced in PDF for member of parliament {} is not in database. Aborting.".format(
                party_abbreviation, guest_full_name(member_of_parliament)))
            sys.exit(1)
    return party_id[0]


def get_name(names, index):
    return names[index] if len(names) > index else ""


def name_query(names, description):
    vorname, zweiter_vorname, nachname = parse_name_combination(
        description, names)

    query = " AND vorname LIKE '{}%' AND nachname LIKE '{}%'".format(
        vorname, nachname)
    if zweiter_vorname:
        query += " AND zweiter_vorname LIKE '{}%'".format(zweiter_vorname)

    return query


def parse_name_combination(names, description):
    vorname = ""
    zweiter_vorname = ""
    nachname = ""
    for name, value in zip(names, description):
        if value == "V":
            vorname += " " + name
        if value == "Z":
            zweiter_vorname += " " + name
        if value == "N":
            nachname += " " + name
        if value == "S":
            vorname += " %" + name

    return vorname.strip(), zweiter_vorname.strip(), nachname.strip()


def get_member_of_parliament(parlamentarier, kanton_id, partei_id):
    with database.cursor() as cursor:
        parlamentarier_id = None
        query = (
            "SELECT id FROM parlamentarier WHERE kanton_id = {0}".format(kanton_id))
        if partei_id:
            query += " AND partei_id = '{}'".format(partei_id)
        else:
            query += " AND partei_id IS NULL"

        for description in ["NV", "NZV", "NNV", "NVV", "NNNV", "NS"]:
            current_query = query + \
                name_query(description, parlamentarier["names"])
            cursor.execute(current_query)
            result = cursor.fetchall()
            if result and len(result) == 1:
                (parlamentarier_id, ) = result[0]
                return parlamentarier_id

    print("\n\n DATA INTEGRITY FAILURE: Member of parliament '{0}' referenced in PDF is not in database. Aborting.".format(
        parlamentarier["names"]))
    sys.exit(1)


def get_person(guest):
    with database.cursor() as cursor:
        parlamentarier_id = None
        query = "SELECT id FROM person WHERE 1=1"

        for description in ["NV", "NZV", "NNV", "NVV", "NNNV", "NS"]:
            current_query = query + name_query(description, guest["names"])
            cursor.execute(current_query)
            result = cursor.fetchall()
            if result and len(result) > 1:
                print(
                    "\n\n DATA INTEGRITY FAILURE: There are multiple possibilities in the database for guest '{0}'. Aborting.".format(result))
                sys.exit(1)
            if result and len(result) == 1:
                (person_id, ) = result[0]
                return person_id

    return None


def get_guests(member_id):
    with database.cursor() as cursor:
        guest_query = (
            "SELECT person_id, funktion, id from zutrittsberechtigung WHERE parlamentarier_id = '{0}' AND bis IS NULL".format(member_id))
        cursor.execute(guest_query)
        guests = cursor.fetchall()
        return guests


def get_guest_names(person_id):
    with database.cursor() as cursor:
        person_query = (
            "SELECT nachname, vorname, zweiter_vorname from person WHERE id = {0}".format(person_id))
        cursor.execute(person_query)
        lists_of_names = [name.replace('"', "").replace(".", "").split(
            " ") if name else "" for name in cursor.fetchone()]
        return [name for namelist in lists_of_names for name in namelist]


def current_date_as_sql_string():
    return "{0}-{1}-{2}".format(datetime.now().year, datetime.now().month, datetime.now().day)


def insert_zutrittsberechtigung(parlamentarier_id, person_id, funktion):
    query = """INSERT INTO `csvimsne_lobbywatch`.`zutrittsberechtigung`
    (`parlamentarier_id`, `person_id`, `funktion`, `von`, `notizen`, `created_visa`)
    VALUES ({0}, {1}, '{2}', '{3}', '{4}', '{5}'); """.format(
        parlamentarier_id,
        person_id if person_id is not None else "(SELECT LAST_INSERT_ID())",
        funktion,
        current_date_as_sql_string(),
        "erzeugt von import",
        "import")
    return query


def update_function_of_zutrittsberechtigung(zutrittsberechtigung_id, function):
    query = """ UPDATE `csvimsne_lobbywatch`.`zutrittsberechtigung`
    SET `funktion` = '{0}', `notizen` = CONCAT_WS(notizen, '{1}'), `updated_visa` = 'import', `updated_date` = '{2}'
    WHERE `id` = {3}; """.format(
        escape_string(function),
        "funktion geändert durch import",
        current_date_as_sql_string(),
        zutrittsberechtigung_id)
    return query


def end_zutrittsberechtigung(zutrittsberechtigung_id):
    query = """ UPDATE `csvimsne_lobbywatch`.`zutrittsberechtigung`
    SET `bis` = '{0}', `notizen` = CONCAT_WS(notizen, '{1}'), `updated_visa` = 'import', `updated_date` = '{2}'
    WHERE `id` = {3}; """.format(
        current_date_as_sql_string(),
        "bis-datum gesetzt durch import",
        current_date_as_sql_string(),
        zutrittsberechtigung_id)
    return query


def insert_person(guest, function):
    query = """INSERT INTO `csvimsne_lobbywatch`.`person`
    (`nachname`, `vorname`, `zweiter_vorname`, `beschreibung_de`, `created_visa`)
    VALUES ('{0}', '{1}', '{2}', '{3}', '{4}');""".format(
        escape_string(guest["names"][0]),
        escape_string(guest["names"][1]),
        escape_string(guest["names"][2] if len(guest["names"]) > 2 else ""),
        escape_string(function),
        "import")
    return query


def guest_full_name(guest):
    return " ".join(guest["names"])


def common_start(names):
    name1, name2 = names
    return name1.startswith(name2) or name2.startswith(name1)


def are_guests_equal(guest1, guest2):
    if guest1 is None and guest2 is None:
        return False
    if guest1 is None and guest2 is not None:
        return False
    if guest1 is not None and guest2 is None:
        return False
    names1 = guest1["names"]
    names2 = guest2["names"]
    common_names = set(names1).intersection(set(names2))
    smallest_name_count = min(len(names1), len(names2))

    if len(common_names) >= smallest_name_count:
        return True
    if len(common_names) == 0:
        return False
    if len(names1) == 3 and len(names2) == 3 and len(common_names) == 2:
        return True
    if len(names1) == len(names2) and all(map(common_start, zip(names1, names2))):
        return True
    if smallest_name_count == 2 and len(common_names) < 2:
        return False
    if smallest_name_count > 2 and len(common_names) > 1:
        return True

    return False


def guest_removed(member_of_parliament, guest_to_remove):
    if guest_to_remove is not None:
        print("\n-- Parlamentarier_in '{}' hat die Zutrittsberechtigung von Gast '{}' mit Funktion '{}' beendet.".format(
            guest_full_name(member_of_parliament),
            guest_full_name(guest_to_remove),
            guest_to_remove["function"]))
        query = end_zutrittsberechtigung(
            guest_to_remove["zutrittsberechtigung_id"])
        print(query)


def guest_added(member_of_parliament, guest_to_add, function):
    print("\n-- Parlamentarier_in '{}' hat einen neuen Gast '{}' mit Funktion '{}'.".format(
        guest_full_name(member_of_parliament),
        guest_full_name(guest_to_add),
        function))
    person_id = get_person(guest_to_add)
    if not person_id:
        print("-- Diese_r muss neu in der Datenbank erzeugt werden")
        insert_query = insert_person(guest_to_add, function)
        print(insert_query)

    query = insert_zutrittsberechtigung(
        member_of_parliament["id"], person_id, function)
    print(query)


def normalize_function(function):
    stripped = function.lower().replace("-", "").replace(" ", "")
    # remove anything in braces ()
    return re.sub(r'\([^)]*\)', '', stripped).strip()


def are_functions_equal(function1, function2):
    equivalent_functions = [("Persönlicher Mitarbeiter", "Collaborateur(rice) personnel(le)"),
                            ("Persönlicher Mitarbeiter",
                             "Persönliche/r Mitarbeiter/in"),
                            ("Persönliche Mitarbeiterin",
                             "Persönliche/r Mitarbeiter/in"),
                            ("Collaborateur personel",
                             "Collaborateur(rice) personnel(le)"),
                            ("Collaboratrice personale",
                             "Collaboratore/trice personale"),
                            ("Collaboratrice personnelle",
                             "Collaborateur(rice) personnel(le)"),
                            ("Persönliche Mitarbeiterin",
                             "Collaborateur(rice) personnel(le)"),
                            ("Persönliche Mitarbeiterin",
                             "Collaboratore/trice personale"),
                            ("Gast", "Invité(e)"),
                            ("Persönlicher Mitarbeiter", "Collaborateur(rice) personnel(le)")]

    if function1 is None and function2 is not None:
        return False
    if function1 is not None and function2 is None:
        return False
    if function1 is None and function2 is None:
        return True

    if (function1, function2) in equivalent_functions:
        return True

    return normalize_function(function1) == normalize_function(function2)


def guest_remained(member_of_parliament, existing_guest, new_guest):
    if not are_functions_equal(existing_guest["function"], new_guest["function"]):
        print("\n-- Parlamentarier_in '{}' hat beim Gast '{}' die Funktion von '{}' auf '{}' geändert.".format(
            guest_full_name(member_of_parliament),
            guest_full_name(existing_guest),
            existing_guest["function"],
            new_guest["function"]))
        query = update_function_of_zutrittsberechtigung(
            existing_guest["zutrittsberechtigung_id"], new_guest["function"])
        print(query)


def extract_existing_guest(guest_id, function, zutrittsberechtigung_id):
    guest = {}
    guest["names"] = get_guest_names(guest_id)
    guest["function"] = function
    guest["id"] = guest_id
    guest["zutrittsberechtigung_id"] = zutrittsberechtigung_id
    return guest


def sync_data(database, filename, council):
    with open(filename) as nr_file:
        nr_data = json.load(nr_file)
        for member_of_parliament in nr_data:
            canton_abbreviation = member_of_parliament["canton"]
            canton_id = get_canton_id(canton_abbreviation)
            party_abbreviation = member_of_parliament["party"]
            party_id = get_party_id(party_abbreviation, member_of_parliament)
            member_id = get_member_of_parliament(
                member_of_parliament, canton_id, party_id)
            member_of_parliament["id"] = member_id

            print("\n\n------------------------------- ")
            print("-- {}: {}".format(council, " ".join([name for name in member_of_parliament["names"]])))
            print("------------------------------- ")

            existing_guests = get_guests(member_id)

            existing_guest_1 = extract_existing_guest(
                *existing_guests[0]) if len(existing_guests) > 0 else None
            existing_guest_2 = extract_existing_guest(
                *existing_guests[1]) if len(existing_guests) > 1 else None

            new_guests = member_of_parliament["guests"]
            new_guest_1 = new_guests[0] if len(new_guests) > 0 else None
            new_guest_2 = new_guests[1] if len(new_guests) > 1 else None

            are_guests_equal(existing_guest_1, new_guest_1)
            are_guests_equal(existing_guest_1, new_guest_2)
            are_guests_equal(existing_guest_2, new_guest_1)
            are_guests_equal(existing_guest_2, new_guest_2)

            if are_guests_equal(existing_guest_1, new_guest_1):
                guest_remained(member_of_parliament,
                               existing_guest_1, new_guest_1)
            elif are_guests_equal(existing_guest_1, new_guest_2):
                guest_remained(member_of_parliament,
                               existing_guest_1, new_guest_2)
            else:
                guest_removed(member_of_parliament, existing_guest_1)

            if are_guests_equal(existing_guest_2, new_guest_1):
                guest_remained(member_of_parliament,
                               existing_guest_2, new_guest_1)
            elif are_guests_equal(existing_guest_2, new_guest_2):
                guest_remained(member_of_parliament,
                               existing_guest_2, new_guest_2)
            else:
                guest_removed(member_of_parliament, existing_guest_2)

            if not are_guests_equal(new_guest_1, existing_guest_1) and not are_guests_equal(new_guest_1, existing_guest_2):
                if new_guest_1 is not None:
                    guest_added(member_of_parliament, new_guest_1,
                                new_guests[0]["function"])

            if not are_guests_equal(new_guest_2, existing_guest_1) and not are_guests_equal(new_guest_2, existing_guest_2):
                if new_guest_2 is not None:
                    guest_added(member_of_parliament, new_guest_2,
                                new_guests[1]["function"])


# main method
if __name__ == "__main__":
    run()
