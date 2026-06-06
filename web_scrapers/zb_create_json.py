from collections import defaultdict
import csv
import json
import os
import re
from argparse import ArgumentParser
from datetime import datetime
from shutil import copyfile
from subprocess import call
from typing import Union

import pdf_helpers
from utils import clean_str, clean_whitespace
import opd

TITLE_CSV = "zb_title.csv"
DATA_CSV = "zb_data.csv"
CONTENT_PDF = "zb_file-content.pdf"

def split_names(names):
    return names.replace('"', "").replace(".", "").split(" ")


class Entity:
    # remove invalid characters from cell entries
    def clean_string(self, s):
        return clean_str(s).replace("\n", " ").replace(' - ', '-')


# represents a member of parliament
class MemberOfParliament(Entity):
    def __init__(self, s):
        # members of parliament are formatted as
        # "[names]"
        # this entire text is passed into the constructor
        full_name = self.clean_string(s)
        self.names = split_names(full_name)
        self.names = self.fix_names(self.names)

    def fix_names(self, names):
        if names == ['Docourt', 'Ducommun-', 'dit-Boudry', 'Martine']:
            return ['Docourt', 'Martine']
        elif names == ['Bally', 'Frehner', 'Maja']:
            return ['Bally', 'Maya']
        else:
            return names

    # <faction>/<canton>
    def set_faction_and_canton(self, s):
        faction_and_canton = s.split("/")
        self.faction = faction_and_canton[0].strip()
        # Hack for wrong faction
        if self.faction == 'los' and self.names == ['Poggia', 'Mauro']:
            self.faction = 'V'
        self.canton = faction_and_canton[1].strip()

        # TODO remove
        # # The FDP can show up as "FDP-Liberale",
        # # so we need to get only the part before the dash
        # faction_split = faction.split("-")
        # if len(faction_split) == 1:
        #     self.faction = faction
        # else:
        #     self.faction = faction_split[0]
        # Partei mapping al -> ALG (hack)
        # if self.faction == 'Al':
        #     self.faction = 'ALG'

    def append_names(self, s):
        names = self.clean_string(s)
        self.names += split_names(names)
        self.names = self.fix_names(self.names)

# represents a guest of a member of parliament
class Guest(Entity):
    def __init__(self, name_raw, function):
        name = self.clean_string(name_raw)
        name = self.fix_name_typos(name)
        self.names = split_names(name)
        self.function = self.clean_string(function)
        self.gender = 'F' if "Frau" in name_raw or "Madame" in name_raw else "M"

    # namemapping fixes, a hack
    def fix_name_typos(self, name):
        # () around calls for multi line writing, insteaf of "\" at EOL
        # https://stackoverflow.com/questions/4768941/how-to-break-a-line-of-chained-methods-in-python
        return (name
        .replace("Schürch Florence", "Schurch Florence")
        .replace("Grunder Michael", "Grunder Michel")
        .replace("Voegeli Tobias", "Vögeli Tobias")
        .replace("Durig Terence", "Durig Térence")
        .replace("Giovanoli Remco", "Giovanoli Remco André")
        .replace("Rosenkrantz Linda", "Rosenkranz Linda")
        )

    def remove_title(self, name):
        return re.sub(r'(Herr|Frau|Monsieur|Madame|Dr.|Signora?)\s+', ' ', name).strip()

    def append_function(self, s):
        self.function += " " + self.clean_string(s)

    def append_names(self, s):
        name = self.clean_string(s)
        name = self.fix_name_typos(name)
        self.names += split_names(name)

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


def log_warn_guest_too_many_lines(guest_line_count: int, current_member_of_parliament: MemberOfParliament, row: list[str]):
    print("WARN: too many guest lines for parlamentarier {}: {}".format(current_member_of_parliament.names, guest_line_count))
    print(row)

