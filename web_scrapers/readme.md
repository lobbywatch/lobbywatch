# Guest-List Parser and Database Updater for National- and Ständerat #

Update the lobbywatch database automatically with updated guest list information from the government.

## Requirements ##

Python 3 

Python modules in requirements.txt (pip install -r requirements.txt)

Java 1.6

tabula-0.9.2-jar-with-dependencies.jar (https://github.com/tabulapdf/tabula-java/releases)

pdftk

## Written by ##

Markus Roth 

maroth@gmail.com

# scrape_parliament_guest_list.py #

This script reads the PDF files that describe the guests that are allowed entrance to the Swiss federal council by the members of parliament, and translates them into machine-digestable .json documents.

## Run ##

> python3 scrape_parliament_guest_lists.py

## Output Files ##

zutrittsberechtigte-nr.json

zutrittsberechtigte-sr.json

## Output Format Example ##

```
[
    {
        "canton": "TI",
        "second_first_name": "",
        "party": "FDP-Liberale",
        "first_name": "Giovanni",
        "last_name": "Merlini",
        "guests": [
            {
                "name": "Speziali Alessandro",
                "function": "Collaboratore/trice personale"
            }
        ]
    },
    {
        "canton": "BE",
        "second_first_name": "",
        "party": "SP",
        "first_name": "Matthias",
        "last_name": "Aebischer",
        "guests": [
            {
                "name": "Bütikofer Etienne",
                "function": "Bildungsorganisation savoirsuisse"
            },
            {
                "name": "Meyer Markus",
                "function": "Gast"
            }
        ]
    },
    [...]
]
```



# create_guest_list_update.py #

This script reads the json files created by scrape_parliament_guest_list.py, then checks if the database is up to date. If there are changes needed, in generates an SQL script that can then be applied to account for the changes in the guest lists.

## Run ##

> python3 create_guest_list_update.py

## Output Files ##

zutrittsberechtigte-nr.sql

zutrittsberechtigte-sr.sql

## Output Format Example ##

```
```
