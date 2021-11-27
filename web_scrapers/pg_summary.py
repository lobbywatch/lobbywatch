class Summary:
    def __init__(self):
        self.parlamentarier_rows = {}
        self.gruppe_rows_by_name = {}
        self.gruppe_rows_by_id = {}
        self.organisationen_added = 0
        self.organisationen_removed = 0
        self.websites_added = 0
        self.websites_changed = 0
        self.aliases_added = 0
        self.aliases_changed = 0
        self.beschreibungen_added = 0
        self.beschreibungen_changed = 0
        self.sekretariats_added = 0
        self.sekretariats_changed = 0
        self.addresses_added = 0
        self.addresses_changed = 0
        self.names_added = 0
        self.names_changed = 0

    def organisation_added(self, name_de, name_fr, name_it, beschreibung, sekretariat, adresse_str, adresse_zusatz, adresse_plz, adresse_ort, homepage, alias):
        self.organisationen_added += 1
        gruppe = self.get_gruppe(name_de, 'neu')
        gruppe.add_change('name_de', None, name_de)
        gruppe.add_change('name_fr', None, name_fr)
        gruppe.add_change('name_it', None, name_it)
        gruppe.add_change('beschreibung', None, beschreibung)
        gruppe.add_change('sekretariat', None, sekretariat)
        gruppe.add_change('adresse_str', None, adresse_str)
        gruppe.add_change('adresse_zusatz', None, adresse_zusatz)
        gruppe.add_change('adresse_plz', None, adresse_plz)
        gruppe.add_change('adresse_ort', None, adresse_ort)
        gruppe.add_change('homepage', None, homepage)
        gruppe.add_change('alias', None, alias)

    def organisation_removed(self):
        self.organisationen_removed += 1

    def sekretariat_added(self, gruppe_name, gruppe_id, neu):
        self.sekretariats_added += 1

        gruppe = self.get_gruppe(gruppe_name, gruppe_id)
        gruppe.add_change('sekretariat', None, neu)

    def sekretariat_changed(self, gruppe_name, gruppe_id, alt, neu):
        self.sekretariats_changed += 1

        gruppe = self.get_gruppe(gruppe_name, gruppe_id)
        gruppe.add_change('sekretariat', alt, neu)

    def adresse_added(self, gruppe_name, gruppe_id, neu):
        self.addresses_added += 1

        gruppe = self.get_gruppe(gruppe_name, gruppe_id)
        gruppe.add_change('adresse', None, neu)

    def adresse_changed(self, gruppe_name, gruppe_id, alt, neu):
        self.addresses_changed += 1

        gruppe = self.get_gruppe(gruppe_name, gruppe_id)
        gruppe.add_change('adresse', alt, neu)

    def website_added(self, gruppe_name, gruppe_id, neu):
        self.websites_added += 1

        gruppe = self.get_gruppe(gruppe_name, gruppe_id)
        gruppe.add_change('web', None, neu)

    def website_changed(self, gruppe_name, gruppe_id, alt, neu):
        self.websites_changed += 1

        gruppe = self.get_gruppe(gruppe_name, gruppe_id)
        gruppe.add_change('web', alt, neu)

    def alias_added(self, gruppe_name, gruppe_id, neu):
        self.aliases_added += 1

        gruppe = self.get_gruppe(gruppe_name, gruppe_id)
        gruppe.add_change('alias', None, neu)

    def alias_changed(self, gruppe_name, gruppe_id, alt, neu):
        self.aliases_changed += 1

        gruppe = self.get_gruppe(gruppe_name, gruppe_id)
        gruppe.add_change('alias', alt, neu)

    def beschreibung_added(self, gruppe_name, gruppe_id, neu):
        self.beschreibungen_added += 1

        gruppe = self.get_gruppe(gruppe_name, gruppe_id)
        gruppe.add_change('beschreibung', None, neu)

    def beschreibung_changed(self, gruppe_name, gruppe_id, alt, neu):
        self.beschreibungen_changed += 1

        gruppe = self.get_gruppe(gruppe_name, gruppe_id)
        gruppe.add_change('beschreibung', alt, neu)

    def name_added(self, gruppe_name, gruppe_id, sprache, neu):
        self.names_added += 1

        gruppe = self.get_gruppe(gruppe_name, gruppe_id)
        gruppe.add_change('name ' + sprache, None, neu)

    def name_changed(self, gruppe_name, gruppe_id, sprache, alt, neu):
        self.names_changed += 1

        gruppe = self.get_gruppe(gruppe_name, gruppe_id)
        gruppe.add_change('name ' + sprache, alt, neu)

    def get_parlamentarier(self, parlamentarier_id):
        if not parlamentarier_id in self.parlamentarier_rows:
            self.parlamentarier_rows[parlamentarier_id] = ParlamentarierSummary(parlamentarier_id, self)

        return self.parlamentarier_rows[parlamentarier_id]

    def get_gruppe(self, gruppe_name, gruppe_id):
        if gruppe_id != 'neu' and gruppe_id in self.gruppe_rows_by_id:
            return self.gruppe_rows_by_id[gruppe_id]
        elif not gruppe_name in self.gruppe_rows_by_name:
            self.gruppe_rows_by_name[gruppe_name] = GruppeSummary(gruppe_name, gruppe_id, self)
            if gruppe_id != 'neu':
                self.gruppe_rows_by_id[gruppe_id] = self.gruppe_rows_by_name[gruppe_name]

        return self.gruppe_rows_by_name[gruppe_name]

    def get_all_parlamentarier(self):
        if not self.parlamentarier_rows:
            return []
        rows_sorted = list(self.parlamentarier_rows.values())
        rows_sorted.sort(key=lambda row : row.parlamentarier_name)
        return rows_sorted

    def get_all_gruppen(self):
        if not self.gruppe_rows_by_name:
            return []
        rows_sorted = list(self.gruppe_rows_by_name.values())
        rows_sorted.sort(key=lambda row : row.gruppe_name)
        return rows_sorted

    def write_parlamentarier_header(self):
        return " No |   | Parlamentarier | ID  ‖ Parlamentarische Gruppen"

    def write_gruppen_header(self):
        return " No |   | Gruppe               | ID    ‖ Parlamentarier / Changes"

    def equal_count(self):
        return len(self.parlamentarier_rows) - self.changed_count()

    def changed_count(self):
        return sum([1 for row in self.parlamentarier_rows.values() if row.has_changed()])

    def added_count(self):
        return sum(len(row.gruppen_neu) for row in self.parlamentarier_rows.values())

    def removed_count(self):
        return sum(len(row.gruppen_beendet) for row in self.parlamentarier_rows.values())

    def aktive_mitglieder_total(self):
        aktive_mitglieder_via_parlamentarier = sum(row.get_aktive_gruppen_count() for row in self.parlamentarier_rows.values())
        aktive_mitglieder_via_gruppen = sum(row.get_aktive_parlamentarier_count() for row in self.gruppe_rows_by_name.values())
        assert aktive_mitglieder_via_parlamentarier == aktive_mitglieder_via_gruppen
        return aktive_mitglieder_via_parlamentarier

    def im_vortand_total(self):
        count_via_parlamentarier = sum(row.get_im_vorstand_count() for row in self.parlamentarier_rows.values())
        count_via_gruppen = sum(row.get_im_vorstand_count() for row in self.gruppe_rows_by_name.values())
        assert count_via_parlamentarier == count_via_gruppen
        return count_via_parlamentarier

    def sekretariat_added_count(self):
        return self.sekretariats_added

    def sekretariat_changed_count(self):
        return self.sekretariats_changed

    def adresse_added_count(self):
        return self.addresses_added

    def adresse_changed_count(self):
        return self.addresses_changed

    def names_changed_count(self):
        return self.names_changed

    def names_added_count(self):
        return self.names_added

    def websites_added_count(self):
        return self.websites_added

    def websites_changed_count(self):
        return self.websites_changed

    def aliases_added_count(self):
        return self.aliases_added

    def aliases_changed_count(self):
        return self.aliases_changed

    def beschreibungen_added_count(self):
        return self.beschreibungen_added

    def beschreibungen_changed_count(self):
        return self.beschreibungen_changed

    def organisation_added_count(self):
        return self.organisationen_added

    def organisation_removed_count(self):
        return self.organisationen_removed

    def get_gruppen_total(self):
        return len(self.gruppe_rows_by_name)

    def organisation_data_changed(self):
        return \
            self.websites_added + \
            self.websites_changed + \
            self.sekretariats_added + \
            self.sekretariats_changed + \
            self.names_added + \
            self.names_changed + \
            self.addresses_added + \
            self.addresses_changed + \
            self.websites_added + \
            self.websites_changed + \
            self.aliases_added + \
            self.aliases_changed + \
            self.beschreibungen_added + \
            self.beschreibungen_changed + \
            self.organisationen_removed \
            > 0

    def any_parlamentarier_changed(self):
        return any([parlamentarier.has_changed() for parlamentarier in self.get_all_parlamentarier()])

    def set_parlamentarier_name(self, parlamentarier_id, name):
        parlamentarier = self.get_parlamentarier(parlamentarier_id)
        parlamentarier.set_parlamentarier_name(name)

    def neue_gruppe(self, parlamentarier_id, gruppe_id, gruppe_name, art):
        parlamentarier = self.get_parlamentarier(parlamentarier_id)
        parlamentarier.neue_gruppe(gruppe_id, gruppe_name, art)

        gruppe = self.get_gruppe(gruppe_name, gruppe_id)
        gruppe.neue_gruppe(parlamentarier_id, parlamentarier.get_parlamentarier_name(), art)

    def gruppe_beendet(self, parlamentarier_id, gruppe_id, gruppe_name, art):
        parlamentarier = self.get_parlamentarier(parlamentarier_id)
        parlamentarier.gruppe_beendet(gruppe_id, gruppe_name, art)

        gruppe = self.get_gruppe(gruppe_name, gruppe_id)
        gruppe.gruppe_beendet(parlamentarier_id, parlamentarier.get_parlamentarier_name(), art)

    def gruppe_geloescht(self, gruppe_id, gruppe_name):
        gruppe = self.get_gruppe(gruppe_name, gruppe_id)
        gruppe.gruppe_geloescht()
        self.organisation_removed()

    def gruppe_unveraendert(self, parlamentarier_id, gruppe_id, gruppe_name, art):
        parlamentarier = self.get_parlamentarier(parlamentarier_id)
        parlamentarier.gruppe_unveraendert(gruppe_id, gruppe_name, art)

        gruppe = self.get_gruppe(gruppe_name, gruppe_id)
        gruppe.gruppe_unveraendert(parlamentarier_id, parlamentarier.get_parlamentarier_name(), art)

    def gruppe_veraendert(self, parlamentarier_id, gruppe_id, gruppe_name, art):
        parlamentarier = self.get_parlamentarier(parlamentarier_id)
        parlamentarier.gruppe_veraendert(gruppe_id, gruppe_name, art)

        gruppe = self.get_gruppe(gruppe_name, gruppe_id)
        gruppe.gruppe_veraendert(parlamentarier_id, parlamentarier.get_parlamentarier_name(), art)

    def has_parlamentarier_changed(self, parlamentarier_id):
        parlamentarier = self.get_parlamentarier(parlamentarier_id)
        return parlamentarier.has_changed()

    def get_parlamentarier_name(self, parlamentarier_id):
        parlamentarier = self.get_parlamentarier(parlamentarier_id)
        return parlamentarier.get_parlamentarier_name()

    # def get_gruppe_name(self, gruppe_name):
    #     gruppe = self.get_gruppe_by_name(gruppe_name)
    #     return gruppe.get_gruppe_name()

    def print_parlamentarier(self):
        print(self.write_parlamentarier_header())
        for index, parlamentarier in enumerate(self.get_all_parlamentarier()):
            print(parlamentarier.write(index + 1))

    def print_gruppen(self):
        print(self.write_gruppen_header())
        for index, gruppe in enumerate(self.get_all_gruppen()):
            print(gruppe.write(index + 1))


