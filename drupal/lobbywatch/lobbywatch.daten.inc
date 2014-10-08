<?php

// Ref https://api.drupal.org/api/examples/ajax_example!ajax_example_autocomplete.inc/7
function _lobbywatch_search_autocomplete($str = '', $check_unpublished = true) {

//   return _lobbywatch_parlamentarier_list();
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
//     $result = _lobbywatch_search_autocomplete_LIKE_UNION($str);
    $result = _lobbywatch_search_autocomplete_LIKE_search_table($str);
//     $result = _lobbywatch_search_autocomplete_FULLTEXT($str);

  //   dpm($result, 'result');

    $items = array();
    while($record = $result->fetchAssoc()) {
      $key = check_plain($record['name']) . " [" . check_plain($record['page']). '=' . check_plain($record['id']) . "]";
      $items[$key] = check_plain(ucfirst($record['page']) . ': ' . $record['name']);
    }
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
  }

//   dpm($items, 'items');

  drupal_json_output($items);
}

function _lobbywatch_search_autocomplete_LIKE_search_table($str, $check_unpublished = true) {
  $sql = "
SELECT id, page, name
FROM v_search_table
WHERE
search_keywords LIKE :str ". ($check_unpublished && !user_access('access lobbywatch advanced content') ? ' AND (bis IS NULL OR bis > NOW())' : '') . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND freigabe_datum <= NOW()' : '') . "
ORDER BY table_weight, weight
LIMIT 20;";
  //dpm($sql, 'suche');
  $result = db_query($sql, array(':str' => _lobbywatch_search_keyword_processing($str)));

  return $result;
}

function _lobbywatch_search_form_LIKE_search_table($search_str) {
  $table = 'search_table';
  $query = db_select("v_$table", $table)->extend('PagerDefault');
  $query->fields($table, array('id', 'page', 'name'));
  $query->condition('search_keywords', $search_str, 'LIKE');
  if (!user_access('access lobbywatch unpublished content')) {
    //       $query->where('(bis IS NULL OR bis > NOW()) AND freigabe_datum <= NOW()');
    $query->where('freigabe_datum <= NOW()');
  }
  if (!user_access('access lobbywatch advanced content')) {
    //       $query->where('(bis IS NULL OR bis > NOW()) AND freigabe_datum <= NOW()');
    $query->condition(db_or()->isNull('bis')->where('bis > NOW()'));
  }
  $query_result = $query->execute();
  return $query_result;
}

function _lobbywatch_search_keyword_processing($str) {
  $search_str = preg_replace('!\*+!', '%', $str);
  //     $search_str = '%' . db_like($keys) . '%'
  if (!preg_match('/[%_]/', $search_str)) {
    $search_str = "%$search_str%";
  }
  return $search_str;
}

/** Currently not used */
// function _lobbywatch_search_autocomplete_LIKE_UNION($str, $check_unpublished = true) {
//   $table_sql = array();
//   $table = 'parlamentarier';
//   $page = 'parlamentarier';
//   $table_sql[] = "SELECT '$page' as page, CONCAT_WS(', ', anzeige_name, rat, partei, kanton) as name, id FROM v_$table WHERE anzeige_name LIKE :str" . ($check_unpublished && !user_access('access lobbywatch advanced content') ? ' AND (im_rat_bis IS NULL OR im_rat_bis > NOW())' : '') . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND freigabe_datum <= NOW()' : '');

//   // TODO use zutrittsberechtigung_simple
//   $table = 'zutrittsberechtigung_simple';
//   $page = 'zutrittsberechtigter';
//   $table_sql[] = "SELECT '$page' as page, anzeige_name as name, id FROM v_$table WHERE anzeige_name LIKE :str" . ($check_unpublished && !user_access('access lobbywatch advanced content') ? ' AND (bis IS NULL OR bis > NOW())' : '') . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND freigabe_datum <= NOW()' : '');

//   foreach (Constants::$entities_web as $table => $page) {
//     $table_sql[] = "SELECT '$page' as page, anzeige_name as name, id FROM v_$table WHERE anzeige_name LIKE :str" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND freigabe_datum <= NOW()' : '');
//   }

// //   $sql = 'SELECT tables.* FROM (' . implode("\n UNION \n", $table_sql) . ') tables LIMIT 20;';
//   $sql = implode("\n UNION ALL \n", $table_sql). ' LIMIT 20;'; // LIMIT applies to the whole query, ref http://stackoverflow.com/questions/12684407/how-to-using-order-by-and-limit-in-union-mysql-query
//   //dpm($sql, 'suche');
//   $result = db_query($sql, array(':str' => _lobbywatch_search_keyword_processing($str)));

//   return $result;
// }

/** Currently not used */
// function _lobbywatch_search_form_LIKE_UNION($search_str) {
//   $table = 'parlamentarier';
//   $page = 'parlamentarier';
//   $query = db_select("v_$table", $table);
//   $query->fields($table, array('id'));
//   $query->addExpression("'$page'", 'page');
//   $query->addExpression("CONCAT_WS(', ', anzeige_name, rat, partei, kanton)", 'name');
//   $query->condition('anzeige_name', $search_str, 'LIKE');
//   if (!user_access('access lobbywatch unpublished content')) {
//     //       $query->where('(im_rat_bis IS NULL OR im_rat_bis > NOW()) AND freigabe_datum <= NOW()');
//     $query->where('freigabe_datum <= NOW()');
//   }
//   if (!user_access('access lobbywatch advanced content')) {
//     //       $query->where('(im_rat_bis IS NULL OR im_rat_bis > NOW()) AND freigabe_datum <= NOW()');
//     $query->condition(db_or()->isNull('im_rat_bis')->where('im_rat_bis > NOW()'));
//   }

//   $table = 'zutrittsberechtigung_simple';
//   $page = 'zutrittsberechtigter';
//   $query2 = db_select("v_$table", $table);
//   $query2->fields($table, array('id'));
//   $query2->addExpression("'$page'", 'page');
//   $query2->addExpression("anzeige_name", 'name');
//   $query2->condition('anzeige_name', $search_str, 'LIKE');
//   if (!user_access('access lobbywatch unpublished content')) {
//     //       $query->where('(bis IS NULL OR bis > NOW()) AND freigabe_datum <= NOW()');
//     $query2->where('freigabe_datum <= NOW()');
//   }
//   if (!user_access('access lobbywatch advanced content')) {
//     //       $query->where('(bis IS NULL OR bis > NOW()) AND freigabe_datum <= NOW()');
//     $query2->condition(db_or()->isNull('bis')->where('bis > NOW()'));
//   }
//   $merged_query = $query->union($query2, 'ALL');

//   $query2 = db_select("v_$table", $table);
//   foreach (Constants::$entities_web as $table => $page) {
//     $query2 = db_select("v_$table", $table);
//     $query2->addExpression("'$page'", 'page');
//     $query2->addExpression("anzeige_name", 'name');
//     //       $query2->addField("'$table'", "anzeige_name", 'name'); // causes problem with field order
//     $query2->fields($table, array('id'));
//     $query2->condition('anzeige_name', $search_str, 'LIKE');
//     if (!user_access('access lobbywatch unpublished content')) {
//       //       $query->where('(im_rat_bis IS NULL OR im_rat_bis > NOW()) AND freigabe_datum <= NOW()');
//       $query2->where('freigabe_datum <= NOW()');
//     }
//     $merged_query = $merged_query->union($query2, 'ALL')->extend('PagerDefault');
//   }

//   $limit_query = db_select($merged_query, 'limit_query');
//   $limit_query = $limit_query->range(0, 400);
//   $limit_query->fields('limit_query', array('name', 'page', 'id'));

//   // Workaround for https://www.drupal.org/node/2303337 / https://www.drupal.org/node/1634908
//   $union_query = db_select($limit_query, 'union_query');
//   $union_query = $union_query->extend('PagerDefault')->limit(20);
//   $union_query->fields('union_query', array('name', 'page', 'id'));
//   $query_result = $union_query->execute();
//   return $query_result;
// }

/**
 * Does not work.
 *
 * @deprecated
 * http://dev.mysql.com/doc/refman/5.1/en/fulltext-search.html
 * http://devzone.zend.com/26/using-mysql-full-text-searching/
 *
 * @param string $check_unpublished
 * @return DatabaseStatementInterface
 */
// function _lobbywatch_search_autocomplete_FULLTEXT($str, $check_unpublished = true) {
//   $table_sql = array();
//   $table = 'mv_parlamentarier_myisam';
//   $page = 'parlamentarier';
//   $table_sql[] = "SELECT '$page' as page, CONCAT_WS(', ', anzeige_name, rat, partei, kanton) as name, id FROM $table WHERE MATCH(`anzeige_name`) AGAINST(:str) " . ($check_unpublished && !user_access('access lobbywatch advanced content') ? ' AND (im_rat_bis IS NULL OR im_rat_bis > NOW())' : '') . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND freigabe_datum <= NOW()' : '');

//   // TODO use zutrittsberechtigung_simple
//   $table = 'mv_zutrittsberechtigung_myisam';
//   $page = 'zutrittsberechtigter';
//   $table_sql[] = "SELECT '$page' as page, anzeige_name as name, id FROM $table WHERE MATCH(`anzeige_name`) AGAINST(:str)" . ($check_unpublished && !user_access('access lobbywatch advanced content') ? ' AND (bis IS NULL OR bis > NOW())' : '') . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND freigabe_datum <= NOW()' : '');

