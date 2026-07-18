from typing import List, Optional, Literal, Union
import requests

from pydantic import BaseModel, TypeAdapter


class Data[DataT](BaseModel):
    data: List[DataT]


class OpdMp(BaseModel):
    body_key: Literal[
        "CHE", "UR", "198", "BE", "351", "BS"
    ]  # TODO remove all except CHE
    lastname: str
    firstname: str
    active: bool
    parliament_sector: Optional[Literal["NR", "SR"]]  # TODO remove optional


class TranslatedString(BaseModel):
    de: Optional[str]
    fr: Optional[str]


class OpdAccessBadge(BaseModel):
    type_harmonized: Optional[Literal["lobbyist", "staff", "guest", "language_assistant"]]
    type: Union[
        Optional[TranslatedString], dict
    ]  # sometimes it really is an empty dict
    beneficiary_group: Optional[str]
    beneficiary_person_fullname: str
    person_fullname: str
    person_external_id: str
    person_id: int
    body_key: Literal["CHE", "UR"]  # TODO remove all except CHE
    person: Data[OpdMp]
    valid_from: str


def get_mps_and_access_badges() -> List[OpdAccessBadge]:
    access_badges_raw = _fetch_all_access_badges()
    adapter = TypeAdapter(list[OpdAccessBadge])
    return adapter.validate_python(access_badges_raw)


def _fetch_all_access_badges():
    response = requests.get(
        "https://api.openparldata.ch/v1/access_badges/?body_key=CHE&exclude_null=beneficiary_person_fullname&expand=person&latest=true",
        timeout=10,
    )
    response.raise_for_status()
    body = response.json()
    access_badges = body["data"]

    while body["meta"]["has_more"] is True:
        response = requests.get(body["meta"]["next_page"], timeout=10)
        response.raise_for_status()
        body = response.json()
        access_badges.append(body["data"])

    return access_badges
