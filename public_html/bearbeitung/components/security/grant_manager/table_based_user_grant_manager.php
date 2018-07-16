<?php

include_once dirname(__FILE__) . '/' . '../../../database_engine/engine.php';
include_once dirname(__FILE__) . '/' . '../../../database_engine/commands.php';
include_once dirname(__FILE__) . '/' . '../../../database_engine/select_command.php';

include_once dirname(__FILE__) . '/' . '../../dataset/dataset.php';
include_once dirname(__FILE__) . '/' . '../../dataset/query_dataset.php';

include_once dirname(__FILE__) . '/' . '../permission_set.php';
include_once dirname(__FILE__) . '/' . 'user_grant_manager.php';
include_once dirname(__FILE__) . '/' . '../user_manager.php';

include_once dirname(__FILE__) . '/' . '../../utils/hash_utils.php';

class TableBasedUserGrantManager extends UserGrantManager
{
    /** @var ConnectionFactory */
    private $connectionFactory;
    /** @var array */
    private $connectionOptions;

    /** @var string */
    private $usersTable;
    private $users_UserId;
    private $users_UserName;
    private $users_Password;

    private $userPermsTable;
    private $userPerms_UserId;
    private $userPerms_PageName;
    private $userPerms_Grant;

    private $securityCache = array();
    private $allowGuest = array();

    /**
     * @param ConnectionFactory $connectionFactory
     * @param array $connectionOptions
     * @param array $usersTableInfo
     * @param array $userPermsTableInfo
     * @param array $tableCaptions
     * @param bool $allowGuest
     */
    public function __construct($connectionFactory, $connectionOptions,
        $usersTableInfo, $userPermsTableInfo, $tableCaptions, $allowGuest = true)
    {
        $this->allowGuest = $allowGuest;

        $this->connectionFactory = $connectionFactory;
        $this->connectionOptions = $connectionOptions;

        $this->usersTable = $usersTableInfo['TableName'];
        $this->users_UserId = $usersTableInfo['UserId'];
        $this->users_UserName = $usersTableInfo['UserName'];
        $this->users_Password = $usersTableInfo['Password'];

        $this->userPermsTable = $userPermsTableInfo['TableName'];
        $this->userPerms_UserId = $userPermsTableInfo['UserId'];
        $this->userPerms_PageName = $userPermsTableInfo['PageName'];
        $this->userPerms_Grant = $userPermsTableInfo['Grant'];

        $this->tableCaptions = $tableCaptions;
        $this->adminGrantCache = array();
    }

    public function RetrieveSecurityInfo($userName, $includePublic = true)
    {
        $queryBuilder = new SelectCommand($this->connectionFactory->CreateEngCommandImp());

        $queryBuilder->AddField($this->usersTable, $this->users_UserId, FieldType::Number, 'users_user_id');
        $queryBuilder->AddField($this->usersTable, $this->users_UserName, FieldType::String, 'users_user_name');

        $queryBuilder->AddField($this->userPermsTable, $this->userPerms_UserId, FieldType::Number, 'userperms_user_id');
        $queryBuilder->AddField($this->userPermsTable, $this->userPerms_PageName, FieldType::String, 'userperms_pagename');
        $queryBuilder->AddField($this->userPermsTable, $this->userPerms_Grant, FieldType::String, 'userperms_grant');

        $queryBuilder->SetSourceTableName($this->userPermsTable);
        $queryBuilder->AddJoin(JoinKind::LeftOuter, $this->usersTable, 'userperms_user_id', $this->users_UserId);

        if ($userName == 'guest')
        {
            $queryBuilder->AddFieldFilter('userperms_user_id', new FieldFilter('-1', '='));
        }
        else if ($includePublic) {
            $queryBuilder->AddCompositeFieldFilter('OR',
                array(
                    'users_user_name',
                    'userperms_user_id'
                ),
                array(
                    new FieldFilter($userName, 'ILIKE'),
                    new FieldFilter('0', '=')
                ));
        } else {
            $queryBuilder->AddFieldFilter('users_user_name', new FieldFilter($userName, 'ILIKE'));
        }

        $dataset = new QueryDataset($this->connectionFactory, $this->connectionOptions, $queryBuilder->GetSQL(), array(), array(), array(), 'user_grants');
        $dataset->AddField(new StringField('userperms_grant'), false);
        $dataset->AddField(new StringField('userperms_pagename'), false);

        $dataset->Open();

        $result = array();
        $grantsBySource = array();

        while ($dataset->Next()) {
            $dataSourceName = $dataset->GetFieldValueByName('userperms_pagename');
            $grant = $dataset->GetFieldValueByName('userperms_grant');

            if (!isset($grantsBySource[$dataSourceName])) {
                $grantsBySource[$dataSourceName] = array();
            }

            $grantsBySource[$dataSourceName][] = strtolower($grant);
        }

        $dataset->Close();

        foreach ($grantsBySource as $dataSourceName => $grants) {
            if (in_array('admin', $grants)) {
                $result[$dataSourceName] = new AdminPermissionSet();
            } else {
                $result[$dataSourceName] = new PermissionSet(
                    in_array('select', $grants),
                    in_array('update', $grants),
                    in_array('insert', $grants),
                    in_array('delete', $grants)
                );
            }
        }

        return $result;
        
    }

