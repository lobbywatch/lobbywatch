from typing import TypedDict, List, Optional, Literal


class JsonFunction(TypedDict):
    de: str
    fr: str


class JsonGuest(TypedDict):
    """Guest as written by zb_create_json.py"""

    names: List[str]
    function: JsonFunction
    id: Optional[str]  # FIXME: only set by guest_added before inserting a new guest
    beneficiary_group: Optional[str]
    valid_from: str


class JsonParlamentarier(TypedDict):
    """Parlamentarier as written by zb_create_json.py"""

    biography_id: int
    guests: List[JsonGuest]


class DbParlamentarier(TypedDict):
    """Parlamentarier as returned by db.py, incomplete!"""

    id: int
    vorname: str
    nachname: str
    arbeitssprache: Literal["de", "fr"]
