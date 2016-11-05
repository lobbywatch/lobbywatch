<?php

include_once dirname(__FILE__) . '/../datasource_security_info.php';

abstract class UserGrantManager
{
    /**
     * @param string $userName
     * @param string $dataSourceName
     * @return IDataSourceSecurityInfo
     */
    public abstract function GetSecurityInfo($userName, $dataSourceName);

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
