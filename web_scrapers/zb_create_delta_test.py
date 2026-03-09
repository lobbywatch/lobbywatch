import sys
from datetime import date, datetime
from typing import Dict, List
from unittest.mock import Mock, create_autospec, patch, call, _Call
from zb_summary import Guest, SummaryRow
import db
import sql_statement_generator
import pytest

mock_db = create_autospec(db)
sql_statement_generator_mock = create_autospec(sql_statement_generator)
conn = Mock()
batch_time = datetime.fromisoformat("2025-10-15T10:12:16Z")
pdf_date = date.fromisoformat("2025-10-11")

with patch.dict(
    sys.modules,
    {"db": mock_db, "sql_statement_generator": sql_statement_generator_mock},
):
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


def filter_str_calls(item):
    """filters out calls to __str__ created when mocks are printed, e.g. by the logger"""
    match item:
        case ("().__str__", (), {}):
            return False
        case _:
            return True


def assert_sql_generator_calls(calls: Dict[str, List[_Call]]) -> None:
    for name in calls:
        if name not in dir(sql_statement_generator):
            pytest.fail(f"{name} not in {sql_statement_generator.__name__}")  # ty:ignore[invalid-argument-type]

    for name in dir(sql_statement_generator):
        fn = getattr(sql_statement_generator_mock, name)
        if isinstance(fn, Mock):
            if name in calls:
                assert list(filter(filter_str_calls, fn.mock_calls)) == calls[name]
            else:
                fn.assert_not_called()


def assert_guests(summary_row: SummaryRow, guests: List[Guest]):
    assert len(summary_row._guests) == len(guests)
    for guest in guests:
        assert guest in summary_row._guests


def setup_function(function):
    sql_statement_generator_mock.reset_mock()


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
    assert_guests(
        summary_row,
        [
            Guest(symbol="=", id="guest_1", name="Guest 1"),
            Guest(symbol="=", id="guest_2", name="Guest 2"),
        ],
    )
    assert_sql_generator_calls({})


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
    assert_guests(
        summary_row,
        [
            Guest(symbol="=", id="guest_1", name="Guest 1"),
            Guest(symbol="=", id="guest_2", name="Guest 2"),
        ],
    )
    assert_sql_generator_calls({})


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
    mock_db.get_guests.return_value = (db_guest(1),)
    mock_db.get_parlamentarier_id_by_names_kanton_fraktion.return_value = "1"

    summary_row = sync_parliamentarian(p, conn, batch_time, pdf_date, 0)

    assert summary_row.number == "0"
    assert summary_row.parlamentarier_id == "1"
    assert summary_row.parlamentarier_name == "peter parl"
    assert summary_row.has_changed() is True
    assert_guests(
        summary_row,
        [Guest(symbol="-", id_old="guest_1", name_old="Guest 1")],
    )
    assert_sql_generator_calls(
        {"end_zutrittsberechtigung": [call("zb_1", batch_time, pdf_date)]}
    )


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
    mock_db.get_guests.return_value = ()
    mock_db.get_parlamentarier_id_by_names_kanton_fraktion.return_value = "1"
    mock_db.get_person_id.return_value = ""

    summary_row = sync_parliamentarian(p, conn, batch_time, pdf_date, 0)

    assert summary_row.number == "0"
    assert summary_row.parlamentarier_id == "1"
    assert summary_row.parlamentarier_name == "peter parl"
    assert summary_row.has_changed() is True
    assert_guests(
        summary_row,
        [Guest(symbol="+", name="Guest 1")],
    )
    assert_sql_generator_calls(
        {
            "insert_zutrittsberechtigung": [
                call("1", "", json_guest(1)["function"], batch_time, pdf_date)
            ],
            "insert_person": [call(json_guest(1), batch_time, pdf_date)],
        }
    )


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
    mock_db.get_guests.return_value = (db_guest(1),)
    mock_db.get_parlamentarier_id_by_names_kanton_fraktion.return_value = "1"

    summary_row = sync_parliamentarian(p, conn, batch_time, pdf_date, 0)

    assert summary_row.number == "0"
    assert summary_row.parlamentarier_id == "1"
    assert summary_row.parlamentarier_name == "peter parl"
    assert summary_row.has_changed() is True
    assert_guests(
        summary_row,
        [
            Guest(symbol="=", id="guest_1", name="Guest 1"),
            Guest(symbol="+", name="Guest 2"),
        ],
    )
    assert_sql_generator_calls(
        {
            "insert_zutrittsberechtigung": [
                call("1", "", json_guest(2)["function"], batch_time, pdf_date)
            ],
            "insert_person": [call(json_guest(2), batch_time, pdf_date)],
        }
    )


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
    assert_guests(
        summary_row,
        [
            Guest(symbol="≠", id="guest_1", name="Guest 1", changes="funktion"),
            Guest(
                symbol="=",
                name="Guest 2",
                id="guest_2",
            ),
        ],
    )
    assert_sql_generator_calls(
        {
            "update_function_of_zutrittsberechtigung": [
                call("zb_1", "something fancy", batch_time, pdf_date)
            ],
        }
    )


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
    assert_guests(
        summary_row,
        [
            Guest(symbol="-", id_old="guest_1", name_old="Guest 1"),
            Guest(symbol="=", id="guest_2", name="Guest 2"),
        ],
    )
    assert_sql_generator_calls(
        {
            "end_zutrittsberechtigung": [call("zb_1", batch_time, pdf_date)],
        }
    )


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
    def get_person_id_mock(conn, names):
        match names:
            case ["Guest", "1"] | ["Guest", "2"]:
                return "some_id"
            case _:
                return ""

    mock_db.get_person_id.side_effect = get_person_id_mock

    summary_row = sync_parliamentarian(p, conn, batch_time, pdf_date, 0)

    assert summary_row.number == "0"
    assert summary_row.parlamentarier_id == "1"
    assert summary_row.parlamentarier_name == "peter parl"
    assert summary_row.has_changed() is True
    assert_guests(
        summary_row,
        [
            Guest(symbol="-", id_old="guest_1", name_old="Guest 1"),
            Guest(symbol="+", name="Guest 3"),
            Guest(symbol="=", id="guest_2", name="Guest 2"),
        ],
    )
    assert_sql_generator_calls(
        {
            "end_zutrittsberechtigung": [call("zb_1", batch_time, pdf_date)],
            "insert_zutrittsberechtigung": [
                call("1", "", json_guest(3)["function"], batch_time, pdf_date)
            ],
            "insert_person": [call(json_guest(3), batch_time, pdf_date)],
        }
    )


