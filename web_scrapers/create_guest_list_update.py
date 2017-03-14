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

        query = ("SELECT id from parlamentarier WHERE vorname = '{0}' AND nachname = '{1}' AND kanton_id = {2} AND partei_id = {3}".format(
           member_of_parliament["first_name"],
           member_of_parliament["last_name"],
           canton_id,
           party_id))
        if member_of_parliament["second_first_name"] != "":
            query += " AND zweiter_vorname = '{}'".format(member_of_parliament["second_first_name"])
        cursor.execute(query)
        parlamentarier_id = cursor.fetchone()

        if parlamentarier_id is None:
            # hack to account for people with two seperate last names (e.g. Laurance Fehlmann Rielle, where the last name is "Fehlmann Rielle",
            # but our script interprets "Rielle" as the first name, "Fehlmann" as the last name, and "Laurance" as the second first name
            double_last_name_query = ("SELECT id from parlamentarier WHERE vorname = '{0}' AND nachname = '{1}' AND kanton_id = {2} AND partei_id = {3}".format(
               member_of_parliament["second_first_name"],
               member_of_parliament["last_name"] + " " + member_of_parliament["first_name"],
               canton_id,
               party_id))
            cursor.execute(double_last_name_query)
            parlamentarier_id = cursor.fetchone()

        if parlamentarier_id is None:
            # hack to account for people with two seperate first names (e.g. Min Li Marti, where the first name is "Min Li",
            # but our script interprets "Min" as the first name, "Marti" as the last name, and "Li" as the second first name
            double_first_name_query = ("SELECT id from parlamentarier WHERE vorname = '{0}' AND nachname = '{1}' AND kanton_id = {2} AND partei_id = {3}".format(
               member_of_parliament["first_name"] + " " + member_of_parliament["second_first_name"],
               member_of_parliament["last_name"],
               canton_id,
               party_id))
            cursor.execute(double_first_name_query)
            parlamentarier_id = cursor.fetchone()

        if parlamentarier_id is None:
            print("DATA INTEGRITY FAILURE: Member of parliament '{0}' referenced in PDF is not in database. Aborting.".format(member_of_parliament["first_name"] + " " + member_of_parliament["last_name"]))
            print("Query was: {}".format(query))
            print("Double Last Name Query was: {}".format(double_last_name_query))
            print("Double First Name Query was: {}".format(double_first_name_query))
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
