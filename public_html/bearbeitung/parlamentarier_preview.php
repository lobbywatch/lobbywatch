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

function GetPageList() {
    $currentPageCaption = 'Preview';
    $result = new PageList(null);
    if (GetCurrentUserGrantForDataSource('organisation')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="entity important-entity">Organisation</span>'), 'organisation.php', ('Organisation'), $currentPageCaption == ('<span class="entity important-entity">Organisation</span>')));
    if (GetCurrentUserGrantForDataSource('parlamentarier')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="entity important-entity">Parlamentarier</span>'), 'parlamentarier.php', ('Parlamentarier'), $currentPageCaption == ('<span class="entity important-entity">Parlamentarier</span>')));
    if (GetCurrentUserGrantForDataSource('zutrittsberechtigung')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="entity">Zutrittsberechtigter</span>'), 'zutrittsberechtigung.php', ('Zutrittsberechtigter'), $currentPageCaption == ('<span class="entity">Zutrittsberechtigter</span>')));
    if (GetCurrentUserGrantForDataSource('interessenbindung')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="relation">Interessenbindung</span>'), 'interessenbindung.php', ('Interessenbindung'), $currentPageCaption == ('<span class="relation">Interessenbindung</span>')));
    if (GetCurrentUserGrantForDataSource('mandat')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="relation">Mandat</span>'), 'mandat.php', ('Mandat'), $currentPageCaption == ('<span class="relation">Mandat</span>')));
    if (GetCurrentUserGrantForDataSource('in_kommission')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="relation">In Kommission</span>'), 'in_kommission.php', ('In Kommission'), $currentPageCaption == ('<span class="relation">In Kommission</span>')));
    if (GetCurrentUserGrantForDataSource('organisation_beziehung')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="relation">Organisation Beziehung</span>'), 'organisation_beziehung.php', ('Organisation Beziehung'), $currentPageCaption == ('<span class="relation">Organisation Beziehung</span>')));
    if (GetCurrentUserGrantForDataSource('branche')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="entity">Branche</span>'), 'branche.php', ('Branche'), $currentPageCaption == ('<span class="entity">Branche</span>')));
    if (GetCurrentUserGrantForDataSource('interessengruppe')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="entity">Lobbygruppe</span>'), 'interessengruppe.php', ('Lobbygruppe'), $currentPageCaption == ('<span class="entity">Lobbygruppe</span>')));
    if (GetCurrentUserGrantForDataSource('kommission')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="entity">Kommission</span>'), 'kommission.php', ('Kommission'), $currentPageCaption == ('<span class="entity">Kommission</span>')));
    if (GetCurrentUserGrantForDataSource('partei')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="entity">Partei</span>'), 'partei.php', ('Partei'), $currentPageCaption == ('<span class="entity">Partei</span>')));
    if (GetCurrentUserGrantForDataSource('fraktion')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="entity">Fraktion</span>'), 'fraktion.php', ('Fraktion'), $currentPageCaption == ('<span class="entity">Fraktion</span>')));
    if (GetCurrentUserGrantForDataSource('kanton')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="entity">Kanton</span>'), 'kanton.php', ('Kanton'), $currentPageCaption == ('<span class="entity">Kanton</span>')));
    if (GetCurrentUserGrantForDataSource('settings')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="settings">Settings</span>'), 'settings.php', ('Settings'), $currentPageCaption == ('<span class="settings">Settings</span>')));
    if (GetCurrentUserGrantForDataSource('settings_category')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="settings">Settings Category</span>'), 'settings_category.php', ('Settings Category'), $currentPageCaption == ('<span class="settings">Settings Category</span>')));
    if (GetCurrentUserGrantForDataSource('user')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="settings">User</span>'), 'user.php', ('User'), $currentPageCaption == ('<span class="settings">User</span>')));
    if (GetCurrentUserGrantForDataSource('q_unvollstaendige_parlamentarier')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="view">Unvollständige Parlamentarier</span>'), 'q_unvollstaendige_parlamentarier.php', ('Unvollständige Parlamentarier'), $currentPageCaption == ('<span class="view">Unvollständige Parlamentarier</span>')));
    if (GetCurrentUserGrantForDataSource('q_unvollstaendige_zutrittsberechtigte')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="view">Unvollständige Zutrittsberechtigte</span>'), 'q_unvollstaendige_zutrittsberechtigte.php', ('Unvollständige Zutrittsberechtigte'), $currentPageCaption == ('<span class="view">Unvollständige Zutrittsberechtigte</span>')));
    if (GetCurrentUserGrantForDataSource('q_unvollstaendige_organisationen')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="view">Unvollständige Organisationen</span>'), 'q_unvollstaendige_organisationen.php', ('Unvollständige Organisationen'), $currentPageCaption == ('<span class="view">Unvollständige Organisationen</span>')));
    if (GetCurrentUserGrantForDataSource('q_last_updated_tables')->HasViewGrant())
        $result->AddPage(new PageLink(('<span class="view">Tabellenstand</span>'), 'tabellenstand.php', ('Tabellenstand'), $currentPageCaption == ('<span class="view">Tabellenstand</span>')));

    if ( HasAdminPage() && GetApplication()->HasAdminGrantForCurrentUser() )
      $result->AddPage(new PageLink(('AdminPage'), 'phpgen_admin.php', ('AdminPage'), false, true));

    add_more_navigation_links($result); // Afterburned
    return $result;
}

function fillZutrittsberechtigterEmail($i) {
  global $zbList;
  global $emailEndZb;
  global $mailtoZb;
  global $emailIntroZb;
  global $rowCellStylesZb;
  global $rowData;
  global $mailtoZb;

  if (isset($zbList[$i])) {
    $res = array(
        'Id'  => $zbList[$i]['id'],
        'Title' => 'Vorschau: ' . $zbList[$i]["zutrittsberechtigung_name"],
        'Preview' => '<table style="margin-top: 1em; margin-bottom: 1em;">
                    <tr><td style="padding: 16px; '. $rowCellStylesZb[$i]['id'] . '" title="Status des Arbeitsablaufes dieses Zutrittsberechtigten">Arbeitsablauf</td><td style="padding: 16px; '. $rowCellStylesZb[$i]['nachname'] . '" title="Status der Vollständigkeit der Felder dieses Zutrittsberechtigten">Vollständigkeit</td></tr></table>' .
        '<p>Zutrittsberechtigung von '. $rowData["parlamentarier_name2"] . '<br><b>Funktion</b>: ' . $zbList[$i]['funktion'] . '<br><b>Beruf</b>: ' . $zbList[$i]['beruf'] . '</p>' . '<h4>Mandate</h4><ul>' . $zbList[$i]['mandate'] . '</ul>',
        'EmailTitle' => 'Autorisierungs-E-Mail: ' . '<a href="' . $mailtoZb[$i]. '" target="_blank">' . $zbList[$i]["zutrittsberechtigung_name"] . '</a>',
        'EmailText' => '<p>' . $zbList[$i]['anrede'] . '</p>' .  $emailIntroZb[$i] . '<p>' . (isset($zbList[$i]['funktion']) ? '<br><b>Funktion</b>: ' . $zbList[$i]['funktion'] . '' : '') . (isset($zbList[$i]['beruf']) ? '<br><b>Beruf</b>: ' . $zbList[$i]['beruf'] . '' : ''). '</p><p><b>Ihre Mandate</b>:</p><ul>' . $zbList[$i]['mandate'] . '</ul>' .
        $emailEndZb[$i],
        // '<p><b>Mandate</b> Ihrer Gäste:<p>' . gaesteMitMandaten($con, $id, true)
        'MailTo' => $mailtoZb[$i],
        'ParlamentarierName' => $rowData["parlamentarier_name"]
    );
  } else {
    $res = array();
  }
  return $res;
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
    try {
      $eng_con->Connect();
      $con = $eng_con->GetConnectionHandle();
//         df($eng_con->Connected(), 'connected');
//         df($con, 'con');
      $cmd = $con_factory->CreateEngCommandImp();

      set_db_session_parameters($con);

      $sql = "SELECT parlamentarier.id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.name as parlamentarier_name2, parlamentarier.email, parlamentarier.geschlecht, parlamentarier.beruf, parlamentarier.eingabe_abgeschlossen_datum, parlamentarier.kontrolliert_datum, parlamentarier.freigabe_datum, parlamentarier.autorisierung_verschickt_datum, parlamentarier.autorisiert_datum, parlamentarier.kontrolliert_visa, parlamentarier.eingabe_abgeschlossen_visa, parlamentarier.im_rat_bis, parlamentarier.sitzplatz, parlamentarier.geburtstag, parlamentarier.im_rat_bis, parlamentarier.kleinbild, parlamentarier.parlament_biografie_id,
GROUP_CONCAT(DISTINCT
    CONCAT('<li>',
    IF(interessenbindung.bis IS NOT NULL AND interessenbindung.bis < NOW(), '<s>', ''),
    organisation.anzeige_name,
    IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '<span class=\"preview-missing-data\">, Rechtsform fehlt</span>', CONCAT(', ', organisation.rechtsform)),
    IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ',
    CONCAT(UCASE(LEFT(interessenbindung.art, 1)), SUBSTRING(interessenbindung.art, 2)),
    IF(interessenbindung.funktion_im_gremium IS NULL OR TRIM(interessenbindung.funktion_im_gremium) = '', '', CONCAT(', ',CONCAT(UCASE(LEFT(interessenbindung.funktion_im_gremium, 1)), SUBSTRING(interessenbindung.funktion_im_gremium, 2)))),
    IF(interessenbindung.beschreibung IS NULL OR TRIM(interessenbindung.beschreibung) = '', '', CONCAT('<small class=\"desc\">, &quot;', interessenbindung.beschreibung, '&quot;</small>')),
    IF(interessenbindung.bis IS NOT NULL AND interessenbindung.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(interessenbindung.bis, '%Y'), '</s>'), '')
    )
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
) interessenbindungen,
GROUP_CONCAT(DISTINCT
    IF(interessenbindung.bis IS NULL OR interessenbindung.bis > NOW(), CONCAT('<li>', organisation.anzeige_name,
    IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)),
    IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', CONCAT(UCASE(LEFT(interessenbindung.art, 1)), SUBSTRING(interessenbindung.art, 2)),
    IF(interessenbindung.funktion_im_gremium IS NULL OR TRIM(interessenbindung.funktion_im_gremium) = '', '', CONCAT(', ',CONCAT(UCASE(LEFT(interessenbindung.funktion_im_gremium, 1)), SUBSTRING(interessenbindung.funktion_im_gremium, 2)))),
    IF(TRUE OR interessenbindung.beschreibung IS NULL OR TRIM(interessenbindung.beschreibung) = '', '', CONCAT(', ',interessenbindung.beschreibung))
    ), '')
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
) interessenbindungen_for_email,
GROUP_CONCAT(DISTINCT
    CONCAT('<li>', IF(zutrittsberechtigung.bis IS NOT NULL AND zutrittsberechtigung.bis < NOW(), '<s>', '<!-- [VALID_Zutrittsberechtigung] -->'),
    zutrittsberechtigung.name,
    IF(zutrittsberechtigung.funktion IS NULL OR TRIM(zutrittsberechtigung.funktion) = '', ', <small><em>Funktion fehlt</em></small>', CONCAT(', ', zutrittsberechtigung.funktion)),
    IF(zutrittsberechtigung.beruf IS NULL OR TRIM(zutrittsberechtigung.beruf) = '', ', <small><em>Beruf fehlt</em></small>', CONCAT(', ', zutrittsberechtigung.beruf)),
    IF(zutrittsberechtigung.bis IS NOT NULL AND zutrittsberechtigung.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(zutrittsberechtigung.bis, '%Y'), '</s>'), '')
    )
  ORDER BY zutrittsberechtigung.name
  SEPARATOR ' '
) zutrittsberechtigungen,
GROUP_CONCAT(DISTINCT
    IF(zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW(), CONCAT('<li>',
    zutrittsberechtigung.name, ', ',
    zutrittsberechtigung.funktion,
    IF(zutrittsberechtigung.beruf IS NULL OR TRIM(zutrittsberechtigung.beruf) = '', '', CONCAT(', ', zutrittsberechtigung.beruf))
    ), '')
  ORDER BY zutrittsberechtigung.name
  SEPARATOR ' '
) zutrittsberechtigungen_for_email,
CASE parlamentarier.geschlecht
  WHEN 'M' THEN CONCAT('Sehr geehrter Herr ', parlamentarier.nachname)
  WHEN 'F' THEN CONCAT('<p>Sehr geehrte Frau ', parlamentarier.nachname)
  ELSE CONCAT('Sehr geehrte(r) Herr/Frau ', parlamentarier.nachname)
END anrede,
GROUP_CONCAT(DISTINCT
    CONCAT('<li>',
    IF(in_kommission.bis IS NOT NULL AND in_kommission.bis < NOW(), '<s>', ''),
    in_kommission.name, ' (', in_kommission.abkuerzung, ') ',
    ', ', CONCAT(UCASE(LEFT(in_kommission.funktion, 1)), SUBSTRING(in_kommission.funktion, 2)),
    IF(in_kommission.bis IS NOT NULL AND in_kommission.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(in_kommission.bis, '%Y'), '</s>'), '')
    )
    ORDER BY in_kommission.abkuerzung
    SEPARATOR ' '
) kommissionen
FROM v_parlamentarier parlamentarier
LEFT JOIN v_interessenbindung interessenbindung
  ON interessenbindung.parlamentarier_id = parlamentarier.id -- AND interessenbindung.bis IS NULL
LEFT JOIN v_organisation organisation
  ON interessenbindung.organisation_id = organisation.id
LEFT JOIN v_zutrittsberechtigung zutrittsberechtigung
  ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id -- AND zutrittsberechtigung.bis IS NULL
LEFT JOIN v_in_kommission_liste in_kommission
  ON in_kommission.parlamentarier_id = parlamentarier.id -- AND interessenbindung.bis IS NULL
WHERE
  parlamentarier.id=:id
GROUP BY parlamentarier.id;";

//         df($sql);
        $result = array();
//         $eng_con->ExecQueryToArray($sql, $result);
//          df($eng_con->LastError(), 'last error');
//         $eng_con->Disconnect();
//         df($result, 'result');
//         $preview = $rowData['email_text_html'];

//         $q = $con->query($sql);
//         $result2 = $q->fetchAll();
//         df($eng_con->LastError(), 'last error');
//         df($q, 'q');
//         df($result2, 'result2');

      $sth = $con->prepare($sql);
      $sth->execute(array(':id' => $id));
      $result = $sth->fetchAll();

      if (!$result) {
        df($eng_con->LastError());
        throw new Exception('ID not found');
      }
    } finally {
      $eng_con->Disconnect();
    }

    $rowData = $result[0];

    $emailSubjectParlam = getSettingValue('parlamentarierAutorisierungEmailSubject', false, 'Interessenbindungen');
    $emailIntroParlam = getSettingValue('parlamentarierAutorisierungEmailEinleitung', false, '<p>[Einleitung]</p>');
    $emailEndParlam = getSettingValue('parlamentarierAutorisierungEmailSchluss', false, '<p>Freundliche Grüsse<br>%name%</p>');
    $emailEndParlam = StringUtils::ReplaceVariableInTemplate($emailEndParlam, 'name', getFullUsername(Application::Instance()->GetCurrentUser()));

    //df($rowData);
    $rowCellStylesParlam = '';
    $rowStyles = '';
    customDrawRow('parlamentarier', $rowData, $rowCellStylesParlam, $rowStyles);

    $zbRet = zutrittsberechtigteForParlamentarier($con, $id, true);
    $zbList = $zbRet['zutrittsberechtigte'];
//         df($zbRet, '$zbRet');

    $mailtoParlam = 'mailto:' . urlencode($rowData["email"]) . '?subject=' . urlencode($emailSubjectParlam) . '&body=' . urlencode('[Kopiere von Vorlage]') . '&bcc=info@lobbywatch.ch';

    $i = 0;
    foreach ($zbList as $zb) {

      $emailSubjectZb[$i] = getSettingValue('zutrittsberechtigterAutorisierungEmailSubject', false, 'Zugangsberechtigung ins Parlament');
      $emailIntroZb[$i] = StringUtils::ReplaceVariableInTemplate(getSettingValue('zutrittsberechtigterAutorisierungEmailEinleitung', false, '<p>[Einleitung]</p><p>Zutrittsberechtigung erhalten von %parlamentarierName%.</p>'), 'parlamentarierName', $rowData["parlamentarier_name2"]);
      $emailEndZb[$i] = StringUtils::ReplaceVariableInTemplate(getSettingValue('zutrittsberechtigterAutorisierungEmailSchluss', false, '<p>Freundliche Grüsse<br>%name%</p>'), 'name', getFullUsername(Application::Instance()->GetCurrentUser()));

      $rowCellStylesZb[$i] = '';
      $rowStyles = '';
      customDrawRow('zutrittsberechtigung', $rowData, $rowCellStylesZb[$i], $rowStyles);

      $mailtoZb[$i] = 'mailto:' . urlencode($zb["email"]) . '?subject=' . urlencode($emailSubjectZb[$i]) . '&body=' . urlencode('[Kopiere von Vorlage]') . '&bcc=info@lobbywatch.ch';

      $i++;
    }

//         ShowPreviewPage('<h4>Preview</h4><h3>' .$rowData["parlamentarier_name"] . '</h3>' .
//         '<h4>Interessenbindungen</h4><ul>' . $rowData['interessenbindungen'] . '</ul>' .
//         '<h4>Gäste</h4><ul>' . $rowData['zutrittsberechtigungen'] . '</ul>' .
//         '<h4>Mandate</h4><ul>' . $rowData['mandate'] . '</ul>');
    DisplayTemplateSimple('custom_templates/parlamentarier_preview_page.tpl',
      array(
      ),
      array(
        'App' => array(
          'ContentEncoding' => 'UTF-8',
          'PageCaption' => 'Vorschau: ' . $rowData["parlamentarier_name"],
          'Header' => GetPagesHeader(),
          'Direction' => 'ltr',
      ),
        'Footer' => GetPagesFooter(),
        'Parlamentarier' => array(
          'Id'  => $id,
          'Title' => 'Vorschau: ' . $rowData["parlamentarier_name"],
          'Preview' => '<table style="margin-top: 1em; margin-bottom: 1em;">
              <tr><td style="padding: 16px; '. $rowCellStylesParlam['id'] . '" title="Status des Arbeitsablaufes dieses Parlamenteriers">Arbeitsablauf</td><td style="padding: 16px; '. $rowCellStylesParlam['nachname'] . '" title="Status der Vollständigkeit der Felder dieses Parlamenteriers">Vollständigkeit</td></tr></table>' .
              '<p><b>Beruf</b>: ' . $rowData['beruf'] . '</p>' .
            '<h4>Kommissionen</h4><ul>' . $rowData['kommissionen'] . '</ul>' .
            '<h4>Interessenbindungen</h4><ul>' . $rowData['interessenbindungen'] . '</ul>' .
            '<h4>Gäste' . (substr_count($rowData['zutrittsberechtigungen'], '[VALID_Zutrittsberechtigung]') > 2 ? ' <img src="img/icons/warning.gif" alt="Warnung">': '') . '</h4>' . ($rowData['zutrittsberechtigungen'] ? '<ul>' . $rowData['zutrittsberechtigungen'] . '</ul>': '<p>keine</p>') .
            '<h4>Mandate der Gäste</h4>' . $zbRet['gaesteMitMandaten'],
          'EmailTitle' => 'Autorisierungs-E-Mail: ' . '<a href="' . $mailtoParlam. '" target="_blank">' . $rowData["parlamentarier_name"] . '</a>',
          'EmailText' => '<p>' . $rowData['anrede'] . '</p>' . $emailIntroParlam . (isset($rowData['beruf']) ? '<p><b>Beruf</b>: ' . $rowData['beruf'] . '</p>' : '') . '<p><b>Ihre Interessenbindungen</b>:</p><ul>' . $rowData['interessenbindungen_for_email'] . '</ul>' .
            '<p><b>Ihre Gäste</b>:</p>' . ($rowData['zutrittsberechtigungen_for_email'] ? '<ul>' . $rowData['zutrittsberechtigungen_for_email'] . '</ul>': '<p>keine</p>') .
            $emailEndParlam,
            // '<p><b>Mandate</b> Ihrer Gäste:<p>' . gaesteMitMandaten($con, $id, true)
           'MailTo' => $mailtoParlam
        ),
        'Zutrittsberechtigter0' => fillZutrittsberechtigterEmail(0),
        'Zutrittsberechtigter1' => fillZutrittsberechtigterEmail(1),
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
