<?php
// Run: /opt/lampp/bin/php -f ws_uid_fetcher.php -- --uid 107810911 --ssl -t

require_once dirname(__FILE__) . '/public_html/settings/settings.php';
require_once dirname(__FILE__) . '/public_html/common/utils.php';
// Change to forms root in order satisfy relative imports
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

const FIELD_MODE_OVERWRITE = 0;
const FIELD_MODE_OVERWRITE_MARK = 1;
const FIELD_MODE_OPTIONAL = 2;
const FIELD_MODE_ONLY_NEW = 3;

global $script;
global $context;
global $show_sql;
global $db;
global $today;
global $errors;
global $verbose;
global $download_images;
global $convert_images;
global $lobbywatch_is_forms;

$show_sql = false;
$today = date('d.m.Y');

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

$script = array();
$script[] = "-- SQL script from ws.uid.admin.ch " . date("d.m.Y");

$errors = array();
$verbose = 0;

main();

function main() {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $db_connection;
  global $today;
  global $errors;
  global $verbose;
  global $env;

  $docRoot = "./public_html";
  $default_uid = 'CHE-107.810.911';

  print("$env: {$db_connection['database']}\n");

//     var_dump($argc); //number of arguments passed
//     var_dump($argv); //the arguments passed
  $options = getopt('hsv::u:tsmo:n::',array('docroot:','help', 'uid:', 'ssl'));

//    var_dump($options);

  if (isset($options['docroot'])) {
    $docRoot = $options['docroot'];
    print "DocRoot: $docRoot";
  }

  if (isset($options['v'])) {
    if ($options['v']) {
      $verbose = $options['v'];
    } else {
      $verbose = 1;
    }
     print("Verbose level: $verbose\n");
  }

  if (isset($options['n'])) {
    if ($options['n']) {
      $records_limit = $options['n'];
    } else {
      $records_limit = 10;
    }
    print("Records limit: $records_limit\n");
  } else {
    $records_limit = null;
  }

  if (isset($options['t'])) {
     $test_mode = true;
     print("WS Test mode enabled\n");
  } else {
     $test_mode = false;
  }

  if (isset($options['ssl'])) {
     $ssl = true;
     print("SSL enabled\n");
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
      show_ws_uid_data($uid, $ssl, $test_mode);
  } else if (isset($options['u'])) {
      $uid = $options['u'];
      if (!$uid) {
        $uid = $default_uid;
      }
      show_ws_uid_data($uid, $ssl, $test_mode);
  }

  if (isset($options['m'])) {
    migrate_old_hr_id_from_url($records_limit, $ssl, $test_mode);
  }

  if (isset($options['s'])) {
    print("\nSQL:\n");
    print(implode("\n", $script));
    print("\n");
  }
  if (isset($options['h']) || isset($options['help'])) {
    print("ws.parlament.ch Fetcher for Lobbywatch.ch.
Parameters:
-u UID, --uid UID   UID as 9-digit or CHE-000.000.000 string (default: $default_uid)
-o HR-ID        Search old HR-ID
-t              Call test service (default: production)
--ssl           Use SSL
-m              Migrate old hr-id to uid from handelsregister_url
-n number       Limit number of records
-s              Output SQL script
-v[level]       Verbose, optional level, 1 = default
-h, --help      This help
--docroot path  Set the document root for images
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

function show_ws_uid_data($uid, $ssl, $test_mode) {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $today;
  global $verbose;

//   ini_set('soap.wsdl_cache_enabled',0);
//   ini_set('soap.wsdl_cache_ttl',0);

  print("UID=$uid\n");
  $data = _lobbywatch_fetch_ws_uid_data($uid, $verbose, $ssl, $test_mode);
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
  global $verbose;

  $script[] = $comment = "\n-- Organisation migrate old HR-ID to UID from handelsregister URL";

  $sql = "SELECT id, name_de, handelsregister_url, uid, rechtsform_handelsregister FROM organisation ORDER BY id;"; //  WHERE handelsregister_url IS NOT NULL
  $stmt = $db->prepare($sql);

  $stmt->execute ( array() );
//   $organisation_list = $stmt->fetchAll(PDO::FETCH_CLASS);
//   $organisation_list = $stmt->fetchAll(PDO::FETCH_CLASS);

//   var_dump($parlamentarier_list_db);

  $data = initDataArray();
  $client = initSoapClient($data, $verbose, $ssl, $test_mode);

  $level = 0;
  $n_new_uid = 0;
  $n_different_uid= 0;
  $n_equal_uid = 0;
  $n_not_found = 0;
  $n_no_url = 0;
  $n_only_uid = 0;
  $n_fix_uid = 0;
  $n_bad_uid = 0;

  echo "\nMigrate old Handelsregister ID to UID\n";
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
    $matches = array();
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
      $script[] = $command = "-- DISABLED UPDATE organisation SET uid='$uid_ws', updated_visa='import' WHERE id = $id;";
      if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
      if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
    } else if ($hr_url && !$uid_ws) {
      $sign = '!';
      $n_not_found++;
    } else if ($uid_db && $uid_ws && $uid_db != $uid_ws) {
      $sign = '≠';
      $n_different_uid++;

      $script[] = $comment = "-- Update DIFFERENCE " . mb_substr($name, 0, 45) . ", id = $id, $old_hr_id → $uid_ws";
      $script[] = $command = "-- UPDATE organisation SET uid='$uid_ws', updated_visa='import' WHERE id = $id;";
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
        $script[] = $command = "UPDATE organisation SET uid='$uid_ws', updated_visa='import' WHERE id = $id;";
        if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
        if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
      } else if (!$uid_ws && $uid_db != $uid_ws) {
        $sign = 'X';
        $n_bad_uid++;
        $uid = null;
        $old_hr_id = $uid_db; // For logging set to other variable
        $script[] = $comment = "-- Delete BAD UID " . mb_substr($name, 0, 45) . ", id = $id, $uid_db → NULL";
        $script[] = $command = "UPDATE organisation SET uid=NULL, updated_visa='import' WHERE id = $id;";
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
        $data = _lobbywatch_fetch_ws_uid_data($uid, $verbose, $ssl, $test_mode);
        if ($data['success']) {
          $rechtsform_handelsregister = $data['data']['rechtsform_handelsregister'];
        }
      }
      if ($rechtsform_handelsregister && $rechtsform_handelsregister != $rechtsform_handelsregister_db) {
        $script[] = $comment = "-- Set rechtsform_handelsregister " . mb_substr($name, 0, 45) . ", id = $id, $uid, ";
        $script[] = $command = "UPDATE organisation SET rechtsform_handelsregister='$rechtsform_handelsregister', updated_visa='import' WHERE id = $id;";
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
  print("\n");
}

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
