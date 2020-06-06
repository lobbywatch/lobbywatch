<?php

include_once dirname(__FILE__) . '/common_page.php';
include_once dirname(__FILE__) . '/../security/security_feedback.php';
include_once dirname(__FILE__) . '/../security/table_based_user_manager.php';
include_once dirname(__FILE__) . '/page_utils.php';

class ResendVerificationPage extends CommonPage
{
    /** @var TableBasedUserManager */
    private $userManager;
    /** @var Mailer */
    private $mailer;
    /** @var  GoogleReCaptcha|null */
    private $reCaptcha;
    /** @var Renderer */
    private $renderer;

    /** @var boolean */
    private $formIsCommit = false;
    /** @var array */
    private $response;

    /**
     * @param TableBasedUserManager $userManager
     * @param Mailer $mailer
     * @param GoogleReCaptcha|null $reCaptcha
     */
    public function __construct($userManager, $mailer, $reCaptcha)
    {
        parent::__construct('Resend_verification', 'UTF-8');
        $this->userManager = $userManager;
        $this->mailer = $mailer;
        $this->reCaptcha = $reCaptcha;
        $this->renderer = new ViewAllRenderer($this->GetLocalizerCaptions());
    }

    public function GetPageFileName()
    {
        return basename(__FILE__);
    }

    public function getType()
    {
        return PageType::ResendVerification;
    }

    public function Accept(Renderer $renderer) {
        $renderer->RenderResendVerificationPage($this);
    }

    public function GetTitle() {
        return $this->GetLocalizerCaptions()->GetMessageString('ResendVerificationEmail');
    }

    public function ProcessMessages()
    {
        if (isset($_POST['email'])) {
            $this->commitForm($_POST['email']);
        }
    }

    private function commitForm($email) {
        $this->formIsCommit = true;
        try {
            $this->response = array(
                'success' => true,
                'message' => ''
            );
            $this->processReCaptcha();
            $this->processResendVerificationEmail($email);
            $this->setSessionVariable(SecurityFeedback::Positive,  $this->GetLocalizerCaptions()->GetMessageString('VerificationLinkResent'));
        } catch (Exception $e) {
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
    }

    /** @throw LogicException */
    private function processReCaptcha() {
        if ($this->reCaptcha) {
            $reCaptchaResponse = $this->reCaptcha->verifyResponse();
            if (!$reCaptchaResponse->IsSuccess()) {
                throw new LogicException($reCaptchaResponse->getErrorMessage());
            }
        }
    }

    private function processResendVerificationEmail($email) {
        $userInfo = $this->userManager->getUserInfoByUsernameOrEmail($email);
        if (!isset($userInfo)) {
            throw new LogicException($this->GetLocalizerCaptions()->GetMessageString('AccountNotFound'));
        } elseif ($userInfo['Status'] != UserStatus::WaitingForVerification) {
            throw new LogicException($this->GetLocalizerCaptions()->GetMessageString('ResendVerificationAccountVerified'));
        }
        $userToken = GenerateToken();
        $this->userManager->setAccountVerificationToken($userInfo['Username'], $userToken);
        $this->resendVerificationEmail($userInfo['Username'], $userInfo['Email'], $userToken);
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $token
     */
    private function resendVerificationEmail($username, $email, $token) {
        $this->mailer->send($email, RenderAccountVerificationMailSubject($this), RenderAccountVerificationMailBody($this, $username, $token));
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

    private function setSessionVariable($name, $value) {
        GetApplication()->SetSessionVariable($name, $value);
    }

    /** @return GoogleReCaptcha */
    public function getReCaptcha() {
        return $this->reCaptcha;
    }

}
