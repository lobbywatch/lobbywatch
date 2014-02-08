<?php

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

$edit_general_hint = '<div class="clearfix rbox note"><div class="rbox-title"><img src="img/icons/book_open.png" alt="Hinweis" title="Hinweis" class="icon" width="16" height="16"><span>Hinweis</span></div><div class="rbox-data">Bitte die Bearbeitungsdokumentation (vor einer Bearbeitung) beachten und bei Unklarheiten anpassen, siehe <a href="http://lobbywatch.ch/wiki/tiki-index.php?page=Datenerfassung&structure=Lobbywatch-Wiki" target="_blank">Wiki Datenbearbeitung</a> und <a href="/sites/lobbywatch.ch/app' . $env_dir . 'lobbywatch_datenmodell_1page.pdf">Datenmodell</a> (PDF 1 Seite) / (<a href="/sites/lobbywatch.ch/app' . $env_dir . 'lobbywatch_datenmodell.pdf">gross, 4-seitig</a>).</div></div>';

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

function before_render(Page $page) {
  $page->OnCustomHTMLHeader->AddListener('add_custom_header');

  $hints = array();
  foreach($page->GetGrid()->GetViewColumns() as $column) {
    $raw_name = $column->GetName();
    $name = preg_replace('/^(.*?_id).*/', '\1', $raw_name);
    $name = preg_replace('/_anzeige_name$/', '', $name);
    $hints[$name] = htmlspecialchars($column->GetDescription());
//      df("Names: $raw_name -> $name");
  }
  $GLOBALS['customParams'] = array( 'Hints' => $hints);
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
  if ($file !== null && !endsWith($file, '/')) {
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
  if ($imRatBis !== null && $imRatBis->GetTimestamp() > SMDateTime::Now()->GetTimestamp()) {
    $cancel = true;
    $message = '"Im Rat bis"-Datum darf nicht in der Zukunft liegen: ' . $imRatBis->ToString('d.m.Y');
  }
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
  if ($bis !== null && $bis->GetTimestamp() > SMDateTime::Now()->GetTimestamp()) {
    $cancel = true;
    $message = 'Bis-Datum darf nicht in der Zukunft liegen: ' . $bis->ToString('d.m.Y');
  }
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


function clean_fields($page, &$rowData, &$cancel, &$message, $tableName)
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
    $this->grid->GetDataset ()->SetFieldValueByName ( 'autorisierung_verschickt_datum', $datetime );
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

function gaesteMitMandaten($con, $parlamentarier_id) {
  $sql = "SELECT zutrittsberechtigung.id, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.funktion,
GROUP_CONCAT(DISTINCT
    CONCAT('<li>', organisation.anzeige_name, IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', CONCAT(UCASE(LEFT(mandat.art, 1)), SUBSTRING(mandat.art, 2)), IF(mandat.beschreibung IS NULL OR TRIM(mandat.beschreibung) = '', '', CONCAT(', ', mandat.beschreibung)))
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
) mandate
FROM v_zutrittsberechtigung zutrittsberechtigung
LEFT JOIN v_mandat mandat
  ON mandat.zutrittsberechtigung_id = zutrittsberechtigung.id AND mandat.bis IS NULL
LEFT JOIN v_organisation organisation
  ON mandat.organisation_id = organisation.id
WHERE
  zutrittsberechtigung.bis IS NULL
  AND zutrittsberechtigung.parlamentarier_id=:id
GROUP BY zutrittsberechtigung.id;";

  $gaeste = array();
  $sth = $con->prepare($sql);
  $sth->execute(array(':id' => $parlamentarier_id));
  $gaeste = $sth->fetchAll();

  if (!$gaeste) {
    return '<p>keine</p>';
//      throw new Exception('Parlamentarier ID not found');
  }

  $res = '';
  foreach($gaeste as $gast) {
    $res .= '<h5>' . $gast['zutrittsberechtigung_name'] . '</h5>';
    //$res .= mandateList($con, $gast['id']);
    $res .= "<ul>\n" . $gast['mandate'] . "\n</ul>";
  }

  return $res;
}

function mandateList($con, $zutrittsberechtigte_id) {
  $sql = "SELECT zutrittsberechtigung.id, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.funktion,
GROUP_CONCAT(DISTINCT
    CONCAT('<li>', organisation.anzeige_name, IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', CONCAT(UCASE(LEFT(mandat.art, 1)), SUBSTRING(mandat.art, 2)), IF(mandat.beschreibung IS NULL OR TRIM(mandat.beschreibung) = '', '', CONCAT(', ', mandat.beschreibung)))
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
) mandate
FROM v_zutrittsberechtigung zutrittsberechtigung
LEFT JOIN v_mandat mandat
  ON mandat.zutrittsberechtigung_id = zutrittsberechtigung.id AND mandat.bis IS NULL
LEFT JOIN v_organisation organisation
  ON mandat.organisation_id = organisation.id
WHERE
  zutrittsberechtigung.bis IS NULL
  AND zutrittsberechtigung.id=:id
GROUP BY zutrittsberechtigung.id;";

  $result = array();
  $sth = $con->prepare($sql);
  $sth->execute(array(':id' => $zutrittsberechtigte_id));
  $result = $sth->fetchAll();

  if (!$result) {
    return '<p>keine</p>';
  }

  return "<ul>\n" . $result[0]['mandate'] . "\n</ul>";
}

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
