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

database = MySQLdb.connect(user="lobbywatch", passwd="lobbywatch", host="10.0.0.2", db="csvimsne_lobbywatch")

def run():
    sync_data(database)
    database.close()

def get_canton_id(canton_abbreviation):
    with database.cursor() as cursor:
        cursor.execute("SELECT id FROM kanton WHERE abkuerzung = '{}'".format(canton_abbreviation))
        canton_id = cursor.fetchone()[0]
    return canton_id


def get_party_id(party_abbreviation):
    with database.cursor() as cursor:
        cursor.execute("SELECT id from partei WHERE abkuerzung = '{0}' OR abkuerzung_fr = '{0}'".format(
           party_abbreviation))
        party_id = cursor.fetchone()

        if party_id is None:
            print("DATA INTEGRITY FAILURE: party '{0}' referenced in PDF is not in database. Aborting.".format(party_abbreviation))
            sys.exit(1)
    return party_id[0]


def get_member_of_parliament(member_of_parliament, canton_id, party_id):
    with database.cursor() as cursor:
        cursor.execute("SELECT id from parlamentarier WHERE vorname = '{0}' AND nachname = '{1}' AND zweiter_vorname = '{2}' AND kanton_id = {3} AND partei_id = {4}".format(
           member_of_parliament["first_name"],
           member_of_parliament["last_name"],
           member_of_parliament["second_first_name"],
           canton_id,
           party_id))
        parlamentarier_id = cursor.fetchone()

        if parlamentarier_id is None:
            print("DATA INTEGRITY FAILURE: Member of parliament '{0}' referenced in PDF is not in database. Aborting.".format(member_of_parliament["first_name"] + " " + member_of_parliament["last_name"]))
            sys.exit(1)
    return parlamentarier_id[0]


def sync_data(database):
    with open("zutrittsberechtigte-nr.json") as nr_file:
        nr_data = json.load(nr_file)
        for member_of_parliament in nr_data:
            canton_abbreviation = member_of_parliament["canton"]
            canton_id = get_canton_id(canton_abbreviation)
            party_abbreviation = member_of_parliament["party"]
            party_id = get_party_id(party_abbreviation)
            member_id = get_member_of_parliament(member_of_parliament, canton_id, party_id)
            print(member_id)

                



#main method
if __name__ == "__main__":
    run()
