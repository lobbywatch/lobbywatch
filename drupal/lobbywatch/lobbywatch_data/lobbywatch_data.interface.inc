<?php

global $show_sql, $show_stacktrace;
$show_sql = true;
$show_stacktrace = true;

const SUPPORTED_DB_CHARS = '-a-zA-Z0-9_.äöüÄÖÜéèàÉÈâ%';

function _lobbywatch_data_table_flat_id($table, $id, $json = true) {
  global $show_sql;
  $success = true;
  $count = 0;
  $items = null;
  $message = '';

  //df($table, '_lobbywatch_data_table_flat_id');
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    $sql = "
      SELECT " . _lobbywatch_data_select_fields_SQL($table) . "
      FROM v_$table $table
      WHERE $table.id=:id" .
        (in_array($table, array('parlamentarier', 'zutrittsberechtigung')) ? '' : _lobbywatch_data_filter_unpublished_SQL($table)) .
        _lobbywatch_data_filter_fields_SQL($table);

    $result = db_query($sql, array(':id' => $id));

    $items = _lobbywatch_data_clean_records($result);

    _lobbywatch_data_transformation($table, $items);

    $count = count($items);
    $success = $count == 1;
    $message .= count($items) . " record(s) found";
  } catch(Exception $e) {
    $message .= _lobbywatch_data_add_exeption($e);
    $success = false;
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();

    $response = array(
        'success' => $success,
        'count' => $count,
        'message' => $message,
        'sql' => $show_sql? $sql : '',
        'source' => $table,
        'build secs' => '' . _lobbywatch_page_build_secs(),
        'data' => $success ? $items[0] : null,
    );

    if ($json) {
      lobbywatch_json_output($response);
      drupal_exit();
    } else {
      return $response;
    }
  }
}

function _lobbywatch_data_not_found($type, $table, $json = true) {
  $message = '';
  try {
    $message .= "Data interface call to $type/$table does not exist";
  } catch(Exception $e) {
    $message .= _lobbywatch_data_add_exeption($e);
  } finally {
    $response = array(
        'success' => false,
        'count' => 0,
        'message' => $message,
        'sql' => '',
        'source' => $table,
        'build secs' => '' . _lobbywatch_page_build_secs(),
        'data' => null,
    );

    if ($json) {
      drupal_add_http_header('Status', '404 Not Found');
      lobbywatch_json_output($response);
      exit(2);
    } else {
      return $response;
    }
  }
}

function _lobbywatch_data_not_allowd($type, $table, $json = true) {
  $message = '';

  try {
    $message .= "Data interface call to $type/$table not allowed";
  } catch(Exception $e) {
    $message .= _lobbywatch_data_add_exeption($e);
  } finally {
    $response = array(
        'success' => false,
        'count' => 0,
        'message' => $message,
        'sql' => '',
        'source' => $table,
        'build secs' => '' . _lobbywatch_page_build_secs(),
        'data' => null,
    );

    if ($json) {
      drupal_add_http_header('Status', '403 Forbidden');
      lobbywatch_json_output($response);
      exit(2);
    } else {
      return $response;
    }
  }
}

function lobbywatch_json_output($response = null) {
  drupal_json_output($response);
}

function _lobbywatch_data_check_allowd_table($table) {
  if (!array_key_exists($table, Constants::$workflow_tables)) {
    throw new Exception("Table not allowed: $table");
  }
}

function _lobbywatch_data_add_exeption($e) {
  global $show_stacktrace;
  return $show_stacktrace ? $e->getMessage() . "\n------\n" . $e->getTraceAsString() : $e->getMessage();
}

/** Filters out unpublished data if not privileged or includeUnpublished == 0 */
function _lobbywatch_data_filter_unpublished_SQL($table) {
  return ((isset($_GET['includeUnpublished']) && $_GET['includeUnpublished'] != 1) || !user_access('access lobbywatch data unpublished content') ? " AND $table.freigabe_datum < NOW()" : '');
}

function _lobbywatch_data_clean_DB_value($value) {
  // TODO improve sanitation
//   $con = Database::getConnection('lobbywatch');
//   return mysql_escape_string($value);
  return preg_replace('/[^' . SUPPORTED_DB_CHARS . ']/', '', $value);
}

/** TODO Docu */
function _lobbywatch_data_filter_field_SQL($table, $field) {
  $paramSingle = "filter_${field}";
  $paramList = "filter_${field}_list";
  $paramLike = "filter_${field}_like";
  $matches = [];
  if (isset($_GET[$paramSingle]) && is_numeric($_GET[$paramSingle])) {
    return " AND $table.${field} = " . intval($_GET[$paramSingle]);
  } else if (isset($_GET[$paramSingle])) {
    return " AND $table.${field} = '" . _lobbywatch_data_clean_DB_value($_GET[$paramSingle]) . "'";
  } else if (isset($_GET[$paramList]) && preg_match_all('/([' . SUPPORTED_DB_CHARS . ']+)/', $_GET[$paramList], $matches)) {
    return " AND $table.${field} IN ( " . implode(',', $matches[1]) . ')';
  } else if (isset($_GET[$paramLike])) {
    return " AND $table.${field} LIKE '" . _lobbywatch_data_clean_DB_value($_GET[$paramLike]) . "'";
  } else {
    return '';
  }
}

function _lobbywatch_data_filter_fields_SQL($table) {
  $sql = '';
  $prefix = "filter_";
  // TODO filter fields not allowed
  foreach($_GET as $key => $value) {
    $matches = [];
    if (preg_match('/^filter_([a-z0-9_]+?)(_list|_like)?$/', $key, $matches) && !_lobbywatch_data_check_intern_field($matches[1])) {
      $sql .= _lobbywatch_data_filter_field_SQL($table, $matches[1]);
    }
  }
  return $sql;
}

function _lobbywatch_data_filter_limit_SQL() {
//   df($_GET['limit']);
//   df(is_int($_GET['limit'] + 0), 'int?');
  if (isset($_GET['limit']) && $_GET['limit'] == 'none') {
    return '';
  } else {
    return  " LIMIT " . (isset($_GET['limit']) && is_int($limit = $_GET['limit'] + 0) && $limit > 0 ? $limit : 10) . " ";
  }
}

function _lobbywatch_data_select_fields_SQL($table) {
//   df($_GET['select_fields']);
//   df(is_int($_GET['limit'] + 0), 'int?');
  $matches = [];
  if (isset($_GET['select_fields']) && $_GET['select_fields'] != '*' && preg_match_all('/([A-Za-z0-9_.,*\-]+)/', $_GET['select_fields'], $matches)) {
//     df($matches, 'select');
//     df(implode(', ', explode(', ', $matches[1][0])), 'fields');
    return  " $table.id, " . implode(', ', explode(', ', $matches[1][0])) . " ";
  } else {
//     df($matches, '*');
    return "$table.*";
  }
}

function _lobbywatch_data_get_lang() {
  if (isset($_GET['lang']) && $_GET['lang'] == 'fr') {
    return 'fr';
  } else {
    return 'de';
  }
}

/**
 * Filter fields and keep only one language and set it in the base field name.
 *
 * Looks for "_fr" since this is consistently used language fields. If found,
 * delete "_fr" and "_de" fields and set value in base field name.
 *
 * E.g
 * If anzeige_name_fr is found, anzeige_name_fr and anzeige_name_de are deleted and the
 * for lang=fr anzeige_name set with the value of anzeige_name_fr.
 *
 * @param unknown $items
 * @return either
 */
