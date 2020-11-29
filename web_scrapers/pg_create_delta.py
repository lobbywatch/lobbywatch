import json
import re
import sys
from datetime import datetime, date
from argparse import ArgumentParser

import db
import sql_statement_generator
from pg_summary import Summary
from utils import clean_whitespace, escape_newlines
import literals

HOST_REGEX = r'(?i)(?:https?://)?((?:[-\w.]+)(?:[-\w.]+\.(?:ch|net|li|org|com|swiss|at|de|edu|info|io|name|pro)))\b'

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
    print("Hinzugefügte Beschreibungen: {}".format(summary.beschreibungen_added_count()))
    print("Geänderte Beschreibungen: {}".format(summary.beschreibungen_changed_count()))

    print("""*/""")

    if any([row.has_changed() for row in summary.get_rows()]) or summary.organisation_data_changed():
        print("-- DATA CHANGED")
    else:
        print("-- DATA UNCHANGED")


# Often some small details in organisation naming can be ignored
def normalize_organisation(name):
    if name == None: return None
    return re.sub(r'\([^)]*\)', '', name).strip()

def get_organisation(group, conn):
    name_de = normalize_organisation(group["name_de"])
    name_fr = normalize_organisation(group["name_fr"])
    name_it = normalize_organisation(group["name_it"])

    # Workaround for multiple results for 'Sport'
    if name_de == 'Sport':
        organisation_id = 1751
    else:
        organisation_id = db.get_organisation_id(conn, name_de, name_fr, name_it)

    return organisation_id, name_de, name_fr, name_it

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
        url = content["metadata"]["url"]
        pdf_date = datetime.strptime(pdf_date_str, "%Y-%m-%d %H:%M:%S") # 2019-07-12 14:55:08
        stichdatum = pdf_date
        print("-- PDF creation date: {}".format(pdf_date))
        print("-- PDF archive file: {}".format(archive_pdf_name))
        print("-- ----------------------------- ")

        handle_removed_groups(content, conn, summary, stichdatum, batch_time, pdf_date)

        print('\n-- Sync pgs...')

        handled_organisation_ids = []
        for group in content["data"]:
            members = group["praesidium"] + group["mitglieder"]

            organisation_id, name_de, name_fr, name_it = get_organisation(group, conn)

            # Skip duplicate groups: Aktive Mobilität and Langsamverkehr are twice in 23.11.2020 PDF
            if organisation_id in handled_organisation_ids:
                print('-- WARN: Organisation "{}" ID={} twice in PDF. Skipped'.format(name_de, organisation_id))
                continue
            else:
                handled_organisation_ids.append(organisation_id)

            if organisation_id:
                handle_names(group, name_de, name_fr, name_it, organisation_id, summary, conn, batch_time, pdf_date)
            else:
                print('-- INFO: Organisation "{}" not found in DB'.format(name_de))

            handle_organisation(group, name_de, name_fr, name_it, organisation_id, summary, conn, batch_time, pdf_date)

            processed_parlamentarier_ids = []
            for member, title in members:
                names = get_names(member)
                parlamentarier_id, parlamentarier_bis = db.get_parlamentarier_id_by_name(conn, names, False)

                if not parlamentarier_id:
                    print("DATA INTEGRITY FAILURE: Parlamentarier '{}' of group '{}' not found in database.".format(member, name_de))
                    sys.exit(1)
                elif parlamentarier_bis and parlamentarier_bis < date.today():
                    print("-- INFO: Parlamentarier '{}' ({}) ist nicht mehr aktiv ('{}')".format(member, parlamentarier_id, parlamentarier_bis))
                    continue
                elif parlamentarier_id in processed_parlamentarier_ids:
                    print('-- WARN: Ignore duplicate member "{}" ({}) in PG "{}"'.format(member, parlamentarier_id, name_de))
                    continue
                else:
                    processed_parlamentarier_ids.append(parlamentarier_id)

                art = "vorstand" if title else "mitglied"

                db_parlamentarier = db.get_parlamentarier_dict(conn, parlamentarier_id)
                geschlecht = 0 if db_parlamentarier["geschlecht"] == 'M' else 1
                funktion_im_gremium = literals.president_mapping[title][0] if title else None
                beschreibung = literals.president_mapping[title][1][geschlecht] if title else "Mitglied"
                beschreibung_fr = literals.president_mapping[title][2][geschlecht] if title else "Membre"

                interessenbindung_id = None
                if parlamentarier_id and organisation_id:
                    interessenbindung_id, db_art, db_funktion_im_gremium, db_beschreibung, db_beschreibung_fr = db.get_interessenbindung_id(
                        conn, parlamentarier_id, organisation_id, stichdatum)

                summary_row = summary.get_row(parlamentarier_id)
                if not interessenbindung_id:
                    print(
                        "\n-- Neue Interessenbindung zwischen '{}' und '{}' als {}{}".format(name_de, member, art, '/' + funktion_im_gremium if funktion_im_gremium else ''))
                    if not organisation_id:
                        organisation_id = '@last_parlamentarische_gruppe'
                        summary_row.neue_gruppe("neu", name_de, art)
                    else:
                        summary_row.neue_gruppe(organisation_id, name_de, art)

                    print(sql_statement_generator.insert_interessenbindung_parlamentarische_gruppe(
                        parlamentarier_id, organisation_id, stichdatum, title != None, beschreibung, beschreibung_fr, funktion_im_gremium, url, batch_time, pdf_date))
                elif art != db_art: # Do not check funktion_im_gremium for change (simply update funktion_im_gremium)
                    print(
                        "\n-- Interessenbindungsart oder Funktion geändert zwischen '{}' und '{}': '{}', '{}'".format(name_de, member, art, funktion_im_gremium))
                    print(sql_statement_generator.end_interessenbindung(interessenbindung_id, stichdatum, batch_time, pdf_date))
                    print(sql_statement_generator.insert_interessenbindung_parlamentarische_gruppe(
                        parlamentarier_id, organisation_id, stichdatum, title != None, beschreibung, beschreibung_fr, funktion_im_gremium, url, batch_time, pdf_date))
                    summary_row.gruppe_veraendert(organisation_id, name_de, art)
                elif funktion_im_gremium != db_funktion_im_gremium or beschreibung != db_beschreibung or beschreibung_fr != db_beschreibung_fr:
                    print(
                        "\n-- Interessenbindungsbeschreibung geändert '{}': '{}' → '{}' / '{}' → '{}' / '{}' → '{}'".format(name_de, db_funktion_im_gremium, funktion_im_gremium, db_beschreibung, beschreibung,db_beschreibung_fr, beschreibung_fr))
                    print(sql_statement_generator.update_beschreibung_interessenbindung(
                        interessenbindung_id, funktion_im_gremium, beschreibung, beschreibung_fr, url, batch_time, pdf_date))
                    summary_row.gruppe_veraendert(organisation_id, name_de, art)
                else:
                    summary_row.gruppe_unveraendert(organisation_id, name_de, art)

    return(summary)

