# -*- coding: utf-8 -*-

# Created by Markus Roth in March 2017 (maroth@gmail.com)
# Licenced via Affero GPL v3

import json
import re
import sys
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
        stichdatum = datetime.strptime(content["metadata"]["pdf_creation_date"], "%Y-%m-%d %H:%M:%S") 
        print("-- PDF creation date: {}".format(stichdatum))
        print("-- PDF archive file: {}".format(content["metadata"]["archive_pdf_name"]))
        print("-- ----------------------------- ")
        count = 1
        for group in content["data"]:

            #load group name
            name = normalize_organisation(group["name"])
            members = group["members"]

            organisation_id = db.get_organisation_id(conn, name)

            if not organisation_id:
                print("\n-- Neue parlamentarische Gruppe: {}".format(name))
                print(create_queries.insert_parlamentarische_gruppe(name, batch_time))

            for member in members:
                names = get_names(member)
                parlamentarier_id = db.get_parlamentarier_id_by_name(conn, names)
                interessenbindung_id = None
                if parlamentarier_id and organisation_id:
                    interessenbindung_id = db.get_interessenbindung_id(conn, parlamentarier_id, organisation_id, stichdatum)

                else:
                    print("DATA INTEGRITY FAILURE: Parlamentarier {} not found in database".format(member))


                if not interessenbindung_id:
                    print("\n-- Neue Interessenbindung zwischen {} und {}".format(name, member))
                    print(create_queries.insert_interessenbindung_parlamentarische_gruppe(parlamentarier_id, organisation_id, stichdatum, batch_time))

            #summary row
            #summary_row = summary.SummaryRow(parlamentarier, count, parlamentarier_db_dict)
            count += 1

            #summary_rows.append(summary_row)

    #return("\n".join(summary_rows))
    return(summary_rows)


# main method
if __name__ == "__main__":
    run()
