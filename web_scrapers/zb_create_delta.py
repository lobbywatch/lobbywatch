import copy
import json
from datetime import date, datetime
from operator import attrgetter
from argparse import ArgumentParser
import db
import name_logic
import sql_statement_generator
import funktion_logic
import zb_summary as summary
from zb_types import JsonParlamentarier, DbParlamentarier, JsonGuest

# Most MPs can have two guests, but those who need more assistance can have up to four guests
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
            for guest in row._guests:
                if guest.symbol == '=':
                    count_equal += 1
                elif guest.symbol == ' ':
                    count_no_zb += 1
                elif guest.symbol == '≠':
                    count_field_change += 1
                elif guest.symbol == '+':
                    count_added += 1
                elif guest.symbol == '-':
                    count_removed += 1
                elif guest.symbol == '±':
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
        pdf_creation_date = datetime.fromisoformat(pdf_date_str) # 2019-07-12 14:55:08
        stand_date = datetime.fromisoformat(stand_date_str).date() # 2019-07-12
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
def guest_added(conn, member_of_parliament:JsonParlamentarier, guest_to_add: JsonGuest, date, pdf_date, parlamentarier_db_dict: DbParlamentarier):
    if guest_to_add is not None:
        function = get_function(guest_to_add, parlamentarier_db_dict)

        print("\n-- Parlamentarier_in '{}' hat einen neuen Gast '{}' mit Funktion '{}'.".format(
            name_logic.fullname(member_of_parliament),
            name_logic.fullname(guest_to_add),
            function))

        guest_to_add["names"] = name_logic.fixNobleNames(guest_to_add["names"])
        # check if the new guest is already a person in the database, if not create them
        person_id = db.get_person_id(conn, guest_to_add["names"])
        if not person_id:
            print("-- Diese_r muss neu in der Datenbank erzeugt werden")
            print(sql_statement_generator.insert_person(guest_to_add, date, pdf_date, function))
        else:
            guest_to_add["id"] = person_id

        print(sql_statement_generator.insert_zutrittsberechtigung(parlamentarier_db_dict["id"], person_id, function, date, pdf_date))


# if a guest remains, we may update their function
def guest_remained(member_of_parliament: JsonParlamentarier, existing_guest, new_guest: JsonGuest, date, pdf_date, parlamentarier_db_dict: DbParlamentarier):
    function = get_function(new_guest, parlamentarier_db_dict)
    funktion_equal = funktion_logic.are_functions_equal(existing_guest["function"], function)
    if not funktion_equal:
        print("\n-- Parlamentarier_in '{}' hat beim Gast '{}' die Funktion von '{}' auf '{}' geändert.".format(
            name_logic.fullname(member_of_parliament),
            name_logic.fullname(existing_guest),
            existing_guest["function"],
            function))
        print(sql_statement_generator.update_function_of_zutrittsberechtigung(existing_guest["zutrittsberechtigung_id"], function, date, pdf_date))
    return funktion_equal


def sync_parliamentarian(parlamentarier: JsonParlamentarier, conn, batch_time: datetime, pdf_date: date, count: int) -> summary.SummaryRow:
    #load info about parlamentarier
    parlamentarier_db_dict: DbParlamentarier = db.get_parlamentarier_by_biography_id(conn, parlamentarier['biography_id'])

    #existing guests (from database)
    existing_guests = db.get_guests(conn, parlamentarier_db_dict['id'], GUEST_LIMIT)
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
                    parlamentarier, existing_guest, new_guest, batch_time, pdf_date, parlamentarier_db_dict
                )
                if not funktion_equal:
                    summary_row.set_guest_changes(existing_guest, "funktion")
                else:
                    summary_row.set_guest(existing_guest)

    for unmatched_new_guest in unmatched_new_guests:
        guest_added(conn, parlamentarier, unmatched_new_guest, batch_time, pdf_date, parlamentarier_db_dict)
        summary_row.set_new_guest(unmatched_new_guest)

    for unmatched_existing_guest in unmatched_existing_guests:
        guest_removed(parlamentarier, unmatched_existing_guest, batch_time, pdf_date)
        summary_row.set_removed_guest(unmatched_existing_guest)

    return summary_row

def get_function(guest: JsonGuest, parlamentarier_db_dict: DbParlamentarier) -> str:
    language = parlamentarier_db_dict['arbeitssprache']
    beneficiary_group = guest["beneficiary_group"]
    function = guest["function"][language] if guest["function"] else ''

    if beneficiary_group and function:
        return f'{function}: {beneficiary_group}'
    elif function:
        return function
    elif beneficiary_group:
        return beneficiary_group
    else:
        return ''

# main method
if __name__ == "__main__":
    run()
