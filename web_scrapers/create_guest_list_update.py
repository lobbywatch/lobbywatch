#!/usr/bin/python3
# -*- coding: utf-8 -*-


# A script that imports JSON files describing the guest lists of members
# of parliament, checks if those guests are already in the database,
# and creates an updating SQL script accordingly.

# Created by Markus Roth in February 2017 (maroth@gmail.com)
# Licenced via Affero GPL v3

import json
import MySQLdb
import random

database = MySQLdb.connect(user="lobbywatch", passwd="lobbywatch", host="10.0.0.2", db="lobbywatchtest")

def run():
    sync_data(database)
    database.close()

def get_canton_id(canton_abbreviation):
    with database.cursor() as cursor:
        cursor.execute("SELECT id FROM kanton WHERE abkuerzung = '{}'".format(canton_abbreviation))
        canton_id = cursor.fetchone()

        #TODO: just for testing
        if canton_id == 0:
            cursor.execute("INSERT INTO kanton (abkuerzung, kantonsnr, name_de, name_fr, name_it, anzahl_staenderaete, amtssprache, hauptort_de, flaeche_km2, beitrittsjahr, wappen_klein, wappen, lagebild, created_visa) VALUES ('{0}', {1}, 'test', 'test', 'test', 1, 'de', 'test', 1, 1, 'a', 'a', 'a', 'a')".format(canton_abbreviation, random.randint(1, 127)))
            database.commit()
            print("created canton: " + canton_abbreviation)
            cursor.execute("SELECT id FROM kanton WHERE abkuerzung = '{}'".format(canton_abbreviation))

    return canton_id


def get_parlamentarier(member_of_parliament):
    with database.cursor() as cursor:
        cursor.execute("SELECT id from parlamentarier WHERE vorname = '{0}' AND nachname = '{1}' AND zweiter_vorname = '{2}' AND kanton_id = {3}".format(
           member_of_parliament["first_name"],
           member_of_parliament["last_name"],
           member_of_parliament["second_first_name"],
           canton_id))
        parlamentarier_id = cursor.fetchone()

        #TODO: just for testing
        if parlamentarier_id == 0:
            cursor.execute("INSERT INTO kanton (abkuerzung, kantonsnr, name_de, name_fr, name_it, anzahl_staenderaete, amtssprache, hauptort_de, flaeche_km2, beitrittsjahr, wappen_klein, wappen, lagebild, created_visa) VALUES ('{0}', {1}, 'test', 'test', 'test', 1, 'de', 'test', 1, 1, 'a', 'a', 'a', 'a')".format(canton_abbreviation, random.randint(1, 127)))
            cursor.execute("INSERT INTO parlamentarier (vorname, nachname, zweiter_vorname, kanton_id, rat_id, im_rat_seit, created_visa) VALUES ('{0}', '{1}', '{2}', {3}, 1, '2012-01-01', 'a')".format(
               member_of_parliament["first_name"],
               member_of_parliament["last_name"],
               member_of_parliament["second_first_name"],
               canton_id))
            database.commit()
    return 


def sync_data(database):
    with open("zutrittsberechtigte-nr.json") as nr_file:
        nr_data = json.load(nr_file)
        for member_of_parliament in nr_data:
            canton_abbreviation = member_of_parliament["canton"]
            canton_id = get_canton_id(canton_abbreviation)[0]

            with database.cursor() as cursor:
                



#main method
if __name__ == "__main__":
    run()



