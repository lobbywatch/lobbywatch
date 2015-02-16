<?php

// Run: /opt/lampp/bin/php -f ws_parlament_fetcher.php

// TODO multipage handling
// TODO Datenquelle angeben
// TODO historized handlen
// TODO historized fragen

$kommission_ids = array();

// $url = 'http://ws.parlament.ch/committees?ids=1;2;3&mainOnly=false&permanentOnly=true&currentOnly=true&lang=de&pageNumber=1&format=xml';
$ws_parlament_url = 'http://ws.parlament.ch/committees?ids=1;2;3&format=json&lang=de';
// $url = 'http://lobbywatch.ch/de/data/interface/v1/json/table/branche/flat/id/1';

// $json = fopen($url, 'r');
// $json = file_get_contents($url);
// $json = new_get_file_contents($url);

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
$json = file_get_contents($ws_parlament_url, false, $context);

// $handle = @fopen($url, "r");
// if ($handle) {
//     while (($buffer = fgets($handle, 4096)) !== false) {
//         echo $buffer;
//     }
//     if (!feof($handle)) {
//         echo "Error: unexpected fgets() fail\n";
//     }
//     fclose($handle);
// }

var_dump($json);
$obj = json_decode($json);
var_dump($obj);

foreach($obj as $kommission) {
  $memberNames = '';
  foreach($kommission->members as $member) {
    $memberNames .= $member->lastName . ', ';
  }
  print('Kommission: ' . $kommission->id . ' ' . $kommission->abbreviation . ': ' . $memberNames . "\n");
}


// function to replace file_get_contents()
function new_get_file_contents($url) {
$ch = curl_init();
$timeout = 10; // set to zero for no timeout
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$file_contents = curl_exec($ch); // take out the spaces of curl statement!!
curl_close($ch);
return $file_contents;
}
