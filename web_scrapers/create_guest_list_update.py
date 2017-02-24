#!/usr/bin/python3
# -*- coding: utf-8 -*-


# A script that imports JSON files describing the guest lists of members
# of parliament, checks if those guests are already in the database,
# and creates an updating SQL script accordingly.

# Created by Markus Roth in February 2017 (maroth@gmail.com)
# Licenced via Affero GPL v3

import json
import MySQLdb


def run():
    database = MySQLdb.connect(user='lobbywatch', passwd="lobbywatch", host="10.0.0.2", db='lobbywatchtest')
    cursor = database.cursor()
    cursor.execute(""" SELECT * from parlamentarier""")
    parlamentarier = cursor.fetchall()
    print(parlamentarier)
    cursor.close()
    database.close()



#main method
if __name__ == "__main__":
    run()



