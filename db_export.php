<?php
declare(strict_types=1);

// Run: php -d memory_limit=256M -f db_export.php -- -c -f -v

/*
# ./deploy.sh -b -B -p
# ./run_local_db_script.sh lobbywatchtest prod_bak/`cat prod_bak/last_dbdump_data.txt`

./db_prod_to_local.sh lobbywatchtest
export SYNC_FILE=sql/ws_uid_sync_`date +"%Y%m%d"`.sql; php -f ws_uid_fetcher.php -- -a --ssl -v1 -s | tee $SYNC_FILE; less $SYNC_FILE
./run_local_db_script.sh lobbywatchtest $SYNC_FILE
./deploy.sh -r -s $SYNC_FILE
./deploy.sh -p -r -s $SYNC_FILE
*/

// TODO optimize cartesian with freigabe
// TODO explain, replace views with original tables, remove select fields
// TODO refactor extract const from code, make const, use keyword const
// TODO add french columns to exports, add filter to get only german export
// TODO export de, export fr, compined export?
// TODO welche Daten mit welchem Tool, Übersicht machen
// TODO generate meta file with date, git hash for reproduction
// TODO JanusGraph import
// TODO TigerGraph ETL CSV import (not open source)
// TODO Check Graph DBs: Amazon Neptune, Oracle PGX, Neo4j Server, SAP HANA Graph, AgensGraph (over PostgreSQL), Azure CosmosDB, Redis Graph, SQL Server 2017 Graph, Cypher for Apache Spark, Cypher for Gremlin, SQL Property Graph Querying, TigerGraph, Memgraph, JanusGraph, DSE Graph
// DONE GraphML (http://graphml.graphdrawing.org/primer/graphml-primer.html)
// TODO csv raw and csv relations replaced, use abbreviation for party, kanton, rat, ...
// TODO XML (Excel 2003, SpreadsheetML): https://github.com/PHPOffice/PhpSpreadsheet, https://en.wikipedia.org/wiki/Microsoft_Office_XML_formats, https://phpspreadsheet.readthedocs.io/en/latest/
// TODO unify exported field names
// DONE MySQL Export PHP https://github.com/ifsnop/mysqldump-php/blob/master/src/Ifsnop/Mysqldump/Mysqldump.php
// DONE JSON lines JSONL format support (http://jsonlines.org/) like CSV
// DONE support Arango DB https://www.arangodb.com/docs/stable/programs-arangoimport-details.html
// TODO add yaml for markdown
// DONE export YAML (https://yaml.org/, https://www.php.net/manual/en/book.yaml.php, https://github.com/EvilFreelancer/yaml-php)
// TODO Generate XML Schema from XML file (reverse engineer) (https://www.dotkam.com/2008/05/28/generate-xsd-from-xml/)
// TODO option to refresh views before exporting
// TODO refactor table_meta access, avoid multiple calls
// TODO preprocess table_meta data for performance
// TODO replace aktiv by exression (for NOW() replacement)
// TODO replace NOW() by $variable
// TODO refactor table and col alias handling, write easy functions, improve DX
// TODO one function to split fields
// TODO uniformize names
// TODO hist should also clean jahr entries and stichdatum entries
// DONE Prio Dim 1 (formats): CSV (cartesian_essential, flat), SQL, GraphML, SQL, Neo4j, Arango, OrientDB, JSON
// TODO Prio Dim 2 (aggregation): parlamentarier, organisation, interessengruppe, branche, kommission
// TODO PHPUnit for refactorings
// TODO add flag, automatically prefix fields with table names instead of doing manually, "expand alias", (allow overruling)
// TODO support docu_de, docu_fr

require_once dirname(__FILE__) . '/public_html/settings/settings.php';
require_once dirname(__FILE__) . '/public_html/common/utils.php';

ini_set('memory_limit','512M');

global $intern_fields;

$intern_fields = ['notizen', 'freigabe_visa', 'created_date', 'created_date_unix', 'created_visa', 'updated_date', 'updated_date_unix', 'updated_visa', 'autorisiert_datum',  'autorisiert_datum_unix', 'autorisierung_verschickt_visa', 'autorisierung_verschickt_datum', 'eingabe_abgeschlossen_datum', 'kontrolliert_datum', 'autorisierung_verschickt_datum_unix', 'eingabe_abgeschlossen_datum_unix', 'kontrolliert_datum_unix', 'autorisiert_visa', 'eingabe_abgeschlossen_visa', 'kontrolliert_visa', 'symbol_abs', 'photo', 'ALT_kommission', 'ALT_parlam_verbindung', 'email', 'telephon_1', 'telephon_2', 'adresse_strasse', 'adresse_zusatz', 'anzahl_interessenbindungen', 'anzahl_hauptberufliche_interessenbindungen', 'anzahl_nicht_hauptberufliche_interessenbindungen', 'anzahl_abgelaufene_interessenbindungen', 'anzahl_interessenbindungen_alle', 'anzahl_erfasste_verguetungen', 'anzahl_erfasste_hauptberufliche_verguetungen', 'anzahl_erfasste_nicht_hauptberufliche_verguetungen', 'verguetungstransparenz_berechnet', 'verguetungstransparenz_berechnet_nicht_beruflich', 'verguetungstransparenz_berechnet_alle', 'parlamentarier_lobbyfaktor', 'ALT_branche_id'];

const EOL = "\n";

const DOCU = "docu";
/**
 * Export tables/views configuration array.
 *
 * Key (tkey): string, Key, used as filename, table name if no view or table are provided
 * table (optional): string, table name,
 * view (optional): string, view to use instead of the table
 * hist_field (optional): null, string or array, temporal fields for end date for historised records
 * remove_cols (optional): array, colums to remove
 * select_cols (optional): array, select only these fields, otherwise all fields are automatically selected, refers only the the main query table, not joined tables, use additional_join_cols for cols of joined tables
 *    Fields starting with "(" are treated as expressions, e.g. "(CONCAT(nachname, ', ', vorname)) name"
 * name (optional): string, name to use in additional column
 * start_id (optional): string, to build an directed edge, this is the field containing the starting id
 * end_id (optional): string, string, to build an directed edge, this is the field containing the destination id
 * join (optional): string, tables to join, table/view alias is separted by a space, none of the fields are added automatially, use additional_join_cols
 * additional_join_cols (optional): array, fields of the joined table to export, field alias is separted by a space
 * freigabe_datum (optional): string, field denoting published date, default freigabe_datum
 * id (optional): string, field denoting ID, default id
 * order_by (optional): string, order by field
 * slow (optional): 0-3, indication of slowliness of this export, 0 fast, 3 very slow
 * transform_field (optional): array, field => transform function, [0..9] => transform function (called for all fields), transform_function($val, $key, $data_type, $exporter, $format, $level, $table_key, $table)
 * docu (optional): array, description of this dataset
 * not_as_attribute (optional): array, fields that must not be exported as attributes (used for XML)
 * aggregated_tables (optional): array
 * - Key (tkey): string, name of the aggregated table, table name if no view or table are provided
 * - view (optional), string, see above
 * - hist_field (optional), null, string, array, see above
 * - parent_id (optional): string, relation field in this child record to the parent record
 * - id_in_parent (optional): string or array, relation field in the parent record having the id of this child record
 * - remove_cols (optional): array, see above
 * - freigabe_datum (optional): string, see above
 * - order_by (optional): string, see above
 * - transform_field (optional): array, field => transform function, [0..9] => transform function (called for all fields), transform_function($val, $key, $data_type, $exporter, $format, $level, $table_key, $table)
 * - not_as_attribute (optional): array, fields that must not be exported as attributes (used for XML)
 */

// TODO use YAML for config https://symfony.com/doc/current/components/yaml.html
$aggregated_tables = [
  // TODO 'interessengruppe' => ['view' => 'v_interessengruppe_simple', 'hist_field' => null, 'remove_cols' => []],
  // TODO 'organisation' => ['view' => 'v_organisation_simple', 'hist_field' => null, 'remove_cols' => []],
  // TODO add CDATA fields for xml
  // TODO use table as view name
  // TODO parlamentarier_aggregated fix YAML
  'essential_parlamentarier_nested' => ['display_name' => 'Parlamentarier', 'view' => 'v_parlamentarier_medium_raw', 'hist_field' => 'im_rat_bis', 'remove_cols' => [], 'docu' => ['Datensatz mit den wesentlichen Daten über Parlamentarier als geschachtelte Struktur.'], 'aggregated_tables' => [
    'in_kommission' => ['view' => 'v_in_kommission_liste', 'parent_id' => "parlamentarier_id", 'order_by' => 'von', 'hist_field' => 'bis', 'remove_cols' => [], 'transform_field' => ['sachbereiche' => 'transform_sachbereiche']],
    'parlamentarier_transparenz' => ['view' => 'v_parlamentarier_transparenz', 'parent_id' => "parlamentarier_id", 'order_by' => 'stichdatum', 'hist_field' => '', 'remove_cols' => []],
    'interessenbindungen' => ['view' => 'v_interessenbindung_medium_raw', 'parent_id' => "parlamentarier_id", 'order_by' => 'von', 'hist_field' => 'bis', 'remove_cols' => [],
      'aggregated_tables' => [
        'verguetungen' => ['view' => 'v_interessenbindung_jahr', 'parent_id' => "interessenbindung_id", 'order_by' => 'jahr', 'hist_field' => '', 'remove_cols' => []],
        'organisation' => ['view' => 'v_organisation_medium_raw', 'parent_id' => null, 'id_in_parent' => 'organisation_id', 'order_by' => null, 'hist_field' => '', 'remove_cols' => [],
        // 'aggregated_tables' => [
        //   'interessengruppe' => ['view' => 'v_interessengruppe_simple', 'parent_id' => null, 'id_in_parent' => ['interessengruppe_id', 'interessengruppe2_id', 'interessengruppe3_id'], 'order_by' => null, 'hist_field' => '', 'remove_cols' => [],
        //   'aggregated_tables' => [
        //     'branche' => ['view' => 'v_branche_simple', 'parent_id' => null, 'id_in_parent' => ['branche_id'], 'order_by' => null, 'hist_field' => '', 'remove_cols' => [],
        //     'aggregated_tables' => [
        //       'kommissionen' => ['view' => 'v_kommission', 'parent_id' => null, 'id_in_parent' => ['kommission_id', 'kommission2_id'], 'order_by' => null, 'hist_field' => '', 'remove_cols' => [],
        //       'aggregated_tables' => [
        //       ]],
        //       ]],
        //     ]],
        //   ]
        ],
      ],
    ],
    // TODO interessengruppen flach
    'zutrittsberechtigungen' => ['view' => 'v_zutrittsberechtigung_raw', 'parent_id' => "parlamentarier_id", 'order_by' => 'von', 'hist_field' => 'bis', 'remove_cols' => [],
    'aggregated_tables' => [
      'mandate' => ['view' => 'v_mandat_medium_raw', 'parent_id' => "person_id", 'order_by' => 'von', 'hist_field' => 'bis', 'remove_cols' => [],
        'aggregated_tables' => [
          'verguetungen' => ['view' => 'v_mandat_jahr', 'parent_id' => "mandat_id", 'order_by' => 'jahr', 'hist_field' => '', 'remove_cols' => []],
          'organisation' => ['view' => 'v_organisation_medium_raw', 'parent_id' => null, 'id_in_parent' => 'organisation_id', 'order_by' => null, 'hist_field' => '', 'remove_cols' => [],
          // 'aggregated_tables' => [
          //   'interessengruppe' => ['view' => 'v_interessengruppe_simple', 'parent_id' => null, 'id_in_parent' => ['interessengruppe_id', 'interessengruppe2_id', 'interessengruppe3_id'], 'order_by' => null, 'hist_field' => '', 'remove_cols' => [],
          //   'aggregated_tables' => [
          //     'branche' => ['view' => 'v_branche_simple', 'parent_id' => null, 'id_in_parent' => ['branche_id'], 'order_by' => null, 'hist_field' => '', 'remove_cols' => [],
          //     'aggregated_tables' => [
          //       'kommissionen' => ['view' => 'v_kommission', 'parent_id' => null, 'id_in_parent' => ['kommission_id', 'kommission2_id'], 'order_by' => null, 'hist_field' => '', 'remove_cols' => [],
          //       'aggregated_tables' => [
          //       ]],
          //       ]],
          //     ]],
          //   ]
          ],
        ],
      ],
    ],
  ],
]],
  'parlamentarier_nested' => ['display_name' => 'Parlamentarier', 'slow' => 3, 'view' => 'v_parlamentarier_medium_raw', 'hist_field' => 'im_rat_bis', 'remove_cols' => [],
  'aggregated_tables' => [
    'in_kommission' => ['view' => 'v_in_kommission_liste', 'parent_id' => "parlamentarier_id", 'order_by' => 'von', 'hist_field' => 'bis', 'remove_cols' => [], 'transform_field' => ['sachbereiche' => 'transform_sachbereiche']],
    'parlamentarier_transparenz' => ['view' => 'v_parlamentarier_transparenz', 'parent_id' => "parlamentarier_id", 'order_by' => 'stichdatum', 'hist_field' => '', 'remove_cols' => []],
    'interessenbindungen' => ['view' => 'v_interessenbindung_medium_raw', 'parent_id' => "parlamentarier_id", 'order_by' => 'von', 'hist_field' => 'bis', 'remove_cols' => [],
      'aggregated_tables' => [
        'verguetungen' => ['view' => 'v_interessenbindung_jahr', 'parent_id' => "interessenbindung_id", 'order_by' => 'jahr', 'hist_field' => '', 'remove_cols' => []],
        'organisation' => ['view' => 'v_organisation_simple', 'parent_id' => null, 'id_in_parent' => 'organisation_id', 'order_by' => null, 'hist_field' => '', 'remove_cols' => [],
        'aggregated_tables' => [
          'interessengruppe' => ['view' => 'v_interessengruppe_simple', 'parent_id' => null, 'id_in_parent' => ['interessengruppe_id', 'interessengruppe2_id', 'interessengruppe3_id'], 'order_by' => null, 'hist_field' => '', 'remove_cols' => [],
          'aggregated_tables' => [
            'branche' => ['view' => 'v_branche_simple', 'parent_id' => null, 'id_in_parent' => ['branche_id'], 'order_by' => null, 'hist_field' => '', 'remove_cols' => [],
            'aggregated_tables' => [
              'kommissionen' => ['view' => 'v_kommission', 'parent_id' => null, 'id_in_parent' => ['kommission_id', 'kommission2_id'], 'order_by' => null, 'hist_field' => '', 'remove_cols' => [],
              'aggregated_tables' => [
              ]],
              ]],
            ]],
          ]],
      ],
    ],
    // TODO interessengruppen flach
    'zutrittsberechtigungen' => ['view' => 'v_zutrittsberechtigung_raw', 'parent_id' => "parlamentarier_id", 'order_by' => 'von', 'hist_field' => 'bis', 'remove_cols' => [],
      'aggregated_tables' => [
        'mandate' => ['view' => 'v_mandat_medium_raw', 'parent_id' => "person_id", 'order_by' => 'von', 'hist_field' => 'bis', 'remove_cols' => [],
          'aggregated_tables' => [
            'verguetungen' => ['view' => 'v_mandat_jahr', 'parent_id' => "mandat_id", 'order_by' => 'jahr', 'hist_field' => '', 'remove_cols' => []],
            'organisation' => ['view' => 'v_organisation_simple', 'parent_id' => null, 'id_in_parent' => 'organisation_id', 'order_by' => null, 'hist_field' => '', 'remove_cols' => [],
            'aggregated_tables' => [
              'interessengruppe' => ['view' => 'v_interessengruppe_simple', 'parent_id' => null, 'id_in_parent' => ['interessengruppe_id', 'interessengruppe2_id', 'interessengruppe3_id'], 'order_by' => null, 'hist_field' => '', 'remove_cols' => [],
              'aggregated_tables' => [
                'branche' => ['view' => 'v_branche_simple', 'parent_id' => null, 'id_in_parent' => ['branche_id'], 'order_by' => null, 'hist_field' => '', 'remove_cols' => [],
                'aggregated_tables' => [
                  'kommissionen' => ['view' => 'v_kommission', 'parent_id' => null, 'id_in_parent' => ['kommission_id', 'kommission2_id'], 'order_by' => null, 'hist_field' => '', 'remove_cols' => [],
                  'aggregated_tables' => [
                  ]],
                  ]],
                ]],
              ]],
          ],
        ],
      ],
    ],
  ]],
  ];

// :ID(partei_id) :LABEL (separated by ;) :IGNORE
// --nodes[:Label1:Label2]=<"headerfile,file1,file2,…​">
// --id-type=<STRING|INTEGER|ACTUAL>
$nodes = [
  'partei' => ['table' => 'partei', 'view' => 'v_partei', 'name' => 'Partei', 'hist_field' => null, 'remove_cols' => []],
  'branche' => ['table' => 'branche', 'view' => 'v_branche_simple', 'name' => 'Branche', 'hist_field' => null, 'remove_cols' => ['farbcode', 'symbol_abs', 'symbol_rel', 'symbol_klein_rel', 'symbol_dateiname_wo_ext', 'symbol_dateierweiterung', 'symbol_dateiname', 'symbol_mime_type']],
  'interessengruppe' => ['table' => 'interessengruppe', 'view' => 'v_interessengruppe_simple', 'name' => 'Lobbygruppe', 'hist_field' => null, 'remove_cols' => []],
  'interessenraum' => ['table' => 'interessenraum', 'view' => 'v_interessenraum', 'name' => 'Interessenraum', 'hist_field' => null, 'remove_cols' => []],
  'kommission' => ['table' => 'kommission', 'view' => 'v_kommission', 'name' => 'Kommission', 'hist_field' => null, 'remove_cols' => []],
  'organisation' => ['table' => 'organisation', 'view' => 'v_organisation_simple', 'name' => 'Organisation', 'hist_field' => null, 'remove_cols' => []],
  'organisation_jahr' => ['table' => 'organisation_jahr', 'view' => 'v_organisation_jahr', 'name' => 'Organisationsjahr', 'hist_field' => null, 'remove_cols' => []],
  'parlamentarier' => ['table' => 'parlamentarier', 'view' => 'v_parlamentarier_simple', 'name' => 'Parlamentarier', 'hist_field' => 'im_rat_bis', 'remove_cols' => []],
  'fraktion' => ['table' => 'fraktion', 'view' => 'v_fraktion', 'name' => 'Fraktion', 'hist_field' => null, 'remove_cols' => []],
  'rat' => ['table' => 'rat', 'view' => 'v_rat', 'name' => 'Rat', 'hist_field' => null, 'remove_cols' => []],
  'kanton' => ['table' => 'kanton', 'view' => 'v_kanton_simple', 'name' => 'Kanton', 'hist_field' => null, 'remove_cols' => []],
  'kanton_jahr' => ['table' => 'kanton_jahr', 'view' => 'v_kanton_jahr', 'name' => 'Kantonjahr', 'hist_field' => null, 'remove_cols' => []],
  'person' => ['table' => 'person', 'view' => 'v_person_simple', 'name' => 'Person', 'hist_field' => null, 'remove_cols' => []],
];

