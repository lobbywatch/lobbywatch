<?php

include_once dirname(__FILE__) . '/' . 'user_authentication.php';

class UserDefinedUserAuthentication extends AbstractUserAuthentication
{
    /** @var Event */
    public $OnCheckUserIdentity;

    /** @inheritdoc */
    public function __construct($identityStorage, $guestAccessEnabled, $hasher)
    {
        parent::__construct($identityStorage, $guestAccessEnabled, $hasher);
        $this->OnCheckUserIdentity = new Event();
    }

    /** @inheritdoc */
    public function checkUserIdentity($username, $password) {
        $result = true;
        $this->OnCheckUserIdentity->Fire(array($username, $password, &$result));
        return $result;
    }
}
