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
def insert_zutrittsberechtigung(parlamentarier_id, person_id, funktion):
    query = """INSERT INTO `csvimsne_lobbywatch`.`zutrittsberechtigung`
    (`parlamentarier_id`, `person_id`, `funktion`, `von`, `notizen`, `created_visa`)
    VALUES ({0}, {1}, '{2}', '{3}', '{4}', '{5}'); """.format(
        parlamentarier_id,
        person_id if person_id is not None else "(SELECT LAST_INSERT_ID())",
        funktion,
        current_date_as_sql_string(),
        "erzeugt von import",
        "import")
    return query


# update the function of an existing zutrittsberechtigung
def update_function_of_zutrittsberechtigung(zutrittsberechtigung_id, function):
    query = """ UPDATE `csvimsne_lobbywatch`.`zutrittsberechtigung`
    SET `funktion` = '{0}', `notizen` = CONCAT_WS(notizen, '{1}'), `updated_visa` = 'import', `updated_date` = '{2}'
    WHERE `id` = {3}; """.format(
        escape_string(function),
        "funktion geändert durch import",
        current_date_as_sql_string(),
        zutrittsberechtigung_id)
    return query


# end a zutrittsberechtigung
def end_zutrittsberechtigung(zutrittsberechtigung_id):
    query = """ UPDATE `csvimsne_lobbywatch`.`zutrittsberechtigung`
    SET `bis` = '{0}', `notizen` = CONCAT_WS(notizen, '{1}'), `updated_visa` = 'import', `updated_date` = '{2}'
    WHERE `id` = {3}; """.format(
        current_date_as_sql_string(),
        "bis-datum gesetzt durch import",
        current_date_as_sql_string(),
        zutrittsberechtigung_id)
    return query


# insert a new person
def insert_person(guest):
    query = """INSERT INTO `csvimsne_lobbywatch`.`person`
    (`nachname`, `vorname`, `zweiter_vorname`, `beschreibung_de`, `created_visa`)
    VALUES ('{0}', '{1}', '{2}', '{3}', '{4}');""".format(
        escape_string(guest["names"][0]),
        escape_string(guest["names"][1]),
        escape_string(guest["names"][2] if len(guest["names"]) > 2 else ""),
        escape_string(guest["function"]),
        "import")
    return query
    

# simple esape function for input strings
# real escaping not needed as we trust
# input from parlament.ch to not have SQL injection attacks
def escape_string(string):
    result = string.replace("'", "''")
    return result


# the current date formatted as a string MySQL can understand
def current_date_as_sql_string():
    return "{0}-{1}-{2}".format(datetime.now().year, datetime.now().month, datetime.now().day)


