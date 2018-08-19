# -*- coding: utf-8 -*-

import json
import re
import sys
from datetime import datetime
from operator import itemgetter, attrgetter, methodcaller
from argparse import ArgumentParser

import db
import sql_statement_generator
import name_logic
import funktion_logic
from pg_summary import Summary, SummaryRow

WEB_URL_REGEX = r"""(?i)\b((?:https?:(?:/{1,3}|[a-z0-9%])|[a-z0-9.\-]+[.](?:com|net|org|edu|gov|mil|aero|asia|biz|cat|coop|info|int|jobs|mobi|museum|name|post|pro|tel|travel|xxx|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|dd|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|Ja|sk|sl|sm|sn|so|sr|ss|st|su|sv|sx|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)/)(?:[^\s()<>{}\[\]]+|\([^\s()]*?\([^\s()]+\)[^\s()]*?\)|\([^\s]+?\))+(?:\([^\s()]*?\([^\s()]+\)[^\s()]*?\)|\([^\s]+?\)|[^\s`!()\[\]{};:'".,<>?«»“”‘’])|(?:(?<!@)[a-z0-9]+(?:[.\-][a-z0-9]+)*[.](?:com|net|org|edu|gov|mil|aero|asia|biz|cat|coop|info|int|jobs|mobi|museum|name|post|pro|tel|travel|xxx|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|dd|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|Ja|sk|sl|sm|sn|so|sr|ss|st|su|sv|sx|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)\b/?(?!@)))"""

def run():
    parser = ArgumentParser(description='Create SQL files for data differences')
    parser.add_argument("--db", dest="db_name", help="name of DB to use", metavar="DB", default=None)
    args = parser.parse_args()

    batch_time = datetime.now().replace(microsecond=0)
    conn = db.connect(args.db_name)
    summary = sync_data(conn, "parlamentarische-gruppen.json", batch_time)
    conn.close()
    print_summary(summary, batch_time)


def print_summary(summary, batch_time):
    print("""/*\n\nactive Parlamentarische Gruppen on {}-{:02d}-{:02d} {:02d}:{:02d}:{:02d}
    """.format(batch_time.day, batch_time.month, batch_time.year, batch_time.hour, batch_time.minute, batch_time.second))

    summary.write_header()
    for index, row in enumerate(summary.get_rows()):
        print(row.write(index))

    print("Parlamentarier mit unveränderten Interessenbindungen: {}".format(summary.equal_count()))
    print("Parlamentarier mit geänderten Interessenbindungen: {}".format(summary.changed_count()))
    print("Hinzugefügte Interessenbindungen: {}".format(summary.added_count()))
    print("Beendete Interessenbindungen: {}".format(summary.removed_count()))
    print("Hinzugefügte Sekretariate: {}".format(summary.sekretariats_added_count()))
    print("Geänderte Sekretariate: {}".format(summary.sekretariats_changed_count()))
    print("Hinzugefügte Websites: {}".format(summary.websites_added_count()))
    print("Geänderte Websites: {}".format(summary.websites_changed_count()))

    print("""/*""")

    if any([row.has_changed() for row in summary.get_rows()]):
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

    summary = Summary()
    with open(filename) as data_file:
        content = json.load(data_file)
        stichdatum = datetime.strptime(
            content["metadata"]["pdf_creation_date"], "%Y-%m-%d %H:%M:%S")
        print("-- PDF creation date: {}".format(stichdatum))
        print(
            "-- PDF archive file: {}".format(content["metadata"]["archive_pdf_name"]))
        print("-- ----------------------------- ")
        print("use lobbywat_lobbywatch;")

        handle_removed_groups(content, conn, summary, stichdatum, batch_time)

        for group in content["data"]:
            name_de = normalize_organisation(group["name_de"])
            name_fr = normalize_organisation(group["name_fr"])
            name_it = normalize_organisation(group["name_it"])
            members = group["praesidium"]

            organisation_id = db.get_organisation_id(conn, name_de)
            handle_homepage_and_sekretariat(group, name_de, name_fr, name_it, organisation_id, summary, conn, batch_time)

            for member in members:
                names = get_names(member)
                parlamentarier_id = db.get_parlamentarier_id_by_name(conn, names)

                if not parlamentarier_id:
                    print("DATA INTEGRITY FAILURE: Parlamentarier {} not found in database.".format(member))
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

                summary_row = summary.get_row(parlamentarier_id)
                if not interessenbindung_id:
                    print(
                        "\n-- Neue Interessenbindung zwischen {} und {}".format(name_de, member))
                    if not organisation_id:
                        organisation_id = '@last_parlamentarische_gruppe'
                        summary_row.neue_gruppe("neu", name_de)
                    else:
                        summary_row.neue_gruppe(organisation_id, name_de)

                    print(sql_statement_generator.insert_interessenbindung_parlamentarische_gruppe(
                        parlamentarier_id, organisation_id, stichdatum, beschreibung, batch_time))


                else:
                    summary_row.gruppe_unveraendert(organisation_id, name_de)

    for row in summary.get_rows():
        parl_dict = db.get_parlamentarier_dict(conn, row.parlamentarier_id)
        name = parl_dict["vorname"] + " " + parl_dict["nachname"]
        row.parlamentarier_name = name

    return(summary)
 

