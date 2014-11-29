<?php

/**
 * Fast JSON response for autocomplete AJAX. Avoids Drupal boot overhead.
 * Currently only for published and non historised data.
 *
 * http://lobbywatch.dev/sites/all/modules/lobbywatch/lobbywatch_autocomplete_json.php/[type]/[query]
 * http://lobbywatch.dev/sites/all/modules/lobbywatch/lobbywatch_autocomplete_json.php/[query]
 *
 * type:
 * - publ: default, only published and active values
 * - unpubl: show unpublished data
 * - hist: show historised data
 * - all: not filtered, all data
 *
 * http://lobbywatch.ch/sites/all/modules/lobbywatch/lobbywatch_autocomplete_json.php/all/gr
 * 160ms
 *
 * http://lobbywatch.ch/de/autocomplete/lobbywatch/search/gr
 * 450ms
 *
 * Partial bootstrap of Drupal could also be a solution:
 * https://api.drupal.org/api/drupal/includes!bootstrap.inc/function/drupal_bootstrap/7
 *
 * Module js could also be an alternative:
 * https://www.drupal.org/project/js
 */

require_once dirname(__FILE__) . '/../../../lobbywatch.ch/app/settings/settings.php';
require_once dirname(__FILE__) . '/../../../lobbywatch.ch/app/common/utils.php';
// require_once '/home/rkurmann/dev/web/lobbywatch/lobbydev/public_html/settings/settings.php';
// require_once '/home/rkurmann/dev/web/lobbywatch/lobbydev/public_html/common/utils.php';
// df(dirname(__FILE__), 'dirname(__FILE__)');

_lobbywatch_search_autocomplete();

// http://stackoverflow.com/questions/6776661/drupal-autocomplete-callback-with-multiple-parameters/#answer-21853762
// http://sachachua.com/blog/2011/08/drupal-overriding-drupal-autocompletion-to-pass-more-parameters/
// http://complexdan.com/passing-custom-arguments-drupal-7-autocomplete/

// Ref https://api.drupal.org/api/examples/ajax_example!ajax_example_autocomplete.inc/7
function _lobbywatch_search_autocomplete($str = '') {
//   df($_SERVER, '$$$$_SERVER');

  $type = 'publ';
  if (!$str) {
//     $str = preg_replace('|^/|', '', $_SERVER['PATH_INFO']);
      $matches = array();
      if (preg_match('%(/(.+))?/(.*)%', $_SERVER['PATH_INFO'], $matches)) {
        $type = $matches[2];
        $str = $matches[3];
//         df($matches, '$matches');
      }
  }

//     $result = _lobbywatch_search_autocomplete_LIKE_UNION($str);
    $result = _lobbywatch_search_autocomplete_LIKE_search_table($str, !($type == 'all' || $type == 'unpubl'), !($type == 'all' || $type == 'hist'));
//     $result = _lobbywatch_search_autocomplete_FULLTEXT($str);

  //   dpm($result, 'result');

  $lang_suffix = get_lang_suffix();
    $items = array();
//     while($record = $result->fetchAssoc()) {
    foreach($result as $record) {
      $key = $record["name$lang_suffix"] . " [" . common_check_plain($record['page']). '=' . common_check_plain($record['id']) . "]";
      $items[$key] = common_check_plain(ucfirst($record['page']) . ': ' . $record["name$lang_suffix"]);
    }

  output_json($items);
  exit;
}

function output_json($var) {
  header('Content-Type: application/json');
  // Encode <, >, ', &, and " using the json_encode() options parameter.
  echo json_encode($var, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
}

function _lobbywatch_search_keyword_processing($str) {
  $search_str = preg_replace('!\*+!', '%', $str);
  //     $search_str = '%' . db_like($keys) . '%'
  if (!preg_match('/[%_]/', $search_str)) {
    $search_str = "%$search_str%";
  }
  return $search_str;
}

function _lobbywatch_search_autocomplete_LIKE_search_table($str, $filter_unpublished = true, $filter_historised = true) {
  $lang_suffix = get_lang_suffix();

  $sql = "
SELECT id, page, name$lang_suffix
-- , freigabe_datum, bis
FROM v_search_table
WHERE
search_keywords$lang_suffix LIKE :str ". ($filter_historised ? ' AND (bis IS NULL OR bis > NOW())' : '') . ($filter_unpublished ? ' AND freigabe_datum <= NOW()' : '') . "
ORDER BY table_weight, weight
LIMIT 20;";
  //dpm($sql, 'suche');
//   $result = db_query($sql, array(':str' => _lobbywatch_search_keyword_processing($str)));

  $q = get_PDO_lobbywatch_DB_connection()->prepare($sql);
  $q->execute(array(':str' => _lobbywatch_search_keyword_processing($str)));
  return $q->fetchAll(PDO::FETCH_ASSOC);
}

