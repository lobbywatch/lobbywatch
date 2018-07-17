# -*- coding: utf-8 -*-

import re
import literals

# decide whether two functions of guests are different enough to warrant a mutation
def are_functions_equal(function1, function2):

    if function1 is None and function2 is not None:
        return False
    if function1 is not None and function2 is None:
        return False
    if function1 is None and function2 is None:
        return True

    if (function1, function2) in literals.equivalent_functions:
        return True

    return normalize_function(function1) == normalize_function(function2)


# Often some small details in function naming can be ignored
def normalize_function(function):
    stripped = function.lower().replace("-", "").replace(" ", "")

    # remove anything in braces ()
    return re.sub(r'\([^)]*\)', '', stripped).strip()
