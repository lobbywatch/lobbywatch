# -*- coding: utf-8 -*-

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

    # if either last name contains a dash, and the first names are equal and one last name starts with the other, the names are equal
    # this happens when a person marries, for example
    if len(names1) == 2 and len(names2) == 2 and ("-" in names1[0] or "-" in names2[0]) and common_start((names1[0], names2[0])) and names1[1] == names2[1]:
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


# create vorname, zweiter_vorname and name from a untyped list of names according to a pattern
def parse_name_combination(names, pattern):
    vorname = ""
    zweiter_vorname = ""
    nachname = ""
    for name, value in zip(names, pattern):
        if value == "V":
            vorname += " " + name
        if value == "Z":
            zweiter_vorname += " " + name
        if value == "N":
            nachname += " " + name
        if value == "S":
            vorname += " %" + name

    return vorname.strip(), zweiter_vorname.strip(), nachname.strip()
