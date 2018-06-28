<?php

include_once dirname(__FILE__) . '/' . '../user_identity.php';
include_once dirname(__FILE__) . '/' . 'user_identity_storage.php';
include_once dirname(__FILE__) . '/' . 'remember_me_generator.php';
include_once dirname(__FILE__) . '/' . '../../utils/hash_utils.php';

class UserIdentitySessionStorage implements UserIdentityStorage
{
    const KEY_SESSION_IDENTITY = 'current_user';
    const KEY_REMEMBER_ME = 'remember_me';
    const REMEMBER_ME_LIFETIME = 15552000; //3600 * 24 * 180 = 6 months

    /** @var ArrayWrapper */
    private $sessionWrapper;

    /** @var ArrayWrapper */
    private $cookiesWrapper;

    /** @var  bool */
    private $isRealCookies;

    /** @var RememberMeGenerator */
    private $rememberMeGenerator;

    public function __construct(
        ArrayWrapper $sessionWrapper = null,
        ArrayWrapper $cookiesWrapper = null)
    {
        $this->sessionWrapper = is_null($sessionWrapper)
            ? ArrayWrapper::createSessionWrapperForDirectory()
            : $sessionWrapper;

        $this->cookiesWrapper = $this->initCookiesWrapper($cookiesWrapper);

        $this->rememberMeGenerator = new RememberMeGenerator();
    }

    private function initCookiesWrapper($cookiesWrapper) {
        if (is_null($cookiesWrapper)) {
            $this->isRealCookies = true;
            return ArrayWrapper::createCookiesWrapper();
        }
        else {
            $this->isRealCookies = false;
            return $cookiesWrapper;
        }
    }

    public function SaveUserIdentity(UserIdentity $identity)
    {
        $this->sessionWrapper->setValue(self::KEY_SESSION_IDENTITY, $identity);

        if ($identity->persistent) {
            setcookie(
                self::KEY_REMEMBER_ME,
                $this->rememberMeGenerator->encode($identity),
                time() + self::REMEMBER_ME_LIFETIME
            );
        }
    }

    public function ClearUserIdentity()
    {
        $this->sessionWrapper->unsetValue(self::KEY_SESSION_IDENTITY);
        $this->clearCookie(self::KEY_REMEMBER_ME);
    }

    /**
     * @return UserIdentity|null
     */
    public function getUserIdentity()
    {
        if (!$this->sessionWrapper->isValueSet(self::KEY_SESSION_IDENTITY)) {
            return $this->restoreFromRememberMeCookie();
        }

        return $this->sessionWrapper->getValue(self::KEY_SESSION_IDENTITY);
    }

    /**
     * @param string $cookie
     */
    private function clearCookie($cookie)
    {
        if ($this->isRealCookies) {
            setcookie($cookie, '', time() - 3600);
        }
        else {
            $this->cookiesWrapper->unsetValue($cookie);
        }
    }

    private function restoreFromRememberMeCookie()
    {
        if (!$this->cookiesWrapper->isValueSet(self::KEY_REMEMBER_ME)) {
            return null;
        }
        
        return $this->rememberMeGenerator->decode(
            $this->cookiesWrapper->getValue(self::KEY_REMEMBER_ME)
        );
    }
}
