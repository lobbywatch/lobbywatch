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

function fillZutrittsberechtigterEmail($i) {
  global $zbList;
  global $emailEndZb;
  global $mailtoZb;
  global $emailIntroZb;
  global $rowCellStylesZb;
  global $rowData;

  if (isset($zbList[$i])) {
    $lang = $zbList[$i]['arbeitssprache'];
    $oldlang = lobbywatch_set_language($lang);
    $lang_suffix = get_lang_suffix($lang);

    $state = '<table style="margin-top: 1em; margin-bottom: 1em;">
                    <tr><td style="padding: 16px; '. $rowCellStylesZb[$i]['id'] . '" title="Status des Arbeitsablaufes dieses Zutrittsberechtigten">Arbeitsablauf</td><td style="padding: 16px; ' . (!empty($rowCellStylesZb[$i]['nachname']) ? $rowCellStylesZb[$i]['nachname'] : '') . '" title="Status der Vollständigkeit der Felder dieses Zutrittsberechtigten">Vollständigkeit</td></tr></table>';
    $res = array(
        'Id'  => $zbList[$i]['id'],
        'Title' => 'Vorschau: ' . $zbList[$i]["zutrittsberechtigung_name"],
        'State' =>  $state,
        'Preview' =>  '<p>Zutrittsberechtigung von '. $rowData["parlamentarier_name2"] . '<br><b>Funktion</b>: ' . $zbList[$i]['funktion'] . '<br><b>Beruf</b>: ' . $zbList[$i]['beruf'] . '</p>' . '<h4>Mandate</h4><ul>' . $zbList[$i]['mandate'] . '</ul>',
        'EmailTitle' => 'Autorisierungs-E-Mail: ' . '<a href="' . $mailtoZb[$i]. '" target="_blank">' . $zbList[$i]["zutrittsberechtigung_name"] . '</a>',
        'EmailText' => '<div>' . $zbList[$i]['anrede'] . '' .  $emailIntroZb[$i] . '' . (isset($zbList[$i]['funktion']) ? '<br><b>' . lt('Deklarierte Funktion:') . '</b> ' . $zbList[$i]['funktion'] . '' : '') . (isset($zbList[$i]['beruf']) ? '<br><b>' . lt('Beruf:') . '</b> ' . $zbList[$i]['beruf'] . '' : ''). '<br><br><b>' . lt('Ihre Tätigkeiten:') . '</b><br>' . ($zbList[$i]['mandate'] ? '<ul>' . $zbList[$i]['mandate'] . '</ul>' : lt('keine') . '<br>') . $zbList[$i]['organisationsbeziehungen'] .
        '' . $emailEndZb[$i] . '</div>',
        // '<p><b>Mandate</b> Ihrer Gäste:<p>' . gaesteMitMandaten($con, $id, true)
        'MailTo' => $mailtoZb[$i],
        'ParlamentarierName' => $rowData["parlamentarier_name"]
    );

    // Reset language
    lobbywatch_set_language($oldlang);

  } else {
    $res = array();
  }
  return $res;
}

