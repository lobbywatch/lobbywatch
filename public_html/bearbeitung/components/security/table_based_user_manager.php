<?php

include_once dirname(__FILE__) . '/' . 'user_manager.php';
include_once dirname(__FILE__) . '/' . '../../database_engine/engine.php';
include_once dirname(__FILE__) . '/../dataset/dataset.php';
include_once dirname(__FILE__) . '/../dataset/table_dataset.php';
include_once dirname(__FILE__) . '/../../components/utils/system_utils.php';

class UserStatus
{
    const OK = 0;
    const WaitingForVerification = 1;
    const WaitingForRecoveringPassword = 2;
}

class TableBasedUserManager implements IUserManager
{
    /** @var ConnectionFactory */
    private $connectionFactory;
    /** @var array */
    private $connectionOptions;
    /** @var StringHasher */
    private $passwordHasher;
    /** @var boolean */
    private $emailBasedFeaturesEnabled;
    /** @var Event */
    public $OnVerifyPasswordStrength;
    /** @var string */
    private $usersTableName;
    private $userIdField;
    private $userNameField;
    private $userPasswordField;
    private $userEmailField;
    private $userTokenField;
    private $userStatusField;

    /**
     * @param ConnectionFactory $connectionFactory
     * @param array $connectionOptions
     * @param array $usersTableInfo
     * @param StringHasher $passwordHasher
     * @param boolean $emailBasedFeaturesEnabled
     */
    public function __construct($connectionFactory, $connectionOptions, $usersTableInfo, $passwordHasher, $emailBasedFeaturesEnabled = true) {
        $this->connectionFactory = $connectionFactory;
        $this->connectionOptions = $connectionOptions;
        $this->passwordHasher = $passwordHasher;
        $this->emailBasedFeaturesEnabled = $emailBasedFeaturesEnabled;
        $this->OnVerifyPasswordStrength = new Event();

        $this->usersTableName = $usersTableInfo['TableName'];
        $this->userIdField = $usersTableInfo['UserId'];
        $this->userNameField = $usersTableInfo['UserName'];
        $this->userPasswordField = $usersTableInfo['Password'];
        $this->userEmailField = $usersTableInfo['Email'];
        $this->userTokenField = $usersTableInfo['UserToken'];
        $this->userStatusField = $usersTableInfo['UserStatus'];
    }

    private function createUsersDataset()
    {
        $usersDataset = new TableDataset($this->connectionFactory, $this->connectionOptions, $this->usersTableName);
        $usersDataset->AddField(new IntegerField($this->userIdField, true, true,true));
        $usersDataset->AddField(new StringField($this->userNameField));
        $usersDataset->AddField(new StringField($this->userPasswordField));
        if ($this->emailBasedFeaturesEnabled) {
            $usersDataset->AddField(new StringField($this->userEmailField));
            $usersDataset->AddField(new StringField($this->userTokenField));
            $usersDataset->AddField(new StringField($this->userStatusField));
        }
        return $usersDataset;
    }

    /** @inheritdoc */
    public function addUser($name, $password) {
        $this->verifyPasswordStrength($password);

        $usersDataset = $this->createUsersDataset();
        $usersDataset->Insert();
        $usersDataset->SetFieldValueByName($this->userNameField, $name);
        $usersDataset->SetFieldValueByName($this->userPasswordField, $this->passwordHasher->GetHash($password));
        $usersDataset->Post();
        return $usersDataset->GetFieldValueByName($this->userIdField);
    }

    /** @inheritdoc */
    public function addUserEx($name, $password, $email, $token = null, $status = UserStatus::OK) {
        $this->verifyPasswordStrength($password);

        $usersDataset = $this->createUsersDataset();
        $usersDataset->Insert();
        $usersDataset->SetFieldValueByName($this->userNameField, $name);
        $usersDataset->SetFieldValueByName($this->userPasswordField, $this->passwordHasher->GetHash($password));
        $usersDataset->SetFieldValueByName($this->userEmailField, $email);
        $usersDataset->SetFieldValueByName($this->userTokenField, $token);
        $usersDataset->SetFieldValueByName($this->userStatusField, $status);
        $usersDataset->Post();
        return $usersDataset->GetFieldValueByName($this->userIdField);
    }

