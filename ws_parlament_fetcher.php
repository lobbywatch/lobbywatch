<?php
require_once dirname(__FILE__) . '/public_html/settings/settings.php';
require_once dirname(__FILE__) . '/public_html/common/utils.php';

/*
# ./deploy.sh -b -p
# ./run_local_db_script.sh lobbywatchtest prod_bak/`cat prod_bak/last_dbdump_data.txt`

./db_prod_to_local.sh lobbywatchtest
export SYNC_FILE=sql/ws_parlament_ch_sync_`date +"%Y%m%d"`.sql; php -f ws_parlament_fetcher.php -- -pks | tee $SYNC_FILE; less $SYNC_FILE
./run_local_db_script.sh lobbywatchtest $SYNC_FILE
./deploy.sh -r -s $SYNC_FILE
./deploy.sh -p -r -s $SYNC_FILE
*/

// mogrify -path public_html/files/parlamentarier_photos/gross -filter Lanczos -resize 150x211 public_html/files/parlamentarier_photos/original/*
// convert public_html/files/parlamentarier_photos/original/* -filter Lanczos -resize 150x211 public_html/files/parlamentarier_photos/gross
// convert '*.jpg' -resize 120x120 thumbnail%03d.png
// Ref: http://www.imagemagick.org/script/command-line-processing.php

// https://www.parlament.ch/centers/documents/de/kurzdokumentation-webservices-d.pdf

// TODO Change to new ws, currently using http://ws-old.parlament.ch/
// TODO multipage handling
// TODO Datenquelle angeben
// TODO historized handlen
// TODO historized fragen

// TODO parlamentarier kommissionen
// TODO parlamentarier ratsmitgliedschaft

// $kommission_ids = array();

// $url = 'http://ws-old.parlament.ch/committees?ids=1;2;3&mainOnly=false&permanentOnly=true&currentOnly=true&lang=de&pageNumber=1&format=xml';
// $url = 'http://lobbywatch.ch/de/data/interface/v1/json/table/branche/flat/id/1';

// $json = fopen($url, 'r');
// $json = file_get_contents($url);
// $json = new_get_file_contents($url);

const NEW_ID = 'LAST_INSERT_ID()';

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
$options = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>
      "Content-Type: application/json\r\n" .
      "Accept:application/json\r\n" .
//       "User-Agent: Mozilla/5.0\r\n" // i.e. Firefox 60
//       "User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0\r\n" // i.e. Firefox 60
      "User-Agent:LWAgent/1.0\r\n"
  )
);

$context = stream_context_create($options);



$script = array();
$script[] = "-- SQL script from ws.parlament.ch $transaction_date";

$errors = array();
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

  $docRoot = "./public_html";

//     var_dump($argc); //number of arguments passed
//     var_dump($argv); //the arguments passed
  $options = getopt('kphsv::dc',array('docroot:','db:','help'));

