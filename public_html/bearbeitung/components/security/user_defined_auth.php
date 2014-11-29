<?php

include_once dirname(__FILE__) . '/' . 'base_user_auth.php';
include_once dirname(__FILE__) . '/' . '../../components/utils/event.php';

class UserDefinedAuthorization extends AbstractUserAuthorization
{
    public function GetCurrentUserId() { return null; }

    public function GetCurrentUser() { return GetCurrentUser(); }

    public function IsCurrentUserLoggedIn() { return $this->GetCurrentUser() != 'guest'; }

    public function GetUserRoles($userName, $dataSourceName)
    {
        if ($userName == 'guest')
            $result = new DataSourceSecurityInfo(false, false, false, false);
        else
            $result = new DataSourceSecurityInfo(true, true, true, true);

        return $result;
    }

    public function HasAdminGrant($userName)
    {
        return false;
    }
}

class UserDefinedIdentityCheckStrategy extends IdentityCheckStrategy
{
    #region Events
    /** @var Event */
    public $OnCheckUserNameAndPasswordIdentity;
    #endregion

    public function __construct()
    {
        $this->OnCheckUserNameAndPasswordIdentity = new Event();
    }

    public function CheckUsernameAndEncryptedPassword($username, $password) {
        return $this->CheckUsernameAndPassword($username, $password, $errorMessage);
    }

    public function GetEncryptedPassword($plainPassword) {
        return $plainPassword;
    }

    public function CheckUsernameAndPassword($username, $password, &$errorMessage)
    {
        $result = true;
        $this->OnCheckUserNameAndPasswordIdentity->Fire(array($username, $password, &$result));
        return $result;
    }
}
