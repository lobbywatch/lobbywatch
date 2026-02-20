import sys
from datetime import date, datetime
from typing import Dict
from unittest.mock import Mock, create_autospec, patch

import db
import pytest

mock_db = create_autospec(db)
conn = Mock()
batch_time = datetime.fromisoformat("2025-10-15T10:12:16Z")
pdf_date = date.fromisoformat("2025-10-11")

with patch.dict(sys.modules, {"db": mock_db}):
    from zb_create_delta import sync_parliamentarian


def db_guest(num: int, function=None) -> Dict:
    return {
        "names": ["Guest", str(num)],
        "function": function if function else f"guest {num} function",
        "id": f"guest_{num}",
        "zutrittsberechtigung_id": f"zb_{num}",
    }


def json_guest(num: int, function=None) -> Dict:
    return {
        "names": ["Guest", str(num)],
        "function": function if function else f"guest {num} function",
    }


def test_sync_parliamentarian_two_guests_no_changes() -> None:
    p = {
        "canton": "bern",
        "faction": "",
        "names": ["peter", "parl"],
        "guests": [
            json_guest(1),
            json_guest(2),
        ],
    }
    mock_db.get_parlamentarier_dict.return_value = {
        "im_rat_bis": None,
        "id": "1",
        "names": ["peter", "parl"],
    }
    mock_db.get_guests.return_value = (
        db_guest(1),
        db_guest(2),
    )
    mock_db.get_parlamentarier_id_by_names_kanton_fraktion.return_value = "1"

    summary_row = sync_parliamentarian(p, conn, batch_time, pdf_date, 0)

    assert summary_row.number == "0"
    assert summary_row.parlamentarier_id == "1"
    assert summary_row.parlamentarier_name == "peter parl"
    assert summary_row.has_changed() is False
    assert summary_row.get_symbol1() == "="
    assert summary_row.gast1_id == "guest_1"
    assert summary_row.gast1_name == "Guest 1"
    assert summary_row.gast1_id_old == ""
    assert summary_row.gast1_changes == ""
    assert summary_row.get_symbol2() == "="
    assert summary_row.gast2_id == "guest_2"
    assert summary_row.gast2_name == "Guest 2"
    assert summary_row.gast2_id_old == ""
    assert summary_row.gast2_changes == ""
    assert summary_row.write(0) == "  0| == | peter parl     |   1 ‖ Guest 1      | guest_1 |               ‖ Guest 2      | guest_2 |               ‖              |      |              |      |"

def test_sync_parliamentarian_two_guests_order_changed_in_pdf() -> None:
    p = {
        "canton": "bern",
        "faction": "",
        "names": ["peter", "parl"],
        "guests": [
            json_guest(2),
            json_guest(1),
        ],
    }
    mock_db.get_parlamentarier_dict.return_value = {
        "im_rat_bis": None,
        "id": "1",
        "names": ["peter", "parl"],
    }
    mock_db.get_guests.return_value = (
        db_guest(1),
        db_guest(2),
    )
    mock_db.get_parlamentarier_id_by_names_kanton_fraktion.return_value = "1"

    summary_row = sync_parliamentarian(p, conn, batch_time, pdf_date, 0)

    assert summary_row.number == "0"
    assert summary_row.parlamentarier_id == "1"
    assert summary_row.parlamentarier_name == "peter parl"
    assert summary_row.has_changed() is False
    assert summary_row.get_symbol1() == "="
    assert summary_row.gast1_id == "guest_1"
    assert summary_row.gast1_name == "Guest 1"
    assert summary_row.gast1_id_old == ""
    assert summary_row.gast1_changes == ""
    assert summary_row.get_symbol2() == "="
    assert summary_row.gast2_id == "guest_2"
    assert summary_row.gast2_name == "Guest 2"
    assert summary_row.gast2_id_old == ""
    assert summary_row.gast2_changes == ""
    assert summary_row.write(0) == "  0| == | peter parl     |   1 ‖ Guest 1      | guest_1 |               ‖ Guest 2      | guest_2 |               ‖              |      |              |      |"

