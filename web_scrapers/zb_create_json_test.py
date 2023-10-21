import json
from typing import Any

import pytest
from zb_create_json import read_guests


def test_read_guests(capsys: pytest.CaptureFixture[str]) -> None:
    # Arrange

    # Act
    actual_guests = read_guests('web_scrapers/zb_data_test.csv')

    # Assert
    # json.dumps(actual_guests, ensure_ascii=False)
    expected_guests = read_json('web_scrapers/zb_data_test.json')
    assert actual_guests == expected_guests

    captured = capsys.readouterr()

    assert "WARN: too many guest lines for parlamentarier ['Michel', 'Matthias']: 2\n['', 'Vieli Martina', 'Interessenvertreter/in: SRG SSR', '']" in captured.out
    assert captured.err == ''



def read_json(filename: str) -> Any:
    """Returns a list or a dictionary."""
    with open(filename, "r", encoding="utf-8") as infile:
        return json.load(infile)
