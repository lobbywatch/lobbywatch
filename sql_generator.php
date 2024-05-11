<?php

// Run: /opt/lampp/bin/php -f sql_generator.php

require_once dirname(__FILE__) . '/public_html/common/utils.php';

// Query form database with
/*
 * SELECT
 T ABLES.`TABLE_NAME` AS *TABLES_TABLE_NAME
 FROM
 `COLUMNS` COLUMNS INNER JOIN `TABLES` TABLES ON COLUMNS.`TABLE_NAME` = TABLES.`TABLE_NAME`
 AND TABLES.`TABLE_SCHEMA` = COLUMNS.`TABLE_SCHEMA`
 WHERE
 TABLES.`TABLE_SCHEMA` = 'lobbywatch'
 AND COLUMNS.`COLUMN_NAME` = 'updated_date'
 AND TABLES.`TABLE_TYPE` = 'BASE TABLE'
 ORDER BY
 TABLES.`TABLE_NAME` ASC
 */
$workflow_tables = array(
    'branche' => 'Branche',
    'interessenbindung' => 'Interessenbindung',
    'interessenbindung_jahr' => 'Interessenbindungsverguetung',
    'interessengruppe' => 'Lobbygruppe',
    'in_kommission' => 'In Kommission',
    'kommission' => 'Kommission',
    'mandat' => 'Mandat',
    'mandat_jahr' => 'Mandatsverguetung',
    'organisation' => 'Organisation',
    'organisation_beziehung' => 'Organisation Beziehung',
    'organisation_jahr' => 'Organisationsjahr',
    'parlamentarier' => 'Parlamentarier',
    'partei' => 'Partei',
    'fraktion' => 'Fraktion',
    'rat' => 'Rat',
    'kanton' => 'Kanton',
    'kanton_jahr' => 'Kantonjahr',
    'zutrittsberechtigung' => 'Zutrittsberechtigung',
    'person' => 'Person',
);

$meta_tables = array(
    'parlamentarier_anhang' => 'Parlamentarieranhang',
    'organisation_anhang' => 'Organisationsanhang',
    'person_anhang' => 'Personenanhang',
    'country' => 'Länder',
    'mil_grad' => 'Militärischer Grad',
    'settings' => 'Einstellungen',
    'settings_category' => 'Einstellungskategorien',
);

$tables = array_merge($workflow_tables, $meta_tables);

// $table_query = "(SELECT
// '$table' tabe_name,
// t.`updated_visa` AS branche_updated_visa,
// MAX(t.`updated_date` )
// FROM
// `$table` t)";

