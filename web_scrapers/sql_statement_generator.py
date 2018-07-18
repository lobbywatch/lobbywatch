# -*- coding: utf-8 -*-

from datetime import datetime
import os

user = os.getenv('USER', 'import')

# create new zutrittsberechtigung for a perlamentarier
def insert_zutrittsberechtigung(parlamentarier_id, person_id, funktion, date):
    query = "INSERT INTO `zutrittsberechtigung` (`parlamentarier_id`, `person_id`, `funktion`, `von`, `notizen`, `created_visa`, `created_date`, `updated_visa`, `updated_date`, `updated_by_import`) VALUES ({0}, {1}, '{2}', STR_TO_DATE('{3}', '%d.%m.%Y'), '{4}', '{5}', STR_TO_DATE('{6}', '%d.%m.%Y %T'), '{7}', STR_TO_DATE('{8}', '%d.%m.%Y %T'), STR_TO_DATE('{8}', '%d.%m.%Y %T'));\n".format(
            parlamentarier_id,
            person_id if person_id is not None else "(SELECT LAST_INSERT_ID())",
            funktion,
            _date_as_sql_string(date),
            "{0}/import/{1}: Erzeugt".format(_date_as_sql_string(date), user),
            "import",
            _datetime_as_sql_string(date),
            "import",
            _datetime_as_sql_string(date)
    )

    return query


# update the function of an existing zutrittsberechtigung
def update_function_of_zutrittsberechtigung(zutrittsberechtigung_id, function, date):
    query = "UPDATE `zutrittsberechtigung` SET `funktion` = '{0}', `notizen` = CONCAT_WS('{1}', notizen), `updated_visa` = '{2}', `updated_date` = STR_TO_DATE('{3}', '%d.%m.%Y %T'), `updated_by_import` = STR_TO_DATE('{3}', '%d.%m.%Y %T') WHERE `id` = {4};\n".format(
        _escape_string(function),
        "{0}/import/{1}: Funktion geändert\\n\\n".format(
            _date_as_sql_string(date), user),
        "import",
        _datetime_as_sql_string(date),
        zutrittsberechtigung_id
    )

    return query


# end a zutrittsberechtigung
def end_zutrittsberechtigung(zutrittsberechtigung_id, date):
    query = "UPDATE `zutrittsberechtigung` SET `bis` = STR_TO_DATE('{0}', '%d.%m.%Y'), `notizen` = CONCAT_WS('{1}', notizen), `updated_visa` = '{2}', `updated_date` = STR_TO_DATE('{3}', '%d.%m.%Y %T'), `updated_by_import` = STR_TO_DATE('{3}', '%d.%m.%Y %T') WHERE `id` = {4};\n".format(
        _date_as_sql_string(date),
        "{0}/import/{1}: Bis-Datum gesetzt\\n\\n".format(
            _date_as_sql_string(date), user),
        "import",
        _datetime_as_sql_string(date),
        zutrittsberechtigung_id
    )

    return query


# insert a new person
def insert_person(guest, date):
    query = "INSERT INTO `person` (`nachname`, `vorname`, `zweiter_vorname`, `beschreibung_de`, `created_visa`, `created_date`, `updated_visa`, `updated_date`, `updated_by_import`, `notizen`) VALUES ('{0}', '{1}', '{2}', '{3}', '{4}', STR_TO_DATE('{5}', '%d.%m.%Y %T'), '{6}', STR_TO_DATE('{7}', '%d.%m.%Y %T'), STR_TO_DATE('{7}', '%d.%m.%Y %T'), '{8}');\n".format(
            _escape_string(guest["names"][0]),
            _escape_string(guest["names"][1]),
            _escape_string(guest["names"][2] if len(
                guest["names"]) > 2 else ""),
            _escape_string(guest["function"]),
            "import",
            _datetime_as_sql_string(date),
            "import",
            _datetime_as_sql_string(date),
            "{0}/import/{1}: Erzeugt".format(_date_as_sql_string(date), user)
    )

    return query


# insert a new organisation with the characteristics of a parlamentarische gruppe
def insert_parlamentarische_gruppe(name_de, sekretariat, homepage, date):
    query = """INSERT INTO `organisation` (`name_de`, `sekretariat`,`homepage`,`land_id`, `rechtsform`, `typ`, `vernehmlassung`, `created_visa`, `created_date`, `updated_visa`, `updated_by_import`, `notizen`) VALUES ('{}', '{}', '{}', {}, '{}', '{}', '{}', '{}', STR_TO_DATE('{}', '%d.%m.%Y %T'), '{}', STR_TO_DATE('{}', '%d.%m.%Y %T'), '{}');
SET @last_parlamentarische_gruppe = LAST_INSERT_ID();
""".format(
            _escape_string(name_de),
            _escape_string(sekretariat),
            _escape_string(homepage),
            191,  # Schweiz
            "Parlamentarische Gruppe",
            "EinzelOrganisation,dezidierteLobby",
            "nie",
            "import",
            _datetime_as_sql_string(date),
            "import",
            _datetime_as_sql_string(date),
            "{0}/import/{1}: Erzeugt".format(_date_as_sql_string(date), user)
    )

    return query


