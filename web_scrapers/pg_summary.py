# -*- coding: utf-8 -*-

from collections import defaultdict

class Summary:
    def __init__(self):
        self.rows = {}

    def add_row(self, row):
        self.rows[row.parlamentarier_id] = row

    def get_row(self, parlamentarier_id):
        if not parlamentarier_id in self.rows:
            self.rows[parlamentarier_id] = SummaryRow(parlamentarier_id)

        return self.rows[parlamentarier_id]

    def get_rows(self):
        if not self.rows:
            return []
        rows_sorted = list(self.rows.values())
        rows_sorted.sort(key=lambda row : row.parlamentarier_name)
        return rows_sorted

    def write_header(self):
        print(" No |   | Parlamentarier | ID  ‖ Parlamentarische Gruppen")

    def equal_count(self):
        return len(self.rows) - self.changed_count()

    def changed_count(self):
        return sum([1 for row in self.rows.values() if row.has_changed()])

    def added_count(self):
        return sum(len(row.gruppen_neu) for row in self.rows.values())

    def removed_count(self):
        return sum(len(row.gruppen_beendet) for row in self.rows.values())

    def no_groups_count(self):
        return sum(1 for row in self.rows.values() if len(row.gruppen_neu) == 0 and len(row.gruppen_unveraendert) == 0)


class SummaryRow:
    def __init__(self, parlamentarier_id):
        self.gruppen_neu = []
        self.gruppen_unveraendert = []
        self.gruppen_beendet = []
        self.parlamentarier_id = parlamentarier_id
        self.parlamentarier_name = ""

    def neue_gruppe(self, gruppe_id, gruppe_name):
        self.gruppen_neu.append((gruppe_name, gruppe_id))

    def gruppe_beendet(self, gruppe_id, gruppe_name):
        self.gruppen_beendet.append((gruppe_name, gruppe_id))

    def gruppe_unveraendert(self, gruppe_id, gruppe_name):
        self.gruppen_unveraendert.append((gruppe_name, gruppe_id))

    def has_changed(self):
        return len(self.gruppen_neu) > 0 or len(self.gruppen_beendet) > 0



    def write(self, index):
        changed_symbol = "="
        gruppen = ""
        for gruppe_name, gruppe_id in self.gruppen_unveraendert:
            gruppen += "= {} ({}) ".format(gruppe_name, gruppe_id)
        for gruppe_name, gruppe_id in self.gruppen_beendet:
            gruppen += "- {} ({}) ".format(gruppe_name, gruppe_id)
        for gruppe_name, gruppe_id in self.gruppen_neu:
            gruppen += "+ {} ({}) ".format(gruppe_name, gruppe_id)

        return "{:3d} | {} | {} | {} | {}".format(
            index, 
            changed_symbol, 
            self.parlamentarier_name[:14].ljust(14),
            str(self.parlamentarier_id).rjust(3),
            gruppen
            )