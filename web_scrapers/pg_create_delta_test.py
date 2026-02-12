import pytest
from pg_create_delta import normalize_organisation


@pytest.mark.parametrize(
    "input,expected",
    [
        (None, None),
        (" ", ""),
        (" ()", ""),
        ("-- ()", ""),
        ("--- ()", ""),
        ("Kindes- und Erwachsenenschutz ()", "Kindes- und Erwachsenenschutz"),
        ("Enfance et jeunesse (IPEJ)", "Enfance et jeunesse"),
        ("Climat", "Climat"),
    ],
)
def test_normalize_organisation(input, expected):
    assert normalize_organisation(input) == expected
