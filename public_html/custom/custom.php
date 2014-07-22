<?php

// ATTENTION: THIS FILE IS ENCODED AS ISO-8859-1

require_once dirname(__FILE__) . "/../settings/settings.php";
require_once dirname(__FILE__) . "/../common/utils.php";
require_once dirname(__FILE__) . '/../bearbeitung/components/grid/grid_state.php';
require_once dirname(__FILE__) . '/../bearbeitung/components/common.php';

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

$edit_general_hint = '<div class="clearfix rbox note"><div class="rbox-title"><img src="img/icons/book_open.png" alt="Hinweis" title="Hinweis" class="icon" width="16" height="16"><span>Hinweis</span></div><div class="rbox-data">Bitte die Bearbeitungsdokumentation (vor einer Bearbeitung) beachten und bei Unklarheiten anpassen, siehe <a href="http://lobbywatch.ch/wiki/tiki-index.php?page=Datenerfassung&structure=Lobbywatch-Wiki" target="_blank">Wiki Datenbearbeitung</a> und <a href="/sites/lobbywatch.ch/app' . $env_dir . 'lobbywatch_datenmodell_simplified.pdf">Vereinfachtes Datenmodell</a> (Komplex: <a href="/sites/lobbywatch.ch/app' . $env_dir . 'lobbywatch_datenmodell_1page.pdf">1 Seite</a> /<a href="/sites/lobbywatch.ch/app' . $env_dir . 'lobbywatch_datenmodell.pdf">4 Seiten</a>).</div></div>';

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
    case 'zugangsberechtigung' :
      $rss_title = 'Zutrittsberechtigung %vorname% %nachname% changed by %updated_visa% at %updated_date%';
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
  foreach($page->GetGrid()->GetViewColumns() as $column) {
    $raw_name = $column->GetName();
    $name = preg_replace('/^(.*?_id).*/', '\1', $raw_name);
    $name = preg_replace('/_anzeige_name$/', '', $name);
    $hints[$name] = htmlspecialchars($column->GetDescription());
    //      df("Names: $raw_name -> $name");
  }
  $params = array_merge($params, array( 'Hints' => $hints));
//   df($params, 'params');
}

