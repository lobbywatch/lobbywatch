import json
import re
import sys
from datetime import datetime
from argparse import ArgumentParser

import db
import sql_statement_generator
from pg_summary import Summary
from utils import clean_whitespace
import literals

# TODO bereinigen: Wirtschafts - und währungspolitischer Arbeitskreis (WPA), id 1973, 5978


WEB_URL_REGEX = r"""(?i)\b((?:https?:(?:/{1,3}|[a-z0-9%])|[a-z0-9.\-]+[.](?:com|net|org|edu|gov|mil|aero|asia|biz|cat|coop|info|int|jobs|mobi|museum|name|post|pro|tel|travel|xxx|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|dd|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|Ja|sk|sl|sm|sn|so|sr|ss|st|su|sv|sx|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw|swiss)/)(?:[^\s()<>{}\[\]]+|\([^\s()]*?\([^\s()]+\)[^\s()]*?\)|\([^\s]+?\))+(?:\([^\s()]*?\([^\s()]+\)[^\s()]*?\)|\([^\s]+?\)|[^\s`!()\[\]{};:'".,<>?«»“”‘’])|(?:(?<!@)[a-z0-9]+(?:[.\-][a-z0-9]+)*[.](?:com|net|org|edu|gov|mil|aero|asia|biz|cat|coop|info|int|jobs|mobi|museum|name|post|pro|tel|travel|xxx|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|dd|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|Ja|sk|sl|sm|sn|so|sr|ss|st|su|sv|sx|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw|swiss)\b/?(?!@)))"""

def run():
    parser = ArgumentParser(description='Create SQL files for data differences')
    parser.add_argument("--db", dest="db_name", help="name of DB to use", metavar="DB", default=None)
    args = parser.parse_args()

    batch_time = datetime.now().replace(microsecond=0)
    conn = db.connect(args.db_name)
    print(sql_statement_generator.start_transaction())
    summary = sync_data(conn, "parlamentarische-gruppen.json", batch_time)
    print(sql_statement_generator.commit_transaction())
    conn.close()
    print_summary(summary, batch_time)


def print_summary(summary, batch_time):
    print("""/*\n\nactive Parlamentarische Gruppen on {}-{:02d}-{:02d} {:02d}:{:02d}:{:02d}
    """.format(batch_time.day, batch_time.month, batch_time.year, batch_time.hour, batch_time.minute, batch_time.second))

    summary.write_header()
    for index, row in enumerate(summary.get_rows()):
        print(row.write(index))

    print("Hinzugefügte Organisationen: {}".format(summary.organisation_added_count()))
    print("Parlamentarier mit unveränderten Interessenbindungen: {}".format(summary.equal_count()))
    print("Parlamentarier mit geänderten Interessenbindungen: {}".format(summary.changed_count()))
    print("Hinzugefügte Interessenbindungen: {}".format(summary.added_count()))
    print("Beendete Interessenbindungen: {}".format(summary.removed_count()))
    print("Hinzugefügte Sekretariate: {}".format(summary.sekretariat_added_count()))
    print("Geänderte Sekretariate: {}".format(summary.sekretariat_changed_count()))
    print("Hinzugefügte Adressen: {}".format(summary.adresse_added_count()))
    print("Geänderte Adressen: {}".format(summary.adresse_changed_count()))
    print("Hinzugefügte Websites: {}".format(summary.websites_added_count()))
    print("Geänderte Websites: {}".format(summary.websites_changed_count()))
    print("Hinzugefügte Namen DE/FR/IT: {}".format(summary.names_added_count()))
    print("Geänderte Namen DE/FR/IT: {}".format(summary.names_changed_count()))
    print("Hinzugefügte Alias: {}".format(summary.aliases_added_count()))
    print("Geänderte Alias: {}".format(summary.aliases_changed_count()))

    print("""*/""")

    if any([row.has_changed() for row in summary.get_rows()]) or summary.organisation_data_changed():
        print("-- DATA CHANGED")
    else:
        print("-- DATA UNCHANGED")


# Often some small details in organisation naming can be ignored
def normalize_organisation(name):
    if name == None: return None
    return re.sub(r'\([^)]*\)', '', name).strip()


