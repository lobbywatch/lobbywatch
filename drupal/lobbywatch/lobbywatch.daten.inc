<?php

// Ref https://api.drupal.org/api/examples/ajax_example!ajax_example_autocomplete.inc/7
function _lobbywatch_parlamentarier_autocomplete($str = '', $check_unpublished = true) {

//   return _lobbywatch_parlamentarier_list();
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    $items = array();
    $sql = "SELECT parlamentarier.anzeige_name, parlamentarier.id FROM v_parlamentarier parlamentarier WHERE parlamentarier.im_rat_bis IS NULL AND parlamentarier.anzeige_name LIKE :str" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND parlamentarier.freigabe_datum <= NOW() LIMIT 20' : '');
    $result = db_query($sql, array(':str' => "%$str%"));

  //   dpm($result, 'result');

    while($record = $result->fetchAssoc()) {
      $key = check_plain($record['anzeige_name']) . " [" . check_plain($record['id']) . "]";
      $items[$key] = check_plain($record['anzeige_name']);
    }
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
  }

//   dpm($items, 'items');

  drupal_json_output($items);

  // TODO exit required? it's not in examples
//    exit();
}

/**
 * Builds a page which prints hierarchically the kategory taxonomy terms.
 */
function _lobbywatch_category_list($vid = 2) {
  $markup = '';
//   $markup = t('<p>See also <a href="/en/artikel/bisherige">Article Archive</a>, <a href="/en/schluesselwoerter">Keywords</a>, and <a href="/en/quelle">Sources</a></p>'); //<p>Siehe auch <a href="/artikel/bisherige">Bisherige Artikel</a>, <a href="/schluesselwoerter">Schlüsselwörterübersicht</a> oder <a href="/quelle">Quellenübersicht</a></p>
  $markup .= bergamotte_vocabulary_tree($vid);
//   $markup .= t("<h2>Combinations</h2>") . "\n"; //<h2>Kombinationen</h2>
//   $markup .= "<ul class=\"term-combinations\">\n";
//   $markup .= "<li>" . bergamotte_build_term_with_feed(t('Akteure [Person; Institution]'), array(19, 20)) . "</li>\n";
//   $markup .= "<li>" . bergamotte_build_term_with_feed(t('Akteure [Politik, Regulation & Gesundheitswesen; Multiple Sklerose Gesellschaft; Schweizerische Multiple Sklerose Gesellschaft; DMSG; Pharma; Person; Institution; Patientenerfahrung]'), array(14, 15, 18, 19, 20, 21, 16, 17)) . "</li>\n";
//   $markup .= "</ul>\n";

  $build = array();
  $build['terms'] = array(
    '#weight' => -5,
    '#prefix' => '<div class="category-listing">',
    '#markup' => $markup,
    '#suffix' => '</div>',
   );

  return $build;
}

/**
 * Builds a page which prints hierarchically the kategory taxonomy terms.
 */
function _lobbywatch_category_lobby_group_list($vid = 2) {
  $markup = '';
//   $markup .= t('<p>See also <a href="/en/artikel/bisherige">Article Archive</a>, <a href="/en/schluesselwoerter">Keywords</a>, and <a href="/en/quelle">Sources</a></p>');
  //<p>Siehe auch <a href="/artikel/bisherige">Bisherige Artikel</a>, <a href="/schluesselwoerter">Schlüsselwörterübersicht</a> oder <a href="/quelle">Quellenübersicht</a></p>
  $markup .= bergamotte_vocabulary_tree($vid);
//   $markup .= t("<h2>Combinations</h2>") . "\n"; //<h2>Kombinationen</h2>
//   $markup .= "<ul class=\"term-combinations\">\n";
//   $markup .= "<li>" . bergamotte_build_term_with_feed(t('Akteure [Person; Institution]'), array(19, 20)) . "</li>\n";
//   $markup .= "<li>" . bergamotte_build_term_with_feed(t('Akteure [Politik, Regulation & Gesundheitswesen; Multiple Sklerose Gesellschaft; Schweizerische Multiple Sklerose Gesellschaft; DMSG; Pharma; Person; Institution; Patientenerfahrung]'), array(14, 15, 18, 19, 20, 21, 16, 17)) . "</li>\n";
//   $markup .= "</ul>\n";

  $build = array();
  $build['terms'] = array(
    '#weight' => -5,
    '#prefix' => '<div class="category-lobby-group-listing">',
    '#markup' => $markup,
    '#suffix' => '</div>',
   );

  return $build;
}

/**
 * @deprecated Old list, now we use a view
 */
function _lobbywatch_parlamentarier_list($check_unpublished = true) {
  $markup = t('<p>Liste der Parlamentarier</p>');
  $markup .= '<table border="0"  class="tablesorter table-medium header-sticky-enabled">
  <thead>
  <tr><th>Name</th><th>Photo</th><th>Partei</th><th>Rat</th><th>Kanton</th></tr>
  </thead>
  <tbody>';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    $result = db_query("SELECT parlamentarier.*
    FROM v_parlamentarier parlamentarier
    WHERE "
     . ($check_unpublished = true && !user_access('access lobbywatch unpublished content') ? '(parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW()) AND parlamentarier.freigabe_datum <= NOW()' : '') . "
    ORDER BY parlamentarier.anzeige_name");

    $inactive = !$record['im_rat_bis'] || $record['im_rat_bis'] < time();

    while ($record = $result->fetchAssoc()) {
      $markup .= '<tr' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . '><td>' . ($inactive ? '<s>': '') . '<a href="/daten/parlamentarier/' . check_plain($record['id']) . '/' . _lobbywatch_clean_for_url($record['anzeige_name']) . '">' . check_plain($record['anzeige_name']) . '</a>' . ($inactive ? '</s>': '') . '</td><td><a href="/daten/parlamentarier/' . check_plain($record['id']) . '/' . _lobbywatch_clean_for_url($record['anzeige_name']) . '">' . '<img src="/sites/lobbywatch.ch/app/auswertung/parlamentarierBilder/' . check_plain($record['kleinbild']) . '" alt="' . check_plain($record['anzeige_name']) . '">' . '</a></td><td>' . check_plain($record['partei']) . '</td><td>' . check_plain($record['ratstyp']) . '</td><td>' . check_plain($record['kanton']) . '</td></tr>';
    }

    $markup .= '</tbody></table>';
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
  }

  $build = array();
  $build['terms'] = array(
    '#weight' => -5,
    '#prefix' => '<div class="lobbywatch">',
    '#markup' => $markup,
    '#suffix' => '</div>',
    );

  return $build;

}


function _lobbywatch_page_title($prefix, $suffix, $table, $id) {
  return $prefix . _lobbywatch_fetch_anzeige_name($table, $id) . $suffix;
}


function _lobbywatch_fetch_anzeige_name($table, $id) {
  $url_name = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    $sql = "SELECT * FROM v_$table WHERE id=:id";
    $result = db_query($sql, array(':id' => check_plain($id)));

    if ($result) {
      $record = $result->fetchAssoc();
      $url_name = check_plain($record['anzeige_name']);
    }
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
  }
  return $url_name;
}


function _lobbywatch_anzeige_name_for_url($table, $id) {
  return _lobbywatch_clean_for_url(_lobbywatch_fetch_anzeige_name($table, $id));
}

function _lobbywatch_clean_for_url($name){
  $url_name = preg_replace('/[.,]/i', '', $name);
  $url_name = preg_replace('/[\/]/i', '-', $url_name);
  return check_plain($url_name);
}

// Ref: http://stackoverflow.com/questions/1416697/converting-timestamp-to-time-ago-in-php-e-g-1-day-ago-2-days-ago
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function years($date_str) {
//   dpm($date_str);
  $today = new DateTime();
  $date = new DateTime($date_str);
//   dpm($date);
  $interval  = $today->diff($date);
//   dpm($interval);
  return format_plural($interval->format('%y'), '1 Jahr', '@count Jahre');
}

/**
 *
 * @param unknown $date_str
 * @param string $ref reference date, today if nothing/null is given
 * @return string
 */
function years_months($date_str, $ref = null) {
//   dpm($date_str);
  if ($ref === null) {
    $ref = new DateTime();
  } else {
    $ref = new DateTime($ref);
  }

  $date = new DateTime($date_str);
//   dpm($date);
  $interval  = $ref->diff($date);
//   dpm($interval);
  $years = $interval->format('%y');
  $months = $interval->format('%m');
  return ($years > 0 ? format_plural($years, '1 Jahr', '@count Jahre') : '') . ($months > 0 ? ' ' . format_plural($months, '1 Monat', '@count Monate') : '');
}