    /** @inheritdoc */
    public function renameUser($id, $newUsername) {
        $usersDataset = $this->createUsersDataset();
        $usersDataset->SetSingleRecordState(array($id));
        $usersDataset->Open();
        if ($usersDataset->Next()) {
            $usersDataset->Edit();
            $usersDataset->SetFieldValueByName($this->userNameField, $newUsername);
            $usersDataset->Post();
        } else {
            throw new Exception('User with user id = ' . $id . ' does not exist.');
        }
    }

    /** @inheritdoc */
    public function updateUser($id, $name, $email, $status) {
        $usersDataset = $this->createUsersDataset();
        $usersDataset->SetSingleRecordState(array($id));
        $usersDataset->Open();
        if ($usersDataset->Next()) {
            $usersDataset->Edit();
            $usersDataset->SetFieldValueByName($this->userNameField, $name);
            $usersDataset->SetFieldValueByName($this->userEmailField, $email);
            $usersDataset->SetFieldValueByName($this->userStatusField, $status);
            $usersDataset->Post();
        } else {
            throw new Exception('User with user id = ' . $id . ' does not exist.');
        }
    }

    /** @inheritdoc */
    public function changeUserPassword($id, $password) {
        $this->verifyPasswordStrength($password);

        $usersDataset = $this->createUsersDataset();
        $usersDataset->SetSingleRecordState(array($id));
        $usersDataset->Open();
        if ($usersDataset->Next())
        {
            $usersDataset->Edit();
            $usersDataset->SetFieldValueByName($this->userPasswordField, $this->passwordHasher->GetHash($password));
            $usersDataset->Post();
        } else {
            throw new Exception('User with user id = ' . $id . ' does not exist.');
        }
    }

    /**
     * @param string $password
     * @throws Exception
     */
    private function verifyPasswordStrength($password) {
        $result = true;
        $passwordRule = '';
        $this->OnVerifyPasswordStrength->Fire(array(
            $password,
            &$result,
            &$passwordRule
        ));
        if (!$result) {
            throw new Exception($passwordRule);
        }
    }

    /** @inheritdoc */
    public function removeUser($id) {
        $usersDataset = $this->CreateUsersDataset();
        $usersDataset->SetSingleRecordState(array($id));
        $usersDataset->Open();
        if ($usersDataset->Next())
        {
            $usersDataset->Delete();
        }
        else {
            throw new Exception('User with user id = ' . $id . ' does not exist.');
        }
    }

    /**
     * @param string $email
     * @return boolean
     */
    public function checkEmailExists($email) {
        $usersDataset = $this->createUsersDataset();
        $usersDataset->AddFieldFilter(
            $this->userEmailField,
            new FieldFilter($email, '=', true));
        $usersDataset->Open();
        return $usersDataset->GetTotalRowCount() > 0;
    }

    /**
     * @param string $username
     * @return boolean
     */
    public function checkUserExists($username) {
        $usersDataset = $this->createUsersDataset();
        $usersDataset->AddFieldFilter(
            $this->userNameField,
            new FieldFilter($username, '=', true));
        $usersDataset->Open();
        return $usersDataset->GetTotalRowCount() > 0;
    }

    /**
     * @param string $token
     * @return boolean
     */
    public function updateUserStatusByToken($token) {
        $usersDataset = $this->createUsersDataset();
        $usersDataset->AddFieldFilter(
            $this->userTokenField,
            new FieldFilter($token, '=', true));
        $usersDataset->Open();
        $result = $usersDataset->GetTotalRowCount() > 0;
        if ($result) {
            $usersDataset->Next();
            $usersDataset->Edit();
            $usersDataset->SetFieldValueByName($this->userTokenField, null);
            $usersDataset->SetFieldValueByName($this->userStatusField, UserStatus::OK);
            $usersDataset->Post();
        }
        return $result;
    }

