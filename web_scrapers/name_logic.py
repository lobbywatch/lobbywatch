#!/usr/bin/python3
# -*- coding: utf-8 -*-

# Created by Markus Roth in February 2017 (maroth@gmail.com)
# Licenced via Affero GPL v3

import re

# string representation of all the names in a list of names
def fullname(guest):
    return " ".join(guest["names"])


# are these two guests equal according to their names?
def are_guests_equal(guest1, guest2):
    if guest1 is None and guest2 is None:
        return False
    if guest1 is None and guest2 is not None:
        return False
    if guest1 is not None and guest2 is None:
        return False
    names1 = guest1["names"]
    names2 = guest2["names"]
    result = are_names_equal(names1, names2)
    return result


# do we assume these two lists of names denote the same person?
def are_names_equal(names1, names2):
    # number of names that are in both name lists
    common_names = set(names1).intersection(set(names2))

    # number of names in the shorter name list
    smallest_name_count = min(len(names1), len(names2))

    # if all the names in one name list are in the other name list, the people are the same
    if len(common_names) >= smallest_name_count:
        return True

    # if the two name lists have no name at all in common, they must not be the same person
    if len(common_names) == 0:
        return False

    # if both people have three names, and two of those three are the same, they probably are the same person
    if len(names1) == 3 and len(names2) == 3 and len(common_names) == 2:
        return True

    # if both name lists have 3 or more names, and all these names start with the same substrings, we assume them to be identical
    if len(names1) > 2 and len(names1) == len(names2) and all(map(common_start, zip(names1, names2))):
        return True

    # if one of the name lists only has two names, and one of them is not in the other name list, the people are not identical
    if smallest_name_count == 2 and len(common_names) < 2:
        return False

    # last chance: if the smaller name list has at least 3 items, and at leat 2 are shared, then we accept
    if smallest_name_count > 2 and len(common_names) > 1:
        return True

    # default is not equal
    return False


# does either name start with the other?
def common_start(names):
    name1, name2 = names
    return name1.startswith(name2) or name2.startswith(name1)


# decide whether two functions of guests are different enough to warrant a mutation
def are_functions_equal(function1, function2):

    # these are simply new standards and likely have no meaning
    equivalent_functions = [("Persönlicher Mitarbeiter", "Collaborateur(rice) personnel(le)"),
                            ("Persönlicher Mitarbeiter",
                             "Persönliche/r Mitarbeiter/in"),
                            ("Persönliche Mitarbeiterin",
                             "Persönliche/r Mitarbeiter/in"),
                            ("Collaborateur personel",
                             "Collaborateur(rice) personnel(le)"),
                            ("Collaboratrice personale",
                             "Collaboratore/trice personale"),
                            ("Collaboratrice personnelle",
                             "Collaborateur(rice) personnel(le)"),
                            ("Persönliche Mitarbeiterin",
                             "Collaborateur(rice) personnel(le)"),
                            ("Persönliche Mitarbeiterin",
                             "Collaboratore/trice personale"),
                            ("Gast", "Invité(e)"),
                            ("Persönlicher Mitarbeiter", "Collaborateur(rice) personnel(le)")]

    if function1 is None and function2 is not None:
        return False
    if function1 is not None and function2 is None:
        return False
    if function1 is None and function2 is None:
        return True

    if (function1, function2) in equivalent_functions:
        return True

    return normalize_function(function1) == normalize_function(function2)


# Often some small details in function naming can be ignored
def normalize_function(function):
    stripped = function.lower().replace("-", "").replace(" ", "")
    # remove anything in braces ()
    return re.sub(r'\([^)]*\)', '', stripped).strip()