function organisationsbeziehungen($con, $organisationen_id_comma_list, $for_email = false, $check_unpublished = true) {
//   df($organisationen_id_comma_list);
  $admin = false;
  $num_arbeitet_fuer = $admin ? 2 : 0;
  $num_tochtergesellschaft_von = $admin ? 3 : 0;

  $organistionen_ids = explode(',', $organisationen_id_comma_list);

  $found = false;


  $inner_markup = '';

  foreach ($organistionen_ids as $organisation_id) {

      $sql = "SELECT organisation.id, organisation.freigabe_datum_unix, organisation.lobbyeinfluss, organisation.anzahl_interessenbindung_hoch, organisation.anzahl_interessenbindung_mittel, organisation.anzahl_interessenbindung_tief, organisation.anzahl_interessenbindung_hoch_nach_wahl, organisation.anzahl_interessenbindung_mittel_nach_wahl, organisation.anzahl_interessenbindung_tief_nach_wahl, organisation.anzahl_mandat_hoch, organisation.anzahl_mandat_mittel, organisation.anzahl_mandat_tief, organisation.rechtsform, " . _lobbywatch_get_rechtsform_translation_SQL('organisation') . " as rechtsform_translated, organisation.ort, organisation.anzeige_name_de, organisation.anzeige_name_fr, "
  ." CONCAT('<li>', " . lobbywatch_lang_field('organisation.name_de') . ",
    IF(FALSE AND (organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = ''), '', CONCAT(', ', ". _lobbywatch_get_rechtsform_translation_SQL("organisation") . ")),
    IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort))
) organisation, "
  // . _lobbywatch_get_workflow_state_SELECT_SQL('organisation') . " "
  . _lobbywatch_organisation_beziehung_SELECT_SQL('arbeitet_fuer', $num_arbeitet_fuer, false) . ", "
  // . _lobbywatch_organisation_beziehung_SELECT_SQL('auftraggeber_fuer', $num_arbeitet_fuer)  . ", "
  // . _lobbywatch_organisation_beziehung_SELECT_SQL('mitglied_von', $num_mitglied_von) . ", "
  // . _lobbywatch_organisation_beziehung_SELECT_SQL('mitglieder', $num_mitglied_von)  . ", "
  . _lobbywatch_organisation_beziehung_SELECT_SQL('tochtergesellschaften', $num_tochtergesellschaft_von, false) . ", "
  . _lobbywatch_organisation_beziehung_SELECT_SQL('muttergesellschaften', $num_tochtergesellschaft_von, false)  // . ", "
  // . _lobbywatch_organisation_beziehung_SELECT_SQL('partner', $num_partner) . ", "
  // . _lobbywatch_organisation_beziehung_SELECT_SQL('beteiligungen_an', $num_beiteiligung_an) . ", "
  // . _lobbywatch_organisation_beziehung_SELECT_SQL('beteiligungen_von', $num_beiteiligung_an) . ", "
  // . _lobbywatch_verbundene_parlamentarier_SELECT_SQL(false) //. ", "
  . "
    FROM v_organisation organisation "
  //     . _lobbywatch_verbundene_parlamentarier_FROM_SQL()
      . _lobbywatch_organisation_beziehung_FROM_SQL('organisation', 'arbeitet fuer', true, $num_arbeitet_fuer, 'arbeitet_fuer', 'auftraggeber_fuer')
  //     . _lobbywatch_organisation_beziehung_FROM_SQL('organisation', 'mitglied von', true, $num_mitglied_von, 'mitglied_von', 'mitglieder')
      . _lobbywatch_organisation_beziehung_FROM_SQL('organisation', 'tochtergesellschaft von', true, $num_tochtergesellschaft_von, 'muttergesellschaften', 'tochtergesellschaften')
  //     . _lobbywatch_organisation_beziehung_FROM_SQL('organisation', 'partner von', false, $num_partner, 'partner', 'partner_reverse')
  //     . _lobbywatch_organisation_beziehung_FROM_SQL('organisation', 'beteiligt an', true, $num_beiteiligung_an, 'beteiligungen_an', 'beteiligungen_von')
    . "
    WHERE organisation.id IN (:ids) "
         . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '') . "
    GROUP BY organisation.id";

//     df($sql, 'sql');
    $result = lobbywatch_forms_db_query($sql, array(':ids' => $organisation_id), array('fetch' => PDO::FETCH_ASSOC))->fetch();
//     df($result, 'result');

    $inner_markup .= print_organisation_beziehung($result, lt('arbeitet für'), 'arbeitet_fuer', $num_arbeitet_fuer);
    $inner_markup .= print_organisation_beziehung($result, lt('Tochtergesellschaften'), 'tochtergesellschaften', $num_tochtergesellschaft_von);
    $inner_markup .= print_organisation_beziehung($result, lt('Muttergesellschaften'), 'muttergesellschaften', $num_tochtergesellschaft_von);
  }
  if ($inner_markup) {
    $markup = '<b>' . lt('Mit diesen Organisationen sind die oben erwähnten Institutionen verbunden:') . '</b>'
      . '<ul>'
      . $inner_markup
      . '</ul>';
  } else {
    $markup = '';
  }
  return $markup;
}

