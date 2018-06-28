<?php
// Processed by afterburner.sh



require_once 'phpgen_settings.php';
require_once 'components/application.php';
require_once 'components/security/permission_set.php';
require_once 'components/security/user_authentication/table_based_user_authentication.php';
require_once 'components/security/grant_manager/user_grant_manager.php';
require_once 'components/security/grant_manager/composite_grant_manager.php';
require_once 'components/security/grant_manager/hard_coded_user_grant_manager.php';
require_once 'components/security/grant_manager/table_based_user_grant_manager.php';
require_once 'components/security/table_based_user_manager.php';

include_once 'components/security/user_identity_storage/user_identity_session_storage.php';

require_once 'database_engine/mysql_engine.php';

$grants = array();

$appGrants = array();

$dataSourceRecordPermissions = array();

$tableCaptions = array('organisation' => '<span class="entity important-entity">Organisation</span>',
'organisation.organisation_anhang' => '<span class="entity important-entity">Organisation</span>->Organisation Anhang',
'organisation.organisation_jahr' => '<span class="entity important-entity">Organisation</span>->Organisation Jahr',
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
'parlamentarier.v_organisation_parlamentarier_beide_indirekt' => '<span class="entity important-entity">Parlamentarier</span>->Organisation Parlamentarier Beide Indirekt',
'parlamentarier.v_in_kommission_liste' => '<span class="entity important-entity">Parlamentarier</span>->In Kommission Liste',
'parlamentarier.person' => '<span class="entity important-entity">Parlamentarier</span>->Person',
'parlamentarier.v_interessenbindung_liste_indirekt' => '<span class="entity important-entity">Parlamentarier</span>-><s>V Interessenbindung Liste Indirekt</s>',
'parlamentarier.v_zutrittsberechtigung_mit_mandaten_indirekt' => '<span class="entity important-entity">Parlamentarier</span>-><s>V Zutrittsberechtigung Mit Mandaten Indirekt</s>',
'parlamentarier.v_interessenbindung_liste' => '<span class="entity important-entity">Parlamentarier</span>-><s>V Interessenbindung Liste</s>',
'parlamentarier.v_zutrittsberechtigung_mit_mandaten' => '<span class="entity important-entity">Parlamentarier</span>-><s>V Zutrittsberechtigung Mit Mandaten</s>',
'person' => '<span class="entity important-entity">Person</span>',
'person.person_anhang' => '<span class="entity important-entity">Person</span>->Person Anhang',
'person.v_zutrittsberechtigung_mandate' => '<span class="entity important-entity">Person</span>-><s>V Zutrittsberechtigung Mandate</s>',
'person.mandat' => '<span class="entity important-entity">Person</span>-><s>Mandat</s>',
'interessenbindung' => '<span class="relation" title="Interessenbindungen der Parlamentarier">Intereressenbindungen von NR/SR</span>',
'interessenbindung.interessenbindung_jahr' => '<span class="relation" title="Interessenbindungen der Parlamentarier">Intereressenbindungen von NR/SR</span>->Interessenbindungsvergütung',
'zutrittsberechtigung' => '<span class="relation" title="Zutrittsberechtigungen für Gäse ins Bundeshaus">Zutrittsberechtigung</span>',
'mandat' => '<span class="relation" title="Mandate der Zutrittsberechtigten">Mandate von Personen</span>',
'mandat.mandat_jahr' => '<span class="relation" title="Mandate der Zutrittsberechtigten">Mandate von Personen</span>->Mandatsvergütung',
'in_kommission' => '<span class="relation">In Kommission</span>',
'organisation_beziehung' => '<span class="relation">Organisation Beziehung</span>',
'branche' => '<span class="entity">Branche</span>',
'branche.interessengruppe' => '<span class="entity">Branche</span>->Interessengruppe',
'branche.organisation' => '<span class="entity">Branche</span>->Organisation',
'interessengruppe' => '<span class="entity">Lobbygruppe</span>',
'interessengruppe.organisation' => '<span class="entity">Lobbygruppe</span>->Organisation',
'interessengruppe.parlamentarier' => '<span class="entity">Lobbygruppe</span>->Parlamentarier',
'kommission' => '<span class="entity">Kommission</span>',
'kommission.v_in_kommission' => '<span class="entity">Kommission</span>->Parlamentarier in Kommission',
'kommission.branche' => '<span class="entity">Kommission</span>->Branche',
'partei' => '<span class="entity">Partei</span>',
'partei.parlamentarier' => '<span class="entity">Partei</span>->Parlamentarier',
'partei.fraktion' => '<span class="entity">Partei</span>->Fraktion',
'fraktion' => '<span class="entity">Fraktion</span>',
'fraktion.partei' => '<span class="entity">Fraktion</span>->Partei',
'fraktion.parlamentarier' => '<span class="entity">Fraktion</span>->Parlamentarier',
'kanton' => '<span class="entity">Kanton</span>',
'kanton.kanton_jahr' => '<span class="entity">Kanton</span>->Kanton Jahr',
'kanton.parlamentarier' => '<span class="entity">Kanton</span>-><s>Parlamentarier</s>',
'v_last_updated_tables' => '<span class="view">Tabellenstand</span>',
'q_last_updated_tables' => '<span class="view">Tabellenstand</span>',
'q_unvollstaendige_parlamentarier' => '<span class="view">Unvollständige Parlamentarier</span>',
'q_unvollstaendige_zutrittsberechtigte' => '<span class="view">Unvollständige Zutrittsberechtigte</span>',
'q_unvollstaendige_organisationen' => '<span class="view">Unvollständige Organisationen</span>',
'settings' => '<span class="settings">Settings</span>',
'settings_category' => '<span class="settings">Settings Category</span>',
'settings_category.settings' => '<span class="settings">Settings Category</span>-><s>Settings</s>',
'translation_source' => '<span class="settings">Translation Source</span>',
'translation_source.translation_target' => '<span class="settings">Translation Source</span>->Translation Target',
'translation_source.translation_target01' => '<span class="settings">Translation Source</span>->Translation Target',
'translation_target' => '<span class="settings">Translation Target</span>',
'user' => '<span class="settings">User</span>',
'parlamentarier_anhang' => 'Parlamentarier Anhang',
'person_anhang' => 'Person Anhang');

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

}

