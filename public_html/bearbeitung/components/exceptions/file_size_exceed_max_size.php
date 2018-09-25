<?php

include_once dirname(__FILE__) . '/abstract_localized_exception.php';

class FileSizeExceedMaxSize extends AbstractLocalizedException
{
    /** @var string */
    private $fieldName;
    /** @var int */
    private $actualFileSize;
    /** @var int */
    private $maxSize;

    /**
     * @param string $fieldName
     * @param int $actualFileSize
     * @param int $maxSize
     */
    public function  __construct($fieldName, $actualFileSize, $maxSize) {
        parent::__construct('', 0);
        $this->fieldName = $fieldName;
        $this->actualFileSize = $actualFileSize;
        $this->maxSize = $maxSize;
    }

    /** @return string */
    public function GetFieldName() {
        return $this->fieldName;
    }

    /**
     * @param int $size
     * @return string
     */
    private function getSizeInKbAsString($size) {
        return ceil($size / 1024) . 'Kb';
    }

    /** @return string */
    private function getActualFileSizeInKbAsString() {
        return $this->getSizeInKbAsString($this->actualFileSize);
    }

    /** @return string */
    private function getMaxSizeInKbAsString() {
        return $this->getSizeInKbAsString($this->maxSize);
    }

    /**
     * @param Captions $captions
     * @return string
     */
    public function getLocalizedMessage(Captions $captions) {
        return sprintf($captions->GetMessageString('FileSizeExceedMaxSizeForField'), $this->fieldName, $this->getActualFileSizeInKbAsString(), $this->getMaxSizeInKbAsString());
    }
}
