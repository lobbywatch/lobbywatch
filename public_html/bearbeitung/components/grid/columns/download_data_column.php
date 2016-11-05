<?php

class DownloadDataColumn extends AbstractDatasetFieldViewColumn
{
    private $linkInnerHtml;

    public function __construct($fieldName, $datasetFieldName, $caption, $dataset, $linkInnerHtml = 'download')
    {
        parent::__construct($fieldName, $datasetFieldName, $caption, $dataset, false);
        $this->linkInnerHtml = $linkInnerHtml;
    }

    public function GetLinkInnerHtml()
    {
        return $this->linkInnerHtml;
    }

    public function GetDownloadLink()
    {
        $result = $this->GetGrid()->CreateLinkBuilder();
        $result->AddParameter('hname', $this->getName().'_handler');
        AddPrimaryKeyParameters($result, $this->GetDataset()->GetPrimaryKeyValues());

        return $result->GetLink();
    }

    /**
     * @param Renderer $renderer
     * @return void
     */
    public function Accept($renderer)
    {
        $renderer->RenderDownloadDataViewColumn($this);
    }

    public function IsDataColumn()
    {
        return false;
    }
}
