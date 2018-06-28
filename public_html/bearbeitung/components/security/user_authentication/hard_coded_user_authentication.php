<?php

include_once dirname(__FILE__) . '/' . 'user_authentication.php';

class HardCodedUserAuthentication extends AbstractUserAuthentication
{
    /** @var array */
    private $usersInfo;

    /**
     * @param UserIdentityStorage $identityStorage
     * @param boolean $guestAccessEnabled
     * @param StringHasher $hasher
     * @param array $usersInfo
     */
    public function __construct($identityStorage, $guestAccessEnabled, $hasher, $usersInfo)
    {
        parent::__construct($identityStorage, $guestAccessEnabled, $hasher);
        $this->usersInfo = $usersInfo;
    }

    /** @inheritdoc */
    public function checkUserIdentity($username, $password) {
        return isset($this->usersInfo[$username]) && $this->checkPassword($password, $this->usersInfo[$username]);
    }
}
