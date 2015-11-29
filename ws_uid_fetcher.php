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
$script[] = "-- SQL script from handelsregister " . date("d.m.Y");

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
  $options = getopt('hsv::u:t',array('docroot:','help', 'uid:', 'ssl'));

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

  if (isset($options['uid'])) {
      $uid = $options['uid'];
  } else if (isset($options['u'])) {
      $uid = $options['u'];
  } else {
      $uid = $default_uid;
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

  if (isset($options['h']) || isset($options['help'])) {
    print("ws.parlament.ch Fetcher for Lobbywatch.ch.
Parameters:
-u UID, --uid UID   UID as 9-digit or CHE-000.000.000 string (default: $default_uid)
-t              Call test service (default: production)
--ssl           Use SSL
-v[level]       Verbose, optional level, 1 = default
-h, --help      This help
--docroot path  Set the document root for images
");
  }

  show_ws_uid_data($uid, $ssl, $test_mode);

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

  $data = _lobbywatch_fetch_ws_uid_data($uid, $verbose, $ssl, $test_mode);
  print_r($data);
}