def get_names(member):
    member_name_cleaned = re.sub(r'(NR|SR|CN|CE)', '', member).strip()
    # member_name_cleaned = re.sub(r'(Herr|Frau|Nationalratspräsidentin|Nationalratspräsident|Ständeratspräsidentin|Ständeratspräsident|Nationalrat|Nationalrätin|Ständerat|Ständerätin|CN|CE|NR|SR|Monsieur le Conseiller national)', '', member).strip()
    names = member_name_cleaned.split(' ')
    names = [re.sub(r'\([^)]*\)', '', name).strip() for name in names]
    return names

def sync_data(conn, filename, batch_time):
    backup_filename = "{}-{:02d}-{:02d}-{}".format(
        batch_time.year, batch_time.month, batch_time.day, filename)
    print("\n\n-- ----------------------------- ")
    print("-- File: {}".format(backup_filename))

    summary = Summary()

    for parlamentarier_id, nachname, vorname in db.get_active_parlamentarier(conn):
        summary_row = summary.get_row(parlamentarier_id)
        summary_row.parlamentarier_name = nachname + ", " + vorname

    with open(filename) as data_file:
        content = json.load(data_file)
        pdf_date_str = content["metadata"]["pdf_creation_date"]
        archive_pdf_name = content["metadata"]["archive_pdf_name"]
        pdf_date = datetime.strptime(pdf_date_str, "%Y-%m-%d %H:%M:%S") # 2019-07-12 14:55:08
        stichdatum = pdf_date
        print("-- PDF creation date: {}".format(pdf_date))
        print("-- PDF archive file: {}".format(archive_pdf_name))
        print("-- ----------------------------- ")

        handle_removed_groups(content, conn, summary, stichdatum, batch_time, pdf_date)

        for group in content["data"]:
            name_de = normalize_organisation(group["name_de"])
            name_fr = normalize_organisation(group["name_fr"])
            name_it = normalize_organisation(group["name_it"])
            # members = [(m, 'praesidium') for m in group["praesidium"]]
            # members += [(m, 'mitglied') for m in group["mitglieder"]]
            members = group["praesidium"] + group["mitglieder"]

            # TODO Hack since there is a name clash with id 1846 with this id 1851
            if name_de == 'Glasfasernetz Schweiz':
                name_de = 'Parlamentarische Gruppe Glasfasernetz Schweiz'
            elif name_de == 'Hauptstadtregion Schweiz':
                name_de = 'Parlamentarische Gruppe Hauptstadtregion Schweiz'

            # TODO remove after dev
            if name_de == 'Gruppa parlamentara "lingua e cultura rumantscha"':
                organisation_id = 5964
            elif name_de == 'Print und Kommunikation':
                organisation_id = 5348
            elif name_de == 'Sexuelle Gesundheit und Rechte':
                organisation_id = 890
            elif name_de == 'Sport':
                organisation_id = 1751
            elif name_de == 'Nichtionisierende Strahlung, Umwelt und Gesundheit':
                organisation_id = 5734
            else:
                organisation_id = db.get_organisation_id(conn, name_de, name_fr, name_it)

            if organisation_id:
                handle_names(group, name_de, name_fr, name_it, organisation_id, summary, conn, batch_time, pdf_date)
            # TODO remove after dev
            # elif name_de == 'Suisse - Solidarité internationale':
            #     organisation_id = 3500
            # elif name_de == 'Spirituosen und Prävention':
            #     organisation_id = 6135
            elif name_de in ['Für Behindertenfragen', 'Bergberufe', 'Bürgergemeinden und Korporationen', 'Kindes- und Erwachsenenschutz', 'Gutes Hören','Menschenhandel','Startups und Unternehmertum', 'TC Bundeshaus']:
                pass
            else:
                print('INFO: Organisation "{}" not found in DB'.format(name_de))

            handle_homepage_and_sekretariat(group, name_de, name_fr, name_it, organisation_id, summary, conn, batch_time, pdf_date)
            handle_beschreibung(group, organisation_id, summary, conn, batch_time, pdf_date)


            processed_parlamentarier_ids = []
            for member, title in members:
                names = get_names(member)
                parlamentarier_id = db.get_parlamentarier_id_by_name(conn, names, title != None)

                if not parlamentarier_id:
                    print("DATA INTEGRITY FAILURE: Parlamentarier '{}' of group '{}' not found in database.".format(member, name_de))
                    sys.exit(1)
                elif parlamentarier_id in processed_parlamentarier_ids:
                    print('-- INFO: Ignore duplicate member "{}" ({}) in PG "{}"'.format(member, parlamentarier_id, name_de))
                    continue
                else:
                    processed_parlamentarier_ids.append(parlamentarier_id)

                art = "vorstand" if title != None else "mitglied"

                parlamentarier_dict = db.get_parlamentarier_dict(conn, parlamentarier_id)
                geschlecht = 0 if parlamentarier_dict["geschlecht"] == 'M' else 1
                beschreibung = literals.president_mapping[title][geschlecht] if title != None else "Mitglied"
                funktion_im_gremium = literals.president_mapping[title][2] if title != None else None
                # TODO beschreibung_fr

                interessenbindung_id = None
                if parlamentarier_id and organisation_id:
                    interessenbindung_id, db_art, db_funktion_im_gremium = db.get_interessenbindung_id(
                        conn, parlamentarier_id, organisation_id, stichdatum)

                summary_row = summary.get_row(parlamentarier_id)
                if not interessenbindung_id:
                    print(
                        "\n-- Neue Interessenbindung zwischen '{}' und '{}'".format(name_de, member))
                    if not organisation_id:
                        organisation_id = '@last_parlamentarische_gruppe'
                        summary_row.neue_gruppe("neu", name_de)
                    else:
                        summary_row.neue_gruppe(organisation_id, name_de)

                    print(sql_statement_generator.insert_interessenbindung_parlamentarische_gruppe(
                        parlamentarier_id, organisation_id, stichdatum, title != None, beschreibung, funktion_im_gremium, batch_time, pdf_date))
                elif art != db_art or funktion_im_gremium != db_funktion_im_gremium:
                    print(
                        "\n-- Interessenbindungsart oder Funktion geändert zwischen '{}' und '{}': '{}', '{}'".format(name_de, member, art, funktion_im_gremium))
                    print(sql_statement_generator.end_interessenbindung(interessenbindung_id, stichdatum, batch_time, pdf_date))
                    print(sql_statement_generator.insert_interessenbindung_parlamentarische_gruppe(
                        parlamentarier_id, organisation_id, stichdatum, title != None, beschreibung, funktion_im_gremium, batch_time, pdf_date))
                else:
                    summary_row.gruppe_unveraendert(organisation_id, name_de)

    return(summary)

