# -*- coding: utf-8 -*-

# Created by Markus Roth in March 2017 (maroth@gmail.com)
# Licenced via Affero GPL v3

import json
from datetime import datetime

import db
import create_queries
import name_logic
import funktion_logic
import summary

# TODO return/keep state of dela, any changes in this download?

def run():
    batch_time = datetime.now().replace(microsecond=0)
    conn = db.connect()
    rows = []
    rows.append(sync_data(conn, "zutrittsberechtigte-nr.json", "Nationalrat", batch_time))
    rows.append(sync_data(conn, "zutrittsberechtigte-sr.json", "Ständerat", batch_time))
    conn.close()
    print_summary(rows, batch_time)


def print_summary(rows, batch_time):
    print("""/*\n\nActive Zutrittsberechtigungen on {}-{:02d}-{:02d} {:02d}:{:02d}:{:02d}
    """.format(batch_time.day, batch_time.month, batch_time.year, batch_time.hour, batch_time.minute, batch_time.second))

    print(summary.write_header())
    data_changed = False
    count_equal = 0
    count_no_zb = 0
    count_field_change = 0
    count_added = 0
    count_removed = 0
    count_replaced = 0
    for rat in rows:
        for row in rat:
            print(row.write())
            data_changed |= row.has_changed()
            if row.get_symbol1() == '=':
                count_equal += 1
            if row.get_symbol2() == '=':
                count_equal += 1
            if row.get_symbol1() == ' ':
                count_no_zb += 1
            if row.get_symbol2() == ' ':
                count_no_zb += 1
            if row.get_symbol1() == '≠':
                count_field_change += 1
            if row.get_symbol2() == '≠':
                count_field_change += 1
            if row.get_symbol1() == '+':
                count_added += 1
            if row.get_symbol2() == '+':
                count_added += 1
            if row.get_symbol1() == '-':
                count_removed += 1
            if row.get_symbol2() == '-':
                count_removed += 1
            if row.get_symbol1() == '±':
                count_replaced += 1
            if row.get_symbol2() == '±':
                count_replaced += 1

    print("\n = : {:>3d} unchanged\n   : {:>3d} no zutrittsberechtigte\n ≠ : {:>3d} Fields changed\n + : {:>3d} Zutrittsberechtigung added\n - : {:>3d} Zutrittsberechtigung removed\n ± : {:>3d} Zutrittsberechtigung replaced\n\n */".format(count_equal, count_no_zb, count_field_change, count_added, count_removed, count_replaced))
    
    if  data_changed:
        print("-- DATA CHANGED")
    else:
        print("-- DATA UNCHANGED")