function print_organisation_beziehung($record, $relation, $field_name_base, $transitiv_num) {
  $lang = get_lang();
  $lang_suffix = get_lang_suffix();
  $admin = user_access('access lobbywatch admin');
  $adminBool = $admin ? "1" : "0";

  $markup = '';
  if ($record["${field_name_base}_0"]) {
    $markup .= $record['organisation'] . " <b>$relation</b>"
    . '<ul>'
        . $record["${field_name_base}_0"]
        . '</ul>';
//     if ($admin) {
//       $markup .= "<div class='admin'>";
//       for($i = 1; $i <= $transitiv_num; $i++) {
//         if ($record["${field_name_base}_$i"]) {
//           $markup .= '<h4>'. lt('Transitiv') . ' ' . $i . '</h4>'
//               . '<ul>'
//                   . $record["${field_name_base}_$i"]
//                   . '</ul>';
//           } else {
//             break;
//           }
//       }
//       $markup .= "</div>";
//     }
  }
  return $markup;
}

function zutrittsberechtigteForParlamentarier($con, $parlamentarier_id, $for_email = false) {

  $sql = "SELECT zutrittsberechtigung.id, zutrittsberechtigung.person_id, zutrittsberechtigung.arbeitssprache FROM v_zutrittsberechtigung_simple_compat zutrittsberechtigung
          WHERE
  (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW())
  AND zutrittsberechtigung.parlamentarier_id=:id
GROUP BY zutrittsberechtigung.id;";

  //         df($sql);
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

  //       $sth = $con->prepare($sql);
  //       $sth->execute(array(':id' => $id));
  $zbs = lobbywatch_forms_db_query($sql, array(':id' => $parlamentarier_id));

  $gaeste = array();

  $organisationsbeziehungen = array();

  foreach ($zbs as $zb) {
    $id = $zb->id;
    $lang = $zb->arbeitssprache;
    $oldlang = lobbywatch_set_language($lang);
    $lang_suffix = get_lang_suffix($lang);

    $sql = "SELECT zutrittsberechtigung.id, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.geschlecht, zutrittsberechtigung.funktion, zutrittsberechtigung.beruf, zutrittsberechtigung.email, zutrittsberechtigung.arbeitssprache, zutrittsberechtigung.nachname,
  GROUP_CONCAT(DISTINCT
      CONCAT('<li>', " . (!$for_email ? "IF(mandat.bis IS NOT NULL AND mandat.bis < NOW(), '<s>', ''), " : "") . lobbywatch_lang_field('organisation.name_de') . ",
      IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', " . (!$for_email ? "'<span class=\"preview-missing-data\">, Rechtsform fehlt</span>'" : "''") . ", CONCAT(', ', ". _lobbywatch_get_rechtsform_translation_SQL("organisation") . ")), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ',
      " . _lobbywatch_bindungsart('zutrittsberechtigung', 'mandat', 'organisation') . ",
      " . (!$for_email ? " IF(mandat.beschreibung IS NULL OR TRIM(mandat.beschreibung) = '', '', CONCAT('<small class=\"desc\">, &quot;', mandat.beschreibung, '&quot;</small>'))," : "") . "
      IF(mandat.bis IS NOT NULL AND mandat.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(mandat.bis, '%Y'), '</s>'), ''))
      ORDER BY organisation.anzeige_name
      SEPARATOR ' '
  ) mandate,
  GROUP_CONCAT(DISTINCT
      IF(mandat.bis IS NULL OR mandat.bis > NOW(), CONCAT(organisation.id), '')
      ORDER BY organisation.anzeige_name
      SEPARATOR ','
  ) organisationen_from_mandate,
    CASE zutrittsberechtigung.geschlecht
      WHEN 'M' THEN CONCAT(" . lts('Sehr geehrter Herr') . ",' ', zutrittsberechtigung.nachname)
      WHEN 'F' THEN CONCAT(" . lts('Sehr geehrte Frau') . ",' ', zutrittsberechtigung.nachname)
      ELSE CONCAT(" . lts('Sehr geehrte(r) Herr/Frau') . ",' ', zutrittsberechtigung.nachname)
  END anrede
  FROM v_zutrittsberechtigung_simple_compat zutrittsberechtigung
  LEFT JOIN v_mandat_simple mandat
    ON mandat.person_id = zutrittsberechtigung.id " . ($for_email ? 'AND mandat.bis IS NULL' : '') . "
  LEFT JOIN v_organisation_simple organisation
    ON mandat.organisation_id = organisation.id
  WHERE
    zutrittsberechtigung.id=:id
  GROUP BY zutrittsberechtigung.id;";

    //     df($sql, 'sql');

    $res = array();
    $sth = $con->prepare($sql);
    $sth->execute(array(':id' => $id));
    $gast = $sth->fetchAll();

