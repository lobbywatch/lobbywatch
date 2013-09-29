<?php
// Processed by afterburner.sh

//  define('SHOW_VARIABLES', 1);
//  define('DEBUG_LEVEL', 1);

//  error_reporting(E_ALL ^ E_NOTICE);
//  ini_set('display_errors', 'On');

set_include_path('.' . PATH_SEPARATOR . get_include_path());


require_once 'components/utils/system_utils.php';

//  SystemUtils::DisableMagicQuotesRuntime();

SystemUtils::SetTimeZoneIfNeed('Europe/Belgrade');

function GetGlobalConnectionOptions()
{
    return array(
  'server' => 'localhost',
  'port' => '3306',
  'username' => 'web369',
  'password' => 'D-UaQ1EDGjwwvBv7oB2X',
  'database' => 'usr_web369_5'
);
}

function HasAdminPage()
{
    return false;
}

function GetPageInfos()
{
    $result = array();
    $result[] = array('caption' => 'Interessenbindungen', 'short_caption' => 'Interessenbindungen', 'filename' => 'interessenbindungen.php', 'name' => 'interessenbindungen');
    $result[] = array('caption' => 'Zugangsberechtigungen', 'short_caption' => 'Zugangsberechtigungen', 'filename' => 'zugangsberechtigungen.php', 'name' => 'zugangsberechtigungen');
    $result[] = array('caption' => 'Parlamentarier', 'short_caption' => 'Parlamentarier', 'filename' => 'parlamentarier.php', 'name' => 'parlamentarier');
    $result[] = array('caption' => 'Lobbyorganisationen', 'short_caption' => 'Lobbyorganisationen', 'filename' => 'lobbyorganisationen.php', 'name' => 'lobbyorganisationen');
    $result[] = array('caption' => 'Lobbygruppen', 'short_caption' => 'Lobbygruppen', 'filename' => 'lobbygruppen.php', 'name' => 'lobbygruppen');
    $result[] = array('caption' => 'Lobbytypen', 'short_caption' => 'Lobbytypen', 'filename' => 'lobbytypen.php', 'name' => 'lobbytypen');
    $result[] = array('caption' => 'Kommissionen', 'short_caption' => 'Kommissionen', 'filename' => 'kommissionen.php', 'name' => 'kommissionen');
    return $result;
}

function GetPagesHeader()
{
    return
    '<h1>Lobbycontrol Datenbearbeitung</h1>';
}

function GetPagesFooter()
{
    return
        'Administrationsseiten von <a href="/lobbycontrol">Lobbycontrol</a>.'; 
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



?>