function _lobbywatch_gaesteMitMandaten($parlamentarier_id, $only_active_zutrittsberechtigte = true, $check_unpublished = true) {
  $sql = "SELECT zutrittsberechtigung.id, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.funktion,
GROUP_CONCAT(DISTINCT
    CONCAT('<li>', IF(mandat.bis IS NOT NULL AND mandat.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation.id, '\">', organisation.anzeige_name, '</a>',
    IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', CONCAT(UCASE(LEFT(mandat.art, 1)), SUBSTRING(mandat.art, 2)),
    IF(mandat.funktion_im_gremium IS NULL OR TRIM(mandat.funktion_im_gremium) = '', '', CONCAT(', ',CONCAT(UCASE(LEFT(mandat.funktion_im_gremium, 1)), SUBSTRING(mandat.funktion_im_gremium, 2)))),
    IF(mandat.beschreibung IS NULL OR TRIM(mandat.beschreibung) = '', '', CONCAT('<small class=\"desc\">, &quot;', mandat.beschreibung, '&quot;</small>')))
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
) mandate
FROM v_zutrittsberechtigung zutrittsberechtigung
LEFT JOIN v_mandat mandat
  ON mandat.zutrittsberechtigung_id = zutrittsberechtigung.id " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND mandat.freigabe_datum <= NOW() ' : '') . "
LEFT JOIN v_organisation organisation
  ON mandat.organisation_id = organisation.id " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '') . "
WHERE 1
  " . ($only_active_zutrittsberechtigte ? ' AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW())' : '') . "
  AND zutrittsberechtigung.parlamentarier_id=:id
" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND zutrittsberechtigung.freigabe_datum <= NOW()' : '') . "
GROUP BY zutrittsberechtigung.id;";

  $gaeste = array();
//   $sth = $con->prepare($sql);
//   $sth->execute(array(':id' => $parlamentarier_id));
  $result = db_query($sql, array(':id' => $parlamentarier_id));
//  $record = $result->fetchAssoc();

  //$gaeste = $result->fetchAssoc();
  $gaeste = $result->fetchAllAssoc('id', PDO::FETCH_ASSOC);
  //dpm($gaeste, '$gaeste');

  if (!$gaeste) {
    return '<p>keine</p>';
//      throw new Exception('Parlamentarier ID not found');
  }

  $res = '';
  foreach($gaeste as $gast) {
    $res .= '<h4>' . $gast['zutrittsberechtigung_name'] . '</h4>';
    //$res .= mandateList($con, $gast['id']);
    $res .= "<ul>\n" . $gast['mandate'] . "\n</ul>";
  }

  return $res;
}

function _lobbywatch_brancheMitInteressengruppen($kommission_id, $check_unpublished = true) {
  $sql = "SELECT branche.id, branche.anzeige_name as branche_name,
GROUP_CONCAT(DISTINCT
    CONCAT('<li><a href=\"/daten/lobbygruppe/', interessengruppe.id, '\">', interessengruppe.anzeige_name, '</a>',
    IF(interessengruppe.beschreibung IS NULL OR TRIM(interessengruppe.beschreibung) = '', '', CONCAT('<small class=\"desc\">, &quot;', interessengruppe.beschreibung, '&quot;</small>')))
    ORDER BY interessengruppe.anzeige_name
    SEPARATOR ' '
) interessengruppen
FROM `v_branche` branche
LEFT JOIN `v_interessengruppe` interessengruppe
  ON interessengruppe.branche_id = branche.id" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND interessengruppe.freigabe_datum <= NOW() ' : '') . "
WHERE
  branche.kommission_id=:id
" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND branche.freigabe_datum <= NOW() ' : '') . "
GROUP BY branche.id;";

  $branchen = array();
//   $sth = $con->prepare($sql);
//   $sth->execute(array(':id' => $parlamentarier_id));
  $result = db_query($sql, array(':id' => $kommission_id));
//  $record = $result->fetchAssoc();

  //$branchen = $result->fetchAssoc();
  $branchen = $result->fetchAllAssoc('id', PDO::FETCH_ASSOC);
  //dpm($branchen, '$branchen');

  if (!$branchen) {
    return '<p>keine</p>';
//      throw new Exception('Parlamentarier ID not found');
  }

  $res = '';
  foreach($branchen as $branche) {
    $res .= '<h4>' . $branche['branche_name'] . '</h5>';
    //$res .= mandateList($con, $branche['id']);
    $res .= "<ul>\n" . $branche['interessengruppen'] . "\n</ul>";
  }

//   dpm($res, 'branchen');

  return $res;
}


function _lobbywatch_parlamentarier_id($id, $name='', $check_unpublished = true) {

//   dpm($id, 'id');

  $url_name = _lobbywatch_anzeige_name_for_url('parlamentarier', $id);
  if ($name !== $url_name) {
    drupal_goto("daten/parlamentarier/$id/$url_name");
  }

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    $sql = "SET SESSION group_concat_max_len=10000;";
    db_query($sql);

    $sql = "SELECT parlamentarier.id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.geburtstag, DATE_FORMAT(parlamentarier.geburtstag, '%d.%m.%Y') as geburtstag_formatted, parlamentarier.beruf, parlamentarier.im_rat_seit, DATE_FORMAT(parlamentarier.im_rat_seit, '%d.%m.%Y') as im_rat_seit_formatted, parlamentarier.im_rat_bis, DATE_FORMAT(parlamentarier.im_rat_bis, '%d.%m.%Y') as im_rat_bis_formatted, parlamentarier.kleinbild, parlamentarier.ratstyp, parlamentarier.kanton, parlamentarier.geschlecht, parlamentarier.homepage, parlamentarier.parlament_biografie_id, parlamentarier.partei, parlamentarier.partei_id,
    parlamentarier.name, parlamentarier.anzahl_kinder, rat.name_de as rat_name_de, kanton.einwohner, kanton.auslaenderanteil, kanton.anzahl_staenderaete, kanton.anzahl_nationalraete, parlamentarier.updated_date, parlamentarier.updated_date_unix, parlamentarier.updated_visa, parlamentarier.created_date, parlamentarier.created_date_unix, parlamentarier.created_visa, parlamentarier.eingabe_abgeschlossen_datum, parlamentarier.eingabe_abgeschlossen_datum_unix, parlamentarier.eingabe_abgeschlossen_visa, parlamentarier.freigabe_datum, parlamentarier.freigabe_datum_unix, parlamentarier.freigabe_visa, parlamentarier.kontrolliert_datum, parlamentarier.kontrolliert_datum_unix, parlamentarier.kontrolliert_visa,
    GROUP_CONCAT(DISTINCT
      CONCAT('<li>', IF(interessenbindung.bis IS NOT NULL AND interessenbindung.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation.id, '\">', organisation.anzeige_name, '</a>',
      IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)),
      IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', CONCAT(UCASE(LEFT(interessenbindung.art, 1)), SUBSTRING(interessenbindung.art, 2)),
      IF(interessenbindung.funktion_im_gremium IS NULL OR TRIM(interessenbindung.funktion_im_gremium) = '', '', CONCAT(', ',CONCAT(UCASE(LEFT(interessenbindung.funktion_im_gremium, 1)), SUBSTRING(interessenbindung.funktion_im_gremium, 2)))),
      IF(TRUE OR interessenbindung.beschreibung IS NULL OR TRIM(interessenbindung.beschreibung) = '', '', CONCAT(', ',interessenbindung.beschreibung)),
      IF(interessenbindung.bis IS NOT NULL AND interessenbindung.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(interessenbindung.bis, '%Y'), '</s>'), ''))
      ORDER BY organisation.anzeige_name
      SEPARATOR ' '
    ) interessenbindungen,
    GROUP_CONCAT(DISTINCT
      CONCAT('<li>', IF(v.bis IS NOT NULL AND v.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation2.id, '\">', organisation2.anzeige_name, '</a>',
      IF(organisation2.rechtsform IS NULL OR TRIM(organisation2.rechtsform) = '', '', CONCAT(', ', organisation2.rechtsform)),
      IF(organisation2.ort IS NULL OR TRIM(organisation2.ort) = '', '', CONCAT(', ', organisation2.ort)),
      IF(FALSE AND v.beziehung = 'indirekt', CONCAT(', <em>', v.beziehung, '</em>'), ''),
      IF(v.bis IS NOT NULL AND v.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(v.bis, '%Y'), '</s>'), '')
      )
      ORDER BY organisation2.anzeige_name
      SEPARATOR ' '
    ) verbundene_organisationen,
    GROUP_CONCAT(DISTINCT
      CONCAT('<li>', IF(ik.bis IS NOT NULL AND ik.bis < NOW(), '<s>', ''), '<a href=\"/daten/kommission/', kommission.id, '\">',
      kommission.name, ' (', kommission.abkuerzung, ')</a> ',
      IF(kommission.art IS NULL, '', CONCAT(', ', kommission.art)),
      IF(ik.bis IS NOT NULL AND ik.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(ik.bis, '%Y'), '</s>'), '')
      )
      ORDER BY kommission.abkuerzung
      SEPARATOR ' '
      ) kommissionen_namen,
    GROUP_CONCAT(DISTINCT
      CONCAT('<li>', IF(zutrittsberechtigung.bis IS NOT NULL AND zutrittsberechtigung.bis < NOW(), '<s>', ''), '<a href=\"/daten/zutrittsberechtigter/', zutrittsberechtigung.id, '\">',
      zutrittsberechtigung.name, '</a>, ',
      zutrittsberechtigung.funktion,
      IF(zutrittsberechtigung.beruf IS NULL OR TRIM(zutrittsberechtigung.beruf) = '', '', CONCAT(', ', zutrittsberechtigung.beruf)),
      IF(zutrittsberechtigung.bis IS NOT NULL AND zutrittsberechtigung.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(zutrittsberechtigung.bis, '%Y'), '</s>'), ''))
      ORDER BY zutrittsberechtigung.name
      SEPARATOR ' '
    ) zutrittsberechtigungen
      FROM v_parlamentarier parlamentarier
      LEFT JOIN v_interessenbindung interessenbindung
      ON interessenbindung.parlamentarier_id = parlamentarier.id" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND interessenbindung.freigabe_datum <= NOW() ' : '') . "
      LEFT JOIN v_organisation organisation
      ON interessenbindung.organisation_id = organisation.id" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '') . "
      LEFT JOIN v_zutrittsberechtigung zutrittsberechtigung
      ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND zutrittsberechtigung.freigabe_datum <= NOW() ' : '') . "
      LEFT JOIN v_in_kommission ik
      ON parlamentarier.id = ik.parlamentarier_id" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND ik.freigabe_datum <= NOW() ' : '') . "
      LEFT JOIN v_kommission kommission
      ON ik.kommission_id=kommission.id" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND kommission.freigabe_datum <= NOW() ' : '') . "
      LEFT JOIN v_organisation_parlamentarier_beide_indirekt v
      ON v.parlamentarier_id = parlamentarier.id" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND v.freigabe_datum <= NOW() ' : '') . "
      LEFT JOIN v_organisation organisation2
      ON organisation2.id = v.connector_organisation_id" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation2.freigabe_datum <= NOW() ' : '') . "
      LEFT JOIN v_kanton kanton
      ON kanton.id = parlamentarier.kanton_id" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND kanton.freigabe_datum <= NOW() ' : '') . "
      LEFT JOIN v_rat rat
      ON rat.id = parlamentarier.rat_id " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND rat.freigabe_datum <= NOW() ' : '') . "
      WHERE
      parlamentarier.id=:id"
           . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND parlamentarier.freigabe_datum <= NOW() ' : '') . "
      GROUP BY parlamentarier.id";

    $result = db_query($sql, array(':id' => $id));

  //     dpm($result, 'result');

      $record = $result->fetchAssoc();
      $gaeste = _lobbywatch_gaesteMitMandaten($id, $check_unpublished);

  //     dpm($record, '$record');

  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
  }

  if ($record) {
      $rat = check_plain($record['rat_name_de']);
      $ratstyp = check_plain($record['ratstyp']);
      if ($ratstyp == 'SR') {
        $vertretung = round($record['einwohner'] / $record['anzahl_staenderaete']);
      } else {
        $vertretung = round($record['einwohner'] / $record['anzahl_nationalraete']);
      }
      // TODO security, check content
      $parlamentarier_name = check_plain($record["parlamentarier_name"]);
      $active = $record['im_rat_bis'] == null;
      $markup .=
      (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . t('Unpublished') . '</p>': '<div>') .
//       '<h2>' . $parlamentarier_name . '</h2>' .
      '<img src="/sites/lobbywatch.ch/app/auswertung/parlamentarierBilder/' . check_plain($record['kleinbild']) . '" alt="' . $parlamentarier_name . '">' .
      (!$active ? '<p><strong><em>Nicht mehr im Parlament. War im Parlament bis ' . check_plain($record['im_rat_bis_formatted']) . '.</em></strong></p>' : '') .
      (user_access('access lobbywatch unpublished content') ? '<p class="unpublished">Lobbyfaktor: <b>XY</b></p>' : '') .
      '<table border="0">' .
      '<tr><td>Rat:</td><td>' . $rat . '</td></tr>' .
      '<tr><td>Kanton:</td><td>' . check_plain($record['kanton']) . '</td></tr>' .
      '<tr><td>Partei:</td><td>' . l($record['partei'], 'daten/partei/' . $record['partei_id']) . '</td></tr>' .
      '<tr><td>Vertretene Bevölkerung:</td><td>' . number_format($vertretung , 0 , '.' , '\'') . '</td></tr>' .
      '<tr><td>' . ($active ? 'Im Parlament seit:' : 'Dauer:') . '</td><td>' . years_months(check_plain($record['im_rat_seit']), $record['im_rat_bis']) . ' (' . check_plain($record['im_rat_seit_formatted']) . ($active ? '' : ' – ' . check_plain($record['im_rat_bis_formatted']) ) . ')' . '</td></tr>' .
      '<tr><td>Alter:</td><td>' . years(check_plain($record['geburtstag'])) . ' (' . check_plain($record['geburtstag_formatted']) . ')' . '</td></tr>' .
      '<tr><td>Beruf:</td><td>' . check_plain($record['beruf']) . '</td></tr>' .
      '<tr><td>Kinder:</td><td>' . check_plain($record['anzahl_kinder']) . '</td></tr>' .
      '<tr><td>Homepage:</td><td>' . ($record['homepage'] ? l(preg_replace('|https?://|i', '', $record['homepage']), $record['homepage'], array('attributes' => array('class' => array('elf-external', 'elf-icon')))) : '') . '</td></tr>' .
      '<tr><td>Parlament.ch Webseite:</td><td>' . ($record['parlament_biografie_id'] ? l($record['name'], 'http://www.parlament.ch/d/suche/seiten/biografie.aspx?biografie_id=' . $record['parlament_biografie_id'], array('attributes' => array('class' => array('elf-external', 'elf-icon')))) : '' ) . '</td></tr>' .
      '</table>' .
      '<h3>Kommissionen</h3><ul>' . $record['kommissionen_namen'] . '</ul>' .
      '<h3>Interessenbindungen</h3><ul>' . $record['interessenbindungen'] . '</ul>' .
      '<h3>Gäste</h3><ul>' . $record['zutrittsberechtigungen'] . '</ul>' .
      '<h3>Mandate der Gäste</h3>' . $gaeste .
      '<h3>Organisationen mit einer Verbindung</h3>'
      . '<p>Auch indirekte Verbindungen werden aufgeführt.</p>'
      . '<ul>' . $record['verbundene_organisationen'] . '</ul>'
      . _lobbywatch_get_workflow_state_line($record)
      . '</div>'
      ;

    $build = array();
    $build['terms'] = array(
      '#weight' => -5,
      '#prefix' => '<div class="lobbywatch">',
      '#markup' => $markup,
      '#suffix' => '</div>',
      );

      return $build;
  } else {
    //drupal_goto('daten/parlamentarier');
    drupal_not_found();
  }
}

