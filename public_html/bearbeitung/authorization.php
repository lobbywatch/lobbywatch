<?php
// Processed by afterburner.sh



include_once dirname(__FILE__) . '/' . 'phpgen_settings.php';
include_once dirname(__FILE__) . '/' . 'components/application.php';
include_once dirname(__FILE__) . '/' . 'components/security/permission_set.php';
include_once dirname(__FILE__) . '/' . 'components/security/user_authentication/table_based_user_authentication.php';
include_once dirname(__FILE__) . '/' . 'components/security/grant_manager/table_based_user_grant_manager.php';
include_once dirname(__FILE__) . '/' . 'components/security/table_based_user_manager.php';
include_once dirname(__FILE__) . '/' . 'components/security/user_identity_storage/user_identity_session_storage.php';
include_once dirname(__FILE__) . '/' . 'components/security/recaptcha.php';
include_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';



$dataSourceRecordPermissions = array();

$tableCaptions = array('organisation' => '<span class="entity important-entity">Organisation</span>',
'organisation.organisation_anhang' => '<span class="entity important-entity">Organisation</span>->Organisation Anhang',
'organisation.organisation_jahr' => '<span class="entity important-entity">Organisation</span>->Organisation Jahr',
'organisation.organisation_log' => '<span class="entity important-entity">Organisation</span>->Organisation Log',
'organisation.v_organisation_parlamentarier_beide_indirekt' => '<span class="entity important-entity">Organisation</span>->Organisation Parlamentarier Beide Indirekt',
'organisation.v_organisation_beziehung_auftraggeber_fuer' => '<span class="entity important-entity">Organisation</span>->Organisation Beziehung Auftraggeber Fuer',
'organisation.v_organisation_beziehung_arbeitet_fuer' => '<span class="entity important-entity">Organisation</span>->Organisation Beziehung Arbeitet Fuer',
'organisation.v_organisation_beziehung_mitglied_von' => '<span class="entity important-entity">Organisation</span>->Organisation Beziehung Mitglied Von',
'organisation.v_organisation_beziehung_mitglieder' => '<span class="entity important-entity">Organisation</span>->Organisation Beziehung Mitglieder',
'organisation.v_organisation_parlamentarier' => '<span class="entity important-entity">Organisation</span>-><s>V Organisation Parlamentarier</s>',
'organisation.v_organisation_parlamentarier_indirekt' => '<span class="entity important-entity">Organisation</span>-><s>Organisation Parlamentarier Indirekt</s>',
'organisation.v_organisation_parlamentarier_beide' => '<span class="entity important-entity">Organisation</span>-><s>V Organisation Parlamentarier Beide</s>',
'organisation.interessenbindung' => '<span class="entity important-entity">Organisation</span>-><s>Interessenbindung</s>',
'organisation.mandat' => '<span class="entity important-entity">Organisation</span>-><s>Mandat</s>',
'parlamentarier' => '<span class="entity important-entity">Parlamentarier</span>',
'parlamentarier.parlamentarier_anhang' => '<span class="entity important-entity">Parlamentarier</span>->Parlamentarier Anhang',
'parlamentarier.parlamentarier_anhang.parlamentarier_anhang_log' => '<span class="entity important-entity">Parlamentarier</span>->Parlamentarier Anhang->Parlamentarier Anhang Log',
'parlamentarier.vf_parlamentarier_transparenz' => '<span class="entity important-entity">Parlamentarier</span>->Parlamentarier Vergütungstransparenz',
'parlamentarier.parlamentarier_log' => '<span class="entity important-entity">Parlamentarier</span>->Parlamentarier Log',
'parlamentarier.v_zutrittsberechtigung_simple_compat' => '<span class="entity important-entity">Parlamentarier</span>->Zutrittsberechtigte',
'parlamentarier.v_organisation_parlamentarier_beide_indirekt' => '<span class="entity important-entity">Parlamentarier</span>->Organisation Parlamentarier Beide Indirekt',
'parlamentarier.v_in_kommission_liste' => '<span class="entity important-entity">Parlamentarier</span>->In Kommission Liste',
'parlamentarier.v_interessenbindung_liste_indirekt' => '<span class="entity important-entity">Parlamentarier</span>-><s>V Interessenbindung Liste Indirekt</s>',
'parlamentarier.v_zutrittsberechtigung_mit_mandaten_indirekt' => '<span class="entity important-entity">Parlamentarier</span>-><s>V Zutrittsberechtigung Mit Mandaten Indirekt</s>',
'parlamentarier.v_interessenbindung_liste' => '<span class="entity important-entity">Parlamentarier</span>-><s>V Interessenbindung Liste</s>',
'parlamentarier.v_zutrittsberechtigung_mit_mandaten' => '<span class="entity important-entity">Parlamentarier</span>-><s>V Zutrittsberechtigung Mit Mandaten</s>',
'vf_parlamentarier_transparenz' => '<span class="relation" title="Interessenbindungen der Parlamentarier">Parlamentarier Vergütungstransparenz</span>',
'vf_parlamentarier_transparenz.parlamentarier_transparenz_log' => '<span class="relation" title="Interessenbindungen der Parlamentarier">Parlamentarier Vergütungstransparenz</span>->Parlamentarier Transparenz Log',
'person' => '<span class="entity important-entity">Person</span>',
'person.person_anhang' => '<span class="entity important-entity">Person</span>->Person Anhang',
'person.person_anhang.person_anhang_log' => '<span class="entity important-entity">Person</span>->Person Anhang->Person Anhang Log',
'person.person_log' => '<span class="entity important-entity">Person</span>->Person Log',
'person.v_zutrittsberechtigung_mandate' => '<span class="entity important-entity">Person</span>-><s>V Zutrittsberechtigung Mandate</s>',
'person.mandat' => '<span class="entity important-entity">Person</span>-><s>Mandat</s>',
'vf_interessenbindung' => '<span class="relation" title="Interessenbindungen der Parlamentarier">Intereressenbindungen von NR/SR</span>',
'vf_interessenbindung.interessenbindung_jahr' => '<span class="relation" title="Interessenbindungen der Parlamentarier">Intereressenbindungen von NR/SR</span>->Interessenbindungsvergütung',
'vf_interessenbindung.interessenbindung_log' => '<span class="relation" title="Interessenbindungen der Parlamentarier">Intereressenbindungen von NR/SR</span>->Interessenbindung Log',
'uv_interessenbindung_jahr' => 'Interessenbindungsvergütungen',
'uv_interessenbindung_jahr.interessenbindung_jahr_log' => 'Interessenbindungsvergütungen->Interessenbindungsvergütungen Log',
'zutrittsberechtigung' => '<span class="relation" title="Zutrittsberechtigungen für Gäse ins Bundeshaus">Zutrittsberechtigung</span>',
'zutrittsberechtigung.zutrittsberechtigung_log' => '<span class="relation" title="Zutrittsberechtigungen für Gäse ins Bundeshaus">Zutrittsberechtigung</span>->Zutrittsberechtigung Log',
'mandat' => '<span class="relation" title="Mandate der Zutrittsberechtigten">Mandate von Personen</span>',
'mandat.mandat_jahr' => '<span class="relation" title="Mandate der Zutrittsberechtigten">Mandate von Personen</span>->Mandatsvergütung',
'mandat.mandat_log' => '<span class="relation" title="Mandate der Zutrittsberechtigten">Mandate von Personen</span>->Mandat Log',
'in_kommission' => '<span class="relation">In Kommission</span>',
'in_kommission.in_kommission_log' => '<span class="relation">In Kommission</span>->In Kommission Log',
'organisation_beziehung' => '<span class="relation">Organisation Beziehung</span>',
'organisation_beziehung.organisation_beziehung_log' => '<span class="relation">Organisation Beziehung</span>->Organisation Beziehung Log',
'branche' => '<span class="entity">Branche</span>',
'branche.interessengruppe' => '<span class="entity">Branche</span>->Interessengruppe',
'branche.organisation' => '<span class="entity">Branche</span>->Organisation',
'branche.branche_log' => '<span class="entity">Branche</span>->Branche Log',
'interessengruppe' => '<span class="entity">Lobbygruppe</span>',
'interessengruppe.interessengruppe_log' => '<span class="entity">Lobbygruppe</span>->Interessengruppe Log',
'interessengruppe.organisation' => '<span class="entity">Lobbygruppe</span>->Organisation',
'interessengruppe.parlamentarier' => '<span class="entity">Lobbygruppe</span>->Parlamentarier',
'kommission' => '<span class="entity">Kommission</span>',
'kommission.v_in_kommission' => '<span class="entity">Kommission</span>->Parlamentarier in Kommission',
'kommission.branche' => '<span class="entity">Kommission</span>->Branche',
'kommission.kommission_log' => '<span class="entity">Kommission</span>->Kommission Log',
'partei' => '<span class="entity">Partei</span>',
'partei.partei_log' => '<span class="entity">Partei</span>->Partei Log',
'partei.parlamentarier' => '<span class="entity">Partei</span>->Parlamentarier',
'partei.fraktion' => '<span class="entity">Partei</span>->Fraktion',
'fraktion' => '<span class="entity">Fraktion</span>',
'fraktion.fraktion_log' => '<span class="entity">Fraktion</span>->Fraktion Log',
'fraktion.partei' => '<span class="entity">Fraktion</span>->Partei',
'fraktion.parlamentarier' => '<span class="entity">Fraktion</span>->Parlamentarier',
'kanton' => '<span class="entity">Kanton</span>',
'kanton.kanton_jahr' => '<span class="entity">Kanton</span>->Kanton Jahr',
'kanton.parlamentarier' => '<span class="entity">Kanton</span>-><s>Parlamentarier</s>',
'kanton.kanton_log' => '<span class="entity">Kanton</span>->Kanton Log',
'v_last_updated_tables' => '<span class="view">Tabellenstand</span>',
'q_last_updated_tables' => '<span class="view">Tabellenstand</span>',
'q_unvollstaendige_parlamentarier' => '<span class="view">Unvollständige Parlamentarier</span>',
'q_unvollstaendige_zutrittsberechtigte' => '<span class="view">Unvollständige Zutrittsberechtigte</span>',
'q_unvollstaendige_organisationen' => '<span class="view">Unvollständige Organisationen</span>',
'settings' => '<span class="settings">Settings</span>',
'settings.settings_log' => '<span class="settings">Settings</span>->Settings Log',
'settings_category' => '<span class="settings">Settings Category</span>',
'settings_category.settings' => '<span class="settings">Settings Category</span>-><s>Settings</s>',
'settings_category.settings_category_log' => '<span class="settings">Settings Category</span>->Settings Category Log',
'translation_source' => '<span class="settings">Translation Source</span>',
'translation_source.translation_target' => '<span class="settings">Translation Source</span>->Translation Target',
'translation_source.translation_target01' => '<span class="settings">Translation Source</span>->Translation Target',
'translation_target' => '<span class="settings">Translation Target</span>',
'user' => '<span class="settings">User</span>',
'parlamentarier_anhang' => 'Parlamentarier Anhang',
'person_anhang' => 'Person Anhang',
'uv_wissensartikel_link' => '<span class="relation" title="Verknüpfung von Lobbypedia-Artikel mit einem Datensatz">Lobbypediaverknüpfung</span>',
'uv_wissensartikel_link.wissensartikel_link_log' => '<span class="relation" title="Verknüpfung von Lobbypedia-Artikel mit einem Datensatz">Lobbypediaverknüpfung</span>->Lobbypediaverknüpfung Log');