def test_sync_parliamentarian_one_guest_to_no_guests() -> None:
    p = {
        "canton": "bern",
        "faction": "",
        "names": ["peter", "parl"],
        "guests": [],
    }
    mock_db.get_parlamentarier_dict.return_value = {
        "im_rat_bis": None,
        "id": "1",
        "names": ["peter", "parl"],
    }
    mock_db.get_guests.return_value = (
        db_guest(1),
        None,
    )
    mock_db.get_parlamentarier_id_by_names_kanton_fraktion.return_value = "1"

    summary_row = sync_parliamentarian(p, conn, batch_time, pdf_date, 0)

    assert summary_row.number == "0"
    assert summary_row.parlamentarier_id == "1"
    assert summary_row.parlamentarier_name == "peter parl"
    assert summary_row.has_changed() is True
    assert summary_row.get_symbol1() == "-"
    assert summary_row.gast1_id == ""
    assert summary_row.gast1_id_old == "guest_1"
    assert summary_row.gast1_changes == ""
    assert summary_row.get_symbol2() == " "
    assert summary_row.gast2_id == ""
    assert summary_row.gast2_id_old == ""
    assert summary_row.gast2_changes == ""
    assert summary_row.write(0) == "  0| -  | peter parl     |   1 ‖              |      |               ‖              |      |               ‖ Guest 1      | guest_1 |              |      |"

def test_sync_parliamentarian_no_guests_to_one_guest() -> None:
    p = {
        "canton": "bern",
        "faction": "",
        "names": ["peter", "parl"],
        "guests": [json_guest(1)],
    }
    mock_db.get_parlamentarier_dict.return_value = {
        "im_rat_bis": None,
        "id": "1",
        "names": ["peter", "parl"],
    }
    mock_db.get_guests.return_value = (
        None,
        None,
    )
    mock_db.get_parlamentarier_id_by_names_kanton_fraktion.return_value = "1"
    mock_db.get_person_id.return_value = ""

    summary_row = sync_parliamentarian(p, conn, batch_time, pdf_date, 0)

    assert summary_row.number == "0"
    assert summary_row.parlamentarier_id == "1"
    assert summary_row.parlamentarier_name == "peter parl"
    assert summary_row.has_changed() is True
    assert summary_row.get_symbol1() == "+"
    assert summary_row.gast1_id == ""
    assert summary_row.gast1_id_old == ""
    assert summary_row.gast1_changes == ""
    assert summary_row.get_symbol2() == " "
    assert summary_row.gast2_id == ""
    assert summary_row.gast2_id_old == ""
    assert summary_row.gast2_changes == ""
    assert summary_row.write(0) == "  0| +  | peter parl     |   1 ‖ Guest 1      |      |               ‖              |      |               ‖              |      |              |      |"


def test_sync_parliamentarian_one_guest_to_two_guests() -> None:
    p = {
        "canton": "bern",
        "faction": "",
        "names": ["peter", "parl"],
        "guests": [
            json_guest(1),
            json_guest(2),
        ],
    }
    mock_db.get_parlamentarier_dict.return_value = {
        "im_rat_bis": None,
        "id": "1",
        "names": ["peter", "parl"],
    }
    mock_db.get_guests.return_value = (
        db_guest(1),
        None,
    )
    mock_db.get_parlamentarier_id_by_names_kanton_fraktion.return_value = "1"

    summary_row = sync_parliamentarian(p, conn, batch_time, pdf_date, 0)

    assert summary_row.number == "0"
    assert summary_row.parlamentarier_id == "1"
    assert summary_row.parlamentarier_name == "peter parl"
    assert summary_row.has_changed() is True
    assert summary_row.get_symbol1() == "="
    assert summary_row.gast1_id == "guest_1"
    assert summary_row.gast1_id_old == ""
    assert summary_row.gast1_changes == ""
    assert summary_row.get_symbol2() == "+"
    assert summary_row.gast2_id == ""
    assert summary_row.gast2_id_old == ""
    assert summary_row.gast2_changes == ""
    assert summary_row.write(0) == "  0| =+ | peter parl     |   1 ‖ Guest 1      | guest_1 |               ‖ Guest 2      |      |               ‖              |      |              |      |"