class ParlamentarierSummary:
    def __init__(self, parlamentarier_id, parent):
        self.parlamentarier_id = parlamentarier_id
        self.parent = parent
        self.gruppen_neu = []
        self.gruppen_veraendert = []
        self.gruppen_unveraendert = []
        self.gruppen_beendet = []
        self.parlamentarier_name = ""

    def set_parlamentarier_name(self, name):
        self.parlamentarier_name = name

    def get_parlamentarier_name(self):
        return self.parlamentarier_name

    def neue_gruppe(self, gruppe_id, gruppe_name, art):
        self.gruppen_neu.append((gruppe_name, gruppe_id, art))

    def gruppe_beendet(self, gruppe_id, gruppe_name, art):
        self.gruppen_beendet.append((gruppe_name, gruppe_id, art))

    def gruppe_unveraendert(self, gruppe_id, gruppe_name, art):
        self.gruppen_unveraendert.append((gruppe_name, gruppe_id, art))

    def gruppe_veraendert(self, gruppe_id, gruppe_name, art):
        self.gruppen_veraendert.append((gruppe_name, gruppe_id, art))

    def get_aktive_gruppen_count(self):
        return len(self.gruppen_neu) + len(self.gruppen_veraendert) + len(self.gruppen_unveraendert)

    def get_im_vorstand_count(self):
        return len([x for x in self.gruppen_neu if x[2][0].upper() == "V"]) + len([x for x in self.gruppen_veraendert if x[2][0].upper() == "V"]) + len([x for x in self.gruppen_unveraendert if x[2][0].upper() == "V"])

    def has_changed(self):
        return len(self.gruppen_neu) > 0 or len(self.gruppen_beendet) > 0 or len(self.gruppen_veraendert) > 0

    def write(self, index):
        changed_symbol = "≠" if self.has_changed() else "="
        gruppen = []
        for gruppe_name, gruppe_id, ib_art in self.gruppen_beendet:
            gruppen.append("- {} {} ({}) ".format(ib_art[0].upper(), clean_gruppen_name(gruppe_name), gruppe_id))
        for gruppe_name, gruppe_id, ib_art in self.gruppen_neu:
            gruppen.append("+ {} {} ({}) ".format(ib_art[0].upper(), clean_gruppen_name(gruppe_name), gruppe_id))
        for gruppe_name, gruppe_id, ib_art in self.gruppen_veraendert:
            gruppen.append("≠ {} {} ({}) ".format(ib_art[0].upper(), clean_gruppen_name(gruppe_name), gruppe_id))
        for gruppe_name, gruppe_id, ib_art in self.gruppen_unveraendert:
            gruppen.append("= {} {} ({}) ".format(ib_art[0].upper(), clean_gruppen_name(gruppe_name), gruppe_id))
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


