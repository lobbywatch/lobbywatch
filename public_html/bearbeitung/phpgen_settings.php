<?php
// Processed by afterburner.sh

require_once dirname(__FILE__) . "/../common/settings.php";
require_once dirname(__FILE__) . "/custom/custom.php";
require_once dirname(__FILE__) . "/../common/build_date.php";
// Processed by afterburner.sh



//  define('SHOW_VARIABLES', 1);
//  define('DEBUG_LEVEL', 1);

//  error_reporting(E_ALL ^ E_NOTICE);
//  ini_set('display_errors', 'On');

set_include_path('.' . PATH_SEPARATOR . get_include_path());


require_once 'components/utils/system_utils.php';

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
    $result[] = array('caption' => 'Lobbyorganisation', 'short_caption' => 'Lobbyorganisation', 'filename' => 'lobbyorganisation.php', 'name' => 'lobbyorganisation');
    $result[] = array('caption' => 'Parlamentarier', 'short_caption' => 'Parlamentarier', 'filename' => 'parlamentarier.php', 'name' => 'parlamentarier');
    $result[] = array('caption' => 'Interessengruppe', 'short_caption' => 'Interessengruppe', 'filename' => 'interessengruppe.php', 'name' => 'interessengruppe');
    $result[] = array('caption' => 'Branche', 'short_caption' => 'Branche', 'filename' => 'branche.php', 'name' => 'branche');
    $result[] = array('caption' => 'Kommission', 'short_caption' => 'Kommission', 'filename' => 'kommission.php', 'name' => 'kommission');
    $result[] = array('caption' => 'Partei', 'short_caption' => 'Partei', 'filename' => 'partei.php', 'name' => 'partei');
    $result[] = array('caption' => 'Zugangsberechtigung', 'short_caption' => 'Zugangsberechtigung', 'filename' => 'zugangsberechtigung.php', 'name' => 'zugangsberechtigung');
    $result[] = array('caption' => 'Interessenbindung', 'short_caption' => 'Interessenbindung', 'filename' => 'interessenbindung.php', 'name' => 'interessenbindung');
    return $result;
}

function GetPagesHeader()
{
    return
    '<h1>Lobbycontrol Datenbearbeitung</h1>';
}

function GetPagesFooter(){
    global $build_date;

    return
        'Bearbeitungsseiten von <a href="/">Lobbycontrol</a>.<br>
Build date: ' . "$build_date" . ''; 
    }

function ApplyCommonPageSettings($page, $grid)
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

function Global_BeforeUpdateHandler($page, $rowData, &$cancel, &$message, $tableName)
{

}

function Global_BeforeDeleteHandler($page, $rowData, &$cancel, &$message, $tableName)
{

}

function Global_BeforeInsertHandler($page, $rowData, &$cancel, &$message, $tableName)
{

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