function _lobbywatch_data_handle_lang_fields(&$items) {
  $lang = get_lang();
  $fr_suffix = '_fr';
  $de_suffix = '_de';

  $fields = [];

//    df($items, 'fields before lang');
  foreach($items as &$fields) {
    foreach($fields as $key => $value) {
//       df($key, '$key');
//       df($value, '$value');
      $matches = [];
      if (preg_match('/^(.+)_fr$/i', $key, $matches)) {
        $base_field_name = $matches[1];
//         df($base_field_name, '$base_field_name');
        if (
          isset($fields["${base_field_name}_de"]) ||
          (array_key_exists("${base_field_name}_de", $fields) && !array_key_exists($base_field_name, $fields))
        ) {
          $de_field_name = "${base_field_name}_de";
        } else {
          $de_field_name = $base_field_name;
        }
        $lang_value = translate_record_field($fields, $de_field_name);
//          df($lang_value, '$lang_value');
        unset($fields["${base_field_name}_fr"]);
        unset($fields["${base_field_name}_de"]);
        unset($fields["${base_field_name}_it"]);
        $fields[$base_field_name] = $lang_value;
      }
    }
  }
//    df($items, 'fields after lang');
  return $fields;
}

function _lobbywatch_data_clean_records($result) {
  $items = [];
  $includeHistorised = isset($_GET['includeInactive']) && $_GET['includeInactive'] == 1 && user_access('access lobbywatch unpublished content');

  // TODO test exclusion of historised records
  while($record = $result->fetchAssoc()) {
    if (
      (!array_key_exists('bis_unix', $record) || (is_null($record['bis_unix']) || $record['bis_unix'] > time()))
      && (!array_key_exists('im_rat_bis_unix', $record) || (is_null($record['im_rat_bis_unix']) || $record['im_rat_bis_unix'] > time()))
      && (!array_key_exists('zutrittsberechtigung_bis_unix', $record) || (is_null($record['zutrittsberechtigung_bis_unix']) || $record['zutrittsberechtigung_bis_unix'] > time()))
      || $includeHistorised
    ) {
      $items[] = _lobbywatch_data_clean_fields($record);
    }
  }

  _lobbywatch_data_handle_lang_fields($items);

  return $items;
}

function _lobbywatch_data_clean_fields($input_record) {

  $record = $input_record;

  $updated_fields = array('updated_date', 'updated_date_unix', 'refreshed_date');
  if (!isset($_GET['includeConfidentialData']) || $_GET['includeConfidentialData'] != 1 || !user_access('access lobbywatch data confidential content')) {
    foreach ($record as $key => $value) {
      // Clean intern fields
      if (in_array($key, Constants::$intern_fields)) {
        unset($record[$key]);
      }
      if (in_array($key, Constants::$meta_fields) && !in_array($key, $updated_fields)) {
        unset($record[$key]);
      }
    }
  }

  if (!isset($_GET['includeMetaData']) || $_GET['includeMetaData'] != 1) {
    foreach ($record as $key => $value) {
      // Clean intern fields
      if (in_array($key, $updated_fields)) {
        unset($record[$key]);
      }
    }
  }

  foreach ($record as $key => $value) {
    // Clean intern fields
    if (preg_match('/_(BAD|OLD|ALT)$/i', $key)) {
      unset($record[$key]);
    } else if (preg_match('/_json$/i', $key)) {
      $record[$key] = decodeJson($value, true, 512, JSON_THROW_ON_ERROR);
    } else if (is_string($value)) {
      $clean = clean_str($value);
      // TODO move list of strip_tags exception to general location
      if (!in_array($key, ['parlament_interessenbindungen'], true)) {
        $clean = strip_tags($clean);
      }
      $record[$key] = $clean;
    }

  }


  global $public_files_dir_abs;
  global $rel_files_url;
  global $base_root;
  if (isset($record['symbol_klein_rel'])) {
    $record['symbol_path'] = $rel_files_url . '/' . $record['symbol_klein_rel'];
    $record['symbol_url'] = $base_root . $record['symbol_path'];
  }

  if (isset($record['kleinbild'])) {
    $record['kleinbild_path'] = $rel_files_url . '/../files/parlamentarier_photos/klein/' . $record['kleinbild'];
    $record['kleinbild_url'] = $base_root . $record['kleinbild_path'];
  }

  return $record;
}

function _lobbywatch_data_check_intern_field($field) {
  return !user_access('access lobbywatch data confidential content') && in_array($field, Constants::$intern_fields);
}

function _lobbywatch_data_transformation($table, &$items) {
  if (in_array($table, ['organisation', 'interessenbindung_liste'], true)) {
    $lang = get_lang();

    $pg_prefix = [
      'de' => 'Parlamentarische Gruppe ',
      'fr' => 'Intergroupe parlementaire ',
      'it' => 'Intergruppo parlamentare ',
    ];

    // df($lang, '$lang');
    // df($pg_prefix[$lang], 'prefix');
    // df($items);

    foreach($items as $item_key => $fields) {
      foreach($fields as $key => $value) {
        if ($fields['rechtsform'] === 'Parlamentarische Gruppe' && in_array($key, ['name', 'organisation_name'], true)) {
          df('should replace');
          $items[$item_key][$key] = $pg_prefix[$lang] . ($lang === 'de' ? $items[$item_key][$key] : lcfirst($items[$item_key][$key]));
        }
      }
    }
  }
}

function _lobbywatch_data_table_flat_list($table, $condition = '1', $json = true, $order_by = '', $join = '', $join_select = '') {
  global $show_sql;
  $success = true;
  $message = '';
  $count = 0;
  $items = null;
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    // TXTTODO _lobbywatch_data_table_flat_list nothing todo
    $sql = "
    SELECT " . _lobbywatch_data_select_fields_SQL($table) . "
    $join_select
    FROM v_$table $table
    $join
    WHERE $condition " .
        _lobbywatch_data_filter_unpublished_SQL($table) .
        _lobbywatch_data_filter_fields_SQL($table) .
        " $order_by" .
        _lobbywatch_data_filter_limit_SQL() .
        ';';

    $result = db_query($sql, []);

    $items = _lobbywatch_data_clean_records($result);

    _lobbywatch_data_transformation($table, $items);

    $count = count($items);
    $success = $count > 0;
    $message = $count . " record(s) found";
  } catch(Exception $e) {
    $message .= _lobbywatch_data_add_exeption($e);
    $success = false;
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
    $response = array(
        'success' => $success,
        'count' => $count,
        'message' => $message,
        'sql' => $show_sql ? $sql : '',
        'source' => $table,
        'build secs' => '' . _lobbywatch_page_build_secs(),
        'data' => $items
    );

    if ($json) {
      lobbywatch_json_output($response);
      drupal_exit();
    } else {
      return $response;
    }
  }
}

function _lobbywatch_data_relation_flat_list($table, $condition = '1', $json = true) {
  global $show_sql;
  $success = true;
  $message = '';
  $count = 0;
  $items = null;
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    $sql = "
    SELECT " . _lobbywatch_data_select_fields_SQL($table) . "
    FROM v_$table $table
    WHERE $condition " .
        _lobbywatch_data_filter_unpublished_SQL($table) .
        _lobbywatch_data_filter_fields_SQL($table) .
        _lobbywatch_data_filter_limit_SQL() .
        ';';

    $result = db_query($sql, []);

    $items = _lobbywatch_data_clean_records($result);

    $count = count($items);
    $success = $count > 0;
    $message = $count . " record(s) found";
  } catch(Exception $e) {
    $message .= _lobbywatch_data_add_exeption($e);
    $success = false;
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
    $response = array(
        'success' => $success,
        'count' => $count,
        'message' => $message,
        'sql' => $show_sql ? $sql : '',
        'source' => $table,
        'build secs' => '' . _lobbywatch_page_build_secs(),
        'data' => $items
    );

    if ($json) {
      lobbywatch_json_output($response);
      drupal_exit();
    } else {
      return $response;
    }
  }
}

