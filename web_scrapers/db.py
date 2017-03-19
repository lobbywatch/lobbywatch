#!/usr/bin/python3
# -*- coding: utf-8 -*-

# Created by Markus Roth in March 2017 (maroth@gmail.com)
# Licenced via Affero GPL v3

import MySQLdb
import sys
import name_logic


# establish connection to the database
def connect():
    database = MySQLdb.connect(user="lobbywatch", passwd="lobbywatch", host="10.0.0.2", db="csvimsne_lobbywatch")
    return database


# get kanton by kanton_kuerzel
def get_kanton_id(database, kanton_kuerzel):
    with database.cursor() as cursor:
        cursor.execute( "SELECT id FROM kanton WHERE abkuerzung = '{}'".format(kanton_kuerzel))
        kanton_id = cursor.fetchone()[0]
    return kanton_id


# get partei by parlamentarier
def get_partei_id(database, partei_kuerzel):
    if not partei_kuerzel:
        return None
    with database.cursor() as cursor:
        cursor.execute("SELECT id from partei WHERE abkuerzung = '{0}' OR abkuerzung_fr = '{0}'".format(partei_kuerzel))
        partei_id = cursor.fetchone()

        if partei_id is None:
            print("\n\n DATA INTEGRITY FAILURE: Partei '{}' referenced in PDF for member of parliament {} is not in database. Aborting.".format(
                party_abbreviation, name_logic.fullname(member_of_parliament)))
            sys.exit(1)
    return partei_id[0]


# get a parlamentarier_id by names, kanton_id and partei_id
def get_parlamentarier(database, names, kanton_id, partei_id):
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
                name_query(description, names)
            cursor.execute(current_query)
            result = cursor.fetchall()
            if result and len(result) == 1:
                (parlamentarier_id, ) = result[0]
                return parlamentarier_id

    print("\n\n DATA INTEGRITY FAILURE: Member of parliament '{0}' referenced in PDF is not in database. Aborting.".format(names))
    sys.exit(1)


# get person_id by names
def get_person(database, names):
    with database.cursor() as cursor:
        parlamentarier_id = None
        query = "SELECT id FROM person WHERE 1=1"

        for description in ["NV", "NZV", "NNV", "NVV", "NNNV", "NS"]:
            current_query = query + name_query(description, names)
            cursor.execute(current_query)
            result = cursor.fetchall()
            if result and len(result) > 1:
                print("\n\n DATA INTEGRITY FAILURE: There are multiple possibilities in the database for guest '{0}'. Aborting.".format(result))
                sys.exit(1)
            if result and len(result) == 1:
                (person_id, ) = result[0]
                return person_id
    return None


# create query according to list and pattern (which name belongs to vorname, zweiter_vorname, and nachname)
# example: names = ["Markus", "Alexander", "Michael", "von", "Meier"]
#          pattern = "VZZNN"
# result:  vorname = "Markus"
#          zweiter_vorname = "Alexander Michael"
#          nachname = "von Meier"
def name_query(names, pattern):
    vorname, zweiter_vorname, nachname = parse_name_combination(pattern, names)

    query = " AND vorname LIKE '{}%' AND nachname LIKE '{}'".format(
        vorname, nachname)
    if zweiter_vorname:
        query += " AND zweiter_vorname LIKE '{}%'".format(zweiter_vorname)

    return query


# create vorname, zweiter_vorname and name from a untyped list of names according to a pattern
def parse_name_combination(names, pattern):
    vorname = ""
    zweiter_vorname = ""
    nachname = ""
    for name, value in zip(names, pattern):
        if value == "V":
            vorname += " " + name
        if value == "Z":
            zweiter_vorname += " " + name
        if value == "N":
            nachname += " " + name
        if value == "S":
            vorname += " %" + name

    return vorname.strip(), zweiter_vorname.strip(), nachname.strip()


# get guests for parlamentarier
# returns a 2-tuple of person_id or None
def get_guests(conn, parlamentarier_id):
    with conn.cursor() as cursor:
        guest_query = ("SELECT person_id, funktion, id from zutrittsberechtigung WHERE parlamentarier_id = '{0}' AND bis IS NULL".format(parlamentarier_id))
        cursor.execute(guest_query)
        existing_guests = cursor.fetchall()
        existing_guest_1 = extract_existing_guest(conn, *existing_guests[0]) if len(existing_guests) > 0 else None
        existing_guest_2 = extract_existing_guest(conn, *existing_guests[1]) if len(existing_guests) > 1 else None
        return (existing_guest_1, existing_guest_2)
        

# get additional information for loaded guest
def extract_existing_guest(conn, guest_id, function, zutrittsberechtigung_id):
    guest = {}
    guest["names"] = get_person_names(conn, guest_id)
    guest["function"] = function
    guest["id"] = guest_id
    guest["zutrittsberechtigung_id"] = zutrittsberechtigung_id
    return guest


# get all names for a person by person_id
def get_person_names(database, person_id):
    with database.cursor() as cursor:
        person_query = (
            "SELECT nachname, vorname, zweiter_vorname from person WHERE id = {0}".format(person_id))
        cursor.execute(person_query)
        lists_of_names = [name.replace('"', "").replace(".", "").split(
            " ") if name else "" for name in cursor.fetchone()]
        return [name for namelist in lists_of_names for name in namelist]
