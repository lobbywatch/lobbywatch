<?php

global $show_debug;
$show_debug = true;

function _lobbywatch_data_table_flat_id($table, $id, $json = true) {
  global $show_debug;
  $success = false;
  $message = '';
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    $sql = "
      SELECT $table.*
      FROM v_$table $table
      WHERE $table.id=:id" .
        _lobbywatch_data_filter_unpublished_SQL($table);

    $result = db_query($sql, array(':id' => $id));

    $items = _lobbywatch_data_clean_records($result);

    $success = count($items) == 1;
    $message .= count($items) . " record(s) found";
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();

    $response = array(
        'success' => $success,
        'message' => $message,
        'debug' => $show_debug? $sql : '',
        'data' => $success ? $items[0] : null,
    );

    if ($json) {
      drupal_json_output($response);
    } else {
      return $response;
    }
  }
}

/** Filters out unpublished data if not privileged or includeUnpublished == 0 */
function _lobbywatch_data_filter_unpublished_SQL($table) {
  return ($_GET['includeUnpublished'] != 1 || !user_access('access lobbywatch data unpublished content') ? " AND $table.freigabe_datum < NOW()" : '');
}

function _lobbywatch_data_clean_records($result) {
  $items = array();
  $includeHistorised = $_GET['includeInactive'] == 1 && user_access('access lobbywatch unpublished content');

  // @todo test exclusion of historised records
  while($record = $result->fetchAssoc()) {
    if ((!array_key_exists('bis_unix', $record) && !array_key_exists('im_rat_bis_unix', $record))
        || (!isset($record['bis_unix']) || $record['bis_unix'] > time())
        || (!isset($record['im_rat_bis_unix']) || $record['im_rat_bis_unix'] > time())
        || $includeHistorised) {
          $items[] = _lobbywatch_data_clean_fields($record);
    }
  }
  return $items;
}

function _lobbywatch_data_clean_fields($record) {
  if (!user_access('access lobbywatch data confidential content')) {
    foreach ($record as $key => $value) {
      // Clean intern fields
      if (in_array($key, Constants::$intern_fields)) {
        unset($record[$key]);
      }

    }
  }

  global $public_files_dir_abs;
  global $rel_files_url;
  global $base_root;
  if (isset($record['symbol_klein_rel'])) {
    $record['symbol_path'] = $rel_files_url . '/' . $record['symbol_klein_rel'];
    $record['symbol_url'] = $base_root . '/' . $record['symbol_path'];
  }

  if (isset($record['kleinbild'])) {
    $record['kleinbild_path'] = $rel_files_url . '/../auswertung/parlamentarierBilder/' . $record['kleinbild'];
    $record['kleinbild_url'] = $base_root . '/' . $record['kleinbild_path'];
  }

  return $record;
}

function _lobbywatch_data_table_flat_list($table, $condition = '1', $json = true) {
  global $show_debug;
  $success = false;
  $message = '';
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    $sql = "
    SELECT $table.*
    FROM v_$table $table
    WHERE $condition " .
    _lobbywatch_data_filter_unpublished_SQL($table);

    $result = db_query($sql, array());

    $items = _lobbywatch_data_clean_records($result);

    $success = count($items) > 0;
    $message = count($items) . " record(s) found";
  } finally {
  // Go back to the default database,
      // otherwise Drupal will not be able to access it's own data later on.
      db_set_active();
      $response = array(
          'success' => $success,
          'message' => $message,
          'debug' => $show_debug ? $sql : '',
          'data' => $items
      );

      if ($json) {
        drupal_json_output($response);
      } else {
        return $response;
      }
    }
}

