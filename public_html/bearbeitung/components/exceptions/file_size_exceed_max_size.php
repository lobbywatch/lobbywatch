<?php

include_once dirname(__FILE__) . '/abstract_localized_exception.php';

class FileSizeExceedMaxSize extends AbstractLocalizedException
{
    private $fieldName;
    private $actualFileSize;
    private $maxSize;

    public function  __construct($fieldName, $actualFileSize, $maxSize)
    {
        parent::__construct('', 0);
        $this->fieldName = $fieldName;
        $this->actualFileSize = $actualFileSize;
        $this->maxSize = $maxSize;
    }

    public function GetFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param Captions $captions
     *
     * @return string
     */
    public function getLocalizedMessage(Captions $captions)
    {
        return sprintf($captions->GetMessageString('FileSizeExceedMaxSizeForField'), $this->fieldName, $this->actualFileSize, $this->maxSize);
    }
}