def handle_removed_groups(content, conn, summary, stichdatum, batch_time, pdf_date):
    print('-- Check removed groups...')
    ib_managed_by_import = db.get_pg_interessenbindungen_managed_by_import(conn)
    if ib_managed_by_import:
        parlamentarier_id_cache = {}
        organisation_id_cache = {}
        lastprogress = -1
        for i, (ib_id, ib_art, ib_funktion_im_gremium, org_id, org_name, parl_vorname, parl_zweiter_vorname, parl_nachname, parl_id) in enumerate(ib_managed_by_import):
            progress = 100 * i // len(ib_managed_by_import)
            if progress % 25 == 0 and progress != lastprogress:
                print('-- Progress {}%'.format(progress))
                lastprogress = progress
            present = False
            for group in content["data"]:
                org_key = group["name_de"]
                if org_key in organisation_id_cache:
                    organisation_id, name_de, name_fr, name_it = organisation_id_cache[org_key]
                else:
                    organisation_id, name_de, name_fr, name_it = get_organisation(group, conn)
                    organisation_id_cache[org_key] = (organisation_id, name_de, name_fr, name_it)
                if org_id == organisation_id:
                    members = group["praesidium"] + group["mitglieder"]
                    processed_parlamentarier_ids = []
                    for member, title in members:
                        parl_key = (member, title)
                        if parl_key in parlamentarier_id_cache:
                            parlamentarier_id, parlamentarier_bis = parlamentarier_id_cache[parl_key]
                        else:
                            names = get_names(member)
                            parlamentarier_id, parlamentarier_bis = db.get_parlamentarier_id_by_name(conn, names, False)
                            parlamentarier_id_cache[parl_key] = (parlamentarier_id, parlamentarier_bis)

                        if not parlamentarier_id:
                            print("DATA INTEGRITY FAILURE: Parlamentarier '{}' of group '{}' not found in database.".format(member, name_de))
                            sys.exit(1)
                        elif parlamentarier_bis and parlamentarier_bis < date.today():
                            # print("-- INFO: Parlamentarier '{}' ({}) ist nicht mehr aktiv ('{}')".format(member, parlamentarier_id, parlamentarier_bis))
                            continue
                        elif parlamentarier_id in processed_parlamentarier_ids:
                            # print('-- INFO: Ignore duplicate member "{}" ({}) in PG "{}"'.format(member, parlamentarier_id, name_de))
                            continue
                        else:
                            processed_parlamentarier_ids.append(parlamentarier_id)

                        art = "vorstand" if title else "mitglied"
                        # Do not check funktion_im_gremium
                        if parl_id == parlamentarier_id and ib_art == art:
                            present = True
                            break

            if not present:
                full_name = parl_vorname
                if parl_zweiter_vorname:
                    full_name += " " + parl_zweiter_vorname
                full_name += " " + parl_nachname
                print("\n-- Interessenbindung zwischen Parlamentarier '{}' und Gruppe '{}' als {}{} nicht mehr vorhanden".format(full_name, org_name,ib_art, '/' + ib_funktion_im_gremium if ib_funktion_im_gremium else ''))
                print(sql_statement_generator.end_interessenbindung(ib_id, stichdatum, batch_time, pdf_date))

                summary_row = summary.get_row(parl_id)
                summary_row.gruppe_beendet(ib_id, org_name, ib_art)

    print('-- Progress {}%'.format(100))


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



