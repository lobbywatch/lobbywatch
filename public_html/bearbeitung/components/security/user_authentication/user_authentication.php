<?php

include_once dirname(__FILE__) . '/' . '../permission_set.php';
include_once dirname(__FILE__) . '/' . '../../utils/hash_utils.php';
include_once dirname(__FILE__) . '/' . '../user_identity_storage/user_identity_storage.php';
include_once dirname(__FILE__) . '/' . '../user_identity_storage/user_identity_array_storage.php';

abstract class AbstractUserAuthentication
{
    /** @var UserIdentityStorage  */
    private $identityStorage;
    /** @var boolean  */
    private $guestAccessEnabled;
    /** @var StringHasher */
    private $hasher;

    /**
     * @param UserIdentityStorage $identityStorage
     * @param boolean $guestAccessEnabled
     * @param StringHasher $hasher
     */
    public function __construct(UserIdentityStorage $identityStorage, $guestAccessEnabled = false, $hasher = null) {
        $this->identityStorage = $identityStorage;
        $this->guestAccessEnabled = $guestAccessEnabled;
        $this->hasher = $hasher;
    }

    /** @return UserIdentityStorage */
    public function getIdentityStorage() {
        return $this->identityStorage;
    }

    /** @return boolean */
    public function getGuestAccessEnabled() {
        return $this->guestAccessEnabled;
    }

    /** @param boolean $value */
    public function setGuestAccessEnabled($value) {
        $this->guestAccessEnabled = $value;
    }

    /** @return int|null */
    public function getCurrentUserId() {
        return null;
    }

    /** @return string */
    public function getCurrentUserName()
    {
        $identity = $this->identityStorage->getUserIdentity();
        if (is_null($identity)) {
            return 'guest';
        }

        return $identity->userName;
    }

    /** @return boolean */
    public function isCurrentUserLoggedIn()
    {
        return $this->getCurrentUserName() != 'guest';
    }

    /** @return boolean */
    public function canUserChangeOwnPassword() {
        return false;
    }


    /** @param array $connectionOptions */
    public function applyIdentityToConnectionOptions(&$connectionOptions) { }

    /**
     * @param string $username
     * @return boolean
     */
    public function checkUserAccountVerified($username) {
        return true;
    }

    /**
     * @param string $username
     * @param string $password
     * @return boolean
     */
    public abstract function checkUserIdentity($username, $password);

    /**
     * @param UserIdentity $userIdentity
     */
    public function saveUserIdentity(UserIdentity $userIdentity) {
        $identityToSave = clone $userIdentity;
        $identityToSave->password = '';
        $this->identityStorage->SaveUserIdentity($identityToSave);
    }

    /**
     * @param string $actualPassword
     * @param string $expectedPassword
     * @return boolean
     */
    protected function checkPassword($actualPassword, $expectedPassword) {
        return $this->hasher->CompareHash($expectedPassword, $actualPassword);
    }

    /** @return boolean */
    public function getSelfRegistrationEnabled() {
        return false;
    }

    /** @return boolean */
    public function getRecoveringPasswordEnabled() {
        return false;
    }

}

class NullUserAuthentication extends AbstractUserAuthentication
{
    public function __construct() {
        parent::__construct(new UserIdentityArrayStorage());
    }

    /** @inheritdoc */
    public function getCurrentUserName() {
        return '';
    }

    /** @inheritdoc */
    public function checkUserIdentity($username, $password) {
        return true;
    }
}