// :START_ID(parlamentarier_id) :END_ID(partei_id) :TYPE :IGNORE
// --relationships[:RELATIONSHIP_TYPE]=<"headerfile,file1,file2,…​">
// JOIN all tables of relations in order to ensure that the target tables are published
// The check for published is done for all JOIN tables
$relationships = [
  'interessenbindung' => ['table' => 'interessenbindung', 'name' => 'HAT_INTERESSENBINDUNG_MIT', 'start_id' => 'parlamentarier_id', 'end_id' => 'organisation_id', 'hist_field' => ['bis', 'p.im_rat_bis'], 'remove_cols' => [],
    'join' => ['JOIN parlamentarier p ON interessenbindung.parlamentarier_id = p.id', 'JOIN organisation o ON interessenbindung.organisation_id = o.id']],
  'interessenbindung_jahr' => ['table' => 'interessenbindung_jahr',
    'join' => ['JOIN v_interessenbindung_simple i ON interessenbindung_jahr.interessenbindung_id = i.id', 'JOIN v_parlamentarier_simple p ON i.parlamentarier_id = p.id', 'JOIN organisation o ON i.organisation_id = o.id'],
    'name' => 'VERGUETED', 'start_id' => 'organisation_id', 'end_id' => 'parlamentarier_id', 'additional_join_cols' => ['i.parlamentarier_id', 'i.organisation_id'], 'hist_field' => ['p.im_rat_bis', 'i.bis'], 'remove_cols' => array_map(function($val) { return "interessenbindung.$val"; }, array_merge($intern_fields, ['id', 'beschreibung', 'quelle_url_gueltig', 'quelle_url', 'quelle']))],
  'in_kommission' => ['table' => 'in_kommission', 'name' => 'IST_IN_KOMMISSION', 'start_id' => 'parlamentarier_id', 'end_id' => 'kommission_id', 'hist_field' => ['bis', 'p.im_rat_bis', 'k.bis'], 'remove_cols' => [],
    'join' => ['JOIN parlamentarier p ON in_kommission.parlamentarier_id = p.id', 'JOIN kommission k ON in_kommission.kommission_id = k.id']],
  'mandat' => ['table' => 'mandat', 'name' => 'HAT_MANDAT', 'start_id' => 'person_id', 'end_id' => 'organisation_id', 'hist_field' => ['z.bis', 'p.im_rat_bis'], 'remove_cols' => [],
    'join' => ['JOIN person r ON mandat.person_id = r.id', 'JOIN zutrittsberechtigung z ON z.person_id = r.id', 'JOIN parlamentarier p ON z.parlamentarier_id = p.id', 'JOIN organisation o ON mandat.organisation_id = o.id']],
  'mandat_jahr' => ['table' => 'mandat_jahr',
    'join' => ['JOIN v_mandat_simple m ON mandat_jahr.mandat_id = m.id', 'JOIN person r ON m.person_id = r.id', 'JOIN v_zutrittsberechtigung_simple z ON z.person_id = r.id', 'JOIN v_parlamentarier_simple p ON z.parlamentarier_id = p.id', 'JOIN organisation o ON m.organisation_id = o.id'], 'name' => 'VERGUETED', 'start_id' => 'organisation_id', 'end_id' => 'person_id', 'additional_join_cols' => ['m.person_id', 'm.organisation_id'], 'hist_field' => ['m.bis', 'z.bis', 'p.im_rat_bis'], 'remove_cols' => array_map(function($val) { return "m.$val"; }, array_merge($intern_fields, ['id', 'beschreibung', 'quelle_url_gueltig', 'quelle_url', 'quelle'])),],
  'organisation_beziehung' => ['table' => 'organisation_beziehung', 'name' => 'HAT_BEZIEHUNG', 'type_col' => 'art', 'start_id' => 'organisation_id', 'end_id' => 'ziel_organisation_id', 'end_id_space' => 'organisation_id', 'hist_field' => 'bis', 'remove_cols' => [],
    'join' => ['JOIN organisation os ON organisation_beziehung.organisation_id = os.id', 'JOIN organisation oz ON organisation_beziehung.ziel_organisation_id = oz.id']],
  'zutrittsberechtigung' => ['table' => 'zutrittsberechtigung', 'name' => 'HAT_ZUTRITTSBERECHTIGTER', 'start_id' => 'parlamentarier_id', 'end_id' => 'person_id', 'hist_field' => ['bis', 'p.im_rat_bis'], 'remove_cols' => [],
    'join' => ['JOIN parlamentarier p ON zutrittsberechtigung.parlamentarier_id = p.id', 'JOIN person r ON zutrittsberechtigung.person_id = r.id']],
  'parlamentarier_partei' => ['table' => 'parlamentarier', 'name' => 'IST_PARTEIMITGLIED_VON', 'start_id' => 'id', 'end_id' => 'partei_id', 'hist_field' => 'im_rat_bis', 'select_cols' => ['created_date'], 'remove_cols' => [],
    'join' => ['JOIN partei p ON parlamentarier.partei_id = p.id']],
  'parlamentarier_fraktion' => ['table' => 'parlamentarier', 'name' => 'IST_FRAKTIONMITGLIED_VON', 'start_id' => 'id', 'end_id' => 'fraktion_id', 'hist_field' => 'im_rat_bis', 'select_cols' => ['created_date'], 'remove_cols' => [],
    'join' => ['JOIN fraktion f ON parlamentarier.fraktion_id = f.id']],
  'parlamentarier_rat' => ['table' => 'parlamentarier', 'name' => 'IST_IM_RAT', 'start_id' => 'id', 'end_id' => 'rat_id', 'hist_field' => 'im_rat_bis', 'select_cols' => ['created_date'], 'remove_cols' => [],
    'join' => ['JOIN rat r ON parlamentarier.rat_id = r.id']],
  'parlamentarier_kanton' => ['table' => 'parlamentarier', 'name' => 'WOHNT_IM_KANTON', 'start_id' => 'id', 'end_id' => 'kanton_id', 'hist_field' => 'im_rat_bis', 'select_cols' => ['created_date'], 'remove_cols' => [],
    'join' => ['JOIN kanton k ON parlamentarier.rat_id = k.id']],
  'organisation_interessengruppe' => ['table' => 'organisation', 'name' => 'GEHOERT_ZU', 'start_id' => 'id', 'end_id' => 'interessengruppe_id', 'end_id_space' => 'interessengruppe_id', 'hist_field' => null, 'select_cols' => ['created_date'], 'remove_cols' => [],
    'join' => ['JOIN interessengruppe i ON organisation.interessengruppe_id = i.id']],
  'organisation_interessengruppe2' => ['table' => 'organisation', 'name' => 'GEHOERT_ZU', 'start_id' => 'id', 'end_id' => 'interessengruppe2_id', 'end_id_space' => 'interessengruppe_id', 'hist_field' => null, 'select_cols' => ['created_date'], 'remove_cols' => [],
    'join' => ['JOIN interessengruppe i ON organisation.interessengruppe2_id = i.id']],
  'organisation_interessengruppe3' => ['table' => 'organisation', 'name' => 'GEHOERT_ZU', 'start_id' => 'id', 'end_id' => 'interessengruppe3_id', 'end_id_space' => 'interessengruppe_id', 'hist_field' => null, 'select_cols' => ['created_date'], 'remove_cols' => [],
    'join' => ['JOIN interessengruppe i ON organisation.interessengruppe3_id = i.id']],
  'organisation_interessenraum' => ['table' => 'organisation', 'name' => 'HAT_INTERESSENRAUM', 'start_id' => 'id', 'end_id' => 'interessenraum_id', 'hist_field' => null, 'select_cols' => ['created_date'], 'remove_cols' => [],
    'join' => ['JOIN interessenraum i ON organisation.interessenraum_id = i.id']],
  'organisation_jahr' => ['table' => 'organisation_jahr', 'name' => 'ORGANISATION_HAT_IM_JAHR', 'start_id' => 'organisation_id', 'end_id' => 'id', 'end_id_space' => 'organisation_jahr_id', 'hist_field' => null, 'select_cols' => ['created_date'], 'remove_cols' => [],
    'join' => ['JOIN organisation o ON organisation_jahr.organisation_id = o.id']],
  'kanton_jahr' => ['table' => 'kanton_jahr', 'name' => 'KANTON_HAT_IM_JAHR', 'start_id' => 'kanton_id', 'end_id' => 'id', 'end_id_space' => 'kanton_jahr_id', 'hist_field' => null, 'select_cols' => ['created_date'], 'remove_cols' => [],
    'join' => ['JOIN kanton k ON kanton_jahr.kanton_id = k.id']],
  'interessengruppe_branche' => ['table' => 'interessengruppe', 'name' => 'IST_IN_BRANCHE', 'start_id' => 'id', 'end_id' => 'branche_id', 'hist_field' => null, 'select_cols' => ['created_date'], 'remove_cols' => [],
    'join' => ['JOIN branche b ON interessengruppe.branche_id = b.id']],
  'branche_kommission' => ['table' => 'branche', 'name' => 'HAT_ZUSTAENDIGE_KOMMISSION', 'start_id' => 'id', 'end_id' => 'kommission_id', 'end_id_space' => 'kommission_id', 'hist_field' => null, 'select_cols' => ['created_date'], 'remove_cols' => [],
    'join' => ['JOIN kommission k ON branche.kommission_id = k.id']],
  'branche_kommission2' => ['table' => 'branche', 'name' => 'HAT_ZUSTAENDIGE_KOMMISSION', 'start_id' => 'id', 'end_id' => 'kommission2_id', 'end_id_space' => 'kommission_id', 'hist_field' => null, 'select_cols' => ['created_date'], 'remove_cols' => [],
    'join' => ['JOIN kommission k ON branche.kommission2_id = k.id']],
];

$flat_tables = [
  'partei' => ['view' => 'v_partei', 'hist_field' => null, 'remove_cols' => []],
  'branche' => ['view' => 'v_branche_simple', 'hist_field' => null, 'remove_cols' => ['farbcode', 'symbol_abs', 'symbol_rel', 'symbol_klein_rel', 'symbol_dateiname_wo_ext', 'symbol_dateierweiterung', 'symbol_dateiname', 'symbol_mime_type']],
  'interessengruppe' => ['view' => 'v_interessengruppe_simple', 'hist_field' => null, 'remove_cols' => []],
  'interessenraum' => ['view' => 'v_interessenraum', 'hist_field' => null, 'remove_cols' => []],
  'kommission' => ['view' => 'v_kommission', 'hist_field' => null, 'remove_cols' => []],
  'organisation' => ['view' => 'v_organisation_simple', 'hist_field' => null, 'remove_cols' => []],
  'organisation_jahr' => ['view' => 'v_organisation_jahr', 'hist_field' => null, 'remove_cols' => []],
  'parlamentarier' => ['view' => 'v_parlamentarier_medium_raw', 'hist_field' => 'im_rat_bis', 'remove_cols' => ['anzeige_name_de','anzeige_name_fr', 'name_de', 'name_fr', 'parlament_interessenbindungen', 'parlament_interessenbindungen_json']],
  'fraktion' => ['view' => 'v_fraktion', 'hist_field' => null, 'remove_cols' => []],
  'rat' => ['view' => 'v_rat', 'hist_field' => null, 'remove_cols' => []],
  'kanton' => ['view' => 'v_kanton_simple', 'hist_field' => null, 'remove_cols' => []],
  'kanton_jahr' => ['view' => 'v_kanton_jahr', 'hist_field' => null, 'remove_cols' => []],
  'person' => ['view' => 'v_person_simple', 'hist_field' => null, 'remove_cols' => []],

  'interessenbindung' => ['hist_field' => ['bis', 'p.im_rat_bis'], 'remove_cols' => [], 'join' => ['JOIN parlamentarier p ON interessenbindung.parlamentarier_id = p.id']],
  'interessenbindung_jahr' => ['hist_field' => ['p.im_rat_bis', 'i.bis'], 'remove_cols' => array_map(function($val) { return "i.$val"; }, array_merge($intern_fields, ['id', 'beschreibung', 'quelle_url_gueltig', 'quelle_url', 'quelle'])), 'join' => ['JOIN interessenbindung i ON interessenbindung_jahr.interessenbindung_id = i.id', 'JOIN v_parlamentarier_simple p ON i.parlamentarier_id = p.id']],
  'in_kommission' => ['hist_field' => ['bis', 'p.im_rat_bis'], 'remove_cols' => [], 'join' => ['JOIN parlamentarier p ON in_kommission.parlamentarier_id = p.id']],
  'mandat' => ['hist_field' => ['bis', 'z.bis', 'p.im_rat_bis'], 'remove_cols' => [], 'join' => ['JOIN person r ON mandat.person_id = r.id', 'JOIN zutrittsberechtigung z ON z.person_id = r.id', 'JOIN parlamentarier p ON z.parlamentarier_id = p.id']],
  'mandat_jahr' => ['hist_field' => ['m.bis', 'z.bis', 'p.im_rat_bis'], 'remove_cols' => [], 'join' => ['JOIN v_mandat_simple m ON mandat_jahr.mandat_id = m.id', 'JOIN person r ON m.person_id = r.id', 'JOIN zutrittsberechtigung z ON z.person_id = r.id', 'JOIN parlamentarier p ON z.parlamentarier_id = p.id']],
  'zutrittsberechtigung' => ['hist_field' => ['bis', 'p.im_rat_bis'], 'remove_cols' => [], 'join' => ['JOIN parlamentarier p ON zutrittsberechtigung.parlamentarier_id = p.id']],
];

$sql_tables = [
  'kanton' => ['hist_field' => null, 'remove_cols' => []],
  'kanton_jahr' => ['hist_field' => null, 'remove_cols' => []],
  'interessenraum' => ['hist_field' => null, 'remove_cols' => []],
  'rat' => ['hist_field' => null, 'remove_cols' => []],
  'fraktion' => ['hist_field' => null, 'remove_cols' => []],
  'partei' => ['hist_field' => null, 'remove_cols' => []],
  'kommission' => ['hist_field' => null, 'remove_cols' => []],
  'branche' => ['hist_field' => null, 'remove_cols' => ['farbcode', 'symbol_abs', 'symbol_rel', 'symbol_klein_rel', 'symbol_dateiname_wo_ext', 'symbol_dateierweiterung', 'symbol_dateiname', 'symbol_mime_type']],
  'interessengruppe' => ['hist_field' => null, 'remove_cols' => []],
  'organisation' => ['hist_field' => null, 'remove_cols' => []],
  'organisation_jahr' => ['hist_field' => null, 'remove_cols' => []],
  'parlamentarier' => ['hist_field' => 'im_rat_bis', 'remove_cols' => []],
  'parlamentarier_transparenz' => ['hist_field' => null, 'remove_cols' => []],
  'person' => ['hist_field' => null, 'remove_cols' => []],

  'interessenbindung' => ['hist_field' => ['bis', 'p.im_rat_bis'], 'remove_cols' => [], 'join' => ['JOIN parlamentarier p ON interessenbindung.parlamentarier_id = p.id']],
  'interessenbindung_jahr' => ['hist_field' => ['i.bis', 'p.im_rat_bis'], 'remove_cols' => array_map(function($val) { return "i.$val"; }, array_merge($intern_fields, ['id', 'beschreibung', 'quelle_url_gueltig', 'quelle_url', 'quelle'])), 'join' => ['JOIN v_interessenbindung_simple i ON interessenbindung_jahr.interessenbindung_id = i.id', 'JOIN v_parlamentarier_simple p ON i.parlamentarier_id = p.id']],
  'in_kommission' => ['hist_field' => ['bis', 'p.im_rat_bis'], 'remove_cols' => [], 'join' => ['JOIN parlamentarier p ON in_kommission.parlamentarier_id = p.id']],
  'mandat' => ['hist_field' => ['bis', 'z.bis', 'p.im_rat_bis'], 'remove_cols' => [], 'join' => ['JOIN person r ON mandat.person_id = r.id', 'JOIN zutrittsberechtigung z ON z.person_id = r.id', 'JOIN parlamentarier p ON z.parlamentarier_id = p.id']],
  'mandat_jahr' => ['hist_field' => ['m.bis', 'z.bis', 'p.im_rat_bis'], 'remove_cols' => [], 'join' => ['JOIN v_mandat_simple m ON mandat_jahr.mandat_id = m.id', 'JOIN person r ON m.person_id = r.id', 'JOIN zutrittsberechtigung z ON z.person_id = r.id', 'JOIN parlamentarier p ON z.parlamentarier_id = p.id']],
  'zutrittsberechtigung' => ['hist_field' => ['bis', 'p.im_rat_bis'], 'remove_cols' => [], 'join' => ['JOIN parlamentarier p ON zutrittsberechtigung.parlamentarier_id = p.id']],
];

