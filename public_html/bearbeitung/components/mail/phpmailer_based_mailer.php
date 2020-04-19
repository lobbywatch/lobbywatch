<?php

include_once dirname(__FILE__) . '/' . 'mailer.php';
include_once dirname(__FILE__) . '/' . '../../libs/phpmailer/PHPMailerAutoload.php';

class PHPMailerBasedMailer extends Mailer
{
    /** @var PHPMailer */
    private $phpMailer;

    /**
     * @param MailerOptions $options
     */
    public function __construct(MailerOptions $options) {
        parent::__construct($options);

        $this->phpMailer = $this->createPHPMailer();
        $this->phpMailer->CharSet = 'UTF-8';
        $this->phpMailer->isHTML(true);
        $this->phpMailer->From = $options->getFromEmail();
        $this->phpMailer->FromName = $options->getFromName();
        switch ($options->getType()) {
            case MailerType::SMTP:
                $this->phpMailer->isSMTP();
                $this->setSMTPOptions($options->smtpOptions());
                break;
            case MailerType::Mail:
                $this->phpMailer->isMail();
                break;
            case MailerType::Qmail:
                $this->phpMailer->isQmail();
                break;
            case MailerType::Sendmail:
                $this->phpMailer->isSendmail();
        }
    }

    protected function createPHPMailer() {
        return new PHPMailer(true);
    }

    /**
     * @param SMTPOptions $options
     * @throws Exception
     */
    private function setSMTPOptions(SMTPOptions $options) {
        if (!isset($options)) {
            throw new Exception('SMTP options are not defined.');
        }
        $this->phpMailer->Host = $options->getHost();
        $this->phpMailer->Port = $options->getPort();
        $this->phpMailer->SMTPAuth = $options->getUseAuthentication();
        $this->phpMailer->SMTPSecure = $options->getEncryption();
        $this->phpMailer->Username = $options->getUsername();
        $this->phpMailer->Password = $options->getPassword();
    }

    /**
     * @param string|array $recipients
     * @param string $subject
     * @param string $body
     * @param string|array $attachments
     * @param string|array $cc
     * @param string|array $bcc
     * @throws phpmailerException
     */
    public function send($recipients, $subject, $body, $attachments = '', $cc = '', $bcc = '') {
        $this->phpMailer->clearAllRecipients();
        $this->phpMailer->clearAttachments();

        $this->addRecipients($recipients);
        $this->addAttachments($attachments);
        $this->addCarbonCopyRecipients($cc);
        $this->addBlindCarbonCopyRecipients($bcc);

        $this->phpMailer->Subject = $subject;
        $this->phpMailer->Body = $body;

        $this->phpMailer->send();
    }

    /**
     * @param string|array $recipients
     */
    private function addRecipients($recipients) {
        foreach ($this->getPreparedArray($recipients) as $email => $name) {
            $this->phpMailer->addAddress($email, $name);
        }
    }

    /**
     * @param string|array $recipients
     */
    private function addCarbonCopyRecipients($recipients) {
        foreach ($this->getPreparedArray($recipients) as $email => $name) {
            $this->phpMailer->addCC($email, $name);
        }
    }

    /**
     * @param string|array $recipients
     */
    private function addBlindCarbonCopyRecipients($recipients) {
        foreach ($this->getPreparedArray($recipients) as $email => $name) {
            $this->phpMailer->addBCC($email, $name);
        }
    }

    /**
     * @param string|array $attachments
     */
    private function addAttachments($attachments) {
        foreach ($this->getPreparedArray($attachments) as $filePath => $fileName) {
            $this->phpMailer->addAttachment($filePath, $fileName);
        }
    }

    /**
     * @param int $debugLevel
     */
    public function setSMTPDebug($debugLevel) {
        $this->phpMailer->SMTPDebug = $debugLevel;         
    }

    /** @return PHPMailer */
    public function getPHPMailer() {
        return $this->phpMailer;
    }
}
