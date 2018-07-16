<?php

include_once dirname(__FILE__) . '/' . '../user_identity.php';

class RememberMeGenerator
{
    const DELIMETER = ':';

    /**
     * @param UserIdentity $userIdentity
     * @return string
     */
    public function encode(UserIdentity $userIdentity)
    {
        return base64_encode(sprintf(
            '%s%s%s',
            $userIdentity->userName,
            self::DELIMETER,
            $userIdentity->password
        ));
    }

    /**
     * @param string $value
     * @return null|UserIdentity
     */
    public function decode($value)
    {
        $values = explode(self::DELIMETER, base64_decode($value));

        if (count($values) !== 2) {
            return null;
        }

        list($userName, $password) = $values;

        $userIdentity = new UserIdentity($userName, $password, true);

        return $userIdentity;
    }
}