// TODO add zutrittsberechtigte
// TODO add indirekte
// TODO add combined
// TODO add zb.aktiv
// TODO add mandat.aktiv
$cartesian_tables = [
  'parlamentarier_interessenbindung' => ['view' => 'v_parlamentarier_medium_raw p', 'hist_field' => ['p.im_rat_bis', 'i.bis'], 'unpubl_fields' => ['p.erfasst'], 'remove_cols' => ['anzeige_name_de','anzeige_name_fr', 'name_de', 'name_fr', 'parlament_interessenbindungen', 'parlament_interessenbindungen_json', 'von', 'bis'], 'join' => ["LEFT JOIN v_interessenbindung_raw i ON p.id = i.parlamentarier_id", "LEFT JOIN v_interessenbindung_jahr_max ij ON ij.interessenbindung_id = i.id", "LEFT JOIN v_organisation_medium_raw o ON o.id = i.organisation_id"], 'additional_join_cols' => [
    'i.beschreibung interessenbindung_beschreibung', 'i.von interessenbindung_von', 'i.bis interessenbindung_bis', 'i.art interessenbindung_art', 'i.funktion_im_gremium interessenbindung_funktion_im_gremium', 'i.deklarationstyp interessenbindung_deklarationstyp', 'i.status interessenbindung_status', 'i.hauptberuflich interessenbindung_hauptberuflich', 'i.behoerden_vertreter interessenbindung_behoerden_vertreter', 'i.wirksamkeit interessenbindung_wirksamkeit', 'i.wirksamkeit_index interessenbindung_wirksamkeit_index',
    'i.organisation_id', 'o.name_de organisation_name_de', 'o.uid organisation_uid', 'o.name_fr organisation_name_fr', 'o.ort organisation_ort', 'o.rechtsform organisation_rechtsform', 'o.rechtsform_handelsregister organisation_rechtsform_handelsregister', 'o.rechtsform_zefix organisation_rechtsform_zefix', 'o.typ organisation_typ', 'o.vernehmlassung organisation_vernehmlassung',
  'o.interessengruppe1 organisation_interessengruppe1', 'o.interessengruppe1_id organisation_interessengruppe1_id', 'o.interessengruppe1_branche organisation_interessengruppe1_branche', 'o.interessengruppe1_branche_id organisation_interessengruppe1_branche_id', 'o.interessengruppe1_branche_kommission1_abkuerzung organisation_interessengruppe1_branche_kommission1_abkuerzung', 'o.interessengruppe1_branche_kommission2_abkuerzung organisation_interessengruppe1_branche_kommission2_abkuerzung',
  'o.interessengruppe2 organisation_interessengruppe2', 'o.interessengruppe2_id organisation_interessengruppe2_id', 'o.interessengruppe2_branche organisation_interessengruppe2_branche', 'o.interessengruppe2_branche_id organisation_interessengruppe2_branche_id','o.interessengruppe2_branche_kommission1_abkuerzung organisation_interessengruppe2_branche_kommission1_abkuerzung', 'o.interessengruppe2_branche_kommission2_abkuerzung organisation_interessengruppe2_branche_kommission2_abkuerzung',
  'o.interessengruppe3 organisation_interessengruppe3', 'o.interessengruppe3_id organisation_interessengruppe3_id', 'o.interessengruppe3_branche organisation_interessengruppe3_branche', 'o.interessengruppe3_branche_id organisation_interessengruppe3_branche_id', 'o.interessengruppe3_branche_kommission1_abkuerzung organisation_interessengruppe3_branche_kommission1_abkuerzung', 'o.interessengruppe3_branche_kommission2_abkuerzung organisation_interessengruppe3_branche_kommission2_abkuerzung',
  'ij.verguetung', 'ij.verguetung_jahr', 'ij.verguetung_beschreibung'], 'order_by' => 'anzeige_name',
  ],
  'essential_parlamentarier_interessenbindung' => ['view' => 'v_parlamentarier_medium_raw p', 'hist_field' => ['p.im_rat_bis', 'i.bis'], 'unpubl_fields' => ['p.erfasst'], 'remove_cols' => ['anzeige_name_de','anzeige_name_fr', 'name_de', 'name_fr', 'parlament_interessenbindungen', 'parlament_interessenbindungen_json', 'von', 'bis'], 'join' => ["LEFT JOIN v_interessenbindung_raw i ON p.id = i.parlamentarier_id", "LEFT JOIN v_interessenbindung_jahr_max ij ON ij.interessenbindung_id = i.id", "LEFT JOIN v_organisation_medium_raw o ON o.id = i.organisation_id"],
'select_cols' => ['anzeige_name parlamentarier_anzeige_name', 'id parlamentarier_id', 'nachname parlamentarier_nachname', 'vorname parlamentarier_vorname', 'zweiter_vorname parlamentarier_zweiter_vorname', 'rat parlamentarier_rat', 'kanton parlamentarier_kanton', 'partei_de parlamentarier_partei', 'fraktion parlamentarier_fraktion', 'kommissionen parlamentarier_kommissionen', 'im_rat_seit parlamentarier_im_rat_seit', 'im_rat_bis parlamentarier_im_rat_bis', 'geschlecht parlamentarier_geschlecht', 'geburtstag parlamentarier_geburtstag', 'parlament_biografie_id parlamentarier_parlament_biografie_id', 'parlament_number parlamentarier_parlament_number', 'sprache parlamentarier_sprache', 'arbeitssprache parlamentarier_arbeitssprache'],
  'additional_join_cols' => [
    'i.beschreibung interessenbindung_beschreibung', 'i.von interessenbindung_von', 'i.bis interessenbindung_bis', 'i.art interessenbindung_art', 'i.funktion_im_gremium interessenbindung_funktion_im_gremium', 'i.deklarationstyp interessenbindung_deklarationstyp', 'i.status interessenbindung_status', 'i.hauptberuflich interessenbindung_hauptberuflich', 'i.behoerden_vertreter interessenbindung_behoerden_vertreter', 'i.wirksamkeit interessenbindung_wirksamkeit',
    'i.organisation_id', 'o.name_de organisation_name_de', 'o.uid organisation_uid', 'o.name_fr organisation_name_fr', 'o.ort organisation_ort', 'o.rechtsform organisation_rechtsform', 'o.rechtsform_handelsregister organisation_rechtsform_handelsregister', 'o.rechtsform_zefix organisation_rechtsform_zefix', 'o.typ organisation_typ', 'o.vernehmlassung organisation_vernehmlassung',
  'o.interessengruppe1 organisation_interessengruppe1', 'o.interessengruppe1_branche organisation_interessengruppe1_branche', 'o.interessengruppe1_branche_kommission_abkuerzung organisation_interessengruppe1_branche_kommission_abkuerzung',
  'o.interessengruppe2 organisation_interessengruppe2', 'o.interessengruppe2_branche organisation_interessengruppe2_branche','o.interessengruppe2_branche_kommission_abkuerzung organisation_interessengruppe2_branche_kommission_abkuerzung',
  'o.interessengruppe3 organisation_interessengruppe3', 'o.interessengruppe3_branche organisation_interessengruppe3_branche', 'o.interessengruppe3_branche_kommission_abkuerzung organisation_interessengruppe3_branche_kommission_abkuerzung',
  'ij.verguetung', 'ij.verguetung_jahr', 'ij.verguetung_beschreibung'], 'order_by' => 'anzeige_name',
  ],
  'minimal_parlamentarier_interessenbindung' => ['view' => 'v_parlamentarier_medium_raw p', 'hist_field' => ['p.im_rat_bis', 'i.bis'], 'unpubl_fields' => ['p.erfasst'], 'remove_cols' => ['anzeige_name_de','anzeige_name_fr', 'name_de', 'name_fr', 'parlament_interessenbindungen', 'parlament_interessenbindungen_json', 'von', 'bis'], 'join' => ["LEFT JOIN v_interessenbindung_raw i ON p.id = i.parlamentarier_id", "LEFT JOIN v_interessenbindung_jahr_max ij ON ij.interessenbindung_id = i.id", "LEFT JOIN v_organisation_medium_raw o ON o.id = i.organisation_id"],
  'select_cols' => ['anzeige_name parlamentarier_name', 'id parlamentarier_id', 'rat parlamentarier_rat', 'kanton parlamentarier_kanton', 'partei_de parlamentarier_partei', 'fraktion parlamentarier_fraktion', 'kommissionen parlamentarier_kommissionen', 'im_rat_seit parlamentarier_im_rat_seit', 'im_rat_bis parlamentarier_im_rat_bis', 'geschlecht parlamentarier_geschlecht', 'geburtstag parlamentarier_geburtstag', 'parlament_biografie_id parlamentarier_parlament_biografie_id', 'parlament_number parlamentarier_parlament_number', 'sprache parlamentarier_sprache', 'arbeitssprache parlamentarier_arbeitssprache'],
  'additional_join_cols' => [
    'i.beschreibung interessenbindung_beschreibung', 'i.von interessenbindung_von', 'i.bis interessenbindung_bis', 'i.art interessenbindung_art', 'i.funktion_im_gremium interessenbindung_funktion_im_gremium', 'i.deklarationstyp interessenbindung_deklarationstyp', 'i.status interessenbindung_status', 'i.hauptberuflich interessenbindung_hauptberuflich', 'i.behoerden_vertreter interessenbindung_behoerden_vertreter', 'i.wirksamkeit interessenbindung_wirksamkeit',
    'i.organisation_id', 'o.name_de organisation_name', 'o.ort organisation_ort', 'o.rechtsform organisation_rechtsform', 'o.typ organisation_typ', 'o.vernehmlassung organisation_vernehmlassung',
  'o.interessengruppe1 organisation_interessengruppe1', 'o.interessengruppe1_branche organisation_interessengruppe1_branche', 'o.interessengruppe1_branche_kommission_abkuerzung organisation_interessengruppe1_branche_kommission_abkuerzung',
  'ij.verguetung', 'ij.verguetung_jahr', 'ij.verguetung_beschreibung'], 'order_by' => 'anzeige_name',
  ],
  'parlamentarier_verguetungstransparenz' => ['view' => 'parlamentarier_transparenz t', 'hist_field' => ['p.im_rat_bis'], 'unpubl_fields' => null, 'remove_cols' => null, 'join' => ["JOIN v_parlamentarier_medium_raw p ON p.id = t.parlamentarier_id"],
  'select_cols' => null,
  'additional_join_cols' => ['anzeige_name parlamentarier_name', 'rat parlamentarier_rat', 'kanton parlamentarier_kanton', 'partei_de parlamentarier_partei', 'fraktion parlamentarier_fraktion', 'kommissionen parlamentarier_kommissionen', 'im_rat_seit parlamentarier_im_rat_seit', 'im_rat_bis parlamentarier_im_rat_bis', 'geschlecht parlamentarier_geschlecht', 'geburtstag parlamentarier_geburtstag', 'parlament_biografie_id parlamentarier_parlament_biografie_id', 'parlament_number parlamentarier_parlament_number', 'sprache parlamentarier_sprache', 'arbeitssprache parlamentarier_arbeitssprache'], 'order_by' => 'p.anzeige_name, t.stichdatum DESC',
  ],

  'parlamentarier_interessenbindung_interessengruppe' => ['view' => 'v_parlamentarier_medium_raw p', 'slow' => 2, 'hist_field' => ['p.im_rat_bis', 'i.bis'], 'unpubl_fields' => ['p.erfasst'], 'remove_cols' => ['anzeige_name_de','anzeige_name_fr', 'name_de', 'name_fr', 'parlament_interessenbindungen', 'parlament_interessenbindungen_json', 'von', 'bis'], 'join' => ["LEFT JOIN v_interessenbindung_raw i ON p.id = i.parlamentarier_id", "LEFT JOIN v_interessenbindung_jahr_max ij ON ij.interessenbindung_id = i.id", "LEFT JOIN v_organisation_normalized_interessengruppe_raw o ON o.id = i.organisation_id"], 'additional_join_cols' => [
    'i.beschreibung interessenbindung_beschreibung', 'i.von interessenbindung_von', 'i.bis interessenbindung_bis', 'i.art interessenbindung_art', 'i.funktion_im_gremium interessenbindung_funktion_im_gremium', 'i.deklarationstyp interessenbindung_deklarationstyp', 'i.status interessenbindung_status', 'i.hauptberuflich interessenbindung_hauptberuflich', 'i.behoerden_vertreter interessenbindung_behoerden_vertreter', 'i.wirksamkeit interessenbindung_wirksamkeit', 'i.wirksamkeit_index interessenbindung_wirksamkeit_index',
    'i.organisation_id', 'o.name_de organisation_name_de', 'o.uid organisation_uid', 'o.name_fr organisation_name_fr', 'o.ort organisation_ort', 'o.rechtsform organisation_rechtsform', 'o.rechtsform_handelsregister organisation_rechtsform_handelsregister', 'o.rechtsform_zefix organisation_rechtsform_zefix', 'o.typ organisation_typ', 'o.vernehmlassung organisation_vernehmlassung',
    'o.interessengruppe organisation_interessengruppe', 'o.interessengruppe_id organisation_interessengruppe_id', 'o.interessengruppe_branche organisation_interessengruppe_branche', 'o.interessengruppe_branche_id organisation_interessengruppe_branche_id', 'o.interessengruppe_branche_kommission1_abkuerzung organisation_interessengruppe_branche_kommission1_abkuerzung', 'o.interessengruppe_branche_kommission2_abkuerzung organisation_interessengruppe_branche_kommission2_abkuerzung',
    'ij.verguetung', 'ij.verguetung_jahr', 'ij.verguetung_beschreibung'], 'order_by' => 'anzeige_name',
  ],
  'parlamentarier_kommission_interessenbindung_interessengruppe' => ['view' => 'v_parlamentarier_medium_raw p', 'slow' => 3, 'hist_field' => ['p.im_rat_bis', 'i.bis', 'k.bis'], 'unpubl_fields' => ['p.erfasst'], 'remove_cols' => ['anzeige_name_de','anzeige_name_fr', 'name_de', 'name_fr', 'parlament_interessenbindungen', 'parlament_interessenbindungen_json', 'von', 'bis'], 'join' => ["LEFT JOIN v_in_kommission_liste k ON p.id = k.parlamentarier_id LEFT JOIN v_interessenbindung_raw i ON p.id = i.parlamentarier_id", "LEFT JOIN v_interessenbindung_jahr_max ij ON ij.interessenbindung_id = i.id", "LEFT JOIN v_organisation_normalized_interessengruppe_raw o ON o.id = i.organisation_id"], 'additional_join_cols' => [
    'k.kommission_id parlamentarier_kommission_id', 'k.funktion parlamentarier_kommission_funktion', 'k.parlament_committee_function', 'k.parlament_committee_function_name', 'k.von parlamentarier_kommission_von', 'k.bis parlamentarier_kommission_bis', 'k.abkuerzung parlamentarier_kommission_abkuerzung', 'k.abkuerzung_fr parlamentarier_kommission_abkuerzung_fr', 'k.name parlamentarier_kommission_name', 'k.name_fr parlamentarier_kommission_name_fr',
    'i.beschreibung interessenbindung_beschreibung', 'i.von interessenbindung_von', 'i.bis interessenbindung_bis', 'i.art interessenbindung_art', 'i.funktion_im_gremium interessenbindung_funktion_im_gremium', 'i.deklarationstyp interessenbindung_deklarationstyp', 'i.status interessenbindung_status', 'i.hauptberuflich interessenbindung_hauptberuflich', 'i.behoerden_vertreter interessenbindung_behoerden_vertreter', 'i.wirksamkeit interessenbindung_wirksamkeit', 'i.wirksamkeit_index interessenbindung_wirksamkeit_index',
    'i.organisation_id', 'o.name_de organisation_name_de', 'o.uid organisation_uid', 'o.name_fr organisation_name_fr', 'o.ort organisation_ort', 'o.rechtsform organisation_rechtsform', 'o.rechtsform_handelsregister organisation_rechtsform_handelsregister', 'o.rechtsform_zefix organisation_rechtsform_zefix', 'o.typ organisation_typ', 'o.vernehmlassung organisation_vernehmlassung',
    'o.interessengruppe organisation_interessengruppe', 'o.interessengruppe_id organisation_interessengruppe_id', 'o.interessengruppe_branche organisation_interessengruppe_branche', 'o.interessengruppe_branche_id organisation_interessengruppe_branche_id', 'o.interessengruppe_branche_kommission1_abkuerzung organisation_interessengruppe_branche_kommission1_abkuerzung', 'o.interessengruppe_branche_kommission2_abkuerzung organisation_interessengruppe_branche_kommission2_abkuerzung',
    'ij.verguetung', 'ij.verguetung_jahr', 'ij.verguetung_beschreibung'], 'order_by' => 'anzeige_name',
  ],
  'parlamentarier_zutrittsberechtigung' => ['view' => 'v_parlamentarier_medium_raw p', 'hist_field' => ['p.im_rat_bis', 'z.bis'], 'unpubl_fields' => ['p.erfasst'], 'remove_cols' => ['anzeige_name_de','anzeige_name_fr', 'name_de', 'name_fr', 'parlament_interessenbindungen', 'parlament_interessenbindungen_json', 'von', 'bis'], 'join' => ["LEFT JOIN v_zutrittsberechtigung_simple z ON p.id = z.parlamentarier_id", "LEFT JOIN v_person_simple r ON r.id = z.person_id"],
  // 'select_cols' => [''],
  'additional_join_cols' => [
    'z.id zutrittsberechtigung_id', 'z.funktion zutrittsberechtigung_funktion', 'z.funktion_fr zutrittsberechtigung_funktion_fr', 'z.von zutrittsberechtigung_von', 'z.bis zutrittsberechtigung_bis',
    'r.id person_id', 'r.anzeige_name person_anzeige_name', 'r.nachname person_nachname', 'r.vorname person_vorname', 'r.zweiter_vorname person_zweiter_vorname', 'r.namensunterscheidung person_namensunterscheidung', 'r.beschreibung_de person_beschreibung_de', 'r.beschreibung_fr person_beschreibung_fr', 'r.beruf person_beruf', 'r.beruf_fr person_beruf_fr', 'r.beruf_interessengruppe_id person_beruf_interessengruppe_id', 'r.partei_id person_partei_id', 'r.geschlecht person_geschlecht', 'r.arbeitssprache person_arbeitssprache', 'r.homepage person_homepage', 'r.twitter_name person_twitter_name', 'r.linkedin_profil_url person_linkedin_profil_url', 'r.xing_profil_name person_xing_profil_name', 'r.facebook_name person_facebook_name'], 'order_by' => 'anzeige_name',
  ],
  'minimal_parlamentarier_zutrittsberechtigung' => ['view' => 'v_parlamentarier_medium_raw p', 'hist_field' => ['p.im_rat_bis', 'z.bis'], 'unpubl_fields' => ['p.erfasst'], 'join' => ["LEFT JOIN v_zutrittsberechtigung_simple z ON p.id = z.parlamentarier_id", "LEFT JOIN v_person_simple r ON r.id = z.person_id"],
  'select_cols' => ['anzeige_name parlamentarier_anzeige_name', 'id parlamentarier_id', 'rat parlamentarier_rat', 'kanton parlamentarier_kanton', 'partei_de parlamentarier_partei_de', 'fraktion parlamentarier_fraktion', 'kommissionen parlamentarier_kommissionen', 'im_rat_seit parlamentarier_im_rat_seit', 'im_rat_bis parlamentarier_im_rat_bis', 'geschlecht parlamentarier_geschlecht', 'geburtstag parlamentarier_geburtstag', 'parlament_biografie_id parlamentarier_parlament_biografie_id', 'parlament_number parlamentarier_parlament_number', 'sprache parlamentarier_sprache', 'arbeitssprache parlamentarier_arbeitssprache'],
  'additional_join_cols' => [
    'z.id zutrittsberechtigung_id', 'z.funktion zutrittsberechtigung_funktion', 'z.funktion_fr zutrittsberechtigung_funktion_fr', 'z.von zutrittsberechtigung_von', 'z.bis zutrittsberechtigung_bis',
    'r.id person_id', 'r.nachname person_nachname', 'r.vorname person_vorname', 'r.zweiter_vorname person_zweiter_vorname', 'r.namensunterscheidung person_namensunterscheidung', 'r.beschreibung_de person_beschreibung_de', 'r.beschreibung_fr person_beschreibung_fr', 'r.beruf person_beruf', 'r.beruf_fr person_beruf_fr', 'r.beruf_interessengruppe_id person_beruf_interessengruppe_id', 'r.partei_id person_partei_id', 'r.geschlecht person_geschlecht', 'r.arbeitssprache person_arbeitssprache', 'r.homepage person_homepage', 'r.twitter_name person_twitter_name', 'r.linkedin_profil_url person_linkedin_profil_url', 'r.xing_profil_name person_xing_profil_name', 'r.facebook_name person_facebook_name'], 'order_by' => 'anzeige_name',
  ],
  'minimal_parlamentarier_zutrittsberechtigung_mandat' => ['view' => 'v_parlamentarier_medium_raw p', 'hist_field' => ['p.im_rat_bis', 'z.bis'], 'unpubl_fields' => ['p.erfasst'], 'join' => ["LEFT JOIN v_zutrittsberechtigung_simple z ON p.id = z.parlamentarier_id", "LEFT JOIN v_person_simple r ON r.id = z.person_id", "LEFT JOIN v_mandat_raw i ON z.id = i.person_id", "LEFT JOIN v_organisation_medium_raw o ON o.id = i.organisation_id"],
  'select_cols' => ['anzeige_name parlamentarier_anzeige_name', 'id parlamentarier_id', 'rat parlamentarier_rat', 'kanton parlamentarier_kanton', 'partei_de parlamentarier_partei_de', 'fraktion parlamentarier_fraktion', 'kommissionen parlamentarier_kommissionen', 'im_rat_seit parlamentarier_im_rat_seit', 'im_rat_bis parlamentarier_im_rat_bis', 'geschlecht parlamentarier_geschlecht', 'geburtstag parlamentarier_geburtstag', 'parlament_biografie_id parlamentarier_parlament_biografie_id', 'parlament_number parlamentarier_parlament_number', 'sprache parlamentarier_sprache', 'arbeitssprache parlamentarier_arbeitssprache'],
  'additional_join_cols' => [
    'z.id zutrittsberechtigung_id', 'z.funktion zutrittsberechtigung_funktion', 'z.funktion_fr zutrittsberechtigung_funktion_fr', 'z.von zutrittsberechtigung_von', 'z.bis zutrittsberechtigung_bis',
    'r.id person_id', 'r.nachname person_nachname', 'r.vorname person_vorname', 'r.zweiter_vorname person_zweiter_vorname', 'r.namensunterscheidung person_namensunterscheidung', 'r.beschreibung_de person_beschreibung_de', 'r.beschreibung_fr person_beschreibung_fr', 'r.beruf person_beruf', 'r.beruf_fr person_beruf_fr', 'r.beruf_interessengruppe_id person_beruf_interessengruppe_id', 'r.partei_id person_partei_id', 'r.geschlecht person_geschlecht', 'r.arbeitssprache person_arbeitssprache', 'r.homepage person_homepage', 'r.twitter_name person_twitter_name', 'r.linkedin_profil_url person_linkedin_profil_url', 'r.xing_profil_name person_xing_profil_name', 'r.facebook_name person_facebook_name',
    'i.beschreibung interessenbindung_beschreibung', 'i.von mandat_von', 'i.bis mandat_bis', 'i.art mandat_art', 'i.funktion_im_gremium mandat_funktion_im_gremium', 'i.hauptberuflich mandat_hauptberuflich', 'i.wirksamkeit mandat_wirksamkeit',
    'i.organisation_id', 'o.name_de organisation_name', 'o.ort organisation_ort', 'o.rechtsform organisation_rechtsform', 'o.typ organisation_typ', 'o.vernehmlassung organisation_vernehmlassung',
  'o.interessengruppe1 organisation_interessengruppe1', 'o.interessengruppe1_branche organisation_interessengruppe1_branche', 'o.interessengruppe1_branche_kommission_abkuerzung organisation_interessengruppe1_branche_kommission_abkuerzung'], 'order_by' => 'anzeige_name',
  ],
];


$data_source = [
  'flat' => $flat_tables,
  'node' => $nodes,
  'relationship' => $relationships,
  'aggregated' => $aggregated_tables,
  'sql' => $sql_tables,
  'cartesian' => $cartesian_tables,
];

