<?php

include_once dirname(__FILE__) . '/' . 'user_authentication.php';
include_once dirname(__FILE__) . '/' . '../table_based_user_manager.php';
include_once dirname(__FILE__) . '/' . '../../common_utils.php';

class TableBasedUserAuthentication extends AbstractUserAuthentication
{
    /** @var TableBasedUserManager */
    private $userManager;
    /** @var boolean */
    private $allowUserChangePassword;
    /** @var boolean */
    private $selfRegistrationEnabled;
    /** @var boolean */
    private $recoveringPasswordEnabled;
    /** @var int */
    private $cachedCurrentUserId;

    /**
     * @param UserIdentityStorage $identityStorage
     * @param boolean $guestAccessEnabled
     * @param StringHasher $hasher
     * @param TableBasedUserManager $userManager
     * @param boolean $allowUserChangePassword
     * @param boolean $selfRegistrationEnabled
     * @param boolean $recoveringPasswordEnabled
     */
    public function __construct($identityStorage, $guestAccessEnabled, $hasher, $userManager,
        $allowUserChangePassword = false, $selfRegistrationEnabled = false, $recoveringPasswordEnabled = false)
    {
        parent::__construct($identityStorage, $guestAccessEnabled, $hasher);
        $this->userManager = $userManager;
        $this->allowUserChangePassword = $allowUserChangePassword;
        $this->selfRegistrationEnabled = $selfRegistrationEnabled;
        $this->recoveringPasswordEnabled = $recoveringPasswordEnabled;
    }

    /** @inheritdoc */
    public function getCurrentUserId() {
        if (!$this->isCurrentUserLoggedIn()) {
            return null;
        }

        if (is_null($this->cachedCurrentUserId)) {
            $this->cachedCurrentUserId = $this->userManager->getUserIdByName($this->getCurrentUserName());
        }

        return $this->cachedCurrentUserId;
    }

    /** @param boolean $value */
    public function setAllowUserChangePassword($value) {
        $this->allowUserChangePassword = $value;
    }

    /** @inheritdoc */
    public function canUserChangeOwnPassword() {
        return $this->allowUserChangePassword;
    }

    /** @inheritdoc */
    public function checkUserAccountVerified($username) {
        return $this->userManager->checkUserAccountVerified($username);
    }

    /** @inheritdoc */
    public function checkUserIdentity($username, $password) {
        $expectedPassword = $this->userManager->getUserPasswordByName($username);
        if (isset($expectedPassword)) {
            return $this->checkPassword($password, $expectedPassword);
        } else {
            return false;
        }
    }

    /** @inheritdoc */
    public function getSelfRegistrationEnabled() {
        return $this->selfRegistrationEnabled;
    }

    /**
     * @param boolean $value
    */
    public function setSelfRegistrationEnabled($value) {
        $this->selfRegistrationEnabled = $value;
    }

    /** @inheritdoc */
    public function getRecoveringPasswordEnabled() {
        return $this->recoveringPasswordEnabled;
    }

    /**
     * @param boolean $value
    */
    public function setRecoveringPasswordEnabled($value) {
        $this->recoveringPasswordEnabled = $value;
    }

}
