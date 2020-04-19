<?php

include_once dirname(__FILE__) . '/abstract_http_handler.php';

class LinkedImagesHTTPHandler extends AbstractHTTPHandler
{
    /** @var Dataset */
    private $dataset;
    private $fieldName;
    /** @var LinkedImages */
    private $linkedImages;

    public function __construct($name, $dataset, $fieldName, LinkedImages $linkedImages)
    {
        parent::__construct($name);
        $this->dataset = $dataset;
        $this->fieldName = $fieldName;
        $this->linkedImages = $linkedImages;
    }

    private function getDataNameAttributeValue() {
        return $this->fieldName.'_'.implode('_', $this->dataset->GetPrimaryKeyValues());
    }

    public function Render(Renderer $renderer)
    {
        $primaryKeyValues = array();
        ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);

        $this->dataset->SetSingleRecordState($primaryKeyValues);
        $this->dataset->Open();
        $result = '';
        if ($this->dataset->Next()) {
            $linkedImagesInfo = $this->linkedImages->getLinkedImagesInfo($this->dataset);

            foreach ($linkedImagesInfo as $linkedImageInfo) {
                $result .=
                    sprintf(
                        '<a class="image gallery-item" data-name="%s" href="%s" title="%s"></a>',
                        $this->getDataNameAttributeValue(),
                        $linkedImageInfo['source'],
                        $linkedImageInfo['caption']
                    );
            }
        }
        $this->dataset->Close();

        return $result;
    }
}
