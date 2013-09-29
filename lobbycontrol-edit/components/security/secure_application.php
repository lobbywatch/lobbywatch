<?php

include_once dirname(__FILE__) . '/' . 'base_user_auth.php';
include_once dirname(__FILE__) . '/' . 'record_level_permissions_retrieve_strategy.php';
include_once dirname(__FILE__) . '/' . 'null_user_manager.php';

class SecureApplication
{
    /** @var AbstractUserAuthorization */
    private $userAuthorizationStrategy;

    /** @var HardCodedDataSourceRecordPermissionRetrieveStrategy */
    private $dataSourceRecordPermissionRetrieveStrategy;

    /** @var IUserManager */
    private $userManager;

    public function __construct()
    {
        $this->userAuthorizationStrategy = new NullUserAuthorization();
        $this->dataSourceRecordPermissionRetrieveStrategy = new NullDataSourceRecordPermissionRetrieveStrategy();
        $this->userManager = new NullUserManager();
    }

    public function SetUserAuthorizationStrategy(AbstractUserAuthorization $userAuthorizationStrategy = null)
    {
        $this->userAuthorizationStrategy = $userAuthorizationStrategy;
    }

    /**
     * @return AbstractUserAuthorization|null
     */
    public function GetUserAuthorizationStrategy()
    {
        return $this->userAuthorizationStrategy;
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

    public function SetUserManager(IUserManager $value)
    {
        $this->userManager = $value;
    }

    /**
     * @return IUserManager
     */
    public function GetUserManager()
    {
        return $this->userManager;
    }
}

