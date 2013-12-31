<?php
// Processed by afterburner.sh



require_once 'phpgen_settings.php';
require_once 'components/security/security_info.php';
require_once 'components/security/datasource_security_info.php';
require_once 'components/security/tablebased_auth.php';
require_once 'components/security/user_grants_manager.php';
require_once 'components/security/table_based_user_grants_manager.php';

require_once 'database_engine/mysql_engine.php';

$grants = array();

$appGrants = array();

$dataSourceRecordPermissions = array();

$tableCaptions = array('kommission' => 'Kommission',
'kommission.in_kommission' => 'Kommission.Parlamentarier in Kommission',
'kommission.branche' => 'Kommission.Branche',
'organisation' => 'Organisation',
'organisation.v_organisation_parlamentarier_indirekt' => 'Organisation.V Organisation Parlamentarier Indirekt',
'organisation.v_organisation_beziehung_auftraggeber_fuer' => 'Organisation.V Organisation Beziehung Auftraggeber Fuer',
'organisation.v_organisation_beziehung_arbeitet_fuer' => 'Organisation.V Organisation Beziehung Arbeitet Fuer',
'organisation.v_organisation_beziehung_mitglied_von' => 'Organisation.V Organisation Beziehung Mitglied Von',
'organisation.v_organisation_beziehung_mitglieder' => 'Organisation.V Organisation Beziehung Mitglieder',
'organisation.v_organisation_parlamentarier' => 'Organisation.V Organisation Parlamentarier',
'organisation.interessenbindung' => 'Organisation.Interessenbindung',
'organisation.mandat' => 'Organisation.Mandat',
'parlamentarier' => 'Parlamentarier',
'parlamentarier.parlamentarier_anhang' => 'Parlamentarier.Parlamentarier Anhang',
'parlamentarier.v_interessenbindung_liste_indirekt' => 'Parlamentarier.V Interessenbindung Liste Indirekt',
'parlamentarier.v_zutrittsberechtigung_mit_mandaten_indirekt' => 'Parlamentarier.V Zutrittsberechtigung Mit Mandaten Indirekt',
'parlamentarier.v_in_kommission_liste' => 'Parlamentarier.In Kommission Liste',
'parlamentarier.v_interessenbindung_liste' => 'Parlamentarier.V Interessenbindung Liste',
'parlamentarier.v_zutrittsberechtigung_mit_mandaten' => 'Parlamentarier.V Zutrittsberechtigung Mit Mandaten',
'in_kommission' => 'In Kommission',
'in_kommission.v_in_kommission_parlamentarier' => 'In Kommission.V In Kommission Parlamentarier',
'interessenbindung' => 'Interessenbindung',
'zutrittsberechtigung' => 'Zutrittsberechtigung',
'zutrittsberechtigung.v_zutrittsberechtigung_mandate' => 'Zutrittsberechtigung.V Zutrittsberechtigung Mandate',
'zutrittsberechtigung.mandat' => 'Zutrittsberechtigung.Mandat',
'mandat' => 'Mandat',
'organisation_beziehung' => 'Organisation Beziehung',
'branche' => 'Branche',
'branche.interessengruppe' => 'Branche.Interessengruppe',
'branche.organisation' => 'Branche.Organisation',
'partei' => 'Partei',
'partei.parlamentarier' => 'Partei.Parlamentarier',
'interessengruppe' => 'Interessengruppe',
'interessengruppe.organisation' => 'Interessengruppe.Organisation',
'interessengruppe.parlamentarier' => 'Interessengruppe.Parlamentarier',
'v_parlamentarier_authorisierungs_email' => 'Parlamentarier Email',
'q_unvollstaendige_parlamentarier' => 'Unvollständige Parlamentarier',
'q_unvollstaendige_organisationen' => 'Unvollständige Organisationen',
'v_last_updated_tables' => 'Tabellenstand',
'q_last_updated_tables' => 'Tabellenstand');

function CreateTableBasedGrantsManager()
{
    global $tableCaptions;
    $usersTable = array('TableName' => 'user', 'UserName' => 'name', 'UserId' => 'id', 'Password' => 'password');
    $userPermsTable = array('TableName' => 'user_permission', 'UserId' => 'user_id', 'PageName' => 'page_name', 'Grant' => 'permission_name');

    $passwordHasher = HashUtils::CreateHasher('PHPass');
    $connectionOptions = GetGlobalConnectionOptions();
    $tableBasedGrantsManager = new TableBasedUserGrantsManager(new MyPDOConnectionFactory(), $connectionOptions,
        $usersTable, $userPermsTable, $tableCaptions, $passwordHasher, true);
    return $tableBasedGrantsManager;
}

function SetUpUserAuthorization()
{
    global $grants;
    global $appGrants;
    global $dataSourceRecordPermissions;
    $hardCodedGrantsManager = new HardCodedUserGrantsManager($grants, $appGrants);
$tableBasedGrantsManager = CreateTableBasedGrantsManager();
$grantsManager = new CompositeGrantsManager();
$grantsManager->AddGrantsManager($hardCodedGrantsManager);
if (!is_null($tableBasedGrantsManager)) {
    $grantsManager->AddGrantsManager($tableBasedGrantsManager);
    GetApplication()->SetUserManager($tableBasedGrantsManager);
}
$userAuthorizationStrategy = new TableBasedUserAuthorization(new MyPDOConnectionFactory(), GetGlobalConnectionOptions(), 'user', 'name', 'id', $grantsManager);
    GetApplication()->SetUserAuthorizationStrategy($userAuthorizationStrategy);

GetApplication()->SetDataSourceRecordPermissionRetrieveStrategy(
    new HardCodedDataSourceRecordPermissionRetrieveStrategy($dataSourceRecordPermissions));
}

function GetIdentityCheckStrategy()
{
    return new TableBasedIdentityCheckStrategy(new MyPDOConnectionFactory(), GetGlobalConnectionOptions(), 'user', 'name', 'password', 'PHPass');
}

function CanUserChangeOwnPassword()
{
    return true;
}
