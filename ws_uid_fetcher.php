<?php
// Run: php -f ws_uid_fetcher.php -- -a --ssl -v1 -n20 -s -t
// php -f ws_uid_fetcher.php -- -a --ssl -v2 -s > sql/20201129_ws_uid_fetcher.sql
// ./run_update_ws_parlament.sh -v 1 -M -T -U -B

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
// TODO setup DB as in ws_parlament_fetcher.php
$oldDir = getcwd();
chdir(dirname(__FILE__) . '/public_html/bearbeitung');
require_once dirname(__FILE__) . '/public_html/bearbeitung/database_engine/mysql_engine.php';
chdir($oldDir);

// BFS
// http://www.bfs.admin.ch/bfs/portal/de/index/themen/00/05/blank/03/03/04.html
// http://www.bfs.admin.ch/bfs/portal/de/index/themen/00/05/blank/03/03/04.Document.139962.pdf
// https://www.uid-wse-a.admin.ch/V3.0/PublicServices.svc
// https://www.uid-wse-a.admin.ch/V3.0/PublicServices.svc?wsdl
// No login required

// https://www.uid.admin.ch/Detail.aspx?uid_id=CHE-107.810.911

// Zefix
// https://www.e-service.admin.ch/wiki/display/openegovdoc/Zefix+Webservice
// https://www.e-service.admin.ch/wiki/display/openegovdoc/Zefix+Schnittstelle
// https://www.e-service.admin.ch/wiki/download/attachments/44827026/Zefix+Webservice+Schnittstelle_%28v6.2%29.pdf?version=2&modificationDate=1428392210000

// http://www.zefix.admin.ch/WebServices/Zefix/Zefix.asmx/ShowFirm?parId=0&parChnr=CH03570104919&language=1
// http://zefix.ch/WebServices/Zefix/Zefix.asmx/SearchFirm?id=CHE-107810911&language=1

global $script;
global $context;
global $show_sql;
global $db;
global $verbose;
global $download_images;
global $convert_images;
global $lobbywatch_is_forms;

$show_sql = false;

$lobbywatch_is_forms = true;

// Set user agent, otherwise only HTML will be returned instead of JSON, ref http://stackoverflow.com/questions/2107759/php-file-get-contents-and-headers
$options = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
              "User-Agent: Mozilla/5.0\r\n" // i.e. An iPad
  )
);

$context = stream_context_create($options);

get_PDO_lobbywatch_DB_connection();

$script = [];
$script[] = "-- SQL script from ws.parlament.ch $transaction_date";
$script[] = "SET autocommit = 0;";
$script[] = "START TRANSACTION;";

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

  $docRoot = "./public_html";
  $default_uid = 'CHE-107.810.911';

  print("-- $env: {$db_connection['database']}\n");

//     var_dump($argc); //number of arguments passed
//     var_dump($argv); //the arguments passed
  $options = getopt('hsv::u:tsmo:n::fz:Z:a::',array('docroot:','help', 'uid:', 'ssl', 'zefix:', 'zefix-rest:'));

