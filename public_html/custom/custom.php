<?php

// ATTENTION: THIS FILE IS ENCODED AS ISO-8859-1

timer_start('page_build');

require_once dirname(__FILE__) . "/../settings/settings.php";
require_once dirname(__FILE__) . "/../common/utils.php";
require_once dirname(__FILE__) . '/../bearbeitung/components/grid/grid_state.php';
require_once dirname(__FILE__) . '/../bearbeitung/components/common.php';

// define('LOBBYWATCH_IS_FORMS', true);

global $lobbywatch_is_forms;
$lobbywatch_is_forms = true;

define('OPERATION_INPUT_FINISHED_SELECTED', 'finsel');
define('OPERATION_DE_INPUT_FINISHED_SELECTED', 'definsel');
define('OPERATION_CONTROLLED_SELECTED', 'consel');
define('OPERATION_DE_CONTROLLED_SELECTED', 'deconsel');
define('OPERATION_AUTHORIZATION_SENT_SELECTED', 'sndsel');
define('OPERATION_DE_AUTHORIZATION_SENT_SELECTED', 'desndsel');
define('OPERATION_AUTHORIZE_SELECTED', 'autsel');
define('OPERATION_DE_AUTHORIZE_SELECTED', 'deautsel');
define('OPERATION_RELEASE_SELECTED', 'relsel');
define('OPERATION_DE_RELEASE_SELECTED', 'derelsel');
define('OPERATION_SET_IMRATBIS_SELECTED', 'setimratbissel');
define('OPERATION_CLEAR_IMRATBIS_SELECTED', 'clearimratbissel');

$edit_header_message = '';
$edit_header_message .= ($env !== 'PRODUCTION' ? "<p>Umgebung: <span style=\"background-color:red\">$env</span></p>" : '');
/*
$edit_header_message = "<div class=\"simplebox\"><b>Stand (Version $version " . ($deploy_date_short === $build_date_short ? "<span title=\"Generiert und Hochgeladen am\">$deploy_date_short</span>" : "D<span title=\"Hochgeladen am\">$deploy_date_short</span>/G<span title=\"Forumlare generiert am\">$build_date_short</span>") . ")</b>" . ($env !== 'PRODUCTION' ? " <span style=\"background-color:red\">$env</span>" : '') . ": <i>Alle Tabellen können bearbeitet werden.</i>
<ul>
<li>Am besten werden zuerst ein, zwei komplette Fälle quer durch alle Tabellen durchgespielt.
<li>Das Bearbeiten von alten und das Erfassen von neuen Daten sollte systematisch und abgesprochen erfolgen, wegen der grossen Datenmenge und der Neuheit der Eingabeformulare.</ul></div>";
*/

$edit_general_hint = '<div class="clearfix rbox note"><div class="rbox-title"><img src="' . util_data_uri('img/icons/book_open.png') . '" alt="Hinweis" title="Hinweis" class="icon" width="16" height="16"><span>Hinweis</span></div><div class="rbox-data">Bitte die Bearbeitungsdokumentation (vor einer Bearbeitung) beachten und bei Unklarheiten anpassen, siehe <a href="http://lobbywatch.ch/wiki/tiki-index.php?page=Datenerfassung&structure=Lobbywatch-Wiki" target="_blank">Wiki Datenbearbeitung</a> und <a href="/sites/lobbywatch.ch/app' . $env_dir . 'lobbywatch_datenmodell_simplified.pdf">Vereinfachtes Datenmodell</a> (Komplex: <a href="/sites/lobbywatch.ch/app' . $env_dir . 'lobbywatch_datenmodell_1page.pdf">1 Seite</a> /<a href="/sites/lobbywatch.ch/app' . $env_dir . 'lobbywatch_datenmodell.pdf">4 Seiten</a>).</div></div>';

lobbywatch_language_initialize('de');

// $params = session_get_cookie_params();
// df($params, "Session params");
// df(__FILE__);
// df(dirname(__FILE__));
// df($_REQUEST, 'request');
// df($_SERVER, 'server');

//df_clean();

function setupRSS($page, $dataset) {
  $title = ucwords ( $page->GetCaption () );
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
  $generator = new DatasetRssGenerator ( $dataset, convert_utf8 ( $title . ' RSS' ), $base_url, convert_utf8('Änderungen der Lobbywatch-Datenbank als RSS Feed'), convert_utf8 ($rss_title), $table . ' at %id%', convert_utf8 ($rss_body) );
  $generator->SetItemPublicationDateFieldName ( 'updated_date' );
  $generator->SetOrderByFieldName ( 'updated_date' );
  $generator->SetOrderType ( otDescending );

  return $generator;
}

function convert_utf8($text) {
  return ConvertTextToEncoding($text, GetAnsiEncoding(), 'UTF-8' );
}

function convert_ansi($text) {
  return ConvertTextToEncoding($text, 'UTF-8', GetAnsiEncoding());
}

// Call: defaultOnGetCustomTemplate($this, $part, $mode, $result, $params);
function defaultOnGetCustomTemplate(Page $page, $part, $mode, &$result, &$params)
{
  if ($part == PagePart::VerticalGrid && $mode == PageMode::Edit) {
    $result = 'edit/grid.tpl';
  } else if ($part == PagePart::VerticalGrid && $mode == PageMode::Insert) {
    $result = 'insert/grid.tpl';
  } else if ($part == PagePart::RecordCard && $mode == PageMode::View) {
    $result = 'view/grid.tpl';
  } else if ($part == PagePart::Grid && $mode == PageMode::ViewAll) {
    $result = 'list/grid.tpl';
  } else if ($part == PagePart::PageList) {
    $result = 'page_list.tpl';
  }

  fillHintParams($page, $params);
}

function fillHintParams(Page $page, &$params) {
  // Fill info hints
  $hints = array();
  $minimal_fields = array();
  $fr_field_names = array();
  $fr_field_descriptions = array();
  $form_translations = array();
//   df($page->GetGrid()->GetDataset()->GetName(), '$page->GetGrid()->GetDataset()->GetName()');
//    df($page->GetDataset()->GetName(), '$page->GetDataset()->GetName()');
  $table_name = getTableName($page);
  foreach($page->GetGrid()->GetViewColumns() as $column) {
    $raw_name = $column->GetName();
    $name = preg_replace('/^(.*?_id).*/', '\1', $raw_name);
    $name = preg_replace('/_anzeige_name$/', '', $name);
    $hint_de = htmlspecialchars($column->GetDescription());

    $minimal_fields[$name] = is_minimal_field($table_name, $name);

//     df("Names: $raw_name -> $name", 'fields');
//     df($column->GetData(), 'field data');
//     df($column->GetCaption(), 'field caption');
    if ($column->IsDataColumn()) {
      $field_translation_key = "$table_name.$name";
      $field_name_de = $column->GetCaption();
//       df("$field_translation_key = $field_name_de", 'field');
//       $field_name_fr = "$field_name_de FR";
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
      //       df("$table_name.$name.hint = $field_hint_de", 'field');
      $fr_field_descriptions[$name] = "$table_name.$name.hint";

      $hints[$name] = ($hint_fr != '' ? "<p>$hint_fr</p><hr>" : '') . "<p>$hint_de</p>";

      $date = date('d.m.Y');
      $form_translations[] = "$field_translation_key\t\t$date\t\tforms\t\t$field_translation_key\t$field_name_de\t" . ($field_name_fr != $field_name_de ? $field_name_fr : '');
      $form_translations[] = "$field_hint_translation_key\t\t$date\t\tforms\t\t$field_hint_translation_key\t$field_hint_de\t$field_hint_fr";
    }
  }
  $params = array_merge($params, array( 'Hints' => $hints, 'MinimalFields' => $minimal_fields, 'FrFieldNames' => $fr_field_names, 'FrFieldDescriptions' => $fr_field_descriptions));
//   df($params, 'params');
//   df($form_translations, 'form translations');
//   df("\n" . implode("\n", $form_translations) . "\n", 'form translations');
}

