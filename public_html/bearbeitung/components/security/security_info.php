<?php

include_once dirname(__FILE__) . '/' . 'base_user_auth.php';
include_once dirname(__FILE__) . '/' . 'record_level_permissions.php';
include_once dirname(__FILE__) . '/' . 'record_level_permissions_retrieve_strategy.php';
include_once dirname(__FILE__) . '/' . 'user_identity_cookie_storage.php';

#region Auth utils functions
// TODO : move to utils class

//$currentUser = null; Afterburned

function SetCurrentUser($userName)
{
    $_SESSION['user'] = $userName; /*afterburner*/ 
}

function GetCurrentUser()
{
    // TODO : use SuperGlobals
//    global $currentUser; Afterburned
    if (isset($_SESSION['user'])) // Afterburned
     return $_SESSION['user']; // Afterburned

    if (function_exists('GetIdentityCheckStrategy')) {
        $identityCheckStrategy = GetIdentityCheckStrategy();
        if (isset($identityCheckStrategy)) {
            $storage = new UserIdentityCookieStorage($identityCheckStrategy);
            $userIdentity = $storage->LoadUserIdentity();
            if ($userIdentity != null) {
                if ($identityCheckStrategy->CheckUsernameAndEncryptedPassword(
                        $userIdentity->userName, $userIdentity->password)) {
                    $currentUser = $userIdentity->userName;
                    return $currentUser;
                }
            }
        }
    }
    return 'guest';
}

// TODO : remove this function
function GetUserGrantInfo($username, $tableName)
{
    global $userGrants;
    if (isset($userGrants[$username]))
        if (isset($userGrants[$username][$tableName]))
            return $userGrants[$username][$tableName];
}

function GetCurrentUserGrantForDataSource($dataSourceName)
{
    return GetApplication()->GetCurrentUserGrants($dataSourceName);
}

function GetCurrentUserRecordPermissionsForDataSource($dataSourceName)
{
    return GetApplication()->GetCurrentUserRecordPermissionsForDataSource($dataSourceName);
}

#endregion