//     df($gast, 'gast');


    $gast[0]['organisationsbeziehungen'] = organisationsbeziehungen($con, $gast[0]["organisationen_from_mandate"]);

    $gaeste = array_merge($gaeste, $gast);

    $gaesteMitMandaten = '';

    // Reset language
    lobbywatch_set_language($oldlang);
  }

  if (!$gaeste) {
    $gaesteMitMandaten = '<p>' . lt('keine') . '</p>';
    //      throw new Exception('Parlamentarier ID not found');
  } else {
    foreach($gaeste as $gast) {
      $gaesteMitMandaten .= '<h5>' . $gast['zutrittsberechtigung_name'] . '</h5>';
      //$gaesteMitMandaten .= mandateList($con, $gast['id']);
      $gaesteMitMandaten .= "<ul>\n" . $gast['mandate'] . "\n</ul>";
    }
  }

  $res['gaesteMitMandaten'] = $gaesteMitMandaten;
  $res['zutrittsberechtigte'] = $gaeste;

  return $res;
}


// Main

SetUpUserAuthorization(GetApplication());

// lobbywatch_set_language('fr');

// df(lt('test'), 'lt-test');

// df(lobbywatch_lang_field('organisation.name'));
// df(lobbywatch_lang_field('organisation.name_de'));

// lobbywatch_set_language('de');
// df(lobbywatch_lang_field('organisation.name'));
// df(lobbywatch_lang_field('organisation.name_de'));

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

//     $con_factory = new MyPDOConnectionFactory();
//     $options = GetConnectionOptions();
//     $eng_con = $con_factory->CreateConnection($options);
// //     try {
//       $eng_con->Connect();
//       $con = $eng_con->GetConnectionHandle();
// //         df($eng_con->Connected(), 'connected');
// //         df($con, 'con');
//       $cmd = $con_factory->CreateEngCommandImp();

    $con = getDBConnectionHandle();
    set_db_session_parameters($con);

      $sql = "SELECT parlamentarier.arbeitssprache FROM v_parlamentarier_simple parlamentarier
          WHERE
  parlamentarier.id=:id;";

//         df($sql);
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

//       $sth = $con->prepare($sql);
//       $sth->execute(array(':id' => $id));
      $obj = lobbywatch_forms_db_query($sql, array(':id' => $id))->fetch();
      $lang = $parlamentarier_lang = $obj->arbeitssprache;
      lobbywatch_set_language($lang);
      $lang_suffix = get_lang_suffix($lang);

      $result = array();
      $sql = "SELECT parlamentarier.id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.name as parlamentarier_name2, parlamentarier.email, parlamentarier.geschlecht, parlamentarier.beruf, parlamentarier.eingabe_abgeschlossen_datum, parlamentarier.kontrolliert_datum, parlamentarier.freigabe_datum, parlamentarier.autorisierung_verschickt_datum, parlamentarier.autorisiert_datum, parlamentarier.kontrolliert_visa, parlamentarier.eingabe_abgeschlossen_visa, parlamentarier.im_rat_bis, parlamentarier.sitzplatz, parlamentarier.geburtstag, parlamentarier.im_rat_bis, parlamentarier.kleinbild, parlamentarier.parlament_biografie_id, parlamentarier.arbeitssprache,
