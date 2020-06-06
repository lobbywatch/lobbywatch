<?php

include_once dirname(__FILE__) . '/../security/table_based_user_manager.php';
include_once dirname(__FILE__) . '/../security/security_feedback.php';

class RegistrationForm
{
    /** @var RegistrationPage */
    private $registrationPage;
    /** @var boolean */
    private $isCommit = false;
    /** @var array */
    private $response;

    /**
     * @param RegistrationPage $page
     */
    public function __construct(RegistrationPage $page) {
        $this->registrationPage = $page;
    }

    /**
     * @return RegistrationPage
     */
    public function getRegistrationPage()
    {
        return $this->registrationPage;
    }

    public function Accept(Renderer $renderer) {
        $renderer->RenderRegistrationForm($this);
    }

    public function ProcessMessages() {
        if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
            $this->commit($_POST['username'], $_POST['password'], $_POST['email']);
        } elseif (isset($_GET['token'])) {
            $this->verifyUserAccountByToken($_GET['token']);
        }
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $email
     */
    private function commit($username, $password, $email) {
        $this->isCommit = true;
        try {
            $this->response = array(
                'success' => true,
                'message' => ''
            );
            $this->processReCaptcha();
            $this->checkUserExists($username);
            $this->checkEmailExists($email);
            $this->getRegistrationPage()->registerNewUser($username, $password, $email);
            $this->setSessionVariable(SecurityFeedback::Positive,  sprintf($this->registrationPage->GetLocalizerCaptions()->GetMessageString('AccountRegistered'), $email));
        } catch (Exception $e) {
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
    }

    /** @throw LogicException */
    private function processReCaptcha() {
        if ($this->getRegistrationPage()->getReCaptcha()) {
            $recaptchaResponse = $this->getRegistrationPage()->getReCaptcha()->verifyResponse();
            if (!$recaptchaResponse->isSuccess()) {
                throw new LogicException($recaptchaResponse->getErrorMessage());
            }
        }
    }

    /**
     * @param string $username
     * @throw LogicException
     */
    private function checkUserExists($username) {
        if ($this->getRegistrationPage()->getUserManager()->checkUserExists($username)) {
            throw new LogicException(sprintf($this->registrationPage->GetLocalizerCaptions()->GetMessageString('UserExists'), $username));
        }
    }

    /**
     * @param string $email
     * @throw LogicException
     */
    private function checkEmailExists($email) {
        if ($this->getRegistrationPage()->getUserManager()->checkEmailExists($email)) {
            throw new LogicException(sprintf($this->registrationPage->GetLocalizerCaptions()->GetMessageString('EmailExists'), $email));
        }
    }

    /**
     * @param string $token
     * @return boolean
     */
    private function verifyUserAccountByToken($token) {
        $verificationResult = $this->getRegistrationPage()->getUserManager()->updateUserStatusByToken($token);
        if ($verificationResult) {
            $this->setSessionVariable(SecurityFeedback::Positive,  $this->registrationPage->GetLocalizerCaptions()->GetMessageString('AccountVerified'));
        } else {
            $this->setSessionVariable(SecurityFeedback::Negative,  $this->registrationPage->GetLocalizerCaptions()->GetMessageString('AccountVerificationFailed'));
        }
        $this->goToLoginPage();
    }

    public function getResponse() {
        return $this->response;
    }

    public function isCommit() {
        return $this->isCommit;
    }

    private function goToLoginPage() {
        header('Location: login.php');
        exit;
    }

    private function setSessionVariable($name, $value) {
        GetApplication()->SetSessionVariable($name, $value);
    }

}