//    var_dump($options);

  if (isset($options['docroot'])) {
    $docRoot = $options['docroot'];
    print "-- DocRoot: $docRoot";
  }

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

  if (isset($options['t'])) {
     $test_mode = true;
     print("-- WS Test mode enabled\n");
  } else {
     $test_mode = false;
  }

  if (isset($options['ssl'])) {
     $ssl = true;
     print("-- SSL enabled\n");
  } else {
     $ssl = false;
  }

  if (isset($options['o'])) {
    $hr_id = $options['o'];
    if (!$hr_id) {
      $hr_id = 'CH27030020612';
    }
    show_ws_search_hr_id($hr_id, $ssl, $test_mode);
  }

  if (isset($options['uid'])) {
      $uid = $options['uid'];
      if (!$uid) {
        $uid = $default_uid;
      }
      show_ws_uid_bfs_ws_data($uid, $ssl, $test_mode);
  } else if (isset($options['u'])) {
      $uid = $options['u'];
      if (!$uid) {
        $uid = $default_uid;
      }
      show_ws_uid_bfs_ws_data($uid, $ssl, $test_mode);
  }

  if (isset($options['zefix'])) {
      $uid = $options['zefix'];
      if (!$uid) {
        $uid = $default_uid;
      }
      show_ws_zefix_soap_ws_data($uid, $ssl, $test_mode);
  } else if (isset($options['Z'])) {
      $uid = $options['Z'];
      if (!$uid) {
        $uid = $default_uid;
      }
      show_ws_zefix_soap_ws_data($uid, $ssl, $test_mode);
  }

  if (isset($options['zefix-rest'])) {
      $uid = $options['zefix-rest'];
      if (!$uid) {
        $uid = $default_uid;
      }
      show_ws_zefix_rest_ws_data($uid, $ssl, $test_mode);
  } else if (isset($options['z'])) {
      $uid = $options['z'];
      if (!$uid) {
        $uid = $default_uid;
      }
      show_ws_zefix_rest_ws_data($uid, $ssl, $test_mode);
  }

  if (isset($options['m'])) {
    migrate_old_hr_id_from_url($records_limit, $ssl, $test_mode);
  }

  if (isset($options['a'])) {
    $start_id = $options['a'];
    actualise_organisations_having_an_UID($records_limit, $start_id, $ssl, $test_mode);
  }

  if (isset($options['f'])) {
    search_name_and_set_uid($records_limit, $ssl, $test_mode);
  }

  if (count($errors) > 0) {
    echo "\nErrors:\n", implode("\n", $errors), "\n";
    exit(1);
  } else {
    $script[] = "COMMIT;";
  }

  if (isset($options['s'])) {
    print("\n-- SQL:\n");
    print(implode("\n", $script));
    print("\n");
  }
  if (isset($options['h']) || isset($options['help'])) {
    print("ws uid Fetcher for Lobbywatch.ch.
Parameters:
-u UID, --uid UID    Call UID-Register-WS, UID as 9-digit, CHE00000000, or CHE-000.000.000 string (default: $default_uid)
-Z UID, --zefix      UID Call Zefix-WS, UID as 9-digit , CHE00000000, or CHE-000.000.000 string (default: $default_uid)
-z UID, --zefix-rest UID Call Zefix-WS, UID as 9-digit , CHE00000000, or CHE-000.000.000 string (default: $default_uid)
-o HR-ID             Search old HR-ID
-t                   Call test service (default: production)
--ssl                Use SSL
-m                   Migrate old hr-id to uid from handelsregister_url
-a [START_ID]        Actualise organisations with UID from webservices, optional starting from organisation START_ID
-n number            Limit number of records
-s                   Output SQL script
-v[level]            Verbose, optional level, 1 = default
-h, --help           This help
--docroot path       Set the document root for images
");
  }

  if (count($errors) > 0) {
    echo "\nErrors:\n", implode("\n", $errors), "\n";
    exit(1);
  }

}

// /* Create a class for your webservice structure */
// class GetByUID {
//     function GetByUID($uidOrganisationIdCategory, $uidOrganisationId) {
//         $this->uidOrganisationIdCategory = $uidOrganisationIdCategory;
//         $this->uidOrganisationId = $uidOrganisationId;
//     }
// }

function show_ws_uid_bfs_ws_data($uid, $ssl, $test_mode) {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $today;
  global $verbose;

//   ini_set('soap.wsdl_cache_enabled',0);
//   ini_set('soap.wsdl_cache_ttl',0);

  print("UID=$uid\n");
  $data = _lobbywatch_fetch_ws_uid_bfs_data($uid, $verbose, $ssl, $test_mode);
  print_r($data);
  print_r($data['data']);
}

function show_ws_zefix_rest_ws_data($uid, $ssl, $test_mode) {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $today;
  global $verbose;

  print("UID=$uid\n");
  $data = _lobbywatch_fetch_ws_zefix_rest_data($uid, $verbose, $ssl, $test_mode);
  print_r($data);
  print_r($data['data']);
}

function show_ws_zefix_soap_ws_data($uid, $ssl, $test_mode) {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $today;
  global $verbose;

//   ini_set('soap.wsdl_cache_enabled',0);
//   ini_set('soap.wsdl_cache_ttl',0);

  print("UID=$uid\n");
  $data = _lobbywatch_fetch_ws_zefix_soap_data($uid, $verbose, $ssl, $test_mode);
  print_r($data);
  print_r($data['data']);
}

