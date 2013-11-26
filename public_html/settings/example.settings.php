<?php

// Copy example.settings.php to settings.php and
// configure your settings.

require_once dirname(__FILE__) . "/maintenance_mode.php";

$debug = false;

$env = "dev";
$env_dir = "/";

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
    'demo' => '',
);

if (@$maintenance_mode === true) {
  include dirname(__FILE__) . "/../common/maintenance.php";
  exit(0);
}
