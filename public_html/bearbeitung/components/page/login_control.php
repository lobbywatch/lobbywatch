<?php

class LoginControl {
    /** @var Page */
    private $page;

    private $urlToRedirectAfterLogin;
    /** @var IdentityCheckStrategy */
    private $identityCheckStrategy;
    /** @var UserIdentityCookieStorage */
    private $userIdentityStorage;
    private $errorMessage = '';
    private $lastUserName;
    private $lastSaveidentity = false;
    /** @var Captions */
    private $captions;
    private $canLoginAsGuest = false;
    private $connectionFactory;

    /**
     * @param LoginPage $page
     * @param string $urlToRedirectAfterLogin
     * @param IdentityCheckStrategy $identityCheckStrategy
     * @param UserIdentityStorage $userIdentityStorage
     * @param ConnectionFactory $connectionFactory
     * @param bool $canLoginAsGuest
     * @param Captions $captions
     */
    public function __construct(
        LoginPage $page,
        $urlToRedirectAfterLogin,
        IdentityCheckStrategy $identityCheckStrategy,
        UserIdentityStorage $userIdentityStorage,
        ConnectionFactory $connectionFactory,
        $canLoginAsGuest,
        Captions $captions)
    {
        $this->page = $page;
        $this->connectionFactory = $connectionFactory;
        $this->identityCheckStrategy = $identityCheckStrategy;
        $this->urlToRedirectAfterLogin = $urlToRedirectAfterLogin;
        $this->captions = $captions;
        $this->userIdentityStorage = $userIdentityStorage;
        $this->canLoginAsGuest = $canLoginAsGuest;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function Accept(Renderer $renderer) {
        $renderer->RenderLoginControl($this);
    }

    public function GetErrorMessage() {
        return $this->errorMessage;
    }

    public function GetLastUserName() {
        return $this->lastUserName;
    }

    public function GetLastSaveidentity() {
        return $this->lastSaveidentity;
    }

    public function CanLoginAsGuest() {
        return $this->canLoginAsGuest;
    }

    public function GetLoginAsGuestLink() {
        $pageInfos = GetPageInfos();
        foreach ($pageInfos as $pageInfo) {
            if (GetApplication()->GetUserRoles('guest', $pageInfo['name'])->HasViewGrant()) {
                return $pageInfo['filename'];
            }
        }
        return $this->urlToRedirectAfterLogin;
    }

    public function CheckUsernameAndPassword($username, $password, &$errorMessage) {
        try {
            $result = $this->identityCheckStrategy->CheckUsernameAndPassword($username, $password);
            if (!$result) {
                $errorMessage = $this->captions->GetMessageString('UsernamePasswordWasInvalid');
            }
            return $result;
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            return false;
        }
    }

    public function SaveUserIdentity($username, $password, $saveidentity) {
        $this->userIdentityStorage->SaveUserIdentity(new UserIdentity($username, $password, $saveidentity));
    }

    public function ClearUserIdentity() {
        $this->userIdentityStorage->ClearUserIdentity();
    }

    private function DoOnAfterLogin($userName) {
        $connection = $this->connectionFactory->CreateConnection(GetConnectionOptions());
        try {
            $connection->Connect();
        } catch (Exception $e) {
            ShowErrorPage($e);
            die;
        }

        $this->page->OnAfterLogin->Fire(array($userName, $connection));

        $connection->Disconnect();
    }

    private function GetUrlToRedirectAfterLogin() {
        if (GetApplication()->GetSuperGlobals()->IsGetValueSet('redirect')) {
            return GetApplication()->GetSuperGlobals()->GetGetValue('redirect');
        }

        $pageInfos = GetPageInfos();
        foreach ($pageInfos as $pageInfo) {
            if (GetCurrentUserGrantForDataSource($pageInfo['name'])->HasViewGrant()) {
                return $pageInfo['filename'];
            }
        }
        return $this->urlToRedirectAfterLogin;
    }

    public function ProcessMessages() {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $saveidentity = isset($_POST['saveidentity']);

            if ($this->CheckUsernameAndPassword($username, $password, $this->errorMessage)) {
                $this->SaveUserIdentity($username, $password, $saveidentity);
                $this->DoOnAfterLogin($username);
                header('Location: ' . $this->GetUrlToRedirectAfterLogin());
                exit;
            } else {
                $this->lastUserName = $username;
                $this->lastSaveidentity = $saveidentity;
            }
        } elseif (isset($_GET[OPERATION_PARAMNAME]) && $_GET[OPERATION_PARAMNAME] == 'logout') {
            $this->ClearUserIdentity();
        }
    }
}