function show_ws_search_hr_id($old_hr_id_raw, $ssl, $test_mode) {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $today;
  global $verbose;

//   ini_set('soap.wsdl_cache_enabled',0);
//   ini_set('soap.wsdl_cache_ttl',0);

  print("HR-ID-raw=$old_hr_id_raw\n");
  $old_hr_id = formatOldHandelsregisterID($old_hr_id_raw);
  print("HR-ID=$old_hr_id\n");
  $data = _lobbywatch_fetch_ws_uid_data_from_old_hr_id($old_hr_id, $verbose, $ssl, $test_mode);
  print_r($data);
  print_r($data['data']);
}

function migrate_old_hr_id_from_url($records_limit, $ssl, $test_mode) {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $today;
  global $sql_today;
  global $transaction_date;
  global $sql_transaction_date;
  global $verbose;

  $script[] = $comment = "\n-- Organisation migrate old HR-ID to UID from handelsregister URL $transaction_date";

  $sql = "SELECT id, name_de, handelsregister_url, uid, rechtsform_handelsregister FROM organisation ORDER BY id;"; //  WHERE handelsregister_url IS NOT NULL
  $stmt = $db->prepare($sql);

  $stmt->execute ( [] );
//   $organisation_list = $stmt->fetchAll(PDO::FETCH_CLASS);
//   $organisation_list = $stmt->fetchAll(PDO::FETCH_CLASS);

//   var_dump($parlamentarier_list_db);

  echo "\n/*\nMigrate old Handelsregister ID to UID $transaction_date\n";
  print("rows = " . $stmt->rowCount() . "\n");

  $data = initDataArray();
  $client = initSoapClient($data, getUidBfsWsLogin($test_mode), $verbose, $ssl);

  $level = 0;
  $n_new_uid = 0;
  $n_different_uid= 0;
  $n_equal_uid = 0;
  $n_not_found = 0;
  $n_no_url = 0;
  $n_only_uid = 0;
  $n_fix_uid = 0;
  $n_bad_uid = 0;

//   for($page = 1, $hasMorePages = true, $i = 0; $hasMorePages; $page++) {
  $i = 0;
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $i++;
    if ($records_limit && $i > $records_limit) {
      break;
    }
    $sign = ' ';
    $hr_url = $row['handelsregister_url'];
    $rechtsform_handelsregister_db = $row['rechtsform_handelsregister'];
    $id = $row['id'];
    $uid = $uid_db = $row['uid'];
    $name = $row['name_de'];
    // chnr=0203032453
    $old_hr_id = null;
    $uid_ws = null;
    $rechtsform_handelsregister = null;
    $matches = [];
    if (preg_match('/chnr=(\d{10,11})/',$hr_url, $matches)) {
      $old_hr_id_raw = $matches[1];
      $old_hr_id = formatOldHandelsregisterID($old_hr_id_raw);
    } else if (preg_match('/CH-?\d{3}[.-]?\d[.-]?\d{3}[.-]?\d{3}[-]?\d/', $hr_url, $matches)) {
      $old_hr_id_raw = $matches[0];
      $old_hr_id = formatOldHandelsregisterID($old_hr_id_raw);
    } else if (preg_match('/CHE-?\d{3}[.]?\d{3}[.]?\d{2,3}/', $hr_url, $matches)) {
      $uid_raw = $matches[0];
      $uid_ws = formatUID($uid_raw);
    }
    if ($old_hr_id) {
//       $data = _lobbywatch_fetch_ws_uid_data_from_old_hr_id($old_hr_id, $verbose, $ssl, $test_mode);
//       if ($data['success'] && $data['data']['uid']) {
//         $uid_ws = $data['data']['uid'];
//       }
      $data = initDataArray();
      $uid = $uid_ws = ws_get_uid_from_old_hr_id($old_hr_id, $client, $data, $verbose);
      $rechtsform_handelsregister = $data['data']['rechtsform_handelsregister'];
    }
    if ($uid_db == $uid_ws && $uid_ws) {
      $sign = '=';
      $n_equal_uid++;
    } else if (!$uid_db && $uid_ws) {
      $sign = '+';
      $n_new_uid++;

      $script[] = $comment = "-- Update " . mb_substr($name, 0, 45) . ", id = $id, $old_hr_id → $uid_ws";
      $script[] = $command = "-- DISABLED UPDATE organisation SET uid='$uid_ws', updated_visa='import', updated_date=$sql_transaction_date WHERE id = $id;";
      if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
      if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
    } else if ($hr_url && !$uid_ws) {
      $sign = '!';
      $n_not_found++;
    } else if ($uid_db && $uid_ws && $uid_db != $uid_ws) {
      $sign = '≠';
      $n_different_uid++;

      $script[] = $comment = "-- Update DIFFERENCE " . mb_substr($name, 0, 45) . ", id = $id, $old_hr_id → $uid_ws";
      $script[] = $command = "-- UPDATE organisation SET uid='$uid_ws', updated_visa='import', updated_date=$sql_transaction_date WHERE id = $id;";
      if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
      if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
    } else if ($uid_db && !$uid_ws && preg_match('/\d{3}[.]?\d{3}[.]?\d{2,3}/', $uid_db, $matches)) {
      $uid_raw = str_replace('.', '', $matches[0]);
      $uid_ws = formatUID($uid_raw);
      if ($uid_ws && $uid_db != $uid_ws) {
        $sign = '#';
        $n_fix_uid++;
        $uid = $uid_ws;
        $old_hr_id = $uid_db; // For logging set to other variable
        $script[] = $comment = "-- Update FIX UID" . mb_substr($name, 0, 45) . ", id = $id, $uid_db → $uid_ws";
        $script[] = $command = "UPDATE organisation SET uid='$uid_ws', updated_visa='import', updated_date=$sql_transaction_date WHERE id = $id;";
        if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
        if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
      } else if (!$uid_ws && $uid_db != $uid_ws) {
        $sign = 'X';
        $n_bad_uid++;
        $uid = null;
        $old_hr_id = $uid_db; // For logging set to other variable
        $script[] = $comment = "-- Delete BAD UID " . mb_substr($name, 0, 45) . ", id = $id, $uid_db → NULL";
        $script[] = $command = "UPDATE organisation SET uid=NULL, updated_visa='import', updated_date=$sql_transaction_date WHERE id = $id;";
        if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
        if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
      } else if ($uid_ws && $uid_db == $uid_ws) {
        $sign = '=';
        $n_equal_uid++;
      } else {
        $sign = '.';
        $n_only_uid++;
      }
    } else {
      $sign = ' ';
      $n_no_url++;
    }

    if ($uid) {
      if (!$rechtsform_handelsregister) {
        $data = _lobbywatch_fetch_ws_uid_bfs_data($uid, $verbose, $ssl, $test_mode);
        if ($data['success']) {
          $rechtsform_handelsregister = $data['data']['rechtsform_handelsregister'];
        }
      }
      if ($rechtsform_handelsregister && $rechtsform_handelsregister != $rechtsform_handelsregister_db) {
        $script[] = $comment = "-- Set rechtsform_handelsregister " . mb_substr($name, 0, 45) . ", id = $id, $uid, ";
        $script[] = $command = "UPDATE organisation SET rechtsform_handelsregister='$rechtsform_handelsregister', updated_visa='import', updated_date=$sql_transaction_date WHERE id = $id;";
        if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
        if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
      }
    }

    $mgr_msg = $rechtsform_handelsregister ? $rechtsform_handelsregister . "\t" : '';
    $mgr_msg .= ($uid_ws ? "$old_hr_id → $uid_ws" : $hr_url);
    print(str_repeat("\t", $level) . str_pad($i, 4, " ", STR_PAD_LEFT) . '|' . str_pad($id, 4, " ", STR_PAD_LEFT) . '|' . str_pad($uid_db, 15, " ", STR_PAD_LEFT) . mb_str_pad(" | $sign | " . mb_substr($name, 0, 42), 50, " ") . "| " . $mgr_msg . "\n");
  }

  print("\n+: $n_new_uid");
  print("\n≠: $n_different_uid");
  print("\n=: $n_equal_uid");
  print("\n!: $n_not_found");
  print("\n.: $n_only_uid");
  print("\n#: $n_fix_uid");
  print("\nX: $n_bad_uid");
  print("\n : $n_no_url");
  print("\nΣ: " . ($n_new_uid + $n_different_uid + $n_equal_uid + $n_not_found + $n_no_url + $n_only_uid + $n_fix_uid + $n_bad_uid));
  print("\n\n*/\n");
}