/**
 * TODO
 */
function _lobbywatch_organisation_list($check_unpublished = true) {
  $markup = t('<p>Liste der Organisationen</p>');
  $markup .= '<ul>';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  $result = db_query("SELECT * FROM v_organisation organisation  WHERE 1 " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '') . "  ORDER BY organisation.anzeige_name");

  while ($record = $result->fetchAssoc()) {
    $markup .=
    '<li' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . '><a href="/daten/organisation/' . check_plain($record['id']) . '">' . check_plain($record['anzeige_name']) . '</a>' . (check_plain($record['rechtsform']) ? ', ' . check_plain($record['rechtsform']) : '');
  }

  $markup .= '</ul>';

  // Go back to the default database,
  // otherwise Drupal will not be able to access it's own data later on.
  db_set_active();

  $build = array();
  $build['terms'] = array(
    '#weight' => -5,
    '#prefix' => '<div class="lobbywatch">',
    '#markup' => $markup,
    '#suffix' => '</div>',
    );

    return $build;
}

function _lobbywatch_organisation_id($id, $name = '', $check_unpublished = true) {

  //   dpm($id, 'id');

  $url_name = _lobbywatch_anzeige_name_for_url('organisation', $id);
  if ($name !== $url_name) {
    drupal_goto("daten/organisation/$id/$url_name");
  }

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  /*
-- Organisationen für welche eine PR-Agentur arbeitet.
-- Connector: organisation_beziehung.organisation_id
CREATE OR REPLACE VIEW `v_organisation_beziehung_arbeitet_fuer` AS
SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.art, organisation_beziehung.von, organisation_beziehung.bis, organisation.*
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation organisation
  ON organisation_beziehung.ziel_organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer'
ORDER BY organisation.anzeige_name;

-- Organisationen, die eine PR-Firma beauftragt haben.
-- Connector: organisation_beziehung.ziel_organisation_id
-- Reverse Beziehung
CREATE OR REPLACE VIEW `v_organisation_beziehung_auftraggeber_fuer` AS
SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.art, organisation_beziehung.von, organisation_beziehung.bis, organisation.*
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation organisation
  ON organisation_beziehung.organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer'
ORDER BY organisation.anzeige_name;

-- Organisationen, in welcher eine Organisation Mitglied ist.
-- Connector: organisation_beziehung.organisation_id
CREATE OR REPLACE VIEW `v_organisation_beziehung_mitglied_von` AS
SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.art, organisation_beziehung.von, organisation_beziehung.bis, organisation.*
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation organisation
  ON organisation_beziehung.ziel_organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'mitglied von'
ORDER BY organisation.anzeige_name;

-- Mitgliedsorganisationen
-- Connector: organisation_beziehung.ziel_organisation_id
-- Reverse Beziehung
CREATE OR REPLACE VIEW `v_organisation_beziehung_mitglieder` AS
SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.art, organisation_beziehung.von, organisation_beziehung.bis, organisation.*
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation organisation
  ON organisation_beziehung.organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'mitglied von'
ORDER BY organisation.anzeige_name;

-- Muttergesellschaften.
-- Connector: organisation_beziehung.organisation_id
CREATE OR REPLACE VIEW `v_organisation_beziehung_muttergesellschaft` AS
SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.art, organisation_beziehung.von, organisation_beziehung.bis, organisation.*
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation organisation
  ON organisation_beziehung.ziel_organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'tochtergesellschaft von'
ORDER BY organisation.anzeige_name;

-- Tochtergesellschaften
-- Connector: organisation_beziehung.ziel_organisation_id
-- Reverse Beziehung
CREATE OR REPLACE VIEW `v_organisation_beziehung_tochtergesellschaften` AS
SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.art, organisation_beziehung.von, organisation_beziehung.bis, organisation.*
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation organisation
  ON organisation_beziehung.organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'tochtergesellschaft von'
ORDER BY organisation.anzeige_name;
*/

  try {
    $sql = "SET SESSION group_concat_max_len=10000;";
    db_query($sql);

    $sql_arbeitet = "SELECT organisation.*,
      GROUP_CONCAT(DISTINCT
        CONCAT('<li>', IF((v.bis IS NOT NULL AND v.bis < NOW()) OR (parlamentarier.im_rat_bis IS NOT NULL AND parlamentarier.im_rat_bis < NOW()), '<s>', ''), '<a href=\"/daten/parlamentarier/', parlamentarier.id, '\">', parlamentarier.anzeige_name, '</a>',
        ', ', parlamentarier.ratstyp,
        IF(parlamentarier.partei IS NULL, '', CONCAT(', ', parlamentarier.partei)),
        ', ', parlamentarier.kanton,
        IF(FALSE AND v.beziehung = 'indirekt', CONCAT(', <em>', v.beziehung, '</em>'), ''),
        IF((v.bis IS NOT NULL AND v.bis < NOW()) OR (parlamentarier.im_rat_bis IS NOT NULL AND parlamentarier.im_rat_bis < NOW()), CONCAT(', bis ', DATE_FORMAT(LEAST(IFNULL(v.bis, parlamentarier.im_rat_bis), IFNULL(parlamentarier.im_rat_bis, v.bis)), '%Y'), IF(parlamentarier.im_rat_bis IS NOT NULL AND parlamentarier.im_rat_bis < NOW(), ' im Rat', ''), '</s>'), '')
        )
        ORDER BY parlamentarier.anzeige_name
        SEPARATOR ' '
      ) verbundene_parlamentarier,
      GROUP_CONCAT(DISTINCT
        CONCAT('<li>', IF(organisation_arbeitet_fuer.bis IS NOT NULL AND organisation_arbeitet_fuer.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation_arbeitet_fuer.id, '\">', organisation_arbeitet_fuer.anzeige_name, '</a>',
        IF(organisation_arbeitet_fuer.rechtsform IS NULL OR TRIM(organisation_arbeitet_fuer.rechtsform) = '', '', CONCAT(', ', organisation_arbeitet_fuer.rechtsform)),
        IF(organisation_arbeitet_fuer.ort IS NULL OR TRIM(organisation_arbeitet_fuer.ort) = '', '', CONCAT(', ', organisation_arbeitet_fuer.ort)),
        IF(organisation_arbeitet_fuer.bis IS NOT NULL AND organisation_arbeitet_fuer.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(organisation_arbeitet_fuer.bis, '%Y'), '</s>'), '')
        )
        ORDER BY organisation_arbeitet_fuer.anzeige_name
        SEPARATOR ' '
      ) arbeitet_fuer,
      GROUP_CONCAT(DISTINCT
        CONCAT('<li>', IF(organisation_auftraggeber_fuer.bis IS NOT NULL AND organisation_auftraggeber_fuer.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation_auftraggeber_fuer.id, '\">', organisation_auftraggeber_fuer.anzeige_name, '</a>',
        IF(organisation_auftraggeber_fuer.rechtsform IS NULL OR TRIM(organisation_auftraggeber_fuer.rechtsform) = '', '', CONCAT(', ', organisation_auftraggeber_fuer.rechtsform)),
        IF(organisation_auftraggeber_fuer.ort IS NULL OR TRIM(organisation_auftraggeber_fuer.ort) = '', '', CONCAT(', ', organisation_auftraggeber_fuer.ort)),
        IF(organisation_auftraggeber_fuer.bis IS NOT NULL AND organisation_auftraggeber_fuer.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(organisation_auftraggeber_fuer.bis, '%Y'), '</s>'), '')
        )
        ORDER BY organisation_auftraggeber_fuer.anzeige_name
        SEPARATOR ' '
      ) auftraggeber_fuer
  FROM v_organisation organisation
  LEFT JOIN v_organisation_parlamentarier_beide_indirekt v
  ON v.connector_organisation_id = organisation.id " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND v.freigabe_datum <= NOW() ' : '') . "
  LEFT JOIN v_parlamentarier parlamentarier
  ON parlamentarier.id = v.parlamentarier_id " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND parlamentarier.freigabe_datum <= NOW() ' : '') . "
  LEFT JOIN v_organisation_beziehung_arbeitet_fuer organisation_arbeitet_fuer
  ON organisation_arbeitet_fuer.organisation_id = organisation.id " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation_arbeitet_fuer.freigabe_datum <= NOW() ' : '') . "
  LEFT JOIN v_organisation_beziehung_auftraggeber_fuer organisation_auftraggeber_fuer
  ON organisation_auftraggeber_fuer.ziel_organisation_id = organisation.id " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation_auftraggeber_fuer.freigabe_datum <= NOW() ' : '') . "
  WHERE organisation.id=:id "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '') . "
  GROUP BY organisation.id";

    $sql_mitglied = "SELECT organisation.*,
      GROUP_CONCAT(DISTINCT
        CONCAT('<li>', IF(organisation_mitglied_von.bis IS NOT NULL AND organisation_mitglied_von.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation_mitglied_von.id, '\">', organisation_mitglied_von.anzeige_name, '</a>',
        IF(organisation_mitglied_von.rechtsform IS NULL OR TRIM(organisation_mitglied_von.rechtsform) = '', '', CONCAT(', ', organisation_mitglied_von.rechtsform)),
        IF(organisation_mitglied_von.ort IS NULL OR TRIM(organisation_mitglied_von.ort) = '', '', CONCAT(', ', organisation_mitglied_von.ort)),
        IF(organisation_mitglied_von.bis IS NOT NULL AND organisation_mitglied_von.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(organisation_mitglied_von.bis, '%Y'), '</s>'), '')
        )
        ORDER BY organisation_mitglied_von.anzeige_name
        SEPARATOR ' '
      ) mitglied_von,
      GROUP_CONCAT(DISTINCT
        CONCAT('<li>', IF(organisation_mitglieder.bis IS NOT NULL AND organisation_mitglieder.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation_mitglieder.id, '\">', organisation_mitglieder.anzeige_name, '</a>',
        IF(organisation_mitglieder.rechtsform IS NULL OR TRIM(organisation_mitglieder.rechtsform) = '', '', CONCAT(', ', organisation_mitglieder.rechtsform)),
        IF(organisation_mitglieder.ort IS NULL OR TRIM(organisation_mitglieder.ort) = '', '', CONCAT(', ', organisation_mitglieder.ort)),
        IF(organisation_mitglieder.bis IS NOT NULL AND organisation_mitglieder.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(organisation_mitglieder.bis, '%Y'), '</s>'), '')
        )
        ORDER BY organisation_mitglieder.anzeige_name
        SEPARATOR ' '
      ) mitglieder
  FROM v_organisation organisation
  LEFT JOIN v_organisation_beziehung_mitglied_von organisation_mitglied_von
  ON organisation_mitglied_von.organisation_id = organisation.id " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation_mitglied_von.freigabe_datum <= NOW() ' : '') . "
  LEFT JOIN v_organisation_beziehung_mitglieder organisation_mitglieder
  ON organisation_mitglieder.ziel_organisation_id = organisation.id " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation_mitglieder.freigabe_datum <= NOW() ' : '') . "
  WHERE organisation.id=:id "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '') . "
  GROUP BY organisation.id";

     $sql_tochter = "SELECT organisation.*,
      GROUP_CONCAT(DISTINCT
        CONCAT('<li>', IF(organisation_muttergesellschaft.bis IS NOT NULL AND organisation_muttergesellschaft.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation_muttergesellschaft.id, '\">', organisation_muttergesellschaft.anzeige_name, '</a>',
        IF(organisation_muttergesellschaft.rechtsform IS NULL OR TRIM(organisation_muttergesellschaft.rechtsform) = '', '', CONCAT(', ', organisation_muttergesellschaft.rechtsform)),
        IF(organisation_muttergesellschaft.ort IS NULL OR TRIM(organisation_muttergesellschaft.ort) = '', '', CONCAT(', ', organisation_muttergesellschaft.ort)),
        IF(organisation_muttergesellschaft.bis IS NOT NULL AND organisation_muttergesellschaft.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(organisation_muttergesellschaft.bis, '%Y'), '</s>'), '')
        )
        ORDER BY organisation_muttergesellschaft.anzeige_name
        SEPARATOR ' '
      ) muttergesellschaft,
      GROUP_CONCAT(DISTINCT
        CONCAT('<li>', IF(organisation_tochtergesellschaften.bis IS NOT NULL AND organisation_tochtergesellschaften.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation_tochtergesellschaften.id, '\">', organisation_tochtergesellschaften.anzeige_name, '</a>',
        IF(organisation_tochtergesellschaften.rechtsform IS NULL OR TRIM(organisation_tochtergesellschaften.rechtsform) = '', '', CONCAT(', ', organisation_tochtergesellschaften.rechtsform)),
        IF(organisation_tochtergesellschaften.ort IS NULL OR TRIM(organisation_tochtergesellschaften.ort) = '', '', CONCAT(', ', organisation_tochtergesellschaften.ort)),
        IF(organisation_tochtergesellschaften.bis IS NOT NULL AND organisation_tochtergesellschaften.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(organisation_tochtergesellschaften.bis, '%Y'), '</s>'), '')
        )
        ORDER BY organisation_tochtergesellschaften.anzeige_name
        SEPARATOR ' '
      ) tochtergesellschaften
  FROM v_organisation organisation
  LEFT JOIN v_organisation_beziehung_muttergesellschaft organisation_muttergesellschaft
  ON organisation_muttergesellschaft.organisation_id = organisation.id" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation_muttergesellschaft.freigabe_datum <= NOW() ' : '') . "
  LEFT JOIN v_organisation_beziehung_tochtergesellschaften organisation_tochtergesellschaften
  ON organisation_tochtergesellschaften.ziel_organisation_id = organisation.id " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation_tochtergesellschaften.freigabe_datum <= NOW() ' : '') . "
  WHERE organisation.id=:id "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '') . "
  GROUP BY organisation.id";

  $result_arbeitet = db_query($sql_arbeitet, array(':id' => $id));
  $record = $result_arbeitet->fetchAssoc();

//   dpm($record_arbeitet, '$record_arbeitet');

  $result_mitglied = db_query($sql_mitglied, array(':id' => $id));
  $record_mitglied = $result_mitglied->fetchAssoc();
  $record['mitglied_von'] = $record_mitglied['mitglied_von'];
  $record['mitglieder'] = $record_mitglied['mitglieder'];

//   dpm($record_mitglied, '$record_mitglied');

  $result_tochter = db_query($sql_tochter, array(':id' => $id));
  $record_tochter = $result_tochter->fetchAssoc();
  $record['muttergesellschaft'] = $record_tochter['muttergesellschaft'];
  $record['tochtergesellschaften'] = $record_tochter['tochtergesellschaften'];

//   dpm($record_tochter, '$record_tochter');

//   $record = array_merge($record_arbeitet, $record_mitglied, $record_tocher);

//      dpm($record, '$record');
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
  }
  if (isset($record['id'])) {
    $markup .=
    (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . t('Unpublished') . '</p>': '<div>') .
//    '<h2>' . check_plain($record["anzeige_name"]) . '</h2>' .
//     . '<h3>Informationen</h3>'
    '<table border="0">'
//     . '<li>Vernehmlassung: ' . $record['vernehmlassung']
    . '<tr><td>Rechtsform</td><td>' . check_plain($record['rechtsform']) . '</td></tr>'
    . '<tr><td>Ort</td><td>' . check_plain($record['ort']) . '</td></tr>'
    . '<tr><td>Branche</td><td>' . l($record['branche'], 'daten/branche/' . $record['branche_id']) . '</td></tr>'
    . '<tr><td>Lobbygruppe</td><td>' . l($record['interessengruppe'], 'daten/lobbygruppe/' . $record['interessengruppe_id']) . '</td></tr>'
    . '<tr><td>Homepage:</td><td>' . ($record['homepage'] ? l(preg_replace('|https?://|i', '', $record['homepage']), $record['homepage'], array('attributes' => array('class' => array('elf-external', 'elf-icon')))) : '') . '</td></tr>'
    . '<tr><td>Handelsregister:</td><td>' . ($record['handelsregister_url'] ? l($record['anzeige_name'], $record['handelsregister_url'], array('attributes' => array('class' => array('elf-external', 'elf-icon')))) : '') . '</td></tr>'
    . '</table>';
    if ($record['arbeitet_fuer']) {
      $markup .= '<h3>Arbeitet für</h3>'
      . '<ul>'
      . $record['arbeitet_fuer']
      . '</ul>';
//       dpm($record['arbeitet_fuer'], 'arbeitet_fuer');
    }
    if ($record['auftraggeber_fuer']) {
      $markup .= '<h3>Auftraggeber für</h3>'
      . '<ul>'
      . $record['auftraggeber_fuer']
      . '</ul>';
//       dpm($record['auftraggeber_fuer'], 'auftraggeber_fuer');
    }
    if ($record['mitglied_von']) {
      $markup .= '<h3>Mitglied von</h3>'
      . '<ul>'
      . $record['mitglied_von']
      . '</ul>';
//       dpm($record['mitglied_von'], 'mitglied_von');
    }
    if ($record['mitglieder']) {
      $markup .= '<h3>Mitglieder</h3>'
      . '<ul>'
      . $record['mitglieder']
      . '</ul>';
//       dpm($record['mitglieder'], 'mitglieder');
    }
    if ($record['muttergesellschaft']) {
      $markup .= '<h3>Muttergesellschaft</h3>'
      . '<ul>'
      . $record['muttergesellschaft']
      . '</ul>';
//       dpm($record['muttergesellschaft'], 'muttergesellschaft');
    }
    if ($record['tochtergesellschaften']) {
      $markup .= '<h3>Tochtergesellschaften</h3>'
      . '<ul>'
      . $record['tochtergesellschaften']
      . '</ul>';
//       dpm($record['tochtergesellschaften'], 'tochtergesellschaften');
    }
    if ($record['verbundene_parlamentarier']) {
      $markup .= '<h3>Parlamentarierverbindungen</h3>'
      . '<ul>'
      . $record['verbundene_parlamentarier']
      . '</ul>';
//       dpm($record['verbundene_parlamentarier'], 'verbundene_parlamentarier');
    }

    $markup .= '</div>';


    $build = array();
    $build['terms'] = array(
      '#weight' => -5,
      '#prefix' => '<div class="lobbywatch">',
      '#markup' => $markup,
      '#suffix' => '</div>',
      );

      return $build;
  } else {
//     drupal_goto('daten/organisation');
    drupal_not_found();
  }
}

