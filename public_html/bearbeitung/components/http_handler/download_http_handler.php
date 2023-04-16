<?php

include_once dirname(__FILE__) . '/abstract_http_handler.php';

class DownloadHTTPHandler extends AbstractHTTPHandler
{
    /** @var Dataset */
    private $dataset;

    /** @var string */
    private $fieldName;

    /** @var string */
    private $contentType;

    /** @var string */
    private $downloadFileName;

    /** @var boolean */
    private $forceDownload = true;

    /**
     * @param Dataset $dataset
     * @param string $fieldName
     * @param string $name
     * @param string $contentType
     * @param string $downloadFileName
     * @param boolean $forceDownload
     */
    public function __construct($dataset, $fieldName, $name, $contentType, $downloadFileName, $forceDownload = true)
    {
        parent::__construct($name);
        $this->dataset = $dataset;
        $this->fieldName = $fieldName;
        $this->contentType = $contentType;
        $this->downloadFileName = $downloadFileName;
        $this->forceDownload = $forceDownload;
    }

    public function Render(Renderer $renderer)
    {
        $primaryKeyValues = array();
        ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);

        $this->dataset->SetSingleRecordState($primaryKeyValues);
        $this->dataset->Open();
        $result = '';
        if ($this->dataset->Next())
            $result = $this->dataset->GetFieldValueByName($this->fieldName);
        $this->dataset->Close();

        header('Content-type: ' . FormatDatasetFieldsTemplate($this->dataset, $this->contentType));
        if ($this->forceDownload)
            header('Content-Disposition: attachment; filename="' . FormatDatasetFieldsTemplate($this->dataset, $this->downloadFileName) . '"');

        echo $result;
    }
}