// TODO write PHPunit tests, e.g. for formatRow(), https://blog.dcycle.com/blog/2019-10-16/unit-testing/
// TODO rename to IExporter
interface IExporter {
  const FILE_ONE = 'one_file';
  const FILE_MULTI = 'multi_file';
  function supportsOneFile(): bool;
  function prefersOneFile(): bool;
  function isAggregatedFormat(): bool;
  function getExportOnlyHeader(): bool;

  function getRowSeparator(): string;
  function getFieldSeparator(): ?string;
  function getQuoteEscape(): ?string;
  function getFormatName(): string;
  function getFormat(): string;
  function getFormatDesc(): array;
  function getFileSuffix(): string;
  function setFormatParameter(array $parameter);
  function getFormatParameter(): array;
  function getDataSourceKeys(): array;
  function getFileHeader(bool $wrap, string $transaction_date): array;
  function hasHeaderDeclaration(): bool;
  function getHeaderDeclaration(array $cols): array;
  function getExtraCol(array $table_meta): ?string;
  function getExtraColDesc(array $table_meta): ?string;
  /**
   * Returns data for one header column.
   */
  function getHeaderCol(string $col, string $dataType, string $table, array $table_meta): ?array;

  function formatFieldAliasOrNull(string $table, string $field): ?string;
  function formatFieldAlias(string $table, string $field): string;

  /**
   * @return array lines
   */
  function getTableHeader(string $table, array $export_header, array $table_create_lines, array $table_meta, bool $wrap, bool $first): array;
  function formatRow(array $row, array $data_types, int $level, string $table_key, string $table, array $table_meta): string;
  /**
   * @return array lines
   */
  function getTableFooter(string $table, array $table_meta, bool $wrap, bool $last): array;
  function getFileFooter(bool $wrap): array;
  function getImportHint(string &$separator): array;
  function getImportHintFromTable(string $export_file_name, string $export_file_base_name, string $export_file_suffix, string $export_file_path_name, string $table, array $table_meta): ?string;

  function validate($file);
}

abstract class AbstractExporter implements IExporter {
  protected $format;
  protected $fileSuffix;
  protected $formatName;
  protected $formatDesc = [];
  protected $parameter = [];
  protected $eol = "\n";

  function getFormat(): string {
    return $this->format;
  }

  function getFormatName(): string {
    return $this->formatName;
  }

  function getFormatDesc(): array {
    return $this->formatDesc;
  }

  function getFormatParameter(): array {
    return $this->parameter;
  }

  function getExportOnlyHeader(): bool {
    return false;
  }
  function setFormatParameter(array $parameter): string {
    $this->parameter = $parameter;
  }

  function isAggregatedFormat(): bool {
    return false;
  }
  function getRowSeparator(): string {
    return '';
  }

  protected function setFieldSeparator(string $sep = null): ?string {
    return null;
  }
  protected function setQuoteEscape(string $qe = null): ?string {
    return null;
  }

  function getFieldSeparator(): ?string {
    return null;
  }
  function getQuoteEscape(): ?string {
    return null;
  }

  function getFileSuffix(): string {
    return $this->fileSuffix;
  }

  function getFileHeader(bool $wrap, string $transaction_date): array {
    return [];
  }

  function formatFieldAliasOrNull(string $table, string $field): ?string {
    return null;
  }
  function formatFieldAlias(string $table, string $field): string {
    return $field;
  }

  function hasHeaderDeclaration(): bool {
    return false;
  }

  function getHeaderDeclaration(array $cols): array {
    return [];
  }

  function getExtraCol(array $table_meta): ?string {
    return null;
  }

  function getExtraColDesc(array $table_meta): ?string {
    return null;
  }

  /**
   * Returns data for one header column.
   */
  function getHeaderCol(string $col, string $dataType, string $table, array $table_meta): array {
    $type = $table_meta['source'] ?? null;
    $skip_rows_for_empty_field = false;
    if ($type == 'relationship') {
      if ($col == $table_meta['start_id'] && ($table_meta['id'] ?? 'id') == $table_meta['start_id']) {
        $skip_rows_for_empty_field = false;
      } elseif ($col == $table_meta['start_id']) {
        $skip_rows_for_empty_field = true;
      } elseif ($col == $table_meta['end_id']) {
        $skip_rows_for_empty_field = true;
      } else {
        $skip_rows_for_empty_field = false;
      }
    }

    return [$col, $skip_rows_for_empty_field];
  }

  function getTableHeader(string $table, array $export_header, array $table_create_lines, array $table_meta, bool $wrap, bool $first): array {
    return [];
  }
  function formatRow(array $row, array $data_types, int $level, string $table_key, string $table, array $table_meta): string {
    return '';
  }
  function getTableFooter(string $table, array $table_meta, bool $wrap, bool $last): array {
    return [];
  }
  function getFileFooter(bool $wrap): array {
    return [];
  }
  function getImportHint(string &$separator): array {
    $separator = "\n";
    return [];
  }

  function getImportHintFromTable(string $export_file_name, string $export_file_base_name, string $export_file_suffix, string $export_file_path_name, string $table, array $table_meta): ?string {
    return null;
  }

  function validate($file) {
    return null;
  }

  function prefersOneFile(): bool {
    return false;
  }

   protected function getEdge(array $table_meta): array {
    $edgeName = $table_meta['name'] ?? $table;
    $startId = $table_meta['start_id'] ?? $table_meta['id'] ?? 'id';
    $endId = $table_meta['end_id'] ?? $table_meta['id'] ?? 'id';

    $start_space = preg_replace('/_id$/', '', $table_meta['start_id_space'] ?? $table_meta['start_id'] ?? $table_meta['table']);
    $start_space = $start_space == 'id' ? $table_meta['table'] : $start_space;
    $sourceNode = camelize($start_space);

    $end_space = preg_replace('/_id$/', '', $table_meta['end_id_space'] ?? ($table_meta['end_id']) ?? null);
    $end_space = $end_space == 'id' ? $table_meta['table'] : $end_space;
    $targetNode = camelize($end_space);

    return [$edgeName, $startId, $start_space, $sourceNode, $endId, $end_space, $targetNode];

  }

}

abstract class FlatExporter extends AbstractExporter implements IExporter {

  protected $qe;
  protected $sep;

  function __construct($sep = null, $qe = null) {
    $this->setFieldSeparator($sep);
    $this->setQuoteEscape($qe);
  }

  function getQuoteEscape(): string {
    $this->qe;
  }

  function getFieldSeparator(): string {
    $this->sep;
  }

  protected function setFieldSeparator(string $sep = null): ?string {
    if ($sep == null) {
      $this->sep = $this->getSupportedFieldSeparator()[0];
    } elseif (in_array($sep, $this->getSupportedFieldSeparator())) {
      $this->sep = $sep;
    } else {
      throw new Exception("Separator '$sep' not supported for format '$format'. Supported separators: " . implode(', ', $this->getSupportedFieldSeparator()));
    }
    return $this->sep;
  }

  protected function setQuoteEscape(string $qe = null): ?string {
    if ($qe == null) {
      $this->qe = $this->getSupportedQuoteEscape()[0];
    } elseif (in_array($qe, $this->getSupportedQuoteEscape())) {
      $this->qe = $qe;
    } else {
      throw new Exception("Quote escape '$qe' not supported for format '$format'. Supported: " . implode(', ', $this->getSupportedQuoteEscape()));
    }
    return $this->qe;
  }

  function supportsOneFile(): bool {
    return false;
  }
  function isAggregatedFormat(): bool {
    return false;
  }

  function getDataSourceKeys(): array {
    return ['flat'];
  }

  protected abstract function getSupportedQuoteEscape(): array;

  protected abstract function getSupportedFieldSeparator(): array;

}

abstract class AggregatedExporter extends AbstractExporter implements IExporter {

  function supportsOneFile(): bool {
    return true;
  }
  function isAggregatedFormat(): bool {
    return true;
  }
  function getDataSourceKeys(): array {
    return ['flat', 'aggregated'];
  }

}


class CsvExporter extends FlatExporter {

  function __construct($sep = null, $qe = null) {
    parent::__construct($sep, $qe);
    $this->format = 'csv';
    $this->formatName = 'CSV';
    $this->fileSuffix = 'csv';
  }

  function getDataSourceKeys(): array {
    return ['flat', 'cartesian'];
  }
  protected function getSupportedQuoteEscape(): array {
    return ['"', '\\'];
  }

  protected function getSupportedFieldSeparator(): array {
    return ["\t", ';', ',', '|', ':'];
  }

  function getQuoteEscape(): string {
    return $this->qe;
  }

  function getFieldSeparator(): string {
    return $this->sep;
  }

  protected function setFieldSeparator(string $sep = null): ?string {
    if ($sep == null) {
      $this->sep = $this->getSupportedFieldSeparator()[0];
    } elseif (in_array($sep, $this->getSupportedFieldSeparator())) {
      $this->sep = $sep;
    } else {
      throw new Exception("Separator '$sep' not supported for format '$format'. Supported separators: " . implode(', ', $this->getSupportedFieldSeparator()));
    }
    return $this->sep;
  }

  protected function setQuoteEscape(string $qe = null): ?string {
    if ($qe == null) {
      $this->qe = $this->getSupportedQuoteEscape()[0];
    } elseif (in_array($qe, $this->getSupportedQuoteEscape())) {
      $this->qe = $qe;
    } else {
      throw new Exception("Quote escape '$qe' not supported for format '$format'. Supported: " . implode(', ', $this->getSupportedQuoteEscape()));
    }
    return $this->qe;
  }

  function getTableHeader(string $table, array $export_header, array $table_create_lines, array $table_meta, bool $wrap, bool $first): array {
    return [implode($this->sep, $export_header)];
  }

  function formatRow(array $row, array $data_types, int $level, string $table_key, string $table, array $table_meta): string {
    assert(count($row) === count($data_types));
    $type_col = $table_meta['type_col'] ?? null;
    $extra_val = $table_meta['name'] ?? null;

    $qes = array_fill(0, $extra_val ? count($data_types) : count($data_types), $this->qe);

    return ($extra_val ? ($type_col ? str_replace(' ', '_', strtoupper($row[$type_col])) : $extra_val) . "$this->sep" : '') .
    implode($this->sep, array_map(['self', 'escape_csv_field'], $row, $data_types, $qes));
  }

  protected static function escape_csv_field(string $field = null, string $data_type, string $qe): string {
    if (is_null($field)) {
      return '';
    }

    switch ($data_type) {
      case 'timestamp': return str_replace(' ', 'T', $field);
      case 'datetime': return $field;
      case 'date': return $field;
    }
    switch ($field) {
      case is_numeric($field): return $field;
      default: return '"' . str_replace('"', "$qe\"", str_replace("\n", '\n', $field)) . '"';
    }
  }

  function formatFieldAliasOrNull(string $table, string $field): ?string {
    return $this->formatFieldAlias($table, $field);
  }

  function formatFieldAlias(string $table, string $field): string {
    return "${table}_$field";
  }

}

class Neo4jCsvExporter extends CsvExporter {

  function __construct($sep = null, $qe = null) {
    parent::__construct($sep, $qe);
    $this->format = 'neo4j_csv';
    $this->formatName = 'Neo4j CSV';
    $this->fileSuffix = 'csv';
  }

  protected function getSupportedQuoteEscape(): array {
    return ['"', '\\'];
  }

  protected function getSupportedFieldSeparator(): array {
    return ["\t", ';', ',', '|', ':'];
  }

  private const TYPE_MAPPING = [
    'int' => 'int',
    'tinyint' => 'int',
    'smallint' => 'int',
    'bigint' => 'long',
    'float' => 'float',
    'double' => 'double',
    'boolean' => 'boolean',
    'varchar' => 'string',
    'char' => 'char',
    'enum' => 'string',
    'set' => 'string[]', // TODO fix export, set quotes correctly, use ; as delim
    'mediumtext' => 'string',
    'text' => 'string',
    'json' => 'string',
    'date' => 'date',
    'timestamp' => 'localdatetime',
  ];

  function getDataSourceKeys(): array {
    return ['node', 'relationship'];
  }

  function getExtraCol(array $table_meta): ?string {
    if (isset($table_meta['name'])) {
      $type = $table_meta['source'];
      switch ($type) {
        case 'node': return ':LABEL';
        case 'relationship': return ':TYPE';
        default: throw new Exception("Unknown type" . $type);
      }
    } else {
      return null;
    }
  }

  /**
   * Returns data for one header column.
   */
  function getHeaderCol(string $col, string $dataType, string $table, array $table_meta): array {
    $type = $table_meta['source'];
    $header_field = $col;
    $skip_rows_for_empty_field = false;
    if ($type == 'node') {
      if ($col == ($table_meta['id'] ?? 'id')) {
        $header_field .= ":ID({$table}_id)";
      } else {
        $header_field .= ":" . self::TYPE_MAPPING[$dataType];
      }
      $skip_rows_for_empty_field = false;
    } elseif ($type == 'relationship') {
      if ($col == $table_meta['start_id'] && ($table_meta['id'] ?? 'id') == $table_meta['start_id']) {
        $header_field .= ":START_ID({$table}_$col)";
        $skip_rows_for_empty_field = false;
      } elseif ($col == $table_meta['start_id']) {
        $id_space = $table_meta['start_id_space'] ?? $col;
        $header_field .= ":START_ID($id_space)";
        $skip_rows_for_empty_field = true;
      } elseif ($col == $table_meta['end_id']) {
        $id_space = $table_meta['end_id_space'] ?? $col;
        $header_field .= ":END_ID($id_space)";
        $skip_rows_for_empty_field = true;
      } else {
        $header_field .= ":" . self::TYPE_MAPPING[$dataType];
        $skip_rows_for_empty_field = false;
      }
    } else {
      throw new Exception('Unknown source type: ' . $type);
    }
    return [$header_field, $skip_rows_for_empty_field];
  }

  function getImportHint(string &$separator): array {
    $separator = " \\\n";
    $cmd_args = [];
    $cmd_args[] = '#!/bin/bash';
    $cmd_args[] = '';
    $cmd_args[] = '# Neo4j sample import script for Lobbywatch data';
    $cmd_args[] = '# https://neo4j.com/docs/operations-manual/current/tools/import/';
    $cmd_args[] = '';
    $cmd_args[] = 'set -e';
    $cmd_args[] = '';
    $cmd_args[] = '# MATCH (n) DETACH DELETE n';
    $cmd_args[] = '';
    $cmd_args[] = '';
    //     $cmd_args[] = "neo4j-admin";
    $cmd_args[] = 'NEO4J_PATH="$HOME/.config/Neo4j Desktop/Application/neo4jDatabases/database-0b42a643-61a0-4b3f-8c54-4dfbe872d200/installation-3.5.6"';
    $cmd_args[] = 'NEO4J_DB="$NEO4J_PATH/data/databases/graph.db/"';
    $cmd_args[] = '[ -d "$NEO4J_DB" ] && rm -r "$NEO4J_DB" && echo "Existing graph.db deleted"';
    $cmd_args[] = '';
    $cmd_args[] = '"$NEO4J_PATH/bin/neo4j-admin" \\';
    $cmd_args[] = "import \\";
    $cmd_args[] = "--database=graph.db \\";
    $cmd_args[] = "--id-type=INTEGER \\";
    $cmd_args[] = "--delimiter='\\t' \\";
    $cmd_args[] = "--array-delimiter=',' \\";
    $cmd_args[] = "--report-file=neo4j_import.log";

    return [implode("\n", $cmd_args)];
  }

  function getImportHintFromTable(string $export_file_name, string $export_file_base_name, string $export_file_suffix, string $export_file_path_name, string $table, array $table_meta): string {
    $type = $table_meta['source'];
    return "--{$type}s \"$export_file_path_name\"";
  }

}

class SqlExporter extends FlatExporter implements IExporter {

  // protected $oneline = false;

  function __construct($sep = null, $qe = null) {
    parent::__construct($sep, $qe);
    $this->format = 'sql';
    $this->formatName = 'SQL';
    $this->fileSuffix = 'sql';
  }

  protected function getSupportedQuoteEscape(): array {
    return ["'", '\\'];
  }

  protected function getSupportedFieldSeparator(): array {
    return [','];
  }

  function supportsOneFile(): bool {
    return true;
  }
  function prefersOneFile(): bool {
    return true;
  }
  function isAggregatedFormat(): bool {
    return false;
  }

  function getDataSourceKeys(): array {
    return ['sql'];
  }

  function getRowSeparator(): string {
    return ',';
  }

  function getFileHeader(bool $wrap, $transaction_date): array {
    $header = [];
    $header[] = "-- Lobbywatch.ch SQL export $transaction_date";
    $header[] = '';
    // $header[] = "-- Hist data included: " . !$filter_hist . "$eol");
    // $header[] = "-- Intern data included: " . !$filter_intern_fields . "$eol$eol");

    $header[] = "SET NAMES utf8mb4;";
    $header[] = "SET TIME_ZONE='+00:00';";
    $header[] = '';
    $header[] = "SET FOREIGN_KEY_CHECKS=0;";
    $header[] = '';
    $header[] = "-- SET SQL_NOTES=0;";
    $header[] = '';

    $header[] = "CREATE DATABASE IF NOT EXISTS lobbywatch_public DEFAULT CHARACTER SET utf8mb4;";
    $header[] = "USE lobbywatch_public;";
    $header[] = '';
    $header[] = '';

    return $header;
  }

  function getTableHeader(string $table, array $export_header, array $table_create_lines, array $table_meta, bool $wrap, bool $first): array {
    return [
    "DROP TABLE IF EXISTS $table;",
    str_replace('`', '', implode($this->eol, $table_create_lines) . ";"),
    '',
    "INSERT INTO $table (" . implode(", ", $export_header) . ") VALUES"
    ];
  }
  function formatRow(array $row, array $data_types, int $level, string $table_key, string $table, array $table_meta): string {
    $qes = array_fill(0, count($data_types), $this->qe);
    return '(' . implode(",", array_map(['self', 'escape_sql_field'], $row, $data_types, $qes)) . ')';
  }
  function getTableFooter(string $table, array $table_meta, bool $wrap, bool $last): array {
    return [';', ''];
  }
  function getFileFooter(bool $wrap): array {
    return ['SET FOREIGN_KEY_CHECKS=1;'];
  }


  protected static function escape_sql_field(string $field = null, string $data_type, string $qe): string {
    if (is_null($field)) {
      return 'NULL';
    }
    switch ($data_type) {
      case 'int':
      case 'tinyint':
      case 'smallint':
      case 'bigint':
      case 'float':
      case 'double':
      case 'boolean': return $field;

      case 'json': return "'" . str_replace('\"', '\\\\"', str_replace("'", $qe . "'", str_replace("\n", '\n', $field))) . "'";

      case 'timestamp':
      case 'date':
      case 'varchar':
      case 'char':
      case 'enum':
      case 'set':
      case 'mediumtext':
      case 'text':
      default: return "'" . str_replace("'", $qe . "'", str_replace("\n", '\n', $field)) . "'";
    }
    //     switch ($field) {
      //         case is_numeric($field): return $field;
      //         default: return '"' . str_replace('"', '""', str_replace("\n", '\n', str_replace("\r", '', $field))) . '"';
      //     }
    }

}


class JsonExporter extends AggregatedExporter {

  function __construct() {
    $this->format = 'json';
    $this->fileSuffix = 'json';
    $this->formatName = 'JSON';
  }

  function getDataSourceKeys(): array {
    return ['flat', 'aggregated', 'node', 'relationship'];
  }

  function getRowSeparator(): string {
    return ',';
  }

  function formatFieldAliasOrNull(string $table, string $field): ?string {
    return $this->formatFieldAlias($table, $field);
  }

  function formatFieldAlias(string $table, string $field): string {
    return "${table}_$field";
  }

  function getFileHeader(bool $wrap, $transaction_date): array {
    return $wrap ? ['{'] : [];
  }

