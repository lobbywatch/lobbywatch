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

include_once dirname(__FILE__) . '/../custom/custom_page.php';


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

    $con = getDBConnectionHandle();
    set_db_session_parameters($con);

    $result = lobbywatch_forms_db_query("SELECT parlamentarier.*
    FROM v_parlamentarier parlamentarier
    WHERE "
     . " (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW()) /* AND parlamentarier.freigabe_datum <= NOW()*/" . "
    ORDER BY parlamentarier.rat, parlamentarier.anzeige_name");

  $markup = '<h1>Parlamentarier Overview</h1>';
  $markup .= '<table border="1" class="tablesorter table-medium header-sticky-enabled">
  <thead>
  <tr><th>Nr</th><th>Name</th><th>ID</th><th>Partei</th><th>Rat</th><th>Kanton</th><th>Interessenbindungen</th></tr>
  </thead>
  <tbody>';

  $i = 0;
  foreach ($result as $record) {
    // while ($record = $result->fetchAssoc()) {
      $active = $record->im_rat_bis == null || $record->im_rat_bis_unix > time();

      $id = $record->id;
      $i++;

      $rowData = get_parlamentarier($con, $id);

      $markup .= '<tr' . (!$record->freigabe_datum_unix || $record->freigabe_datum_unix > time() ? ' class="unpublished"': '') . "><td>$i</td><td>" . (!$active ? '<s>': '') . '<a href="/daten/parlamentarier/' . check_plain($id) . '/' . _lobbywatch_clean_for_url($record->anzeige_name) . '">' . check_plain($record->anzeige_name) . '</a>' . (!$active ? '</s>': '') . '</td><td>' . $id . '</td><td>' . check_plain($record->partei) . '</td><td>' . check_plain($record->rat) . '</td><td>' . check_plain($record->kanton) .
      '</td><td>'. $rowData['interessenbindungen']
      . '</td></tr>';
    }

    $markup .= '</tbody></table>';

    $html = "<html>"
    . "<head>"
    . "<title>Parlamentarier Overview</title>"
    . '<meta name="Generator" content="Hand made" />
    <meta name="viewportXXX" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1">
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="/favicon.png" type="image/png" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="stylesheet" type="text/css" href="components/assets/css/main.css" />
    <link rel="stylesheet" type="text/css" href="components/assets/css/custom/custom.css" />

    <script type="text/javascript" src="components/js/require-config.js"></script>
    <script type="text/javascript" src="components/js/libs/require.js"></script>
    <script type="text/javascript" src="components/js/main-bundle-custom.js"></script>'
    . "<style>
table td, table td * {
    vertical-align: top;
}
body {
  padding-top: 0;
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
