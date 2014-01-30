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

$tableCaptions = array('organisation' => '<span class="entity important-entity">Organisation</span>',
'organisation.v_organisation_parlamentarier_beide_indirekt' => '<span class="entity important-entity">Organisation</span>.V Organisation Parlamentarier Beide Indirekt',
'organisation.v_organisation_parlamentarier_beide' => '<span class="entity important-entity">Organisation</span>.V Organisation Parlamentarier Beide',
'organisation.v_organisation_parlamentarier_indirekt' => '<span class="entity important-entity">Organisation</span>.V Organisation Parlamentarier Indirekt',
'organisation.v_organisation_beziehung_auftraggeber_fuer' => '<span class="entity important-entity">Organisation</span>.V Organisation Beziehung Auftraggeber Fuer',
'organisation.v_organisation_beziehung_arbeitet_fuer' => '<span class="entity important-entity">Organisation</span>.V Organisation Beziehung Arbeitet Fuer',
'organisation.v_organisation_beziehung_mitglied_von' => '<span class="entity important-entity">Organisation</span>.V Organisation Beziehung Mitglied Von',
'organisation.v_organisation_beziehung_mitglieder' => '<span class="entity important-entity">Organisation</span>.V Organisation Beziehung Mitglieder',
'organisation.v_organisation_parlamentarier' => '<span class="entity important-entity">Organisation</span>.V Organisation Parlamentarier',
'organisation.interessenbindung' => '<span class="entity important-entity">Organisation</span>.Interessenbindung',
'organisation.mandat' => '<span class="entity important-entity">Organisation</span>.Mandat',
'parlamentarier' => '<span class="entity important-entity">Parlamentarier</span>',
'parlamentarier.parlamentarier_anhang' => '<span class="entity important-entity">Parlamentarier</span>.Parlamentarier Anhang',
'parlamentarier.v_organisation_parlamentarier_beide_indirekt' => '<span class="entity important-entity">Parlamentarier</span>.V Organisation Parlamentarier Beide Indirekt',
'parlamentarier.v_interessenbindung_liste_indirekt' => '<span class="entity important-entity">Parlamentarier</span>.V Interessenbindung Liste Indirekt',
'parlamentarier.v_zutrittsberechtigung_mit_mandaten_indirekt' => '<span class="entity important-entity">Parlamentarier</span>.V Zutrittsberechtigung Mit Mandaten Indirekt',
'parlamentarier.v_in_kommission_liste' => '<span class="entity important-entity">Parlamentarier</span>.In Kommission Liste',
'parlamentarier.v_interessenbindung_liste' => '<span class="entity important-entity">Parlamentarier</span>.V Interessenbindung Liste',
'parlamentarier.v_zutrittsberechtigung_mit_mandaten' => '<span class="entity important-entity">Parlamentarier</span>.V Zutrittsberechtigung Mit Mandaten',
'zutrittsberechtigung' => '<span class="entity">Zutrittsberechtigung</span>',
'zutrittsberechtigung.zutrittsberechtigung_anhang' => '<span class="entity">Zutrittsberechtigung</span>.Zutrittsberechtigung Anhang',
'zutrittsberechtigung.v_zutrittsberechtigung_mandate' => '<span class="entity">Zutrittsberechtigung</span>.V Zutrittsberechtigung Mandate',
'zutrittsberechtigung.mandat' => '<span class="entity">Zutrittsberechtigung</span>.Mandat',
'interessenbindung' => '<span class="relation">Interessenbindung</span>',
'mandat' => '<span class="relation">Mandat</span>',
'in_kommission' => '<span class="relation">In Kommission</span>',
'in_kommission.v_in_kommission_parlamentarier' => '<span class="relation">In Kommission</span>.V In Kommission Parlamentarier',
'organisation_beziehung' => '<span class="relation">Organisation Beziehung</span>',
'interessengruppe' => '<span class="entity">Interessengruppe</span>',
'interessengruppe.organisation' => '<span class="entity">Interessengruppe</span>.Organisation',
'interessengruppe.parlamentarier' => '<span class="entity">Interessengruppe</span>.Parlamentarier',
'branche' => '<span class="entity">Branche</span>',
'branche.interessengruppe' => '<span class="entity">Branche</span>.Interessengruppe',
'branche.organisation' => '<span class="entity">Branche</span>.Organisation',
'kommission' => '<span class="entity">Kommission</span>',
'kommission.in_kommission' => '<span class="entity">Kommission</span>.Parlamentarier in Kommission',
'kommission.branche' => '<span class="entity">Kommission</span>.Branche',
'partei' => '<span class="entity">Partei</span>',
'partei.parlamentarier' => '<span class="entity">Partei</span>.Parlamentarier',
'partei.fraktion' => '<span class="entity">Partei</span>.Fraktion',
'fraktion' => '<span class="entity">Fraktion</span>',
'fraktion.partei' => '<span class="entity">Fraktion</span>.Partei',
'v_parlamentarier_authorisierungs_email' => '<span class="view">Parlamentarier Email</span>',
'q_unvollstaendige_parlamentarier' => '<span class="view">Unvollständige Parlamentarier</span>',
'q_unvollstaendige_organisationen' => '<span class="view">Unvollständige Organisationen</span>',
'v_last_updated_tables' => '<span class="view">Tabellenstand</span>',
'q_last_updated_tables' => '<span class="view">Tabellenstand</span>');

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
