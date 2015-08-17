<?php

include_once dirname(__FILE__) . '/' . '../base_user_auth.php';
include_once dirname(__FILE__) . '/' . '../user_identity.php';
include_once dirname(__FILE__) . '/' . 'user_identity_storage.php';
include_once dirname(__FILE__) . '/' . 'remember_me_generator.php';

class UserIdentitySessionStorage implements UserIdentityStorage
{
    const KEY_SESSION_IDENTITY = 'current_user';
    const KEY_REMEMBER_ME = 'remember_me';
    const REMEMBER_ME_LIFETIME = 15552000; //3600 * 24 * 180 = 6 months

    /**
     * @var ArrayWrapper
     */
    private $sessionWrapper;

    /**
     * @var ArrayWrapper
     */
    private $cookiesWrapper;

    /**
     * @var IdentityCheckStrategy
     */
    private $identityCheckStrategy;

    /**
     * @var RememberMeGenerator
     */
    private $rememberMeGenerator;

    public function __construct(
        IdentityCheckStrategy $identityCheckStrategy,
        ArrayWrapper $sessionWrapper = null,
        ArrayWrapper $cookiesWrapper = null)
    {
        $this->identityCheckStrategy = $identityCheckStrategy;
        $this->sessionWrapper = is_null($sessionWrapper)
            ? ArrayWrapper::createSessionWrapperForDirectory()
            : $sessionWrapper;
        $this->cookiesWrapper = is_null($cookiesWrapper)
            ? ArrayWrapper::createCookiesWrapper()
            : $cookiesWrapper;
        $this->rememberMeGenerator = new RememberMeGenerator();
    }

    public function SaveUserIdentity(UserIdentity $identity)
    {
        $identity->encryptedPassword = $this->identityCheckStrategy
            ->GetEncryptedPassword($identity->password);
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
        $this->ClearCookie(self::KEY_REMEMBER_ME);
    }

    /**
     * @param string $newPassword
     */
    public function UpdatePassword($newPassword)
    {
        if (!$this->sessionWrapper->isValueSet(self::KEY_SESSION_IDENTITY)) {
            throw new LogicException('cannot update password of the empty user');
        }

        $userIdentity = $this->getUserIdentity();
        $userIdentity->password = $newPassword;
        $this->SaveUserIdentity($userIdentity);
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
    private function ClearCookie($cookie)
    {
        setcookie($cookie, '', time() - 3600);
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
