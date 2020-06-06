<?php

include_once dirname(__FILE__) . '/' . '../application.php';
include_once dirname(__FILE__) . '/' . '../utils/request_router.php';
include_once dirname(__FILE__) . '/' . 'user_self_management.php';
include_once dirname(__FILE__) . '/' . 'user_identity_storage/user_identity_storage.php';

class UserManagementRequestHandler
{
    /** @var RequestRouter */
    private $router;

    /** @var TableBasedUserGrantManager */
    private $tableBasedGrantsManager;

    /** @var AbstractUserAuthentication */
    private $userAuthentication;

    /** @var SecureApplication */
    private $app;

    /** @var IUserManager */
    private $userManger;

    /**
     * @param TableBasedUserGrantManager|null $tableBasedGrantsManager
     * @param IUserManager $userManager
     */
    private function __construct($tableBasedGrantsManager, $userManager) {
        $this->tableBasedGrantsManager = $tableBasedGrantsManager;
        $this->router = $this->CreateAndConfigureRequestRouter();
        $this->app = GetApplication();
        $this->userAuthentication = $this->app->GetUserAuthentication();
        $this->userManger = $userManager;
    }

    /**
     * @param string $currentPassword
     * @param string $newPassword
     */
    public function SelfChangePassword($currentPassword, $newPassword)
    {
        $userSelfManagement = new UserSelfManagement($this->app, $this->userManger);
        $userSelfManagement->ValidateAndChangePassword($currentPassword, $newPassword);
    }

    /**
     * @param string $userName
     * @param string $password
     * @return array
     */
    public function AdminAddUser($userName, $password)
    {
        $this->CheckAdminGrant();
        $userId = $this->userManger->addUser($userName, $password);

        $userId = $userId == null || $userId == '' ? getDBConnection()->ExecScalarSQL('SELECT MAX(id) FROM user;') : $userId; // Afterburned

        return array('id' => $userId, 'name' => $userName, 'password' => '******');

    }

    /**
     * @param string $userName
     * @param string $password
     * @param string $email
     * @return array
     */
    public function AdminAddUserEx($userName, $password, $email)
    {
        $this->CheckAdminGrant();
        $userId = $this->userManger->addUserEx($userName, $password, $email);
        return array('id' => $userId, 'name' => $userName, 'password' => '******', 'email' => $email);
    }

    /**
     * @param int $userId
     * @param string $userName
     * @param string $email
     * @param int $status
     * @return array
     */
    public function AdminUpdateUser($userId, $userName, $email, $status)
    {
        $this->CheckAdminGrant();
        $this->userManger->updateUser($userId, $userName, $email, $status);
        return array('id' => $userId, 'name' => $userName, 'email' => $email, 'status' => $status);
    }

    /**
     * @param int $userId
     */
    public function AdminRemoveUser($userId)
    {
        $this->CheckAdminGrant();
        $this->userManger->RemoveUser($userId);
    }

    /**
     * @param int $userId
     * @param string $newUserName
     * @return array
     */
    public function AdminChangeUserName($userId, $newUserName)
    {
        $this->CheckAdminGrant();
        $this->userManger->renameUser($userId, $newUserName);
        return array('username' => $newUserName);
    }

    /**
     * @param int $userId
     * @param string $newUserPassword
     * @return array
     */
    public function AdminChangeUserPassword($userId, $newUserPassword)
    {
        $this->CheckAdminGrant();
        $this->userManger->ChangeUserPassword($userId, $newUserPassword);
    }

    /**
     * @param int $userId
     * @return array
     */
    public function AdminGetUserGrants($userId)
    {
        $this->CheckAdminPanelGrant();
        return $this->tableBasedGrantsManager->GetUserGrants(
            $userId,
            Captions::getInstance('UTF-8'),
            $this->tableBasedGrantsManager->getAdminDatasources($this->app->GetCurrentUser())
        );
    }

    /**
     * @param int $userId
     * @param string $pageName
     * @param string $grant
     */
    public function AdminAddUserGrant($userId, $pageName, $grant)
    {
        $this->CheckAdminPanelGrant($pageName);
        $this->tableBasedGrantsManager->AddUserGrant($userId, $pageName, $grant);
    }

    /**
     * @param int $userId
     * @param string $pageName
     * @param string $grant
     */
    public function AdminRemoveUserGrant($userId, $pageName, $grant)
    {
        $this->CheckAdminPanelGrant($pageName);
        $this->tableBasedGrantsManager->RemoveUserGrant($userId, $pageName, $grant);
    }

    /**
     * @return RequestRouter
     */
    private function CreateAndConfigureRequestRouter()
    {
        $routes = array(
            new RequestRoute('self_change_password',
                array($this, 'SelfChangePassword'),
                array('current_password', 'new_password')),
            new RequestRoute('au',
                array($this, 'AdminAddUser'),
                array('username', 'password')),
            new RequestRoute('aue',
                array($this, 'AdminAddUserEx'),
                array('username', 'password', 'email')),
            new RequestRoute('uu',
                array($this, 'AdminUpdateUser'),
                array('user_id', 'username', 'email', 'status')),
            new RequestRoute('ru',
                array($this, 'AdminRemoveUser'),
                array('user_id')),
            new RequestRoute('cun',
                array($this, 'AdminChangeUserName'),
                array('user_id', 'username')),
            new RequestRoute('cup',
                array($this, 'AdminChangeUserPassword'),
                array('user_id', 'password')),
            new RequestRoute('gug',
                array($this, 'AdminGetUserGrants'),
                array('user_id')),
            new RequestRoute('aug',
                array($this, 'AdminAddUserGrant'),
                array('user_id', 'page_name', 'grant')),
            new RequestRoute('rug',
                array($this, 'AdminRemoveUserGrant'),
                array('user_id', 'page_name', 'grant'))
        );
        $result = new RequestRouter($routes, 'hname');
        return $result;
    }

    /**
     * @throws Exception
     */
    private function CheckAdminGrant()
    {
        if (!$this->hasAdminGrant()) {
            throw new Exception(Captions::getInstance('UTF-8')->GetMessageString('AccessDenied'));
        }
    }

    private function CheckAdminPanelGrant($dataSource = null)
    {
        if (!$this->app->HasAdminPanelForCurrentUser()) {
            throw new Exception(Captions::getInstance('UTF-8')->GetMessageString('AccessDenied'));
        }

        if (!$this->hasAdminGrant() && !is_null($dataSource)) {
            $adminDataSources = $this->tableBasedGrantsManager->getAdminDatasources($this->app->GetCurrentUser());
            if (!in_array($dataSource, $adminDataSources)) {
                throw new Exception(Captions::getInstance('UTF-8')->GetMessageString('AccessDenied'));
            }
        }
    }

    private function hasAdminGrant() {
        return $this->app->HasAdminGrantForCurrentUser();
    }

    /**
     * @param array $parameters
     * @param TableBasedUserGrantManager|null $tableBasedGrantsManager
     * @param IUserManager $userManager
     */
    static public function HandleRequest($parameters, $tableBasedGrantsManager, $userManager) {
        $instance = new UserManagementRequestHandler(
            $tableBasedGrantsManager,
            $userManager
        );

        header('Content-Type: application/json');

        echo $instance->router->HandleRequest($parameters);
    }
}
