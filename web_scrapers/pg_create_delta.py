# -*- coding: utf-8 -*-

# Created by Markus Roth in March 2017 (maroth@gmail.com)
# Licenced via Affero GPL v3

import json
import re
from datetime import datetime
from operator import itemgetter, attrgetter, methodcaller

import db
import create_queries
import name_logic
import funktion_logic
import zb_summary as summary

def run():
    batch_time = datetime.now().replace(microsecond=0)
    conn = db.connect()
    rows = sync_data(conn, "parlamentarische-gruppen.json", batch_time)
    conn.close()
    #print_summary(rows, batch_time)


def print_summary(rows, batch_time):
    print("""/*\n\nActive Zutrittsberechtigungen on {}-{:02d}-{:02d} {:02d}:{:02d}:{:02d}
    """.format(batch_time.day, batch_time.month, batch_time.year, batch_time.hour, batch_time.minute, batch_time.second))

    sorted_rows = []
    sorted_rows.append(sorted(rows[0], key=attrgetter('parlamentarier_name')))
    sorted_rows.append(sorted(rows[1], key=attrgetter('parlamentarier_name')))

    print(summary.write_header())
    data_changed = False
    count_equal = 0
    count_no_zb = 0
    count_field_change = 0
    count_added = 0
    count_removed = 0
    count_replaced = 0
    i = 0
    for rat in sorted_rows:
        j = 0
        i += 1
        for row in rat:
            j += 1
            print(row.write(j))
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


# Often some small details in organisation naming can be ignored
def normalize_organisation(name):
    return re.sub(r'\([^)]*\)', '', name).strip()


def get_names(member):
    names = member.split(' ')[2:]
    names = [re.sub(r'\([^)]*\)', '', name).strip() for name in names]
    return names


def sync_data(conn, filename, batch_time):
    backup_filename = "{}-{:02d}-{:02d}-{}".format(batch_time.year, batch_time.month, batch_time.day, filename)
    print("\n\n-- ----------------------------- ")
    print("-- File: {}".format(backup_filename))

    summary_rows = []
    with open(filename) as data_file:
        content = json.load(data_file)
        print("-- PDF creation date: {}".format(content["metadata"]["pdf_creation_date"]))
        print("-- PDF archive file: {}".format(content["metadata"]["archive_pdf_name"]))
        print("-- ----------------------------- ")
        count = 1
        for group in content["data"]:

            #load group name
            name = normalize_organisation(group["name"])
            members = group["members"]

            organisation_id = db.get_organisation_id(conn, name)
            #print("\n\n", name)
            if organisation_id:
                for member in members:
                    names = get_names(member)
                    parlamentarier_id = db.get_parlamentarier_id_by_name(conn, names)
                    #print(parlamentarier_id)
            else:
                print("\n-- Neue parlamentarische Gruppe: {}".format(name))
                print(create_queries.insert_parlamentarische_gruppe(name, batch_time))

            #if organisation_id:
            #    print(name, organisation_id) 

            #summary row
            #summary_row = summary.SummaryRow(parlamentarier, count, parlamentarier_db_dict)
            count += 1

            for member in members:
                #identify parlamentarier
                #if parlamentarier is already in group
                #if parlamentarier is new in group
                #for every parlamentarier that is no longer in the group
                pass

            #summary_rows.append(summary_row)

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
        person_id = db.get_person_id(conn, guest_to_add["names"])
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
