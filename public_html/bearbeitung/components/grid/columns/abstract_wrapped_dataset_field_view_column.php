<?php

require_once dirname(__FILE__) . '/abstract_dataset_field_view_column.php';

abstract class AbstractWrappedDatasetFieldViewColumn extends AbstractDatasetFieldViewColumn
{
    private $sourcePrefix;
    private $sourceSuffix;

    public function SetSourcePrefix($value)
    {
        $this->sourcePrefix = $value;
    }

    public function GetSourcePrefix()
    {
        return $this->sourcePrefix;
    }

    public function SetSourceSuffix($value)
    {
        $this->sourceSuffix = $value;
    }

    public function GetSourceSuffix()
    {
        return $this->sourceSuffix;
    }

    public function getWrappedValue()
    {
        return $this->getSourcePrefix().$this->getValue().$this->getSourceSuffix();
    }
}