// //   $table = 'mv_organisation_myisam';
// //   $page = 'organisation';
// //   $table_sql[] = "SELECT '$page' as page, anzeige_name as name, id FROM $table WHERE MATCH(`anzeige_name`) AGAINST(:str)" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND freigabe_datum <= NOW()' : '');

//   foreach (Constants::$entities_web as $table => $page) {
//     $table_sql[] = "SELECT '$page' as page, anzeige_name as name, id FROM v_$table WHERE anzeige_name LIKE :like_str" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND freigabe_datum <= NOW()' : '');
//   }

//   $sql = 'SELECT tables.* FROM (' . implode("\n UNION \n", $table_sql) . ') tables LIMIT 20;';
//   //dpm($sql, 'suche');
//   $result = db_query($sql, array(':like_str' => "%$str%", ':str' => $str));

//   return $result;
//   }

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
     . ($check_unpublished && !user_access('access lobbywatch advanced content') ? '(parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW()) AND parlamentarier.freigabe_datum <= NOW()' : '') . "
    ORDER BY parlamentarier.anzeige_name");

    $inactive = !$record['im_rat_bis'] || $record['im_rat_bis'] < time();

    while ($record = $result->fetchAssoc()) {
      $markup .= '<tr' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . '><td>' . ($inactive ? '<s>': '') . '<a href="/daten/parlamentarier/' . check_plain($record['id']) . '/' . _lobbywatch_clean_for_url($record['anzeige_name']) . '">' . check_plain($record['anzeige_name']) . '</a>' . ($inactive ? '</s>': '') . '</td><td><a href="/daten/parlamentarier/' . check_plain($record['id']) . '/' . _lobbywatch_clean_for_url($record['anzeige_name']) . '">' . '<img src="/sites/lobbywatch.ch/app/auswertung/parlamentarierBilder/' . check_plain($record['kleinbild']) . '" alt="' . check_plain($record['anzeige_name']) . '">' . '</a></td><td>' . check_plain($record['partei']) . '</td><td>' . check_plain($record['rat']) . '</td><td>' . check_plain($record['kanton']) . '</td></tr>';
    }

    $markup .= '</tbody></table>';
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
  }

  $build = array();
  $build['lobbywatch_parlamentarier_list'] = array(
    '#weight' => -5,
    '#prefix' => '<div class="lobbywatch">',
    '#markup' => $markup,
    '#suffix' => '</div>',
    );

  return $build;

}


function _lobbywatch_page_title($prefix, $suffix, $table, $id, $title = '') {
//   df("_lobbywatch_page_title $prefix, $suffix, $table, $id");
//   df($prefix . _lobbywatch_fetch_anzeige_name($table, $id) . $suffix . ' title: '. $title, '_lobbywatch_page_title');
//   require_once '123';
  if ($table == 'parlamentarier') {
    $title = _lobbywatch_fetch_parlamentarier_title($table, $id);
  } elseif ($table == 'organsation') {
    $title = _lobbywatch_fetch_organisation_title($table, $id);
  } else {
    $title = $prefix . _lobbywatch_fetch_anzeige_name($table, $id) . $suffix;
  }
  // Workaround, title is otherwise empty, quite a strange thing
  drupal_set_title($title);
  return $title;
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
  $admin = user_access('access lobbywatch admin');

  $sql = "SELECT zutrittsberechtigung.id, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.nachname, zutrittsberechtigung.vorname, zutrittsberechtigung.name, zutrittsberechtigung.funktion, zutrittsberechtigung.lobbyfaktor, zutrittsberechtigung.lobbyfaktor_max, zutrittsberechtigung.anzahl_mandat_hoch, zutrittsberechtigung.anzahl_mandat_mittel, zutrittsberechtigung.anzahl_mandat_tief,
GROUP_CONCAT(DISTINCT
    CONCAT('<li class=\"'," ._lobbywatch_add_admin_class_value_for_freigabe('mandat', 'organisation') . ", ' wirksamkeit-', mandat.wirksamkeit, '\" title=\"Wirksamkeit: ', REPLACE(mandat.wirksamkeit, 'tief', 'gering'), '\" >', IF(mandat.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation.id, '\">', organisation.anzeige_name, '</a>', " .
//     IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)),
//     IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)),
"     ', ', " . _lobbywatch_bindungsart('zutrittsberechtigung', 'mandat', 'organisation') . "," .
//    IF(mandat.beschreibung IS NULL OR TRIM(mandat.beschreibung) = '', '', CONCAT('<small class=\"desc\">, &quot;', mandat.beschreibung, '&quot;</small>')),
"    IF(mandat.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(mandat.bis, '%Y'), '</s>'), ''))
    ORDER BY mandat.wirksamkeit_index DESC, organisation.anzeige_name
    SEPARATOR ' '
) mandate
FROM v_zutrittsberechtigung zutrittsberechtigung
LEFT JOIN v_mandat mandat
  ON mandat.zutrittsberechtigung_id = zutrittsberechtigung.id " . ($check_unpublished && !user_access('access lobbywatch advanced content') ? ' AND (mandat.bis IS NULL OR mandat.bis > NOW())' : '') . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND mandat.freigabe_datum <= NOW() ' : '') . "
LEFT JOIN v_organisation organisation
  ON mandat.organisation_id = organisation.id " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '') . "
WHERE 1
  " . ($only_active_zutrittsberechtigte ? ' AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW())' : '') . "
  AND zutrittsberechtigung.parlamentarier_id=:id
" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND zutrittsberechtigung.freigabe_datum <= NOW()' : '') . "
GROUP BY zutrittsberechtigung.id;";

//   dpm($sql);

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
    $res .= '<h4>' . $gast['zutrittsberechtigung_name'] . ', ' . $gast['funktion'] . (isset($gast['beruf']) ? '/' . $gast['beruf'] : '') . ($admin ? ' <span class="admin">[LF: <b>'. $gast['lobbyfaktor'] . '</b>' . ($admin ? ' (' . round($gast['lobbyfaktor'] /$gast['lobbyfaktor_max'] * 100, 0) . '% von akt. max ' . $gast['lobbyfaktor_max'] . ')' .  ' <span class="admin">[' . $gast['anzahl_mandat_hoch'] . ', ' . $gast['anzahl_mandat_mittel'] . ', ' . $gast['anzahl_mandat_tief'] . ']</span>' : '') . ']' . '</span>': '') . '' . '</h4>';
    $res .= '<p><a href="/daten/zutrittsberechtigter/' . $gast['id'] . '">' . $gast['name'] . '</a>  ist mit folgenden Firmen, Verbänden und Organisationen verbunden:</p>';
    //$res .= mandateList($con, $gast['id']);
    $res .= $gast['mandate'] ? /*_lobbywatch_legende_wirksamkeit() .*/ "<ul>\n" . $gast['mandate'] . "\n</ul>" : '<p>keine</p>';
  }

  return $res;
}

function _lobbywatch_add_admin_class_for_freigabe($table_relation, $target_table = null) {
  return "IF($table_relation.freigabe_datum IS NULL OR $table_relation.freigabe_datum > NOW()" . ($target_table ? " OR $target_table.freigabe_datum IS NULL OR $target_table.freigabe_datum > NOW()" : '') . ", ' class=\"unpublished\"', '')";
}

function _lobbywatch_add_admin_class_value_for_freigabe($table_relation, $target_table = null) {
  return "IF($table_relation.freigabe_datum IS NULL OR $table_relation.freigabe_datum > NOW()" . ($target_table ? " OR $target_table.freigabe_datum IS NULL OR $target_table.freigabe_datum > NOW()" : '') . ", ' unpublished', '')";
}

function _lobbywatch_brancheMitInteressengruppen($kommission_id, $check_unpublished = true) {
  $sql = "SELECT branche.id, branche.anzeige_name as branche_name,
GROUP_CONCAT(DISTINCT
    CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('interessengruppe') . ", '><a href=\"/daten/lobbygruppe/', interessengruppe.id, '\">', interessengruppe.anzeige_name, '</a>',
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
    $res .= '<h4>Lobbygruppen in der Branche ' . $branche['branche_name'] . '</h5>';
    //$res .= mandateList($con, $branche['id']);
    $res .= "<ul>\n" . $branche['interessengruppen'] . "\n</ul>";
  }

//   dpm($res, 'branchen');

  return $res;
}


function _lobbywatch_parlamentarier_id($id, $name='', $check_unpublished = true) {

//   dpm($id, 'id');
  timer_start('lobbywatch_db_read');

  $url_name = _lobbywatch_anzeige_name_for_url('parlamentarier', $id);
  if ($name !== $url_name) {
    drupal_goto("daten/parlamentarier/$id/$url_name");
  }

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    set_db_session_parameters();

    $sql = //db_session_parameters_SQL() .
    "SELECT parlamentarier.id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.name, parlamentarier.geburtstag, DATE_FORMAT(parlamentarier.geburtstag, '%d.%m.%Y') as geburtstag_formatted, parlamentarier.beruf, parlamentarier.im_rat_seit, DATE_FORMAT(parlamentarier.im_rat_seit, '%d.%m.%Y') as im_rat_seit_formatted, parlamentarier.im_rat_bis, DATE_FORMAT(parlamentarier.im_rat_bis, '%d.%m.%Y') as im_rat_bis_formatted, parlamentarier.kleinbild, parlamentarier.rat, parlamentarier.kanton, parlamentarier.geschlecht, parlamentarier.homepage, parlamentarier.parlament_biografie_id, parlamentarier.partei, parlamentarier.partei_id,
    parlamentarier.name, parlamentarier.anzahl_kinder, rat.name_de as rat_name_de, kanton.einwohner, kanton.auslaenderanteil, kanton.anzahl_staenderaete, kanton.anzahl_nationalraete, parlamentarier.updated_date, parlamentarier.updated_date_unix, parlamentarier.updated_visa, parlamentarier.created_date, parlamentarier.created_date_unix, parlamentarier.created_visa, parlamentarier.eingabe_abgeschlossen_datum, parlamentarier.eingabe_abgeschlossen_datum_unix, parlamentarier.eingabe_abgeschlossen_visa, parlamentarier.freigabe_datum, parlamentarier.freigabe_datum_unix, parlamentarier.freigabe_visa, parlamentarier.kontrolliert_datum, parlamentarier.kontrolliert_datum_unix, parlamentarier.kontrolliert_visa, parlamentarier.titel_de, parlamentarier.nachname,
parlamentarier.lobbyfaktor, parlamentarier.lobbyfaktor_max,
parlamentarier.anzahl_interessenbindung_tief,
parlamentarier.anzahl_interessenbindung_mittel,
parlamentarier.anzahl_interessenbindung_hoch,
parlamentarier.anzahl_interessenbindung_tief_nach_wahl,
parlamentarier.anzahl_interessenbindung_mittel_nach_wahl,
parlamentarier.anzahl_interessenbindung_hoch_nach_wahl,
GREATEST(MAX(parlamentarier.updated_date_unix), MAX(interessenbindung.updated_date_unix)) as combined_updated_date_unix,
            GROUP_CONCAT(DISTINCT
      CONCAT('<li class=\"'," ._lobbywatch_add_admin_class_value_for_freigabe('interessenbindung', 'organisation') . ", ' wirksamkeit-', interessenbindung.wirksamkeit, '\" title=\"Wirksamkeit: ', REPLACE(interessenbindung.wirksamkeit, 'tief', 'gering'), '\" >',
          IF(interessenbindung.bis < NOW(), '<s>', ''),
          IF(interessenbindung.von > parlamentarier.im_rat_seit, '<i>', ''),
          '<a href=\"/daten/organisation/', organisation.id, '\">', organisation.anzeige_name, '</a>', " .
//       IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)),
//       IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)),
"      ', ', " . _lobbywatch_bindungsart('parlamentarier', 'interessenbindung', 'organisation') . ",
      IF(TRUE OR interessenbindung.beschreibung IS NULL OR TRIM(interessenbindung.beschreibung) = '', '', CONCAT(', ',interessenbindung.beschreibung)),
      IF(interessenbindung.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(interessenbindung.bis, '%Y'), '</s>'), ''),
      IF(interessenbindung.von > parlamentarier.im_rat_seit, CONCAT(', seit ', DATE_FORMAT(interessenbindung.von, '%Y'), '</i>'), ''))
      ORDER BY interessenbindung.wirksamkeit_index DESC, organisation.anzeige_name
      SEPARATOR ' '
    ) interessenbindungen,
    GROUP_CONCAT(DISTINCT
      CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('v', 'organisation2') . ", '>', IF(v.bis IS NOT NULL AND v.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation2.id, '\">', organisation2.anzeige_name, '</a>',
      IF(organisation2.rechtsform IS NULL OR TRIM(organisation2.rechtsform) = '', '', CONCAT(', ', organisation2.rechtsform)),
      IF(organisation2.ort IS NULL OR TRIM(organisation2.ort) = '', '', CONCAT(', ', organisation2.ort)),
      IF(FALSE AND v.beziehung = 'indirekt', CONCAT(', <em>', v.beziehung, '</em>'), ''),
      IF(v.bis IS NOT NULL AND v.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(v.bis, '%Y'), '</s>'), '')
      )
      ORDER BY organisation2.anzeige_name
      SEPARATOR ' '
    ) verbundene_organisationen,
    GROUP_CONCAT(DISTINCT
      CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('ik', 'kommission') . ", '>', IF(ik.bis IS NOT NULL AND ik.bis < NOW(), '<s>', ''), '<a href=\"/daten/kommission/', kommission.id, '\">',
      kommission.name, ' (', kommission.abkuerzung, ')</a> ',
      IF(kommission.art IS NULL, '', CONCAT(', ', kommission.art)),
      IF(ik.bis IS NOT NULL AND ik.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(ik.bis, '%Y'), '</s>'), '')
      )
      ORDER BY kommission.abkuerzung
      SEPARATOR ' '
      ) kommissionen_namen,
    GROUP_CONCAT(DISTINCT
      CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('zutrittsberechtigung') . ", '>', IF(zutrittsberechtigung.bis IS NOT NULL AND zutrittsberechtigung.bis < NOW(), '<s>', ''), '<a href=\"/daten/zutrittsberechtigter/', zutrittsberechtigung.id, '\">',
      zutrittsberechtigung.name, '</a>, ',
      zutrittsberechtigung.funktion,
      IF(zutrittsberechtigung.beruf IS NULL OR TRIM(zutrittsberechtigung.beruf) = '', '', CONCAT(', ', zutrittsberechtigung.beruf)),
      IF(zutrittsberechtigung.bis IS NOT NULL AND zutrittsberechtigung.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(zutrittsberechtigung.bis, '%Y'), '</s>'), ''))
      ORDER BY zutrittsberechtigung.name
      SEPARATOR ' '
    ) zutrittsberechtigungen
      FROM v_parlamentarier parlamentarier
      LEFT JOIN v_interessenbindung interessenbindung
      ON interessenbindung.parlamentarier_id = parlamentarier.id" . ($check_unpublished && !user_access('access lobbywatch advanced content') ? ' AND (interessenbindung.bis IS NULL OR interessenbindung.bis > NOW())' : '') . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND interessenbindung.freigabe_datum <= NOW() ' : '') . "
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

