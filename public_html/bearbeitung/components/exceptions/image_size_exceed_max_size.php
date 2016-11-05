<?php

include_once dirname(__FILE__) . '/abstract_localized_exception.php';

class ImageSizeExceedMaxSize extends AbstractLocalizedException
{
    private $fieldName;
    private $actualWidth;
    private $actualHeight;
    private $maxWidth;
    private $maxHeight;

    public function  __construct($fieldName, $actualWidth, $actualHeight, $maxWidth, $maxHeight)
    {
        parent::__construct('', 0);
        $this->fieldName = $fieldName;
        $this->actualWidth = $actualWidth;
        $this->actualHeight = $actualHeight;
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
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
        return sprintf($captions->GetMessageString('ImageSizeExceedMaxSizeForField'), $this->fieldName, $this->actualWidth, $this->actualHeight, $this->maxWidth, $this->maxHeight);
    }
}
