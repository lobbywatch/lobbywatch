<?php

include_once dirname(__FILE__) . '/' . '../../libs/phpass/PasswordHash.php';
include_once dirname(__FILE__) . '/' . 'event.php';

$hasher = array();

function GetHasher($encryptionType) {
    global $hasher;
    if (!array_key_exists($encryptionType, $hasher)) {
        $hasher[$encryptionType] = CreateHasher($encryptionType);
    }

    return $hasher[$encryptionType];
}

/**
 * @param string $encryptionType
 * @return StringHasher
 */
function CreateHasher($encryptionType) {
    if (trim($encryptionType) == '')
        return new PlainStringHasher();
    else if (strtolower($encryptionType) == 'md5')
        return new MD5StringHasher();
    else if (strtolower($encryptionType) == 'sha1')
        return new SHA1StringHasher();
    else if (strtolower($encryptionType) == 'phpass')
        return new PHPassStringHasher();
    else if (strtolower($encryptionType) == 'crypt')
        return new CryptStringHasher();
    else if (strtolower($encryptionType) == 'custom')
        return new CustomStringHasher();
    else
        return new HashFunctionBasedStringHasher($encryptionType);
}

class PHPVersionMismatchException extends Exception {
}

abstract class StringHasher {

    /**
     * Empty constructor
     */
    public function __construct() {
    }

    /**
     * @abstract
     * @param string $string
     * @return string
     */
    public abstract function GetHash($string);

    /**
     * @param string $hash
     * @param string $string
     * @return boolean
     */
    public function CompareHash($hash, $string) {
        return $hash == $this->GetHash($string);
    }

    /**
     * @param string $hash1
     * @param string $hash2
     * @return boolean
     */
    public function CompareTwoHashes($hash1, $hash2) {
        return $hash1 === $hash2;
    }
}

class PlainStringHasher extends StringHasher {
    /**
     * @param string $string
     * @return string
     */
    public function GetHash($string) {
        return $string;
    }
}

class MD5StringHasher extends StringHasher {
    /**
     * @param string $string
     * @return string
     */
    public function GetHash($string) {
        return md5($string);
    }
}

class SHA1StringHasher extends StringHasher {
    /**
     * @param string $string
     * @return string
     */
    public function GetHash($string) {
        return sha1($string);
    }
}

class CryptStringHasher extends StringHasher {
    /**
     * @param string $string
     * @return string
     */
    public function GetHash($string) {
        return crypt($string, 'PHPGENERATOR_SALT');
    }

    /**
     * @param string $hash
     * @param string $string
     * @return boolean
     */
    public function CompareHash($hash, $string) {
        return crypt($string, $hash) == $hash;
    }
}

class HashFunctionBasedStringHasher extends StringHasher {

    /** @var string */
    private $algorithmName;

    /**
     * @param string $algorithmName
     */
    public function __construct($algorithmName) {
        parent::__construct();
        $this->algorithmName = $algorithmName;
    }

    private function CheckPHPVersion() {
        if (version_compare(PHP_VERSION, '5.1.2', '<'))
            throw new PHPVersionMismatchException('Custom hash function requires php 5.1.2 or higher');
    }

    /**
     * @param string $string
     * @return string
     */
    public function GetHash($string) {
        $this->CheckPHPVersion();
        return hash($this->algorithmName, $string);
    }
}

class PHPassStringHasher extends StringHasher {
    /** @var \PasswordHash */
    private $hasher;

    public function __construct() {
        $this->hasher = new PasswordHash(8, FALSE);
    }

    /**
     * @param string $hash
     * @param string $string
     * @return boolean
     */
    public function CompareHash($hash, $string) {
        return $this->hasher->CheckPassword($string, $hash);
    }

    /**
     * @param string $string
     * @return string
     */
    public function GetHash($string) {
        return $this->hasher->HashPassword($string);
    }
}

class CustomStringHasher extends StringHasher {

    #region Events
    /** @var Event */
    public $OnEncryptPassword;
    public $OnVerifyPassword;
    #endregion

    public function __construct()
    {
        parent::__construct();
        $this->OnEncryptPassword = new Event();
        $this->OnVerifyPassword = new Event();
    }

    public function GetHash($string) {
        $result = $string;
        $this->OnEncryptPassword->Fire(array($string, &$result));
        return $result;
    }

    public function CompareHash($hash, $string) {
        $result = parent::CompareHash($hash, $string);
        $this->OnVerifyPassword->Fire(array($string, $hash, &$result));
        return $result;
    }

}
