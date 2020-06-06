<?php

include_once dirname(__FILE__) . '/common_page.php';
include_once dirname(__FILE__) . '/registration_form.php';
include_once dirname(__FILE__) . '/../renderers/list_renderer.php';
include_once dirname(__FILE__) . '/../security/table_based_user_manager.php';
include_once dirname(__FILE__) . '/page_utils.php';

class RegistrationPage extends CommonPage
{
    /** @var TableBasedUserManager */
    private $userManager;
    /** @var Mailer */
    private $mailer;
    /** @var  GoogleReCaptcha */
    private $reCaptcha;

    /** @var RegistrationForm */
    private $registrationForm;
    /** @var Renderer */
    private $renderer;

    /** @var Event */
    public $OnBeforeUserRegistration;
    public $OnAfterUserRegistration;

    /**
     * @param TableBasedUserManager $userManager
     * @param Mailer $mailer
     * @param GoogleReCaptcha $reCaptcha
     */
    public function __construct($userManager, $mailer, $reCaptcha)
    {
        parent::__construct('Register', 'UTF-8');

        $this->userManager = $userManager;
        $this->mailer = $mailer;
        $this->reCaptcha = $reCaptcha;
        $this->renderer = new ViewAllRenderer($this->GetLocalizerCaptions());
        $this->registrationForm = new RegistrationForm($this);
        $this->OnBeforeUserRegistration = new Event();
        $this->OnAfterUserRegistration = new Event();
    }

    public function GetPageFileName()
    {
        return basename(__FILE__);
    }

    public function getType()
    {
        return PageType::Register;
    }

    public function Accept(Renderer $renderer) {
        $renderer->RenderRegistrationPage($this);
    }

    public function GetTitle() {
        return $this->GetLocalizerCaptions()->GetMessageString('RegistrationPageTitle');
    }

    public function GetReadyPageList() {
        return null;
    }

    public function getRegistrationForm() {
        return $this->registrationForm;
    }

    public function getUserManager() {
        return $this->userManager;
    }

    public function BeginRender() {
        $this->registrationForm->ProcessMessages();
    }

    public function EndRender() {
        echo $this->renderer->Render($this);
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $email
     */
    public function registerNewUser($username, $password, $email) {
        $this->doBeforeUserRegistration($username, $email, $password);
        $userToken = GenerateToken();
        $this->userManager->addUserEx($username, $password, $email, $userToken, UserStatus::WaitingForVerification);
        $this->sendAccountVerificationEmail($username, $email, $userToken);
        $this->doAfterUserRegistration($username, $email);
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $token
     */
    private function sendAccountVerificationEmail($username, $email, $token) {
        $this->mailer->send($email, RenderAccountVerificationMailSubject($this), RenderAccountVerificationMailBody($this, $username, $token));
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     */
    private function doBeforeUserRegistration($username, $email, $password) {
        $allowRegistration = true;
        $errorMessage = '';
        $this->OnBeforeUserRegistration->Fire(array($username, $email, $password, &$allowRegistration, &$errorMessage));
        if (!$allowRegistration) {
            throw new LogicException($errorMessage);
        }
    }

    /**
     * @param string $username
     * @param string $email
     */
    private function doAfterUserRegistration($username, $email) {
        $this->OnAfterUserRegistration->Fire(array($username, $email));
    }

    /** @return GoogleReCaptcha */
    public function getReCaptcha() {
        return $this->reCaptcha;
    }

}
