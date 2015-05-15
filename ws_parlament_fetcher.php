<?php
require_once dirname(__FILE__) . '/public_html/settings/settings.php';
require_once dirname(__FILE__) . '/public_html/common/utils.php';
// Run: /opt/lampp/bin/php -f ws_parlament_fetcher.php | less

// http://www.parlament.ch/D/DOKUMENTATION/WEBSERVICES-OPENDATA/Seiten/default.aspx

// TODO multipage handling
// TODO Datenquelle angeben
// TODO historized handlen
// TODO historized fragen

$kommission_ids = array();

// $url = 'http://ws.parlament.ch/committees?ids=1;2;3&mainOnly=false&permanentOnly=true&currentOnly=true&lang=de&pageNumber=1&format=xml';
// $url = 'http://lobbywatch.ch/de/data/interface/v1/json/table/branche/flat/id/1';

// $json = fopen($url, 'r');
// $json = file_get_contents($url);
// $json = new_get_file_contents($url);
global $script;
global $context;
global $show_sql;
global $db;

$show_sql = false;
$level = 0;
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
$script[] = "-- SQL script from ws.parlament.ch " . date("d.m.Y");

parlamentarierOhneBiografie();

$sql = "SELECT kommission.id, kommission.abkuerzung, kommission.name, kommission.typ, kommission.art, kommission.parlament_id, kommission.mutter_kommission_id, 'NOK' as status FROM kommission kommission WHERE bis IS NULL;";
$stmt = $db->prepare($sql);

$stmt->execute ( array() );
$db_kommissionen = $stmt->fetchAll(PDO::FETCH_CLASS);