GROUP_CONCAT(DISTINCT
    CONCAT('<li>',
    IF(interessenbindung.bis IS NOT NULL AND interessenbindung.bis < NOW(), '<s>', ''),
    organisation.anzeige_name,
    IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '<span class=\"preview-missing-data\">, Rechtsform fehlt</span>', CONCAT(', ', ". _lobbywatch_get_rechtsform_translation_SQL("organisation") . ")),
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
    IF(interessenbindung.bis IS NULL OR interessenbindung.bis > NOW(), CONCAT('<li>', " . lobbywatch_lang_field('organisation.name_de') . ",
    IF(FALSE AND (organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = ''), '', CONCAT(', ', ". _lobbywatch_get_rechtsform_translation_SQL("organisation") . ")),
    IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ',
    " . _lobbywatch_bindungsart('parlamentarier', 'interessenbindung', 'organisation') . ",
    IF(TRUE OR interessenbindung.beschreibung IS NULL OR TRIM(interessenbindung.beschreibung) = '', '', CONCAT(', ',interessenbindung.beschreibung))
    ), '')
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
) interessenbindungen_for_email,
GROUP_CONCAT(DISTINCT
    IF(interessenbindung.bis IS NULL OR interessenbindung.bis > NOW(), CONCAT(organisation.id), '')
    ORDER BY organisation.anzeige_name
    SEPARATOR ','
) organisationen_from_interessenbindungen,
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
  WHEN 'M' THEN CONCAT(" . lts('Sehr geehrter Herr') . ",' ', parlamentarier.nachname)
  WHEN 'F' THEN CONCAT(" . lts('Sehr geehrte Frau') . ",' ', parlamentarier.nachname)
  ELSE CONCAT(" . lts('Sehr geehrte(r) Herr/Frau') . ",' ', parlamentarier.nachname)
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
FROM v_parlamentarier_simple parlamentarier
LEFT JOIN v_interessenbindung_simple interessenbindung
  ON interessenbindung.parlamentarier_id = parlamentarier.id -- AND interessenbindung.bis IS NULL
LEFT JOIN v_organisation_simple organisation
  ON interessenbindung.organisation_id = organisation.id
LEFT JOIN v_zutrittsberechtigung_simple_compat zutrittsberechtigung
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

//       $sth = $con->prepare($sql);
//       $sth->execute(array(':id' => $id));
      $options = array(
        'fetch' => PDO::FETCH_BOTH, // for compatibility with existing code
      );
        $result = lobbywatch_forms_db_query($sql, array(':id' => $id), $options)->fetchAll();
//       df($sql, 'sql');
//       $result = $sth->fetchAll();

      if (!$result) {
//         df($eng_con->LastError());
        throw new Exception('ID not found');
      }
