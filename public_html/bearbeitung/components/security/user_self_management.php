<?php

include_once dirname(__FILE__) . '/' . 'secure_application.php';
include_once dirname(__FILE__) . '/' . 'grant_manager/table_based_user_grant_manager.php';
include_once dirname(__FILE__) . '/' . '../captions.php';

class UserSelfManagement
{
    /** @var SecureApplication */
    private $app;

    /** @var AbstractUserAuthentication */
    private $userAuthentication;

    /** @var IUserManager */
    private $userManager;

    /**
     * @param SecureApplication $app
     * @param null|IUserManager $um
     */
    public function __construct($app, $um = null)
    {
        $this->app = $app;
        $this->userAuthentication = $this->app->GetUserAuthentication();
        $this->userManager = $um;
    }

    /**
     * @param string $currentPassword
     * @param string $newPassword
     * @throws Exception
     */
    public function ValidateAndChangePassword($currentPassword, $newPassword)
    {
        if (!$this->CheckSecurityManagementObjects())
            throw new Exception('This configuration is not secure');
        $this->ValidateCurrentPassword($currentPassword);
        checkPasswordStrength($newPassword); // Afterburned
        $this->ChangePassword($newPassword);
    }

    private function CheckSecurityManagementObjects() {
        return !(is_null($this->userManager) || is_null($this->userAuthentication) || !$this->userAuthentication->isCurrentUserLoggedIn());
    }

    /**
     * @param string $currentPassword
     * @throws Exception
     */
    private function ValidateCurrentPassword($currentPassword)
    {
        if (!$this->userAuthentication->checkUserIdentity(
            $this->userAuthentication->getCurrentUserName(), $currentPassword)
        ) {
            throw new Exception(Captions::getInstance('UTF-8')->GetMessageString('UsernamePasswordWasInvalid'));
        }
    }

    /**
     * @param string $newPassword
     */
    private function ChangePassword($newPassword)
    {
        $this->userManager->changeUserPassword($this->app->GetUserAuthentication()->GetCurrentUserId(), $newPassword);
    }
}
