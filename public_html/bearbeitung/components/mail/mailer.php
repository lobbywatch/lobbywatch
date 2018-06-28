<?php

class MailerType
{
    const Sendmail = 0;
    const SMTP = 1;
    const Mail = 2;
    const Qmail = 3;
}

class SMTPOptions
{
    /** @var string */
    private $host;
    /** @var integer */
    private $port;
    /** @var boolean */
    private $useAuthentication;
    /** @var string */
    private $username;
    /** @var string */
    private $password;
    /** @var string */
    private $encryption;

    /**
     * @param string $host
     * @param integer $port
     * @param boolean $useAuthentication
     * @param string $username
     * @param string $password
     * @param string $encryption
     */
    public function __construct($host, $port, $useAuthentication, $username, $password, $encryption) {
        $this->host = $host;
        $this->port = $port;
        $this->useAuthentication = $useAuthentication;
        $this->username = $username;
        $this->password = $password;
        $this->encryption = $encryption;
    }

    /** @return string */
    public function getHost() {
        return $this->host;
    }

    /** @return integer */
    public function getPort() {
        return $this->port;
    }

    /** @return boolean */
    public function getUseAuthentication() {
        return $this->useAuthentication;
    }

    /** @return string */
    public function getUsername() {
        return $this->username;
    }

    /** @return string */
    public function getPassword() {
        return $this->password;
    }

    /** @return string */
    public function getEncryption() {
        return $this->encryption;
    }
}

class MailerOptions
{
    /** @var int */
    private $type;
    /** @var string */
    private $fromEmail;
    /** @var string */
    private $fromName;
    /** @var SMTPOptions */
    private $smtpOptions;

    /**
     * @param int $type
     * @param string $fromEmail
     * @param string $fromName
     * @param SMTPOptions $smtpOptions
     */
    public function __construct($type, $fromEmail, $fromName, $smtpOptions = null) {
        $this->type = $type;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
        $this->smtpOptions = $smtpOptions;
    }

    /** @return int */
    public function getType() {
        return $this->type;
    }

    /** @return string */
    public function getFromEmail() {
        return $this->fromEmail;
    }

    /** @return string */
    public function getFromName() {
        return $this->fromName;
    }

    /** @return SMTPOptions */
    public function smtpOptions() {
        return $this->smtpOptions;
    }
}

abstract class Mailer
{
    /** @var Mailer[] */
    private static $instances = array();

    /** @var MailerOptions */
    private $mailerOptions;

    /**
     * @param MailerOptions $options
     */
    public function __construct(MailerOptions $options) {
        $this->mailerOptions = $options;
    }

    /**
     * @return MailerOptions
     */
    public function getMailerOptions() {
        return $this->mailerOptions;
    }

    /**
     * @param MailerOptions $options
     * @return Mailer
     */
    public static function getInstance($options)
    {
        $className = get_called_class();
        if (!array_key_exists($className, self::$instances)) {
            self::$instances[$className] = new $className($options);
        }

        return self::$instances[$className];
    }

    /**
     * @param string|array $source
     * @return array
     */
    protected function getPreparedArray($source) {
        $result = array();
        if (is_array($source)) {
            if ($this->is_associative_array($source) ) {
                foreach ($source as $key => $value)
                {
                    $result[$key] = $value;
                }
            } else {
                foreach ($source as $value)
                {
                    $result[$value] = '';
                }
            }
        } elseif (!empty($source)) {
            $result[$source] = '';
        }
        return $result;
    }

    /**
     * @param array $arr
     * @return boolean
     */
    private function is_associative_array($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * @param string|array $recipients
     * @param string $subject
     * @param string $body
     * @param string|array $attachments
     * @param string|array $cc
     * @param string|array $bcc
     */
    abstract public function send($recipients, $subject, $body, $attachments = '', $cc = '', $bcc = '');

}
