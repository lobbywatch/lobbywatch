from typing import List, Dict
from collections import defaultdict
import json
from datetime import datetime
import opd


def scrape():
    badges = opd.get_mps_and_access_badges()

    badges_per_mp = defaultdict(list)
    for badge in badges:
        apply_data_fixes_to_single_badge(badge)
        badges_per_mp[badge.person_external_id].append(badge)

    apply_data_fixes_after_grouping(badges_per_mp)

    badges_per_council = defaultdict(list)
    for person_external_id in badges_per_mp:
        person = badges_per_mp[person_external_id][0].person.data[0]
        badges_per_council[person.parliament_sector].append(
            {
                "names": [person.firstname, person.lastname],
                "biography_id": person_external_id,
                "guests": [
                    {
                        "names": badge.beneficiary_person_fullname.split(" "),
                        "function": badge.type.model_dump() if badge.type else None,
                        "beneficiary_group": badge.beneficiary_group,
                        "valid_from": badge.valid_from
                    }
                    for badge in badges_per_mp[person_external_id]
                ],
            }
        )

    for council_type in ["nr", "sr"]:
        now = datetime.now()

        data = {
            "metadata": {
                "pdf_creation_date": now.isoformat(),
                "stand_date": now.isoformat(),
                "pdf_date": now.isoformat(),
                "archive_pdf_name": "",
                "url": "https://api.openparldata.ch/v1/access_badges",
            },
            "data": badges_per_council[council_type.upper()],
        }

        with open(f"zutrittsberechtigte-{council_type}.json", "w") as file:
            json.dump(data, file)


def apply_data_fixes_to_single_badge(badge) -> None:
    """OPD data is not alyways correct. This is the place to make (temporary) fixes. Try to include gitlab tickets so we know when to remove a fix."""

    # her name is wrong in the PDFs provided by the Parlamentsdienste, not sure why
    if badge.beneficiary_person_fullname == "Schürch Florence":
        badge.beneficiary_person_fullname = "Schurch Florence"

def apply_data_fixes_after_grouping(
    badges_per_mp: Dict[int, List[opd.OpdAccessBadge]],
) -> Dict[int, List[opd.OpdAccessBadge]]:
    """OPD data is not alyways correct. This is the place to make (temporary) fixes. Try to include gitlab tickets so we know when to remove a fix."""
    return badges_per_mp


if __name__ == "__main__":
    scrape()
