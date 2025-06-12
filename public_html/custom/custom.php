<?php

// MIGR Filter only active (remove afterburner based date bis filtering), parlamentarier, interessenbindung
// MIGR Refactor code
// MIGR Restore feature: Keyboard shortcuts (Ctrl-S)
// MIGR Replace field_label.tpl with custom version
// MIGR Redo each template modif of former templates
// MIGR Security more standard
// MIGR hints more standard
// MIGR standarize event method names
// MIGR use normal event for on page before
// MIGR Enable feature: On-the-fly adding
// MIGR Enable feature: Multi column header
// MIGR Enable feature: Message showing after record action
// MIGR Enable feature: New controls (autocomplete, multilevel autocomplete)
// MIGR Improve layout, condesed, less columns
// MIGR Improve header short and detailed table description
// MIGR Clean code
// MIGR Use some charts
// MIGR Order columns in tables in last modified order
// MIGR Restore feature: add favicon to forms
// MIGR Intercept pageInfo()
// MIGR Increase gint popover window width
// MIGR forms partition omit ", .

timer_start('page_build');

$old_bearbeitung = preg_match("/old_bearbeitung/", $_SERVER['REQUEST_URI']);

require_once dirname(__FILE__) . "/../settings/settings.php";
require_once dirname(__FILE__) . "/../common/utils.php";
include_once dirname(__FILE__) . '/../common/import_date.php';
include_once dirname(__FILE__) . '/build.php';
include_once dirname(__FILE__) . '/deploy.php';
include_once dirname(__FILE__) . '/version.php';
include_once dirname(__FILE__) . '/generator_version.php';
include_once dirname(__FILE__) . '/hash_css_custom.php';
include_once dirname(__FILE__) . '/hash_css_main.php';
include_once dirname(__FILE__) . '/hash_js_main_bundle.php';
require_once dirname(__FILE__) . '/../bearbeitung/components/common.php';
include_once dirname(__FILE__) . '/../bearbeitung/components/http_handler/abstract_http_handler.php';

global $lobbywatch_is_forms;
$lobbywatch_is_forms = true;

$edit_header_message = '';
$edit_general_hint = '';

lobbywatch_language_initialize('de');

function setupRSS($page, $dataset) {
  $title = 'MIGR';
  $table = str_replace ( '`', '', $dataset->GetName () );
  switch ($table) {
    case 'parlamentarier' :
      $rss_title = 'Parlamentarier %vorname% %nachname% changed by %updated_visa% at %updated_date%';
      $rss_body = 'Beruf: %beruf%<br>Kanton: %kanton%';
      break;
    case 'interessenbindung' :
      $rss_title = 'Interessenbindung %beschreibung% changed by %updated_visa% at %updated_date%';
      $rss_body = '';
      break;
    case 'mandat' :
      $rss_title = 'Mandat %beschreibung% changed by %updated_visa% at %updated_date%';
      $rss_body = '';
      break;
    case 'interessengruppe' :
      $rss_title = 'Interessengruppe %name% changed by %updated_visa% at %updated_date%';
      $rss_body = '%beschreibung%';
      break;
    case 'branche' :
      $rss_title = 'Branche %name% changed by %updated_visa% at %updated_date%';
      $rss_body = '%beschreibung%<br>%angaben%';
      break;
    case 'partei' :
      $rss_title = 'Partei %abkuerzung% changed by %updated_visa% at %updated_date%';
      $rss_body = 'Name: %name%<br>Gründung: %gruendung%<br>Position: %position%';
      break;
    case 'person' :
      $rss_title = 'Person %vorname% %nachname% changed by %updated_visa% at %updated_date%';
      $rss_body = 'Funktion: %funktion%';
      break;
    case 'organisation' :
      $rss_title = 'Organisation %name% changed by %updated_visa% at %updated_date%';
      $rss_body = '%beschreibung%<br>Typ: %typ%<br>Vernehmlassung: %vernehmlassung%';
      break;
    case 'organisation_beziehung' :
      $rss_title = 'Organisation_Beziehung ID %id% changed by %updated_visa% at %updated_date%';
      $rss_body = '%beziehungsart%';
      break;
      case 'kommission' :
      $rss_title = 'Kommission %name% (%abkuerzung%) changed by %updated_visa% at %updated_date%';
      $rss_body = '%sachbereiche%';
      break;
    case 'in_kommission' :
      $rss_title = 'In_Kommission ID %id% changed by %updated_visa% at %updated_date%';
      $rss_body = '';
      $no_body_id = true;
      break;
    default :
      $rss_title = "$title ID %id% changed by %updated_visa% at %updated_date%";
      $rss_body = '';
      $no_body_id = true;
  }

  $rss_body .= "<br>%notizen%";

  if (!isset($no_body_id) || $no_body_id === false)
    $rss_body .= "<br>$title ID %id%<br>Updated by %updated_visa% at %updated_date%";

  $base_url = "http://$_SERVER[HTTP_HOST]";
  $generator = new DatasetRssGenerator ( $dataset, $title . ' RSS', $base_url, 'Änderungen der Lobbywatch-Datenbank als RSS Feed', $rss_title, $table . ' at %id%', $rss_body );
  $generator->SetItemPublicationDateFieldName ( 'updated_date' );
  $generator->SetOrderByFieldName ( 'updated_date' );
  $generator->SetOrderType ( otDescending );

  return $generator;
}

function convert_nop($text) {
  return $text;
}

function convert_utf8($text) {
  return ConvertTextToEncoding($text, GetAnsiEncoding(), 'UTF-8' );
}

function convert_ansi($text) {
  return ConvertTextToEncoding($text, 'UTF-8', GetAnsiEncoding());
}

// Comments on form fields
function fillHintParams(Page $page, &$params) {
  // Fill info hints
  $hints = [];
  $minimal_fields = [];
  $fr_field_names = [];
  $fr_field_descriptions = [];
  $form_translations = [];
  $table_name = getTableName($page);
  if ($params == null) {
    $params = [];
  }
  foreach($page->GetGrid()->GetViewColumns() as $column) {
    $raw_name = $column->GetName();
    $name = preg_replace('/^(.*?_id).*/', '\1', $raw_name);
    $name = preg_replace('/_anzeige_name$/', '', $name);
    $hint_de = htmlspecialchars($column->GetDescription());

    $minimal_fields[$name] = is_minimal_field($table_name, $name);

    if ($column->IsDataColumn()) {
      $field_translation_key = "$table_name.$name";
      $field_name_de = $column->GetCaption();
      $field_name_fr = lobbywatch_translate($field_translation_key, null, 'fr', 'forms', $field_translation_key);
      if ($field_name_fr == $field_translation_key || $field_name_fr == '') {
        $field_name_fr = $field_name_de;
      }
      $fr_field_names[$name] = $field_name_fr;

      $field_hint_translation_key = "$table_name.$name.hint";
      $field_hint_de = $column->GetDescription();
      $field_hint_fr = lobbywatch_translate($field_hint_translation_key, null, 'fr', 'forms', $field_hint_translation_key);
      if ($field_hint_fr == $field_hint_translation_key) {
        $field_hint_fr = '';
      }
      $hint_fr = htmlspecialchars($field_hint_fr);
      $fr_field_descriptions[$name] = "$table_name.$name.hint";

      $hints[$name] = ($hint_fr != '' ? "<p>$hint_fr</p><hr>" : '') . "<p>$hint_de</p>" . "<hr><p>DB: $table_name.$name</p>";

      $date = date('d.m.Y');
      $form_translations[] = "$field_translation_key\t\t$date\t\tforms\t\t$field_translation_key\t$field_name_de\t" . ($field_name_fr != $field_name_de ? $field_name_fr : '');
      $form_translations[] = "$field_hint_translation_key\t\t$date\t\tforms\t\t$field_hint_translation_key\t$field_hint_de\t$field_hint_fr";
    }
  }
  switch ($table_name) {
    case 'parlamentarier':
      $imported_fields = array('nachname' => true, 'vorname' => true, 'rat_id' => true, 'kanton_id' => true, 'kommissionen' => true, 'partei_id' => true, 'fraktion_id' => true, 'im_rat_seit' => true, 'im_rat_bis' => true, 'beruf' => true, 'geschlecht' => true, 'geburtstag' => true, 'titel' => true, 'aemter' => true, 'weitere_aemter' => true, 'zivilstand' => true, 'anzahl_kinder' => true, 'militaerischer_grad_id' => true, 'email' => true, 'homepage' => true, 'homepage_2' => true, 'parlament_biografie_id' => true, 'parlament_number' => true, 'parlament_interessenbindungen' => true, 'arbeitssprache' => true, 'sprache' => true, 'adresse_plz' => true, 'adresse_ort' => true, 'telephon_1' => true, 'telephon_2' => true, 'kleinbild' => true, 'fraktionsfunktion' => true, 'adresse_firma' => true, 'adresse_strasse' => true, 'ratsunterbruch_von' => true, 'ratsunterbruch_bis' => true, 'ratswechsel' => true, );
      break;
    case 'person':
      $imported_fields = array('nachname' => true, 'vorname' => true, 'zweiter_vorname' => true, );
      break;
    case 'organisation':
      $imported_fields = array('name_de' => true, 'name_fr' => true, 'name_it' => true, 'sekretariat' => true, 'adresse_strasse' => true, 'adresse_zusatz' => true, 'adresse_plz' => true, 'ort' => true, 'homepage' => true, 'alias_namen_de' => true, );
      break;
    case 'zutrittsberechtigung':
      $imported_fields = array('funktion' => true, 'parlament_id' => true, 'person_id' => true, 'bis' => true, 'von' => true, );
      break;
    case 'kommission':
      $imported_fields = array('abkuerzung' => true, 'abkuerzung_fr' => true, 'name' => true, 'name_fr' => true, 'rat_id' => true, 'typ' => true, 'parlament_id' => true, 'parlament_committee_number' => true, 'parlament_subcommittee_number' => true, 'parlament_type_code' => true, 'von' => true, 'bis' => true,);
      break;
    case 'in_kommission':
      $imported_fields = array('parlament_committee_function' => true, 'parlament_committee_function_name' => true, 'parlamentarier_id' => true, 'kommission_id' => true, 'von' => true, 'bis' => true, 'funktion' => true,);
      break;
    default :
    $imported_fields = [];
  }

  $params = array_merge($params, array('Hints' => $hints, 'MinimalFields' => $minimal_fields, 'FrFieldNames' => $fr_field_names, 'FrFieldDescriptions' => $fr_field_descriptions, 'ImportedFields' => $imported_fields));
}

function getTableName(Page $page) {
  return preg_replace('/`/', '', $page->GetDataset()->GetName());
}
function is_minimal_field($table, $field) {
  switch ($table) {
    case 'parlamentarier':
      switch ($field) {
        case 'email':
        case 'geburtstag':
        case 'geschlecht':
        case 'kleinbild':
        case 'parlament_biografie_id':
          return true;
        default:
          return false;
      }
    case 'person':
      switch ($field) {
        case 'email':
        case 'geburtstag':
        case 'geschlecht':
        case 'beruf':
          return true;
        default:
          return false;
      }
    case 'partei':
      switch ($field) {
        case 'abkuerzung_fr':
        case 'name':
        case 'name_fr':
          return true;
        default:
          return false;
      }
    case 'fraktion':
      switch ($field) {
        case 'name_fr':
          return true;
        default:
          return false;
      }
    case 'organisation':
      switch ($field) {
        case 'interessengruppe_id':
          return true;
        default:
          return false;
      }
    case 'interessengruppe':
      switch ($field) {
        case 'name_fr':
          return true;
        default:
          return false;
      }
    case 'branche':
      switch ($field) {
        case 'name_fr':
        case 'kommission_id':
          return true;
        default:
          return false;
      }
    case 'kommission':
      switch ($field) {
        case 'abkuerzung_fr':
        case 'name_fr':
        case 'parlament_url':
        case 'anzahl_nationalraete':
        case 'anzahl_staenderaete':
          return true;
        default:
          return false;
      }
    default:
      return false;
  }
}

/*

 */
function before_render(Page $page) {

  custom_set_db_session_parameters($page);

  // Add custom headers
  $page->OnCustomHTMLHeader->AddListener('add_custom_header');
  write_user_last_access($page);

  applyDefaultFilters($page);
}