//     } finally {
//       // Connection will automatically be closed at the end of the request.
// //       $eng_con->Disconnect();
//     }

    $rowData = $result[0];

    $emailSubjectParlam = getSettingValue("parlamentarierAutorisierungEmailSubject$lang_suffix", false, 'Interessenbindungen');
    $emailIntroParlam = getSettingValue("parlamentarierAutorisierungEmailEinleitung$lang_suffix", false, '[Einleitung]<br><br>');
    $emailEndParlam = getSettingValue("parlamentarierAutorisierungEmailSchluss$lang_suffix", false, '<br><br>Freundliche Grüsse<br>%name%');
    $emailEndParlam = StringUtils::ReplaceVariableInTemplate($emailEndParlam, 'name', getFullUsername(Application::Instance()->GetCurrentUser()));

    //df($rowData);
    $rowCellStylesParlam = '';
    $rowStyles = '';
    customDrawRow('parlamentarier', $rowData, $rowCellStylesParlam, $rowStyles);

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

      $rowCellStylesZb[$i] = '';
      $rowStyles = '';
      customDrawRow('zutrittsberechtigung', $rowData, $rowCellStylesZb[$i], $rowStyles);

      $mailtoZb[$i] = 'mailto:' . rawurlencode($zb["email"]) . '?subject=' . rawurlencode($emailSubjectZb[$i]) . '&body=' . rawurlencode('[Kopiere von Vorlage]') . '&bcc=redaktion@lobbywatch.ch';

      $i++;
    }

    $lang = $parlamentarier_lang;
    lobbywatch_set_language($lang);
    $lang_suffix = get_lang_suffix($lang);

    $organisationsbezeihungen = organisationsbeziehungen($con, $rowData["organisationen_from_interessenbindungen"]);

//         ShowPreviewPage('<h4>Preview</h4><h3>' .$rowData["parlamentarier_name"] . '</h3>' .
//         '<h4>Interessenbindungen</h4><ul>' . $rowData['interessenbindungen'] . '</ul>' .
//         '<h4>Gäste</h4><ul>' . $rowData['zutrittsberechtigungen'] . '</ul>' .
//         '<h4>Mandate</h4><ul>' . $rowData['mandate'] . '</ul>');

    $state = '<table style="margin-top: 1em; margin-bottom: 1em;">
              <tr><td style="padding: 16px; '. $rowCellStylesParlam['id'] . '" title="Status des Arbeitsablaufes dieses Parlamenteriers">Arbeitsablauf</td><td style="padding: 16px; '. $rowCellStylesParlam['nachname'] . '" title="Status der Vollständigkeit der Felder dieses Parlamenteriers">Vollständigkeit</td></tr></table>';

//     $trans = lt('Ihre Interessenbindungen:');
//     df($trans);

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
          'State' =>  $state,
          'Preview' => '<p><b>Beruf</b>: ' . $rowData['beruf'] . '</p>' .
            '<h4>Kommissionen</h4><ul>' . $rowData['kommissionen'] . '</ul>' .
            '<h4>Interessenbindungen</h4><ul>' . $rowData['interessenbindungen'] . '</ul>' .
            '<h4>Gäste' . (substr_count($rowData['zutrittsberechtigungen'], '[VALID_Zutrittsberechtigung]') > 2 ? ' <img src="img/icons/warning.gif" alt="Warnung">': '') . '</h4>' . ($rowData['zutrittsberechtigungen'] ? '<ul>' . $rowData['zutrittsberechtigungen'] . '</ul>': '<p>keine</p>') .
            '<h4>Mandate der Gäste</h4>' . $zbRet['gaesteMitMandaten'],
          'EmailTitle' => 'Autorisierungs-E-Mail: ' . '<a href="' . $mailtoParlam. '" target="_blank">' . $rowData["parlamentarier_name"] . '</a>',
          'EmailText' => '<div>' . $rowData['anrede'] . '' . $emailIntroParlam . (isset($rowData['beruf']) ? '<b>' . lt('Beruf:') . '</b> ' . $rowData['beruf'] . '' : '') . '<br><br><b>' . lt('Ihre Interessenbindungen:') .'</b><ul>' . $rowData['interessenbindungen_for_email'] . '</ul>' .
            $organisationsbezeihungen .
            '<b>' . lt('Ihre Gäste:') . '</b></p>' . ($rowData['zutrittsberechtigungen_for_email'] ? '<ul>' . $rowData['zutrittsberechtigungen_for_email'] . '</ul>': '<br>' . lt('keine') . '<br>') .
            '' . $emailEndParlam . '</div>',
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