function before_render(Page $page) {
  // Add custom headers
  $page->OnCustomHTMLHeader->AddListener('add_custom_header');
  write_user_last_access($page);

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

function write_user_last_access($page) {
  $connection = getDBConnection();

  // Do not update access time more than once per 180 seconds.
  // Inspired by Drupal 7 session.inc _drupal_session_write()
  if (($id = GetApplication()->GetCurrentUserId()) && ($request_time = $_SERVER['REQUEST_TIME']) - $connection->ExecScalarSQL("SELECT UNIX_TIMESTAMP(last_access) FROM user WHERE id= $id;") > 180) {
    $connection->ExecSQL("UPDATE `user` SET `last_access`= CURRENT_TIMESTAMP WHERE `id` = $id;");
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
  $result->AddPage(new PageLink('<span class="website">Website</span>', '/', 'Homepage', false, true));
  $result->AddPage(new PageLink('<span class="wiki">Wiki</span>', '/wiki', 'Wiki', false, false));
  $result->AddPage(new PageLink('<span class="auswertung">Auswertung</span>', $GLOBALS['env_dir'] . 'auswertung', 'Auswertung ' . $GLOBALS['env'] , false, false));
  $result->AddPage(new PageLink('<span class="state">Stand SGK</span>', 'auswertung/anteil.php?option=kommission&id=1', 'Stand SGK', false, true));
  $result->AddPage(new PageLink('<span class="state">Stand UREK</span>', 'auswertung/anteil.php?option=kommission&id=3', 'Stand UREK', false, false));
  $result->AddPage(new PageLink('<span class="state">Erstellungsanteil</span>', 'auswertung/anteil.php?option=erstellungsanteil', 'Wer hat wieviele Datens&auml;tze erstellt?', false, false));
  $result->AddPage(new PageLink('<span class="state">Bearbeitungsanteil</span>', 'auswertung/anteil.php?option=bearbeitungsanteil', 'Wer hat wieviele Datens&auml;tze abgeschlossen?', false, false));
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
  $sql = "SELECT zutrittsberechtigung.id, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.funktion, zutrittsberechtigung.beruf, zutrittsberechtigung.email,
GROUP_CONCAT(DISTINCT
    CONCAT('<li>', IF(mandat.bis IS NOT NULL AND mandat.bis < NOW(), '<s>', ''), organisation.anzeige_name,
    IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', " . (!$for_email ? "'<span class=\"preview-missing-data\">, Rechtsform fehlt</span>'" : "''") . ", CONCAT(', ', organisation.rechtsform)), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', CONCAT(UCASE(LEFT(mandat.art, 1)), SUBSTRING(mandat.art, 2)),
    IF(mandat.funktion_im_gremium IS NULL OR TRIM(mandat.funktion_im_gremium) = '', '', CONCAT(', ',CONCAT(UCASE(LEFT(mandat.funktion_im_gremium, 1)), SUBSTRING(mandat.funktion_im_gremium, 2)))),
    " . (!$for_email ? " IF(mandat.beschreibung IS NULL OR TRIM(mandat.beschreibung) = '', '', CONCAT('<small class=\"desc\">, &quot;', mandat.beschreibung, '&quot;</small>'))," : "") . "
    IF(mandat.bis IS NOT NULL AND mandat.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(mandat.bis, '%Y'), '</s>'), ''))
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
) mandate,
CASE zutrittsberechtigung.geschlecht
    WHEN 'M' THEN CONCAT('Sehr geehrter Herr ', zutrittsberechtigung.nachname)
    WHEN 'F' THEN CONCAT('Sehr geehrte Frau ', zutrittsberechtigung.nachname)
    ELSE CONCAT('Sehr geehrte(r) Herr/Frau ', zutrittsberechtigung.nachname)
END anrede
FROM v_zutrittsberechtigung zutrittsberechtigung
LEFT JOIN v_mandat mandat
  ON mandat.zutrittsberechtigung_id = zutrittsberechtigung.id " . ($for_email ? 'AND mandat.bis IS NULL' : '') . "
LEFT JOIN v_organisation organisation
  ON mandat.organisation_id = organisation.id
WHERE
  (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW())
  AND zutrittsberechtigung.parlamentarier_id=:id
GROUP BY zutrittsberechtigung.id;";

  $res = array();
  $gaeste = array();
  $sth = $con->prepare($sql);
  $sth->execute(array(':id' => $parlamentarier_id));
  $gaeste = $sth->fetchAll();

  $gaesteMitMandaten = '';

  if (!$gaeste) {
    $gaesteMitMandaten = '<p>keine</p>';
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

  if ($table_name === 'parlamentarier' || $table_name === 'zutrittsberechtigung') {
    //df($rowData, '$rowData');

    //df(getTimestamp($rowData['freigabe_datum']), 'getTimestamp($rowData[freigabe_datum])');

    $workflow_styles = '';

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
          $workflow_styles .= 'background-image: url(img/icons/warning.gif); background-repeat: no-repeat;';

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
          $workflow_styles .= 'background-image: url(img/tick.png); background-repeat: no-repeat; background-position: bottom right;';
//           $workflow_styles .= 'background-image: url(img/icons/fugue/eye.png); background-repeat: no-repeat; background-position: bottom right;';
    } elseif (getTimestamp($rowData['kontrolliert_datum']) >= $update_threshold_ts
    && !preg_match('/background-image/',$workflow_styles)
//     && (getTimestamp($rowData['kontrolliert_datum']) - getTimestamp($rowData['eingabe_abgeschlossen_datum']) > 3600
//       || $rowData['kontrolliert_visa'] != $rowData['eingabe_abgeschlossen_visa'])
    ) {
      //$workflow_styles .= 'border-color: green; border-width: 3px;';
//           $workflow_styles .= 'background-image: url(img/icons/fugue/eye-half.png); background-repeat: no-repeat; background-position: bottom right;';
          $workflow_styles .= 'background-image: url(img/tick-small-red.png); background-repeat: no-repeat; background-position: bottom right;';
    }

    if ((isset($rowData['im_rat_bis']) && getTimestamp($rowData['im_rat_bis']) < $now_ts) || (isset($rowData['bis']) && getTimestamp($rowData['bis']) < $now_ts)) {
      $workflow_styles .= 'text-decoration: line-through;';
    } elseif ((isset($rowData['im_rat_bis']) && getTimestamp($rowData['im_rat_bis']) > $now_ts) || (isset($rowData['bis']) && getTimestamp($rowData['bis']) > $now_ts)) {
      $workflow_styles .= 'text-decoration: underline;';
    }

    // Check completeness
    $completeness_styles = '';

    if ($table_name === 'parlamentarier') {
      // Check zutrittsberechtigung workflow state
      $zb_list = zutrittsberechtigung_state($rowData['id']);
      $zb_state = count($zb_list) <= 2;
      $zb_controlled = true;
      foreach($zb_list as $zb) {
        $zb_state &= getTimestamp($zb['eingabe_abgeschlossen_datum']) >= $update_threshold_ts;
        $zb_controlled &= getTimestamp($zb['kontrolliert_datum']) >= $update_threshold_ts && getTimestamp($zb['kontrolliert_datum']) > getTimestamp($zb['eingabe_abgeschlossen_datum']);
      }
      if ($zb_state && $zb_controlled) {
        $completeness_styles .= 'background-image: url(img/icons/fugue/user-green-female.png); background-repeat: no-repeat; background-position: bottom right;';
      } elseif ($zb_state) {
        $completeness_styles .= 'background-image: url(img/icons/fugue/user.png); background-repeat: no-repeat; background-position: bottom right;';
      } elseif (getTimestamp($rowData['eingabe_abgeschlossen_datum']) >= $update_threshold_ts) {
        $completeness_styles .= 'background-image: url(img/icons/fugue/user--exclamation.png); background-repeat: no-repeat; background-position: bottom right;';
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

   } elseif ($table_name === 'zutrittsberechtigung') {
     if (isset($rowData['email']) && isset($rowData['geschlecht']) && isset($rowData['beruf']) && isset($rowData['funktion'])) {
       $completeness_styles .= 'background-color: greenyellow;';
     } elseif (isset($rowData['email']) || isset($rowData['geschlecht']) || isset($rowData['beruf']) || isset($rowData['funktion'])) {
        $completeness_styles .= 'background-color: orange;';
      }
      checkAndMarkColumnNotNull('email', $rowData, $rowCellStyles);
      checkAndMarkColumnNotNull('geschlecht', $rowData, $rowCellStyles);
      checkAndMarkColumnNotNull('beruf', $rowData, $rowCellStyles);
      checkAndMarkColumnNotNull('funktion', $rowData, $rowCellStyles);
   }

    // Write styles
    $rowCellStyles['nachname'] = $completeness_styles;
    $rowCellStyles['id'] = $workflow_styles;

    //     df($rowCellStyles, '$rowCellStyles ' . $rowData['nachname'] . ' ' .$rowData['vorname']);
  } else {
    //df($rowData, '$rowData');

    //df(getTimestamp($rowData['freigabe_datum']), 'getTimestamp($rowData[freigabe_datum])');

    $workflow_styles = '';

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
          $workflow_styles .= 'background-image: url(img/icons/warning.gif); background-repeat: no-repeat;';
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
          $workflow_styles .= 'background-image: url(img/tick.png); background-repeat: no-repeat; background-position: bottom right;';
//           $workflow_styles .= 'background-image: url(img/icons/fugue/eye.png); background-repeat: no-repeat; background-position: bottom right;';
    } elseif (getTimestamp($rowData['kontrolliert_datum']) >= $update_threshold_ts
    && !preg_match('/background-image/', $workflow_styles)
//     && (getTimestamp($rowData['kontrolliert_datum']) - getTimestamp($rowData['eingabe_abgeschlossen_datum']) > 3600
//       || $rowData['kontrolliert_visa'] != $rowData['eingabe_abgeschlossen_visa'])
    ) {
      //$workflow_styles .= 'border-color: green; border-width: 3px;';
//           $workflow_styles .= 'background-image: url(img/icons/fugue/eye-half.png); background-repeat: no-repeat; background-position: bottom right;';
          $workflow_styles .= 'background-image: url(img/tick-small-red.png); background-repeat: no-repeat; background-position: bottom right;';
    }

      if (isset($rowData['bis']) && getTimestamp($rowData['bis']) < $now_ts) {
      $workflow_styles .= 'text-decoration: line-through;';
    } elseif (isset($rowData['bis']) && getTimestamp($rowData['bis']) > $now_ts) {
      $workflow_styles .= 'text-decoration: underline;';
    }

    // Check completeness

    $completeness_styles = '';

    if ($table_name == 'partei' && isset($rowData['name'])) {
      $completeness_styles .= 'background-color: greenyellow;';
      checkAndMarkColumnNotNull('name', $rowData, $rowCellStyles);
    } elseif ($table_name === 'organisation') {
      if (isset($rowData['rechtsform']) && isset($rowData['interessengruppe_id']) && isset($rowData['branche_id'])) {
        $completeness_styles .= 'background-color: greenyellow;';
      } elseif (isset($rowData['rechtsform']) || isset($rowData['interessengruppe_id']) || isset($rowData['branche_id'])) {
        $completeness_styles .= 'background-color: orange;';
      }
      checkAndMarkColumnNotNull('rechtsform', $rowData, $rowCellStyles);
      checkAndMarkColumnNotNull('interessengruppe_id', $rowData, $rowCellStyles);
      checkAndMarkColumnNotNull('branche_id', $rowData, $rowCellStyles);
    } elseif ($table_name === 'branche') {
      if (isset($rowData['kommission_id'])) {
        $completeness_styles .= 'background-color: greenyellow;';
      }
      checkAndMarkColumnNotNull('kommission_id', $rowData, $rowCellStyles);
    } elseif ($table_name === 'kommission') {
//       df(in_kommission_anzahl($rowData['id'])['num'], 'in_kommission_anzahl($rowData[id][num]');
      if (!isset($rowData['anzahl_nationalraete']) || !isset($rowData['anzahl_staenderaete'])) {
        $completeness_styles .= 'background-color: #FF1493;';
      } elseif ((in_kommission_anzahl($rowData['id'], 'NR')['num'] != $rowData['anzahl_nationalraete'] || in_kommission_anzahl($rowData['id'], 'SR')['num'] != $rowData['anzahl_staenderaete']) /*&& $rowData['typ'] == 'kommission'*/) {
        $completeness_styles .= 'background-color: red;'; // deep pink
      } elseif (isset($rowData['parlament_url'])) {
        $completeness_styles .= 'background-color: greenyellow;';
      }
      checkAndMarkColumnNotNull('parlament_url', $rowData, $rowCellStyles);
      checkAndMarkColumnNotNull('anzahl_nationalraete', $rowData, $rowCellStyles);
      checkAndMarkColumnNotNull('anzahl_staenderaete', $rowData, $rowCellStyles);
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

function zutrittsberechtigung_state($parlamentarier_id) {
  $zb_state = &php_static_cache(__FUNCTION__);

  // Load all zutrittsberechtige on first call
  if (!isset($zb_state)) {
    // Fetch all the first time
    $eng_con = getDBConnection();
    $con = $eng_con->GetConnectionHandle();
    // TODO close connection
    $sql = "SELECT zutrittsberechtigung.id, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.eingabe_abgeschlossen_datum, zutrittsberechtigung.kontrolliert_datum, zutrittsberechtigung.autorisiert_datum, zutrittsberechtigung.freigabe_datum, zutrittsberechtigung.parlamentarier_id
  FROM v_zutrittsberechtigung zutrittsberechtigung
  WHERE
    -- zutrittsberechtigung.parlamentarier_id=:id AND
    zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW()
    -- ORDER BY zutrittsberechtigung.parlamentarier_id LIMIT 10
        ;";

    $zbs = array();
    $sth = $con->prepare($sql);
    $sth->execute(array(':id' => $parlamentarier_id));
    $zbs = $sth->fetchAll();

    $eng_con->Disconnect();

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
  FROM v_zutrittsberechtigung zutrittsberechtigung
  WHERE
    zutrittsberechtigung.parlamentarier_id=:id
    AND zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW();";

    $zbs = array();
    $sth = $con->prepare($sql);
    $sth->execute(array(':id' => $parlamentarier_id));
    $zbs = $sth->fetchAll();

    $eng_con->Disconnect();

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
    $eng_con = getDBConnection();
    $con = $eng_con->GetConnectionHandle();
    // TODO close connection
    $sql = "SELECT in_kommission.kommission_id, count( DISTINCT in_kommission.parlamentarier_id) as num, in_kommission.abkuerzung, in_kommission.kommission_name, in_kommission.kommission_typ
  FROM v_in_kommission in_kommission
  WHERE in_kommission.bis IS NULL OR in_kommission.bis > NOW()"
. ( $rat ? " AND in_kommission.rat='$rat'" : '')
. "  GROUP BY in_kommission.kommission_id;";

    $zbs = array();
    $sth = $con->prepare($sql);
    $sth->execute(array());
    $zbs = $sth->fetchAll();

    $eng_con->Disconnect();

    foreach($zbs as $zb) {
      $cache[$zb['kommission_id']] = $zb;
    }
    //     df($cache, '$cache');
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

  return $cache[$kommission_id];
}


function checkAndMarkColumnNotNull($column, $rowData, &$rowCellStyles) {
    if (empty($rowData[$column]) || $rowData[$column] == '') {
      if (empty($rowCellStyles[$column])) {
        $rowCellStyles[$column] = '';
      }
      $rowCellStyles[$column] .= 'background-image: url(img/book-question.png); background-repeat: no-repeat; background-position: top left;';
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
 * Fetch a setting parameter.
 * @return value or default value if nothing found
 */
function getSettingValue($key, $json = false, $defaultValue = null) {
  $settings = &php_static_cache(__FUNCTION__);
  if (!isset($settings)) {
    // Initially, fetch all at once
    $eng_con = getDBConnection();
    $values = array();
    try {
      $con = $eng_con->GetConnectionHandle();
      // TODO close connection
      $sql = "SELECT id, key_name, value
          FROM v_settings settings
          -- WHERE settings.key_name=:key";

      $sth = $con->prepare($sql);
      $sth->execute(array(':key' => $key));
      $values = $sth->fetchAll();
    } finally {
      $eng_con->Disconnect();
    }

  //   df($values, '$values');
  //   df($defaultValue, '$defaultValue');
  //   df($values[0]['value'], '$values[0][value]');

  //   df(getSettingCategoryValues('Test'), 'Test');
  //   df(getSettingCategoryValues('Test3', 'nothing'), 'Test nothing');

    foreach($values as $value) {
      // Take the first result
      $settings[$value['key_name']] =  $value['value'];
    }
//     df($settings, 'settings');
  }
  if (!isset($settings[$key])) {
    // If this function is being called for the first time for a particular
    // index field, then execute code needed to index the information already
    // available in $settings by the desired field.
    $eng_con = getDBConnection();
    $values = array();
    try {
      $con = $eng_con->GetConnectionHandle();
      // TODO close connection
      $sql = "SELECT id, value
          FROM v_settings settings
          WHERE settings.key_name=:key";

      $sth = $con->prepare($sql);
      $sth->execute(array(':key' => $key));
      $values = $sth->fetchAll();
    } finally {
      $eng_con->Disconnect();
    }

  //   df($values, '$values');
  //   df($defaultValue, '$defaultValue');
  //   df($values[0]['value'], '$values[0][value]');

  //   df(getSettingCategoryValues('Test'), 'Test');
  //   df(getSettingCategoryValues('Test3', 'nothing'), 'Test nothing');

    if (count($values) > 1) {
      throw new Exception('Too many values for setting "' . $key . '""');
    } else if (count($values) == 0) {
      // Nothing found, return defaultValue
      $settings[$key] = $defaultValue;
    } else {
      // Take the first result
      $settings[$key] =  $values[0]['value'];
    }
  }
  // Subsequent invocations of this function for a particular index field
  // skip the above two code blocks and quickly return the already indexed
  // information.
  $setting = $settings[$key];

  return $json ? json_decode($setting, true) : $setting;
}

/**
 * Useful for color values.
 * @return key=value array
 */
function getSettingCategoryValues($categoryName, $defaultValue = null) {
  $settings = &php_static_cache(__FUNCTION__);
//   if (!isset($settings)) {
//     // If this function is being called for the first time after a reset,
//     // query the database and execute any other code needed to retrieve
//     // information about the supported languages.
//   }
  if (!isset($settings[$categoryName])) {
    $eng_con = getDBConnection();
    $values = array();
    try {
      $con = $eng_con->GetConnectionHandle();
      // TODO close connection
      $sql = "SELECT id, key_name, value
    FROM v_settings settings
    WHERE settings.category_name=:categoryName";

      $sth = $con->prepare($sql);
      $sth->execute(array(':categoryName' => $categoryName));
      $values = $sth->fetchAll();
    } finally {
      $eng_con->Disconnect();
    }

    if (count($values) == 0) {
      // Nothing found, return defaultValue
      $settings[$categoryName] = $defaultValue;
    } else {
      $simple = array();
      foreach ($values as $rec) {
        $simple[$rec['key_name']] = $rec['value'];
      }
      $settings[$categoryName] = $simple;
    }
  }

  // Subsequent invocations of this function for a particular index field
  // skip the above two code blocks and quickly return the already indexed
  // information.
  return $settings[$categoryName];
}

/**
 * Certain operations are only available for full workflow users. This method defines who is a full workflow user.
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
