<?php

include_once dirname(__FILE__) . '/image_view_column.php';

class BlobImageViewColumn extends ImageViewColumn
{
    private $handlerName;

    public function __construct($fieldName, $datasetFieldName, $caption, $dataset, $enablePictureZoom = true, $handlerName)
    {
        parent::__construct($fieldName, $datasetFieldName, $caption, $dataset);
        $this->handlerName = $handlerName;
    }

    public function GetImageLink()
    {
        $result = $this->GetGrid()->CreateLinkBuilder();
        $result->AddParameter('hname', $this->handlerName);
        AddPrimaryKeyParameters($result, $this->GetDataset()->GetPrimaryKeyValues());

        return $result->GetLink();
    }

    public function GetFullImageLink()
    {
        $result = $this->GetGrid()->CreateLinkBuilder();
        $result->AddParameter('hname', $this->handlerName);
        $result->AddParameter('large', '1');
        AddPrimaryKeyParameters($result, $this->GetDataset()->GetPrimaryKeyValues());

        return $result->GetLink();
    }

    /**
     * @param Renderer $renderer
     * @return void
     */
    public function Accept($renderer)
    {
        $renderer->RenderBlobImageViewColumn($this);
    }

    public function IsDataColumn()
    {
        return false;
    }
}
