<?php

include_once dirname(__FILE__) . '/' . 'record_level_permissions.php';

class HardCodedDataSourceRecordPermissionRetrieveStrategy
{
    /**
     * @var DataSourceRecordPermission[]
     */
    private $recordPermissionCheckers;

    /**
     * @param DataSourceRecordPermission[] $recordPermissionCheckers
     */
    public function __construct($recordPermissionCheckers)
    {
        $this->recordPermissionCheckers = $recordPermissionCheckers;
    }

    /**
     * @param string $dataSourceName
     * @param int $userId
     * @return null|UserDataSourceRecordPermissions
     */
    public function GetUserRecordPermissionsForDataSource($dataSourceName, $userId)
    {
        if (isset($this->recordPermissionCheckers[$dataSourceName]))
            return new UserDataSourceRecordPermissions($userId, $this->recordPermissionCheckers[$dataSourceName]);
        else
            return null;
    }
}

class NullDataSourceRecordPermissionRetrieveStrategy extends HardCodedDataSourceRecordPermissionRetrieveStrategy
{
    /**
     * {@inheritdoc}
     */
    public function GetUserRecordPermissionsForDataSource($dataSourceName, $userId)
    {
        return new NullUserDataSourceRecordPermissions();
    }

    public function __construct() {
        parent::__construct(array());
    }
}
