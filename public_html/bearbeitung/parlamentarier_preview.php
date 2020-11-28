<?php

/**
 * This file was written quick and dirty. ;-)
 */

//phpinfo();

include_once dirname(__FILE__) . '/../custom/custom_page.php';


// Main

// lobbywatch_set_language('fr');

// df(lt('test'), 'lt-test');

// df(lobbywatch_lang_field('organisation.name'));
// df(lobbywatch_lang_field('organisation.name_de'));

// lobbywatch_set_language('de');
// df(lobbywatch_lang_field('organisation.name'));
// df(lobbywatch_lang_field('organisation.name_de'));

try {
    $con = getDBConnectionHandle();
    set_db_session_parameters($con);

    $p_pk0 = 'pk0';
    $p_pk = 'pk';
    $z_pk = 'zpk';
    if (GetApplication()->IsGETValueSet($p_pk0)){
      if (!($id = GetApplication()->GetGETValue($p_pk0))) {
        throw new Exception('ID missing');
      }
    } else if (GetApplication()->IsGETValueSet($p_pk)){
      if (!($id = GetApplication()->GetGETValue($p_pk))) {
        throw new Exception('ID missing');
      }
    } else if (GetApplication()->IsGETValueSet($z_pk)){
      if (!($z_id = GetApplication()->GetGETValue($z_pk))) {
        throw new Exception('ID missing');
      }
      $id = get_parlamentarier_id_for_zutrittsberechtige_person($con, $z_id);
    } else {
      throw new Exception('ID parameter missing, e.g. ?pk=215');
   }

//     $con_factory = new MyPDOConnectionFactory();
//     $options = GetConnectionOptions();
//     $eng_con = $con_factory->CreateConnection($options);
// //     try {
//       $eng_con->Connect();
//       $con = $eng_con->GetConnectionHandle();
// //         df($eng_con->Connected(), 'connected');
// //         df($con, 'con');
//       $cmd = $con_factory->CreateEngCommandImp();

    $lang = $parlamentarier_lang = get_parlamentarier_lang($con, $id);
    lobbywatch_set_language($lang);
    $lang_suffix = get_lang_suffix($lang);
    $jahr = date("Y");

    $rowData = get_parlamentarier($con, $id, $jahr);
    $rowData_no_pg_members = get_parlamentarier($con, $id, $jahr, false);
    $lastLogRowParlamentInteressenbindungen = get_parlamentarier_log_last_changed_parlament_interessenbindungen($con, $id);
    $lastLogRowParlamentBeruf = get_parlamentarier_log_last_changed_parlament_beruf_json($con, $id);

    $reAuthorization = isset($rowData['autorisierung_verschickt_datum']);

    $emailSubjectParlam = getSettingValue("parlamentarierAutorisierungEmailSubject$lang_suffix", false, 'Interessenbindungen');
    $emailIntroParlam = getSettingValue('parlamentarier' . ($reAuthorization ? 'Re' : '') . "AutorisierungEmailEinleitung$lang_suffix", false, '[Einleitung]<br><br>');
    $emailEndParlam = getSettingValue('parlamentarier' . ($reAuthorization ? 'Re' : '') . "AutorisierungEmailSchluss$lang_suffix", false, '<br><br>Freundliche Grüsse<br>%name%');
    $emailEndParlam = StringUtils::ReplaceVariableInTemplate($emailEndParlam, 'name', getFullUsername(Application::Instance()->GetCurrentUser()));

    //df($rowData);
    $rowCellStyles = [];
    $rowStyles = '';
    $rowClasses = '';
    $cellClasses = [];
    customDrawRow('parlamentarier', $rowData, $rowCellStyles, $rowStyles, $rowClasses, $cellClasses);

    $old_ib_html = normalizeParlamentInteressenbindungen($lastLogRowParlamentInteressenbindungen['parlament_interessenbindungen']);
    $new_ib_html = normalizeParlamentInteressenbindungen($rowData['parlament_interessenbindungen']);
    $ib_diff_html = htmlDiffStyled($old_ib_html, $new_ib_html, false);

    $old_beruf_html = $lastLogRowParlamentBeruf['parlament_beruf_json'] ?? '';
    $new_beruf_html = $rowData['parlament_beruf_json'] ?? '';
    $beruf_diff_html = htmlDiffStyled($old_beruf_html, $new_beruf_html, false);

    $zbRetDetail = zutrittsberechtigteForParlamentarier($con, $id, false);
    $zbRet = zutrittsberechtigteForParlamentarier($con, $id, true);
    $zbList = $zbRet['zutrittsberechtigte'];
//         df($zbRet, '$zbRet');

    $mailtoParlam = 'mailto:' . rawurlencode($rowData["email"]) . '?subject=' . rawurlencode($emailSubjectParlam) . '&body=' . rawurlencode('[Kopiere von Vorlage]') . '&bcc=redaktion@lobbywatch.ch';

    $i = 0;
    foreach ($zbList as $zb) {

      $lang = $zb['arbeitssprache'];
      lobbywatch_set_language($lang);
      $lang_suffix = get_lang_suffix($lang);

      $emailSubjectZb[$i] = getSettingValue("zutrittsberechtigterAutorisierungEmailSubject$lang_suffix", false, 'Zugangsberechtigung ins Parlament');
      $emailIntroZb[$i] = StringUtils::ReplaceVariableInTemplate(getSettingValue("zutrittsberechtigterAutorisierungEmailEinleitung$lang_suffix", false, '[Einleitung]<br><br>Zutrittsberechtigung erhalten von %parlamentarierName%.'), 'parlamentarierName', $rowData["parlamentarier_name2"]);
      $emailEndZb[$i] = StringUtils::ReplaceVariableInTemplate(getSettingValue("zutrittsberechtigterAutorisierungEmailSchluss$lang_suffix", false, '<br><br>Freundliche Grüsse<br>%name%'), 'name', getFullUsername(Application::Instance()->GetCurrentUser()));

      $rowCellStylesZb[$i] = [];
      $rowStyles = '';
      customDrawRow('zutrittsberechtigung', $rowData, $rowCellStylesZb[$i], $rowStyles);

      $mailtoZb[$i] = 'mailto:' . rawurlencode($zb["email"]) . '?subject=' . rawurlencode($emailSubjectZb[$i]) . '&body=' . rawurlencode('[Kopiere von Vorlage]') . '&bcc=redaktion@lobbywatch.ch';

      $i++;
    }

    $lang = $parlamentarier_lang;
    lobbywatch_set_language($lang);
    $lang_suffix = get_lang_suffix($lang);

    // $organisationsbeziehungen = organisationsbeziehungen($con, $rowData["organisationen_from_interessenbindungen"]); RK Do not show Organisationsbeziehungen in Autorisierungs E-Mail, request Otto

//         ShowPreviewPage('<h4>Preview</h4><h3>' .$rowData["parlamentarier_name"] . '</h3>' .
//         '<h4>Interessenbindungen</h4><ul>' . $rowData['F'] . '</ul>' .
//         '<h4>Gäste</h4><ul>' . $rowData['zutrittsberechtigungen'] . '</ul>' .
//         '<h4>Mandate</h4><ul>' . $rowData['mandate'] . '</ul>');

    $state = '<table style="margin-top: 1em; margin-bottom: 1em;">
              <tr><td style="padding: 16px; '. $rowCellStyles['id'] . '" title="Status des Arbeitsablaufes dieses Parlamenteriers">Arbeitsablauf</td><td style="padding: 16px; '. $rowCellStyles['nachname'] . '" title="Status der Vollständigkeit der Felder dieses Parlamenteriers">Vollständigkeit</td></tr></table>';

//     $trans = lt('Ihre Interessenbindungen:');
//     df($trans);

    $viewData = new CommonPageViewData();
    $viewData->setTitle($rowData["parlamentarier_name"] . ' - Vorschau');
    DisplayTemplateSimple('custom_templates/parlamentarier_preview_page.tpl',
      array(
      ),
      array(
        'common' => $viewData,
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
          'parlamentarier_name' => $rowData["parlamentarier_name"],
          'State' =>  $state,
          'Preview' => '<p><b>Beruf</b>: ' . $rowData['beruf'] . '</p>' .
            '<h4>Kommissionen</h4><ul>' . $rowData['kommissionen'] . '</ul>' .
            '<h4>Interessenbindungen</h4><ul>' . $rowData['interessenbindungen'] . '</ul>' .
            '<h4>Gäste' . (substr_count($rowData['zutrittsberechtigungen'], '[VALID_Zutrittsberechtigung]') > 2 ? ' <img src="img/icons/warning.gif" alt="Warnung">': '') . '</h4>' . ($rowData['zutrittsberechtigungen'] ? '<ul>' . $rowData['zutrittsberechtigungen'] . '</ul>': '<p>keine</p>') .
            '<h4>Mandate der Gäste</h4>' . $zbRetDetail['gaesteMitMandaten'],
          'EmailTitle' => ($reAuthorization ? 'Re-' : '') . 'Autorisierungs-E-Mail: ' . '<a href="' . $mailtoParlam. '" target="_blank">' . $rowData["parlamentarier_name"] . '</a>',
          'EmailText' => '<div>' . $rowData['anrede'] . '' . $emailIntroParlam . (isset($rowData['beruf']) ? '<b>' . lt('Beruf:') . '</b> ' . translate_record_field($rowData, 'beruf', false, true) . '' : '') . '<br><br><b>' . lt('Ihre Interessenbindungen:') .'</b><ul>' . $rowData_no_pg_members['interessenbindungen_for_email'] . '</ul>' .
            // $organisationsbeziehungen .  RK Do not show Organisationsbeziehungen in Autorisierungs E-Mail, request Otto
            '<b>' . lt('Ihre Gäste:') . '</b></p>' . ($rowData['zutrittsberechtigungen_for_email'] ? '<ul>' . $rowData['zutrittsberechtigungen_for_email'] . '</ul>': '<br>' . lt('keine') . '<br>') .
            '' . $emailEndParlam . '</div>',
            // '<p><b>Mandate</b> Ihrer Gäste:<p>' . gaesteMitMandaten($con, $id, true)
           'MailTo' => $mailtoParlam,
          'aemter' => $rowData['aemter'],
          'weitere_aemter' => $rowData['weitere_aemter'],
          'parlament_beruf' => $beruf_diff_html,
          'parlament_interessenbindungen' => $ib_diff_html /*. "<br><p>_____________________________<br>Ohne Delta:</p>" . $new_ib_html*/,
          'parlament_interessenbindungen_updated' => $rowData['parlament_interessenbindungen_updated_formatted'],
          'parlament_biografie_id' => $rowData['parlament_biografie_id'],
          'import_date_wsparlamentch' => $import_date_wsparlamentch,
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
        ),
        'HideSideBarByDefault' => true,
        'Variables' => '',
      )
    );
} catch(Exception $e) {
    ShowErrorPage($e);
}
