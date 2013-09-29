<?php

include_once dirname(__FILE__) . '/' . '../../database_engine/engine.php';
include_once dirname(__FILE__) . '/' . '../../database_engine/commands.php';
include_once dirname(__FILE__) . '/' . '../../database_engine/select_command.php';

include_once dirname(__FILE__) . '/' . '../dataset/dataset.php';
include_once dirname(__FILE__) . '/' . '../dataset/query_dataset.php';

include_once dirname(__FILE__) . '/' . 'datasource_security_info.php';
include_once dirname(__FILE__) . '/' . 'user_grants_manager.php';
include_once dirname(__FILE__) . '/' . 'user_manager.php';

include_once dirname(__FILE__) . '/' . '../utils/hash_utils.php';

class TableBasedUserGrantsManager extends UserGrantsManager implements IUserManager
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
    private $adminGrantCache = array();
    private $allowGuest = array();

    /**
     * @param ConnectionFactory $connectionFactory
     * @param array $connectionOptions
     * @param array $usersTableInfo
     * @param array $userPermsTableInfo
     * @param array $tableCaptions
     * @param StringHasher $passwordHasher
     * @param bool $allowGuest
     */
    public function __construct($connectionFactory, $connectionOptions,
        $usersTableInfo, $userPermsTableInfo, $tableCaptions, $passwordHasher, $allowGuest = true)
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
        $this->passwordHasher = $passwordHasher;
        $this->adminGrantCache = array();
    }

    private function RetrieveSecurityInfo($userName, $dataSourceName)
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
        else
        {
            $queryBuilder->AddCompositeFieldFilter('OR',
                array(
                    'users_user_name',
                    'userperms_user_id'
                ),
                array(
                    new FieldFilter($userName, 'ILIKE'),
                    new FieldFilter('0', '=')
                ));
        }

        $queryBuilder->AddCompositeFieldFilter('OR',
            array(
                'userperms_pagename',
                'userperms_pagename'
            ),
            array(
                new FieldFilter($dataSourceName, 'ILIKE'),
                new FieldFilter('', '=')
            ));

        $dataset = new QueryDataset($this->connectionFactory, $this->connectionOptions, $queryBuilder->GetSQL(), array(), array(), array(), 'user_grants');
        $dataset->AddField(new StringField('userperms_grant'), false);

        $result = null;

        $dataset->Open();

        $selectGrant = false;
        $updateGrant = false;
        $insertGrant = false;
        $deleteGrant = false;
        $adminGrant = false;

        while ($dataset->Next())
        {
            $grant = $dataset->GetFieldValueByName('userperms_grant');
            $selectGrant = $selectGrant || StringUtils::SameText($grant, 'select');
            $updateGrant = $updateGrant || StringUtils::SameText($grant, 'update');
            $insertGrant = $insertGrant || StringUtils::SameText($grant, 'insert');
            $deleteGrant = $deleteGrant || StringUtils::SameText($grant, 'delete');
            $adminGrant = $adminGrant || StringUtils::SameText($grant, 'admin');

            if ($adminGrant)
            {
                $result = new AdminDataSourceSecurityInfo();
                break;
            }
        }
        $dataset->Close();

        return !is_null($result) ? $result : new DataSourceSecurityInfo($selectGrant, $updateGrant, $insertGrant, $deleteGrant);
        
    }

    /**
     * @param $userName
     * @param $dataSourceName
     * @return IDataSourceSecurityInfo
     */
    public function GetSecurityInfo($userName, $dataSourceName)
    {
        $result = null;

        if (isset($this->securityCache[$userName]))
            if (isset($this->securityCache[$userName][$dataSourceName]))
                $result = $this->securityCache[$userName][$dataSourceName];

        if (is_null($result))
        {
            $result = $this->RetrieveSecurityInfo($userName, $dataSourceName);
            if (!isset($this->securityCache[$userName]))
                $this->securityCache[$userName] = array();
            if (!isset($this->securityCache[$userName][$dataSourceName]))
                $this->securityCache[$userName][$dataSourceName] = $result;
        }
        
        return $result;
    }

    private function CreateUsersDataset()
    {
        $usersDataset = new TableDataset($this->connectionFactory, $this->connectionOptions, $this->usersTable);
        $usersDataset->AddField(new IntegerField($this->users_UserId), true);
        $usersDataset->AddField(new StringField($this->users_UserName), false);
        $usersDataset->AddField(new StringField($this->users_Password), false);
        return $usersDataset;
    }

    private function CreateUserGrantsDataset()
    {
        $result = new TableDataset($this->connectionFactory, $this->connectionOptions, $this->userPermsTable);
        $result->AddField(new IntegerField($this->userPerms_UserId), true);
        $result->AddField(new StringField($this->userPerms_PageName), true);
        $result->AddField(new StringField($this->userPerms_Grant), true);
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function CanAddUser()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function AddUser($id, $userName, $password)
    {
        $usersDataset = $this->CreateUsersDataset();

        $usersDataset->Insert();

        $usersDataset->SetFieldValueByName($this->users_UserId, $id);
        $usersDataset->SetFieldValueByName($this->users_UserName, $userName);
        $usersDataset->SetFieldValueByName($this->users_Password,
                                           $this->passwordHasher->GetHash($password));

        $usersDataset->Post();
    }

    /**
     * {@inheritdoc}
     */
    public function CanChangeUserName()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function ChangeUserName($user_id, $userName)
    {
        $usersDataset = $this->CreateUsersDataset();
        $usersDataset->SetSingleRecordState(array($user_id));

        $usersDataset->Open();
        if ($usersDataset->Next())
        {
            $usersDataset->Edit();
            $usersDataset->SetFieldValueByName($this->users_UserName, $userName);
            $usersDataset->Post();
        }
        $usersDataset->Close();
        return $userName;
    }

    /**
     * {@inheritdoc}
     */
    public function CanChangeUserPassword()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function ChangeUserPassword($user_id, $password)
    {
        $usersDataset = $this->CreateUsersDataset();
        $usersDataset->SetSingleRecordState(array($user_id));

        $usersDataset->Open();
        if ($usersDataset->Next())
        {
            $usersDataset->Edit();

            $usersDataset->SetFieldValueByName($this->users_Password,
                                           $this->passwordHasher->GetHash($password));

            $usersDataset->Post();
        }
        $usersDataset->Close();
    }

    /**
     * {@inheritdoc}
     */
    public function CanRemoveUser()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function RemoveUser($userId)
    {
        $usersDataset = $this->CreateUsersDataset();

        $usersDataset->SetSingleRecordState(array($userId));
        $usersDataset->Open();
        if ($usersDataset->Next())
        {
            $usersDataset->Delete();
        }
        else
        {
            throw new Exception('User with user id = ' . $userId . ' does not exists.');
        }
        $usersDataset->Close();
    }

    /**
     * @param string $userName
     * @return boolean
     */
    public function RetrieveAdminGrant($userName)
    {
        $queryBuilder = new SelectCommand($this->connectionFactory->CreateEngCommandImp());

        $queryBuilder->AddField($this->usersTable, $this->users_UserId, FieldType::Number, 'users_user_id');
        $queryBuilder->AddField($this->usersTable, $this->users_UserName, FieldType::String, 'users_user_name');

        $queryBuilder->AddField($this->userPermsTable, $this->userPerms_UserId, FieldType::Number, 'userperms_user_id');
        $queryBuilder->AddField($this->userPermsTable, $this->userPerms_PageName, FieldType::String, 'userperms_pagename');
        $queryBuilder->AddField($this->userPermsTable, $this->userPerms_Grant, FieldType::String, 'userperms_grant');

        $queryBuilder->SetSourceTableName($this->userPermsTable);
        $queryBuilder->AddJoin(JoinKind::Inner, $this->usersTable, 'userperms_user_id', $this->users_UserId);

        $queryBuilder->AddFieldFilter('users_user_name', new FieldFilter($userName, 'ILIKE'));
        $queryBuilder->AddFieldFilter('userperms_grant', new FieldFilter('ADMIN', 'ILIKE'));

        $queryBuilder->AddCompositeFieldFilter('OR',
            array('userperms_pagename', 'userperms_pagename'),
            array(new FieldFilter('', 'ILIKE'), new IsNullFieldFilter()));

        $dataset = new QueryDataset($this->connectionFactory, $this->connectionOptions, $queryBuilder->GetSQL(), array(), array(), array(), 'user_grants');
        $dataset->AddField(new StringField('userperms_grant'), false);

        $dataset->Open();

        if ($dataset->Next())
        {
            $dataset->Close();
            return true;
        }
        $dataset->Close();
        return false;
    }

    public function HasAdminGrant($userName)
    {
        if (!isset($this->adminGrantCache[$userName]))
            $this->adminGrantCache[$userName] = $this->RetrieveAdminGrant($userName);
        return $this->adminGrantCache[$userName];
    }

    /**
     * @param int $userId
     * @param Captions $captions
     * @return array
     */
    public function GetUserGrants($userId, Captions $captions)
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
        $dataset->AddField(new StringField('userperms_grant'), false);
        $dataset->AddField(new StringField('userperms_pagename'), false);

        $dataset->Open();

        $pages = array();
        $pages[''] = array(
            'name' => '',
            'caption' => $captions->GetMessageString('Application'),
            'selectGrant' => false,
            'updateGrant' => false,
            'insertGrant' => false,
            'deleteGrant' => false,
            'adminGrant' => false
        );
        foreach($this->tableCaptions as $name => $caption)
            $pages[$name] = array(
                'name' => $name,
                'caption' => $captions->RenderText($caption),
                'selectGrant' => false,
                'updateGrant' => false,
                'insertGrant' => false,
                'deleteGrant' => false,
                'adminGrant' => false
            );

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

    public function GetAllUsersAsJson()
    {
        $usersDataset = $this->CreateUsersDataset();
        $usersDataset->Open();

        $users = array();
        if ($this->allowGuest)
            array_push($users, array(
                'id' => -1,
                'name' => 'guest',
                'password' => '******',
                'editable' => false
            ));
        array_push($users, array(
            'id' => 0,
            'name' => 'PUBLIC (All users)',
            'password' => '******',
            'editable' => false
        ));
        while ($usersDataset->Next())
        {
            $user = array(
                'id' => $usersDataset->GetFieldValueByName($this->users_UserId),
                'name' => $usersDataset->GetFieldValueByName($this->users_UserName),
                'password' => '******',
                'editable' => true
            );
            array_push($users, $user);
        }
        $usersDataset->Close();
        return SystemUtils::ToJSON($users);
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