// Duplicated from lobbywatch_autocomplete_json.php
function _lobbywatch_search_keyword_processing($str) {
  $search_str = preg_replace('!\*+!', '%', $str);
  //     $search_str = '%' . db_like($keys) . '%'
  if (!preg_match('/[%_]/', $search_str)) {
    $search_str = "%$search_str%";
  }
  return $search_str;
}

// Adapted from lobbywatch_autocomplete_json.php
function _lobbywatch_data_search($search_str, $json = true, $filter_unpublished = true) {
  global $show_sql;
  $success = true;
  $count = 0;
  $items = null;
  $message = '';
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');
  $table = 'search_table';

  try {
    $lang_suffix = get_lang_suffix();

    $has_query = mb_strlen($search_str) > 0;

    $conditions = array_filter([
      isset($_GET['tables'])
        ? 'table_name IN (' . implode(',', array_map(
          function ($table) { return "'" . db_escape_table($table) . "'"; },
          explode(',', $_GET['tables'])
        )) . ')'
        : '',
      $has_query ? "search_keywords$lang_suffix LIKE :str": '',
      $filter_unpublished ? '(table_name=\'parlamentarier\' OR table_name=\'zutrittsberechtigung\' OR freigabe_datum <= NOW())' : '',
    ]);

    // Show all parlamentarier in search, even if not freigegeben, RKU 22.01.2015
    $sql = "
    SELECT id, page, table_name, name_de, name_fr, table_weight, weight
    -- , freigabe_datum, bis
    FROM v_search_table
    WHERE " .
      implode($conditions, ' AND ') .
    "ORDER BY table_weight, weight";

    $sql .=  _lobbywatch_data_filter_limit_SQL() .
        ';';

//       df($search_str, '$search_str');
//       df(_lobbywatch_search_keyword_processing($search_str), 'str');
    $result = db_query($sql, array(':str' => _lobbywatch_search_keyword_processing($search_str)));

    $items = _lobbywatch_data_clean_records($result);
//       $items = $result;
//       $items = _lobbywatch_data_handle_lang_fields($items);

    $count = count($items);
    $success = $count > 0;
    $message .= count($items) . " record(s) found ";
  } catch(Exception $e) {
    $message .= _lobbywatch_data_add_exeption($e);
    $success = false;
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
    $response = array(
        'success' => $success,
        'count' => $count,
        'message' => $message,
        'sql' => $show_sql ? $sql : '',
        'source' => $table,
        'build secs' => '' . _lobbywatch_page_build_secs(),
        'data' => $items
    );
    if ($json) {
      lobbywatch_json_output($response);
      drupal_exit();
    } else {
      return $response;
    }
  }
}

function _lobbywatch_data_table_flat_list_search($table, $search_str, $json = true) {
  global $show_sql;
  $success = true;
  $count = 0;
  $items = null;
  $message = '';
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    if ($table === 'parlamentarier') {
      //TODO test check permissions
      $sql = "SELECT " . _lobbywatch_data_select_fields_SQL($table) . " FROM v_$table WHERE anzeige_name LIKE :str" . (!(isset($_GET['includeInactive']) && $_GET['includeInactive'] != 0 && user_access('access lobbywatch unpublished content')) ? ' AND (im_rat_bis IS NULL OR im_rat_bis > NOW())' : '')
       . _lobbywatch_data_filter_unpublished_SQL($table) .
        _lobbywatch_data_filter_fields_SQL($table);
    } else if ($table === 'zutrittsberechtigung') {
      $sql = "SELECT " . _lobbywatch_data_select_fields_SQL($table) . " FROM v_$table WHERE anzeige_name LIKE :str" . (!(isset($_GET['includeInactive']) && $_GET['includeInactive'] != 0 && !user_access('access lobbywatch unpublished content')) ? ' AND (bis IS NULL OR bis > NOW())' : '')
       . _lobbywatch_data_filter_unpublished_SQL($table) .
        _lobbywatch_data_filter_fields_SQL($table);
    } else if (in_array($table, Constants::$entities_web)) {
      $sql = "SELECT " . _lobbywatch_data_select_fields_SQL($table) . " FROM v_$table WHERE anzeige_name LIKE :str"
           . _lobbywatch_data_filter_unpublished_SQL($table) .
        _lobbywatch_data_filter_fields_SQL($table);
    } else {
      throw new Exception("Table $table does not exist");
    }
    $sql .=  _lobbywatch_data_filter_limit_SQL() .
        ';';
    $result = db_query($sql, array(':str' => "%$search_str%"));

    $items = _lobbywatch_data_clean_records($result);
    $count = count($items);
    $success = $count > 0;
    $message .= count($items) . " record(s) found";
  } catch(Exception $e) {
    $message .= _lobbywatch_data_add_exeption($e);
    $success = false;
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
    $response = array(
        'success' => $success,
        'count' => $count,
        'message' => $message,
        'sql' => $show_sql ? $sql : '',
        'source' => $table,
        'build secs' => '' . _lobbywatch_page_build_secs(),
        'data' => $items
    );
    if ($json) {
      lobbywatch_json_output($response);
      drupal_exit();
    } else {
      return $response;
    }
  }
}

function _lobbywatch_data_table_zutrittsberechtigte_aggregated_id($id, $json = true) {
  global $show_sql;
  $success = true;
  $count = 0;
  $message = '';
  $sql = '';
  $table = 'zutrittsberechtigung';
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');
  try {
    $zutrittsberechtigung = _lobbywatch_data_table_flat_id('zutrittsberechtigung', $id, false);
    $aggregated = $zutrittsberechtigung['data'];
    $message .= ' | ' . $zutrittsberechtigung['message'];
    $sql .= ' | ' . $zutrittsberechtigung['sql'];
    $success = $success && $zutrittsberechtigung['success'];

    if ($success) {
      $mandate = _lobbywatch_data_table_flat_list('person_mandate', "person_mandate.person_id = $id", false);
      $aggregated['mandate'] = $mandate['data'];
      $message .= ' | ' . $mandate['message'] . '';
      $sql .= ' | ' . $mandate['sql'] . '';
      // $success = $success && $mandate['success'];

      foreach ($aggregated['mandate'] as $key => $value) {
        $verguetungen = _lobbywatch_data_table_flat_list('mandat_jahr', "mandat_jahr.mandat_id = ${value['id']}", false, 'ORDER BY jahr DESC');
        $message .= ' | ' . $verguetungen['message'];
        $sql .= ' | ' . $verguetungen['sql'];
        _add_verguetungen($aggregated['mandate'][$key], $verguetungen, $value['von']);
      }

  //     dpm($zutrittsberechtigung, '$zutrittsberechtigung');
      // TODO handle duplicate zutrittsberechtigung due to historization
      $parlamentarier_id = $zutrittsberechtigung['data']['parlamentarier_id'];
      $parlamentarier = _lobbywatch_data_table_flat_list('parlamentarier', "parlamentarier.id = $parlamentarier_id", false);
  //     dpm($parlamentarier, '$parlamentarier');
      $aggregated['parlamentarier'] = $parlamentarier['data'][0];
      $message .= ' | ' . $parlamentarier['message'];
      $sql .= ' | ' . $parlamentarier['sql'];
      $success = $success && $parlamentarier['success'];

      _lobbywatch_add_wissensartikel($table, $id, $aggregated, $message, $sql);
    }

    $count = $zutrittsberechtigung['count'];
//     dpm($aggregated, '$aggregated');
    //$sql = $in_kommission['sql'];
  } catch(Exception $e) {
    $message .= _lobbywatch_data_add_exeption($e);
    $success = false;
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();

    $response = array(
        'success' => $success,
        'count' => $count,
        'message' => $message,
        'sql' => $show_sql ? $sql : '',
        'source' => $table,
        'build secs' => '' . _lobbywatch_page_build_secs(),
        'data' => $aggregated
    );
    if ($json) {
      lobbywatch_json_output($response);
      drupal_exit();
    } else {
      return $response;
    }
  }

}

