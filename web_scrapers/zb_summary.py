# -*- coding: utf-8 -*-

from datetime import date, datetime

class SummaryRow:
    def __init__(self, parlamentarier, count, parlamentarier_db_dict):
        self.number = str(count)
        self.symbol1 = " "
        self.symbol2 = " "
        self.parlamentarier_name = _display_name(parlamentarier["names"])
        self.parlamentarier_id = str(parlamentarier["id"])
        self.gast1_name = ""
        self.gast1_id = ""
        self.gast1_changes = ""
        self.gast2_name = ""
        self.gast2_id = ""
        self.gast2_changes = ""
        self.gast1_name_old = ""
        self.gast1_id_old = ""
        self.gast2_name_old = ""
        self.gast2_id_old = ""
        self.parlamentarier_db_dict = parlamentarier_db_dict

    def set_guest_1(self, person):
        if person:
            self.gast1_name = _display_name(person["names"])
            if "id" in person:
                self.gast1_id = str(person["id"])
                self.symbol1 = "="

    def set_removed_guest_1(self, person):
        if person:
            self.gast1_name_old = _display_name(person["names"])
            self.gast1_id_old = str(person["id"])
            self.symbol1 = "-"

    def set_new_guest_1(self, person):
        self.set_guest_1(person)
        if person:
            self.symbol1 = "+"

    def set_guest_2(self, person):
        if person:
            self.gast2_name = _display_name(person["names"])
            if "id" in person:
                self.gast2_id = str(person["id"])
                self.symbol2 = "="

    def set_removed_guest_2(self, person):
        if person:
            self.gast2_name_old = _display_name(person["names"])
            self.gast2_id_old = str(person["id"])
            self.symbol2 = "-"

    def set_new_guest_2(self, person):
        self.set_guest_2(person)
        if person:
            self.symbol2 = "+"

    def set_guest_1_changes(self, changes):
        self.gast1_changes = changes
        self.symbol1 = "≠"

    def set_guest_2_changes(self, changes):
        self.gast2_changes = changes
        self.symbol2 = "≠"

    def update_symbols(self):
        if self.gast1_name_old != "":
            if self.gast1_name != "":
                self.symbol1 = "±"

        if self.gast2_name_old != "":
            if self.gast2_name != "":
                self.symbol2 = "±"

    def has_changed(self):
        return not ((self.symbol1 == "=" or self.symbol1 == " ") and (self.symbol2 == "=" or self.symbol2 == " "))

    def get_symbol1(self):
        return self.symbol1

    def get_symbol2(self):
        return self.symbol2

    def is_parlamentarier_active(self):
        return self.parlamentarier_db_dict['im_rat_bis'] == None or self.parlamentarier_db_dict['im_rat_bis'] > date.today()

    def write(self, row_nr):
        self.update_symbols()
        mark = ' ' if self.is_parlamentarier_active() else '~'
        return "{:3d}|{}{}{}{}|{}{}{}|{}{}{}‖ {} | {} | {} ‖ {} | {} | {} ‖ {} | {} | {} | {} |".format(
            row_nr,  # self.number.ljust(3),
            mark,
            self.symbol1,
            self.symbol2,
            mark,
            mark,
            self.parlamentarier_name[:14].ljust(14),
            mark,
            mark,
            self.parlamentarier_id.rjust(3),
            mark,
            self.gast1_name[:12].ljust(12),
            self.gast1_id.rjust(3),
            self.gast1_changes.ljust(13),
            self.gast2_name[:12].ljust(12),
            self.gast2_id.rjust(3),
            self.gast2_changes.ljust(13),
            self.gast1_name_old[:12].ljust(12),
            self.gast1_id_old.rjust(3),
            self.gast2_name_old[:12].ljust(12),
            self.gast2_id_old.rjust(3))


def write_header():
    return "No |    | Parlamentarier | ID  ‖ Gast 1       | ID  | Changes       ‖ Gast 2       | ID  | Changes       ‖ -Gast 1      | ID  | -Gast 2      | ID  |"


def _display_name(names):
    name = " ".join(names)
    return name