def handle_organisation(group, name_de, name_fr, name_it, organisation_id, summary, conn, batch_time, pdf_date):
    sekretariat = "\n".join(group["sekretariat"])
    sekretariat_line = '; '.join(sekretariat.splitlines())
    sekretariat_list = "\n".join(group["sekretariat"]).replace('; ', '\n').replace(';', '\n').replace(', ', '\n').replace(',', '\n').splitlines()

    (adresse_str_list, adresse_zusatz_list, adresse_plz, adresse_ort, alias_list) = ([], [], None, None, [])
    # The order is important. The last match wins. Convenient, addresses are built like that.
    for line_raw in sekretariat_list:
        line = clean_whitespace(line_raw)
        if re.search(r'@\w+\.\w+', line):
            # We already reached the email address
            break

        m_str = re.search(r'(strasse|gasse|weg|rain|graben|gebäude\b|park|platz|zentrum|av\.|chemin|rue|quai|route|via|Technopôle|Bollwerk)|^\d{1,3} [a-z]+', line, re.IGNORECASE)
        m_zusatz = re.search(r'(Postfach|Case postale|Casella postale|Botschaft|c/o|p\.a\.|Schweiz|Suisse|Swiss|Svizzera|Schweizer|Schweizerischer|Schweizerische|Herr|Frau|Monsieur|Madame|Dr\. | AG|\.ag| SA|GmbH|Sàrl|Ltd|Public|Swiss|Pro|relazioni|Repubblica|Cancelleria|Lia|Koalition|Forum|International|Institut|\bHaus\b|Stiftung|Verein|verband|vereinigung|forum|Gesellschaft|Association|Fédération|Sekretariat|sekretär|Geschäft|Vereinigung|Collaborateur|Bewegung|Minister|Direktor|präsident|Assistent|Délégation|Comité|national|Mesdames|Messieurs|industrie|Inclusion|organisation|Partner|Center|Netzwerk|[^.]com|Vauroux|furrerhugi|Burson|konferenz|bewegung|\.iur|rat|Leiter|Kommunikation|Office)', line, re.IGNORECASE)
        m_ort = re.search(r'(\d{4,5}) ([\w. ]+)', re.sub(r'\s*Tel.*', '', line), re.IGNORECASE)
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
        max_adresse_zusatz_len = 150
        if len(adresse_zusatz) > max_adresse_zusatz_len:
            print("-- WARN 'adresse_zusatz' TOO LONG: " + str(len(adresse_zusatz)))
            print("-- Line: " + line)
            print("-- Adresse_zusatz: " + adresse_zusatz)
            adresse_zusatz = adresse_zusatz[:max_adresse_zusatz_len]
    else:
        adresse_zusatz = None

    if alias_list:
        alias = "; ".join(alias_list)
    else:
        alias = None

    adresse = (adresse_str, adresse_zusatz, adresse_plz, adresse_ort)

    homepage = re.findall(HOST_REGEX, sekretariat)
    email_host = re.findall(r"(?i)@([-\w.]+)", sekretariat)

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

    beschreibung = get_pg_beschreibung(name_de, group, organisation_id, summary, conn, batch_time, pdf_date)

    if not organisation_id:
        print("\n-- Neue parlamentarische Gruppe: '{}'".format(name_de))
        print(sql_statement_generator.insert_parlamentarische_gruppe(
            name_de, name_fr, name_it, beschreibung, sekretariat, adresse_str, adresse_zusatz, adresse_plz, adresse_ort, homepage, alias, batch_time, pdf_date))
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
                print("-- Sekretariat der Gruppe '{}' geändert".format(name_de))
                print('-- Sekretariat alt: ' + db_sekretariat_line)
                print('-- Sekretariat neu: ' + sekretariat_line)
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

        # if (db_homepage == None and homepage != None) or (db_homepage != None and homepage == None) or (db_homepage != None and homepage != None and db_homepage.lower() != homepage.lower()): # case insensitive comparision
        if db_homepage != homepage:
            if db_homepage:
                summary.website_changed()
                print("-- Website der Gruppe '{}' geändert von '{}' zu '{}'".format(name_de, escape_newlines(db_homepage), escape_newlines(homepage)))
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
                print("-- Alias der Gruppe '{}' geändert von '{}' zu '{}'".format(name_de, escape_newlines(db_alias), escape_newlines(alias)))
            else:
                # Same code as in new organisation
                summary.alias_added()
                print("-- Alias der Gruppe '{}' hinzugefügt: '{}'"
                .format(name_de, alias))
            print(sql_statement_generator.update_alias_organisation(
                    organisation_id, alias, batch_time, pdf_date))

        db_beschreibung = db.get_organisation_beschreibung(conn, organisation_id)

        if db_beschreibung != beschreibung:
            if db_beschreibung:
                summary.beschreibung_changed()
                print("-- Beschreibung der Gruppe '{}' geändert".format(name_de))
                print('-- Beschreibung alt: ' + escape_newlines(db_beschreibung))
                print('-- Beschreibung neu: ' + escape_newlines(beschreibung))
            else:
                # Same code as in new organisation
                summary.beschreibung_added()
                print("-- Beschreibung der Gruppe '{}' hinzugefügt".format(name_de))
            print(sql_statement_generator.update_beschreibung_organisation(
                    organisation_id, beschreibung, batch_time, pdf_date))


