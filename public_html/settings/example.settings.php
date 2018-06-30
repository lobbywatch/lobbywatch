<?php

// Copy example.settings.php to settings.php and
// configure your settings.

require_once dirname(__FILE__) . "/maintenance_mode.php";

global $public_files_dir_abs;
global $db_connection;
global $mail_connection;
global $zefix_ws_login;
global $rel_files_url;
global $private_files_dir;
global $env_dir;
global $env;
global $debug;

global $drupal_running;
global $lobbywatch_mode;

if (isset($drupal_running) && $drupal_running) {
  $stage = $lobbywatch_mode === 'PROD';
} else {
  $stage = false;
}

$debug = false;

$zefix_ws_login = array(
  'username' => 'xyz@example.com', // Zefix WS login username
  'password' => 'xyz', // Zefix WS login password
  'keys' => array('abc',), // Allowed key for accessing data interface REST webservice
);

$db_connections = [
  'lobbywatch' => [
      'server' => 'localhost',
      'port' => '3306',
      'database' => 'lobbywatch',
      'username' => 'lobbywatch',
      'password' => '',
      'reader_username' => 'lw_reader',
      'reader_password' => '',
      'adv_username' => 'lobbywatch_adv',
      'adv_password' => '',
  ],
  'lobbywatchtest' => [
      'server' => 'localhost',
      'port' => '3306',
      'database' => 'lobbywatchtest',
      'username' => 'lobbywatch',
      'password' => '',
      'reader_username' => 'lw_reader',
      'reader_password' => '',
      'adv_username' => 'lobbywatch_adv',
      'adv_password' => '',
  ],
];


if ($stage) {
  $env = "STAGE";

  $env_dir = "/";
  // $env_dir = "/test/";
  $public_files_dir_rel = "files";
  $public_files_dir_abs = $public_files_dir = "/home/lobbywatch/public_html/$public_files_dir_rel";
  $rel_files_url = "/$public_files_dir_rel";
  $private_files_dir = "/home/lobbywatch/private_files/lobbywatch_db_files";

  $db_connection = $db_connections[0];
} else {
  $env = "DEV";

  $env_dir = "/";
  // $env_dir = "/test/";
  $public_files_dir_rel = "test/files";
  $public_files_dir_abs = $public_files_dir = "/home/lobbywatch/public_html/test/$public_files_dir_rel";
  $rel_files_url = "/$public_files_dir_rel";
  $private_files_dir = "/home/lobbywatch/private_files/lobbywatch_db_files/test";

  $db_connection = $db_connections[1];
}

    $mail_connection = [
        'host' => 'mail.cyon.ch',
        'username' => '',
        'password' => '',
        'port' => 587,
        'secure' => 'tls',
    ];

if (!isset($drupal_running) || !$drupal_running) {
  session_set_cookie_params(3600 * 24 * 30, '/bearbeitung/');
  ini_set('session.gc_maxlifetime', 3600 * 24 * 30);
  ini_set('session.cookie_httponly', true);
  session_name('lwdb_sess');
  session_save_path('/path/for/sessions');
//   phpinfo();

//   $users = array (
//       'otto' => '',
//       'roland' => '',
//       'rebecca' => '',
//       'thomas' => '',
//       'bane' => '',
//       'admin' => '',
//       'demo' => '',
//   );

  if (@$maintenance_mode === true && preg_match('/(auswertung|info.php$)/', $_SERVER['REQUEST_URI']) == 0) {
    include dirname(__FILE__) . "/../common/maintenance.php";
    exit(0);
  }
}
