<?php

global $show_sql;
global $show_stacktrace;
$show_sql = true;
$show_stacktrace = false;

const SUPPORTED_DB_CHARS = '-a-zA-Z0-9_.äöüÄÖÜéèàÉÈâ%';

function _lobbywatch_data_table_flat_id($table, $id, $json = true) {
  global $show_sql;
  $success = true;
  $count = 0;
  $items = null;
  $message = '';
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');

  try {
    $sql = "
      SELECT $table.*
      FROM v_$table $table
      WHERE $table.id=:id" .
        _lobbywatch_data_filter_unpublished_SQL($table) .
        _lobbywatch_data_filter_fields_SQL($table);

    $result = db_query($sql, array(':id' => $id));

    $items = _lobbywatch_data_clean_records($result);

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
        'data' => $success ? $items[0] : null,
    );

    if ($json) {
      drupal_json_output($response);
      drupal_exit();
    } else {
      return $response;
    }
  }
}

function _lobbywatch_data_not_found($type, $table, $json = true) {
  $message = '';
  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429

  try {
    $message .= "Data interface call to $type/$table does not exist";
  } catch(Exception $e) {
    $message .= _lobbywatch_data_add_exeption($e);
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.

    $response = array(
        'success' => false,
        'count' => 0,
        'message' => $message,
        'sql' => '',
        'source' => $table,
        'data' => null,
    );

    if ($json) {
      drupal_json_output($response);
      exit(2);
    } else {
      return $response;
    }
  }
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
  $matches = array();
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
    $matches = array();
    if (preg_match('/^filter_([a-z0-9_]+?)(_list|_like)?$/', $key, $matches) && !_lobbywatch_data_check_intern_field($matches[1])) {
      $sql .= _lobbywatch_data_filter_field_SQL($table, $matches[1]);
    }
  }
  return $sql;
}


function _lobbywatch_data_clean_records($result) {
  $items = array();
  $includeHistorised = isset($_GET['includeInactive']) && $_GET['includeInactive'] == 1 && user_access('access lobbywatch unpublished content');

  // TODO test exclusion of historised records
  while($record = $result->fetchAssoc()) {
    if ((array_key_exists('bis_unix', $record) && (is_null($record['bis_unix']) || $record['bis_unix'] > time()))
        || (array_key_exists('im_rat_bis_unix', $record) && (is_null($record['im_rat_bis_unix']) || $record['im_rat_bis_unix'] > time()))
        || (!array_key_exists('bis_unix', $record) &&!array_key_exists('im_rat_bis_unix', $record))
        || $includeHistorised) {
          $items[] = _lobbywatch_data_clean_fields($record);
    }
  }
  return $items;
}

