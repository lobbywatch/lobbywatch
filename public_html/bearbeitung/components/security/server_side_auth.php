<?php

include_once dirname(__FILE__) . '/' . 'datasource_security_info.php';
include_once dirname(__FILE__) . '/' . 'base_user_auth.php';
include_once dirname(__FILE__) . '/' . 'user_identity_storage/user_identity_storage.php';

class ServerSideUserAuthorization extends AbstractUserAuthorization
{
    private $rolesSecurityInfo;
    private $guestUserName;
    private $allowGuestAccess;
    private $guestServerLogin;
    private $guestServerPassword;

    /**
     * @param UserIdentityStorage $identityStorage
     * @param $rolesSecurityInfo
     * @param string $guestUserName
     * @param bool $allowGuestAccess
     * @param string $guestServerLogin
     * @param string $guestServerPassword
     */
    public function __construct(UserIdentityStorage $identityStorage, $rolesSecurityInfo, $guestUserName,
                                $allowGuestAccess, $guestServerLogin, $guestServerPassword)
    {
        parent::__construct($identityStorage);
        $this->rolesSecurityInfo = $rolesSecurityInfo;
        $this->guestUserName = $guestUserName;
        $this->allowGuestAccess = $allowGuestAccess;
        $this->guestServerLogin = $guestServerLogin;
        $this->guestServerPassword = $guestServerPassword;
    }
    
    public function GetCurrentUserId() { return null; }
    
    public function IsCurrentUserLoggedIn() { return $this->GetCurrentUser() != 'guest'; }
    
    public function GetUserRoles($userName, $dataSourceName)
    {
        if (($userName == $this->guestUserName) and (!$this->allowGuestAccess))
            $result = new DataSourceSecurityInfo(false, false, false, false);
        else
            $result = new DataSourceSecurityInfo(true, true, true, true);

        return $result;
    }
    
    public function ApplyIdentityToConnectionOptions(&$connectionOptions)
    {
        if ($this->GetCurrentUser() == $this->guestUserName) {
            if ($this->allowGuestAccess) {
                $connectionOptions['username'] = $this->guestServerLogin;
                $connectionOptions['password'] = $this->guestServerPassword;
            }
        } else {
            $identity = $this->getIdentityStorage()->getUserIdentity();
            $connectionOptions['username'] = $identity->userName;
            $connectionOptions['password'] = $identity->password;
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
