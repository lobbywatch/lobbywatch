# Zutrittsberechtigte bei Nationalrat und Ständerat #

This project does the following:

* Download the source files containing Zutrittsberechtigte of [Nationalrat](parlament.ch/centers/documents/de/zutrittsberechtigte-nr.pdf) and [Ständerat](parlament.ch/centers/documents/de/zutrittsberechtigte-sr.pdf).

* Parse the source files into a JSON structure using [Tabula](http://tabula.technology/) and save the generated files as **zutrittsberechtigte-nr.json** and **zutrittsberechtigte-sr.json**. Since the parsing is not perfect, some fiddling is required to get all the data.

* For each Nationalrat and Ständerat, compare the new guests shown in the JSON file with the current guests as they are in the LobbyWatch database. As guests can have varying ways of writing their names, matching these guests to the database is non-trivial.

* If a Zutrittsberechtigung has ended, a new one has begun, or the named function of a Zutrittsberechtigte_r has changed, generate commented SQL statement to update the LobbyWatch database accordingly.

* Write these statements as a script called **delta.sql** to the project folder. Running it will update the database to the newest Zutrittsberechtigungen.

Note that this script itself only ever reads from the database, never updates it. To update the database, the generated **delta.sql** script needs to be run manually.

## Requirements ##

Python 3 

Java 1.6

[Tabula](https://github.com/tabulapdf/tabula-java/releases)

[PDFtk](https://www.pdflabs.com/tools/pdftk-the-pdf-toolkit/)

## Installing ##

* Install Python3 and the Java 1.6 JRE

* Clone the repository

* Create a Python virtual environment 

```python3 -m venv virtual_environment_lobbywatch```

* Activate the virtual environment

``` source virtual_environment_lobbywatch/bin/activate```

* Install the requirements

```pip install -r requirements.txt```

## Running ##

```./scrape.sh```

## Output Files ##

zutrittsberechtigte-nr.json

zutrittsberechtigte-sr.json

delta.sql

## Notes on Mapping Names ##

One of the core problems of this application is matching people from the PDF to people in the database. The names in the database are structured: There are fields for **vorname**, **zweiter_vorname**, and **nachnahme**. The field **vorname** sometimes also contains the nickname of the person in quotes, so for example you might have a vorname of **Hans "Hänsu"**.

Now consider someone with a first name of **Min Li**, two second first names of **Michaelangelo Sebastian** and a quadruple-word last name of **de la Scalia Widmer**. This person would be represented in the PDF als **De la Scalia Widmer Min Li Michaelangelo Sebastian**. How should I know which of these words corresponds to which part of the name? The person might as well have a first name of **Min**, four second first names of **Li Michaelangelo Sebastian De**, and a last name of **la Scalia Widmer**. And since there are nicknames in the database, I can`t do strict string comparison on first names, even if I did manage to guess which part of the name is actually the first name! After writing quite a few combinations, I came up with a solution to at least express this problem in a succinct way.

Every person gets an unstructured list of names in the order they appear in the PDF. We make no attempt to group names, every space in the name denotes a new name. So our examplle would be the list [Min, Li, Michaelangelo, Sebastian, De, La, Scalia, Widmer]. To search for such a person in the database, we need to construct a query where these names are mapped to the fields of **vorname**, **zweiter_vorname** and **nachname**. To have an succinct way of doing this, I essentially created a domain-specific language for name mapping. Inputting the list of names above and a string like "VVZZNNNN" would denote that the first two name in the string should be used as the vorname (V), the second two als the zweiter_vorname (Z), and the last four as the nachname (N). You can also use S für nicknames (Spitzname). This way, trying multiple combinations of names becomes easy, and can be updated if any other strange name combination will appear in the future.


The second challenge is deciding whether the person listed as a guest in the database is the same person referenced in the PDF. To solve this, I also read the names out of the database and structure them the same way, concatenating nachname + vorname + zweiter_vorname, and again splitting by spaces. This makes the nickname appear as a seperate name in the list (e.g. [Hans, Hänsu, Christian, Müller-Scheitlin]. I can then make use of heuristics like: Is one list of names a strict subset of the other? How many names are shared between the two lists? Are some names in one list abbreviations or initials of names in the other list? This gives a high flexibility of creatinga  heuristic filter that can decide whether two lists of names reference the same person.

## Written by ##

Markus Roth 

maroth@gmail.com

