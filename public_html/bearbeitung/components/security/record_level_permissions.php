<?php

include_once dirname(__FILE__) . '/' . '../dataset/dataset.php';

interface IRecordPermissions
{
    /**
     * @param IDataset $dataset
     * @return bool
     */
    public function HasViewGrant(IDataset $dataset);

    /**
     * @param IDataset $dataset
     * @return bool
     */
    public function HasEditGrant(IDataset $dataset);

    /**
     * @param IDataset $dataset
     * @return bool
     */
    public function HasDeleteGrant(IDataset $dataset);

    /**
     * @return bool
     */
    public function CanAllUsersViewRecords();
}

class AdminRecordPermissions implements IRecordPermissions
{
    /**
     * {@inheritdoc}
     */
    public function HasViewGrant(IDataset $dataset)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function HasEditGrant(IDataset $dataset)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function HasDeleteGrant(IDataset $dataset)
    { 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function CanAllUsersViewRecords()
    {
        return true;
    }
}

class UserDataSourceRecordPermissions implements IRecordPermissions
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var DataSourceRecordPermission
     */
    private $permissionsChecker;

    /**
     * @param int $userId
     * @param DataSourceRecordPermission $permissionsChecker
     */
    public function __construct($userId, DataSourceRecordPermission $permissionsChecker)
    {
        $this->userId = $userId;
        $this->permissionsChecker = $permissionsChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function HasViewGrant(IDataset $dataset)
    {
        return $this->permissionsChecker->HasViewGrant($dataset, $this->userId);
    }

    /**
     * {@inheritdoc}
     */
    public function HasEditGrant(IDataset $dataset)
    {
        return $this->permissionsChecker->HasEditGrant($dataset, $this->userId);
    }

    /**
     * {@inheritdoc}
     */
    public function HasDeleteGrant(IDataset $dataset)
    {
        return $this->permissionsChecker->HasDeleteGrant($dataset, $this->userId);
    }

    /**
     * {@inheritdoc}
     */
    public function CanAllUsersViewRecords()
    {
        return $this->permissionsChecker->CanAllUsersViewRecords();
    }
}

class NullUserDataSourceRecordPermissions implements IRecordPermissions
{
    /**
     * {@inheritdoc}
     */
    public function HasViewGrant(IDataset $dataset)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function HasEditGrant(IDataset $dataset)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function HasDeleteGrant(IDataset $dataset)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function CanAllUsersViewRecords()
    {
        return true;
    }
}

class DataSourceRecordPermission
{
    /**
     * @var bool
     */
    private $canAllView, $canAllDelete, $canAllEdit;

    /**
     * @var bool
     */
    private $canOwnerView, $canOwnerDelete, $canOwnerEdit;

    /**
     * @var string
     */
    private $ownerIdField;

    /**
     * @param string $ownerIdField
     * @param bool $canAllView
     * @param bool $canAllDelete
     * @param bool $canAllEdit
     * @param bool $canOwnerView
     * @param bool $canOwnerDelete
     * @param bool $canOwnerEdit
     */
    public function __construct($ownerIdField, $canAllView, $canAllDelete, $canAllEdit, 
        $canOwnerView, $canOwnerDelete, $canOwnerEdit)
    {
        $this->ownerIdField = $ownerIdField;
        $this->canAllView = $canAllView;
        $this->canAllDelete = $canAllDelete;
        $this->canAllEdit = $canAllEdit;
        $this->canOwnerView = $canOwnerView;
        $this->canOwnerDelete = $canOwnerDelete;
        $this->canOwnerEdit = $canOwnerEdit;
    }

    /**
     * @param IDataset $dataset
     * @param int $userId
     * @return bool
     */
    public function HasEditGrant(IDataset $dataset, $userId)
    {
        return $this->IsRecordOwner($dataset, $userId) ? $this->canOwnerEdit : $this->canAllEdit;
    }

    /**
     * @param IDataset $dataset
     * @param int $userId
     * @return bool
     */
    public function HasViewGrant(IDataset $dataset, $userId)
    {
        return $this->IsRecordOwner($dataset, $userId) ? $this->canOwnerView : $this->canAllView;
    }

    /**
     * @param IDataset $dataset
     * @param int $userId
     * @return bool
     */
    public function HasDeleteGrant(IDataset $dataset, $userId)
    { 
        return $this->IsRecordOwner($dataset, $userId) ? $this->canOwnerDelete : $this->canAllDelete;
    }

    /**
     * @return bool
     */
    public function CanAllUsersViewRecords()
    {
        return $this->canAllView;
    }

    /**
     * @param IDataset $dataset
     * @param int $userId
     * @return bool
     */
    private function IsRecordOwner(IDataset $dataset, $userId)
    {
        return $dataset->GetFieldValueByName($this->ownerIdField) == $userId;
    }

}

