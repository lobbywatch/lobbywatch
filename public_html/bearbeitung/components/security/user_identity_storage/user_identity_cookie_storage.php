<?php

include_once dirname(__FILE__) . '/' . '../user_identity.php';
include_once dirname(__FILE__) . '/' . 'user_identity_storage.php';

class UserIdentityCookieStorage implements UserIdentityStorage {
    const userNameCookie = 'username';
    const passwordCookie = 'password';
    const persistentCookie = 'user_identity_persistence';

    /** @var StringHasher */
    private $hasher;

    public function __construct(StringHasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public function SaveUserIdentity(UserIdentity $identity)
    {
        $expire = $this->CalculateCookieExpirationTime($identity->persistent);
        setcookie(self::userNameCookie, $identity->userName, $expire);
        $this->SetPasswordCookieEncrypted($identity->password, $expire);
        setcookie(self::persistentCookie, strval((int)$identity->persistent), $expire);
    }

    public function ClearUserIdentity()
    {
        $this->ClearCookie(self::userNameCookie);
        $this->ClearCookie(self::passwordCookie);
        $this->ClearCookie(self::persistentCookie);
    }

    /**
     * @return UserIdentity|null
     */
    public function getUserIdentity()
    {
        if (!isset($_COOKIE[self::userNameCookie]) || !isset($_COOKIE[self::passwordCookie]))
            return null;

        return new UserIdentity($_COOKIE[self::userNameCookie], $_COOKIE[self::passwordCookie], $this->IsPersistent());
    }

    /**
     * @param string $plainPassword
     * @param int $expire
     */
    private function SetPasswordCookieEncrypted($plainPassword, $expire)
    {
        setcookie(self::passwordCookie, $this->hasher->GetHash($plainPassword), $expire);
    }

    /**
     * @return bool
     */
    private function IsPersistent()
    {
        return isset($_COOKIE[self::persistentCookie]) && (bool)intval($_COOKIE[self::persistentCookie]);
    }

    /**
     * @param bool $persistent
     * @return int
     */
    private function CalculateCookieExpirationTime($persistent)
    {
        return $persistent ? time() + 3600 * 24 * 365 : 0;
    }

    /**
     * @param string $cookie
     */
    private function ClearCookie($cookie)
    {
        setcookie($cookie, '', time() - 3600);
    }
}
