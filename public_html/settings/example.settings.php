<?php

// Copy example.settings.php to settings.php and
// configure your settings.

require_once dirname(__FILE__) . "/maintenance_mode.php";

$debug = false;

$db_connection = array (
    'server' => 'localhost',
    'port' => '3306',
    'username' => '',
    'password' => '',
    'database' => '',
);

$users = array (
    'otto' => '',
    'roland' => '',
    'rebecca' => '',
    'thomas' => '',
    'bane' => '',
    'admin' => '',
);

if (@$maintenance_mode === true) {
  include dirname(__FILE__) . "/../common/maintenance.php";
  exit(0);
}