  function getTableHeader(string $table, array $export_header, array $table_create_lines, array $table_meta, bool $wrap, bool $first): array {
    return [ $wrap ? "\"$table\":[" : '[' ];
  }
  function formatRow(array $row, array $data_types, int $level, string $table_key, string $table, array $table_meta): string {
    return json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK | JSON_THROW_ON_ERROR);
  }
  function getTableFooter(string $table, array $table_meta, bool $wrap, bool $last): array {
    return ["$this->eol]" . (!$last ? ',' : '')];
  }
  function getFileFooter(bool $wrap): array {
    return $wrap ? ['}'] : [];
  }

}

// http://orientdb.com/docs/3.0.x/gettingstarted/tutorials/Import-the-Database-of-Beers.html
// http://orientdb.com/docs/3.0.x/etl/Import-from-JSON.html
class JsonOrientDBExporter extends AbstractExporter {

  function __construct() {
    $this->format = 'orientdb_etl_json';
    $this->fileSuffix = 'etl.json';
    $this->formatName = 'OrientDB ETL JSON';
  }

  function formatFieldAliasOrNull(string $table, string $field): ?string {
    return $this->formatFieldAlias($table, $field);
  }

  function formatFieldAlias(string $table, string $field): string {
    return "${table}_$field";
  }

  function supportsOneFile(): bool {
    return false;
  }
  function getDataSourceKeys(): array {
    return ['node', 'relationship'];
  }
  function getExportOnlyHeader(): bool {
    return true;
  }

  function getImportHint(string &$separator): array {
    $separator = "\n";

    $this->db_name = 'lw_graph';
    $db = $this->db_name;

    $cmd_args = [];
    $cmd_args[] = '#!/bin/bash';
    $cmd_args[] = '';
    $cmd_args[] = '# OrientDB sample import script for Lobbywatch data';
    $cmd_args[] = '# https://orientdb.com/docs/3.0.x/etl/Import-from-JSON.html';
    $cmd_args[] = '';
    $cmd_args[] = 'set -e';
    $cmd_args[] = '';
    //$cmd_args[] = "docker stop orientdb";
    $cmd_args[] = "echo -e \"docker restart orientdb\"; docker restart orientdb";
    $cmd_args[] = "echo -e \"drop database $db\"; docker exec -it orientdb bin/console.sh drop database plocal:/orientdb/databases/$db admin admin";
    $cmd_args[] = "echo -e \"create database $db\"; docker exec -it orientdb bin/console.sh create database plocal:/orientdb/databases/$db admin admin PLOCAL GRAPH";
    $cmd_args[] = "";

    return $cmd_args;
  }

  function getImportHintFromTable(string $export_file_name, string $export_file_base_name, string $export_file_suffix, string $export_file_path_name, string $table, array $table_meta): string {
    $tkey = $table_meta['tkey'];
    return "echo -e \"\\n\\nImport '$tkey' with '$export_file_path_name'\"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/$export_file_path_name; echo";
  }

  // https://stackoverflow.com/questions/33679571/how-to-use-orientdb-etl-to-create-edges-only
  // https://stackoverflow.com/questions/35485693/orient-etl-many-to-many
  // https://stackoverflow.com/questions/36570525/etl-and-many-to-many-relation
  function getTableHeader(string $table, array $export_header, array $table_create_lines, array $table_meta, bool $wrap, bool $first): array {

    $source = $table_meta['source'];
    $tkey = $table_meta['tkey'];

    $db = $this->db_name;

    // TODO extract variable from templates: db, path, ...
    if ($source == 'node') {
      $nodeName = camelize($table);

      // https://stackoverflow.com/questions/33751381/update-vertex-field-values-using-orientdb-etl
      // "command" : { "command" : "UPDATE CustomerService set   CustomerService.IsDown = $input.IsDown UPSERT where CustomerService.Name = $input.Name"}

      // https://stackoverflow.com/questions/44654941/orientdb-only-update-field-if-null

      // http://orientdb.com/docs/3.0.x/indexing/Indexes.html
      $etl_json = <<<EOT
{
  "config": {
    "log": "debug"
  },
  "source" : {
    "file": { "path": "/import/export/node_$tkey.json" }
  },
  "extractor" : {
    "json": {}
  },
  "transformers" : [
    { "vertex": { "class": "$nodeName", "skipDuplicates": true } }
  ],
  "loader" : {
    "orientdb": {
      "dbURL": "plocal:/orientdb/databases/$db",
      "dbUser": "admin",
      "dbPassword": "admin",
      "dbAutoDropIfExists": false,
      "dbAutoCreate": true,
      "standardElementConstraints": false,
      "tx": false,
      "wal": false,
      "batchCommit": 1000,
      "dbType": "graph",
      "class": "$nodeName",
      "classes": [{"name": "$nodeName", "extends":"V"}],
      "indexes": [{"class":"$nodeName", "fields":["id:integer"], "type":"UNIQUE" }]
    }
  }
}
EOT;
      } else {
        list($edgeName, $startId, $start_space, $sourceNode, $endId, $end_space, $targetNode) = $this->getEdge($table_meta);
        $edgeFieldsEscape = !empty($export_header) ? implode(' ', array_map(function($field) {return "var val_$field = record.getProperty('$field'); record.setProperty('escaped_$field' , typeof val_$field == 'string' ? (val_$field + '').replace(/'/g, \"\\\\\\'\") : typeof val_$field == 'undefined' ? 'null' : val_$field + '');"; }, $export_header)) : '';
        $edgeFields = !empty($export_header) ? ' SET ' . implode(', ', array_map(function($field) {return "$field = '\${input.escaped_$field}'"; }, $export_header)) : '';

        // https://stackoverflow.com/questions/37133409/orientdb-import-edges-only-using-etl-tool

        // "lookup" uses indexes, [node].[field], thus the node is determined by the index, eg. Person.id
        // "code": "print('Loooos'); print(record); $edgeFieldsEscape print(record); record"
        $etl_json = <<<EOT
{
  "config": {
    "log": "debug"
  },
  "source" : {
    "file": { "path": "/import/export/relationship_$tkey.json" }
  },
  "extractor" : {
    "json": {}
  },
  "transformers" : [
    { "code":
      { "code": "$edgeFieldsEscape record"
      }
    },
    {"command" :
      {
        "command" : "create edge $edgeName from (select from $sourceNode where id= \${input.$startId}) to (select from $targetNode where id = \${input.$endId})$edgeFields",
        "output" : "edge"
      }
    }
  ],
  "loader" : {
    "orientdb": {
      "dbURL": "plocal:/orientdb/databases/$db",
      "dbUser": "admin",
      "dbPassword": "admin",
      "dbAutoDropIfExists": false,
      "dbAutoCreate": true,
      "standardElementConstraints": false,
      "tx": false,
      "wal": false,
      "batchCommit": 1000,
      "dbType": "graph",
      "classes": [{"name": "$edgeName", "extends": "E"}],
      "indexes": []
    }
  }
}
EOT;
      }
    return [$etl_json];
  }

}

class JsonlExporter extends JsonExporter {

  function __construct() {
    $this->format = 'jsonl';
    $this->fileSuffix = 'jsonl';
    $this->formatName = 'JsonL';
  }

  function supportsOneFile(): bool {
    return false;
  }

  function getRowSeparator(): string {
    return '';
  }

  function getFileHeader(bool $wrap, $transaction_date): array {
    return [];
  }

  function getFileFooter(bool $wrap): array {
    return [];
  }

  function getTableHeader(string $table, array $export_header, array $table_create_lines, array $table_meta, bool $wrap, bool $first): array {
    return [];
  }

  function getTableFooter(string $table, array $table_meta, bool $wrap, bool $last): array {
    return [];
  }

}

// TODO support filename prefix
class ArangoDBJsonlExporter extends JsonlExporter {

  function __construct() {
    $this->format = 'arangodb_jsonl';
    $this->fileSuffix = 'arangodb.jsonl';
    $this->formatName = 'ArangoDB Jsonl';
  }

  function getDataSourceKeys(): array {
    return ['node', 'relationship'];
  }

  function getImportHint(string &$separator): array {
    $separator = "\n";
    $cmd_args = [];

    $cmd_args[] = '#!/bin/bash';
    $cmd_args[] = '';
    $cmd_args[] = '# ArangoDB sample import script for Lobbywatch data';
    $cmd_args[] = '# https://www.arangodb.com/docs/stable/programs-arangoimport-options.html';
    $cmd_args[] = '';
    $cmd_args[] = 'set -e';
    $cmd_args[] = '';

    return $cmd_args;
  }

  function getImportHintFromTable(string $export_file_name, string $export_file_base_name, string $export_file_suffix, string $export_file_path_name, string $table, array $table_meta): string {
    $tkey = $table_meta['tkey'];
    $source = $table_meta['source'];
    $collection = $source == 'node' ? camelize($table) : $table_meta['name'];
    list($edgeName, $startId, $start_space, $sourceNode, $endId, $end_space, $targetNode) = $this->getEdge($table_meta);
    // return "echo -e \"\\n\\nImport '$tkey' with '$filename'\"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/$filename";
    // cat '$filename' |
    return "echo -e \"\\n\\nImport '$tkey' with '$export_file_path_name'\"; docker exec -it arangodb arangosh --server.authentication false --javascript.execute-string \"db._drop('$collection');\"; docker exec -it arangodb arangoimport --server.authentication false --file '/import/$export_file_path_name' --type jsonl --progress true --create-collection --collection $collection" . ($source == 'edgte' ? " --create-collection-type edge --from-collection-prefix $sourceNode --to-collection-prefix $targetNode" : '');
  }

  function formatRow(array $row, array $data_types, int $level, string $table_key, string $table, array $table_meta): string {

    $source = $table_meta['source'];
    $tkey = $table_meta['tkey'];

    // TODO extract variable from templates: db, path, ...
    if ($source == 'node') {
      $nodeName = camelize($table);
      $row['_key'] = '' . $row[$table_meta['id'] ?? 'id'];
    } else {
      list($edgeName, $startId, $start_space, $sourceNode, $endId, $end_space, $targetNode) = $this->getEdge($table_meta);
      $row['_from'] = '' . $row[$startId];
      $row['_to'] = '' . $row[$endId];
    }

    // return parent::formatRow($row, $data_types, $level, $table_key, $table, $table_meta);
    return json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR /*| JSON_NUMERIC_CHECK*/);
  }

}


// https://stackoverflow.com/questions/6260224/how-to-write-cdata-using-simplexmlelement
class SimpleXMLExtended extends SimpleXMLElement {
  public function addCData($cdata_text) {
    $node = dom_import_simplexml($this);
    $no   = $node->ownerDocument;
    $node->appendChild($no->createCDATASection($cdata_text));
  }
}

class XmlExporter extends AggregatedExporter {

  function __construct(bool $as_attributes = true) {
    $this->format = 'xml';
    $this->fileSuffix = 'xml';
    $this->formatName = 'XML';
    $this->as_attributes = $as_attributes;
  }

  function getFileHeader(bool $wrap, $transaction_date): array {
    return ['<?xml version="1.0" encoding="UTF-8"?>', "<!-- Exported: $transaction_date -->"];
  }

  function getTableHeader(string $table, array $export_header, array $table_create_lines, array $table_meta, bool $wrap, bool $first): array {
    return ["<${table}_liste>"];
  }
  function formatRow(array $row, array $data_types, int $level, string $table_key, string $table, array $table_meta): string {
    if ($this->as_attributes) {
      $row = $this->convertToAttr($row);
    } elseif (!empty($id_val = $row[$id = $table_meta['id'] ?? 'id'])) {
      $row['@' . $id] = $id_val;
    }
    return str_repeat("\t", $level) . $this->array2xml($table, $table, $row);
  }
  function getTableFooter(string $table, array $table_meta, bool $wrap, bool $last): array {
    return ["$this->eol</${table}_liste>"];
  }
  function getFileFooter(bool $wrap): array {
    return [];
  }

  protected function convertToAttr(array $row): array {
    $attr = [];
    foreach($row as $key => $val) {
      if (!is_numeric($key) && !is_array($val) && !in_array($key, $table_meta['not_as_attribute'] ?? ['notizen', 'beschreibung', 'beschreibung_de', 'beschreibung_fr', 'sachbereiche', 'parlament_interessenbindungen', 'parlament_interessenbindungen_json'])) {
        $attr["@$key"] = $val;
      } elseif (!empty($val) && is_array($val) && isset($val[0]) && is_array($val[0])) { // array of data objects
        foreach($val as $row_key => $inner_row) {
          $attr[$key][$row_key] = $this->convertToAttr($inner_row);
        }
      } elseif (!empty($val) && is_array($val)) { // only 1 data object
        $attr[$key] = $this->convertToAttr($val);
      } else {
        $attr[$key] = $val;
      }
    }
    return $attr;
  }

  protected function array2xml(string $outer_name, string $inner_name, array $data, $file = false) {
    $xml_root = new SimpleXMLElement("<root/>");
    $xml_data = $xml_root->addChild("$outer_name");

    // function call to convert array to xml
    $this->array2xmlObj($inner_name, $data, $xml_data);

    //saving generated xml file;
    $str = '';
    foreach ($xml_root as $elem) {
      if ($file)  {
        $elem->asXML($file);
      } else {
        $str .= $elem->asXML();
      }
    }
    return str_replace("\n", '&#xA;', $str);
  }

  // https://stackoverflow.com/questions/1397036/how-to-convert-array-to-simplexml
  protected function array2xmlObj(string $name, array $data, $xml_data) {
    foreach($data as $key => $value) {
      if (is_numeric($key)){
        // $key = "item$key"; //dealing with <0/>..<n/> issues
        $key = $name;
      }
      if (is_array($value)) {
        $subnode = $xml_data->addChild(isset($value[0]) && is_array($value[0]) ? "${key}_liste" : $key);
        $this->array2xmlObj($key, $value, $subnode);
      } elseif (utils_startsWith($key, '@')) {
        $xml_data->addAttribute(mb_substr("$key", 1), htmlspecialchars("$value", ENT_XML1));
      } else {
        $xml_data->addChild("$key", htmlspecialchars("$value", ENT_XML1));
      }
     }
  }

}

// http://graphml.graphdrawing.org/primer/graphml-primer.html
class GraphMLExporter extends XmlExporter {

  function __construct() {
    $this->format = 'graphml';
    $this->fileSuffix = 'graphml';
    $this->formatName = 'GraphML';
  }

  function getDataSourceKeys(): array {
    return ['node', 'relationship'];
  }

  function formatFieldAliasOrNull(string $table, string $field): ?string {
    return $this->formatFieldAlias($table, $field);
  }

  function formatFieldAlias(string $table, string $field): string {
    return "${table}_$field";
  }

  function prefersOneFile(): bool {
    return true;
  }

  function getFileHeader(bool $wrap, $transaction_date): array {
    return ['<?xml version="1.0" encoding="UTF-8"?>', "<!-- Exported: $transaction_date -->", '<graphml xmlns="http://graphml.graphdrawing.org/xmlns" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://graphml.graphdrawing.org/xmlns http://graphml.graphdrawing.org/xmlns/1.0/graphml.xsd">', ''];
  }

  function getFileFooter(bool $wrap): array {
    return ['</graph>', '</graphml>'];
  }

  function hasHeaderDeclaration(): bool {
    return true;
  }

  function getHeaderDeclaration(array $cols): array {
    $attributes = [];
    $xml = [];
    $forMapping = [
      'node' => 'all', // node
      'relationship' => 'all', // edge
      'edge' => 'all'// edge
    ];
    // boolean, int, long, float, double, or string
    $typeMapping = [
      'int' => 'int',
      'tinyint' => 'int',
      'smallint' => 'int',
      'bigint' => 'long',
      'float' => 'float',
      'decimal' => 'double',
      'double' => 'double',
      'boolean' => 'boolean',
      'varchar' => 'string',
      'string' => 'string',
      'char' => 'string',
      'enum' => 'string',
      'set' => 'string',
      'mediumtext' => 'string',
      'text' => 'string',
      'json' => 'string',
      'date' => 'string',
      'timestamp' => 'string',
    ];

    $cols[] = ['col' => 'table', 'source' => 'node', 'type' => 'string'];
    $cols[] = ['col' => 'table', 'source' => 'edge', 'type' => 'string'];
    $cols[] = ['col' => 'labels', 'source' => 'node', 'type' => 'string'];
    $cols[] = ['col' => 'label', 'source' => 'edge', 'type' => 'string'];

    // TODO store names separatly for edge and nodes
    $names = [];
    foreach($cols as $col) {
      if (!in_array($col['col'], $names)) {
        $names[] = $col['col'];
      } else {
        continue;
      }
      $xml[] = "<key id=\"${col['col']}\" for=\"" . $forMapping[$col['source']] . "\" attr.name=\"${col['col']}\" attr.type=\"" . $typeMapping[$col['type']] . "\" />";
    }
    $xml[] = "<graph id=\"LobbywatchGraph\" edgedefault=\"directed\">";
    return $xml;
  }

  function getTableHeader(string $table, array $export_header, array $table_create_lines, array $table_meta, bool $wrap, bool $first): array {
    return [];
  }

  function getTableFooter(string $table, array $table_meta, bool $wrap, bool $last): array {
    return [];
  }

  function formatRow(array $row, array $data_types, int $level, string $table_key, string $table, array $table_meta): string {
    assert(count($row) === count($data_types));
    $source = $table_meta['source'];
    $type = $source == 'node' ? $source : 'edge';
    $xml_root = new SimpleXMLElement("<root/>");
    $xml_data = $xml_root->addChild($type);
    if ($type == 'node') {
      $label = ':' . ucfirst($table);
      $xml_data->addAttribute("id", htmlspecialchars("${table_key}_${row['id']}", ENT_XML1));
      $xml_data->addChild("data", htmlspecialchars($label, ENT_XML1))
        ->addAttribute("key", htmlspecialchars('labels', ENT_XML1));
    } elseif ($type == 'edge') {
      $xml_data->addAttribute("id", htmlspecialchars("edge_${table_key}_${row['id']}", ENT_XML1));

      $type_col = $table_meta['type_col'] ?? null;
      $extra_val = $table_meta['name'] ?? null;

      $label = ($extra_val ? ($type_col ? str_replace(' ', '_', strtoupper($row[$type_col])) : $extra_val) : '');

      $xml_data->addChild("data", htmlspecialchars($label, ENT_XML1))
        ->addAttribute("key", htmlspecialchars('label', ENT_XML1));
    }

    $i = 0;
    foreach ($row as $col => $value) {
      $isJson = $data_types[$i] === 'json';

      // Do not export JSON fields: OrientDB cannot import them error:
      // Error: com.orientechnologies.orient.core.db.tool.ODatabaseImportException: Invalid format. Found unsupported tag 'Name'
      if ($isJson) {
        continue;
      }

      // DONE handle JSON data as CDATA → does not help
      // https://stackoverflow.com/questions/6260224/how-to-write-cdata-using-simplexmlelement
      // $xml->title = NULL; // VERY IMPORTANT! We need a node where to append
      // $xml->title->addCData('Site Title');
      // $xml->title->addAttribute('lang', 'en');

      $xml_data->addChild("data", /*($isJson ? '<![CDATA[' : '') .*/ htmlspecialchars(is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK | JSON_THROW_ON_ERROR) : (string) $value, ENT_XML1) /*. ($isJson ? ']]>' : '')*/)
        ->addAttribute("key", htmlspecialchars($col, ENT_XML1));

      if ($type == 'edge') {
        if ($col == $table_meta['start_id'] && ($table_meta['id'] ?? 'id') == $table_meta['start_id']) {
          $xml_data->addAttribute("source", htmlspecialchars($table . '_' . $row[($table_meta['id'] ?? 'id')], ENT_XML1));
          // $header_field .= ":START_ID({$table}_$col)";
          // $skip_rows_for_empty_field = false;
        } elseif ($col == $table_meta['start_id']) {
          // TODO avoid preg_replace here, fix config data
          $id_space_raw = $table_meta['start_id_space'] ?? $col;
          $id_space = preg_replace('/_id$/', '', $id_space_raw);
          $start_id = $row[$table_meta['start_id']];
          $xml_data->addAttribute("source", htmlspecialchars("${id_space}_$start_id", ENT_XML1));
          // $header_field .= ":START_ID($id_space)";
          // $skip_rows_for_empty_field = true;
        } elseif ($col == $table_meta['end_id']) {
          $id_space_raw = $table_meta['end_id_space'] ?? $col;
          $id_space = preg_replace('/_id$/', '', $id_space_raw);
          $end_id = $row[$table_meta['end_id']];
          $xml_data->addAttribute("target", htmlspecialchars("${id_space}_$end_id", ENT_XML1));
          // $header_field .= ":END_ID($id_space)";
          // $skip_rows_for_empty_field = true;
        }
      }
      $i++;
    }
    // return $this->array2xml($type, 'data', $outer);
    $str = '';
    foreach ($xml_root as $elem) {
      $str .= $elem->asXML();
    }
    return str_replace("\n", '&#xA;', $str);
  }

}