$usersTableInfo = array(
    'TableName' => 'user',
    'UserId' => 'id',
    'UserName' => 'name',
    'Password' => 'password',
    'Email' => '',
    'UserToken' => '',
    'UserStatus' => ''
);

function EncryptPassword($password, &$result)
{
    custom_OnEncryptPassword($password, $result);
}

function VerifyPassword($enteredPassword, $encryptedPassword, &$result)
{
    custom_OnVerifyPassword($enteredPassword, $encryptedPassword, $result);
}

function BeforeUserRegistration($userName, $email, $password, &$allowRegistration, &$errorMessage)
{

}    

function AfterUserRegistration($userName, $email)
{

}    

function PasswordResetRequest($userName, $email)
{

}

function PasswordResetComplete($userName, $email)
{

}

function VerifyPasswordStrength($password, &$result, &$passwordRuleMessage) 
{

}

function CreatePasswordHasher()
{
    $hasher = CreateHasher('Custom');
    if ($hasher instanceof CustomStringHasher) {
        $hasher->OnEncryptPassword->AddListener('EncryptPassword');
        $hasher->OnVerifyPassword->AddListener('VerifyPassword');
    }
    return $hasher;
}

function CreateGrantManager() 
{
    global $tableCaptions;
    global $usersTableInfo;
    
    $userPermsTableInfo = array('TableName' => 'user_permission', 'UserId' => 'user_id', 'PageName' => 'page_name', 'Grant' => 'permission_name');
    
    return new TableBasedUserGrantManager(MyPDOConnectionFactory::getInstance(), GetGlobalConnectionOptions(),
        $usersTableInfo, $userPermsTableInfo, $tableCaptions, true);
}

function CreateTableBasedUserManager() 
{
    global $usersTableInfo;

    $userManager = new TableBasedUserManager(MyPDOConnectionFactory::getInstance(), GetGlobalConnectionOptions(), 
        $usersTableInfo, CreatePasswordHasher(), false);
    $userManager->OnVerifyPasswordStrength->AddListener('VerifyPasswordStrength');

    return $userManager;
}

function GetReCaptcha($formId) 
{
    return null;
}

function SetUpUserAuthorization() 
{
    global $dataSourceRecordPermissions;

    $hasher = CreatePasswordHasher();

    $grantManager = CreateGrantManager();

    $userAuthentication = new TableBasedUserAuthentication(new UserIdentitySessionStorage(), true, $hasher, CreateTableBasedUserManager(), true, false, false);

    GetApplication()->SetUserAuthentication($userAuthentication);
    GetApplication()->SetUserGrantManager($grantManager);
    GetApplication()->SetDataSourceRecordPermissionRetrieveStrategy(new HardCodedDataSourceRecordPermissionRetrieveStrategy($dataSourceRecordPermissions));
}