function getTableName(Page $page) {
  return preg_replace('/`/', '', $page->GetDataset()->GetName());
}
function is_minimal_field($table, $field) {
//   df("$table.$field", 'is_minimal_field');
  switch ($table) {
    case 'parlamentarier':
      switch ($field) {
        case 'email':
        case 'geburtstag':
//         case 'im_rat_seit': is required
        case 'geschlecht':
        case 'kleinbild':
        case 'parlament_biografie_id':
        case 'beruf':
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
//         case 'rechtsform': now required
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

//   // Fill info hints
//   $hints = array();
//   foreach($page->GetGrid()->GetViewColumns() as $column) {
//     $raw_name = $column->GetName();
//     $name = preg_replace('/^(.*?_id).*/', '\1', $raw_name);
//     $name = preg_replace('/_anzeige_name$/', '', $name);
//     $hints[$name] = htmlspecialchars($column->GetDescription());
// //      df("Names: $raw_name -> $name");
//   }
//   $GLOBALS['customParams'] = array( 'Hints' => $hints);
}

function custom_set_db_session_parameters($page) {
  $connection = getDBConnection();

  $connection->ExecSQL("SET SESSION wait_timeout=120;");

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
  $sessionParamName = 'alreadyCalledDefaultFilter_' . $page->GetDataset()->GetName();
//   df($_SESSION, 'session before');
//   df($_POST, 'post before');
//   df(GetApplication()->IsSessionVariableSet($sessionParamName), $sessionParamName);
  if(!($column = ($page->AdvancedSearchControl->FindSearchColumnByName('bis')))) {
    if(!($column = ($page->AdvancedSearchControl->FindSearchColumnByName('im_rat_bis')))) {
      // no bis or im_rat_bis column found, return
      return false;
    }
  }
//   df($column->IsFilterActive(), 'IsFilterActive' );
  if (!GetApplication()->IsSessionVariableSet($sessionParamName) && !$column->GetFilterIndex()) {
    $column->SetFilterIndex('IS NULL');
    $column->SetApplyNotOperator(false);
//     df($column->IsFilterActive(), 'IsFilterActive after' );
    $column->SaveSearchValuesToSession();
    GetApplication()->SetSessionVariable($page->AdvancedSearchControl->getName() . 'SearchType', 1);
//     df($_SESSION, 'session after');
    $page->AdvancedSearchControl->ProcessMessages();
    GetApplication()->SetSessionVariable($sessionParamName, true);
  }

}

function add_custom_header(&$page, &$result) {
//   <script>
//   (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
//     (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
//     m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
//   })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

//   ga('create', 'UA-45624114-1', 'lobbywatch.ch');
//   ga('send', 'pageview');

//   </script>

$result = <<<'EOD'
  <meta name="Generator" content="PHP Generator for MySQL (http://sqlmaestro.com)" />
  <link rel="shortcut icon" href="/favicon.png" type="image/png" />

EOD;
$result .= <<<EOD
  <link rel="alternate" type="application/rss+xml" title="{$page->GetCaption()} Update RSS" href="{$page->GetRssLink()}" />
EOD;

}

function parlamentarier_update_photo_metadata($page, &$rowData, &$cancel, &$message, $tableName)
{
//   df($rowData, 'parlamentarier_update_photo_metadata $rowData');
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
    $rowData['kleinbild'] = $path_parts['basename'];
  } else {
    $rowData['photo'] = null;
    $rowData['photo_dateiname'] = null;
    $rowData['photo_dateierweiterung'] = null;
    $rowData['photo_dateiname_voll'] = null;
    $rowData['photo_mime_type'] = null;
    $rowData['kleinbild'] = null;
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
//    df($rowData, 'parlamentarier_remove_old_photo $rowData');
//    df($tableName);
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

  $values = array();
  $page->GetConnection()->ExecQueryToArray("SELECT `id`, `photo` FROM $tableName WHERE `id`=$id", $values);
//   df("SELECT `photo` FROM $tableName WHERE id=$id");
//   df($values);
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
  //   df($rowData, 'parlamentarier_update_photo_metadata $rowData');
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

    // Remove path, we want it relative
    //$rowData['symbol_klein'] = $path_parts['basename'];
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
  //    df($rowData, 'parlamentarier_remove_old_photo $rowData');
  //    df($tableName);
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

  $values = array();
  $page->GetConnection()->ExecQueryToArray("SELECT `id`, `symbol_rel` FROM $tableName WHERE `id`=$id", $values);
  //   df("SELECT `photo` FROM $tableName WHERE id=$id");
  //   df($values);
  if (count($values) > 0 ) {
    $old_file = $values[0]['symbol_rel'];
    $small_old_file= $values[0]['symbol_klein_rel'];
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
 *
 * @param unknown $page
 * @param unknown $rowData
 * @param unknown $cancel
 * @param unknown $message
 * @param unknown $tableName
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

function parlamentarier_check_imRatBis($page, &$rowData, &$cancel, &$message, $tableName)
{
//   df($rowData, 'parlamentarier_check_imRatBis $rowData');
  if (isset($rowData['im_rat_seit']) || isset($rowData['im_rat_bis'])) {
    $imRatSeit = $rowData['im_rat_seit'];
    $imRatBis = $rowData['im_rat_bis'];
  } else {
    return;
  }

// df($imRatBis);
// df($imRatBis === null);
// df($imRatBis === '');
// df($imRatBis->GetTimestamp());
// df($imRatBis->GetDateTime());

  // Roland, 20.04.2014: We support since v1.12 im_rat_bis in the future
//   if ($imRatBis !== null && $imRatBis->GetTimestamp() > SMDateTime::Now()->GetTimestamp()) {
//     $cancel = true;
//     $message = '"Im Rat bis"-Datum darf nicht in der Zukunft liegen: ' . $imRatBis->ToString('d.m.Y');
//   }

  if ($imRatSeit !== null && $imRatBis !== null && $imRatSeit->GetTimestamp() >= $imRatBis->GetTimestamp()) {
    $cancel = true;
    $message = '"Im Rat bis"-Datum darf nicht kleiner als "Im Rat seit"-Datum sein: ' . $imRatBis->ToString('d.m.Y');
  }

}

function check_bis_date($page, &$rowData, &$cancel, &$message, $tableName)
{
// df($rowData);
  if (isset($rowData['von']) || isset($rowData['bis'])) {
    $von = $rowData['von'];
    $bis = $rowData['bis'];
  } else {
    return;
  }
// df($bis);
// df($bis->GetTimestamp());

  // Roland, 20.04.2014: We support since v1.12 bis in the future
//   if ($bis !== null && $bis->GetTimestamp() > SMDateTime::Now()->GetTimestamp()) {
//     $cancel = true;
//     $message = 'Bis-Datum darf nicht in der Zukunft liegen: ' . $bis->ToString('d.m.Y');
//   }
  if ($von !== null && $bis !== null && $von->GetTimestamp() >= $bis->GetTimestamp()) {
    $cancel = true;
    $message = 'Bis-Datum darf nicht kleiner als Von-Datum sein: ' . $bis->ToString('d.m.Y');
  }
}

function check_organisation_interessengruppe_order($page, &$rowData, &$cancel, &$message, $tableName)
{
//   df($rowData, 'check_organisation_interessengruppe_order $rowData');

  if ((isset($rowData['interessengruppe2_id']) && !isset($rowData['interessengruppe_id'])) ||
    (isset($rowData['interessengruppe3_id']) && !isset($rowData['interessengruppe2_id']))) {
    $cancel = true;
    $message = 'Lücke in den Interessengruppen. Bitte zuerst Interessengruppe 1, dann 2 und 3 einfüllen.';
  }
}


function clean_fields(/*$page,*/ &$rowData /*, &$cancel, &$message, $tableName*/)
{
//   df($rowData);
  foreach($rowData as $name => &$value) {
    if (is_string($value)) {
      $value = trim($value);
    }
  }
  unset($value);
//   df($rowData);
}


abstract class SelectedOperationGridState extends GridState {
  protected $date;

  protected function DoCanChangeData(&$rowValues, &$message) {
    $cancel = false;
    $this->grid->BeforeUpdateRecord->Fire ( array (
        $this->GetPage (),
        &$rowValues,
        &$cancel,
        &$message,
        $this->GetDataset ()->GetName ()
    ) );
    return ! $cancel;
  }
  protected function DoAfterChangeData($rowValues) {
    $this->grid->AfterUpdateRecord->Fire ( array (
        $this->GetPage (),
        &$rowValues,
        $this->GetDataset ()->GetName ()
    ) );
  }
  protected abstract function DoOperation();

  protected function isValidDate($date) {
    $date_array = date_parse($date);
    return preg_match('/^(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[012])\.(20)\d\d$/', $date) && checkdate($date_array["month"], $date_array["day"], $date_array["year"]);
  }

  // Similar to globalOnBeforeUpdate
  protected function setUpdatedMetaData() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $userName = $this->GetPage ()->GetEnvVar ( 'CURRENT_USER_NAME' );
    $datetime = $this->GetPage ()->GetEnvVar ( 'CURRENT_DATETIME' );

    $this->grid->GetDataset ()->SetFieldValueByName ( 'updated_visa', $userName );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'updated_date', $datetime );
  }

  public function ProcessMessages() {
    // df(GetApplication ()->GetPOSTValue ( 'recordCount' ), 'recCount');
    $primaryKeysArray = array ();
    // df($_POST, 'post');
    for($i = 0; $i < GetApplication ()->GetPOSTValue ( 'recordCount' ); $i ++) {
      if (GetApplication ()->IsPOSTValueSet ( 'rec' . $i )) {
        // TODO : move GetPrimaryKeyFieldNames function to private
        $primaryKeys = array ();
        $primaryKeyNames = $this->grid->GetDataset ()->GetPrimaryKeyFieldNames ();
        for($j = 0; $j < count ( $primaryKeyNames ); $j ++)
          $primaryKeys [] = GetApplication ()->GetPOSTValue ( 'rec' . $i . '_pk' . $j );
        $primaryKeysArray [] = $primaryKeys;
      }
    }

    // df($primaryKeysArray);

    $inlineInsertedRecordPrimaryKeyNames = GetApplication ()->GetSuperGlobals ()->GetPostVariablesIf ( create_function ( '$str', 'return StringUtils::StartsWith($str, \'inline_inserted_rec_\') && !StringUtils::Contains($str, \'pk\');' ) );

    // df($inlineInsertedRecordPrimaryKeyNames);

    foreach ( $inlineInsertedRecordPrimaryKeyNames as $name => $value ) {
      $primaryKeys = array ();
      $primaryKeyNames = $this->grid->GetDataset ()->GetPrimaryKeyFieldNames ();
      for($i = 0; $i < count ( $primaryKeyNames ); $i ++)
        $primaryKeys [] = GetApplication ()->GetSuperGlobals ()->GetPostValue ( $name . '_pk' . $i );
      $primaryKeysArray [] = $primaryKeys;
    }

    // df($primaryKeysArray);

    $input_date = GetApplication ()->GetPOSTValue ( 'date' );
//     df('Dates:');
//     df($input_date);
//     df($this->GetPage ()->GetEnvVar ( 'CURRENT_DATETIME' ));
    if ($this->isValidDate($input_date)) {
      $this->date = $input_date;
    } else { // includes empty date
      $this->date = $this->GetPage ()->GetEnvVar ( 'CURRENT_DATETIME' );
    }
//     df($this->date);

    foreach ( $primaryKeysArray as $primaryKeyValues ) {
      $this->grid->GetDataset ()->SetSingleRecordState ( $primaryKeyValues );
      $this->grid->GetDataset ()->Open ();
      $this->grid->GetDataset ()->Edit ();

      if ($this->grid->GetDataset ()->Next ()) {
        $message = '';

        $fieldValues = $this->grid->GetDataset ()->GetCurrentFieldValues ();
        if ($this->CanChangeData ( $fieldValues, $message )) {
          try {
            $this->DoOperation ();
            $this->setUpdatedMetaData();
            $this->grid->GetDataset ()->Post ();
            // Refetch field values as the may have changed
            $fieldValues = $this->grid->GetDataset ()->GetCurrentFieldValues ();
            $this->DoAfterChangeData ( $fieldValues );
          } catch ( Exception $e ) {
            $this->grid->GetDataset ()->SetAllRecordsState ();
            $this->ChangeState ( OPERATION_VIEWALL );
            $this->SetGridErrorMessage ( $e );
            return;
          }
        } else {
          $this->grid->GetDataset ()->SetAllRecordsState ();
          $this->ChangeState ( OPERATION_VIEWALL );
          $this->SetGridSimpleErrorMessage ( $message );
          return;
        }
      }
      $this->grid->GetDataset ()->Close ();
    }

    $this->ApplyState ( OPERATION_VIEWALL );
  }
}

class InputFinishedSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $userName = $this->GetPage ()->GetEnvVar ( 'CURRENT_USER_NAME' );
    $datetime = $this->GetPage ()->GetEnvVar ( 'CURRENT_DATETIME' );

    $this->grid->GetDataset ()->SetFieldValueByName ( 'eingabe_abgeschlossen_visa', $userName );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'eingabe_abgeschlossen_datum', $datetime );
  }
}
class DeInputFinishedSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset ()->SetFieldValueByName ( 'eingabe_abgeschlossen_visa', null );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'eingabe_abgeschlossen_datum', null );
      }
}

class ControlledSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $userName = $this->GetPage ()->GetEnvVar ( 'CURRENT_USER_NAME' );
    $datetime = $this->GetPage ()->GetEnvVar ( 'CURRENT_DATETIME' );

    $this->grid->GetDataset ()->SetFieldValueByName ( 'kontrolliert_visa', $userName );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'kontrolliert_datum', $datetime );
  }
}
class DeControlledSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset ()->SetFieldValueByName ( 'kontrolliert_visa', null );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'kontrolliert_datum', null );
  }
}

class AuthorizationSentSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $userName = $this->GetPage ()->GetEnvVar ( 'CURRENT_USER_NAME' );
    $datetime = $this->GetPage ()->GetEnvVar ( 'CURRENT_DATETIME' );

    $this->grid->GetDataset ()->SetFieldValueByName ( 'autorisierung_verschickt_visa', $userName );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'autorisierung_verschickt_datum', $this->date );
  }
}
class DeAuthorizationSentSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset ()->SetFieldValueByName ( 'autorisierung_verschickt_visa', null );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'autorisierung_verschickt_datum', null );
  }
}

class AuthorizeSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $userName = $this->GetPage ()->GetEnvVar ( 'CURRENT_USER_NAME' );

    $this->grid->GetDataset ()->SetFieldValueByName ( 'autorisiert_visa', $userName );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'autorisiert_datum', $this->date );
  }
}
class DeAuthorizeSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset ()->SetFieldValueByName ( 'autorisiert_visa', null );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'autorisiert_datum', null );
  }
}

class ReleaseSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $userName = $this->GetPage ()->GetEnvVar ( 'CURRENT_USER_NAME' );

    $this->grid->GetDataset ()->SetFieldValueByName ( 'freigabe_visa', $userName );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'freigabe_datum', $this->date );
  }
}
class DeReleaseSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset ()->SetFieldValueByName ( 'freigabe_visa', null );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'freigabe_datum', null );
  }
}

class SetImRatBisSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    $this->grid->GetDataset ()->SetFieldValueByName ( 'im_rat_bis', $this->date );
  }
}
class ClearImRatBisSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    $this->grid->GetDataset ()->SetFieldValueByName ( 'im_rat_bis', null );
  }
}

function add_more_navigation_links(&$result) {
  $result->AddGroup('Links');

  $result->AddPage(new PageLink('<span class="website">Website</span>', '/', 'Homepage', false, true, 'Links'));
  $result->AddPage(new PageLink('<span class="wiki">Wiki</span>', '/wiki', 'Wiki', false, false, 'Links'));
  $result->AddPage(new PageLink('<span class="kommissionen">Kommissionen</span>', '/de/daten/kommission', 'Kommissionen', false, false, 'Links'));
  $result->AddPage(new PageLink('<span class="auswertung">Auswertung</span>', $GLOBALS['env_dir'] . 'auswertung', 'Auswertung ' . $GLOBALS['env'] , false, false, 'Links'));
//   $result->AddPage(new PageLink('<span class="state">Stand SGK</span>', 'auswertung/anteil.php?option=kommission&id=1&id2=47', 'Stand SGK', false, true, 'Links'));
//   $result->AddPage(new PageLink('<span class="state">Stand UREK</span>', 'auswertung/anteil.php?option=kommission&id=3&id2=48', 'Stand UREK', false, false, 'Links'));
  $result->AddPage(new PageLink('<span class="state">Stand WAK</span>', 'auswertung/anteil.php?option=kommission&id=11&id2=52', 'Stand WAK', false, false, 'Links'));
  $result->AddPage(new PageLink('<span class="state">Stand SiK</span>', 'auswertung/anteil.php?option=kommission&id=7&id2=50', 'Stand Sik', false, false, 'Links'));
  $result->AddPage(new PageLink('<span class="state">Erstellungsanteil</span>', 'auswertung/anteil.php?option=erstellungsanteil', 'Wer hat wieviele Datens&auml;tze erstellt?', false, false, 'Links'));
  $result->AddPage(new PageLink('<span class="state">Bearbeitungsanteil</span>', 'auswertung/anteil.php?option=bearbeitungsanteil', 'Wer hat wieviele Datens&auml;tze abgeschlossen?', false, false, 'Links'));
  $result->AddPage(new PageLink('<span class="state">Erstellungsanteil (Zeitraum)</span>', 'auswertung/anteil.php?option=erstellungsanteil-periode', 'Wer hat wieviele Datens&auml;tze erstellt?', false, false, 'Links'));
  $result->AddPage(new PageLink('<span class="state">Bearbeitungsanteil (Zeitraum)</span>', 'auswertung/anteil.php?option=bearbeitungsanteil-periode', 'Wer hat wieviele Datens&auml;tze abgeschlossen?', false, false, 'Links'));
}

