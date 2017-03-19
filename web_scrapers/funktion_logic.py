# -*- coding: utf-8 -*-

# Created by Markus Roth in February 2017 (maroth@gmail.com)
# Licenced via Affero GPL v3

import re


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
