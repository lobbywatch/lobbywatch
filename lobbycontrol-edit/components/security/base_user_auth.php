<?php

include_once dirname(__FILE__) . '/' . 'datasource_security_info.php';
include_once dirname(__FILE__) . '/' . '../utils/hash_utils.php';

abstract class AbstractUserAuthorization
{
    /**
     * @return int
     */
    public abstract function GetCurrentUserId();

    /**
     * @return string|null
     */
    public abstract function GetCurrentUser();

    /**
     * @return bool
     */
    public abstract function IsCurrentUserLoggedIn();

    /**
     * @param string $userName
     * @param string $dataSourceName
     * @return IDataSourceSecurityInfo
     */
    public abstract function GetUserRoles($userName, $dataSourceName);

    /**
     * @param string $userName
     * @return bool
     */
    public abstract function HasAdminGrant($userName);

    /**
     * @param array $connectionOptions see GetGlobalConnectionOptions
     */
    public function ApplyIdentityToConnectionOptions(&$connectionOptions) { }
}

class NullUserAuthorization extends AbstractUserAuthorization
{
    public function GetCurrentUser()
    {
        return null; 
    }
    
    public function GetUserRoles($userName, $dataSourceName)
    {
        return new AdminDataSourceSecurityInfo();
    } 
    
    public function IsCurrentUserLoggedIn() { 
        return false; 
    }

    public function GetCurrentUserId()
    {
        return 0; 
    }    

    public function HasAdminGrant($userName)
    {
        return false;
    }

    public function ApplyIdentityToConnectionOptions(&$connectionOptions) { }
}

abstract class IdentityCheckStrategy
{
    public function ApplyIdentityToConnectionOptions($connectionOptions) { }

    /**
     * @param string $username
     * @param string $password
     * @param string $errorMessage
     * @return bool
     */
    public abstract function CheckUsernameAndPassword($username, $password, &$errorMessage);

    public abstract function CheckUsernameAndEncryptedPassword($username, $password);

    public abstract function GetEncryptedPassword($plainPassword);
}
