<?php

include_once dirname(__FILE__) . '/' . 'user_authentication/user_authentication.php';
include_once dirname(__FILE__) . '/' . 'grant_manager/user_grant_manager.php';
include_once dirname(__FILE__) . '/' . 'record_level_permissions_retrieve_strategy.php';
include_once dirname(__FILE__) . '/' . 'null_user_manager.php';

class SecureApplication
{
    /** @var AbstractUserAuthentication */
    private $userAuthentication;
    /** @var UserGrantManager */
    private $userGrantManager;
    /** @var HardCodedDataSourceRecordPermissionRetrieveStrategy */
    private $dataSourceRecordPermissionRetrieveStrategy;

    public function __construct()
    {
        $this->userAuthentication = new NullUserAuthentication();
        $this->userGrantManager = new NullUserGrantManager();
        $this->dataSourceRecordPermissionRetrieveStrategy = new NullDataSourceRecordPermissionRetrieveStrategy();
    }

    /** @param AbstractUserAuthentication $userAuthentication */
    public function SetUserAuthentication($userAuthentication)
    {
        $this->userAuthentication = $userAuthentication;
    }

    /** @return AbstractUserAuthentication */
    public function GetUserAuthentication()
    {
        return $this->userAuthentication;
    }

    /**
     * @param UserGrantManager $userGrantManager
     */
    public function SetUserGrantManager(UserGrantManager $userGrantManager) {
        $this->userGrantManager = $userGrantManager;
    }

    /**
     * @return UserGrantManager
     */
    public function GetUserGrantManager()
    {
        return $this->userGrantManager;
    }

    public function SetDataSourceRecordPermissionRetrieveStrategy(
        HardCodedDataSourceRecordPermissionRetrieveStrategy $value = null)
    {
        $this->dataSourceRecordPermissionRetrieveStrategy = $value;
    }

    /**
     * @return HardCodedDataSourceRecordPermissionRetrieveStrategy|null
     */
    public function GetDataSourceRecordPermissionRetrieveStrategy()
    {
        return $this->dataSourceRecordPermissionRetrieveStrategy;
    }

    /** @return boolean */
    public function isAuthenticationEnabled() {
        return !($this->userAuthentication instanceof NullUserAuthentication);
    }

}