def test_sync_parliamentarian_three_guests_add_one_more() -> None:
    p = {
        "canton": "bern",
        "faction": "",
        "names": ["peter", "parl"],
        "guests": [json_guest(1), json_guest(2), json_guest(3), json_guest(4)],
    }
    mock_db.get_parlamentarier_dict.return_value = {
        "im_rat_bis": None,
        "id": "1",
        "names": ["peter", "parl"],
    }
    mock_db.get_guests.return_value = (
        db_guest(1),
        db_guest(2),
        db_guest(3),
    )
    mock_db.get_parlamentarier_id_by_names_kanton_fraktion.return_value = "1"

    def get_person_id_mock(conn, names):
        match names:
            case ["Guest", "1"] | ["Guest", "2"] | ["Guest", "3"]:
                return "some_id"

    mock_db.get_person_id.side_effect = get_person_id_mock

    summary_row = sync_parliamentarian(p, conn, batch_time, pdf_date, 0)

    assert summary_row.number == "0"
    assert summary_row.parlamentarier_id == "1"
    assert summary_row.parlamentarier_name == "peter parl"
    assert summary_row.has_changed() is True
    assert_guests(
        summary_row,
        [
            Guest(symbol="=", id="guest_1", name="Guest 1"),
            Guest(symbol="=", id="guest_2", name="Guest 2"),
            Guest(symbol="=", id="guest_3", name="Guest 3"),
            Guest(symbol="+",  name="Guest 4"),
        ],
    )
    assert_sql_generator_calls(
        {
            "insert_zutrittsberechtigung": [
                call("1", None, json_guest(4)["function"], batch_time, pdf_date)
            ],
            "insert_person": [call(json_guest(4), batch_time, pdf_date)],
        }
    )


def test_sync_parliamentarian_four_guests_one_leaves() -> None:
    p = {
        "canton": "bern",
        "faction": "",
        "names": ["peter", "parl"],
        "guests": [json_guest(1), json_guest(3), json_guest(4)],
    }
    mock_db.get_parlamentarier_dict.return_value = {
        "im_rat_bis": None,
        "id": "1",
        "names": ["peter", "parl"],
    }
    mock_db.get_guests.return_value = (
        db_guest(1),
        db_guest(2),
        db_guest(3),
        db_guest(4),
    )
    mock_db.get_parlamentarier_id_by_names_kanton_fraktion.return_value = "1"
    mock_db.get_person_id.return_value = "some_id"
    summary_row = sync_parliamentarian(p, conn, batch_time, pdf_date, 0)

    assert summary_row.number == "0"
    assert summary_row.parlamentarier_id == "1"
    assert summary_row.parlamentarier_name == "peter parl"
    assert summary_row.has_changed() is True
    assert_guests(
        summary_row,
        [
            Guest(symbol="=", id="guest_1", name="Guest 1"),
            Guest(symbol="-", id_old="guest_2", name_old="Guest 2"),
            Guest(symbol="=", id="guest_3", name="Guest 3"),
            Guest(symbol="=", id="guest_4", name="Guest 4"),
        ],
    )
    assert_sql_generator_calls(
        {
            "end_zutrittsberechtigung": [call("zb_2", batch_time, pdf_date)],
        }
    )


