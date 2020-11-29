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

$script = [];
$script[] = "-- SQL script sql_migration " . date("d.m.Y");

$errors = [];
$verbose = 0;

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
  $options = getopt('hv::En::jJu::', ['help','utf8mb4::', 'user-prefix:', 'db:']);

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

  get_PDO_lobbywatch_DB_connection($db_name, $user_prefix);
  print("-- $env: {$db_connection['database']}\n");

  if (isset($options['E'])) {
    print("Execute!\n");
    $execute = true;
  } else {
    $execute = false;
  }

  if (isset($options['h']) || isset($options['help'])) {
    print("SQL Migration.
Parameters:
-j                  Migrate parlamentarier.parlament_intressenbindungen to JSON
-J                  Migrate parlamentarier_log.parlament_intressenbindungen to JSON
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

  if (isset($options['u'])) {
    if ($options['u']) {
      $schema = $options['u'];
    } else {
      $schema = 'lobbywatchtest';
    }
    print("-- Schema: $schema\n");

    migrate_unused_user_visa($schema, $records_limit);
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

  $stmt->execute ( [] );
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
  $stmt->execute ( [] );
  $cols = $stmt->fetchAll();

  $sql = "SELECT id, name , nachname, vorname, last_login, last_access, email FROM $table_schema.user order by id;";
  $stmt = $db->prepare($sql);
  $stmt->execute ( [] );
  $users = $stmt->fetchAll();

  $sql = "SELECT user_id, count(*) FROM $table_schema.user_permission group by user_id;";
  $stmt = $db->prepare($sql);
  $stmt->execute ( [] );
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