//     dpm($sql, 'sql');

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
      $admin = user_access('access lobbywatch admin');
      $unpublished = user_access('access lobbywatch unpublished content');
      $rat = check_plain($record['rat_name_de']);
      $rat = check_plain($record['rat']);
      $titel_nachname  =  check_plain($record['titel_de'] . ' ' . $record['nachname']);
      if ($rat == 'SR') {
        $vertretung = round($record['einwohner'] / $record['anzahl_staenderaete']);
      } else {
        $vertretung = round($record['einwohner'] / $record['anzahl_nationalraete']);
      }
      // RTODO security, check content
      $parlamentarier_name = check_plain($record["parlamentarier_name"]);
      $active = $record['im_rat_bis'] == null;
      $markup .=
      (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . t('Unpublished') . '</p>': '<div>') .
//       '<h2>' . $parlamentarier_name . '</h2>' .
      '<div class="clearfix">' .
      '<div style="float: left;"><a id="parlamentarier-kleinbild" class="parlamentarier-kleinbild" onclick="false"><img src="/sites/lobbywatch.ch/app/auswertung/parlamentarierBilder/' . check_plain($record['kleinbild']) . '" alt="' . $parlamentarier_name . '"></a></div>' .
      ($admin ? '<p class="admin">Lobbyfaktor: <b>'. $record['lobbyfaktor'] . '</b> (' . round($record['lobbyfaktor'] /$record['lobbyfaktor_max'] * 100, 0) . '% von akt. max ' . $record['lobbyfaktor_max'] . ')' .  ' <span class="admin">[' . $record['anzahl_interessenbindung_hoch'] . '/' . $record['anzahl_interessenbindung_hoch_nach_wahl'] . ', ' . $record['anzahl_interessenbindung_mittel'] . '/' . $record['anzahl_interessenbindung_mittel_nach_wahl'] . ', ' . $record['anzahl_interessenbindung_tief'] . '/' . $record['anzahl_interessenbindung_tief_nach_wahl'] . ']</span>' : '') . '</p>' .
      '<p><a id="show-bio" class="toggle-text" onclick="false"><span id="bio-text-show">Zeige</span><span id="bio-text-hide" style="display:none">Verstecke</span> Angaben zur Person</a></p>'.

      '</div>' .
      (!$active ? "<p><strong><em>Nicht mehr im Parlament. ${record['name']} war im Parlament bis " . check_plain($record['im_rat_bis_formatted']) . '.</em></strong></p>' : '') .
      '<script>
jQuery(document).ready(function() {
    jQuery("#show-bio, #parlamentarier-kleinbild").click(function () {
      jQuery("#show-bio").slideToggle("slow");
      jQuery("#bio").slideToggle("slow");
      // jQuery("#bio-text-show").toggle();
      // jQuery("#bio-text-hide").toggle();
    });
 });
