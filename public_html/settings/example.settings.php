<?php

// Copy example.settings.php to settings.php and
// configure your settings.

require_once dirname(__FILE__) . "/maintenance_mode.php";

$stage = false;

$debug = false;

if ($stage) {
  $env = "STAGE";

  $env_dir = "/";
  // $env_dir = "/test/";
  $public_files_dir = "/home/lobbywatch/public_html/files";
  $private_files_dir = "/home/lobbywatch/private_files/lobbywatch_db_files";

  $db_connection = array (
      'server' => 'localhost',
      'port' => '3306',
      'database' => 'lobbywatch',
      'username' => '',
      'password' => '',
      'reader_username' => '',
      'reader_password' => '',
  );
} else {
  $env = "DEV";

  $env_dir = "/";
  // $env_dir = "/test/";
  $public_files_dir = "/home/lobbywatch/public_html/test/files";
  $private_files_dir = "/home/lobbywatch/private_files/lobbywatch_db_files/test";

  $db_connection = array (
      'server' => 'localhost',
      'port' => '3306',
      'database' => 'lobbywatchtest',
      'username' => '',
      'password' => '',
      'reader_username' => '',
      'reader_password' => '',
  );

}

session_set_cookie_params(3600 * 24 * 14, '/bearbeitung/');

$users = array (
    'otto' => '',
    'roland' => '',
    'rebecca' => '',
    'thomas' => '',
    'bane' => '',
    'admin' => '',
    'demo' => '',
);

if (@$maintenance_mode === true && preg_match('/(auswertung|info.php$)/', $_SERVER['REQUEST_URI']) == 0) {
  include dirname(__FILE__) . "/../common/maintenance.php";
  exit(0);
}
