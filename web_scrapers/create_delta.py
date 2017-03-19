# -*- coding: utf-8 -*-

# Created by Markus Roth in March 2017 (maroth@gmail.com)
# Licenced via Affero GPL v3

import json
import db
import create_queries
import name_logic
import funktion_logic


def run():
    conn = db.connect()
    sync_data(conn, "zutrittsberechtigte-nr.json", "Nationalrat")
    sync_data(conn, "zutrittsberechtigte-sr.json", "Ständerat")
    conn.close()


def sync_data(conn, filename, council):
    print("\n\n------------------------------- ")
    print("-- {} --".format(council))
    print("------------------------------- ")
    with open(filename) as data_file:
        data = json.load(data_file)
        for parlamentarier in data:

            #load info about parlamentarier
            kanton_id = db.get_kanton_id(conn, parlamentarier["canton"])
            partei_id = db.get_partei_id(conn, parlamentarier["party"])
            parlamentarier_id = db.get_parlamentarier(conn, parlamentarier["names"], kanton_id, partei_id)
            parlamentarier["id"] = parlamentarier_id

            #existing guests (from database)
            existing_guest_1, existing_guest_2  = db.get_guests(conn, parlamentarier_id)

            #new guests (from JSON file)
            new_guests = parlamentarier["guests"]
            new_guest_1 = new_guests[0] if len(new_guests) > 0 else None
            new_guest_2 = new_guests[1] if len(new_guests) > 1 else None

            #check if existing guest 1 left or stayed
            if name_logic.are_guests_equal(existing_guest_1, new_guest_1):
                guest_remained(parlamentarier, existing_guest_1, new_guest_1)
            elif name_logic.are_guests_equal(existing_guest_1, new_guest_2):
                guest_remained(parlamentarier, existing_guest_1, new_guest_2)
            else:
                guest_removed(parlamentarier, existing_guest_1)

            #check if existing guest 2 left or stayed
            if name_logic.are_guests_equal(existing_guest_2, new_guest_1):
                guest_remained(parlamentarier, existing_guest_2, new_guest_1)
            elif name_logic.are_guests_equal(existing_guest_2, new_guest_2):
                guest_remained(parlamentarier, existing_guest_2, new_guest_2)
            else:
                guest_removed(parlamentarier, existing_guest_2)

            # check if new guest 1 was already here
            if not name_logic.are_guests_equal(new_guest_1, existing_guest_1) and not name_logic.are_guests_equal(new_guest_1, existing_guest_2):
                guest_added(conn, parlamentarier, new_guest_1)

            # check if new guest 2 was already here
            if not name_logic.are_guests_equal(new_guest_2, existing_guest_1) and not name_logic.are_guests_equal(new_guest_2, existing_guest_2):
                guest_added(conn, parlamentarier, new_guest_2)


# a guest has been removed from a parlamentarier
def guest_removed(member_of_parliament, guest_to_remove):
    if guest_to_remove is not None:
        print("\n-- Parlamentarier_in '{}' hat die Zutrittsberechtigung von Gast '{}' mit Funktion '{}' beendet.".format(
            name_logic.fullname(member_of_parliament),
            name_logic.fullname(guest_to_remove),
            guest_to_remove["function"]))
        print(create_queries.end_zutrittsberechtigung(guest_to_remove["zutrittsberechtigung_id"]))


# a new guest has been added to a parlamentarier
def guest_added(conn, member_of_parliament, guest_to_add):
    if guest_to_add is not None:
        print("\n-- Parlamentarier_in '{}' hat einen neuen Gast '{}' mit Funktion '{}'.".format(
            name_logic.fullname(member_of_parliament),
            name_logic.fullname(guest_to_add),
            guest_to_add["function"]))

        # check if the new guest is already a person in the database, if not create them
        person_id = db.get_person(conn, guest_to_add["names"])
        if not person_id:
            print("-- Diese_r muss neu in der Datenbank erzeugt werden")
            print(create_queries.insert_person(guest_to_add))

        print(create_queries.insert_zutrittsberechtigung(member_of_parliament["id"], person_id, guest_to_add["function"]))


# if a guest remains, we may update their function
def guest_remained(member_of_parliament, existing_guest, new_guest):
    if not funktion_logic.are_functions_equal(existing_guest["function"], new_guest["function"]):
        print("\n-- Parlamentarier_in '{}' hat beim Gast '{}' die Funktion von '{}' auf '{}' geändert.".format(
            name_logic.fullname(member_of_parliament),
            name_logic.fullname(existing_guest),
            existing_guest["function"],
            new_guest["function"]))
        print(create_queries.update_function_of_zutrittsberechtigung(existing_guest["zutrittsberechtigung_id"], new_guest["function"]))


# main method
if __name__ == "__main__":
    run()
