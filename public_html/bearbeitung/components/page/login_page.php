<?php

include_once dirname(__FILE__) . '/page.php';
include_once dirname(__FILE__) . '/login_control.php';
include_once dirname(__FILE__) . '/../renderers/list_renderer.php';

class LoginPage extends CommonPage
{
    private $loginControl;
    private $renderer;
    private $pageFileName;
    private $inactivityTimeoutExpired;
    /** @var AbstractUserAuthentication */
    private $userAuthentication;
    private $reCaptcha;

    #region Events
    public $OnAfterLogin;
    public $OnAfterFailedLoginAttempt;
    public $OnBeforeLogout;
    #endregion

    /**
     * @param string $mainPageUrl
     * @param string $pageFileName
     * @param AbstractUserAuthentication $userAuthentication
     * @param ConnectionFactory $connectionFactory
     * @param Captions $captions
     * @param GoogleReCaptcha|null $reCaptcha
     * @param string $startupPage
     */
    public function __construct($mainPageUrl, $pageFileName, $userAuthentication, $connectionFactory, $captions, $reCaptcha, $startupPage)
    {
        parent::__construct('login', 'UTF-8');

        $this->loginControl = new LoginControl(
            $this,
            $mainPageUrl,
            $userAuthentication,
            $connectionFactory,
            $captions,
            $reCaptcha,
            $startupPage
        );

        $this->userAuthentication = $userAuthentication;
        $this->pageFileName = $pageFileName;
        $this->captions = $captions;
        $this->OnAfterLogin = new Event();
        $this->OnAfterFailedLoginAttempt = new Event();
        $this->OnBeforeLogout = new Event();
        $this->renderer = new ViewAllRenderer($this->captions);
        $this->inactivityTimeoutExpired = false;
        $this->reCaptcha = $reCaptcha;
    }

    public function GetPageFileName()
    {
        return $this->pageFileName;
    }

    public function GetLoginControl() {
        return $this->loginControl;
    }

    public function Accept(Renderer $renderer) {
        $renderer->RenderLoginPage($this);
    }

    public function GetReadyPageList() {
        return null;
    }

    public function GetContentEncoding() {
        return 'UTF-8';
    }

    public function GetCaption() {
        return 'Login';
    }

    public function BeginRender() {
        $this->loginControl->ProcessMessages();
        $this->inactivityTimeoutExpired = GetApplication()->GetSuperGlobals()->IsGetValueSet('inactivity_timeout_expired');
    }

    public function EndRender() {
        echo $this->renderer->Render($this);
    }

    public function getType() {
        return PageType::Login;
    }

    public function GetTitle() {
        return $this->GetLocalizerCaptions()->GetMessageString('LoginTitle');
    }

    public function getInactivityTimeoutExpired() {
        return $this->inactivityTimeoutExpired;
    }

    public function getReCaptcha() {
        return $this->reCaptcha;
    }

}
