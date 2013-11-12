<?php
// Processed by afterburner.sh



require_once 'components/page.php';
require_once 'components/security/datasource_security_info.php';
require_once 'components/security/security_info.php';
require_once 'components/security/hardcoded_auth.php';
require_once 'components/security/user_grants_manager.php';

// Custom modification: Use $users form settings.php

$usersIds = array('otto' => -1, 'roland' => -1, 'rebecca' => -1, 'thomas' => -1, 'bane' => -1, 'admin' => -1);

$dataSourceRecordPermissions = array();

$grants = array('guest' => 
        array()
    ,
    'defaultUser' => 
        array('parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'otto' => 
        array('parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'rkurmann' => 
        array('parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'rebecca' => 
        array('parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'thomas' => 
        array('parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'bane' => 
        array('parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'roland' => 
        array('parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'admin' => 
        array('parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'interessengruppe' => new DataSourceSecurityInfo(false, false, false, false),
        'branche' => new DataSourceSecurityInfo(false, false, false, false),
        'kommission' => new DataSourceSecurityInfo(false, false, false, false),
        'partei' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigung' => new DataSourceSecurityInfo(false, false, false, false),
        'interessenbindung' => new DataSourceSecurityInfo(false, false, false, false),
        'mandat' => new DataSourceSecurityInfo(false, false, false, false),
        'organisation' => new DataSourceSecurityInfo(false, false, false, false))
    );

$appGrants = array('guest' => new DataSourceSecurityInfo(false, false, false, false),
    'defaultUser' => new DataSourceSecurityInfo(true, false, false, false),
    'otto' => new DataSourceSecurityInfo(true, true, true, true),
    'rkurmann' => new DataSourceSecurityInfo(true, true, true, true),
    'rebecca' => new DataSourceSecurityInfo(true, true, true, true),
    'thomas' => new DataSourceSecurityInfo(true, true, true, true),
    'bane' => new AdminDataSourceSecurityInfo(),
    'roland' => new DataSourceSecurityInfo(true, true, true, true),
    'admin' => new AdminDataSourceSecurityInfo());

$tableCaptions = array('parlamentarier' => 'Parlamentarier',
'interessengruppe' => 'Interessengruppe',
'branche' => 'Branche',
'kommission' => 'Kommission',
'partei' => 'Partei',
'zugangsberechtigung' => 'Zugangsberechtigung',
'interessenbindung' => 'Interessenbindung',
'mandat' => 'Mandat',
'organisation' => 'Organisation');

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




