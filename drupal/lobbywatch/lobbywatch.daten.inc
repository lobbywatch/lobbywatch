<?php

// // Ref https://api.drupal.org/api/examples/ajax_example!ajax_example_autocomplete.inc/7
// function _lobbywatch_search_autocomplete($str = '', $check_unpublished = true) {

// //   return _lobbywatch_parlamentarier_list();
//   // Use the database we set up earlier
//   // Ref: https://drupal.org/node/18429
//   $old_db = db_set_active('lobbywatch');

//   try {
// //     $result = _lobbywatch_search_autocomplete_LIKE_UNION($str);
//     $result = _lobbywatch_search_autocomplete_LIKE_search_table($str);
// //     $result = _lobbywatch_search_autocomplete_FULLTEXT($str);

//   //   dpm($result, 'result');

//     $items = array();
//     while($record = $result->fetchAssoc()) {
//       $key = $record['name'] . " [" . check_plain($record['page']). '=' . check_plain($record['id']) . "]";
//       $items[$key] = check_plain(ucfirst($record['page']) . ': ' . $record['name']);
//     }
//   } finally {
//     // Go back to the default database,
//     // otherwise Drupal will not be able to access it's own data later on.
//     db_set_active($old_db);
//   }

// //   dpm($items, 'items');

//   drupal_json_output($items);
// }

// function _lobbywatch_search_autocomplete_LIKE_search_table($str, $check_unpublished = true) {
//   $sql = "
// SELECT id, page, name
// FROM v_search_table
// WHERE
// search_keywords LIKE :str ". ($check_unpublished && !user_access('access lobbywatch advanced content') ? ' AND (bis IS NULL OR bis > NOW())' : '') . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND freigabe_datum <= NOW()' : '') . "
// ORDER BY table_weight, weight
// LIMIT 20;";
//   //dpm($sql, 'suche');
//   $result = db_query($sql, array(':str' => _lobbywatch_search_keyword_processing($str)));

//   return $result;
// }

function _lobbywatch_search_form_LIKE_search_table($search_str) {
  $lang_suffix = get_lang_suffix();

  $table = 'search_table';
  $query = db_select("v_$table", $table)->extend('PagerDefault');
  $query->fields($table, array('id', 'page', "name$lang_suffix"));
  $query->condition("search_keywords$lang_suffix", $search_str, 'LIKE');
  if (!user_access('access lobbywatch unpublished content')) {
    //       $query->where('(bis IS NULL OR bis > NOW()) AND freigabe_datum <= NOW()');
//     $query->where('freigabe_datum <= NOW()');
    // Show all parlamentarier in search, even if not freigegeben, RKU 22.01.2015
    $query->condition(db_or()->condition('table_name', array('parlamentarier', 'zutrittsberechtigung'), 'IN')->where('freigabe_datum <= NOW()'));
  }
//   if (!user_access('access lobbywatch advanced content')) {
//     //       $query->where('(bis IS NULL OR bis > NOW()) AND freigabe_datum <= NOW()');
//     $query->condition(db_or()->isNull('bis')->where('bis > NOW()'));
//   }
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

// /**
//  * @deprecated Old list, now we use a view
//  */
// function _lobbywatch_parlamentarier_list($check_unpublished = true) {
//   $markup = t('<p>Liste der Parlamentarier</p>');
//   $markup .= '<table border="0"  class="tablesorter table-medium header-sticky-enabled">
//   <thead>
//   <tr><th>Name</th><th>Photo</th><th>Partei</th><th>Rat</th><th>Kanton</th></tr>
//   </thead>
//   <tbody>';

//   // Use the database we set up earlier
//   // Ref: https://drupal.org/node/18429
//   db_set_active('lobbywatch');

//   try {
//     $result = db_query("SELECT parlamentarier.*
//     FROM v_parlamentarier parlamentarier
//     WHERE "
//      . ($check_unpublished && !user_access('access lobbywatch advanced content') ? '(parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW()) AND parlamentarier.freigabe_datum <= NOW()' : '') . "
//     ORDER BY parlamentarier.anzeige_name");

//     $inactive = !$record['im_rat_bis'] || $record['im_rat_bis'] < time();

//     while ($record = $result->fetchAssoc()) {
//       $markup .= '<tr' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . '><td>' . ($inactive ? '<s>': '') . '<a href="/daten/parlamentarier/' . check_plain($record['id']) . '/' . _lobbywatch_clean_for_url($record['anzeige_name']) . '">' . check_plain($record['anzeige_name']) . '</a>' . ($inactive ? '</s>': '') . '</td><td><a href="/daten/parlamentarier/' . check_plain($record['id']) . '/' . _lobbywatch_clean_for_url($record['anzeige_name']) . '">' . '<img src="/sites/lobbywatch.ch/app/parlamentarier_photos/klein/' . check_plain($record['kleinbild']) . '" alt="' . check_plain($record['anzeige_name']) . '">' . '</a></td><td>' . check_plain($record['partei']) . '</td><td>' . check_plain($record['rat']) . '</td><td>' . check_plain($record['kanton']) . '</td></tr>';
//     }

//     $markup .= '</tbody></table>';
//   } finally {
//     // Go back to the default database,
//     // otherwise Drupal will not be able to access it's own data later on.
//     db_set_active();
//   }

//   $build = array();
//   $build['lobbywatch_parlamentarier_list'] = array(
//     '#weight' => -5,
//     '#prefix' => '<div class="lobbywatch">',
//     '#markup' => $markup,
//     '#suffix' => '</div>',
//     );

//   return $build;

// }


function _lobbywatch_page_title($prefix, $suffix, $table, $id, $title = '') {
//   df("_lobbywatch_page_title $prefix, $suffix, $table, $id");
//   df($prefix . _lobbywatch_fetch_anzeige_name($table, $id) . $suffix . ' title: '. $title, '_lobbywatch_page_title');
//   require_once '123';
  if ($table == 'parlamentarier') {
    $title = _lobbywatch_fetch_parlamentarier_title($table, $id);
  } elseif ($table == 'organsation') {
    $title = _lobbywatch_fetch_organisation_title($table, $id);
  } else {
    $name = _lobbywatch_fetch_anzeige_name($table, $id);
    if ($table == 'kommission') {
      // A hack: make kommission page title neutral from rat
      $name = preg_replace('/( |-)?(NR|SR|V|CN|CE)/', '', $name);
    }
    $title = ($prefix ? lt($prefix) . ' ' : '') . $name . ($suffix ?  ' ' . lt($suffix) : '');
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
  return lobbywatch_format_plural($interval->format('%y'), '1 Jahr', '@count Jahre');
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
  return ($years > 0 ? lobbywatch_format_plural($years, '1 Jahr', '@count Jahre') : '') . ($months > 0 ? ' ' . lobbywatch_format_plural($months, '1 Monat', '@count Monate') : '');
}

function _lobbywatch_gaesteMitMandaten($parlamentarier_id, $only_active_zutrittsberechtigte = true, $check_unpublished = true) {
  $admin = user_access('access lobbywatch admin');

  $lang = get_lang();
  $lang_suffix = get_lang_suffix();

  $sql = "SELECT zutrittsberechtigung.id, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.nachname, zutrittsberechtigung.vorname, zutrittsberechtigung.name, zutrittsberechtigung.funktion, zutrittsberechtigung.geschlecht, zutrittsberechtigung.lobbyfaktor, zutrittsberechtigung.lobbyfaktor_max, zutrittsberechtigung.anzahl_mandat_hoch, zutrittsberechtigung.anzahl_mandat_mittel, zutrittsberechtigung.anzahl_mandat_tief, zutrittsberechtigung.bis, zutrittsberechtigung.bis_unix, DATE_FORMAT(zutrittsberechtigung.bis, '%Y') bis_formatted,
GROUP_CONCAT(DISTINCT
    /* i18n */
    CONCAT('<li class=\"'," ._lobbywatch_add_admin_class_value_for_freigabe('mandat', 'organisation') . ", ' wirksamkeit-', mandat.wirksamkeit, '\" title=\"Wirksamkeit: ', REPLACE(mandat.wirksamkeit, 'tief', " . lts('gering') . "), '\" >', IF(mandat.bis < NOW(), '<s>', ''), '<a href=\"/$lang/daten/organisation/', organisation.id, '\">', " . lobbywatch_lang_field('organisation.name_de') . ", '</a>', " .
//     IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', ". _lobbywatch_get_rechtsform_translation_SQL("organisation"). ")),
//     IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)),
"     ', ', " . _lobbywatch_bindungsart('zutrittsberechtigung', 'mandat', 'organisation') . "," .
//    IF(mandat.beschreibung IS NULL OR TRIM(mandat.beschreibung) = '', '', CONCAT('<small class=\"desc\">, &quot;', mandat.beschreibung, '&quot;</small>')),
"    IF(mandat.bis < NOW(), CONCAT(', '," . lts('bis') . ", ' ', DATE_FORMAT(mandat.bis, '%Y'), '</s>'), ''))
    ORDER BY mandat.wirksamkeit_index DESC, organisation.anzeige_name
    SEPARATOR ' '
) mandate
FROM v_zutrittsberechtigung zutrittsberechtigung
LEFT JOIN v_mandat mandat
  ON mandat.person_id = zutrittsberechtigung.id " . ($check_unpublished && !user_access('access lobbywatch advanced content') ? ' AND (mandat.bis IS NULL OR mandat.bis > NOW())' : '') . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND mandat.freigabe_datum <= NOW() ' : '') . "
LEFT JOIN v_organisation organisation
  ON mandat.organisation_id = organisation.id " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '') . "
WHERE 1
  " . ($only_active_zutrittsberechtigte ? ' AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW())' : '') . "
  AND zutrittsberechtigung.parlamentarier_id=:id
" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND zutrittsberechtigung.freigabe_datum <= NOW()' : '') . "
GROUP BY zutrittsberechtigung.id
ORDER BY zutrittsberechtigung.bis ASC;";

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
    return '<p>' . lt('keine', array('context' => 'Mandate')) . '</p>';
//      throw new Exception('Parlamentarier ID not found');
  }

  $res = '';
  foreach($gaeste as $gast) {
    $active = $gast['bis'] == null || $gast['bis_unix'] > time();
    if (!$active && !$admin) {
      continue;
    }
    $res .= '<div' . (!$active && $admin ? ' class="admin"' : '') . '>';
    $res .= '<h4>' . (!$active && $admin ? '<s>' : '') . $gast['zutrittsberechtigung_name'] . ', ' . $gast['funktion'] . (isset($gast['beruf']) ? '/' . $gast['beruf'] : ''). (!$active && $admin ? ' '. lt('bis') . ' ' . $gast['bis_formatted'] : '') . ($admin ? ' <span class="admin">[LF: <b>'. $gast['lobbyfaktor'] . '</b>' . ($admin ? ' (' . round($gast['lobbyfaktor'] /$gast['lobbyfaktor_max'] * 100, 0) . '% von akt. max ' . $gast['lobbyfaktor_max'] . ')' .  ' <span class="admin">[' . $gast['anzahl_mandat_hoch'] . ', ' . $gast['anzahl_mandat_mittel'] . ', ' . $gast['anzahl_mandat_tief'] . ']</span>' : '') . ']' . '</span>': '') . (!$active && $admin ? '</s>' : '') . '</h4>';
    $res .= '<p>' . lt('!name ist mit folgenden Firmen, Verbänden und Organisationen verbunden:', array('!name' => "<a href=\"/$lang/daten/zutrittsberechtigter/" . $gast['id'] . '">' . $gast['name'] . '</a>'), array('context' => $gast['geschlecht'])) . '</p>';
    //$res .= mandateList($con, $gast['id']);
    $res .= $gast['mandate'] ? /*_lobbywatch_legende_wirksamkeit() .*/ "<ul>\n" . $gast['mandate'] . "\n</ul>" : '<p>' . lt('keine', array('context' => 'Mandate')) . '</p>';
    $res .= '</div>';
  }

  return $res;
}

function _lobbywatch_brancheMitInteressengruppen($kommission_id, $check_unpublished = true) {
  $lang = get_lang();
  $sql = "SELECT branche.id, " . lobbywatch_lang_field('branche.anzeige_name_de') . " as branche_name,
GROUP_CONCAT(DISTINCT
    CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('interessengruppe') . ", '><a href=\"/$lang/daten/lobbygruppe/', interessengruppe.id, '\">', " . lobbywatch_lang_field('interessengruppe.anzeige_name_de') . ", '</a>',
    " . ($lang != 'fr' ? " IF(interessengruppe.beschreibung IS NULL OR TRIM(interessengruppe.beschreibung) = '', '', CONCAT('<small class=\"desc\">, &quot;', interessengruppe.beschreibung, '&quot;</small>'))) " :
    " IF(interessengruppe.beschreibung_fr IS NULL OR TRIM(interessengruppe.beschreibung_fr) = '', '', CONCAT('<small class=\"desc\">, &quot;', interessengruppe.beschreibung_fr, '&quot;</small>'))) ") . "
    ORDER BY interessengruppe.anzeige_name
    SEPARATOR ' '
) interessengruppen
FROM `v_branche` branche
LEFT JOIN `v_interessengruppe` interessengruppe
  ON interessengruppe.branche_id = branche.id" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND interessengruppe.freigabe_datum <= NOW() ' : '') . "
WHERE
  (branche.kommission_id=:id OR branche.kommission2_id=:id)
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
    return '<p>' . lt('keine', array('context' => 'M')) . '</p>';
//      throw new Exception('Parlamentarier ID not found');
  }

  $res = '';
  foreach($branchen as $branche) {
    $res .= '<h4>' . lt('Lobbygruppen in der Branche @branche', array('@branche' => $branche['branche_name'])) . '</h4>';
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

  $lang = get_lang();
  $lang_suffix = get_lang_suffix();

//   cache_clear_all('translation:', 'cache', TRUE);
//   $trans = lt('des National- und des Ständerates');
//   df($trans);
//   $trans = lt('@titel_nachname ist mit folgenden Firmen, Verbänden und Organisationen verbunden', array('@titel_nachname' => 'TESTNAME'));
//   df($trans, "''");
//   $trans = lt('@titel_nachname ist mit folgenden Firmen, Verbänden und Organisationen verbunden', array('@titel_nachname' => 'TESTNAME'), array('context' => 'M'));
//   df($trans, "'M'");
//   $trans = lt('@titel_nachname ist mit folgenden Firmen, Verbänden und Organisationen verbunden', array('@titel_nachname' => 'TESTNAME'), array('context' => 'F'));
//   df($trans, "'F'");


  $admin = user_access('access lobbywatch admin');
  $adminBool = $admin ? "1" : "0";
  $advanced = user_access('access lobbywatch advanced content');
  $advancedBool = $advanced ? "1" : "0";

//   df($admin, 'admin');

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    set_db_session_parameters();

    $sql = //db_session_parameters_SQL() .
    "SELECT parlamentarier.id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.name, parlamentarier.geburtstag, DATE_FORMAT(parlamentarier.geburtstag, '%d.%m.%Y') as geburtstag_formatted, parlamentarier.beruf, parlamentarier.im_rat_seit, DATE_FORMAT(parlamentarier.im_rat_seit, '%d.%m.%Y') as im_rat_seit_formatted, parlamentarier.im_rat_bis, DATE_FORMAT(parlamentarier.im_rat_bis, '%d.%m.%Y') as im_rat_bis_formatted, parlamentarier.kleinbild, parlamentarier.rat, parlamentarier.rat$lang_suffix, parlamentarier.kanton, parlamentarier.geschlecht, parlamentarier.homepage, parlamentarier.homepage_2, parlamentarier.parlament_biografie_id, parlamentarier.partei_de, parlamentarier.partei_fr, parlamentarier.partei_id, parlamentarier.titel, parlamentarier.aemter, parlamentarier.weitere_aemter,
    parlamentarier.geschlecht, parlamentarier.im_rat_bis_unix,
    parlamentarier.name, parlamentarier.anzahl_kinder, rat.name$lang_suffix as rat_name$lang_suffix, kanton.einwohner, kanton.auslaenderanteil, kanton.anzahl_staenderaete, kanton.anzahl_nationalraete,"
        . _lobbywatch_get_workflow_state_SELECT_SQL('parlamentarier') . "
parlamentarier.titel$lang_suffix, parlamentarier.nachname,
parlamentarier.lobbyfaktor, parlamentarier.lobbyfaktor_max,
parlamentarier.anzahl_interessenbindung_tief,
parlamentarier.anzahl_interessenbindung_mittel,
parlamentarier.anzahl_interessenbindung_hoch,
parlamentarier.anzahl_interessenbindung_tief_nach_wahl,
parlamentarier.anzahl_interessenbindung_mittel_nach_wahl,
parlamentarier.anzahl_interessenbindung_hoch_nach_wahl,
parlamentarier.erfasst,
GREATEST(MAX(parlamentarier.updated_date_unix), MAX(interessenbindung.updated_date_unix)) as combined_updated_date_unix,
            GROUP_CONCAT(DISTINCT
      CONCAT('<li class=\"'," ._lobbywatch_add_admin_class_value_for_freigabe('interessenbindung', 'organisation') . ", ' wirksamkeit-', interessenbindung.wirksamkeit, '\" title=\"Wirksamkeit: ', REPLACE(interessenbindung.wirksamkeit, 'tief', 'gering'), '\" >',
          IF(interessenbindung.bis < NOW(), '<s>', ''),
          IF(interessenbindung.von > parlamentarier.im_rat_seit, '<i>', ''),
          '<a href=\"/$lang/daten/organisation/', organisation.id, '\">', " . lobbywatch_lang_field('organisation.name_de') . ", '</a>', " .
//       IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', ". _lobbywatch_get_rechtsform_translation_SQL("organisation"). ")),
//       IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)),
"      ', ', " . _lobbywatch_bindungsart('parlamentarier', 'interessenbindung', 'organisation') . ",
      IF(TRUE OR interessenbindung.beschreibung IS NULL OR TRIM(interessenbindung.beschreibung) = '', '', CONCAT(', ',interessenbindung.beschreibung)),
      IF(interessenbindung.behoerden_vertreter = 'J' AND parlamentarier.geschlecht='M', CONCAT(' (', " . lts('als Behördenvertreter') . ", ')'), ''),
      IF(interessenbindung.behoerden_vertreter = 'J' AND parlamentarier.geschlecht='F', CONCAT(' (', " . lts('als Behördenvertreterin') . ", ')'), ''),
      IF(interessenbindung.bis < NOW(), CONCAT(', '," . lts('bis') . ", ' ', DATE_FORMAT(interessenbindung.bis, '%Y'), '</s>'), ''),
      IF(interessenbindung.von > parlamentarier.im_rat_seit, CONCAT(', ', ". lts('seit') . ",' ', DATE_FORMAT(interessenbindung.von, '%Y'), '</i>'), ''))
      ORDER BY interessenbindung.wirksamkeit_index DESC, organisation.anzeige_name
      SEPARATOR ' '
    ) interessenbindungen,
    GROUP_CONCAT(DISTINCT
      CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('v', 'organisation2') . ", '>', IF(v.bis < NOW(), '<s>', ''), '<a href=\"/$lang/daten/organisation/', organisation2.id, '\">', " . lobbywatch_lang_field('organisation2.name_de') . ", '</a>',
      IF(organisation2.rechtsform IS NULL OR TRIM(organisation2.rechtsform) = '', '', CONCAT(', ', ". _lobbywatch_get_rechtsform_translation_SQL("organisation2"). ")),
      IF(organisation2.ort IS NULL OR TRIM(organisation2.ort) = '', '', CONCAT(', ', organisation2.ort)),
      IF(FALSE AND v.beziehung = 'indirekt', CONCAT(', <em>', v.beziehung, '</em>'), ''),
      IF(v.beziehung = 'direkt' AND v.verbindung = 'interessenbindung', CONCAT(' (', " . lts('direkte Interessenbindung') . ", ')'),
            IF(v.beziehung = 'direkt' AND v.verbindung = 'zutritt-mandat', CONCAT(' (', " . lts('direktes Gästemandat') . ", ')'),
              CONCAT(IF($adminBool, CONCAT(' <span class=\"admin\">(', v.beziehung, ', ', v.verbindung, ')','</span>'), ''),' ', " . lts('via') . ", ' ', '<a href=\"/$lang/daten/organisation/', zwischen_organisation.id, '\">', " . lobbywatch_lang_field("zwischen_organisation.name_de") .", '</a>'))),
      IF(v.bis < NOW(), CONCAT(', '," . lts('bis') . ", ' ', DATE_FORMAT(v.bis, '%Y'), '</s>'), '')
      )
      ORDER BY organisation2.anzeige_name
      SEPARATOR ' '
    ) verbundene_organisationen,
    GROUP_CONCAT(DISTINCT
      CONCAT('<li'," /*. _lobbywatch_add_admin_class_for_freigabe('ik', 'kommission' . ", " */ . "'>', IF(ik.bis < NOW(), '<s>', ''), " . _lobbywatch_add_expression_for_freigabe(($admin ? "CONCAT('<a href=\"/$lang/daten/kommission/', kommission.id, '\" class=\"unpublished\">')" : "''"),'ik', "CONCAT('<a href=\"/$lang/daten/kommission/', kommission.id, '\">')", 'kommission') . ", " . lobbywatch_lang_field('kommission.name') . ", ' (', " . lobbywatch_lang_field('kommission.abkuerzung') . ", ')', " .  _lobbywatch_add_expression_for_freigabe(($admin ? "'</a>'" : "''"),'ik', "'</a>'", 'kommission') . ", ' ',
--      IF(kommission.art IS NULL, '', CONCAT(', ', kommission.art)), /* i18n */
      IF(ik.bis < NOW(), CONCAT(', '," . lts('bis') . ", ' ', DATE_FORMAT(ik.bis, '%Y'), '</s>'), '')
      )
      ORDER BY kommission.abkuerzung
      SEPARATOR ' '
      ) kommissionen_namen,
    GROUP_CONCAT(DISTINCT
      CONCAT('<li'," /*._lobbywatch_add_admin_class_for_freigabe('zutrittsberechtigung') . ", "*/ . "'>', IF(zutrittsberechtigung.bis < NOW(), '<s>', ''), '<a href=\"/$lang/daten/zutrittsberechtigter/', zutrittsberechtigung.id, '\">',
      zutrittsberechtigung.name, '</a>, ',
      zutrittsberechtigung.funktion,
      IF(zutrittsberechtigung.beruf IS NULL OR TRIM(zutrittsberechtigung.beruf) = '', '', CONCAT(', ', zutrittsberechtigung.beruf)),
      IF(zutrittsberechtigung.bis < NOW(), CONCAT(', '," . lts('bis') . ", ' ', DATE_FORMAT(zutrittsberechtigung.bis, '%Y'), '</s>'), ''))
      ORDER BY zutrittsberechtigung.name
      SEPARATOR ' '
    ) zutrittsberechtigungen
      FROM v_parlamentarier parlamentarier
      LEFT JOIN v_interessenbindung interessenbindung
      ON interessenbindung.parlamentarier_id = parlamentarier.id" . ($check_unpublished && !user_access('access lobbywatch advanced content') ? ' AND (interessenbindung.bis IS NULL OR interessenbindung.bis > NOW())' : '') . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND interessenbindung.freigabe_datum <= NOW() ' : '') . "
      LEFT JOIN v_organisation organisation
      ON interessenbindung.organisation_id = organisation.id" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '') . "
      LEFT JOIN v_zutrittsberechtigung zutrittsberechtigung
      ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id "
      //($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND zutrittsberechtigung.freigabe_datum <= NOW() ' : '') .
    . ($check_unpublished && !user_access('access lobbywatch advanced content') ? " AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW())" : '')
      . " LEFT JOIN v_in_kommission ik
      ON parlamentarier.id = ik.parlamentarier_id" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND ik.freigabe_datum <= NOW() ' : '') . "
      LEFT JOIN v_kommission kommission
      ON ik.kommission_id=kommission.id" /*. ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND kommission.freigabe_datum <= NOW() ' : '')*/ . "
      LEFT JOIN v_organisation_parlamentarier_beide_indirekt v
      ON v.parlamentarier_id = parlamentarier.id"
      . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND v.freigabe_datum <= NOW() ' : '')
      . ($check_unpublished && !user_access('access lobbywatch advanced content') ? " AND (v.bis IS NULL OR v.bis > NOW())" : '')
       . " LEFT JOIN v_organisation zwischen_organisation
        ON v.zwischen_organisation_id = zwischen_organisation.id
      LEFT JOIN v_organisation organisation2
      ON organisation2.id = v.connector_organisation_id" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation2.freigabe_datum <= NOW() ' : '') . "
      LEFT JOIN v_kanton kanton
      ON kanton.id = parlamentarier.kanton_id" . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND kanton.freigabe_datum <= NOW() ' : '') . "
      LEFT JOIN v_rat rat
      ON rat.id = parlamentarier.rat_id " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND rat.freigabe_datum <= NOW() ' : '') . "
      WHERE
      parlamentarier.id=:id"