    /**
     * @param string $username
     * @return boolean
     */
    public function checkUserAccountVerified($username) {
        $result = true;
        $usersDataset = $this->createUsersDataset();
        $usersDataset->AddFieldFilter(
            $this->userNameField,
            new FieldFilter($username, '=', true));
        $usersDataset->Open();
        if ($usersDataset->Next()) {
            $userStatus = $usersDataset->GetFieldValueByName($this->userStatusField);
            return $userStatus <> UserStatus::WaitingForVerification;
        }

        return $result;
    }

    /**
     * @param string $accountName
     * @return null|array
     */
    public function getUserInfoByUsernameOrEmail($accountName) {
        $result = null;
        $usersDataset = $this->createUsersDataset();
        if (filter_var($accountName, FILTER_VALIDATE_EMAIL)) {
            $usersDataset->AddFieldFilter(
                $this->userEmailField,
                new FieldFilter($accountName, '=', true));
        }
        else {
            $usersDataset->AddFieldFilter(
                $this->userNameField,
                new FieldFilter($accountName, '=', true));
        }
        $usersDataset->Open();
        if ($usersDataset->Next()) {
            $result = array();
            $result['Username'] = $usersDataset->GetFieldValueByName($this->userNameField);
            $result['Email'] = $usersDataset->GetFieldValueByName($this->userEmailField);
            $result['Token'] = $usersDataset->GetFieldValueByName($this->userTokenField);
            $result['Status'] = $usersDataset->GetFieldValueByName($this->userStatusField);
        }

        return $result;
    }

    /**
     * @param string $email
     * @return null|array
     */
    public function getUserInfoByEmail($email) {
        $result = null;
        $usersDataset = $this->createUsersDataset();
         $usersDataset->AddFieldFilter(
                $this->userEmailField,
                new FieldFilter($email, '=', true));
        $usersDataset->Open();
        if ($usersDataset->Next()) {
            $result = array();
            $result['Username'] = $usersDataset->GetFieldValueByName($this->userNameField);
            $result['Email'] = $usersDataset->GetFieldValueByName($this->userEmailField);
            $result['Status'] = $usersDataset->GetFieldValueByName($this->userStatusField);
        }

        return $result;
    }

    /**
     * @param string $username
     * @param string $token
     * @throws Exception
     */
    public function setRecoveringPasswordToken($username, $token) {
        $usersDataset = $this->createUsersDataset();
        $usersDataset->AddFieldFilter(
            $this->userNameField,
            new FieldFilter($username, '=', true));
        $usersDataset->Open();
        if ($usersDataset->Next()) {
            $usersDataset->Edit();
            $usersDataset->SetFieldValueByName($this->userTokenField, $token);
            $usersDataset->SetFieldValueByName($this->userStatusField, UserStatus::WaitingForRecoveringPassword);
            $usersDataset->Post();
        } else {
            throw new Exception('Username ' . $username . ' not found');
        }
    }

    /**
     * @param string $username
     * @param string $token
     * @throws Exception
     */
    public function setAccountVerificationToken($username, $token) {
        $usersDataset = $this->createUsersDataset();
        $usersDataset->AddFieldFilter(
            $this->userNameField,
            new FieldFilter($username, '=', true));
        $usersDataset->Open();
        if ($usersDataset->Next()) {
            $usersDataset->Edit();
            $usersDataset->SetFieldValueByName($this->userTokenField, $token);
            $usersDataset->SetFieldValueByName($this->userStatusField, UserStatus::WaitingForVerification);
            $usersDataset->Post();
        } else {
            throw new Exception('Username ' . $username . ' not found');
        }
    }

    /**
     * @param string $token
     * @return boolean
     */
    public function recoveryPasswordTokenExists($token) {
        $usersDataset = $this->createUsersDataset();
        $this->applyRecoveryPasswordTokenFilter($usersDataset, $token);
        $usersDataset->Open();
        return $usersDataset->GetTotalRowCount() > 0;
    }

    /**
     * @param string $token
     * @return null|array
     */
    public function getUsernameByRecoveryPasswordToken($token) {
        $usersDataset = $this->createUsersDataset();
        $this->applyRecoveryPasswordTokenFilter($usersDataset, $token);
        $usersDataset->Open();
        if ($usersDataset->Next()) {
            return $usersDataset->GetFieldValueByName($this->userNameField);
        } else {
            return null;
        }
    }

