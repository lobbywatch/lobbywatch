from datetime import datetime
import subprocess

import MySQLdb
import sys
import os
import re
import name_logic
from utils import escape_SQL, _quote_str_or_NULL, _date_as_sql_string, _datetime_as_sql_string

def get_script_path():
    return os.path.dirname(os.path.realpath(__file__))

# establish connection to the database
def connect(db_name):
    if db_name != None:
        db_param = '--db=' + db_name
    else:
        db_param = ''

    # get the database connection string from the existing php module via a wrapper script
    connection_info = subprocess.check_output(['php', get_script_path() + '/get_db_connection_string.php', db_param]).decode('ascii').split(":")
    batch_time = datetime.now().replace(microsecond=0)
    print("-- Delta created on {} ".format(batch_time))
    print("-- Based on database '{}' on '{}'".
          format(connection_info[3], connection_info[2]))

    try:
        database = MySQLdb.connect(
            user=connection_info[0],
            passwd=connection_info[1],
            host=connection_info[2],
            db=connection_info[3],
            port=int(connection_info[4]),
            charset='utf8mb4',
            use_unicode=True)
    except MySQLdb.OperationalError:
        database = MySQLdb.connect(
            user=connection_info[0],
            passwd=connection_info[1],
            host=connection_info[2],
            db=connection_info[3],
            port=int(connection_info[4]),
            charset='utf8mb4',
            use_unicode=True,
            unix_socket="/home/rkurmann/dev/web/mysql/mysql57/data/mysql.sock")
        print("-- Using docker MySQL")

    return database


# get kanton by kanton_kuerzel
def get_kanton_id(database, kanton_kuerzel):
    with database.cursor() as cursor:
        cursor.execute("""
        SELECT id
        FROM kanton
        WHERE abkuerzung = '{}'
        """.format(kanton_kuerzel))

        kanton_id = cursor.fetchone()[0]
    return kanton_id


# get partei by partei_kuerzel
def get_partei_id(database, partei_kuerzel):
    if not partei_kuerzel:
        return None
    with database.cursor() as cursor:
        cursor.execute("""
        SELECT id
        FROM partei
        WHERE abkuerzung = '{0}'
        OR abkuerzung_fr = '{0}'
        """.format(partei_kuerzel))

        partei_id = cursor.fetchone()

        if partei_id is None:
            print("\n\nDATA INTEGRITY FAILURE: Partei '{}' referenced in PDF is not in database. Aborting.".format(
                partei_kuerzel))
            sys.exit(1)

    return partei_id[0]


# get a parlamentarier_id by names, kanton_id and partei_id
def get_parlamentarier_id(database, names, kanton_id, partei_id):
    with database.cursor() as cursor:
        query = """
        SELECT id
        FROM parlamentarier
        WHERE kanton_id = {0}
        """.format(kanton_id)

        prev_mitte_partei = (8, 22, 7, 11, 12, 18)
        if partei_id in prev_mitte_partei:
            query += " AND partei_id IN ({})".format(", ".join(str(i) for i in prev_mitte_partei)) # handle transition of "Die Mitte" which is a fusion of CVP and BDP
        elif partei_id:
            query += " AND partei_id = '{}'".format(partei_id)
        else:
            query += " AND partei_id IS NULL"

        for description in ["NV", "NZV", "NNV", "NVV", "NNNV", "NS"]:
            current_query = query + _generate_name_query(description, names, False)
            cursor.execute(current_query)
            result = cursor.fetchall()
            if result and len(result) == 1:
                (parlamentarier_id, ) = result[0]
                return parlamentarier_id

    print(
        "\n\nDATA INTEGRITY FAILURE: Member of parliament '{0}' referenced in PDF is not in database. Aborting.".format(names))
    sys.exit(1)


