<?php

//file:///home/rkurmann/dev/web/staging/public_html/d7/sites/lobbywatch.ch/app/settings/settings.php

function _lobbywatch_data_table_flat_id($table, $id) {
  $success = false;
  $message = null;
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    $items = array();
    $sql = "
      SELECT *
      FROM v_$table
      WHERE id=:id" .
        (!user_access('access lobbywatch data unpublished content') ? ' AND freigabe_datum < NOW()' : '');

    $result = db_query($sql, array(':id' => $id));

    while($record = $result->fetchAssoc()) {
      $items[] = _lobbywatch_data_clean_fields($record);
    }
    $success = count($items) == 1;
    $message = count($items) . " record(s) found";
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active();

    $response = array(
        'success' => $success,
        'message' => $message,
        'sql' => $sql,
        'data' => $items[0],
    );

    drupal_json_output($response);
  }
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

  if (true || !empty($record['symbol_klein_rel'])) {
//     require_once dirname(__FILE__) . '/../../../../lobbywatch.ch/app/settings/settings.php';
    require_once DRUPAL_ROOT . '/sites/lobbywatch.ch/app/settings/settings.php';
    $record['symbol_abs_path'] = $public_files_dir_abs . '/' . $record['symbol_klein_rel'];
//     dpm($record, '$record');
  }

  return $record;
}

function _lobbywatch_data_table_flat_list($table) {
  $success = false;
  $message = null;
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    $items = array();
    $sql = "
    SELECT *
    FROM v_$table
    WHERE 1 " .
    (!user_access('access lobbywatch data unpublished content') ? ' AND freigabe_datum < NOW()' : '');

    $result = db_query($sql, array());

    $includeHistorised = false;

    // @todo test exclusion of historised records
    while($record = $result->fetchAssoc()) {
      if ((!array_key_exists('bis_unix', $record) && !array_key_exists('im_rat_bis_unix', $record))
          || (!isset($record['bis_unix']) || $record['bis_unix'] > time())
          || (!isset($record['im_rat_bis_unix']) || $record['im_rat_bis_unix'] > time())
          || $includeHistorised) {
        $items[] = _lobbywatch_data_clean_fields($record);
      }
    }
    $success = count($items) > 0;
    $message = count($items) . " record(s) found";
  } finally {
  // Go back to the default database,
      // otherwise Drupal will not be able to access it's own data later on.
      db_set_active();

      $response = array(
          'success' => $success,
          'message' => $message,
          'sql' => $sql,
          'data' => $items,
      );

      drupal_json_output($response);
    }
}
