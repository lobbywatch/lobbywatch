<?php

include_once dirname(__FILE__) . '/' . 'secure_application.php';
include_once dirname(__FILE__) . '/' . 'table_based_user_grants_manager.php';
include_once dirname(__FILE__) . '/' . '../captions.php';

class UserSelfManagement
{
    /** @var SecureApplication */
    private $app;

    /** @var IUserManager */
    private $userManager;

    /** @var IdentityCheckStrategy */
    private $identityCheckStrategy;

    public function __construct(SecureApplication $app, IUserManager $um = null,
                                IdentityCheckStrategy $identityCheckStrategy = null)
    {
        $this->app = $app;
        $this->userManager = $um;
        $this->identityCheckStrategy = $identityCheckStrategy;
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
        $this->ChangePassword($newPassword);
    }

    private function CheckSecurityManagementObjects() {
        return !(is_null($this->userManager) || is_null($this->identityCheckStrategy) ||
            is_null($this->app->GetUserAuthorizationStrategy()) ||
            !$this->app->GetUserAuthorizationStrategy()->IsCurrentUserLoggedIn());
    }

    /**
     * @param string $currentPassword
     * @throws Exception
     */
    private function ValidateCurrentPassword($currentPassword)
    {
        $errorMessage = '';
        if (!$this->identityCheckStrategy->CheckUsernameAndPassword(
            $this->app->GetUserAuthorizationStrategy()->GetCurrentUser(), $currentPassword, $errorMessage)
        ) {
            throw new Exception(GetCaptions('UTF-8')->GetMessageString('UsernamePasswordWasInvalid'));
        }
    }

    /**
     * @param string $newPassword
     */
    private function ChangePassword($newPassword)
    {
        $this->userManager->ChangeUserPassword($this->app->GetUserAuthorizationStrategy()->GetCurrentUserId(),
              $newPassword);
    }
}