//            . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND parlamentarier.freigabe_datum <= NOW() ' : '')
          . "
      GROUP BY parlamentarier.id";

//      dpm($sql, 'sql');
//      df($sql, 'sql');

    $result = db_query($sql, array(':id' => $id));

  //     dpm($result, 'result');

      $record = $result->fetchAssoc();
      $gaeste = _lobbywatch_gaesteMitMandaten($id, false, $check_unpublished);

  //     dpm($record, '$record');

  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
  }

  if ($record) {
      $unpublished_permission = user_access('access lobbywatch unpublished content');
      $rat_name = $record["rat_name$lang_suffix"];
      $rat = $record["rat$lang_suffix"];
      $rat_id = $record["rat"];
      $titel_nachname  =  $record["titel$lang_suffix"] . ' ' . $record['nachname'];
      if ($rat_id == 'SR') {
        $vertretung = round($record['einwohner'] / $record['anzahl_staenderaete']);
      } else {
        $vertretung = round($record['einwohner'] / $record['anzahl_nationalraete']);
      }
      // RTODO security, check content
      $parlamentarier_name = check_plain($record["parlamentarier_name"]);
      $active = $record['im_rat_bis'] == null || $record['im_rat_bis_unix'] > time();
      $unpublished_record = !$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time();
      $not_erfasst = $record['erfasst'] == 'Nein';
      $show_data = true;

      if (!$active) {
        $markup .= /*i18n t*/ '<p class="message">' . lt('@name ist <strong>nicht mehr im Parlament</strong>.', array('@name' => $record['name'], '@bis' => $record['im_rat_bis_formatted'])) . '</p>';
        $show_data = false;
      } else if ($unpublished_record) {
        $markup .= '<div class="message">';
        $markup .= '<p>' . lt('Die Interessenbindungen dieses Parlamentsmitglieds wurden von !lobbywatch <strong>noch nicht erfasst</strong>. Wir arbeiten daran und freuen uns, wenn Sie demnächst wieder vorbeischauen.', array('!lobbywatch' => l('lobbywatch.ch', '<front>'))) . '</p>';
        $markup .= _lobbywatch_support_message();
        $markup .= '</div>';
        $show_data = false;
      } else if ($not_erfasst) {
//         $markup .= "<p><em>" . lt('Nicht erfasst. Beispielsweise weil er/sie nicht mehr zur Wiederwahl antritt.') . '</em></p>';
        $markup .= '<p class="message">' . lt('Dieses Parlamentsmitglied hat seinen <strong>Rücktritt angekündigt</strong> und wird deshalb von !lobbywatch.ch <strong>nicht mehr erfasst</strong>.', array('!lobbywatch' => l('lobbywatch.ch', '<front>'))) . '</p>';
        $show_data = false;
      }

      $markup .=
      (!$show_data && $unpublished_permission ? '<div ' . /*class="record-unpublished"*/ '>' . '<p class="unpublished">' . lt('Unveröffentlicht') . '</p>': '<div>') .
//       '<h2>' . $parlamentarier_name . '</h2>' .
      '<div class="clearfix">' .
      '<div style="float: left;"><a id="parlamentarier-kleinbild" class="parlamentarier-kleinbild" onclick="false"><img src="/sites/lobbywatch.ch/app/files/parlamentarier_photos/gross/' . check_plain($record['kleinbild']) . '" alt="' . $parlamentarier_name . '"></a></div>' .
      ($admin ? /*i18n t*/ '<p class="admin">Lobbyfaktor: <b>'. $record['lobbyfaktor'] . '</b> (' . round($record['lobbyfaktor'] /$record['lobbyfaktor_max'] * 100, 0) . '% von akt. max ' . $record['lobbyfaktor_max'] . ')' .  ' <span class="admin">[' . $record['anzahl_interessenbindung_hoch'] . '/' . $record['anzahl_interessenbindung_hoch_nach_wahl'] . ', ' . $record['anzahl_interessenbindung_mittel'] . '/' . $record['anzahl_interessenbindung_mittel_nach_wahl'] . ', ' . $record['anzahl_interessenbindung_tief'] . '/' . $record['anzahl_interessenbindung_tief_nach_wahl'] . ']</span>' . '</p>' : '') .

      /*i18n t*/ '<p><a id="show-bio" class="toggle-text" onclick="false"><span id="bio-text-show">' . lt('Angaben zur Person') . '</span><span id="bio-text-hide" style="display:none">' . lt('Verstecke Angaben zur Person') . '</span></a></p>'.

    '</div>' .
//       (!$active ? /*i18n t*/ "<p><strong><em>Nicht mehr im Parlament. ${record['name']} war im Parlament bis " . check_plain($record['im_rat_bis_formatted']) . '.</em></strong></p>' : '') .
      '<script>
jQuery(document).ready(function() {
    jQuery("#show-bio, #parlamentarier-kleinbild").click(function () {
//       jQuery("#show-bio").slideToggle("slow");
      jQuery("#bio").slideToggle("slow");
      jQuery("#bio-text-show").toggle();
      jQuery("#bio-text-hide").toggle();
    });
 });
</script>' .
    '<div id="bio" style="display:none">' .
    '<table border="0">' .
    // '<tr><td>Rat:</td><td>' . $rat . '</td></tr>' .
    // '<tr><td>Kanton:</td><td>' . check_plain($record['kanton']) . '</td></tr>' .
    // ($admin ? '<tr class="admin"><td>' . lt('Partei:') . '</td><td>' . l(translate_record_field($record, 'partei_de'), 'daten/partei/' . $record['partei_id']) . '</td></tr>' : '') .
    ('<tr><td>' . lt('Partei:') . '</td><td>' . l(translate_record_field($record, 'partei_de'), 'daten/partei/' . $record['partei_id']) . '</td></tr>') .
    '<tr><td>' . lt('Vertretene Bevölkerung:') . '</td><td>' . number_format($vertretung , 0 , '.' , '\'') . '</td></tr>' .
    '<tr><td>' . ($active ? lt('Im Parlament seit:') : lt('Dauer:')) . '</td><td>' . years_months(check_plain($record['im_rat_seit']), $record['im_rat_bis']) . ' (' . check_plain($record['im_rat_seit_formatted']) . ($active ? '' : ' – ' . check_plain($record['im_rat_bis_formatted']) ) . ')' . '</td></tr>' .
    '<tr><td>' . lt('Alter:') . '</td><td>' . years(check_plain($record['geburtstag'])) . ' (' . check_plain($record['geburtstag_formatted']) . ')' . '</td></tr>' .
    _lobbywatch_get_optional_tr(lt('Beruf:'), translate_record_field($record, 'beruf', false, true), $admin) .
    _lobbywatch_get_optional_tr(lt('Titel:'), $record['titel'], $admin) .
    _lobbywatch_get_optional_tr(lt('Kinder:'), $record['anzahl_kinder'], $admin) .
    _lobbywatch_get_optional_tr(lt('Homepage:'), _lobbywatch_get_homepage_link($record['homepage']), $admin, true, $record['homepage']) .
    _lobbywatch_get_optional_tr(lt('Homepage 2:'), _lobbywatch_get_homepage_link($record['homepage_2']), $admin, true, $record['homepage_2']) .
    '<tr><td>' . lt('Parlament.ch Webseite:') . '</td><td>' . ($record['parlament_biografie_id'] ? l($record['name'], /* i18n t change link*/'http://www.parlament.ch/d/suche/seiten/biografie.aspx?biografie_id=' . $record['parlament_biografie_id'], array('attributes' => array('class' => array('elf-external', 'elf-icon')))) : '' ) . '</td></tr>' .
    (($record['kommissionen_namen'] && $active) || $unpublished_permission ? '<tr><td>' . lt('Kommissionen:') . '</td><td>' . '<ul>' . $record['kommissionen_namen'] . '</ul>' . '</td></tr>' : '') .
    ($advanced ?
    _lobbywatch_get_optional_tr(lt('Ämter:'),
        _lobbywatch_convert_semicolons_to_ul(check_plain($record["aemter"])), $admin, true)
    . _lobbywatch_get_optional_tr(lt('Weitere Ämter:'),
        _lobbywatch_convert_semicolons_to_ul(check_plain($record["weitere_aemter"])), $admin, true)
        : ''
    ) .
    '</table>' .
    '</div>';

    $markup .= _lobbywatch_get_edit_link($admin, 'parlamentarier', $id) .
    ($admin ? '<div class="admin">Visualisierung EXPERIMENTELL: <a href="/de/daten/grafik/parlamentarier?id=' . $id . '">intern</a> / <a href="/visual/b1/ParlamentarierView.html?id=' . $id . '">extern</a></div>' : '');

    if (!$unpublished_record || $unpublished_permission) {
        $markup .= "<h3 style=\"display: none\">" . lt('Interessenbindungen') . "</h3><h3>" . lt('@titel_nachname ist mit folgenden Firmen, Verbänden und Organisationen verbunden', array('@titel_nachname' => $titel_nachname), array('context' => $record['geschlecht'])) . "</h3>";
        $markup .= ($record['interessenbindungen'] ? _lobbywatch_legende_wirksamkeit() . '<ul>' . $record['interessenbindungen'] . '</ul>' : '<p>' . lt('keine', array('context' => 'M')) . '</p>');
    $markup .= '<h3 style="display: none">' . lt('Tätigkeiten der Gäste') . '</h3>' . "<h3>" . lt('@titel_nachname ermöglicht folgenden Gästen Zutritt zum Bundeshaus', array('@titel_nachname' => $titel_nachname)) . "</h3>" . ($gaeste ? $gaeste : '<p>' . lt('keine', array('context' => 'M')) . '</p>');
    } else if (($not_erfasst || $unpublished_record) && $active) {
//       $markup .= '<b>' . lt('Die Interessenbindungen sind noch nicht recherchiert!') . '</b>';
//       df($record, 'rec');
      $markup .= '<h3>' . lt('Gäste') . "</h3><ul>" . ($record['zutrittsberechtigungen'] ? $record['zutrittsberechtigungen'] : lt('Keine')) . '</ul>';
    }

    $markup .= ($admin ? "<div class='admin'><h3>" . lt('Gäste') . "</h3><ul>" . $record['zutrittsberechtigungen'] . '</ul></div>' : '');
    $markup .= ($admin ? '<div class="admin"><h3>' . lt('Organisationen mit einer Verbindung') . '</h3>'
    . '<p>' . lt('Auch indirekte Verbindungen werden aufgeführt.') . '</p>'
    . '<ul>' . $record['verbundene_organisationen'] . '</ul>'
    . '<h3>' . lt('Kommissionen') . '</h3><ul>' . $record['kommissionen_namen'] . '</ul></div>' : '')
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

function _lobbywatch_support_message() {
  $lang = get_lang();

  $markup = '<p>' . lt('Lobbywatch.ch ist eine private Initiative von Journalistinnen und Journalisten sowie Informatikern. <b>Sie können unsere Arbeit unterstützen, indem Sie Mitglied von Lobbywatch.ch werden.</b> Für nur 50 Franken pro Jahr (Gönnermitglieder: 150 Franken) sind Sie dabei und leisten einen wichtigen Beitrag zu mehr Transparenz in der Wandelhalle.', array('!lobbywatch' => l('lobbywatch.ch', '<front>'))) . '</p>';
  $markup .= '<div>';
  if ($lang == 'fr') {
    $markup .= '<div style="padding:1em 0 0 0"><a href="/fr/contact?subject=Devenir%20membre&message=Je%20voudrais%20devenir%20membre%20de%20l\'association%20Lobbywatch.ch%20.%0A%0ANom%3A%20%0AAdresse%3A%20" title="Contacter Lobbywatch.ch et devenir membre" class="button" style="width:12em;display:inline-block;vertical-align: middle;">Devenir membre</a> <a href="/fr/contact?subject=Devenir%20membre%20donateur&message=Je%20voudrais%20devenir%20membre%20donateur%20de%20l\'association%20Lobbywatch.ch%20.%0A%0ANom%3A%20%0AAdresse%3A%20" title="Contacter Lobbywatch.ch et devenir membre donateur" class="button"style="width:12em;display:inline-block;vertical-align: middle;">Devenir membre donateur</a></div>';
  } else {
    $markup .= '<div style="padding:1em 0 0 0"><a href="/contact?subject=Mitglied%20werden&message=Ich%20m%C3%B6chte%20Mitglied%20des%20Vereins%20Lobbywatch.ch%20werden.%0A%0AName%3A%20%0AAdresse%3A%20" title="Lobbywatch.ch kontaktieren und Mitglied werden" class="button" style="width:8em;display:inline-block;vertical-align: middle;">Mitglied werden</a> <a href="/contact?subject=Gönnermitglied%20werden&message=Ich%20m%C3%B6chte%20Gönnermitglied%20des%20Vereins%20Lobbywatch.ch%20werden.%0A%0AName%3A%20%0AAdresse%3A%20" title="Lobbywatch.ch kontaktieren und Gönnermitglied werden" class="button"style="width:8em;display:inline-block;vertical-align: middle;">Gönner werden</a></div>';
  }
  $markup .= '<p>' . lt('Vielen Dank!') . '</p>';
  $markup .='</div>';
  return $markup;
}

function _lobbywatch_convert_semicolons_to_ul($field, $alternative = null) {
  return $field ? '<ul>'
      . implode(array_map(function($value) {return '<li>' . $value;}, explode(';', check_plain($field))))
      . '</ul>' : $alternative;
}

function _lobbywatch_legende_wirksamkeit() {
  drupal_add_library('system', 'ui.button');
  $rnd = rand();
  return '<div class="legend clearfix">'. lt('Wirksamkeit:') .
  '<button id = "button-legend-close" class="close" style="display:none">Close</button>' .
      '<ul class="legend-list-short" id="legend-short-'. $rnd . '"><li class="wirksamkeit-hoch">● = '. lt('hoch') . '</li> <li class="wirksamkeit-mittel">● = '. lt('mittel') . '</li> <li class="wirksamkeit-tief">● = '. lt('gering') . '</li></ul>' .
      '<div><a id="show-legend-detail" class="toggle-text" onclick="false"><span id="legend-text-show">' . lt('Zeige Erklärung') . '</span><span id="legend-text-hide" style="display:none">' . lt('Verstecke Erklärung') . '</span></a></div>' .
      '<div id="legend-detail-'. $rnd . '" style="display:none">' .

          '<h3>' . lt('Wirksame und weniger wirksame Lobbys') . '</h3>
          <p>' . lt('Anhand der Farbe lässt sich die Wirksamkeit einer Lobbyorganisation ablesen') . ' (<span class="wirksamkeit-hoch">'. lt('hoch') . '</span>, <span class="wirksamkeit-mittel">'. lt('mittel') . '</span>, <span class="wirksamkeit-tief">'. lt('gering') . '</span>).</p>
          <ul id="legend-long-'. $rnd . '" class="legend-list-long">
          <li><span class="wirksamkeit-hoch">● ' . lt('Für den Einflussfaktor «hoch», muss die Lobbyorganisation') . '</span>
              <ul><li>' . lt('immer oder punktuell an Gesetzesrevisionen mitarbeiten (Vernehmlassungen);') . '</li>
              <li>' . lt('im Parlament mit einer Person vertreten sein, die im Verband, in der Organisation et cetera eine Führungsaufgabe wahrnimmt (Geschäftsleitung, Verwaltungsrat, Stiftungsrat);') . '</li>
              <li>' . lt('mit ihrem Parlamentsmitglied in der behandelnden Kommission (z.B. Gesundheitskommission) vertreten sein.') . '</li></ul></li>
              <li><span class="wirksamkeit-mittel">● ' . lt('Für den Einflussfaktor «mittel» muss die Lobbyorganisation') . '</span>
              <ul>
              <li>' . lt('punktuell an Gesetzesrevisionen mitarbeiten (Vernehmlassungen);') . '</li>
              <li>' . lt('im Parlament mit einem Mitglied oder Beirat (Patronatskomitee et cetera) vertreten sein.') . '</li>
              </ul></li>
              <li><span class="wirksamkeit-tief">● ' . lt('Für den Einflussfaktor «gering» muss die Lobbyorganisation') . '</span>
              <ul>
              <li>' . lt('nie an Gesetzesrevisionen mitarbeiten (Vernehmlassungen);') . '</li>
              <li>' . lt('im Parlament mit einer Person vertreten sein.') . '</li>
              </ul>
              </li>
              </ul>
      </div>' .

      '<script>
jQuery(document).ready(function() {
      jQuery("#button-legend-close").button({
        icons: {
          primary: "ui-icon-close"
        },
        text: false
      });
      jQuery("#show-legend-detail, #button-legend-close").click(function () {
      jQuery("#show-legend-detail-'. $rnd . '").slideToggle("slow");
      jQuery("#legend-detail-'. $rnd . '").slideToggle("slow");
      jQuery("#legend-text-show").toggle();
      jQuery("#legend-text-hide").toggle();
      jQuery("#button-legend-close").toggle();
    });
 });
</script>' .
            '</div>';
}

function db_session_parameters_SQL() {
  return "SET SESSION group_concat_max_len=50000;";
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

function _lobbywatch_get_optional_tr($label, $value, $show_admin_always, $html = false, $field_value = null, $mark_admin = false) {
//   df($value, '_lobbywatch_get_optional_tr: $value of ' . $label);
  return (!$html && $value || $html && $field_value || $show_admin_always ? '<tr' . (!$html && !$value && $show_admin_always || $html && !$field_value && $show_admin_always || $mark_admin ? ' class="admin"' : '') . "><td>$label</td><td>" . ($html ? $value: check_plain($value)) . '</td></tr>' : '');
}

/**
 * RTODO
 */
function _lobbywatch_organisation_list($check_unpublished = true) {
  $markup = '<p>' .lt('Liste der Organisationen') . '</p>';
  $markup .= '<ul>';

  $lang = get_lang();
  $lang_suffix = get_lang_suffix();

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  $result = db_query("SELECT organisation.id, " . lobbywatch_lang_field('organisation.anzeige_name_de') . " as anzeige_name_lang,
organisation.freigabe_datum_unix, organisation.rechtsform
FROM v_organisation organisation  WHERE 1 " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '') . "  ORDER BY organisation.anzeige_name");

  while ($record = $result->fetchAssoc()) {
    $markup .=
    '<li' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . "><a href=\"/$lang/daten/organisation/" . check_plain($record['id']) . '">' . check_plain($record["anzeige_name_lang"]) . '</a>' . (check_plain($record['rechtsform']) ? ', ' . lt($record['rechtsform']) : '');
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

function _lobbywatch_verbundene_parlamentarier_FROM_SQL($check_unpublished = true) {
  return
  " LEFT JOIN v_organisation_parlamentarier_beide_indirekt v
      ON v.connector_organisation_id = organisation.id "
      . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND v.freigabe_datum <= NOW() ' : '')
      . ($check_unpublished && !user_access('access lobbywatch advanced content') ? " AND (v.bis IS NULL OR v.bis > NOW())" : '')
  . " LEFT JOIN v_organisation zwischen_organisation
        ON v.zwischen_organisation_id = zwischen_organisation.id "
  . " LEFT JOIN v_organisation connector_organisation
      ON v.connector_organisation_id = connector_organisation.id
    LEFT JOIN v_parlamentarier parlamentarier
      ON parlamentarier.id = v.parlamentarier_id "
      . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND parlamentarier.freigabe_datum <= NOW() ' : '')
      . ($check_unpublished && !user_access('access lobbywatch advanced content') ? " AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())" : '');
}

function _lobbywatch_verbundene_parlamentarier_SELECT_SQL($show_connector_organisation) {
    $lang = get_lang();
    $lang_suffix = get_lang_suffix();
    $admin = user_access('access lobbywatch admin');
    $adminBool = $admin ? "1" : "0";
    $advanced = user_access('access lobbywatch advanced content');
    $advancedBool = $advanced ? "1" : "0";
    $show_connector_organisationBool = $show_connector_organisation ? "1" : "0";
    return "      GROUP_CONCAT(DISTINCT
        CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('v', 'parlamentarier') . ", '>', IF((v.bis < NOW()) OR (parlamentarier.im_rat_bis < NOW()), '<s>', IF($advancedBool AND (parlamentarier.im_rat_bis >= NOW()), '<span class=\"retirement\">', '')), '<a href=\"/$lang/daten/parlamentarier/', parlamentarier.id, '\">', parlamentarier.anzeige_name, '</a>',
        ', ', parlamentarier.rat$lang_suffix,
        IF(parlamentarier.partei IS NULL, '', CONCAT(', ', " . lobbywatch_lang_field("parlamentarier.partei_de") . ")),
        ', ', parlamentarier.kanton,
        IF(FALSE AND v.beziehung = 'indirekt', CONCAT(', <em>', v.beziehung, '</em>'), ''),
        IF(v.beziehung = 'direkt' AND v.verbindung = 'interessenbindung', CONCAT(' (', " . lts('direkte Interessenbindung') .
              ", IF(/*$adminBool AND*/ $show_connector_organisationBool, CONCAT(' <span class=\"admin-disabled\">[<a href=\"/$lang/daten/organisation/', connector_organisation.id, '\">', " . lobbywatch_lang_field("connector_organisation.name_de") . ", '</a>]','</span>'), '')" . ", ')'),
            IF(v.beziehung = 'direkt' AND v.verbindung = 'zutritt-mandat', CONCAT(' (', " . lts('direktes Gästemandat') .
                ", IF(/*$adminBool AND*/ $show_connector_organisationBool, CONCAT(' <span class=\"admin-disabled\">[<a href=\"/$lang/daten/organisation/', connector_organisation.id, '\">', " . lobbywatch_lang_field("connector_organisation.name_de") . ", '</a>]','</span>'), '')" . ", ')'),
              CONCAT(IF($adminBool, CONCAT(' <span class=\"admin\">(', v.beziehung, ', ', v.verbindung, ')','</span>'), ''),' ', " . lts('via') . ", ' ', '<a href=\"/$lang/daten/organisation/', zwischen_organisation.id, '\">', " . lobbywatch_lang_field("zwischen_organisation.name_de") . ", '</a>'))),
        IF((v.bis < NOW()) OR (parlamentarier.im_rat_bis < NOW()), CONCAT(', '," . lts('bis') . ", ' ', DATE_FORMAT(LEAST(IFNULL(v.bis, parlamentarier.im_rat_bis), IFNULL(parlamentarier.im_rat_bis, v.bis)), '%Y'), IF(parlamentarier.im_rat_bis < NOW(), CONCAT(' '," . lts('im Rat') . "), IF($advancedBool AND (parlamentarier.im_rat_bis >= NOW()), '</span>', '')), '</s>'), '')
        )
        ORDER BY parlamentarier.anzeige_name
        SEPARATOR ' '
      ) verbundene_parlamentarier";
}

function _lobbywatch_organisation_id($id, $name = '', $check_unpublished = true) {

  //   dpm($id, 'id');
  timer_start('lobbywatch_db_read');

  $url_name = _lobbywatch_anzeige_name_for_url('organisation', $id);
  if ($name !== $url_name) {
    drupal_goto("daten/organisation/$id/$url_name");
  }

  $lang = get_lang();
  $lang_suffix = get_lang_suffix();
  $admin = user_access('access lobbywatch admin');
  $adminBool = $admin ? "1" : "0";

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

   try {
    set_db_session_parameters();

    // Number of transitiv joins
    $num_arbeitet_fuer = $admin ? 2 : 0;
    $num_mitglied_von = $admin ? 2 : 0;
    $num_tochtergesellschaft_von = $admin ? 3 : 0;
    $num_partner = 0;
    $num_beiteiligung_an = 0;

    // 'arbeitet fuer','mitglied von','tochtergesellschaft von','partner von','beteiligt an'
    $sql = "SELECT organisation.id, organisation.freigabe_datum_unix, organisation.lobbyeinfluss, organisation.anzahl_interessenbindung_hoch, organisation.anzahl_interessenbindung_mittel, organisation.anzahl_interessenbindung_tief, organisation.anzahl_interessenbindung_hoch_nach_wahl, organisation.anzahl_interessenbindung_mittel_nach_wahl, organisation.anzahl_interessenbindung_tief_nach_wahl, organisation.anzahl_mandat_hoch, organisation.anzahl_mandat_mittel, organisation.anzahl_mandat_tief, organisation.rechtsform, organisation.ort, organisation.interessengruppe_branche_de, organisation.interessengruppe_branche_fr, organisation.interessengruppe_branche_id, organisation.interessengruppe, organisation.interessengruppe_fr, organisation.interessengruppe_id, organisation.beschreibung, organisation.beschreibung_fr, organisation.homepage, organisation.handelsregister_url, organisation.uid, organisation.anzeige_name_de, organisation.anzeige_name_fr, "
. _lobbywatch_get_workflow_state_SELECT_SQL('organisation') . " "
. _lobbywatch_organisation_beziehung_SELECT_SQL('arbeitet_fuer', $num_arbeitet_fuer) . ", "
. _lobbywatch_organisation_beziehung_SELECT_SQL('auftraggeber_fuer', $num_arbeitet_fuer)  . ", "
. _lobbywatch_organisation_beziehung_SELECT_SQL('mitglied_von', $num_mitglied_von) . ", "
. _lobbywatch_organisation_beziehung_SELECT_SQL('mitglieder', $num_mitglied_von)  . ", "
. _lobbywatch_organisation_beziehung_SELECT_SQL('tochtergesellschaften', $num_tochtergesellschaft_von) . ", "
. _lobbywatch_organisation_beziehung_SELECT_SQL('muttergesellschaften', $num_tochtergesellschaft_von)  . ", "
. _lobbywatch_organisation_beziehung_SELECT_SQL('partner', $num_partner) . ", "
. _lobbywatch_organisation_beziehung_SELECT_SQL('beteiligungen_an', $num_beiteiligung_an) . ", "
. _lobbywatch_organisation_beziehung_SELECT_SQL('beteiligungen_von', $num_beiteiligung_an) . ", "
. _lobbywatch_verbundene_parlamentarier_SELECT_SQL(false) //. ", "
. "
  FROM v_organisation organisation "
    . _lobbywatch_verbundene_parlamentarier_FROM_SQL()
    . _lobbywatch_organisation_beziehung_FROM_SQL('organisation', 'arbeitet fuer', true, $num_arbeitet_fuer, 'arbeitet_fuer', 'auftraggeber_fuer')
    . _lobbywatch_organisation_beziehung_FROM_SQL('organisation', 'mitglied von', true, $num_mitglied_von, 'mitglied_von', 'mitglieder')
    . _lobbywatch_organisation_beziehung_FROM_SQL('organisation', 'tochtergesellschaft von', true, $num_tochtergesellschaft_von, 'muttergesellschaften', 'tochtergesellschaften')
    . _lobbywatch_organisation_beziehung_FROM_SQL('organisation', 'partner von', false, $num_partner, 'partner', 'partner_reverse')
    . _lobbywatch_organisation_beziehung_FROM_SQL('organisation', 'beteiligt an', true, $num_beiteiligung_an, 'beteiligungen_an', 'beteiligungen_von')
  . "
  WHERE organisation.id=:id "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '') . "
  GROUP BY organisation.id";

//      dpm($sql);

  $result = db_query($sql, array(':id' => $id));
  $record = $result->fetchAssoc();

//   dpm($record, '$record');

  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
  }

  if (isset($record['id'])) {
    $unpublished_permission = user_access('access lobbywatch unpublished content');
    $markup .=
    (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . lt('Unveröffentlicht') . '</p>': '<div>') .
    ($admin ? '<p class="admin">' . lt('Lobbyeinfluss:') . ' <b>'. $record['lobbyeinfluss'] . '</b>' .  ' <span class="admin">[' . $record['anzahl_interessenbindung_hoch'] . '/' . $record['anzahl_interessenbindung_hoch_nach_wahl'] . ', ' . $record['anzahl_interessenbindung_mittel'] . '/' . $record['anzahl_interessenbindung_mittel_nach_wahl'] . ', ' . $record['anzahl_interessenbindung_tief'] . '/' . $record['anzahl_interessenbindung_tief_nach_wahl'] . '], [' . $record['anzahl_mandat_hoch'] . ', ' . $record['anzahl_mandat_mittel'] . ', ' . $record['anzahl_mandat_tief'] . ']' . '</span>' : '') . '</p>' .
//    '<h2>' . check_plain($record["anzeige_name"]) . '</h2>' .
//     . '<h3>Informationen</h3>'
    '<table border="0">'
//     . '<li>Vernehmlassung: ' . $record['vernehmlassung']
       . _lobbywatch_get_optional_tr(lt('Rechtsform:'), lt($record['rechtsform']), $admin)
       . _lobbywatch_get_optional_tr(lt('Ort:'), $record['ort'], $admin)
    . '<tr><td>'. lt('Branche:') . '</td><td>' . l(translate_record_field($record, 'interessengruppe_branche_de'), 'daten/branche/' . $record['interessengruppe_branche_id']) . '</td></tr>'
    . '<tr><td>'. lt('Lobbygruppe:') . '</td><td>' . l(translate_record_field($record, 'interessengruppe'), 'daten/lobbygruppe/' . $record['interessengruppe_id']) . '</td></tr>'
       . _lobbywatch_get_optional_tr(lt('Beschreibung:'), translate_record_field($record, 'beschreibung', true), $admin, true, translate_record_field($record, 'beschreibung'))
       . _lobbywatch_get_optional_tr(lt('Homepage:'), _lobbywatch_get_homepage_link($record['homepage']), $admin, true, $record['homepage'])
       .  ($admin ? _lobbywatch_get_optional_tr(lt('Handelsregister:'), _lobbywatch_get_homepage_link($record['handelsregister_url'], translate_record_field($record, 'anzeige_name_de')), $admin, true, $record['handelsregister_url'], true) : '')
       . _lobbywatch_get_optional_tr(lt('UID:'), l($record['uid'], 'https://www.uid.admin.ch/Detail.aspx?uid_id=' . $record['uid']), $admin, true, $record['uid'])
       . ($admin ? _lobbywatch_get_optional_tr(lt('Handelsregister:'), l($record['uid'], 'http://zefix.ch/WebServices/Zefix/Zefix.asmx/SearchFirm?id=' . $record['uid']), $admin, true, $record['uid'], true) : '')
    . '</table>' .
    _lobbywatch_get_edit_link($admin, 'organisation', $id)
    . ($admin ? '<p class="admin">Visualisierung EXPERIMENTELL: <a href="/de/daten/grafik/organisation?id=' . $id . '">intern</a> / <a href="/visual/b1/OrganisationView.html?id=' . $id . '">extern</a></p>' : '');

    $markup .= _lobbywatch_print_organisation_beziehung($record, lt('Arbeitet für'), 'arbeitet_fuer', $num_arbeitet_fuer);
    $markup .= _lobbywatch_print_organisation_beziehung($record, lt('Auftraggeber für'), 'auftraggeber_fuer', $num_arbeitet_fuer);
    $markup .= _lobbywatch_print_organisation_beziehung($record, lt('Mitglied von'), 'mitglied_von', $num_mitglied_von);
    $markup .= _lobbywatch_print_organisation_beziehung($record, lt('Mitglieder'), 'mitglieder', $num_mitglied_von);
    $markup .= _lobbywatch_print_organisation_beziehung($record, lt('Oberorganisation ("Muttergesellschaft")'), 'muttergesellschaften', $num_tochtergesellschaft_von);
    $markup .= _lobbywatch_print_organisation_beziehung($record, lt('Unterorganisationen ("Tochtergesellschaften, Zweigstellen")'), 'tochtergesellschaften', $num_tochtergesellschaft_von);
    $markup .= _lobbywatch_print_organisation_beziehung($record, lt('Partnerorganisationen'), 'partner', $num_partner);
    $markup .= _lobbywatch_print_organisation_beziehung($record, lt('Beteiligungen an'), 'beteiligungen_an', $num_beiteiligung_an);
    $markup .= _lobbywatch_print_organisation_beziehung($record, lt('Beteiligungen von'), 'beteiligungen_von', $num_beiteiligung_an);

    if ($record['verbundene_parlamentarier']) {
      $markup .= '<h3>'. lt('Parlamentarierverbindungen') . '</h3>'
      . '<p>'. lt('Verbindungen sind direkt über Parlamentarier, indirekt über zutrittsberechtigte Gäste oder indirekt über Mandate von verbundenen PR- oder Beratungsunternehmen.') . '</p>'
      . '<ul>'
      . $record['verbundene_parlamentarier']
      . '</ul>';
//       dpm($record['verbundene_parlamentarier'], 'verbundene_parlamentarier');
    } else {
      $markup .= '<p><strong>' . lt('Diese Organisation verfügt zurzeit über keine Verbindung ins Parlament.') .'</strong></p>';
    }

    // RTODO weitere verbindungstypen hinzufügen

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

function _lobbywatch_print_organisation_beziehung($record, $title, $field_name_base, $transitiv_num) {
  $lang = get_lang();
  $lang_suffix = get_lang_suffix();
  $admin = user_access('access lobbywatch admin');
  $adminBool = $admin ? "1" : "0";

  $markup = '';
  if ($record["${field_name_base}_0"]) {
    $markup .= "<h3>$title</h3>"
    . '<ul>'
    . $record["${field_name_base}_0"]
    . '</ul>';
    if ($admin) {
      $markup .= "<div class='admin'>";
      for($i = 1; $i <= $transitiv_num; $i++) {
        if ($record["${field_name_base}_$i"]) {
          $markup .= '<h4>'. lt('Transitiv') . ' ' . $i . '</h4>'
              . '<ul>'
              . $record["${field_name_base}_$i"]
              . '</ul>';
        } else {
          break;
        }
      }
      $markup .= "</div>";
    }
  }
  return $markup;
}

function _lobbywatch_get_homepage_link($url, $text = null) {
  // preg_replace('|(?!https?://)(.*)|i', '//\1', $url)
  if (!$url) {
    return '';
  }
  if (!preg_match('|^(https?://)|i', $url)) {
    $url = 'http://' . $url;
  }
  return ($url ? l($text ? $text : preg_replace('|https?://|i', '', $url), $url, array('attributes' => array('external' => true, 'absolute' => true, 'class' => array('elf-external', 'elf-icon')))) : '');
}

/**
 * RTODO
 */
function _lobbywatch_kommission_list($check_unpublished = true) {
  $lang = get_lang();
  $lang_suffix = get_lang_suffix();

  $markup = t('<p>'. lt('Liste der Kommissionen') . '</p>');
  $markup .= '<ul>';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  $result = db_query("SELECT kommission.id, " . lobbywatch_lang_field('kommission.anzeige_name_de') . " as anzeige_name_lang, kommission.freigabe_datum_unix
  FROM `v_kommission` kommission  WHERE 1 " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND kommission.freigabe_datum <= NOW() ' : '') . "
  ORDER BY kommission.anzeige_name");

  while ($record = $result->fetchAssoc()) {
    $markup .= '<li' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . "><a href=\"/$lang/daten/kommission/" . check_plain($record['id']) . '">' . check_plain($record["anzeige_name_lang"]) . '</a>';
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
  $lang = get_lang();
  $lang_suffix = get_lang_suffix();

  return "SELECT kommission.id, kommission.freigabe_datum_unix, kommission.abkuerzung_de, kommission.abkuerzung_fr, kommission.parlament_url, kommission.anzeige_name_de, kommission.anzeige_name_fr, kommission.beschreibung, kommission.beschreibung_fr, kommission.typ, kommission.art, kommission.sachbereiche_de, kommission.sachbereiche_fr, zweitrat_kommission.id as zweitrat_id, zweitrat_kommission.abkuerzung_de as zweitrat_abkuerzung_de, zweitrat_kommission.abkuerzung_fr as zweitrat_abkuerzung_fr, zweitrat_kommission.anzeige_name_de as zweitrat_anzeige_name_de, zweitrat_kommission.anzeige_name_fr as zweitrat_anzeige_name_fr,"
      . _lobbywatch_get_workflow_state_SELECT_SQL('kommission') . "
      GROUP_CONCAT(DISTINCT
        CONCAT('<li'," . _lobbywatch_add_admin_class_for_freigabe('parlamentarier') . ", '>', '<a href=\"/$lang/daten/parlamentarier/', parlamentarier.parlamentarier_id, '\">', parlamentarier.parlamentarier_name, '</a>',
        ', ', parlamentarier.rat,
        IF(parlamentarier.partei_id IS NULL, '', CONCAT(', ', " . lobbywatch_lang_field("parlamentarier.partei_de") . ")),
        ', ', parlamentarier.kanton "
      . ",  IF((parlamentarier.bis < NOW()) OR (parlamentarier.im_rat_bis < NOW()), CONCAT(', '," . lts('bis') . ", ' ', DATE_FORMAT(LEAST(IFNULL(parlamentarier.bis, parlamentarier.im_rat_bis), IFNULL(parlamentarier.im_rat_bis, parlamentarier.bis)), '%Y'), IF(parlamentarier.im_rat_bis < NOW(), CONCAT(' '," . lts('im Rat') . "), '')), '')
        )
        ORDER BY parlamentarier.parlamentarier_name
        SEPARATOR ' '
      ) parlamentarier_liste,
      GROUP_CONCAT(DISTINCT
        IF(parlamentarier.rat='NR', CONCAT('<li'," . _lobbywatch_add_admin_class_for_freigabe('parlamentarier') . ", '>', '<a href=\"/$lang/daten/parlamentarier/', parlamentarier.parlamentarier_id, '\">', parlamentarier.parlamentarier_name, '</a>',
        IF(parlamentarier.partei_id IS NULL, '', CONCAT(', ', " . lobbywatch_lang_field("parlamentarier.partei_de") . ")),
        ', ', parlamentarier.kanton " .
       ",  IF((parlamentarier.bis < NOW()) OR (parlamentarier.im_rat_bis < NOW()), CONCAT(', '," . lts('bis') . ", ' ', DATE_FORMAT(LEAST(IFNULL(parlamentarier.bis, parlamentarier.im_rat_bis), IFNULL(parlamentarier.im_rat_bis, parlamentarier.bis)), '%Y'), IF(parlamentarier.im_rat_bis < NOW(), CONCAT(' '," . lts('im Rat') . "), '')), '')
        ), '')
        ORDER BY parlamentarier.parlamentarier_name
        SEPARATOR ' '
      ) nr,
      GROUP_CONCAT(DISTINCT
        IF(parlamentarier.rat='SR', CONCAT('<li'," . _lobbywatch_add_admin_class_for_freigabe('parlamentarier') . ", '>', '<a href=\"/$lang/daten/parlamentarier/', parlamentarier.parlamentarier_id, '\">', parlamentarier.parlamentarier_name, '</a>',
        IF(parlamentarier.partei_id IS NULL, '', CONCAT(', ', " . lobbywatch_lang_field("parlamentarier.partei_de") . ")),
        ', ', parlamentarier.kanton " .
       ",  IF((parlamentarier.bis < NOW()) OR (parlamentarier.im_rat_bis < NOW()), CONCAT(', '," . lts('bis') . ", ' ', DATE_FORMAT(LEAST(IFNULL(parlamentarier.bis, parlamentarier.im_rat_bis), IFNULL(parlamentarier.im_rat_bis, parlamentarier.bis)), '%Y'), IF(parlamentarier.im_rat_bis < NOW(), CONCAT(' '," . lts('im Rat') . "), '')), '')
       ), '')
        ORDER BY parlamentarier.parlamentarier_name
        SEPARATOR ' '
      ) sr,
      GROUP_CONCAT(DISTINCT
        CONCAT('<li'," . _lobbywatch_add_admin_class_for_freigabe('branche') . ", '>', '<a href=\"/$lang/daten/branche/', branche.id, '\">', " . lobbywatch_lang_field('branche.anzeige_name_de') . ", '</a>'
        )
        ORDER BY branche.anzeige_name
        SEPARATOR ' '
      ) branchen
  FROM `v_kommission` kommission
  LEFT JOIN `v_in_kommission_parlamentarier` parlamentarier
  ON (kommission.id = parlamentarier.kommission_id OR kommission.zweitrat_kommission_id = parlamentarier.kommission_id) "
  . ($only_active_parlamentarier ? " AND (parlamentarier.bis IS NULL OR parlamentarier.bis > NOW()) AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())"
      : " AND (parlamentarier.bis < NOW() OR parlamentarier.im_rat_bis < NOW()) ")
  . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND parlamentarier.freigabe_datum <= NOW() ' : '') . "
  LEFT JOIN `v_branche` branche
  ON (kommission.id = branche.kommission_id OR kommission.id = branche.kommission2_id) " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND branche.freigabe_datum <= NOW() ' : '') . "
  LEFT JOIN `v_kommission` zweitrat_kommission
  ON (zweitrat_kommission.id = kommission.zweitrat_kommission_id) " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND zweitrat_kommission.freigabe_datum <= NOW() ' : '') . "
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

  $lang = get_lang();
  $lang_suffix = get_lang_suffix();

  $admin = user_access('access lobbywatch admin');
  $unpublished_permission = user_access('access lobbywatch unpublished content');
  $advanced = user_access('access lobbywatch advanced content');
  $advancedBool = $advanced ? "1" : "0";

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    set_db_session_parameters();

    $sql = _lobbywatch_kommission_id_SQL(true);

  // IF(organisation_auftraggeber_fuer.bis < NOW(), '<s>', ''),

  $result = db_query($sql, array(':id' => $id));

//         dpm($result, 'result');

  $record_active = $result->fetchAssoc();
//   dpm ($record_active, '$record_active');

  $sql = _lobbywatch_kommission_id_SQL(false);

  // IF(organisation_auftraggeber_fuer.bis < NOW(), '<s>', ''),

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
    (!$record_active['freigabe_datum_unix'] || $record_active['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . lt('Unveröffentlicht') . '</p>': '<div>') .
//      '<h2>' . check_plain($record_active["anzeige_name"]) . '</h2>' .
    ($admin ? '<table border="0" class="admin">'
    . '<tr><td>' . lt('Abkürzung:') . '</td><td>' . check_plain($record_active["abkuerzung$lang_suffix"]) . '</td></tr>'
    . '<tr><td>' . lt('Parlament.ch Link:') . '</td><td>' . (isset($record_active['parlament_url']) ? l($record_active["anzeige_name$lang_suffix"], $record_active['parlament_url'], array('attributes' => array('class' => array('elf-external', 'elf-icon')))) : '') . '</td></tr>'
    . '<tr><td>' . lt('Beschreibung:') . '</td><td>' . ucfirst(translate_record_field($record_active, 'beschreibung', true)) . '</td></tr>'
    . '<tr><td>' . lt('Typ:') . '</td><td>' . lt($record_active['typ']) . '</td></tr>'
    . '<tr><td>' . lt('Art:') . '</td><td>' . ($record_active['art'] ? lt($record_active['art']) : '') . '</td></tr>'
    . '<tr><td>' . lt('Kommission im anderen Rat:') . '</td><td>' . (isset($record_active['zweitrat_id']) ? l(translate_record_field($record_active, 'zweitrat_anzeige_name_de'), 'daten/kommission/' . $record_active['zweitrat_id']) : '') . '</td></tr>'
    . '</table>' : '')
    . _lobbywatch_get_edit_link($admin, 'kommission', $id)
    . '<h3>' . lt('Sachbereiche') . '</h3>'
    . _lobbywatch_convert_semicolons_to_ul(check_plain($record_active["sachbereiche$lang_suffix"]))
    . '</ul>'
    // RTODO präsidium
    . '<h3>' . lt('Mitglieder der @kommission_abkuerzung des Ständerates', array('@kommission_abkuerzung' => $record_active["abkuerzung$lang_suffix"])) . '</h3>'
    . '<ul>'
    . $record_active['sr']
    . '</ul>'
    . '<h3>' . lt('Mitglieder der @kommission_abkuerzung des Nationalrates', array('@kommission_abkuerzung' => $record_active["abkuerzung$lang_suffix"])) . '</h3>'
    . '<ul>'
    . $record_active['nr']
    . '</ul>'
//     . '<h3>Mitglieder</h3>'
//     . '<ul>'
//     . $record_active['parlamentarier']
//     . '</ul>'
    . '<h3>' . lt('Branchen in diesem Themenbereich') . '</h3>'
    . '<ul>'
    . $record_active['branchen']
    . '</ul>'
    . '<h3>' . lt('Lobbygruppen') . '</h3>'
    . $interessengruppen
    . ($advanced ? '<div class="advanced"><h3>' . lt('Ehemalige Mitglieder') . '</h3>'
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
  $lang = get_lang();
  $lang_suffix = get_lang_suffix();

  $markup = t('<p>' . lt('Liste der Parteien') . '</p>');
  $markup .= '<ul>';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  $result = db_query("SELECT partei.id, " . lobbywatch_lang_field('partei.anzeige_name_de') . " as anzeige_name_lang, partei.freigabe_datum_unix
       FROM v_partei partei  WHERE 1 "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND partei.freigabe_datum <= NOW() ' : '') . " ORDER BY " . lobbywatch_lang_field('partei.anzeige_name_de') . ";");

  while ($record = $result->fetchAssoc()) {
    $markup .= '<li' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . "><a href=\"/$lang/daten/partei/" . check_plain($record['id']) . '">' . check_plain($record["anzeige_name_lang"]) . '</a>';
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

  $lang = get_lang();
  $lang_suffix = get_lang_suffix();

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    set_db_session_parameters();

    $sql = "SELECT partei.id, partei.freigabe_datum_unix, partei.homepage,"
        . _lobbywatch_get_workflow_state_SELECT_SQL('partei') . "
      GROUP_CONCAT(DISTINCT
        CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('parlamentarier') . ",
      IF(parlamentarier.freigabe_datum IS NULL OR parlamentarier.freigabe_datum > NOW(), ' class=\"unpublished\"', ''), '>',
      IF(parlamentarier.im_rat_bis < NOW(), '<s>', ''), '<a href=\"/$lang/daten/parlamentarier/', parlamentarier.id, '\">', parlamentarier.anzeige_name, '</a>',
        ', ', parlamentarier.rat$lang_suffix,
        IF(parlamentarier.partei_id IS NULL, '', CONCAT(', ', " . lobbywatch_lang_field("parlamentarier.partei_de") . ")),
        ', ', parlamentarier.kanton,
      IF(parlamentarier.im_rat_bis < NOW(), CONCAT(' <small>, ', ". lts('im Rat bis') . ", ' ', DATE_FORMAT(parlamentarier.im_rat_bis, '%d.%m.%Y'), '</small></s>'), '')
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
    $unpublished_permission = user_access('access lobbywatch unpublished content');
    $markup .=
    (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . lt('Unveröffentlicht') . '</p>': '<div>') .
//    '<h2>' . check_plain($record["anzeige_name"]) . '</h2>'
    '<table border=0>'
        . '<tr><td>' . lt('Homepage:') . '</td><td>' . _lobbywatch_get_homepage_link($record['homepage']) . '</td></tr>'
            . '</table>'
    . _lobbywatch_get_edit_link($admin, 'partei', $id)
    . '<h3>' . lt('Mitglieder') . '</h3>'
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
  $lang = get_lang();
  $lang_suffix = get_lang_suffix();

  $markup = t('<p>' . lt('Liste der Branchen') . '</p>');
  $markup .= '<ul>';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  $result = db_query("SELECT branche.id, " . lobbywatch_lang_field('branche.anzeige_name_de') . " as anzeige_name_lang, branche.freigabe_datum_unix FROM v_branche branche WHERE 1 "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND branche.freigabe_datum <= NOW() ' : '') . " ORDER BY branche.anzeige_name");

  while ($record = $result->fetchAssoc()) {
    $markup .= '<li' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . "><a href=\"/$lang/daten/branche/" . check_plain($record['id']) . '">' . check_plain($record["anzeige_name_lang"]) . '</a>';
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

  $lang = get_lang();
  $lang_suffix = get_lang_suffix();

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    set_db_session_parameters();

    $sql = "SELECT branche.id, branche.freigabe_datum_unix, branche.kommission1, branche.kommission1_fr, branche.kommission2, branche.kommission2_fr, branche.kommission_id, branche.kommission2_id, branche.beschreibung, branche.beschreibung_fr, branche.name, branche.name_fr, "
        . _lobbywatch_get_workflow_state_SELECT_SQL('branche') . "
GROUP_CONCAT(DISTINCT
    CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('interessengruppe') . ", '>', '<a href=\"/$lang/daten/lobbygruppe/', interessengruppe.id, '\">', " . lobbywatch_lang_field('interessengruppe.anzeige_name_de') . ", '</a>',
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
    $unpublished_permission = user_access('access lobbywatch unpublished content');
    $markup .=
    (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . lt('Unveröffentlicht') . '</p>': '<div>') .
//     '<h2>' . check_plain($record["anzeige_name"]) . '</h2>' .
    '<table border="0">'
//     . '<li>Vernehmlassung: ' . $record['vernehmlassung']
//     . ($admin ? '<tr class="admin"><td>' . lt('Zuständige Kommission') . '</td><td>' . l(translate_record_field($record, 'kommission'), 'daten/kommission/' . $record['kommission_id']) . '</td></tr>' : '')
    . '<tr><td>' . lt('Zuständige Kommission') . '</td><td>' . l(translate_record_field($record, 'kommission1'), 'daten/kommission/' . $record['kommission_id']) . ($record['kommission2_id'] ? ' / ' . l(translate_record_field($record, 'kommission2'), 'daten/kommission/' . $record['kommission2_id']) : '') . '</td></tr>'
    . '<tr><td>' . lt('Beschreibung') . '</td><td>' . ucfirst(translate_record_field($record, 'beschreibung', true)) . '</td></tr>'
    . '</table>'
    . _lobbywatch_get_edit_link($admin, 'branche', $id)
    . '<h3 style="display:none">' . lt('Lobbygruppen') . '</h3>'
    . '<p>' . lt('In der Branche @branche sind die folgenden Lobbygruppen tätig:', array('@branche' => translate_record_field($record, 'name'))) . '</p>'
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
  $lang = get_lang();
  $lang_suffix = get_lang_suffix();
  $markup = t('<p>' . lt('Liste der Lobbygruppen') . '</p>');
  $markup .= '<ul>';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  $result = db_query("SELECT interessengruppe.id, " . lobbywatch_lang_field('interessengruppe.anzeige_name_de') . " as anzeige_name_lang, interessengruppe.freigabe_datum_unix FROM v_interessengruppe interessengruppe WHERE 1 "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND interessengruppe.freigabe_datum <= NOW() ' : '') . " ORDER BY interessengruppe.anzeige_name");

  while ($record = $result->fetchAssoc()) {
    $markup .= '<li' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . "><a href=\"/$lang/daten/lobbygruppe/" . check_plain($record['id']) . '">' . check_plain($record['anzeige_name_lang']) . '</a>';
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

  $lang = get_lang();
  $lang_suffix = get_lang_suffix();
  $admin = user_access('access lobbywatch admin');
  $adminBool = $admin ? "1" : "0";
  $advanced = user_access('access lobbywatch advanced content');
  $advancedBool = $advanced ? "1" : "0";

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    set_db_session_parameters();
  $sql = "
      SELECT interessengruppe.id, interessengruppe.freigabe_datum_unix, interessengruppe.branche, interessengruppe.branche_fr, interessengruppe.branche_id, interessengruppe.beschreibung, interessengruppe.beschreibung_fr, interessengruppe.kommission1, interessengruppe.kommission1_fr, interessengruppe.kommission2, interessengruppe.kommission2_fr, interessengruppe.kommission_id, interessengruppe.kommission2_id, "
        . _lobbywatch_get_workflow_state_SELECT_SQL('interessengruppe') . "
      GROUP_CONCAT(DISTINCT
      IF($advancedBool = 0 AND (organisation_aktiv.bis < NOW() OR organisation_aktiv.im_rat_bis < NOW()), '', CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('organisation') . ", '>', IF(organisation_aktiv.bis < NOW() OR organisation_aktiv.im_rat_bis < NOW(), '<s>', IF($advancedBool AND (organisation_aktiv.im_rat_bis >= NOW()), '<span class=\"retirement\">', '')), '<a href=\"/$lang/daten/organisation/', organisation.id, '\">', " . lobbywatch_lang_field('organisation.anzeige_name_de') . ", '</a>',
        IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', ". _lobbywatch_get_rechtsform_translation_SQL("organisation") . ")),
          IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)) " .
    // ", IF(organisation.beschreibung IS NULL OR TRIM(organisation.beschreibung) = '', '', CONCAT('<small class=\"desc\">, &quot;', " . _lobbywatch_ucase_SQL('organisation.beschreibung') . ", '&quot;</small>')) " .
      ", IF(organisation_aktiv.bis < NOW() OR organisation_aktiv.im_rat_bis < NOW(), CONCAT(', '," . lts('bis') . ", ' ', DATE_FORMAT(COALESCE(organisation_aktiv.bis, organisation_aktiv.im_rat_bis), '%Y'), '</s>'), IF($advancedBool AND (organisation_aktiv.im_rat_bis >= NOW()), '</span>', ''))
      )
  )
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
) organisationen,
"
        . _lobbywatch_verbundene_parlamentarier_SELECT_SQL(true) . ",
      GROUP_CONCAT(DISTINCT
        CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('parlamentarier_for_beruf') . ", '>', IF((parlamentarier_for_beruf.im_rat_bis < NOW()), '<s>', IF($advancedBool AND (parlamentarier_for_beruf.im_rat_bis >= NOW()), '<span class=\"retirement\">', '')), '<a href=\"/$lang/daten/parlamentarier_for_beruf/', parlamentarier_for_beruf.id, '\">', parlamentarier_for_beruf.anzeige_name, '</a>',
        ', ', parlamentarier_for_beruf.rat$lang_suffix,
        /*IF(parlamentarier_for_beruf.partei IS NULL, '', CONCAT(', ', parlamentarier_for_beruf.partei$lang_suffix)),*/
        IF(parlamentarier_for_beruf.partei_id IS NULL, '', CONCAT(', ', " . lobbywatch_lang_field("parlamentarier_for_beruf.partei_de") . ")),
        ', ', parlamentarier_for_beruf.kanton,
        IF(parlamentarier_for_beruf.beruf$lang_suffix IS NULL, '', CONCAT(', ', parlamentarier_for_beruf.beruf$lang_suffix)),
        IF((parlamentarier_for_beruf.im_rat_bis < NOW()), CONCAT(', '," . lts('bis') . ", ' ', DATE_FORMAT(parlamentarier_for_beruf.im_rat_bis, '%Y'), ' '," . lts('im Rat') . ", '', '</s>'), IF($advancedBool AND (parlamentarier_for_beruf.im_rat_bis >= NOW()), '</span>', ''))
        )
        ORDER BY parlamentarier_for_beruf.anzeige_name
        SEPARATOR ' '
      ) parlamentarier_berufe
      FROM v_interessengruppe interessengruppe "
       . " LEFT JOIN v_organisation organisation
        ON (interessengruppe.id = organisation.interessengruppe_id OR interessengruppe.id = organisation.interessengruppe2_id OR interessengruppe.id = organisation.interessengruppe3_id) "
             . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '')
//              . ($check_unpublished && !user_access('access lobbywatch advanced content') ? " AND organisation.id = v.connector_organisation_id AND (v.bis IS NULL OR v.bis > NOW())" : '')
      . " LEFT JOIN v_organisation_parlamentarier_beide_indirekt organisation_aktiv
        ON organisation_aktiv.connector_organisation_id = organisation.id "
//         . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation_aktiv.freigabe_datum <= NOW() ' : '')
//         . ($check_unpublished && !user_access('access lobbywatch advanced content') ? " AND (organisation_aktiv.bis IS NULL OR organisation_aktiv.bis > NOW())" : '')
      . _lobbywatch_verbundene_parlamentarier_FROM_SQL()
         . "
      LEFT JOIN v_parlamentarier parlamentarier_for_beruf
        ON parlamentarier_for_beruf.beruf_interessengruppe_id = interessengruppe.id "
        . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND parlamentarier_for_beruf.freigabe_datum <= NOW() ' : '')
        . ($check_unpublished && !user_access('access lobbywatch advanced content') ? " AND (parlamentarier_for_beruf.im_rat_bis IS NULL OR parlamentarier_for_beruf.im_rat_bis > NOW())" : '')
        . " WHERE interessengruppe.id=:id"
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND interessengruppe.freigabe_datum <= NOW() ' : '') .
  " GROUP BY interessengruppe.id";

//   dpm($sql);
/*
 * GROUP_CONCAT(DISTINCT
    CONCAT('<li><a href=\"/$lang/daten/lobbygruppe/', interessengruppe.id, '\">', interessengruppe.anzeige_name, '</a>',
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
//   $sql = "SELECT
//       GROUP_CONCAT(DISTINCT
//         CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('v', 'parlamentarier') . ", '>', IF((v.bis < NOW()) OR (parlamentarier.im_rat_bis < NOW()), '<s>', ''), '<a href=\"/$lang/daten/parlamentarier/', parlamentarier.id, '\">', parlamentarier.anzeige_name, '</a>',
//         ', ', parlamentarier.rat,
//         IF(parlamentarier.partei IS NULL, '', CONCAT(', ', parlamentarier.partei)),
//         ', ', parlamentarier.kanton,
//         IF((v.bis < NOW()) OR (parlamentarier.im_rat_bis < NOW()), CONCAT(', bis ', DATE_FORMAT(LEAST(IFNULL(v.bis, parlamentarier.im_rat_bis), IFNULL(parlamentarier.im_rat_bis, v.bis)), '%Y'), IF(parlamentarier.im_rat_bis < NOW(), ' im Rat', ''), '</s>'), '')
//         )
//         ORDER BY parlamentarier.anzeige_name
//         SEPARATOR ' '
//       ) verbundene_parlamentarier
//   FROM v_interessenbindung_liste v "
//    . "
//   LEFT JOIN v_parlamentarier parlamentarier
//   ON parlamentarier.id = v.parlamentarier_id " . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND parlamentarier.freigabe_datum <= NOW() ' : '') . "
//   WHERE  "
// . " (v.interessengruppe_id = :id OR v.interessengruppe2_id = :id OR v.interessengruppe3_id = :id) "
// . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND v.freigabe_datum <= NOW() ' : '')
// . " GROUP BY parlamentarier.id";

//   $result = db_query($sql, array(':id' => $id));

  //     dpm($result, 'result');

  $record_parlamentarier_direkt = $result->fetchAssoc();
//   dpm($record, '$record');
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
  }
  if ($record) {
    $unpublished_permission = user_access('access lobbywatch unpublished content');
    $markup .=
    (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . lt('Unveröffentlicht') . '</p>': '<div>') .
//     '<h2>' . check_plain($record["anzeige_name"]) . '</h2>' .
    '<table border="0">'
//     . '<li>Vernehmlassung: ' . $record['vernehmlassung']
    . '<tr><td>' . lt('Branche') . '</td><td>' . l(translate_record_field($record, 'branche'), 'daten/branche/' . $record['branche_id']) . '</td></tr>'
    . '<tr><td>' . lt('Beschreibung') . '</td><td>' . ucfirst(translate_record_field($record, 'beschreibung', true)) . '</td></tr>'
    . '<tr><td>' . lt('Zuständige Kommission') . '</td><td>' . l(translate_record_field($record, 'kommission1'), 'daten/kommission/' . $record['kommission_id']) . ($record['kommission2_id'] ? ' / ' . l(translate_record_field($record, 'kommission2'), 'daten/kommission/' . $record['kommission2_id']) : '') . '</td></tr>'
    . '</table>'
    . _lobbywatch_get_edit_link($admin, 'interessengruppe', $id)
     . '<h3>' . lt('Organisationen') . '</h3>'
      . '<ul>' . $record['organisationen'] . '</ul>';

    $markup .= '<h3>'. lt('Parlamentarierverbindungen') . '</h3>';
    if ($record['verbundene_parlamentarier']) {
      $markup .= '<h4>'. lt('Interessenbindungen und via Zutrittsberechtigte') . '</h4>';
         $markup .= '<p>'. lt('Verbindungen sind direkt über Parlamentarier, indirekt über zutrittsberechtigte Gäste oder indirekt über Mandate von verbundenen PR- oder Beratungsunternehmen.') . '</p>'
          . '<ul>'
          . $record['verbundene_parlamentarier']
          . '</ul>';
      //       dpm($record['verbundene_parlamentarier'], 'verbundene_parlamentarier');
    }

    if ($record['parlamentarier_berufe']) {
      $markup .= '<h4>'. lt('Beruf') . '</h4>'
          . '<p>'. lt('Verbindungen durch Berufe der Parlamentarier.') . '</p>'
          . '<ul>'
          . $record['parlamentarier_berufe']
          . '</ul>';
      //       dpm($record['verbundene_parlamentarier'], 'verbundene_parlamentarier');
    }

//      . '<h3>Parlamentarier mit einer Interessenbindung</h3>'
//       . '<ul>' . $record_parlamentarier_direkt['verbundene_parlamentarier'] . '</ul>'
    $markup .= _lobbywatch_get_workflow_state_line($record)
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
  $markup = t('<p>' . lt('Liste der Zutrittsberechtigten') . '</p>');

  $lang = get_lang();
  $lang_suffix = get_lang_suffix();

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  $result = db_query("SELECT zutrittsberechtigung.id, zutrittsberechtigung.anzeige_name, zutrittsberechtigung.bis_unix, DATE_FORMAT(zutrittsberechtigung.bis, '%Y') bis_formatted, zutrittsberechtigung.freigabe_datum_unix, zutrittsberechtigung.parlamentarier_id, zutrittsberechtigung.parlamentarier_name, zutrittsberechtigung.funktion,
      parlamentarier.partei as parlamentarier_partei, parlamentarier.kanton as parlamentarier_kanton
       FROM v_zutrittsberechtigung zutrittsberechtigung LEFT JOIN v_parlamentarier parlamentarier ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id WHERE 1 "
//        . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND zutrittsberechtigung.freigabe_datum <= NOW() ' : '')
       . ($check_unpublished && !user_access('access lobbywatch advanced content') ? ' AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW()) ' : '')
      . "  ORDER BY zutrittsberechtigung.anzeige_name");

//   $markup .= '<ul>';
//   while ($record = $result->fetchAssoc()) {
//     $markup .= '<li' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . ">" . ($record['bis_unix'] && $record['bis_unix'] < time() ? '<s>': '') . "<a href=\"/$lang/daten/zutrittsberechtigter/" . check_plain($record['id']) . '">' . check_plain($record['anzeige_name']) . '</a>' . ($check_unpublished && user_access('access lobbywatch advanced content') ? ' <span class="advanced">(' . lt('von') . " <a href=\"/$lang/daten/parlamentarier/" . check_plain($record['parlamentarier_id']) . '">' . check_plain($record['parlamentarier_name']) . '</a>' . ')</span>' : '') . ($record['bis_unix'] && $record['bis_unix'] < time() ? ' '. lt('bis') . ' ' . check_plain($record['bis_formatted']) . '</s>': '');
//   }
//   $markup .= '</ul>';

  $markup .= '<table border=0 class="header-sticky-enabled tablesorter">';
  $markup .= '<thead><tr><th>' . lt('Zutrittsberechtigter') . '</th><th>' .lt('Deklarierte Funktion') . '</th><th>' . lt('Erfasst') . '</th><th>' . lt('Eingeladen von') . '</th></tr></thead>';
  $markup .= '<tbody>';
  while ($record = $result->fetchAssoc()) {
    $strikeOpen = ($record['bis_unix'] && $record['bis_unix'] < time() ? '<s>': '');
    $bisText = ($record['bis_unix'] && $record['bis_unix'] < time() ? ' '. lt('bis') . ' ' . check_plain($record['bis_formatted']) : '');
    $strikeClose = ($record['bis_unix'] && $record['bis_unix'] < time() ? '</s>': '');
    $notErfasst = !$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time();
    $markup .= '<tr' . ($notErfasst ? ' class="unreleased"': '') . '><td>' . $strikeOpen . "<a href=\"/$lang/daten/zutrittsberechtigter/" . check_plain($record['id']) . '">' . check_plain($record['anzeige_name']) . '</a>' . $bisText . $strikeClose . '</td><td>' . $strikeOpen . check_plain(translate_record_field($record, 'funktion')) . $strikeClose . '</td><td>' . $strikeOpen . ($notErfasst ? lt('Nein') : lt('Ja')) . $strikeClose . '</td><td>' . $strikeOpen . " <a href=\"/$lang/daten/parlamentarier/" . check_plain($record['parlamentarier_id']) . '">' . check_plain($record['parlamentarier_name'] . ', ' . ($record['parlamentarier_partei'] ? $record['parlamentarier_partei'] : lt('parteilos')) . ', ' . $record['parlamentarier_kanton']) . '</a>' . '' . $strikeClose . '</td></tr>';
  }
  $markup .= '</tbody>';
  $markup .= '</table>';

  // Parlamentarier ohne Zutrittsberechtigte
  $result = db_query("SELECT parlamentarier.id, parlamentarier.anzeige_name, parlamentarier.partei, parlamentarier.kanton, parlamentarier.freigabe_datum_unix
      FROM v_parlamentarier parlamentarier
      WHERE NOT EXISTS (
          SELECT id
            FROM v_zutrittsberechtigung zutrittsberechtigung
            WHERE zutrittsberechtigung.parlamentarier_id = parlamentarier.id AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW()))
      AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())
   ORDER BY parlamentarier.anzeige_name");

  //   $markup .= '<ul>';
  //   while ($record = $result->fetchAssoc()) {
  //     $markup .= '<li' . (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? ' class="unpublished"': '') . ">" . ($record['bis_unix'] && $record['bis_unix'] < time() ? '<s>': '') . "<a href=\"/$lang/daten/zutrittsberechtigter/" . check_plain($record['id']) . '">' . check_plain($record['anzeige_name']) . '</a>' . ($check_unpublished && user_access('access lobbywatch advanced content') ? ' <span class="advanced">(' . lt('von') . " <a href=\"/$lang/daten/parlamentarier/" . check_plain($record['parlamentarier_id']) . '">' . check_plain($record['parlamentarier_name']) . '</a>' . ')</span>' : '') . ($record['bis_unix'] && $record['bis_unix'] < time() ? ' '. lt('bis') . ' ' . check_plain($record['bis_formatted']) . '</s>': '');
  //   }
  //   $markup .= '</ul>';

  $markup .= '<h3>' . lt('Parlamentarier ohne Zutrittsberechtigte') . '</h3>';
  $markup .= '<p>' . lt('Parlamentarier, welche aktuell keine Gästepässe vergeben haben') . '</>';
  $markup .= '<table border=0 class="header-sticky-enabled tablesorter">';
  $markup .= '<thead><tr><th>' . lt('Parlamentarier') . '</th><th>' . lt('Kanton') . '</th><th>' . lt('Partei') . '</th></tr></thead>';
  $markup .= '<tbody>';
  while ($record = $result->fetchAssoc()) {
    $notErfasst = !$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time();
    $markup .= '<tr' . ($notErfasst ? ' class="unreleased"': '') . '><td>' . "<a href=\"/$lang/daten/parlamentarier/" . check_plain($record['id']) . '">' . check_plain($record['anzeige_name']) . '</a></td><td>' . ($record['partei'] ? $record['partei'] : lt('parteilos')) . '</td><td>' . $record['kanton'] . '</a>' . '</td></tr>';
  }
  $markup .= '</tbody>';
  $markup .= '</table>';


  $markup .= '<h3>' . lt('Quelle') . '</h3>';
  $markup .= '<p>';
  $markup .= '<div>' . lt('Offizielle Listen des Parlamentes:') . '</div><ul>';
  $markup .= '<li><a href="http://www.parlament.ch/d/organe-mitglieder/nationalrat/Documents/zutrittsberechtigte-nr.pdf">' . lt('Zutrittsberechtigte des Nationalrates') . '</a>';
  $markup .= '<li><a href="http://www.parlament.ch/d/organe-mitglieder/staenderat/Documents/zutrittsberechtigte-sr.pdf">' . lt('Zutrittsberechtigte des Ständerates') . '</a>';
  $markup .= '</ul>';
  $markup .= '</p>';

  // Go back to the default database,
  // otherwise Drupal will not be able to access it's own data later on.
  db_set_active();

  $build = array();
  $build['lobbywatch_zutrittsberechtigung_list'] = array(
    '#weight' => -5,
    '#prefix' => '<div class="lobbywatch zutrittsberechtigung-list">',
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

  $lang = get_lang();
  $lang_suffix = get_lang_suffix();

  $markup = '';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    set_db_session_parameters();

    $sql = "SELECT zutrittsberechtigung.id, zutrittsberechtigung.bis, zutrittsberechtigung.bis_unix, zutrittsberechtigung.freigabe_datum_unix, zutrittsberechtigung.name, zutrittsberechtigung.erfasst, zutrittsberechtigung.lobbyfaktor, zutrittsberechtigung.lobbyfaktor_max, zutrittsberechtigung.anzahl_mandat_hoch, zutrittsberechtigung.anzahl_mandat_mittel, zutrittsberechtigung.anzahl_mandat_tief, /*zutrittsberechtigung.parlamentarier_name, zutrittsberechtigung.parlamentarier_id,*/ zutrittsberechtigung.funktion, zutrittsberechtigung.funktion_fr, zutrittsberechtigung.beruf, zutrittsberechtigung.beruf_fr, zutrittsberechtigung.partei_id, zutrittsberechtigung.partei_de, zutrittsberechtigung.partei_fr, zutrittsberechtigung.homepage,"
        . _lobbywatch_get_workflow_state_SELECT_SQL('zutrittsberechtigung') . "
        DATE_FORMAT(zutrittsberechtigung.bis, '%d.%m.%Y') as bis_formatted,
  GROUP_CONCAT(DISTINCT
    CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe('mandat', 'organisation') . ", '>', IF(mandat.bis < NOW(), '<s>', ''), '<a href=\"/$lang/daten/organisation/', organisation.id, '\">', " . lobbywatch_lang_field('organisation.anzeige_name_de') . ", '</a>', " .
//     IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', ". _lobbywatch_get_rechtsform_translation_SQL("organisation"). ")),
//     IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)),
"    ', ', " . _lobbywatch_bindungsart('zutrittsberechtigung', 'mandat', 'organisation') . ", " .
//     IF(TRUE AND (mandat.beschreibung IS NULL OR TRIM(mandat.beschreibung) = ''), '', CONCAT('<small class=\"desc\">, &quot;', " . _lobbywatch_ucase_SQL('mandat.beschreibung') . ", '&quot;</small>')),
"    IF(mandat.bis < NOW(), CONCAT(', '," . lts('bis') . ", ' ', DATE_FORMAT(mandat.bis, '%Y'), '</s>'), ''))
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
) mandate,
  GROUP_CONCAT(DISTINCT
    CONCAT('<li'" /*. "," . _lobbywatch_add_admin_class_for_freigabe('zutrittsberechtigung', 'parlamentarier')*/ . ", '>', IF(zutrittsberechtigung.bis < NOW(), '<s>', ''), '<a href=\"/$lang/daten/parlamentarier/', parlamentarier.id, '\">', " . lobbywatch_lang_field('parlamentarier.anzeige_name_de') . ", '</a>', " .
"    IF(zutrittsberechtigung.bis < NOW(), CONCAT(', '," . lts('bis') . ", ' ', DATE_FORMAT(zutrittsberechtigung.bis, '%Y'), '</s>'), ''))
    ORDER BY zutrittsberechtigung.bis
    SEPARATOR ' '
) zutritt_von_parlamentarier
FROM v_zutrittsberechtigung zutrittsberechtigung
LEFT JOIN v_mandat mandat
  ON mandat.person_id = zutrittsberechtigung.id " . ($check_unpublished && !user_access('access lobbywatch advanced content') ? ' AND (mandat.bis IS NULL OR mandat.bis > NOW())' : '')
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND mandat.freigabe_datum <= NOW() ' : '') . "
LEFT JOIN v_organisation organisation
  ON mandat.organisation_id = organisation.id "
       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '') . "
LEFT JOIN v_parlamentarier parlamentarier
  ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id " . ($check_unpublished && !user_access('access lobbywatch advanced content') ? ' AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW())' : '')
//       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND parlamentarier.freigabe_datum <= NOW()  AND zutrittsberechtigung.freigabe_datum <= NOW()' : '')
        . " WHERE
  zutrittsberechtigung.id=:id "
//       . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND zutrittsberechtigung.freigabe_datum <= NOW() ' : '')
. "GROUP BY zutrittsberechtigung.id;";

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
      $unpublished_permission = user_access('access lobbywatch unpublished content');
      $active = $record['bis'] == null || $record['bis_unix'] > time();
      $unpublished_record = !$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time();
      $show_data = true;

      if (!$active) {
        $markup .= /*i18n t*/ '<p class="message">' . lt('@name hat <strong>keine Zutrittsberechtigung mehr</strong>.', array('@name' => $record['name'], '@bis' => $record['bis_formatted'])) . '</p>';
        $show_data = false;
      } else if ($unpublished_record) {
        $markup .= '<div class="message">';
        $markup .= '<p>' . lt('Die Mandate dieses Zutrittsberechtigten wurden von !lobbywatch <strong>noch nicht erfasst</strong>. Wir arbeiten daran und freuen uns, wenn Sie demnächst wieder vorbeischauen.', array('!lobbywatch' => l('lobbywatch.ch', '<front>'))) . '</p>';
        $markup .= _lobbywatch_support_message();
        $markup .= '</div>';
        $markup .= '<table border="0">'
        . '<tr><td>' . lt('Eingeladen von:') . '</td><td>' . $record['zutritt_von_parlamentarier'] . '</td></tr>'
        . '<tr><td>' . lt('Deklarierte Funktion:') . '</td><td>' . check_plain(translate_record_field($record, 'funktion')) . '</td></tr>'
            . '</table>'
            ;
                $show_data = false;
      }
//       else if ($record['erfasst'] == 'Nein') {
// //         $markup .= "<p><em>" . lt('Nicht erfasst. Beispielsweise weil er/sie nicht mehr zur Wiederwahl antritt.') . '</em></p>';
//         $markup .= '<p class="message">' . lt('Dieses Parlamentsmitglied hat seinen Rücktritt angekündigt und wird deshalb von !lobbywatch.ch nicht mehr erfasst.', array('!lobbywatch' => l('lobbywatch.ch', '<front>'))) . '</p>';
//         $show_data = false;
//       }

  if ($show_data || $unpublished_permission) {
    $admin = user_access('access lobbywatch admin');
    $unbublished = user_access('access lobbywatch unpublished content');
    $markup .=
    (!$record['freigabe_datum_unix'] || $record['freigabe_datum_unix'] > time() ? '<div class="record-unpublished"><p class="unpublished">' . lt('Unveröffentlicht') . '</p>': '<div>') .
//    '<h2>' . check_plain($record["anzeige_name"]) . '</h2>' .
    ($record['bis'] != null ? "<p><strong><em>" . lt('Nicht mehr zutrittsberechtigt ins Parlament. @name war zutrittsberechtigt bis @bis.', array('@name' => $record['name'], '@bis' => $record['bis_formatted'])) . '</em></strong></p>' : '') .
    ($record['erfasst'] == 'Nein' ? "<p><em>" . lt('Nicht erfasst. Beispielsweise weil der einladende Parlamentarier nicht mehr zur Wiederwahl antritt.') . '</em></p>' : '') .
      ($admin ? '<p><span class="admin">' . lt('Lobbyfaktor:') . ' <b>'. $record['lobbyfaktor'] . '</b> (' . round($record['lobbyfaktor'] /$record['lobbyfaktor_max'] * 100, 0) . '% von akt. max ' . $record['lobbyfaktor_max'] . ')' . ' [' . $record['anzahl_mandat_hoch'] . ', ' . $record['anzahl_mandat_mittel'] . ', ' . $record['anzahl_mandat_tief'] . ']</span></p>' : '')
    . '<table border="0">'
    . '<tr><td>' . lt('Eingeladen von:') . '</td><td>' . $record['zutritt_von_parlamentarier'] . '</td></tr>'
    .  '<tr><td>' . lt('Deklarierte Funktion:') . '</td><td>' . check_plain(translate_record_field($record, 'funktion')) . '</td></tr>'
    .  _lobbywatch_get_optional_tr(lt('Beruf:'), translate_record_field($record, 'beruf', false, true), $admin)
    .  _lobbywatch_get_optional_tr(lt('Partei:'), l(translate_record_field($record, 'partei_de'), 'daten/partei/' . $record['partei_id']), $admin, true, $record['partei_id'])
    .  _lobbywatch_get_optional_tr(lt('Homepage:'), _lobbywatch_get_homepage_link($record['homepage']), $admin, true)
    . '</table>'
    . _lobbywatch_get_edit_link($admin, 'person', $id)
    . '<h3>' . lt('Tätigkeiten') . '</h3>'
    . '<ul>'
    .    $record['mandate']
    . '</ul>'
    . _lobbywatch_get_workflow_state_line($record)
      . '</div>'
    ;
  }
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

// function _lobbywatch_get_parteien($check_unpublished = true) {
//   // Cache ref: http://www.lullabot.com/blog/article/beginners-guide-caching-data-drupal-7
//   $cache = &drupal_static(__FUNCTION__);
//   if (!isset($cache)) {
//     db_set_active('lobbywatch');

//     try {
//       $sql = "SELECT partei.* FROM v_partei partei WHERE 1 "
//        . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND partei.freigabe_datum <= NOW() ' : '') . " ;";

//       $result = db_query($sql);

//       while ($record = $result->fetchAssoc()) {
//         $cache[$record['id']] = $record;
//       }
//     } finally {
//       // Go back to the default database,
//       // otherwise Drupal will not be able to access it's own data later on.
//       db_set_active();
//     }
//   }
//   return $cache;
// }

// function _lobbywatch_get_kommissionen($check_unpublished = true) {
//   // Cache ref: http://www.lullabot.com/blog/article/beginners-guide-caching-data-drupal-7
//   $cache = &drupal_static(__FUNCTION__);
//   if (!isset($cache)) {
//     db_set_active('lobbywatch');

//     try {
//       $sql = "SELECT kommission.* FROM v_kommission kommission WHERE 1 "
//        . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND kommission.freigabe_datum <= NOW() ' : '') . " ;";

//       $result = db_query($sql);

//       while ($record = $result->fetchAssoc()) {
//         $cache[$record['id']] = $record;
//       }
//     } finally {
//       // Go back to the default database,
//       // otherwise Drupal will not be able to access it's own data later on.
//       db_set_active();
//     }
//   }
//   return $cache;
// }

/**
 * @see _lobbywatch_get_workflow_state_select_SQL
 */
function _lobbywatch_get_workflow_state_line($record) {
  $lobbywatch_db_read_time = round(timer_read('lobbywatch_db_read') / 1000, 2);
  $page_build_time = _lobbywatch_page_build_secs();
  global $env;
  $admin = user_access('access lobbywatch admin');
//   $unpublished = user_access('access lobbywatch unpublished content');

  $update_date = !empty($record['combined_updated_date_unix']) ? $record['combined_updated_date_unix'] : $record['updated_date_unix'];
//   dpm($record);
  return '<p class="lobbywatch-meta-fields-line"><small>'
    . lt('Letzte Aktualisierung am !date', array ('!date' => '<time datetime="'.getISO8601Date($update_date).'">' . format_date($update_date, 'short_date') . '</time>')) .
    ($admin ?
      '<span class="admin">/' . $record ['updated_visa'] . '</span>' : '') .
      '<br>' . ($record['freigabe_datum'] && $record['freigabe_datum_unix'] < time() ? lt('Veröffentlicht am !date', array ('!date' => '<time datetime="'.getISO8601Date($record['freigabe_datum_unix']).'" pubdate="pubdate">' . format_date($record['freigabe_datum_unix'], 'short_date') . '</time>' )) . ($admin ? '<span class="admin">/' . $record ['freigabe_visa'] . '</span>' : '') : lt('Unveröffentlicht')) .
    ($admin ?  '<span class="admin">
        <br>' . lt('Erstellt am @date', array ('@date' => format_date($record['created_date_unix'], 'short_date'))) . '/' . $record ['created_visa'] .
      ($record ['eingabe_abgeschlossen_datum_unix'] ? '<br>' . lt('Eingabe abgeschlossen am @date', array ('@date' => format_date($record ['eingabe_abgeschlossen_datum_unix'], 'short_date') )) . '/' .  $record ['eingabe_abgeschlossen_visa'] : '')
      . ($record ['kontrolliert_datum_unix'] ? '<br>' . lt('Kontrolliert am @date', array ('@date' => format_date($record ['kontrolliert_datum_unix'], 'short_date') )) . '/' .  $record ['kontrolliert_visa'] : '')
        . '</span>'
    : '') .

  '</small></p>' .
  ($admin ? "<p class=\"lobbywatch-meta-fields-line admin\"><small>" . lt('Verwendete DB: @env; DB read time: @lobbywatch_db_read_time s; Page execution time: @page_build_time s', array ('@env' => $env, '@lobbywatch_db_read_time' => $lobbywatch_db_read_time, '@page_build_time' => $page_build_time, )) . "</small></p>" : "");
}

function getISO8601Date($unix_timestamp) {
  return format_date($unix_timestamp, 'custom', 'Y-m-d\TH:i:sO'); // PHP 'c' format is not proper ISO8601!
}

/**
 * Form builder; Configure site performance settings.
 *
 * @ingroup forms
 * @see system_settings_form()
 */
function lobbywatch_admin_form() {
  //   drupal_add_js(drupal_get_path('module', 'system') . '/system.js');

  $form['fresh_db'] = array(
      '#type' => 'fieldset',
      '#title' => t('Refresh DB'),
  );

  $form['fresh_db']['refresh_mv_hint'] = array(
      '#type' => 'item',
      '#markup' => t('Attention: Refresh MVs may run several minutes.'),
  );

  $form['fresh_db']['refresh_mv'] = array(
      '#type' => 'submit',
      '#value' => t('Refresh MVs'),
      '#submit' => array('lobbywatch_refresh_db_submit'),
  );

//   return system_settings_form($form);
  return $form;
}

/**
 * Submit callback; refresh DB.
 *
 * @ingroup forms
 */
function lobbywatch_refresh_db_submit($form, &$form_state) {
  // Could refreshed as a batch, see https://www.drupal.org/node/180528
  $elapsed = _lobbywatch_refresh_materialized_views(false);
  drupal_set_message(t('MVs refreshed in %ss.', array('%s' => $elapsed)));
}

/**
 * @see _lobbywatch_get_workflow_state_line
 */
function _lobbywatch_get_workflow_state_SELECT_SQL($table, $only_if_admin = true) {
  $admin = user_access('access lobbywatch admin');
  $sql = "$table.updated_date, $table.updated_date_unix, $table.created_date, $table.created_date_unix, $table.freigabe_datum, $table.freigabe_datum_unix, ";
  if (!$only_if_admin || $admin) {
    $sql .= "$table.updated_visa, $table.created_visa, $table.eingabe_abgeschlossen_datum, $table.eingabe_abgeschlossen_datum_unix, $table.eingabe_abgeschlossen_visa, $table.freigabe_visa, $table.kontrolliert_datum, $table.kontrolliert_datum_unix, $table.kontrolliert_visa, ";
  }
  return $sql;
}