def handle_removed_groups(content, conn, summary, stichdatum, batch_time, pdf_date):
    ib_managed_by_import = db.get_pg_interessenbindungen_managed_by_import(conn)
    if ib_managed_by_import:
        for ib_id, org_name, parl_vorname, parl_zweiter_vorname, parl_nachname, parl_id in ib_managed_by_import:
            present = False
            for group in content["data"]:
                name_de_norm = normalize_organisation(group["name_de"]).lower()
                db_name_de_norm = normalize_organisation(org_name).lower()
                if db_name_de_norm == name_de_norm or (name_de_norm.startswith(db_name_de_norm) and len(db_name_de_norm) > 8) or (db_name_de_norm.startswith(name_de_norm) and len(name_de_norm) > 8):
                    members = group["praesidium"] + group["mitglieder"]
                    for member in members:
                        for nachname in parl_nachname.split('-'):
                            if nachname.strip() in member:
                                present = True

            if not present:
                full_name = parl_vorname
                if parl_zweiter_vorname:
                    full_name += " " + parl_zweiter_vorname
                full_name += " " + parl_nachname
                print("\n-- Interessenbindung zwischen Parlamentarier '{}' und Gruppe '{}' nicht mehr vorhanden".format(full_name, org_name))
                print(sql_statement_generator.end_interessenbindung(ib_id, stichdatum, batch_time, pdf_date))

                summary_row = summary.get_row(parl_id)
                summary_row.gruppe_beendet(ib_id, org_name)