    /**
     * @param string $token
     * @return null|array
     */
    public function getUserInfoByRecoveryPasswordToken($token) {
        $result = null;
        $usersDataset = $this->createUsersDataset();
        $this->applyRecoveryPasswordTokenFilter($usersDataset, $token);
        $usersDataset->Open();
        if ($usersDataset->Next()) {
            $result = array();
            $result['Username'] = $usersDataset->GetFieldValueByName($this->userNameField);
            $result['Email'] = $usersDataset->GetFieldValueByName($this->userEmailField);
        }

        return $result;
    }

    /**
     * @param Dataset $dataset
     * @param string $token
     */
    private function applyRecoveryPasswordTokenFilter($dataset, $token) {
        $dataset->AddFieldFilter(
            $this->userTokenField,
            new FieldFilter($token, '=', true));
        $dataset->AddFieldFilter(
            $this->userStatusField,
            new FieldFilter(UserStatus::WaitingForRecoveringPassword, '=', true));
    }

    public function resetUserPassword($username, $password) {
        $this->verifyPasswordStrength($password);

        $usersDataset = $this->createUsersDataset();
        $usersDataset->AddFieldFilter(
            $this->userNameField,
            new FieldFilter($username, '=', true));
        $usersDataset->Open();
        if ($usersDataset->Next()) {
            $usersDataset->Edit();
            $usersDataset->SetFieldValueByName($this->userPasswordField, $this->passwordHasher->GetHash($password));
            $usersDataset->SetFieldValueByName($this->userTokenField, null);
            $usersDataset->SetFieldValueByName($this->userStatusField, UserStatus::OK);
            $usersDataset->Post();
        } else {
            throw new Exception('Username ' . $username . ' not found');
        }
    }

    /**
     * @param string $username
     * @return null|int
     */
    public function getUserIdByName($username) {
        $usersDataset = $this->createUsersDataset();
        $usersDataset->AddFieldFilter(
            $this->userNameField,
            new FieldFilter($username, '=', true));
        $usersDataset->Open();
        if ($usersDataset->Next()) {
            return $usersDataset->GetFieldValueByName($this->userIdField);
        } else {
            return null;
        }
    }

    /**
     * @param string $username
     * @return null|string
     */
    public function getUserPasswordByName($username) {
        $usersDataset = $this->createUsersDataset();
        $usersDataset->AddFieldFilter(
            $this->userNameField,
            new FieldFilter($username, '=', true));
        $usersDataset->Open();
        if ($usersDataset->Next()) {
            return $usersDataset->GetFieldValueByName($this->userPasswordField);
        } else {
            return null;
        }
    }

    /**
     * @param boolean $guestAccessEnabled
     * @return string
     */
    public function getUsersAsJson($guestAccessEnabled) {
        $users = array();
        if ($guestAccessEnabled)
            array_push($users, array(
                'id' => -1,
                'name' => 'guest',
                'password' => '******',
                'email' => '',
                'status' => 0,
                'editable' => false
            ));
        array_push($users, array(
            'id' => 0,
            'name' => 'PUBLIC (All users)',
            'password' => '******',
            'email' => '',
            'status' => 0,
            'editable' => false
        ));

        $usersDataset = $this->createUsersDataset();
        $usersDataset->Open();
        while ($usersDataset->Next())
        {
            $user = array(
                'id' => $usersDataset->GetFieldValueByName($this->userIdField),
                'name' => $usersDataset->GetFieldValueByName($this->userNameField),
                'password' => '******',
                'email' => $this->emailBasedFeaturesEnabled ? $usersDataset->GetFieldValueByName($this->userEmailField) : '',
                'status' => $this->emailBasedFeaturesEnabled ? $usersDataset->GetFieldValueByName($this->userStatusField) : '',
                'editable' => true
            );
            array_push($users, $user);
        }

        return SystemUtils::ToJSON($users);
    }

}
