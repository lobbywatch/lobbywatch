Lobbywatch.ch Data Interface
============================

Date: 20.07.2014  
Data interface version: v1  
Format: JSON  
State: beta  

Introduction
------------

The project Lobbywatch.ch maintains a database (DB) of parlamentarian and their relations to lobby organizations.

This document describes the Lobbywatch.ch data interface (dataIF). This data interface can be used for visualizations,
e.g [D3](http://d3js.org/).

The data interface is close to the DB model. The data interface is based on the views. The data model is available as
[1 page](http://lobbywatch.ch/sites/lobbywatch.ch/app/lobbywatch_datenmodell_1page.pdf) or on 
[4 pages](http://lobbywatch.ch/sites/lobbywatch.ch/app/lobbywatch_datenmodell.pdf). Additionally, there is a 
[simplified data model](http://lobbywatch.ch/sites/lobbywatch.ch/app/lobbywatch_datenmodell_simplified.pdf).
Usage of this data interface requires knowledge of the DB data model.

Data are delivered in JSON.

The data interface is written in a generic way from the data model.

All names and keys are always in minor case. Words are separated by `_`.

Examples
--------

### Example 1: Get data about Branche with Id 1

Call:  
`http://lobbywatch.ch/de/data/interface/v1/json/table/branche/flat/id/1`

JSON Response:

    {
      "success" : true,
      "count" : 1,
      "message" : "1 record(s) found",
      "sql" : "\n      SELECT branche.*\n      FROM v_branche branche\n      WHERE branche.id=:id",
      "source": "branche",
      "data" : {
        "anzeige_name" : "Gesundheit",
        "id" : "1",
        "name" : "Gesundheit",
        "kommission_id" : "1",
        "beschreibung" : "Akteure im Gesundheitswesen",
        "angaben" : "Wie werden die ca. 60 Milliarden j\u00e4hrlich aufgeteilt",
        "farbcode" : "blue",
        "symbol_rel" : null,
        "symbol_klein_rel" : "branche_symbole\/default_branche.png",
        "symbol_dateiname_wo_ext" : null,
        "symbol_dateierweiterung" : null,
        "symbol_dateiname" : null,
        "symbol_mime_type" : null,
        "kommission" : "Kommissionen f\u00fcr soziale Sicherheit und Gesundheit (SGK)",
        "symbol_path" : "\/test\/files\/branche_symbole\/default_branche.png",
        "symbol_url" : "http:\/\/lobbywatch.dev\/\/test\/files\/branche_symbole\/default_branche.png"
      }
    }

### Example 2: Get all lobbygroups from Branche 1

Call:  
`http://lobbywatch.dev/de/data/interface/v1/json/table/interessengruppe/flat/list?filter_branche_id=1`

JSON Response:

    {
      "success" : true,
      "count" : 13,
      "message" : "13 record(s) found",
      "sql" : "\n    SELECT interessengruppe.*\n    FROM v_interessengruppe interessengruppe\n    WHERE 1  AND interessengruppe.branche_id = 1",
      "source": "interessengruppe",
      "data" : [
          {
            "anzeige_name" : "Pharma",
            "id" : "1",
            "name" : "Pharma",
            "branche_id" : "1",
            "beschreibung" : "Medikamentenforschung, Medikamentenvertrieb, Pharmafirmen, Apotheken",
            "branche" : "Gesundheit",
            "kommission_id" : "1",
            "kommission" : "Kommissionen f\u00fcr soziale Sicherheit und Gesundheit (SGK)"
          },
          {
            "anzeige_name" : "Krankenkassen",
            "id" : "2",
            "name" : "Krankenkassen",
            "branche_id" : "1",
            "beschreibung" : "Krankenkassen, Dachorganisationen KK, Unterorganisationen KK",
            "branche" : "Gesundheit",
            "kommission_id" : "1",
            "kommission" : "Kommissionen f\u00fcr soziale Sicherheit und Gesundheit (SGK)"
          },
          …
         {
            "anzeige_name" : "Dienstleistungen",
            "id" : "88",
            "name" : "Dienstleistungen",
            "branche_id" : "1",
            "beschreibung" : "Firmen mit Dienstleistungen explizit f\u00fcr das Gesundheitswesen, z.B. IT-L\u00f6sungen.",
            "branche" : "Gesundheit",
            "kommission_id" : "1",
            "kommission" : "Kommissionen f\u00fcr soziale Sicherheit und Gesundheit (SGK)"
          } ]
    }

Response
--------

A data interface call returns always a JSON response of the same base structure.

    {
      "success" : false,
      "count" : 0,
      "message" : "",
      "sql" : "",
      "source": "",
      "data" : null
    }

Description:

key | value | description
- | - | -
success | true or false | True if call is successful
count | int >= 0 | Number of records, 0 in case of errors, never null
message | string | Messages, e.g. error messages, never null
sql | string | SQL used in this call, never null
source | DB data source | Name of view, the prefix `v_` in the DB is omitted
data | array | Data of the call, data can be nested, null in case of errors or if nothing is found

Calls
-----

The calls to the data interface are following a base structure.

Example call:  
`http://lobbywatch.dev/de/data/interface/v1/json/table/parlamentarier/flat/id/1`

Description of the example call path:

* `http://lobbywatch.dev`: Server name
* `de`: Language of the query, currently only `de`
* `data/interface`: Base path of the data interface
* `v1`: Version of the interface, currently only `v1`
* `json`: Type of the interface, currently only `json`
* `table`: Type of query, currently `table` or `relation`
* `parlamentarier`: Name of the DB table
* `flat`: Type of response data structure, currently `flat` or `aggregated`
* `id`: Specifies query by id
* `1`: Id to use

### Tables

Lobbywatch.ch tables can be queried in several ways. The interfaces access the corresponding views of the tables.
The views enrich the tables and make their usage more convenient.

#### Flat data

Query for one record by id:

`http://lobbywatch.dev/de/data/interface/v1/json/table/$table/flat/id/%`

Query for a list of records (see [filtering](#filtering) below):
`http://lobbywatch.dev/de/data/interface/v1/json/table/$table/flat/list`

Query for a list of records by name (see [filtering](#filtering) below):
`http://lobbywatch.dev/de/data/interface/v1/json/table/$table/flat/list/%`

where `$table` is one of the following tables:

* `branche`: Branche
* `interessenbindung`: Interessenbindung
* `interessengruppe`: Lobbygruppe
* `in_kommission`: In Kommission
* `kommission`: Kommission
* `mandat`: Mandat
* `organisation`: Organisation
* `organisation_beziehung`: Organisation Beziehung
* `organisation_jahr`: Organisationsjahr
* `parlamentarier`: Parlamentarier
* `partei`: Partei
* `fraktion`: Fraktion
* `rat`: Rat
* `kanton`: Kanton
* `kanton_jahr`: Kantonjahr
* `zutrittsberechtigung`: Zutrittsberechtigter

`%` is the placeholder for query data, e.g. the id or the name

#### Aggregated data

Query for one aggreaged record by id:

`http://lobbywatch.dev/de/data/interface/v1/json/table/$table/aggregated/id/%`

where `$table` is one of the following tables:

* `parlamentarier`: Parlamentarier
* `zutrittsberechtigung`: Zutrittsberechtigter

`%` is the placeholder for query data, e.g. the id

### Relations

Query relations (see [filtering](#filtering) below):

`http://lobbywatch.dev/de/data/interface/v1/json/relation/$relation/flat/list`

where `$relation` is one of the following views:

* `in_kommission_liste`: Kommissionen für Parlamenterier
* `interessenbindung_liste`: Interessenbindung eines Parlamenteriers
* `interessenbindung_liste_indirekt`: Indirekte Interessenbindungen eines Parlamenteriers
* `zutrittsberechtigung_mandate`: Mandate einer Zutrittsberechtigung (INNER JOIN)
* `zutrittsberechtigung_mit_mandaten`: Mandate einer Zutrittsberechtigung (LFET JOIN)
* `zutrittsberechtigung_mit_mandaten_indirekt`: Indirekte Mandate einer Zutrittsberechtigung (INNER JOIN)
* `organisation_parlamentarier`: Parlamenterier, die eine Interessenbindung zu dieser Organisation haben
* `organisation_parlamentarier_indirekt`: Parlamenterier, die eine indirekte Interessenbindung zu dieser Organisation haben
* `organisation_parlamentarier_beide`: Parlamenterier, die eine Zutrittsberechtiung mit Mandant oder Interessenbindung zu dieser Organisation haben
* `organisation_parlamentarier_beide_indirekt`: Parlamenterier, die eine indirekte Interessenbindung oder indirekte Zutrittsberechtiung mit Mandat zu dieser Organisation haben
* `organisation_beziehung_arbeitet_fuer`: Organisationen für welche eine PR-Agentur arbeitet.
* `organisation_beziehung_mitglied_von`: Organisationen, in welcher eine Organisation Mitglied ist
* `organisation_beziehung_muttergesellschaft`: Muttergesellschaften
* `organisation_parlamentarier`: Parlamenterier, die eine Interessenbindung zu dieser Organisation haben
* `organisation_parlamentarier_indirekt`: Parlamenterier, die eine indirekte Interessenbindung zu dieser Organisation haben
* `organisation_parlamentarier_beide`: Parlamenterier, die eine Zutrittsberechtiung mit Mandant oder Interessenbindung zu dieser Organisation haben
* `organisation_parlamentarier_beide_indirekt`: Parlamenterier, die eine indirekte Interessenbindung oder indirekte Zutrittsberechtiung mit Mandat zu dieser Organisation haben
* `organisation_beziehung_auftraggeber_fuer`: Organisationen, die eine PR-Firma beauftragt haben
* `organisation_beziehung_mitglieder`: Mitgliedsorganisationen
* `organisation_beziehung_tochtergesellschaften`: Tochtergesellschaften


### Filtering {#filtering}

Records of query calls can be filtered by one or serveral fields by appending URL parameters.

Format of filters:

    filter_$field=$value

where `filter_` is the prefix and `$field` is the name of the field.

Example:

    filter_branche_id=1

Filters work for all available fields in the base query view.

### Options

Queries can be modiefied by serveral options. Some options are only available if permission is granted.


- `includeUnpublished`=1 (default): Show unpublished data? (Requires advanced permission)
- `includeInactive`=0 (default): Show historised data, e.g. retired parlamentarians? (Requires advanced permission)
- `includeConfidentialData`=0 (default): Show confidential data? (Requires advanced permission)
- `includeMetaData`=0 (default): Show meta data, e.g. from the workflow

Architecture
-----------

The data interface is written as Drupal 7 module. Paths are mangaged by the Drupal menu module (`hook_menu`).

The source of the data interface module is available on GitHub
https://github.com/scito/lobbywatch/tree/master/drupal/lobbywatch/lobbywatch_data
