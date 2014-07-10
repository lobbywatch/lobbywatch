<?php

// Run: /opt/lampp/bin/php -f sql_generator.php


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
$tables = array(
'branche' => 'Branche',
'interessenbindung' => 'Interessenbindung',
'interessengruppe' => 'Lobbygruppe',
'in_kommission' => 'In Kommission',
'kommission' => 'Kommission',
'mandat' => 'Mandat',
'organisation' => 'Organisation',
'organisation_anhang' => 'Organisationsanhang',
'organisation_beziehung' => 'Organisation Beziehung',
'organisation_jahr' => 'Organisationsjahr',
'parlamentarier' => 'Parlamentarier',
'parlamentarier_anhang' => 'Parlamentarieranhang',
'partei' => 'Partei',
'fraktion' => 'Fraktion',
'rat' => 'Rat',
'kanton' => 'Kanton',
'kanton_jahr' => 'Kantonjahr',
'settings' => 'Einstellungen',
'settings_category' => 'Einstellungskategorien',
'zutrittsberechtigung' => 'Zutrittsberechtigter',
'zutrittsberechtigung_anhang' => 'Zutrittsberechtigunganhang',
);


// $table_query = "(SELECT
// '$table' tabe_name,
// t.`updated_visa` AS branche_updated_visa,
// MAX(t.`updated_date` )
// FROM
// `$table` t)";

$table_queries = array();
$table_views = array();
$view_queries = array();
$snapshots = array();
$worker = array();
foreach ($tables as $table => $name) {
  //$table_queries2[] = preg_replace('\$table', $table, $table_query);
//   $table_queries[] = "(SELECT
//   '$table' table_name,
//   GROUP_CONCAT(t.`updated_visa` SEPARATOR ', ') AS visa,
//   MAX(t.`updated_date`) last_updated
//   FROM
//   `$table` t
//   GROUP BY t.`updated_date`
//   )";
  $table_queries[] =
  "(SELECT
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
  )";
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

  $snapshots[] = "   INSERT INTO `${table}_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `$table`;";

  $worker[] = "SELECT '$table' as table_name, id, lower(created_visa) as created_visa FROM $table";

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

$master_query = "SELECT * FROM (
SELECT *
FROM (\n" . implode("\nUNION\n", $table_queries) . "\n) union_query
) complete
ORDER BY complete.last_updated DESC;\n";

$unordered_views = "CREATE OR REPLACE VIEW `v_last_updated_tables_unordered` AS\n"
. implode("\nUNION\n", $view_queries) . ";\n";

$master_view = "CREATE OR REPLACE VIEW `v_last_updated_tables` AS
SELECT * FROM `v_last_updated_tables_unordered`
ORDER BY last_updated DESC;\n";

$worker_query = "SELECT created_visa as label, COUNT(created_visa) as value, NULL as color  FROM (
SELECT created_visa
FROM (\n" . implode("\nUNION ALL\n", $worker) . "\n) union_query
) total_created
GROUP BY label
ORDER BY value DESC;\n";

// --UNION
// --SELECT 'all' table_name, visa, last_updated
// --FROM ($union_query) union_query
// --WHERE
// --union_query.`last_updated` = (SELECT MAX(`updated_date`) FROM (union_query))
// --LIMIT 1

echo $master_query;
echo "\n---------------------------------------------------------------------\n";
echo "\n-- Last updated views\n\n";
echo implode("\n", $table_views) . "\n";
echo $unordered_views . "\n";
echo $master_view . "\n";

echo "\n---------------------------------------------------------------------\n";
echo "\n" . implode("\n\n", $snapshots) . "\n";
echo "\n---------------------------------------------------------------------\n";
echo "\n" . $worker_query . "\n";

