<?php
// Run: /opt/lampp/bin/php -f ws_uid_fetcher.php -- --uid 107810911 --ssl -t
// Run: php -f ws_uid_fetcher.php -- -a --ssl -v1 -n20 -s

/*
# ./deploy.sh -b -B -p
# ./run_local_db_script.sh lobbywatchtest prod_bak/`cat prod_bak/last_dbdump_data.txt`

./db_prod_to_local.sh lobbywatchtest
export SYNC_FILE=sql/ws_uid_sync_`date +"%Y%m%d"`.sql; php -f ws_uid_fetcher.php -- -a --ssl -v1 -s | tee $SYNC_FILE; less $SYNC_FILE
./run_local_db_script.sh lobbywatchtest $SYNC_FILE
./deploy.sh -r -s $SYNC_FILE
./deploy.sh -p -r -s $SYNC_FILE
*/


require_once dirname(__FILE__) . '/public_html/settings/settings.php';
require_once dirname(__FILE__) . '/public_html/common/utils.php';
// Change to forms root in order satisfy relative imports
$oldDir = getcwd();
chdir(dirname(__FILE__) . '/public_html/bearbeitung');
require_once dirname(__FILE__) . '/public_html/bearbeitung/database_engine/mysql_engine.php';
chdir($oldDir);

global $script;
global $context;
global $show_sql;
global $db;
global $verbose;
global $download_images;
global $convert_images;
global $lobbywatch_is_forms;
global $intern_fields;

$show_sql = false;

$script = array();
$script[] = "-- SQL script sql_migration " . date("d.m.Y");

$errors = array();
$verbose = 0;

$intern_fields = ['notizen', 'freigabe_visa', 'created_date', 'created_date_unix', 'created_visa', 'updated_date', 'updated_date_unix', 'updated_visa', 'autorisiert_datum',  'autorisiert_datum_unix', 'autorisierung_verschickt_visa', 'autorisierung_verschickt_datum', 'eingabe_abgeschlossen_datum', 'kontrolliert_datum', 'autorisierung_verschickt_datum_unix', 'eingabe_abgeschlossen_datum_unix', 'kontrolliert_datum_unix', 'autorisiert_visa', 'freigabe_visa', 'eingabe_abgeschlossen_visa', 'kontrolliert_visa', 'symbol_abs', 'photo', 'ALT_kommission', 'ALT_parlam_verbindung'];

main();

