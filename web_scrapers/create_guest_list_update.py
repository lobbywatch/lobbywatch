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
import _mysql
from datetime import datetime

database = MySQLdb.connect(user="lobbywatch", passwd="lobbywatch", host="10.0.0.2", db="csvimsne_lobbywatch")

def run():
    sync_data(database, "zutrittsberechtigte-nr.json", "NR")
    sync_data(database, "zutrittsberechtigte-sr.json", "SR")
    database.close()

def get_canton_id(canton_abbreviation):
    with database.cursor() as cursor:
        cursor.execute("SELECT id FROM kanton WHERE abkuerzung = '{}'".format(canton_abbreviation))
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
            print("\n\n DATA INTEGRITY FAILURE: party '{}' referenced in PDF for member of parliament {} is not in database. Aborting.".format(party_abbreviation, guest_full_name(member_of_parliament)))
            sys.exit(1)
    return party_id[0]

def get_person(guest):
    with database.cursor() as cursor:

        query = ("SELECT id from person WHERE vorname = '{0}' AND nachname = '{1}'".format(
           guest["first_name"],
           guest["last_name"]))
        if guest["second_first_name"]:
            query += " AND zweiter_vorname = '{}'".format(guest["second_first_name"])
        cursor.execute(query)
        result = cursor.fetchone()
        if (result):
            (person_id,) = result
        else:
            query = ("SELECT id, zweiter_vorname from person WHERE vorname = '{0}' AND nachname = '{1}'".format(
               guest["first_name"],
               guest["last_name"]))
            cursor.execute(query)
            result = cursor.fetchone()
            if result: 
                person_id, zweiter_vorname = result
                if zweiter_vorname:
                    complete_name_from_database = guest["first_name"] + " " + zweiter_vorname + " " + guest["last_name"]
                else:
                    complete_name_from_database = guest["first_name"] + guest["last_name"]
                print("Assuming that guest from PDF '{}' is the same person as in database '{}'".format(guest_full_name(guest), complete_name_from_database))

            else:
                return None
        
        return person_id


