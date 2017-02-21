== scrape_parliament_guest_list.py ==

This script reads the PDF files that describe the guests that are allowed entrance to the Swiss federal council by the members of parliament, and translates them into machine-digestable .json documents.

= Run =

python3 scrape_parliament_guest_lists.py

= Output Files =

sr_guests.json
nr_guests.json

= Output Format Example =

{
    "Rytz Regula, GPS/BE": [
        {
            "function": "Stiftung Landschaftsschutz Schweiz",
            "name": "Rodewald Raimund"
        },
        {
            "function": "Schweizerischer Gewerkschaftsbund",
            "name": "Bianchi Doris"
        }
    ],
    "Siegenthaler Heinz, BDP/BE": [
        {
            "function": "Persönliche Mitarbeiterin",
            "name": "Luginbühl-Bachmann Anita"
        },
        {
            "function": "Gast",
            "name": "Huissoud-Hauptstein Renate"
        }
    ]
    [...]
}


= Requirements =

Python 3
Java 1.6
tabula-0.9.2-jar-with-dependencies.jar (https://github.com/tabulapdf/tabula-java/releases)
pdftk

= Written by = 

Markus Roth 
maroth@gmail.com
