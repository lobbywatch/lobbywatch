<?php

abstract class ImageViewColumn extends AbstractWrappedDatasetFieldViewColumn
{
    private $imageHintTemplate = '';
    private $enablePictureZoom = true;

    public function __construct($fieldName, $datasetFieldName, $caption, $dataset, $enablePictureZoom = true)
    {
        parent::__construct($fieldName, $datasetFieldName, $caption, $dataset, false);
        $this->enablePictureZoom = $enablePictureZoom;
    }

    public function GetEnablePictureZoom()
    {
        return $this->enablePictureZoom;
    }

    public function SetEnablePictureZoom($value)
    {
        $this->enablePictureZoom = $value;
    }

    public function SetImageHintTemplate($value)
    {
        $this->imageHintTemplate = $value;
    }

    public function GetImageHintTemplate()
    {
        return $this->imageHintTemplate;
    }

    abstract public function GetImageLink();

    public function GetFullImageLink()
    {
        return $this->getImageLink();
    }

    public function GetImageHint()
    {
        return $this->imageHintTemplate;
    }

    public function generateImageSizeString() {
        return '';
    }

}
