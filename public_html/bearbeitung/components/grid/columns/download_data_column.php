<?php

class DownloadDataColumn extends AbstractDatasetFieldViewColumn
{

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
