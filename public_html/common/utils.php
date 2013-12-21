<?php

include_once dirname(__FILE__) . '/build_date.php';
include_once dirname(__FILE__) . '/deploy_date.php';
include_once dirname(__FILE__) . '/version.php';

//Ref: http://stackoverflow.com/questions/834303/php-startswith-and-endswith-functions
function startsWith($haystack, $needle)
{
  return $needle === "" || strpos($haystack, $needle) === 0;
}
//Ref: http://stackoverflow.com/questions/834303/php-startswith-and-endswith-functions
function endsWith($haystack, $needle)
{
  return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}

// Logging

function dt($msg) {
  global $debug;
  if ($debug !== true)
    return;
  if (is_array($msg) || is_object($msg)) {
    $msg = print_r($msg, true);
  }
  print ("<p style='color:red;'>$msg</p>") ;
}
function dtXXX($msg) {
  // Disabled debug comment: do nothing
}

function dl($msg) {
  global $debug;
  if ($debug !== true)
    return;
  if (is_array($msg) || is_object($msg)) {
    $msg = print_r($msg, true);
  }
  error_log($msg) ;
}
function dlXXX($msg) {
  // Disabled debug comment: do nothing
}

function df_clean() {
  global $debug;
  if ($debug !== true)
    return;
  $msg = 'Start logging';
  file_put_contents(dirname(__FILE__) . "/../../logs/bearbeitung.log", date('c') . ': ' . $msg . "\n"/*, FILE_APPEND*/);
}

function df($msg) {
  global $debug;
  if ($debug !== true)
    return;
  if (is_array($msg) || is_object($msg)) {
    $msg = print_r($msg, true);
  }
  file_put_contents(dirname(__FILE__) . "/../../logs/bearbeitung.log", date('c') . ': ' . $msg . "\n", FILE_APPEND);
}
function dfXXX($msg) {
  // Disabled debug comment: do nothing
}

function dc($msg) {
  if ($debug !== true)
    return;
  if (is_array($msg)) {
    $msg = print_r($msg, true);
  }
  print ("<!-- $msg -->") ;
}
function dcXXX($msg) {
  // Disabled debug comment: do nothing
}

//http://stackoverflow.com/questions/2476876/how-do-i-convert-an-object-to-an-array

// http://stackoverflow.com/questions/11648396/php-search-for-a-value-in-an-array-which-contains-objects
function search_objects($objects, $key, $value) {
  $return = array();
  foreach ($objects as $object) {
    $objVars = get_object_vars($object);
    if (isset($objVars[$key]) && $objVars[$key] == $value) {
      $return[] = $object;
    }
  }
  return $return;
}

function is_column_present($columns, $name) {
  foreach ($columns as $column) {
    if ($column->GetFieldName() == $name)
      return true;
  }

  return false;
}