function _lobbywatch_data_table_flat_list_search($table, $search_str, $json = true) {
  global $show_debug;
  $success = false;
  $message = '';
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    if ($table === 'parlamentarier') {
      $sql = "SELECT * FROM v_$table WHERE anzeige_name LIKE :str" . ($_GET['includeInactive'] != 1 && !user_access('access lobbywatch unpublished content') ? ' AND (im_rat_bis IS NULL OR im_rat_bis > NOW())' : '') . _lobbywatch_data_filter_unpublished_SQL($table);
    } else if ($table === 'zutrittsberechtigung') {
      $sql = "SELECT * FROM v_$table WHERE anzeige_name LIKE :str" . ($_GET['includeInactive'] != 1 && !user_access('access lobbywatch unpublished content') ? ' AND (bis IS NULL OR bis > NOW())' : '') . _lobbywatch_data_filter_unpublished_SQL($table);
    } else if (in_array($table, Constants::$entities_web)) {
      $sql = "SELECT * FROM v_$table WHERE anzeige_name LIKE :str" . _lobbywatch_data_filter_unpublished_SQL($table);
    } else {
      throw new Exception("Table $table does not exist");
    }
    $sql .= ' LIMIT 100;';
    $result = db_query($sql, array(':str' => "%$search_str%"));

    $items = _lobbywatch_data_clean_records($result);
        $success = count($items) > 0;
    $message .= count($items) . " record(s) found";
  } catch(Exception $e) {
    $message .= $e->getMessage();
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();
    $response = array(
        'success' => $success,
        'message' => $message,
        'debug' => $show_debug ? $sql : '',
        'data' => $items
    );
    if ($json) {
      drupal_json_output(
          $response);
    } else {
      return $response;
    }
  }
}


function _lobbywatch_data_table_zutrittsberechtigte_aggregated_id($id, $json = true) {
  global $show_debug;
  $success = false;
  $message = '';
  $sql = '';
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');
  try {
    $aggregated = _lobbywatch_data_table_flat_id('zutrittsberechtigung', $id, false)['data'];

    $aggregated['mandate'] = _lobbywatch_data_table_flat_list('zutrittsberechtigung_mandate', "zutrittsberechtigung_mandate.zutrittsberechtigung_id = $id", false)['data'];

//     dpm($aggregated, '$aggregated');
    $success = true;
    //$sql = $in_kommission['sql'];
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();

    $response = array(
        'success' => $success,
        'message' => $message,
        'debug' => $show_debug ? $sql : '',
        'data' => $aggregated
    );
    if ($json) {
      drupal_json_output($response);
    } else {
      return $response;
    }
  }

}

function _lobbywatch_data_table_parlamentarier_aggregated_id($id, $json = true) {
  global $show_debug;
  $success = false;
  $message = '';
  $sql = '';
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');
  try {
    $aggregated = _lobbywatch_data_table_flat_id('parlamentarier', $id, false)['data'];

    $aggregated['in_kommission'] = _lobbywatch_data_table_flat_list('in_kommission_liste', "in_kommission_liste.parlamentarier_id = $id", false)['data'];

    $aggregated['interessenbindungen'] = _lobbywatch_data_table_flat_list('interessenbindung_liste', "interessenbindung_liste.parlamentarier_id = $id", false)['data'];

    $aggregated['zutrittsberechtigungen'] = _lobbywatch_data_table_flat_list('zutrittsberechtigung', "zutrittsberechtigung.parlamentarier_id = $id", false)['data'];

    foreach ($aggregated['zutrittsberechtigungen'] as $key => $value) {
      $aggregated['zutrittsberechtigungen'][$key]['mandate'] = _lobbywatch_data_table_zutrittsberechtigte_aggregated_id($value['id'], false)['data']['mandate'];
    }

    $success = true;
//     dpm($aggregated, '$aggregated');
    //$sql = $in_kommission['sql'];
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();

    $response = array(
        'success' => $success,
        'message' => $message,
        'debug' => $show_debug ? $sql : '',
        'data' => $aggregated
    );
    if ($json) {
      drupal_json_output($response);
    } else {
      return $response;
    }
  }

}