function _add_verguetungen(&$arr, $verguetungen, $ib_von, $im_rat_seit = null) {
  $arr['verguetungen_einzeln'] = $verguetungen['data'];
  $verguetungen_jahr = [];
  foreach ($verguetungen['data'] as $jkey => $jval) {
    $verguetungen_jahr[$jval['jahr']] = $jval;
  }

  $this_year = date("Y");
  $start_years = [$this_year - 5];
  if ($ib_von) {
    $start_years[] = date_parse($ib_von)['year'];
  }
  if ($im_rat_seit) {
    $start_years[] = date_parse($im_rat_seit)['year'];
  }
  $start_year = max($start_years);
  for ($year = $start_year; $year <= $this_year; $year++) {
    $message .= " +$year+";
    $arr['verguetungen_jahr'][$year] = $verguetungen_jahr[$year] ?? null;
    $arr['verguetungen_pro_jahr'][] = $verguetungen_jahr[$year] ?? ['jahr' => "$year"];
  }
}

function _lobbywatch_data_table_parlamentarier_aggregated_id($id, $json = true) {
  global $show_sql;
  global $env;
  $success = true;
  $count = 0;
  $message = '';
  $sql = '';
  $table = 'parlamentarier';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');
  try {
    $message .= "$env";
    $parlamentarier = _lobbywatch_data_table_flat_id('parlamentarier', $id, false);
    $aggregated = $parlamentarier['data'];
    $message .= ' | ' . $parlamentarier['message'];
    $sql .= ' | ' . $parlamentarier['sql'];
    $success = $success && $parlamentarier['success'];

//     df($parlamentarier);

    // load aggregated data only if main object is there
    if ($success) {
      $in_kommissionen =  _lobbywatch_data_table_flat_list('in_kommission_liste', "in_kommission_liste.parlamentarier_id = $id", false);
      $aggregated['in_kommission'] = $in_kommissionen['data'];
      $message .= ' | ' . $in_kommissionen['message'];
      $sql .= ' | ' . $in_kommissionen['sql'];
      // $success = $success && $in_kommissionen['success'];

      $interessenbindungen = _lobbywatch_data_table_flat_list('interessenbindung_liste', "interessenbindung_liste.parlamentarier_id = $id", false);
      $aggregated['interessenbindungen'] = $interessenbindungen['data'];
      $message .= ' | ' . $interessenbindungen['message'];
      $sql .= ' | ' . $interessenbindungen['sql'];
      // $success = $success && $interessenbindungen['success'];

      foreach ($aggregated['interessenbindungen'] as $key => $value) {
        $verguetungen = _lobbywatch_data_table_flat_list('interessenbindung_jahr', "interessenbindung_jahr.interessenbindung_id = ${value['id']}", false, 'ORDER BY jahr DESC');
        $message .= ' | ' . $verguetungen['message'];
        $sql .= ' | ' . $verguetungen['sql'];
        _add_verguetungen($aggregated['interessenbindungen'][$key], $verguetungen, $value['von'], $parlamentarier['data']['im_rat_seit']);
      }

      $verguetungs_tranparenz = _lobbywatch_data_table_flat_list('parlamentarier_transparenz', "parlamentarier_transparenz.parlamentarier_id = $id", false);
      $aggregated['verguetungs_tranparenz'] = $verguetungs_tranparenz['data'];
      $message .= ' | ' . $verguetungs_tranparenz['message'];
      $sql .= ' | ' . $verguetungs_tranparenz['sql'];
      // $success = $success && $interessenbindungen['success'];

      $zutrittsberechtigungen = _lobbywatch_data_table_flat_list('zutrittsberechtigung', "zutrittsberechtigung.parlamentarier_id = $id", false);
      $aggregated['zutrittsberechtigungen'] = $zutrittsberechtigungen['data'];
      $message .= ' | ' . $zutrittsberechtigungen['message'];
      $sql .= ' | ' . $zutrittsberechtigungen['sql'];
      // $success = $success && $zutrittsberechtigungen['success'];

      foreach ($aggregated['zutrittsberechtigungen'] as $key => $value) {
        $mandate = _lobbywatch_data_table_zutrittsberechtigte_aggregated_id($value['id'], false);
        $aggregated['zutrittsberechtigungen'][$key]['mandate'] = $mandate['data']['mandate'];
        $message .= ' | ' . $mandate['message'];
        $sql .= ' | ' . $mandate['sql'];
        // $success = $success && $mandate['success'];
      }

      _lobbywatch_add_wissensartikel($table, $id, $aggregated, $message, $sql);
    }

//     $count = count($aggregated);
    $count = $parlamentarier['count'];
//     dpm($aggregated, '$aggregated');
    //$sql = $in_kommission['sql'];
  } catch(Exception $e) {
    $message .= _lobbywatch_data_add_exeption($e);
    $success = false;
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();

    $response = array(
        'success' => $success,
        'count' => $count,
        'message' => $message,
        'sql' => $show_sql ? $sql : '',
        'source' => $table,
        'build secs' => '' . _lobbywatch_page_build_secs(),
        'data' => $aggregated
    );
    if ($json) {
      lobbywatch_json_output($response);
      drupal_exit();
    } else {
      return $response;
    }
  }
}

