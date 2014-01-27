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
      if (GetCurrentUserGrantForDataSource('v_parlamentarier_authorisierungs_email')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="view">Parlamentarier Email</span>'), 'v_parlamentarier_authorisierungs_email.php', ('Parlamentarier Email'), $currentPageCaption == ('<span class="view">Parlamentarier Email</span>')));
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
        $sql = convert_utf8("SELECT parlamentarier.id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.email,
GROUP_CONCAT(DISTINCT
    CONCAT('<li>', organisation.anzeige_name, IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', interessenbindung.art, ', ', interessenbindung.beschreibung)
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
) interessenbindungen,
  GROUP_CONCAT(DISTINCT
    CONCAT('<li>', zutrittsberechtigung.name, ', ', zutrittsberechtigung.funktion)
    ORDER BY zutrittsberechtigung.name
    SEPARATOR ' '
  ) zutrittsberechtigungen,
  GROUP_CONCAT(DISTINCT
    CONCAT('<li>', zutrittsberechtigung.name, ', ', zutrittsberechtigung.funktion,
    IF (organisation2.id IS NOT NULL,
      CONCAT(', ',
        organisation2.anzeige_name
        , IF(organisation2.rechtsform IS NULL OR TRIM(organisation2.rechtsform) = '', '', CONCAT(', ', organisation2.rechtsform)), IF(organisation2.ort IS NULL OR TRIM(organisation2.ort) = '', '', CONCAT(', ', organisation2.ort)), ', '
        , IFNULL(mandat.art, ''), ', ', IFNULL(mandat.beschreibung, '')
      ),
      '')
    )
    ORDER BY zutrittsberechtigung.name, organisation2.anzeige_name
    SEPARATOR ' '
  ) mandate,
CONCAT(
  CASE parlamentarier.geschlecht
    WHEN 'M' THEN CONCAT('<p>Sehr geehrter Herr ', parlamentarier.nachname, '</p>')
    WHEN 'F' THEN CONCAT('<p>Sehr geehrte Frau ', parlamentarier.nachname, '</p>')
    ELSE CONCAT('<p>Sehr geehrte(r) Herr/Frau ', parlamentarier.nachname, '</p>')
  END,
  '<p>[Einleitung]</p>',
  '<p>Ihre <b>Interessenbindungen</b>:</p>',
  '<ul>',
  GROUP_CONCAT(DISTINCT
    CONCAT('<li>', organisation.anzeige_name, IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', interessenbindung.art, ', ', interessenbindung.beschreibung)
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
  ),
  '</ul>',
  '<p>Ihre <b>Gäste</b>:</p>',
  '<ul>',
  GROUP_CONCAT(DISTINCT
    CONCAT('<li>', zutrittsberechtigung.name, ', ', zutrittsberechtigung.funktion)
    ORDER BY zutrittsberechtigung.name
    SEPARATOR ' '
  ),
  '</ul>',
  '<p><b>Mandate</b> der Gäste:</p>',
  '<ul>',
  GROUP_CONCAT(DISTINCT
    CONCAT('<li>', zutrittsberechtigung.name, ', ', zutrittsberechtigung.funktion,
    IF (organisation2.id IS NOT NULL,
      CONCAT(', ',
        organisation2.anzeige_name
        , IF(organisation2.rechtsform IS NULL OR TRIM(organisation2.rechtsform) = '', '', CONCAT(', ', organisation2.rechtsform)), IF(organisation2.ort IS NULL OR TRIM(organisation2.ort) = '', '', CONCAT(', ', organisation2.ort)), ', '
        , IFNULL(mandat.art, ''), ', ', IFNULL(mandat.beschreibung, '')
      ),
      '')
    )
    ORDER BY zutrittsberechtigung.name, organisation2.anzeige_name
    SEPARATOR ' '
  ),
  '</ul>',
  '<p>Freundliche Grüsse<br></p>'
) email_text_html
FROM v_parlamentarier parlamentarier
LEFT JOIN v_interessenbindung interessenbindung
  ON interessenbindung.parlamentarier_id = parlamentarier.id
LEFT JOIN v_organisation organisation
  ON interessenbindung.organisation_id = organisation.id
LEFT JOIN v_zutrittsberechtigung zutrittsberechtigung
  ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id
LEFT JOIN v_mandat mandat
  ON mandat.zutrittsberechtigung_id = zutrittsberechtigung.id
LEFT JOIN v_organisation organisation2
  ON mandat.organisation_id = organisation2.id
WHERE
parlamentarier.im_rat_bis IS NULL
AND interessenbindung.bis IS NULL
AND zutrittsberechtigung.bis IS NULL
AND mandat.bis IS NULL
AND parlamentarier.id=:id
GROUP BY parlamentarier.id;");
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
              '<h4>Gäste</h4><ul>' . $result[0]['zutrittsberechtigungen'] . '</ul>' .
              '<h4>Mandate</h4><ul>' . $result[0]['mandate'] . '</ul>',
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