$table_queries = [];
$table_views = [];
$view_queries = [];
$snapshots = [];
$worker = [];
foreach ($tables as $table => $name) {
  //$table_queries2[] = preg_replace('\$table', $table, $table_query);
  // verbose $table_queries version, seems to be generating a too big result for PHP Generator for MySQL
//   $table_queries[] = "(SELECT
//   '$table' table_name,
//   GROUP_CONCAT(t.`updated_visa` SEPARATOR ', ') AS visa,
//   MAX(t.`updated_date`) last_updated
//   FROM
//   `$table` t
//   GROUP BY t.`updated_date`
//   )";
//   $table_queries[] =
//   "(SELECT
//   '$table' table_name,
//   '$name' name,
//   (select count(*) from `$table`) anzahl_eintraege,
//   t.`updated_visa` AS last_visa,
//   t.`updated_date` last_updated,
//   t.id last_updated_id
//   FROM
//   `$table` t
//   ORDER BY t.`updated_date` DESC
//   LIMIT 1
//   )";
  $table_queries[] =
  "(SELECT '$table' tn, '$name' n, (select count(*) from `$table`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `$table` t ORDER BY t.`updated_date` DESC LIMIT 1)";
  $table_views[] =
  "CREATE OR REPLACE VIEW `v_last_updated_$table` AS
  (SELECT
  '$table' table_name,
  '$name' name,
  (select count(*) from `$table`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `$table` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );";
  $view_queries[] = "SELECT * FROM v_last_updated_$table";

  $snapshots[] = "   INSERT INTO `{$table}_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `$table`;";

  $created_visa[] = "SELECT '$table' as table_name, id, lower(created_visa) as visa FROM $table";

  // Ref: http://stackoverflow.com/questions/1895110/row-number-in-mysql
  //  @rownum := @rownum + 1 AS rank
//   (SELECT @rownum := 0) r

  //   "(SELECT
//   '$table' table_name,
//   t.`updated_visa` AS visa,
//   t.`updated_date` last_updated
//   FROM
//   `$table` t
//   WHERE
//   t.`updated_date` = (SELECT MAX(`updated_date`) FROM `$table`)
//   LIMIT 1
//   )";
}

$freigaben = [];
$entFreigeben = [];
foreach ($workflow_tables as $table => $name) {
  $eingabe_abgeschlossen[] = "SELECT '$table' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM $table";

  $freigaben[] = "UPDATE $table SET freigabe_datum = NOW(), freigabe_visa='roland*', updated_date = NOW(), updated_visa= 'roland*';";
  $entFreigaben[] = "UPDATE $table SET freigabe_datum = NULL, freigabe_visa=NULL, updated_date = NOW(), updated_visa= '*';";
}

// verbose $master_query version, seems to be generating a too big result for PHP Generator for MySQL
// $master_query = "SELECT * FROM (
// SELECT *
// FROM (\n" . implode("\nUNION\n", $table_queries) . "\n) union_query
// ) complete
// ORDER BY complete.last_updated DESC;\n";

$master_query = "SELECT tn as table_name, n as name, ne as anzahl_eintraege, lv as last_visa, ld as last_updated, lid as last_updated_id
FROM (SELECT * FROM (\n" . implode("\nUNION ", $table_queries) . "\n) uq) c ORDER BY c.lid DESC\n";

$unordered_views = "CREATE OR REPLACE VIEW `v_last_updated_tables_unordered` AS\n"
. implode("\nUNION\n", $view_queries) . ";\n";

$master_view = "CREATE OR REPLACE VIEW `v_last_updated_tables` AS
SELECT * FROM `v_last_updated_tables_unordered`
ORDER BY last_updated DESC;\n";

$creator_query = "SELECT visa as label, COUNT(visa) as value, NULL as color  FROM (
SELECT visa
FROM (\n" . implode("\nUNION ALL\n", $created_visa) . "\n) union_query
) total_visa
GROUP BY label
ORDER BY value DESC;\n";

$worker_query = "SELECT visa as label, COUNT(visa) as value, NULL as color  FROM (
SELECT visa
FROM (\n" . implode("\nUNION ALL\n", $eingabe_abgeschlossen) . "\n) union_query
) total_visa
GROUP BY label
ORDER BY value DESC;\n";

$timestamp56 = [];
foreach ($tables as $table => $name) {
  $timestamp56[] = "ALTER TABLE $table
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';";
  $timestamp56[] = "ALTER TABLE {$table}_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';";
}

// --UNION
// --SELECT 'all' table_name, visa, last_updated
// --FROM ($union_query) union_query
// --WHERE
// --union_query.`last_updated` = (SELECT MAX(`updated_date`) FROM (union_query))
// --LIMIT 1

echo $master_query;
echo "\n-- -------------------------------------------------------------------\n";
echo "\n-- Last updated views\n\n";
echo implode("\n", $table_views) . "\n";
echo $unordered_views . "\n";
echo $master_view . "\n";

echo "\n-- -------------------------------------------------------------------\n";
echo "\n-- Snapshots";
echo "\n" . implode("\n\n", $snapshots) . "\n";
echo "\n-- -------------------------------------------------------------------\n";
echo "\n-- Creators";
echo "\n" . $creator_query . "\n";
echo "\n-- -------------------------------------------------------------------\n";
echo "\n-- Workers";
echo "\n" . $worker_query . "\n";
echo "\n-- -------------------------------------------------------------------\n";
echo "\n-- Alle freigeben";
echo "\n" . implode("\n", $freigaben) . "\n";
echo "\n-- -------------------------------------------------------------------\n";
echo "\n-- Alle ent-freigeben";
echo "\n" . implode("\n", $entFreigaben) . "\n";
echo "\n-- -------------------------------------------------------------------\n";
echo "\n-- MySQL 5.6 timestamp change";
echo "\n" . implode("\n", $timestamp56) . "\n";
echo "\n-- -------------------------------------------------------------------\n";

