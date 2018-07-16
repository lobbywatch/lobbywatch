<?php

include_once dirname(__FILE__) . '/' . 'user_grant_manager.php';

class CompositeGrantManager extends UserGrantManager {
    /** @var UserGrantManager[] */
    private $grantManagers;

    public function __construct() {
        $this->grantManagers = array();
    }

    /**
     * @param UserGrantManager $grantsManager
     * @return void
     */
    public function AddGrantManager($grantsManager) {
        $this->grantManagers[] = $grantsManager;
    }

    /**
     * @inheritdoc
     */
    public function GetPermissionSet($userName, $dataSourceName) {
        $securityInfos = array();
        foreach ($this->grantManagers as $grantsManager)
            array_push($securityInfos, $grantsManager->GetPermissionSet($userName, $dataSourceName));
        return new CompositePermissionSet($securityInfos);
    }

    /**
     * @param string $userName
     * @return boolean
     */
    public function HasAdminGrant($userName) {
        foreach ($this->grantManagers as $grantsManager) {
            if ($grantsManager->HasAdminGrant($userName)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $userName
     * @return boolean
     */
    public function HasAdminPanel($userName) {
        foreach ($this->grantManagers as $grantsManager) {
            if ($grantsManager->HasAdminPanel($userName)) {
                return true;
            }
        }
        return false;
    }

    public function getAdminDatasources($userName)
    {
        $result = array();

        foreach ($this->grantsManager as $grantManager) {
            $result = array_merge($result, $grantManager->getAdminDatasources($userName));
        }

        return array_unique($result);
    }
}
