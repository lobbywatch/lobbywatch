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
    return false;
}

function GetPageInfos()
{
    $result = array();
    $result[] = array('caption' => 'Kommission', 'short_caption' => 'Kommission', 'filename' => 'kommission.php', 'name' => 'kommission');
    $result[] = array('caption' => 'Organisation', 'short_caption' => 'Organisation', 'filename' => 'organisation.php', 'name' => 'organisation');
    $result[] = array('caption' => 'Parlamentarier', 'short_caption' => 'Parlamentarier', 'filename' => 'parlamentarier.php', 'name' => 'parlamentarier');
    $result[] = array('caption' => 'In Kommission', 'short_caption' => 'In Kommission', 'filename' => 'in_kommission.php', 'name' => 'in_kommission');
    $result[] = array('caption' => 'Interessenbindung', 'short_caption' => 'Interessenbindung', 'filename' => 'interessenbindung.php', 'name' => 'interessenbindung');
    $result[] = array('caption' => 'Zugangsberechtigung', 'short_caption' => 'Zugangsberechtigung', 'filename' => 'zugangsberechtigung.php', 'name' => 'zugangsberechtigung');
    $result[] = array('caption' => 'Mandat', 'short_caption' => 'Mandat', 'filename' => 'mandat.php', 'name' => 'mandat');
    $result[] = array('caption' => 'Organisation Beziehung', 'short_caption' => 'Organisation Beziehung', 'filename' => 'organisation_beziehung.php', 'name' => 'organisation_beziehung');
    $result[] = array('caption' => 'Branche', 'short_caption' => 'Branche', 'filename' => 'branche.php', 'name' => 'branche');
    $result[] = array('caption' => 'Partei', 'short_caption' => 'Partei', 'filename' => 'partei.php', 'name' => 'partei');
    $result[] = array('caption' => 'Interessengruppe', 'short_caption' => 'Interessengruppe', 'filename' => 'interessengruppe.php', 'name' => 'interessengruppe');
    $result[] = array('caption' => 'Parlamentarier Email', 'short_caption' => 'Parlamentarier Email', 'filename' => 'v_parlamentarier_authorisierungs_email.php', 'name' => 'v_parlamentarier_authorisierungs_email');
    $result[] = array('caption' => 'Unvollständige Parlamentarier', 'short_caption' => 'Unvollständige Parlamentarier', 'filename' => 'q_unvollstaendige_parlamentarier.php', 'name' => 'q_unvollstaendige_parlamentarier');
    $result[] = array('caption' => 'Unvollständige Organisationen', 'short_caption' => 'Unvollständige Organisationen', 'filename' => 'q_unvollstaendige_organisationen.php', 'name' => 'q_unvollstaendige_organisationen');
    $result[] = array('caption' => 'Tabellenstand', 'short_caption' => 'Tabellenstand', 'filename' => 'tabellenstand.php', 'name' => 'q_last_updated_tables');
    return $result;
}

function GetPagesHeader()
{
    return
    '<h1>Lobbycontrol Datenbearbeitung ' . $GLOBALS["env"] . '</h1>';
}

function GetPagesFooter()
{
    return
        'Bearbeitungsseiten von <a href="' . $GLOBALS["env_dir"] . '">Lobbycontrol ' . $GLOBALS["env"] . '</a>.<br>
Mode: ' . $GLOBALS["env"] . ' / Build date: ' . $GLOBALS["build_date"] . ''; 
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