    /**
     * @inheritdoc
     */
    public function GetPermissionSet($userName, $dataSourceName)
    {
        if (empty($this->securityCache[$userName])) {
            $this->securityCache[$userName] = $this->RetrieveSecurityInfo($userName);
        }

        $applicationPermissions = isset($this->securityCache[$userName][''])
            ? $this->securityCache[$userName]['']
            : new PermissionSet(false, false, false, false);

        return isset($this->securityCache[$userName][$dataSourceName])
            ? new CompositePermissionSet(array($this->securityCache[$userName][$dataSourceName], $applicationPermissions))
            : $applicationPermissions;
    }

    private function CreateUserGrantsDataset()
    {
        $result = new TableDataset($this->connectionFactory, $this->connectionOptions, $this->userPermsTable);
        $result->AddField(new IntegerField($this->userPerms_UserId, true, true));
        $result->AddField(new StringField($this->userPerms_PageName, true, true));
        $result->AddField(new StringField($this->userPerms_Grant, true, true));
        return $result;
    }

    public function HasAdminGrant($userName)
    {
        return $this->GetPermissionSet($userName, '')->HasAdminGrant();
    }

    public function HasAdminPanel($userName)
    {
        if ($this->GetPermissionSet($userName, '')->HasAdminGrant()) {
            return true;
        }

        if (!isset($this->securityCache[$userName]) || !is_array($this->securityCache[$userName])) {
            return false;
        }

        foreach ($this->securityCache[$userName] as $grants) {
            if ($grants->HasAdminGrant()) {
                return true;
            }
        }

        return false;
    }

    public function getAdminDatasources($userName)
    {
        if (!$this->HasAdminPanel($userName)) {
            return array();
        }

        $result = array();
        foreach ($this->securityCache[$userName] as $pageName => $grants) {
            if ($grants->HasAdminGrant()) {
                $result[] = $pageName;
            }
        }

        return $result;
    }