/**
 * TODO
 */
function _lobbywatch_kommission_list($check_unpublished = true) {
  $markup = t('<p>Liste der Kommissionen</p>');
  $markup .= '<ul>';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  $result = db_query("SELECT kommission.*
  FROM `v_kommission` kommission  WHERE 1 " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND kommission.freigabe_datum <= NOW() ' : '') . "
  ORDER BY kommission.anzeige_name");

  while ($record = $result->fetchAssoc()) {
    $markup .= '<li' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . '><a href="/daten/kommission/' . check_plain($record['id']) . '">' . check_plain($record['anzeige_name']) . '</a>';
  }

  $markup .= '</ul>';

  // Go back to the default database,
  // otherwise Drupal will not be able to access it's own data later on.
  db_set_active();

  $build = array();
  $build['terms'] = array(
    '#weight' => -5,
    '#prefix' => '<div class="lobbywatch">',
    '#markup' => $markup,
    '#suffix' => '</div>',
    );

    return $build;
}

function _lobbywatch_kommission_id_SQL($only_active_parlamentarier, $check_unpublished = true) {
  return "SELECT kommission.*,
      GROUP_CONCAT(DISTINCT
        CONCAT('<li><a href=\"/daten/parlamentarier/', parlamentarier.parlamentarier_id, '\">', parlamentarier.parlamentarier_name, '</a>',
        ', ', parlamentarier.ratstyp,
        IF(parlamentarier.partei IS NULL, '', CONCAT(', ', parlamentarier.partei)),
        ', ', parlamentarier.kanton " .
//       ,  IF((parlamentarier.bis IS NOT NULL AND parlamentarier.bis < NOW()) OR (parlamentarier.im_rat_bis IS NOT NULL AND parlamentarier.im_rat_bis < NOW()), CONCAT(', bis ', DATE_FORMAT(LEAST(IFNULL(parlamentarier.bis, parlamentarier.im_rat_bis), IFNULL(parlamentarier.im_rat_bis, parlamentarier.bis)), '%Y'), IF(parlamentarier.im_rat_bis IS NOT NULL AND parlamentarier.im_rat_bis < NOW(), ' im Rat', '')), '')
"        )
        ORDER BY parlamentarier.parlamentarier_name
        SEPARATOR ' '
      ) parlamentarier,
      GROUP_CONCAT(DISTINCT
        CONCAT('<li><a href=\"/daten/branche/', branche.id, '\">', branche.anzeige_name, '</a>'
        )
        ORDER BY branche.anzeige_name
        SEPARATOR ' '
      ) branchen
  FROM `v_kommission` kommission
  LEFT JOIN `v_in_kommission_parlamentarier` parlamentarier
  ON kommission.id = parlamentarier.kommission_id " . ($only_active_parlamentarier ? " AND (parlamentarier.bis IS NULL OR parlamentarier.bis > NOW()) AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())" : " AND (parlamentarier.bis IS NOT NULL AND parlamentarier.bis < NOW()) OR (parlamentarier.im_rat_bis IS NOT NULL AND parlamentarier.im_rat_bis < NOW()) ") . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND parlamentarier.freigabe_datum <= NOW() ' : '') . "
  LEFT JOIN `v_branche` branche
  ON kommission.id = branche.kommission_id " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND branche.freigabe_datum <= NOW() ' : '') . "
  WHERE kommission.id=:id "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND kommission.freigabe_datum <= NOW() ' : '') . "
  GROUP BY kommission.id";
}

function _lobbywatch_kommission_id($id, $name = '') {

  //   dpm($id, 'id');

  $url_name = _lobbywatch_anzeige_name_for_url('kommission', $id);
  if ($name !== $url_name) {
    drupal_goto("daten/kommission/$id/$url_name");
  }

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    $sql = "SET SESSION group_concat_max_len=10000;";
    db_query($sql);

    $sql = _lobbywatch_kommission_id_SQL(true);

  // IF(organisation_auftraggeber_fuer.bis IS NOT NULL AND organisation_auftraggeber_fuer.bis < NOW(), '<s>', ''),

  $result = db_query($sql, array(':id' => $id));

  //     dpm($result, 'result');

  $record_active = $result->fetchAssoc();

  $sql = _lobbywatch_kommission_id_SQL(false);

  // IF(organisation_auftraggeber_fuer.bis IS NOT NULL AND organisation_auftraggeber_fuer.bis < NOW(), '<s>', ''),

  $result = db_query($sql, array(':id' => $id));

  //     dpm($result, 'result');

  $record_inactive = $result->fetchAssoc();

  $interessengruppen = _lobbywatch_brancheMitInteressengruppen($id);
//   dpm($record, '$record');
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
  }
  if ($record_active) {
    $markup .=
    (!$record_active['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . t('Unpublished') . '</p>': '<div>') .
//      '<h2>' . check_plain($record_active["anzeige_name"]) . '</h2>' .
    '<table border="0">'
    . '<tr><td>Abkürzung</td><td>' . check_plain($record_active['abkuerzung']) . '</td></tr>'
    . '<tr><td>Parlament.ch Link</td><td>' . (isset($record_active['parlament_url']) ? l($record_active['anzeige_name'], $record_active['parlament_url'], array('attributes' => array('class' => array('elf-external', 'elf-icon')))) : '') . '</td></tr>'
    . '<tr><td>Beschreibung</td><td>' . check_plain($record_active['beschreibung']) . '</td></tr>'
    . '<tr><td>Typ</td><td>' . check_plain($record_active['typ']) . '</td></tr>'
    . '<tr><td>Art</td><td>' . check_plain($record_active['art']) . '</td></tr>'
    . '</table>'
    . '<h3>Sachbereiche</h3>'
    . '<ul>'
    . implode(array_map(function($value) {return '<li>' . $value;}, explode(';', check_plain($record_active['sachbereiche']))))
    . '</ul>'
    // TODO präsidium
    . '<h3>Mitglieder</h3>'
    . '<ul>'
    . $record_active['parlamentarier']
    . '</ul>'
    . '<h3>Branchen</h3>'
    . '<ul>'
    . $record_active['branchen']
    . '</ul>'
    . '<h3>Lobbygruppen</h3>'
    . $interessengruppen
    . '<h3>Ehemalige Mitglieder</h3>'
    . '<ul>'
    . $record_inactive['parlamentarier']
    . '</ul>'
    . _lobbywatch_get_workflow_state_line($record_active)
    . '</div>'
    ;

    $build = array();
    $build['terms'] = array(
      '#weight' => -5,
      '#prefix' => '<div class="lobbywatch">',
      '#markup' => $markup,
      '#suffix' => '</div>',
      );

      return $build;
  } else {
//     drupal_goto('daten/kommission');
    drupal_not_found();
  }
}

  /**
 * TODO
 */
function _lobbywatch_partei_list($check_unpublished = true) {
  $markup = t('<p>Liste der Parteien</p>');
  $markup .= '<ul>';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  $result = db_query("SELECT * FROM v_partei partei  WHERE 1 "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND partei.freigabe_datum <= NOW() ' : '') . " ORDER BY partei.anzeige_name");

  while ($record = $result->fetchAssoc()) {
    $markup .= '<li' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . '><a href="/daten/partei/' . check_plain($record['id']) . '">' . check_plain($record['anzeige_name']) . '</a>';
  }

  $markup .= '</ul>';

  // Go back to the default database,
  // otherwise Drupal will not be able to access it's own data later on.
  db_set_active();

  $build = array();
  $build['terms'] = array(
    '#weight' => -5,
    '#prefix' => '<div class="lobbywatch">',
    '#markup' => $markup,
    '#suffix' => '</div>',
    );

    return $build;
}

function _lobbywatch_partei_id($id, $name = '', $check_unpublished = true) {

  //   dpm($id, 'id');

  $url_name = _lobbywatch_anzeige_name_for_url('partei', $id);
  if ($name !== $url_name) {
    drupal_goto("daten/partei/$id/$url_name");
  }

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    $sql = "SET SESSION group_concat_max_len=10000;";
    db_query($sql);

    $sql = "SELECT partei.*,
      GROUP_CONCAT(DISTINCT
        CONCAT('<li',
      IF(parlamentarier.freigabe_datum IS NULL OR parlamentarier.freigabe_datum > NOW(), ' class=\"unreleased\"', ''), '>',
      IF(parlamentarier.im_rat_bis < NOW(), '<s>', ''), '<a href=\"/daten/parlamentarier/', parlamentarier.id, '\">', parlamentarier.anzeige_name, '</a>',
        ', ', parlamentarier.ratstyp,
        IF(parlamentarier.partei IS NULL, '', CONCAT(', ', parlamentarier.partei)),
        ', ', parlamentarier.kanton,
      IF(parlamentarier.im_rat_bis < NOW(), CONCAT(' <small>, im Rat bis ', DATE_FORMAT(parlamentarier.im_rat_bis, '%d.%m.%Y'), '</small></s>'), '')
        )
        ORDER BY parlamentarier.anzeige_name
        SEPARATOR ' '
      ) mitglieder
  FROM `v_partei` partei
  LEFT JOIN `v_parlamentarier` parlamentarier
  ON parlamentarier.partei_id = partei.id "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND parlamentarier.freigabe_datum <= NOW() ' : '') . "
  WHERE partei.id=:id "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND partei.freigabe_datum <= NOW() ' : '') . "
  GROUP BY partei.id";

  $result = db_query($sql, array(':id' => $id));

  //     dpm($result, 'result');

  $record = $result->fetchAssoc();
//   dpm($record, '$record');
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
  }
  if ($record) {
    $markup .=
    (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . t('Unpublished') . '</p>': '<div>') .
//    '<h2>' . check_plain($record["anzeige_name"]) . '</h2>'
    '<table border=0>'
        . '<tr><td>Homepage:</td><td>' . ($record['homepage'] ? l(preg_replace('|https?://|i', '', $record['homepage']), $record['homepage'], array('attributes' => array('class' => array('elf-external', 'elf-icon')))) : '') . '</td></tr>'
            . '</table>'
    . '<h3>Mitglieder</h3>'
    . '<ul>'
    . $record['mitglieder']
    . '</ul>'
    . _lobbywatch_get_workflow_state_line($record)
      . '</div>'
    ;

    $build = array();
    $build['terms'] = array(
      '#weight' => -5,
      '#prefix' => '<div class="lobbywatch">',
      '#markup' => $markup,
      '#suffix' => '</div>',
      );

      return $build;
  } else {
//     drupal_goto('daten/partei');
    drupal_not_found();
  }

}

  /**
 * TODO
 */
function _lobbywatch_branche_list($check_unpublished = true) {
  $markup = t('<p>Liste der Branchen</p>');
  $markup .= '<ul>';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  $result = db_query("SELECT * FROM v_branche branche WHERE 1 "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND branche.freigabe_datum <= NOW() ' : '') . " ORDER BY branche.anzeige_name");

  while ($record = $result->fetchAssoc()) {
    $markup .= '<li' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . '><a href="/daten/branche/' . check_plain($record['id']) . '">' . check_plain($record['anzeige_name']) . '</a>';
  }

  $markup .= '</ul>';

  // Go back to the default database,
  // otherwise Drupal will not be able to access it's own data later on.
  db_set_active();

  $build = array();
  $build['terms'] = array(
    '#weight' => -5,
    '#prefix' => '<div class="lobbywatch">',
    '#markup' => $markup,
    '#suffix' => '</div>',
    );

    return $build;
}

function _lobbywatch_branche_id($id, $name = '', $check_unpublished = true) {

//   dpm($id, 'id');
//   dpm($name, '$name');

  $url_name = _lobbywatch_anzeige_name_for_url('branche', $id);
//   dpm($url_name, '$url_name');
  if ($name !== $url_name) {
    drupal_goto("daten/branche/$id/$url_name");
  }

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    $sql = "SET SESSION group_concat_max_len=10000;";
    db_query($sql);

    $sql = "SELECT *,
GROUP_CONCAT(DISTINCT
    CONCAT('<li><a href=\"/daten/lobbygruppe/', interessengruppe.id, '\">', interessengruppe.anzeige_name, '</a>',
    IF(interessengruppe.beschreibung IS NULL OR TRIM(interessengruppe.beschreibung) = '', '', CONCAT('<small class=\"desc\">, &quot;', interessengruppe.beschreibung, '&quot;</small>')))
    ORDER BY interessengruppe.anzeige_name
    SEPARATOR ' '
) interessengruppen
  FROM v_branche branche
  LEFT JOIN `v_interessengruppe` interessengruppe
  ON interessengruppe.branche_id = branche.id "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND interessengruppe.freigabe_datum <= NOW() ' : '') . "
  WHERE branche.id=:id "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND branche.freigabe_datum <= NOW() ' : '') . "
  GROUP BY branche.id";

  $result = db_query($sql, array(':id' => $id));

  //     dpm($result, 'result');

  $record = $result->fetchAssoc();
//   dpm($record, '$record');
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
  }
  if ($record) {
    $markup .=
    (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . t('Unpublished') . '</p>': '<div>') .
//     '<h2>' . check_plain($record["anzeige_name"]) . '</h2>' .
    '<table border="0">'
//     . '<li>Vernehmlassung: ' . $record['vernehmlassung']
    . '<tr><td>Zuständige Kommission</td><td>' . l($record['kommission'], 'daten/kommission/' . $record['kommission_id']) . '</td></tr>'
    . '<tr><td>Beschreibung</td><td>' . check_plain($record['beschreibung']) . '</td></tr>'
    . '</table>'
    . '<h3>Lobbygruppen</h3>'
    . '<ul>'
//     . '<li>Vernehmlassung: ' . $record['vernehmlassung']
//     . '<li>Rechtsform: ' . check_plain($record['rechtsform'])
    . $record['interessengruppen']
    . '</ul>'
    . _lobbywatch_get_workflow_state_line($record)
      . '</div>'
    ;

    $build = array();
    $build['terms'] = array(
      '#weight' => -5,
      '#prefix' => '<div class="lobbywatch">',
      '#markup' => $markup,
      '#suffix' => '</div>',
      );

      return $build;
  } else {
//     drupal_goto('daten/branche');
    drupal_not_found();
  }

}

  /**
 * TODO
 */
function _lobbywatch_interessengruppe_list($check_unpublished = true) {
  $markup = t('<p>Liste der Lobbygruppen</p>');
  $markup .= '<ul>';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  $result = db_query("SELECT * FROM v_interessengruppe interessengruppe WHERE 1 "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND interessengruppe.freigabe_datum <= NOW() ' : '') . " ORDER BY interessengruppe.anzeige_name");

  while ($record = $result->fetchAssoc()) {
    $markup .= '<li' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . '><a href="/daten/lobbygruppe/' . check_plain($record['id']) . '">' . check_plain($record['anzeige_name']) . '</a>';
  }

  $markup .= '</ul>';

  // Go back to the default database,
  // otherwise Drupal will not be able to access it's own data later on.
  db_set_active();

  $build = array();
  $build['terms'] = array(
    '#weight' => -5,
    '#prefix' => '<div class="lobbywatch">',
    '#markup' => $markup,
    '#suffix' => '</div>',
    );

    return $build;
}

function _lobbywatch_interessengruppe_id($id, $name = '', $check_unpublished = true) {

  //   dpm($id, 'id');

  $url_name = _lobbywatch_anzeige_name_for_url('interessengruppe', $id);
  if ($name !== $url_name) {
    drupal_goto("daten/lobbygruppe/$id/$url_name");
  }

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
  $sql = "SELECT * FROM v_interessengruppe interessengruppe WHERE interessengruppe.id=:id"
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND interessengruppe.freigabe_datum <= NOW() ' : '');

  $result = db_query($sql, array(':id' => $id));

  //     dpm($result, 'result');

  $record = $result->fetchAssoc();
//   dpm($record, '$record');
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
  }
  if ($record) {
    $markup .=
    (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . t('Unpublished') . '</p>': '<div>') .
//     '<h2>' . check_plain($record["anzeige_name"]) . '</h2>' .
    '<table border="0">'
//     . '<li>Vernehmlassung: ' . $record['vernehmlassung']
    . '<tr><td>Branche</td><td>' . l($record['branche'], 'daten/branche/' . $record['branche_id']) . '</td></tr>'
    . '<tr><td>Beschreibung</td><td>' . check_plain($record['beschreibung']) . '</td></tr>'
    . '<tr><td>Zuständige Kommission</td><td>' . l($record['kommission'], 'daten/kommission/' . $record['kommission_id']) . '</td></tr>'
    . '</table>'
    . _lobbywatch_get_workflow_state_line($record)
      . '</div>'
    ;

    $build = array();
    $build['terms'] = array(
      '#weight' => -5,
      '#prefix' => '<div class="lobbywatch">',
      '#markup' => $markup,
      '#suffix' => '</div>',
      );

      return $build;
  } else {
//     drupal_goto('daten/lobbygruppe');
    drupal_not_found();
  }

}

  /**
 * TODO
 */
function _lobbywatch_zutrittsberechtigung_list($check_unpublished = true) {
  $markup = t('<p>Liste der Zutrittsberechtigten</p>');
  $markup .= '<ul>';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  $result = db_query("SELECT * FROM v_zutrittsberechtigung zutrittsberechtigung WHERE 1 "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND zutrittsberechtigung.freigabe_datum <= NOW() ' : '') . "  ORDER BY zutrittsberechtigung.anzeige_name");

  while ($record = $result->fetchAssoc()) {
    $markup .= '<li' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . '><a href="/daten/zutrittsberechtigter/' . check_plain($record['id']) . '">' . check_plain($record['anzeige_name']) . '</a>';
  }

  $markup .= '</ul>';

  // Go back to the default database,
  // otherwise Drupal will not be able to access it's own data later on.
  db_set_active();

  $build = array();
  $build['terms'] = array(
    '#weight' => -5,
    '#prefix' => '<div class="lobbywatch">',
    '#markup' => $markup,
    '#suffix' => '</div>',
    );

    return $build;
}

function _lobbywatch_zutrittsberechtigung_id($id, $name = '', $check_unpublished = true) {

  //   dpm($id, 'id');

  $url_name = _lobbywatch_anzeige_name_for_url('zutrittsberechtigung', $id);
  if ($name !== $url_name) {
    drupal_goto("daten/zutrittsberechtigter/$id/$url_name");
  }

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    $sql = "SET SESSION group_concat_max_len=10000;";
    db_query($sql);

    $sql = "SELECT zutrittsberechtigung.*, DATE_FORMAT(zutrittsberechtigung.bis, '%d.%m.%Y') as bis_formatted,
  GROUP_CONCAT(DISTINCT
    CONCAT('<li>', IF(mandat.bis IS NOT NULL AND mandat.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation.id, '\">', organisation.anzeige_name, '</a>',
    IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', CONCAT(UCASE(LEFT(mandat.art, 1)), SUBSTRING(mandat.art, 2)),
    IF(mandat.funktion_im_gremium IS NULL OR TRIM(mandat.funktion_im_gremium) = '', '', CONCAT(', ',CONCAT(UCASE(LEFT(mandat.funktion_im_gremium, 1)), SUBSTRING(mandat.funktion_im_gremium, 2)))),
    IF(TRUE AND (mandat.beschreibung IS NULL OR TRIM(mandat.beschreibung) = ''), '', CONCAT('<small class=\"desc\">, &quot;', mandat.beschreibung, '&quot;</small>')),
    IF(mandat.bis IS NOT NULL AND mandat.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(mandat.bis, '%Y'), '</s>'), ''))
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
) mandate
FROM v_zutrittsberechtigung zutrittsberechtigung
LEFT JOIN v_mandat mandat
  ON mandat.zutrittsberechtigung_id = zutrittsberechtigung.id "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND mandat.freigabe_datum <= NOW() ' : '') . "
LEFT JOIN v_organisation organisation
  ON mandat.organisation_id = organisation.id "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '') . "
WHERE
  zutrittsberechtigung.id=:id "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND zutrittsberechtigung.freigabe_datum <= NOW() ' : '') . "
GROUP BY zutrittsberechtigung.id;";

  $result = db_query($sql, array(':id' => $id));

  //     dpm($result, 'result');

  $record = $result->fetchAssoc();
//   dpm($record, '$record');
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
  }
  if ($record) {
    $markup .=
    (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . t('Unpublished') . '</p>': '<div>') .
//    '<h2>' . check_plain($record["anzeige_name"]) . '</h2>' .
    ($record['bis'] != null ? '<p><strong><em>Nicht mehr zutrittsberechtigt ins Parlament. War zutrittsberechtigt bis ' . check_plain($record['bis_formatted']) . '.</em></strong></p>' : '')
    . '<table border="0">'
//     . '<li>Vernehmlassung: ' . $record['vernehmlassung']
    . '<tr><td>Eingeladen von:</td><td>' . l($record['parlamentarier_name'], 'daten/parlamentarier/' . $record['parlamentarier_id']) . '</td></tr>'
    .  '<tr><td>Funktion:</td><td>' . check_plain($record['funktion']) . '</td></tr>'
    .  '<tr><td>Beruf:</td><td>' . check_plain($record['beruf']) . '</td></tr>'
    .  '<tr><td>Partei:</td><td>' . l($record['partei'], 'daten/partei/' . $record['partei_id']) . '</td></tr>'
    .  '<tr><td>Homepage:</td><td>' . ($record['homepage'] ? l($record['homepage'], $record['homepage'], array('attributes' => array('class' => array('elf-external', 'elf-icon')))) : '') . '</td></tr>'
    . '</table>'
    . '<h3>Mandate</h3>'
    . '<ul>'
    .    $record['mandate']
    . '</ul>'
    . _lobbywatch_get_workflow_state_line($record)
      . '</div>'
    ;

    $build = array();
    $build['terms'] = array(
      '#weight' => -5,
      '#prefix' => '<div class="lobbywatch">',
      '#markup' => $markup,
      '#suffix' => '</div>',
      );

      return $build;
  } else {
//     drupal_goto('daten/zutrittsberechtigter');
    drupal_not_found();
  }

}

function _lobbywatch_get_parteien($check_unpublished = true) {
  // Cache ref: http://www.lullabot.com/blog/article/beginners-guide-caching-data-drupal-7
  $cache = &drupal_static(__FUNCTION__);
  if (!isset($cache)) {
    db_set_active('lobbywatch');

    try {
      $sql = "SELECT partei.* FROM v_partei partei WHERE 1 "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND partei.freigabe_datum <= NOW() ' : '') . " ;";

      $result = db_query($sql);

      while ($record = $result->fetchAssoc()) {
        $cache[$record['id']] = $record;
      }
    } finally {
      // Go back to the default database,
      // otherwise Drupal will not be able to access it's own data later on.
      db_set_active();
    }
  }
  return $cache;
}

function _lobbywatch_get_kommissionen($check_unpublished = true) {
  // Cache ref: http://www.lullabot.com/blog/article/beginners-guide-caching-data-drupal-7
  $cache = &drupal_static(__FUNCTION__);
  if (!isset($cache)) {
    db_set_active('lobbywatch');

    try {
      $sql = "SELECT kommission.* FROM v_kommission kommission WHERE 1 "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND kommission.freigabe_datum <= NOW() ' : '') . " ;";

      $result = db_query($sql);

      while ($record = $result->fetchAssoc()) {
        $cache[$record['id']] = $record;
      }
    } finally {
      // Go back to the default database,
      // otherwise Drupal will not be able to access it's own data later on.
      db_set_active();
    }
  }
  return $cache;
}

function _lobbywatch_get_workflow_state_line($record) {
  global $lobbywatch_mode;
      $admin = user_access('access lobbywatch unpublished content');
      return '<p class="lobbywatch-meta-fields-line"><small>'
        . t('Last updated !date', array ('!date' => '<time datetime="'.getISO8601Date($record['updated_date_unix']).'">' . format_date($record['updated_date_unix'], 'short_date') . '</time>')) .
        ($admin ?
          '/' . $record ['updated_visa'] : '') .
          '<br>' . ($record['freigabe_datum'] && $record['freigabe_datum_unix'] < time() ? t('Published  !date', array ('!date' => '<time datetime="'.getISO8601Date($record['freigabe_datum_unix']).'" pubdate="pubdate">' . format_date($record['freigabe_datum_unix'], 'short_date') . '</time>' )) . ($admin ? '/' . $record ['freigabe_visa'] : '') : t('Unpublished')) .
        ($admin ?  '<br>' . t('Created  @date', array ('@date' => format_date($record['created_date_unix'], 'short_date'))) . '/' . $record ['created_visa'] .
          ($record ['eingabe_abgeschlossen_datum_unix'] ? '<br>' . t('Eingabe  @date', array ('@date' => format_date($record ['eingabe_abgeschlossen_datum_unix'], 'short_date') )) . '/' .  $record ['eingabe_abgeschlossen_visa'] : '')
          . ($record ['kontrolliert_datum_unix'] ? '<br>' . t('Kontrolle  @date', array ('@date' => format_date($record ['kontrolliert_datum_unix'], 'short_date') )) . '/' .  $record ['kontrolliert_visa'] : '')
        : '') .
      '</small></p>' .
      ($admin ? "<p class=\"lobbywatch-meta-fields-line\"><small>Verwendete DB: $lobbywatch_mode</small></p>" : "");
}

function getISO8601Date($unix_timestamp) {
  return format_date($unix_timestamp, 'custom', 'Y-m-d\TH:i:sO'); // PHP 'c' format is not proper ISO8601!
}
