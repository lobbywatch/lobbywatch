<?php

include_once dirname(__FILE__) . '/imageuploader.php';

class MultiUploader extends ImageUploader
{
    /** @var int */
    private $minFileSize = 0;
    /** @var int */
    private $maxFileSize = 0;
    /** string[] */
    private $allowedFileTypes = array();
    /** string[] */
    private $allowedFileExtensions = array();

    /** @return string */
    public function getEditorName() {
        return 'multiuploader';
    }

    public function extractImageActionFromArray(ArrayWrapper $arrayWrapper) {
        return REPLACE_IMAGE_ACTION;
    }

    /** @return int */
    public function getMinFileSize() {
        return $this->minFileSize;
    }

    /** @param int */
    public function setMinFileSize($value) {
        $this->minFileSize = $value;
    }

    /** @return int */
    public function getMaxFileSize() {
        return $this->maxFileSize;
    }

    /** @param int */
    public function setMaxFileSize($value) {
        $this->maxFileSize = $value;
    }

    /** @return string[] */
    public function getAllowedFileTypes() {
        return $this->allowedFileTypes;
    }

    /** @param string[] $values */
    public function setAllowedFileTypes($values) {
        $this->allowedFileTypes = $values;
    }

    /** @return string[] */
    public function getAllowedFileExtensions() {
        return $this->allowedFileExtensions;
    }

    /** @param string[] $values */
    public function setAllowedFileExtensions($values) {
        $this->allowedFileExtensions = $values;
    }

    /** @return string */
    public function getAllowedFileTypesAsJson() {
        return SystemUtils::ToJSON($this->allowedFileTypes);
    }

    /** @return string */
    public function getAllowedFileExtensionsAsJson() {
        return SystemUtils::ToJSON($this->allowedFileExtensions);
    }

}
