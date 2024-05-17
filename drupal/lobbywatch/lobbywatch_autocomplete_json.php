<?php

/**
 * Fast JSON response for autocomplete AJAX. Avoids Drupal boot overhead.
 * Currently only for published and non historised data.
 *
 * http://lobbywatch.dev/sites/all/modules/lobbywatch/lobbywatch_autocomplete_json.php/[langcode]/[type]/[query]
 * http://lobbywatch.dev/sites/all/modules/lobbywatch/lobbywatch_autocomplete_json.php/[langcode]/[query]
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

global $lobbywatch_is_forms;
$lobbywatch_is_forms = true;

_lobbywatch_search_autocomplete();

// http://stackoverflow.com/questions/6776661/drupal-autocomplete-callback-with-multiple-parameters/#answer-21853762
// http://sachachua.com/blog/2011/08/drupal-overriding-drupal-autocompletion-to-pass-more-parameters/
// http://complexdan.com/passing-custom-arguments-drupal-7-autocomplete/

// Ref https://api.drupal.org/api/examples/ajax_example!ajax_example_autocomplete.inc/7
function _lobbywatch_search_autocomplete($str = '') {

  $type = 'publ';
  if (!$str) {
      $matches = [];
      if (preg_match('%(/(de|fr)/(.+))?/(.*)%', $_SERVER['PATH_INFO'], $matches)) {
        $lang = $matches[2];
        $type = $matches[3];
        $str = $matches[4];
      }
  }

  $lang_suffix = get_lang_suffix($lang);

//     $result = _lobbywatch_search_autocomplete_LIKE_UNION($str);
    $result = _lobbywatch_search_autocomplete_LIKE_search_table($str, $lang, !($type == 'all' || $type == 'unpubl'), !($type == 'all' || $type == 'hist'));
//     $result = _lobbywatch_search_autocomplete_FULLTEXT($str);

    $items = [];
    foreach($result as $record) {
      $key = $record["name$lang_suffix"] . " [" . common_check_plain($record['page']). '=' . common_check_plain($record['id']) . "]";
      $items[$key] = common_check_plain(fast_translation($record['page'], $lang) . ': ' . $record["name$lang_suffix"]);
    }

  output_json($items);
  exit;
}

function output_json($var) {
  header('Content-Type: application/json');
  // Encode <, >, ', &, and " using the json_encode() options parameter.
  echo json_encode($var, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
}

// Duplicated in lobbywatch_data.interface.inc
function _lobbywatch_search_keyword_processing($str) {
  $search_str = preg_replace('!\*+!', '%', $str);
  if (!preg_match('/[%_]/', $search_str)) {
    $search_str = "%$search_str%";
  }
  return $search_str;
}

// Similar code in lobbywatch_data.interface.inc
function _lobbywatch_search_autocomplete_LIKE_search_table($str, $lang, $filter_unpublished = true, $filter_historised = true) {
  $lang_suffix = get_lang_suffix($lang);

  // Show all parlamentarier in search, even if not freigegeben, RKU 22.01.2015
  $sql = "
SELECT id, page, name$lang_suffix
-- , freigabe_datum, bis
FROM mv_search_table /*v_search_table does not work with MySQL 5.6, it leads to 'SQLSTATE[HY000]: General error: 1615 Prepared statement needs to be re-prepared' */
WHERE
search_keywords$lang_suffix LIKE :str ".
// ($filter_historised ? ' AND (bis IS NULL OR bis > NOW())' : '') .
($filter_unpublished ? ' AND (table_name=\'parlamentarier\' OR table_name=\'zutrittsberechtigung\' OR freigabe_datum <= NOW())' : '') . "
ORDER BY table_weight, weight
LIMIT 20;";

  $q = get_PDO_lobbywatch_DB_connection()->prepare($sql);
  $q->execute(array(':str' => _lobbywatch_search_keyword_processing($str)));
  return $q->fetchAll(PDO::FETCH_ASSOC);
}

function get_translation_table() {
  static $translation_table;
  if (!$translation_table) {
    // i18n add translations
    $translation_table = array('de' => [], 'fr' => []);
    $translation_table['de']['organisation'] = 'Organisation';
    $translation_table['fr']['organisation'] = 'Organisation';
    $translation_table['de']['branche'] = 'Branche';
    $translation_table['fr']['branche'] = 'Branche';
    $translation_table['de']['parlamentarier'] = 'Parlamentarier';
    $translation_table['fr']['parlamentarier'] = 'Parlementaire';
    $translation_table['de']['zutrittsberechtigter'] = 'Zutrittsberechtigter';
    $translation_table['fr']['zutrittsberechtigter'] = 'Personne avec droit d\'accès';
    $translation_table['de']['lobbygruppe'] = 'Lobbygruppe';
    $translation_table['fr']['lobbygruppe'] = 'Groupe d\'intérêt';
    $translation_table['de']['kommission'] = 'Kommission';
    $translation_table['fr']['kommission'] = 'Commission';
    $translation_table['de']['partei'] = 'Partei';
    $translation_table['fr']['partei'] = 'Parti';
  }
  return $translation_table;
}

function fast_translation($source, $lang) {
  return get_translation_table()[$lang][$source];
}
