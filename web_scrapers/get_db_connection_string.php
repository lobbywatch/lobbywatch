<?php
require_once dirname(__FILE__) . '/../public_html/settings/settings.php';

print("${db_connection["reader_username"]}:${db_connection["reader_password"]}:${db_connection["server"]}:${db_connection["database"]}:${db_connection["port"]}");
