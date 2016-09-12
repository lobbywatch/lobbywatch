Lobbywatch.ch Data Interface
============================

Created date: 20.07.2014  
Updated date: 29.11.2015  
Abbreviation: LWdataIF  
Data interface version: v1  
Format: JSON  
State: Production

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
      "build secs": 0.15,
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
        "symbol_url" : "http:\/\/lobbywatch.ch\/\/test\/files\/branche_symbole\/default_branche.png"
      }
    }

### Example 2: Get all lobbygroups from Branche 1

Call:  
`http://lobbywatch.ch/de/data/interface/v1/json/table/interessengruppe/flat/list?filter_branche_id=1`

JSON Response:

    {
      "success" : true,
      "count" : 13,
      "message" : "13 record(s) found",
      "sql" : "\n    SELECT interessengruppe.*\n    FROM v_interessengruppe interessengruppe\n    WHERE 1  AND interessengruppe.branche_id = 1",
      "source": "interessengruppe",
      "build secs": 0.18,
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
      "build secs": 0,
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
build secs | float | Time in seconds required to process the request
data | array | Data of the call, data can be nested, null in case of errors or if nothing is found

### Cross-origin resource sharing (CORS)

Browsers apply by default the same-origin policy for AJAX calls (XMLHttpRequest). Thus, it is by default not possible to use cross-domain webservices in Javascript.

[Cross-origin resource sharing (CORS)](http://enable-cors.org) is a mechanism that allows restricted resources on a web page to be requested from another domain outside the domain from which the resource originated.

The Lobbywatch Data Interface enables CORS for all domains.

The HTTP response header sets for JSON webservice calls:

    Access-Control-Allow-Origin: *

Please do not abuse the Lobbywatch Data Interface.

DB Calls
--------

The calls to the data interface are following a base structure.

Example call:  
`http://lobbywatch.ch/de/data/interface/v1/json/table/parlamentarier/flat/id/1`

Description of the example call path:

* `http://lobbywatch.ch`: Server name
* `de`: Language of the query, currently only `de`
* `data/interface`: Base path of the data interface
* `v1`: Version of the interface, currently only `v1`
* `json`: Type of the interface, currently only `json`
* `table`: Type of query, currently `table` or `relation` or `search`
* `parlamentarier`: Name of the DB table
* `flat`: Type of response data structure, currently `flat` or `aggregated`
* `id`: Specifies query by id
* `1`: Id to use
* `?parameter1=value1&parameter2=value2`:, e.g ?lang=fr

### Tables

Lobbywatch.ch tables can be queried in several ways. The interfaces access the corresponding views of the tables.
The views enrich the tables and make their usage more convenient.

#### Flat data

Query for one record by id:  
`http://lobbywatch.ch/de/data/interface/v1/json/table/$table/flat/id/%`

Query for a list of records (see <a href="#filtering">filtering</a> below):  
`http://lobbywatch.ch/de/data/interface/v1/json/table/$table/flat/list`

Query for a list of records by name (see <a href="#filtering">filtering</a> below):  
`http://lobbywatch.ch/de/data/interface/v1/json/table/$table/flat/list/%`

where `$table` is one of the following tables:

* `branche`: Branche
* `interessenbindung`: Interessenbindung
* `interessenbindung_jahr`: Interessenbindungsvergütung
* `interessengruppe`: Lobbygruppe
* `in_kommission`: In Kommission
* `kommission`: Kommission
* `mandat`: Mandat
* `mandat_jahr`: Mandatsvergütung
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

`http://lobbywatch.ch/de/data/interface/v1/json/table/$table/aggregated/id/%`

where `$table` is one of the following tables:

* `parlamentarier`: Parlamentarier
* `zutrittsberechtigung`: Zutrittsberechtigte
* `organisation`: Organisationen

`%` is the placeholder for query data, e.g. the id

### Relations

Query relations (see <a href="#filtering">filtering</a> below):

`http://lobbywatch.ch/de/data/interface/v1/json/relation/$relation/flat/list`

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

### Search (Entity detection)

Search for entities having a certain string:

`http://lobbywatch.ch/de/data/interface/v1/json/search/default/%`

`%` is the placeholder for search string, e.g. a name such as Novartis

Result format:

* `id`: ID of the entity
* `table_name`: Table name of the entity, aka techical name
* `page`: Entity name to construct an URL path, e.g. [`page`]/[`id`]
* `name`: Translated name of the entity
* `table_weight`: Weight of the table. This is used for sorting. Value can be ignored. It is just for completeness added.
* `weight`: Weight within the same table, e.g. historised data have a higher weight and come at the end.  Value can be ignored. It is just for completeness added.

Example:

`http://lobbywatch.ch/de/data/interface/v1/json/search/default/Ges?limit=5&lang=de`

Result:

    {
    
        "success": true,
        "count": 5,
        "message": "5 record(s) found ",
        "sql": "\n      SELECT id, page, table_name, name_de, table_weight, weight\n      -- , freigabe_datum, bis\n      FROM v_search_table\n      WHERE\n      search_keywords_de LIKE :str  AND (table_name='parlamentarier' OR table_name='zutrittsberechtigung' OR freigabe_datum <= NOW())\n    ORDER BY table_weight, weight LIMIT 5 ;",
        "source": null,
        "build secs": 0.08,
        "data": 
        [
          {
              "id": "245",
              "page": "parlamentarier",
              "table_name": "parlamentarier",
              "name": "Theiler, Georges, SR, FDP, LU",
              "table_weight": "-20",
              "weight": "-43"
          
          },
          {
              "id": "48",
              "page": "zutrittsberechtigter",
              "table_name": "zutrittsberechtigung",
              "name": "Spicher, Georges",
              "table_weight": "-15",
              "weight": "-22"
          
          },
          {
              "id": "1",
              "page": "branche",
              "table_name": "branche",
              "name": "Gesundheit",
              "table_weight": "-10",
              "weight": "0"
          
          },
          {
              "id": "53",
              "page": "lobbygruppe",
              "table_name": "interessengruppe",
              "name": "Arbeitnehmerorganisationen",
              "table_weight": "-5",
              "weight": "0"
          
          },
          {
              "id": "138",
              "page": "lobbygruppe",
              "table_name": "interessengruppe",
              "name": "Architektur",
              "table_weight": "-5",
              "weight": "0"
          }
        ]
    }


### Fields

Informations about fields

#### freigabe_datum

The `freigabe_datum` meta field gives the state of the record.

* `null`: not yet public, only listed for completness
* < now: published at the freigabe_datum
* > now: is public after freigabe_datum

#### anzeige_name

The `anzeige_name` is a formatted name of the record. This name is localized depending on the languge, see in chapter
language.

#### *_unix

The fields ending with `*_unix` contain the date in the UNIX date format, seconds since 01.01.1970.

#### erfasst

If `erfasst` is `false` means the Parlamentarier, is not entered. This field is set to `false`, if it is known, that
the Parlamentarier will not be available anymore for the parliament in the next election. The value `erfasst` is only
fully reliable if the `freigabe_datum` is set.

### Special Queries

Special queries (see <a href="#filtering">filtering</a> below):

#### Parlament-Partei

Parteien mit den Parlamentarieren und deren Anzahl Verbindugnen.

`http://lobbywatch.ch/de/data/interface/v1/json/query/parlament-partei/aggregated/list`

Example Name:

    http://lobbywatch.ch/de/data/interface/v1/json/query/parlament-partei/aggregated/list?limit=10&select_fields=parlamentarier.anzeige_name

Example number of interessenbindungen of parlamentarier (language depenedet:

    http://lobbywatch.ch/de/data/interface/v1/json/query/parlament-partei/aggregated/list?lang=fr&limit=none&select_fields=parlamentarier.anzeige_name,parlamentarier.anzahl_interessenbindung_tief,parlamentarier.anzahl_interessenbindung_mittel,parlamentarier.anzahl_interessenbindung_hoch,parlamentarier.kommissionen_abkuerzung_de,parlamentarier.kommissionen_abkuerzung_fr,parlamentarier.rat_de,parlamentarier.rat_fr,parlamentarier.freigabe_datum

### <span id="filtering">Filtering</span>


Records of query calls can be filtered by one or serveral fields by appending URL parameters.

Filters work for all available fields in the base query view.

Format of filters:

#### Simple field

    filter_$field=$value

where `filter_` is the prefix, `$field` is the name of the field and `$value` is the value.

Example:

    filter_branche_id=1

#### List

    filter_${field}_list=$value

where `filter_` is the prefix, `_list` ist the suffix, `$field` is the name of the field and `$value` is a comma separated list of values.

Example:

    filter_branche_id_list=1,2,3

Filters work for all available fields in the base query view.

#### Like

    filter_${field}_like=$value

where `filter_` is the prefix, `_like` ist the suffix, `$field` is the name of the field and `$value` is filter value which can contain `%` (matches any number of characters, even zero charachters) or `_` (matches exactly one character).

Example:

    filter_kommissionen=%SGK%

Filters work for all available fields in the base query view.

### Limit results

The number of results can be limited. The default is 10.

    limit=25

Call:  
`http://lobbywatch.ch/de/data/interface/v1/json/table/parlamentarier/flat/list?limit=25`

The parameter `limit`limits the number of results to the number.

    limit=none

`limit=none` excludes the `LIMIT` SQL statement.

### Fields select

The fields to be returned can be given in a parameter comma separeted list. The list must not contain any spaces. The id is always included.

    select_fields=nachname
    select_fields=nachname,vorname
    select_fields=parlamentarier.nachname,parlamentarier.vorname
    select_fields=parlamentarier.*
    select_fields=name_de,name_fr
    select_fields=*

Call:  
`http://lobbywatch.ch/de/data/interface/v1/json/table/parlamentarier/flat/list?select_fields=parlamentarier.nachname,parlamentarier.vorname`

Note: For a correct working, always the fields of all languages must be selected, e.g `name_de` and `name_fr`, sometimes the german field name is without suffix, e.g `name`.

### Language

Data are only returned for one language. If the language parameter `lang` is not set, German is the default.

Example:

    ?lang=fr
    ?lang=de

### Options / Parameters

Queries can be modiefied by serveral options. Some options are only available if permission is granted.

- `includeUnpublished`=1 (default): Show unpublished data? (Requires advanced permission)
- `includeInactive`=0 (default): Show historised data, e.g. retired parlamentarians? (Requires advanced permission)
- `includeConfidentialData`=0 (default): Show confidential data? (Requires advanced permission)
- `includeMetaData`=0 (default): Show meta data, e.g. from the workflow

## Webservice Calls

The Lobbywatch Data Interface provides proxy webservice calls to third-party webservices. Due to the same-origin policy in browsers it is not possible to directly call third-party webservices with AJAX or SOAP.

The webservice interface for calling third-party webservices is similar to the Lobbywatch DB interface.

The base webservice call for querying one record by uid:

    http://lobbywatch.ch/de/data/interface/v1/json/ws/$ws/flat/uid/%

where `$ws` is one of the following webservices:

* `uid`: UID-Register webservice of Bundesamt für Statistik (BfS)
* `zefix`: Zefix webservice (Handelsregister, Zentraler Firmenindex) (not yet implemented)

`%` is the placeholder for the UID, either a 9-digit UID number or a `CHE-000.000.000`

### UID-Register Webservice of Bundesamt für Statistik (BFS)

The UID can be given as 9-digit UID number or as CHE-000.000.000.

The JSON response is given in the same base structure as for the DB interface.

Calls:  
`http://lobbywatch.ch/de/data/interface/v1/json/ws/uid/flat/uid/CHE-107.810.911`  
`http://lobbywatch.ch/de/data/interface/v1/json/ws/uid/flat/uid/107810911`

JSON Response:

    {
        "success": true,
        "count": 12,
        "message": "",
        "sql": "uid=107810911 | wsdl=https://www.uid-wse.admin.ch/V3.0/PublicServices.svc?wsdl",
        "source": "uid",
        "build secs": 1.11,
        "data":
        {
            "uid": "CHE-107.810.911",
            "uid_zahl": "107810911",
            "name_de": "Schweizerischer Nationalfonds zur Förderung der wissenschaftlichen Forschung",
            "rechtsform_handelsregister": "0110",
            "rechtsform": "Stiftung",
            "adresse_strasse": "Wildhainweg 3",
            "adresse_zusatz": null,
            "ort": "Bern",
            "adresse_plz": 3012,
            "land_iso2": "CH",
            "land_id": "191",
            "register_kanton": "BE"
        }
    }

Reference:

* [UID-Register Website](http://www.bfs.admin.ch/bfs/portal/de/index/themen/00/05/blank/03/03/04.html)
* [UID-Register Webservice Schnittstelle 3.0 PDF](http://www.bfs.admin.ch/bfs/portal/de/index/themen/00/05/blank/03/03/04.Document.139962.pdf)
* Web interface example: https://www.uid.admin.ch/Detail.aspx?uid_id=CHE-107.810.911
* Webservice standard: SOAP 1.1
* [SOAP WSDL](https://www.uid-wse.admin.ch/V3.0/PublicServices.svc?wsdl)
* Base URL: https://www.uid-wse.admin.ch/V3.0/PublicServices.svc
* No login is required for public services
* This webservice is run by the Bundesamt für Statistik (BFS).

### Zefix webservice (Handelsregister, Zentraler Firmenindex)

The UID can be given as 9-digit UID number or as CHE-000.000.000.

The JSON response is given in the same base structure as for the DB interface.

The Zefix webservice is not public. The access is protected by an access key. It is has to be added as paremter access_key.

Calls:  
`http://lobbywatch.ch/de/data/interface/v1/json/ws/zefix/flat/uid/CHE-107.810.911?access_key=ACCESS_KEY`  
`http://lobbywatch.ch/de/data/interface/v1/json/ws/zefix/flat/uid/107810911?access_key=ACCESS_KEY`

JSON Response:
    {
        "success": true,
        "count": 1,
        "message": "",
        "sql": "uid=107810911 | wsdl=http://lobbywatch.ch/sites/lobbywatch.ch/app/common/ZefixService16.wsdl",
        "source": "zefix",
        "build secs": 0.48,
        "data":
        {
            "uid": "CHE-107.810.911",
            "uid_zahl": 107810911,
            "alte_hr_id": "CH03570104919",
            "name": "Schweizerischer Nationalfonds zur Förderung der wissenschaftlichen Forschung",
            "name_de": "Schweizerischer Nationalfonds zur Förderung der wissenschaftlichen Forschung",
            "rechtsform_handelsregister": "0110",
            "rechtsform": "Stiftung",
            "rechtsform_zefix": 7,
            "adresse_strasse": "Wildhainweg 3",
            "adresse_zusatz": null,
            "ort": "Bern",
            "adresse_plz": 3012,
            "land_iso2": "CH",
            "land_id": "191",
            "handelsregister_url": "https://be.chregister.ch/cr-portal/auszug/zefix.xhtml?uid=107810911&lang=de",
            "handelsregister_ws_url": "http://ch.powernet.ch/webservices/tnet/HRG/HRG.asmx/getHRG?chnr=CH03570104919&amt=036&toBeModified=0&validOnly=0&lang=1&sort=0",
            "zweck": "Förderung der wissenschaftlichen Forschung in der Schweiz usw.",
            "register_kanton": "BE"
        }
    }

Reference:

* [Zefix-Webservice Website](https://www.e-service.admin.ch/wiki/display/openegovdoc/Zefix+Webservice)
* [Zefix Schnittstelle](https://www.e-service.admin.ch/wiki/display/openegovdoc/Zefix+Schnittstelle)
* [Zefix Schnittstelle v6.2 PDF](https://www.e-service.admin.ch/wiki/download/attachments/44827026/Zefix+Webservice+Schnittstelle_%28v6.2%29.pdf?version=2&modificationDate=1428392210000)
* Web interface example: http://zefix.ch/WebServices/Zefix/Zefix.asmx/SearchFirm?id=CHE-107.810.911&language=1
* Webservice standard: SOAP 1.1
* [SOAP WSDL](https://www.e-service.admin.ch/wiki/download/attachments/44827026/ZefixService.wsdl?version=2&modificationDate=1428391225000)
* [XML-Schema](https://www.e-service.admin.ch/wiki/download/attachments/44827026/ZefixService.xsd?version=2&modificationDate=1428391225000)
* Base URL: http://www.e-service.admin.ch/ws-zefix-1.6/ZefixService
* Login is always required
* This webservice is run by Eidgenössisches Amt für das Handelsregister.

D3 JavaScript Example
---------------------

[D3](https://d3js.org/) Example which shows a visulization of published Parlamentarier in function of time.

### HTML Code

```HTML
<div id="d3-parlamentarier-erfasst-graphic" class="parlamentarier-erfasst"/>
<script>jQuery(document).ready(function() {
  parlamentarierErfasst("#d3-parlamentarier-erfasst-graphic");
});</script>
```

### JavaScript


```js
function parlamentarierErfasst(graphicIdName) {

  // Template: http://bl.ocks.org/mbostock/3883245
  var margin = {top: 20, right: 20, bottom: 30, left: 50},
      width = jQuery(graphicIdName).width() - margin.left - margin.right,
      height = 250 - margin.top - margin.bottom;

  // 2014-09-16 00:00:00
  var parseDate = d3.time.format("%Y-%m-%d %X").parse;

  var startDate = parseDate('2014-01-01 00:00:00');

  var x = d3.time.scale()
      .range([0, width]);

  var y = d3.scale.linear()
      .range([height, 0]);

  var xAxis = d3.svg.axis()
      .scale(x)
      .orient("bottom")
      .ticks(d3.time.year, 1)
      .tickFormat(d3.time.format("%Y"));

  var yAxis = d3.svg.axis()
      .scale(y)
      .orient("left")
      .tickValues([50, 100, 150, 200, 246]);

  var line = d3.svg.line()
      .x(function(d) { return x(d.date); })
      .y(function(d) { return y(d.released); })
      .interpolate("step-after");

  var svg = d3.select(graphicIdName).append("svg")
      .attr("width", width + margin.left + margin.right)
      .attr("height", height + margin.top + margin.bottom)
    .append("g")
      .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

  d3.json("https://lobbywatch.ch/de/data/interface/v1/json/table/parlamentarier/flat/list?limit=600&select_fields=freigabe_datum,im_rat_bis", function(error, rawdata) {
    if (error) throw error;

    var nesteddata = d3.nest()
      .key(function(d) { return d.freigabe_datum; })
      .sortKeys(d3.ascending)
      .rollup(function(leaves) { var nReleased = 0; leaves.forEach(function(d) { if (d.im_rat_bis == null) {nReleased++}}); return nReleased; })
      .entries(rawdata.data);

    var numReleased = 0;
    nesteddata.forEach(function(d) {
      d.date = parseDate(d.key);
      if (d.date != null) {
        numReleased += +d.values;
      }
      d.released = numReleased;
    });

    var data = nesteddata;

    // Filter unreleased parlamentarier
    if (data[data.length - 1].date == null) {
      data.pop();
    }

    data.unshift({date: startDate, released: 0});
    data.push({date: Date.now(), released: numReleased});

    var targetData = [{date: startDate, released: 246}, {date: Date.now(), released: 246}]

    x.domain(d3.extent(data, function(d) { return d.date; }));
    y.domain([0, 246]);

    svg.append("g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + height + ")")
        .call(xAxis);

    svg.append("g")
        .attr("class", "y axis")
        .call(yAxis)
      .append("text")
        .attr("transform", "rotate(-90)")
        .attr("y", 6)
        .attr("dy", ".71em")
        .style("text-anchor", "end")
        .text("");

    svg.append("path")
        .datum(data)
        .attr("class", "line")
        .attr("d", line);

    svg.append("path")
        .datum(targetData)
        .attr("class", "line")
        .style("stroke-dasharray", ("3, 3"))
        .attr("d", line);

  });
}
```
Source: [parlamentarier_erfasst.js](https://github.com/scito/lobbywatch/blob/master/drupal/lobbywatch/js/parlamentarier_erfasst.js)

Architecture
------------

The data interface is written as Drupal 7 module. Paths are mangaged by the Drupal menu module (`hook_menu`).

The source of the data interface module is available on GitHub
https://github.com/scito/lobbywatch/tree/master/drupal/lobbywatch/lobbywatch_data

Reference
---------

http://goessner.net/articles/JsonPath/
