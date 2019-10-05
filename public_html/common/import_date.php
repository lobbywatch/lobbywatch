<?php
require_once dirname(__FILE__) . "/../settings/settings.php";
require_once dirname(__FILE__) . "/../common/utils.php";

try {
  $import_date_wsparlamentch = getSettingValue('ws.parlament.ch_last_import_date');
  $import_date_wsparlamentch_short = substr($import_date_wsparlamentch, 0, 10);
} catch (PDOException $e) {
  $import_date_wsparlamentch = null;
  $import_date_wsparlamentch_short = null;
}