def handle_removed_groups(content, conn, summary, stichdatum, batch_time):
    ib_managed_by_import = db.get_pg_interessenbindungen_managed_by_import(conn)
    if ib_managed_by_import:
        for ib_id, org_name, parl_vorname, parl_zweiter_vorname, parl_nachname, parl_id in ib_managed_by_import:
            org_name = org_name
            present = False
            for group in content["data"]:
                name_de = normalize_organisation(group["name_de"])
                if normalize_organisation(org_name).lower() == name_de.lower():
                    members = group["praesidium"]
                    for member in members:
                        for nachname in parl_nachname.split('-'):
                            if nachname.strip() in member:
                                present = True
            
            if not present:
                full_name = parl_vorname
                if parl_zweiter_vorname:
                    full_name += " " + parl_zweiter_vorname
                full_name += " " + parl_nachname
                print("\n-- Interessenbindung zwischen Parlamentarier {} und Gruppe {} nicht mehr vorhanden".format(full_name, org_name))
                print(sql_statement_generator.end_interessenbindung(ib_id, stichdatum, batch_time))

                summary_row = summary.get_row(parl_id)
                summary_row.gruppe_beendet(ib_id, org_name)


def handle_homepage_and_sekretariat(group, name_de, name_fr, name_it, organisation_id, summary, conn, batch_time):
    sekretariat = "\n".join(group["sekretariat"]).replace('\n', ' ')

    homepage = re.findall(WEB_URL_REGEX, sekretariat)
    if homepage is not None and len(homepage) > 0:
        homepage = max(homepage, key=len)
    else:
        homepage = ""

    if not organisation_id:
        print("\n-- Neue parlamentarische Gruppe: {}".format(name_de))
        print(sql_statement_generator.insert_parlamentarische_gruppe(
            name_de, name_fr, name_it, sekretariat, homepage, batch_time))
    else:
        db_sekretariat = db.get_organisation_sekretariat(conn, organisation_id)

        if db_sekretariat != sekretariat:
            if db_sekretariat:
                summary.sekretariat_changed()
                print("-- Sekretariat der Gruppe {} geändert".format(name_de))
            else:
                summary.sekretariat_added()
                print("-- Sekretariat der Gruppe {} hinzugefügt".format(name_de))
            print(sql_statement_generator.update_sekretariat_organisation(
                    organisation_id, sekretariat, batch_time))

        db_homepage = db.get_organisation_homepage(conn, organisation_id)

        if db_homepage != homepage and homepage.strip() is not "" :
            if db_homepage:
                summary.website_changed()
                print("-- Website der Gruppe {} geändert von {} zu {}".format(name_de, db_homepage, homepage))
            else:
                summary.website_added()
                print("-- Website der Gruppe {} hinzugefügt: {}"
                .format(name_de, homepage))
            print(sql_statement_generator.update_homepage_organisation(
                    organisation_id, homepage, batch_time))
    
# main method
if __name__ == "__main__":
    run()