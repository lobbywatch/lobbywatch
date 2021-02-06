<?php
require_once dirname(__FILE__) . '/public_html/settings/settings.php';
require_once dirname(__FILE__) . '/public_html/common/utils.php';

/*
# ./deploy.sh -b -p
# ./run_local_db_script.sh lobbywatchtest prod_bak/`cat prod_bak/last_dbdump_data.txt`

// ./run_update_ws_parlament.sh -T -W -B -v -n

./db_prod_to_local.sh lobbywatchtest
export SYNC_FILE=sql/ws_parlament_ch_sync_`date +"%Y%m%d"`.sql; php -f ws_parlament_fetcher.php -- -pks | tee $SYNC_FILE; less $SYNC_FILE
./run_local_db_script.sh lobbywatchtest $SYNC_FILE
./deploy.sh -r -s $SYNC_FILE
./deploy.sh -p -r -s $SYNC_FILE
*/

// $json = fopen($url, 'r');
// $json = file_get_contents($url);
// $json = new_get_file_contents($url);

const REQUEST_TIMEOUT_S = 5;

global $script;
global $context;
global $show_sql;
global $db;
global $transaction_date;
global $errors;
global $verbose;
global $download_images;
global $convert_images;
global $user;

$user = getenv("USER");

$show_sql = false;

// Set user agent, otherwise only HTML will be returned instead of JSON, ref http://stackoverflow.com/questions/2107759/php-file-get-contents-and-headers
$options = [
  'http'=> [
    'timeout' => REQUEST_TIMEOUT_S,
    'method'=>"GET",
    'header'=>
      "Content-Type: application/json\r\n" .
      "Accept:application/json\r\n" .
//       "User-Agent: Mozilla/5.0\r\n" // i.e. Firefox 60
//       "User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0\r\n" // i.e. Firefox 60
      "User-Agent:LWAgent/1.0\r\n"
    ]
];

$context = stream_context_create($options);

// https://stackoverflow.com/questions/10236166/does-file-get-contents-have-a-timeout-setting
ini_set('default_socket_timeout', REQUEST_TIMEOUT_S);

$script = [];
$script[] = "-- SQL script wikidata $transaction_date";
$script[] = "SET autocommit = 0;";
$script[] = "START TRANSACTION;";

$errors = [];
$verbose = 0;
$download_images = false;

main();

