<?php
// Processed by afterburner.sh

require_once 'components/page.php';
require_once 'components/security/datasource_security_info.php';
require_once 'components/security/security_info.php';
require_once 'components/security/hardcoded_auth.php';
require_once 'components/security/user_grants_manager.php';

// Custom modification: Use $users form settings.php

$usersIds = array('otto' => -1, 'rkurmann' => -1, 'rebecca' => -1, 'thomas' => -1, 'bane' => -1, 'admin' => -1);

$dataSourceRecordPermissions = array();

$grants = array('guest' => 
        array()
    ,
    'defaultUser' => 
        array('interessenbindungen' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigungen' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbyorganisationen' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbygruppen' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbytypen' => new DataSourceSecurityInfo(false, false, false, false),
        'kommissionen' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'otto' => 
        array('interessenbindungen' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigungen' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbyorganisationen' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbygruppen' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbytypen' => new DataSourceSecurityInfo(false, false, false, false),
        'kommissionen' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'rkurmann' => 
        array('interessenbindungen' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigungen' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbyorganisationen' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbygruppen' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbytypen' => new DataSourceSecurityInfo(false, false, false, false),
        'kommissionen' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'rebecca' => 
        array('interessenbindungen' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigungen' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbyorganisationen' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbygruppen' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbytypen' => new DataSourceSecurityInfo(false, false, false, false),
        'kommissionen' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'thomas' => 
        array('interessenbindungen' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigungen' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbyorganisationen' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbygruppen' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbytypen' => new DataSourceSecurityInfo(false, false, false, false),
        'kommissionen' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'bane' => 
        array('interessenbindungen' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigungen' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbyorganisationen' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbygruppen' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbytypen' => new DataSourceSecurityInfo(false, false, false, false),
        'kommissionen' => new DataSourceSecurityInfo(false, false, false, false))
    ,
    'admin' => 
        array('interessenbindungen' => new DataSourceSecurityInfo(false, false, false, false),
        'zugangsberechtigungen' => new DataSourceSecurityInfo(false, false, false, false),
        'parlamentarier' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbyorganisationen' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbygruppen' => new DataSourceSecurityInfo(false, false, false, false),
        'lobbytypen' => new DataSourceSecurityInfo(false, false, false, false),
        'kommissionen' => new DataSourceSecurityInfo(false, false, false, false))
    );

$appGrants = array('guest' => new DataSourceSecurityInfo(false, false, false, false),
    'defaultUser' => new DataSourceSecurityInfo(true, false, false, false),
    'otto' => new DataSourceSecurityInfo(true, true, true, true),
    'rkurmann' => new DataSourceSecurityInfo(true, true, true, true),
    'rebecca' => new DataSourceSecurityInfo(true, true, true, true),
    'thomas' => new DataSourceSecurityInfo(true, true, true, true),
    'bane' => new AdminDataSourceSecurityInfo(),
    'admin' => new AdminDataSourceSecurityInfo());

$tableCaptions = array('interessenbindungen' => 'Interessenbindungen',
'zugangsberechtigungen' => 'Zugangsberechtigungen',
'parlamentarier' => 'Parlamentarier',
'lobbyorganisationen' => 'Lobbyorganisationen',
'lobbygruppen' => 'Lobbygruppen',
'lobbytypen' => 'Lobbytypen',
'kommissionen' => 'Kommissionen');

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