abstract class AggregatedTextExporter extends AggregatedExporter {

  protected $list_prefix;
  protected $property_prefix;
  protected $indent;

  function formatRow(array $row, array $data_types, int $level, string $table_key, string $table, array $table_meta): string {
    $levels = array_fill(0, count($row), $level);
    $name = $row['anzeige_name'] ?? $row['name'] ?? $row['id'] ?? 'Item';
    $tableName = $table_meta['display_name'] ?? $table;
    $str = $this->list_prefix . "$name:\n" . implode('', array_map([$this, 'serialize_field'], $row, array_keys($row), $levels));
    return preg_replace('/\n+$/', '', $str);
  }

  protected function cleanField(string $str): string {
    return $str;
  }

  abstract protected function getFieldStr(array $lines, array $indented): string;

  protected function serialize_field($field, string $key, int $level): string {
    $str = '';
    if (is_array($field[0] ?? null)) {
      $str .= str_repeat(' ', $level * $this->indent) . $this->property_prefix . "$key:$this->eol";
      foreach ($field as $row) {
        $levels = array_fill(0, count($row), $level + 2);
        $name = $row['anzeige_name'] ?? $row['name'] ?? $row['id'] ?? 'Item';
        $str .= str_repeat(' ', ($level + 1) * $this->indent) . $this->list_prefix . "$name:$this->eol";
        $str .= implode('', array_map([$this, 'serialize_field'], $row, array_keys($row), $levels));
      }
    } elseif (is_array($field ?? null)) {
      $row = $field;
      $str .= str_repeat(' ', $level * $this->indent) . $this->property_prefix . "$key:$this->eol";
      $levels = array_fill(0, count($row), $level + 1);
      $str .= implode('', array_map([$this, 'serialize_field'], $row, array_keys($row), $levels));
    } else {
      $lines = explode("\n", $this->cleanField("$field"));
      $indented = array_map(function($line) use ($level) { return str_repeat(' ', ($level + 1) * $this->indent) . $line; }, $lines);
      $str .= str_repeat(' ', $level * $this->indent) . $this->property_prefix . "$key: " . $this->getFieldStr($lines, $indented) . $this->eol;
    }
    return $str;
  }

}

// TODO YAML ids & references
// TODO YAML null
class YamlExporter extends AggregatedTextExporter {
  function __construct() {
    $this->format = 'yaml';
    $this->fileSuffix = 'yaml';
    $this->formatName = 'YAML';
    $this->list_prefix = '- ';
    $this->property_prefix = '';
    $this->indent = 2;
  }

  function getFileHeader(bool $wrap, string $transaction_date): array {
    return ['%YAML: 1.1', '---', "# $transaction_date"];
  }

  function getTableHeader(string $table, array $export_header, array $table_create_lines, array $table_meta, bool $wrap, bool $first): array {
    return ['', ucfirst($table) . ':'];
  }

  protected function getFieldStr(array $lines, array $indented): string {
    switch (count($lines)) {
      case 0: return '';
      case 1: return $lines[0];
      default: return "|" . $this->eol . implode($this->eol, $indented);
    }
  }

}

class MarkdownExporter extends AggregatedTextExporter {
  function __construct() {
    $this->format = 'md';
    $this->fileSuffix = 'md';
    $this->formatName = 'Markdown';
    $this->list_prefix = '* ';
    $this->property_prefix = '* ';
    $this->indent = 4;
 }

  function getFileHeader(bool $wrap, $transaction_date): array {
    return ['Lobbywatch',
            '==========',
            '',
            "Exportdatum: $transaction_date"];
  }

  function getTableHeader(string $table, array $export_header, array $table_create_lines, array $table_meta, bool $wrap, bool $first): array {
    return ['', str_repeat('#', 2) . " " . ucfirst($table), ''];
  }

  protected function cleanField(string $str): string {
    return preg_replace('/\n+/', "\n", $str);
  }

  protected function getFieldStr(array $lines, array $indented): string {
    array_shift($indented);
    return $lines[0] . (!empty($indented) ? $this->eol . implode($this->eol, $indented) : '');
  }

}

main();

