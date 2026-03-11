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
        self._guests: List[Guest] = []

    def get_guest(self, index: int) -> Guest:
        return self._guests[index - 1]

    def set_new_guest(self, person) -> None:
        guest = Guest()
        guest.name = _display_name(person["names"])
        guest.symbol = "+"
        self._guests.append(guest)

    def set_guest(self, person) -> None:
        guest = Guest()
        guest.name = _display_name(person["names"])
        if "id" in person:
            guest.id = str(person["id"])
            guest.symbol = "="

        self._guests.append(guest)

    def set_removed_guest(self, person):
        guest = Guest()
        guest.name_old = _display_name(person["names"])
        guest.id_old = str(person["id"])
        guest.symbol = "-"
        self._guests.append(guest)

    def set_guest_changes(self, person, changes: Literal["funktion"]):
        guest = Guest()
        guest.changes = changes
        guest.name = _display_name(person["names"])
        if "id" in person:
            guest.id = str(person["id"])
        guest.symbol = "≠"
        self._guests.append(guest)

    def update_symbols(self):
        for guest in self._guests:
            guest.update_symbols()

    def has_changed(self):
        return any([guest.has_changed() for guest in self._guests])

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