</script>' .
      '<div id="bio" style="display:none">' .
      '<table border="0">' .
      // '<tr><td>Rat:</td><td>' . $rat . '</td></tr>' .
      // '<tr><td>Kanton:</td><td>' . check_plain($record['kanton']) . '</td></tr>' .
      ($admin ? '<tr class="admin"><td>Partei:</td><td>' . l($record['partei'], 'daten/partei/' . $record['partei_id']) . '</td></tr>' : '') .
      '<tr><td>Vertretene Bevölkerung:</td><td>' . number_format($vertretung , 0 , '.' , '\'') . '</td></tr>' .
      '<tr><td>' . ($active ? 'Im Parlament seit:' : 'Dauer:') . '</td><td>' . years_months(check_plain($record['im_rat_seit']), $record['im_rat_bis']) . ' (' . check_plain($record['im_rat_seit_formatted']) . ($active ? '' : ' – ' . check_plain($record['im_rat_bis_formatted']) ) . ')' . '</td></tr>' .
      '<tr><td>Alter:</td><td>' . years(check_plain($record['geburtstag'])) . ' (' . check_plain($record['geburtstag_formatted']) . ')' . '</td></tr>' .
      _lobbywatch_get_optional_tr('Beruf:', $record['beruf'], $admin) .
      _lobbywatch_get_optional_tr('Kinder:', $record['anzahl_kinder'], $admin) .
      _lobbywatch_get_optional_tr('Homepage:', _lobbywatch_get_homepage_link($record['homepage']), $admin, true, $record['homepage']) .
      '<tr><td>Parlament.ch Webseite:</td><td>' . ($record['parlament_biografie_id'] ? l($record['name'], 'http://www.parlament.ch/d/suche/seiten/biografie.aspx?biografie_id=' . $record['parlament_biografie_id'], array('attributes' => array('class' => array('elf-external', 'elf-icon')))) : '' ) . '</td></tr>' .
      '</table>' .
      '</div>' .
      _lobbywatch_get_edit_link($admin, 'parlamentarier', $id) .
      ($admin ? '<div class="admin">Visualisierung EXPERIMENTELL: <a href="/de/daten/grafik/parlamentarier?id=' . $id . '">intern</a> / <a href="/visual/b1/ParlamentarierView.html?id=' . $id . '">extern</a></div>' : '') .
      "<h3 style=\"display: none\">Interessenbindungen</h3><h3>$titel_nachname ist mit folgenden Firmen, Verbänden und Organisationen verbunden</h3>" . ($record['interessenbindungen'] ? _lobbywatch_legende_wirksamkeit() . '<ul>' . $record['interessenbindungen'] . '</ul>' : '<p>keine</p>') .
      '<h3 style="display: none">Mandate der Gäste</h3>' . "<h3>$titel_nachname ermöglicht folgenden Gästen Zutritt zum Bundeshaus</h3>" . ($gaeste ? $gaeste : '<p>keine</p>') .
       ($admin ? "<div class='admin'><h3>Gäste</h3><ul>" . $record['zutrittsberechtigungen'] . '</ul></div>' : '') .
      ($admin ? '<div class="admin"><h3>Organisationen mit einer Verbindung</h3>'
      . '<p>Auch indirekte Verbindungen werden aufgeführt.</p>'
      . '<ul>' . $record['verbundene_organisationen'] . '</ul>'
      . '<h3>Kommissionen</h3><ul>' . $record['kommissionen_namen'] . '</ul></div>' : '')
      . _lobbywatch_get_workflow_state_line($record)
      . '</div>'
      ;

    $build = array();
    $build['lobbywatch_parlamentarier'] = array(
      '#weight' => -5,
      '#prefix' => '<div class="lobbywatch">',
      '#markup' => $markup,
      '#suffix' => '</div>',
      );

      return $build;
  } else {
    //drupal_goto('daten/parlamentarier');
    if (!$url_name) {
      drupal_not_found();
    } else {
      drupal_access_denied();
    }
  }
}

function _lobbywatch_legende_wirksamkeit() {
  $rnd = rand();
  return '<div class="legend clearfix">Wirksamkeit:
      <ul class="legend-list-short" id="legend-short-'. $rnd . '"><li class="wirksamkeit-hoch">● = hoch</li> <li class="wirksamkeit-mittel">● = mittel</li> <li class="wirksamkeit-tief">● = gering</li></ul>' .
      '<div><a id="show-legend-detail" class="toggle-text" onclick="false"><span id="legend-text-show">Zeige</span><span id="legend-text-hide" style="display:none">Verstecke</span> Erklärung</a></div>' .
      '<div id="legend-detail-'. $rnd . '" style="display:none">
          <h3>Wirksame und weniger wirksame Lobbys</h3>
          <p>Anhand der Farbe lässt sich die Wirksamkeit einer Lobbyorganisation ablesen (<span class="wirksamkeit-hoch">hoch</span>, <span class="wirksamkeit-mittel">mittel</span>, <span class="wirksamkeit-tief">gering</span>).</p>
          <ul id="legend-long-'. $rnd . '" class="legend-list-long">
          <li><span class="wirksamkeit-hoch">● Für den Einflussfaktor «hoch», muss die Lobbyorganisation</span>
              <ul><li>immer oder punktuell an Gesetzesrevisionen mitarbeiten (Vernehmlassungen);</li>
              <li>im Parlament mit einer Person vertreten sein, die im Verband, in der Organisation et cetera eine Führungsaufgabe wahrnimmt (Geschäftsleitung, Verwaltungsrat, Stiftungsrat);</li>
              <li>mit ihrem Parlamentsmitglied in der behandelnden Kommission (z.B. Gesundheitskommission) vertreten sein</li></ul></li>
              <li><span class="wirksamkeit-mittel">● Für den Einflussfaktor «mittel» muss die Lobbyorganisation</span>
              <ul>
              <li>punktuell an Gesetzesrevisionen mitarbeiten (Vernehmlassungen);</li>
              <li>im Parlament mit einem Mitglied oder Beirat (Patronatskomitee et cetera) vertreten sein.</li>
              </ul></li>
              <li><span class="wirksamkeit-tief">● Für den Einflussfaktor «gering» muss die Lobbyorganisation</span>
              <ul>
              <li>nie an Gesetzesrevisionen mitarbeiten (Vernehmlassungen);</li>
              <li>im Parlament mit einer Person vertreten sein.</li>
              </ul>
              </li>
              </ul>
      </div>' .

      '<script>
jQuery(document).ready(function() {
    jQuery("#show-legend-detail").click(function () {
      jQuery("#show-legend-detail-'. $rnd . '").slideToggle("slow");
      jQuery("#legend-detail-'. $rnd . '").slideToggle("slow");
      jQuery("#legend-text-show").toggle();
      jQuery("#legend-text-hide").toggle();
    });
 });
</script>' .
            '</div>';
}

function db_session_parameters_SQL() {
  return "SET SESSION group_concat_max_len=10000;";
}

function set_db_session_parameters() {
  db_query(db_session_parameters_SQL());
  // db_query("SET SESSION wait_timeout=120;");
}

function _lobbywatch_get_edit_link($admin, $table, $id) {
  global $drupal_base;
  global $env_dir;
  global $lobbywatch_mode;
  return ($admin ? '<div class="admin">' . l("Edit $lobbywatch_mode", "{$env_dir}bearbeitung/$table.php?operation=edit&pk0=$id", array('external' => true,'attributes' => array('class' => array('elf-external', 'elf-icon')))) . '</div>' : '');
}

function _lobbywatch_get_optional_tr($label, $value, $admin, $html = false, $field_value = null) {
  return (!$html && $value || $html && $field_value || $admin ? '<tr' . (!$html && !$value && $admin || $html && !$field_value && $admin ? ' class="admin"' : '') . "><td>$label</td><td>" . ($html ? $value: check_plain($value)) . '</td></tr>' : '');
}

/**
 * RTODO
 */
function _lobbywatch_organisation_list($check_unpublished = true) {
  $markup = t('<p>Liste der Organisationen</p>');
  $markup .= '<ul>';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  $result = db_query("SELECT organisation.* FROM v_organisation organisation  WHERE 1 " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '') . "  ORDER BY organisation.anzeige_name");

  while ($record = $result->fetchAssoc()) {
    $markup .=
    '<li' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . '><a href="/daten/organisation/' . check_plain($record['id']) . '">' . check_plain($record['anzeige_name']) . '</a>' . (check_plain($record['rechtsform']) ? ', ' . check_plain($record['rechtsform']) : '');
  }

  $markup .= '</ul>';

  // Go back to the default database,
  // otherwise Drupal will not be able to access it's own data later on.
  db_set_active();

  $build = array();
  $build['lobbywatch_organisation_list'] = array(
    '#weight' => -5,
    '#prefix' => '<div class="lobbywatch">',
    '#markup' => $markup,
    '#suffix' => '</div>',
    );

    return $build;
}

