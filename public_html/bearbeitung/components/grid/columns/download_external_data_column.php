<?php

class DownloadExternalDataColumn extends AbstractWrappedDatasetFieldViewColumn
{
    private $downloadTextTemplate;
    private $downloadLinkHintTemplate;

    public function __construct(
        $fieldName,
        $datasetFieldName,
        $caption,
        $dataset,
        $downloadTextTemplate,
        // @todo delete unused `$captions` argument (need to change code generation)
        // too tricky to do it with ___tools_replaceGenerated.php
        Captions $captions,
        $downloadLinkHintTemplate = ''
    ) {
        parent::__construct($fieldName, $datasetFieldName, $caption, $dataset, false);
        $this->downloadTextTemplate = $downloadTextTemplate;
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
