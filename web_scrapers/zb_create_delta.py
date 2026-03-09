import copy
import json
from datetime import date, datetime
from operator import attrgetter
from argparse import ArgumentParser
from typing import Dict
import db
import name_logic
import sql_statement_generator
import funktion_logic
import zb_summary as summary

GUEST_LIMIT = 4

def run():
    parser = ArgumentParser(description='Create SQL files for data differences')
    parser.add_argument("--db", dest="db_name", help="name of DB to use", metavar="DB", default=None)
    args = parser.parse_args()

    batch_time = datetime.now().replace(microsecond=0)
    conn = db.connect(args.db_name)
    rows = []
    print(sql_statement_generator.start_transaction())
    rows.append(sync_data(conn, "zutrittsberechtigte-nr.json", "Nationalrat", batch_time))
    rows.append(sync_data(conn, "zutrittsberechtigte-sr.json", "Ständerat", batch_time))
    print(sql_statement_generator.commit_transaction())
    conn.close()
    print_summary(rows, batch_time)


def print_summary(rows, batch_time):
    print("""/*\n\nActive Zutrittsberechtigungen on {}-{:02d}-{:02d} {:02d}:{:02d}:{:02d}
    """.format(batch_time.day, batch_time.month, batch_time.year, batch_time.hour, batch_time.minute, batch_time.second))

    sorted_rows = []
    sorted_rows.append(sorted(rows[0], key=attrgetter('parlamentarier_name')))
    sorted_rows.append(sorted(rows[1], key=attrgetter('parlamentarier_name')))

    print(summary.write_header(GUEST_LIMIT))
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


def sync_data(conn, filename, council, batch_time):
    backup_filename = "{}-{:02d}-{:02d}-{}".format(batch_time.year, batch_time.month, batch_time.day, filename)
    print("\n\n-- ----------------------------- ")
    print("-- {} ".format(council))
    print("-- File: {}".format(backup_filename))

    summary_rows = []
    with open(filename) as data_file:
        content = json.load(data_file)
        pdf_date_str = content["metadata"]["pdf_creation_date"]
        stand_date_str = content["metadata"]["stand_date"]
        pdf_creation_date = datetime.strptime(pdf_date_str, "%Y-%m-%d %H:%M:%S") # 2019-07-12 14:55:08
        stand_date = datetime.strptime(stand_date_str, "%Y-%m-%d").date() # 2019-07-12
        pdf_date = stand_date
        archive_pdf_name = content["metadata"]["archive_pdf_name"]
        print("-- PDF stand: {}".format(stand_date))
        print("-- PDF creation date: {}".format(pdf_creation_date))
        print("-- PDF archive file: {}".format(archive_pdf_name))
        print("-- URL: {}".format(content["metadata"]["url"]))
        print("-- ----------------------------- ")

        count = 1
        for parlamentarier in content["data"]:
            summary_row = sync_parliamentarian(parlamentarier, conn, batch_time, pdf_date, count)
            count += 1
            summary_rows.append(summary_row)

    return (summary_rows)


# a guest has been removed from a parlamentarier
def guest_removed(member_of_parliament, guest_to_remove, date, pdf_date):
    if guest_to_remove is not None:
        print("\n-- Parlamentarier_in '{}' hat die Zutrittsberechtigung von Gast '{}' mit Funktion '{}' beendet.".format(
            name_logic.fullname(member_of_parliament),
            name_logic.fullname(guest_to_remove),
            guest_to_remove["function"]))
        print(sql_statement_generator.end_zutrittsberechtigung(guest_to_remove["zutrittsberechtigung_id"], date, pdf_date))


# a new guest has been added to a parlamentarier
def guest_added(conn, member_of_parliament, guest_to_add, date, pdf_date):
    if guest_to_add is not None:
        print("\n-- Parlamentarier_in '{}' hat einen neuen Gast '{}' mit Funktion '{}'.".format(
            name_logic.fullname(member_of_parliament),
            name_logic.fullname(guest_to_add),
            guest_to_add["function"]))

        guest_to_add["names"] = name_logic.fixNobleNames(guest_to_add["names"])
        # check if the new guest is already a person in the database, if not create them
        person_id = db.get_person_id(conn, guest_to_add["names"])
        if not person_id:
            print("-- Diese_r muss neu in der Datenbank erzeugt werden")
            print(sql_statement_generator.insert_person(guest_to_add, date, pdf_date))
        else:
            guest_to_add["id"] = person_id

        print(sql_statement_generator.insert_zutrittsberechtigung(member_of_parliament["id"], person_id, guest_to_add["function"], date, pdf_date))


# if a guest remains, we may update their function
def guest_remained(member_of_parliament, existing_guest, new_guest, date, pdf_date):
    funktion_equal = funktion_logic.are_functions_equal(existing_guest["function"], new_guest["function"])
    if not funktion_equal:
        print("\n-- Parlamentarier_in '{}' hat beim Gast '{}' die Funktion von '{}' auf '{}' geändert.".format(
            name_logic.fullname(member_of_parliament),
            name_logic.fullname(existing_guest),
            existing_guest["function"],
            new_guest["function"]))
        print(sql_statement_generator.update_function_of_zutrittsberechtigung(existing_guest["zutrittsberechtigung_id"], new_guest["function"], date, pdf_date))
    return funktion_equal


def sync_parliamentarian(parlamentarier: Dict, conn, batch_time: datetime, pdf_date: date, count: int) -> summary.SummaryRow:
    #load info abo?ut parlamentarier
    kanton_id = db.get_kanton_id(conn, parlamentarier["canton"])
    fraktion_id = db.get_fraktion_id(conn, parlamentarier["faction"])
    try:
        parlamentarier_id = db.get_parlamentarier_id_by_names_kanton_fraktion(conn, parlamentarier["names"], kanton_id, fraktion_id)
    except:
        parlamentarier_id = db.get_parlamentarier_id_by_names_kanton(conn, parlamentarier["names"], kanton_id)
    parlamentarier["id"] = parlamentarier_id
    parlamentarier_db_dict = db.get_parlamentarier_dict(conn, parlamentarier_id)

    #existing guests (from database)
    existing_guests = db.get_guests(conn, parlamentarier_id, GUEST_LIMIT)
    unmatched_existing_guests = list(copy.copy(existing_guests))
    # new guests (from JSON file)
    new_guests = parlamentarier["guests"]
    unmatched_new_guests = copy.copy(new_guests)

    summary_row = summary.SummaryRow(
        parlamentarier, count, parlamentarier_db_dict, GUEST_LIMIT
    )

    for new_guest in new_guests:
        for existing_guest in existing_guests:
            if name_logic.are_guests_equal(existing_guest, new_guest):
                unmatched_existing_guests.remove(existing_guest)
                unmatched_new_guests.remove(new_guest)
                funktion_equal = guest_remained(
                    parlamentarier, existing_guest, new_guest, batch_time, pdf_date
                )
                if not funktion_equal:
                    summary_row.set_guest_changes(existing_guest, "funktion")
                else:
                    summary_row.set_guest(existing_guest)

    for unmatched_new_guest in unmatched_new_guests:
        guest_added(conn, parlamentarier, unmatched_new_guest, batch_time, pdf_date)
        summary_row.set_new_guest(unmatched_new_guest)

    for unmatched_existing_guest in unmatched_existing_guests:
        guest_removed(parlamentarier, unmatched_existing_guest, batch_time, pdf_date)
        summary_row.set_removed_guest(unmatched_existing_guest)

    return summary_row


# main method
if __name__ == "__main__":
    run()
