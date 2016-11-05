<?php

include_once dirname(__FILE__) . '/' . '../application.php';
include_once dirname(__FILE__) . '/' . '../utils/request_router.php';
include_once dirname(__FILE__) . '/' . 'user_self_management.php';
include_once dirname(__FILE__) . '/' . 'user_identity_storage/user_identity_storage.php';

class UserManagementRequestHandler
{
    /**
     * @var RequestRouter
     */
    private $router;

    /** @var TableBasedUserGrantManager */
    private $tableBasedGrantsManager;

    /** @var IdentityCheckStrategy */
    private $identityCheckStrategy;

    /**
     * @var SecureApplication
     */
    private $app;

    /**
     * @var UserIdentityCookieStorage
     */
    private $userIdentityStorage;

    /**
     * @param TableBasedUserGrantManager $tableBasedGrantsManager
     * @param IdentityCheckStrategy $identityCheckStrategy
     * @param UserIdentityStorage $userIdentityStorage
     */
    private function __construct(
        TableBasedUserGrantManager $tableBasedGrantsManager,
        IdentityCheckStrategy $identityCheckStrategy,
        UserIdentityStorage $userIdentityStorage)
    {
        $this->tableBasedGrantsManager = $tableBasedGrantsManager;
        $this->identityCheckStrategy = $identityCheckStrategy;
        $this->router = $this->CreateAndConfigureRequestRouter();
        $this->app = GetApplication();
        $this->userIdentityStorage = $userIdentityStorage;
    }

    /**
     * @param string $currentPassword
     * @param string $newPassword
     */
    public function SelfChangePassword($currentPassword, $newPassword)
    {
        $userSelfManagement = new UserSelfManagement($this->app,
            $this->tableBasedGrantsManager, $this->identityCheckStrategy);
        $userSelfManagement->ValidateAndChangePassword($currentPassword, $newPassword);
        $this->userIdentityStorage->UpdatePassword($newPassword);
    }

    /**
     * @param int $userId
     * @param string $userName
     * @param string $password
     * @return array
     */
    public function AdminAddUser($userId, $userName, $password)
    {
        $this->CheckAdminGrant();
        $this->tableBasedGrantsManager->AddUser($userId, $userName, $password);

        $userId = $userId == null || $userId == '' ? getDBConnection()->ExecScalarSQL('SELECT MAX(id) FROM user;') : $userId; // Afterburned

        return array('id' => $userId, 'name' => $userName, 'password' => '******');

    }

    /**
     * @param int $userId
     */
    public function AdminRemoveUser($userId)
    {
        $this->CheckAdminGrant();
        $this->tableBasedGrantsManager->RemoveUser($userId);
    }

    /**
     * @param int $userId
     * @param string $newUserName
     * @return array
     */
    public function AdminChangeUserName($userId, $newUserName)
    {
        $this->CheckAdminGrant();
        return array('username' => $this->tableBasedGrantsManager->ChangeUserName($userId, $newUserName));
    }

    /**
     * @param int $userId
     * @param string $newUserPassword
     * @return array
     */
    public function AdminChangeUserPassword($userId, $newUserPassword)
    {
        $this->CheckAdminGrant();
        $this->tableBasedGrantsManager->ChangeUserPassword($userId, $newUserPassword);
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
                array('id', 'username', 'password')),
            new RequestRoute('ru',
                array($this, 'AdminRemoveUser'),
                array('user_id')),
            new RequestRoute('eu',
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
        return $this->app->GetUserAuthorizationStrategy()->HasAdminGrant($this->app->GetCurrentUser());
    }

    /**
     * @param array $parameters
     * @param TableBasedUserGrantManager $tableBasedGrantsManager
     * @param IdentityCheckStrategy $identityCheckStrategy
     * @param UserIdentityStorage $userIdentityStorage
     */
    static public function HandleRequest(
        $parameters,
        TableBasedUserGrantManager $tableBasedGrantsManager,
        IdentityCheckStrategy $identityCheckStrategy,
        UserIdentityStorage $userIdentityStorage)
    {
        $instance = new UserManagementRequestHandler(
            $tableBasedGrantsManager,
            $identityCheckStrategy,
            $userIdentityStorage
        );

        header('Content-Type: application/json');

        echo $instance->router->HandleRequest($parameters);
    }
}