# PYTHON <= 3.9 workaround: list[dict[str, str|dict[str, str]]]:
def convert_guests(guests: dict[MemberOfParliament, list[Guest]]) -> list[dict[str, Union[str, dict[str, str]]]]:
    return [{
        "names": member_of_parliament.names,
        "faction": member_of_parliament.faction,
        "canton": member_of_parliament.canton,
        "guests": [{
            "names": guest.names,
            "function": guest.function,
            # "gender": guest.gender
            } for guest in current_guests]
        } for member_of_parliament, current_guests in guests.items()]


# is this table row a header row?
def is_header(row):
    if len(row) < 2:
        return True
    header_words = [
        "Ratsmitglied",
        "Fraktion / Kanton",
        "Partei / Kanton",
        "Membre du Conseil",
        "Parti / Canton",
        "Groupe / Canton",
        "Membro del Consiglio",
        "Partito / Cantone",
        "Gruppo / Cantone"
    ]

    return any(header_word in row_entry
               for header_word in header_words
               for row_entry in row)


# is this table row empty?
def is_empty_row(row):
    return all(len(entry.strip()) == 0 for entry in row)


def is_page_number(row):
    return row[0] == '' and row[1] == '' and (re.match(r'\d+/\d+', row[2]) != None or (len(row) > 3 and re.match(r'\d+/\d+', row[3]) != None))

# is the field empty or contains only whitespace?
def is_empty(s):
    return len(s.strip()) == 0


def is_faction_and_canton(s):
    # members of parliament are formatted as
    # <fraction>/<canton>"
    return "/" in s


# write member of parliament and guests to json file
def write_to_json(guests_data, archive_pdf_name, filename, url, creation_date, modified_date, imported_date, stand_date):
    metadata_data = {
                "metadata": {
                    "archive_pdf_name": archive_pdf_name,
                    "filename": filename,
                    "url": url,
                    "pdf_creation_date": creation_date.isoformat(' '), # , timespec is addedin Python 3.6: 'seconds'
                    "pdf_modified_date": modified_date.isoformat(' '), # , timespec is addedin Python 3.6: 'seconds'
                    "imported_date": imported_date.isoformat(' '), # , timespec is addedin Python 3.6: 'seconds'
                    "stand_date": stand_date.isoformat()
                },
                "data": guests_data
    }
    with open(filename, "wb") as json_file:
        contents = json.dumps(metadata_data, indent=2,
                              separators=(',', ': '),
                              ensure_ascii=False).encode("utf-8")
        json_file.write(contents)

# Get path of this python script
# http://stackoverflow.com/questions/4934806/how-can-i-find-scripts-directory-with-python
def get_script_path():
    return os.path.dirname(os.path.realpath(__file__))

def scrape():
    badges = opd.get_mps_and_access_badges()

    badges_per_mp = defaultdict(list)
    for badge in badges:
        badges_per_mp[badge.person_id].append(badge)

    badges_per_council = defaultdict(list)
    for person_id in badges_per_mp:
        person = badges_per_mp[person_id][0].person.data[0]
        badges_per_council[person.parliament_sector].append(
            {
                "biography_id": person_id,
                "guests": [
                    {
                        "names": badge.beneficiary_person_fullname.split(" "),
                        "function": badge.type.de if badge.type else None,
                    }
                    for badge in badges_per_mp[person_id]
                ],
            }
        )

    for council_type in ["nr", "sr"]:
        now = datetime.now()

        data = {
            "metadata": {
                "pdf_creation_date": now.isoformat(),
                "stand_date": now.isoformat(),
                "pdf_date": now.isoformat(),
                "archive_pdf_name": "",
                "url": "https://api.openparldata.ch/v1/access_badges",
            },
            "data": badges_per_council[council_type.upper()],
        }

        with open(f"zutrittsberechtigte-{council_type}.json", 'w') as file:
            json.dump(data, file)

#main method
if __name__ == "__main__":
    scrape()