class GruppeSummary:
    def __init__(self, gruppe_name, gruppe_id, parent):
        self.gruppe_name = gruppe_name
        self.gruppe_id = gruppe_id
        self.parent = parent
        self.ist_neue_gruppe = True if self.gruppe_id == "neu" else False
        self.ist_gruppe_geloescht = False
        self.parlamentarier_neu = []
        self.parlamentarier_beendet = []
        self.parlamentarier_unveraendert = []
        self.parlamentarier_art_geandert = []
        self.changes = []

    def get_gruppe_name(self):
        return self.gruppe_name

    def get_gruppe_id(self):
        return self.gruppe_id

    def gruppe_geloescht(self):
        self.ist_gruppe_geloescht = True

    def add_change(self, feld, alt, neu):
        self.changes.append((feld, alt, neu))

    def neue_gruppe(self, parlamentarier_id, parlamentarier_name, art):
        self.parlamentarier_neu.append((parlamentarier_name, parlamentarier_id, art))

    def gruppe_beendet(self, parlamentarier_id, parlamentarier_name, art):
        self.parlamentarier_beendet.append((parlamentarier_name, parlamentarier_id, art))

    def gruppe_unveraendert(self, parlamentarier_id, parlamentarier_name, art):
        self.parlamentarier_unveraendert.append((parlamentarier_name, parlamentarier_id, art))

    def gruppe_veraendert(self, parlamentarier_id, parlamentarier_name, art):
        self.parlamentarier_art_geandert.append((parlamentarier_name, parlamentarier_id, art))

    def get_aktive_parlamentarier_count(self):
        return len(self.parlamentarier_neu) + len(self.parlamentarier_unveraendert) + len(self.parlamentarier_art_geandert)

    def get_im_vorstand_count(self):
        return len([x for x in self.parlamentarier_neu if x[2][0].upper() == "V"]) + len([x for x in self.parlamentarier_unveraendert if x[2][0].upper() == "V"]) + len([x for x in self.parlamentarier_art_geandert if x[2][0].upper() == "V"])

    def has_changed(self):
        return len(self.changes) > 0

    def write(self, index):
        if self.ist_neue_gruppe:
            changed_symbol = "+"
        elif self.has_changed():
            changed_symbol = "≠"
        elif self.ist_gruppe_geloescht:
            changed_symbol = "-"
        else:
            changed_symbol = "="

        parlamentarier = []
        for parlamentarier_name, parlamentarier_id, ib_art in self.parlamentarier_neu:
            parlamentarier.append(("+", ib_art[0].upper(), parlamentarier_name, parlamentarier_id))
        for parlamentarier_name, parlamentarier_id, ib_art in self.parlamentarier_beendet:
            parlamentarier.append(("-", ib_art[0].upper(), parlamentarier_name, parlamentarier_id))
        for parlamentarier_name, parlamentarier_id, ib_art in self.parlamentarier_unveraendert:
            parlamentarier.append(("=", ib_art[0].upper(), parlamentarier_name, parlamentarier_id))
        for parlamentarier_name, parlamentarier_id, ib_art in self.parlamentarier_art_geandert:
            parlamentarier.append(("≠", ib_art[0].upper(), parlamentarier_name, parlamentarier_id))

        parlamentarier_sorted = sorted(parlamentarier, key=lambda row : ("0" if row[1] == 'V' else "1") + row[2])

        lines = []
        lines.append("{:3d} | {} | {} | {} ‖ {}".format(
                    index,
                    changed_symbol,
                    self.gruppe_name[:20].ljust(20),
                    str(self.gruppe_id).rjust(5),
                    ""
                    ))
        for i, (feld, alt, neu) in enumerate(self.changes):
            if alt:
                lines.append("{:3}   {}   {}   {} ‖ {}: '{}'".format(
                    "",
                    " ",
                    "".ljust(7),
                    "".rjust(5),
                    "alt " + feld.ljust(13),
                    ("; ".join(x for x in alt if x != None) if type(alt) is tuple else alt.replace('\n', '; '))
                    ))
            lines.append("{:3}   {}   {}   {} ‖ {}: {}".format(
                "",
                " ",
                "".ljust(7),
                "".rjust(5),
                "neu " + feld.ljust(13),
                ("'" + ("; ".join(x for x in neu if x != None) if type(neu) is tuple else neu.replace('\n', '; ')) + "'") if neu else '-'
                ))
        for i, (symbol, ib_art, parlamentarier_name, parlamentarier_id) in enumerate(parlamentarier_sorted):
            lines.append("{:3}   {}   {}   {} ‖ {:2} {}".format(
                "",
                " ",
                "".ljust(20),
                "".rjust(5),
                i + 1,
                "{} {} {} ({}) ".format(symbol, ib_art[0].upper(), parlamentarier_name, parlamentarier_id)
                ))
        return "\n".join(lines)


def clean_gruppen_name(str):
    return str.replace("Parlamentarische Gruppe für ", "") \
        .replace("Parlamentarische Gruppe ", "") \
        .replace("Parlamentarische Freundschaftsgruppe ", "") \
        .replace("Parlamentarische Kerngruppe ", "") \
        .replace("Lateinische parlamentarische Gruppe", "Lateinische Gruppe") \
        .replace("Parlamentarische ", "")