def get_pg_beschreibung(name_de, group, organisation_id, summary, conn, batch_time, pdf_date):
    zweck = join_respecting_lists_and_trennzeichen(group["zweck"]) if group["zweck"] != None else None
    aktivitaeten = join_respecting_lists_and_trennzeichen(group["art_der_aktivitaeten"]) if group["art_der_aktivitaeten"] != None else None
    gruendungsjahr = re.sub(r'.*(\d{4}).*', r'\g<1>', group["konstituierung"]) if group["konstituierung"] != None else None

    beschreibung = []
    if zweck:
        beschreibung.append(zweck)
    if aktivitaeten:
        beschreibung.append('Aktivitäten:\n' + aktivitaeten)
    if gruendungsjahr:
        beschreibung.append('Gründung: ' + gruendungsjahr)

    return '\n\n'.join(beschreibung)

def join_respecting_lists_and_trennzeichen(list):
    str = ''
    fix_trennzeichen = False
    for i, x in enumerate(list):
        # (?<! ) = negative lookbehind assertion -> no space before -
        if re.search(r'(?<! )-$', x):
            # Jugend-\nverbände, Natuer-\nHeimatschutz
            fix_trennzeichen = i == len(list) - 1 or (list[i + 1].split(' ')[0] not in ['und', 'oder'] and list[i + 1][0].islower())
            str += re.sub('-$', '', x) if fix_trennzeichen else x
        elif re.match(r'\* |\d+\. ', x): # re.match matches only from beginning
            if i == 0:
                str += x
            else:
                str += '\n' + x
        elif x == '':
            str += '\n'
        else:
            str += ' ' + x if not fix_trennzeichen else x
            fix_trennzeichen = False
    return str.strip()

# main method
if __name__ == "__main__":
    run()
