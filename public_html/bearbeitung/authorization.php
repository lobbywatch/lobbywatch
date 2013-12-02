<?php
// Processed by afterburner.sh



require_once 'components/page.php';
require_once 'components/security/datasource_security_info.php';
require_once 'components/security/security_info.php';
require_once 'components/security/hardcoded_auth.php';
require_once 'components/security/user_grants_manager.php';

// Custom modification: Use $users form settings.php

$usersIds = array('roland' => -1, 'bane' => -1, 'rebecca' => -1, 'otto' => -1, 'thomas' => -1, 'admin' => -1);

$dataSourceRecordPermissions = array();

$grants = array('guest' => 
        array()
    ,
    'roland' => 
        array('branche' => new DataSourceSecurityInfo(false, false, false, false),
        'branche.interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche.organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe.organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe.parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe.zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission.branche' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission.in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_zugangsberechtigung_mit_mandaten' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_interessenbindung_liste' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_in_kommission_liste' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_zugangsberechtigung_mandate' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.parlamentarier_anhang' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'partei.parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung.mandat' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'bane' => 
        array('branche' => new DataSourceSecurityInfo(false, false, false, false),
        'branche.interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche.organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe.organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe.parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe.zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission.branche' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission.in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_zugangsberechtigung_mit_mandaten' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_interessenbindung_liste' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_in_kommission_liste' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_zugangsberechtigung_mandate' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.parlamentarier_anhang' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'partei.parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung.mandat' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'rebecca' => 
        array('branche' => new DataSourceSecurityInfo(false, false, false, false),
        'branche.interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche.organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe.organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe.parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe.zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission.branche' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission.in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_zugangsberechtigung_mit_mandaten' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_interessenbindung_liste' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_in_kommission_liste' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_zugangsberechtigung_mandate' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.parlamentarier_anhang' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'partei.parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung.mandat' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'otto' => 
        array('branche' => new DataSourceSecurityInfo(false, false, false, false),
        'branche.interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche.organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe.organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe.parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe.zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission.branche' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission.in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_zugangsberechtigung_mit_mandaten' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_interessenbindung_liste' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_in_kommission_liste' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_zugangsberechtigung_mandate' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.parlamentarier_anhang' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'partei.parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung.mandat' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'thomas' => 
        array('branche' => new DataSourceSecurityInfo(false, false, false, false),
        'branche.interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche.organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe.organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe.parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe.zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission.branche' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission.in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_zugangsberechtigung_mit_mandaten' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_interessenbindung_liste' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_in_kommission_liste' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_zugangsberechtigung_mandate' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.parlamentarier_anhang' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'partei.parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung.mandat' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'admin' => 
        array('branche' => new DataSourceSecurityInfo(false, false, false, false),
        'branche.interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche.organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe.organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe.parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe.zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission.branche' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission.in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation.zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_zugangsberechtigung_mit_mandaten' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_interessenbindung_liste' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_in_kommission_liste' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.v_zugangsberechtigung_mandate' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier.parlamentarier_anhang' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'partei.parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung.mandat' => new DataSourceSecurityInfo(false, false, false, false))
    );

$appGrants = array('guest' => new DataSourceSecurityInfo(false, false, false, false),
    'roland' => new AdminDataSourceSecurityInfo(),
    'bane' => new AdminDataSourceSecurityInfo(),
    'rebecca' => new DataSourceSecurityInfo(true, true, true, true),
    'otto' => new DataSourceSecurityInfo(true, true, true, true),
    'thomas' => new DataSourceSecurityInfo(true, true, true, true),
    'admin' => new AdminDataSourceSecurityInfo());

$tableCaptions = array('branche' => 'Branche',
'branche.interessengruppe' => 'Branche.Interessengruppe',
'branche.organisation' => 'Branche.Organisation',
'in_kommission' => 'In Kommission',
'interessenbindung' => 'Interessenbindung',
'interessengruppe' => 'Interessengruppe',
'interessengruppe.organisation' => 'Interessengruppe.Organisation',
'interessengruppe.parlamentarier' => 'Interessengruppe.Parlamentarier',
'interessengruppe.zugangsberechtigung' => 'Interessengruppe.Zugangsberechtigung',
'kommission' => 'Kommission',
'kommission.branche' => 'Kommission.Branche',
'kommission.in_kommission' => 'Kommission.In Kommission',
'mandat' => 'Mandat',
'organisation' => 'Organisation',
'organisation.interessenbindung' => 'Organisation.Interessenbindung',
'organisation.mandat' => 'Organisation.Mandat',
'organisation.organisation_beziehung' => 'Organisation.Organisation Beziehung',
'organisation.zugangsberechtigung' => 'Organisation.Zugangsberechtigung',
'organisation_beziehung' => 'Organisation Beziehung',
'parlamentarier' => 'Parlamentarier',
'parlamentarier.v_zugangsberechtigung_mit_mandaten' => 'Parlamentarier.V Zugangsberechtigung Mit Mandaten',
'parlamentarier.v_interessenbindung_liste' => 'Parlamentarier.V Interessenbindung Liste',
'parlamentarier.v_in_kommission_liste' => 'Parlamentarier.V In Kommission Liste',
'parlamentarier.zugangsberechtigung' => 'Parlamentarier.Zugangsberechtigung',
'parlamentarier.v_zugangsberechtigung_mandate' => 'Parlamentarier.V Zugangsberechtigung Mandate',
'parlamentarier.parlamentarier_anhang' => 'Parlamentarier.Parlamentarier Anhang',
'partei' => 'Partei',
'partei.parlamentarier' => 'Partei.Parlamentarier',
'zugangsberechtigung' => 'Zugangsberechtigung',
'zugangsberechtigung.mandat' => 'Zugangsberechtigung.Mandat');

function SetUpUserAuthorization()
{
    global $usersIds;
    global $grants;
    global $appGrants;
    global $dataSourceRecordPermissions;
    $userAuthorizationStrategy = new HardCodedUserAuthorization(new HardCodedUserGrantsManager($grants, $appGrants), $usersIds);
    GetApplication()->SetUserAuthorizationStrategy($userAuthorizationStrategy);

GetApplication()->SetDataSourceRecordPermissionRetrieveStrategy(
    new HardCodedDataSourceRecordPermissionRetrieveStrategy($dataSourceRecordPermissions));
}

function GetIdentityCheckStrategy()
{
    global $users;
    return new SimpleIdentityCheckStrategy($users, 'md5');
}