function actualise_organisations_having_an_UID($records_limit, $start_id, $ssl, $test_mode) {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $today;
  global $sql_today;
  global $transaction_date;
  global $sql_transaction_date;
  global $verbose;

  $uidBFSenabled = true;
  $zefixSoapEnabled = false;
  $zefixRestEnabled = true;

  $script[] = $comment = "\n-- Actualise organisations having an UID from webservices $transaction_date";

  $starting_id_sql = $start_id ? "AND id >= $start_id" : '';
  $sql = "SELECT id, name_de, handelsregister_url, uid, rechtsform, rechtsform_handelsregister, rechtsform_zefix, beschreibung, name_de, name_fr, ort, adresse_strasse, adresse_zusatz, adresse_plz FROM organisation WHERE uid IS NOT NULL $starting_id_sql ORDER BY id;";
  $stmt = $db->prepare($sql);

  $stmt->execute([]);

  echo "\n/*\nActualise organisations having an UID from webservices $transaction_date\n";
  print("rows = " . $stmt->rowCount() . "\n");

  if ($uidBFSenabled) {
    $dataUidBfs = initDataArray();
    $clientUid = initSoapClient($dataUidBfs, getUidBfsWsLogin($test_mode), $verbose, $ssl);
  }
  if ($zefixSoapEnabled) {
    $dataZefix = initDataArray();
    $clientZefix = initSoapClient($dataZefix, getZefixSoapWsLogin($test_mode), $verbose, $ssl);
  }

  $level = 0;
  $n_ok = 0;
  $n_different = 0;
  $n_updated= 0;
  $n_not_found = 0;

  $n_ws_uid_found = 0;
  $n_ws_zefix_found = 0;
  $n_ws_zefix_rest_found = 0;

  $i = 0;
  while ($organisation_db = $stmt->fetch(PDO::FETCH_OBJ)) {
    $i++;
    if ($records_limit && $i > $records_limit) {
      break;
    }
    $sign = '!';
    $id = $organisation_db->id;
    $uid = $uid_db = $organisation_db->uid;
    $name = $organisation_db->name_de;
    $update = [];
    $update_optional = [];
    $fields = [];
    $different_db_values = false;
    // UID WS (BFS)
    if ($uidBFSenabled) {
      $retry_log = '';
      $dataUidBfs = initDataArray();
      ws_get_organization_from_uid_bfs($uid, $clientUid, $dataUidBfs, $verbose, 9, $retry_log); // Similar to _lobbywatch_fetch_ws_uid_bfs_data() in utils.php
      $fields[] = $retry_log;
      if (!$records_limit || $records_limit > 20) {
        sleep(3);
      }
      if ($dataUidBfs['success']) {
        // http://stackoverflow.com/questions/1869091/how-to-convert-an-array-to-object-in-php
        $organisation_ws = (object) $dataUidBfs['data'];

        $different_db_values |= checkField('rechtsform_handelsregister', 'rechtsform_handelsregister', $organisation_db, $organisation_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'getValueFromWSFieldNameEmptyAsNull');
        $different_db_values |= checkField('rechtsform', 'rechtsform_handelsregister', $organisation_db, $organisation_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, '_lobbywatch_ws_get_rechtsform');
      } else {
        // all uids must be available
        $fields[] = "***UID@BFS ERROR [{$dataUidBfs['message']}]***";
      }
    }

    // Zefix SOAP
    if ($zefixSoapEnabled) {
      $dataZefix = initDataArray();
      ws_get_organization_from_zefix_soap($uid, $clientZefix, $dataZefix, $verbose); // Similar to _lobbywatch_fetch_ws_uid_bfs_data() in utils.php
      if ($dataZefix['success']) {
        // http://stackoverflow.com/questions/1869091/how-to-convert-an-array-to-object-in-php
        $organisation_ws = (object) $dataZefix['data'];

        $different_db_values |= checkField('rechtsform_zefix', 'rechtsform_zefix', $organisation_db, $organisation_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'getValueFromWSFieldNameEmptyAsNull');
        // Overwrite (re-set) if we have Zefix data (Many fields will be double set, but this is not a problem)
        $different_db_values |= checkField('rechtsform_handelsregister', 'rechtsform_handelsregister', $organisation_db, $organisation_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'getValueFromWSFieldNameEmptyAsNull');
        $different_db_values |= checkField('rechtsform', 'rechtsform_handelsregister', $organisation_db, $organisation_ws, $update, $update_optional, $fields, FIELD_MODE_ONLY_NEW, '_lobbywatch_ws_get_rechtsform');

        // ----------------------------------------------------------
        // DO NOT FORGET TO ADD NEW DB FIELDS TO SELECT
        // ----------------------------------------------------------

    //     print_r($organisation_ws);
    //     print("Check: $organisation_ws->rechtsform_handelsregister $organisation_db->rechtsform " . _lobbywatch_ws_get_rechtsform($organisation_ws->rechtsform_handelsregister));
      }
    }

    // Zefix REST
    if ($zefixRestEnabled) {
      $dataZefixRest = initDataArray();
      ws_get_organization_from_zefix_rest($uid, $dataZefixRest, $verbose, $test_mode); // Similar to _lobbywatch_fetch_ws_uid_bfs_data() in utils.php
      if ($dataZefixRest['success']) {
        // http://stackoverflow.com/questions/1869091/how-to-convert-an-array-to-object-in-php
        $organisation_ws = (object) $dataZefixRest['data'];

        $different_db_values |= checkField('rechtsform_zefix', 'rechtsform_zefix', $organisation_db, $organisation_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'getValueFromWSFieldNameEmptyAsNull');
        // Overwrite (re-set) if we have Zefix data (Many fields will be double set, but this is not a problem)
        $different_db_values |= checkField('rechtsform_handelsregister', 'rechtsform_handelsregister', $organisation_db, $organisation_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'getValueFromWSFieldNameEmptyAsNull');
        $different_db_values |= checkField('rechtsform', 'rechtsform_handelsregister', $organisation_db, $organisation_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, '_lobbywatch_ws_get_rechtsform');
        $different_db_values |= checkField('beschreibung', 'zweck', $organisation_db, $organisation_ws, $update, $update_optional, $fields, FIELD_MODE_ONLY_NEW, 'getValueFromWSFieldNameEmptyAsNull');
        $different_db_values |= checkField('ort', 'ort', $organisation_db, $organisation_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'getValueFromWSFieldNameEmptyAsNull');
        $different_db_values |= checkField('adresse_strasse', 'adresse_strasse', $organisation_db, $organisation_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'getValueFromWSFieldNameEmptyAsNull');
        $different_db_values |= checkField('adresse_zusatz', 'adresse_zusatz', $organisation_db, $organisation_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'getValueFromWSFieldNameEmptyAsNull');
        $different_db_values |= checkField('adresse_plz', 'adresse_plz', $organisation_db, $organisation_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'getValueFromWSFieldNameEmptyAsNull');

        // ----------------------------------------------------------
        // DO NOT FORGET TO ADD NEW DB FIELDS TO SELECT
        // ----------------------------------------------------------

    //     print_r($organisation_ws);
    //     print("Check: $organisation_ws->rechtsform_handelsregister $organisation_db->rechtsform " . _lobbywatch_ws_get_rechtsform($organisation_ws->rechtsform_handelsregister));
      } else {
        // $fields[] = "Not found on zefix rest";
      }
    }

    $name_short = mb_substr($name, 0, 42);
    if (count($update) > 0) {
      $script[] = $comment = "-- Update Organisation '$name_short', id=$id, uid=$uid, fields: " . implode(", ", $fields);
      $script[] = $command = "UPDATE `organisation` SET " . implode(", ", $update) . ", updated_visa='import', updated_date=$sql_transaction_date, notizen=CONCAT_WS('\\n\\n', '$today/Roland: Update via uid ws',`notizen`) WHERE id=$id;";
      if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
      if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
    }

    if (count($update_optional) > 0) {
      $script[] = $comment = "-- Optional update Organisation '$name_short', id=$id, uid=$uid, fields: " . implode(", ", $fields);
      $script[] = $command = "-- UPDATE `organisation` SET " . implode(", ", $update) . ", updated_visa='import', updated_date=$sql_transaction_date, notizen=CONCAT_WS('\\n\\n', '$today/Roland: Update via uid ws',`notizen`) WHERE id=$id;";
      if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
      if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
    }

    if (count($update) > 0) {
      if ($sign == '!') {
        $sign = '≠';
      }
    } else if ($different_db_values) {
      $sign = '~';
    } else if (empty($dataUidBfs['success']) && empty($dataZefix['success']) && empty($dataZefixRest['success'])) {
      $sign = '!';
    } else {
      $sign = '=';
    }

    // -- End Update fields

    switch ($sign) {
      case '=': $n_ok++; break;
      case '~': $n_different++; break;
      case '≠': $n_updated++; break;
      case '!': $n_not_found++; break;
    }

    $ws_uid_found = !empty($dataUidBfs['success']) && !empty($dataUidBfs['data']);
    $ws_zefix_found = !empty($dataZefix['success']) && !empty($dataZefix['data']);
    $ws_zefix_rest_found = !empty($dataZefixRest['success']) && !empty($dataZefixRest['data']);

    if ($ws_uid_found) {
      $n_ws_uid_found++;
    }

    if ($ws_zefix_found) {
      $n_ws_zefix_found++;
    }

    if ($ws_zefix_rest_found) {
      $n_ws_zefix_rest_found++;
    }

    print(str_repeat("\t", $level) . str_pad($i, 4, " ", STR_PAD_LEFT) . '|' . str_pad($id, 4, " ", STR_PAD_LEFT) . '|' . str_pad($uid_db, 15, " ", STR_PAD_LEFT) . ' |' . ($ws_uid_found ? 'U' : ($uidBFSenabled ? 'u' : ' ')) . ($ws_zefix_found ? 'Z' : ' ') . ($ws_zefix_rest_found ? 'R' : ' ') . mb_str_pad("| $sign | " . mb_substr($name, 0, 42), 50, " ") . "| " . implode(" | ", $fields) . "\n");
  }

  print("\nU: $n_ws_uid_found");
  print("\nZ: $n_ws_zefix_found");
  print("\nR: $n_ws_zefix_rest_found\n");

  print("\n≠: $n_updated");
  print("\n=: $n_ok");
  print("\n~: $n_different");
  print("\n!: $n_not_found");
  print("\nΣ: " . ($n_updated + $n_ok + $n_different + $n_not_found));

  print("\n\n*/\n");

  print("\n-- UID-ORGANISATIONEN " . ($n_updated > 0 ? 'DATA CHANGED' : 'DATA UNCHANGED') . "\n\n");
}