def get_parlamentarier_id_by_name(database, names, prename_first: bool):
    name_patterns = ["VN", "VZN", "VVN", "VNN", "SN", "N"] if prename_first else ["NV", "NVZ", "NVV", "NNV", "NNVV", "NNNV", "NN", "SN", "N"]
    with database.cursor() as cursor:
        query = """
        SELECT id, im_rat_bis
        FROM parlamentarier
        WHERE 1=1
        """

        for description in name_patterns:
            current_query = query + _generate_name_query(description, names, False)
            cursor.execute(current_query)
            result = cursor.fetchall()
            if result and len(result) == 1:
                (parlamentarier_id, bis, ) = result[0]
                return parlamentarier_id, bis

        print(
            "\n\nDATA INTEGRITY ERROR: Member of parliament '{}' referenced in PDF is not in database.\nNames: {}, Prename first: {}\nSQL:\n{}".format(names, names, prename_first, current_query))
    return None, None


# get a parlamentarier dict by parlamentarier_id
def get_parlamentarier_dict(database, parlamentarier_id):
    with database.cursor(MySQLdb.cursors.DictCursor) as cursor:
        parlamentarier = None
        query = """
        SELECT *
        FROM parlamentarier
        WHERE id = {0}
        """.format(parlamentarier_id)

        cursor.execute(query)
        result = cursor.fetchall()
        if result and len(result) == 1:
            parlamentarier = result[0]
            return parlamentarier

    print("\n\nA parlamentarier with parlamentarier_id '{}' does not exist.".format(
        parlamentarier_id))
    sys.exit(1)


# get person_id by names
def get_person_id(database, names):
    with database.cursor() as cursor:
        query = """
        SELECT id
        FROM person
        WHERE 1=1 """

        for description in ["NV", "NZV", "NNV", "NVV", "NNNV", "NS"]:
            current_query = query + _generate_name_query(description, names, True)
            cursor.execute(current_query)
            result = cursor.fetchall()
            if result and len(result) > 1:
                print(
                    "\n\nDATA INTEGRITY FAILURE: There are multiple possibilities in the database for guest {0}: '{1}'. Aborting.".format(names, result))
                sys.exit(1)

            if result and len(result) == 1:
                (person_id, ) = result[0]
                return person_id

    return None


# get organisation by name
def get_organisation_id(database, name_de, name_fr, name_it):
    with database.cursor() as cursor:
        organisation_id = None
        query = """
SELECT id, inaktiv
FROM organisation
WHERE (name_de LIKE '{0}%'
OR name_de LIKE 'Parlamentarische Gruppe%{0}'
OR CONCAT_WS(' ', name_de, abkuerzung_de) LIKE 'Parlamentarische Gruppe%{0}'
OR name_de LIKE 'Parlamentarische%{0}'
OR (CHAR_LENGTH(name_de) > 8 AND LOWER(name_de) = LOWER(SUBSTR('{0}', 1, CHAR_LENGTH(name_de))))
OR (CHAR_LENGTH('{0}') > 8 AND LOWER('{0}') = LOWER(SUBSTR(name_de, 1, CHAR_LENGTH('{0}')))))
AND rechtsform='Parlamentarische Gruppe'
        """.format(escape_SQL(name_de))

        if name_fr:
            query += """
OR name_fr LIKE '{0}'
OR name_fr LIKE 'Intergroupe parlementaire%{0}'
            """.format(escape_SQL(name_fr))

        if name_it:
            query += """
OR name_it LIKE '{0}'
OR name_it LIKE 'Intergruppo parlamentare%{0}'
            """.format(escape_SQL(name_it))

        # Enable ase sensitive comparison for cases Parlamentarische Gruppe ...Sport vs Parlamentarische Gruppe ...Transport
        # query += " COLLATE utf8mb4_bin"
        query += ";"

        cursor.execute(query)
        result = cursor.fetchall()
        assert len(result) <= 1, "DATA INTEGRITY FAILURE: There are multiple possibilities \
in the database for organisation '{}: {}'.  Aborting.\n{}".format(name_de, result, query)
        # if result and len(result) > 1:
#             print("\n\nDATA INTEGRITY FAILURE: There are multiple possibilities \
# in the database for organisation '{0}: {1}'.  Aborting.".format(name_de, result))
#             sys.exit(1)

        if result and len(result) == 1:
            (organisation_id, inaktiv, ) = result[0]
            return organisation_id, inaktiv

    return None, None