def get_member_of_parliament(member_of_parliament, canton_id, party_id):
    with database.cursor() as cursor:

        parlamentarier_id = None

        query = ("SELECT id from parlamentarier WHERE vorname = '{0}' AND nachname = '{1}' AND kanton_id = {2}".format(
           member_of_parliament["first_name"],
           member_of_parliament["last_name"],
           canton_id))
        if member_of_parliament["second_first_name"]:
            query += " AND zweiter_vorname = '{}'".format(member_of_parliament["second_first_name"])
        if party_id:
            query += " AND partei_id = '{}'".format(party_id)
        else:
            query += " AND partei_id IS NULL"

        cursor.execute(query)
        result = cursor.fetchone()
        if result:
            (parlamentarier_id, ) = result

        if parlamentarier_id is None:
            # hack to account for people with two seperate last names (e.g. Laurance Fehlmann Rielle, where the last name is "Fehlmann Rielle",
            # but our script interprets "Rielle" as the first name, "Fehlmann" as the last name, and "Laurance" as the second first name
            double_last_name_query = ("SELECT id from parlamentarier WHERE vorname = '{0}' AND nachname = '{1}' AND kanton_id = {2}".format(
               member_of_parliament["second_first_name"],
               member_of_parliament["last_name"] + " " + member_of_parliament["first_name"],
               canton_id))

            if party_id:
                double_last_name_query += " AND partei_id = '{}'".format(party_id)
            else:
                double_last_name_query += " AND partei_id IS NULL"

            cursor.execute(double_last_name_query)
            result = cursor.fetchone()
            if result:
                (parlamentarier_id, ) = result

        if parlamentarier_id is None:
            # hack to account for people with two seperate first names (e.g. Min Li Marti, where the first name is "Min Li",
            # but our script interprets "Min" as the first name, "Marti" as the last name, and "Li" as the second first name
            double_first_name_query = ("SELECT id from parlamentarier WHERE vorname = '{0}' AND nachname = '{1}' AND kanton_id = {2}".format(
               member_of_parliament["first_name"] + " " + member_of_parliament["second_first_name"],
               member_of_parliament["last_name"],
               canton_id))
            if party_id:
                double_first_name_query += " AND partei_id = '{}'".format(party_id)
            else:
                double_first_name_query += " AND partei_id IS NULL"
            cursor.execute(double_first_name_query)
            result = cursor.fetchone()
            if result:
                (parlamentarier_id, ) = result

        if parlamentarier_id is None:
            # hack to account for people with two seperate first names (e.g. Min Li Marti, where the first name is "Min Li",
            # but our script interprets "Min" as the first name, "Marti" as the last name, and "Li" as the second first name
            last_ditch_effort_query = ("SELECT id, vorname, zweiter_vorname, nachname from parlamentarier WHERE vorname like '{0}' AND nachname like '{1}' AND kanton_id = {2}".format(
               "%{}%".format(member_of_parliament["first_name"]),
               "%{}%".format(member_of_parliament["last_name"]),
               canton_id))
            if party_id:
                last_ditch_effort_query += " AND partei_id = '{}'".format(party_id)
            else:
                last_ditch_effort_query += " AND partei_id IS NULL"
            cursor.execute(last_ditch_effort_query)
            (parlamentarier_id, vorname, zweiter_vorname, nachname) = cursor.fetchone()

            complete_name_from_database = "{} {} {}".format(vorname, zweiter_vorname, nachname).replace("  ", " ")
            print("-- Annahme: Parlamentarier_in aus dem PDF '{}' ist die gleiche Person wie Person '{}' aus der Datenbank".format(guest_full_name(member_of_parliament), complete_name_from_database))

        if parlamentarier_id is None:
            print("\n\n DATA INTEGRITY FAILURE: Member of parliament '{0}' referenced in PDF is not in database. Aborting.".format(member_of_parliament["first_name"] + " " + member_of_parliament["last_name"]))
            print("Original Query was: {}".format(query))
            print("Double Last Name Query was: {}".format(double_last_name_query))
            print("Double First Name Query was: {}".format(double_first_name_query))
            print("Last Ditch Effort Query was: {}".format(last_ditch_effort_query))
            sys.exit(1)
    return parlamentarier_id


def get_guests(member_id):
    with database.cursor() as cursor:
        guest_query = ("SELECT person_id, funktion, id from zutrittsberechtigung WHERE parlamentarier_id = '{0}' AND bis IS NULL".format(
           member_id))
        cursor.execute(guest_query)
        guests = cursor.fetchall()
        return guests


def get_guest_name(person_id):
    with database.cursor() as cursor:
        person_query = ("SELECT vorname, zweiter_vorname, nachname from person WHERE id = '{0}'".format(
           person_id))
        cursor.execute(person_query)
        guest_vorname, guest_zweiter_vorname, guest_nachname = cursor.fetchone()
        result = {}
        result["first_name"] = guest_vorname
        result["second_first_name"] = guest_zweiter_vorname
        result["last_name"] = guest_nachname
        return result

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
        escape_string(guest["last_name"]),
        escape_string(guest["first_name"]), 
        escape_string(guest["second_first_name"]),
        escape_string(function), 
        "import")
    return query
    

def sort_guests(guests):
    return sorted(guests, key=lambda elem: "{}{}{}".format(elem["first_name"], elem["second_first_name"], elem["last_name"]))


def guests_equal(guest1, guest2):
    return guest1["first_name"] == guest2["first_name"] and guest1["second_first_name"] == guest2["second_first_name"] and guest1["last_name"] == guest2["last_name"]


def compare_guests(guest1, guest2):
    return guest_full_name(guest1) > guest_full_name(guest2)


def guest_full_name(guest):
    if guest["second_first_name"]:
        return guest["first_name"] + " "  + guest["second_first_name"] + " " + guest["last_name"] 
    else:
        return guest["first_name"] + " " + guest["last_name"] 


