<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

/**
 * This file was written quick and dirty. ;-)
 */

//phpinfo();

include_once dirname(__FILE__) . '/../bearbeitung/components/utils/check_utils.php';
CheckPHPVersion();
CheckTemplatesCacheFolderIsExistsAndWritable();


include_once dirname(__FILE__) . '/../bearbeitung/phpgen_settings.php';
include_once dirname(__FILE__) . '/../bearbeitung/database_engine/mysql_engine.php';
include_once dirname(__FILE__) . '/../bearbeitung/components/page.php';
include_once dirname(__FILE__) . '/../bearbeitung/authorization.php';

function GetConnectionOptions()
{
    $result = GetGlobalConnectionOptions();
    $result['client_encoding'] = 'utf8';
    GetApplication()->GetUserAuthorizationStrategy()->ApplyIdentityToConnectionOptions($result);
    return $result;
}

function setHTTPHeader() {
  header('Pragma: public');
  header('Cache-Control: max-age=0');
  header('Content-Type: text/html; charset=utf-8');
}

function GetPageList() {
    $currentPageCaption = 'Preview';
            $result = new PageList(null);
            $result->AddGroup('Subjektdaten');
            $result->AddGroup('Verbindungen');
            $result->AddGroup('Stammdaten');
            $result->AddGroup('Metadaten');
            $result->AddGroup('Misc');
            if (GetCurrentUserGrantForDataSource('organisation')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="entity important-entity">Organisation</span>'), 'organisation.php', ('Organisation'), $currentPageCaption == ('<span class="entity important-entity">Organisation</span>'), false, 'Subjektdaten'));
            if (GetCurrentUserGrantForDataSource('parlamentarier')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="entity important-entity">Parlamentarier</span>'), 'parlamentarier.php', ('Parlamentarier'), $currentPageCaption == ('<span class="entity important-entity">Parlamentarier</span>'), false, 'Subjektdaten'));
            if (GetCurrentUserGrantForDataSource('person')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="entity important-entity">Person</span>'), 'person.php', ('Person'), $currentPageCaption == ('<span class="entity important-entity">Person</span>'), false, 'Subjektdaten'));
            if (GetCurrentUserGrantForDataSource('interessenbindung')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="relation" title="Interessenbindungen der Parlamentarier">Intereressenbind. von NR/SR</span>'), 'interessenbindung.php', ('Interessenbindung'), $currentPageCaption == ('<span class="relation" title="Interessenbindungen der Parlamentarier">Intereressenbind. von NR/SR</span>'), false, 'Verbindungen'));
            if (GetCurrentUserGrantForDataSource('zutrittsberechtigung')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="relation" title="Zutrittsberechtigungen für Gäse ins Bundeshaus">Zutrittsberechtigung</span>'), 'zutrittsberechtigung.php', ('Zutrittsberechtigung'), $currentPageCaption == ('<span class="relation" title="Zutrittsberechtigungen für Gäse ins Bundeshaus">Zutrittsberechtigung</span>'), false, 'Verbindungen'));
            if (GetCurrentUserGrantForDataSource('mandat')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="relation" title="Mandate der Zutrittsberechtigten">Mandate von Pers.</span>'), 'mandat.php', ('Mandat'), $currentPageCaption == ('<span class="relation" title="Mandate der Zutrittsberechtigten">Mandate von Pers.</span>'), false, 'Verbindungen'));
            if (GetCurrentUserGrantForDataSource('in_kommission')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="relation">In Kommission</span>'), 'in_kommission.php', ('In Kommission'), $currentPageCaption == ('<span class="relation">In Kommission</span>'), false, 'Verbindungen'));
            if (GetCurrentUserGrantForDataSource('organisation_beziehung')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="relation">Organisation Beziehung</span>'), 'organisation_beziehung.php', ('Organisation Beziehung'), $currentPageCaption == ('<span class="relation">Organisation Beziehung</span>'), false, 'Verbindungen'));
            if (GetCurrentUserGrantForDataSource('branche')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="entity">Branche</span>'), 'branche.php', ('Branche'), $currentPageCaption == ('<span class="entity">Branche</span>'), false, 'Stammdaten'));
            if (GetCurrentUserGrantForDataSource('interessengruppe')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="entity">Lobbygruppe</span>'), 'interessengruppe.php', ('Lobbygruppe'), $currentPageCaption == ('<span class="entity">Lobbygruppe</span>'), false, 'Stammdaten'));
            if (GetCurrentUserGrantForDataSource('kommission')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="entity">Kommission</span>'), 'kommission.php', ('Kommission'), $currentPageCaption == ('<span class="entity">Kommission</span>'), false, 'Stammdaten'));
            if (GetCurrentUserGrantForDataSource('partei')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="entity">Partei</span>'), 'partei.php', ('Partei'), $currentPageCaption == ('<span class="entity">Partei</span>'), false, 'Stammdaten'));
            if (GetCurrentUserGrantForDataSource('fraktion')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="entity">Fraktion</span>'), 'fraktion.php', ('Fraktion'), $currentPageCaption == ('<span class="entity">Fraktion</span>'), false, 'Stammdaten'));
            if (GetCurrentUserGrantForDataSource('kanton')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="entity">Kanton</span>'), 'kanton.php', ('Kanton'), $currentPageCaption == ('<span class="entity">Kanton</span>'), false, 'Stammdaten'));
            if (GetCurrentUserGrantForDataSource('settings')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="settings">Settings</span>'), 'settings.php', ('Settings'), $currentPageCaption == ('<span class="settings">Settings</span>'), false, 'Metadaten'));
            if (GetCurrentUserGrantForDataSource('settings_category')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="settings">Settings Category</span>'), 'settings_category.php', ('Settings Category'), $currentPageCaption == ('<span class="settings">Settings Category</span>'), false, 'Metadaten'));
            if (GetCurrentUserGrantForDataSource('translation_source')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="settings">Translation Source</span>'), 'translation_source.php', ('Translation Source'), $currentPageCaption == ('<span class="settings">Translation Source</span>'), false, 'Metadaten'));
            if (GetCurrentUserGrantForDataSource('translation_target')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="settings">Translation Target</span>'), 'translation_target.php', ('Translation Target'), $currentPageCaption == ('<span class="settings">Translation Target</span>'), false, 'Metadaten'));
            if (GetCurrentUserGrantForDataSource('user')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="settings">User</span>'), 'user.php', ('User'), $currentPageCaption == ('<span class="settings">User</span>'), false, 'Metadaten'));
            if (GetCurrentUserGrantForDataSource('q_unvollstaendige_parlamentarier')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="view">Unvollständige Parlamentarier</span>'), 'q_unvollstaendige_parlamentarier.php', ('Unvollständige Parlamentarier'), $currentPageCaption == ('<span class="view">Unvollständige Parlamentarier</span>'), false, 'Misc'));
            if (GetCurrentUserGrantForDataSource('q_unvollstaendige_zutrittsberechtigte')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="view">Unvollständige Zutrittsberechtigte</span>'), 'q_unvollstaendige_zutrittsberechtigte.php', ('Unvollständige Zutrittsberechtigte'), $currentPageCaption == ('<span class="view">Unvollständige Zutrittsberechtigte</span>'), false, 'Misc'));
            if (GetCurrentUserGrantForDataSource('q_unvollstaendige_organisationen')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="view">Unvollständige Organisationen</span>'), 'q_unvollstaendige_organisationen.php', ('Unvollständige Organisationen'), $currentPageCaption == ('<span class="view">Unvollständige Organisationen</span>'), false, 'Misc'));
            if (GetCurrentUserGrantForDataSource('q_last_updated_tables')->HasViewGrant())
                $result->AddPage(new PageLink(('<span class="view">Tabellenstand</span>'), 'tabellenstand.php', ('Tabellenstand'), $currentPageCaption == ('<span class="view">Tabellenstand</span>'), false, 'Misc'));

            if ( HasAdminPage() && GetApplication()->HasAdminGrantForCurrentUser() ) {
              $result->AddGroup('Admin area');
              $result->AddPage(new PageLink(('AdminPage'), 'phpgen_admin.php', ('AdminPage'), false, false, 'Admin area'));
              }

            add_more_navigation_links($result); // Afterburned
              {
            }
            return $result;
}

// Main

SetUpUserAuthorization(GetApplication());

try
{
//         $Page = new parteiPage("partei.php", "partei", GetCurrentUserGrantForDataSource("partei"), 'UTF-8');
//         $Page->SetShortCaption('<span class="entity">Partei</span>');
//         $Page->SetHeader(GetPagesHeader());
//         $Page->SetFooter(GetPagesFooter());
//         $Page->SetCaption('Partei');
//         $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("partei"));
//         GetApplication()->SetEnableLessRunTimeCompile(GetEnableLessFilesRunTimeCompilation());
      GetApplication()->SetCanUserChangeOwnPassword(
          !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
//         GetApplication()->SetMainPage($Page);
//         before_render($Page);
//         GetApplication()->Run();
    if (!GetApplication()->IsCurrentUserLoggedIn()) {
      ShowErrorPage(new Exception('Not logged in.<br><br>Please <a href="login.php">log in</a>.'));
      exit(1);
    }

}
catch(Exception $e)
{
    ShowErrorPage($e);
}