def test_sync_parliamentarian_four_guests_last_one_changes() -> None:
    p = {
        "canton": "bern",
        "faction": "",
        "names": ["peter", "parl"],
        "guests": [json_guest(1), json_guest(2), json_guest(3), json_guest(5)],
    }
    mock_db.get_parlamentarier_dict.return_value = {
        "im_rat_bis": None,
        "id": "1",
        "names": ["peter", "parl"],
    }
    mock_db.get_guests.return_value = (
        db_guest(1),
        db_guest(2),
        db_guest(3),
        db_guest(4),
    )
    mock_db.get_parlamentarier_id_by_names_kanton_fraktion.return_value = "1"

    def get_person_id_stub(conn, names):
        match names:
            case ("Guest", "1") | ("Guest", "2") | ("Guest", "3") | ("Guest", "4"):
                return "some_id"
            case _:
                return ""

    mock_db.get_person_id.side_effect = get_person_id_stub

    summary_row = sync_parliamentarian(p, conn, batch_time, pdf_date, 0)

    assert summary_row.number == "0"
    assert summary_row.parlamentarier_id == "1"
    assert summary_row.parlamentarier_name == "peter parl"
    assert summary_row.has_changed() is True
    assert_guests(
        summary_row,
        [
            Guest(symbol="=", id="guest_1", name="Guest 1"),
            Guest(symbol="=", id="guest_2", name="Guest 2"),
            Guest(symbol="=", id="guest_3", name="Guest 3"),
            Guest(symbol="-", id_old="guest_4", name_old="Guest 4"),
            Guest(symbol="+",  name="Guest 5"),
        ],
    )
    assert_sql_generator_calls(
        {
            "end_zutrittsberechtigung": [call("zb_4", batch_time, pdf_date)],
            "insert_person": [call(json_guest(5), batch_time, pdf_date)],
            "insert_zutrittsberechtigung": [call("1","", json_guest(5)["function"], batch_time, pdf_date)],
        }
    )


def test_sync_parliamentarian_two_guests_add_three_new() -> None:
    p = {
        "canton": "bern",
        "faction": "",
        "names": ["peter", "parl"],
        "guests": [json_guest(1), json_guest(5), json_guest(6), json_guest(7)],
    }
    mock_db.get_parlamentarier_dict.return_value = {
        "im_rat_bis": None,
        "id": "1",
        "names": ["peter", "parl"],
    }
    mock_db.get_guests.return_value = (
        db_guest(1),
        db_guest(2),
        db_guest(3),
        db_guest(4),
    )
    mock_db.get_parlamentarier_id_by_names_kanton_fraktion.return_value = "1"

    def get_person_id_stub(conn, names):
        match names:
            case ("Guest", "1") | ("Guest", "2") | ("Guest", "3") | ("Guest", "4"):
                return "some_id"
            case _:
                return ""

    mock_db.get_person_id.side_effect = get_person_id_stub
    summary_row = sync_parliamentarian(p, conn, batch_time, pdf_date, 0)

    assert summary_row.number == "0"
    assert summary_row.parlamentarier_id == "1"
    assert summary_row.parlamentarier_name == "peter parl"
    assert summary_row.has_changed() is True
    assert_guests(
        summary_row,
        [
            Guest(symbol="=", id="guest_1", name="Guest 1"),
            Guest(symbol="-", id_old="guest_2", name_old="Guest 2"),
            Guest(symbol="+",  name="Guest 5"),
            Guest(symbol="-", id_old="guest_3", name_old="Guest 3"),
            Guest(symbol="+",  name="Guest 6"),
            Guest(symbol="-", id_old="guest_4", name_old="Guest 4"),
            Guest(symbol="+",  name="Guest 7"),
        ],
    )
    assert_sql_generator_calls(
        {
            "end_zutrittsberechtigung": [
                call("zb_2", batch_time, pdf_date),
                call("zb_3", batch_time, pdf_date),
                call("zb_4", batch_time, pdf_date),
            ],
            "insert_person": [
                call(json_guest(5), batch_time, pdf_date),
                call(json_guest(6), batch_time, pdf_date),
                call(json_guest(7), batch_time, pdf_date),
            ],
            "insert_zutrittsberechtigung": [
                call("1", "", json_guest(5)["function"],batch_time, pdf_date),
                call("1", "", json_guest(6)["function"],batch_time, pdf_date),
                call("1", "", json_guest(7)["function"],batch_time, pdf_date),
            ],
        }
    )
