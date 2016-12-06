<?php

include_once dirname(__FILE__) . '/page.php';
include_once dirname(__FILE__) . '/login_control.php';
include_once dirname(__FILE__) . '/../renderers/list_renderer.php';

class LoginPage extends CommonPage
{
    private $loginControl;
    private $renderer;
    private $header;
    private $footer;
    private $pageFileName;

    /** @var Event */
    public $OnAferLogin;

    public function __construct(
        $mainPageUrl,
        $pageFileName,
        IdentityCheckStrategy $identityCheckStrategy,
        UserIdentityStorage $userIdentityStorage,
        ConnectionFactory $connectionFactory,
        $canLoginAsGuest,
        Captions $captions)
    {
        parent::__construct('login', 'UTF-8');

        $this->loginControl = new LoginControl(
            $this,
            $mainPageUrl,
            $identityCheckStrategy,
            $userIdentityStorage,
            $connectionFactory,
            $canLoginAsGuest,
            $captions
        );

        $this->pageFileName = $pageFileName;
        $this->captions = $captions;
        $this->OnAfterLogin = new Event();
        $this->renderer = new ViewAllRenderer($this->captions);
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

    public function SetHeader($value) {
        $this->header = $value;
    }

    public function GetHeader() {
        return $this->RenderText($this->header);
    }

    public function SetFooter($value) {
        $this->footer = $value;
    }

    public function GetFooter() {
        return $this->RenderText($this->footer);
    }

    public function BeginRender() {
        $this->loginControl->ProcessMessages();
    }

    public function EndRender() {
        echo $this->renderer->Render($this);
    }

    public function getType()
    {
        return PageType::Login;
    }

    public function GetTitle() {
        return $this->GetLocalizerCaptions()->GetMessageString('LoginTitle');
    }
}
