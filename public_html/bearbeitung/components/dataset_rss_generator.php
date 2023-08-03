<?php

include_once dirname(__FILE__) . '/' . 'common.php';
include_once dirname(__FILE__) . '/' . 'common_utils.php';
include_once dirname(__FILE__) . '/' . 'dataset/dataset.php';
include_once dirname(__FILE__) . '/' . 'rss_feed_generator.php';
include_once dirname(__FILE__) . '/' . 'grid/grid.php'; // TODO : remove

class DatasetRssGenerator
{
    private $dataset;
    private $channelTitle;
    private $channelLink;
    private $channelDescription;
    private $itemTitleTemplate;
    private $itemLinkTemplate;
    private $itemDescriptionTemplate;
    private $itemCount;
    private $orderByFieldName;
    private $orderType;
    private $itemPublicationDateFieldName;

    public function __construct(Dataset $dataset,
        $channelTitle, $channelLink, $channelDescription,
        $itemTitleTemplate, $itemLinkTemplate, $itemDescriptionTemplate)
    {
        $this->dataset = $dataset;
        //
        $this->channelTitle = $channelTitle;
        $this->channelLink = $channelLink;
        $this->channelDescription = $channelDescription;
        //
        $this->itemTitleTemplate = $itemTitleTemplate;
        $this->itemLinkTemplate = $itemLinkTemplate;
        $this->itemDescriptionTemplate = $itemDescriptionTemplate;
        //
        $this->itemPublicationDateFieldName = null;
        //
        $this->itemCount = 30;
        $this->orderByFieldName = '';
        $this->orderType = otAscending;
    }

    public function SetItemPublicationDateFieldName($value)
    {
        $this->itemPublicationDateFieldName = $value;
    }

    public function SetItemCount($value)
    {
        $this->itemCount = $value;
    }

    public function SetOrderByFieldName($value)
    {
        $this->orderByFieldName = $value;
    }

    public function SetOrderType($value)
    {
        $this->orderType = $value;
    }

    private function ApplyOrderingToDataset()
    {
        if ($this->orderByFieldName != '')
            $this->dataset->setOrderByField($this->orderByFieldName, GetOrderTypeAsSQL($this->orderType));
    }

    private function ApplyLimitCountToDataset()
    {
        if ($this->itemCount > 0)
        {
            $this->dataset->SetUpLimit(0);
            $this->dataset->SetLimit($this->itemCount);
        }
    }

    private function SetItemPublicationDate(RssItem $rssItem)
    {
        if (isset($this->itemPublicationDateFieldName))
        {
            $fieldValue = $this->dataset->GetFieldValueByName($this->itemPublicationDateFieldName);
            if (!(is_object($fieldValue) && (get_class($fieldValue) == 'SMDateTime')))
                $fieldValue = SMDateTime::Parse($fieldValue, '%d-%m-%Y %H:%M:%S');
            $rssItem->SetPublicationDate($fieldValue);
        }
    }

    public function Generate()
    {
        $rssChannel = new RssChannel($this->channelTitle, $this->channelLink,
            $this->channelDescription);

        $this->ApplyOrderingToDataset();
        $this->ApplyLimitCountToDataset();
        
        $this->dataset->Open();

        while ($this->dataset->Next())
        {
            $item = new RssItem(
                FormatDatasetFieldsTemplate($this->dataset, $this->itemTitleTemplate),
                FormatDatasetFieldsTemplate($this->dataset, $this->itemLinkTemplate),
                FormatDatasetFieldsTemplate($this->dataset, $this->itemDescriptionTemplate)
            );
            $this->SetItemPublicationDate($item);
            $rssChannel->AddItem($item);
        }

        $this->dataset->Close();

        return $rssChannel->GenerateRss();
    }
}

class NullRssGenerator
{
    public function Generate()
    {
        return '';
    }
}