def get_organisation_sekretariat(database, organisation_id):
    with database.cursor() as cursor:
        query = """
        SELECT sekretariat
        FROM organisation
        WHERE id = '{}';
        """.format(organisation_id)

        cursor.execute(query)
        result = cursor.fetchall()

        if result and len(result) == 1:
            (sekretariat, ) = result[0]
            return sekretariat

    return None


def get_organisation_beschreibung(database, organisation_id):
    with database.cursor() as cursor:
        query = """
        SELECT beschreibung
        FROM organisation
        WHERE id = '{}';
        """.format(organisation_id)

        cursor.execute(query)
        result = cursor.fetchall()

        if result and len(result) == 1:
            (beschreibung, ) = result[0]
            return beschreibung

    return None


def get_organisation_names(database, organisation_id):
    with database.cursor() as cursor:
        query = """
        SELECT name_de, name_fr, name_it
        FROM organisation
        WHERE id = '{}';
        """.format(organisation_id)

        cursor.execute(query)
        result = cursor.fetchall()

        if result and len(result) == 1:
            name_de, name_fr, name_it = result[0]
            return (name_de, name_fr, name_it)

    return None


def get_organisation_adresse(database, organisation_id):
    with database.cursor() as cursor:
        query = """
        SELECT adresse_strasse, adresse_zusatz, adresse_plz, ort
        FROM organisation
        WHERE id = '{}';
        """.format(organisation_id)

        cursor.execute(query)
        result = cursor.fetchall()

        if result and len(result) == 1:
            adresse = result[0]
            return adresse

    return None


def get_organisation_homepage(database, organisation_id):
    with database.cursor() as cursor:
        query = """
        SELECT homepage
        FROM organisation
        WHERE id = '{}';
        """.format(organisation_id)

        cursor.execute(query)
        result = cursor.fetchall()

        if result and len(result) == 1:
            (homgepage, ) = result[0]
            return homgepage

    return None


def get_organisation_alias(database, organisation_id):
    with database.cursor() as cursor:
        query = """
        SELECT alias_namen_de
        FROM organisation
        WHERE id = '{}';
        """.format(organisation_id)

        cursor.execute(query)
        result = cursor.fetchall()

        if result and len(result) == 1:
            (alias, ) = result[0]
            return alias

    return None

def get_pg_interessenbindungen_managed_by_import(database):
    with database.cursor() as cursor:
        query = """
        SELECT ib.id, art, funktion_im_gremium, org.id, org.name_de, parl.vorname, parl.zweiter_vorname, parl.nachname, parl.id
        FROM interessenbindung ib
        INNER JOIN organisation org ON ib.organisation_id = org.id
        INNER JOIN parlamentarier parl ON ib.parlamentarier_id = parl.id
        WHERE org.rechtsform = 'Parlamentarische Gruppe'
        AND ib.updated_by_import IS NOT NULL
        AND (ib.bis IS NULL OR ib.bis > NOW());
        """

        cursor.execute(query)
        results = cursor.fetchall()

        if results:
            return results

    return None


def get_interessenbindung_id(database, parlamentarier_id, organisation_id, stichdatum):
    with database.cursor() as cursor:
        query = """
        SELECT id, art, funktion_im_gremium, beschreibung, beschreibung_fr
        FROM interessenbindung
        WHERE parlamentarier_id = {}
        AND organisation_id = {}
        AND (bis IS NULL OR bis > '{}');
        """.format(parlamentarier_id,
                   organisation_id,
                   stichdatum,
                   )

        cursor.execute(query)
        result = cursor.fetchall()
        if result and len(result) > 1:
            # DONE find and fix problem in 2nd run
            print("\n\nDATA INTEGRITY FAILURE: There are multiple interessenbindungen in the database for organisation '{}' and parlamentarier '{}'.  Aborting.\n{}".format(
                organisation_id, parlamentarier_id, query))
            sys.exit(1)

        if result and len(result) == 1:
            (interessenbindung_id, art, funktion_im_gremium, beschreibung, beschreibung_fr, ) = result[0]
            return interessenbindung_id, art, funktion_im_gremium, beschreibung, beschreibung_fr

    return None, None, None, None, None