def sync_data(conn, filename, council, batch_time):
    backup_filename = "{}-{:02d}-{:02d}-{}".format(batch_time.year, batch_time.month, batch_time.day, filename)
    print("\n\n-- ----------------------------- ")
    print("-- {} ".format(council))
    print("-- File: {}".format(backup_filename))

    summary_rows = []
    with open(filename) as data_file:
        content = json.load(data_file)
        print("-- PDF creation date: {}".format(content["metadata"]["pdf_creation_date"]))
        print("-- PDF archive file: {}".format(content["metadata"]["archive_pdf_name"]))
        print("-- ----------------------------- ")
        count = 1
        for parlamentarier in content["data"]:

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

            #summary row
            summary_row = summary.SummaryRow(parlamentarier, count)
            count += 1


            #check if existing guest 1 left or stayed
            if name_logic.are_guests_equal(existing_guest_1, new_guest_1):
                summary_row.set_guest_1(existing_guest_1)
                funktion_equal = guest_remained(parlamentarier, existing_guest_1, new_guest_1, batch_time)
                if not funktion_equal:
                    summary_row.set_guest_1_changes("funktion")

            elif name_logic.are_guests_equal(existing_guest_1, new_guest_2):
                summary_row.set_guest_1(existing_guest_1)
                funktion_equal = guest_remained(parlamentarier, existing_guest_1, new_guest_2, batch_time)
                if not funktion_equal:
                    summary_row.set_guest_1_changes("funktion")
            else:
                guest_removed(parlamentarier, existing_guest_1, batch_time)
                summary_row.set_removed_guest_1(existing_guest_1)

            #check if existing guest 2 left or stayed
            if name_logic.are_guests_equal(existing_guest_2, new_guest_1):
                summary_row.set_guest_2(existing_guest_2)
                funktion_equal = guest_remained(parlamentarier, existing_guest_2, new_guest_1, batch_time)
                if not funktion_equal:
                    summary_row.set_guest_2_changes("funktion")

            elif name_logic.are_guests_equal(existing_guest_2, new_guest_2):
                summary_row.set_guest_2(existing_guest_2)
                funktion_equal = guest_remained(parlamentarier, existing_guest_2, new_guest_2, batch_time)
                if not funktion_equal:
                    summary_row.set_guest_2_changes("funktion")

            else:
                guest_removed(parlamentarier, existing_guest_2, batch_time)
                summary_row.set_removed_guest_2(existing_guest_2)

            # check if new guest 1 was already here
            if not name_logic.are_guests_equal(new_guest_1, existing_guest_1) and not name_logic.are_guests_equal(new_guest_1, existing_guest_2):
                guest_added(conn, parlamentarier, new_guest_1, batch_time)
                summary_row.set_new_guest_1(new_guest_1)

            # check if new guest 2 was already here
            if not name_logic.are_guests_equal(new_guest_2, existing_guest_1) and not name_logic.are_guests_equal(new_guest_2, existing_guest_2):
                guest_added(conn, parlamentarier, new_guest_2, batch_time)

                if name_logic.are_guests_equal(new_guest_1, existing_guest_2):
                    summary_row.set_new_guest_1(new_guest_2)
                else:
                    summary_row.set_new_guest_2(new_guest_2)

            summary_rows.append(summary_row)

    #return("\n".join(summary_rows))
    return(summary_rows)


# a guest has been removed from a parlamentarier
def guest_removed(member_of_parliament, guest_to_remove, date):
    if guest_to_remove is not None:
        print("\n-- Parlamentarier_in '{}' hat die Zutrittsberechtigung von Gast '{}' mit Funktion '{}' beendet.".format(
            name_logic.fullname(member_of_parliament),
            name_logic.fullname(guest_to_remove),
            guest_to_remove["function"]))
        print(create_queries.end_zutrittsberechtigung(guest_to_remove["zutrittsberechtigung_id"], date))


# a new guest has been added to a parlamentarier
def guest_added(conn, member_of_parliament, guest_to_add, date):
    if guest_to_add is not None:
        print("\n-- Parlamentarier_in '{}' hat einen neuen Gast '{}' mit Funktion '{}'.".format(
            name_logic.fullname(member_of_parliament),
            name_logic.fullname(guest_to_add),
            guest_to_add["function"]))

        # check if the new guest is already a person in the database, if not create them
        person_id = db.get_person(conn, guest_to_add["names"])
        if not person_id:
            print("-- Diese_r muss neu in der Datenbank erzeugt werden")
            print(create_queries.insert_person(guest_to_add, date))
        else:
            guest_to_add["id"] = person_id

        print(create_queries.insert_zutrittsberechtigung(member_of_parliament["id"], person_id, guest_to_add["function"], date))


# if a guest remains, we may update their function
def guest_remained(member_of_parliament, existing_guest, new_guest, date):
    funktion_equal = funktion_logic.are_functions_equal(existing_guest["function"], new_guest["function"])
    if not funktion_equal:
        print("\n-- Parlamentarier_in '{}' hat beim Gast '{}' die Funktion von '{}' auf '{}' geändert.".format(
            name_logic.fullname(member_of_parliament),
            name_logic.fullname(existing_guest),
            existing_guest["function"],
            new_guest["function"]))
        print(create_queries.update_function_of_zutrittsberechtigung(existing_guest["zutrittsberechtigung_id"], new_guest["function"], date))
    return funktion_equal


# main method
if __name__ == "__main__":
    run()
