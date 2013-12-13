<?php

// Query form database with
/*
 * SELECT
 T ABLES.`TABLE_NAME` AS *TABLES_TABLE_NAME
 FROM
 `COLUMNS` COLUMNS INNER JOIN `TABLES` TABLES ON COLUMNS.`TABLE_NAME` = TABLES.`TABLE_NAME`
 AND TABLES.`TABLE_SCHEMA` = COLUMNS.`TABLE_SCHEMA`
 WHERE
 TABLES.`TABLE_SCHEMA` = 'lobbycontrol'
 AND COLUMNS.`COLUMN_NAME` = 'updated_date'
 AND TABLES.`TABLE_TYPE` = 'BASE TABLE'
 ORDER BY
 TABLES.`TABLE_NAME` ASC
 */
$tables = array('branche',
'interessenbindung',
'interessengruppe',
'in_kommission',
'kommission',
'mandat',
'organisation',
'organisation_beziehung',
'parlamentarier',
'parlamentarier_anhang',
'partei',
'zugangsberechtigung',);


// $table_query = "(SELECT
// '$table' tabe_name,
// t.`updated_visa` AS branche_updated_visa,
// MAX(t.`updated_date` )
// FROM
// `$table` t)";

$table_queries = array();
foreach ($tables as $table) {
  //$table_queries2[] = preg_replace('\$table', $table, $table_query);
//   $table_queries[] = "(SELECT
//   '$table' table_name,
//   GROUP_CONCAT(t.`updated_visa` SEPARATOR ', ') AS visa,
//   MAX(t.`updated_date`) last_updated
//   FROM
//   `$table` t
//   GROUP BY t.`updated_date`
//   )";
  $table_queries[] = "(SELECT
  '$table' table_name,
  t.`updated_visa` AS visa,
  t.`updated_date` last_updated
  FROM
  `$table` t
  WHERE
  t.`updated_date` = (SELECT MAX(`updated_date`) FROM `$table`)
  LIMIT 1
  )";
}

$union_query = implode("\nUNION\n", $table_queries) . "\n";

$master_query = "SELECT * FROM (
SELECT *
FROM ($union_query) union_query
) complete
ORDER BY complete.last_updated DESC\n";

// --UNION
// --SELECT 'all' table_name, visa, last_updated
// --FROM ($union_query) union_query
// --WHERE
// --union_query.`last_updated` = (SELECT MAX(`updated_date`) FROM (union_query))
// --LIMIT 1

echo $master_query;
