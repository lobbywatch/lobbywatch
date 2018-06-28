<?php

class DownloadExternalDataColumn extends AbstractWrappedDatasetFieldViewColumn
{
    private $downloadLinkHintTemplate;

    public function __construct(
        $fieldName,
        $datasetFieldName,
        $caption,
        $dataset,
        $downloadLinkHintTemplate = ''
    ) {
        parent::__construct($fieldName, $datasetFieldName, $caption, $dataset, false);
        $this->downloadLinkHintTemplate = $downloadLinkHintTemplate;
    }

    public function getDownloadLinkHintTemplate()
    {
        return $this->downloadLinkHintTemplate;
    }

    public function Accept($renderer)
    {
        $renderer->RenderDownloadExternalDataViewColumn($this);
    }
}