function _lobbywatch_data_clean_fields($record) {
  if (!(isset($_GET['includeConfidentialData']) && $_GET['includeConfidentialData'] == 0) && user_access('access lobbywatch data confidential content')) {
    foreach ($record as $key => $value) {
      // Clean intern fields
      if (in_array($key, Constants::$intern_fields)) {
        unset($record[$key]);
      }
    }
  }

  if (!(isset($_GET['includeMetaData']) && $_GET['includeMetaData'] == 0)) {
    foreach ($record as $key => $value) {
      // Clean intern fields
      if (in_array($key, Constants::$meta_fields)) {
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

function _lobbywatch_data_check_intern_field($field) {
  return !user_access('access lobbywatch data confidential content') && in_array($field, Constants::$intern_fields);
}

function _lobbywatch_data_table_flat_list($table, $condition = '1', $json = true) {
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
    SELECT $table.*
    FROM v_$table $table
    WHERE $condition " .
        _lobbywatch_data_filter_unpublished_SQL($table) .
        _lobbywatch_data_filter_fields_SQL($table);

    $result = db_query($sql, array());

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
        'data' => $items
    );

    if ($json) {
      drupal_json_output($response);
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
    SELECT $table.*
    FROM v_$table $table
    WHERE $condition " .
        _lobbywatch_data_filter_unpublished_SQL($table) .
        _lobbywatch_data_filter_fields_SQL($table);

    $result = db_query($sql, array());

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
        'data' => $items
    );

    if ($json) {
      drupal_json_output($response);
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
      $sql = "SELECT * FROM v_$table WHERE anzeige_name LIKE :str" . (!(isset($_GET['includeInactive']) && $_GET['includeInactive'] != 0 && user_access('access lobbywatch unpublished content')) ? ' AND (im_rat_bis IS NULL OR im_rat_bis > NOW())' : '')
       . _lobbywatch_data_filter_unpublished_SQL($table) .
        _lobbywatch_data_filter_fields_SQL($table);
    } else if ($table === 'zutrittsberechtigung') {
      $sql = "SELECT * FROM v_$table WHERE anzeige_name LIKE :str" . (!(isset($_GET['includeInactive']) && $_GET['includeInactive'] != 0 && !user_access('access lobbywatch unpublished content')) ? ' AND (bis IS NULL OR bis > NOW())' : '')
       . _lobbywatch_data_filter_unpublished_SQL($table) .
        _lobbywatch_data_filter_fields_SQL($table);
    } else if ($table === 'organisation') {
      $sql = "SELECT * FROM v_$table WHERE anzeige_name LIKE :str"
           . _lobbywatch_data_filter_unpublished_SQL($table) .
        _lobbywatch_data_filter_fields_SQL($table);
          } else if (in_array($table, Constants::$entities_web)) {
      $sql = "SELECT * FROM v_$table WHERE anzeige_name LIKE :str"
           . _lobbywatch_data_filter_unpublished_SQL($table) .
        _lobbywatch_data_filter_fields_SQL($table);
    } else {
      throw new Exception("Table $table does not exist");
    }
    $sql .= ' LIMIT 100;';
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
        'data' => $items
    );
    if ($json) {
      drupal_json_output($response);
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
    $success &= $zutrittsberechtigung['success'];

    $mandate = _lobbywatch_data_table_flat_list('zutrittsberechtigung_mandate', "zutrittsberechtigung_mandate.zutrittsberechtigung_id = $id", false);
    $aggregated['mandate'] = $mandate['data'];
    $message .= ' | ' . $mandate['message'];
    $success &= $mandate['success'];

//     dpm($zutrittsberechtigung, '$zutrittsberechtigung');
    $parlamentarier_id = $zutrittsberechtigung['data']['parlamentarier_id'];
    $parlamentarier = _lobbywatch_data_table_flat_list('parlamentarier', "parlamentarier.id = $parlamentarier_id", false);
//     dpm($parlamentarier, '$parlamentarier');
    $aggregated['parlamentarier'] = $parlamentarier['data'][0];
    $message .= ' | ' . $parlamentarier['message'];
    $success &= $parlamentarier['success'];

//     dpm($aggregated, '$aggregated');
    $count = count($aggregated);
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
        'data' => $aggregated
    );
    if ($json) {
      drupal_json_output($response);
      drupal_exit();
    } else {
      return $response;
    }
  }

}

function _lobbywatch_data_table_parlamentarier_aggregated_id($id, $json = true) {
  global $show_sql;
  $success = true;
  $count = 0;
  $message = '';
  $sql = '';
  $table = 'parlamentarier';

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  db_set_active('lobbywatch');
  try {
    $parlamentarier = _lobbywatch_data_table_flat_id('parlamentarier', $id, false);
    $aggregated = $parlamentarier['data'];
    $message .= ' | ' . $parlamentarier['message'];
    $success &= $parlamentarier['success'];

    $in_kommissionen =  _lobbywatch_data_table_flat_list('in_kommission_liste', "in_kommission_liste.parlamentarier_id = $id", false);
    $aggregated['in_kommission'] = $in_kommissionen['data'];
    $message .= ' | ' . $in_kommissionen['message'];
    $success &= $in_kommissionen['success'];

    $interessenbindungen = _lobbywatch_data_table_flat_list('interessenbindung_liste', "interessenbindung_liste.parlamentarier_id = $id", false);
    $aggregated['interessenbindungen'] = $interessenbindungen['data'];
    $message .= ' | ' . $interessenbindungen['message'];
    $success &= $interessenbindungen['success'];

    $zutrittsberechtigungen = _lobbywatch_data_table_flat_list('zutrittsberechtigung', "zutrittsberechtigung.parlamentarier_id = $id", false);
    $aggregated['zutrittsberechtigungen'] = $zutrittsberechtigungen['data'];
    $message .= ' | ' . $zutrittsberechtigungen['message'];
    $success &= $zutrittsberechtigungen['success'];

    foreach ($aggregated['zutrittsberechtigungen'] as $key => $value) {
      $mandate = _lobbywatch_data_table_zutrittsberechtigte_aggregated_id($value['id'], false);
      $aggregated['zutrittsberechtigungen'][$key]['mandate'] = $mandate['data']['mandate'];
      $message .= ' | ' . $mandate['message'];
      $success &= $mandate['success'];
    }

    $count = count($aggregated);
    $success = true;
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
        'data' => $aggregated
    );
    if ($json) {
      drupal_json_output($response);
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
    $success &= $organsation['success'];

    // TODO fill aggregated organisation
    $parlamentarier =  _lobbywatch_data_table_flat_list('organisation_parlamentarier', "organisation_parlamentarier.organisation_id = $id", false);
    $aggregated['parlamentarier'] = $parlamentarier['data'];
    $message .= ' | ' . $parlamentarier['message'];
    $success &= $parlamentarier['success'];

    $zutrittsberechtigung =  _lobbywatch_data_table_flat_list('organisation_zutrittsberechtigung', "organisation_zutrittsberechtigung.organisation_id = $id", false);
    $aggregated['zutrittsberechtigte'] = $zutrittsberechtigung['data'];
    $message .= ' | ' . $zutrittsberechtigung['message'];
    $success &= $zutrittsberechtigung['success'];

    //dpm($aggregated['zutrittsberechtigte'], "aggregated['zutrittsberechtigte']");
    foreach ($aggregated['zutrittsberechtigte'] as $key => $value) {
      $mandate = _lobbywatch_data_table_zutrittsberechtigte_aggregated_id($value['zutrittsberechtigung_id'], false);
      $aggregated['zutrittsberechtigte'][$key]['parlamentarier'] = $mandate['data']['parlamentarier'];
      $message .= ' | ' . $mandate['message'];
      $success &= $mandate['success'];
    }

    // TODO interessenbindungen, branche

//     $interessenbindungen = _lobbywatch_data_table_flat_list('interessenbindung_liste', "interessenbindung_liste.parlamentarier_id = $id", false);
//     $aggregated['interessenbindungen'] = $interessenbindungen['data'];
//     $message .= ' | ' . $interessenbindungen['message'];
//     $success &= $interessenbindungen['success'];

//     $zutrittsberechtigungen = _lobbywatch_data_table_flat_list('zutrittsberechtigung', "zutrittsberechtigung.parlamentarier_id = $id", false);
//     $aggregated['zutrittsberechtigungen'] = $zutrittsberechtigungen['data'];
//     $message .= ' | ' . $zutrittsberechtigungen['message'];
//     $success &= $zutrittsberechtigungen['success'];

//     foreach ($aggregated['zutrittsberechtigungen'] as $key => $value) {
//       $mandate = _lobbywatch_data_table_zutrittsberechtigte_aggregated_id($value['id'], false)['data'];
//       $aggregated['zutrittsberechtigungen'][$key]['mandate'] = $mandate['mandate'];
//       $message .= ' | ' . $mandate['message'];
//       $success &= $mandate['success'];
//     }

    $count = count($aggregated);
    $success = true;
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
        'data' => $aggregated
    );
    if ($json) {
      drupal_json_output($response);
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
    $table = 'parlamentarier';
    $sql = "
select count(*) as anzahl, partei.id, $table.partei as partei_short, partei.anzeige_name as partei_name_de, $table.fraktion
from v_parlamentarier $table
inner join v_partei partei
on partei.id = $table.partei_id
WHERE $condition " .
_lobbywatch_data_filter_unpublished_SQL($table) .
_lobbywatch_data_filter_fields_SQL($table) . "
group by $table.partei
order by count(*) desc, $table.partei asc "
    ;

    $result = db_query($sql, array());

    $items['totalMembers'] = 246;
    $items['parteien'] = _lobbywatch_data_clean_records($result);

    $count = count($items['parteien']);
    $message .= $count . " record(s) found";
    $success = $count > 0;

    foreach ($items['parteien'] as &$record) {
      $parlamentarier =  _lobbywatch_data_table_flat_list('parlamentarier', "parlamentarier.partei_id = ${record['id']}", false);
      $record['members'] = $parlamentarier['data'];
      $message .= ' | ' . $parlamentarier['message'];
      $success &= $parlamentarier['success'];

      foreach ($record['members'] as &$parlamentarier) {
        $relation_counts = _lobbywatch_data_parlamentarier_relations_count($parlamentarier['id']);
        $parlamentarier['relations'] = $relation_counts['anzahlTotal'];
        $parlamentarier['relations_simple'] = $relation_counts['anzahlSimple'];
        $parlamentarier['relations_executive'] = $relation_counts['anzahlExecutive'];
        $relation_counts = _lobbywatch_data_parlamentarier_gast_relations_count($parlamentarier['id']);
        $parlamentarier['gast_relations'] = $relation_counts['anzahlTotal'];
        $parlamentarier['gast_relations_simple'] = $relation_counts['anzahlSimple'];
        $parlamentarier['gast_relations_executive'] = $relation_counts['anzahlExecutive'];
      }
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
        'data' => $items
    );

    if ($json) {
      drupal_json_output($response);
      drupal_exit();
    } else {
      return $response;
    }
  }
}

function _lobbywatch_data_parlamentarier_relations_count($id) {
  // TODO implement filter
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
  return _lobbywatch_data_db_query(
  "select sum(anzahlTotal) as anzahlTotal, sum(anzahlExecutive) as anzahlExecutive, sum(anzahlTotal)-sum(anzahlExecutive) as anzahlSimple
from
(
select count(*) as anzahlTotal, 0 as anzahlExecutive
from zutrittsberechtigung zb
inner join mandat ma on ma.zutrittsberechtigung_id = zb.id
where zb.parlamentarier_id = :id " .
      _lobbywatch_data_filter_unpublished_SQL('ib') . "
union
select 0 as anzahlTotal, count(*) as anzahlExecutive
from zutrittsberechtigung zb
inner join mandat ma on ma.zutrittsberechtigung_id = zb.id
where zb.parlamentarier_id = :id
and ma.art in ('vorstand', 'geschaeftsfuehrend')
" . _lobbywatch_data_filter_unpublished_SQL('ib') . "
) t", array('id' => $id))['0'];
}

function _lobbywatch_data_db_query($sql, $params = array()) {
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
