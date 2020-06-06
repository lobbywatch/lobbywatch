<?php

include_once dirname(__FILE__) . '/common_page.php';
include_once dirname(__FILE__) . '/../security/table_based_user_manager.php';
include_once dirname(__FILE__) . '/../security/security_feedback.php';
include_once dirname(__FILE__) . '/../renderers/template_renderer.php';

class PasswordRecoveryPage extends CommonPage
{
    /** @var TableBasedUserManager */
    private $userManager;
    /** @var Mailer */
    private $mailer;
    /** @var  GoogleReCaptcha */
    private $reCaptcha;
    /** @var Renderer */
    private $renderer;

    /** @var boolean */
    private $formIsCommit = false;
    /** @var array */
    private $response;

    /** @var Event */
    public $OnPasswordResetRequest;

    /**
     * @param TableBasedUserManager $userManager
     * @param Mailer $mailer
     * @param GoogleReCaptcha $reCaptcha
     */
    public function __construct($userManager, $mailer, $reCaptcha)
    {
        parent::__construct('Recovering_password', 'UTF-8');
        $this->userManager = $userManager;
        $this->mailer = $mailer;
        $this->reCaptcha = $reCaptcha;
        $this->renderer = new ViewAllRenderer($this->GetLocalizerCaptions());
        $this->OnPasswordResetRequest = new Event();
    }

    public function GetPageFileName()
    {
        return basename(__FILE__);
    }

    public function getType()
    {
        return PageType::PasswordRecovery;
    }

    public function Accept(Renderer $renderer) {
        $renderer->RenderPasswordRecoveryPage($this);
    }

    public function GetTitle() {
        return $this->GetLocalizerCaptions()->GetMessageString('PasswordRecovery');
    }

    public function ProcessMessages()
    {
        if (isset($_POST['account-name'])) {
            $this->commitForm($_POST['account-name']);
        }
    }

    private function commitForm($accountName) {
        $this->formIsCommit = true;
        try {
            $this->response = array(
                'success' => true,
                'message' => ''
            );
            $this->processReCaptcha();
            $this->recoverPassword($accountName);
            $this->setSessionVariable(SecurityFeedback::Positive,  $this->GetLocalizerCaptions()->GetMessageString('RecoveringPasswordLinkSent'));
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

    /**
     * @param string $accountName
     */
    private function recoverPassword($accountName) {
        $userInfo = $this->userManager->getUserInfoByUsernameOrEmail($accountName);
        if (!isset($userInfo)) {
            throw new LogicException($this->GetLocalizerCaptions()->GetMessageString('AccountNotFound'));
        } elseif ($userInfo['Status'] == UserStatus::WaitingForVerification) {
            throw new LogicException($this->GetLocalizerCaptions()->GetMessageString('AccountHasNotBeenVerified'));
        }
        $userToken = GenerateToken();
        $this->userManager->setRecoveringPasswordToken($userInfo['Username'], $userToken);
        $this->sendRecoveringPasswordEmail($userInfo['Username'], $userInfo['Email'], $userToken);
        $this->doAfterUserRequestedPasswordReset($userInfo['Username'], $userInfo['Email']);
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $token
     */
    private function sendRecoveringPasswordEmail($username, $email, $token) {
        $this->mailer->send($email, $this->renderRecoveringPasswordMailSubject(), $this->renderRecoveringPasswordMailBody($username, $token));
    }

    /**
     * @return string
     */
    private function renderRecoveringPasswordMailSubject() {
        $customParams = array();
        $template = $this->getCustomTemplate(PagePart::Mail, PageMode::MailRecoveringPasswordSubject, 'mail/recovering_password_subject.tpl', $customParams);

        $templateRenderer = GetTemplateRenderer();
        return $templateRenderer->render($template, $customParams);
    }

    /**
     * @param string $username
     * @param string $token
     * @return string
     */
    private function renderRecoveringPasswordMailBody($username, $token) {
        $customParams = array();
        $template = $this->getCustomTemplate(PagePart::Mail, PageMode::MailRecoveringPasswordBody, 'mail/recovering_password_body.tpl', $customParams);

        $params = array_merge(
            $customParams,
            array(
                'UserName' => $username,
                'ResetPasswordLink' => $this->getResetPasswordLink($token),
                'SiteURL' => GetSiteURL()
            )
        );

        $templateRenderer = GetTemplateRenderer();
        return $templateRenderer->render($template, $params);
    }

    /**
     * @param string $token
     * @return string
     */
    private function getResetPasswordLink($token) {
        $linkBuilder = new LinkBuilder(GetSiteURL() . 'reset_password.php');
        $linkBuilder->AddParameter('token', $token);
        return $linkBuilder->GetLink();
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

    /**
     * @param string $username
     * @param string $email
     */
    private function doAfterUserRequestedPasswordReset($username, $email) {
        $this->OnPasswordResetRequest->Fire(array($username, $email));
    }

    /** @return GoogleReCaptcha */
    public function getReCaptcha() {
        return $this->reCaptcha;
    }

}
