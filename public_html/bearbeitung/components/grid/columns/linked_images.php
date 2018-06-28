<?php

class LinkedImages {
    /** @var Dataset */
    private $dataset = null;
    /** @var array */
    private $masterKeyFields = array();
    /** @var array */
    private $detailKeyFields = array();
    /** @var string*/
    private $sourceFieldName = '';
    /** @var string*/
    private $captionTemplate = '';
    /** @var string*/
    private $sourcePrefix = '';
    /** @var string*/
    private $sourceSuffix = '';
    /** @var string*/
    private $inlineStyles = '';

    /**
     * @param Dataset $dataset
     * @param array $masterKeyFields
     * @param array $detailKeyFields
     * @param string $sourceFieldName
     * @param string $captionTemplate
     * @param string $sourcePrefix
     * @param string $sourceSuffix
     * @param string $inlineStyles
     */
    public function __construct(Dataset $dataset, $masterKeyFields, $detailKeyFields, $sourceFieldName, $captionTemplate = '', $sourcePrefix = '', $sourceSuffix = '', $inlineStyles = '')
    {
        $this->dataset = $dataset;
        $this->masterKeyFields = $masterKeyFields;
        $this->detailKeyFields = $detailKeyFields;
        $this->sourceFieldName = $sourceFieldName;
        $this->captionTemplate = $captionTemplate;
        $this->sourcePrefix = $sourcePrefix;
        $this->sourceSuffix = $sourceSuffix;
        $this->inlineStyles = $inlineStyles;
    }

    public function getDataset() {
        return $this->dataset;
    }

    public function setDataset(Dataset $dataset) {
        $this->dataset = $dataset;
    }

    public function getMasterKeyFields() {
        return $this->masterKeyFields;
    }

    public function setMasterKeyFields(array $masterKeyFields) {
        $this->masterKeyFields = $masterKeyFields;
    }

    public function getDetailKeyFields() {
        return $this->detailKeyFields;
    }

    public function setDetailKeyFields(array $detailKeyFields) {
        $this->detailKeyFields = $detailKeyFields;
    }

    public function getSourceFieldName() {
        return $this->sourceFieldName;
    }

    public function setSourceFieldName($sourceFieldName) {
        $this->sourceFieldName = $sourceFieldName;
    }

    public function getCaptionTemplate() {
        return $this->captionTemplate;
    }

    public function setCaptionTemplate($value) {
        $this->captionTemplate = $value;
    }

    public function getSourcePrefix() {
        return $this->sourcePrefix;
    }

    public function setSourcePrefix($sourcePrefix) {
        $this->sourcePrefix = $sourcePrefix;
    }

    public function getSourceSuffix() {
        return $this->sourceSuffix;
    }

    public function setSourceSuffix($sourceSuffix) {
        $this->sourceSuffix = $sourceSuffix;
    }

    public function setInlineStyles($inlineStyles) {
        $this->inlineStyles = $inlineStyles;
    }

    public function getInlineStyles() {
        return $this->inlineStyles;
    }

    public function getLinkedImagesInfo(Dataset $masterDataset) {
        $this->dataset->Close();
        $this->dataset->ClearAllFilters();
        $this->applyDatasetFilterBasedOnMasterDataset($masterDataset);

        return $this->getInternalLinkedImagesInfo($masterDataset);
    }

    private function applyDatasetFilterBasedOnMasterDataset(Dataset $masterDataset) {
        foreach ($this->detailKeyFields as $i => $detailKeyField) {
            $this->dataset->AddFieldFilter($detailKeyField,
                new FieldFilter($masterDataset->GetFieldValueByName($this->masterKeyFields[$i]), '='));
        }
    }

    private function getInternalLinkedImagesInfo(Dataset $masterDataset) {
        $result = array();
        $this->dataset->Open();
        while ($this->dataset->Next()) {
            $result[] = array(
                'source' => $this->getWrappedValue($masterDataset, $this->dataset->GetFieldValueByName($this->sourceFieldName)),
                'caption' => FormatDatasetFieldsTemplate($this->dataset, $this->captionTemplate)
            );
        }
        $this->dataset->Close();
        return $result;
    }

    private function getWrappedValue(Dataset $masterDataset, $value) {
        return FormatDatasetFieldsTemplate($masterDataset, $this->sourcePrefix) . $value . $this->sourceSuffix;
    }

}
