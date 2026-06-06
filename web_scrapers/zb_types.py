from typing import TypedDict, List, Optional, Literal


class JsonFunction(TypedDict):
    de: str
    fr: str


class JsonGuest(TypedDict):
    names: List[str]
    function: JsonFunction
    id: Optional[str]  # FIXME: only set by guest_added before inserting a new guest
    beneficiary_group: Optional[str]


# Parlamentarier as written by zb_create_json.py
class JsonParlamentarier(TypedDict):
    biography_id: int
    guests: List[JsonGuest]


# Parlamentarier as returned by db.py, incomplete!
class DbParlamentarier(TypedDict):
    id: int
    vorname: str
    nachname: str
    arbeitssprache: Literal["de", "fr"]
