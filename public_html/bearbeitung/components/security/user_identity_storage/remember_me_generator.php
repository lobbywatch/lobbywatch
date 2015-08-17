<?php

include_once dirname(__FILE__) . '/' . '../user_identity.php';

class RememberMeGenerator
{
    const DELIMETER = ':';

    public function encode(UserIdentity $userIdentity)
    {
        return base64_encode(sprintf(
            '%s%s%s',
            $userIdentity->userName,
            self::DELIMETER,
            $userIdentity->encryptedPassword
        ));
    }

    public function decode($value)
    {
        $values = explode(self::DELIMETER, base64_decode($value));

        if (count($values) !== 2) {
            return null;
        }

        list($userName, $encryptedPassword) = $values;

        $userIdentity = new UserIdentity($userName, null, true);
        $userIdentity->encryptedPassword = $encryptedPassword;

        return $userIdentity;
    }
}