function main() {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $db_connection;
  global $today;
  global $transaction_date;
  global $errors;
  global $verbose;
  global $env;

  print("-- Default $env: {$db_connection['database']}\n");

//     var_dump($argc); //number of arguments passed
//     var_dump($argv); //the arguments passed
  // :  -> mandatory
  // :: -> optional parameter
  $options = getopt('hsv::En::jJu::c::g::s::p:f::', ['help','utf8mb4::', 'user-prefix:', 'db:', 'sep:', 'eol:', 'qe:']);

//    var_dump($options);

  if (isset($options['v'])) {
    if ($options['v']) {
      $verbose = $options['v'];
    } else {
      $verbose = 1;
    }
     print("-- Verbose level: $verbose\n");
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

  if (isset($options['sep'])) {
    $sep = $options['sep'];
  } else {
    $sep = "\t";
  }

  if (isset($options['eol'])) {
    $eol = $options['eol'];
  } else {
    $eol = "\n";
  }

  if (isset($options['qe'])) {
    $qe = $options['qe'];
  } elseif (isset($options['g'])) {
    $qe = '"';
  } elseif (isset($options['c'])) {
    $qe = '\\';
  } elseif (isset($options['s'])) {
    $qe = '\\';
  } else {
    $qe = '\\';
  }

  if (isset($options['p'])) {
    $path = $options['p'];
    print("Path: $path\n");
  } else {
    $path = 'csv';
  }

  get_PDO_lobbywatch_DB_connection($db_name, $user_prefix);
  print("-- $env: {$db_connection['database']}\n");

  if (isset($options['E'])) {
    print("Execute!\n");
    $execute = true;
  } else {
    $execute = false;
  }

  if (isset($options['h']) || isset($options['help'])) {
    print("ws.parlament.ch Fetcher for Lobbywatch.ch.
Parameters:
-j                  Migrate parlamentarier.parlament_intressenbindungen to JSON
-J                  Migrate parlamentarier_log.parlament_intressenbindungen to JSON
-g[=SCHEMA]         Export csv for Neo4j graph DB to PATH (default SCHEMA: lobbywatchtest)
-c[=SCHEMA]         Export plain csv to PATH (default SCHEMA: lobbywatchtest)
-s[=SCHEMA]         Export SQL to PATH (default SCHEMA: lobbywatchtest)
-f[=FILTER]         Filter csv fields, -f filter everything, -f=hist, -f=intern, -f=hist,intern (default: filter nothing)
-p=PATH             Export path (default: csv/)
--sep=SEP           Separator char for columns (default: \\t)
--eol=EOL           End of line (default: \\n)
--qe=QE             Quote escape (default: \")
-u[=SCHEMA]         Migrate user visa (default SCHEMA: lobbywatchtest)
--utf8mb4[=SCHEMA]  Migrate utf8mb4 (default SCHEMA: lobbywatchtest)
-n[=NUMBER]         Limit number of records
-E                  Execute script
--user-prefix=USER  Prefix for db user in settings.php (default: reader_)
--db=DB             DB name for settings.php
-v[=LEVEL]         Verbose, optional level, 1 = default
-h, --help          This help
");
  exit(0);
  }

  if (isset($options['j'])) {
    migrate_parlament_interessenbindungen_to_Json('parlamentarier', 'id', $records_limit);
  }

  if (isset($options['J'])) {
    migrate_parlament_interessenbindungen_to_Json('parlamentarier_log', 'log_id', $records_limit);
  }

  $filter_hist = false;
  $filter_intern_fields = false;
  if (isset($options['f'])) {
    $f_options = explode(',', $options['f']);
    if (in_array('hist', $f_options)) {
      print("Filter: hist\n");
      $filter_hist = true;
    }
    if (in_array('intern', $f_options)) {
      $filter_intern_fields = true;
      print("Filter: intern\n");
    }

    if (empty($options['f'])) {
      $filter_hist = true;
      $filter_intern_fields = true;
      print("Filter: hist + intern\n");
    }
  }

  if (isset($options['u'])) {
    if ($options['u']) {
      $schema = $options['u'];
    } else {
      $schema = 'lobbywatchtest';
    }
    print("-- Schema: $schema\n");

    migrate_unused_user_visa($schema, $records_limit);
  }

  if (isset($options['g'])) {
    if ($options['g']) {
      $schema = $options['g'];
    } else {
      $schema = 'lobbywatchtest';
    }
    print("-- Schema: $schema\n");

    export_csv_for_neo4j($schema, $path, $filter_hist, $filter_intern_fields, $sep, $eol, $qe, $records_limit);
  }

  if (isset($options['c'])) {
    if ($options['c']) {
      $schema = $options['c'];
    } else {
      $schema = 'lobbywatchtest';
    }
    print("-- Schema: $schema\n");

    export_csv_plain($schema, $path = 'csv', $filter_hist, $filter_intern_fields, $sep, $eol, $qe, $records_limit);
  }

  if (isset($options['s'])) {
    if ($options['s']) {
      $schema = $options['s'];
    } else {
      $schema = 'lobbywatchtest';
    }
    print("-- Schema: $schema\n");

    export_sql($schema, $path = 'csv', $filter_hist, $filter_intern_fields, $sep, $eol, $qe, $records_limit);
  }

  if (isset($options['utf8mb4'])) {
    if ($options['utf8mb4']) {
      $schema = $options['utf8mb4'];
    } else {
      $schema = 'lobbywatchtest';
    }
    print("-- Schema: $schema\n");

    migrate_utf8mb4($schema, $execute, $records_limit);
  }

  if (count($errors) > 0) {
    echo "\nErrors:\n", implode("\n", $errors), "\n";
    exit(1);
  }

}

function migrate_parlament_interessenbindungen_to_Json($table_name, $id_field, $records_limit = false) {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $today;
  global $sql_today;
  global $transaction_date;
  global $sql_transaction_date;
  global $verbose;

  $script[] = $comment = "\n-- Migrate parlament_interessenbindungen to parlament_interessenbindungen_json $transaction_date";

  $sql = "SELECT $id_field, nachname, parlament_interessenbindungen FROM $table_name ORDER BY $id_field;";
  $stmt = $db->prepare($sql);

  $stmt->execute ( array() );
//   $organisation_list = $stmt->fetchAll(PDO::FETCH_CLASS);
//   $organisation_list = $stmt->fetchAll(PDO::FETCH_CLASS);

//   var_dump($parlamentarier_list_db);

  echo "\n/*\nMigrate parlament_interessenbindungen to parlament_interessenbindungen_json $transaction_date\n";
  print("rows = " . $stmt->rowCount() . "\n");

  $rechtsformen = [];
  $gremien = [];
  $funktionen = [];
  for ($i = 0; $row = $stmt->fetch(); $i++) {
    if ($records_limit && $i > $records_limit) {
      break;
    }

    $id = $row[$id_field];

    print("-- $i, ${row['nachname']}, $id_field=$id\n");

    $db_ib = $row['parlament_interessenbindungen'];
    $db_ib_clean = str_replace(array("\r\n","\n","\r"), "\n", $db_ib, $clean_count);

//     if ($clean_count > 0) {
//       print(json_encode($db_ib, JSON_UNESCAPED_UNICODE) . "\n");
//       $script[] = "UPDATE $table_name SET parlament_interessenbindungen='$db_ib_clean', updated_date=updated_date WHERE id=$id; -- Clean \\r\n";
//     }
    $raw = $db_ib_clean;
    $raw = preg_replace('%<table.*<tbody>%s', '', $raw);
    $raw = preg_replace('%</tbody>.*</table>%s', '', $raw);
    $raw = preg_replace('%(<tr><td>|</td></tr>)%s', '', $raw);
    $objects = [];
    foreach(explode("\n", $raw) as $line) {
      if (trim($line) == '') continue;
      $vals = explode('</td><td>', $line);

      $objects[] = (object) [
        'Name' => $vals[0],
        'Rechtsform' => $vals[1],
        'Gremium' => $vals[2],
        'Funktion' => $vals[3]
      ];

      @$rechtsformen[$vals[1]]++;
      @$gremien[$vals[2]]++;
      @$funktionen[$vals[3]]++;
    }
    $json = escape_string(json_encode($objects, JSON_UNESCAPED_UNICODE));

    if (json_last_error() != 0) {
      print("ERROR\n");
      print(json_last_error_msg() . ', code: ' . json_last_error() . "\n");
      print_r($objects);
      exit(1);
    }

    //print_r($objects);
    if (count($objects) > 0) {
      $script[] = "UPDATE $table_name SET parlament_interessenbindungen_json='$json', updated_date=updated_date WHERE $id_field=$id;\n";
    }

    print("\n");
  }
  ksort($rechtsformen);
  ksort($gremien);
  ksort($funktionen);

  print("*/\n\n");

  $script[] = "\nSET @disable_table_logging = NULL;";
  $script[] = "SET @disable_triggers = NULL;";

  print("\n" . implode("\n", $script));
  $script[] = "\nSET @disable_table_logging = NULL;";
  $script[] = "SET @disable_triggers = NULL;";
  print("\n\n");

  print("/*\n");
  print("Rechtsformen: ");
  print(implode(", ", array_keys($rechtsformen)) . "\n");
  print_r($rechtsformen);
  print("Gremien: ");
  print(implode(", ", array_keys($gremien)) . "\n");
  print_r($gremien);
  print("Funktionen: ");
  print(implode(", ", array_keys($funktionen)) . "\n");
  print_r($funktionen);


  print("*/\n\n");
}

function migrate_unused_user_visa($table_schema, $records_limit = false) {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $today;
  global $sql_today;
  global $transaction_date;
  global $sql_transaction_date;
  global $verbose;

  $replace_user = "intro2";

  $rename_users = ['Dimitri Zu' => 'dimitri'];

  $script[] = $comment = "\n-- Migrate unused user visa $transaction_date";

  $sql = "SELECT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME like '%_visa' AND table_schema='$table_schema' AND table_catalog='def' AND TABLE_NAME NOT LIKE 'v_%' AND TABLE_NAME NOT LIKE 'mv_%' order by TABLE_NAME, COLUMN_NAME;";
  $stmt = $db->prepare($sql);
  $stmt->execute ( array() );
  $cols = $stmt->fetchAll();

  $sql = "SELECT id, name , nachname, vorname, last_login, last_access, email FROM $table_schema.user order by id;";
  $stmt = $db->prepare($sql);
  $stmt->execute ( array() );
  $users = $stmt->fetchAll();

  $sql = "SELECT user_id, count(*) FROM $table_schema.user_permission group by user_id;";
  $stmt = $db->prepare($sql);
  $stmt->execute ( array() );
  $user_permissions = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
//   print_r($user_permissions);

  echo "\n/*\nMigrate unused user visa  $transaction_date\n";
  print("\n\n");

  $script[] = "\nSET @disable_table_logging = 1;";
  $script[] = "SET @disable_triggers = 1;";

  $count_delete = 0;
  $count_replace = 0;
  $count_rename = 0;
  $i = 0;
  foreach ($users as $row_user) {
    if ($records_limit && $i++ > $records_limit) {
      break;
    }
    $id = $row_user['id'];
    $name = $row_user['name'];

    $clean_script = [];

    print("User $id, $name, ${row_user['nachname']} ${row_user['vorname']}, ${row_user['last_login']}, ${row_user['last_access']}, ${row_user['email']}\n");

    if (array_key_exists($name, $rename_users)) {
      $new_name = $rename_users[$name];
      print("    Rename user from $name to $new_name\n");
    } else {
      $new_name = $replace_user;
    }

    $count_permissions = isset($user_permissions[$id]) ? $user_permissions[$id] : 0;
    print("    # permissions: $count_permissions\n");

    $user_total = 0;
    $user_total_non_log = 0;
    $user_total_recherche = 0;
    foreach ($cols as $row) {
      $table = $row['TABLE_NAME'];
      $col = $row['COLUMN_NAME'];

      $sql = "SELECT count(*) FROM $table_schema.$table WHERE $col='$name';";
//       print("$sql\n");
      $count = $db->query($sql)->fetch()[0];
      $user_total += $count;
      if (! preg_match("/.*_log$/", $table)) {
        $user_total_non_log += $count;
      }
      if (preg_match("/^(interessenbindung|mandat)$/", $table)) {
        $user_total_recherche += $count;
      }
      if ($count > 0) {
        print("    $table.$col: $count\n");
        $clean_script[] = "UPDATE $table SET $col='$new_name', updated_date=updated_date WHERE $col='$name';";
      }
    }
    print("    # active edits: $user_total_non_log\n");
    print("    # recherche: $user_total_recherche\n");

    if (in_array($id, [1, 7, 46, 65])) {
      print("    *** Keep\n");
    } elseif (array_key_exists($name, $rename_users)) {
      $script[] = "\n-- Rename user $id, '$name' to '$new_name'";
      $script = array_merge($script, $clean_script);
      $script[] = "-- Rename user $id, '$name' to '$new_name'\nUPDATE user SET name='$new_name', updated_visa='roland' WHERE ID=$id;";
      $count_rename++;
    } elseif ($user_total == 0) {
      $script[] = "-- Delete $id, $name\nDELETE FROM user_permission WHERE USER_ID=$id; DELETE FROM user WHERE ID=$id;";
      $count_delete++;
    } elseif ($user_total_recherche < 10 && $user_total_non_log < 50) {
      $script[] = "\n-- Replace intro user $id, $name having only $user_total_recherche recherche";
      $script = array_merge($script, $clean_script);
      $script[] = "-- Delete $id, $name\nDELETE FROM user_permission WHERE USER_ID=$id; DELETE FROM user WHERE ID=$id;";
      $count_replace++;
    }
    print("\n");
  }

  $script[] = "\nSET @disable_table_logging = NULL;";
  $script[] = "SET @disable_triggers = NULL;";

  print("*/\n\n");
  print("-- User count: " . count($users) . "\n");
  print("-- Delete count: $count_delete\n");
  print("-- Replace count: $count_replace\n");
  print("-- Rename count: $count_rename\n");
  print("\n" . implode("\n", $script));
  print("\n\n");
}

function migrate_utf8mb4($table_schema, $execute = false, $records_limit = false) {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $today;
  global $sql_today;
  global $transaction_date;
  global $sql_transaction_date;
  global $verbose;

  $count_converted_tables = 0;
  $count_converted_fields = 0;
  $charset = 'utf8mb4';
  $collation = 'utf8mb4_unicode_ci';

  $sql = "SHOW FULL TABLES FROM $table_schema WHERE table_type='base table';";
  $stmt = $db->prepare($sql);
  $stmt->execute([]);
  $tables = $stmt->fetchAll();
  $stmt->closeCursor();
  // print_r($tables);

  echo "\n-- Migrate utf8mb4  $transaction_date\n";
  print("\n\n");

  $line = readline("-- Migrate utf8mb4 on $table_schema? (y/n) ");
  if (!($line == 'y' || $line == 'Y')) {
    print("Aborted\n");
    exit(1);
  }

  $sql = "ALTER DATABASE $table_schema CHARACTER SET = $charset COLLATE = $collation;";
  print("SQL: $sql\n\n");
  $num = $db->exec($sql);
  if ($num === false) {
    print_r($db->errorInfo());
    print("\nERROR\n");
    exit(1);
  }

  $i = 0;
  foreach ($tables as $table_row) {
    $script = [];
    if ($records_limit && $i++ > $records_limit) {
      break;
    }
    $count_converted_tables++;
    $table_name = $table_row[0];

    // print("$table_name\n");

    // $script[] = "\n\! echo 'Convert $table_name'";
    print("-- Convert $table_name\n");
    $script[] = "ALTER TABLE $table_schema.$table_name CONVERT TO CHARACTER SET $charset COLLATE $collation;";

    $sql = "SHOW FULL FIELDS FROM $table_schema.$table_name;";
    $stmt = $db->prepare($sql);
    $stmt->execute([]);
    $fields = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach ($fields as $row) {
      if (strpos($row->Collation, '_bin') !== FALSE) {
        $count_converted_fields++;

        $field_collation = 'utf8mb4_bin';
        // print_r($row);
        // print("\n");

        $default = '';
        if ($row->Default !== NULL) {
          $default = 'DEFAULT ' . ($row->Default == "CURRENT_TIMESTAMP" ? "CURRENT_TIMESTAMP" : "'$row->Default'");
        } elseif ($row->Null == 'YES' && $row->Key == '') {
          if ($row->Type == 'timestamp') {
            $default = ' NULL';
          }
          $default .= ' DEFAULT NULL';
        }

        // $script[] = "\n\! echo 'Convert field $table_name.$row->Field $row->Type $field_collation'";
        print("-- Convert field $table_name.$row->Field $row->Type $field_collation\n");
        $script[] = "ALTER TABLE $table_schema.$table_name MODIFY $row->Field $row->Type CHARACTER SET $charset COLLATE $field_collation " .
                ($row->Null == "YES" ? "" : "NOT NULL") .
                (!empty($default) ? " $default" : '') .
                (!empty($row->Extra) ? " $row->Extra" : '') .
                (!empty($row->Comment) ? " COMMENT '$row->Comment'" : '')
                . ";";
      } elseif ($row->Collation == 'utf8_general_ci') {
      } elseif ($row->Collation == 'utf8mb4_unicode_ci') {
      } elseif ($row->Collation === null) {
      } else {
        print("Unknown collation $row->Collation for $row->Field in $table_schema.$table_name");
        exit(1);
      }
    }
    $script[] = "OPTIMIZE TABLE $table_schema.$table_name;";
    execute($db, $script, $execute);

    print("\n");
  }

  print("-- Converted tables: $count_converted_tables\n");
  print("-- Converted fields: $count_converted_fields\n");
//   print("\n" . implode("\n", $script));
  print("\n\n");
}

function execute($db, $script, $execute = false) {
  foreach ($script as $sql) {
    print("SQL: $sql\n");
    if ($execute && !preg_match('/^-- /', $sql)) {
      $stmt = $db->query($sql);
      if ($stmt === false) {
        print_r($db->errorInfo());
        print("\nERROR\n");
        exit(1);
      }
      // Fetch all data to avoid open cursor
      $result = $stmt->fetchAll();
      // print_r($result);
    }
  }
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
function export_csv_for_neo4j($table_schema, $path, $filter_hist = true, $filter_intern_fields = true, $sep = "\t", $eol = "\n", $qe = '"', $records_limit = false) {
    global $script;
    global $context;
    global $show_sql;
    global $db;
    global $today;
    global $sql_today;
    global $transaction_date;
    global $sql_transaction_date;
    global $verbose;

    global $intern_fields;

    // :ID(partei_id) :LABEL (separated by ;) :IGNORE
    // --nodes[:Label1:Label2]=<"headerfile,file1,file2,…​">
    // --id-type=<STRING|INTEGER|ACTUAL>
    $nodes = [
        'partei' => ['table' => 'partei', 'view' => 'v_partei', 'name' => 'Partei', 'id' => 'id', 'hist_field' => null, 'remove_cols' => []],
        'branche' => ['table' => 'branche', 'view' => 'v_branche_simple', 'name' => 'Branche', 'id' => 'id', 'hist_field' => null, 'remove_cols' => ['farbcode', 'symbol_abs', 'symbol_rel', 'symbol_klein_rel', 'symbol_dateiname_wo_ext', 'symbol_dateierweiterung', 'symbol_dateiname', 'symbol_mime_type']],
        'interessengruppe' => ['table' => 'interessengruppe', 'view' => 'v_interessengruppe_simple', 'name' => 'Lobbygruppe', 'id' => 'id', 'hist_field' => null, 'remove_cols' => []],
        'interessenraum' => ['table' => 'interessenraum', 'view' => 'v_interessenraum', 'name' => 'Interessenraum', 'id' => 'id', 'hist_field' => null, 'remove_cols' => []],
        'kommission' => ['table' => 'kommission', 'view' => 'v_kommission', 'name' => 'Kommission', 'id' => 'id', 'hist_field' => null, 'remove_cols' => []],
        'organisation' => ['table' => 'organisation', 'view' => 'v_organisation_simple', 'name' => 'Organisation', 'id' => 'id', 'hist_field' => null, 'remove_cols' => []],
        'organisation_jahr' => ['table' => 'organisation_jahr', 'view' => 'v_organisation_jahr', 'name' => 'Organisationsjahr', 'id' => 'id', 'hist_field' => null, 'remove_cols' => []],
        'parlamentarier' => ['table' => 'parlamentarier', 'view' => 'v_parlamentarier_simple', 'name' => 'Parlamentarier', 'id' => 'id', 'hist_field' => 'im_rat_bis', 'remove_cols' => []],
        'fraktion' => ['table' => 'fraktion', 'view' => 'v_fraktion', 'name' => 'Fraktion', 'id' => 'id', 'hist_field' => null, 'remove_cols' => []],
        'rat' => ['table' => 'rat', 'view' => 'v_rat', 'name' => 'Rat', 'id' => 'id', 'hist_field' => null, 'remove_cols' => []],
        'kanton' => ['table' => 'kanton', 'view' => 'v_kanton_simple', 'name' => 'Kanton', 'id' => 'id', 'hist_field' => null, 'remove_cols' => []],
        'kanton_jahr' => ['table' => 'kanton_jahr', 'view' => 'v_kanton_jahr', 'name' => 'Kantonjahr', 'id' => 'id', 'hist_field' => null, 'remove_cols' => []],
        'person' => ['table' => 'person', 'view' => 'v_person_simple', 'name' => 'Person', 'id' => 'id', 'hist_field' => null, 'remove_cols' => []],
    ];

    // :START_ID(parlamentarier_id) :END_ID(partei_id) :TYPE :IGNORE
    // --relationships[:RELATIONSHIP_TYPE]=<"headerfile,file1,file2,…​">
    $interessenbindung_join_hist_filter = "JOIN $table_schema.parlamentarier ON interessenbindung.parlamentarier_id = parlamentarier.id AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())";
    $mandat_join_hist_filter = "JOIN $table_schema.person ON mandat.person_id = person.id JOIN $table_schema.zutrittsberechtigung ON zutrittsberechtigung.person_id = person.id AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW()) JOIN $table_schema.parlamentarier ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())";
    $relationships = [
        'interessenbindung' => ['table' => 'interessenbindung', 'name' => 'HAT_INTERESSENBINDUNG_MIT', 'id' => 'id', 'start_id' => 'parlamentarier_id', 'end_id' => 'organisation_id', 'hist_field' => 'bis', 'remove_cols' => [], 'hist_filter_join' => $interessenbindung_join_hist_filter],
        'interessenbindung_jahr' => ['table' => 'interessenbindung_jahr', 'join' => "JOIN $table_schema.interessenbindung ON interessenbindung_jahr.interessenbindung_id = interessenbindung.id", 'name' => 'VERGUETED', 'id' => 'id', 'start_id' => 'organisation_id', 'end_id' => 'parlamentarier_id', 'additional_join_cols' => ['interessenbindung.parlamentarier_id', 'interessenbindung.organisation_id'], 'additional_join_csv_header_cols' => ['parlamentarier_id:END_ID(parlamentarier_id)', 'organisation_id:START_ID(organisation_id)'], 'hist_field' => null, 'remove_cols' => array_map(function($val) { return "interessenbindung.$val"; }, array_merge($intern_fields, ['id', 'beschreibung', 'quelle_url_gueltig', 'quelle_url', 'quelle'])), 'hist_filter_join' => $interessenbindung_join_hist_filter],
        'in_kommission' => ['table' => 'in_kommission', 'name' => 'IST_IN_KOMMISSION', 'id' => 'id', 'start_id' => 'parlamentarier_id', 'end_id' => 'kommission_id', 'hist_field' => 'bis', 'remove_cols' => [], 'hist_filter_join' => "JOIN $table_schema.parlamentarier ON in_kommission.parlamentarier_id = parlamentarier.id AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())"],
        'mandat' => ['table' => 'mandat', 'name' => 'HAT_MANDAT', 'id' => 'id', 'start_id' => 'person_id', 'end_id' => 'organisation_id', 'hist_field' => 'bis', 'remove_cols' => [], 'hist_filter_join' => $mandat_join_hist_filter],
        'mandat_jahr' => ['table' => 'mandat_jahr', 'join' => "JOIN $table_schema.mandat ON mandat_jahr.mandat_id = mandat.id", 'name' => 'VERGUETED', 'id' => 'id', 'start_id' => 'organisation_id', 'end_id' => 'person_id', 'additional_join_cols' => ['mandat.person_id', 'mandat.organisation_id'], 'additional_join_csv_header_cols' => ['person_id:END_ID(person_id)', 'organisation_id:START_ID(organisation_id)'], 'hist_field' => null, 'remove_cols' => array_map(function($val) { return "mandat.$val"; }, array_merge($intern_fields, ['id', 'beschreibung', 'quelle_url_gueltig', 'quelle_url', 'quelle'])), 'hist_filter_join' => $mandat_join_hist_filter],
        'organisation_beziehung' => ['table' => 'organisation_beziehung', 'name' => 'HAT_BEZIEHUNG', 'type_col' => 'art', 'id' => 'id', 'start_id' => 'organisation_id', 'end_id' => 'ziel_organisation_id', 'end_id_space' => 'organisation_id', 'hist_field' => 'bis', 'remove_cols' => []],
        'zutrittsberechtigung' => ['table' => 'zutrittsberechtigung', 'name' => 'HAT_ZUTRITTSBERECHTIGTER', 'id' => 'id', 'start_id' => 'parlamentarier_id', 'end_id' => 'person_id', 'hist_field' => 'bis', 'remove_cols' => [], 'hist_filter_join' => "JOIN $table_schema.parlamentarier ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())"],
        'parlamentarier_partei' => ['table' => 'parlamentarier', 'name' => 'IST_PARTEIMITGLIED_VON', 'id' => 'id', 'start_id' => 'id', 'end_id' => 'partei_id', 'hist_field' => 'im_rat_bis', 'select_cols' => ['freigabe_datum', 'freigabe_visa', 'created_date', 'created_visa', 'updated_date', 'updated_visa'], 'remove_cols' => []],
        'parlamentarier_fraktion' => ['table' => 'parlamentarier', 'name' => 'IST_FRAKTIONMITGLIED_VON', 'id' => 'id', 'start_id' => 'id', 'end_id' => 'fraktion_id', 'hist_field' => 'im_rat_bis', 'select_cols' => ['freigabe_datum', 'freigabe_visa', 'created_date', 'created_visa', 'updated_date', 'updated_visa'], 'remove_cols' => []],
        'parlamentarier_rat' => ['table' => 'parlamentarier', 'name' => 'IST_IM_RAT', 'id' => 'id', 'start_id' => 'id', 'end_id' => 'rat_id', 'hist_field' => 'im_rat_bis', 'select_cols' => ['freigabe_datum', 'freigabe_visa', 'created_date', 'created_visa', 'updated_date', 'updated_visa'], 'remove_cols' => []],
        'parlamentarier_kanton' => ['table' => 'parlamentarier', 'name' => 'WOHNT_IM_KANTON', 'id' => 'id', 'start_id' => 'id', 'end_id' => 'kanton_id', 'hist_field' => 'im_rat_bis', 'select_cols' => ['freigabe_datum', 'freigabe_visa', 'created_date', 'created_visa', 'updated_date', 'updated_visa'], 'remove_cols' => []],
        'organisation_interessengruppe' => ['table' => 'organisation', 'name' => 'GEHOERT_ZU', 'id' => 'id', 'start_id' => 'id', 'end_id' => 'interessengruppe_id', 'end_id_space' => 'interessengruppe_id', 'hist_field' => null, 'select_cols' => ['freigabe_datum', 'freigabe_visa', 'created_date', 'created_visa', 'updated_date', 'updated_visa'], 'remove_cols' => []],
        'organisation_interessengruppe2' => ['table' => 'organisation', 'name' => 'GEHOERT_ZU', 'id' => 'id', 'start_id' => 'id', 'end_id' => 'interessengruppe2_id', 'end_id_space' => 'interessengruppe_id', 'hist_field' => null, 'select_cols' => ['freigabe_datum', 'freigabe_visa', 'created_date', 'created_visa', 'updated_date', 'updated_visa'], 'remove_cols' => []],
        'organisation_interessengruppe3' => ['table' => 'organisation', 'name' => 'GEHOERT_ZU', 'id' => 'id', 'start_id' => 'id', 'end_id' => 'interessengruppe3_id', 'end_id_space' => 'interessengruppe_id', 'hist_field' => null, 'select_cols' => ['freigabe_datum', 'freigabe_visa', 'created_date', 'created_visa', 'updated_date', 'updated_visa'], 'remove_cols' => []],
        'organisation_interessenraum' => ['table' => 'organisation', 'name' => 'HAT_INTERESSENRAUM', 'id' => 'id', 'start_id' => 'id', 'end_id' => 'interessenraum_id', 'hist_field' => null, 'select_cols' => ['freigabe_datum', 'freigabe_visa', 'created_date', 'created_visa', 'updated_date', 'updated_visa'], 'remove_cols' => []],
        'organisation_jahr' => ['table' => 'organisation_jahr', 'name' => 'ORGANISATION_HAT_IM_JAHR', 'id' => 'id', 'start_id' => 'organisation_id', 'end_id' => 'id', 'end_id_space' => 'organisation_jahr_id', 'hist_field' => null, 'select_cols' => ['freigabe_datum', 'freigabe_visa', 'created_date', 'created_visa', 'updated_date', 'updated_visa'], 'remove_cols' => []],
        'kanton_jahr' => ['table' => 'kanton_jahr', 'name' => 'KANTON_HAT_IM_JAHR', 'id' => 'id', 'start_id' => 'kanton_id', 'end_id' => 'id', 'end_id_space' => 'kanton_jahr_id', 'hist_field' => null, 'select_cols' => ['freigabe_datum', 'freigabe_visa', 'created_date', 'created_visa', 'updated_date', 'updated_visa'], 'remove_cols' => []],
        'interessengruppe_branche' => ['table' => 'interessengruppe', 'name' => 'IST_IN_BRANCHE', 'id' => 'id', 'start_id' => 'id', 'end_id' => 'branche_id', 'hist_field' => null, 'select_cols' => ['freigabe_datum', 'freigabe_visa', 'created_date', 'created_visa', 'updated_date', 'updated_visa'], 'remove_cols' => []],
        'branche_kommission' => ['table' => 'branche', 'name' => 'HAT_ZUSTAENDIGE_KOMMISSION', 'id' => 'id', 'start_id' => 'id', 'end_id' => 'kommission_id', 'end_id_space' => 'kommission_id', 'hist_field' => null, 'select_cols' => ['freigabe_datum', 'freigabe_visa', 'created_date', 'created_visa', 'updated_date', 'updated_visa'], 'remove_cols' => []],
        'branche_kommission2' => ['table' => 'branche', 'name' => 'HAT_ZUSTAENDIGE_KOMMISSION', 'id' => 'id', 'start_id' => 'id', 'end_id' => 'kommission2_id', 'end_id_space' => 'kommission_id', 'hist_field' => null, 'select_cols' => ['freigabe_datum', 'freigabe_visa', 'created_date', 'created_visa', 'updated_date', 'updated_visa'], 'remove_cols' => []],
    ];

    $export = [
        'node' => $nodes,
        'relationship' => $relationships,
    ];

    // <name>:<field_type>
    // int, long, float, double, boolean, byte, short, char, string, point, date, localtime, time, localdatetime, datetime, duration (default string)
    // [] --array-delimiter default ;
    // boolean: true, everything else means false
    // --delimiter='\t' (default ,)
    // time{timezone:+02:00} datetime{timezone:Europe/Stockholm} db.temporal.timezone
    // --quote=<quotation-character>
    //     Character to treat as quotation character for values in CSV data. Quotes can be escaped by doubling them, for example "" would be interpreted as a literal ". You cannot escape using \. Default: "
    // --f=<arguments-file>

    // TODO Blogartikel schreiben
    // TODO unify names
    // TODO export neo4j?

    // int	varchar	enum	date	mediumtext	tinyint	json	timestamp

    $type_mapping = [
        'int' => 'int',
        'tinyint' => 'int',
        'smallint' => 'int',
        'bigint' => 'string',
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

    $cmd_args = [];
//     $cmd_args[] = "neo4j-admin";
    $cmd_args[] = "rm -r ~/.config/Neo4j\ Desktop/Application/neo4jDatabases/database-0b42a643-61a0-4b3f-8c54-4dfbe872d200/installation-3.5.6/data/databases/graph.db/; ~/.config/Neo4j\ Desktop/Application/neo4jDatabases/database-0b42a643-61a0-4b3f-8c54-4dfbe872d200/installation-3.5.6/bin/neo4j-admin";
    $cmd_args[] = "import";
    $cmd_args[] = "--database=graph.db";
    $cmd_args[] = "--id-type=INTEGER";
    $cmd_args[] = "--delimiter='\\t'";
    $cmd_args[] = "--array-delimiter=','";
    $cmd_args[] = "--report-file=neo4j_import.log";

    // IN ('" . implode("' , '", array_keys(nodes)) . "')
    $sql = "SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = :table_schema AND table_catalog='def' AND TABLE_NAME = :table ORDER BY ORDINAL_POSITION;";
    print("$sql\n\n");
    $stmt_cols = $db->prepare($sql);

    foreach ($export as $type => $entities) {
        $i = 0;
        foreach ($entities as $file => $table_meta) {
            if ($records_limit && $i++ > $records_limit) {
                break;
            }
            $table = $table_meta['table'];
            $query_table = $table_meta['view'] ?? $table;
            $join = $table_meta['join'] ?? null;
            $join_table = $join ? strtok($join, ' ') : null;

            print(strtoupper($type) . " $file ($table_schema.$table" . ($join ? " $join" : '') . ")\n");

            $csv_file_name = "$path/${type}_$file.csv";
            $csv_file = fopen($csv_file_name, 'w');

            $stmt_cols->execute(['table_schema' => $table_schema, 'table' => $query_table]);
            $cols = $table_cols = $stmt_cols->fetchAll();

            if ($join) {
                $stmt_cols->execute(['table_schema' => $table_schema, 'table' => $join_table]);
                $join_cols = $stmt_cols->fetchAll();
                $cols = array_merge($cols, $join_cols);
            }

            $data_types = [];
            $skip_rows_for_empty_field = [];
            $type_val = $table_meta['name'];
            $select_fields = [];
            $csv_header = $type == 'node' ? [':LABEL'] : [':TYPE'];
            foreach ($cols as $row) {
                $table_name = $row['TABLE_NAME'];
                $col = $row['COLUMN_NAME'];
                $data_type = $row['DATA_TYPE'];

                if ((!isset($table_meta['select_cols']) || in_array($col, $table_meta['select_cols'])) &&
                    (!isset($table_meta['remove_cols']) || !in_array($col, $table_meta['remove_cols'])) &&
                    (!isset($table_meta['remove_cols']) || !in_array("$table_name.$col", $table_meta['remove_cols'])) &&
                    (!$filter_intern_fields || !in_array($col, $intern_fields))
                    || (isset($table_meta['id']) && $col == $table_meta['id'] && $table == $table_name)
                    || (isset($table_meta['start_id']) && $col == $table_meta['start_id'])
                    || (isset($table_meta['end_id']) && $col == $table_meta['end_id'])) {
                    $header_field = $col;
                    if ($type == 'node') {
                        if ($col == $table_meta['id']) {
                            $header_field .= ":ID({$table}_id)";
                        } else {
                            $header_field .= ":$type_mapping[$data_type]";
                        }
                        $skip_rows_for_empty_field[] = false;
                    } else {
                        if ($col == $table_meta['start_id'] && $table_meta['id'] == $table_meta['start_id']) {
                            $header_field .= ":START_ID({$table}_$col)";
                            $skip_rows_for_empty_field[] = false;
                        } elseif ($col == $table_meta['start_id']) {
                            $id_space = $table_meta['start_id_space'] ?? $col;
                            $header_field .= ":START_ID($id_space)";
                            $skip_rows_for_empty_field[] = true;
                        } elseif ($col == $table_meta['end_id']) {
                            $id_space = $table_meta['end_id_space'] ?? $col;
                            $header_field .= ":END_ID($id_space)";
                            $skip_rows_for_empty_field[] = true;
                        } else {
                            $header_field .= ":$type_mapping[$data_type]";
                            $skip_rows_for_empty_field[] = false;
                        }
                    }

                    $select_fields[] = "$table_name.$col";
                    $data_types[] = $data_type;
                    $csv_header[] = $header_field;
                    // print("$header_field\n");
                }
            }

            if (isset($table_meta['additional_join_cols']) && isset($table_meta['additional_join_csv_header_cols']) && count($table_meta['additional_join_cols']) === count($table_meta['additional_join_csv_header_cols'])) {
                foreach ($table_meta['additional_join_cols'] as $additional_join_col) {
                    $select_fields[] = $additional_join_col;
                }
                foreach ($table_meta['additional_join_csv_header_cols'] as $additional_join_col) {
                    $csv_header[] = $additional_join_col;
                    $skip_rows_for_empty_field[] = true;
                }
            }

            $csv_header_str = implode($sep, $csv_header);
            $num_cols = $nodes[$table]['result']['export_col_count'] = count($select_fields);
            $nodes[$table]['result']['export_cols_array'] = $select_fields;
            $nodes[$table]['result']['export_cols_data_types'] = $data_types;
            $nodes[$table]['result']['csv_header_array'] = $csv_header;
            $nodes[$table]['result']['csv_header_str'] = $csv_header_str;
            print("$csv_header_str\n");

            if (count(array_unique($csv_header)) < count($csv_header)) {
                print("\nERROR: duplicate col names!\n\n");
                exit(1);
            }

            fwrite($csv_file, "$csv_header_str$eol");

            $n = export_csv_rows($db, $select_fields, $type_val, $table_schema, $query_table, $join, $table_meta, $data_types, $skip_rows_for_empty_field, $filter_hist, $sep, $eol, $qe, $records_limit, $csv_file);
            fclose($csv_file);

            $cmd_args[] = "--{$type}s \"$csv_file_name\"";

            $nodes[$table]['result']['export_row_count'] = $n;
            print("Exported $n rows having $num_cols cols\n");
            print("\n");
        }
        print("\n");
    }

//     print("rm -r ~/.config/Neo4j\ Desktop/Application/neo4jDatabases/database-0b42a643-61a0-4b3f-8c54-4dfbe872d200/installation-3.5.6/data/databases/graph.db/\n");
    print(implode(' ', $cmd_args) . "\n\n");
}

function export_csv_rows($db, $select_fields, $type_val, $table_schema, $table, $join, $table_meta, $data_types, $skip_rows_for_empty_field, $filter_hist, $sep = "\t", $eol = "\n", $qe = '"', $records_limit, $csv_file) {
    global $show_sql;
    global $db;
    global $today;
    global $transaction_date;
    global $verbose;

    $num_indicator = 20;
    $show_limit = 3;

    $type_col = $table_meta['type_col'] ?? null;
    $hist_filter_join = $table_meta['hist_filter_join'] ?? '';

    $sql_from = " FROM $table_schema.$table" . (isset($join) ? " $join" : '') . ($filter_hist ? " $hist_filter_join" : '') . " WHERE 1" . ($filter_hist && $table_meta['hist_field'] ? " AND ($table.${table_meta['hist_field']} IS NULL OR $table.${table_meta['hist_field']} > NOW())" : '');
    $sql_order = " ORDER BY $table.${table_meta['id']};";

    $sql = "SELECT COUNT(*) $sql_from";
    print("$sql\n");
    $total_rows = $stmt_export = $db->query($sql)->fetchColumn();
    print("$total_rows\n");

    $sql = "SELECT " . implode(', ', $select_fields) . $sql_from . $sql_order;
    print("$sql\n");
    $stmt_export = $db->query($sql);

    $qes = array_fill(0, count($data_types), $qe);

    $skip_counter = 0;
    $i = 0;
    while (($row = $stmt_export->fetch(PDO::FETCH_BOTH)) && (!$records_limit && ++$i || $i < $records_limit)) {
        for ($j = 0, $skip_row = false; $j < count($skip_rows_for_empty_field); $j++) if ($skip_rows_for_empty_field[$j] && is_null($row[$j])) $skip_row = true;

        $row_str = ($type_val ? ($type_col ? str_replace(' ', '_', strtoupper($row[$type_col])) : $type_val) . "$sep" : '') . implode($sep, array_map('escape_csv_field', array_filter($row, function ($key) { return is_numeric($key); }, ARRAY_FILTER_USE_KEY), $data_types, $qes)) ;
        if ($skip_row) {
            if ($skip_counter++ < 5) print("SKIP $i) $row_str\n");
            continue;
        }
        if ($i < $show_limit) print("$i) $row_str\n");
        if ($i == $show_limit) print(str_repeat('_', $num_indicator) . "\r");
        if ($total_rows > 2 * $num_indicator && $i % round($total_rows / $num_indicator) == 0) print('.');
        fwrite($csv_file, "$row_str$eol");
    }
    print("\n");
    return $i;
}

function escape_csv_field($field, $data_type, $qe = '"') {
    switch ($data_type) {
        case 'timestamp': return str_replace(' ', 'T', $field);
        case 'date': return $field;
    }
    switch ($field) {
        case is_numeric($field): return $field;
        default: return '"' . str_replace('"', "$qe\"", str_replace("\n", '\n', str_replace("\r", '', $field))) . '"';
    }
}

function export_csv_plain($table_schema, $path, $filter_hist = true, $filter_intern_fields = true, $sep = "\t", $eol = "\n", $qe = '"', $records_limit = false) {
    global $script;
    global $context;
    global $show_sql;
    global $db;
    global $today;
    global $sql_today;
    global $transaction_date;
    global $sql_transaction_date;
    global $verbose;

    global $intern_fields;

    $interessenbindung_join_hist_filter = "JOIN $table_schema.parlamentarier ON interessenbindung.parlamentarier_id = parlamentarier.id AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())";
    $mandat_join_hist_filter = "JOIN $table_schema.person ON mandat.person_id = person.id JOIN $table_schema.zutrittsberechtigung ON zutrittsberechtigung.person_id = person.id AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW()) JOIN $table_schema.parlamentarier ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())";
    $tables = [
        'partei' => ['view' => 'v_partei', 'hist_field' => null, 'id' => 'id', 'remove_cols' => []],
        'branche' => ['view' => 'v_branche_simple', 'hist_field' => null, 'id' => 'id', 'remove_cols' => ['farbcode', 'symbol_abs', 'symbol_rel', 'symbol_klein_rel', 'symbol_dateiname_wo_ext', 'symbol_dateierweiterung', 'symbol_dateiname', 'symbol_mime_type']],
        'interessengruppe' => ['view' => 'v_interessengruppe_simple', 'hist_field' => null, 'id' => 'id', 'remove_cols' => []],
        'interessenraum' => ['view' => 'v_interessenraum', 'hist_field' => null, 'id' => 'id', 'remove_cols' => []],
        'kommission' => ['view' => 'v_kommission', 'hist_field' => null, 'id' => 'id', 'remove_cols' => []],
        'organisation' => ['view' => 'v_organisation_simple', 'hist_field' => null, 'id' => 'id', 'remove_cols' => []],
        'organisation_jahr' => ['view' => 'v_organisation_jahr', 'hist_field' => null, 'id' => 'id', 'remove_cols' => []],
        'parlamentarier' => ['view' => 'v_parlamentarier_simple', 'hist_field' => 'im_rat_bis', 'id' => 'id', 'remove_cols' => []],
        'fraktion' => ['view' => 'v_fraktion', 'hist_field' => null, 'id' => 'id', 'remove_cols' => []],
        'rat' => ['view' => 'v_rat', 'hist_field' => null, 'id' => 'id', 'remove_cols' => []],
        'kanton' => ['view' => 'v_kanton_simple', 'hist_field' => null, 'id' => 'id', 'remove_cols' => []],
        'kanton_jahr' => ['view' => 'v_kanton_jahr', 'hist_field' => null, 'id' => 'id', 'remove_cols' => []],
        'person' => ['view' => 'v_person_simple', 'hist_field' => null, 'id' => 'id', 'remove_cols' => []],

        'interessenbindung' => ['hist_field' => 'bis', 'id' => 'id', 'remove_cols' => [], 'hist_filter_join' => $interessenbindung_join_hist_filter],
        'interessenbindung_jahr' => ['hist_field' => null, 'id' => 'id', 'remove_cols' => array_map(function($val) { return "interessenbindung.$val"; }, array_merge($intern_fields, ['id', 'beschreibung', 'quelle_url_gueltig', 'quelle_url', 'quelle'])), 'hist_filter_join' => "JOIN $table_schema.interessenbindung ON interessenbindung_jahr.interessenbindung_id = interessenbindung.id $interessenbindung_join_hist_filter"],
        'in_kommission' => ['hist_field' => 'bis', 'id' => 'id', 'remove_cols' => [], 'hist_filter_join' => "JOIN $table_schema.parlamentarier ON in_kommission.parlamentarier_id = parlamentarier.id AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())"],
        'mandat' => ['hist_field' => 'bis', 'id' => 'id', 'remove_cols' => [], 'hist_filter_join' => $mandat_join_hist_filter],
        'mandat_jahr' => ['hist_field' => null, 'id' => 'id', 'remove_cols' => [], 'hist_filter_join' => "JOIN $table_schema.mandat ON mandat_jahr.mandat_id = mandat.id $mandat_join_hist_filter"],
        'zutrittsberechtigung' => ['hist_field' => 'bis', 'id' => 'id', 'remove_cols' => [], 'hist_filter_join' => "JOIN $table_schema.parlamentarier ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())"],
        'organisation_jahr' => ['hist_field' => null, 'select_cols' => ['freigabe_datum', 'freigabe_visa', 'created_date', 'created_visa', 'updated_date', 'updated_visa'], 'id' => 'id', 'remove_cols' => []],
        'kanton_jahr' => ['hist_field' => null, 'select_cols' => ['freigabe_datum', 'freigabe_visa', 'created_date', 'created_visa', 'updated_date', 'updated_visa'], 'id' => 'id', 'remove_cols' => []],
    ];

    $type_mapping = [
        'int' => 'INT',
        'tinyint' => 'INT',
        'smallint' => 'INT',
        'bigint' => 'BIGINT',
        'float' => 'FLOAT',
        'double' => 'DOUBLE',
        'boolean' => 'BOOLEAN',
        'varchar' => 'VARCHAR',
        'char' => 'CHAR',
        'enum' => 'ENUM',
        'set' => 'SET', // TODO fix export, set quotes correctly, use ; as delim
        'mediumtext' => 'VARCHAR',
        'text' => 'VARCHAR',
        'json' => 'JSON',
        'date' => 'DATE',
        'timestamp' => 'TIMESTAMP',
    ];

    $sql = "SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = :table_schema AND table_catalog='def' AND TABLE_NAME = :table ORDER BY ORDINAL_POSITION;";
    print("$sql\n\n");
    $stmt_cols = $db->prepare($sql);

    $i = 0;
    foreach ($tables as $table => $table_meta) {
        if ($records_limit && $i++ > $records_limit) {
            break;
        }
        $query_table = $table_meta['view'] ?? $table;

        print("$file ($table_schema.$table\n");

        $csv_file_name = "$path/table_$table.csv";
        $csv_file = fopen($csv_file_name, 'w');

        $stmt_cols->execute(['table_schema' => $table_schema, 'table' => $query_table]);
        $cols = $table_cols = $stmt_cols->fetchAll();

        $data_types = [];
        $skip_rows_for_empty_field = [];
        $select_fields = [];
        $csv_header = [];
        foreach ($cols as $row) {
            $table_name = $row['TABLE_NAME'];
            $col = $row['COLUMN_NAME'];
            $data_type = $row['DATA_TYPE'];

            if ((!isset($table_meta['select_cols']) || in_array($col, $table_meta['select_cols'])) &&
                (!isset($table_meta['remove_cols']) || !in_array($col, $table_meta['remove_cols'])) &&
                (!isset($table_meta['remove_cols']) || !in_array("$table_name.$col", $table_meta['remove_cols'])) &&
                (!$filter_intern_fields || !in_array($col, $intern_fields))
                || $col == 'id'
                || preg_match('/_id$/', $col)
                ) {
                $header_field = $col;
                $header_field .= ":$type_mapping[$data_type]";
                $skip_rows_for_empty_field[] = false;

                $select_fields[] = "$table_name.$col";
                $data_types[] = $data_type;
                $csv_header[] = $header_field;
                // print("$header_field\n");
            }
        }

        $csv_header_str = implode($sep, $csv_header);
        $num_cols = $tables[$table]['result']['export_col_count'] = count($select_fields);
        $tables[$table]['result']['export_cols_array'] = $select_fields;
        $tables[$table]['result']['export_cols_data_types'] = $data_types;
        $tables[$table]['result']['csv_header_array'] = $csv_header;
        $tables[$table]['result']['csv_header_str'] = $csv_header_str;
        print("$csv_header_str\n");

        if (count(array_unique($csv_header)) < count($csv_header)) {
            print("\nERROR: duplicate col names!\n\n");
            exit(1);
        }

        fwrite($csv_file, "$csv_header_str$eol");

        $n = export_csv_rows($db, $select_fields, null, $table_schema, $query_table, null, $table_meta, $data_types, $skip_rows_for_empty_field, $filter_hist, $sep, $eol, $qe, $records_limit, $csv_file);
        fclose($csv_file);

        $tables[$table]['result']['export_row_count'] = $n;
        print("Exported $n rows having $num_cols cols\n");
        print("\n");
    }
}

// https://github.com/ifsnop/mysqldump-php/blob/master/src/Ifsnop/Mysqldump/Mysqldump.php
function export_sql($table_schema, $path, $filter_hist = true, $filter_intern_fields = true, $sep = "\t", $eol = "\n", $qe = '"', $records_limit = false, $oneline = false) {
    global $script;
    global $context;
    global $show_sql;
    global $db;
    global $today;
    global $sql_today;
    global $transaction_date;
    global $sql_transaction_date;
    global $verbose;

    global $intern_fields;

    $interessenbindung_join_hist_filter = "JOIN $table_schema.parlamentarier ON interessenbindung.parlamentarier_id = parlamentarier.id AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())";
    $mandat_join_hist_filter = "JOIN $table_schema.person ON mandat.person_id = person.id JOIN $table_schema.zutrittsberechtigung ON zutrittsberechtigung.person_id = person.id AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW()) JOIN $table_schema.parlamentarier ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())";
    $tables = [
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
        'person' => ['hist_field' => null, 'remove_cols' => []],

        'interessenbindung' => ['hist_field' => 'bis', 'remove_cols' => [], 'hist_filter_join' => $interessenbindung_join_hist_filter],
        'interessenbindung_jahr' => ['hist_field' => null, 'remove_cols' => array_map(function($val) { return "interessenbindung.$val"; }, array_merge($intern_fields, ['id', 'beschreibung', 'quelle_url_gueltig', 'quelle_url', 'quelle'])), 'hist_filter_join' => "JOIN $table_schema.interessenbindung ON interessenbindung_jahr.interessenbindung_id = interessenbindung.id $interessenbindung_join_hist_filter"],
        'in_kommission' => ['hist_field' => 'bis', 'remove_cols' => [], 'hist_filter_join' => "JOIN $table_schema.parlamentarier ON in_kommission.parlamentarier_id = parlamentarier.id AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())"],
        'mandat' => ['hist_field' => 'bis', 'remove_cols' => [], 'hist_filter_join' => $mandat_join_hist_filter],
        'mandat_jahr' => ['hist_field' => null, 'remove_cols' => [], 'hist_filter_join' => "JOIN $table_schema.mandat ON mandat_jahr.mandat_id = mandat.id $mandat_join_hist_filter"],
        'zutrittsberechtigung' => ['hist_field' => 'bis', 'remove_cols' => [], 'hist_filter_join' => "JOIN $table_schema.parlamentarier ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())"],
    ];

    $sql = "SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = :table_schema AND table_catalog='def' AND TABLE_NAME = :table ORDER BY ORDINAL_POSITION;";
    print("$sql\n\n");
    $stmt_cols = $db->prepare($sql);

    $sql_file_name = "$path/lobbywatch_public.sql";
    $sql_file = fopen($sql_file_name, 'w');
    // gzopen, gzwrite
    // $this->compressContext = deflate_init(ZLIB_ENCODING_GZIP, array('level' => 9));
    // fwrite($this->fileHandler, deflate_add($this->compressContext, $str, ZLIB_NO_FLUSH))
    // fwrite($this->fileHandler, deflate_add($this->compressContext, '', ZLIB_FINISH))

    fwrite($sql_file, "-- Lobbywatch.ch SQL export $transaction_date$eol$eol");
    fwrite($sql_file, "-- Hist data included: " . !$filter_hist . "$eol");
    fwrite($sql_file, "-- Intern data included: " . !$filter_intern_fields . "$eol$eol");

    fwrite($sql_file, "SET NAMES utf8mb4;${eol}SET TIME_ZONE='+00:00';$eol$eol");
    fwrite($sql_file, "SET FOREIGN_KEY_CHECKS=0;$eol$eol");
    fwrite($sql_file, "-- SET SQL_NOTES=0;$eol$eol");

    fwrite($sql_file, "CREATE DATABASE IF NOT EXISTS lobbywatch_public DEFAULT CHARACTER SET utf8mb4;${eol}USE lobbywatch_public;$eol$eol");

    $sql = "SET SQL_QUOTE_SHOW_CREATE=0;";
    print("$sql\n");
    $db->query($sql);

    $i = 0;
    foreach ($tables as $table => $table_meta) {
        if ($records_limit && $i++ > $records_limit) {
            break;
        }
        $query_table = $table_meta['view'] ?? $table;

        print("$table_schema.$table\n");

        $sql = "SHOW CREATE TABLE $table";
        print("$sql\n");
        $table_create = $db->query($sql)->fetchColumn(1);
        $table_create_clean = str_replace('`', '', $table_create);
        print("DROP TABLE IF EXISTS $table;\n$table_create_clean;\n");
        fwrite($sql_file, "DROP TABLE IF EXISTS $table;$eol$table_create_clean;$eol");

        // TODO clean create table
        $stmt_cols->execute(['table_schema' => $table_schema, 'table' => $query_table]);
        $cols = $table_cols = $stmt_cols->fetchAll();

        $data_types = [];
        $skip_rows_for_empty_field = [];
        $select_fields = [];
        $sql_header = [];
        foreach ($cols as $row) {
            $table_name = $row['TABLE_NAME'];
            $col = $row['COLUMN_NAME'];
            $data_type = $row['DATA_TYPE'];

            if ((!isset($table_meta['select_cols']) || in_array($col, $table_meta['select_cols'])) &&
                (!isset($table_meta['remove_cols']) || !in_array($col, $table_meta['remove_cols'])) &&
                (!isset($table_meta['remove_cols']) || !in_array("$table_name.$col", $table_meta['remove_cols'])) &&
                (!$filter_intern_fields || !in_array($col, $intern_fields))
                || $col == 'id'
                || preg_match('/_id$/', $col)
                ) {
                $header_field = $col;
                $skip_rows_for_empty_field[] = false;

                $select_fields[] = "$table_name.$col";
                $data_types[] = $data_type;
                $sql_header[] = $header_field;
                // print("$header_field\n");
            }
        }

        $sql_header_str = "INSERT INTO $table (" . implode(", ", $sql_header) . ") VALUES";
        $num_cols = $tables[$table]['result']['export_col_count'] = count($select_fields);
        $tables[$table]['result']['export_cols_array'] = $select_fields;
        $tables[$table]['result']['export_cols_data_types'] = $data_types;
        $tables[$table]['result']['sql_header_array'] = $sql_header;
        $tables[$table]['result']['sql_header_str'] = $sql_header_str;
        print("$sql_header_str\n");

        if (count(array_unique($sql_header)) < count($sql_header)) {
            print("\nERROR: duplicate col names!\n\n");
            exit(1);
        }

        $n = export_sql_rows($db, $select_fields, $table_schema, $query_table, $sql_header_str, $table_meta, $data_types, $skip_rows_for_empty_field, $filter_hist, $sep, $eol, $qe, $records_limit, $sql_file, $oneline);

        $tables[$table]['result']['export_row_count'] = $n;
        print("Exported $n rows having $num_cols cols\n");
        print("\n");
    }
    fwrite($sql_file, "SET FOREIGN_KEY_CHECKS=1;$eol$eol");
    fclose($sql_file);
}

function export_sql_rows($db, $select_fields, $table_schema, $table, $sql_header_str, $table_meta, $data_types, $skip_rows_for_empty_field, $filter_hist, $sep = "\t", $eol = "\n", $qe = '"', $records_limit, $sql_file, $oneline) {
    global $show_sql;
    global $db;
    global $today;
    global $transaction_date;
    global $verbose;

    $num_indicator = 20;
    $show_limit = 3;

    $hist_filter_join = $table_meta['hist_filter_join'] ?? '';

    $sql_from = " FROM $table_schema.$table" . ($filter_hist ? " $hist_filter_join" : '') . " WHERE 1" . ($filter_hist && $table_meta['hist_field'] ? " AND ($table.${table_meta['hist_field']} IS NULL OR $table.${table_meta['hist_field']} > NOW())" : '');
    $sql_order = " ORDER BY id;";

    $sql = "SELECT COUNT(*) $sql_from";
    print("$sql\n");
    $total_rows = $stmt_export = $db->query($sql)->fetchColumn();
    print("$total_rows\n");

    $sql = "SELECT " . implode(', ', $select_fields) . $sql_from . $sql_order;
    print("$sql\n");
    $stmt_export = $db->query($sql);

    $qes = array_fill(0, count($data_types), $qe);

    if (!$oneline)
        fwrite($sql_file, "$sql_header_str$eol");

    $skip_counter = 0;
    $i = 0;
    while (($row = $stmt_export->fetch(PDO::FETCH_BOTH)) && (!$records_limit && ++$i || $i < $records_limit)) {
        for ($j = 0, $skip_row = false; $j < count($skip_rows_for_empty_field); $j++) if ($skip_rows_for_empty_field[$j] && is_null($row[$j])) $skip_row = true;

        $row_str = '(' . implode(",", array_map('escape_sql_field', array_filter($row, function ($key) { return is_numeric($key); }, ARRAY_FILTER_USE_KEY), $data_types, $qes)) . ')';
        if ($skip_row) {
            if ($skip_counter++ < 5) print("SKIP $i) $row_str\n");
            continue;
        }
        if ($i < $show_limit) print("$i) $row_str\n");
        if ($i == $show_limit) print(str_repeat('_', $num_indicator) . "\r");
        if ($total_rows > 2 * $num_indicator && $i % round($total_rows / $num_indicator) == 0) print('.');
        if ($oneline) fwrite($sql_file, "$sql_header_str$eol");
        fwrite($sql_file, "$row_str" . (!$oneline && $i < $total_rows ? ",$eol" : '') . ($oneline && $i < $total_rows ? ";$eol" : ''));
    }
    fwrite($sql_file, ";$eol$eol");
    print("\n");
    return $i;
}

function escape_sql_field($field, $data_type, $qe ='"') {
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

        case 'json': return "'" . str_replace('\"', '\\\\"', str_replace("'", $qe . "'", str_replace("\n", '\n', str_replace("\r", '', $field)))) . "'";

        case 'timestamp':
        case 'date':
        case 'varchar':
        case 'char':
        case 'enum':
        case 'set':
        case 'mediumtext':
        case 'text':
        default: return "'" . str_replace("'", $qe . "'", str_replace("\n", '\n', str_replace("\r", '', $field))) . "'";
    }
//     switch ($field) {
//         case is_numeric($field): return $field;
//         default: return '"' . str_replace('"', '""', str_replace("\n", '\n', str_replace("\r", '', $field))) . '"';
//     }
}