//   var_dump($options);

  if (isset($options['docroot'])) {
    $docRoot = $options['docroot'];
    print "-- DocRoot: $docRoot";
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

  if (isset($options['d'])) {
    $download_images = true;
    $convert_images = true;
  }

  if (isset($options['c'])) {
     $convert_images = true;
  }

  if (isset($options['k'])) {
    parlamentarierOhneBiografieID();
    syncKommissionen();
//     setImportDate();
  }

  if (isset($options['p'])) {
    parlamentarierOhneBiografieID();
    $img_path = "$docRoot/files/parlamentarier_photos";
    print "-- Image path: $img_path\n";
    syncParlamentarier($img_path);
    setImportDate();
  }

  if (isset($options['s'])) {
    print("\n-- SQL:\n");
    print(implode("\n", $script));
    print("\n");
  }
  if (isset($options['h']) || isset($options['help'])) {
    print("ws.parlament.ch Fetcher for Lobbywatch.ch.
Parameters:
-k              Sync Kommissionen
-p              Sync Parlamentarier
-s              Output SQL script
-v[level]       Verbose, optional level, 1 = default
-d              Download all images (implies -c)
-c              Convert all images
--db=db_name      Name of DB to use
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

function syncKommissionen() {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $today;
  global $sql_today;
  global $transaction_date;
  global $sql_transaction_date;
  global $user;

  $script[] = $comment = "\n-- Kommissionen $transaction_date";

  $sql = "SELECT kommission.id, kommission.abkuerzung, kommission.name, kommission.abkuerzung_fr, kommission.name_fr,  kommission.typ, kommission.art, kommission.parlament_id, kommission.mutter_kommission_id, 'NOK' as status FROM kommission kommission WHERE bis IS NULL;";
  $stmt = $db->prepare($sql);

  $stmt->execute ( array() );
  $kommissionen_db = $stmt->fetchAll(PDO::FETCH_CLASS);

  $level = 0;

  print("\n/*\nKommissionen $transaction_date\n");

  $new_kommission_count = 0;            // '+'
  $updated_kommission_count = 0;        // '≠'
  $deleted_kommission_count = 0;        // '~'
  $equal_kommission_count = 0;          // '='

  $equal_inkommission_count = 0;        // '='
  $new_inkommission_count = 0;          // '+'
  $new2_inkommission_count = 0;         // '&'
  $change_inkommission_count = 0;       // '≠'
  $terminated_inkommission_count = 0;   // '#'
  $deleted_inkommission_count = 0;      // '-'
  $duplicate_inkommission_count = 0;    // '*'
  $untracked_inkommission_count = 0;    // 'x'
  $error_inkommission_count = 0;        // '?'
//   $error_inkommission_count = 0;     // 'P'

  for($page = 1, $hasMorePages = true, $i = 0; $hasMorePages; $page++) {
    $ws_parlament_url = "http://ws-old.parlament.ch/committees?currentOnly=true&mainOnly=true&permanentOnly=true&format=json&lang=de&pageNumber=$page";
    $json = get_web_data($ws_parlament_url);

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

    // var_dump($json);
    $obj = json_decode($json);
    // var_dump($obj);

  //   $sql = "SELECT * FROM kommission kommission WHERE parlament_id = :kommission_parlament_id;";
  //   $stmt = $db->prepare($sql);

  $hasMorePages = false;
    print("Page: $page\n");
    foreach($obj as $kommission_ws) {
      if(property_exists($kommission_ws, 'hasMorePages')) {
        $hasMorePages = $kommission_ws->hasMorePages;
      }
      $i++;

  //     if ($i > 2) {
  //       print("Aborted i > x\n");
  //       return;
  //     }
  //     $stmt->execute ( array(':kommission_parlament_id' => "$kommission->id") );
  //     $res = $stmt->fetchAll(PDO::FETCH_CLASS);
  //     $kommission_db = getKommissionId($kommission->id);
  //     $ok = $kommission_db !== false;
  //     print_r($kommission_db);
      $kommission_db = search_objects($kommissionen_db, 'parlament_id', $kommission_ws->id);
      //         print("Search $member->id\n");
      //         print_r($db_member);

      $ws_parlament_url = "http://ws-old.parlament.ch/committees?ids=$kommission_ws->id&format=json&lang=fr&subcom=true&pageNumber=1";
      $json_fr = get_web_data($ws_parlament_url);
      $obj_fr = json_decode($json_fr);
      $kommission_fr = $obj_fr[0];

      $council = $kommission_ws->council;

      if ($ok = ($n = count($kommission_db)) == 1) {
        $kommission_db_obj = $kommission_db[0];
        $id = $kommission_db_obj->id;
        if ($kommission_db_obj->abkuerzung != $kommission_ws->abbreviation || $kommission_db_obj->abkuerzung_fr != $kommission_fr->abbreviation || $kommission_db_obj->name != $kommission_ws->name || $kommission_db_obj->name_fr != $kommission_fr->name) {
          $kommission_db_obj->status = 'UPDATED';
          $sign = '≠';
          $script[] = $comment = "-- Update Kommission $kommission_ws->abbreviation=$kommission_ws->name, id=$id";
          $script[] = $command = "UPDATE kommission SET abkuerzung='$kommission_ws->abbreviation', abkuerzung_fr='$kommission_fr->abbreviation', name='$kommission_ws->name', name_fr='". escape_string($kommission_fr->name) . "', updated_visa='import', updated_date=$sql_transaction_date, notizen=CONCAT_WS('\\n\\n', '$today/$user: Update via ws.parlament.ch',`notizen`) WHERE id=$id;";

          // TODO update more kommissions fields
          //rat_id, typ, parlament_id, parlament_committee_number, parlament_subcommittee_number, parlament_type_code, von, created_visa, created_date, , notizen) VALUES (, , " . getRatId($council->type) . ", 'kommission', $kommission_ws->id, $kommission_ws->committeeNumber, NULL, $kommission_ws->typeCode, $sql_today, 'import', $sql_today, 'import', '$today/$user: Kommission importiert von ws.parlament.ch');";
          if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
          if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
          $updated_kommission_count++;
        } else {
          $kommission_db_obj->status = 'OK';
          $sign = '=';
          $equal_kommission_count++;
        }
      } else if ($n > 1) {
        $sign = '*';
        // Duplicate
      } else {
        $sign = '+';
        $script[] = $comment = "-- New Kommission $kommission_ws->abbreviation=$kommission_ws->name";
        $script[] = $command = "-- INSERT INTO kommission (abkuerzung, abkuerzung_fr, name, name_fr, rat_id, typ, parlament_id, parlament_committee_number, parlament_subcommittee_number, parlament_type_code, von, created_visa, created_date, updated_visa, updated_date, notizen) VALUES ('$kommission_ws->abbreviation', '$kommission_fr->abbreviation', '$kommission_ws->name', '". escape_string($kommission_fr->name) . "', " . getRatId($council->type) . ", 'kommission', $kommission_ws->id, $kommission_ws->committeeNumber, NULL, $kommission_ws->typeCode, $sql_today, 'import', $sql_transaction_date, 'import', $sql_transaction_date, '$today/$user: Kommission importiert von ws.parlament.ch');";
        if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
        if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
        $new_kommission_count++;
      }

      $council = $kommission_ws->council;
      print(str_repeat("\t", $level) . "$i. $sign Kommission: $kommission_ws->id $kommission_ws->abbreviation=$kommission_ws->name, $council->abbreviation, $kommission_ws->typeCode" . ($ok ? ", id=$kommission_db_obj->id" : '') . "\n");
      $inkommission_counts = show_members(array($kommission_ws->id), $level + 1);

      $equal_inkommission_count      += $inkommission_counts['equal_inkommission_count'];        // '='
      $new_inkommission_count        += $inkommission_counts['new_inkommission_count'];          // '+'
      $new2_inkommission_count       += $inkommission_counts['new2_inkommission_count'];         // '&'
      $change_inkommission_count     += $inkommission_counts['change_inkommission_count'];       // '≠'
      $terminated_inkommission_count += $inkommission_counts['terminated_inkommission_count'];   // '#'
      $deleted_inkommission_count    += $inkommission_counts['deleted_inkommission_count'];      // '-'
      $duplicate_inkommission_count  += $inkommission_counts['duplicate_inkommission_count'];    // '*'
      $untracked_inkommission_count  += $inkommission_counts['untracked_inkommission_count'];    // 'x'
      $error_inkommission_count      += $inkommission_counts['error_inkommission_count'];        // '?'
    }

    $db_kommissionen_NOK_in_DB = search_objects($kommissionen_db, 'status', 'NOK');
    foreach($db_kommissionen_NOK_in_DB as $kommission_NOK_in_DB) {
      print(str_repeat("\t", $level) . "    - Kommission: pid=$kommission_NOK_in_DB->parlament_id $kommission_NOK_in_DB->abkuerzung=$kommission_NOK_in_DB->name, id=$kommission_NOK_in_DB->id\n");

      $script[] = $comment = "-- Historize old Kommission $kommission_NOK_in_DB->abkuerzung=$kommission_NOK_in_DB->name, id=$kommission_NOK_in_DB->id";
      $script[] = $command = "UPDATE kommission SET bis=$sql_today, updated_visa='import', updated_date=$sql_transaction_date, notizen=CONCAT_WS('\\n\\n', '$today/$user: Kommission nicht mehr aktiv auf ws.parlament.ch',`notizen`) WHERE id=$kommission_NOK_in_DB->id;";
      if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
      if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");

      $script[] = $comment = "-- Not in_kommission anymore (outdated kommission) $kommission_NOK_in_DB->abkuerzung=$kommission_NOK_in_DB->name, id=$kommission_NOK_in_DB->id";
      $script[] = $command = "UPDATE in_kommission SET bis=$sql_today, updated_visa='import', updated_date=$sql_transaction_date, notizen=CONCAT_WS('\\n\\n', '$today/$user: Kommission nicht mehr aktiv auf ws.parlament.ch',`notizen`) WHERE kommission_id=$kommission_NOK_in_DB->id;";
      if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
      if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
      $deleted_kommission_count++;
    }
  }

  print("\nKommissionen:");
  print("\n = : $equal_kommission_count unverändert");
  print("\n + : $new_kommission_count neu");
  print("\n ≠ : $updated_kommission_count updated");
  print("\n ~ : $deleted_kommission_count gelöscht");

  print("\n\nInKommission:");
  print("\n = : $equal_inkommission_count unverändert");        // '='
  print("\n + : $new_inkommission_count neu");                  // '+'
  print("\n & : $new2_inkommission_count neu2");                // '&'
  print("\n ≠ : $change_inkommission_count aktualisiert");      // '≠'
  print("\n # : $terminated_inkommission_count terminiert");    // '#'
  print("\n - : $deleted_inkommission_count gelöscht");         // '-'
  print("\n * : $duplicate_inkommission_count doppelt");        // '*'
  print("\n x : $untracked_inkommission_count nicht in DB");    // 'x'
  print("\n ? : $error_inkommission_count Fehler");             // '?'
//   print("\nP: $error_inkommission_count");                 // 'P'

  print("\n*/\n");
  print($new_kommission_count > 0 ? "\n\n-- KOMMISSION ADDED" : '');
  print("\n-- KOMMISSION " . ($new_kommission_count + $updated_kommission_count + $deleted_kommission_count + $new_inkommission_count + $new2_inkommission_count + $change_inkommission_count + $terminated_inkommission_count + $deleted_inkommission_count + $duplicate_inkommission_count > 0 ? 'DATA CHANGED' : 'DATA UNCHANGED') . "\n\n");
}

function syncParlamentarier($img_path) {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $today;
  global $sql_today;
  global $transaction_date;
  global $sql_transaction_date;
  global $user;

  $script[] = $comment = "\n-- Parlamentarier $transaction_date";

  $sql = "SELECT id, parlament_biografie_id, 'NOK' as status, nachname, vorname, parlament_number, titel, aemter, weitere_aemter, kleinbild, kanton_id, rat_id, fraktion_id, fraktionsfunktion, partei_id, geburtstag, sprache, arbeitssprache, geschlecht, anzahl_kinder, zivilstand, beruf, militaerischer_grad_id, im_rat_seit, im_rat_bis, ratsunterbruch_von, ratsunterbruch_bis, ratswechsel, homepage, homepage_2, email, telephon_1, telephon_2, adresse_ort, adresse_strasse, adresse_plz, adresse_firma, parlament_interessenbindungen, parlament_interessenbindungen_json, parlament_interessenbindungen_updated FROM parlamentarier ORDER BY nachname, vorname;";
  $stmt = $db->prepare($sql);

  $stmt->execute ( array() );
  $parlamentarier_list_db = $stmt->fetchAll(PDO::FETCH_CLASS);

//   var_dump($parlamentarier_list_db);

  $level = 0;
  $new_parlamentarier_count = 0;
  $updated_parlamentarier_count = 0;
  $deleted_parlamentarier_count = 0;
  $modified_parlamentarier_count = 0;
  $delta = [];

  echo "\n/*\nActive Parlamentarier on ws.parlament.ch $transaction_date\n";
  for($page = 1, $hasMorePages = true, $i = 0; $hasMorePages; $page++) {
    $ws_parlament_url = "http://ws-old.parlament.ch/councillors/basicdetails?format=json&lang=de&pageNumber=$page";
//     $ws_parlament_url = "http://ws-old.parlament.ch/councillors/historic?legislativePeriodFromFilter=50&format=json&lang=de&pageNumber=$page";
    $json = get_web_data($ws_parlament_url);

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

    // var_dump($json);
    $obj = json_decode($json);
//     var_dump($obj);

    $hasMorePages = false;
    print("Page: $page\n");
    foreach($obj as $parlamentarier_short_ws) {
      if(property_exists($parlamentarier_short_ws, 'hasMorePages')) {
        $hasMorePages = $parlamentarier_short_ws->hasMorePages;
      }
      $i++;

  //     if ($i > 2) {
  //       print("Aborted i > x\n");
  //       return;
  //     }
  //     $stmt->execute ( array(':kommission_parlament_id' => "$kommission->id") );
  //     $res = $stmt->fetchAll(PDO::FETCH_CLASS);
  //     $kommission_db = getKommissionId($kommission->id);
  //     $ok = $kommission_db !== false;
  //     print_r($kommission_db);
      $biografie_id = $parlamentarier_short_ws->id;
      $parlamentarier_db = search_objects($parlamentarier_list_db, 'parlament_biografie_id', $biografie_id);
      //         print("Search $member->id\n");
      //         print_r($db_member);

      $sign = '!';
      $update = array();
      $update_optional = array();
      $fields = array();
      if ($ok = ($n = count($parlamentarier_db)) == 0) {

        $id = findIDOfParlamentarierWithoutBiografieIDByName($parlamentarier_short_ws->lastName);
        if ($id) {
          $sign = '≠';
          $parlamentarier_db = search_objects($parlamentarier_list_db, 'id', $id);
          $script[] = $comment = "-- Update parlamentarier $parlamentarier_short_ws->lastName, $parlamentarier_short_ws->firstName, biografie_id=$biografie_id, id=$id";
          $script[] = $command = "UPDATE parlamentarier SET parlament_biografie_id=$biografie_id, updated_visa='import', updated_date=$sql_transaction_date WHERE id = $id;";
          if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
          if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
        } else {
          $sign = '+';
//           print_r($parlamentarier_short_ws);
          $number = empty($parlamentarier_short_ws->number) ? 'NULL' : $parlamentarier_short_ws->number;
          $script[] = $comment = "-- Insert parlamentarier $parlamentarier_short_ws->lastName, $parlamentarier_short_ws->firstName, biografie_id=$biografie_id";
          $script[] = $command = "INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, updated_date, notizen) VALUES ($biografie_id, $number, '" . escape_string($parlamentarier_short_ws->lastName) . "', '" . escape_string($parlamentarier_short_ws->firstName) . "', " . getRatId($parlamentarier_short_ws->council) . ", " . getKantonId($parlamentarier_short_ws->canton /* wrong in ws.parlament.ch $parlamentarier_ws*/) . ", STR_TO_DATE('" . (isset($parlamentarier_short_ws->membership->entryDate) ? date('d.m.Y', strtotime( $parlamentarier_short_ws->membership->entryDate)) : $today) . "','%d.%m.%Y'), 'import', $sql_transaction_date, 'import', $sql_transaction_date, '$today/$user: Import von ws.parlament.ch');";
          if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
          if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
        }
      }

      if ($ok |= ($n = count($parlamentarier_db)) == 1) {
        if ($sign != '+') {
          $parlamentarier_db_obj = $parlamentarier_db[0];
          $parlamentarier_db_obj->status = 'OK';
          $id = $parlamentarier_db_obj->id;
        } else {
          $parlamentarier_db_obj = null;
          $id = NEW_ID;
        }

        updateParlamentarierFields($id, $biografie_id, $parlamentarier_db_obj, $update, $update_optional, $fields, $sign, $img_path, $delta);
      }

      if ($n > 1) {
        $sign = '*';
        // Duplicate
      } // else == 0 already handled

      switch ($sign) {
		case '+': $new_parlamentarier_count++; break;
		case '≠': $updated_parlamentarier_count++; break;
		case '~': $modified_parlamentarier_count++; break;
      }

      print(str_repeat("\t", $level) . str_pad($i, 3, " ", STR_PAD_LEFT) . mb_str_pad("| $sign | $parlamentarier_short_ws->lastName, $parlamentarier_short_ws->firstName |$parlamentarier_short_ws->council|$parlamentarier_short_ws->id" . ($ok ? "| id=$id" : ''), 53, " ") . "| " . implode(" | ", $fields) . "\n");
    }
  }

  print("\n + : $new_parlamentarier_count");
  print("\n ≠ : $updated_parlamentarier_count");
  print("\n ~ : $modified_parlamentarier_count");

  print($new_parlamentarier_count > 0 ? "\n\n-- PARLAMENTARIER ADDED" : '');
  print("\n\n-- PARLAMENTARIER " . ($new_parlamentarier_count + $updated_parlamentarier_count + $deleted_parlamentarier_count + $modified_parlamentarier_count > 0 ? 'DATA CHANGED' : 'DATA UNCHANGED') . "\n\n");

  $new_parlamentarier_count = 0;
  $updated_parlamentarier_count = 0;
  $deleted_parlamentarier_count = 0;
  $modified_parlamentarier_count = 0;

  print("\n\nRetired Parlamentarier in DB\n");

  $sign = '-';
  $i = 0;
  $parlamentarier_inactive_list = search_objects($parlamentarier_list_db, 'status', 'NOK');
  foreach($parlamentarier_inactive_list as $parlamentarier_inactive) {
    $i++;
    $id = $parlamentarier_inactive->id;
    $sign = '!';

    $update = array();
    $update_optional = array();
    $fields = array();
    if ($biografie_id = $parlamentarier_inactive->parlament_biografie_id) {
      updateParlamentarierFields($id, $biografie_id, $parlamentarier_inactive, $update, $update_optional, $fields, $sign, $img_path, $delta);
    } else {
      $biografie_id = 'null';
    }

    switch ($sign) {
      case '+': $new_parlamentarier_count++; break;
      case '≠': $updated_parlamentarier_count++; break;
      case '-': $deleted_parlamentarier_count++; break;
      case '~': $modified_parlamentarier_count++; break;
    }

    print(str_repeat("\t", $level) . str_pad($i, 3, " ", STR_PAD_LEFT) . mb_str_pad("| $sign | $parlamentarier_inactive->nachname, $parlamentarier_inactive->vorname |$biografie_id" . ($ok ? "| id=$id" : ''), 50, " ") . "| " . implode(" | ", $fields) . "\n");
  }

  print("\n + : $new_parlamentarier_count");
  print("\n ≠ : $updated_parlamentarier_count");
  print("\n - : $deleted_parlamentarier_count");
  print("\n ~ : $modified_parlamentarier_count");

  print("\n\nGeänderte Intressenbindungen der Parlamentarier:\n\n");
  print(implode("\n", $delta));

  print("\n\n*/\n");
  print("\n\n-- RETIRED " . ($new_parlamentarier_count + $updated_parlamentarier_count + $deleted_parlamentarier_count + $modified_parlamentarier_count > 0 ? 'DATA CHANGED' : 'DATA UNCHANGED') . "\n\n");
}

function updateParlamentarierFields($id, $biografie_id, $parlamentarier_db_obj, &$update, &$update_optional, &$fields, &$sign, $img_path, &$delta) {
  global $script;
  global $context;
  global $show_sql;
  global $db;
  global $today;
  global $sql_today;
  global $transaction_date;
  global $sql_transaction_date;
  global $download_images;
  global $convert_images;
  global $verbose;
  global $user;

  if ($verbose >= 9) {
    $max_output_length = 100000;
  } else if ($verbose > 5) {
    $max_output_length = 1000;
  } else if ($verbose > 2) {
    $max_output_length = 100;
  } else if ($verbose > 1) {
    $max_output_length = 25;
  } else {
    $max_output_length = 10;
  }


  // TODO repeal and replace ws-old.parlament.ch, see https://www.parlament.ch/de/services/open-data-webservices
  // The new services should be available end of 2018
  $ws_parlament_url = "http://ws-old.parlament.ch/councillors/$biografie_id?format=json&lang=de";
  $json = get_web_data($ws_parlament_url);
  $parlamentarier_ws = json_decode($json);

//   print_r($parlamentarier_ws);
  //         var_dump($parlamentarier_ws);
  //         exit(0);

  $different_db_values = false;

  $different_db_values |= checkField('parlament_number', 'number', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE);

  $field = 'kleinbild';
  $val = "$parlamentarier_ws->number.jpg";
  //         if ($parlamentarier_db_obj->$field == 'leer.png') {
  if (empty($parlamentarier_db_obj->$field) || $parlamentarier_db_obj->$field != $val || $convert_images || $download_images) {
    $filename = "$val";

    if (empty($parlamentarier_db_obj->$field) || $parlamentarier_db_obj->$field != $val) {
      $update[] = "$field = '" . escape_string($filename) . "'";
      $msg = ($verbose ? " (" . (isset($parlamentarier_db_obj->$field) ? cut($parlamentarier_db_obj->$field, $max_output_length) . " → " : '') . (isset($val) ? cut($val, $max_output_length) : 'null') .  ")" : '');
      $fields[] = "$field $msg";
   }

    if ($download_images || $id === NEW_ID) {
      // http://stackoverflow.com/questions/9801471/download-image-from-url-using-php-code
      //           $img = "$kleinbild_path/$filename";
//       $url = "https://www.parlament.ch/SiteCollectionImages/profil/klein/$filename";
//       $img = "$img_path/klein/$filename";
//       file_put_contents($img, file_get_contents($url));

//       $url = "https://www.parlament.ch/SiteCollectionImages/profil/gross/$filename";
//       $img = "$img_path/mittel/$filename";
//       file_put_contents($img, file_get_contents($url));

      $url = "https://www.parlament.ch/SiteCollectionImages/profil/original/$filename";
      $img = "$img_path/original/$filename";
      create_parent_dir_if_not_exists($img);
//       $img_content = @file_get_contents($url);
      $img_content = @get_web_data($url);
      if ($img_content ==! FALSE) {
        file_put_contents($img, $img_content);
      } else {
        print("Warning: Image $url does not exist\n");
        $fields[] = "**originalImageMissing(download)** ";
      }

      // Does not exist anymore
//       $url = "http://www.parlament.ch/SiteCollectionImages/profil/225x225/$filename";
//       $img = "$img_path/225x225/$filename";
//       file_put_contents($img, file_get_contents($url));

      $url = "https://www.parlament.ch/SiteCollectionImages/profil/portrait-260/$filename";
      $img = "$img_path/portrait-260/$filename";
      create_parent_dir_if_not_exists($img);
      file_put_contents($img, get_web_data($url));

      $fields[] = "downloadImage ";
    }

    if ($convert_images || $download_images || $id === NEW_ID) {

      $filename = "$val";
      $img = "$img_path/gross/$filename";
      create_parent_dir_if_not_exists($img);
      if (file_exists("$img_path/original/$filename") && filesize("$img_path/original/$filename") > 0) {
        exec("convert $img_path/original/$filename -filter Lanczos -resize 150x211 -quality 90 $img");
      } else {
        exec("convert $img_path/portrait-260/$filename -filter Lanczos -resize 150x211 -quality 90 $img");
        $fields[] = "**originalImageMissing(convert)** ";
      }

      // Imagemagick is not available in XAMPP
  //     $image = new Imagick("$img_path/original/$filename");

  //     $originalWidth = $imagick->getImageWidth();
  //     $originalHeight = $imagick->getImageHeight();

  //     $newWidth = 150;
  //     $newHeight = $originalHeight / $originalWidth * $newWidth; // proportional
  //     $image->resizeImage($newHeight, $newWidth, Imagick::FILTER_LANCZOS, 1);
  //     $image->writeImage("$img_path/gross/$filename");

  //     $image->destroy();
      $fields[] = "convertImage ";
    }
  }

  // ----------------------------------------------------------
  // DO NOT FORGET TO ADD NEW DB FIELDS TO SELECT IN syncParlamentarier()
  // ----------------------------------------------------------

  // TODO Use max ID in membership
//   print_r($parlamentarier_ws);
  $terminated = false;
  if (!$parlamentarier_ws->active && strtotime($parlamentarier_ws->councilMemberships[count($parlamentarier_ws->councilMemberships) - 1]->entryDate) < time()) {
    $different_db_values |= checkField('im_rat_bis', 'active', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE /*FIELD_MODE_ONLY_NEW*/, 'getImRatBis');
    $terminated = true;
  } else if ($parlamentarier_ws->active && isset($parlamentarier_db_obj->im_rat_bis) && strtotime($parlamentarier_db_obj->im_rat_bis) < time()) {
    $fields[] = '!im_rat_bis!';
    // Fix wrong im_rat_bis dates of active parlamentarier
//     $different_db_values |= checkField('im_rat_bis', 'active', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE /*FIELD_MODE_ONLY_NEW*/, 'getImRatBis');
    add_field_to_update($parlamentarier_db_obj, 'im_rat_bis', null, $update, null /*'updated_date'*/);
  }
  $different_db_values |= checkField('im_rat_seit', 'councilMemberships', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'getImRatSeit');
  $different_db_values |= checkField('ratsunterbruch_von', 'councilMemberships', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'getRatsunterbruchVon');
  $different_db_values |= checkField('ratsunterbruch_bis', 'councilMemberships', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'getRatsunterbruchBis');
  $different_db_values |= checkField('ratswechsel', 'councilMemberships', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'getRatswechsel');
  $different_db_values |= checkField('rat_id', 'council', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE_MARK, 'getRatId');
  $different_db_values |= checkField('homepage', 'homepagePrivate', $parlamentarier_db_obj, $parlamentarier_ws->contact ?? (object)[], $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'convertURL'); // the last wins
  $different_db_values |= checkField('homepage' . (!empty($parlamentarier_ws->contact->homepagePrivate) ? '_2' : ''), 'homepageWork', $parlamentarier_db_obj, $parlamentarier_ws->contact ?? (object)[], $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'convertURL'); // the last wins
  $different_db_values |= checkField('email', 'emailWork', $parlamentarier_db_obj, $parlamentarier_ws->contact ?? (object)[], $update, $update_optional, $fields, FIELD_MODE_ONLY_NEW, 'transformDashToNull'); // the last wins // TODO check ignore
  $different_db_values |= checkField('email', 'emailPrivate', $parlamentarier_db_obj, $parlamentarier_ws->contact ?? (object)[], $update, $update_optional, $fields, FIELD_MODE_OVERWRITE_MARK, 'transformDashToNull'); // the last wins // TODO check
  $different_db_values |= checkField('telephon_1', 'phonePrivate', $parlamentarier_db_obj, $parlamentarier_ws->contact ?? (object)[], $update, $update_optional, $fields, FIELD_MODE_ONLY_NEW, 'transformDashToNull'); // the last wins
  $different_db_values |= checkField('telephon_1', 'phoneWork', $parlamentarier_db_obj, $parlamentarier_ws->contact ?? (object)[], $update, $update_optional, $fields, FIELD_MODE_OVERWRITE_MARK, 'transformDashToNull'); // the last wins // TODO check
  $different_db_values |= checkField('telephon_2', 'phoneMobilePrivate', $parlamentarier_db_obj, $parlamentarier_ws->contact ?? (object)[], $update, $update_optional, $fields, FIELD_MODE_ONLY_NEW, 'transformDashToNull'); // the last wins
  $different_db_values |= checkField('telephon_2', 'phoneMobileWork', $parlamentarier_db_obj, $parlamentarier_ws->contact ?? (object)[], $update, $update_optional, $fields, FIELD_MODE_OVERWRITE_MARK, 'transformDashToNull'); // the last wins
  $different_db_values |= checkField('titel', 'title', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE);
  $different_db_values |= checkField('sprache', 'language', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'checkSprache');
  $different_db_values |= checkField('nachname', 'lastName', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE_MARK_LOG);
  $different_db_values |= checkField('vorname', 'firstName', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_ONLY_NEW); // TODO consider using FIELD_MODE_OVERWRITE_MARK_LOG
  $different_db_values |= checkField('kanton_id', 'cantonName', $parlamentarier_db_obj, $parlamentarier_ws/*$parlamentarier_ws->cantonName*/ /*$parlamentarier_short_ws->canton*/ /* wrong in ws.parlament.ch $parlamentarier_ws*/, $update, $update_optional, $fields, FIELD_MODE_OPTIONAL, 'getKantonId');
  $different_db_values |= checkField('fraktion_id', 'faction', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE_MARK, 'getFraktionId');
  $different_db_values |= checkField('fraktionsfunktion', 'function', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'getFraktionFunktion');
  $different_db_values |= checkField('partei_id', 'party', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE_NULL, 'getParteiId');
  $different_db_values |= checkField('geburtstag', 'birthDate', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE_MARK);
  $different_db_values |= checkField('arbeitssprache', 'workLanguage', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE);
  $different_db_values |= checkField('geschlecht', 'gender', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE_MARK, 'convertGeschlecht');
  $different_db_values |= checkField('anzahl_kinder', 'numberOfChildren', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE);
  $different_db_values |= checkField('zivilstand', 'maritalStatus', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'convertZivilstand');
  $different_db_values |= checkField('beruf', 'professions', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_ONLY_NEW);
  $different_db_values |= checkField('militaerischer_grad_id', 'militaryGrade', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'getMilGradId');
  $domicileFound = !empty($parlamentarier_ws->domicile->city);
  $postalAddressFound = !empty($parlamentarier_ws->postalAddress->city);
  if ($domicileFound) {
    $different_db_values |= checkField('adresse_firma', 'company', $parlamentarier_db_obj, $parlamentarier_ws->domicile, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE); // the last wins
    $different_db_values |= checkField('adresse_strasse', 'addressLine', $parlamentarier_db_obj, $parlamentarier_ws->domicile, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE); // the last wins
    $different_db_values |= checkField('adresse_plz', 'zip', $parlamentarier_db_obj, $parlamentarier_ws->domicile, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE); // the last wins
    $different_db_values |= $domicileFound = checkField('adresse_ort', 'city', $parlamentarier_db_obj, $parlamentarier_ws->domicile, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE); // the last wins
  } else if ($postalAddressFound) {
    $different_db_values |= checkField('adresse_firma', 'company', $parlamentarier_db_obj, $parlamentarier_ws->postalAddress, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE); // the last wins
    $different_db_values |= checkField('adresse_ort', 'city', $parlamentarier_db_obj, $parlamentarier_ws->postalAddress, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE); // the last wins
    $different_db_values |= checkField('adresse_strasse', 'addressLine', $parlamentarier_db_obj, $parlamentarier_ws->postalAddress, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE); // the last wins
    $different_db_values |= checkField('adresse_plz', 'zip', $parlamentarier_db_obj, $parlamentarier_ws->postalAddress, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE); // the last wins
  } else {
    $fields[] = '{no address}';
  }
  $different_db_values |= checkField('aemter', 'mandate', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE);
  $different_db_values |= checkField('weitere_aemter', 'additionalMandate', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE);

  $different_db_values |= $ib_changed = checkField('parlament_interessenbindungen', 'concerns', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'getParlamentInteressenbindungen', 'parlament_interessenbindungen_updated', 'normalizeParlamentInteressenbindungen');
  $different_db_values |= $ib_json_changed = checkField('parlament_interessenbindungen_json', 'concerns', $parlamentarier_db_obj, $parlamentarier_ws, $update, $update_optional, $fields, FIELD_MODE_OVERWRITE, 'getParlamentInteressenbindungenJson', 'parlament_interessenbindungen_updated', 'decodeJson');

  if ($ib_changed) {
    // print("<p>p<ins>ins</ins><del>del</del><i>i</i><b>b</b><mark>mark</mark><s>s</s></p>");
    $old_ib_html = $parlamentarier_db_obj->parlament_interessenbindungen;
    $old_ib_html_normalized = normalizeParlamentInteressenbindungen($old_ib_html);
    $new_ib_html = getParlamentInteressenbindungen($parlamentarier_ws->concerns);
    $diff = htmlDiffStyled($old_ib_html_normalized, $new_ib_html);
    // $old_json = json_encode($old_ib_html);
    // $old_json_normalized = json_encode($old_ib_html_normalized);
    // $new_json = json_encode($new_ib_html);
    // $delta[] = "\n\nOLD:\n$old_ib_html\n$old_json\nNORMALIZED:\n$old_ib_html_normalized\n$old_json_normalized\nNEW:\n$new_ib_html\n$new_json\n\n";
    // $delta[] = "\n\nOLD:\n$old_ib_html\nNORMALIZED:\n$old_ib_html_normalized\nNEW:\n$new_ib_html\n\n";
    $delta[] = "Geänderte Interessenbingungen bei <b>$parlamentarier_ws->lastName, $parlamentarier_ws->firstName</b>, biografie_id=$biografie_id, id=$id:\n$diff\n";
  }

  // ----------------------------------------------------------
  // DO NOT FORGET TO ADD NEW DB FIELDS TO SELECT IN syncParlamentarier()
  // ----------------------------------------------------------


  if (count($update) > 0) {
    $script[] = $comment = "-- Update Parlamentarier $parlamentarier_ws->lastName, $parlamentarier_ws->firstName, id=$id, fields: " . implode(", ", $fields);
    $script[] = $command = "UPDATE `parlamentarier` SET " . implode(", ", $update) . ", updated_visa='import', updated_date=$sql_transaction_date, notizen=CONCAT_WS('\\n\\n', '$today/$user: Update via ws.parlament.ch',`notizen`) WHERE id=$id;";
    if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
    if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
  }

  if (count($update_optional) > 0) {
    $script[] = $comment = "-- Optional update Parlamentarier $parlamentarier_ws->lastName, $parlamentarier_ws->firstName, id=$id, fields: " . implode(", ", $fields);
    $script[] = $command = "-- UPDATE `parlamentarier` SET " . implode(", ", $update_optional) . ", updated_visa='import', updated_date=$sql_transaction_date, notizen=CONCAT_WS('\\n\\n', '$today/$user: Update via ws.parlament.ch',`notizen`) WHERE id=$id;";
    if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
    if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
  }

  if (count($update) > 0) {
    if ($sign == '!' && $terminated) {
      $sign = '+';
    } else if ($sign == '!') {
      $sign = '≠';
    }
  } else if ($different_db_values) {
    $sign = '~';
  } else {
    $sign = '=';
  }

  return $sign;
}

function show_members(array $ids, $level = 1) {
  global $db;
  global $script;
  global $context;
  global $show_sql;
  global $today;
  global $sql_today;
  global $transaction_date;
  global $sql_transaction_date;
  global $user;

  $ids_str = implode(';', $ids);

  $sql = "SELECT parlamentarier.id, parlamentarier.name, parlamentarier.anzeige_name, parlamentarier.parlament_biografie_id, kommission.abkuerzung, kommission.name as kommission_name, kommission.typ, kommission.art, kommission.parlament_id, kommission.mutter_kommission_id, in_kommission.parlament_committee_function, in_kommission.parlament_committee_function_name, 'NOK' as status, in_kommission.id as in_kommission_id, in_kommission.kommission_id FROM v_kommission kommission JOIN v_in_kommission in_kommission ON in_kommission.kommission_id = kommission.id JOIN v_parlamentarier_simple parlamentarier ON in_kommission.parlamentarier_id = parlamentarier.id WHERE in_kommission.bis IS NULL AND kommission.parlament_id = :kommission_parlament_id;"; // AND parlamentarier.parlament_biografie_id = :parlamentarier_parlament_id AND parlamentarier.im_rat_bis IS NULL
  $stmt = $db->prepare($sql);

  $equal_subkommission_count = 0;       // '='
  $untracked_subkommission_count = 0;   // 'x'

  $equal_inkommission_count = 0;        // '='
  $new_inkommission_count = 0;          // '+'
  $new2_inkommission_count = 0;         // '&'
  $change_inkommission_count = 0;       // '≠'
  $terminated_inkommission_count = 0;   // '#'
  $deleted_inkommission_count = 0;      // '-'
  $duplicate_inkommission_count = 0;    // '*'
  $untracked_inkommission_count = 0;    // 'x'
//   $error_inkommission_count = 0;     // 'P'
  $error_inkommission_count = 0;        // '?'

  for($page = 1, $hasMorePages = true, $i = 0, $j = 0; $hasMorePages; $page++) {
    $ws_parlament_url = "http://ws-old.parlament.ch/committees?ids=$ids_str&format=json&lang=de&subcom=true&pageNumber=$page";
    $json = get_web_data($ws_parlament_url);

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

    // var_dump($json);
    $obj = json_decode($json);

    // https://stackoverflow.com/questions/2630013/invalid-argument-supplied-for-foreach
    // if (is_array($values) || is_object($values))

    // var_dump($obj);

    $hasMorePages = false;
//     print("Mitgliederpage: $page\n");
    foreach($obj as $kommission) {
      if(property_exists($kommission, 'hasMorePages')) {
        $hasMorePages = $kommission->hasMorePages;
      }
      $i++;

      $kommission_db = getKommissionId($kommission->id);
      $kommission_db_ok = $kommission_db !== false;
      if(!$kommission_db_ok) {
        // TODO add kommission
//         $script[] = $comment = "-- Add kommission ...";
//         $script[] = $command = "-- INSERT INTO kommission ...;";
//         print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
//         print(str_repeat("\t", $level + 1) . "SQL: $command\n");
        // TODO try get kommission again
        // kommission_db = getKommissionId($kommission->id);
      }

      $stmt->execute ( array(':kommission_parlament_id' => "$kommission->id") );
      $db_members = $stmt->fetchAll(PDO::FETCH_CLASS);

      $memberNames = '';
      foreach($kommission->members as $member) {
        $memberNames .= $member->lastName . ', ';

//         print_r($db_members);
        $db_member = search_objects($db_members, 'parlament_biografie_id', $member->id);
//         print("Search $member->id\n");
//         print_r($db_member);
        $member_party = property_exists($member, 'party') ? $member->party : 'No party'; // Avoid missing party property missing problem

        if ($ok = ($n = count($db_member)) == 1) {
          $db_member_obj = $db_member[0];

          if (!$db_member_obj->parlament_committee_function) {
                  $sign = '≠';
                  $db_member_obj->status = 'UPDATED';
            $script[] = $comment = "-- Update with new data $db_member_obj->name, $db_member_obj->abkuerzung=$db_member_obj->kommission_name, in_kommission_id=$db_member_obj->in_kommission_id, id=$db_member_obj->id";
            $script[] = $command = "UPDATE in_kommission SET parlament_committee_function=$member->committeeFunction, parlament_committee_function_name='$member->committeeFunctionName', updated_visa='import', updated_date=$sql_transaction_date, notizen=CONCAT_WS('\\n\\n', '$today/$user: Update von ws.parlament.ch',`notizen`) WHERE id=$db_member_obj->in_kommission_id;";
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
          } else if (/* FIXME Dirty quick fix for Ruiz Ana problem */ $db_member_obj->id == 252 && $db_member_obj->in_kommission_id == 1048) {
                  $sign = 'X';
                  $db_member_obj->status = 'OK';
            $script[] = $comment = "-- XXX FIXME $db_member_obj->name, $db_member_obj->abkuerzung=$db_member_obj->kommission_name, in_kommission_id=$db_member_obj->in_kommission_id, id=$db_member_obj->id, parlament_committee_function_name='$member->committeeFunctionName', parlament_committee_function=$member->committeeFunction";
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
          } else if (/*$db_member_obj->funktion != getKommissionsFunktion($member->committeeFunction) ||*/ $db_member_obj->parlament_committee_function != $member->committeeFunction /*|| $db_member_obj->parlament_committee_function_name != $member->committeeFunctionName*/) {
                  $sign = '#';
                  $db_member_obj->status = 'UPDATED';
            $script[] = $comment = "-- Terminate due to changed function $db_member_obj->name, $db_member_obj->abkuerzung=$db_member_obj->kommission_name, in_kommission_id=$db_member_obj->in_kommission_id, id=$db_member_obj->id";
            $script[] = $command = "UPDATE in_kommission SET bis=$sql_today, updated_visa='import', updated_date=$sql_transaction_date, notizen=CONCAT_WS('\\n\\n', '$today/$user: Beende wegen geänderter Funktion ws.parlament.ch',`notizen`) WHERE id=$db_member_obj->in_kommission_id;";
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
            $script[] = $comment = "-- Insert for changed function $db_member_obj->name, $db_member_obj->abkuerzung=$db_member_obj->kommission_name, in_kommission_id=$db_member_obj->in_kommission_id, id=$db_member_obj->id";
//             $script[] = $command = "UPDATE in_kommission SET parlament_committee_function=$member->committeeFunction, parlament_committee_function_name='$member->committeeFunctionName', bis=$sql_today, updated_visa='import', updated_date=$sql_transaction_date, notizen=CONCAT_WS('\\n\\n', '$today/$user: Update von ws.parlament.ch',`notizen`) WHERE id=$db_member_obj->in_kommission_id;";
//             $script[] = $comment = "-- New in_kommission $member->id ($member->number) $member->firstName $member->lastName $kommission_db->abkuerzung=$kommission_db->name, $member->committeeFunction=$member->committeeFunctionName, $member_party, $member->canton, id=$parlamentarier_db->id";
            $script[] = $command = "INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, updated_date, notizen, freigabe_datum, freigabe_visa) VALUES ($db_member_obj->id, $kommission_db->id, $sql_today, '" . getKommissionsFunktion($member->committeeFunction) .  "', $member->committeeFunction, '$member->committeeFunctionName', 'import', $sql_transaction_date, 'import', $sql_transaction_date, '$today/$user: Changed function via ws.parlament.ch', $sql_today, 'import');";
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
          } else {
            $db_member_obj->status = 'OK';
            $sign = '=';
          }
        } else if ($n > 1) {
          $sign = '*';
          // Duplicate
          $k = 0;
//           print_r($db_member);
          foreach($db_member as $db_member_obj) {
            $db_member_obj->status = 'OK';
            if ($k++ == 0) {
//               print(str_repeat("\t", $level) . "continue k=$k\n");
              continue;  // skip first
            }
            $script[] = $comment = "-- Delete in_kommission duplicate n=$n: $db_member_obj->name $db_member_obj->abkuerzung=$db_member_obj->kommission_name";
            $script[] = $command = "DELETE FROM in_kommission WHERE id=$db_member_obj->in_kommission_id;";
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
            //             print_r($script);
          }
          $db_member_obj = $db_member[0];
//           print(str_repeat("\t", $level) . "DUPLICATE $db_member_obj->name n=$n\n");
        } else {
//           print_r($db_member);
          $parlamentarier_db = getParlamentarierId($member->id);
          $parlamentarier_db_ok = $parlamentarier_db !== false;
          $parlamentarier_by_name_db = getParlamentarierIdByName($member->lastName, $member->firstName);
          $parlamentarier_by_name_db_ok = $parlamentarier_by_name_db !== false;
          $parlamentarier_by_name_db_needs_update = false;

          if (!$parlamentarier_db_ok && $parlamentarier_by_name_db_ok) {
            $updated_parlamentarier_id = $parlamentarier_by_name_db->id;
            $script[] = $comment = "-- Update missing parlament_biografie_id $member->id ($member->number) $member->firstName $member->lastName $member_party, $member->canton, id=$updated_parlamentarier_id";
            $script[] = $command = "UPDATE parlamentarier SET parlament_biografie_id = $member->id, updated_visa='import', updated_date=$sql_transaction_date, notizen=CONCAT_WS('\\n\\n', '$today/$user: Update Biographie-ID via ws.parlament.ch',`notizen`) WHERE id = $updated_parlamentarier_id;";
//         print(str_repeat("\t", $level) . "- $member_NOK_in_DB->name, in_kommission_id=$member_NOK_in_DB->in_kommission_id id=$member_NOK_in_DB->id\n");
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
            $parlamentarier_db = $parlamentarier_by_name_db;

            $db_member_updated = search_objects($db_members, 'id', $updated_parlamentarier_id);
            if (count($db_member_updated) == 1) {
              $updated_db_member_obj = $db_member_updated[0];
              $updated_db_member_obj->status = 'UPDATED';
              $parlamentarier_by_name_db_needs_update = false;
              $sign = 'P';
            } else {
              $parlamentarier_by_name_db_needs_update = true;
            }
          }

          if ($kommission_db_ok && ($parlamentarier_db_ok || $parlamentarier_by_name_db_needs_update)) {
            if ($parlamentarier_db_ok) {
              $sign = '+';
            } elseif (!$parlamentarier_db_ok && $parlamentarier_by_name_db_needs_update) {
              $sign = '&';
            } else {
              $sign = '?';
            }
//            print_r($kommission_db);
//            print_r($kommission_db->abkuerzung);
//           print_r($member);
//           print("Test $kommission_db->id");
            $script[] = $comment = "-- New in_kommission $member->id ($member->number) $member->firstName $member->lastName $kommission_db->abkuerzung=$kommission_db->name, $member->committeeFunction=$member->committeeFunctionName, $member_party, $member->canton, id=$parlamentarier_db->id";
            $script[] = $command = "INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, updated_date, notizen, freigabe_datum, freigabe_visa) VALUES ($parlamentarier_db->id, $kommission_db->id, $sql_today, '" . getKommissionsFunktion($member->committeeFunction) .  "', $member->committeeFunction, '$member->committeeFunctionName', 'import', $sql_transaction_date, 'import', $sql_transaction_date, '$today/$user: Import von ws.parlament.ch', $sql_today, 'import');";
//         print(str_repeat("\t", $level) . "- $member_NOK_in_DB->name, in_kommission_id=$member_NOK_in_DB->in_kommission_id id=$member_NOK_in_DB->id\n");
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
          } else {
            $sign = 'x';
          }
        }
//         print_r($db_member);
        //     print_r($res);

      switch ($sign) {
        case '=': $equal_inkommission_count++; break;
        case '+': $new_inkommission_count++; break;
        case '&': $new2_inkommission_count++; break;
        case '≠': $change_inkommission_count++; break;
        case '#': $terminated_inkommission_count++; break;
        case '-': $deleted_inkommission_count++; break; // counted also below
        case '*': $duplicate_inkommission_count++; break;
        case 'x': $untracked_inkommission_count++; break;
        case 'P': // fallthrough
        case '?': $error_inkommission_count++; break;
      }

        print(str_repeat("\t", $level) . "$sign " . str_pad($member->id, 4, " ", STR_PAD_LEFT) . "  (" . str_pad($member->number, 4, " ", STR_PAD_LEFT) . ") $member->firstName $member->lastName  $member->committeeFunction=$member->committeeFunctionName, $member_party, $member->canton" . ($ok ? ", id=$db_member_obj->id" : '')  . "\n");
      }
//       print(str_repeat("\t", $level) . " Kommissionsmitglieder: $kommission->id $kommission->abbreviation: $memberNames\n");
//       print_r($db_members);
      $db_members_NOK_in_DB = search_objects($db_members, 'status', 'NOK');
      $sign = '-';
      foreach($db_members_NOK_in_DB as $member_NOK_in_DB) {
            $script[] = $comment = "-- Not in_kommission anymore $member_NOK_in_DB->name, $member_NOK_in_DB->abkuerzung=$member_NOK_in_DB->kommission_name, in_kommission_id=$member_NOK_in_DB->in_kommission_id, id=$member_NOK_in_DB->id";
            $script[] = $command = "UPDATE in_kommission SET bis=$sql_today, updated_visa='import', updated_date=$sql_transaction_date, notizen=CONCAT_WS('\\n\\n', '$today/$user: Update von ws.parlament.ch',`notizen`) WHERE id=$member_NOK_in_DB->in_kommission_id;";
        print(str_repeat("\t", $level) . $sign . " $member_NOK_in_DB->name, in_kommission_id=$member_NOK_in_DB->in_kommission_id id=$member_NOK_in_DB->id\n");
        if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
        if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
        $deleted_inkommission_count++;
      }

      foreach($kommission->subcommittees as $subCom) {
        $j++;

        $sign = '';
        $subKommission_db = getKommissionId($subCom->id);
        $subKommission_db_ok = $subKommission_db !== false;
        if($subKommission_db_ok) {
          $sign = '=';
          $equal_subkommission_count++;
        } else {
          $sign = 'x';
          $untracked_subkommission_count++;
        }

        $council = $subCom->council;
        print(str_repeat("\t", $level) . $j . ". $sign Subkommission: $subCom->id $subCom->abbreviation: $subCom->name, $council->abbreviation\n");
        $inkommission_counts = show_members(array($subCom->id), $level + 1);

        $equal_subkommission_count     += $inkommission_counts['equal_subkommission_count'];      // '='
        $untracked_subkommission_count += $inkommission_counts['untracked_subkommission_count'];  // 'x'

        $equal_inkommission_count      += $inkommission_counts['equal_inkommission_count'];        // '='
        $new_inkommission_count        += $inkommission_counts['new_inkommission_count'];          // '+'
        $new2_inkommission_count       += $inkommission_counts['new2_inkommission_count'];         // '&'
        $change_inkommission_count     += $inkommission_counts['change_inkommission_count'];       // '≠'
        $terminated_inkommission_count += $inkommission_counts['terminated_inkommission_count'];   // '#'
        $deleted_inkommission_count    += $inkommission_counts['deleted_inkommission_count'];      // '-'
        $duplicate_inkommission_count  += $inkommission_counts['duplicate_inkommission_count'];    // '*'
        $untracked_inkommission_count  += $inkommission_counts['untracked_inkommission_count'];    // 'x'
        $error_inkommission_count      += $inkommission_counts['error_inkommission_count'];        // '?'
      }
    }
  }
  return [
    'equal_subkommission_count' =>     $equal_subkommission_count,       // '='
    'untracked_subkommission_count' => $untracked_subkommission_count,   // 'x'

    'equal_inkommission_count' =>      $equal_inkommission_count,        // '='
    'new_inkommission_count' =>        $new_inkommission_count,          // '+'
    'new2_inkommission_count' =>       $new2_inkommission_count,         // '&'
    'change_inkommission_count' =>     $change_inkommission_count,       // '≠'
    'terminated_inkommission_count' => $terminated_inkommission_count,   // '#'
    'deleted_inkommission_count' =>    $deleted_inkommission_count,      // '-'
    'duplicate_inkommission_count' =>  $duplicate_inkommission_count,    // '*'
    'untracked_inkommission_count' =>  $untracked_inkommission_count,    // 'x'
    'error_inkommission_count' =>      $error_inkommission_count,        // '?'
//     $error_inkommission_count,                                        // 'P'
  ];
}

function getParlamentarierId($parlamentBiografieId) {
  global $db;
  $sql = "SELECT parlamentarier.id, parlamentarier.name, parlamentarier.anzeige_name, parlamentarier.parlament_biografie_id FROM v_parlamentarier_simple parlamentarier WHERE parlamentarier.parlament_biografie_id = :parlament_biografie_id;";
  $stmt = $db->prepare($sql);
  $stmt->execute (array(':parlament_biografie_id' => "$parlamentBiografieId"));
  $db_member = $stmt->fetchAll(PDO::FETCH_CLASS);
  if (count($db_member) == 1) {
    return $db_member[0];
  } else {
    return false;
  }
}

function getParlamentarierIdByName($nachname, $vorname) {
  global $db;
  $sql = "SELECT parlamentarier.id, parlamentarier.name, parlamentarier.anzeige_name, parlamentarier.parlament_biografie_id FROM v_parlamentarier_simple parlamentarier WHERE parlamentarier.nachname = :nachname AND parlamentarier.vorname = :vorname AND parlamentarier.parlament_biografie_id IS NULL;";
  $stmt = $db->prepare($sql);
  $stmt->execute (array(':nachname' => "$nachname", ':vorname' => "$vorname"));
  $db_member = $stmt->fetchAll(PDO::FETCH_CLASS);
  if (count($db_member) == 1) {
    return $db_member[0];
  } else {
    return false;
  }
}

function getKommissionId($parlamentCommitteeId) {
  global $db;
  $sql = "SELECT * FROM kommission kommission WHERE parlament_id = :kommission_parlament_id;";
  $stmt = $db->prepare($sql);
  $stmt->execute (array(':kommission_parlament_id' => "$parlamentCommitteeId"));
  $db_kommission = $stmt->fetchAll(PDO::FETCH_CLASS);
  if (count($db_kommission) == 1) {
    return $db_kommission[0];
  } else {
//     print("getKommissionId not found: $parlamentCommitteeId");
    return false;
  }
}

function getKommissionsFunktion($committeeFunction) {
  global $errors;
  switch($committeeFunction) {
    case 11: return 'mitglied'; // Fraktionspräsident/in
    case 9: return 'mitglied'; // Stimmenzähler/in
    case 10: return 'mitglied'; // Ersatzstimmenzähler/in
    case 13: return 'vizepraesident'; // 1. Vizepräsident/in
    case 14: return 'vizepraesident'; // 2. Vizepräsident/in
    case 2: return 'praesident'; // Präsident/in
    case 1: return 'mitglied'; // Mitglied
    case 6: return 'vizepraesident'; // Vizepräsident/in
    default: return 'mitglied';
  }
}

function getRatId($council) {
  global $errors;
  if (is_object($council)) {
  // Historic
	$councilType = $council->type;
  } else {
	// BasicDetails
	$councilType = $council;
  }
  switch($councilType) {
    case 'N': return 1;
    case 'S': return 2;
    case 'B': return 4;
    case '': case null: return null;
    default: $errors[] = "Wrong rat code '$councilType'"; return "ERR: $councilType";
  }
}

function checkSprache($language) {
  global $errors;
  if ($language == '') {
    return 'de';
  } else if (in_array($language, array('de', 'fr', 'it', 'sk', 'rm', 'tr'))) {
    return $language;
  } else {
    $errors[] = "Unsupported language '$language'"; return "ERR: $language";
  }
}

function getMilGradId($militaryGrade) {
  global $errors;
  $val = preg_replace('/( aD| EMG)$/', '', $militaryGrade);
  switch($val) {
    case 'Rekrut': return 1;
    case 'Soldat': return 2;
    case 'Gefreiter': return 3;
    case 'Gefreiter Motf.': return 3;
    case 'Gefreiter / Fallschirmgrenadier': return 3;
    case 'Obergefreiter': return 4;
    case 'Korporal': return 5;
    case 'Wachtmeister': return 6;
    case 'Oberwachtmeister': return 7;
    case 'Feldweibel': return 8;
    case 'Hauptfeldweibel': return 9;
    case 'Fourier': return 10;
    case 'Adjutant h UOF': // fallthrough
    case 'Adjutant h UOF aD': // fallthrough
    case 'Adjutant Unteroffizier': return 11;
    case 'Stabsadjutant': return 12;
    case 'Hauptadjutant': return 13;
    case 'Chefadjutant': return 14;
    case 'Leutnant': return 15;
    case 'Oberleutnant': return 16;
    case 'Hauptmann': return 17;
    case 'Major': return 18;
    case 'Fachoffizier (Maj)': return 18; // Added
    case 'Fachoffizier': return 16; // Added
    case 'Oberstleutnant': return 19;
    case 'Oberst': return 20;
    case 'Oberst i Gst': return 20; // Added
    case 'Brigadier': return 21;
    case 'Divisionär': return 22;
    case 'Korpskommandant': return 23;
    case '': case null: return null;
    default: $errors[] = "Wrong MilGrad code '$militaryGrade'"; return "ERR $militaryGrade";
  }
}

function convertGeschlecht($genderCode) {
  return strtoupper($genderCode);
}

function convertURL($url) {
  return starts_with($url, 'www.') ? "http://$url" : $url;
}

/*
There are NULL cases!

FIELD_MODE_OVERWRITE      -> users are free to set something, it will not be overwritten
FIELD_MODE_OVERWRITE_NULL -> the zivilstand will have as well NULL values if the original data are like this, users cannot set anything

Data analysis:
  [NULL] => 81
  [verheiratet] => 141
  [in eingetragener Partnerschaft] => 3
  [ledig] => 17
  [getrennt] => 1
  [verwitwet] => 1
  [geschieden] => 1

Reason: Damian Müller, wrong zivilstand
*/
function convertZivilstand($martialState) {
  if ($martialState == 'in eingetragener Partnerschaft') {
    return 'eingetragene partnerschaft';
  } else {
    return $martialState;
  }
}

function getFraktionFunktion($factionFunction) {
  global $errors;
  switch($factionFunction) {
    case 'Mitglied': return 'mitglied';
    case 'Präsident/in': return 'praesident';
    case 'Vizepräsident/in': return 'vizepraesident';
    case '': case null: return null;
    default: $errors[] = "Wrong fraktion funktion code '$factionFunction'"; return "ERR $factionFunction";
  }
}

function getFraktionId($faction) {
  global $errors;
  if (is_object($faction)) {
  // Historic
	$factionCode = $faction->abbreviation;
  } else {
	// BasicDetails
	$factionCode = $faction;
  }
  switch($factionCode) {
    case 'BD': return 7;
    case 'C': return 6;
    case 'CE': return 6;
    case 'G': return 4;
    case 'GL': return 2;
    case 'RL': return 1;
    case 'S': return 3;
    case 'V': return 5;
    case '-': case '': case null: return null;
    default: $errors[] = "Wrong fraktion code '$factionCode'"; return "ERR $factionCode";
  }
}

function getParteiId($party) {
  global $errors;
  if (is_object($party)) {
  // Historic
	$partyCode = $party->abbreviation;
  } else {
	// BasicDetails
	$partyCode = $party;
  }
  switch($partyCode) {
    case 'BDP': return 8;
    case 'CSP': return 11;
    case 'csp-ow': return 11;
    case 'CSPO': return 12;
    case 'CVP': return 7;
    case 'CVPO': return 18;
    case 'EVP': return 6;
    case 'FDP-Liberale': return 1;
    case 'glp': return 2;
    case 'GPS': return 4;
    case 'Lega': return 10;
    case 'MCR': return 9;
    case 'MCG': return 9;
    case 'SP': return 3;
    case 'SVP': return 5;
    case 'PdA': return 13;
    case 'LPS': return 14;
    case 'LDP': return 16;
    case 'BastA': return 15;
    case '-': case '': case null: return null;
    default: $errors[] = "Wrong partei code '$partyCode'"; return "ERR $partyCode";
  }
}

function transformDashToNull($in) {
switch ($in) {
  case '-': return null;
  default: return $in;
  }
}

function getImRatBis($active, $parlamentarier_ws, $field_ws, $parlamentarier_db_obj, $field) {
  global $errors;
  global $sql_today;

  if (!$active && isset($parlamentarier_ws->councilMemberships)) {
      $max_leaving_date = null;
      foreach ($parlamentarier_ws->councilMemberships as $membership) {
        $is_date = isset($membership->leavingDate) && !is_array($membership->leavingDate) && preg_match('/^\d{4}-\d{2}-\d{2}/', $membership->leavingDate);
        if ($is_date) {
          $leaving_date = substr($membership->leavingDate, 0, 10);
          $max_leaving_date = max($max_leaving_date, $leaving_date);
        } else {
        $errors[] = "councilMemberships->leavingDate is not a date!";
        }
      }
      if ($max_leaving_date) {
        return "STR_TO_DATE('$max_leaving_date','%Y-%m-%d')";
      } else {
        $errors[] = "Not active, but no leaving dates either!";
        return null;
      }
    }

  return null;
}

function getImRatSeit($councilMemberships, $parlamentarier_ws, $field_ws, $parlamentarier_db_obj, $field) {
  global $errors;
  global $sql_today;

  if (isset($councilMemberships)) {
      $min_entry_date = !empty($parlamentarier_db_obj->$field) ? $parlamentarier_db_obj->$field : '2100-12-31';
      foreach ($councilMemberships as $membership) {
        $is_date = isset($membership->entryDate) && !is_array($membership->entryDate) && preg_match('/^\d{4}-\d{2}-\d{2}/', $membership->entryDate);
        if ($is_date) {
          $entry_date = substr($membership->entryDate, 0, 10);
          $min_entry_date = min($min_entry_date, $entry_date);
        } else {
          $errors[] = "councilMemberships->entryDate is not a date!";
        }
      }
      return "STR_TO_DATE('$min_entry_date','%Y-%m-%d')";
    }

  return null;
}

/**
 * If several Ratswechsel, return the last one.
 */
function getRatswechsel($councilMemberships, $parlamentarier_ws, $field_ws, $parlamentarier_db_obj, $field) {
  global $errors;
  global $sql_today;

  if (isset($councilMemberships)) {
      $last_rat_typ = null;
      $last_ratswechsel = null;
      foreach ($councilMemberships as $membership) {
        $rat_typ = $membership->council->type;
        if (!$last_rat_typ) {
          $last_rat_typ = $rat_typ;
        } else if ($last_rat_typ != $rat_typ && in_array($rat_typ, array('N', 'S'))) {
          $last_rat_typ = $rat_typ;
          $is_date = isset($membership->entryDate) && !is_array($membership->entryDate) && preg_match('/^\d{4}-\d{2}-\d{2}/', $membership->entryDate);
          if ($is_date) {
            $entry_date = substr($membership->entryDate, 0, 10);
            $last_ratswechsel = max($last_ratswechsel, $entry_date);
          } else {
            $errors[] = "councilMemberships->entryDate is not a date!";
          }
        }
      }
      if ($last_ratswechsel) {
        return "STR_TO_DATE('$last_ratswechsel','%Y-%m-%d')";
      }
    }

  return null;
}

function getRatsunterbruchVon($councilMemberships, $parlamentarier_ws, $field_ws, $parlamentarier_db_obj, $field, &$fields_msg) {
  return getRatsunterbruch(true, $councilMemberships, $parlamentarier_ws, $field_ws, $parlamentarier_db_obj, $field, $fields_msg);
}

function getRatsunterbruchBis($councilMemberships, $parlamentarier_ws, $field_ws, $parlamentarier_db_obj, $field, &$fields_msg) {
  return getRatsunterbruch(false, $councilMemberships, $parlamentarier_ws, $field_ws, $parlamentarier_db_obj, $field, $fields_msg);
}


/**
 * If several Ratsunterbrüche, return the last one.
 */
function getRatsunterbruch($von, $councilMemberships, $parlamentarier_ws, $field_ws, $parlamentarier_db_obj, $field, &$fields_msg) {
  global $errors;
  global $sql_today;
  global $verbose;

  if ($verbose >= 9) {
    $max_output_length = 100000;
  } else if ($verbose > 5) {
    $max_output_length = 1000;
  } else if ($verbose > 2) {
    $max_output_length = 100;
  } else if ($verbose > 1) {
    $max_output_length = 25;
  } else {
    $max_output_length = 10;
  }

  if (isset($councilMemberships)) {
      $last_leaving_date = null;
      $last_ratsunterbruch_von = null;
      $last_ratsunterbruch_bis = null;
      $first = true;
      foreach ($councilMemberships as $membership) {
        $is_date = isset($membership->leavingDate) && !is_array($membership->leavingDate) && preg_match('/^\d{4}-\d{2}-\d{2}/', $membership->leavingDate);
        if ($first && $is_date) {
          $last_leaving_date = substr($membership->leavingDate, 0, 10);
          $first = false;
          continue;
        }
//         echo $last_leaving_date, ' ', substr($membership->entryDate, 0, 10), ' ', ($leaving_time = strtotime("+1 day", strtotime($last_leaving_date))), ' ', ($re_entry_time = strtotime(substr($membership->entryDate, 0, 10))), ' ', $is_date, ' ', $last_leaving_date;
        if ($last_leaving_date && ($leaving_time = strtotime("+1 day", strtotime($last_leaving_date))) < ($re_entry_time = strtotime(substr($membership->entryDate, 0, 10)))) {
          if ($verbose > 1 && $von && $last_ratsunterbruch_von) {
//             $msg = ($verbose ? " (" . (isset($parlamentarier_db_obj->$field) ? cut($parlamentarier_db_obj->$field, $max_output_length) . " → " : '') . (isset($val) ? cut($val, $max_output_length) : 'null') .  ")" : '');
            $msg = " previous $last_ratsunterbruch_von to $last_ratsunterbruch_bis";
            $fields_msg[] = "?{$field}{$msg}?";
          }
          $last_ratsunterbruch_von = date('Y-m-d', $leaving_time);
          $last_ratsunterbruch_bis = date('Y-m-d', strtotime("-1 day", $re_entry_time));
//           echo " unterbruch von $last_ratsunterbruch_von bis $last_ratsunterbruch_bis";
        }
//         echo "\n";
        if ($is_date) {
          $last_leaving_date = substr($membership->leavingDate, 0, 10);
        }
      }
      if ($last_ratsunterbruch_von && $von) {
        return "STR_TO_DATE('$last_ratsunterbruch_von','%Y-%m-%d')";
      } else if ($last_ratsunterbruch_bis && !$von) {
        return "STR_TO_DATE('$last_ratsunterbruch_bis','%Y-%m-%d')";
      }
    }

  return null;
}

function getKantonId($kanton) {
  global $errors;
  if (is_object($kanton)) {
  // Historic
    $kanton_code = $kanton->abbreviation;
  } else {
    // BasicDetails
    $kanton_code = $kanton;
  }
  switch($kanton_code) {
    case 'AG': return 19;
    case 'AR': return 15;
    case 'AI': return 16;
    case 'BL': return 13;
    case 'BS': return 12;
    case 'BE': return 2;
    case 'FR': return 10;
    case 'GE': return 25;
    case 'GL': return 8;
    case 'GR': return 18;
    case 'JU': return 26;
    case 'LU': return 3;
    case 'NE': return 24;
    case 'NW': return 7;
    case 'OW': return 6;
    case 'SH': return 14;
    case 'SZ': return 5;
    case 'SO': return 11;
    case 'SG': return 17;
    case 'TI': return 21;
    case 'TG': return 20;
    case 'UR': return 4;
    case 'VD': return 22;
    case 'VS': return 23;
    case 'ZG': return 9;
    case 'ZH': return 1;
    case '': case null: return null;
    default: $errors[] = "Wrong canton code '$kanton_code'"; return "ERR $kanton_code";
  }
}

function getParlamentInteressenbindungen($concerns) {
  global $errors;
  $interessenbindungen = array();
  $html = null;
  if (is_array($concerns) && !empty($concerns)) {
    foreach($concerns as $concern) {
//       print_r($concern);
      $interessenbindungen[] = "<tr><td>$concern->name</td><td>$concern->type</td><td>$concern->organizationType</td><td>$concern->function</td></tr>";
    }
  $html = "<table border='0'>" .
  "<thead><tr><th>Name</th><th>Rechtsform</th><th><abbr title='Gremium'>Gr.</abbr></th><th><abbr title='Funktion'>F.</abbr></th></tr></thead>\n" .
  "<tbody>\n" . implode("\n", $interessenbindungen) . "\n</tbody>\n</table>";
  }
  return $html;
}

// Returns interessenbindungen as PHP data structures (arrays and objects)
function getParlamentInteressenbindungenJson($concerns) {
  global $errors;
  $interessenbindungen = array();
  $objects = null;
  if (is_array($concerns) && !empty($concerns)) {
    $objects = [];
    foreach($concerns as $concern) {
//       print_r($concern);
      $objects[] = (object) [
        'Name' => $concern->name,
        'Rechtsform' => $concern->type,
        'Gremium' => $concern->organizationType,
        'Funktion' => $concern->function
      ];
    }
  }
  return $objects;
}

/** Converts a $json string to PHP datastructures (objects and arrays) */
function decodeJson($json, $parlamentarier_db_obj) {
  global $errors;
  $data = isset($json) ? json_decode($json) : null;

  if (json_last_error() != 0) {
    $errors[] = 'json_decode ERROR: ' . json_last_error() . ', ' . json_last_error_msg() . ", id=" . $parlamentarier_db_obj->id . " → '" . $json . "' / '" . $data . "'";
  }

  return $data;
}

function parlamentarierOhneBiografieID() {
  global $db;

  $sql = "SELECT id, vorname, nachname FROM parlamentarier WHERE parlament_biografie_id IS NULL;";
  $stmt = $db->prepare($sql);
  $stmt->execute(array());
  $res = $stmt->fetchAll(PDO::FETCH_CLASS);

  if (count($res) == 0) {
    return;
  }

  print("/*------------------------------------------------\n");
  print("Parlamentarier ohne Biografie-ID:\n");
  $i = 0;
  foreach($res as $obj) {
    $i++;
    print("$i. $obj->vorname $obj->nachname id=$obj->id\n");
  }
  print("Parlamentarier ohne Biografie-ID Ende\n");
  print("------------------------------------------------*/\n\n");
}

function findIDOfParlamentarierWithoutBiografieIDByName($nachname) {
  global $db;

  $sql = "SELECT id, vorname, nachname FROM parlamentarier WHERE parlament_biografie_id IS NULL AND nachname='" . escape_string($nachname) . "';";
  $stmt = $db->prepare($sql);
  $stmt->execute(array());
  $res = $stmt->fetchAll(PDO::FETCH_CLASS);

  foreach($res as $obj) {
    return $obj->id;
  }
  return false;
}

function get_web_data($url) {
//   print("Call $url\n");
  $data = get_web_data_fgc_retry($url);
//   print_r($url . "→ " . $data);
  return $data;
}

// $response = get_web_data('http://images.google.com/images?hl=en&q=' . urlencode ($query) . '&imgsz=' . $size . '&imgtype=' . $type . '&start=' . (($page - 1) * 21));
// https://stackoverflow.com/questions/11920026/replace-file-get-content-with-curl
// https://stackoverflow.com/questions/8540800/how-to-use-curl-instead-of-file-get-contents
function get_web_data_curl($url) {
  $ch = curl_init();
  $timeout = 10;
  curl_setopt($ch, CURLOPT_URL, $url);
//   curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json'));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json',
                                             'Content-Type: application/json',));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

function get_web_data_fgc_retry($url) {
  global $context;
  global $verbose;
  $num_retry = 25;
  for ($i = 1; $i <= $num_retry; $i++) {
    $data = @file_get_contents($url, false, $context);
    // $http_response_header is automatically populated, see https://www.php.net/manual/en/reserved.variables.httpresponseheader.php
    $code = get_http_code($http_response_header);
//     print("Code: $code\n");
    if ($code == 200) {
//       print("$url: OK\n");
      return $data;
    } else if ($code == 500) {
      if ($verbose > 1) print("$url failed with $code, retry $i\n");
      sleep(1);
    } else {
      if ($verbose > 1) print("WARNING: $url failed with $code, retry $i\n");
      print_r($http_response_header);
      sleep(1);
      // return $data;
    }
  }
  print("ERROR: $url failed $num_retry times\n");
  exit(2);
}

function get_web_data_fgc($url) {
  global $context;
  $data = file_get_contents($url, false, $context);
  return $data;
}

// @file_get_contents("http://example.com");
// $code=getHttpCode($http_response_header);
// https://stackoverflow.com/questions/15620124/http-requests-with-file-get-contents-getting-the-response-code
function get_http_code($http_response_header) {
  if(is_array($http_response_header)) {
    $parts = explode(' ', $http_response_header[0]);
    if (count($parts) > 1) {//HTTP/1.0 <code> <text>
      return intval($parts[1]); //Get code
    }
  }
  return 0;
}
