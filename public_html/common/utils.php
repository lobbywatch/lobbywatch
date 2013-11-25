<?php

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

function df($msg) {
  global $debug;
  if ($debug !== true)
    return;
  if (is_array($msg) || is_object($msg)) {
    $msg = print_r($msg, true);
  }
  file_put_contents(dirname(__FILE__) . "/../../logs/bearbeitung.log", date('c') . ': ' . $msg, FILE_APPEND);
}
function dfXXX($msg) {
  // Disabled debug comment: do nothing
}