function clean_non_ascii($str) {
  return preg_replace('/[^\w\d_-]*/','', $str);
}

/** Copied from DownloadHTTPHandler*/
class PrivateFileDownloadHTTPHandler extends HTTPHandler
{
  private $dataset;
  private $fieldName;
  private $contentType;
  private $downloadFileName;

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
    $primaryKeyValues = array();
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
    //df($private_file, '$private_file');

    header('Content-type: ' . FormatDatasetFieldsTemplate($this->dataset, $this->contentType));
    if ($this->forceDownload)
      header('Content-Disposition: attachment; filename="' . FormatDatasetFieldsTemplate($this->dataset, $private_file_name) . '"');

//     echo $result;
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
      //drupal_not_found();
      // error handling
    }
  }
}

function DisplayTemplateSimple($TemplateName, $InputObjects, $InputValues, $display = true) {
  $captions = GetCaptions('UTF-8');
  $smarty = new Smarty();
  $smarty->template_dir = '/components/templates';
  foreach($InputObjects as $ObjectName => &$Object)
    $smarty->assign_by_ref($ObjectName, $Object);
  //       $smarty->assign_by_ref('Renderer', $this);
  $smarty->assign_by_ref('Captions', $captions);
  //       $smarty->assign('RenderScripts', $this->renderScripts);
  //       $smarty->assign('RenderText', $this->renderText);

//   if (isset($this->additionalParams))
//   {
//     foreach($this->additionalParams as $ValueName => $Value)
//     {
//       $smarty->assign($ValueName, $Value);
//     }
//   }

  foreach($InputValues as $ValueName => $Value)
    $smarty->assign($ValueName, $Value);

  $rendered = $smarty->fetch($TemplateName, null, null, $display);
}

function zutrittsberechtigteForParlamentarier($con, $parlamentarier_id, $for_email = false) {

  $sql = "SELECT zutrittsberechtigung.id, zutrittsberechtigung.arbeitssprache FROM v_zutrittsberechtigung_simple_compat zutrittsberechtigung
          WHERE
  (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW())
  AND zutrittsberechtigung.parlamentarier_id=:id
GROUP BY zutrittsberechtigung.id;";

  //         df($sql);
  //         $eng_con->ExecQueryToArray($sql, $result);
  //          df($eng_con->LastError(), 'last error');
  //         $eng_con->Disconnect();
  //         df($result, 'result');
  //         $preview = $rowData['email_text_html'];

  //         $q = $con->query($sql);
  //         $result2 = $q->fetchAll();
  //         df($eng_con->LastError(), 'last error');
  //         df($q, 'q');
  //         df($result2, 'result2');

  //       $sth = $con->prepare($sql);
  //       $sth->execute(array(':id' => $id));
  $zbs = lobbywatch_forms_db_query($sql, array(':id' => $parlamentarier_id));

  $gaeste = array();

  foreach ($zbs as $zb) {
    $id = $zb->id;
    $lang = $zb->arbeitssprache;
    $oldlang = lobbywatch_set_language($lang);
    $lang_suffix = get_lang_suffix($lang);

    $sql = "SELECT zutrittsberechtigung.id, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.geschlecht, zutrittsberechtigung.funktion, zutrittsberechtigung.beruf, zutrittsberechtigung.email, zutrittsberechtigung.arbeitssprache, zutrittsberechtigung.nachname,
  GROUP_CONCAT(DISTINCT
      CONCAT('<li>', " . (!$for_email ? "IF(mandat.bis IS NOT NULL AND mandat.bis < NOW(), '<s>', ''), " : "") . lobbywatch_lang_field('organisation.name_de') . ",
      IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', " . (!$for_email ? "'<span class=\"preview-missing-data\">, Rechtsform fehlt</span>'" : "''") . ", CONCAT(', ', organisation.rechtsform)), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ',
      " . _lobbywatch_bindungsart('zutrittsberechtigung', 'mandat', 'organisation') . ",
      " . (!$for_email ? " IF(mandat.beschreibung IS NULL OR TRIM(mandat.beschreibung) = '', '', CONCAT('<small class=\"desc\">, &quot;', mandat.beschreibung, '&quot;</small>'))," : "") . "
      IF(mandat.bis IS NOT NULL AND mandat.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(mandat.bis, '%Y'), '</s>'), ''))
      ORDER BY organisation.anzeige_name
      SEPARATOR ' '
  ) mandate,
  CASE zutrittsberechtigung.geschlecht
      WHEN 'M' THEN CONCAT(" . lts('Sehr geehrter Herr') . ",' ', zutrittsberechtigung.nachname)
      WHEN 'F' THEN CONCAT(" . lts('Sehr geehrte Frau') . ",' ', zutrittsberechtigung.nachname)
      ELSE CONCAT(" . lts('Sehr geehrte(r) Herr/Frau') . ",' ', zutrittsberechtigung.nachname)
  END anrede
  FROM v_zutrittsberechtigung_simple_compat zutrittsberechtigung
  LEFT JOIN v_mandat mandat
    ON mandat.person_id = zutrittsberechtigung.id " . ($for_email ? 'AND mandat.bis IS NULL' : '') . "
  LEFT JOIN v_organisation_simple organisation
    ON mandat.organisation_id = organisation.id
  WHERE
    zutrittsberechtigung.id=:id
  GROUP BY zutrittsberechtigung.id;";

//     df($sql, 'sql');

    $res = array();
    $sth = $con->prepare($sql);
    $sth->execute(array(':id' => $id));
    $gast = $sth->fetchAll();

    $gaeste = array_merge($gaeste, $gast);

    $gaesteMitMandaten = '';

    // Reset language
    lobbywatch_set_language($oldlang);
  }

  if (!$gaeste) {
    $gaesteMitMandaten = '<p>' . lt('keine') . '</p>';
    //      throw new Exception('Parlamentarier ID not found');
  } else {
    foreach($gaeste as $gast) {
      $gaesteMitMandaten .= '<h5>' . $gast['zutrittsberechtigung_name'] . '</h5>';
      //$gaesteMitMandaten .= mandateList($con, $gast['id']);
      $gaesteMitMandaten .= "<ul>\n" . $gast['mandate'] . "\n</ul>";
    }
  }

  $res['gaesteMitMandaten'] = $gaesteMitMandaten;
  $res['zutrittsberechtigte'] = $gaeste;

  return $res;
}

// function mandateList($con, $zutrittsberechtigte_id) {
//   $sql = "SELECT zutrittsberechtigung.id, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.funktion,
// GROUP_CONCAT(DISTINCT
//     CONCAT('<li>', organisation.anzeige_name,
//     IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', CONCAT(UCASE(LEFT(mandat.art, 1)), SUBSTRING(mandat.art, 2)),
//     IF(mandat.funktion_im_gremium IS NULL OR TRIM(mandat.funktion_im_gremium) = '', '', CONCAT(', ',CONCAT(UCASE(LEFT(mandat.funktion_im_gremium, 1)), SUBSTRING(mandat.funktion_im_gremium, 2)))),
//     IF(mandat.beschreibung IS NULL OR TRIM(mandat.beschreibung) = '', '', CONCAT('<small class=\"desc\">, &quot;', mandat.beschreibung, '&quot;</small>')))
//     ORDER BY organisation.anzeige_name
//     SEPARATOR ' '
// ) mandate
// FROM v_zutrittsberechtigung zutrittsberechtigung
// LEFT JOIN v_mandat mandat
//   ON mandat.zutrittsberechtigung_id = zutrittsberechtigung.id AND mandat.bis IS NULL
// LEFT JOIN v_organisation organisation
//   ON mandat.organisation_id = organisation.id
// WHERE
//   zutrittsberechtigung.bis IS NULL
//   AND zutrittsberechtigung.id=:id
// GROUP BY zutrittsberechtigung.id;";
//
//   $result = array();
//   $sth = $con->prepare($sql);
//   $sth->execute(array(':id' => $zutrittsberechtigte_id));
//   $result = $sth->fetchAll();
//
//   if (!$result) {
//     return '<p>keine</p>';
//   }
//
//   return "<ul>\n" . $result[0]['mandate'] . "\n</ul>";
// }

