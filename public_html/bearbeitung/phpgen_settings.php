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
    $result[] = array('caption' => '<span class="entity">Zutrittsberechtigter</span>', 'short_caption' => 'Zutrittsberechtigter', 'filename' => 'zutrittsberechtigung.php', 'name' => 'zutrittsberechtigung');
    $result[] = array('caption' => '<span class="relation">Interessenbindung</span>', 'short_caption' => 'Interessenbindung', 'filename' => 'interessenbindung.php', 'name' => 'interessenbindung');
    $result[] = array('caption' => '<span class="relation">Mandat</span>', 'short_caption' => 'Mandat', 'filename' => 'mandat.php', 'name' => 'mandat');
    $result[] = array('caption' => '<span class="relation">In Kommission</span>', 'short_caption' => 'In Kommission', 'filename' => 'in_kommission.php', 'name' => 'in_kommission');
    $result[] = array('caption' => '<span class="relation">Organisation Beziehung</span>', 'short_caption' => 'Organisation Beziehung', 'filename' => 'organisation_beziehung.php', 'name' => 'organisation_beziehung');
    $result[] = array('caption' => '<span class="entity">Branche</span>', 'short_caption' => 'Branche', 'filename' => 'branche.php', 'name' => 'branche');
    $result[] = array('caption' => '<span class="entity">Lobbygruppe</span>', 'short_caption' => 'Lobbygruppe', 'filename' => 'interessengruppe.php', 'name' => 'interessengruppe');
    $result[] = array('caption' => '<span class="entity">Kommission</span>', 'short_caption' => 'Kommission', 'filename' => 'kommission.php', 'name' => 'kommission');
    $result[] = array('caption' => '<span class="entity">Partei</span>', 'short_caption' => 'Partei', 'filename' => 'partei.php', 'name' => 'partei');
    $result[] = array('caption' => '<span class="entity">Fraktion</span>', 'short_caption' => 'Fraktion', 'filename' => 'fraktion.php', 'name' => 'fraktion');
    $result[] = array('caption' => '<span class="entity">Kanton</span>', 'short_caption' => 'Kanton', 'filename' => 'kanton.php', 'name' => 'kanton');
    $result[] = array('caption' => '<span class="settings">Settings</span>', 'short_caption' => 'Settings', 'filename' => 'settings.php', 'name' => 'settings');
    $result[] = array('caption' => '<span class="settings">Settings Category</span>', 'short_caption' => 'Settings Category', 'filename' => 'settings_category.php', 'name' => 'settings_category');
    $result[] = array('caption' => '<span class="settings">User</span>', 'short_caption' => 'User', 'filename' => 'user.php', 'name' => 'user');
    $result[] = array('caption' => '<span class="view">Unvollständige Parlamentarier</span>', 'short_caption' => 'Unvollständige Parlamentarier', 'filename' => 'q_unvollstaendige_parlamentarier.php', 'name' => 'q_unvollstaendige_parlamentarier');
    $result[] = array('caption' => '<span class="view">Unvollständige Zutrittsberechtigte</span>', 'short_caption' => 'Unvollständige Zutrittsberechtigte', 'filename' => 'q_unvollstaendige_zutrittsberechtigte.php', 'name' => 'q_unvollstaendige_zutrittsberechtigte');
    $result[] = array('caption' => '<span class="view">Unvollständige Organisationen</span>', 'short_caption' => 'Unvollständige Organisationen', 'filename' => 'q_unvollstaendige_organisationen.php', 'name' => 'q_unvollstaendige_organisationen');
    $result[] = array('caption' => '<span class="view">Tabellenstand</span>', 'short_caption' => 'Tabellenstand', 'filename' => 'tabellenstand.php', 'name' => 'q_last_updated_tables');
    return $result;
}

function GetPagesHeader()
{
    return
    '<a href="/"><img id="site-logo" width="30px" height="auto" typeof="foaf:Image" src="lobbywatch-eye-transparent-bg-cut-75px-tiny.png" alt="Lobbywatch"></a> <h1 id="site-name"><a href="/">Lobbywatch Datenbearbeitung ' . $GLOBALS["env"] . '</a></h1>';
}

function GetPagesFooter()
{
    return
        'Bearbeitungsseiten von <a href="' . $GLOBALS["env_dir"] . '">Lobbywatch ' . $GLOBALS["env"] . '</a>; <!-- a href="' . $GLOBALS["env_dir"] . 'auswertung">Auswertung</a--> <a href="/wiki">Wiki</a><br>
Mode: ' . $GLOBALS["env"] . ' / Version: ' . $GLOBALS["version"] . ' / Deploy date: ' . $GLOBALS["deploy_date"] . ' / Build date: ' . $GLOBALS["build_date"] . ' / Page execution time: ' . _custom_page_build_secs() . 's'; 
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
globalOnBeforeUpdate($page, $rowData, $cancel, $message, $tableName);
}

function Global_BeforeDeleteHandler($page, &$rowData, &$cancel, &$message, $tableName)
{
globalOnBeforeDelete($page, $rowData, $cancel, $message, $tableName);
}

function Global_BeforeInsertHandler($page, &$rowData, &$cancel, &$message, $tableName)
{
globalOnBeforeInsert($page, $rowData, $cancel, $message, $tableName);
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
