<?php

include_once dirname(__FILE__) . '/' . '../application.php';
include_once dirname(__FILE__) . '/' . '../utils/request_router.php';
include_once dirname(__FILE__) . '/' . 'user_self_management.php';
include_once dirname(__FILE__) . '/' . 'user_identity_cookie_storage.php';

class UserManagementRequestHandler
{
    /**
     * @var RequestRouter
     */
    private $router;

    /** @var TableBasedUserGrantsManager */
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
     * @param TableBasedUserGrantsManager $tableBasedGrantsManager
     * @param IdentityCheckStrategy $identityCheckStrategy
     */
    private function __construct($tableBasedGrantsManager, $identityCheckStrategy)
    {
        $this->tableBasedGrantsManager = $tableBasedGrantsManager;
        $this->identityCheckStrategy = $identityCheckStrategy;
        $this->router = $this->CreateAndConfigureRequestRouter();
        $this->app = GetApplication();
        $this->userIdentityStorage = new UserIdentityCookieStorage($identityCheckStrategy);
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
        $this->CheckAdminGrant();
        return $this->tableBasedGrantsManager->GetUserGrants($userId, new Captions('UTF-8'));
    }

    /**
     * @param int $userId
     * @param string $pageName
     * @param string $grant
     */
    public function AdminAddUserGrant($userId, $pageName, $grant)
    {
        $this->CheckAdminGrant();
        $this->tableBasedGrantsManager->AddUserGrant($userId, $pageName, $grant);
    }

    /**
     * @param int $userId
     * @param string $pageName
     * @param string $grant
     */
    public function AdminRemoveUserGrant($userId, $pageName, $grant)
    {
        $this->CheckAdminGrant();
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
        if (!$this->app->GetUserAuthorizationStrategy()->HasAdminGrant($this->app->GetCurrentUser()))
            throw new Exception(GetCaptions('UTF-8')->GetMessageString('AccessDenied'));
    }

    /**
     * @param array $parameters
     * @param TableBasedUserGrantsManager $tableBasedGrantsManager
     * @param IdentityCheckStrategy $identityCheckStrategy
     */
    static public function HandleRequest($parameters, $tableBasedGrantsManager, $identityCheckStrategy)
    {
        $instance = new UserManagementRequestHandler($tableBasedGrantsManager, $identityCheckStrategy);
        header('Content-Type: application/json');
        echo $instance->router->HandleRequest($parameters);
    }
}
