<?php

class UserIdentity
{
    /**
     * @var string
     */
    public $userName, $password;

    /**
     * @var bool
     */
    public $persistent;

    /**
     * @param string $userName
     * @param string $password
     * @param bool $persistent
     */
    public function __construct($userName, $password, $persistent)
    {
        $this->userName = $userName;
        $this->password = $password;
        $this->persistent = $persistent;
    }
}
