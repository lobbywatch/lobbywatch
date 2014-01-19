<?php
// Processed by afterburner.sh

require_once dirname(__FILE__) . "/../settings/settings.php";
require_once dirname(__FILE__) . "/../custom/custom.php";
require_once dirname(__FILE__) . "/../common/build_date.php";
require_once dirname(__FILE__) . "/../common/utils.php";
// Processed by afterburner.sh



//  define('SHOW_VARIABLES', 1);
//  define('DEBUG_LEVEL', 1);

//  error_reporting(E_ALL ^ E_NOTICE);
//  ini_set('display_errors', 'On');

set_include_path('.' . PATH_SEPARATOR . get_include_path());


include_once dirname(__FILE__) . '/' . 'components/utils/system_utils.php';

//  SystemUtils::DisableMagicQuotesRuntime();

SystemUtils::SetTimeZoneIfNeed('Europe/Belgrade');

function GetGlobalConnectionOptions(){
    // Custom modification: Use $db_connection from settings.php
    global $db_connection;
    return $db_connection;
}

function HasAdminPage()
{
    return true;
}

function GetPageInfos()
{
    $result = array();
    $result[] = array('caption' => '<span class="entity important-entity">Organisation</span>', 'short_caption' => 'Organisation', 'filename' => 'organisation.php', 'name' => 'organisation');
    $result[] = array('caption' => '<span class="entity important-entity">Parlamentarier</span>', 'short_caption' => 'Parlamentarier', 'filename' => 'parlamentarier.php', 'name' => 'parlamentarier');
    $result[] = array('caption' => '<span class="entity">Zutrittsberechtigung</span>', 'short_caption' => 'Zutrittsberechtigung', 'filename' => 'zutrittsberechtigung.php', 'name' => 'zutrittsberechtigung');
    $result[] = array('caption' => '<span class="relation">Interessenbindung</span>', 'short_caption' => 'Interessenbindung', 'filename' => 'interessenbindung.php', 'name' => 'interessenbindung');
    $result[] = array('caption' => '<span class="relation">Mandat</span>', 'short_caption' => 'Mandat', 'filename' => 'mandat.php', 'name' => 'mandat');
    $result[] = array('caption' => '<span class="relation">In Kommission</span>', 'short_caption' => 'In Kommission', 'filename' => 'in_kommission.php', 'name' => 'in_kommission');
    $result[] = array('caption' => '<span class="relation">Organisation Beziehung</span>', 'short_caption' => 'Organisation Beziehung', 'filename' => 'organisation_beziehung.php', 'name' => 'organisation_beziehung');
    $result[] = array('caption' => '<span class="entity">Interessengruppe</span>', 'short_caption' => 'Interessengruppe', 'filename' => 'interessengruppe.php', 'name' => 'interessengruppe');
    $result[] = array('caption' => '<span class="entity">Branche</span>', 'short_caption' => 'Branche', 'filename' => 'branche.php', 'name' => 'branche');
    $result[] = array('caption' => '<span class="entity">Kommission</span>', 'short_caption' => 'Kommission', 'filename' => 'kommission.php', 'name' => 'kommission');
    $result[] = array('caption' => '<span class="entity">Partei</span>', 'short_caption' => 'Partei', 'filename' => 'partei.php', 'name' => 'partei');
    $result[] = array('caption' => '<span class="view">Parlamentarier Email</span>', 'short_caption' => 'Parlamentarier Email', 'filename' => 'v_parlamentarier_authorisierungs_email.php', 'name' => 'v_parlamentarier_authorisierungs_email');
    $result[] = array('caption' => '<span class="view">Unvollständige Parlamentarier</span>', 'short_caption' => 'Unvollständige Parlamentarier', 'filename' => 'q_unvollstaendige_parlamentarier.php', 'name' => 'q_unvollstaendige_parlamentarier');
    $result[] = array('caption' => '<span class="view">Unvollständige Organisationen</span>', 'short_caption' => 'Unvollständige Organisationen', 'filename' => 'q_unvollstaendige_organisationen.php', 'name' => 'q_unvollstaendige_organisationen');
    $result[] = array('caption' => '<span class="view">Tabellenstand</span>', 'short_caption' => 'Tabellenstand', 'filename' => 'tabellenstand.php', 'name' => 'q_last_updated_tables');
    return $result;
}

function GetPagesHeader()
{
    return
    '<a href="/"><img class="site-logo" width="30px" height="30px" typeof="foaf:Image" src="/sites/lobbywatch.ch/lobbywatch-eye-transparent-bg-cut-75px-tiny.png" alt="Lobbywatch"></a> <h1 id="site-name"><a href="/">Lobbywatch Datenbearbeitung ' . $GLOBALS["env"] . '</a></h1>';
}

function GetPagesFooter()
{
    return
        'Bearbeitungsseiten von <a href="' . $GLOBALS["env_dir"] . '">Lobbywatch ' . $GLOBALS["env"] . '</a>; <a href="' . $GLOBALS["env_dir"] . 'auswertung">Auswertung</a>; <a href="/wiki">Wiki</a><br>
Mode: ' . $GLOBALS["env"] . ' / Version: ' . $GLOBALS["version"] . ' / Deploy date: ' . $GLOBALS["deploy_date"] . ' / Build date: ' . $GLOBALS["build_date"] . ''; 
    }

function ApplyCommonPageSettings(Page $page, Grid $grid)
{
    $page->SetShowUserAuthBar(true);
    $grid->BeforeUpdateRecord->AddListener('Global_BeforeUpdateHandler');
    $grid->BeforeDeleteRecord->AddListener('Global_BeforeDeleteHandler');
    $grid->BeforeInsertRecord->AddListener('Global_BeforeInsertHandler');
}

/*
  Default code page: 1252
*/
function GetAnsiEncoding() { return 'windows-1252'; }

function Global_BeforeUpdateHandler($page, &$rowData, &$cancel, &$message, $tableName)
{
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
$rowData['updated_visa'] = $userName;
$rowData['updated_date'] = $datetime;

clean_fields($page, $rowData, $cancel, $message, $tableName);
}

function Global_BeforeDeleteHandler($page, &$rowData, &$cancel, &$message, $tableName)
{

}

function Global_BeforeInsertHandler($page, &$rowData, &$cancel, &$message, $tableName)
{
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

$rowData['created_visa'] = $userName;
$rowData['created_date'] = $datetime;

$rowData['updated_visa'] = $userName;
$rowData['updated_date'] = $datetime;

clean_fields($page, $rowData, $cancel, $message, $tableName);
}

function GetDefaultDateFormat()
{
    return 'd.m.Y';
}

function GetFirstDayOfWeek()
{
    return 1;
}

function GetEnableLessFilesRunTimeCompilation()
{
    return false;
}