function getFullUsername($user) {
  switch($user) {
  	case 'otto' :
  	  return 'Otto Hostettler';
  	case 'roland' :
  	  return 'Roland Kurmann';
    case 'thomas' :
  	  return 'Thomas Angeli';
    case 'rebecca' :
  	  return 'Rebecca Wyss';
    default:
  	  return '';
  }
}

/*function getDateTime($value) {
  if (isset($value))
    return SMDateTime::Parse($value, '%Y-%m-%d %H:%M:%S');
  else
    return null;
}

function getDate(&$value) {
  if (isset($value))
    return SMDateTime::Parse($value, '%Y-%m-%d');
  else
    return null;
}

function getTime(&$value) {
  if (isset($value))
    return SMDateTime::Parse($value, '%H:%M:%S');
  else
    return null;
}*/

function getTimestamp($date_str) {
  return SMDateTime::Parse($date_str, 'Y-m-d H:i:s')->GetTimestamp();
}

/**
 * Set background-color if farbcode is set.

 * @param unknown $table_name
 * @param unknown $rowData farbcode
 * @param unknown $rowCellStyles
 * @param unknown $rowStyles
 */
function customDrawRowFarbcode($table_name, $rowData, &$rowCellStyles, &$rowStyles) {
  if (isset($rowData['farbcode'])) {
    $rowCellStyles['farbcode'] = 'background-color: ' . $rowData['farbcode'];
  }
}

/**
 *
 * @param unknown $table_name
 * @param unknown $rowData eingabe_abgeschlossen_datum, kontrolliert_datum, freigabe_datum, autorisierung_verschickt_datum, autorisiert_datum, kontrolliert_visa, eingabe_abgeschlossen_visa, im_rat_bis, sitzplatz, email, geburtstag, im_rat_bis, geschlecht, kleinbild, parlament_biografie_id, beruf, farbcode
 * @param unknown $rowCellStyles
 * @param unknown $rowStyles
 */