function _lobbywatch_data_table_organisation_aggregated_id($id, $json = true) {
  global $show_sql;
  $success = true;
  $count = 0;
  $message = '';
  $sql = '';
  $table = 'organisation';
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');
  try {
    $organsation = _lobbywatch_data_table_flat_id('organisation', $id, false);
    $aggregated = $organsation['data'];
    $message .= ' | ' . $organsation['message'];
    $sql .= ' | ' . $organsation['sql'];
    $success = $success && $organsation['success'];

    // load aggregated data only if main object is there
    if ($success) {
      $beziehungen = _lobbywatch_data_table_flat_list(
        'organisation_beziehung',
        "organisation_beziehung.organisation_id = $id OR organisation_beziehung.ziel_organisation_id = $id",
        false
      );

      $aggregated['beziehungen'] = $beziehungen['data'];
      $message .= ' | ' . $beziehungen['message'];
      $sql .= ' | ' . $beziehungen['sql'];
      // $success = $success && $beziehungen['success'];

      $reverse_art = array(
        'arbeitet fuer' => 'auftraggeber fuer',
        'mitglied von' => 'mitglieder',
        'tochtergesellschaft von' => 'tochtergesellschaften',
        'partner von' => 'partner von', // not directional
        'beteiligt an' => 'beteiligungen von',
      );
      foreach ($aggregated['beziehungen'] as $key => $value) {
        if ($value['ziel_organisation_id'] === $aggregated['id']) {
          $aggregated['beziehungen'][$key]['art'] = $reverse_art[$value['art']];
          $aggregated['beziehungen'][$key]['ziel_organisation_id'] = $value['organisation_id'];
          $aggregated['beziehungen'][$key]['ziel_organisation_name'] = $value['organisation_name'];
          $aggregated['beziehungen'][$key]['organisation_name'] = $aggregated['name'];
          $aggregated['beziehungen'][$key]['organisation_id'] = $aggregated['id'];
        }
      }

      $parlamentarier =  _lobbywatch_data_table_flat_list('organisation_parlamentarier', "organisation_parlamentarier.organisation_id = $id", false);
      $aggregated['parlamentarier'] = $parlamentarier['data'];
      $message .= ' | ' . $parlamentarier['message'];
      $sql .= ' | ' . $parlamentarier['sql'];
      // $success = $success && $parlamentarier['success'];

      foreach ($aggregated['parlamentarier'] as $key => $value) {
        $verguetungen = _lobbywatch_data_table_flat_list('interessenbindung_jahr', "interessenbindung_jahr.interessenbindung_id = ${value['id']}", false, 'ORDER BY jahr DESC');
        $message .= ' | ' . $verguetungen['message'];
        $sql .= ' | ' . $verguetungen['sql'];
        _add_verguetungen($aggregated['parlamentarier'][$key], $verguetungen, $value['von'], $parlamentarier['data']['im_rat_seit']);
      }

      $zutrittsberechtigung =  _lobbywatch_data_table_flat_list('organisation_zutrittsberechtigung', "organisation_zutrittsberechtigung.organisation_id = $id", false);
      $aggregated['zutrittsberechtigte'] = $zutrittsberechtigung['data'];
      $message .= ' | ' . $zutrittsberechtigung['message'];
      $sql .= ' | ' . $zutrittsberechtigung['sql'];
      // $success = $success && $zutrittsberechtigung['success'];

      //dpm($aggregated['zutrittsberechtigte'], "aggregated['zutrittsberechtigte']");
      foreach ($aggregated['zutrittsberechtigte'] as $key => $value) {
        $parlamentarier = _lobbywatch_data_table_flat_id('parlamentarier', $value['parlamentarier_id'], false);
        $aggregated['zutrittsberechtigte'][$key]['parlamentarier'] = $parlamentarier['data'];
        $message .= ' | ' . $parlamentarier['message'];
        $sql .= ' | ' . $parlamentarier['sql'];
        // $success = $success && $parlamentarier['success'];
      }

      // TODO interessenbindungen, branche

  //     $interessenbindungen = _lobbywatch_data_table_flat_list('interessenbindung_liste', "interessenbindung_liste.parlamentarier_id = $id", false);
  //     $aggregated['interessenbindungen'] = $interessenbindungen['data'];
  //     $message .= ' | ' . $interessenbindungen['message'];
  //     $success = $success && $interessenbindungen['success'];

  //     $zutrittsberechtigungen = _lobbywatch_data_table_flat_list('zutrittsberechtigung', "zutrittsberechtigung.parlamentarier_id = $id", false);
  //     $aggregated['zutrittsberechtigungen'] = $zutrittsberechtigungen['data'];
  //     $message .= ' | ' . $zutrittsberechtigungen['message'];
  //     $success = $success && $zutrittsberechtigungen['success'];

  //     foreach ($aggregated['zutrittsberechtigungen'] as $key => $value) {
  //       $mandate = _lobbywatch_data_table_zutrittsberechtigte_aggregated_id($value['id'], false)['data'];
  //       $aggregated['zutrittsberechtigungen'][$key]['mandate'] = $mandate['mandate'];
  //       $message .= ' | ' . $mandate['message'];
  //       $success = $success && $mandate['success'];
  //     }

      _lobbywatch_add_wissensartikel($table, $id, $aggregated, $message, $sql);
    }

    $count = $organsation['count'];
//     $success = true;
//     dpm($aggregated, '$aggregated');
    //$sql = $in_kommission['sql'];
  } catch(Exception $e) {
    $message .= _lobbywatch_data_add_exeption($e);
    $success = false;
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();

    $response = array(
        'success' => $success,
        'count' => $count,
        'message' => $message,
        'sql' => $show_sql ? $sql : '',
        'source' => $table,
        'build secs' => '' . _lobbywatch_page_build_secs(),
        'data' => $aggregated
    );
    if ($json) {
      lobbywatch_json_output($response);
      drupal_exit();
    } else {
      return $response;
    }
  }

}

/** Add knowledge_articles of current language. The Drupal 7 translation system is used. */
function _lobbywatch_add_wissensartikel($source_table, $id, &$aggregated, &$message, &$sql) {
  $lang = get_lang();
  $knowledge_articles =  _lobbywatch_data_table_flat_list('wissensartikel_link', "wissensartikel_link.target_id = $id AND wissensartikel_link.target_table_name = '$source_table'", false, '', 'JOIN v_d7_node node ON node.tnid_nid = (SELECT tnid_nid FROM v_d7_node WHERE nid=wissensartikel_link.node_id) AND node.status=1' . ($lang ? " AND node.language = '$lang'" : ''), ', node.tnid_nid, node.language article_language, node.type article_type, node.status article_status, node.nid article_nid, node.title as article_title');
  $aggregated['knowledge_articles'] = $knowledge_articles['data'];
  $message .= ' | ' . $knowledge_articles['message'];
  $sql .= ' | ' . $knowledge_articles['sql'];
}

function _lobbywatch_data_table_interessengruppe_aggregated_id($id, $json = true) {
  global $show_sql;
  $success = true;
  $count = 0;
  $message = '';
  $sql = '';
  $table = 'interessengruppe';
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');
  try {
    $interessengruppe = _lobbywatch_data_table_flat_id($table, $id, false);
    $aggregated = $interessengruppe['data'];
    $message .= ' | ' . $interessengruppe['message'];
    $sql .= ' | ' . $interessengruppe['sql'];
    $success = $success && $interessengruppe['success'];

    // load aggregated data only if main object is there
    if ($success) {
      $organisationen = _lobbywatch_data_table_flat_list(
        'organisation',
        "organisation.interessengruppe_id = $id OR organisation.interessengruppe2_id = $id OR organisation.interessengruppe3_id = $id",
        false
      );

      $aggregated['organisationen'] = $organisationen['data'];
      $message .= ' | ' . $organisationen['message'];
      $sql .= ' | ' . $organisationen['sql'];
      // $success = $success && $organisationen['success'];

      $aggregated_parlamentarier = _lobbywatch_data_get_parlamentarier_from_organisation($organisationen['data']);
      $aggregated = array_merge($aggregated, $aggregated_parlamentarier);

      $zwischen_organisationen_conditions = array_map(
        function($con) { return "organisation.id = " . $con['zwischen_organisation_id']; },
        array_filter(
          $connections['data'],
          function($con) { return !empty($con['zwischen_organisation_id']); }
        )
      );
      $zwischen_organisationen_conditions = !empty($zwischen_organisationen_conditions) ? $zwischen_organisationen_conditions : ['1=0'];

      $zwischen_organisationen = _lobbywatch_data_table_flat_list(
        'organisation',
        "(" . implode(" OR ", $zwischen_organisationen_conditions) . ")",
        false
      );

      $aggregated['zwischen_organisationen'] = $zwischen_organisationen['data'];
      $message .= ' | ' . $zwischen_organisationen['message'];
      $sql .= ' | ' . $zwischen_organisationen['sql'];
      // $success = $success && $zwischen_organisationen['success'];


      $zutrittsberechtigte_conditions = array_map(
        function($con) { return "zutrittsberechtigung.person_id = " . $con['person_id']; },
        array_filter(
          $connections['data'],
          function($con) { return !empty($con['person_id']); }
        )
      );
      $zutrittsberechtigte_conditions = !empty($zutrittsberechtigte_conditions) ? $zutrittsberechtigte_conditions : ['1=0'];

      $zutrittsberechtigte = _lobbywatch_data_table_flat_list(
        'zutrittsberechtigung',
        "(" . implode(" OR ", $zutrittsberechtigte_conditions) . ")",
        false
      );

      $aggregated['zutrittsberechtigte'] = $zutrittsberechtigte['data'];
      $message .= ' | ' . $zutrittsberechtigte['message'];
      $sql .= ' | ' . $zutrittsberechtigte['sql'];
      // $success = $success && $zutrittsberechtigte['success'];

      _lobbywatch_add_wissensartikel($table, $id, $aggregated, $message, $sql);
    }

    $count = $organsation['count'];
  } catch(Exception $e) {
    $message .= _lobbywatch_data_add_exeption($e);
    $success = false;
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();

    $response = array(
        'success' => $success,
        'count' => $count,
        'message' => $message,
        'sql' => $show_sql ? $sql : '',
        'source' => $table,
        'build secs' => '' . _lobbywatch_page_build_secs(),
        'data' => $aggregated
    );
    if ($json) {
      lobbywatch_json_output($response);
      drupal_exit();
    } else {
      return $response;
    }
  }
}

