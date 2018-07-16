<?php

include_once dirname(__FILE__) . '/../permission_set.php';

abstract class UserGrantManager
{
    /**
     * @param string $userName
     * @param string $dataSourceName
     * @return IPermissionSet
     */
    public abstract function GetPermissionSet($userName, $dataSourceName);

    /**
     * @abstract
     * @param string $userName
     * @return boolean
     */
    public abstract function HasAdminGrant($userName);

    /**
     * @abstract
     * @param string $userName
     * @return boolean
     */
    public abstract function HasAdminPanel($userName);

    /**
     * @abstract
     * @param string $userName
     * @return array
     */
    public abstract function getAdminDatasources($userName);
}

class NullUserGrantManager extends UserGrantManager
{
    /** @inheritdoc */
    public function GetPermissionSet($userName, $dataSourceName) {
        return new AdminPermissionSet();
    }

    /** @inheritdoc */
    public function HasAdminGrant($userName) {
        return false;
    }

    /** @inheritdoc */
    public function HasAdminPanel($userName) {
        return false;
    }

    /** @inheritdoc */
    public function getAdminDatasources($userName) {
        return array();
    }
}