# get all names for a person by person_id
def get_person_names(database, person_id):
    with database.cursor() as cursor:
        person_query = """
        SELECT nachname, vorname, zweiter_vorname
        FROM person
        WHERE id = {0}
        """.format(person_id)

        cursor.execute(person_query)
        lists_of_names = [name
                          .replace('"', "")
                          .replace(".", "")
                          .split(" ")
                          if name
                          else ""
                          for name in cursor.fetchone()]

        return [name
                for namelist
                in lists_of_names
                for name
                in namelist]


# get guests for parlamentarier
# returns a 2-tuple of person_id or None
def get_guests(conn, parlamentarier_id):
    with conn.cursor() as cursor:
        guest_query = """
        SELECT person_id, funktion, id
        FROM zutrittsberechtigung
        WHERE parlamentarier_id = '{0}'
        AND (bis IS NULL OR bis > NOW())
        """.format(parlamentarier_id)

        # get additional information for loaded guest
        def extract_existing_guest(conn, guest_id, function, zutrittsberechtigung_id):
            guest = {}
            guest["names"] = get_person_names(conn, guest_id)
            guest["function"] = function
            guest["id"] = guest_id
            guest["zutrittsberechtigung_id"] = zutrittsberechtigung_id
            return guest

        cursor.execute(guest_query)
        existing_guests = cursor.fetchall()

        existing_guest_1 = extract_existing_guest(
            conn, *existing_guests[0]) if len(existing_guests) > 0 else None

        existing_guest_2 = extract_existing_guest(
            conn, *existing_guests[1]) if len(existing_guests) > 1 else None

        return (existing_guest_1, existing_guest_2)


# create query according to list and pattern (which name belongs to vorname, zweiter_vorname, and nachname)
# example: names = ["Markus", "Alexander", "Michael", "von", "Meier"]
#          pattern = "VZZNN"
# result:  vorname = "Markus"
#          zweiter_vorname = "Alexander Michael"
#          nachname = "von Meier"
def _generate_name_query(pattern, names, exact):
    vorname, zweiter_vorname, nachname = name_logic.parse_name_combination(
        names, pattern)

    vorname = escape_SQL(vorname)
    nachname = escape_SQL(nachname)
    zweiter_vorname = escape_SQL(zweiter_vorname)

    # Problem Adèle Thorens-Goumaz vs Adèle Thorens Goumaz

    # case Stefan vs Stefano Kunz (person_id 390 and 663)
    # case Streiff vs Streiff-Feller (parlamentarier_id 195)
    if exact:
        query = " AND vorname = '{}' AND (nachname = '{}' OR nachname = '{}' OR nachname = '{}')".format(
            vorname, nachname, nachname.replace('-', ' '), re.sub(r'-.*', '', nachname))
        if zweiter_vorname:
            query += " AND zweiter_vorname = '{}'".format(zweiter_vorname)
    else:
        query = " AND vorname LIKE '{}%' AND (nachname LIKE '{}%' OR nachname LIKE '{}%' OR nachname LIKE '{}%' OR nachname LIKE '%{}')".format(
            vorname, nachname, nachname.replace('-', ' '), re.sub(r'-.*', '', nachname), nachname)
        if zweiter_vorname:
            query += " AND zweiter_vorname LIKE '{}%'".format(zweiter_vorname)

    return query

# get all active parlamentarier ids as a list
# returns a list parlamentarier tuple (id, nachname, vorname)
def get_active_parlamentarier(conn):
    with conn.cursor() as cursor:
        query = """
        SELECT id, nachname, vorname
        FROM parlamentarier
        WHERE (im_rat_bis IS NULL OR im_rat_bis > NOW())
        """
        cursor.execute(query)
        parlamentarier = cursor.fetchall()

        return parlamentarier
