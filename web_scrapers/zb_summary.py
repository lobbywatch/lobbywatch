from dataclasses import dataclass
from datetime import date
from typing import List, Literal


@dataclass
class Guest:
    symbol: str = " "
    id: str = ""
    id_old: str = ""
    name: str = ""
    name_old: str = ""
    changes: str = ""

    def update_symbols(self):
        if self.name_old != "" and self.name != "":
            self.symbol = "±"

    def has_changed(self):
        return not (self.symbol == "=" or self.symbol == " ")

    def write(self) -> str:
        return " {} | {} | {}  {} | {} ".format(
            self.name[:12].ljust(12),
            self.id.rjust(4),
            self.changes.ljust(13),
            self.name_old[:12].ljust(12),
            self.id_old.rjust(4),
        )


class SummaryRow:
    def __init__(self, parlamentarier, count, parlamentarier_db_dict, guest_limit):
        self.number = str(count)
        self.parlamentarier_name = _display_name(parlamentarier["names"])
        self.parlamentarier_id = str(parlamentarier["id"])
        self.parlamentarier_db_dict = parlamentarier_db_dict
        self._guests: List[Guest] = [Guest() for _ in range(guest_limit)]

    def get_guest(self, index: int) -> Guest:
        return self._guests[index - 1]

    def set_new_guest(self, index: int, person) -> None:
        self.set_guest(index, person)
        if person:
            self.get_guest(index).symbol = "+"

    def set_guest(self, index: int, person) -> None:
        if person:
            guest = self.get_guest(index)
            guest.name = _display_name(person["names"])
            if "id" in person:
                guest.id = str(person["id"])
                guest.symbol = "="

    def set_removed_guest(self, index: int, person):
        if person:
            guest = self.get_guest(index)
            guest.name_old = _display_name(person["names"])
            guest.id_old = str(person["id"])
            guest.symbol = "-"

    def set_guest_changes(self, index: int, changes: Literal["funktion"]):
        guest = self.get_guest(index)
        guest.changes = changes
        guest.symbol = "≠"

    @property
    def gast1_id(self):
        return self.get_guest(1).id

    @property
    def gast2_id(self):
        return self.get_guest(2).id

    @property
    def gast1_name(self):
        return self.get_guest(1).name

    @property
    def gast2_name(self):
        return self.get_guest(2).name

    @property
    def gast1_id_old(self):
        return self.get_guest(1).id_old

    @property
    def gast2_id_old(self):
        return self.get_guest(2).id_old

    @property
    def gast1_name_old(self):
        return self.get_guest(1).name_old

    @property
    def gast2_name_old(self):
        return self.get_guest(2).name_old

    @property
    def gast1_changes(self):
        return self.get_guest(1).changes

    @property
    def gast2_changes(self):
        return self.get_guest(2).changes

    @property
    def symbol1(self):
        return self.get_guest(1).symbol

    @property
    def symbol2(self):
        return self.get_guest(2).symbol

    def set_guest_1(self, person):
        self.set_guest(1, person)

    def set_removed_guest_1(self, person):
        self.set_removed_guest(1, person)

    def set_new_guest_1(self, person):
        self.set_new_guest(1, person)

    def set_guest_2(self, person):
        self.set_guest(2, person)

    def set_removed_guest_2(self, person):
        self.set_removed_guest(2, person)

    def set_new_guest_2(self, person):
        self.set_new_guest(2, person)

    def set_guest_1_changes(self, changes):
        self.set_guest_changes(1, changes)

    def set_guest_2_changes(self, changes):
        self.set_guest_changes(2, changes)

    def update_symbols(self):
        for guest in self._guests:
            guest.update_symbols()

    def has_changed(self):
        return any([guest.has_changed() for guest in self._guests])

    def get_symbol1(self):
        return self.get_guest(1).symbol

    def get_symbol2(self):
        return self.get_guest(2).symbol

    def is_parlamentarier_active(self):
        return (
            self.parlamentarier_db_dict["im_rat_bis"] is None
            or self.parlamentarier_db_dict["im_rat_bis"] > date.today()
        )

    def write(self, row_nr):
        self.update_symbols()
        mark = " " if self.is_parlamentarier_active() else "~"
        first_block = "{:3d}|{}{}{}|{}{}{}|{}{}{}".format(
            row_nr,  # self.number.ljust(3),
            mark,
            "".join([guest.symbol for guest in self._guests]),
            mark,
            mark,
            self.parlamentarier_name[:14].ljust(14),
            mark,
            mark,
            self.parlamentarier_id.rjust(3),
            mark,
        )
        guest_block = (guest.write() for guest in self._guests)
        return "‖".join([first_block, *guest_block])


def write_header(num_guests: int) -> str:
    return "‖".join(
        [
            "No |    | Parlamentarier |",
            *(
                f" ID  ‖ Gast {i}       | ID   | Changes  |  -Gast {i}      | ID "
                for i in range(num_guests)
            ),
        ]
    )


def _display_name(names):
    name = " ".join(names)
    return name