/**
 * Migration function. Only useful for migration
 * @return UID or null
 */
function ws_get_uid_from_old_hr_id($old_hr_id, $client, &$data, $verbose) {
  try {
//     $response = $client->__soapCall("GetByUID", array($params));
    $params = array(
      'searchParameters' => array(
        'organisation' => array(
          'organisationIdentification' => array(
            'OtherOrganisationId' => array(
              'organisationIdCategory' => 'CH.HR',
              'organisationId' => $old_hr_id,
            )
          )
        )
      ),
    );
    $response = $client->Search($params);
    fillDataFromUIDResult($response->SearchResult, $data);
    //     print_r($data);
    if (!empty($data['data']['uid'])) {
      return $data['data']['uid'];
    } else {
      return null;
    }
  } catch(Exception $e) {
    $data['message'] .= _utils_get_exeption($e);
    $data['success'] = false;
    return null;
  } finally {
    ws_verbose_logging($client, $response, $data, $verbose);
  }
}

function search_name_and_set_uid($records_limit, $ssl, $test_mode) {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $today;
  global $sql_today;
  global $transaction_date;
  global $sql_transaction_date;
  global $verbose;

  $script[] = $comment = "\n-- Organisation migrate old HR-ID to UID from handelsregister URL $transaction_date";

  $sql = "SELECT id, name_de, uid, adresse_plz, rechtsform FROM organisation WHERE uid IS NULL ORDER BY id;"; //  WHERE handelsregister_url IS NOT NULL
  $stmt = $db->prepare($sql);

  $stmt->execute ( [] );

  echo "\nSearch name and set UID $transaction_date\n";
  print("rows = " . $stmt->rowCount() . "\n");

  $data = initDataArray();
  $client = initSoapClient($data, getUidBfsWsLogin($test_mode), $verbose, $ssl);

  $level = 0;

  $n_new_uid = 0;
  $n_different_name= 0;
  $n_nothing_found = 0;

  $i = 0;
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $i++;
    if ($records_limit && $i > $records_limit) {
      break;
    }
    $sign = ' ';
    $id = $row['id'];
    $name = $row['name_de'];
    $plz = $row['adresse_plz'];
    $rechtsform = $row['rechtsform'];
    $name_ws = null;
    $plz_ws = null;
    $rechtsform_ws = null;
    $uid = $uid_db = $row['uid']; // always null
    $matches = [];

    $data = initDataArray();
    $uid = $uid_ws = ws_get_uid_from_name_search($name, $plz, $client, $data, $verbose);

    if ($uid_db == $uid_ws && $uid_ws) {
      $sign = '=';
      $n_equal_uid++;
    } else if (!$uid_db && $uid_ws) {
      $data = initDataArray();
      ws_get_organization_from_uid_bfs($uid_ws, $client, $data, $verbose);
      if (!$records_limit || $records_limit > 20) {
        sleep(3);
      }
      @$plz_ws = $data['data']['adresse_plz'];
      @$rechtsform_ws = $data['data']['rechtsform'];
      $replace_pattern = '/[.,() "-/]/ui';
      if ($data['success'] && preg_replace($replace_pattern, '', mb_strtolower($name_ws = $data['data']['name_de'])) == preg_replace($replace_pattern, '', mb_strtolower($name))) {
        $sign = '+';
        $n_new_uid++;


        $script[] = $comment = "-- Set found uid " . mb_substr($name, 0, 45) . ", id = $id, $uid_ws";
        $script[] = $command = "UPDATE organisation SET uid='$uid_ws', updated_visa='import', updated_date=$sql_transaction_date WHERE id = $id;";
        if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
        if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
      } else {
        $sign = '≠';
        $n_different_name++;
      }
    } else {
      $sign = ' ';
      $n_nothing_found++;
    }

    $mgr_msg = str_pad($plz, 4, " ", STR_PAD_LEFT) . '  ' . str_pad(mb_substr($rechtsform, 0, 8), 8, " ", STR_PAD_RIGHT) . ' ' .  ($uid_ws ? "$uid_ws    " .  str_pad($plz_ws, 4, " ", STR_PAD_LEFT) . '  ' . str_pad(mb_substr($rechtsform_ws, 0, 8), 8, " ", STR_PAD_RIGHT) . '     ' . mb_str_pad(mb_substr($name_ws, 0, 62), 62, " ", STR_PAD_RIGHT): '');
    print(str_repeat("\t", $level) . str_pad($i, 4, " ", STR_PAD_LEFT) . '|' . str_pad($id, 4, " ", STR_PAD_LEFT) . '|' . str_pad($uid_db, 15, " ", STR_PAD_LEFT) . mb_str_pad(" | $sign | " . mb_substr($name, 0, 62), 70, " ") . "| " . $mgr_msg . "\n");
  }

  print("\n+: $n_new_uid");
  print("\n≠: $n_different_name");
  print("\n : $n_nothing_found");
  print("\nΣ: " . ($n_new_uid + $n_different_name + $n_nothing_found /*+ $n_only_uid + $n_fix_uid + $n_bad_uid + $n_equal_uid + $n_not_found*/));
  print("\n");
}

function ws_get_uid_from_name_search($name, $plz, $client, &$data, $verbose) {
  $response = null;
  try {
//     $response = $client->__soapCall("GetByUID", array($params));
    $params = array(
      'searchParameters' => array(
        'organisation' => array(
          'organisationIdentification' => array(
            'organisationName' => $name
          )
        )
      ),
    );
    if ($plz) {
      $params['searchParameters']['organisation']['contact'] = array(
        'address' => array(
          'postalAddress' => array(
            'addressInformation' => array(
              'swissZipCode' => $plz,
            )
          )
        )
      );
//       print_r($params);
    }
    $response = $client->Search($params);
    fillDataFromUIDResult($response->SearchResult, $data);
//     print_r($data);
    if ($data['success'] && !empty($data['data']['uid']) && $data['count']) {
      return $data['data']['uid'];
    } else {
      return null;
    }
  } catch(Exception $e) {
    $data['message'] .= _utils_get_exeption($e);
    $data['success'] = false;
    return null;
  } finally {
    ws_verbose_logging($client, $response, $data, $verbose);
  }
}
