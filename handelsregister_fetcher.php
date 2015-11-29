<?php

require_once dirname(__FILE__) . '/public_html/settings/settings.php';
require_once dirname(__FILE__) . '/public_html/common/utils.php';
// Run: /opt/lampp/bin/php -f handelsregister_fetcher.php -- --uid 107810911

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

$show_sql = false;
$today = date('d.m.Y');

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
  global $today;
  global $errors;
  global $verbose;
  global $download_images;
  global $convert_images;
  global $env;
  global $db_connection;

  $docRoot = "./public_html";

  print("$env: {$db_connection['database']}\n");

//     var_dump($argc); //number of arguments passed
//     var_dump($argv); //the arguments passed
  $options = getopt('hsv::',array('docroot:','help', 'uid:'));

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
  }

  if (isset($options['uid'])) {
      $uid = $options['uid'];
  } else {
      $uid = '107810911';
  }

  if (isset($options['h']) || isset($options['help'])) {
    print("ws.parlament.ch Fetcher for Lobbywatch.ch.
Parameters:
-uid uid        UID (default: 107810911)
-s              Output SQL script
-v[level]       Verbose, optional level, 1 = default
-h, --help      This help
--docroot path  Set the document root for images
");
  }

  show_bfs_uid_data('www.uid-wse-a.admin.ch', $uid);

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

function show_bfs_uid_data($host, $uid_raw) {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $today;

      $matches = array();
    if (preg_match('/^CHE-(\d{3})\.(\d{3}).(\d{3})$/', $uid_raw, $matches)) {
      $uid = $matches[1] . $matches[2] . $matches[3];
    } else if (preg_match('/^(\d{9})$/', $uid_raw, $matches)) {
      $uid = $matches[1];
    } else {
      print( 'UID wrong format (9-digits or CHE-000.000.000): ' . $uid_raw);
      return;
    }

  // openssl pkcs12 -in rk_startcom_cert_bak.p12 -out roland_kurmann@yahoo.pem -nodes -clcerts

//   ini_set('soap.wsdl_cache_enabled',0);
//   ini_set('soap.wsdl_cache_ttl',0);

    $context = stream_context_create(array(
    // unsecure
//       'ssl' => array(
//     	  'verify_peer' => false,
//     	  'verify_peer_name' => false,
//     	  'allow_self_signed' => true,
//       ),
    // secure
      "ssl"=>array(
    	  "verify_peer"=>true,
    	  "allow_self_signed"=>false,
    	  "cafile"=> dirname(__FILE__) . "/public_html/settings/cacert.pem",
    	  "verify_depth"=>5,
    	  "peer_name"=>"$host",
    	  'disable_compression' => true,
    	  'SNI_enabled'         => true,
    	  'ciphers'             => 'ALL!EXPORT!EXPORT40!EXPORT56!aNULL!LOW!RC4',
//     	  'ciphers'             => 'ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:DHE-DSS-AES128-GCM-SHA256:kEDH+AESGCM:ECDHE-RSA-AES128-SHA256:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA:ECDHE-ECDSA-AES128-SHA:ECDHE-RSA-AES256-SHA384:ECDHE-ECDSA-AES256-SHA384:ECDHE-RSA-AES256-SHA:ECDHE-ECDSA-AES256-SHA:DHE-RSA-AES128-SHA256:DHE-RSA-AES128-SHA:DHE-DSS-AES128-SHA256:DHE-RSA-AES256-SHA256:DHE-DSS-AES256-SHA:DHE-RSA-AES256-SHA:AES128-GCM-SHA256:AES256-GCM-SHA384:ECDHE-RSA-RC4-SHA:ECDHE-ECDSA-RC4-SHA:AES128:AES256:RC4-SHA:HIGH:!aNULL:!eNULL:!EXPORT:!DES:!3DES:!MD5:!PSK',
      ),
      'http'=>array(
            'user_agent' => 'PHPSoapClient',
//             'user_agent' => 'Apache-HttpClient/4.1.1 (java 1.5)',
            ),
    ));


//   $wsdl = "https://www.uid-wse-a.admin.ch/V3.0/PublicServices.svc?wsdl";
  $wsdl = "https://$host/V3.0/PublicServices.svc?wsdl";
//   $wsdl = "PublicServices.svc.xml";
//   $wsdl = null;
  $soapParam = array(
    	  'stream_context' => $context,
//         "trace"         => 1,
//         "exceptions"    => true,
//          "local_cert"    => "/home/rkurmann/dev/sec/2015/rk.pem",
//         "uri"           => "urn:xmethods-delayed-quotes",
//         "style"         => SOAP_RPC,
//         "use"           => SOAP_ENCODED,
//         "soap_version"  => SOAP_1_1,
        "cache_wsdl"     => WSDL_CACHE_NONE,
//         "location"      => 'https://www.uid-wse-a.admin.ch/V3.0/PublicServices.svc',
//         "uri"    	    => 'http://www.uid.admin.ch/xmlns/uid-wse',
//     	"style"=>SOAP_DOCUMENT,
//     	"use"=>SOAP_LITERAL,
//     	"soap_version"=>SOAP_1_1,
    	"trace"=>true,
//     	"authentication"=>SOAP_AUTHENTICATION_DIGEST,
    	"exceptions"=>true,
//     	"local_cert"=>"certfile.p12",
//     	"passphrase"=>"passwordhere",
//     	"ssl_method"=>SOAP_SSL_METHOD_TLS,
//     	"features" => SOAP_SINGLE_ELEMENT_ARRAYS,
//     	"features" => SOAP_USE_XSI_ARRAY_TYPE,
    );

//     $xxx = file_get_contents('https://www.uid-wse-a.admin.ch/V3.0/PublicServices.svc?wsdl=wsdl0', false, $context);
//     $xxx = getSSLPage('https://www.uid-wse-a.admin.ch/V3.0/PublicServices.svc?wsdl=wsdl0', $context);
//     echo $xxx;

//     echo "END\n";

  /* Initialize webservice with your WSDL */
  $client = new SoapClient($wsdl, $soapParam);

//   var_dump($client->__getFunctions());

//   $uid = new GetByUID('CHE', '107810911');

  /* Set your parameters for the request */
  $params = array(
//     "uid" => $uid,
    "uid" => array('uidOrganisationIdCategorie' => 'CHE', 'uidOrganisationId' => $uid,),
//     "uid" => array('uidOrganisationIdCategorie' => 'CHE', 'uidOrganisationId' => '107810911',),
  );

  /*
  Parameter: uid
  Datentyp: eCH-0097:uidStructureType
      http://www.ech.ch/vechweb/page?p=dossier&documentNumber=eCH-0097&documentVersion=2.0
      http://www.ech.ch/alfresco/guestDownload/attach/workspace/SpacesStore/978ac878-a051-401d-b219-f6e540cadab5/STAN_d_REP_2015-11-26_eCH-0097_V2.0_Datenstandard%20Unternehmensidentifikation.pdf
  Beschreibung: UID des gesuchten Unternehmens

  Rückgabewert: eCH-0108:organisationType Array
      http://www.ech.ch/vechweb/page?p=dossier&documentNumber=eCH-0108&documentVersion=3.0
      http://www.ech.ch/alfresco/guestDownload/attach/workspace/SpacesStore/bc371174-261e-4152-9d60-3b5a4e79ce7b/STAN_d_DEF_2014-04-11_eCH-0108_V3.0_Unternehmens-Identifikationsregister.pdf

  Mögliche Fehlermeldungen:
  - Data_validation_failed
  - Request_limit_exceeded

  <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:uid="http://www.uid.admin.ch/xmlns/uid-wse" xmlns:ns="http://www.ech.ch/xmlns/eCH-0097-f/2">
   <soapenv:Header/>
   <soapenv:Body>
      <uid:GetByUID>
         <!--Optional:-->
         <uid:uid>
            <!--Optional:-->
            <ns:uidOrganisationIdCategorie>CHE</ns:uidOrganisationIdCategorie>
            <!--Optional:-->
            <ns:uidOrganisationId>107810911</ns:uidOrganisationId>
         </uid:uid>
      </uid:GetByUID>
   </soapenv:Body>
  </soapenv:Envelope>
  */

  /* Invoke webservice method with your parameters, in this case: Function1 */
  try {
//     $response = $client->__soapCall("GetByUID", array($params));
//     $response = $client->__soapCall("GetByUID", array('uid' => ));
//     $response = $client->__soapCall("GetByUID", $uid);
//     $response = $client->GetByUID(new GetByUID('CHE', '107810911'));
//     $response = $client->GetByUID('CHE', '107810911');
    $response = $client->GetByUID($params);
  } finally {
    print_r($client->__getLastRequestHeaders());
    print_r($client->__getLastRequest());
  }

  /* Print webservice response */
  print_r($response);

  print("-------------------\n");
  print("uid=$uid\n");
  print($response->GetByUIDResult->organisationType->organisation->organisationIdentification->organisationName . "\n");
}

function getSSLPage($url) {
    $ch = curl_init();
//     curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_SSLVERSION,3);
    $result = curl_exec($ch);
    echo curl_error($ch);
    curl_close($ch);
    return $result;
}
