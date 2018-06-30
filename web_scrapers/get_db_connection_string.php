<?php
require_once dirname(__FILE__) . '/../public_html/settings/settings.php';

$options = getopt('h', array('db:','help'));

if (isset($options['h']) || isset($options['help'])) {
  print("Parameters:
--db=db_name      Name of DB to use
-h, --help        This help
");
  exit(0);
}

if (isset($options['db'])) {
  $db_name = $options['db'];
} else {
  $db_name = null;
}

if ($db_name != null && $db_name != 'DEFAULT') {
  if (empty($db_connections[$db_name])) {
    print("ERROR: DB connection '$db_name' not in settings.php\n");
    exit(1);
  }
  $db_con = $db_connections[$db_name];
} else {
  $db_con = $db_connection;
}

print("${db_con["reader_username"]}:${db_con["reader_password"]}:${db_con["server"]}:${db_con["database"]}:${db_con["port"]}");
