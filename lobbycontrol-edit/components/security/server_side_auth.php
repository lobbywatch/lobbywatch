<?php

include_once dirname(__FILE__) . '/' . 'datasource_security_info.php';
include_once dirname(__FILE__) . '/' . 'base_user_auth.php';
include_once dirname(__FILE__) . '/' . 'user_identity_cookie_storage.php';

class ServerSideUserAuthorization extends AbstractUserAuthorization
{
    private $rolesSecurityInfo;
    private $allRoles;
    private $guestUserName;
    private $allowGuestAccess;
    private $guestServerLogin;
    private $guestServerPassword;
    
    public function __construct($rolesSecurityInfo, $guestUserName, $allowGuestAccess, $guestServerLogin, $guestServerPassword)
    {
        $this->rolesSecurityInfo = $rolesSecurityInfo;
        $this->allRoles = new DataSourceSecurityInfo(true, true, true, true);
        $this->guestUserName = $guestUserName;
        $this->allowGuestAccess = $allowGuestAccess;
        $this->guestServerLogin = $guestServerLogin;
        $this->guestServerPassword = $guestServerPassword;
    }
    
    public function GetCurrentUserId() { return null; }
    
    public function GetCurrentUser() { return GetCurrentUser(); }
    public function IsCurrentUserLoggedIn() { return $this->GetCurrentUser() != 'guest'; }
    
    public function GetUserRoles($userName, $dataSourceName)
    {
        return $this->allRoles;
    }
    
    public function ApplyIdentityToConnectionOptions(&$connectionOptions)
    {
        if ($this->GetCurrentUser() == $this->guestUserName)
        {
            if ($this->allowGuestAccess)
            {
                $connectionOptions['username'] = $this->guestServerLogin;
                $connectionOptions['password'] = $this->guestServerPassword;
            }
            else
                RaiseError(GetCaptions()->GetMessageString('GuestAccessDenied'));
        }
        else
        {
            $connectionOptions['username'] = $this->GetCurrentUser();
            $connectionOptions['password'] = $_COOKIE[UserIdentityCookieStorage::passwordCookie];
        }
    }

    public function HasAdminGrant($userName)
    {
        return false;
    }
}

class ServerSideIdentityCheckStrategy extends IdentityCheckStrategy
{
    /**
     * @var ConnectionFactory
     */
    private $connectionFactory;
    /**
     * @var array
     */
    private $connectionOptions;

    public function __construct(ConnectionFactory $connectionFactory, $connectionOptions)
    {
        $this->connectionFactory = $connectionFactory;
        $this->connectionOptions = $connectionOptions;
    }

    public function CheckUsernameAndEncryptedPassword($username, $password) {
        return $this->CheckUsernameAndPassword($username, $password, $errorMessage);
    }

    public function GetEncryptedPassword($plainPassword) {
        return $plainPassword;
    }

    public function CheckUsernameAndPassword($username, $password, &$errorMessage)
    {
        $this->connectionOptions['username'] = $username;
        $this->connectionOptions['password'] = $password;
        
        $connection = $this->connectionFactory->CreateConnection($this->connectionOptions);
        $connection->Connect();
        if ($connection->Connected())
        {
            $errorMessage = null;
            $connection->Disconnect();
            return true;            
        }
        else
        {   
            $errorMessage = $connection->LastError();//'The username/password combination you entered was invalid.';
            return false;
        }
    }
}
