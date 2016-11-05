<?php

class DetailColumn extends AbstractViewColumn
{
    private $masterKeyFields;
    private $pageHandlerName;
    private $dataset;
    private $name;
    private $frameRandomNumber;

    public function __construct(
        $masterKeyFields,
        $name,
        $pageHandlerName,
        Dataset $dataset,
        $caption
    ) {
        parent::__construct($caption);
        $this->masterKeyFields = $masterKeyFields;
        $this->name = $name;
        $this->pageHandlerName = $pageHandlerName;
        $this->dataset = $dataset;
        $this->frameRandomNumber = Random::GetIntRandom();
        $this->dataset->OnNextRecord->AddListener('NextRecordHandler', $this);
    }

    public function GetPageHandlerName()
    {
        return $this->pageHandlerName;
    }

    public function NextRecordHandler()
    {
        $this->frameRandomNumber = Random::GetIntRandom();
    }

    public function GetDataset()
    {
        return $this->dataset;
    }

    private function GetDetailsControlSuffix()
    {
        return $this->frameRandomNumber;
    }

    public function GetLink()
    {
        $linkBuilder = $this->GetGrid()->CreateLinkBuilder();
        $linkBuilder->AddParameter('detailrow', 'DetailContent_'.$this->name.'_'.$this->GetDetailsControlSuffix());
        $linkBuilder->AddParameter('hname', $this->pageHandlerName);
        for ($i = 0; $i < count($this->masterKeyFields); $i++) {
            $linkBuilder->AddParameter('fk'.$i, $this->GetDataset()->GetFieldValueByName($this->masterKeyFields[$i]));
        }

        return $linkBuilder->GetLink();
    }


    public function DecorateLinkForPostMasterRecord(LinkBuilder $linkBuilder)
    {
        $linkBuilder->AddParameter('details-redirect', $this->pageHandlerName);
    }

    public function GetSeparateViewLink()
    {
        $values = array();
        foreach ($this->masterKeyFields as $i => $field) {
            $values[$i] = $this->GetDataset()->GetFieldValueByName($field);
        }

        return $this->getUrlForRecord($values);
    }

    public function getUrlForRecord($values, $withViewMode = true)
    {
        $linkBuilder = $this->GetGrid()->CreateLinkBuilder();
        $linkBuilder->AddParameter('hname', $this->pageHandlerName);

        foreach (array_keys($this->masterKeyFields) as $i) {
            $linkBuilder->AddParameter('fk' . $i, $values[$i]);
        }

        if ($withViewMode) {
            $linkBuilder->AddParameter('master_viewmode', $this->getGrid()->getViewMode());
        }

        return $linkBuilder->GetLink();
    }

    public function GetViewData()
    {
        $result = array(
            'caption' => $this->GetCaption(),
            'gridLink' => $this->GetLink(),
            'SeparatedPageLink' => $this->GetSeparateViewLink(),
            'detailId' => 'detail-'.$this->GetDetailsControlSuffix()
        );

        return $result;
    }

    public function GetName()
    {
        return $this->name;
    }

    public function GetValue() {
    }

    public function GetDisplayValue(Renderer $renderer) {
    }

    public function Accept($renderer)
    {
    }
}
