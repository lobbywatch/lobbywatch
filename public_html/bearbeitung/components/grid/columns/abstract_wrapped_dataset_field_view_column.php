<?php

require_once dirname(__FILE__) . '/abstract_dataset_field_view_column.php';
require_once dirname(__FILE__) . '/../../../components/common_utils.php';

abstract class AbstractWrappedDatasetFieldViewColumn extends AbstractDatasetFieldViewColumn
{
    /** @var  string */
    private $sourcePrefixTemplate;
    /** @var  string */
    private $sourceSuffix;

    /** @param string $value */
    public function setSourcePrefixTemplate($value) {
        $this->sourcePrefixTemplate = $value;
    }

    /** @return string */
    public function getSourcePrefixTemplate() {
        return $this->sourcePrefixTemplate;
    }

    /** @param string $value */
    public function setSourceSuffix($value) {
        $this->sourceSuffix = $value;
    }

    /** @return string */
    public function getSourceSuffix() {
        return $this->sourceSuffix;
    }

    /** @return string */
    public function getWrappedValue() {
        $sourcePrefix = FormatDatasetFieldsTemplate($this->GetDataset(), $this->sourcePrefixTemplate);
        return $sourcePrefix . $this->getValue() . $this->sourceSuffix;
    }
}
