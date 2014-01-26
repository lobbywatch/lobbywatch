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
//         GetApplication()->SetCanUserChangeOwnPassword(
//             !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
//         GetApplication()->SetMainPage($Page);
//         before_render($Page);
//         GetApplication()->Run();
        if (!GetApplication()->IsCurrentUserLoggedIn()) {
          ShowErrorPage('Not logged in.<br><br>Please <a href="login.php">log in</a>.');
          exit(1);
        }
//         print "Say hello";
        $id = GetApplication()->GetGETValue('pk0');

        $con_factory = new MyPDOConnectionFactory();
        $options = GetConnectionOptions();
        $eng_con = $con_factory->CreateConnection($options);
        $eng_con->Connect();
        $con = $eng_con->GetConnectionHandle();
        df($eng_con->Connected(), 'connected');
        df($con, 'con');
        $cmd = $con_factory->CreateEngCommandImp();
        $sql = convert_utf8("SELECT parlamentarier.id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.email,
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
) email_text_html,
CONCAT(
  CASE parlamentarier.geschlecht
    WHEN 'M' THEN CONCAT('Sehr geehrter Herr ', parlamentarier.nachname, '\r\n')
    WHEN 'F' THEN CONCAT('Sehr geehrte Frau ', parlamentarier.nachname, '\r\n')
    ELSE CONCAT('Sehr geehrte(r) Herr/Frau ', parlamentarier.nachname, '\r\n')
  END,
  '\r\n[Ersetze Text mit HTML-Vorlage]\r\n',
  'Ihre Interessenbindungen:\r\n',
  GROUP_CONCAT(DISTINCT
    CONCAT('* ', organisation.anzeige_name, IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', interessenbindung.art, ', ', interessenbindung.beschreibung, '\r\n')
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
  ),
  '\r\nIhre Gäste:\r\n',
  GROUP_CONCAT(DISTINCT
    CONCAT('* ', zutrittsberechtigung.name, ', ', zutrittsberechtigung.funktion, '\r\n')
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
  ),
  '\r\nMit freundlichen Grüssen,\r\n'
) email_text_for_url
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
AND parlamentarier.id=" .
$cmd->GetFieldValueAsSQL(new FieldInfo('table', 'fieldname', ftNumber, 'alias'), $id) .
" GROUP BY parlamentarier.id;");
//         df($sql);
        $result = array();
        $eng_con->ExecQueryToArray($sql, $result);
         df($eng_con->LastError(), 'last error');
        $eng_con->Disconnect();
        df($result, 'result');
        $preview = $result[0]['email_text_html'];

//         $q = $con->query($sql);
//         df($eng_con->LastError(), 'last error');
//         df($q, 'q');
//         $result2 = $q->fetchAll();
//         df($result2, 'result2');

        ShowPreviewPage('<h3>Preview</h3>' . $preview);
    }
    catch(Exception $e)
    {
        ShowErrorPage($e->getMessage());
    }
