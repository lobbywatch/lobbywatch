#!/usr/bin/python3
# -*- coding: utf-8 -*-


# A script that imports PDFs that are on the site of the government that
# indicate which member of the two Swiss parliaments have which guests
# on their guest list.

# Since the information is only provided as PDF documents that are not easily
# machine-readable, this script translates the PDF into a JSON document hat can
# then be used for further automation.

# Created by Markus Roth in February 2017 (maroth@gmail.com)
# Licenced via Affero GPL v3

import requests
import csv
import json
import os
from subprocess import call
from collections import defaultdict


class Entity:
    # remove invalid characters from cell entries
    def clean_string(self, s):
        return s.replace("\n", " ")


# represents a member of parliament
class MemberOfParliament(Entity):
    def __init__(self, description):
        # members of parliament are formatted as
        # "<lastname> <firstname> [<second_firstname>], <party>/<canton>"
        # this entire description is passed into the constructor
        name_and_party = self.clean_string(description).split(",")
        names = name_and_party[0].split(" ")
        self.last_name = names[0]
        self.first_name = names[1]
        if len(names) > 2:
            self.second_first_name = names[2]
        else:
            self.second_first_name = ""
        party_and_canton = name_and_party[1].split("/")
        self.party = party_and_canton[0].strip()
        self.canton = party_and_canton[1]


# represents a guest of a member of parliament
class Guest(Entity):
    def __init__(self, name, function):
        self.name = self.clean_string(name)
        self.function = self.clean_string(function)


# read file from url while respecting redirects and accepting cookies
# this is necessary because simply using a direct HTTP connection
# doesn't work aon admin.ch, it sets a cookie and then redirects
# you to some other URL
def get_pdf_from_admin_ch(url, filename):
    initial_reponse = requests.get(url)
    response_with_cookie = requests.get(url, cookies=r.cookies)
    with open(filename, "wb") as target_file:
        target_file.write(response_with_cookie.content)


# create a guest object from the passed csv row
# taking name and function from the passed indexes of the row
# if the row is not long enough, the function of the
# guest is missing
def create_guest(row, name_index, function_index):
    # guest has no name
    if (is_empty(row[name_index])):
        return None

    # guest has name and function
    if len(row) > function_index:
        return Guest(row[name_index], row[function_index])

    # guest has only a name, but no function
    else:
        return Guest(row[name_index], "")


# read the csv generated by tabula and get rid of empty rows and headers
def cleanup_file(filename):
    guests = {}
    current_member_of_parliament = None
    # in general, if row[0] is not empty, we are at a new member of
    # parliament. all guests from that row and the following rows belong
    # to that member of parliament, until a new name shows up in row[0].

    # but: sometimes tabula gets mixed up and puts the guest in row[0].
    # So we need to check if the first cell is a member of parliament
    # manually so we don't miss anything.
    for row in csv.reader(open(filename, encoding="utf-8")):
        if not is_header(row) and not is_empty_row(row):
            if is_empty(row[0]):
                # row[0] is empty
                # guest name is in row[1]
                # and guest function is in row[2]
                # for member of parliament defined in a previous row
                guest = create_guest(row, 1, 2)
                if guest is not None:
                    guests[current_member_of_parliament].append(guest)

            else:
                if is_member_of_parliament(row[0]):
                    # row[0] is member of parliament,
                    # row[1] and row[2] are guest name and function
                    current_member_of_parliament = MemberOfParliament(row[0])
                    guests[current_member_of_parliament] = []
                    guest = create_guest(row, 1, 2)
                    if guest is not None:
                        guests[current_member_of_parliament].append(guest)

                else:
                    # tabula messed up, so row[0] is the guest name
                    # and row[1] is the guest function
                    guest = create_guest(row, 0, 1)
                    if guest is not None:
                        guests[current_member_of_parliament].append(guest)

    # print counts for sanity check
    print("{} members of parliament\n"
          "{} guests total\n"
          "{} members with 0 guests\n"
          "{} members with 1 guest\n"
          "{} members with 2 guests".format(
              len(guests),
              sum(len(guest) for guest in guests.values()),
              sum(1 for guest in guests.values() if len(guest) == 0),
              sum(1 for guest in guests.values() if len(guest) == 1),
              sum(1 for guest in guests.values() if len(guest) == 2)))
    return guests


# is this table row a header row?
def is_header(row):
    if len(row) < 2:
        return True
    header_words = ["Partito", "Cantone", "Consigliere", "Fonction",
                    "Conseiller", "Funzionenktion", "Funktion",
                    "Funzione", "Name", "Partei / Kanton", "Funzionenktion,",
                    "Conseiller/,", "Parti / Canton"]

    return any(header_word in row_entry
               for header_word in header_words
               for row_entry in row)


# is this table row empty?
def is_empty_row(row):
    return all(len(entry.strip()) == 0 for entry in row)


# is the field empty or contains only whitespace?
def is_empty(s):
    return len(s.strip()) == 0


def is_member_of_parliament(s):
    # members of parliament are formatted as
    # "<lastname <firstname>, <party>/<canton>"
    return "," in s and "/" in s


# write member of parliament and guests to json file
def write_to_json(guests, filename):
    data = [{
            "first_name": member_of_parliament.first_name,
            "last_name": member_of_parliament.last_name,
            "second_first_name": member_of_parliament.second_first_name,
            "party": member_of_parliament.party,
            "canton": member_of_parliament.canton,
            "guests": [{
                "name": guest.name,
                "function": guest.function
                } for guest in current_guests]
            } for member_of_parliament, current_guests in guests.items()]

    with open(filename, "wb") as json_file:
        contents = json.dumps(data, indent=4,
                              separators=(',', ': '),
                              ensure_ascii=False).encode("utf-8")
        json_file.write(contents)


# download a pdf containing the guest lists of members of parlament in a table
# then parse the file into json and save the json files to disk
def scrape_pdf(url, filename):
    print("\ndownloading " + url)
    get_pdf_from_admin_ch(url, "file.pdf")

    print("removing first page of PDF...")
    call(["pdftk", "file.pdf", "cat", "2-end", "output", "file-stripped.pdf"])

    print("parsing PDF...")
    call(["java", "-jar", "tabula-0.9.2-jar-with-dependencies.jar",
         "file-stripped.pdf", "--pages", "all", "-o", "data.csv"])

    print("cleaning up parsed data...")
    guests = cleanup_file("data.csv")

    print("writing " + filename + "...")
    write_to_json(guests, filename)

    print("cleaning up...")
    os.remove("file.pdf")
    os.remove("file-stripped.pdf")
    os.remove("data.csv")

    print("done!")


# scrape the nationalrat and ständerat guest lists and write them to
# structured JSON files
def scrape():
    root = "https://www.parlament.ch/centers/documents/de/"

    #scrape nationalrat
    scrape_pdf(root +
               "zutrittsberechtigte-nr.pdf",
               "zutrittsberechtigte-nr.json")

    #scrape ständerat
    scrape_pdf(root +
               "zutrittsberechtigte-sr.pdf",
               "zutrittsberechtigte-sr.json")


#main method
if __name__ == "__main__":
    scrape()