for($page = 1, $hasMorePages = true, $i = 0; $hasMorePages; $page++) {
  global $db;

  $ws_parlament_url = "http://ws.parlament.ch/committees?currentOnly=true&mainOnly=true&permanentOnly=true&format=json&lang=de&pageNumber=$page";
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

  // var_dump($json);
  $obj = json_decode($json);
  // var_dump($obj);

//   $sql = "SELECT * FROM kommission kommission WHERE parlament_id = :kommission_parlament_id;";
//   $stmt = $db->prepare($sql);

  $hasMorePages = false;
  print("Page: $page\n");
  foreach($obj as $kommission) {
    if(property_exists($kommission, 'hasMorePages')) {
      $hasMorePages = $kommission->hasMorePages;
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
    $kommission_db = search_objects($db_kommissionen, 'parlament_id', $kommission->id);
    //         print("Search $member->id\n");
    //         print_r($db_member);
    if ($ok = ($n = count($kommission_db)) == 1) {
      $kommission_db_obj = $kommission_db[0];
      $kommission_db_obj->status = 'OK';
      $sign = '=';
    } else if ($n > 1) {
      $sign = '*';
      // Duplicate
    } else {
      $sign = '!';

      $ws_parlament_url = "http://ws.parlament.ch/committees?ids=$kommission->id&format=json&lang=fr&subcom=true&pageNumber=1";
	  $json_fr = file_get_contents($ws_parlament_url, false, $context);
	  $obj_fr = json_decode($json_fr);
	  $kommission_fr = $obj_fr[0];

	  $council = $kommission->council;

	  $script[] = $comment = "-- New Kommission $kommission->abbreviation=$kommission->name";
	  $script[] = $command = "-- INSERT INTO kommission (abkuerzung, abkuerzung_fr, name, name_fr, rat_id, typ, parlament_id, parlament_committee_number, parlament_subcommittee_number, parlament_type_code, von, created_visa, created_date, updated_visa, notizen) VALUES ('$kommission->abbreviation', '$kommission_fr->abbreviation', '$kommission->name', '". escape_string($kommission_fr->name) . "', " . getRatId($council->type) . ", 'kommission', $kommission->id, $kommission->committeeNumber, NULL, $kommission->typeCode, STR_TO_DATE('$today','%d.%m.%Y'), 'import', STR_TO_DATE('$today','%d.%m.%Y'), 'import', '$today/Roland: Kommission importiert von ws.parlament.ch');";
	  if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
	  if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
    }

    print(str_repeat("\t", $level) . "$i. $sign Kommission: $kommission->id $kommission->abbreviation=$kommission->name, " . $kommission->council->abbreviation . ", $kommission->typeCode" . ($ok ? ', id=' . $kommission_db_obj->id : '') . "\n");
    show_members(array($kommission->id), $level + 1);
  }

  $db_kommissionen_NOK_in_DB = search_objects($db_kommissionen, 'status', 'NOK');
  foreach($db_kommissionen_NOK_in_DB as $kommission_NOK_in_DB) {
    print(str_repeat("\t", $level) . "    - Kommission: pid=$kommission_NOK_in_DB->parlament_id $kommission_NOK_in_DB->abkuerzung=$kommission_NOK_in_DB->name, id=$kommission_NOK_in_DB->id\n");

    $script[] = $comment = "-- Historize old Kommission $kommission_NOK_in_DB->abkuerzung=$kommission_NOK_in_DB->name, id=$kommission_NOK_in_DB->id";
    $script[] = $command = "UPDATE kommission SET bis=STR_TO_DATE('$today','%d.%m.%Y'), updated_visa='import', notizen=CONCAT_WS('\\n\\n', '$today/Roland: Kommission nicht mehr aktiv auf ws.parlament.ch',`notizen`) WHERE id=$kommission_NOK_in_DB->id;";
    if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
    if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");

    $script[] = $comment = "-- Not in_kommission anymore (outdated kommission) $kommission_NOK_in_DB->abkuerzung=$kommission_NOK_in_DB->name, id=$kommission_NOK_in_DB->id";
    $script[] = $command = "UPDATE in_kommission SET bis=STR_TO_DATE('$today','%d.%m.%Y'), updated_visa='import', notizen=CONCAT_WS('\\n\\n', '$today/Roland: Kommission nicht mehr aktiv auf ws.parlament.ch',`notizen`) WHERE kommission_id=$kommission_NOK_in_DB->id;";
    if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
    if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
  }

  print("\nSQL:\n");
  print(implode("\n", $script));
  print("\n");
}

function show_members(array $ids, $level = 1) {
  global $db;
  global $script;
  global $context;
  global $show_sql;
  global $today;


  $ids_str = implode(';', $ids);

  $sql = "SELECT parlamentarier.id, parlamentarier.name, parlamentarier.anzeige_name, parlamentarier.parlament_biografie_id, kommission.abkuerzung, kommission.name as kommission_name, kommission.typ, kommission.art, kommission.parlament_id, kommission.mutter_kommission_id, in_kommission.parlament_committee_function, in_kommission.parlament_committee_function_name, 'NOK' as status, in_kommission.id as in_kommission_id, in_kommission.kommission_id FROM v_kommission kommission JOIN v_in_kommission in_kommission ON in_kommission.kommission_id = kommission.id JOIN v_parlamentarier_simple parlamentarier ON in_kommission.parlamentarier_id = parlamentarier.id WHERE in_kommission.bis IS NULL AND kommission.parlament_id = :kommission_parlament_id;"; // AND parlamentarier.parlament_biografie_id = :parlamentarier_parlament_id AND parlamentarier.im_rat_bis IS NULL
  $stmt = $db->prepare($sql);

  for($page = 1, $hasMorePages = true, $i = 0, $j = 0; $hasMorePages; $page++) {
    $ws_parlament_url = "http://ws.parlament.ch/committees?ids=$ids_str&format=json&lang=de&subcom=true&pageNumber=$page";
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

    // var_dump($json);
    $obj = json_decode($json);
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
        if ($ok = ($n = count($db_member)) == 1) {
          $db_member_obj = $db_member[0];

          if (!$db_member_obj->parlament_committee_function) {
			$sign = '≠';
			$db_member_obj->status = 'UPDATED';
            $script[] = $comment = "-- Update with new data $db_member_obj->name, $db_member_obj->abkuerzung=$db_member_obj->kommission_name, in_kommission_id=$db_member_obj->in_kommission_id, id=$db_member_obj->id";
            $script[] = $command = "UPDATE in_kommission SET parlament_committee_function=$member->committeeFunction, parlament_committee_function_name='$member->committeeFunctionName', updated_visa='import', notizen=CONCAT_WS('\\n\\n', '$today/Roland: Update von ws.parlament.ch',`notizen`) WHERE id=$db_member_obj->in_kommission_id;";
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
          } elseif (/*$db_member_obj->funktion != getKommissionsFunktion($member->committeeFunction) ||*/ $db_member_obj->parlament_committee_function != $member->committeeFunction /*|| $db_member_obj->parlament_committee_function_name != $member->committeeFunctionName*/) {
			$sign = '#';
			$db_member_obj->status = 'UPDATED';
            $script[] = $comment = "-- Terminate due to changed function $db_member_obj->name, $db_member_obj->abkuerzung=$db_member_obj->kommission_name, in_kommission_id=$db_member_obj->in_kommission_id, id=$db_member_obj->id";
            $script[] = $command = "UPDATE in_kommission SET bis=STR_TO_DATE('$today','%d.%m.%Y'), updated_visa='import', notizen=CONCAT_WS('\\n\\n', '$today/Roland: Beende wegen geänderter Funktion ws.parlament.ch',`notizen`) WHERE id=$db_member_obj->in_kommission_id;";
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
            $script[] = $comment = "-- Insert for changed function $db_member_obj->name, $db_member_obj->abkuerzung=$db_member_obj->kommission_name, in_kommission_id=$db_member_obj->in_kommission_id, id=$db_member_obj->id";
//             $script[] = $command = "UPDATE in_kommission SET parlament_committee_function=$member->committeeFunction, parlament_committee_function_name='$member->committeeFunctionName', bis=STR_TO_DATE('$today','%d.%m.%Y'), updated_visa='import', notizen=CONCAT_WS('\\n\\n', '$today/Roland: Update von ws.parlament.ch',`notizen`) WHERE id=$db_member_obj->in_kommission_id;";
//             $script[] = $comment = "-- New in_kommission $member->id ($member->number) $member->firstName $member->lastName $kommission_db->abkuerzung=$kommission_db->name, $member->committeeFunction=$member->committeeFunctionName, $member->party, $member->canton, id=$parlamentarier_db->id";
            $script[] = $command = "INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (". $db_member_obj->id . ", $kommission_db->id, STR_TO_DATE('$today','%d.%m.%Y'), '" . getKommissionsFunktion($member->committeeFunction) .  "', $member->committeeFunction, '$member->committeeFunctionName', 'import', STR_TO_DATE('$today','%d.%m.%Y'), 'import', '$today/Roland: Changed function via ws.parlament.ch');";
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
            $script[] = $comment = "-- Update missing parlament_biografie_id $member->id ($member->number) $member->firstName $member->lastName $member->party, $member->canton, id=$updated_parlamentarier_id";
            $script[] = $command = "UPDATE parlamentarier SET parlament_biografie_id = $member->id, updated_visa='import', notizen=CONCAT_WS('\\n\\n', '$today/Roland: Update Biographie-ID via ws.parlament.ch',`notizen`) WHERE id = $updated_parlamentarier_id;";
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
//           print("Test " . $kommission_db->id);
            $script[] = $comment = "-- New in_kommission $member->id ($member->number) $member->firstName $member->lastName $kommission_db->abkuerzung=$kommission_db->name, $member->committeeFunction=$member->committeeFunctionName, $member->party, $member->canton, id=$parlamentarier_db->id";
            $script[] = $command = "INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (". $parlamentarier_db->id . ", $kommission_db->id, STR_TO_DATE('$today','%d.%m.%Y'), '" . getKommissionsFunktion($member->committeeFunction) .  "', $member->committeeFunction, '$member->committeeFunctionName', 'import', STR_TO_DATE('$today','%d.%m.%Y'), 'import', '$today/Roland: Import von ws.parlament.ch');";
//         print(str_repeat("\t", $level) . "- $member_NOK_in_DB->name, in_kommission_id=$member_NOK_in_DB->in_kommission_id id=$member_NOK_in_DB->id\n");
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
          } else {
			$sign = 'x';
          }
        }
//         print_r($db_member);
        //     print_r($res);

        print(str_repeat("\t", $level) . "$sign " . str_pad($member->id, 4, " ", STR_PAD_LEFT) . "  (" . str_pad($member->number, 4, " ", STR_PAD_LEFT) . ") $member->firstName $member->lastName  $member->committeeFunction=$member->committeeFunctionName, $member->party, $member->canton" . ($ok ? ', id=' . $db_member_obj->id : '')  . "\n");
      }
//       print(str_repeat("\t", $level) . ' Kommissionsmitglieder: ' . $kommission->id . ' ' . $kommission->abbreviation . ': ' . $memberNames . "\n");
//       print_r($db_members);
      $db_members_NOK_in_DB = search_objects($db_members, 'status', 'NOK');
      $sign = '-';
      foreach($db_members_NOK_in_DB as $member_NOK_in_DB) {
            $script[] = $comment = "-- Not in_kommission anymore $member_NOK_in_DB->name, $member_NOK_in_DB->abkuerzung=$member_NOK_in_DB->kommission_name, in_kommission_id=$member_NOK_in_DB->in_kommission_id, id=$member_NOK_in_DB->id";
            $script[] = $command = "UPDATE in_kommission SET bis=STR_TO_DATE('$today','%d.%m.%Y'), updated_visa='import', notizen=CONCAT_WS('\\n\\n', '$today/Roland: Update von ws.parlament.ch',`notizen`) WHERE id=$member_NOK_in_DB->in_kommission_id;";
        print(str_repeat("\t", $level) . $sign . " $member_NOK_in_DB->name, in_kommission_id=$member_NOK_in_DB->in_kommission_id id=$member_NOK_in_DB->id\n");
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $comment\n");
            if ($show_sql) print(str_repeat("\t", $level + 1) . "SQL: $command\n");
      }

      foreach($kommission->subcommittees as $subCom) {
        $j++;

        $sign = '';
        $subKommission_db = getKommissionId($subCom->id);
        $subKommission_db_ok = $subKommission_db !== false;
        if($subKommission_db_ok) {
          $sign = '=';
        } else {
          $sign = 'x';
        }

        print(str_repeat("\t", $level) . $j . ". $sign Subkommission: $subCom->id $subCom->abbreviation: $subCom->name, " . $subCom->council->abbreviation . "\n");
        show_members(array($subCom->id), $level + 1);
      }
    }
  }
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

function getRatId($councilType) {
  switch($councilType) {
    case 'N': return 1;
    case 'S': return 2;
    case 'B': return 4;
    default: return "'NULL'";
  }
}

function parlamentarierOhneBiografie() {
  global $db;

  $sql = "SELECT id, vorname, nachname FROM parlamentarier WHERE parlament_biografie_id IS NULL;";
  $stmt = $db->prepare($sql);
  $stmt->execute(array());
  $res = $stmt->fetchAll(PDO::FETCH_CLASS);

print("************************************************\n");
  print("Parlamentarier ohne Biografie-ID:\n");
  $i = 0;
  foreach($res as $obj) {
    $i++;
    print("$i. $obj->vorname $obj->nachname id=$obj->id\n");
  }
  print("Parlamentarier ohne Biografie-ID Ende\n");
  print("************************************************\n\n");
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

function escape_string($string) {
	// return mysql_escape_string($string);
	// mysql_real_escape_string requires the connection

	$replacements = array(
		"\x00" => '\x00',
		"\n" => '\n',
		"\r" => '\r',
		"\\" => '\\\\',
		"'" => "\'",
		'"' => '\"',
		"\x1a" => '\x1a'
	);
	return strtr($string, $replacements);
}