function VerifyPassword($enteredPassword, $encryptedPassword, &$result)
{

}

function BeforeUserRegistration($username, $email, $password, &$allowRegistration, &$errorMessage)
{

}    

function AfterUserRegistration($username, $email)
{

}    

function PasswordResetRequest($username, $email)
{

}

function PasswordResetComplete($username, $email)
{

}

function CreatePasswordHasher()
{
    $hasher = CreateHasher('PHPass');
    if ($hasher instanceof CustomStringHasher) {
        $hasher->OnEncryptPassword->AddListener('EncryptPassword');
        $hasher->OnVerifyPassword->AddListener('VerifyPassword');
    }
    return $hasher;
}

function CreateTableBasedGrantManager()
{
    global $tableCaptions;
    global $usersTableInfo;
    $userPermsTableInfo = array('TableName' => 'user_permission', 'UserId' => 'user_id', 'PageName' => 'page_name', 'Grant' => 'permission_name');
    
    $tableBasedGrantManager = new TableBasedUserGrantManager(MyPDOConnectionFactory::getInstance(), GetGlobalConnectionOptions(),
        $usersTableInfo, $userPermsTableInfo, $tableCaptions, true);
    return $tableBasedGrantManager;
}

function CreateTableBasedUserManager() {
    global $usersTableInfo;
    return new TableBasedUserManager(MyPDOConnectionFactory::getInstance(), GetGlobalConnectionOptions(), $usersTableInfo, CreatePasswordHasher(), false);
}

function SetUpUserAuthorization()
{
    global $grants;
    global $appGrants;
    global $dataSourceRecordPermissions;

    $hasher = CreatePasswordHasher();

    $hardCodedGrantManager = new HardCodedUserGrantManager($grants, $appGrants);
    $tableBasedGrantManager = CreateTableBasedGrantManager();
    $grantManager = new CompositeGrantManager();
    $grantManager->AddGrantManager($hardCodedGrantManager);
    if (!is_null($tableBasedGrantManager)) {
        $grantManager->AddGrantManager($tableBasedGrantManager);
    }

    $userAuthentication = new TableBasedUserAuthentication(new UserIdentitySessionStorage(), true, $hasher, CreateTableBasedUserManager(), true, false, false);

    GetApplication()->SetUserAuthentication($userAuthentication);
    GetApplication()->SetUserGrantManager($grantManager);
    GetApplication()->SetDataSourceRecordPermissionRetrieveStrategy(new HardCodedDataSourceRecordPermissionRetrieveStrategy($dataSourceRecordPermissions));
}
