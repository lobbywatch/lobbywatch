<?php

include_once dirname(__FILE__) . '/image_view_column.php';
include_once dirname(__FILE__) . '/view_column_utils.php';

class ExternalImageViewColumn extends ImageViewColumn
{

    /** @var string */
    private $height = '';
    /** @var string */
    private $width = '';
    /** @var string|null */
    private $originalImageFieldName = null;
    /** @var string */
    private $originalImagePrefix = '';
    /** @var string */
    private $originalImageSuffix = '';

    /**
     * @param string $value
     */
    public function setHeight($value)
    {
        $this->height = $value;
    }

    /**
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param string $value
     */
    public function setWidth($value)
    {
        $this->width = $value;
    }

    /**
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    public function GetImageLink()
    {
        return $this->getWrappedValue();
    }

    public function Accept($renderer)
    {
        $renderer->RenderImageViewColumn($this);
    }

    public function generateImageSizeString() {
        return generateDimensionString($this->height, $this->width);
    }

    /**
     * @param string $fieldName
     * @param string $prefix
     * @param string $suffix
     */
    public function setOriginalImageInfo($fieldName, $prefix = '', $suffix = '')
    {
        $this->originalImageFieldName = $fieldName;
        $this->originalImagePrefix = $prefix;
        $this->originalImageSuffix = $suffix;
    }

    /** @inheritdoc */
    public function GetFullImageLink()
    {
        if (!is_null($this->originalImageFieldName) && ($this->GetDataset()->GetFieldByName($this->originalImageFieldName) !== null)) {
            return $this->getWrappedOriginalImageFieldValue();
        } else {
            return parent::GetFullImageLink();
        }
    }

    private function getWrappedOriginalImageFieldValue() {
        return
            $this->originalImagePrefix .
            $this->GetDataset()->GetFieldValueByName($this->originalImageFieldName) .
            $this->originalImageSuffix;
    }

}
