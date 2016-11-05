<?php

include_once dirname(__FILE__) . '/' . 'base_user_auth.php';
include_once dirname(__FILE__) . '/' . '../../components/utils/event.php';
include_once dirname(__FILE__) . '/' . 'user_identity_storage/user_identity_storage.php';

class UserDefinedAuthorization extends AbstractUserAuthorization
{
    public function GetCurrentUserId() { return null; }

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

    public function HasAdminPanel($userName)
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
        return $this->CheckUsernameAndPassword($username, $password);
    }

    public function GetEncryptedPassword($plainPassword) {
        return $plainPassword;
    }

    public function CheckUsernameAndPassword($username, $password)
    {
        $result = true;
        $this->OnCheckUserNameAndPasswordIdentity->Fire(array($username, $password, &$result));
        return $result;
    }
}
