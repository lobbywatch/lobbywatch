# -*- coding: utf-8 -*-

# Created by Markus Roth in February 2017 (maroth@gmail.com)
# Licenced via Affero GPL v3

from datetime import datetime


# create new zutrittsberechtigung for a perlamentarier
# linking to a person and having a function
# since the script is to be executed ansynchronously, the id
# of the person might not be known yet, so LAST_INSERT_ID() is used
# note that this means the statements need to be executed in
# exactly the right order!
def insert_zutrittsberechtigung(parlamentarier_id, person_id, funktion, date):
    query = """INSERT INTO `zutrittsberechtigung`
    (`parlamentarier_id`, `person_id`, `funktion`, `von`, `notizen`, `created_visa`, `created_date`, `updated_visa`, `updated_date`)
    VALUES ({0}, {1}, '{2}', STR_TO_DATE('{3}', '%d.%m.%Y'), '{4}', '{5}', STR_TO_DATE('{6}', '%d.%m.%Y %T'), '{7}', STR_TO_DATE('{8}', '%d.%m.%Y %T')); """.format(
        parlamentarier_id,
        person_id if person_id is not None else "(SELECT LAST_INSERT_ID())",
        funktion,
        date_as_sql_string(date),
        "{0}: erzeugt durch import".format(date_as_sql_string(date)),
        "import",
        datetime_as_sql_string(date),
        "import",
        datetime_as_sql_string(date))
    return query


# update the function of an existing zutrittsberechtigung
def update_function_of_zutrittsberechtigung(zutrittsberechtigung_id, function, date):
    query = """UPDATE `zutrittsberechtigung`
    SET `funktion` = '{0}', `notizen` = CONCAT_WS(notizen, '{1}'), `updated_visa` = '{2}', `updated_date` = STR_TO_DATE('{3}', '%d.%m.%Y %T') 
    WHERE `id` = {4}; """.format(
        escape_string(function),
        "\\n\\n{0}: funktion geändert durch import".format(date_as_sql_string(date)),
        "import",
        datetime_as_sql_string(date),
        zutrittsberechtigung_id)
    return query


# end a zutrittsberechtigung
def end_zutrittsberechtigung(zutrittsberechtigung_id, date):
    query = """UPDATE `zutrittsberechtigung`
    SET `bis` = STR_TO_DATE('{0}', '%d.%m.%Y'), `notizen` = CONCAT_WS(notizen, '{1}'), `updated_visa` = '{2}', `updated_date` = STR_TO_DATE('{3}', '%d.%m.%Y %T')
    WHERE `id` = {4}; """.format(
        date_as_sql_string(date),
        "\\n\\n {0}: bis-datum gesetzt durch import".format(date_as_sql_string(date)),
        "import",
        datetime_as_sql_string(date),
        zutrittsberechtigung_id)
    return query


# insert a new person
def insert_person(guest, date):
    query = """INSERT INTO `person`
    (`nachname`, `vorname`, `zweiter_vorname`, `beschreibung_de`, `created_visa`, `created_date`, `updated_visa`, `updated_date`)
    VALUES ('{0}', '{1}', '{2}', '{3}', '{4}', STR_TO_DATE('{5}', '%d.%m.%Y %T'), '{6}', STR_TO_DATE('{7}', '%d.%m.%Y %T'));""".format(
        escape_string(guest["names"][0]),
        escape_string(guest["names"][1]),
        escape_string(guest["names"][2] if len(guest["names"]) > 2 else ""),
        escape_string(guest["function"]),
        "import",
        datetime_as_sql_string(date),
        "import",
        datetime_as_sql_string(date))
    return query
    

# simple esape function for input strings
# real escaping not needed as we trust
# input from parlament.ch to not have SQL injection attacks
def escape_string(string):
    result = string.replace("'", "''")
    return result


# the current date formatted as a string MySQL can understand
def date_as_sql_string(date):
    return "{0:02d}.{1:02d}.{2}".format(date.day, date.month, date.year)

def datetime_as_sql_string(date):
    return "{0:02d}.{1:02d}.{2} {3}:{4:02d}:{5:02d}".format(date.day, date.month, date.year, date.hour, date.minute, date.second)
