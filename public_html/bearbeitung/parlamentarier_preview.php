<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */


    include_once dirname(__FILE__) . '/' . 'components/utils/check_utils.php';
    CheckPHPVersion();
    CheckTemplatesCacheFolderIsExistsAndWritable();


    include_once dirname(__FILE__) . '/' . 'phpgen_settings.php';
    include_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';
    include_once dirname(__FILE__) . '/' . 'components/page.php';
    include_once dirname(__FILE__) . '/' . 'authorization.php';

    function GetConnectionOptions()
    {
        $result = GetGlobalConnectionOptions();
        $result['client_encoding'] = 'utf8';
        GetApplication()->GetUserAuthorizationStrategy()->ApplyIdentityToConnectionOptions($result);
        return $result;
    }

    function ShowPreviewPage($message)
    {
      $smarty = new Smarty();
      $smarty->template_dir = '/components/templates';
      $smarty->assign('Message', $message);
      $captions = GetCaptions('UTF-8');
      $smarty->assign('Captions', $captions);
      $smarty->assign('App', array(
          'ContentEncoding' => 'UTF-8',
          'PageCaption' => 'Some title'
      ));
      $smarty->display('custom_templates/parlamentarier_preview_page.tpl');
    }

    function setHTTPHeader() {
      header('Pragma: public');
      header('Cache-Control: max-age=0');
      header('Content-Type: text/html; charset=utf-8');
    }

    function GetPageList()
    {
      $currentPageCaption = 'Preview';
      $result = new PageList(null);
      if (GetCurrentUserGrantForDataSource('organisation')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="entity important-entity">Organisation</span>'), 'organisation.php', ('Organisation'), $currentPageCaption == ('<span class="entity important-entity">Organisation</span>')));
      if (GetCurrentUserGrantForDataSource('parlamentarier')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="entity important-entity">Parlamentarier</span>'), 'parlamentarier.php', ('Parlamentarier'), $currentPageCaption == ('<span class="entity important-entity">Parlamentarier</span>')));
      if (GetCurrentUserGrantForDataSource('zutrittsberechtigung')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="entity">Zutrittsberechtigung</span>'), 'zutrittsberechtigung.php', ('Zutrittsberechtigung'), $currentPageCaption == ('<span class="entity">Zutrittsberechtigung</span>')));
      if (GetCurrentUserGrantForDataSource('interessenbindung')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="relation">Interessenbindung</span>'), 'interessenbindung.php', ('Interessenbindung'), $currentPageCaption == ('<span class="relation">Interessenbindung</span>')));
      if (GetCurrentUserGrantForDataSource('mandat')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="relation">Mandat</span>'), 'mandat.php', ('Mandat'), $currentPageCaption == ('<span class="relation">Mandat</span>')));
      if (GetCurrentUserGrantForDataSource('in_kommission')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="relation">In Kommission</span>'), 'in_kommission.php', ('In Kommission'), $currentPageCaption == ('<span class="relation">In Kommission</span>')));
      if (GetCurrentUserGrantForDataSource('organisation_beziehung')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="relation">Organisation Beziehung</span>'), 'organisation_beziehung.php', ('Organisation Beziehung'), $currentPageCaption == ('<span class="relation">Organisation Beziehung</span>')));
      if (GetCurrentUserGrantForDataSource('interessengruppe')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="entity">Interessengruppe</span>'), 'interessengruppe.php', ('Interessengruppe'), $currentPageCaption == ('<span class="entity">Interessengruppe</span>')));
      if (GetCurrentUserGrantForDataSource('branche')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="entity">Branche</span>'), 'branche.php', ('Branche'), $currentPageCaption == ('<span class="entity">Branche</span>')));
      if (GetCurrentUserGrantForDataSource('kommission')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="entity">Kommission</span>'), 'kommission.php', ('Kommission'), $currentPageCaption == ('<span class="entity">Kommission</span>')));
      if (GetCurrentUserGrantForDataSource('partei')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="entity">Partei</span>'), 'partei.php', ('Partei'), $currentPageCaption == ('<span class="entity">Partei</span>')));
      if (GetCurrentUserGrantForDataSource('fraktion')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="entity">Fraktion</span>'), 'fraktion.php', ('Fraktion'), $currentPageCaption == ('<span class="entity">Fraktion</span>')));
      if (GetCurrentUserGrantForDataSource('q_unvollstaendige_parlamentarier')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="view">Unvollständige Parlamentarier</span>'), 'q_unvollstaendige_parlamentarier.php', ('Unvollständige Parlamentarier'), $currentPageCaption == ('<span class="view">Unvollständige Parlamentarier</span>')));
      if (GetCurrentUserGrantForDataSource('q_unvollstaendige_organisationen')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="view">Unvollständige Organisationen</span>'), 'q_unvollstaendige_organisationen.php', ('Unvollständige Organisationen'), $currentPageCaption == ('<span class="view">Unvollständige Organisationen</span>')));
      if (GetCurrentUserGrantForDataSource('q_last_updated_tables')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="view">Tabellenstand</span>'), 'tabellenstand.php', ('Tabellenstand'), $currentPageCaption == ('<span class="view">Tabellenstand</span>')));

      if ( HasAdminPage() && GetApplication()->HasAdminGrantForCurrentUser() )
        $result->AddPage(new PageLink(('AdminPage'), 'phpgen_admin.php', ('AdminPage'), false, true));

      add_more_navigation_links($result); // Was once Afterburned
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
          ShowErrorPage('Not logged in.<br><br>Please <a href="login.php">log in</a>.');
          exit(1);
        }
//         print "Say hello";

        $param = 'pk0';
        if (GetApplication()->IsGETValueSet($param)){
          if (!($id = GetApplication()->GetGETValue($param))) {
            throw new Exception('ID missing');
          }
        } else {
          throw new Exception('ID parameter missing');
       }

        $con_factory = new MyPDOConnectionFactory();
        $options = GetConnectionOptions();
        $eng_con = $con_factory->CreateConnection($options);
        $eng_con->Connect();
        $con = $eng_con->GetConnectionHandle();
//         df($eng_con->Connected(), 'connected');
//         df($con, 'con');
        $cmd = $con_factory->CreateEngCommandImp();

        $sql = "SELECT parlamentarier.id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.email, parlamentarier.geschlecht,
  GROUP_CONCAT(DISTINCT
      CONCAT('<li>', organisation.anzeige_name, IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', CONCAT(UCASE(LEFT(interessenbindung.art, 1)), SUBSTRING(interessenbindung.art, 2)), IF(interessenbindung.beschreibung IS NULL OR TRIM(interessenbindung.beschreibung) = '', '', CONCAT(', ',interessenbindung.beschreibung))
)
      ORDER BY organisation.anzeige_name
      SEPARATOR ' '
  ) interessenbindungen,
  GROUP_CONCAT(DISTINCT
      CONCAT('<li>', organisation.anzeige_name, IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', CONCAT(UCASE(LEFT(interessenbindung.art, 1)), SUBSTRING(interessenbindung.art, 2)), IF(TRUE OR interessenbindung.beschreibung IS NULL OR TRIM(interessenbindung.beschreibung) = '', '', CONCAT(', ',interessenbindung.beschreibung))
)
      ORDER BY organisation.anzeige_name
      SEPARATOR ' '
  ) interessenbindungen_for_email,
            GROUP_CONCAT(DISTINCT
    CONCAT('<li>', zutrittsberechtigung.name, ', ', zutrittsberechtigung.funktion)
    ORDER BY zutrittsberechtigung.name
    SEPARATOR ' '
  ) zutrittsberechtigungen,
  CASE parlamentarier.geschlecht
  WHEN 'M' THEN CONCAT('<p>Sehr geehrter Herr ', parlamentarier.nachname, '</p>')
  WHEN 'F' THEN CONCAT('<p>Sehr geehrte Frau ', parlamentarier.nachname, '</p>')
  ELSE CONCAT('<p>Sehr geehrte(r) Herr/Frau ', parlamentarier.nachname, '</p>')
  END anrede
FROM v_parlamentarier parlamentarier
LEFT JOIN v_interessenbindung interessenbindung
  ON interessenbindung.parlamentarier_id = parlamentarier.id AND interessenbindung.bis IS NULL
LEFT JOIN v_organisation organisation
  ON interessenbindung.organisation_id = organisation.id
LEFT JOIN v_zutrittsberechtigung zutrittsberechtigung
  ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id AND zutrittsberechtigung.bis IS NULL
WHERE
  parlamentarier.im_rat_bis IS NULL
  AND parlamentarier.id=:id
GROUP BY parlamentarier.id;";

//         df($sql);
        $result = array();
//         $eng_con->ExecQueryToArray($sql, $result);
//          df($eng_con->LastError(), 'last error');
//         $eng_con->Disconnect();
//         df($result, 'result');
//         $preview = $result[0]['email_text_html'];

//         $q = $con->query($sql);
//         $result2 = $q->fetchAll();
//         df($eng_con->LastError(), 'last error');
//         df($q, 'q');
//         df($result2, 'result2');

        $sth = $con->prepare($sql);
        $sth->execute(array(':id' => $id));
        $result = $sth->fetchAll();

        if (!$result) {
          throw new Exception('ID not found');
        }

//         ShowPreviewPage('<h4>Preview</h4><h3>' .$result[0]["parlamentarier_name"] . '</h3>' .
//         '<h4>Interessenbindungen</h4><ul>' . $result[0]['interessenbindungen'] . '</ul>' .
//         '<h4>Gäste</h4><ul>' . $result[0]['zutrittsberechtigungen'] . '</ul>' .
//         '<h4>Mandate</h4><ul>' . $result[0]['mandate'] . '</ul>');
        DisplayTemplateSimple('custom_templates/parlamentarier_preview_page.tpl',
          array(
          ),
          array(
            'App' => array(
              'ContentEncoding' => 'UTF-8',
              'PageCaption' => 'Vorschau: ' . $result[0]["parlamentarier_name"],
              'Header' => GetPagesHeader(),
              'Direction' => 'ltr',
          ),
            'Footer' => GetPagesFooter(),
            'Parlamentarier' => array(
              'Title' => 'Vorschau: ' . $result[0]["parlamentarier_name"],
              'Preview' => '<h4>Interessenbindungen</h4><ul>' . $result[0]['interessenbindungen'] . '</ul>' .
                '<h4>Gäste</h4>' . ($result[0]['zutrittsberechtigungen'] ? '<ul>' . $result[0]['zutrittsberechtigungen'] . '</ul>': '<p>keine</p>') .
                '<h4>Mandate der Gäste</h4>' . gaesteMitMandaten($con, $id),
              'EmailTitle' => 'Autorisierungs-E-Mail: ' . '<a href="mailto:' . urlencode($result[0]["email"]) . '?subject=' . urlencode('Interessenbindungen') . '&body=' . urlencode('[Kopiere von Vorlage]') . '&bcc=info@lobbywatch.ch" target="_blank">' . $result[0]["parlamentarier_name"] . '</a>',
              'EmailText' => '<p>' . $result[0]['anrede'] . '</p>' .'<p>[Einleitung]</p>' . '<p>Ihre <b>Interessenbindungen</b>:</p><ul>' . $result[0]['interessenbindungen_for_email'] . '</ul>' .
                '<p>Ihre <b>Gäste</b>:</p>' . ($result[0]['zutrittsberechtigungen'] ? '<ul>' . $result[0]['zutrittsberechtigungen'] . '</ul>': '<p>keine</p>') .
                '<p><b>Mandate</b> Ihrer Gäste:<p>' . gaesteMitMandaten($con, $id) . '<p>Freundliche Grüsse<br>' . getFullUsername(Application::Instance()->GetCurrentUser()) . '</p>',
          ),
            'Authentication' => array(
                'Enabled' => true,
                'LoggedIn' => GetApplication()->IsCurrentUserLoggedIn(),
                'CurrentUser' => array(
                    'Name' => GetApplication()->GetCurrentUser(),
                    'Id' => GetApplication()->GetCurrentUserId(),
                ),
                'CanChangeOwnPassword' => GetApplication()->GetUserManager()->CanChangeUserPassword() &&
                        GetApplication()->CanUserChangeOwnPassword(),
            ),
            'HideSideBarByDefault' => true,
            'PageList' => GetPageList()->GetViewData(),
            'Variables' => '',
          )
        );
    }
    catch(Exception $e)
    {
        ShowErrorPage($e->getMessage());
    }