function main() {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $today;
  global $transaction_date;
  global $errors;
  global $verbose;
  global $download_images;
  global $convert_images;
  global $env;
  global $user;
  global $db_name;
  global $db_con;
  global $db_schema;

  $docRoot = "./public_html";

//     var_dump($argc); //number of arguments passed
//     var_dump($argv); //the arguments passed
  $options = getopt('hsv::n::f',array('db:','help'));

  if (isset($options['h']) || isset($options['help'])) {
    print("ws.parlament.ch Fetcher for Lobbywatch.ch.
Parameters:
-s              Output SQL script
-f              Fast update (only entries without wikidata are checked)
-n number       Limit number of records
-v[level]       Verbose, optional level, 1 = default
--db=db_name    Name of DB to use
--docroot path  Set the document root for images
-h, --help      This help

Commands:
./db_prod_to_local.sh lobbywatchtest
export SYNC_FILE=sql/ws_parlament_ch_sync_`date +\"%Y%m%d\"`.sql; php -f ws_parlament_fetcher.php -- -pks | tee \$SYNC_FILE; less \$SYNC_FILE
./run_local_db_script.sh lobbywatchtest \$SYNC_FILE
./deploy.sh -r -s \$SYNC_FILE
./deploy.sh -p -r -s \$SYNC_FILE
");
  }

  if (isset($options['db'])) {
    $db_name = $options['db'];
  } else {
    $db_name = null;
  }
  get_PDO_lobbywatch_DB_connection($db_name);

  print("-- $env: {$db_con['database']}\n");
  print("-- Executing user=$user\n");

  if (isset($options['v'])) {
    if ($options['v']) {
      $verbose = $options['v'];
    } else {
      $verbose = 1;
    }
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

  $schema = $db_schema;
  print("-- Schema: $schema\n");

  $fast = isset($options['f']);

  updateWikidata($schema, $fast, $records_limit);
  // setImportDate();

  if (count($errors) > 0) {
    echo "\nErrors:\n", implode("\n", $errors), "\n";
    exit(1);
  } else {
    $script[] = "COMMIT;";
  }

  if (isset($options['s'])) {
    print("\n-- SQL-START:\n");
    print(implode("\n", $script));
    print("\n-- SQL-END\n");
    print("\n");
  }
  if (count($errors) > 0) {
    echo "\nErrors:\n", implode("\n", $errors), "\n";
    exit(1);
  }

}

function setImportDate() {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $today;
  global $sql_today;
  global $transaction_date;
  global $sql_transaction_date;
  global $user;

  $script[] = "UPDATE settings SET value='$transaction_date' WHERE key_name='ws.parlament.ch_last_import_date';";
}

function updateWikidata(string $schema, bool $fast, $records_limit = false) {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $today;
  global $sql_today;
  global $transaction_date;
  global $sql_transaction_date;
  global $user;

  $script[] = $comment = "\n-- Update Wikidata $transaction_date";
  echo "\n/*\nUpdate Wikidata $transaction_date\n";

  $sql = "SELECT `TABLE_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `COLUMN_NAME`='wikidata_qid' AND `TABLE_SCHEMA`='$schema' AND TABLE_NAME NOT LIKE '%_log' AND TABLE_NAME NOT LIKE 'mv_%' AND TABLE_NAME NOT LIKE 'v_%'AND TABLE_NAME NOT LIKE 'uv_%'AND TABLE_NAME NOT LIKE 'vf_%' ORDER BY TABLE_NAME;";
  $stmt = $db->prepare($sql);
  $stmt->execute([]);
  $wikidata_tables = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

  $level = 0;
  $wikidata_equal_count = 0;
  $wikidata_updated_count = 0;
  $wikidata_new_count = 0;
  $wikidata_empty_count = 0;
  $wikidata_removed_count = 0;

  $fast_condition = $fast ? 'AND wikidata_qid IS NULL' : '';

  $i = 0;
  foreach ($wikidata_tables as $wikidata_table) {
    $sql = "SELECT id, wikipedia, wikidata_qid FROM $wikidata_table WHERE wikipedia IS NOT NULL $fast_condition ORDER BY id;";
    foreach ($db->query($sql) as $row) {
      $i++;
      if ($records_limit && $i > $records_limit) {
        break;
      }
      $id = $row['id'];
      $wikipedia_url = $row['wikipedia'];
      $wikidata_qid_db = $row['wikidata_qid'];
      $webpage = get_web_data($wikipedia_url);
      $matches = [];
      $msg = [];
      $wikidata_qid = null;

      if (empty($webpage)) {
        $msg[] = "Wikipedia not found";
        $sign = '!';
        $wikidata_empty_count++;
      } else if (preg_match('%https://www.wikidata.org/wiki/Special:EntityPage/(Q\d+)%', $webpage, $matches)) {
        if (count($matches) > 2) {
          $msg[] = "Too many results";
        }
        $wikidata_qid = $matches[1];

        $sign = '!';
        $update = [];
        $update_optional = [];

        if ($wikidata_qid_db === $wikidata_qid) {
          $sign = '=';
          $wikidata_equal_count++;
        } else {
          if (empty($wikidata_qid_db)) {
            $sign = '+';
            $wikidata_new_count++;
          } else if (empty($wikidata_qid)) {
            $sign = '-';
            $wikidata_removed_count++;
          } else {
            $sign = '≠';
            $wikidata_updated_count++;
          }
          $different_db_values = false;
          $different_db_values |= checkField('wikidata_qid', $wikidata_qid, $row, null, $update, $update_optional, $msg, FIELD_MODE_OVERWRITE);

          $script[] = $comment = "-- Update wikidata $wikidata_table from $wikipedia_url, id=$id";
          $script[] = $command = "UPDATE $wikidata_table SET " . implode(", ", $update) . ", updated_visa='import', updated_date=$sql_transaction_date WHERE id = $id;";
          if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
          if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
        }
      } else {
        $msg[] = "Qid not found";
        $sign = '!';
        $wikidata_empty_count++;
      }
      print(str_repeat("\t", $level) . str_pad($i, 5, " ", STR_PAD_LEFT) . "| $sign | " . mb_str_pad("$wikidata_table", 25) . "|" . str_pad($id, 4, " ", STR_PAD_LEFT) . "| " . mb_str_pad($wikipedia_url, 75) . "| " . mb_str_pad($wikidata_qid_db, 12) . " | " . mb_str_pad($wikidata_qid, 12) . " | " . implode(" | ", $msg) . "\n");
    }
  }

  print("\n = : $wikidata_equal_count");
  print("\n ≠ : $wikidata_updated_count");
  print("\n + : $wikidata_new_count");
  print("\n - : $wikidata_removed_count");
  print("\n ! : $wikidata_empty_count");
  print("\n\n*/\n");
  print("\n\n-- WIKIDATA " . ($wikidata_updated_count + $wikidata_new_count + $wikidata_removed_count > 0 ? 'DATA CHANGED' : 'DATA UNCHANGED') . "\n\n");
}