    /**
     * @param int $userId
     * @param Captions $captions
     * @param array|null $dataSources
     * @return array
     */
    public function GetUserGrants($userId, Captions $captions, array $dataSources = null)
    {
        $queryBuilder = new SelectCommand($this->connectionFactory->CreateEngCommandImp());

        $queryBuilder->AddField($this->usersTable, $this->users_UserId, FieldType::Number, 'users_user_id');
        $queryBuilder->AddField($this->usersTable, $this->users_UserName, FieldType::String, 'users_user_name');
        $queryBuilder->AddField($this->userPermsTable, $this->userPerms_UserId, FieldType::Number, 'userperms_user_id');
        $queryBuilder->AddField($this->userPermsTable, $this->userPerms_PageName, FieldType::String, 'userperms_pagename');
        $queryBuilder->AddField($this->userPermsTable, $this->userPerms_Grant, FieldType::String, 'userperms_grant');

        $queryBuilder->SetSourceTableName($this->userPermsTable);
        $queryBuilder->AddJoin(JoinKind::LeftOuter, $this->usersTable, 'userperms_user_id', $this->users_UserId);

        $queryBuilder->AddFieldFilter('userperms_user_id', new FieldFilter($userId, '='));

        $dataset = new QueryDataset($this->connectionFactory, $this->connectionOptions, $queryBuilder->GetSQL(), array(), array(), array(), 'user_grants');
        $dataset->AddField(new StringField('userperms_grant'));
        $dataset->AddField(new StringField('userperms_pagename'));

        $dataset->Open();

        $pages = array();

        if (!is_null($dataSources) && in_array('', $dataSources)) {
            $dataSources = null;
        }

        if (is_null($dataSources)) {
            $pages[''] = array(
                'name' => '',
                'caption' => $captions->GetMessageString('Application'),
                'selectGrant' => false,
                'updateGrant' => false,
                'insertGrant' => false,
                'deleteGrant' => false,
                'adminGrant' => false
            );
        }

        foreach($this->tableCaptions as $name => $caption) {
            if (is_null($dataSources) || in_array($name, $dataSources)) {
                $pages[$name] = array(
                    'name' => $name,
                    'caption' => $caption,
                    'selectGrant' => false,
                    'updateGrant' => false,
                    'insertGrant' => false,
                    'deleteGrant' => false,
                    'adminGrant' => false
                );
            }
        }

        while ($dataset->Next())
        {
            $grant = $dataset->GetFieldValueByName('userperms_grant');
            $pageName = $dataset->GetFieldValueByName('userperms_pagename');
            if (isset($pages[$pageName]))
            {
                $pages[$pageName]['selectGrant'] = $pages[$pageName]['selectGrant'] || StringUtils::SameText($grant, 'select');
                $pages[$pageName]['updateGrant'] = $pages[$pageName]['updateGrant'] || StringUtils::SameText($grant, 'update');
                $pages[$pageName]['insertGrant'] = $pages[$pageName]['insertGrant'] || StringUtils::SameText($grant, 'insert');
                $pages[$pageName]['deleteGrant'] = $pages[$pageName]['deleteGrant'] || StringUtils::SameText($grant, 'delete');
                $pages[$pageName]['adminGrant'] = $pages[$pageName]['adminGrant'] || StringUtils::SameText($grant, 'admin');

            }
        }
        $dataset->Close();

        return array_values($pages);
    }

    /**
     * @param int $userId
     * @param Captions $captions
     * @return string
     */
    public function GetUserGrantsAsJson($userId, Captions $captions)
    {
        return SystemUtils::ToJSON(array('status' => 'OK', 'result' => $this->GetUserGrants($userId, $captions)));
    }

    public function FillEmptyPagePermsArray(&$pagePerms)
    {
        foreach($this->tableCaptions as $name => $caption)
            array_push(
                $pagePerms,
                array(
                    'pageName' => $name,
                    'pageCaption' => $caption,
                    'acl' => ''
                ));
    }

    public function AddUserGrant($user_id, $page_name, $grant)
    {
        if ($this->NormalizeGrant($grant) != null)
        {
            $userGrants = $this->CreateUserGrantsDataset();
            $userGrants->Insert();

            $userGrants->SetFieldValueByName($this->userPerms_UserId, $user_id);
            $userGrants->SetFieldValueByName($this->userPerms_PageName, $page_name);
            $userGrants->SetFieldValueByName($this->userPerms_Grant, $this->NormalizeGrant($grant));

            $userGrants->Post();
        }
        else
            throw new Exception("$grant is not valid grant name.");
    }

    public function RemoveUserGrant($user_id, $page_name, $grant)
    {
        if ($this->NormalizeGrant($grant) != null)
        {
            $userGrants = $this->CreateUserGrantsDataset();
            $userGrants->AddFieldFilter($this->userPerms_PageName,
                new FieldFilter($page_name, '='));
            $userGrants->AddFieldFilter($this->userPerms_UserId,
                new FieldFilter($user_id, '='));

            $userGrants->Open();

            while($userGrants->Next())
            {
                if (StringUtils::SameText($userGrants->GetFieldValueByName($this->userPerms_Grant), $grant))
                {
                    $userGrants->Delete();
                    $userGrants->Close();
                    return true;
                }
            }
            $userGrants->Close();
            throw new Exception("User with id = $user_id has no $grant grant.");
        }
        else
            throw new Exception("$grant is not valid grant name.");
    }

    private function NormalizeGrant($grant)
    {
        if (StringUtils::SameText($grant, 'SELECT'))
            return 'SELECT';
        if (StringUtils::SameText($grant, 'UPDATE'))
            return 'UPDATE';
        if (StringUtils::SameText($grant, 'INSERT'))
            return 'INSERT';
        if (StringUtils::SameText($grant, 'DELETE'))
            return 'DELETE';
        if (StringUtils::SameText($grant, 'ADMIN'))
            return 'ADMIN';
        return null;
    }
}
