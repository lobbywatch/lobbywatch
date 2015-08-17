<?php

class UserIdentity
{
    /**
     * @var string
     */
    public $userName, $password, $encryptedPassword;

    /**
     * @var bool
     */
    public $persistent;

    public function __construct($userName, $password, $persistent)
    {
        $this->userName = $userName;
        $this->password = $password;
        $this->persistent = $persistent;
    }
}