def update_sekretariat_organisation(organisation_id, sekretariat, batch_time):
    query = "UPDATE `organisation` SET `sekretariat` = '{0}', `notizen` = CONCAT_WS('{1}', notizen), `updated_visa` = '{2}', `updated_date` = STR_TO_DATE('{3}', '%d.%m.%Y %T'), `updated_by_import` = STR_TO_DATE('{3}', '%d.%m.%Y %T') WHERE `id` = {4};\n".format(
        _escape_string(sekretariat),
        "{0}/import/{1}: Sekretariat geändert\\n\\n".format(
            _date_as_sql_string(batch_time), user),
        "import",
        _datetime_as_sql_string(batch_time),
        organisation_id 
    )

    return query


def update_homepage_organisation(organisation_id, homepage, batch_time):
    query = "UPDATE `organisation` SET `homepage` = '{0}', `notizen` = CONCAT_WS('{1}', notizen), `updated_visa` = '{2}', `updated_date` = STR_TO_DATE('{3}', '%d.%m.%Y %T'), `updated_by_import` = STR_TO_DATE('{3}', '%d.%m.%Y %T') WHERE `id` = {4};\n".format(
        _escape_string(homepage),
        "{0}/import/{1}: Homepage geändert\\n\\n".format(
            _date_as_sql_string(batch_time), user),
        "import",
        _datetime_as_sql_string(batch_time),
        organisation_id 
    )

    return query

def insert_interessenbindung_parlamentarische_gruppe(parlamentarier_id,
                                                     organisation_id, stichdatum, beschreibung, date):

    query = "INSERT INTO `interessenbindung` (`parlamentarier_id`, `organisation_id`, `art`, `funktion_im_gremium`, `beschreibung`,`deklarationstyp`, `status`, `behoerden_vertreter`, `von`, `created_visa`,`created_date`, `updated_visa`, `updated_by_import`, `notizen`) VALUES ({}, {}, '{}', '{}', '{}','{}', '{}', '{}', STR_TO_DATE('{}', '%d.%m.%Y'),'{}',STR_TO_DATE('{}', '%d.%m.%Y %T'), '{}', STR_TO_DATE('{}', '%d.%m.%Y %T'), '{}');\n".format(
        parlamentarier_id,
        organisation_id,
        "vorstand",
        "praesident",
        beschreibung,
        "deklarationspflichtig",
        "deklariert",
        "N",
        _date_as_sql_string(stichdatum),
        "import",
        _datetime_as_sql_string(date),
        "import",
        _datetime_as_sql_string(date),
        "{0}/import/{1}: Erzeugt".format(_date_as_sql_string(date), user))

    return query


def end_interessenbindung(interessenbindung_id, stichdatum, batch_time):
    query = "UPDATE `interessenbindung` SET `bis` = STR_TO_DATE('{0}', '%d.%m.%Y'), `notizen` = CONCAT_WS('{1}', notizen), `updated_visa` = '{2}', `updated_date` = STR_TO_DATE('{3}', '%d.%m.%Y %T'), `updated_by_import` = STR_TO_DATE('{3}', '%d.%m.%Y %T') WHERE `id` = {4};\n".format(
        _date_as_sql_string(stichdatum),
        "{0}/import/{1}: Bis-Datum gesetzt\\n\\n".format(
            _date_as_sql_string(batch_time), user),
        "import",
        _datetime_as_sql_string(batch_time),
       interessenbindung_id 
    )

    return query


# simple esape function for input strings
# real escaping not needed as we trust
# input from parlament.ch to not have SQL injection attacks
def _escape_string(string):
    result = string.replace("'", "''")
    return result


# the current date formatted as a string MySQL can understand
def _date_as_sql_string(date):
    return "{0:02d}.{1:02d}.{2}".format(date.day, date.month, date.year)


def _datetime_as_sql_string(date):
    return "{0:02d}.{1:02d}.{2} {3:02d}:{4:02d}:{5:02d}".format(date.day, date.month, date.year, date.hour, date.minute, date.second)
