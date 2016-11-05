<?php

include_once dirname(__FILE__) . '/' . 'datasource_security_info.php';
include_once dirname(__FILE__) . '/' . 'grant_manager/user_grant_manager.php';

class HardCodedUserAuthorization extends AbstractUserAuthorization {
    private $grantsManager;
    private $userIds;

    public function __construct(
        UserIdentityStorage $identityStorage,
        UserGrantManager $grantsManager,
        $userIds)
    {
        parent::__construct($identityStorage);
        $this->grantsManager = $grantsManager;
        $this->userIds = $userIds;
    }

    public function GetCurrentUserId() {
        if (isset($this->userIds[$this->GetCurrentUser()]))
            return $this->userIds[$this->GetCurrentUser()];
        else
            return null;
    }

    public function HasAdminGrant($userName) {
        return $this->grantsManager->HasAdminGrant($userName);
    }

    public function HasAdminPanel($userName)
    {
        return false;
    }

    public function IsCurrentUserLoggedIn() {
        return $this->GetCurrentUser() != 'guest';
    }

    public function GetUserRoles($userName, $dataSourceName) {
        return $this->grantsManager->GetSecurityInfo($userName, $dataSourceName);
    }
}

class SimpleIdentityCheckStrategy extends IdentityCheckStrategy {

    private $userInfos;
    /**
     * @var StringHasher
     */
    private $passwordHasher;

    public function __construct($userInfos, $passwordEncryption = '') {
        $this->userInfos = $userInfos;
        $this->passwordHasher = HashUtils::CreateHasher($passwordEncryption);
    }

    private function CheckPasswordEquals($actualPassword, $expectedPassword) {
        return $this->passwordHasher->CompareHash($expectedPassword, $actualPassword);
    }

    private function CheckHashedPasswordEquals($actualPassword, $expectedPassword) {
        return $this->passwordHasher->CompareTwoHashes($expectedPassword, $actualPassword);
    }

    public function CheckUsernameAndPassword($username, $password) {
        return isset($this->userInfos[$username]) && $this->CheckPasswordEquals($password, $this->userInfos[$username]);
    }

    public function CheckUsernameAndEncryptedPassword($username, $hashedPassword) {
        return  (isset($this->userInfos[$username]) && $this->CheckHashedPasswordEquals($hashedPassword, $this->userInfos[$username]));
    }

    public function GetEncryptedPassword($plainPassword) {
        return $this->passwordHasher->GetHash($plainPassword);
    }
}
