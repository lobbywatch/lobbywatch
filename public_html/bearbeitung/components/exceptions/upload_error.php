<?php

include_once dirname(__FILE__) . '/abstract_localized_exception.php';

class UploadError extends AbstractLocalizedException
{
    private $fieldName;
    private $caption;

    /**
     * @param string $fieldName
     * @param string $caption
     * @param string $message
     * @param int $code
     */
    public function __construct($fieldName, $caption, $message, $code)
    {
        parent::__construct($message, $code);
        $this->fieldName = $fieldName;
        $this->caption = $caption;
    }

    public function GetFieldName()
    {
        return $this->fieldName;
    }

    public function GetCaption()
    {
        return $this->caption;
    }

    /**
     * @param Captions $captions
     *
     * @return string
     */
    public function getLocalizedMessage(Captions $captions)
    {
        return sprintf(
            $captions->GetMessageString($this->getMessage()),
            $this->caption,
            $this->getCode()
        );
    }
}
