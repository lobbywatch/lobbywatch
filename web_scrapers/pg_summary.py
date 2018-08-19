# -*- coding: utf-8 -*-

class Summary:
    def __init__(self):
        self.rows = {}
        self.websites_added = 0
        self.websites_changed = 0
        self.sekretariats_added = 0
        self.sekretariats_changed = 0

    def sekretariat_added(self):
        self.sekretariats_added += 1

    def sekretariat_changed(self):
        self.sekretariats_changed += 1

    def website_added(self):
        self.websites_added += 1

    def website_changed(self):
        self.websites_changed += 1

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

    def sekretariats_added_count(self):
        return self.sekretariats_added

    def sekretariats_changed_count(self):
        return self.sekretariats_changed

    def websites_added_count(self):
        return self.websites_added

    def websites_changed_count(self):
        return self.websites_changed


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

    def clean_gruppen_name(self, str):
        return str.replace("Parlamentarische Gruppe für ", "") \
            .replace("Parlamentarische Gruppe ", "") \
            .replace("Parlamentarische Freundschaftsgruppe ", "") \
            .replace("Parlamentarische Kerngruppe ", "") \
            .replace("Lateinische parlamentarische Gruppe", "Lateinische Gruppe") \
            .replace("Parlamentarische ", "")

    def write(self, index):
        changed_symbol = "≠" if self.has_changed() else "="
        gruppen = []
        for gruppe_name, gruppe_id in self.gruppen_beendet:
            gruppen.append("- {} ({}) ".format(self.clean_gruppen_name(gruppe_name), gruppe_id))
        for gruppe_name, gruppe_id in self.gruppen_neu:
            gruppen.append("+ {} ({}) ".format(self.clean_gruppen_name(gruppe_name), gruppe_id))
        for gruppe_name, gruppe_id in self.gruppen_unveraendert:
            gruppen.append("= {} ({}) ".format(self.clean_gruppen_name(gruppe_name), gruppe_id))
        lines = []
        lines.append("{:3d} | {} | {} | {} ‖ {}".format(
                    index,
                    changed_symbol,
                    self.parlamentarier_name[:14].ljust(14),
                    str(self.parlamentarier_id).rjust(3),
                    ""
                    ))
        for i, gruppe in enumerate(gruppen):
            if i == 0:
                lines[0] += gruppe
            else:
                lines.append("{:3}   {}   {}   {} ‖ {}".format(
                    "",
                    " ",
                    "".ljust(14),
                    "".rjust(3),
                    gruppe
                    ))
        return "\n".join(lines)