function _lobbywatch_organisation_id($id, $name = '', $check_unpublished = true) {

  //   dpm($id, 'id');
  timer_start('lobbywatch_db_read');

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
    set_db_session_parameters();

    $sql_arbeitet = "SELECT organisation.*,
      GROUP_CONCAT(DISTINCT
        CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('v', 'parlamentarier') . ", '>', IF((v.bis IS NOT NULL AND v.bis < NOW()) OR (parlamentarier.im_rat_bis IS NOT NULL AND parlamentarier.im_rat_bis < NOW()), '<s>', ''), '<a href=\"/daten/parlamentarier/', parlamentarier.id, '\">', parlamentarier.anzeige_name, '</a>',
        ', ', parlamentarier.rat,
        IF(parlamentarier.partei IS NULL, '', CONCAT(', ', parlamentarier.partei)),
        ', ', parlamentarier.kanton,
        IF(FALSE AND v.beziehung = 'indirekt', CONCAT(', <em>', v.beziehung, '</em>'), ''),
        IF((v.bis IS NOT NULL AND v.bis < NOW()) OR (parlamentarier.im_rat_bis IS NOT NULL AND parlamentarier.im_rat_bis < NOW()), CONCAT(', bis ', DATE_FORMAT(LEAST(IFNULL(v.bis, parlamentarier.im_rat_bis), IFNULL(parlamentarier.im_rat_bis, v.bis)), '%Y'), IF(parlamentarier.im_rat_bis IS NOT NULL AND parlamentarier.im_rat_bis < NOW(), ' im Rat', ''), '</s>'), '')
        )
        ORDER BY parlamentarier.anzeige_name
        SEPARATOR ' '
      ) verbundene_parlamentarier,
      GROUP_CONCAT(DISTINCT
        CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('organisation_arbeitet_fuer') . ", '>', IF(organisation_arbeitet_fuer.bis IS NOT NULL AND organisation_arbeitet_fuer.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation_arbeitet_fuer.id, '\">', organisation_arbeitet_fuer.anzeige_name, '</a>',
        IF(organisation_arbeitet_fuer.rechtsform IS NULL OR TRIM(organisation_arbeitet_fuer.rechtsform) = '', '', CONCAT(', ', organisation_arbeitet_fuer.rechtsform)),
        IF(organisation_arbeitet_fuer.ort IS NULL OR TRIM(organisation_arbeitet_fuer.ort) = '', '', CONCAT(', ', organisation_arbeitet_fuer.ort)),
        IF(organisation_arbeitet_fuer.bis IS NOT NULL AND organisation_arbeitet_fuer.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(organisation_arbeitet_fuer.bis, '%Y'), '</s>'), '')
        )
        ORDER BY organisation_arbeitet_fuer.anzeige_name
        SEPARATOR ' '
      ) arbeitet_fuer,
      GROUP_CONCAT(DISTINCT
        CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('organisation_auftraggeber_fuer') . ", '>', IF(organisation_auftraggeber_fuer.bis IS NOT NULL AND organisation_auftraggeber_fuer.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation_auftraggeber_fuer.id, '\">', organisation_auftraggeber_fuer.anzeige_name, '</a>',
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
        CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('organisation_mitglied_von') . ", '>', IF(organisation_mitglied_von.bis IS NOT NULL AND organisation_mitglied_von.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation_mitglied_von.id, '\">', organisation_mitglied_von.anzeige_name, '</a>',
        IF(organisation_mitglied_von.rechtsform IS NULL OR TRIM(organisation_mitglied_von.rechtsform) = '', '', CONCAT(', ', organisation_mitglied_von.rechtsform)),
        IF(organisation_mitglied_von.ort IS NULL OR TRIM(organisation_mitglied_von.ort) = '', '', CONCAT(', ', organisation_mitglied_von.ort)),
        IF(organisation_mitglied_von.bis IS NOT NULL AND organisation_mitglied_von.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(organisation_mitglied_von.bis, '%Y'), '</s>'), '')
        )
        ORDER BY organisation_mitglied_von.anzeige_name
        SEPARATOR ' '
      ) mitglied_von,
      GROUP_CONCAT(DISTINCT
        CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('organisation_mitglieder') . ", '>', IF(organisation_mitglieder.bis IS NOT NULL AND organisation_mitglieder.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation_mitglieder.id, '\">', organisation_mitglieder.anzeige_name, '</a>',
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
        CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('organisation_muttergesellschaft') . ", '>', IF(organisation_muttergesellschaft.bis IS NOT NULL AND organisation_muttergesellschaft.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation_muttergesellschaft.id, '\">', organisation_muttergesellschaft.anzeige_name, '</a>',
        IF(organisation_muttergesellschaft.rechtsform IS NULL OR TRIM(organisation_muttergesellschaft.rechtsform) = '', '', CONCAT(', ', organisation_muttergesellschaft.rechtsform)),
        IF(organisation_muttergesellschaft.ort IS NULL OR TRIM(organisation_muttergesellschaft.ort) = '', '', CONCAT(', ', organisation_muttergesellschaft.ort)),
        IF(organisation_muttergesellschaft.bis IS NOT NULL AND organisation_muttergesellschaft.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(organisation_muttergesellschaft.bis, '%Y'), '</s>'), '')
        )
        ORDER BY organisation_muttergesellschaft.anzeige_name
        SEPARATOR ' '
      ) muttergesellschaft,
      GROUP_CONCAT(DISTINCT
        CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('organisation_tochtergesellschaften') . ", '>', IF(organisation_tochtergesellschaften.bis IS NOT NULL AND organisation_tochtergesellschaften.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation_tochtergesellschaften.id, '\">', organisation_tochtergesellschaften.anzeige_name, '</a>',
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
    $admin = user_access('access lobbywatch admin');
    $unpublished = user_access('access lobbywatch unpublished content');
    $markup .=
    (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . t('Unpublished') . '</p>': '<div>') .
    ($admin ? '<p class="admin">Lobbyeinfluss: <b>'. $record['lobbyeinfluss'] . '</b>' .  ' <span class="admin">[' . $record['anzahl_interessenbindung_hoch'] . '/' . $record['anzahl_interessenbindung_hoch_nach_wahl'] . ', ' . $record['anzahl_interessenbindung_mittel'] . '/' . $record['anzahl_interessenbindung_mittel_nach_wahl'] . ', ' . $record['anzahl_interessenbindung_tief'] . '/' . $record['anzahl_interessenbindung_tief_nach_wahl'] . '], [' . $record['anzahl_mandat_hoch'] . ', ' . $record['anzahl_mandat_mittel'] . ', ' . $record['anzahl_mandat_tief'] . ']' . '</span>' : '') . '</p>' .
//    '<h2>' . check_plain($record["anzeige_name"]) . '</h2>' .
//     . '<h3>Informationen</h3>'
    '<table border="0">'
//     . '<li>Vernehmlassung: ' . $record['vernehmlassung']
       . _lobbywatch_get_optional_tr('Rechtsform:', $record['rechtsform'], $admin)
       . _lobbywatch_get_optional_tr('Ort:', $record['ort'], $admin)
    . '<tr><td>Branche</td><td>' . l($record['branche'], 'daten/branche/' . $record['branche_id']) . '</td></tr>'
    . '<tr><td>Lobbygruppe</td><td>' . l($record['interessengruppe'], 'daten/lobbygruppe/' . $record['interessengruppe_id']) . '</td></tr>'
       . _lobbywatch_get_optional_tr('Beschreibung:', $record['beschreibung'], $admin, false, $record['beschreibung'])
       . _lobbywatch_get_optional_tr('Homepage:', _lobbywatch_get_homepage_link($record['homepage']), $admin, true, $record['homepage'])
       . _lobbywatch_get_optional_tr('Handelsregister:', _lobbywatch_get_homepage_link($record['handelsregister_url'], $record['anzeige_name']), $admin, true, $record['handelsregister_url'])
    . '</table>' .
    _lobbywatch_get_edit_link($admin, 'organisation', $id)
    . ($admin ? '<p class="admin">Visualisierung EXPERIMENTELL: <a href="/de/daten/grafik/organisation?id=' . $id . '">intern</a> / <a href="/visual/b1/OrganisationView.html?id=' . $id . '">extern</a></p>' : '');
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
      . '<p>Verbindungen sind direkt über Parlamentarier, indirekt über zutrittsberechtigte Gäste oder indirekt über Mandate von verbundenen PR oder Beratungsunternehmen.</p>'
      . '<ul>'
      . $record['verbundene_parlamentarier']
      . '</ul>';
//       dpm($record['verbundene_parlamentarier'], 'verbundene_parlamentarier');
    }

    $markup .= _lobbywatch_get_workflow_state_line($record) .
    '</div>';


    $build = array();
    $build['lobbywatch_organisation'] = array(
      '#weight' => -5,
      '#prefix' => '<div class="lobbywatch">',
      '#markup' => $markup,
      '#suffix' => '</div>',
      );

      return $build;
  } else {
//     drupal_goto('daten/organisation');
  if (!$url_name) {
      drupal_not_found();
    } else {
      drupal_access_denied();
    }
  }
}

function _lobbywatch_get_homepage_link($url, $text = null) {
  // preg_replace('|(?!https?://)(.*)|i', '//\1', $url)
  if (!preg_match('|^(https?://)|i', $url)) {
    $url = 'http://' . $url;
  }
  return ($url ? l($text ? $text : preg_replace('|https?://|i', '', $url), $url, array('attributes' => array('external' => true, 'absolute' => true, 'class' => array('elf-external', 'elf-icon')))) : '');
}