/**
 * @param $orgs array of organisation having 'id'
 */
function _lobbywatch_data_get_parlamentarier_from_organisation($orgs) {

  $aggregated = [];

  $org_conditions = array_map(
    function($org) { return "organisation_parlamentarier_beide_indirekt.connector_organisation_id = " . $org['id']; },
    $orgs
  );

  $connections = _lobbywatch_data_table_flat_list(
    'organisation_parlamentarier_beide_indirekt',
    "(" . implode(" OR ", $org_conditions) . ")",
    false
  );

  $aggregated['connections'] = $connections['data'];
  $message .= ' | ' . $connections['message'];
  $sql .= ' | ' . $connections['sql'];
  // $success = $success && $connections['success'];

  $parlamentarier_conditions = array_map(
    function($con) { return "parlamentarier.id = " . $con['parlamentarier_id']; },
    $connections['data']
  );

  $parlamentarier = _lobbywatch_data_table_flat_list(
    'parlamentarier',
    "(" . implode(" OR ", $parlamentarier_conditions) . ")",
    false
  );

  $aggregated['parlamentarier'] = $parlamentarier['data'];
  $message .= ' | ' . $parlamentarier['message'];
  $sql .= ' | ' . $parlamentarier['sql'];

  return $aggregated;
}

function _lobbywatch_data_table_branche_aggregated_id($id, $json = true) {
  global $show_sql;
  $success = true;
  $count = 0;
  $message = '';
  $sql = '';
  $table = 'branche';
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');
  try {
    $branche = _lobbywatch_data_table_flat_id($table, $id, false);
    $aggregated = $branche['data'];
    $message .= ' | ' . $branche['message'];
    $sql .= ' | ' . $branche['sql'];
    $success = $success && $branche['success'];

    // load aggregated data only if main object is there
    if ($success) {
      // interessengruppe
      $interessengruppe = _lobbywatch_data_table_flat_list(
        'interessengruppe',
        "interessengruppe.branche_id = $id",
        false
      );

      $aggregated['interessengruppe'] = $interessengruppe['data'];
      $message .= ' | ' . $interessengruppe['message'];
      $sql .= ' | ' . $interessengruppe['sql'];
      $success = $success && $interessengruppe['success'];

      // Get aggregated interessengruppe, this is slow, around 4s
//       foreach($interessengruppe['data'] as $ig) {
//         $ig_id = $ig['id'];
//         $interessengruppe_aggr = _lobbywatch_data_table_interessengruppe_aggregated_id($ig_id, false);
//
//         $aggregated['interessengruppe_aggr'][] = $interessengruppe_aggr['data'];
//         $message .= ' | ' . $interessengruppe_aggr['message'];
//         $sql .= ' | ' . $interessengruppe_aggr['sql'];
//         $success = $success && $interessengruppe_aggr['success'];
//       }
//
      // organisations
      $organisationen = _lobbywatch_data_table_flat_list(
        'organisation',
        "organisation.interessengruppe_branche_id = $id OR organisation.interessengruppe2_branche_id = $id OR organisation.interessengruppe3_branche_id = $id",
        false
      );

      $aggregated['organisationen'] = $organisationen['data'];
      $message .= ' | ' . $organisationen['message'];
      $sql .= ' | ' . $organisationen['sql'];
      $success = $success && $organisationen['success'];

      // parlamentarier
      $aggregated_parlamentarier = _lobbywatch_data_get_parlamentarier_from_organisation($organisationen['data']);
      $aggregated = array_merge($aggregated, $aggregated_parlamentarier);

      _lobbywatch_add_wissensartikel($table, $id, $aggregated, $message, $sql);
    }

    $count = $branche['count'];
  } catch(Exception $e) {
    $message .= _lobbywatch_data_add_exeption($e);
    $success = false;
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();

    $response = array(
        'success' => $success,
        'count' => $count,
        'message' => $message,
        'sql' => $show_sql ? $sql : '',
        'source' => $table,
        'build secs' => '' . _lobbywatch_page_build_secs(),
        'data' => $aggregated
    );
    if ($json) {
      lobbywatch_json_output($response);
      drupal_exit();
    } else {
      return $response;
    }
  }
}

function _lobbywatch_data_query_parlament_partei_aggregated_list($condition = '1', $json = true) {
  global $show_sql;
  $success = true;
  $message = '';
  $count = 0;
  $items = null;
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    $lang_suffix = get_lang_suffix();

    $table = 'parlamentarier';
    $sql = "
select count(*) as anzahl, partei.id, $table.partei$lang_suffix as partei_short, partei.anzeige_name$lang_suffix as partei_name, $table.fraktion
from v_parlamentarier $table
inner join v_partei partei
on partei.id = $table.partei_id
WHERE $condition " .
// _lobbywatch_data_filter_unpublished_SQL($table) .
_lobbywatch_data_filter_fields_SQL($table) . "
group by $table.partei
order by count(*) desc, $table.partei asc "
    ;

    $result = db_query($sql, []);

    $items['totalMembers'] = 246;
    $items['parteien'] = _lobbywatch_data_clean_records($result);

    $count = count($items['parteien']);
    $message .= $count . " record(s) found";
    $success = $count > 0;

    foreach ($items['parteien'] as &$record) {
      $parlamentarier =  _lobbywatch_data_table_flat_list('parlamentarier', "parlamentarier.partei_id = ${record['id']}", false);
      $record['members'] = $parlamentarier['data'];
      $message .= ' | ' . $parlamentarier['message'];
      $sql .= ' | ' . $parlamentarier['sql'];
      $success = $success && ($parlamentarier['success'] || (!$parlamentarier['success'] && $parlamentarier['data']['members'] == 0));

      // Deprecated code
//       foreach ($record['members'] as &$parlamentarier_inner) {
//         $relation_counts = _lobbywatch_data_parlamentarier_relations_count($parlamentarier_inner['id']);
//         $parlamentarier_inner['relations'] = $relation_counts['anzahlTotal'];
//         $parlamentarier_inner['relations_simple'] = $relation_counts['anzahlSimple'];
//         $parlamentarier_inner['relations_executive'] = $relation_counts['anzahlExecutive'];
//         $relation_counts = _lobbywatch_data_parlamentarier_gast_relations_count($parlamentarier_inner['id']);
//         $parlamentarier_inner['gast_relations'] = $relation_counts['anzahlTotal'];
//         $parlamentarier_inner['gast_relations_simple'] = $relation_counts['anzahlSimple'];
//         $parlamentarier_inner['gast_relations_executive'] = $relation_counts['anzahlExecutive'];
//       }
    }

  } catch(Exception $e) {
    $message .= _lobbywatch_data_add_exeption($e);
    $success = false;
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
    $response = array(
        'success' => $success,
        'count' => $count,
        'message' => $message,
        'sql' => $show_sql ? $sql : '',
        'source' => $table,
        'build secs' => '' . _lobbywatch_page_build_secs(),
        'data' => $items
    );

    if ($json) {
      lobbywatch_json_output($response);
      drupal_exit();
    } else {
      return $response;
    }
  }
}