def handle_names(group, name_de, name_fr, name_it, organisation_id, summary, conn, batch_time, pdf_date):
    db_name_de, db_name_fr, db_name_it = db.get_organisation_names(conn, organisation_id)

    if db_name_de != name_de and name_de:
        if db_name_de:
            print("-- Geänderter deutscher Name für Organisation '{}'. Bisher: '{}' Neu: '{}'".format(name_de, db_name_de, name_de))
            summary.name_changed()
        print(sql_statement_generator.update_name_de_organisation(organisation_id, name_de, batch_time, pdf_date))

    if db_name_fr != name_fr and name_fr:
        if db_name_fr:
            print("-- Geänderter französischer Name für Organisation '{}'. Bisher: '{}' Neu: '{}'".format(name_de, db_name_fr, name_fr))
            summary.name_changed()
        else:
            print("-- Neuer französischer Name für Organisation '{}': '{}'".format(name_de, name_fr))
            summary.name_added()
        print(sql_statement_generator.update_name_fr_organisation(organisation_id, name_fr, batch_time, pdf_date))

    if db_name_it != name_it and name_it:
        if db_name_it:
            summary.name_changed()
            print("-- Geänderter italienischer Name für Organisation '{}'. Bisher: '{}' Neu: '{}'".format(name_de, db_name_it, name_it))
        else:
            print("-- Neuer italienischer Name für Organisation '{}': '{}'".format(name_de, name_it))
            summary.name_added()
        print(sql_statement_generator.update_name_it_organisation(organisation_id, name_it, batch_time, pdf_date))



