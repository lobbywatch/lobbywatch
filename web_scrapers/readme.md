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
        "party": "SVP",
        "guests": [
            {
                "name": "Reimann Johann Peter",
                "function": "Gast"
            },
            {
                "name": "Hürzeler Urs",
                "function": "Persönliche/r Mitarbeiter/in"
            }
        ],
        "canton": "AG",
        "name": "Reimann Maximilian"
    },
    {
        "party": "SVP",
        "guests": [
            {
                "name": "Wegelin Reinhard",
                "function": "SVP des Kt. Zürich"
            },
            {
                "name": "Fischer Benjamin",
                "function": "Junge SVP Schweiz"
            }
        ],
        "canton": "ZH",
        "name": "Tuena Mauro"
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
