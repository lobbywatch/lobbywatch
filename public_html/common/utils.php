<?php

function dt($msg) {
  if ($debug !== true)
    return;
  if (is_array($msg)) {
    $msg = print_r($msg, true);
  }
  print ("<p style='color:red;'>$msg</p>") ;
}
function dtXXX($msg) {
  // Disabled debug comment: do nothing
}
