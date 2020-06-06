<?php

include_once dirname(__FILE__) . '/' . '../utils/array_wrapper.php';
include_once dirname(__FILE__) . '/' . '../captions.php';

class GoogleReCaptchaResponse
{
    /** @var Captions */
    private $localizer;
    /** @var  array */
    private $verificationResult;

    /** @param array */
    public function __construct($verificationResult = array('success' => true))
    {
        $this->verificationResult = $verificationResult;
        $this->localizer = Captions::getInstance('UTF-8');
    }

    public function isSuccess() {
        return $this->verificationResult['success'] == true;
    }

    public function getErrorMessage() {
        $result = '';
        if (array_key_exists('error-codes', $this->verificationResult)) {
            if (in_array('curl-extension-is-not-enabled', $this->verificationResult['error-codes'])) {
                $result = $this->getCURLExtensionMustBeEnabledMessage();
            } elseif (in_array('missing-input-secret', $this->verificationResult['error-codes'])) {
                $result = $this->localizer->GetMessageString('RecaptchaMissingInputSecret');
            } elseif (in_array('invalid-input-secret', $this->verificationResult['error-codes'])) {
                $result = $this->localizer->GetMessageString('RecaptchaInvalidInputSecret');
            } elseif (in_array('missing-input-response', $this->verificationResult['error-codes'])) {
                $result = $this->localizer->GetMessageString('RecaptchaMissingInputResponse');
            } elseif (in_array('invalid-input-response', $this->verificationResult['error-codes'])) {
                $result = $this->localizer->GetMessageString('RecaptchaInvalidInputResponse');
            } elseif (in_array('bad-request', $this->verificationResult['error-codes'])) {
                $result = $this->localizer->GetMessageString('RecaptchaBadRequest');
            } elseif (in_array('timeout-or-duplicate', $this->verificationResult['error-codes'])) {
                $result = $this->localizer->GetMessageString('RecaptchaTimeout');
            }
        }
        return $result;
    }

    private function getCURLExtensionMustBeEnabledMessage() {
        return '<a href="http://php.net/manual/en/curl.installation.php" target="_blank">cURL</a> extension must be enabled to check reCAPTCHA response.';
    }

}

class GoogleReCaptchaType {
    const Checkbox = 'checkbox';
    const Invisible = 'invisible';
}

class GoogleReCaptcha {

    /** @var string */
    private $siteKey;

    /** @var string */
    private $secretKey;

    /** @var string */
    private $type;

    /** @var boolean */
    private $useDarkColorTheme = false;

    /** @var string */
    private $recaptchaPostVariableName = "g-recaptcha-response";

    /** @var string */
    private $lastRecaptchaResponseSessionVariableName = 'last_recaptcha_response';

    /** @var string */
    private $siteVerifyUrl = "https://www.google.com/recaptcha/api/siteverify";

    /**
     * @param string $siteKey
     * @param string $secretKey
     * @param string $type
     * @param boolean $useDarkColorTheme
     */
    public function __construct($siteKey, $secretKey, $type, $useDarkColorTheme = false) {
        $this->siteKey = $siteKey;
        $this->secretKey = $secretKey;
        $this->type = $type;
        $this->useDarkColorTheme = $useDarkColorTheme;
    }

    /** @return GoogleReCaptchaResponse */
    public function verifyResponse() {
        if (!function_exists('curl_init')) {
            return new GoogleReCaptchaResponse(array('success' => false, 'error-codes' => array('curl-extension-is-not-enabled')));
        }

        $lastReCaptchaResponse = GetApplication()->GetSessionVariable($this->lastRecaptchaResponseSessionVariableName);
        $postWrapper = ArrayWrapper::createPostWrapper();
        $response = $postWrapper->getValue($this->recaptchaPostVariableName);

        if (!empty($response) && $response === $lastReCaptchaResponse) {
            return new GoogleReCaptchaResponse();
        }

        $verificationResult = $this->getResponseVerification($response);
        GetApplication()->SetSessionVariable($this->lastRecaptchaResponseSessionVariableName, $response);

        return new GoogleReCaptchaResponse($verificationResult);
    }

    /**
     * @param string $response
     * @return array
    */
    private function getResponseVerification($response) {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $this->siteVerifyUrl,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => array(
                    'secret' => $this->secretKey,
                    'response' => $response
                ),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false
            )
        );
        $output = curl_exec($curl);
        curl_close($curl);
        return json_decode($output, true);
    }

    /**
     * @return string
     */
    public function getSiteKey() {
        return $this->siteKey;
    }

    /**
     * @return bool
     */
    public function isCheckboxCaptcha() {
        return $this->type == GoogleReCaptchaType::Checkbox;
    }

    /**
     * @return bool
     */
    public function isInvisibleCaptcha() {
        return $this->type == GoogleReCaptchaType::Invisible;
    }

    /** @return boolean */
    public function getUseDarkColorTheme() {
        return $this->useDarkColorTheme;
    }

    /** @param boolean */
    public function setUseDarkColorTheme($useDarkColorTheme) {
        $this->useDarkColorTheme = $useDarkColorTheme;
    }
}
