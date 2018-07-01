# -*- coding: utf-8 -*-

import json
import re
import sys
from datetime import datetime
from operator import itemgetter, attrgetter, methodcaller

import db
import sql_statement_generator
import name_logic
import funktion_logic
import zb_summary as summary

WEB_URL_REGEX = r"""(?i)\b((?:https?:(?:/{1,3}|[a-z0-9%])|[a-z0-9.\-]+[.](?:com|net|org|edu|gov|mil|aero|asia|biz|cat|coop|info|int|jobs|mobi|museum|name|post|pro|tel|travel|xxx|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|dd|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|Ja|sk|sl|sm|sn|so|sr|ss|st|su|sv|sx|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)/)(?:[^\s()<>{}\[\]]+|\([^\s()]*?\([^\s()]+\)[^\s()]*?\)|\([^\s]+?\))+(?:\([^\s()]*?\([^\s()]+\)[^\s()]*?\)|\([^\s]+?\)|[^\s`!()\[\]{};:'".,<>?«»“”‘’])|(?:(?<!@)[a-z0-9]+(?:[.\-][a-z0-9]+)*[.](?:com|net|org|edu|gov|mil|aero|asia|biz|cat|coop|info|int|jobs|mobi|museum|name|post|pro|tel|travel|xxx|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|dd|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|Ja|sk|sl|sm|sn|so|sr|ss|st|su|sv|sx|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)\b/?(?!@)))"""


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

    print("\n = : {:>3d} unchanged\n   : {:>3d} no zutrittsberechtigte\n ≠ : {:>3d} Fields changed\n + : {:>3d} Zutrittsberechtigung added\n - : {:>3d} Zutrittsberechtigung removed\n ± : {:>3d} Zutrittsberechtigung replaced\n\n */".format(
        count_equal, count_no_zb, count_field_change, count_added, count_removed, count_replaced))

    if data_changed:
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
    backup_filename = "{}-{:02d}-{:02d}-{}".format(
        batch_time.year, batch_time.month, batch_time.day, filename)
    print("\n\n-- ----------------------------- ")
    print("-- File: {}".format(backup_filename))

    summary_rows = []
    with open(filename) as data_file:
        content = json.load(data_file)
        stichdatum = datetime.strptime(
            content["metadata"]["pdf_creation_date"], "%Y-%m-%d %H:%M:%S")
        print("-- PDF creation date: {}".format(stichdatum))
        print(
            "-- PDF archive file: {}".format(content["metadata"]["archive_pdf_name"]))
        print("-- ----------------------------- ")
        print("use lobbywat_lobbywatch;")
        count = 1

        for ib_id, org_name, parl_vorname, parl_zweiter_vorname, parl_nachname in db.get_pg_interessenbindungen_managed_by_import(conn):
            present = False
            for group in content["data"]:
                name = normalize_organisation(group["name"])
                if normalize_organisation(org_name).lower() == name.lower():
                    members = group["praesidium"]
                    for member in members:
                        for nachname in parl_nachname.split('-'):
                            if nachname.strip() in member:
                                present = True
            
            if not present:
                print("\n-- Interessenbindung zwischen Parlamentarier {} {} {} und Gruppe {} nicht mehr vorhanden".format(parl_vorname, parl_zweiter_vorname, parl_nachname, org_name))
                print(sql_statement_generator.end_interessenbindung(ib_id, stichdatum, batch_time))
        

        sys.exit()

        for group in content["data"]:

            name = normalize_organisation(group["name"])
            members = group["praesidium"]
            sekretariat = "\\n".join(group["sekretariat"]).replace('\n', ' ')

            homepage = re.findall(WEB_URL_REGEX, sekretariat)
            if homepage is not None and len(homepage) > 0:
                homepage = max(homepage, key=len)
            else:
                homepage = ""

            organisation_id = db.get_organisation_id(conn, name)

            if not organisation_id:
                print("\n-- Neue parlamentarische Gruppe: {}".format(name))
                print(sql_statement_generator.insert_parlamentarische_gruppe(
                    name, sekretariat, homepage, batch_time))
            else:
                db_sekretariat = db.get_organisation_sekretariat(conn, organisation_id)

                if db_sekretariat != sekretariat:
                    if db_sekretariat:
                        print("-- Sekretariat der Gruppe {} geändert".format(name))
                    else:
                        print("-- Sekretariat der Gruppe {} hinzugefügt".format(name))
                    print(sql_statement_generator.update_sekretariat_organisation(
                            organisation_id, sekretariat, batch_time))

                db_homepage = db.get_organisation_homepage(conn, organisation_id)

                if db_homepage != homepage and homepage.strip() is not "" :
                    if db_homepage:
                        print("-- Website der Gruppe {} geändert von {} zu {}".format(name, db_homepage, homepage))
                    else:
                        print("-- Website der Gruppe {} hinzugefügt: {}"
                        .format(name, homepage))
                    print(sql_statement_generator.update_homepage_organisation(
                            organisation_id, homepage, batch_time))


            for member in members:
                names = get_names(member)
                parlamentarier_id = db.get_parlamentarier_id_by_name(
                    conn, names)

                if not parlamentarier_id:
                    print(
                        "DATA INTEGRITY FAILURE: Parlamentarier {} not found in database.".format(member))
                    sys.exit(1)

                parlamentarier_dict = db.get_parlamentarier_dict(conn, parlamentarier_id)
                geschlecht = parlamentarier_dict["geschlecht"]
                beschreibung = ''
                if len(members) > 1:
                    if geschlecht == "M":
                        beschreibung = "Co-Präsident"
                    if geschlecht == "F":
                        beschreibung = "Co-Präsidentin"

                interessenbindung_id = None
                if parlamentarier_id and organisation_id:
                    interessenbindung_id = db.get_interessenbindung_id(
                        conn, parlamentarier_id, organisation_id, stichdatum)

                if not interessenbindung_id:
                    print(
                        "\n-- Neue Interessenbindung zwischen {} und {}".format(name, member))
                    if not organisation_id:
                        organisation_id = '@last_parlamentarische_gruppe'
                    print(sql_statement_generator.insert_interessenbindung_parlamentarische_gruppe(
                        parlamentarier_id, organisation_id, stichdatum, beschreibung, batch_time))

            # summary row
            #summary_row = summary.SummaryRow(parlamentarier, count, parlamentarier_db_dict)
            count += 1

            # summary_rows.append(summary_row)

    # return("\n".join(summary_rows))
    return(summary_rows)


# main method
if __name__ == "__main__":
    run()