def test_sync_parliamentarian_two_guests_first_guest_changes_function() -> None:
    p = {
        "canton": "bern",
        "faction": "",
        "names": ["peter", "parl"],
        "guests": [
            json_guest(1, function="something fancy"),
            json_guest(2),
        ],
    }
    mock_db.get_parlamentarier_dict.return_value = {
        "im_rat_bis": None,
        "id": "1",
        "names": ["peter", "parl"],
    }
    mock_db.get_guests.return_value = (
        db_guest(1),
        db_guest(2),
    )
    mock_db.get_parlamentarier_id_by_names_kanton_fraktion.return_value = "1"

    summary_row = sync_parliamentarian(p, conn, batch_time, pdf_date, 0)

    assert summary_row.number == "0"
    assert summary_row.parlamentarier_id == "1"
    assert summary_row.parlamentarier_name == "peter parl"
    assert summary_row.has_changed() is True
    assert summary_row.get_symbol1() == "≠"
    assert summary_row.gast1_id == "guest_1"
    assert summary_row.gast1_id_old == ""
    assert summary_row.gast1_changes == "funktion"
    assert summary_row.get_symbol2() == "="
    assert summary_row.gast2_id == "guest_2"
    assert summary_row.gast2_id_old == ""
    assert summary_row.gast2_changes == ""
    assert summary_row.write(0) == "  0| ≠= | peter parl     |   1 ‖ Guest 1      | guest_1 | funktion      ‖ Guest 2      | guest_2 |               ‖              |      |              |      |"

def test_sync_parliamentarian_two_guests_first_guest_leaves() -> None:
    p = {
        "canton": "bern",
        "faction": "",
        "names": ["peter", "parl"],
        "guests": [
            json_guest(2),
        ],
    }
    mock_db.get_parlamentarier_dict.return_value = {
        "im_rat_bis": None,
        "id": "1",
        "names": ["peter", "parl"],
    }
    mock_db.get_guests.return_value = (
        db_guest(1),
        db_guest(2),
    )
    mock_db.get_parlamentarier_id_by_names_kanton_fraktion.return_value = "1"

    summary_row = sync_parliamentarian(p, conn, batch_time, pdf_date, 0)

    assert summary_row.number == "0"
    assert summary_row.parlamentarier_id == "1"
    assert summary_row.parlamentarier_name == "peter parl"
    assert summary_row.has_changed() is True
    assert summary_row.get_symbol1() == "-"
    assert summary_row.gast1_id == ""
    assert summary_row.gast1_id_old == "guest_1"
    assert summary_row.gast1_changes == ""
    assert summary_row.get_symbol2() == "="
    assert summary_row.gast2_id == "guest_2"
    assert summary_row.gast2_id_old == ""
    assert summary_row.gast2_changes == ""
    assert summary_row.write(0) == "  0| -= | peter parl     |   1 ‖              |      |               ‖ Guest 2      | guest_2 |               ‖ Guest 1      | guest_1 |              |      |"

def test_sync_parliamentarian_two_guests_first_guest_changes() -> None:
    p = {
        "canton": "bern",
        "faction": "",
        "names": ["peter", "parl"],
        "guests": [json_guest(3), json_guest(2)],
    }
    mock_db.get_parlamentarier_dict.return_value = {
        "im_rat_bis": None,
        "id": "1",
        "names": ["peter", "parl"],
    }
    mock_db.get_guests.return_value = (
        db_guest(1),
        db_guest(2),
    )
    mock_db.get_parlamentarier_id_by_names_kanton_fraktion.return_value = "1"
    mock_db.get_person_id.return_value = ""
    summary_row = sync_parliamentarian(p, conn, batch_time, pdf_date, 0)

    assert summary_row.number == "0"
    assert summary_row.parlamentarier_id == "1"
    assert summary_row.parlamentarier_name == "peter parl"
    assert summary_row.has_changed() is True
    assert summary_row.get_symbol1() == "+"
    assert summary_row.gast1_id == ""
    assert summary_row.gast1_id_old == "guest_1"
    assert summary_row.gast1_changes == ""
    assert summary_row.get_symbol2() == "="
    assert summary_row.gast2_id == "guest_2"
    assert summary_row.gast2_id_old == ""
    assert summary_row.gast2_changes == ""
    assert summary_row.write(0) == "  0| ±= | peter parl     |   1 ‖ Guest 3      |      |               ‖ Guest 2      | guest_2 |               ‖ Guest 1      | guest_1 |              |      |"
