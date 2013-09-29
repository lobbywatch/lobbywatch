<?php

/* Exception utils */
function RaiseNotSupportedException()
{
    echo "Not supported";
}

function CreateConnectionOptionsArray($host, $database, $login, $password, $port = null)
{
    $result = array();
    if (isset($host))
        $result['server'] = $host;
    if (isset($database))
        $result['database'] = $database;
    if (isset($login))
        $result['username'] = $login;
    if (isset($password))
        $result['password'] = $password;
    if (isset($port))
        $result['port'] = $port;
    return $result;
}

class SMVersion
{
    private $major;
    private $minor;

    public function __construct($major, $minor)
    {
        $this->major = intval(ltrim($major, '0'));
        $this->minor = intval(ltrim($minor, '0'));
    }

    public function SetMinor($minor)
    {
        $this->minor = intval(ltrim($minor, '0'));
    }

    public function SetMajor($major)
    {
        $this->major = intval(ltrim($major, '0'));
    }

    public function IsServerVersion($major, $minor = 0)
    {
        if ($this->major >= $major)
            return true;
        else if ($this->major == $major)
            return $this->minor >= $minor;
        else
            return false;
    }

    public function AsString()
    {
        return $this->major . '.' . $this->minor; 
    }
}