function customDrawRow($table_name, $rowData, &$rowCellStyles, &$rowStyles) {

  customDrawRowFarbcode($table_name, $rowData, $rowCellStyles, $rowStyles);

  $workflowStateColors = array();
  $workflowStateColors['freigabe'] = 'greenyellow';
  $workflowStateColors['autorisiert'] = 'lightblue';
  $workflowStateColors['autorisierung_verschickt'] = 'blue';
  $workflowStateColors['kontrolliert'] = 'orange';
  $workflowStateColors['eingabe_abgeschlossen'] = 'yellow';

  $workflowStateColors = getSettingValue('arbeitsablaufStatusFarben', true, $workflowStateColors);

//   df(json_encode($workflowStateColors), '$workflowStateColors');

  $update_threshold_setting = getSettingValue('ueberarbeitungsDatumSchwellwert', false, '2012-01-01');
  $update_threshold = SMDateTime::Parse($update_threshold_setting, 'Y-m-d');
  $update_threshold_ts = $update_threshold->GetTimestamp();
  $now_ts = time();

  $workflow_styles = '';

  // Check completeness
  $completeness_styles = '';

  if ($table_name === 'parlamentarier' || $table_name === 'person') {
    //df($rowData, '$rowData');

    //df(getTimestamp($rowData['freigabe_datum']), 'getTimestamp($rowData[freigabe_datum])');

//     df(is_object($rowData['freigabe_datum']), 'is_object($rowData[freigabe_datum]');
//     df(is_string($rowData['freigabe_datum']), 'is_string($rowData[freigabe_datum])');
//     df(gettype($rowData['freigabe_datum']), 'gettype($rowData[freigabe_datum])');

    // Check inconsistencies
    // TODO check ranges
    // TODO do not check only on !getTimestamp, use range
    if ((getTimestamp($rowData['freigabe_datum']) >= $update_threshold_ts
          && (!getTimestamp($rowData['autorisierung_verschickt_datum'])
//               || !getTimestamp($rowData['kontrolliert_datum'])
              || !getTimestamp($rowData['eingabe_abgeschlossen_datum'])))
        || (getTimestamp($rowData['autorisierung_verschickt_datum']) >= $update_threshold_ts
          && !(getTimestamp($rowData['freigabe_datum']) >= $update_threshold_ts)
          && (
//               !getTimestamp($rowData['kontrolliert_datum'])
//               ||
              !getTimestamp($rowData['eingabe_abgeschlossen_datum'])))
        || (getTimestamp($rowData['kontrolliert_datum']) >= $update_threshold_ts
//           && !(getTimestamp($rowData['freigabe_datum']) >= $update_threshold_ts)
//           && !(getTimestamp($rowData['autorisierung_verschickt_datum']) >= $update_threshold_ts)
          && (!getTimestamp($rowData['eingabe_abgeschlossen_datum']
//           || (getTimestamp($rowData['eingabe_abgeschlossen_datum']) >= $update_threshold_ts && (getTimestamp($rowData['kontrolliert_datum']) - getTimestamp($rowData['eingabe_abgeschlossen_datum']) < 0))
            ))
          )
        || (!empty($rowData['autorisiert_datum']) && getTimestamp($rowData['autorisiert_datum']) >= $update_threshold_ts
//           && !(getTimestamp($rowData['freigabe_datum']) >= $update_threshold_ts)
//           && !(getTimestamp($rowData['autorisierung_verschickt_datum']) >= $update_threshold_ts)
          && (!getTimestamp($rowData['eingabe_abgeschlossen_datum']
//              || (getTimestamp($rowData['eingabe_abgeschlossen_datum']) < $update_threshold_ts)
//           || (getTimestamp($rowData['eingabe_abgeschlossen_datum']) >= $update_threshold_ts && (getTimestamp($rowData['kontrolliert_datum']) - getTimestamp($rowData['eingabe_abgeschlossen_datum']) < 0))
            ))
          )
    ) {
//       df($rowData, '$rowData');
//       df(getTimestamp($rowData['autorisierung_verschickt_datum']), 'getTimestamp($rowData[autorisierung_verschickt_datum]');
//       df(!getTimestamp($rowData['autorisierung_verschickt_datum']), '!getTimestamp($rowData[autorisierung_verschickt_datum]');
//       df(getTimestamp($rowData['kontrolliert_datum']), 'getTimestamp($rowData[kontrolliert_datum]');
//       df(!getTimestamp($rowData['kontrolliert_datum']), '!getTimestamp($rowData[kontrolliert_datum]');
//       df(getTimestamp($rowData['eingabe_abgeschlossen_datum']), 'getTimestamp($rowData[eingabe_abgeschlossen_datum]');
//       df(!getTimestamp($rowData['eingabe_abgeschlossen_datum']), '!getTimestamp($rowData[eingabe_abgeschlossen_datum]');

//       $workflow_styles .= 'background-color: red;';
          $workflow_styles .= 'background-image: url(' . util_data_uri('img/icons/warning.gif') . '); background-repeat: no-repeat;';

    }

    // Color states
    //else
    if (getTimestamp($rowData['freigabe_datum']) >= $update_threshold_ts) {
      $workflow_styles .= "background-color: {$workflowStateColors['freigabe']};";
    } else if (getTimestamp($rowData['autorisiert_datum']) >= $update_threshold_ts) {
      $workflow_styles .= "background-color: {$workflowStateColors['autorisiert']};";
    } else if (getTimestamp($rowData['autorisierung_verschickt_datum']) >= $update_threshold_ts) {
      $workflow_styles .= "background-color: {$workflowStateColors['autorisierung_verschickt']};";
    } else if (getTimestamp($rowData['kontrolliert_datum']) >= $update_threshold_ts) {
      $workflow_styles .= "background-color: {$workflowStateColors['kontrolliert']};";
    } else if (getTimestamp($rowData['eingabe_abgeschlossen_datum']) >= $update_threshold_ts) {
      $workflow_styles .= "background-color: {$workflowStateColors['eingabe_abgeschlossen']};";
    }

    if (getTimestamp($rowData['kontrolliert_datum']) >= $update_threshold_ts
    && !preg_match('/background-image/',$workflow_styles)
    && (getTimestamp($rowData['kontrolliert_datum']) - getTimestamp($rowData['eingabe_abgeschlossen_datum']) > 3600
       || ($rowData['kontrolliert_visa'] != $rowData['eingabe_abgeschlossen_visa'] && getTimestamp($rowData['kontrolliert_datum']) - getTimestamp($rowData['eingabe_abgeschlossen_datum']) > 0)
//     && (getTimestamp($rowData['kontrolliert_datum']) - getTimestamp($rowData['eingabe_abgeschlossen_datum']) > 0
//       && $rowData['kontrolliert_visa'] != $rowData['eingabe_abgeschlossen_visa']
      )
    ) {
      //$workflow_styles .= 'border-color: green; border-width: 3px;';
          $workflow_styles .= 'background-image: url(' . util_data_uri('img/tick.png') . '); background-repeat: no-repeat; background-position: bottom right;';
//           $workflow_styles .= 'background-image: url(img/icons/fugue/eye.png); background-repeat: no-repeat; background-position: bottom right;';
    } elseif (getTimestamp($rowData['kontrolliert_datum']) >= $update_threshold_ts
    && !preg_match('/background-image/',$workflow_styles)
//     && (getTimestamp($rowData['kontrolliert_datum']) - getTimestamp($rowData['eingabe_abgeschlossen_datum']) > 3600
//       || $rowData['kontrolliert_visa'] != $rowData['eingabe_abgeschlossen_visa'])
    ) {
      //$workflow_styles .= 'border-color: green; border-width: 3px;';
//           $workflow_styles .= 'background-image: url(img/icons/fugue/eye-half.png); background-repeat: no-repeat; background-position: bottom right;';
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

      if (/*isset($rowData['sitzplatz']) &&*/ isset($rowData['email']) && isset($rowData['geburtstag']) && isset($rowData['im_rat_seit']) && isset($rowData['geschlecht']) && isset($rowData['kleinbild']) && isset($rowData['parlament_biografie_id']) && isset($rowData['beruf']) && $zb_state) {
        $completeness_styles .= 'background-color: greenyellow;';
      } elseif (/*isset($rowData['sitzplatz']) ||*/ isset($rowData['email']) || isset($rowData['geburtstag']) || isset($rowData['im_rat_seit']) || isset($rowData['geschlecht']) || isset($rowData['kleinbild']) || isset($rowData['parlament_biografie_id']) || isset($rowData['beruf'])){
        $completeness_styles .= 'background-color: orange;';
      }
      //checkAndMarkColumnNotNull('sitzplatz', $rowData, $rowCellStyles);
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
//       checkAndMarkColumnNotNull('funktion', $rowData, $rowCellStyles);
    }

    // Write styles
    $rowCellStyles['nachname'] = $completeness_styles;
    $rowCellStyles['id'] = $workflow_styles;

    //     df($rowCellStyles, '$rowCellStyles ' . $rowData['nachname'] . ' ' .$rowData['vorname']);
  } else if($table_name === 'translation_source' || $table_name === 'translation_target') { // BIG IF ELSE not parlamentarier or person
    // do nothing
  } else { // BIG IF ELSE not parlamentarier or person
    //df($rowData, '$rowData');

    //df(getTimestamp($rowData['freigabe_datum']), 'getTimestamp($rowData[freigabe_datum])');

//     df(is_object($rowData['freigabe_datum']), 'is_object($rowData[freigabe_datum]');
//     df(is_string($rowData['freigabe_datum']), 'is_string($rowData[freigabe_datum])');
//     df(gettype($rowData['freigabe_datum']), 'gettype($rowData[freigabe_datum])');

    // Check inconsistencies
    if ((getTimestamp($rowData['freigabe_datum']) >= $update_threshold_ts
          && (!getTimestamp($rowData['kontrolliert_datum'])
              || !getTimestamp($rowData['eingabe_abgeschlossen_datum'])))
        || (getTimestamp($rowData['kontrolliert_datum']) >= $update_threshold_ts
          && !(getTimestamp($rowData['freigabe_datum']) >= $update_threshold_ts)
          && !getTimestamp($rowData['eingabe_abgeschlossen_datum']))
        || (!empty($rowData['autorisiert_datum']) && getTimestamp($rowData['autorisiert_datum']) >= $update_threshold_ts
//           && !(getTimestamp($rowData['freigabe_datum']) >= $update_threshold_ts)
//           && !(getTimestamp($rowData['autorisierung_verschickt_datum']) >= $update_threshold_ts)
          && (!getTimestamp($rowData['eingabe_abgeschlossen_datum']
//              || (getTimestamp($rowData['eingabe_abgeschlossen_datum']) < $update_threshold_ts)
//           || (getTimestamp($rowData['eingabe_abgeschlossen_datum']) >= $update_threshold_ts && (getTimestamp($rowData['kontrolliert_datum']) - getTimestamp($rowData['eingabe_abgeschlossen_datum']) < 0))
            ))
          )
    ) {
//       df($rowData, '$rowData');
//       df(getTimestamp($rowData['autorisierung_verschickt_datum']), 'getTimestamp($rowData[autorisierung_verschickt_datum]');
//       df(!getTimestamp($rowData['autorisierung_verschickt_datum']), '!getTimestamp($rowData[autorisierung_verschickt_datum]');
//       df(getTimestamp($rowData['kontrolliert_datum']), 'getTimestamp($rowData[kontrolliert_datum]');
//       df(!getTimestamp($rowData['kontrolliert_datum']), '!getTimestamp($rowData[kontrolliert_datum]');
//       df(getTimestamp($rowData['eingabe_abgeschlossen_datum']), 'getTimestamp($rowData[eingabe_abgeschlossen_datum]');
//       df($update_threshold_ts, '$update_threshold_ts');
//       df(!getTimestamp($rowData['eingabe_abgeschlossen_datum']), '!getTimestamp($rowData[eingabe_abgeschlossen_datum]');

//           $workflow_styles .= 'background-color: red;';
          $workflow_styles .= 'background-image: url(' . util_data_uri('img/icons/warning.gif') . '); background-repeat: no-repeat;';
    }
    // Color states

    //else
    if (getTimestamp($rowData['freigabe_datum']) >= $update_threshold_ts) {
      $workflow_styles .= "background-color: {$workflowStateColors['freigabe']};";
//     } else if (getTimestamp($rowData['autorisiert_datum']) >= $update_threshold_ts) {
//       $rowCellStyles['id'] .= 'background-color: lightblue;';
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
//     && (getTimestamp($rowData['kontrolliert_datum']) - getTimestamp($rowData['eingabe_abgeschlossen_datum']) > 0
//       && $rowData['kontrolliert_visa'] != $rowData['eingabe_abgeschlossen_visa']
      )
    ) {
      //$workflow_styles .= 'border-color: green; border-width: 3px;';
          $workflow_styles .= 'background-image: url(' . util_data_uri('img/tick.png') . '); background-repeat: no-repeat; background-position: bottom right;';
//           $workflow_styles .= 'background-image: url(img/icons/fugue/eye.png); background-repeat: no-repeat; background-position: bottom right;';
    } elseif (getTimestamp($rowData['kontrolliert_datum']) >= $update_threshold_ts
    && !preg_match('/background-image/', $workflow_styles)
//     && (getTimestamp($rowData['kontrolliert_datum']) - getTimestamp($rowData['eingabe_abgeschlossen_datum']) > 3600
//       || $rowData['kontrolliert_visa'] != $rowData['eingabe_abgeschlossen_visa'])
    ) {
      //$workflow_styles .= 'border-color: green; border-width: 3px;';
//           $workflow_styles .= 'background-image: url(img/icons/fugue/eye-half.png); background-repeat: no-repeat; background-position: bottom right;';
          $workflow_styles .= 'background-image: url(' . util_data_uri('img/tick-small-red.png') . '); background-repeat: no-repeat; background-position: bottom right;';
    }

    if (isset($rowData['bis']) && getTimestamp($rowData['bis']) < $now_ts) {
      $workflow_styles .= 'text-decoration: line-through;';
      $completeness_styles .= 'text-decoration: line-through;';
    } elseif (isset($rowData['bis']) && getTimestamp($rowData['bis']) > $now_ts) {
      $workflow_styles .= 'text-decoration: underline;';
      $completeness_styles .= 'text-decoration: underline;';
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
//       df(in_kommission_anzahl($rowData['id'])['num'], 'in_kommission_anzahl($rowData[id][num]');
      if (!isset($rowData['anzahl_nationalraete']) || !isset($rowData['anzahl_staenderaete'])) {
        $completeness_styles .= 'background-color: #FF1493;';
      } elseif ((in_kommission_anzahl($rowData['id'])['NR']['num'] != $rowData['anzahl_nationalraete'] || in_kommission_anzahl($rowData['id'])['SR']['num'] != $rowData['anzahl_staenderaete']) /*&& $rowData['typ'] == 'kommission'*/) {
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

    //     df($rowCellStyles, '$rowCellStyles ' . $rowData['nachname'] . ' ' .$rowData['vorname']);
  }
}

function custom_GetConnectionOptions()
{
  $result = GetGlobalConnectionOptions();
  $result['client_encoding'] = 'utf8';
  GetApplication()->GetUserAuthorizationStrategy()->ApplyIdentityToConnectionOptions($result);
  return $result;
}

function getDBConnection() {
  $con_factory = new MyPDOConnectionFactory();

  $options = function_exists('GetConnectionOptions') ? GetConnectionOptions() : custom_GetConnectionOptions();
  $eng_con = $con_factory->CreateConnection($options);
  $eng_con->Connect();
  // TODO close connection
  //$con = $eng_con->GetConnectionHandle();
  return $eng_con;
}

function getDBConnectionHandle() {
  return getDBConnection()->GetConnectionHandle();
}

/**
 * Executes an arbitrary query string against the active database.
 *
 * Copied from Drupal db_query();
 *
 * Use this function for SELECT queries if it is just a simple query string.
 * If the caller or other modules need to change the query, use db_select()
 * instead.
 *
 * Do not use this function for INSERT, UPDATE, or DELETE queries. Those should
 * be handled via db_insert(), db_update() and db_delete() respectively.
 *
 * @param $query
 *   The prepared statement query to run. Although it will accept both named and
 *   unnamed placeholders, named placeholders are strongly preferred as they are
 *   more self-documenting.
 * @param $args
 *   An array of values to substitute into the query. If the query uses named
 *   placeholders, this is an associative array in any order. If the query uses
 *   unnamed placeholders (?), this is an indexed array and the order must match
 *   the order of placeholders in the query string.
 * @param $options
 *   An array of options to control how the query operates.
 *
 * @return DatabaseStatementInterface
 *   A prepared statement object, already executed.
 *
 * @see DatabaseConnection::defaultOptions()
 */
function lobbywatch_forms_db_query($query, array $args = array(), array $options = array()) {

//   if (empty($options['target'])) {
//     $options['target'] = 'default';
//   }

  $con_factory = new MyPDOConnectionFactory();
  $con_options = GetConnectionOptions();
  $eng_con = $con_factory->CreateConnection($con_options);
  try {
    $eng_con->Connect();
    $con = $eng_con->GetConnectionHandle();
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    //         df($eng_con->Connected(), 'connected');
    //         df($con, 'con');
    $cmd = $con_factory->CreateEngCommandImp();

    set_db_session_parameters($con);

//   return Database::getConnection($options['target'])->query($query, $args, $options);
    // Use default values if not already set.
    $options += lobbywatch_PDO_defaultOptions();

    lobbywatch_DB_expandArguments($query, $args);
//     $stmt = $this->prepareQuery($query);
    $query = lobbywatch_prefixTables($query);
    $stmt = $con->prepare($query);

    if (isset($options['fetch'])) {
      if (is_string($options['fetch'])) {
        // Default to an object. Note: db fields will be added to the object
        // before the constructor is run. If you need to assign fields after
        // the constructor is run, see http://drupal.org/node/315092.
        $stmt->setFetchMode(PDO::FETCH_CLASS, $options['fetch']);
      }
      else {
        $stmt->setFetchMode($options['fetch']);
      }
    }

    $stmt->execute($args);

    // Depending on the type of query we may need to return a different value.
    // See DatabaseConnection::defaultOptions() for a description of each
    // value.
    switch ($options['return']) {
      case LW_DB_RETURN_STATEMENT:
        return $stmt;
      case LW_DB_RETURN_AFFECTED:
        return $stmt->rowCount();
      case LW_DB_RETURN_INSERT_ID:
//         return $this->lastInsertId();
        return;
      case LW_DB_RETURN_NULL:
        return;
      default:
        throw new PDOException('Invalid return directive: ' . $options['return']);
    }
    }
    catch (PDOException $e) {
      if ($options['throw_exception']) {
        // Add additional debug information.
//         if ($query instanceof DatabaseStatementInterface) {
//           $e->query_string = $stmt->getQueryString();
//         }
//         else {
          $e->query_string = $query;
//         }
        $e->args = $args;
        throw $e;
      }
      return NULL;
//     }


//     //         df($sql);
//     $result = array();

//     $result = $sth->fetchAll();

//     if (!$result) {
//       df($eng_con->LastError());
//       throw new Exception('ID not found');
//     }
  } finally {
    // Connection will automatically be closed at the end of the request.
    //     $eng_con->Disconnect();
  }
}

/**
 * Set the list of prefixes used by this database connection.
 *
 * Copied from setPrefix().
 *
 * @param $prefix
 *   The prefixes, in any of the multiple forms documented in
 *   default.settings.php.
 */
function lobbywatch_DB_setPrefix($prefix) {
  if (is_array($prefix)) {
    $prefixes = $prefix + array('default' => '');
  }
  else {
    $prefixes = array('default' => $prefix);
  }

  // Set up variables for use in prefixTables(). Replace table-specific
  // prefixes first.
  $prefixSearch = array();
  $prefixReplace = array();
  foreach ($prefixes as $key => $val) {
    if ($key != 'default') {
      $prefixSearch[] = '{' . $key . '}';
      $prefixReplace[] = $val . $key;
    }
  }
  // Then replace remaining tables with the default prefix.
  $prefixSearch[] = '{';
  $prefixReplace[] = $this->prefixes['default'];
  $prefixSearch[] = '}';
  $prefixReplace[] = '';
}

/**
 * Appends a database prefix to all tables in a query.
 *
 * Copied from prefixTables().
 *
 * Queries sent to Drupal should wrap all table names in curly brackets. This
 * function searches for this syntax and adds Drupal's table prefix to all
 * tables, allowing Drupal to coexist with other systems in the same database
 * and/or schema if necessary.
 *
 * @param $sql
 *   A string containing a partial or entire SQL query.
 *
 * @return
 *   The properly-prefixed string.
 */
function lobbywatch_prefixTables($sql, $prefix = '') {
  if (is_array($prefix)) {
    $prefixes = $prefix + array('default' => '');
  }
  else {
    $prefixes = array('default' => $prefix);
  }

  // Set up variables for use in prefixTables(). Replace table-specific
  // prefixes first.
  $prefixSearch = array();
  $prefixReplace = array();
  foreach ($prefixes as $key => $val) {
    if ($key != 'default') {
      $prefixSearch[] = '{' . $key . '}';
      $prefixReplace[] = $val . $key;
    }
  }
  // Then replace remaining tables with the default prefix.
  $prefixSearch[] = '{';
  $prefixReplace[] = $prefixes['default'];
  $prefixSearch[] = '}';
  $prefixReplace[] = '';

  return str_replace($prefixSearch, $prefixReplace, $sql);
}

/**
 * Flag to indicate a query call should simply return NULL.
 *
 * This is used for queries that have no reasonable return value anyway, such
 * as INSERT statements to a table without a serial primary key.
 */
define('LW_DB_RETURN_NULL', 0);

/**
 * Flag to indicate a query call should return the prepared statement.
 */
define('LW_DB_RETURN_STATEMENT', 1);

/**
 * Flag to indicate a query call should return the number of affected rows.
 */
define('LW_DB_RETURN_AFFECTED', 2);

/**
 * Flag to indicate a query call should return the "last insert id".
 */
define('LW_DB_RETURN_INSERT_ID', 3);

/**
 * Returns the default query options for any given query.
 *
 * A given query can be customized with a number of option flags in an
 * associative array:
 * - target: The database "target" against which to execute a query. Valid
 *   values are "default" or "slave". The system will first try to open a
 *   connection to a database specified with the user-supplied key. If one
 *   is not available, it will silently fall back to the "default" target.
 *   If multiple databases connections are specified with the same target,
 *   one will be selected at random for the duration of the request.
 * - fetch: This element controls how rows from a result set will be
 *   returned. Legal values include PDO::FETCH_ASSOC, PDO::FETCH_BOTH,
 *   PDO::FETCH_OBJ, PDO::FETCH_NUM, or a string representing the name of a
 *   class. If a string is specified, each record will be fetched into a new
 *   object of that class. The behavior of all other values is defined by PDO.
 *   See http://php.net/manual/pdostatement.fetch.php
 * - return: Depending on the type of query, different return values may be
 *   meaningful. This directive instructs the system which type of return
 *   value is desired. The system will generally set the correct value
 *   automatically, so it is extremely rare that a module developer will ever
 *   need to specify this value. Setting it incorrectly will likely lead to
 *   unpredictable results or fatal errors. Legal values include:
 *   - Database::RETURN_STATEMENT: Return the prepared statement object for
 *     the query. This is usually only meaningful for SELECT queries, where
 *     the statement object is how one accesses the result set returned by the
 *     query.
 *   - Database::RETURN_AFFECTED: Return the number of rows affected by an
 *     UPDATE or DELETE query. Be aware that means the number of rows actually
 *     changed, not the number of rows matched by the WHERE clause.
 *   - Database::RETURN_INSERT_ID: Return the sequence ID (primary key)
 *     created by an INSERT statement on a table that contains a serial
 *     column.
 *   - Database::RETURN_NULL: Do not return anything, as there is no
 *     meaningful value to return. That is the case for INSERT queries on
 *     tables that do not contain a serial column.
 * - throw_exception: By default, the database system will catch any errors
 *   on a query as an Exception, log it, and then rethrow it so that code
 *   further up the call chain can take an appropriate action. To suppress
 *   that behavior and simply return NULL on failure, set this option to
 *   FALSE.
 *
 * @return
 *   An array of default query options.
 */
function lobbywatch_PDO_defaultOptions() {
  return array(
      'target' => 'default',
      'fetch' => PDO::FETCH_OBJ,
      'return' => LW_DB_RETURN_STATEMENT,
      'throw_exception' => TRUE,
  );
}

/**
 * Expands out shorthand placeholders.
 *
 * Copied from expandArguments()
 *
 * Drupal supports an alternate syntax for doing arrays of values. We
 * therefore need to expand them out into a full, executable query string.
 *
 * @param $query
 *   The query string to modify.
 * @param $args
 *   The arguments for the query.
 *
 * @return
 *   TRUE if the query was modified, FALSE otherwise.
 */
function lobbywatch_DB_expandArguments(&$query, &$args) {
  $modified = FALSE;

  // If the placeholder value to insert is an array, assume that we need
  // to expand it out into a comma-delimited set of placeholders.
  foreach (array_filter($args, 'is_array') as $key => $data) {
    $new_keys = array();
    foreach (array_values($data) as $i => $value) {
      // This assumes that there are no other placeholders that use the same
      // name.  For example, if the array placeholder is defined as :example
      // and there is already an :example_2 placeholder, this will generate
      // a duplicate key.  We do not account for that as the calling code
      // is already broken if that happens.
      $new_keys[$key . '_' . $i] = $value;
    }

    // Update the query with the new placeholders.
    // preg_replace is necessary to ensure the replacement does not affect
    // placeholders that start with the same exact text. For example, if the
    // query contains the placeholders :foo and :foobar, and :foo has an
    // array of values, using str_replace would affect both placeholders,
    // but using the following preg_replace would only affect :foo because
    // it is followed by a non-word character.
    $query = preg_replace('#' . $key . '\b#', implode(', ', array_keys($new_keys)), $query);

    // Update the args array with the new placeholders.
    unset($args[$key]);
    $args += $new_keys;

    $modified = TRUE;
  }

  return $modified;
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
    -- zutrittsberechtigung.parlamentarier_id=:id AND
    zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW()
    -- ORDER BY zutrittsberechtigung.parlamentarier_id LIMIT 10
        ;";

    $zbs = array();
    $sth = $con->prepare($sql);
    $sth->execute(array(':id' => $parlamentarier_id));
    $zbs = $sth->fetchAll();

    // Connection will automatically be closed at the end of the request.
//     $eng_con->Disconnect();

    foreach($zbs as $zb) {
      $zb_state[$zb['parlamentarier_id']][] = $zb;
    }
//     df($zb_state, '$zb_state');
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

    $zbs = array();
    $sth = $con->prepare($sql);
    $sth->execute(array(':id' => $parlamentarier_id));
    $zbs = $sth->fetchAll();

    // Connection will automatically be closed at the end of the request.
//     $eng_con->Disconnect();

    $zb_state[$parlamentarier_id] = $zbs;

//     df($zb_state, '$zb_state');
  }

//   df($zb_state[$parlamentarier_id], '$zb_state[$parlamentarier_id]');

  return $zb_state[$parlamentarier_id];
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
//     $eng_con = getDBConnection();
//     $con = $eng_con->GetConnectionHandle();
    // TODO close connection
    $sql = "SELECT in_kommission.kommission_id, count( DISTINCT in_kommission.parlamentarier_id) as num, in_kommission.kommission_abkuerzung, in_kommission.kommission_name, in_kommission.kommission_typ, in_kommission.rat
  FROM v_in_kommission in_kommission
  WHERE (in_kommission.bis IS NULL OR in_kommission.bis > NOW() )"
. ( $rat ? " AND in_kommission.rat='$rat'" : '')
. "  GROUP BY in_kommission.kommission_id, in_kommission.rat;";

//     $zbs = array();
//     $sth = $con->prepare($sql);
//     $sth->execute(array());
//     $zbs = $sth->fetchAll();

    $zbs = lobbywatch_forms_db_query($sql, array(), array('fetch' => PDO::FETCH_ASSOC))->fetchAll();

    // Connection will automatically be closed at the end of the request.
//     $eng_con->Disconnect();

    foreach($zbs as $zb) {
      $cache[$zb['kommission_id']][$zb['rat']] = $zb;
    }
    //     df($cache, '$cache');
//   df($cache, 'cache');
  }