function custom_set_db_session_parameters($page) {
  $connection = getDBConnection();

  $connection->ExecSQL("SET SESSION wait_timeout=120;" .
    "SET sql_mode='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';");
}

function write_user_last_access($page) {
  $connection = getDBConnection();

  // Do not update access time more than once per 180 seconds.
  // Inspired by Drupal 7 session.inc _drupal_session_write()
  if (($id = GetApplication()->GetCurrentUserId()) && ($request_time = $_SERVER['REQUEST_TIME']) - $connection->ExecScalarSQL("SELECT UNIX_TIMESTAMP(last_access) FROM user WHERE id= $id;") > 180) {
    $connection->ExecSQL("UPDATE `user` SET `last_access`= CURRENT_TIMESTAMP WHERE `id` = $id;");
  }

}

function applyDefaultFilters(Page $page) {
}

function add_custom_header(&$page, &$result) {
$result = <<<'EOD'
  <link rel="shortcut icon" href="/favicon.png" type="image/png" />

EOD;
}

function parlamentarier_update_photo_metadata($page, &$rowData, &$cancel, &$message, $tableName)
{
  if (isset($rowData['photo'])) {
    $file = $rowData['photo'];
  } else {
    return;
  }

  // A photo filename ending with / means there was no photo
  if ($file !== null && !utils_endsWith($file, '/')) {
    $path_parts = pathinfo($file);

    $finfo_mime = new finfo(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension

    $rowData['photo_dateiname'] = $path_parts['filename'];
    if (isset($path_parts['extension'])) {
      $rowData['photo_dateierweiterung'] = $path_parts['extension'];
    }
    $rowData['photo_dateiname_voll'] = $path_parts['basename'];
    $rowData['photo_mime_type'] = $finfo_mime->file($file);

    // Kleinbild
    $file = $rowData['kleinbild'];

    $path_parts = pathinfo($file);

    // Remove path, we want it relative
    // $rowData['kleinbild'] = $path_parts['basename']; // We use ws_parlament_fetcher for images, do not change it here
  } else {
    $rowData['photo'] = null;
    $rowData['photo_dateiname'] = null;
    $rowData['photo_dateierweiterung'] = null;
    $rowData['photo_dateiname_voll'] = null;
    $rowData['photo_mime_type'] = null;
    // $rowData['kleinbild'] = null; // We use ws_parlament_fetcher for images, do not change it here
  }
}

/**
 * Used on update.
 *
 * @param unknown $page
 * @param unknown $rowData
 * @param unknown $cancel
 * @param unknown $message
 * @param unknown $tableName
 * @return void|boolean
 */
function parlamentarier_remove_old_photo($page, &$rowData, &$cancel, &$message, $tableName)
{
  if (isset($rowData['photo']) && isset($rowData['id'])) {
    $file = $rowData['photo'];
    $id = $rowData['id'];
  } else {
    return;
  }

  // prevent SQL injection
  if (!is_numeric($id)) {
    return false;
  }

  $values = [];
  $page->GetConnection()->ExecQueryToArray("SELECT `id`, `photo` FROM $tableName WHERE `id`=$id", $values);
  if (count($values) > 0 ) {
    $old_file = $values[0]['photo'];
  } else {
    return false;
  }
  // A photo filename ending with / means there was no photo
  if ($old_file !== null && $old_file !== $file) {
    if (FileUtils::FileExists($old_file))
      $result = FileUtils::RemoveFile($old_file);
    $message = "Deleted old photo $old_file";
  }
}

/**
 * Used on delete.
 *
 * @param unknown $page
 * @param unknown $rowData
 * @param unknown $cancel
 * @param unknown $message
 * @param unknown $tableName
 */
function parlamentarier_remove_photo($page, &$rowData, &$cancel, &$message, $tableName) {
  $target = $rowData['photo'];
  $result = -2;
  if (FileUtils::FileExists($target))
    $result = FileUtils::RemoveFile($target);

  $message = "Delete file '$target'. Result $result";
}

function symbol_update_metadata($page, &$rowData, &$cancel, &$message, $tableName)
{
  if (isset($rowData['symbol_abs'])) {
    $file = $rowData['symbol_abs'];
  } else {
    return;
  }

  // A photo filename ending with / means there was no photo
  if ($file !== null && !utils_endsWith($file, '/')) {
    $path_parts = pathinfo($file);

    $finfo_mime = new finfo(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension

    $rowData['symbol_dateiname_wo_ext'] = $path_parts['filename'];
    if (isset($path_parts['extension'])) {
      $rowData['symbol_dateierweiterung'] = $path_parts['extension'];
    }
    $rowData['symbol_dateiname'] = $path_parts['basename'];
    $rowData['symbol_mime_type'] = $finfo_mime->file($file);
    $rowData['symbol_rel'] = preg_replace('/^.*?files\//', '', $file);

    // Kleinbild
    $file = $rowData['symbol_klein_rel'];

    $path_parts = pathinfo($file);

    // Remove path to files, we want it relative
    $rowData['symbol_klein_rel'] = preg_replace('/^.*?files\//', '', $rowData['symbol_klein_rel']);
  } else {
    $rowData['symbol_abs'] = null;
    $rowData['symbol_rel'] = null;
    $rowData['symbol_dateiname_wo_ext'] = null;
    $rowData['symbol_dateierweiterung'] = null;
    $rowData['symbol_dateiname'] = null;
    $rowData['symbol_mime_type'] = null;
    $rowData['symbol_klein_rel'] = null;
  }
}

function symbol_remove_old($page, &$rowData, &$cancel, &$message, $tableName)
{
  if (isset($rowData['symbol_rel']) && isset($rowData['id'])) {
    $file = $rowData['symbol_rel'];
    $small_file = $rowData['symbol_klein_rel'];
    $id = $rowData['id'];
  } else {
    return;
  }

  // prevent SQL injection
  if (!is_numeric($id)) {
    return false;
  }

  $values = [];
  $page->GetConnection()->ExecQueryToArray("SELECT `id`, `symbol_rel` FROM $tableName WHERE `id`=$id", $values);
  if (count($values) > 0 ) {
    $old_file = $values[0]['symbol_rel'];
    $small_old_file= !empty($values[0]['symbol_klein_rel']) ? $values[0]['symbol_klein_rel'] : null;
  } else {
    return false;
  }
  // A symbol filename ending with / means there was no symbol
  if ($old_file !== null && $old_file !== $file) {
    $old_file_abs = $public_files_dir_abs . '/' . $old_file;
    if (FileUtils::FileExists($old_file_abs))
      $result = FileUtils::RemoveFile($old_file_abs);
    $message = "Deleted old symbol $old_file. ";
  }

  // A symbol filename ending with / means there was no symbol
  if ($small_old_file !== null && $small_old_file !== $small_file) {
    $small_old_file_abs = $public_files_dir_abs . '/' . $small_old_file;
    if (FileUtils::FileExists($small_old_file_abs))
      $result = FileUtils::RemoveFile($small_old_file_abs);
    $message .= "\nDeleted old small symbol $small_old_file. ";
  }
}

/**
 * Used on delete.
 */
function symbol_remove($page, &$rowData, &$cancel, &$message, $tableName) {
  $target = $rowData['symbol_rel'];
  $result = -2;
  $target_abs = $public_files_dir_abs . '/' . $target;
  if (FileUtils::FileExists($target_abs))
    $result = FileUtils::RemoveFile($target_abs);

  $message = "Delete file '$target_abs'. Result $result";
}

function datei_anhang_delete($page, &$rowData, &$cancel, &$message, $tableName) {
  $target = $rowData['datei'];
  $result = -2;
  if (FileUtils::FileExists($target))
    $result = FileUtils::RemoveFile($target);

  $message = "Delete file '$target'. Result $result";
}

function datei_anhang_insert($page, &$rowData, &$cancel, &$message, $tableName) {
  $file = $rowData['datei'];

  $path_parts = pathinfo($file);

  $finfo_mime = new finfo(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
  $finfo_encoding = new finfo(FILEINFO_MIME_ENCODING); // return mime encoding

  $rowData['dateiname'] = $path_parts['filename'];
  if (isset($path_parts['extension'])) {
    $rowData['dateierweiterung'] = $path_parts['extension'];
  }
  $rowData['dateiname_voll'] = $path_parts['basename'];
  $rowData['mime_type'] = $finfo_mime->file($file);
  $rowData['encoding'] = $finfo_encoding->file($file);
}

function parlamentarier_check_imRatBis($page, &$rowData, &$cancel, &$message, $tableName) {
  if (!empty($rowData['im_rat_seit']) && !empty($rowData['im_rat_bis'])) {
    check_von_bis_date($rowData['im_rat_seit'], $rowData['im_rat_bis'], $cancel, $message);
  }
}

function check_bis_date($page, &$rowData, &$cancel, &$message, $tableName) {
  if (!empty($rowData['von']) && !empty($rowData['bis'])) {
    check_von_bis_date($rowData['von'], $rowData['bis'], $cancel, $message);
  }
}

function check_von_bis_date($von, $bis, &$cancel, &$message) {
  if ($von != null && $bis != null && $von instanceof SMDateTime && $bis instanceof SMDateTime && $von->GetTimestamp() >= $bis->GetTimestamp()) {
    $cancel = true;
    $message = 'Bis-Datum darf nicht kleiner als Von-Datum sein.<br>Von: ' . $von->ToString('d.m.Y') . ' > Bis: ' . $bis->ToString('d.m.Y');
  } else if ($von != null && $bis != null) {
    $vonUnix = strtotime($von);
    $bisUnix = strtotime($bis);
    if ($vonUnix >= $bisUnix) {
      $cancel = true;
      $message = "Operation abgebrochen.<br>Bis-Datum darf nicht kleiner als Von-Datum sein.<br>Von: $von > Bis: $bis";
    }
  }
}

function check_organisation_interessengruppe_order($page, &$rowData, &$cancel, &$message, $tableName)
{

  if ((isset($rowData['interessengruppe2_id']) && !isset($rowData['interessengruppe_id'])) ||
    (isset($rowData['interessengruppe3_id']) && !isset($rowData['interessengruppe2_id']))) {
    $cancel = true;
    $message = 'Lücke in den Interessengruppen. Bitte zuerst Interessengruppe 1, dann 2 und 3 einfüllen.';
  }
}


function clean_fields(/*$page,*/ &$rowData /*, &$cancel, &$message, $tableName*/)
{
  // foreach($rowData as $name => &$value) Does not work!
  foreach($rowData as $name => $value) {
    if (is_string($value)) {
      $rowData[$name] = clean_str($value);
    }
  }
}

function clean_non_ascii($str) {
  return preg_replace('/[^\w\d_-]*/','', $str);
}

/** Copied from DownloadHTTPHandler*/
// MIGR Replace class PrivateFileDownloadHTTPHandler extends HTTPHandler
class PrivateFileDownloadHTTPHandler extends AbstractHTTPHandler
{
  private $dataset;
  private $fieldName;
  private $contentType;
  private $downloadFileName;
  private $forceDownload = true;

  public function __construct($dataset, $fieldName, $name, $contentType, $downloadFileName, $forceDownload = true)
  {
    parent::__construct($name);
    $this->dataset = $dataset;
    $this->fieldName = $fieldName;
    $this->contentType = $contentType;
    $this->downloadFileName = $downloadFileName;
    $this->forceDownload = $forceDownload;
  }

  public function Render(Renderer $renderer)
  {
    $primaryKeyValues = [];
    ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);

    $this->dataset->SetSingleRecordState($primaryKeyValues);
    $this->dataset->Open();
    $private_file = '';
    $private_file_name = '';
    if ($this->dataset->Next()) {
      $private_file = $this->dataset->GetFieldValueByName($this->fieldName);
      $private_file_name = basename($private_file);
    } else {
      $this->dataset->Close();
      return false;
    }
    $this->dataset->Close();

    header('Content-type: ' . FormatDatasetFieldsTemplate($this->dataset, $this->contentType));
    if ($this->forceDownload)
      header('Content-Disposition: attachment; filename="' . FormatDatasetFieldsTemplate($this->dataset, $private_file_name) . '"');
//     http://ch1.php.net/file_get_contents

//     https://api.drupal.org/api/drupal/includes!file.inc/function/file_transfer/7

    // http://www.php.net/manual/en/wrappers.php
    $uri = 'file://' . $private_file;

    // Transfer file in 1024 byte chunks to save memory usage.
    if (/*$scheme && file_stream_wrapper_valid_scheme($scheme) &&*/ $fd = fopen($uri, 'rb')) {
      while (!feof($fd)) {
        print fread($fd, 1024);
      }
      fclose($fd);
    }
    else {
      // error handling
    }
  }
}

function DisplayTemplateSimple($TemplateName, $InputObjects, $InputValues, $display = true) {
  $captions = Captions::getInstance('UTF-8');
  $smarty = new Smarty();
  $smarty->template_dir = '/components/templates';
  foreach($InputObjects as $ObjectName => &$Object)
    $smarty->assign_by_ref($ObjectName, $Object);
  $smarty->assign_by_ref('Captions', $captions);

  foreach($InputValues as $ValueName => $Value)
    $smarty->assign($ValueName, $Value);

  $rendered = $smarty->fetch($TemplateName, null, null, $display);
}

function getFullUsername($user) {
  switch(strtolower($user)) {
  	case 'otto' :
  	  return 'Otto Hostettler';
  	case 'roland' :
  	  return 'Roland Kurmann';
    case 'thomas' :
  	  return 'Thomas Angeli';
    case 'rebecca' :
      return 'Rebecca Wyss';
    case 'graf' :
  	  return 'Céline Graf';
    case 'samuel' :
  	  return 'Samuel Meier';
    case 'fabian' :
  	  return 'Fabian Strässle';
    case 'alice' :
  	  return 'Alice Kohli';
    default:
  	  return '';
  }
}

function getTimestamp($date_str) {
  return SMDateTime::Parse($date_str, 'Y-m-d H:i:s')->GetTimestamp();
}

/**
 * Set background-color if farbcode is set.
 */
function customDrawRowFarbcode($table_name, $rowData, &$rowCellStyles, &$rowStyles, &$rowClasses = '', &$cellClasses = []) {
  if (isset($rowData['farbcode'])) {
    $rowCellStyles['farbcode'] = 'background-color: ' . $rowData['farbcode'];
  }
}

/**
 * Changes styles of cells.
 *
 * @param unknown $rowData eingabe_abgeschlossen_datum, kontrolliert_datum, freigabe_datum, autorisierung_verschickt_datum, autorisiert_datum, kontrolliert_visa, eingabe_abgeschlossen_visa, im_rat_bis, sitzplatz, email, geburtstag, im_rat_bis, geschlecht, kleinbild, parlament_biografie_id, beruf, farbcode
 */
function customDrawRow($table_name, $rowData, &$rowCellStyles, &$rowStyles, &$rowClasses = '', &$cellClasses = []) {

// MIGR customDrawRow()
  customDrawRowFarbcode($table_name, $rowData, $rowCellStyles, $rowStyles, $rowClasses, $cellClasses);
  drawWorkflowStyles($table_name, $rowData, $rowCellStyles, $rowStyles, $rowClasses, $cellClasses);
}

/**
 * Changes styles of cells.
 *
 * @param unknown $rowData eingabe_abgeschlossen_datum, kontrolliert_datum, freigabe_datum, autorisierung_verschickt_datum, autorisiert_datum, kontrolliert_visa, eingabe_abgeschlossen_visa, im_rat_bis, sitzplatz, email, geburtstag, im_rat_bis, geschlecht, kleinbild, parlament_biografie_id, beruf, farbcode
 */
function drawWorkflowStyles($table_name, $rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses) {
  $workflowStateColors = [];
  $workflowStateColors['freigabe'] = 'greenyellow';
  $workflowStateColors['autorisiert'] = 'lightblue';
  $workflowStateColors['autorisierung_verschickt'] = 'blue';
  $workflowStateColors['kontrolliert'] = 'orange';
  $workflowStateColors['eingabe_abgeschlossen'] = 'yellow';

  /*
   "freigabe":"greenyellow",
   "autorisiert":"lightblue",
   "autorisierung_verschickt":"blue",
   "kontrolliert":"orange",
   "eingabe_abgeschlossen":"yellow",
   "unbearbeitet_neutral":"white",
   "unbearbeitet_farbe":"#FFFFBB",
   "nicht_erfasst":"silver"
   */

  $workflowStateColors = getSettingValue('arbeitsablaufStatusFarben', true, $workflowStateColors);

  $update_threshold_setting = getSettingValue('ueberarbeitungsDatumSchwellwert', false, '2012-01-01');
  $update_threshold = SMDateTime::Parse($update_threshold_setting, 'Y-m-d');
  $update_threshold_ts = $update_threshold->GetTimestamp();
  $now_ts = time();

  $workflow_styles = '';

  // Check completeness
  $completeness_styles = '';

  if ($table_name === 'parlamentarier' || $table_name === 'person') {
    // Check inconsistencies
    // TODO check ranges
    // TODO do not check only on !getTimestamp, use range
    if ((getTimestamp($rowData['freigabe_datum']) >= $update_threshold_ts
        && (!getTimestamp($rowData['autorisierung_verschickt_datum'])
            || !getTimestamp($rowData['eingabe_abgeschlossen_datum'])))
        || (getTimestamp($rowData['autorisierung_verschickt_datum']) >= $update_threshold_ts
            && !(getTimestamp($rowData['freigabe_datum']) >= $update_threshold_ts)
            && (
                !getTimestamp($rowData['eingabe_abgeschlossen_datum'])))
        || (getTimestamp($rowData['kontrolliert_datum']) >= $update_threshold_ts
            && (!getTimestamp($rowData['eingabe_abgeschlossen_datum']
                ))
            )
        || (!empty($rowData['autorisiert_datum']) && getTimestamp($rowData['autorisiert_datum']) >= $update_threshold_ts
            && (!getTimestamp($rowData['eingabe_abgeschlossen_datum']
                ))
        )
      ) {
        $workflow_styles .= 'background-image: url(' . util_data_uri('img/icons/warning.gif') . '); background-repeat: no-repeat;';

        }

        // Color states
        //else
        if (isset($rowData['erfasst']) && $rowData['erfasst'] == 'Nein' && getTimestamp($rowData['freigabe_datum']) >= $update_threshold_ts) {
          $workflow_styles .= "background-color: {$workflowStateColors['nicht_erfasst_published']};";
        } else if (getTimestamp($rowData['freigabe_datum']) >= $update_threshold_ts) {
          $workflow_styles .= "background-color: {$workflowStateColors['freigabe']};";
        } else if (getTimestamp($rowData['autorisiert_datum']) >= $update_threshold_ts) {
          $workflow_styles .= "background-color: {$workflowStateColors['autorisiert']};";
        } else if (getTimestamp($rowData['autorisierung_verschickt_datum']) >= $update_threshold_ts) {
          $workflow_styles .= "background-color: {$workflowStateColors['autorisierung_verschickt']};";
        } else if (getTimestamp($rowData['kontrolliert_datum']) >= $update_threshold_ts) {
          $workflow_styles .= "background-color: {$workflowStateColors['kontrolliert']};";
        } else if (getTimestamp($rowData['eingabe_abgeschlossen_datum']) >= $update_threshold_ts) {
          $workflow_styles .= "background-color: {$workflowStateColors['eingabe_abgeschlossen']};";
        } else if (isset($rowData['erfasst']) && $rowData['erfasst'] == 'Nein') {
          $workflow_styles .= "background-color: {$workflowStateColors['nicht_erfasst']};";
        }

        if (getTimestamp($rowData['kontrolliert_datum']) >= $update_threshold_ts
            && !preg_match('/background-image/',$workflow_styles)
            && (getTimestamp($rowData['kontrolliert_datum']) - getTimestamp($rowData['eingabe_abgeschlossen_datum']) > 3600
                || ($rowData['kontrolliert_visa'] != $rowData['eingabe_abgeschlossen_visa'] && getTimestamp($rowData['kontrolliert_datum']) - getTimestamp($rowData['eingabe_abgeschlossen_datum']) > 0)
                )
            ) {
              $workflow_styles .= 'background-image: url(' . util_data_uri('img/tick.png') . '); background-repeat: no-repeat; background-position: bottom right;';
            } elseif (getTimestamp($rowData['kontrolliert_datum']) >= $update_threshold_ts
                && !preg_match('/background-image/',$workflow_styles)
                ) {
                  $workflow_styles .= 'background-image: url(' . util_data_uri('img/tick-small-red.png') . '); background-repeat: no-repeat; background-position: bottom right;';
            }

            if ((isset($rowData['im_rat_bis']) && getTimestamp($rowData['im_rat_bis']) < $now_ts) || (isset($rowData['bis']) && getTimestamp($rowData['bis']) < $now_ts)) {
              $workflow_styles .= 'text-decoration: line-through;';
              $completeness_styles .= 'text-decoration: line-through;';
            } elseif ((isset($rowData['im_rat_bis']) && getTimestamp($rowData['im_rat_bis']) > $now_ts) || (isset($rowData['bis']) && getTimestamp($rowData['bis']) > $now_ts)) {
              $workflow_styles .= 'text-decoration: underline;';
              $completeness_styles .= 'text-decoration: underline;';
            } elseif (isset($rowData['erfasst']) && $rowData['erfasst'] === 'Nein') {
              //       $workflow_styles .= 'text-decoration: overline; text-decoration-style: wavy; text-decoration-color: red;';
              $workflow_styles .= 'text-decoration: underline wavy red;';
              $completeness_styles .= 'text-decoration: underline wavy red;';
            }

            if ($table_name === 'parlamentarier') {
              // Check person workflow state
              $zb_list = zutrittsberechtigung_state($rowData['id']);
              $zb_state = count($zb_list) <= 2;
              $zb_controlled = true;
              foreach($zb_list as $zb) {
                // TODO check also fields from Person
                $zb_state &= getTimestamp($zb['eingabe_abgeschlossen_datum']) >= $update_threshold_ts;
                $zb_controlled &= getTimestamp($zb['kontrolliert_datum']) >= $update_threshold_ts && getTimestamp($zb['kontrolliert_datum']) > getTimestamp($zb['eingabe_abgeschlossen_datum']);
              }
              if ($zb_state && $zb_controlled) {
                $completeness_styles .= 'background-image: url(' . util_data_uri('img/icons/fugue/user-green-female.png') . '); background-repeat: no-repeat; background-position: bottom right;';
              } elseif ($zb_state) {
                $completeness_styles .= 'background-image: url(' . util_data_uri('img/icons/fugue/user.png') . '); background-repeat: no-repeat; background-position: bottom right;';
              } elseif (getTimestamp($rowData['eingabe_abgeschlossen_datum']) >= $update_threshold_ts) {
                $completeness_styles .= 'background-image: url(' . util_data_uri('img/icons/fugue/user--exclamation.png') . '); background-repeat: no-repeat; background-position: bottom right;';
              } // else nothing

              if (isset($rowData['email']) && isset($rowData['geburtstag']) && isset($rowData['im_rat_seit']) && isset($rowData['geschlecht']) && isset($rowData['kleinbild']) && isset($rowData['parlament_biografie_id']) && isset($rowData['beruf']) && $zb_state) {
                $completeness_styles .= 'background-color: greenyellow;';
              } elseif (isset($rowData['email']) || isset($rowData['geburtstag']) || isset($rowData['im_rat_seit']) || isset($rowData['geschlecht']) || isset($rowData['kleinbild']) || isset($rowData['parlament_biografie_id']) || isset($rowData['beruf'])){
                $completeness_styles .= 'background-color: orange;';
              }
              checkAndMarkColumnNotNull('email', $rowData, $rowCellStyles);
              checkAndMarkColumnNotNull('geburtstag', $rowData, $rowCellStyles);
              checkAndMarkColumnNotNull('im_rat_seit', $rowData, $rowCellStyles);
              checkAndMarkColumnNotNull('geschlecht', $rowData, $rowCellStyles);
              checkAndMarkColumnNotNull('kleinbild', $rowData, $rowCellStyles);
              checkAndMarkColumnNotNull('parlament_biografie_id', $rowData, $rowCellStyles);
              checkAndMarkColumnNotNull('beruf', $rowData, $rowCellStyles);

            } elseif ($table_name === 'person') {
              if (isset($rowData['email']) && isset($rowData['geschlecht']) && isset($rowData['beruf']) /*&& isset($rowData['funktion'])*/) {
                $completeness_styles .= 'background-color: greenyellow;';
              } elseif (isset($rowData['email']) || isset($rowData['geschlecht']) || isset($rowData['beruf']) /*|| isset($rowData['funktion'])*/) {
                $completeness_styles .= 'background-color: orange;';
              }
              checkAndMarkColumnNotNull('email', $rowData, $rowCellStyles);
              checkAndMarkColumnNotNull('geschlecht', $rowData, $rowCellStyles);
              checkAndMarkColumnNotNull('beruf', $rowData, $rowCellStyles);
            }

            // Write styles
            $rowCellStyles['nachname'] = $completeness_styles;
            $rowCellStyles['id'] = $workflow_styles;

  } else if($table_name === 'translation_source' || $table_name === 'translation_target') { // BIG IF ELSE not parlamentarier or person
    // do nothing
  } else { // BIG IF ELSE not parlamentarier or person
    $threshold_apply_list = array('interessenbindung', 'mandat');
    if (!in_array($table_name, $threshold_apply_list)) {
      $update_threshold_ts = 1; // Do not use update threshold for orgnisations
    }
    // Check inconsistencies
    if ((getTimestamp($rowData['freigabe_datum']) >= $update_threshold_ts
        && (!getTimestamp($rowData['kontrolliert_datum'])
            || !getTimestamp($rowData['eingabe_abgeschlossen_datum'])))
        || (getTimestamp($rowData['kontrolliert_datum']) >= $update_threshold_ts
            && !(getTimestamp($rowData['freigabe_datum']) >= $update_threshold_ts)
            && !getTimestamp($rowData['eingabe_abgeschlossen_datum']))
        || (!empty($rowData['autorisiert_datum']) && getTimestamp($rowData['autorisiert_datum']) >= $update_threshold_ts
            && (!getTimestamp($rowData['eingabe_abgeschlossen_datum']
                ))
        )
      ) {
        $workflow_styles .= 'background-image: url(' . util_data_uri('img/icons/warning.gif') . '); background-repeat: no-repeat;';
        }
        // Color states

        //else
        if (getTimestamp($rowData['freigabe_datum']) >= $update_threshold_ts) {
          $workflow_styles .= "background-color: {$workflowStateColors['freigabe']};";
        } else if (!empty($rowData['autorisiert_datum']) && getTimestamp($rowData['autorisiert_datum']) >= $update_threshold_ts) {
          $workflow_styles .= "background-color: {$workflowStateColors['autorisiert']};";
        } else if (getTimestamp($rowData['kontrolliert_datum']) >= $update_threshold_ts) {
          $workflow_styles .= "background-color: {$workflowStateColors['kontrolliert']};";
        } else if (getTimestamp($rowData['eingabe_abgeschlossen_datum']) >= $update_threshold_ts) {
          $workflow_styles .= "background-color: {$workflowStateColors['eingabe_abgeschlossen']};";
        }

        if (getTimestamp($rowData['kontrolliert_datum']) >= $update_threshold_ts
            && !preg_match('/background-image/',$workflow_styles)
            && (getTimestamp($rowData['kontrolliert_datum']) - getTimestamp($rowData['eingabe_abgeschlossen_datum']) > 3600
                || ($rowData['kontrolliert_visa'] != $rowData['eingabe_abgeschlossen_visa'] && getTimestamp($rowData['kontrolliert_datum']) - getTimestamp($rowData['eingabe_abgeschlossen_datum']) > 0)
                )
            ) {
              $workflow_styles .= 'background-image: url(' . util_data_uri('img/tick.png') . '); background-repeat: no-repeat; background-position: bottom right;';
            } elseif (getTimestamp($rowData['kontrolliert_datum']) >= $update_threshold_ts
                && !preg_match('/background-image/', $workflow_styles)
                ) {
                  $workflow_styles .= 'background-image: url(' . util_data_uri('img/tick-small-red.png') . '); background-repeat: no-repeat; background-position: bottom right;';
            }

            if (isset($rowData['parlamentarier_id'])) {
              $state = parlamentarier_state($rowData['parlamentarier_id']);
              $parlamentarier_workflow_styles = '';
              if (isset($state['erfasst']) && $state['erfasst'] == 'Nein' && getTimestamp($state['freigabe_datum']) >= $update_threshold_ts) {
                $parlamentarier_workflow_styles .= "background-color: {$workflowStateColors['nicht_erfasst_published']};";
              } else if (getTimestamp($state['freigabe_datum']) >= $update_threshold_ts) {
                $parlamentarier_workflow_styles .= "background-color: {$workflowStateColors['freigabe']};";
              } else if (getTimestamp($state['autorisiert_datum']) >= $update_threshold_ts) {
                $parlamentarier_workflow_styles .= "background-color: {$workflowStateColors['autorisiert']};";
              } else if (getTimestamp($state['autorisierung_verschickt_datum']) >= $update_threshold_ts) {
                $parlamentarier_workflow_styles .= "background-color: {$workflowStateColors['autorisierung_verschickt']};";
              } else if (getTimestamp($state['kontrolliert_datum']) >= $update_threshold_ts) {
                $parlamentarier_workflow_styles .= "background-color: {$workflowStateColors['kontrolliert']};";
              } else if (getTimestamp($state['eingabe_abgeschlossen_datum']) >= $update_threshold_ts) {
                $parlamentarier_workflow_styles .= "background-color: {$workflowStateColors['eingabe_abgeschlossen']};";
              } else if (isset($state['erfasst']) && $state['erfasst'] == 'Nein') {
                $parlamentarier_workflow_styles .= "background-color: {$workflowStateColors['nicht_erfasst']};";
              }
              $rowCellStyles['parlamentarier_id'] = $parlamentarier_workflow_styles;
            }

            if (isset($rowData['bis']) && getTimestamp($rowData['bis']) < $now_ts) {
              $workflow_styles .= 'text-decoration: line-through;';
              $completeness_styles .= 'text-decoration: line-through;';
            } elseif (isset($rowData['bis']) && getTimestamp($rowData['bis']) > $now_ts) {
              $workflow_styles .= 'text-decoration: underline;';
              $completeness_styles .= 'text-decoration: underline;';
            }

            if ($table_name == 'interessenbindung' || $table_name == 'mandat') {
              $sql = 'SELECT * FROM organisation WHERE id = :id;';
              $options = array(
                  'fetch' => PDO::FETCH_BOTH, // for compatibility with existing code
              );
              $subRowData = lobbywatch_forms_db_query($sql, array(':id' =>$rowData['organisation_id']), $options)->fetch();

              $subRowCellStyles = [];
              $subRowStyles = '';
              customDrawRow('organisation', $subRowData, $subRowCellStyles, $subRowStyles);
              $rowCellStyles['organisation_id'] = $subRowCellStyles['id'];
            } else if ($table_name == 'organisation_beziehung') {
              $sql = 'SELECT * FROM organisation WHERE id = :id;';
              $options = array(
                  'fetch' => PDO::FETCH_BOTH, // for compatibility with existing code
              );

              $subRowCellStyles = [];
              $subRowStyles = '';

              $subRowData = lobbywatch_forms_db_query($sql, array(':id' => $rowData['organisation_id']), $options)->fetch();
              customDrawRow('organisation', $subRowData, $subRowCellStyles, $subRowStyles);
              $rowCellStyles['organisation_id'] = $subRowCellStyles['id'];

              $subRowData = lobbywatch_forms_db_query($sql, array(':id' => $rowData['ziel_organisation_id']), $options)->fetch();
              customDrawRow('organisation', $subRowData, $subRowCellStyles, $subRowStyles);
              $rowCellStyles['ziel_organisation_id'] = $subRowCellStyles['id'];
            }

            // Check completeness

            $completeness_styles = '';

            if ($table_name == 'partei' && isset($rowData['name'])) {
              $completeness_styles .= 'background-color: greenyellow;';
              checkAndMarkColumnNotNull('name', $rowData, $rowCellStyles);
            } elseif ($table_name === 'organisation') {
              if (isset($rowData['rechtsform']) && isset($rowData['interessengruppe_id']) && isset($rowData['branche_id'])) {
                $completeness_styles .= 'background-color: greenyellow;';
              } elseif (isset($rowData['rechtsform']) || isset($rowData['interessengruppe_id'])) {
                $completeness_styles .= 'background-color: orange;';
              }
              checkAndMarkColumnNotNull('rechtsform', $rowData, $rowCellStyles);
              checkAndMarkColumnNotNull('interessengruppe_id', $rowData, $rowCellStyles);
            } elseif ($table_name === 'branche') {
              if (isset($rowData['kommission_id'])) {
                $completeness_styles .= 'background-color: greenyellow;';
              }
              checkAndMarkColumnNotNull('kommission_id', $rowData, $rowCellStyles);
            } elseif ($table_name === 'kommission') {
              if (!isset($rowData['anzahl_mitglieder'])) {
                $completeness_styles .= 'background-color: #FF1493;';
              } elseif ((in_kommission_anzahl($rowData['id'])['num'] != $rowData['anzahl_mitglieder']) /*&& $rowData['typ'] == 'kommission'*/) {
                $completeness_styles .= 'background-color: red;'; // deep pink
              } elseif (isset($rowData['parlament_url'])) {
                $completeness_styles .= 'background-color: greenyellow;';
              } else {
                $completeness_styles .= 'background-color: orange;';
              }
              checkAndMarkColumnNotNull('parlament_url', $rowData, $rowCellStyles);
              checkAndMarkColumnNotNull('anzahl_nationalraete', $rowData, $rowCellStyles);
              checkAndMarkColumnNotNull('anzahl_staenderaete', $rowData, $rowCellStyles);
              } elseif ($table_name === 'zutrittsberechtigung') {
                if (isset($rowData['funktion'])) {
                  $completeness_styles .= 'background-color: greenyellow;';
                } elseif (isset($rowData['funktion'])) {
                  $completeness_styles .= 'background-color: orange;';
                }
                checkAndMarkColumnNotNull('funktion', $rowData, $rowCellStyles);
              }

              // Write styles

              $rowCellStyles['id'] = $workflow_styles;

              if ($completeness_styles != '') {
                switch($table_name) {
                  case 'zutrittsberechtigung':
                    $rowCellStyles['person_id'] = $completeness_styles;
                    break;
                  case 'organisation':
                    $rowCellStyles['name_de'] = $completeness_styles;
                    break;
                  case 'kommission':
                  case 'partei':
                  case 'fraktion':
                    $rowCellStyles['abkuerzung'] = $completeness_styles;
                    break;
                  case 'interessengruppe':
                  case 'branche':
                    $rowCellStyles['name'] = $completeness_styles;
                    break;
                  default:
                    $rowCellStyles['freigabe_datum'] = $completeness_styles;
                }
              }
            }

}

// logTableExtendedDrawRow('XXX_log', $rowData, $rowCellStyles, $rowStyles, $rowClasses, $cellClasses);
function logTableExtendedDrawRow($table_name, $rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses) {
  $logId = $rowData['log_id'];
  $id = $rowData['id'];
  $sql = "SELECT * FROM $table_name WHERE id = :id AND log_id < :log_id ORDER BY log_id DESC LIMIT 1;";
  $options = array(
      'fetch' => PDO::FETCH_BOTH, // for compatibility with existing code
  );
  // these many DB calls are not very efficient, it could be optimized if necessary
  $prevRowData = lobbywatch_forms_db_query($sql, array(':id' => $id, ':log_id' => $logId), $options)->fetch();
  if ($prevRowData) {
    foreach ($rowData as $key => $value) {
      if (array_key_exists($key, $prevRowData)
      && !in_array($key, ['updated_date', 'updated_visa', 'action', 'action_date', 'log_id', 'snapshot_id'])
      && clean_str($value) !== clean_str($prevRowData[$key])) {
        $rowCellStyles[$key] = 'background-color: yellow;';
      }
    }
  }
}

function zutrittsberechtigung_state($parlamentarier_id) {
  $zb_state = &php_static_cache(__FUNCTION__);

  // Load all zutrittsberechtige on first call
  if (!isset($zb_state)) {
    // Fetch all the first time
    $eng_con = getDBConnection();
    $con = $eng_con->GetConnectionHandle();
    // TODO close connection
    $sql = "SELECT zutrittsberechtigung.id, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.eingabe_abgeschlossen_datum, zutrittsberechtigung.kontrolliert_datum, zutrittsberechtigung.autorisiert_datum, zutrittsberechtigung.freigabe_datum, zutrittsberechtigung.parlamentarier_id
  FROM v_zutrittsberechtigung_simple_compat zutrittsberechtigung
  WHERE
    zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW()
        ;";

    $zbs = [];
    $sth = $con->prepare($sql);
    $sth->execute([]);
    $zbs = $sth->fetchAll();

    // Connection will automatically be closed at the end of the request.

    foreach($zbs as $zb) {
      $zb_state[$zb['parlamentarier_id']][] = $zb;
    }
  }

  // Fetch a single parlamentarier, should not be called anymore
  if (!isset($zb_state[$parlamentarier_id])) {
    $eng_con = getDBConnection();
    $con = $eng_con->GetConnectionHandle();
    // TODO close connection
    $sql = "SELECT zutrittsberechtigung.id, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.eingabe_abgeschlossen_datum, zutrittsberechtigung.kontrolliert_datum, zutrittsberechtigung.autorisiert_datum, zutrittsberechtigung.freigabe_datum
  FROM v_zutrittsberechtigung_simple_compat zutrittsberechtigung
  WHERE
    zutrittsberechtigung.parlamentarier_id=:id
    AND zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW();";

    $zbs = [];
    $sth = $con->prepare($sql);
    $sth->execute(array(':id' => $parlamentarier_id));
    $zbs = $sth->fetchAll();

    // Connection will automatically be closed at the end of the request.

    $zb_state[$parlamentarier_id] = $zbs;

  }

  return $zb_state[$parlamentarier_id];
}

function parlamentarier_state($parlamentarier_id) {
  $parlam_state = &php_static_cache(__FUNCTION__);

  // Load all parlamentarier on first call
  if (!isset($parlam_state)) {
    // Fetch all the first time
    $eng_con = getDBConnection();
    $con = $eng_con->GetConnectionHandle();
    // TODO close connection
    $sql = "SELECT parlamentarier.id as parlamentarier_id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.eingabe_abgeschlossen_datum, parlamentarier.kontrolliert_datum, autorisierung_verschickt_datum, parlamentarier.autorisiert_datum, parlamentarier.freigabe_datum, parlamentarier.erfasst
  FROM v_parlamentarier_simple parlamentarier;";

    $sth = $con->prepare($sql);
    $sth->execute([]);
    $zbs = $sth->fetchAll();

    // Connection will automatically be closed at the end of the request.

    foreach($zbs as $zb) {
      $parlam_state[$zb['parlamentarier_id']] = $zb;
    }
  }

  // Fetch a single parlamentarier, should not be called anymore
  if (!isset($parlam_state[$parlamentarier_id])) {
    df("Single");
    $eng_con = getDBConnection();
    $con = $eng_con->GetConnectionHandle();
    // TODO close connection
    $sql = "SELECT parlamentarier.id as parlamentarier_id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.eingabe_abgeschlossen_datum, parlamentarier.kontrolliert_datum, autorisierung_verschickt_datum, parlamentarier.autorisiert_datum, parlamentarier.freigabe_datum, parlamentarier.erfasst
  FROM v_parlamentarier_simple parlamentarier
  WHERE
    parlamentarier.id=:id;";

    $zbs = [];
    $sth = $con->prepare($sql);
    $sth->execute(array(':id' => $parlamentarier_id));
    $zb = $sth->fetch();

    // Connection will automatically be closed at the end of the request.

    $parlam_state[$parlamentarier_id] = $zb;

  }

  return $parlam_state[$parlamentarier_id];
}

/**
 *
 * @param int $kommission_id
 * @param string $rat 'NR', 'SR' or <code>null</code> for both raete
 * @return number
 */
function in_kommission_anzahl($kommission_id, $rat = null) {
  $cache = &php_static_cache(__FUNCTION__);

  // Load all zutrittsberechtige on first call
  if (!isset($cache)) {
    // Fetch all the first time
    // TODO close connection
    $sql = "SELECT in_kommission.kommission_id, count( DISTINCT in_kommission.parlamentarier_id) as num, in_kommission.kommission_abkuerzung, in_kommission.kommission_name, in_kommission.kommission_typ, in_kommission.rat
  FROM v_in_kommission in_kommission
  WHERE (in_kommission.bis IS NULL OR in_kommission.bis > NOW() )"
. ( $rat ? " AND in_kommission.rat='$rat'" : '')
. "  GROUP BY in_kommission.kommission_id, in_kommission.rat;";

    $zbs = lobbywatch_forms_db_query($sql, [], array('fetch' => PDO::FETCH_ASSOC))->fetchAll();

    // Connection will automatically be closed at the end of the request.

    foreach($zbs as $zb) {
      $cache[$zb['kommission_id']] = $zb;
    }
  }
  return (isset($cache[$kommission_id]) ? $cache[$kommission_id] : 0);
}


function checkAndMarkColumnNotNull($column, $rowData, &$rowCellStyles) {
    if (empty($rowData[$column]) || $rowData[$column] == '') {
      if (empty($rowCellStyles[$column])) {
        $rowCellStyles[$column] = '';
      }
      $rowCellStyles[$column] .= 'background-image: url(' . util_data_uri('img/book-question.png') . '); background-repeat: no-repeat; background-position: top left;';
    }
}

function globalOnBeforeUpdate($page, &$rowData, &$cancel, &$message, $tableName) {
  // Set in project option

  // Ref for events: http://www.sqlmaestro.com/products/mysql/phpgenerator/help/01_03_04_page_editor_events/

  // CURRENT_DATETIME = 24-11-2013 23:42:09
  // CURRENT_DATETIME_ISO_8601 = 2013-11-24T23:42:09+01:00
  // CURRENT_DATETIME_RFC_2822 = 2013-11-24T23:42:09+01:00
  // CURRENT_UNIX_TIMESTAMP = 1385332929
  // CURRENT_USER_NAME
  // CURRENT_USER_ID
  // PAGE_SHORT_CAPTION
  // PAGE_CAPTION

  $userName = $page->GetEnvVar('CURRENT_USER_NAME');
  $datetime = $page->GetEnvVar('CURRENT_DATETIME');

  $rowData['updated_visa'] = strtolower($userName);
  $rowData['updated_date'] = $datetime;

  clean_fields($rowData);
}

function globalOnBeforeDelete($page, &$rowData, &$cancel, &$message, $tableName) {

}

function globalOnBeforeInsert($page, &$rowData, &$cancel, &$message, $tableName) {
  // Set in project option

  // Ref for events: http://www.sqlmaestro.com/products/mysql/phpgenerator/help/01_03_04_page_editor_events/

  // CURRENT_DATETIME = 24-11-2013 23:42:09
  // CURRENT_DATETIME_ISO_8601 = 2013-11-24T23:42:09+01:00
  // CURRENT_DATETIME_RFC_2822 = 2013-11-24T23:42:09+01:00
  // CURRENT_UNIX_TIMESTAMP = 1385332929
  // CURRENT_USER_NAME
  // CURRENT_USER_ID
  // PAGE_SHORT_CAPTION
  // PAGE_CAPTION

  $userName = $page->GetEnvVar('CURRENT_USER_NAME');
  $datetime = $page->GetEnvVar('CURRENT_DATETIME');

  $rowData['created_visa'] = strtolower($userName);
  $rowData['created_date'] = $datetime;

  $rowData['updated_visa'] = strtolower($userName);
  $rowData['updated_date'] = $datetime;

  clean_fields($rowData);
}

/**
 * Certain operations are only available for full workflow users. This method defines who is a full workflow user.
 *
 * They have more permissions. More operations are allowed.
 *
 * @return boolean true for full workflow users
 */
function isFullWorkflowUser() {
  return in_array(Application::Instance()->GetCurrentUserId(), [
  1, // admin
  2, // roland
  3, // otto
  4, // thomas
  // 5, // rebecca
  // 6, // bane
  12, // Alice Kohli
  18, // philippe
  // 37, // Graf, Céline Graf
  // 63, // Flavia, Von Gunten
  // 67, // Alexandra Baertsch
  // 69, // Samuel Meier
  71, // Strässle Fabian
  72, // Priscilla Imboden
  73, // Balz Oertli
  76, // Etienne Kocher
  86, // Lucas
  87, // Flavia
  ], false);
}

function defaultOnAfterLogin($userName, $connection) {
  $connection->ExecSQL("UPDATE `user` SET `last_login`= CURRENT_TIMESTAMP WHERE `name` = '$userName';");
}

function _custom_page_build_secs() {
  return round(timer_read('page_build')/1000, 2);
}

/**
 * Starts the timer with the specified name.
 *
 * Copied from Drupal 7.
 *
 * If you start and stop the same timer multiple times, the measured intervals
 * will be accumulated.
 *
 * @param $name
 *   The name of the timer.
 */
function timer_start($name) {
  global $timers;

  $timers[$name]['start'] = microtime(TRUE);
  $timers[$name]['count'] = isset($timers[$name]['count']) ? ++$timers[$name]['count'] : 1;
}

/**
 * Reads the current timer value without stopping the timer.
 *
 * Copied from Drupal 7.
 *
 * @param $name
 *   The name of the timer.
 *
 * @return
 *   The current timer value in ms.
 */
function timer_read($name) {
  global $timers;

  if (isset($timers[$name]['start'])) {
    $stop = microtime(TRUE);
    $diff = round(($stop - $timers[$name]['start']) * 1000, 2);

    if (isset($timers[$name]['time'])) {
      $diff += $timers[$name]['time'];
    }
    return $diff;
  }
  return $timers[$name]['time'];
}

/**
 * Stops the timer with the specified name.
 *
 * Copied from Drupal 7.
 *
 * @param $name
 *   The name of the timer.
 *
 * @return
 *   A timer array. The array contains the number of times the timer has been
 *   started and stopped (count) and the accumulated timer value in ms (time).
 */
function timer_stop($name) {
  global $timers;

  if (isset($timers[$name]['start'])) {
    $stop = microtime(TRUE);
    $diff = round(($stop - $timers[$name]['start']) * 1000, 2);
    if (isset($timers[$name]['time'])) {
      $timers[$name]['time'] += $diff;
    }
    else {
      $timers[$name]['time'] = $diff;
    }
    unset($timers[$name]['start']);
  }

  return $timers[$name];
}

/**
 * Copied from language_default in bootstrap.inc.
 *
 * @param string $property
 * @return The
 */
function lobbywatch_language_default($property = NULL) {
  //   $language = variable_get('language_default', (object) array('language' => 'en', 'name' => 'English', 'native' => 'English', 'direction' => 0, 'enabled' => 1, 'plurals' => 0, 'formula' => '', 'domain' => '', 'prefix' => '', 'weight' => 0, 'javascript' => ''));
  $language = (object) array('language' => 'de', 'name' => 'German', 'native' => 'Deutsch', 'direction' => 0, 'enabled' => 1, 'plurals' => 0, 'formula' => '', 'domain' => '', 'prefix' => '', 'weight' => 0, 'javascript' => '');
  return $property ? $language->$property : $language;
}

/**
 * Initilizes the current language for translations. de is default.
 * @param unknown $langcode ISO language code, de, fr supported
 * @return the old language
 */
function lobbywatch_language_initialize($langcode = 'de') {
  global $language;
  $oldlanguage = $language;
  if ($langcode == 'fr') {
    $language = (object) array('language' => 'fr', 'name' => 'French', 'native' => 'Französisch', 'direction' => 0, 'enabled' => 1, 'plurals' => 0, 'formula' => '', 'domain' => '', 'prefix' => '', 'weight' => 0, 'javascript' => '');
  } else {
    $language = (object) array('language' => 'de', 'name' => 'German', 'native' => 'Deutsch', 'direction' => 0, 'enabled' => 1, 'plurals' => 0, 'formula' => '', 'domain' => '', 'prefix' => '', 'weight' => 0, 'javascript' => '');
  }
  return $oldlanguage;
}

/**
 * Sets the current language for translations. de is default.
 *
 * @param unknown $langcode ISO language code, de, fr supported
 * @return the old language
 */
function lobbywatch_set_language($langcode = 'de') {
  return lobbywatch_language_initialize($langcode);
}

// **************************************************
// customOnCustomRenderColumn('table', $fieldName, $fieldData, $rowData, $customText, $handled);
function customOnCustomRenderColumn($table, $fieldName, $fieldData, $rowData, &$customText, &$handled) {
// MIGR OnCustomRenderColumn() start

  $update_threshold_setting = getSettingValue('ueberarbeitungsDatumSchwellwert', false, '2012-01-01');
  $update_threshold = SMDateTime::Parse($update_threshold_setting, 'Y-m-d');
  $update_threshold_ts = $update_threshold->GetTimestamp();
  $now_ts = time();

  // Hide edit and delete button if already controlled
  if (($fieldName == 'edit' || $fieldName == 'delete') && (in_array($table, array(/*'parlamentarier',*/ 'zutrittsberechtigung', 'interessenbindung', 'mandat', 'organisation_beziehung', 'in_kommission'))) && !isFullWorkflowUser() && isset($rowData['kontrolliert_datum'])
      && getTimestamp($rowData['kontrolliert_datum']) >= $update_threshold_ts) {
    $customText = '';
    $handled = true;
  }

  $organisation_beziehung_art_map = array('arbeitet fuer' => lt('arbeitet für'),'mitglied von' => lt('Mitglied von'),'tochtergesellschaft von' => '<abbr title="'. lt('z.B. Tochtergesellschaft o. Zweigniederlassung') . '">' . lt('Suborganisation von') . '</abbr>','partner von' => lt('Partner von'),'beteiligt an' => lt('beteiligt an')); // TODO lang
  if ($table == 'organisation_beziehung' && $fieldName == 'art' && array_key_exists($fieldData, $organisation_beziehung_art_map)) {
    $customText = $organisation_beziehung_art_map[$fieldData];
    $handled = true;
  } else if ($table == 'parlamentarier' && $fieldName == 'parlament_interessenbindungen') {
    $id = $rowData['id'];
  } else if ($table == 'organisation' && $fieldName == 'rechtsform_handelsregister') {
    $id = $rowData['id'];
    $customText = "<abbr title='" . _lobbywatch_get_rechtsform_handelsregister_code_name($fieldData) . "'>$fieldData</abbr>";
    $handled = true;
  }
  // MIGR OnCustomRenderColumn() end
}

/**
 * Determine whether the user has a given privilege.
 *
 * Compatibility function for Drupal.
 *
 * @param $string
 *   The permission, such as "administer nodes", being checked for.
 * @param $account
 *   (optional) The account to check, if not given use currently logged in user.
 *
 * @return
 *   Boolean TRUE if the current user has the requested permission.
 *
 * All permission checks in Drupal should go through this function. This
 * way, we guarantee consistent behavior, and ensure that the superuser
 * can perform all actions.
 */
function user_access($string, $account = NULL) {
  global $user;

  if (!isset($account)) {
    $account = $user;
  }

  switch($string) {
    case 'access lobbywatch general content':
      return true;
    case 'access lobbywatch advanced content':
    case 'access lobbywatch unpublished content':
    case 'access lobbywatch admin':
    default:
      return false;
  }

}

function getCustomPagesHeader() {
  global $env;
  return "<h1 id='site-name'>
  <a href='/'><img id='site-logo' width='30px' height='auto' typeof='foaf:Image' src='lobbywatch-eye-transparent-bg-cut-75px-tiny.png' alt='Lobbywatch'></a>
  <a href='index.php'>Lobbywatch Datenbearbeitung " . ($env !== 'PRODUCTION' ? "<span style=\"background-color:red\">$env</span>" : '') . "</a>
  </h1>";
}

function getCustomPagesFooter() {
  global $env, $env_dir, $env_dirauswertung, $version, $generator_version, $deploy_date, $build_date, $deploy_last_commit, $build_last_commit, $import_date_wsparlamentch, $db_connection, $mysql_client_version, $mysql_server_version;
  $deploy_commit = substr($deploy_last_commit, 0, 8);
  $build_commit = substr($build_last_commit, 0, 8);
  $now = date('H:i');
  return "Bearbeitungsseiten von <a href='$env_dir'>Lobbywatch $env</a>;
  <!-- a href='$env_dirauswertung'>Auswertung</a--> <a href='/wiki'>Wiki</a><br>
  Mode: $env / DB: {$db_connection['database']} / Version: $version / Generator: $generator_version / Deploy date: $deploy_date / Build date: $build_date / Deploy commit: $deploy_commit / Build commit: $build_commit / Server time: $now /
  Last ws.parlament.ch import: $import_date_wsparlamentch / Page execution time: " . _custom_page_build_secs() . "s<br>".
  "PHP: " . phpversion() . " / MySQL server: $mysql_server_version / MySQL client: $mysql_client_version";
}

/**
 * This event occurs when generating the HEAD section of the page. It allows you to define the contents of the
 *
 * HEAD section (like meta tags or favicon) for all pages of the generated website.
 * @param CommonPage $page
 * @param string $customHtmlHeaderText
 */
function globalOnCustomHTMLHeader(CommonPage $page, &$customHtmlHeaderText) {
}

// MIGR add Page $page parameter
/**
 * This event occurs before other events are declared and allow to create global objects, declare functions, and include
 * third-party libraries. This helps you to define a snapshot of PHP code that will be included into all the pages.
 *
 * http://www.sqlmaestro.com/products/mysql/phpgenerator/help/01_03_04_14_global_on_before_page_execute/
 */
function globalOnBeforePageExecute() {
}

function customOnBeforePageExecute($table) {
}

/**
 * This piece of code is a method of the Page class that is called at the end of the constructor. It allows you to
 * customize all members of the class, for example, add an additional filter to the dataset.
 *
 * http://www.sqlmaestro.com/products/mysql/phpgenerator/help/01_03_04_30_global_on_prepare_page/
 */
function globalOnPreparePage(Page $page) {
  global $env_dir, $edit_header_message, $edit_general_hint;
  $general_detailed_desc = '<div class="clearfix rbox note"><div class="rbox-title">
      <img src="img/icons/book_open.png" alt="Hinweis" title="Hinweis" class="icon" width="16" height="16">
      <span>Hinweis</span></div>
      <div class="rbox-data">Bitte die Bearbeitungsdokumentation (vor einer Bearbeitung) beachten und bei Unklarheiten anpassen, siehe <a href="https://cms.lobbywatch.ch/wiki/tiki-index.php?page=Datenerfassung&structure=Lobbywatch-Wiki" target="_blank">Wiki Datenbearbeitung</a> und <a href="/sites/lobbywatch.ch/app' . $env_dir . 'lobbywatch_datenmodell_simplified.pdf">Vereinfachtes Datenmodell</a> (Komplex: <a href="/sites/lobbywatch.ch/app' . $env_dir . 'lobbywatch_datenmodell_1page.pdf">1&nbsp;Seite</a> / <a href="/sites/lobbywatch.ch/app' . $env_dir . 'lobbywatch_datenmodell.pdf">4&nbsp;Seiten</a>).</div></div>';


  $isicv4_doc = '
  <h4>ISICv4<br>
  International Standard of Industrial Classification of All Economic Activities (ISIC), Revision 4</h4>

  <blockquote>
  Die International Standard Industrial Classification (ISIC, dt. &quot;Internationale Standardklassifikation der Wirtschaftszweige&quot;) ist eine Klassifikation der UNO zur Gliederung von Wirtschaftsbereichen und Industriezweigen.
  Dabei wird jede Branche aus dem primären, sekundären und tertiärer Wirtschaftssektor einer von 21 Hauptgruppen zugeordnet, die wiederum in Untergruppen aufgeteilt sind.
  </blockquote>

  <p>
  <span style="font-size: 2em"><strong><a href="https://unstats.un.org/unsd/publication/SeriesM/seriesm_4rev4e.pdf">ISICv4 PDF</a></strong></span>

  <ul>
  <li><a href="https://en.wikipedia.org/wiki/International_Standard_Industrial_Classification">Wiki DE: International Standard of Industrial Classification</a>
  <li><a href="https://de.wikipedia.org/wiki/International_Standard_Industrial_Classification">Wiki EN: International Standard of Industrial Classification</a>
  </ul>

  <h4>NACE/NOGA<br>
  Nomenclature statistique des activités économiques dans la Communauté européenne (NACE)</h4>
  <a href="https://de.wikipedia.org/wiki/Statistische_Systematik_der_Wirtschaftszweige_in_der_Europ%C3%A4ischen_Gemeinschaft">Wiki DE: NACE</a>

  <blockquote>
  Die Statistische Systematik der Wirtschaftszweige in der Europäischen Gemeinschaft (französisch Nomenclature statistique des activités économiques dans la Communauté européenne), meist nur als NACE bezeichnet, ist ein System zur Klassifizierung von Wirtschaftszweigen, das von Seiten der Europäischen Union auf Basis der ISIC (International Standard Industrial Classification of all Economic Activities) der Vereinten Nationen[1] entworfen wurde. Ihr entspricht auch die Schweizerische Nomenclature Générale des Activités économiques (NOGA).
  </blockquote>
';

  switch ($page->GetPageId()) {
  case 'organisation':
      $page->setDetailedDescription('<div class="wiki-table-help">
<div class="clearfix rbox warning"><div class="rbox-title"><img src="img/icons/exclamation.png" alt="Warnung" title="Warnung" class="icon" width="16" height="16"><span>Warnung</span></div><div class="rbox-data">Der Name sollte nur den Namen enthalten. Andere Informationen wie Orte sollen in den dafür vorgesehenen Feldern erfasst werden.</div></div><a id="plugin-edit-remarksbox5" href="javascript:void(1)" class="editplugin"><img src="img/icons/wiki_plugin_edit.png" alt="Edit Plugin:remarksbox" title="Edit Plugin:remarksbox" class="icon" width="16" height="16"></a>
<p>
<br>Grund: Wenn mehrere Daten in einem Feld abgelegt sind können diese Felder nicht mehr automatisch ausgewertet werden.
</p>

<div class="clearfix rbox note"><div class="rbox-title"><img src="img/icons/information.png" alt="Hinweis" title="Hinweis" class="icon" width="16" height="16"><span>Hinweis</span></div><div class="rbox-data">Durch die Interessengruppe wird eine Organisation einer Branche zugeordnet.</div></div><a id="plugin-edit-remarksbox6" href="javascript:void(1)" class="editplugin"><img src="img/icons/wiki_plugin_edit.png" alt="Edit Plugin:remarksbox" title="Edit Plugin:remarksbox" class="icon" width="16" height="16"></a>
<p>
<br>Das Erfassen der Interessengruppe bei einer Organisation ist deshalb wichtig.
</p>

<div class="clearfix rbox note"><div class="rbox-title"><img src="img/icons/information.png" alt="Hinweis" title="Hinweis" class="icon" width="16" height="16"><span>Hinweis</span></div><div class="rbox-data">Durch die Rechtsform einer Organisation wird bei einer Interessenbindung bestimmt, ob ein Vorstand ein Stiftungsrat oder ein Verwaltungsrat ist.</div></div><a id="plugin-edit-remarksbox7" href="javascript:void(1)" class="editplugin"><img src="img/icons/wiki_plugin_edit.png" alt="Edit Plugin:remarksbox" title="Edit Plugin:remarksbox" class="icon" width="16" height="16"></a>
</div>'
      . $general_detailed_desc);
      $page->setDescription("{$edit_header_message}Organisationen, die Lobbying im Parlament betreiben. Klicke <span><i class='icon-question'></i></span> für zusätzliche Infos.$edit_general_hint");
      break;
  case 'parlamentarier':
      $page->setDetailedDescription('<div class="wiki-table-help">
<div class="clearfix rbox note"><div class="rbox-title"><img src="img/icons/information.png" alt="Hinweis" title="Hinweis" class="icon" width="16" height="16"><span>Hinweis</span></div><div class="rbox-data">Ohne Zuordnung einer Partei, gilt ein Parlamentarier als parteilos.</div></div><a id="plugin-edit-remarksbox8" href="javascript:void(1)" class="editplugin"><img src="img/icons/wiki_plugin_edit.png" alt="Edit Plugin:remarksbox" title="Edit Plugin:remarksbox" class="icon" width="16" height="16"></a>
<p>
<br>Partei erfassen, deshalb nicht vergessen.
</p>

<div class="clearfix rbox note"><div class="rbox-title"><img src="img/icons/information.png" alt="Hinweis" title="Hinweis" class="icon" width="16" height="16"><span>Tip</span></div><div class="rbox-data">Der Sitzplatz eines Parlamentariers kann auf <a class="wiki external" target="_blank" href="http://www.parlament.ch/D/ORGANE-MITGLIEDER/NATIONALRAT/SITZORDNUNG/Seiten/default.aspx" rel="external nofollow">Parlament.ch Sitzordnung</a><img src="img/icons/external_link.gif" alt="(externer Link)" title="(externer Link)" class="icon" width="15" height="14"> und das Photo kann auf der Biographie des jeweiligen <a class="wiki external" target="_blank" href="http://www.parlament.ch/D/ORGANE-MITGLIEDER/NATIONALRAT/Seiten/default.aspx" rel="_blank external nofollow">Parlamentariers</a><img src="img/icons/external_link.gif" alt="(externer Link)" title="(externer Link)" class="icon" width="15" height="14"> abgerufen werden.</div></div><a id="plugin-edit-remarksbox9" href="javascript:void(1)" class="editplugin"><img src="img/icons/wiki_plugin_edit.png" alt="Edit Plugin:remarksbox" title="Edit Plugin:remarksbox" class="icon" width="16" height="16"></a>
</div>

<b>Parlamentarieranhang</b>
<div class="wiki-table-help">
<p>Innerhalb der Bearbeitungsmaske Parlamentarier können Dateien als Anhang gespeichert werden. z.B. Autorisierungs-E-Mails (als pdf), Korrespondenzen, wichtige Hinweise, Quellen, die der Rückverfolgbarkeit und der Beweisführung für verwendete Informationen dienen.
</p>
</div>'
      . $general_detailed_desc);
      $page->setDescription("{$edit_header_message}" . '<a class="wiki external" target="_blank" href="https://www.parlament.ch/de/organe-home" rel="_blank external nofollow">Parlamentarier</a><img src="img/icons/external_link.gif" alt="(externer Link)" title="(externer Link)" class="icon" width="15" height="14"> des Parlamentes.' . " Klicke <span><i class='icon-question'></i></span> für zusätzliche Infos.$edit_general_hint");
      break;
  case 'person':
      $page->setDetailedDescription($general_detailed_desc);
      $page->setDescription("{$edit_header_message}Diese Tabelle enthält Lobbyisten und Leute mit Zugang ins Parlament. Klicke <span><i class='icon-question'></i></span> für zusätzliche Infos.$edit_general_hint");
      break;
  case 'interessenbindung':
      $page->setDetailedDescription('
<div class="wiki-table-help">
<div class="clearfix rbox note"><div class="rbox-title"><img src="img/icons/information.png" alt="Hinweis" title="Hinweis" class="icon" width="16" height="16"><span>Hinweis</span></div><div class="rbox-data">Das Feld Interessenbindung.beschreibung soll den Bearbeitern einen Hinweis geben. Das Feld wird nicht automatisch ausgewertet.</div></div><a id="plugin-edit-remarksbox10" href="javascript:void(1)" class="editplugin"><img src="img/icons/wiki_plugin_edit.png" alt="Edit Plugin:remarksbox" title="Edit Plugin:remarksbox" class="icon" width="16" height="16"></a>
</div>
'
. $isicv4_doc
. $general_detailed_desc);
      $page->setDescription($edit_header_message . 'Zuordnung der Interessenbindungen der <a class="wiki external" target="_blank" href="https://www.parlament.ch/centers/documents/de/interessen-nr.pdf" rel="_blank external nofollow">National</a><img src="img/icons/external_link.gif" alt="(externer Link)" title="(externer Link)" class="icon" width="15" height="14">- und <a class="wiki external" target="_blank" href="https://www.parlament.ch/centers/documents/de/interessen-sr.pdf" rel="_blank external nofollow">Ständeräte</a><img src="img/icons/external_link.gif" alt="(externer Link)" title="(externer Link)" class="icon" width="15" height="14">.' . " Klicke <span><i class='icon-question'></i></span> für zusätzliche Infos.$edit_general_hint");
      break;
  case 'zutrittsberechtigung':
      $page->setDetailedDescription('<div class="wiki-table-help">
<div class="clearfix rbox note"><div class="rbox-title"><img src="img/icons/information.png" alt="Hinweis" title="Hinweis" class="icon" width="16" height="16"><span>Hinweis</span></div><div class="rbox-data">Die Funktion enthält die bei den Parlamentsdiensten angegebene Funktion. Allfällige Umschreibungen sollten in den Notizen gemacht werden.</div></div>
</div>' . $general_detailed_desc);
      $page->setDescription($edit_header_message . 'Diese Tabelle ordnet einem <a class="wiki external" target="_blank" href="https://www.parlament.ch/de/organe-home" rel="_blank external nofollow">Parlamentarier</a> die <a class="wiki external" target="_blank" href="https://www.parlament.ch/centers/documents/de/zutrittsberechtigte-nr.pdf" rel="_blank external nofollow">zutrittsberechtigten Personen NR</a> / <a class="wiki external" target="_blank" href="https://www.parlament.ch/centers/documents/de/zutrittsberechtigte-sr.pdf" rel="_blank external nofollow">zutrittsberechtigten Personen SR</a> zu.' . " Klicke <span><i class='icon-question'></i></span> für zusätzliche Infos.$edit_general_hint");
      break;
  case 'mandat':
      $page->setDetailedDescription('<div class="wiki-table-help">
<p>Diese Zuordnung ist analog zu den Interessenbindungen der Parlamentarier.
</p>

<div class="clearfix rbox note"><div class="rbox-title"><img src="img/icons/information.png" alt="Hinweis" title="Hinweis" class="icon" width="16" height="16"><span>Hinweis</span></div><div class="rbox-data">Das Feld Mandat.beschreibung soll den Bearbeitern einen Hinweis geben. Das Feld wird nicht automatisch ausgewertet.</div></div><a id="plugin-edit-remarksbox11" href="javascript:void(1)" class="editplugin"><img src="img/icons/wiki_plugin_edit.png" alt="Edit Plugin:remarksbox" title="Edit Plugin:remarksbox" class="icon" width="16" height="16"></a>
</div>' . $general_detailed_desc);
      $page->setDescription($edit_header_message . 'Zuordung von Mandaten  zu Zutrittsberechtigen (analog den Interessenbindungen von Parlamentariern).' . " Klicke <span><i class='icon-question'></i></span> für zusätzliche Infos.$edit_general_hint");
      break;
  case 'in_kommission':
      $page->setDetailedDescription('<div class="wiki-table-help">
<p>WIRD AUTOMATISCH EINGELESEN! Falls veraltete Daten vorhanden sind, bitte den Admin Roland Kurmann benachrichtigen.</p>
</div>
  ' . $general_detailed_desc);
      $page->setDescription($edit_header_message . 'Diese Tabelle ordnet einem <a class="wiki external" target="_blank" href="https://www.parlament.ch/de/organe-home" rel="_blank external nofollow">Parlamentarier</a><img src="img/icons/external_link.gif" alt="(externer Link)" title="(externer Link)" class="icon" height="14" width="15"> seine parlamentarischen <a class="wiki external" target="_blank" href="https://www.parlament.ch/de/organe/kommissionen" rel="_blank external nofollow">Kommissions</a><img src="img/icons/external_link.gif" alt="(externer Link)" title="(externer Link)" class="icon" height="14" width="15">- und <a class="wiki external" target="_blank" href="https://www.parlament.ch/de/organe/delegationen" rel="_blank external nofollow">Delegations</a><img src="img/icons/external_link.gif" alt="(externer Link)" title="(externer Link)" class="icon" height="14" width="15">mitgliedschaften zu. WIRD AUTOMATISCH EINGELESEN!' . " Klicke <span><i class='icon-question'></i></span> für zusätzliche Infos.$edit_general_hint");
      break;
  case 'organisation_beziehung':
      $page->setDetailedDescription('<div class="wiki-table-help">
<div class="clearfix rbox note"><div class="rbox-title"><img src="img/icons/information.png" alt="Hinweis" title="Hinweis" class="icon" width="16" height="16"><span>Hinweis</span></div><div class="rbox-data">Die richtige Angabe von Organisation und Zielorganisation ist wichtig. Eine Organisation bezieht sich auf eine Zielorganisation.</div></div><a id="plugin-edit-remarksbox13" href="javascript:void(1)" class="editplugin"><img src="img/icons/wiki_plugin_edit.png" alt="Edit Plugin:remarksbox" title="Edit Plugin:remarksbox" class="icon" width="16" height="16"></a>
<p>Beispiel: Novartis ist Mitglied von Interpharma.
</p>
<ul><li> Organisation = Novartis
</li><li> Beziehungsart = Mitglied von
</li><li> Zielorganisation = Interpharma
</li></ul><p>
<br>Beispiel: PR-Büro X arbeitet für Novartis.
</p>
<ul><li> Organisation = PR-Büro X
</li><li> Beziehungsart = arbeitet für
</li><li> Zielorganisation = Novartis
</li></ul></div>' . $general_detailed_desc);
      $page->setDescription($edit_header_message . 'Beziehungen wie <em>"Mitglied von"</em> und <em>"arbeitet für"</em> zwischen Organisationen können erfasst werden.' . " Klicke <span><i class='icon-question'></i></span> für zusätzliche Infos.$edit_general_hint");
      break;
  case 'branche':
      $page->setDetailedDescription('<div class="wiki-table-help">
<div class="clearfix rbox note"><div class="rbox-title"><img src="img/icons/information.png" alt="Hinweis" title="Hinweis" class="icon" width="16" height="16"><span>Hinweis</span></div><div class="rbox-data">Branchen sollen einer zuständigen Kommission zugeordnet werden.</div></div><a id="plugin-edit-remarksbox14" href="javascript:void(1)" class="editplugin"><img src="img/icons/wiki_plugin_edit.png" alt="Edit Plugin:remarksbox" title="Edit Plugin:remarksbox" class="icon" width="16" height="16"></a>
</div>'
. $isicv4_doc
. $general_detailed_desc);
      $page->setDescription($edit_header_message . 'Tabelle der Wirtschaftsbranchen.' . " Klicke <span><i class='icon-question'></i></span> für zusätzliche Infos.$edit_general_hint");
      break;
  case 'interessengruppe':
      $page->setDetailedDescription('<div class="wiki-table-help">
<p>Liste der Interessengruppen. Innerhalb einer Branche gibt es normalerweise verschiedene Interessengruppen.
</p>

<p>Beispiel: Das Gesundheitswesen hat die Interessengruppen Pharmaindustrie, Ärzte, Pflegeberufe, Patienten, Spitäler, Krankenkassen, ...
</p>

<p>Interessengruppen versuchen die Politik in ihrem Interesse zu beeinflussen.
</p>
</div>' . $general_detailed_desc);
      $page->setDescription($edit_header_message . 'Liste der Interessengruppen.' . " Klicke <span><i class='icon-question'></i></span> für zusätzliche Infos.$edit_general_hint");
      break;
  case 'kommission':
      $page->setDetailedDescription('<div class="wiki-table-help">
<div class="clearfix rbox note"><div class="rbox-title"><img src="img/icons/information.png" alt="Hinweis" title="Hinweis" class="icon" height="16" width="16"><span>Hinweis</span></div><div class="rbox-data">
<ul><li> Delegationen im engeren Sinne (Bsp GPDel - Geschäftsprüfungsdelegation) sind Subkommissionen [Typ=subkommission]. Die zugehörige "Mutterkommission" muss angegeben werden.
</li><li> Delegationen im weiteren Sinne (Bsp ER - Parlamentarische Versammlung des Europarates) sind Spezialkommissionen [Typ=spezialkommission].
</li></ul></div></div><a id="plugin-edit-remarksbox12" href="javascript:void(1)" class="editplugin"><img src="img/icons/wiki_plugin_edit.png" alt="Edit Plugin:remarksbox" title="Edit Plugin:remarksbox" class="icon" height="16" width="16"></a>
<p>
</p>
<div class="clearfix rbox note"><div class="rbox-title"><img src="img/icons/information.png" alt="Hinweis" title="Hinweis" class="icon" height="16" width="16"><span>Hinweis</span></div><div class="rbox-data">
<p>Das Feld Sachbereiche enthält eine Aufzählung der Sachbereiche dieser Kommission wie auf parlament.ch angegeben. Die einzelnen Punkte werden durch ";" (ein Strichpunkt) getrennt. Siehe Beispiel <a class="wiki external" target="_blank" href="https://cms.lobbywatch.ch/bearbeitung/kommission.php?operation=view&amp;pk0=1" rel="_blank external nofollow">SGK</a><img src="img/icons/external_link.gif" alt="(externer Link)" title="(externer Link)" class="icon" height="14" width="15"> (<a class="wiki external" target="_blank" href="https://www.parlament.ch/de/organe/kommissionen/sachbereichskommissionen/kommissionen-sgk" rel="_blank external nofollow">parlament.ch</a><img src="img/icons/external_link.gif" alt="(externer Link)" title="(externer Link)" class="icon" height="14" width="15">)
</p>
</div></div><a id="plugin-edit-remarksbox13" href="javascript:void(1)" class="editplugin"><img src="img/icons/wiki_plugin_edit.png" alt="Edit Plugin:remarksbox" title="Edit Plugin:remarksbox" class="icon" height="16" width="16"></a>
</div>
  ' . $general_detailed_desc);
      $page->setDescription($edit_header_message . '<a class="wiki external" target="_blank" href="https://www.parlament.ch/de/organe/kommissionen" rel="_blank external nofollow">Kommissionen</a><img src="img/icons/external_link.gif" alt="(externer Link)" title="(externer Link)" class="icon" height="14" width="15"> und <a class="wiki external" target="_blank" href="https://www.parlament.ch/de/organe/delegationen" rel="_blank external nofollow">Delegationen</a><img src="img/icons/external_link.gif" alt="(externer Link)" title="(externer Link)" class="icon" height="14" width="15"> des Parlamentes. WIRD AUTOMATISCH EINGELESEN.' . " Klicke <span><i class='icon-question'></i></span> für zusätzliche Infos.$edit_general_hint");
      break;
    case 'partei':
      $page->setDetailedDescription($general_detailed_desc);
      $page->setDescription("{$edit_header_message}Liste der Parteien. Klicke <span><i class='icon-question'></i></span> für zusätzliche Infos.$edit_general_hint");
      break;
    case 'fraktion':
      $page->setDetailedDescription($general_detailed_desc);
      $page->setDescription("{$edit_header_message}Tabelle der Bundeshausfraktionen. Klicke <span><i class='icon-question'></i></span> für zusätzliche Infos.$edit_general_hint");
      break;
    case 'kanton':
      $page->setDetailedDescription($general_detailed_desc);
      $page->setDescription("{$edit_header_message}Tabelle mit Daten zu den Kantonen. Klicke <span><i class='icon-question'></i></span> für zusätzliche Infos.$edit_general_hint");
      break;
    case 'settings':
      $page->setDescription("{$edit_header_message}Einstellungen für Lobbywatch. Die Funktionsweise kann gesteuert werden. Beispielsweise können Angaben zum Arbeitsablauf gesetzt werden.$edit_general_hint");
      break;
    case 'settings_category':
      $page->setDescription("{$edit_header_message}Die Einstellungsparameter können kategorisiert und gruppiert werden.$edit_general_hint");
      break;
    case 'translation_source':
      $page->setDescription("{$edit_header_message}Tabelle der Begriffe von Lobbywatch, welche für die Webseite gebraucht werden. In dieser Tabelle stehen die deutschen Wörter.$edit_general_hint");
      break;
    case 'translation_target':
      $page->setDescription("{$edit_header_message}Übersetzungen der von Lobbywatch verwendeten Wörter.$edit_general_hint");
      break;
    case 'user':
      $page->setDetailedDescription('Neue Benutzer müssen über das Admin-Panel angelegt werden.');
      $page->setDescription("{$edit_header_message}Tabelle der DB-Bearbeitungsbenutzer.$edit_general_hint");
      break;
    case 'q_last_updated_tables':
      $page->setDescription("{$edit_header_message}Zeigt die letzten Änderungen der Tabellen an.$edit_general_hint");
      break;
    }
}

function customOnPreparePage(Page $page) {
}

/**
 * Navigation
 * http://www.sqlmaestro.com/products/mysql/phpgenerator/help/01_03_04_29_global_on_customize_page_list/
 * @param CommonPage $page
 * @param PageList $pageList
 */
function globalOnCustomizePageList(CommonPage $page, PageList $pageList) {
  $pageList->AddPageAt(new PageLink('<span class="overview">Vergütungsübersicht</span>', 'parlamentarier_overview.php', 'Parlamentarierübersicht auf einer Seite mit den Interessenbindungen inkl. Vergütung', false, false, 'Auswertungen'), 0);
  $pageList->AddPageAt(new PageLink('<span class="state">Erstellungsanteil</span>', 'anteil.php?option=erstellungsanteil', 'Wer hat wieviele Datens&auml;tze erstellt?', false, false, 'Auswertungen'), 1);
  $pageList->AddPageAt(new PageLink('<span class="state">Bearbeitungsanteil</span>', 'anteil.php?option=bearbeitungsanteil', 'Wer hat wieviele Datens&auml;tze abgeschlossen?', false, false, 'Auswertungen'), 2);

  $pageList->AddGroup('Links');

  $pageList->AddPage(new PageLink('<span class="website">Frontend</span>', 'https://lobbywatch.ch', 'Frontend', false, false, 'Links'));
  $pageList->AddPage(new PageLink('<span class="website">CMS</span>', '/', 'CMS', false, false, 'Links'));
  $pageList->AddPage(new PageLink('<span class="wiki">Wiki</span>', '/wiki', 'Wiki', false, false, 'Links'));
  $pageList->AddPage(new PageLink('<span class="kommissionen">Kommissionen</span>', '/de/daten/kommission', 'Kommissionen', false, false, 'Links'));
}

/**
 * http://www.sqlmaestro.com/products/mysql/phpgenerator/help/01_03_04_14_global_after_update_record/
 */
function globalOnAfterInsertRecord(Page $page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime) {
}

/**
 * http://www.sqlmaestro.com/products/mysql/phpgenerator/help/01_03_04_15_global_after_insert_record/
 */
function globalOnAfterUpdateRecord(Page $page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime) {
}

/**
 * http://www.sqlmaestro.com/products/mysql/phpgenerator/help/01_03_04_16_global_after_delete_record/
 */
function globalOnAfterDeleteRecord(Page $page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime) {
}

/**
 * This event allows you to customize the layout for View, Edit, and Insert forms.
 *
 * http://www.sqlmaestro.com/products/mysql/phpgenerator/help/01_03_04_31_on_get_column_form_layout/
 *
 * Layout structure
 * The layout has the following hierarchical structure: layout -> groups -> rows -> columns. This means you can add groups to the layout, rows to groups, and columns to rows.
 *
 * Layout mode
 * By default all forms are horizontal i.e. the control label is placed on the left of the editor (for vertical forms the label is placed on the top of the editor). To create a vertical form, use the following call:
 * $layout->setMode(FormLayoutMode::VERTICAL);
 *
 * Adding a group
 * To add a new group to the layout, use the addGroup method of the FormLayout class:
 * function addGroup($name = null, $width = 12);
 * This function returns an instance of the FormLayoutGroup class that can be later used to add rows to the new group (see below). Width is provided in relative units, possible values are integers from 1 to 12.
 *
 * Adding rows to a group
 * To add a new row to a group, use the addRow method of the FormLayoutGroup class (see above):
 * function addRow();
 * This function has no parameters and returns an instance of the FormLayoutRow class that can be later used to add controls to the new row (see below).
 *
 * Adding controls to a row
 * To add a new control to a row, use the addCol method of the FormLayoutRow class (see above):
 * function addCol($column, $inputWidth = null, $labelWidth = null);
 *
 * @param string $mode The form mode. Possible values are "edit", "insert", and "view".
 * @param FixedKeysArray $columns The associative array of columns displayed in the form.
 * @param FormLayout $layout An instance of the FormLayout class.
 */
function customOnGetCustomFormLayout(Page $page, $mode, FixedKeysArray $columns, FormLayout $layout) {
  if ($page->GetPageId() === 'partei') {
  }
}

/**
 * This event allows you to setup multi-row grid header.
 *
 * http://www.sqlmaestro.com/products/mysql/phpgenerator/help/01_03_04_32_on_get_custom_column_group/
 *
 * You can organize columns in logical groups and display them using multi-row header representation. A column group is visually represented by a header displayed above the headers of the columns it combines. Each group can contain data columns as well as other groups.
 *
 *
 * @param Page $page
 * @param FixedKeysArray $columns The associative array of column headers.
 * @param ViewColumnGroup $columnGroup The default (root) group. By default all column headers are placed in this group.
 */
function customOnGetCustomColumnGroup(Page $page, FixedKeysArray $columns, ViewColumnGroup $columnGroup) {
}

// Call: defaultOnGetCustomTemplate($this, $part, $mode, $result, $params);
function defaultOnGetCustomTemplate(Page $page, $part, $mode, &$result, &$params) {
  if ($params == null) {
    $params = [];
  }
  // MIGR OnGetCustomTemplate()
}

/**
 * http://www.sqlmaestro.com/products/mysql/phpgenerator/help/01_03_04_12_global_get_custom_template/
 *
 * @param PageType $type the type of the web page to be affected by the template. Possible values are PageType::Data, PageType::Home, PageType::Login, and PageType::Admin
 * @param PagePart $part the part of the page. Possible values are PagePart::Grid, PagePart::GridRow, PagePart::VerticalGrid, PagePart::PageList, PagePart::Layout, and PagePart::RecordCard
 * @param PageMode $mode the current state of thje page. Possible values are PageMode::ViewAll, PageMode::View, PageMode::Edit, PageMode::Insert, PageMode::ModalView, PageMode::ModalEdit, and PageMode::ModalInsert
 * @param string $result a variable to store the file name of the template. The file itself should be uploaded to the components/templates/custom_template directory
 * @param array $params an array of additional parameters you want to assign to the template
 * @param CommonPage $page the page to be displayed or null
 */
function globalOnGetCustomTemplate($type, $part, $mode, &$result, &$params, CommonPage $page) {
  if ($params == null) {
    $params = [];
  }
  if ($part === PagePart::Layout) {
    $params['PHPGenVersion'] = GENERATOR_VERSION;
    $result = 'common/layout.tpl';
  }

  if ($page instanceof Page) {
    fillHintParams($page, $params);
  }

  $params['hash_css_main'] = $GLOBALS['hash_css_main'];
  $params['hash_css_custom'] = $GLOBALS['hash_css_custom'];
  $params['hash_js_main_bundle'] = $GLOBALS['hash_js_main_bundle'];
}

function customGetPageInfos(array $pageInfos) {
  return $pageInfos;
}

// Must not be in utils.php since in Drupal we use a similar function
function set_db_session_parameters($con) {
  return utils_set_db_session_parameters($con);
}

function custom_OnEncryptPassword($password, &$result) {
  $result = password_hash($password, PASSWORD_DEFAULT);
}

function custom_OnVerifyPassword($enteredPassword, $encryptedPassword, &$result) {
  $result = password_verify($enteredPassword, $encryptedPassword);
}

// custom_OnBeforeSaveWissensartikelLink($page, $rowData, $tableName, $cancel, $message, $messageDisplayTime);
function custom_OnBeforeSaveWissensartikelLink($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime) {
  // Ref for events: http://www.sqlmaestro.com/products/mysql/phpgenerator/help/01_03_04_page_editor_events/

  // Extract id from column target_table_name_with_id to target_id because dependent lookup cannot handle it
  $rowData['target_id'] = explode('#', $rowData['target_table_name_with_id'])[1] ?? null;
}

// custom_OnGetCustomColumnGroup_wissensartikel($columns, $columnGroup);
function custom_OnGetCustomColumnGroup_wissensartikel(FixedKeysArray $columns, ViewColumnGroup $columnGroup)
{
    $columnGroup->add(new ViewColumnGroup('Lobbypedia-Artikel',
        [
            $columns['node_id'],
            $columns['language'],
            $columns['nid'],
            $columns['status'],
        ]
    ));
    $columnGroup->add(new ViewColumnGroup('Datensatz',
        [
            $columns['target_table_name'],
            $columns['target_table_name_with_id'],
            $columns['target_id'],
        ]
    ));
}