/**
 * RTODO
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
  $build['lobbywatch_kommission_list'] = array(
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
        CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('parlamentarier') . ", '>', '<a href=\"/daten/parlamentarier/', parlamentarier.parlamentarier_id, '\">', parlamentarier.parlamentarier_name, '</a>',
        ', ', parlamentarier.rat,
        IF(parlamentarier.partei IS NULL, '', CONCAT(', ', parlamentarier.partei)),
        ', ', parlamentarier.kanton " .
//       ,  IF((parlamentarier.bis IS NOT NULL AND parlamentarier.bis < NOW()) OR (parlamentarier.im_rat_bis IS NOT NULL AND parlamentarier.im_rat_bis < NOW()), CONCAT(', bis ', DATE_FORMAT(LEAST(IFNULL(parlamentarier.bis, parlamentarier.im_rat_bis), IFNULL(parlamentarier.im_rat_bis, parlamentarier.bis)), '%Y'), IF(parlamentarier.im_rat_bis IS NOT NULL AND parlamentarier.im_rat_bis < NOW(), ' im Rat', '')), '')
"        )
        ORDER BY parlamentarier.parlamentarier_name
        SEPARATOR ' '
      ) parlamentarier_liste,
      GROUP_CONCAT(DISTINCT
        IF(parlamentarier.rat='NR', CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('parlamentarier') . ", '>', '<a href=\"/daten/parlamentarier/', parlamentarier.parlamentarier_id, '\">', parlamentarier.parlamentarier_name, '</a>',
        IF(parlamentarier.partei IS NULL, '', CONCAT(', ', parlamentarier.partei)),
        ', ', parlamentarier.kanton " .
//       ,  IF((parlamentarier.bis IS NOT NULL AND parlamentarier.bis < NOW()) OR (parlamentarier.im_rat_bis IS NOT NULL AND parlamentarier.im_rat_bis < NOW()), CONCAT(', bis ', DATE_FORMAT(LEAST(IFNULL(parlamentarier.bis, parlamentarier.im_rat_bis), IFNULL(parlamentarier.im_rat_bis, parlamentarier.bis)), '%Y'), IF(parlamentarier.im_rat_bis IS NOT NULL AND parlamentarier.im_rat_bis < NOW(), ' im Rat', '')), '')
"        ), '')
        ORDER BY parlamentarier.parlamentarier_name
        SEPARATOR ' '
      ) nr,
      GROUP_CONCAT(DISTINCT
        IF(parlamentarier.rat='SR', CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('parlamentarier') . ", '>', '<a href=\"/daten/parlamentarier/', parlamentarier.parlamentarier_id, '\">', parlamentarier.parlamentarier_name, '</a>',
        IF(parlamentarier.partei IS NULL, '', CONCAT(', ', parlamentarier.partei)),
        ', ', parlamentarier.kanton " .
//       ,  IF((parlamentarier.bis IS NOT NULL AND parlamentarier.bis < NOW()) OR (parlamentarier.im_rat_bis IS NOT NULL AND parlamentarier.im_rat_bis < NOW()), CONCAT(', bis ', DATE_FORMAT(LEAST(IFNULL(parlamentarier.bis, parlamentarier.im_rat_bis), IFNULL(parlamentarier.im_rat_bis, parlamentarier.bis)), '%Y'), IF(parlamentarier.im_rat_bis IS NOT NULL AND parlamentarier.im_rat_bis < NOW(), ' im Rat', '')), '')
"        ), '')
        ORDER BY parlamentarier.parlamentarier_name
        SEPARATOR ' '
      ) sr,
      GROUP_CONCAT(DISTINCT
        CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('branche') . ", '>', '<a href=\"/daten/branche/', branche.id, '\">', branche.anzeige_name, '</a>'
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
  timer_start('lobbywatch_db_read');

  $url_name = _lobbywatch_anzeige_name_for_url('kommission', $id);
  if ($name !== $url_name) {
    drupal_goto("daten/kommission/$id/$url_name");
  }

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    set_db_session_parameters();

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
    $admin = user_access('access lobbywatch admin');
    $unpublished = user_access('access lobbywatch unpublished content');
    $markup .=
    (!$record_active['freigabe_datum_unix'] || $record_active['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . t('Unpublished') . '</p>': '<div>') .
//      '<h2>' . check_plain($record_active["anzeige_name"]) . '</h2>' .
    ($admin ? '<table border="0" class="admin">'
    . '<tr><td>Abkürzung</td><td>' . check_plain($record_active['abkuerzung']) . '</td></tr>'
    . '<tr><td>Parlament.ch Link</td><td>' . (isset($record_active['parlament_url']) ? l($record_active['anzeige_name'], $record_active['parlament_url'], array('attributes' => array('class' => array('elf-external', 'elf-icon')))) : '') . '</td></tr>'
    . '<tr><td>Beschreibung</td><td>' . check_plain(ucfirst($record_active['beschreibung'])) . '</td></tr>'
    . '<tr><td>Typ</td><td>' . check_plain(ucfirst($record_active['typ'])) . '</td></tr>'
    . '<tr><td>Art</td><td>' . check_plain(ucfirst($record_active['art'])) . '</td></tr>'
    . '</table>' : '')
    . _lobbywatch_get_edit_link($admin, 'kommission', $id)
    . '<h3>Sachbereiche</h3>'
    . '<ul>'
    . implode(array_map(function($value) {return '<li>' . $value;}, explode(';', check_plain($record_active['sachbereiche']))))
    . '</ul>'
    // RTODO präsidium
    . '<h3>Mitglieder der ' . check_plain($record_active['abkuerzung']) . ' Ständerat</h3>'
    . '<ul>'
    . $record_active['sr']
    . '</ul>'
    . '<h3>Mitglieder der ' . check_plain($record_active['abkuerzung']) . ' Nationalrat</h3>'
    . '<ul>'
    . $record_active['nr']
    . '</ul>'
//     . '<h3>Mitglieder</h3>'
//     . '<ul>'
//     . $record_active['parlamentarier']
//     . '</ul>'
    . '<h3>Branchen in diesem Themenbereich</h3>'
    . '<ul>'
    . $record_active['branchen']
    . '</ul>'
    . '<h3>Lobbygruppen</h3>'
    . $interessengruppen
    . ($admin ? '<div class="admin"><h3>Ehemalige Mitglieder</h3>'
    . '<ul>'
    . $record_inactive['parlamentarier_liste']
    . '</ul></div>' : '')
    . _lobbywatch_get_workflow_state_line($record_active)
    . '</div>'
    ;

    $build = array();
    $build['lobbywatch_kommission'] = array(
      '#weight' => -5,
      '#prefix' => '<div class="lobbywatch">',
      '#markup' => $markup,
      '#suffix' => '</div>',
      );

      return $build;
  } else {
//     drupal_goto('daten/kommission');
  if (!$url_name) {
      drupal_not_found();
    } else {
      drupal_access_denied();
    };
  }
}

  /**
 * RTODO
 */
