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
  $public_files_dir_rel = "files";
  $public_files_dir_abs = $public_files_dir = "/home/lobbywatch/public_html/$public_files_dir_rel";
  $rel_files_url = "/$public_files_dir_rel";
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
  $public_files_dir_rel = "test/files";
  $public_files_dir_abs = $public_files_dir = "/home/lobbywatch/public_html/test/$public_files_dir_rel";
  $rel_files_url = "/$public_files_dir_rel";
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

session_set_cookie_params(3600 * 24 * 30, '/bearbeitung/');
ini_set('session.gc_maxlifetime', 200000);
ini_set('session.cookie_httponly', true);

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