function main() {
  global $verbose;
  global $db_connection;
  global $env;

//     var_dump($argc); //number of arguments passed
//     var_dump($argv); //the arguments passed
  // :  -> mandatory parameter
  // :: -> optional parameter
  $options = getopt('hv::n::cjaxXgmosltp:e::1d:', ['help','user-prefix:', 'db:', 'sep:', 'eol:', 'qe:', 'arangodb', 'slow::']);

//    var_dump($options);

  if (isset($options['v'])) {
    if ($options['v']) {
      $verbose = $options['v'];
    } else {
      $verbose = 1;
    }
     print("-- Verbose level: $verbose\n");
  } else {
    $verbose = 0;
  }

  if (isset($options['n'])) {
    if ($options['n']) {
      $records_limit = $options['n'];
    } else {
      $records_limit = 10;
    }
    print("-- Records limit: $records_limit\n");
  } else {
    $records_limit = null;
  }

  if (isset($options['user-prefix'])) {
    if ($options['user-prefix']) {
      $user_prefix = $options['user-prefix'];
    } else {
      $user_prefix = '';
    }
    print("-- User prefix: $user_prefix\n");
  } else {
    $user_prefix = '';
  }

  if (isset($options['db'])) {
    $db_name = $options['db'];
    print("-- DB index: $db_name\n");
  } else {
    $db_name = null;
  }

  $one_file = isset($options['1']) ?? false ? 'one_file': 'multi_file';

  if (isset($options['sep'])) {
    $sep = $options['sep'];
  } else {
    $sep = null;
  }

  if (isset($options['eol'])) {
    $eol = $options['eol'];
  } else {
    $eol = "\n";
  }

  if (isset($options['qe'])) {
    $qe = $options['qe'];
  } else {
    $qe = null;
  }

  if (isset($options['p'])) {
    $path = $options['p'];
    print("Path: $path\n");
  } else {
    $path = 'export';
  }

  if (isset($options['d'])) {
    $schema = $options['d'];
  } else {
    $schema = 'lobbywatchtest';
  }
  print("-- Schema: $schema\n");

  if (!file_exists($path . '/' . DOCU) && !is_dir($path . '/' . DOCU)) {
    $ret = mkdir($path . '/' . DOCU, 0777, true);
    if ($ret == true)
      print("directory '$path' created successfully...");
    else
      print( "directory '$path' is not created successfully...");
  }

  $db = get_PDO_lobbywatch_DB_connection($db_name, $user_prefix);
  utils_set_db_session_parameters_exec($db);
  print("-- $env: {$db_connection['database']}\n");

  if (isset($options['h']) || isset($options['help'])) {
    print("DB export
Parameters:
-g                  Export csv for Neo4j graph DB to PATH
-m                  Export GraphML DB to PATH
-o                  Generate JSON ETL for OrientDB to PATH
--arangodb          Generate  for ArangoDB to PATH
-c                  Export plain csv to PATH
-j                  Export JSON to PATH
-l                  Export JSONL to PATH
-t                  Text format YAML and MD export to PATH
-x                  Export XML using attributes to PATH
-X                  Export XML only as tags to PATH
-s                  Export SQL to PATH
-a                  Export all formats to PATH
-e=LIST             Type of data to export, add this type of data -e=hist, -e=intern, -e=unpubl, -e=hist+unpubl+intern (default: filter at most)
--slow[=NUMBER]     Include slow exports, level 0 to 3, 0 no slow exports, 3 including slowest exports (default: 0, default of switch: 3)
-p=PATH             Export path (default: export/)
-1                  Export JSON as one file
--sep=SEP           Separator char for columns (default: \\t)
--eol=EOL           End of line (default: \\n)
--qe=QE             Quote escape (default: \")
-n[=NUMBER]         Limit number of records, positive numbers use SQL LIMIT, negative use only breaked loops
-d=SCHEMA           DB schema (default SCHEMA: lobbywatchtest)
--user-prefix=USER  Prefix for db user in settings.php (default: reader_)
--db=DB             DB name for settings.php
-v[=LEVEL]          Verbose, optional level, 1 = default
-h, --help          This help
");
  exit(0);
  }

  $filter = [
    'hist' => true,
    'intern' => true,
    'unpubl' => true,
    'slow' => 0
  ];
  if (isset($options['e'])) {
    $f_options = explode('+', (string) $options['e']);
    if (in_array('hist', $f_options)) {
      print("Export: hist\n");
      $filter['hist'] = false;
    }
    if (in_array('intern', $f_options)) {
      print("Export: intern\n");
      $filter['intern'] = false;
    }
    if (in_array('unpubl', $f_options)) {
      print("Export: unpubl\n");
      $filter['unpubl'] = false;
    }
  }

  if (isset($options['slow'])) {
    if ($options['slow']) {
      $filter['slow'] = $options['slow'];
      if ($filter['slow'] < 0 || $filter['slow'] > 3) {
        throw new RuntimeException("Parlameter slow not in range [0..3]: ${filter['slow']}");
      }
    } else {
      $filter['slow'] = 3;
    }
    print("-- Speed filter: ${filter['slow']}\n");
  }



  $start_export = microtime(true);

  if (isset($options['c'])) {
    export(new CsvExporter($sep, $qe), $schema, $path, $filter, $eol, $one_file, $records_limit, $db);
   }

   if (isset($options['s'])) {
    export(new SqlExporter($sep, $qe), $schema, $path, $filter, $eol, 'one_file', $records_limit, $db);
  }

  if (isset($options['m'])) {
    export(new GraphMLExporter($sep, $qe), $schema, $path, $filter, $eol, $one_file, $records_limit, $db);
   }

   if (isset($options['g'])) {
    export(new Neo4jCsvExporter($sep, $qe), $schema, $path, $filter, $eol, $one_file, $records_limit, $db);
  }

  if (isset($options['arangodb'])) {
    export(new ArangoDBJsonlExporter(), $schema, $path, $filter, $eol, $one_file, $records_limit, $db);
  }

  if (isset($options['j'])) {
    export(new JsonExporter(), $schema, $path, $filter, $eol, $one_file, $records_limit, $db);
  }

  if (isset($options['l'])) {
    export(new JsonlExporter(), $schema, $path, $filter, $eol, $one_file, $records_limit, $db);
  }

  if (isset($options['o'])) {
    // export(new JsonExporter(), $schema, $path, $filter, $eol, 'multi_file', $records_limit, $db);
    export(new JsonOrientDBExporter(), $schema, $path, $filter, $eol, 'multi_file', $records_limit, $db);
  }

  if (isset($options['x'])) {
    export(new XmlExporter(true), $schema, $path, $filter, $eol, $one_file, $records_limit, $db);
  }

  if (isset($options['X'])) {
    export(new XmlExporter(false), $schema, $path, $filter, $eol, $one_file, $records_limit, $db);
  }

  if (isset($options['t'])) {
    export(new YamlExporter(), $schema, $path, $filter, $eol, $one_file, $records_limit, $db);
    export(new MarkdownExporter(), $schema, $path, $filter, $eol, $one_file, $records_limit, $db);
  }

  if (isset($options['a'])) {
    export(new CsvExporter($sep, $qe), $schema, $path, $filter, $eol, 'multi_file', $records_limit, $db);
    export(new Neo4jCsvExporter($sep, $qe), $schema, $path, $filter, $eol, 'multi_file', $records_limit, $db);
    export(new JsonOrientDBExporter(), $schema, $path, $filter, $eol, 'multi_file', $records_limit, $db);
    export(new SqlExporter($sep, $qe), $schema, $path, $filter, $eol, 'one_file', $records_limit, $db);
    export(new JsonExporter(), $schema, $path, $filter, $eol, 'one_file', $records_limit, $db);
    export(new JsonExporter(), $schema, $path, $filter, $eol, 'multi_file', $records_limit, $db);
    export(new JsonlExporter(), $schema, $path, $filter, $eol, 'multi_file', $records_limit, $db);
    export(new ArangoDBJsonlExporter(), $schema, $path, $filter, $eol, 'multi_file', $records_limit, $db);
    export(new GraphMLExporter(), $schema, $path, $filter, $eol, 'one_file', $records_limit, $db);
    export(new XmlExporter(), $schema, $path, $filter, $eol, 'one_file', $records_limit, $db);
    export(new XmlExporter(), $schema, $path, $filter, $eol, 'multi_file', $records_limit, $db);
    export(new YamlExporter(), $schema, $path, $filter, $eol, 'one_file', $records_limit, $db);
    export(new YamlExporter(), $schema, $path, $filter, $eol, 'multi_file', $records_limit, $db);
    export(new MarkdownExporter(), $schema, $path, $filter, $eol, 'one_file', $records_limit, $db);
    export(new MarkdownExporter(), $schema, $path, $filter, $eol, 'multi_file', $records_limit, $db);
  }

  print(getMemory() . "\n");
  $end_export = microtime(true);
  print("Total time elapsed: " . round($end_export - $start_export) . "s\n");
}

// https://neo4j.com/docs/operations-manual/current/tools/import/file-header-format/
// https://neo4j.com/docs/operations-manual/current/tools/import/
// https://neo4j.com/docs/operations-manual/current/tools/import/options/

// neo4j_home$ ls import
// actors-header.csv  actors.csv.zip  movies-header.csv  movies.csv.gz  roles-header.csv  roles.csv.gz
// neo4j_home$ bin/neo4j-admin import --nodes import/movies-header.csv,import/movies.csv.gz --nodes import/actors-header.csv,import/actors.csv.zip --relationships import/roles-header.csv,import/roles.csv.gz

// https://neo4j-contrib.github.io/neo4j-apoc-procedures/#schema
// CALL apoc.meta.graph

// MATCH (n)
// OPTIONAL MATCH (n)-[r]-()
// WITH n,r LIMIT 50000
// DELETE n,r
// RETURN count(n) as deletedNodesCount

// DONE unified export main loop/function?
// DONE add csv?
// DONE add csv_neo4j
// DONE add sql?
// DONE call format specific rows function?
// DONE one big input table with all definitions?
// DONE one big input table with all definitions: format preferences in table?, restrictions/characteristics
// DONE strategy: 1. keep dedicated export functions, 2. extend/enrich structured export function with dedicated functionality

function export(IExporter $exporter, string $table_schema, string $path, array $filter, string $eol = "\n", string $storage_type = 'multi_file', $records_limit = false, PDO $db) {
  global $verbose;
  global $data_source;
  global $intern_fields;
  global $transaction_date;

  $start_export_tables = microtime(true);

  if ($verbose >= 0) print("Export " . $exporter->getFormatName() . ($storage_type == 'one_file' ? ' 1' : '') . "\n");
  if ($verbose > 2) print(getMemory() . "\n");

  $sql = "USE $table_schema";
  if ($verbose > 2) print("$sql\n");
  $db->exec($sql);

  $cmd_args_sep = '';
  $cmd_args = $exporter->getImportHint($cmd_args_sep);

  $export_tables = [];
  foreach ($data_source as $source => $tables) {
    if (in_array($source, $exporter->getDataSourceKeys())) {
      $mapped_tables = array_map(function(array $array, string $key) use ($source) {
        $array['source'] = $source;
        $array['tkey'] = $key;
        return $array;
      }, $tables, array_keys($tables));
      $export_tables = array_merge($export_tables, $mapped_tables);
    }
  }

  if ($exporter->prefersOneFile()) {
    $storage_type = 'one_file';
  }

  // Write file header
  if ($storage_type == 'one_file') {
    $export_file_name = "lobbywatch." . $exporter->getFileSuffix();
    $export_file_path_name = "$path/$export_file_name";
    $export_file = fopen($export_file_path_name, 'w');

    $docu_file_path_name = "$path/" . DOCU . "/$export_file_name.md";
    $docu_file = fopen($docu_file_path_name, 'w');

    fwrite($export_file, implode($eol, $exporter->getFileHeader(true, $transaction_date)));
    fwrite($docu_file, implode(EOL, getDocuFileHeader($exporter, $export_tables, $transaction_date, $table_schema, $filter, $export_file_path_name)));
  } elseif ($storage_type == 'multi_file') {
    $export_file = null;
    $docu_file = null;
  }

  $docu_table_written = [];

  export_tables($exporter, $export_tables, null, null, 1, $table_schema, $path, $filter, $eol, 'file', $storage_type, $export_file, $docu_file, $records_limit, $cmd_args, $db, $docu_table_written);

  // Write file end
  if ($storage_type == 'one_file') {
    fwrite($export_file, implode($eol, $exporter->getFileFooter(true)));
    fclose($export_file);

    $exporter->validate($export_file);

    fwrite($docu_file, implode(EOL, getDocuFileFooter($exporter, $export_tables, $transaction_date, $table_schema, $filter, $export_file_path_name)));
    fclose($docu_file);
  }

  if ($verbose > 1) print(implode($cmd_args_sep, $cmd_args) . "\n\n");

  if (!empty($cmd_args)) {
    $cmd_file_name = "$path/" . $exporter->getFormat() . '_lobbywatch_import.sh';
    $cmd_file = fopen($cmd_file_name, 'w');
    fwrite($cmd_file, implode($cmd_args_sep, $cmd_args) . "\n\n");
    fclose($cmd_file);
    if ($verbose > 0) print("\nCmd file '$cmd_file_name' written\n");
  }

  $end_export_tables = microtime(true);
  if ($verbose > 1) print($exporter->getFormatName() . ($storage_type == 'one_file' ? ' 1' : '') . ": Time elapsed: " . round($end_export_tables - $start_export_tables) . "s\n");
  if ($verbose > 1) print(getMemory() . "\n");
}

function setTableAliasToCols(array $cols, string $tableAlias, bool $addAlias = true): array {
  if ($addAlias) {
    $prefixed_cols = array_map(function($str) use ($tableAlias) {return preg_match('/\./', $str) ? $str : "$tableAlias.$str";}, $cols);
    return $prefixed_cols;
  } else {
    return $cols;
  }
}

function getAliasCols(array $cols): array {
  // https://stackoverflow.com/questions/1530883/regex-to-split-a-string-only-by-the-last-whitespace-character
  $regex = '/\s+(?=\S*+$)/';
  $select_cols = array_map(function($str) use ($regex) {return preg_split($regex, $str)[0];}, $cols);
  $select_alias_cols = array_map(function($str) use ($regex) {return preg_split($regex, $str)[1] ?? null;}, $cols);
  $alias_map = array_combine($select_cols, $select_alias_cols);
  $select_field_map = array_combine($select_cols, $cols);
  return [$select_cols, $select_alias_cols, $alias_map, $select_field_map];
}

function isColOk(string $col, array $table_meta, string $table_name, string $query_table_alias, array $intern_fields, array $filter) {
  // separate name and alias
  list($select_cols, $select_alias_cols, $alias_map, $select_field_map) = getAliasCols(array_merge(setTableAliasToCols($table_meta['select_cols'] ?? [], $query_table_alias, hasJoin($table_meta)), $table_meta['additional_join_cols'] ?? []));

  $remove_cols = setTableAliasToCols($table_meta['remove_cols'] ?? [], $query_table_alias);

  return (!isset($table_meta['select_cols']) || in_array($col, $select_cols) || in_array("$table_name.$col", $select_cols)) &&
      (!isset($table_meta['remove_cols']) || !in_array("$table_name.$col", $remove_cols)) &&
      (!$filter['intern'] || !in_array($col, $intern_fields)) &&
      (!$filter['intern'] || !in_array("$table_name.$col", $intern_fields))
      || $col == ($table_meta['id'] ?? 'id')
      || (!empty($table_meta['id']) && $col == $table_meta['id'] && $table == $table_name)
      || (!empty($table_meta['start_id']) && $col == $table_meta['start_id'])
      || (!empty($table_meta['end_id']) && $col == $table_meta['end_id'])
      || (!$filter['hist'] && ($col == ($fg = $table_meta['aktiv'] ?? 'aktiv') || "$table_name.$col" == $fg))
      || ((!$filter['unpubl'] || !$filter['hist']) && !empty($table_meta['unpubl_fields']) && (in_array($col, $table_meta['unpubl_fields']) || in_array("$table_name.$col", $table_meta['unpubl_fields'])))
      || starts_with($col, '('); // expression
}

function getJoinTableMaps(array $join): array {
  preg_match_all('/JOIN\s+(\S+)\s+(\S+)?\s*ON/i', implode(' ', $join), $matches, PREG_UNMATCHED_AS_NULL);
  $join_table_alias_map = array_combine($matches[1], $matches[2]);
  $join_alias_table_map = [];
  // $join_alias_table_map = array_combine($matches[2], $matches[1]); // alias might be null
  foreach($join_table_alias_map as $table => $alias) {
    $join_alias_table_map[$alias ?? $table] = $table;
  }
  return [$join_table_alias_map, $join_alias_table_map];
}

function sortRows(array $unsorted_rows, array $ordered_col_names): array {
  $sorted_rows = [];
  $remaining_rows = $unsorted_rows;
  foreach ($ordered_col_names as $col_name) {
    foreach ($remaining_rows as $key => $row) {
      if (str_replace("'", '', $col_name) === $row['COLUMN_NAME']) {
        $sorted_rows[] = $row;
        unset($remaining_rows[$key]);
        break;
      }
    }
  }
  $all_rows_in_order = array_merge($sorted_rows, $remaining_rows);
  return $all_rows_in_order;
}

/**
 * @param array $select_cols No table alias, eg. p.name
 */
function getFilteredFields(array $select_cols, string $table_alias_name): array {
  return array_map(function($str) { return preg_replace('/^([^.]+\.)?(\S+)( \S+)?$/', '\2', $str); }, array_filter($select_cols, function($str) use ($table_alias_name) {$alias = preg_replace('/^([^.]+\.)?(\S+)( \S+)?$/', '\1', $str); return empty($alias) || $alias === $table_alias_name . '.';}));
}
/**
 * @param array $select_cols No table alias, eg. p.name
 */
function getFilteredExpressionsSelect(array $select_cols): array {
  return array_filter($select_cols, function($str) {return preg_match('/^\(/', $str);}, ARRAY_FILTER_USE_KEY);
}

function getCleanQueryTableName($query_table) {
  return preg_replace('/(v_|_medium|_raw|_simple|_liste)/', '', $query_table);
}

// Idea: get datatypes from query limit 0 instead of information schema (this allows SQL like CONCAT in stmts), use getColumnMeta()
function getSqlData(string $num_key, array $table_meta, string $table_schema, int $level, array $filter, IExporter $exporter, PDO $db) {
  global $verbose;

  $level_indent = str_repeat("\t", $level);

  $table_key = $table_meta['tkey'] ?? $num_key;
  $table = $table_meta['table'] ?? $table_key;
  $query_table_with_alias = $table_meta['view'] ?? $table;
  $query_table = explode(' ', $query_table_with_alias)[0];
  $query_table_alias = explode(' ', $query_table_with_alias)[1] ?? $query_table;
  $join = $table_meta['join'] ?? null;
  $source = $table_meta['source'] ?? null;
  $schema_query_table = "$table_schema.$query_table";

  list($table_alias_map, $alias_table_map) = getJoinTableMaps($table_meta['join'] ?? []);
  // TODO move into getJoinTableMaps()
  $table_alias_map[$query_table] = $query_table_alias;
  $alias_table_map[$query_table_alias ?? $query_table] = $query_table;

  $hist_table_alias_map = [];
  $hist_cols = [];
  if (!$filter['hist'] && !empty($table_meta['hist_field'])) {
    $hist_fields = is_array($table_meta['hist_field']) ? $table_meta['hist_field'] : [$table_meta['hist_field']];
    foreach ($hist_fields as $hist_col) {
      preg_match('/(([^.])\.)?(\S+)/i', $hist_col, $matches, PREG_UNMATCHED_AS_NULL);
      $hist_col_table_alias = $matches[2] ?? $query_table_alias;
      $hist_cols[] = $matches[3] ?? null;
      $hist_table_alias_map[$alias_table_map[$hist_col_table_alias]] = $hist_col_table_alias;
    }
  }

  // TODO build expression with hist fields
  $aktiv_cols = !$filter['hist'] ? array_map(function($table, $alias) use ($filter, $table_meta, $exporter) { return ("$alias." ?? '') . ($fg = $table_meta['aktiv'] ?? 'aktiv') . ' ' . $exporter->formatFieldAlias(getCleanQueryTableName($table), 'aktiv');}, array_keys($hist_table_alias_map), $hist_table_alias_map) : [];

  // TODO refactor
  $unpubl_cols = [];
  if ((!$filter['unpubl'] || !$filter['hist']) && !empty($table_meta['unpubl_fields'])) {
    // list($unpubl_select_cols, $unpubl_select_alias_cols, $unpubl_alias_map, $unpubl_select_field_map) = getAliasCols($table_meta['unpubl_fields']);
    // foreach ($table_meta['unpubl_fields'] as $col) {
    //   preg_match('/(([^.])\.)?(\S+)/i', $col, $matches, PREG_UNMATCHED_AS_NULL);
    //   $col_table_alias = $matches[2] ?? $query_table_alias;
    //   $col_field = $matches[3] ?? null;
    //   $publ_table_alias_map[$alias_table_map[$col_table_alias]] = $col_table_alias;

    //   $unpubl_cols_field = !$filter['unpubl'] || !$filter['hist'] ? array_map(function($table, $alias) use ($filter, $table_meta, $unpubl_col) { return ("$alias." ?? '') . ($unpubl_col) . ' ' . getCleanQueryTableName($table) . '_aktiv';}, array_keys($hist_table_alias_map), $hist_table_alias_map) : [];
    //   $unpubl_cols = array_merge($unpubl_cols, $unpubl_cols_field);
    // }
    $unpubl_cols = !$filter['unpubl'] || !$filter['hist'] ? array_map(function($col) use ($query_table_alias, $alias_table_map, $exporter) {preg_match('/(([^.])\.)?(\S+)/i', $col, $matches, PREG_UNMATCHED_AS_NULL); $col_table_alias = $matches[2] ?? $query_table_alias; $col_field = $matches[3] ?? null; return ("$col_table_alias." ?? '') . ($col_field) . ' ' . $exporter->formatFieldAlias(getCleanQueryTableName($alias_table_map[$col_table_alias]), $col_field);}, $table_meta['unpubl_fields']) : [];
  }

  $expression_cols = !$filter['unpubl'] && !($exporter instanceof SqlExporter) ? array_map(function($table, $alias) use ($filter, $table_meta, $exporter) { return ("(IFNULL($alias." .  ($table_meta['freigabe_date'] ?? 'freigabe_datum') . " <= NOW(), FALSE))") . ' ' . $exporter->formatFieldAlias(getCleanQueryTableName($table), 'published');}, array_keys($table_alias_map), $table_alias_map) : [];

  list($select_cols, $select_alias_cols, $alias_map, $select_field_map) = getAliasCols(array_merge(setTableAliasToCols($table_meta['select_cols'] ?? [], $query_table_alias, hasJoin($table_meta)), $table_meta['additional_join_cols'] ?? [], $aktiv_cols, $unpubl_cols, $expression_cols));

  $id_alias = $alias_map[$query_table_alias . '.' . ($table_meta['id'] ?? 'id')] ?? $table_meta['id'] ?? 'id';

  static $stmt_information_schema_cols;
  if (empty($stmt_information_schema_cols)) {
    $sql = "SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE, IF(EXTRA LIKE '%VIRTUAL%', 1, 0) `VIRTUAL_COLUMN`, COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = :table_schema AND table_catalog='def' AND TABLE_NAME = :table;";
    if ($verbose > 5) print("$level_indent$sql\n");
    $stmt_information_schema_cols = $db->prepare($sql);
  }

  $cols_filtered_cleaned = getFilteredFields($select_cols, $query_table_alias);
  $cols_cache_key = "$schema_query_table#All#" . implode(',', $cols_filtered_cleaned);
  static $cached_cols = [];
  if (empty($cached_cols[$cols_cache_key])) {
    $stmt_information_schema_cols->execute(['table_schema' => $table_schema, 'table' => $query_table]);
    $unsorted_rows = $stmt_information_schema_cols->fetchAll();
    $sorted_rows = sortRows($unsorted_rows, $cols_filtered_cleaned);
    $cached_cols[$cols_cache_key] = $sorted_rows;
  }
  $cols = $cached_cols[$cols_cache_key];

  $join_cols = [];
  if ($join && !empty($table_meta['additional_join_cols'])) {
    list($join_table_alias_map, $join_alias_table_map) = getJoinTableMaps($join);
    $additional_export_cols_all = array_merge($aktiv_cols, $unpubl_cols);
    $additional_export_cols = [];
    foreach ($join_table_alias_map as $join_table => $join_alias) {
      $additional_export_cols_filtered = getFilteredFields($additional_export_cols_all, $join_alias); // Not the best function, but just needed for filtering and counting later
      $additional_export_cols = array_merge($additional_export_cols, $additional_export_cols_filtered);
      $join_table_alias_name = $join_alias ?? $join_table;
      $join_table_without_schema = preg_replace('/^([^.]+\.)/', '', $join_table);

      $additional_cols_filtered_cleaned = getFilteredFields($select_cols, $join_table_alias_name);

      if (!empty($additional_cols_filtered_cleaned)) {
        $additional_cols_cache_key = "$table_schema.$join_table_without_schema#" . implode(',', $additional_cols_filtered_cleaned);
        if (empty($cached_cols[$additional_cols_cache_key])) {
          $additional_cols_quoted = array_map(function($str) { return "'$str'"; }, $additional_cols_filtered_cleaned);
          $additional_cols_str = implode(', ', $additional_cols_quoted);
          $sql = "SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE, IF(EXTRA LIKE '%VIRTUAL%', 1, 0) `VIRTUAL_COLUMN`, COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$table_schema' AND table_catalog='def' AND TABLE_NAME = '$join_table_without_schema' AND COLUMN_NAME IN ($additional_cols_str);";
          if ($verbose > 5) print("$level_indent$sql\n");
          $stmt_information_schema_cols_in_list = $db->query($sql);
          $unsorted_rows = $stmt_information_schema_cols_in_list->fetchAll();
          $sorted_rows = sortRows($unsorted_rows, $additional_cols_filtered_cleaned);
          $cached_cols[$additional_cols_cache_key] = $sorted_rows;
        }
        $new_join_cols = $cached_cols[$additional_cols_cache_key];

        $join_cols = array_merge($join_cols, $new_join_cols);
      }
    }
    if (count($table_meta['additional_join_cols']) + count($additional_export_cols)  !== count($join_cols)) {
      print("additional_join_cols:\n");
      print_r($table_meta['additional_join_cols']);
      print("additional_export_cols:\n");
      print_r($additional_export_cols);
      print("actual cols:\n");
      print_r(array_map(function($e) {return "${e['TABLE_NAME']}.${e['COLUMN_NAME']}";}, $join_cols));
      throw new RuntimeException("Additional join cols not same count for '$table_key': " . (count($table_meta['additional_join_cols']) + count($additional_export_cols)) . ' != ' . count($join_cols));
    }
  }

  $expression_cols_types = [];
  $expressions_filtered = getFilteredExpressionsSelect($alias_map);
  $expression_rows = [];
  if (!empty($expressions_filtered)) {
    $expression_cols_cache_key = "$table_schema.$query_table_with_alias#" . implode(',', $expressions_filtered);
    if (empty($cached_cols[$expression_cols_cache_key])) {
      list($sql, $parent_id_col, $sql_select, $sql_from, $sql_join, $sql_where, $sql_order) = buildRowsSelect($table, $query_table_alias, $query_table_with_alias, $table_meta, array_map(function ($str, $alias) { return $str . ' ' . $alias;}, array_keys($expressions_filtered), $expressions_filtered), $filter, 0);

      $sql = "$sql_select$sql_from$sql_join$sql_where LIMIT 0;";
      if ($verbose > 5) print("$level_indent$sql\n");
      $stmt_expression_col_types = $db->query($sql);
      $unsorted_rows = [];
      // $stmt->getColumnCount() fails, thus use count()
      for ($i = 0; $i < count($expressions_filtered); $i++) {
        $expression_col_types = $stmt_expression_col_types->getColumnMeta($i);
        $pdo_type = $expression_col_types['pdo_type'];
        $data_type = null;
        switch ($pdo_type) {
          case PDO::PARAM_BOOL: $data_type = 'int'; break;
          case PDO::PARAM_NULL: $data_type = 'null'; break;
          case PDO::PARAM_INT: $data_type = 'int'; break;
          case PDO::PARAM_STR: $data_type = 'varchar'; break;
          case PDO::PARAM_STR_NATL: $data_type = 'varchar'; break;
          case PDO::PARAM_STR_CHAR: $data_type = 'char'; break;
          case PDO::PARAM_LOB: $data_type = 'blob'; break;
          default: throw new RuntimeException("Unknown type: $pdo_type");
        }
        $unsorted_rows[] = ['TABLE_NAME' => $query_table, 'COLUMN_NAME' => array_keys($expressions_filtered)[$i], 'DATA_TYPE' => $data_type, 'VIRTUAL_COLUMN' => 0, 'COLUMN_COMMENT' => null];
      }
      $sorted_rows = $unsorted_rows;
      // $sorted_rows = sortRows($unsorted_rows, $cols_filtered_cleaned);
      $cached_cols[$expression_cols_cache_key] = $sorted_rows;
    }
    $expression_rows = $cached_cols[$expression_cols_cache_key];
  }

  $cols = array_merge($cols, $join_cols, $expression_rows);

  return [$table_key, $table, $query_table, $query_table_with_alias, $query_table_alias, $join, $source, $cols, $select_cols, $select_alias_cols, $alias_map, $select_field_map, $table_alias_map, $alias_table_map, $id_alias];
}

function getColNames(array $row, array $table_alias_map, array $alias_map, array $select_field_map, array $table_meta): array {
  $table_name = $row['TABLE_NAME'];
  $col = $row['COLUMN_NAME'];
  $data_type = $row['DATA_TYPE'];
  $virtual_col = $row['VIRTUAL_COLUMN'];
  $col_comment = $row['COLUMN_COMMENT'];

  $table_name_alias = $table_alias_map[$table_name] ?? $table_name;
  $alias = $alias_map["$table_name_alias.$col"] ?? $alias_map[$col] ?? null;
  $select_field = $select_field_map["$table_name_alias.$col"] ?? $select_field_map[$col] ?? (hasJoin($table_meta) ? "$table_name_alias.$col" : $col);
  return [$table_name, $col, $data_type, $virtual_col, $table_name_alias, $alias, $select_field, $col_comment];
}

function export_tables(IExporter $exporter, array $tables, int $parent_id = null, array $parent_row = null, $level, string $table_schema, ?string $path, array $filter, string $eol = "\n", string $format = 'json', string $storage_type, $parent_export_file, $parent_docu_file, $records_limit = false, array &$cmd_args, PDO $db, array &$docu_table_written) {
  global $verbose;
  global $intern_fields;
  global $transaction_date;

  $level_indent = str_repeat("\t", $level);

  if ($storage_type == 'one_file') {
    $export_file = $parent_export_file;
    $docu_file = $parent_docu_file;

    if ($exporter->hasHeaderDeclaration()) {
      if ($verbose > 2) print("${level_indent}Generate header declaration...");
      // Get all attributes for header declaration
      $all_cols = [];
      foreach ($tables as $num_key => $table_meta) {
        list($table_key, $table, $query_table, $query_table_with_alias, $query_table_alias, $join, $source, $cols, $select_cols, $select_alias_cols, $alias_map, $select_field_map, $table_alias_map, $alias_table_map, $id_alias) = getSqlData("$num_key", $table_meta, $table_schema, $level, $filter, $exporter, $db);

        foreach ($cols as $row) {
          list($table_name, $col, $data_type, $virtual_col, $table_name_alias, $alias, $select_field, $col_comment) = getColNames($row, $table_alias_map, $alias_map, $select_field_map, $table_meta);

          if (isColOk($col, $table_meta, $table_name_alias, $query_table_alias, $intern_fields, $filter)) {
            $data_types[] = $data_type;
          $all_cols[] = ['col' => $alias ?? $col, 'source' => $source, 'type' => $data_type, 'table' => $table_name , 'table_alias' => $table_name_alias];
          }
        }
      }

      if (!empty($declaration = $exporter->getHeaderDeclaration($all_cols)))
        fwrite($export_file, implode($eol, $declaration) . $eol);
    }
  }

  $aggregated_tables_data = [];

  $i = 0;
  foreach ($tables as $num_key => $table_meta) {
    $start_export_table = microtime(true);
    $join = implode(' ', $table_meta['join'] ?? []) ;
    $formatName = $exporter->getFormatName();
    $tkey = ($table_meta['source'] ?? '') . '.' . ($table_meta['tkey'] ?? $num_key);
    if ($verbose > 0 && $level < 2 || $verbose > 2) print("$level_indent$tkey [$formatName]\n");

    if (($table_meta['slow'] ?? 0) > $filter['slow']) {
      if ($verbose > 0 && $level < 2 || $verbose > 2) print("${level_indent}Skip slow export (${table_meta['slow']})\n\n");
      continue;
    }

    list($table_key, $table, $query_table, $query_table_with_alias, $query_table_alias, $join, $source, $cols, $select_cols, $select_alias_cols, $alias_map, $select_field_map, $table_alias_map, $alias_table_map, $id_alias) = getSqlData("$num_key", $table_meta, $table_schema, $level, $filter, $exporter, $db);

    if ($storage_type == 'multi_file') {
      $export_file_base_name = "${source}_$table_key";
      $export_file_name = "$export_file_base_name." . $exporter->getFileSuffix();
      $export_file_path_name = "$path/$export_file_name";
      $export_file = fopen($export_file_path_name, 'w');

      if (!empty($header = $exporter->getFileHeader(false, $transaction_date)))
        fwrite($export_file, implode($eol, $header) . $eol);

      $docu_file_path_name = "$path/" . DOCU . "/$export_file_name.md";
      $docu_file = fopen($docu_file_path_name, 'w');

      if (!empty($meta_header = getDocuFileHeader($exporter, $table_meta, $transaction_date, $table_schema, $filter, $export_file_path_name)))
        fwrite($docu_file, implode(EOL, $meta_header) . $eol);
    } elseif ($storage_type == 'one_file') {
      $export_file = $parent_export_file;
      $docu_file = $parent_docu_file;
    } else {
      $export_file = null;
      $docu_file = $parent_docu_file;
    }

    if ($exporter instanceof SqlExporter) {
      $sql = "SHOW CREATE TABLE $table";
      if ($verbose > 2) print("$level_indent$sql\n");
      $table_create = $db->query($sql)->fetchColumn(1);
      $table_create_lines = explode("\n", $table_create);
    } else {
      $table_create_lines = [];
    }

    $data_types = [];
    $skip_rows_for_empty_field = [];
    $select_fields = [];
    $has_extra_col = $exporter->getExtraCol($table_meta) !== null;
    $export_header = $has_extra_col ? [$exporter->getExtraCol($table_meta)] : [];
    $docu_cols = !empty($extraColDesc = $exporter->getExtraColDesc($table_meta)) ? [$extraColDesc] : [];

    foreach ($cols as $row) {
      list($table_name, $col, $data_type, $virtual_col, $table_name_alias, $alias, $select_field, $col_comment) = getColNames($row, $table_alias_map, $alias_map, $select_field_map, $table_meta);

      if (isColOk($col, $table_meta, $table_name_alias, $query_table_alias, $intern_fields, $filter)) {
        if (!($exporter instanceof SqlExporter && $virtual_col)) {
          $data_types[] = $data_type;
          $select_fields[] = $select_field;
          // TODO add @ for attribute

          list($header_field, $skip_row_for_empty_field) = $exporter->getHeaderCol($alias ?? $col, $data_type, $table, $table_meta);
          $export_header[] = $header_field;
          $skip_rows_for_empty_field[] = $skip_row_for_empty_field;
          if ($verbose > 4) print("$level_indent$header_field\n");

          $docu_col = getDocuCol($level, $col, $col_comment, $data_type, $table, $table_meta);
          if (!empty($docu_col)) {
            $docu_cols[] = $docu_col;
          }
        }
      } else {
        // Remove cols from create table statement
        if ($verbose > 3) print("${level_indent}Clean create: $col\n");
        $table_create_lines = array_filter($table_create_lines, function ($line) use ($col) {return strpos($line, "`$col`") === false;});
        // print_r($table_create_lines);
      }
    }

    // remove trailing comma in last definition line 2nd last line
    end($table_create_lines);
    $last_declaration = prev($table_create_lines);
    $last_declaration_key = key($table_create_lines);
    $table_create_lines[$last_declaration_key] = preg_replace('/,$/', '', $last_declaration);
    reset($table_create_lines);

    $num_cols = count($select_fields);

    if (!in_array($format, ['array', 'attribute_array']) && !empty($header = $exporter->getTableHeader($table, $export_header, $table_create_lines, $table_meta, $storage_type != 'multi_file', $storage_type == 'multi_file' || $i === 0))) {
      fwrite($export_file, implode($eol, $header) . $eol);
    }

    if (!($docu_table_written[($docu_table_key = "$table_key#header")] ?? null)) {
      $docu_table_header = getDocuTableHeader($level, $table_key, $table, $query_table, $table_meta, $storage_type != 'multi_file', $storage_type == 'multi_file' || $i === 0);
      fwrite($docu_file, implode(EOL, $docu_table_header) . EOL);
      fwrite($docu_file, implode(EOL, $docu_cols) . EOL);
      $docu_table_written[$docu_table_key] = true;
    }

    if (count(array_unique($export_header)) < count($export_header)) {
      // print("\nERROR: duplicate col names!\n\n");
      print_r(array_count_values($export_header));
      throw new Exception('Duplicate col names');
    }

    if (!$exporter->getExportOnlyHeader()) {
      assert(count($select_fields) === count($data_types));
      $rows_data = export_rows($exporter, $id_alias, $parent_id, $parent_row, $db, $select_fields, $has_extra_col, $table_schema, $table_key, $table, $query_table, $query_table_with_alias, $query_table_alias, $table_meta, $data_types, $skip_rows_for_empty_field, $filter, $eol, $format, $level, $records_limit, $export_file, $docu_file, $cmd_args, $docu_table_written);
      if (in_array($format, ['array', 'attribute_array'])) {
        if (is_array($rows_data[0] ?? null)) {
          // array of data arrays
          $n = count($rows_data);
        } elseif (is_array($rows_data ?? null) && !empty($rows_data)) {
          // only one data array
          $n = 1;
        } else {
          $n = 0;
        }
        $aggregated_tables_data["${table}"] = $rows_data;
      } else {
        $n = $rows_data;
        fwrite($export_file, implode($eol, $exporter->getTableFooter($table, $table_meta, $storage_type != 'multi_file', $storage_type == 'multi_file' || $i === count($tables) - 1)) . $eol);

        if (!($docu_table_written[($docu_table_key = "$table_key#footer")] ?? null)) {
          fwrite($docu_file, implode(EOL, getDocuTableFooter($level, $table_key, $table, $query_table, $table_meta, $storage_type != 'multi_file', $storage_type == 'multi_file' || $i === count($tables) - 1)) . EOL);
          $docu_table_written[$docu_table_key] = true;
        }
      }
    } else {
      $n = 0;
    }

    if ($storage_type == 'multi_file') {
      fwrite($export_file, implode($eol, $exporter->getFileFooter(false)));
      fclose($export_file);

      if ($cmd_arg = $exporter->getImportHintFromTable($export_file_name, $export_file_base_name, $exporter->getFileSuffix(), $export_file_path_name, $table, $table_meta))
      $cmd_args[] = $cmd_arg;

      $exporter->validate($export_file);

      fwrite($docu_file, implode(EOL, getDocuFileFooter($exporter, $table_meta, $transaction_date, $table_schema, $filter, $export_file_path_name)));
      fclose($docu_file);
    }

    $end_export_table = microtime(true);

    if ($verbose > 0 && $level < 2 || $verbose > 2) print("${level_indent}Exported $n rows having $num_cols cols in " . round($end_export_table - $start_export_table) . "s\n");
    if ($verbose > 0 && $level < 2) print("\n");
    $i++;
  }

  if (in_array($format, ['array', 'attribute_array'])) {
    return $aggregated_tables_data;
  }
}

function getRowsIterator(string $sql, array $ids_in_parent = null, int $parent_id = null, string $format, array $table_meta, PDO $db, int &$count): iterable {
  global $verbose;

  static $rowsCache = [];

  if (in_array($format, ['array', 'attribute_array'])) {
    // assert($parent_id != null, "parent_id must not be null in nested rows");
    if (empty($rowsCache[$sql])) {
      if ($verbose > 2) print("Query for Cache: $sql\n");
      $all_rows = $db->query($sql)->fetchAll();
      // https://stackoverflow.com/questions/7574857/group-array-by-subarray-values
      $indexed = [];
      foreach ($all_rows as $key => $item) {
        // Be careful id_in_parent and parent_id control implicit logic, data errors hard to detect
        if (!empty($ids_in_parent)) {
          $id_index = $item[$table_meta['id'] ?? 'id'];
          $indexed[$id_index][$key] = $item;
        } elseif (!empty($parent_id)) {
          $parent_id_index = $item['_parent_id'];
          // remove _parent_id element from $item array: num index and name index
          array_pop($item);
          array_pop($item);
          $indexed[$parent_id_index][$key] = $item;
        } else {
          throw new RuntimeException('Either id_in_parent or parent_id must be given');
        }
      }
      ksort($indexed, SORT_NUMERIC);
      $rowsCache[$sql] = $indexed;
    }
    if (!empty($ids_in_parent)) {
      $rows = [];
      foreach ($ids_in_parent as $id_in_parent) {
        $rows = array_merge($rows, $rowsCache[$sql][$id_in_parent] ?? []);
      }
    } elseif (!empty($parent_id)) {
      $rows = $rowsCache[$sql][$parent_id] ?? [];
    } else {
      throw new RuntimeException('Either id_in_parent or parent_id must be given');
    }
    $count = count($rows);
    return $rows;
  } else {
    if ($verbose > 2) print("Direct DB query: $sql\n");
    $stmt_export = $db->query($sql);
    $count = $db->query("SELECT FOUND_ROWS();")->fetchColumn();
    return $stmt_export;
  }
}

function getHistCondition(array $table_meta, string $table_alias, string $query_table_alias): string {
  $sql_cond = '';
  $hist_fields = is_array($table_meta['hist_field']) ? $table_meta['hist_field'] : [$table_meta['hist_field']];
  foreach ($hist_fields as $hist_col) {
    preg_match('/(([^.])\.)?(\S+)/i', $hist_col, $matches, PREG_UNMATCHED_AS_NULL);
    $hist_col_table_alias = $matches[2] ?? null;
    $hist_col_field = $matches[3] ?? null;
    if ($hist_col_table_alias == $table_alias) {
      $sql_cond .= " AND ($hist_col IS NULL OR $hist_col > NOW())";
    } elseif (empty($hist_col_table_alias)) {
      $sql_cond = " AND ($query_table_alias.$hist_col IS NULL OR $query_table_alias.$hist_col > NOW())";
    }
  }
  return $sql_cond;
}

// Check: add freigabe_datum and bis to indexes to speed up queries
function buildRowsSelect(string $table, string $query_table_alias, string $query_table_with_alias, array $table_meta, array $select_fields, array $filter, $records_limit): array {
  $type_col = $table_meta['type_col'] ?? null;
  $parent_id_col = $table_meta['parent_id'] ?? null;
  $freigabe_datum = $table_meta['freigabe_datum'] ?? 'freigabe_datum';
  $joins = $table_meta['join'] ?? [];
  list($table_alias_map, $alias_table_map) = getJoinTableMaps($joins);

  if ($parent_id_col) {
    $table_alias = hasJoin($table_meta) ? "$query_table_alias." : '';
    $select_fields[] = "$table_alias$parent_id_col _parent_id";
  }
  $sql_select = "SELECT " . implode(', ', $select_fields);

  $sql_from = " FROM $query_table_with_alias";
  $sql_where = " WHERE 1 ";

  if ($filter['unpubl']) {
    $sql_where .= " AND $query_table_alias.$freigabe_datum <= NOW()";
  }
  if ($filter['hist'] && !empty($table_meta['hist_field'])) {
    $sql_where .= getHistCondition($table_meta, $query_table_alias, $query_table_alias);
  }

  $sql_joins = [];
  foreach ($joins as $join) {
    $sql_join = $join;

    preg_match('/JOIN\s+(\S+)\s+(\S+)?\s*ON/i', $join, $matches, PREG_UNMATCHED_AS_NULL);

    $table = $matches[1];
    $alias = $matches[2] ?? null;
    $table_alias = $alias ?? $table;

    if ($filter['hist'] && !empty($table_meta['hist_field'])) {
      $sql_join .= getHistCondition($table_meta, $table_alias, $query_table_alias);
    }
    if ($filter['unpubl']) {
      $sql_join .= " AND $table_alias.$freigabe_datum <= NOW()";
    }

    $sql_joins[] = $sql_join;
  }

  $sql_join = ' '. implode(' ', $sql_joins);

  $sql_order = " ORDER BY $query_table_alias." . ($table_meta['order_by'] ?? $table_meta['id'] ?? 'id');

  $sql_limit = '';
  if ($records_limit > 0) {
    $sql_limit = " LIMIT " . abs($records_limit);
  }

  $sql = $sql_select . $sql_from . $sql_join . $sql_where . $sql_order . $sql_limit . ';';

  return [$sql, $parent_id_col, $sql_select, $sql_from, $sql_join, $sql_where, $sql_order, $sql_limit];
}

function export_rows(IExporter $exporter, string $id_alias, int $parent_id = null, array $parent_row = null, $db, array $select_fields, bool $has_extra_col, string $table_schema, string $table_key, string $table, string $query_table, string $query_table_with_alias, string $query_table_alias, array $table_meta, array $data_types, array $skip_rows_for_empty_field, $filter, string $eol = "\n", string $format = 'json', int $level = 1, $records_limit, $export_file, $docu_file, &$cmd_args, &$docu_table_written) {
  global $verbose;

  $num_indicator = 20;
  $show_limit = 3;

  $level_indent = str_repeat("\t", $level);

  list($sql_raw, $parent_id_col, $sql_select, $sql_from, $sql_join, $sql_where, $sql_order, $sql_limit) = buildRowsSelect($table, $query_table_alias, $query_table_with_alias, $table_meta, $select_fields, $filter, $format, $records_limit);

  if (in_array($format, ['array', 'attribute_array'])) {
    $sql = $sql_select . $sql_from . $sql_join . $sql_where . $sql_order . ';'; // No limit
  } else {
    $sql = $sql_raw;
  }

  $id_in_parent_col = $table_meta['id_in_parent'] ?? null;

  if (!empty($id_in_parent_col)) {
    $ids_in_parent = [];
    foreach (is_array($id_in_parent_col) ? $id_in_parent_col : [$id_in_parent_col] as $elem) {
      $ids_in_parent[] = $parent_row[$elem];
    }
  } else {
    $ids_in_parent = null;
  }

  assert(!(isset($table_meta['id_in_parent']) && isset($table_meta['parent_id'])), "id_in_parent and parent_id must not be set at the same time");

  $rows_count = 0;
  $rows_data = [];
  $skip_counter = 0;
  $i = 0;
  foreach (getRowsIterator($sql, $ids_in_parent, $parent_id, $format, $table_meta, $db, $rows_count) as $row) {
    ++$i;
    if (!(!$records_limit || $i < abs($records_limit))) break;

    for ($j = 0, $skip_row = false; $j < count($skip_rows_for_empty_field) && !$skip_row; $j++) if ($skip_rows_for_empty_field[$j] && is_null($row[$j])) $skip_row = true;

    if ($i > 1 && !$skip_row && !in_array($format, ['array', 'attribute_array'])) fwrite($export_file, $exporter->getRowSeparator() . $eol);

    if (!$skip_row) {
      $id = $row[$id_alias];
      $vals = array_filter($row, function ($key) { return !is_numeric($key); }, ARRAY_FILTER_USE_KEY);

      // TODO set json_decode params
      // TODO replace by transform_field function
      if ($exporter->isAggregatedFormat()) {
        $j = 0;
        foreach ($vals as $key => $val) {
          if ($data_types[$j++] == 'json' && $val !== null) {
              $vals[$key] = json_decode($val, true);
          }
          // TODO fix id is missing in rat.xml <rat>
          if (in_array($format, ['xml', 'attribute_array']) && in_array($key, [$table_meta['id'], 'anzeige_name'])) {
              $vals["@$key"] = $val;
          }
        }
      }

      if (!empty($table_meta['transform_field'])) {
        $j = 0;
        foreach ($vals as $key => $val) {
          for ($i = 0; $i < 10; $i++) {
            if ($function = $table_meta['transform_field'][$i] ?? null) {
              $vals[$key] = $function($val, $key, $data_types[$j], $exporter, $format, $level, $table_key, $table);
            }
          }

          if ($function = $table_meta['transform_field'][$key] ?? null) {
            $vals[$key] = $function($val, $key, $data_types[$j], $exporter, $format, $level, $table_key, $table);
          }
          $j++;
        }
      }

      $vals = array_map(function($field) {return !empty($field) && is_string($field) ? str_replace("\r", '', $field) : $field;}, $vals);

      if (($aggregated_tables = $table_meta['aggregated_tables'] ?? null) && $exporter->isAggregatedFormat()) {
        $aggregated_data = export_tables($exporter, $aggregated_tables, $id, $row, $level + 1, $table_schema, null, $filter, $eol, $format == 'xml' ? 'attribute_array' : 'array', $format == 'xml' ? 'attribute_array' : 'array', null, $docu_file, $records_limit, $cmd_args, $db, $docu_table_written);
        $vals = array_merge($vals, $aggregated_data);
      }

      if (in_array($format, ['array', 'attribute_array'])) {
        // TODO array_xml and array_json for attribute annotation?
        // TODO for array return, use $format or $storage_type?
        switch ($format) {
          case 'array': $rows_data[] = $vals; $row_str = print_r($vals, true); break;
          case 'attribute_array': $rows_data[] = $vals; $row_str = print_r($vals, true); break;
        }
      } else {
        $row_str = $exporter->formatRow($vals, $data_types, $level, $table_key, $table, $table_meta);
        fwrite($export_file, $row_str);
      }
    } else {
      if ($verbose > 2 && $skip_counter++ < 5) print("SKIP $i) $row_str\n");
    }

    // TODO list verbose level output -> make overview
    // TODO refactor verbose level outputs
    if ($verbose > 6 && $i < $show_limit) print("$i) $row_str\n");
    if ($verbose > 0 && ($level < 2 || $verbose > 2) && $i == $show_limit) print($level_indent . str_repeat('_', $num_indicator) . "\r$level_indent");
    if ($verbose > 0 && ($level < 2 || $verbose > 2) && $i >= $show_limit && $rows_count >= $num_indicator && ($i % round($rows_count / $num_indicator) == 0 || $i == $rows_count)) print('.');
  }

  if ($verbose > 0 && ($level < 2 || $verbose > 2)) print("\n");

  // DONE return aggregated array here
  if (in_array($format, ['array', 'attribute_array'])) {
    if (isset($ids_in_parent) && count($ids_in_parent) === 1) {
      return $rows_data[0];
    } else {
      return $rows_data;
    }
  } else {
    return $i;
  }
}

function hasJoin(array $table_meta): bool {
  return !empty($table_meta['join']);
}

function getMemory(): string {
  return convert(memory_get_usage(false)) . ' (' . convert(memory_get_usage(true)) . ') |  ' . convert(memory_get_peak_usage(false)) . ' (' . convert(memory_get_peak_usage(true)) . ')';
}

function convert($size) {
    $unit = ['B','kiB','MiB','GiB','TiB','PiB'];
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

function getDocuTableHeader(int $level, string $table_key, string $table, string $query_table, array $table_meta, bool $wrap, bool $first): array {
  $level_indent = str_repeat("    ", $level);
  $clean_query_table = getCleanQueryTableName($query_table);
  $table_docu = $table_meta['docu'] ?? [];

  $docu = [];
  $docu[] = '';
  $docu[] = str_repeat("#", $level + 1) . " $table_key ($clean_query_table)";
  $docu[] = '';
  if (!empty($table_docu)) {
    $docu[] = implode(EOL, $table_docu);
    $docu[] = '';
  }
  if (!empty($table_meta['source'])) {
    $docu[] = "Datensatztyp: " . ($table_meta['source']);
    $docu[] = '';
  }
  $docu[] = 'Feld | Beschreibung';
  $docu[] = '- | -';
  return $docu;
}

function getDocuTableFooter(int $level, string $table_key, string $table, string $query_table, array $table_meta, bool $wrap, bool $last): array {
  return [""];
}

// see fillHintParams() in utils.php
function getDocuCol(int $level, string $col, $col_comment, string $data_type, string $table, array $table_meta): ?string {
  return "$col | $col_comment";
}

function getDocuFileHeader(IExporter $exporter, array $table_meta, string $transaction_date, string $table_schema, array $filter, string $export_file_path_name): array {

  $is_public_export = !empty($filter['hist']) && !empty($filter['unpubl']);

  $export_type = [];
  if ($is_public_export) {
    $export_type[] = 'Öffentlich / Public';
  } else {
    if (empty($filter['hist'])) $export_type[] ='historisiert';
    if (empty($filter['unpubl'])) $export_type[] ='unveröffentlicht';
    if (empty($filter['intern'])) $export_type[] ='intern';
  }

  $docu = [];
  $docu[] = "Lobbywatch Export: " . ($formatName = $exporter->getFormatName());
  $docu[] = "==================="  . str_repeat('=', strlen($formatName));
  $docu[] = "";
  $docu[] = "Datei: $export_file_path_name  ";
  $docu[] = "Datum: $transaction_date  ";
  if (!empty($table_meta['source'])) {
    $docu[] = "Datensatztyp: " . ($table_meta['source']) . '  ';
  }
  $docu[] = "Exporttyp: " . implode('+', $export_type) . '  ';
  $docu[] = "";
  if (!empty($exporter->getFormatDesc())) {
    $docu[] = "Formatbeschreibung: " . implode(EOL, $exporter->getFormatDesc());
    $docu[] = "";
  }
  $docu[] = "Herausgeber: Lobbywatch (https://lobbywatch.ch)  ";
  if ($is_public_export) {
    $docu[] = "";
    $docu[] = "Die Inhalte von Lobbywatch.ch sind lizenziert unter einer Creative Commons Namensnennung - Weitergabe unter gleichen Bedingungen 4.0 International Lizenz. (https://creativecommons.org/licenses/by-sa/4.0/deed.de)";
    $docu[] = "";
    $docu[] = "Data are licensed as CC BY-SA";
  } else {
    $docu[] = "Dieser Datensatz dürfen ohne Einwilligung des Lobbywatch-Vorstandes nicht veröffentlicht oder weitergegeben werden.";
  }
  $docu[] = "";

  return $docu;
}

function getDocuFileFooter(IExporter $exporter, array $table_meta, string $transaction_date, string $table_schema, array $filter, string $export_file_path_name): array {
  return [''];
}

function transform_sachbereiche($field, $format) {
  $str = str_replace("\r", '', str_replace("\n", '', $field));
  return explode(';', $str);
}