function _lobbywatch_partei_list($check_unpublished = true) {
  $markup = t('<p>Liste der Parteien</p>');
  $markup .= '<ul>';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  $result = db_query("SELECT partei.* FROM v_partei partei  WHERE 1 "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND partei.freigabe_datum <= NOW() ' : '') . " ORDER BY partei.anzeige_name");

  while ($record = $result->fetchAssoc()) {
    $markup .= '<li' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . '><a href="/daten/partei/' . check_plain($record['id']) . '">' . check_plain($record['anzeige_name']) . '</a>';
  }

  $markup .= '</ul>';

  // Go back to the default database,
  // otherwise Drupal will not be able to access it's own data later on.
  db_set_active();

  $build = array();
  $build['lobbywatch_partei_list'] = array(
    '#weight' => -5,
    '#prefix' => '<div class="lobbywatch">',
    '#markup' => $markup,
    '#suffix' => '</div>',
    );

    return $build;
}

function _lobbywatch_partei_id($id, $name = '', $check_unpublished = true) {

  //   dpm($id, 'id');
  timer_start('lobbywatch_db_read');

  $url_name = _lobbywatch_anzeige_name_for_url('partei', $id);
  if ($name !== $url_name) {
    drupal_goto("daten/partei/$id/$url_name");
  }

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    set_db_session_parameters();

    $sql = "SELECT partei.*,
      GROUP_CONCAT(DISTINCT
        CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('parlamentarier') . ",
      IF(parlamentarier.freigabe_datum IS NULL OR parlamentarier.freigabe_datum > NOW(), ' class=\"unpublished\"', ''), '>',
      IF(parlamentarier.im_rat_bis < NOW(), '<s>', ''), '<a href=\"/daten/parlamentarier/', parlamentarier.id, '\">', parlamentarier.anzeige_name, '</a>',
        ', ', parlamentarier.rat,
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
    $admin = user_access('access lobbywatch admin');
    $unpublished = user_access('access lobbywatch unpublished content');
    $markup .=
    (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . t('Unpublished') . '</p>': '<div>') .
//    '<h2>' . check_plain($record["anzeige_name"]) . '</h2>'
    '<table border=0>'
        . '<tr><td>Homepage:</td><td>' . _lobbywatch_get_homepage_link($record['homepage']) . '</td></tr>'
            . '</table>'
    . _lobbywatch_get_edit_link($admin, 'partei', $id)
    . '<h3>Mitglieder</h3>'
    . '<ul>'
    . $record['mitglieder']
    . '</ul>'
    . _lobbywatch_get_workflow_state_line($record)
      . '</div>'
    ;

    $build = array();
    $build['lobbywatch_partei'] = array(
      '#weight' => -5,
      '#prefix' => '<div class="lobbywatch">',
      '#markup' => $markup,
      '#suffix' => '</div>',
      );

      return $build;
  } else {
//     drupal_goto('daten/partei');
  if (!$url_name) {
      drupal_not_found();
    } else {
      drupal_access_denied();
    };
  }

}

  /**
 * RTODO
 */
function _lobbywatch_branche_list($check_unpublished = true) {
  $markup = t('<p>Liste der Branchen</p>');
  $markup .= '<ul>';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  $result = db_query("SELECT branche.* FROM v_branche branche WHERE 1 "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND branche.freigabe_datum <= NOW() ' : '') . " ORDER BY branche.anzeige_name");

  while ($record = $result->fetchAssoc()) {
    $markup .= '<li' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . '><a href="/daten/branche/' . check_plain($record['id']) . '">' . check_plain($record['anzeige_name']) . '</a>';
  }

  $markup .= '</ul>';

  // Go back to the default database,
  // otherwise Drupal will not be able to access it's own data later on.
  db_set_active();

  $build = array();
  $build['lobbywatch_branche_list'] = array(
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
  timer_start('lobbywatch_db_read');

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
    set_db_session_parameters();

    $sql = "SELECT branche.*,
GROUP_CONCAT(DISTINCT
    CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('interessengruppe') . ", '>', '<a href=\"/daten/lobbygruppe/', interessengruppe.id, '\">', interessengruppe.anzeige_name, '</a>',
    IF(interessengruppe.beschreibung IS NULL OR TRIM(interessengruppe.beschreibung) = '', '', CONCAT('<small class=\"desc\">, &quot;', " . _lobbywatch_ucase_SQL('interessengruppe.beschreibung') . ", '&quot;</small>')))
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
    $admin = user_access('access lobbywatch admin');
    $unpublished = user_access('access lobbywatch unpublished content');
    $markup .=
    (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . t('Unpublished') . '</p>': '<div>') .
//     '<h2>' . check_plain($record["anzeige_name"]) . '</h2>' .
    '<table border="0">'
//     . '<li>Vernehmlassung: ' . $record['vernehmlassung']
    . ($admin ? '<tr class="admin"><td>Zuständige Kommission</td><td>' . l($record['kommission'], 'daten/kommission/' . $record['kommission_id']) . '</td></tr>' : '')
    . '<tr><td>Beschreibung</td><td>' . check_plain(ucfirst($record['beschreibung'])) . '</td></tr>'
    . '</table>'
    . _lobbywatch_get_edit_link($admin, 'branche', $id)
    . '<h3 style="display:none">Lobbygruppen</h3>'
    . '<p>In der Branche ' . $record['kommission'] . ' sind die folgenden Lobbygruppen tätig:</p>'
    . '<ul>'
//     . '<li>Vernehmlassung: ' . $record['vernehmlassung']
//     . '<li>Rechtsform: ' . check_plain($record['rechtsform'])
    . $record['interessengruppen']
    . '</ul>'
    . _lobbywatch_get_workflow_state_line($record)
      . '</div>'
    ;

    $build = array();
    $build['lobbywatch_branche'] = array(
      '#weight' => -5,
      '#prefix' => '<div class="lobbywatch">',
      '#markup' => $markup,
      '#suffix' => '</div>',
      );

      return $build;
  } else {
//     drupal_goto('daten/branche');
  if (!$url_name) {
      drupal_not_found();
    } else {
      drupal_access_denied();
    };
  }

}

  /**
 * RTODO
 */
function _lobbywatch_interessengruppe_list($check_unpublished = true) {
  $markup = t('<p>Liste der Lobbygruppen</p>');
  $markup .= '<ul>';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  $result = db_query("SELECT interessengruppe.* FROM v_interessengruppe interessengruppe WHERE 1 "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND interessengruppe.freigabe_datum <= NOW() ' : '') . " ORDER BY interessengruppe.anzeige_name");

  while ($record = $result->fetchAssoc()) {
    $markup .= '<li' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . '><a href="/daten/lobbygruppe/' . check_plain($record['id']) . '">' . check_plain($record['anzeige_name']) . '</a>';
  }

  $markup .= '</ul>';

  // Go back to the default database,
  // otherwise Drupal will not be able to access it's own data later on.
  db_set_active();

  $build = array();
  $build['lobbywatch_interessengruppe_list'] = array(
    '#weight' => -5,
    '#prefix' => '<div class="lobbywatch">',
    '#markup' => $markup,
    '#suffix' => '</div>',
    );

    return $build;
}

function _lobbywatch_interessengruppe_id($id, $name = '', $check_unpublished = true) {

  //   dpm($id, 'id');
  timer_start('lobbywatch_db_read');

  $url_name = _lobbywatch_anzeige_name_for_url('interessengruppe', $id);
  if ($name !== $url_name) {
    drupal_goto("daten/lobbygruppe/$id/$url_name");
  }

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    set_db_session_parameters();
  $sql = "
      SELECT interessengruppe.*,
      GROUP_CONCAT(DISTINCT
    CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('organisation') . ", '>', '<a href=\"/daten/organisation/', organisation.id, '\">', organisation.anzeige_name, '</a>',
    IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)),
      IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)) " .
// ", IF(organisation.beschreibung IS NULL OR TRIM(organisation.beschreibung) = '', '', CONCAT('<small class=\"desc\">, &quot;', " . _lobbywatch_ucase_SQL('organisation.beschreibung') . ", '&quot;</small>')) " .
"  )
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
) organisationen
      FROM v_interessengruppe interessengruppe
      LEFT JOIN v_organisation organisation
        ON interessengruppe.id = organisation.interessengruppe_id OR interessengruppe.id = organisation.interessengruppe2_id OR interessengruppe.id = organisation.interessengruppe3_id"
             . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '') . "
      WHERE interessengruppe.id=:id"
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND interessengruppe.freigabe_datum <= NOW() ' : '') .
  " GROUP BY interessengruppe.id";

//   dpm($sql);
/*
 * GROUP_CONCAT(DISTINCT
    CONCAT('<li><a href=\"/daten/lobbygruppe/', interessengruppe.id, '\">', interessengruppe.anzeige_name, '</a>',
    IF(interessengruppe.beschreibung IS NULL OR TRIM(interessengruppe.beschreibung) = '', '', CONCAT('<small class=\"desc\">, &quot;', " . _lobbywatch_ucase_SQL('interessengruppe.beschreibung') . ", '&quot;</small>')))
    ORDER BY interessengruppe.anzeige_name
    SEPARATOR ' '
) interessengruppen
  FROM v_branche branche
  LEFT JOIN `v_interessengruppe` interessengruppe
  ON interessengruppe.branche_id = branche.id "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND interessengruppe.freigabe_datum <= NOW() ' : '') . "
  WHERE branche.id=:id "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND branche.freigabe_datum <= NOW() ' : '') . "
  GROUP BY branche.id
 */
  $result = db_query($sql, array(':id' => $id));

  //     dpm($result, 'result');

  $record = $result->fetchAssoc();

  // Parlamentarier
  $sql = "SELECT
      GROUP_CONCAT(DISTINCT
        CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('v', 'parlamentarier') . ", '>', IF((v.bis IS NOT NULL AND v.bis < NOW()) OR (parlamentarier.im_rat_bis IS NOT NULL AND parlamentarier.im_rat_bis < NOW()), '<s>', ''), '<a href=\"/daten/parlamentarier/', parlamentarier.id, '\">', parlamentarier.anzeige_name, '</a>',
        ', ', parlamentarier.rat,
        IF(parlamentarier.partei IS NULL, '', CONCAT(', ', parlamentarier.partei)),
        ', ', parlamentarier.kanton,
        IF((v.bis IS NOT NULL AND v.bis < NOW()) OR (parlamentarier.im_rat_bis IS NOT NULL AND parlamentarier.im_rat_bis < NOW()), CONCAT(', bis ', DATE_FORMAT(LEAST(IFNULL(v.bis, parlamentarier.im_rat_bis), IFNULL(parlamentarier.im_rat_bis, v.bis)), '%Y'), IF(parlamentarier.im_rat_bis IS NOT NULL AND parlamentarier.im_rat_bis < NOW(), ' im Rat', ''), '</s>'), '')
        )
        ORDER BY parlamentarier.anzeige_name
        SEPARATOR ' '
      ) verbundene_parlamentarier
  FROM v_interessenbindung_liste v "
   . "
  LEFT JOIN v_parlamentarier parlamentarier
  ON parlamentarier.id = v.parlamentarier_id " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND parlamentarier.freigabe_datum <= NOW() ' : '') . "
  WHERE  "
. " (v.interessengruppe_id = :id OR v.interessengruppe2_id = :id OR v.interessengruppe3_id = :id) "
. ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND v.freigabe_datum <= NOW() ' : '')
. " GROUP BY parlamentarier.id";

  $result = db_query($sql, array(':id' => $id));

  //     dpm($result, 'result');

  $record_parlamentarier_direkt = $result->fetchAssoc();
//   dpm($record, '$record');
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
  }
  if ($record) {
    $admin = user_access('access lobbywatch admin');
    $unpublished = user_access('access lobbywatch unpublished content');
    $markup .=
    (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . t('Unpublished') . '</p>': '<div>') .
//     '<h2>' . check_plain($record["anzeige_name"]) . '</h2>' .
    '<table border="0">'
//     . '<li>Vernehmlassung: ' . $record['vernehmlassung']
    . '<tr><td>Branche</td><td>' . l($record['branche'], 'daten/branche/' . $record['branche_id']) . '</td></tr>'
    . '<tr><td>Beschreibung</td><td>' . check_plain(ucfirst($record['beschreibung'])) . '</td></tr>'
    . '<tr><td>Zuständige Kommission</td><td>' . l($record['kommission'], 'daten/kommission/' . $record['kommission_id']) . '</td></tr>'
    . '</table>'
    . _lobbywatch_get_edit_link($admin, 'interessengruppe', $id)
     . '<h3>Organisationen</h3>'
      . '<ul>' . $record['organisationen'] . '</ul>'
//      . '<h3>Parlamentarier mit einer Interessenbindung</h3>'
//       . '<ul>' . $record_parlamentarier_direkt['verbundene_parlamentarier'] . '</ul>'
    . _lobbywatch_get_workflow_state_line($record)
      . '</div>'
    ;

    $build = array();
    $build['lobbywatch_interessengruppe'] = array(
      '#weight' => -5,
      '#prefix' => '<div class="lobbywatch">',
      '#markup' => $markup,
      '#suffix' => '</div>',
      );

      return $build;
  } else {
//     drupal_goto('daten/lobbygruppe');
  if (!$url_name) {
      drupal_not_found();
    } else {
      drupal_access_denied();
    };
  }

}

  /**
 * RTODO
 */
