<?php

include_once dirname(__FILE__) . '/common_page.php';
include_once dirname(__FILE__) . '/../security/security_feedback.php';
include_once dirname(__FILE__) . '/../security/table_based_user_manager.php';

class ResetPasswordPage extends CommonPage
{
    const ResetPasswordToken = 'reset_password_token';

    /** @var TableBasedUserManager */
    private $userManager;
    /** @var Renderer */
    private $renderer;

    /** @var boolean */
    private $formIsCommit = false;
    /** @var array */
    private $response;

    /** @var Event */
    public $OnPasswordResetComplete;

    /**
     * @param TableBasedUserManager $userManager
     */
    public function __construct($userManager)
    {
        parent::__construct('Reset_password', 'UTF-8');
        $this->userManager = $userManager;
        $this->renderer = new ViewAllRenderer($this->GetLocalizerCaptions());
        $this->OnPasswordResetComplete = new Event();
    }

    public function GetPageFileName()
    {
        return basename(__FILE__);
    }

    public function getType()
    {
        return PageType::ResetPassword;
    }

    public function Accept(Renderer $renderer) {
        $renderer->RenderResetPasswordPage($this);
    }

    public function GetTitle() {
        return $this->GetLocalizerCaptions()->GetMessageString('ResetPassword');
    }

    public function ProcessMessages()
    {
        if (isset($_POST['password'])) {
            $this->commitForm($_POST['password']);
            if ($this->response['success']) {
                if ($this->response['token_exists']) {
                    $this->setSessionVariable(SecurityFeedback::Positive,  $this->GetLocalizerCaptions()->GetMessageString('PasswordResetSuccessfully'));
                } else {
                    $this->setSessionVariable(SecurityFeedback::Negative,  $this->GetLocalizerCaptions()->GetMessageString('PasswordResetFailed'));
                }
                $this->unsetResetPasswordTokenSessionVariable();
            }
        } elseif (isset($_GET['token'])) {
            $this->processToken($_GET['token']);
        } else {
            $this->setSessionVariable(SecurityFeedback::Negative,  $this->GetLocalizerCaptions()->GetMessageString('PasswordResetFailed'));
            $this->goToLoginPage();
        }
    }

    private function commitForm($password) {
        $this->formIsCommit = true;
        try {
            $this->response = array(
                'success' => true,
                'message' => '',
                'token_exists' => true,
            );
            if (!$this->resetPassword($password)) {
                $this->response['token_exists'] = false;
            }
        } catch (Exception $e) {
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
    }

    /**
     * @param string $password
     * @return boolean
     */
    private function resetPassword($password) {
        $result = $this->isResetPasswordTokenSessionVariableSet();
        if ($result) {
            $token = $this->getResetPasswordTokenSessionVariable();
            $userInfo = $this->userManager->getUserInfoByRecoveryPasswordToken($token);
            if (isset($userInfo)) {
                $this->userManager->resetUserPassword($userInfo['Username'], $password);
                $this->doAfterUserResetPassword($userInfo['Username'], $userInfo['Email']);
            } else {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * @param string $token
     * @return boolean
     */
    public function processToken($token) {
        if ($this->userManager->recoveryPasswordTokenExists($token)) {
            $this->setSessionVariable(self::ResetPasswordToken, $token);
        } else {
            $this->setSessionVariable(SecurityFeedback::Negative,  $this->GetLocalizerCaptions()->GetMessageString('PasswordResetFailed'));
            $this->goToLoginPage();
        }
    }

    public function BeginRender() {
        $this->ProcessMessages();
    }

    public function EndRender() {
        echo $this->renderer->Render($this);
    }

    public function GetReadyPageList() {
        return null;
    }

    public function getResponse() {
        return $this->response;
    }

    public function formIsCommit() {
        return $this->formIsCommit;
    }

    private function isResetPasswordTokenSessionVariableSet() {
        return GetApplication()->IsSessionVariableSet(self::ResetPasswordToken);
    }

    private function getResetPasswordTokenSessionVariable() {
        return GetApplication()->GetSessionVariable(self::ResetPasswordToken);
    }

    private function unsetResetPasswordTokenSessionVariable() {
        GetApplication()->UnSetSessionVariable(self::ResetPasswordToken);
    }

    private function setSessionVariable($name, $value) {
        GetApplication()->SetSessionVariable($name, $value);
    }

    private function goToLoginPage() {
        header('Location: login.php');
        exit;
    }

    /**
     * @param string $username
     * @param string $email
     */
    private function doAfterUserResetPassword($username, $email) {
        $this->OnPasswordResetComplete->Fire(array($username, $email));
    }

}
