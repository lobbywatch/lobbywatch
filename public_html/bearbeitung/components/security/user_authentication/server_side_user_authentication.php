<?php

include_once dirname(__FILE__) . '/' . 'user_authentication.php';

class ServerSideUserAuthentication extends AbstractUserAuthentication
{
    /** @var string */
    private $guestServerLogin;
    /** @var string */
    private $guestServerPassword;
    /** @var ConnectionFactory */
    private $connectionFactory;
    /** @var array */
    private $connectionOptions;

    /**
     * @param UserIdentityStorage $identityStorage
     * @param boolean $guestAccessEnabled
     * @param StringHasher $hasher
     * @param ConnectionFactory $connectionFactory
     * @param array $connectionOptions
     * @param string $guestServerLogin
     * @param string $guestServerPassword
     */
    public function __construct($identityStorage, $guestAccessEnabled, $hasher, $connectionFactory, $connectionOptions, $guestServerLogin = '', $guestServerPassword = '')
    {
        parent::__construct($identityStorage, $guestAccessEnabled, $hasher);

        $this->connectionFactory = $connectionFactory;
        $this->connectionOptions = $connectionOptions;
        $this->guestServerLogin = $guestServerLogin;
        $this->guestServerPassword = $guestServerPassword;
    }

    /** @return string */
    public function getGuestServerLogin() {
        return $this->guestServerLogin;
    }

    /** @param string $guestServerLogin */
    public function setGuestServerLogin($guestServerLogin) {
        $this->guestServerLogin = $guestServerLogin;
    }

    /** @return string */
    public function getGuestServerPassword() {
        return $this->guestServerPassword;
    }

    /** @param string $guestServerPassword */
    public function setGuestServerPassword($guestServerPassword) {
        $this->guestServerPassword = $guestServerPassword;
    }

    /** @inheritdoc */
    public function applyIdentityToConnectionOptions(&$connectionOptions)
    {
        if ($this->getCurrentUserName() == 'guest') {
            if ($this->getGuestAccessEnabled()) {
                $connectionOptions['username'] = $this->guestServerLogin;
                $connectionOptions['password'] = $this->guestServerPassword;
            }
        } else {
            $identity = $this->getIdentityStorage()->getUserIdentity();
            $connectionOptions['username'] = $identity->userName;
            $connectionOptions['password'] = $identity->password;
        }
    }
    
    /** @inheritdoc */
    public function checkUserIdentity($username, $password)
    {
        $this->connectionOptions['username'] = $username;
        $this->connectionOptions['password'] = $password;

        $connection = $this->connectionFactory->CreateConnection($this->connectionOptions);
        try {
            $connection->Connect();
        } catch (SMSQLException $e) {
            return false;
        }

        $connection->Disconnect();
        return true;
    }

    public function saveUserIdentity(UserIdentity $userIdentity) {
        $this->getIdentityStorage()->SaveUserIdentity($userIdentity);
    }
}
