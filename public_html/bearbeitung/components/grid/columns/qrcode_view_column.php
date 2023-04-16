<?php

class QRCodeViewColumn extends AbstractDatasetFieldViewColumn
{
    /** @var int */
    private $sizeFactor;

    /** @var int */
    private $frameWidth = 1;

    public function __construct($fieldName, $datasetFieldName, $caption, $dataset, $sizeFactor)
    {
        parent::__construct($fieldName, $datasetFieldName, $caption, $dataset, false);
        $this->sizeFactor = $sizeFactor;
    }

    public function getSizeFactor() {
        return $this->sizeFactor;
    }

    public function setSizeFactor($value) {
        $this->sizeFactor = $value;
    }

    public function getFrameWidth() {
        return $this->frameWidth;
    }

    public function setFrameWidth($value) {
        $this->frameWidth = $value;
    }

    public function Accept($renderer)
    {
        $renderer->RenderQRCodeViewColumn($this);
    }
}
