<?php
// Processed by afterburner.sh



require_once 'components/page.php';
require_once 'components/security/datasource_security_info.php';
require_once 'components/security/security_info.php';
require_once 'components/security/hardcoded_auth.php';
require_once 'components/security/user_grants_manager.php';

// Custom modification: Use $users form settings.php

$usersIds = array('otto' => -1, 'roland' => -1, 'rebecca' => -1, 'thomas' => -1, 'bane' => -1, 'demo' => -1, 'admin' => -1);

$dataSourceRecordPermissions = array();

$grants = array('guest' => 
        array('kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'defaultUser' => 
        array('kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'otto' => 
        array('kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'rkurmann' => 
        array('kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'rebecca' => 
        array('kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'thomas' => 
        array('kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'bane' => 
        array('kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'roland' => 
        array('kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'demo' => 
        array('kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'admin' => 
        array('kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'in_kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation_beziehung' => new DataSourceSecurityInfo(false, false, false, false))
    );

$appGrants = array('guest' => new DataSourceSecurityInfo(GetApplication()->GetOperation() === 'rss', false, false, false),
    'defaultUser' => new DataSourceSecurityInfo(true, false, false, false),
    'otto' => new DataSourceSecurityInfo(true, true, true, true),
    'rkurmann' => new DataSourceSecurityInfo(true, true, true, true),
    'rebecca' => new DataSourceSecurityInfo(true, true, true, true),
    'thomas' => new DataSourceSecurityInfo(true, true, true, true),
    'bane' => new DataSourceSecurityInfo(true, true, true, true),
    'roland' => new AdminDataSourceSecurityInfo(),
    'demo' => new DataSourceSecurityInfo(true, false, false, false),
    'admin' => new AdminDataSourceSecurityInfo());

$tableCaptions = array('kommission' => 'Kommission',
'in_kommission' => 'In Kommission',
'parlamentarier' => 'Parlamentarier',
'interessengruppe' => 'Interessengruppe',
'branche' => 'Branche',
'partei' => 'Partei',
'zugangsberechtigung' => 'Zugangsberechtigung',
'interessenbindung' => 'Interessenbindung',
'mandat' => 'Mandat',
'organisation' => 'Organisation',
'organisation_beziehung' => 'Organisation Beziehung');

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