function _lobbywatch_zutrittsberechtigung_list($check_unpublished = true) {
  $markup = t('<p>Liste der Zutrittsberechtigten</p>');
  $markup .= '<ul>';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  $result = db_query("SELECT zutrittsberechtigung.* FROM v_zutrittsberechtigung zutrittsberechtigung WHERE 1 "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND zutrittsberechtigung.freigabe_datum <= NOW() ' : '') . "  ORDER BY zutrittsberechtigung.anzeige_name");

  while ($record = $result->fetchAssoc()) {
    $markup .= '<li' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . '><a href="/daten/zutrittsberechtigter/' . check_plain($record['id']) . '">' . check_plain($record['anzeige_name']) . '</a>';
  }

  $markup .= '</ul>';

  // Go back to the default database,
  // otherwise Drupal will not be able to access it's own data later on.
  db_set_active();

  $build = array();
  $build['lobbywatch_zutrittsberechtigung_list'] = array(
    '#weight' => -5,
    '#prefix' => '<div class="lobbywatch">',
    '#markup' => $markup,
    '#suffix' => '</div>',
    );

    return $build;
}

function _lobbywatch_zutrittsberechtigung_id($id, $name = '', $check_unpublished = true) {

  //   dpm($id, 'id');
  timer_start('lobbywatch_db_read');

  $url_name = _lobbywatch_anzeige_name_for_url('zutrittsberechtigung', $id);
  if ($name !== $url_name) {
    drupal_goto("daten/zutrittsberechtigter/$id/$url_name");
  }

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    set_db_session_parameters();

    $sql = "SELECT zutrittsberechtigung.*, DATE_FORMAT(zutrittsberechtigung.bis, '%d.%m.%Y') as bis_formatted,
  GROUP_CONCAT(DISTINCT
    CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('mandat', 'organisation') . ", '>', IF(mandat.bis IS NOT NULL AND mandat.bis < NOW(), '<s>', ''), '<a href=\"/daten/organisation/', organisation.id, '\">', organisation.anzeige_name, '</a>', " .
//     IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)),
//     IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)),
"    ', ', " . _lobbywatch_bindungsart('zutrittsberechtigung', 'mandat', 'organisation') . ", " .
//     IF(TRUE AND (mandat.beschreibung IS NULL OR TRIM(mandat.beschreibung) = ''), '', CONCAT('<small class=\"desc\">, &quot;', " . _lobbywatch_ucase_SQL('mandat.beschreibung') . ", '&quot;</small>')),
"    IF(mandat.bis IS NOT NULL AND mandat.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(mandat.bis, '%Y'), '</s>'), ''))
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
) mandate
FROM v_zutrittsberechtigung zutrittsberechtigung
LEFT JOIN v_mandat mandat
  ON mandat.zutrittsberechtigung_id = zutrittsberechtigung.id " . ($check_unpublished && !user_access('access lobbywatch advanced content') ? ' AND (mandat.bis IS NULL OR mandat.bis > NOW())' : '')
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND mandat.freigabe_datum <= NOW() ' : '') . "
LEFT JOIN v_organisation organisation
  ON mandat.organisation_id = organisation.id "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '') . "
WHERE
  zutrittsberechtigung.id=:id "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND zutrittsberechtigung.freigabe_datum <= NOW() ' : '') . "
GROUP BY zutrittsberechtigung.id;";

//     dpm($sql, '$sql');
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
    $admin = user_access('access lobbywatch admin');
    $unbublished = user_access('access lobbywatch unpublished content');
    $markup .=
    (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . t('Unpublished') . '</p>': '<div>') .
//    '<h2>' . check_plain($record["anzeige_name"]) . '</h2>' .
    ($record['bis'] != null ? "<p><strong><em>Nicht mehr zutrittsberechtigt ins Parlament. ${record['name']} war zutrittsberechtigt bis " . check_plain($record['bis_formatted']) . '.</em></strong></p>' : '') .
      '<p>Lobbyfaktor: <b>'. $record['lobbyfaktor'] . '</b> (' . round($record['lobbyfaktor'] /$record['lobbyfaktor_max'] * 100, 0) . '% von akt. max ' . $record['lobbyfaktor_max'] . ')' . ($admin ? ' <span =  class="admin">[' . $record['anzahl_mandat_hoch'] . ', ' . $record['anzahl_mandat_mittel'] . ', ' . $record['anzahl_mandat_tief'] . ']</span></p>' : '')
    . '<table border="0">'
//     . '<li>Vernehmlassung: ' . $record['vernehmlassung']
    . '<tr><td>Eingeladen von:</td><td>' . l($record['parlamentarier_name'], 'daten/parlamentarier/' . $record['parlamentarier_id']) . '</td></tr>'
    .  '<tr><td>Funktion:</td><td>' . check_plain($record['funktion']) . '</td></tr>'
    .  _lobbywatch_get_optional_tr('Beruf:', $record['beruf'], $admin)
    .  _lobbywatch_get_optional_tr('Partei:', l($record['partei'], 'daten/partei/' . $record['partei_id']), $admin, true, $record['partei_id'])
    .  _lobbywatch_get_optional_tr('Homepage:', _lobbywatch_get_homepage_link($record['homepage']), $admin, true)
    . '</table>'
    . _lobbywatch_get_edit_link($admin, 'zutrittsberechtigung', $id)
    . '<h3>Mandate</h3>'
    . '<ul>'
    .    $record['mandate']
    . '</ul>'
    . _lobbywatch_get_workflow_state_line($record)
      . '</div>'
    ;

    $build = array();
    $build['lobbywatch_zutrittsberechtigung'] = array(
      '#weight' => -5,
      '#prefix' => '<div class="lobbywatch">',
      '#markup' => $markup,
      '#suffix' => '</div>',
      );

      return $build;
  } else {
//     drupal_goto('daten/zutrittsberechtigter');
  if (!$url_name) {
      drupal_not_found();
    } else {
      drupal_access_denied();
    };
  }

}

function _lobbywatch_ucase_SQL($str) {
  return "CONCAT(UCASE(LEFT($str, 1)), SUBSTRING($str, 2))";
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
  $lobbywatch_db_read_time = round(timer_read('lobbywatch_db_read') / 1000, 2);
  $page_build_time = _lobbywatch_page_build_secs();
  global $env;
  $admin = user_access('access lobbywatch admin');
  $unpublished = user_access('access lobbywatch unpublished content');

  $update_date = !empty($record['combined_updated_date_unix']) ? $record['combined_updated_date_unix'] : $record['updated_date_unix'];
//   dpm($record);
  return '<p class="lobbywatch-meta-fields-line"><small>'
    . t('Last updated !date', array ('!date' => '<time datetime="'.getISO8601Date($update_date).'">' . format_date($update_date, 'short_date') . '</time>')) .
    ($admin ?
      '<span class="admin">/' . $record ['updated_visa'] . '</span>' : '') .
      '<br>' . ($record['freigabe_datum'] && $record['freigabe_datum_unix'] < time() ? t('Veröffentlicht !date', array ('!date' => '<time datetime="'.getISO8601Date($record['freigabe_datum_unix']).'" pubdate="pubdate">' . format_date($record['freigabe_datum_unix'], 'short_date') . '</time>' )) . ($admin ? '<span class="admin">/' . $record ['freigabe_visa'] . '</span>' : '') : t('Unveröffentlicht')) .
    ($admin ?  '<span class="admin">
        <br>' . t('Created  @date', array ('@date' => format_date($record['created_date_unix'], 'short_date'))) . '/' . $record ['created_visa'] .
      ($record ['eingabe_abgeschlossen_datum_unix'] ? '<br>' . t('Eingabe  @date', array ('@date' => format_date($record ['eingabe_abgeschlossen_datum_unix'], 'short_date') )) . '/' .  $record ['eingabe_abgeschlossen_visa'] : '')
      . ($record ['kontrolliert_datum_unix'] ? '<br>' . t('Kontrolle  @date', array ('@date' => format_date($record ['kontrolliert_datum_unix'], 'short_date') )) . '/' .  $record ['kontrolliert_visa'] : '')
        . '</span>'
    : '') .

  '</small></p>' .
  ($admin ? "<p class=\"lobbywatch-meta-fields-line admin\"><small>Verwendete DB: $env; DB read time: ${lobbywatch_db_read_time}s; Page execution time: ${page_build_time}s</small></p>" : "");
}

function getISO8601Date($unix_timestamp) {
  return format_date($unix_timestamp, 'custom', 'Y-m-d\TH:i:sO'); // PHP 'c' format is not proper ISO8601!
}