//   // Fetch a single parlamentarier, should not be called anymore
//   if (!isset($cache[$kommission_id])) {
//     $eng_con = getDBConnection();
//     $con = $eng_con->GetConnectionHandle();
//     // TODO close connection
//     $sql = "SELECT zutrittsberechtigung.id, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.eingabe_abgeschlossen_datum, zutrittsberechtigung.kontrolliert_datum, zutrittsberechtigung.autorisiert_datum, zutrittsberechtigung.freigabe_datum
//   FROM v_zutrittsberechtigung zutrittsberechtigung
//   WHERE
//     zutrittsberechtigung.parlamentarier_id=:id
//     AND zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW();";

//     $zbs = array();
//     $sth = $con->prepare($sql);
//     $sth->execute(array(':id' => $parlamentarier_id));
//     $zbs = $sth->fetchAll();

//     $eng_con->Disconnect();

//     $cache[$parlamentarier_id] = $zbs;

//     //     df($cache, '$cache');
//   }

//  df($cache, '$cache');
//  df($cache[$kommission_id], '$cache[$kommission_id]');

//   df($kommission_id, 'kommission_id');

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

  //if ($userName != 'admin')

  //$rowData['created_visa'] = $userName;
  //$rowData['created_date'] = $datetime;
  $rowData['updated_visa'] = strtolower($userName);
  $rowData['updated_date'] = $datetime;

  clean_fields(/*$page,*/ $rowData/*, $cancel, $message, $tableName*/);
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

  //if ($userName != 'admin')

  $rowData['created_visa'] = strtolower($userName);
  $rowData['created_date'] = $datetime;

  $rowData['updated_visa'] = strtolower($userName);
  $rowData['updated_date'] = $datetime;

  clean_fields(/*$page,*/ $rowData/*, $cancel, $message, $tableName*/);
}