function _lobbywatch_data_parlamentarier_relations_count($id) {
  // TODO implement filter
  // TODO Must be fixed, it must use wirksamkeit instead of counts
  return _lobbywatch_data_db_query(
  "select sum(anzahlTotal) as anzahlTotal, sum(anzahlExecutive) as anzahlExecutive, sum(anzahlTotal)-sum(anzahlExecutive) as anzahlSimple
from
(
select count(*) as anzahlTotal, 0 as anzahlExecutive
from interessenbindung ib
where ib.parlamentarier_id = :id" .
      _lobbywatch_data_filter_unpublished_SQL('ib') . "
union
select 0 as anzahlTotal, count(*) as anzahlExecutive
from interessenbindung ib
where ib.parlamentarier_id = :id
and ib.art in ('vorstand', 'geschaeftsfuehrend') " .
      _lobbywatch_data_filter_unpublished_SQL('ib') . "
) t", array('id' => $id))['0'];
}

function _lobbywatch_data_parlamentarier_gast_relations_count($id) {
  // TODO implement filter
  // TODO counts are not following lobbywatch.ch rules
  // TODO Must be fixed, it must use wirksamkeit instead of counts
  return _lobbywatch_data_db_query(
  "select sum(anzahlTotal) as anzahlTotal, sum(anzahlExecutive) as anzahlExecutive, sum(anzahlTotal)-sum(anzahlExecutive) as anzahlSimple
from
(
select count(*) as anzahlTotal, 0 as anzahlExecutive
from v_zutrittsberechtigung zb
inner join v_mandat ma on ma.person_id = zb.person_id
where zb.parlamentarier_id = :id " .
      _lobbywatch_data_filter_unpublished_SQL('zb') . "
union
select 0 as anzahlTotal, count(*) as anzahlExecutive
from v_zutrittsberechtigung zb
inner join v_mandat ma on ma.person_id = zb.person_id
where zb.parlamentarier_id = :id
and ma.art in ('vorstand', 'geschaeftsfuehrend')
" . _lobbywatch_data_filter_unpublished_SQL('zb') . "
) t", array('id' => $id))['0'];
}

function _lobbywatch_data_db_query($sql, $params = []) {
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    $result = db_query($sql, $params);

//     dpm($sql, '$sql');
//     dpm($params, '$params');

    $items = _lobbywatch_data_clean_records($result);

//     dpm($items);

    $count = count($items);
    return $items;
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
  }
}

function _lobbywatch_data_router($path = '', $version = '', $data_type = '', $call_type = '', $object = '', $response_type = '', $respone_object = '', $parameter = '', $json_output = false) {
//   df($path, '$path');
//   df($version, '$version');
//   df($data_type, '$data_type');
//   df($call_type, '$call_type');
//   df($object, '$object');
//   df($response_type, '$response_type');
//   df($respone_object, '$respone_object');
//   df($parameter, '$parameter');

  if ($version !== 'v1' || $data_type !== 'json') {
    _lobbywatch_data_json_404();
  }
  //   foreach (Constants::$workflow_tables as $key => $value) {
  //     $items["v1/json/table/$key/flat/id/%"] = array(
  //       'callback' => '_lobbywatch_data_table_flat_id',
  //       'page arguments' => array($key, 6, false),
  //       'access arguments' => array('access lobbywatch data general content'),
  //       'file' => 'lobbywatch_data.interface.inc',
  //       'dependencies' => array('lobbywatch', 'user'),
  //       'skip_hook_init' => TRUE,
  //     );

  //     $items["v1/json/table/$key/flat/list"] = array(
  //       'callback' => '_lobbywatch_data_table_flat_list',
  //       'page arguments' => array($key, '1', false),
  //       'access arguments' => array('access lobbywatch data general content'),
  //       'file' => 'lobbywatch_data.interface.inc',
  //       'dependencies' => array('lobbywatch', 'user'),
  //       'skip_hook_init' => TRUE,
  //     );

  //     $items["v1/json/table/$key/flat/list/%"] = array(
  //       'callback' => '_lobbywatch_data_table_flat_list_search',
  //       'page arguments' => array($key, 6, false),
  //       'access arguments' => array('access lobbywatch data general content'),
  //       'file' => 'lobbywatch_data.interface.inc',
  //       'dependencies' => array('lobbywatch', 'user'),
  //       'skip_hook_init' => TRUE,
  //     );
  //   }

  lobbywatch_set_lang(_lobbywatch_data_get_lang());

  // Idea: We could alternatively use regex with path instead of ifs, maybe more convenient
  if ($call_type === 'table' && array_key_exists($object, Constants::$workflow_tables) && $response_type === 'flat' && $respone_object === 'id' && $parameter) {
//     df($parameter, '_lobbywatch_data_table_flat_id');
    return _lobbywatch_data_table_flat_id($object, $parameter, false);
  } else if ($call_type === 'table' && array_key_exists($object, Constants::$workflow_tables) && $response_type === 'flat' && $respone_object === 'list' && $parameter) {
//     df($parameter, '_lobbywatch_data_table_flat_list_search');
    return _lobbywatch_data_table_flat_list_search($object, $parameter, false);
  } else if ($call_type === 'table' && array_key_exists($object, Constants::$workflow_tables) && $response_type === 'flat' && $respone_object === 'list') {
//     df($parameter, '_lobbywatch_data_table_flat_list');
    return _lobbywatch_data_table_flat_list($object, 1, false);
  } else if ($call_type === 'ws' && (in_array($object, ['uid','zefix-soap', 'zefix-rest', 'uid-bfs'])) && $response_type === 'flat' && $respone_object === 'uid' && $parameter) {
//     df($parameter, '_lobbywatch_data_table_flat_list');
    return _lobbywatch_data_ws_uid($object, $parameter, false);
//   } else if ($call_type === 'ws' && ($object === 'zefix') && $response_type === 'flat' && $respone_object === 'uid' && $parameter) {
// //     df($parameter, '_lobbywatch_data_table_flat_list');
//     return _lobbywatch_data_ws_zefix($object, $parameter, false);
  }

  //   foreach (Constants2::getAllEnrichedRelations() as $key => $value) {
  //     $items["v1/json/relation/$key/flat/list"] = array(
  //       'callback' => '_lobbywatch_data_relation_flat_list',
  //       'page arguments' => array($key, '1', false),
  //       'access arguments' => array('access lobbywatch data general content'),
  //       'file' => 'lobbywatch_data.interface.inc',
  //       'dependencies' => array('lobbywatch', 'user'),
  //       'skip_hook_init' => TRUE,
  //     );
  //   }

  else if ($call_type === 'relation' && array_key_exists($object, Constants::getAllEnrichedRelations()) && $response_type === 'flat' && $respone_object === 'list') {
//     df($parameter, '_lobbywatch_data_relation_flat_list');
    return _lobbywatch_data_relation_flat_list($object, 1, false);
  }

  //   $items["v1/json/table/zutrittsberechtigung/aggregated/id/%"] = array(
  //       'callback' => '_lobbywatch_data_table_zutrittsberechtigte_aggregated_id',
  //       'page arguments' => array(8, false),
  //       'access arguments' => array('access lobbywatch data general content'),
  //       'file' => 'lobbywatch_data.interface.inc',
  //       'dependencies' => array('lobbywatch', 'user'),
  //       'skip_hook_init' => TRUE,
  //   );

  else if ($call_type === 'table' && $object === 'zutrittsberechtigung' && $response_type === 'aggregated' && $respone_object === 'id' && $parameter) {
//     df($parameter, '_lobbywatch_data_table_zutrittsberechtigte_aggregated_id');
    return _lobbywatch_data_table_zutrittsberechtigte_aggregated_id($parameter, false);
  }

  //   $items["v1/json/table/parlamentarier/aggregated/id/%"] = array(
  //       'callback' => '_lobbywatch_data_table_parlamentarier_aggregated_id',
  //       'page arguments' => array(8, false),
  //       'access arguments' => array('access lobbywatch data general content'),
  //       'file' => 'lobbywatch_data.interface.inc',
  //       'dependencies' => array('lobbywatch', 'user'),
  //       'skip_hook_init' => TRUE,
  //   );

  else if ($call_type === 'table' && $object === 'parlamentarier' && $response_type === 'aggregated' && $respone_object === 'id' && $parameter) {
//     df($parameter, '_lobbywatch_data_table_parlamentarier_aggregated_id');
    return _lobbywatch_data_table_parlamentarier_aggregated_id($parameter, false);
  }

  //   $items["v1/json/table/organisation/aggregated/id/%"] = array(
  //       'callback' => '_lobbywatch_data_table_organisation_aggregated_id',
  //       'page arguments' => array(8, false),
  //       'access arguments' => array('access lobbywatch data general content'),
  //       'file' => 'lobbywatch_data.interface.inc',
  //       'dependencies' => array('lobbywatch', 'user'),
  //       'skip_hook_init' => TRUE,
  //   );

  else if ($call_type === 'table' && $object === 'organisation' && $response_type === 'aggregated' && $respone_object === 'id' && $parameter) {
//     df($parameter, '_lobbywatch_data_table_organisation_aggregated_id');
    return _lobbywatch_data_table_organisation_aggregated_id($parameter, false);
  }

  else if ($call_type === 'table' && $object === 'interessengruppe' && $response_type === 'aggregated' && $respone_object === 'id' && $parameter) {
    return _lobbywatch_data_table_interessengruppe_aggregated_id($parameter, false);
  }

  else if ($call_type === 'table' && $object === 'branche' && $response_type === 'aggregated' && $respone_object === 'id' && $parameter) {
    return _lobbywatch_data_table_branche_aggregated_id($parameter, false);
  }

  //   $items["v1/json/query/parlament-partei/aggregated/list"] = array(
  //       'callback' => '_lobbywatch_data_query_parlament_partei_aggregated_list',
  //       'page arguments' => array('1', false),
  //       'access arguments' => array('access lobbywatch data general content'),
  //       'file' => 'lobbywatch_data.interface.inc',
  //       'dependencies' => array('lobbywatch', 'user'),
  //       'skip_hook_init' => TRUE,
  //   );

  else if ($call_type === 'query' && $object === 'parlament-partei' && $response_type === 'aggregated' && $respone_object === 'list') {
//     df($parameter, '_lobbywatch_data_query_parlament_partei_aggregated_list');
    return _lobbywatch_data_query_parlament_partei_aggregated_list(1, false);
  } else if ($call_type === 'search' && $object === 'default' /*&& $response_type === 'aggregated' && $respone_object === 'list'*/ /*&& $parameter*/) {
//      df($response_type, '_lobbywatch_data_search');
    return _lobbywatch_data_search($response_type, false);
  }

  //   // TODO error message in case of wrong names
  //   $items["v1/json/%/%"] = array(
  //       'callback' => '_lobbywatch_data_not_found',
  //       'page arguments' => array(4, 5, false),
  //       'access arguments' => array('access lobbywatch data general content'),
  //       'file' => 'lobbywatch_data.interface.inc',
  //       'dependencies' => array('lobbywatch', 'user'),
  //       'skip_hook_init' => TRUE,
  //   );

  //   $items["v1/json/table/parlamentarier/aggregated/all"] = array(
  //       'page callback' => '_lobbywatch_data_table_parlamentarier_aggregated_list',
  //       'page arguments' => [],
  //       'access arguments' => array('access lobbywatch data general content'),
  //       'type' => MENU_CALLBACK,
  //       'file' => 'lobbywatch_data.interface.inc',
  //   );


  _lobbywatch_data_json_404();
}

function _lobbywatch_data_json_404() {
  drupal_add_http_header('Status', '404 Not Found');
  $response = array(
      'success' => false,
      'count' => 0,
      'message' => '404 Not Found. The requested URL "' . check_plain(request_uri()) . '" was not found on this server.',
      'sql' => '',
      'source' => '',
      'build secs' => '' . _lobbywatch_page_build_secs(),
      'data' => null,
  );
  print lobbywatch_json_output($response);
  exit;
}

function _lobbywatch_data_json_403() {
  drupal_add_http_header('Status', '403 Forbidden');
  $response = array(
      'success' => false,
      'count' => 0,
      'message' => '403 Forbidden. The requested URL "' . check_plain(request_uri()) . '" is protected.',
      'sql' => '',
      'source' => '',
      'build secs' => '' . _lobbywatch_page_build_secs(),
      'data' => null,
  );
  print lobbywatch_json_output($response);
  exit;
}

function _lobbywatch_data_ws_uid($table, $uid, $json = true) {
  global $show_sql;
  global $zefix_ws_login;
  global $allowed_uid_access_keys;
  $success = true;
  $count = 0;
  $items = null;
  $message = '';
  $sql = '';

  // Protect Zefix WS, either with key or from cyon.ch server or localhost
  if (in_array($table, ['uid', 'zefix-rest', 'uid-bfs']) && (empty($_GET['access_key']) || !in_array($_GET['access_key'], $allowed_uid_access_keys, true)) && $_SERVER['REMOTE_ADDR'] !== '91.206.24.232' && $_SERVER['REMOTE_ADDR'] !== '127.0.0.1') {
    _lobbywatch_data_json_403();
  } else if (in_array($table, ['zefix-soap']) && (empty($_GET['access_key']) || !in_array($_GET['access_key'], $zefix_ws_login['keys'], true)) && $_SERVER['REMOTE_ADDR'] !== '91.206.24.232' && $_SERVER['REMOTE_ADDR'] !== '127.0.0.1') {
    _lobbywatch_data_json_403();
  }

  //df($table, '_lobbywatch_data_table_flat_id');
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    if ($table == 'uid-bfs') {
      $items = _lobbywatch_fetch_ws_uid_bfs_data($uid, 0, false, false);
    } else if ($table == 'zefix-soap') {
      $items = _lobbywatch_fetch_ws_zefix_soap_data($uid, 0, false, false);
    } else if ($table == 'zefix-rest') {
      $items = _lobbywatch_fetch_ws_zefix_rest_data($uid, 0, false);
    } else if ($table == 'uid') {
      $items = _lobbywatch_fetch_ws_zefix_rest_data($uid, 0, false);
      if (!$items['success']) {
        $message .= "zefix-rest unsuccessful (${items['message']}), calling uid@bfs | ";
        $items = _lobbywatch_fetch_ws_uid_bfs_data($uid, 0, false, false, 0);
      }
    } else {
      // Must not happen
      $items = null;
    }

//     $count = !empty($items['data']) ? 1 : 0;
    $count = $items['count']; // already set by fillDataFromUIDResult()
    $success = $items['success'];
    $message .= $items['message'];
    $sql .= $items['sql'];
  } catch(Exception $e) {
    $message .= _lobbywatch_data_add_exeption($e);
    $success = false;
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();

    $response = array(
        'success' => $success,
        'count' => $count,
        'message' => $message,
        'sql' => $show_sql? $sql : '',
        'source' => $table,
        'build secs' => '' . _lobbywatch_page_build_secs(),
        'data' => $success ? $items['data'] : null,
    );

    if ($json) {
      lobbywatch_json_output($response);
      drupal_exit();
    } else {
      return $response;
    }
  }
}
