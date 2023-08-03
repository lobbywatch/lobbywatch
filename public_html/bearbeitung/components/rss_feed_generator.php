<?php
// Processed by afterburner.sh



include_once dirname(__FILE__) . '/' . 'common.php';
include_once dirname(__FILE__) . '/' . 'common_utils.php';
include_once dirname(__FILE__) . '/' . 'utils/string_utils.php';

class RssUtils
{
    public static function CreateTag($tagName, $content, $useCData = false)
    {
        if ($useCData)
            return "<$tagName><![CDATA[$content]]></$tagName>";
        else
            return "<$tagName>$content</$tagName>";
    }
}

class RssChannel
{
    private $title;
    private $link;
    private $description;
    //
    private $items;

    public function  __construct($title, $link, $description)
    {
        $this->items = array();
        $this->SetTitle($title);
        $this->SetLink($link);
        $this->SetDescription($description);
    }

    public function SetTitle($value)
    { $this->title = $value; }
    public function GetTitle()
    { return $this->title; }

    public function SetLink($value)
    { $this->link = $value; }
    public function GetLink()
    { return $this->link; }

    public function SetDescription($value)
    { $this->description = $value; }
    public function GetDescription()
    { return $this->description; }

    public function GetItems()
    {
        return $this->items;
    }

    public function AddItem(RssItem $rssItem)
    {
        $this->items[] = $rssItem;
    }

    private function GenerateItemRss(RssItem $rssItem)
    {
        $result = '';
        AddStr($result, '<item>');
        
        AddStr($result, RssUtils::CreateTag('title', $this->escapeXmlString($rssItem->GetTitle())));
        AddStr($result, RssUtils::CreateTag('link', $this->escapeXmlString($rssItem->GetLink())));
        AddStr($result, RssUtils::CreateTag('description', $rssItem->GetDescription(), true));
        if ($rssItem->GetPublicationDate() != null)
            AddStr($result,
                RssUtils::CreateTag(
                        'pubDate',
                        $this->escapeXmlString($rssItem->GetPublicationDate()->ToRfc822String())
                        )
                    );
        
        AddStr($result, '</item>');
        
        return $result;
    }

    public function GenerateRss()
    {
        $result = '<?xml version="1.0" encoding="utf-8"?>';
        AddStr($result, '<rss version="2.0">');
        AddStr($result, '<channel>');
        
        AddStr($result, RssUtils::CreateTag('title', $this->escapeXmlString($this->GetTitle())));
        AddStr($result, RssUtils::CreateTag('link', $this->escapeXmlString($this->GetLink())));
        AddStr($result, RssUtils::CreateTag('description', $this->GetDescription()));

        foreach($this->GetItems() as $item)
            AddStr($result, $this->GenerateItemRss($item));

        AddStr($result, '</channel>');
        AddStr($result, '</rss>');

        return $result;
    }

    private function escapeXmlString($value) {
        return htmlspecialchars($value, ENT_COMPAT, 'utf-8');
    }
}

class RssItem
{
    private $title;
    private $link;
    private $description;
    private $publicationDate;

    public function  __construct($title, $link, $description)
    {
        $this->SetTitle($title);
        $this->SetLink($link);
        $this->SetDescription($description);
        $this->SetPublicationDate(null);
    }

    public function SetTitle($value)
    { $this->title = $value; }
    public function GetTitle()
    { return $this->title; }

    public function SetLink($value)
    { $this->link = $value; }
    public function GetLink()
    { return $this->link; }

    public function SetDescription($value)
    { $this->description = $value; }
    public function GetDescription()
    { return $this->description; }

    public function SetPublicationDate(SMDateTime $value = null)
    { $this->publicationDate = $value; }
    public function GetPublicationDate()
    { return $this->publicationDate; }
}