/**
 * Certain operations are only available for full workflow users. This method defines who is a full workflow user.
 *
 * They have more permissions. More operations are allowed.
 *
 * @return boolean true for full workflow users
 */
function isFullWorkflowUser() {
//   df(Application::Instance()->GetCurrentUserId(), 'id');
  return in_array(Application::Instance()->GetCurrentUserId(), array(1, 2, 3, 4, 5, 6), false);
}

function defaultOnAfterLogin($userName, $connection) {
  $connection->ExecSQL("UPDATE `user` SET `last_login`= CURRENT_TIMESTAMP WHERE `name` = '$userName';");
}

function set_db_session_parameters($con) {
  $session_sql = "SET SESSION group_concat_max_len=10000;";
  $con->query($session_sql);
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
//   df($fieldName, '$fieldName');
//   df($fieldData, 'fieldData');

  $update_threshold_setting = getSettingValue('ueberarbeitungsDatumSchwellwert', false, '2012-01-01');
  $update_threshold = SMDateTime::Parse($update_threshold_setting, 'Y-m-d');
  $update_threshold_ts = $update_threshold->GetTimestamp();
  $now_ts = time();

//   $update_threshold_ts = $now_ts;

  if (($fieldName == 'edit' || $fieldName == 'delete') && (in_array($table, array(/*'parlamentarier',*/ 'zutrittsberechtigung', 'interessenbindung', 'mandat', 'organisation_beziehung', 'in_kommission'))) && !isFullWorkflowUser() && isset($rowData['kontrolliert_datum'])
      && getTimestamp($rowData['kontrolliert_datum']) >= $update_threshold_ts) {
//     df($rowData['kontrolliert_datum'], "rowData['kontrolliert_datum']");
    $customText = '';
    $handled = true;
  }
}