<?php

include_once dirname(__FILE__) . '/abstract_http_handler.php';

class ImageHTTPHandler extends AbstractHTTPHandler
{
    /** @var IDataset */
    private $dataset;
    private $fieldName;
    /** @var ImageFilter */
    private $imageFilter;

    public function __construct($dataset, $fieldName, $name, $imageFilter)
    {
        parent::__construct($name);
        $this->dataset = $dataset;
        $this->fieldName = $fieldName;
        $this->imageFilter = $imageFilter;
    }

    function TransformImage(&$imageString)
    {
        echo $this->imageFilter->ApplyFilter($imageString);
    }

    public function Render(Renderer $renderer)
    {
        $result = '';
        $contentType = 'image';

        $primaryKeyValues = array ( );
        ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);

        $this->dataset->SetSingleRecordState($primaryKeyValues);
        $this->dataset->Open();
        if ($this->dataset->Next())
            $result = $this->dataset->GetFieldValueByName($this->fieldName);
        $this->dataset->Close();

        if (version_compare(PHP_VERSION, "5.3.0", ">=") && extension_loaded('fileinfo')) {
            $finfo = new finfo(FILEINFO_MIME);
            $finfoBuffer = $finfo->buffer($result);
            if (($finfoBuffer !== false) && (strpos($finfoBuffer, 'image/svg+xml') !== false)) {
                $contentType = 'image/svg+xml';
            }
        }

        header('Content-type: ' . $contentType);

        if (GetApplication()->IsGETValueSet('large'))
            echo $result;
        else
            $this->TransformImage($result);

        return '';
    }
}
