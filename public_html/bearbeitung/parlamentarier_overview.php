<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

/**
 * This file was written quick and dirty. ;-)
 */

//phpinfo();
const DEBUG_LEVEL=1;

require_once dirname(__FILE__) . '/../custom/custom_page.php';


// Main

// lobbywatch_set_language('fr');

// df(lt('test'), 'lt-test');

// df(lobbywatch_lang_field('organisation.name'));
// df(lobbywatch_lang_field('organisation.name_de'));

// lobbywatch_set_language('de');
// df(lobbywatch_lang_field('organisation.name'));
// df(lobbywatch_lang_field('organisation.name_de'));

function check_plain($raw) {
  return $raw;
}

try
{

//     $con_factory = new MyPDOConnectionFactory();
//     $options = GetConnectionOptions();
//     $eng_con = $con_factory->CreateConnection($options);
// //     try {
//       $eng_con->Connect();
//       $con = $eng_con->GetConnectionHandle();
// //         df($eng_con->Connected(), 'connected');
// //         df($con, 'con');
//       $cmd = $con_factory->CreateEngCommandImp();

  date_default_timezone_set('Europe/Zurich');
  $now = date('d.m.Y H:i:s');

  $con = getDBConnectionHandle();
  set_db_session_parameters($con);


  $result = lobbywatch_forms_db_query("SELECT max(stichdatum) stichdatum
  FROM v_parlamentarier_transparenz parlamentarier_transparenz
  LIMIT 1");
  $stichtag = $result->fetchColumn();

  $title = 'Vergütungstransparenzübersicht';
  $markup = "<h1>$title</h1>";
  $markup .= "<table border='1' class='tablesorter table-medium header-sticky-enabled'>
  <thead>
  <tr><th>Nr</th><th>Name</th><th>ID</th><th>Partei</th><th>Rat</th><th>Kanton</th><th title='Beurteilung der Transparenz'>transp. $stichtag</th><th title='0: total intransparent, 0 < x < 1: teilweise transparent, ≥1: voll transparent'>Transparenz<br>(#V / #I<sub>NB</sub>)</th><th title='Anzahl Interessenbindungen'>#I</th><th title='Anzahl nicht berufliche Interessenbindungen'>#I<sub>NB</sub></th><th title='Anzahl erfasste Vergütungen'>#V</th><th title='Anzahl erfasste nicht berufliche Vergütungen'>#V<sub>NB<sub></th><th title='Gesamte Anzahl Interessenbindungen (gültige und beendete)'>#I<sub>all</sub></th><th>Interessenbindungen <small class='desc'>(id)</small></th></tr>
  </thead>
  <tbody>";

  $result = lobbywatch_forms_db_query("SELECT parlamentarier.*
  FROM v_parlamentarier_medium_raw parlamentarier
  WHERE "
    . " (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW()) /* AND parlamentarier.freigabe_datum <= NOW()*/" . "
  ORDER BY parlamentarier.rat, parlamentarier.anzeige_name");

  $i = 0;
  foreach ($result as $record) {
    // while ($record = $result->fetchAssoc()) {
      $active = $record->im_rat_bis == null || $record->im_rat_bis_unix > time();

      $id = $record->id;
      $i++;

      $rowData = get_parlamentarier($con, $id);
      $transparenzData = get_parlamentarier_transparenz($con, $id);

      $markup .= '<tr' . (!$record->freigabe_datum_unix || $record->freigabe_datum_unix > time() ? ' class="unpublished"': '') . "><td>$i</td><td>" . (!$active ? '<s>': '') . '<a href="/daten/parlamentarier/' . check_plain($id) . '/' . _lobbywatch_clean_for_url($record->anzeige_name) . '">' . check_plain($record->anzeige_name) . '</a>' . (!$active ? '</s>': '') . '</td><td>' . $id . '</td><td>' . check_plain($record->partei) . '</td><td>' . check_plain($record->rat) . '</td><td>' . check_plain($record->kanton) .
      "</td><td title='{$transparenzData['stichdatum']}'>" . ($transparenzData['verguetung_transparent'] ?? '-') . "</td><td>" . ($transparenzData['anzahl_nicht_hauptberufliche_interessenbindungen'] > 0 ? round($transparenzData['anzahl_erfasste_verguetungen'] / $transparenzData['anzahl_nicht_hauptberufliche_interessenbindungen'], 2) : '1') . "<td>{$transparenzData['anzahl_interessenbindungen']}</td><td>{$transparenzData['anzahl_nicht_hauptberufliche_interessenbindungen']}</td><td>{$transparenzData['anzahl_erfasste_verguetungen']}</td><td>{$transparenzData['anzahl_erfasste_nicht_hauptberufliche_verguetungen']}</td><td>{$transparenzData['anzahl_interessenbindungen_alle']}</td><td>" . $rowData['interessenbindungen']
      . "</td></tr>\n";
    }

    $markup .= '</tbody></table>';
    $markup .= "<p>Date: $now</p>";
    // $markup .= "<p>TZ: " . date_default_timezone_get() . "</p>";

    $html = "<!DOCTYPE html>\n<html>"
    . "<head>"
    . "<title>$title</title>"
    . '<meta name="Generator" content="Hand made" />
    <meta name="viewportXXX" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1">
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="/favicon.png" type="image/png" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
'
    . "<style>
table {
  border-collapse: collapse;
}

table th, table td, table td * {
    vertical-align: top;
}

ul, ol {
  list-style-position: inner;
}

li {
  margin-left: 1.2em;
}

ul.jahr {
  margin: 0 0 0.5em 0;
  padding: 0;
}

[title] {
  text-decoration: underline dotted;
}

body {
  padding-top: 0;
  background: white;
}
    </style>"
    . "</head>"
    . "<body>"
    . $markup
    . "</body>"
    . "</html>";

    print($html);
}
catch(Exception $e)
{
    ShowErrorPage($e);
}
