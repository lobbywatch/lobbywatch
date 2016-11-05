<?php

class ExternalAudioFileColumn extends AbstractWrappedDatasetFieldViewColumn
{
    private $sourcePrefix;
    private $sourceSuffix;

    public function __construct($fieldName, $datasetFieldName, $caption, $dataset)
    {
        parent::__construct($fieldName, $datasetFieldName, $caption, $dataset);
        $this->sourcePrefix = '';
        $this->sourceSuffix = '';
    }

    public function Accept($renderer)
    {
        $renderer->RenderExternalAudioViewColumn($this);
    }
}