def are_guests_equal(guest1, guest2):
    if guest1 is None and guest2 is None:
        return False
    if guest1 is None and guest2 is not None:
        return False
    if guest1 is not None and guest2 is None:
        return False
    # exact match
    if guest_full_name(guest1) == guest_full_name(guest2):
        return True

    # probablye match
    if guest1["first_name"] == guest2["first_name"] and guest2["last_name"].startswith(guest1["last_name"]):
        print("-- Annahme: '{}' ist dieselbe Person wie '{}'.".format(guest_full_name(guest1), guest_full_name(guest2)))
        return True

    if guest1["first_name"] == guest2["first_name"] and guest1["last_name"].startswith(guest2["last_name"]):
        print("-- Annahme: '{}' ist dieselbe Person wie '{}'.".format(guest_full_name(guest1), guest_full_name(guest2)))
        return True
    else:
        return False


def guest_changed(member_id, existing_guest, new_guest):
    pass
    #print("update guest")
    #print(member_id, guest_full_name(existing_guest), new_guest)


def guest_removed(member_of_parliament, guest_to_remove):
    if guest_to_remove is not None:
        print("\n-- Parlamentarier_in '{}' hat die Zutrittsberechtigung von Gast '{}' mit Funktion '{}' beendet.".format(
            guest_full_name(member_of_parliament),
            guest_full_name(guest_to_remove),
            guest_to_remove["function"]))
        query = end_zutrittsberechtigung(guest_to_remove["zutrittsberechtigung_id"])
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

    query = insert_zutrittsberechtigung(member_of_parliament["id"], person_id, function)
    print(query)

def guest_remained(member_of_parliament, existing_guest, new_guest):
    if not existing_guest["function"] == new_guest["function"]:
        print("\n-- Parlamentarier_in '{}' hat beim Gast '{}' die Funktion von '{}' auf '{}' geändert.".format(
            guest_full_name(member_of_parliament),
            guest_full_name(existing_guest),
            existing_guest["function"],
            new_guest["function"]))
        query = update_function_of_zutrittsberechtigung(existing_guest["zutrittsberechtigung_id"], new_guest["function"])
        print(query)
        


def extract_existing_guest(guest_id, function, zutrittsberechtigung_id):
    guest = get_guest_name(guest_id)
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
            member_id = get_member_of_parliament(member_of_parliament, canton_id, party_id)
            member_of_parliament["id"] = member_id
            print("\n\n------------------------------- ")
            print("-- {}: {} {}".format(council, member_of_parliament["first_name"], member_of_parliament["last_name"]))
            print("------------------------------- ")

            existing_guests = get_guests(member_id)
            existing_guest_1 = extract_existing_guest(*existing_guests[0]) if len(existing_guests) > 0 else None
            existing_guest_2 = extract_existing_guest(*existing_guests[1]) if len(existing_guests) > 1 else None

            new_guests = member_of_parliament["guests"]
            new_guest_1 = new_guests[0] if len(new_guests) > 0 else None
            new_guest_2 = new_guests[1] if len(new_guests) > 1 else None

            if are_guests_equal(existing_guest_1, new_guest_1):
                guest_remained(member_of_parliament, existing_guest_1, new_guest_1)
            elif are_guests_equal(existing_guest_1, new_guest_2):
                guest_remained(member_of_parliament, existing_guest_1, new_guest_2)
            else:
                guest_removed(member_of_parliament, existing_guest_1)

            if are_guests_equal(existing_guest_2, new_guest_1):
                guest_remained(member_of_parliament, existing_guest_2, new_guest_1)
            elif are_guests_equal(existing_guest_2, new_guest_2):
                guest_remained(member_of_parliament, existing_guest_2, new_guest_2)
            else:
                guest_removed(member_of_parliament, existing_guest_2)

            if not are_guests_equal(new_guest_1, existing_guest_1) and not are_guests_equal(new_guest_1, existing_guest_2):
                if new_guest_1 is not None:
                    guest_added(member_of_parliament, new_guest_1, new_guests[0]["function"])

            if not are_guests_equal(new_guest_2, existing_guest_1) and not are_guests_equal(new_guest_2, existing_guest_2):
                if new_guest_2 is not None:
                    guest_added(member_of_parliament, new_guest_2, new_guests[1]["function"])

#main method
if __name__ == "__main__":
    run()