def handle_homepage_and_sekretariat(group, name_de, name_fr, name_it, organisation_id, summary, conn, batch_time, pdf_date):
    sekretariat = "\n".join(group["sekretariat"])
    sekretariat_line = '; '.join(sekretariat.splitlines())
    sekretariat_list = "\n".join(group["sekretariat"]).replace('; ', '\n').replace(';', '\n').replace(', ', '\n').replace(',', '\n').splitlines()

    (adresse_str_list, adresse_zusatz_list, adresse_plz, adresse_ort, alias_list) = ([], [], None, None, [])
    # The order is important. The last match wins. Convenient, addresses are built like that.
    for line_raw in sekretariat_list:
        line = clean_whitespace(line_raw)
        if re.search(r'@\w+\.\w+', line):
            # We alread reached the email address
            break

        m_str = re.search(r'(strasse|gasse|weg|rain|graben|gebäude\b|park|platz|zentrum|av\.|chemin|rue|quai|route|via|Technopôle|Bollwerk)|^\d{1,3} [a-z]+', line, re.IGNORECASE)
        m_zusatz = re.search(r'(Postfach|Case postale|Casella postale|Botschaft|c/o|p\.a\.|Schweiz|Suisse|Swiss|Svizzera|Schweizer|Schweizerischer|Schweizerische|Herr|Frau|Monsieur|Madame|Dr\. | AG|\.ag| SA|GmbH|Sàrl|Ltd|Public|Swiss|Pro|relazioni|Repubblica|Cancelleria|Lia|Koalition|Forum|International|Institut|\bHaus\b|Stiftung|Verein|verband|vereinigung|forum|Gesellschaft|Association|Fédération|Sekretariat|sekretär|Geschäft|Vereinigung|Collaborateur|Bewegung|Minister|Direktor|präsident|Assistent|Délégation|Comité|national|Mesdames|Messieurs|industrie|Inclusion|organisation|Partner|Center|Netzwerk|[^.]com|Vauroux|furrerhugi|Burson|konferenz|bewegung|\.iur|rat|Leiter|Kommunikation)', line, re.IGNORECASE)
        m_ort = re.search(r'(\d{4,5}) ([\w. ]+)', line, re.IGNORECASE)
        m_alias = re.findall(r'\b([A-Z]{3,5})\b', line)
        if m_str:
            adresse_str_list.append(line)
        if m_zusatz:
            adresse_zusatz_list.append(line)
        if m_ort:
            adresse_plz = m_ort.group(1)
            adresse_ort = m_ort.group(2)
            break
        if m_alias:
            for alias in m_alias:
                if alias not in ('CVP', 'EVP', 'FDP', 'SVP', 'GLP'):
                  alias_list.append(alias)

    if adresse_str_list:
        adresse_str = "; ".join(adresse_str_list)
    else:
        adresse_str = None

    if adresse_zusatz_list:
        adresse_zusatz = "; ".join(adresse_zusatz_list)
        if len(adresse_zusatz) > 150:
            print("ERROR 'adresse_zusatz' TOO LONG: " + str(len(adresse_zusatz)))
            print("Line: " + line)
            print("Adresse_zusatz: " + adresse_zusatz)
    else:
        adresse_zusatz = None

    if alias_list:
        alias = "; ".join(alias_list)
    else:
        alias = None

    adresse = (adresse_str, adresse_zusatz, adresse_plz, adresse_ort)

    homepage = re.findall(WEB_URL_REGEX, sekretariat)
    email_host =re.findall(r"@([a-zA-Z.\-_]+)", sekretariat)

    if homepage is not None and len(homepage) > 0:
        homepage = max(homepage, key=len)
        if not re.match('^https?://', homepage):
            homepage = 'http://' + homepage
    elif email_host:
        homepage = None
        for host in email_host:
            if host not in ('parl.ch', 'bluewin.ch', 'gmail.com', 'yahoo.com', 'yahoo.de', 'yahoo.fr', 'gmx.ch', 'gmx.net', 'gmx.de', 'swissonline.ch', 'hotmail.com', 'bluemail.ch', 'outlook.com'):
                homepage = ("http://" + host).upper()
                # print("-- Benutze Domain von E-Mail-Adresse: " + homepage)
                break
    else:
        homepage = None

    sekretariat_line = '; '.join(sekretariat.splitlines())

    if not organisation_id:
        print("\n-- Neue parlamentarische Gruppe: '{}'".format(name_de))
        print(sql_statement_generator.insert_parlamentarische_gruppe(
            name_de, name_fr, name_it, sekretariat, adresse_str, adresse_zusatz, adresse_plz, adresse_ort, homepage, alias, batch_time, pdf_date))
        summary.organisation_added()

        organisation_id = '@last_parlamentarische_gruppe'

    else:
        db_sekretariat = db.get_organisation_sekretariat(conn, organisation_id)

        if db_sekretariat:
            db_sekretariat_line = '; '.join(db_sekretariat.splitlines())
        else:
            db_sekretariat_line = ''

        if db_sekretariat_line != sekretariat_line:
            if db_sekretariat:
                summary.sekretariat_changed()
                print('-- Sekretariat alt: ' + db_sekretariat_line)
                print('-- Sekretariat neu: ' + sekretariat_line)
                print("-- Sekretariat der Gruppe '{}' geändert".format(name_de))
            else:
                # Same code as in new organisation
                summary.sekretariat_added()
                print("-- Sekretariat der Gruppe '{}' hinzugefügt".format(name_de))
            print(sql_statement_generator.update_sekretariat_organisation(
                    organisation_id, sekretariat, batch_time, pdf_date))

        db_adresse = db.get_organisation_adresse(conn, organisation_id)

        if db_adresse != adresse:
            if db_adresse:
                summary.adresse_changed()
                print("-- Adresse der Gruppe '{}' geändert von {} zu {}".format(name_de, db_adresse, adresse))
            else:
                # Same code as in new organisation
                summary.adresse_added()
                print("-- Adresse der Gruppe {} hinzugefügt".format(name_de))
            print('-- Sekretariat: ' + sekretariat.replace('\n', '; '))
            print(sql_statement_generator.update_adresse_organisation(
                    organisation_id, adresse_str, adresse_zusatz, adresse_plz, adresse_ort, batch_time, pdf_date))

        db_homepage = db.get_organisation_homepage(conn, organisation_id)

        if db_homepage != homepage:
            if db_homepage:
                summary.website_changed()
                print("-- Website der Gruppe '{}' geändert von '{}' zu '{}'".format(name_de, '\\n'.join(db_homepage.splitlines()), homepage))
            else:
                # Same code as in new organisation
                summary.website_added()
                print("-- Website der Gruppe '{}' hinzugefügt: '{}'"
                .format(name_de, homepage))
            print(sql_statement_generator.update_homepage_organisation(
                    organisation_id, homepage, batch_time, pdf_date))

        db_alias = db.get_organisation_alias(conn, organisation_id)

        if db_alias != alias:
            if db_alias:
                summary.alias_changed()
                print("-- Alias der Gruppe '{}' geändert von '{}' zu '{}'".format(name_de, '\\n'.join(db_alias.splitlines()), alias))
            else:
                # Same code as in new organisation
                summary.alias_added()
                print("-- Alias der Gruppe '{}' hinzugefügt: '{}'"
                .format(name_de, alias))
            print(sql_statement_generator.update_alias_organisation(
                    organisation_id, alias, batch_time, pdf_date))

def handle_beschreibung(group, organisation_id, summary, conn, batch_time, pdf_date):
    # TODO implement
    pass

# main method
if __name__ == "__main__":
    run()
