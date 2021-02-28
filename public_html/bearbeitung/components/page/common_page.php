<?php

include_once dirname(__FILE__) . '/common_page_viewdata.php';
include_once dirname(__FILE__) . '/../captions.php';

/**
 * Page with common view data
 */
abstract class CommonPage
{
    /** @var string */
    private $id;
    /** @var string */
    private $contentEncoding;
    /** @var string */
    private $title;
    /** @var string */
    private $header;
    /** @var string */
    private $footer;
    /** @var Captions */
    private $localizerCaptions;

    /** @var PageList */
    private $pageList;
    /** @var boolean */
    private $showPageList = false;

    /** @var Event */
    public $OnGetCustomTemplate;
    /** @var Event */
    public $OnCustomHTMLHeader;

    /**
     * CommonPage constructor.
     * @param $id
     * @param $contentEncoding
     */
    public function __construct($id, $contentEncoding) {
        $this->id = $id;
        $this->contentEncoding = $contentEncoding;

        if (function_exists('GetPagesHeader')) {
            $this->header = GetPagesHeader();
        }
        if (function_exists('GetPagesFooter')) {
            $this->footer = GetPagesFooter();
        }

        $this->OnGetCustomTemplate = new Event();
        $this->OnCustomHTMLHeader = new Event();

        $this->attachCommonEventListeners();
    }

    private function attachCommonEventListeners() {
        if (function_exists('Global_GetCustomTemplateHandler')) {
            $this->OnGetCustomTemplate->AddListener('Global_GetCustomTemplateHandler');
        }
        if (function_exists('Global_CustomHTMLHeaderHandler')) {
            $this->OnCustomHTMLHeader->AddListener('Global_CustomHTMLHeaderHandler');
        }
    }

    /** @return CommonPageViewData */
    public function getCommonViewData() {
        $viewData = new CommonPageViewData();

        return $viewData
            ->setDirection($this->GetPageDirection())
            ->setContentEncoding($this->GetContentEncoding())
            ->setTitle($this->GetTitle())
            ->setHeader($this->GetHeader())
            ->setFooter($this->GetFooter())
            ->setCustomHead($this->GetCustomPageHeader())
            ->setInactivityTimeout(GetInactivityTimeout())
            ->setClientSideScript(
                'OnBeforeLoadEvent',
                $this->GetCustomClientScript()
            )
            ->setClientSideScript(
                'OnAfterLoadEvent',
                $this->GetOnPageLoadedClientScript()
            );
    }

    /** @return string */
    public function GetPageDirection() {
        return null;
    }

    /** @return string */
    public function GetContentEncoding() {
        return $this->contentEncoding;
    }

    /** @return string */
    public function GetCustomClientScript() {
        return '';
    }

    /** @return string */
    public function GetOnPageLoadedClientScript() {
        return '';
    }

    /** @return string */
    public function GetPageId() {
        return $this->id;
    }

    /** @return string */
    public function GetTitle() {
        return $this->title;
    }

    /** @param string $value */
    public function SetTitle($value) {
        $this->title = $value;
    }

    /** @return string */
    public function GetHeader() {
        return $this->header;
    }

    /** @param string $value */
    public function SetHeader($value) {
        $this->header = $value;
    }

    /** @return string */
    public function GetFooter() {
        return $this->footer;
    }

    /** @param string $value */
    public function SetFooter($value) {
        $this->footer = $value;
    }

    /** @return Captions */
    public function GetLocalizerCaptions() {
        if (!isset($this->localizerCaptions)) {
            $this->localizerCaptions = Captions::getInstance($this->GetContentEncoding());
        }

        return $this->localizerCaptions;
    }

    public abstract function GetPageFileName();
    public abstract function getType();

    public function GetCustomTemplate($part, $mode, $defaultValue, &$params = array()) {
        $result = null;
        $this->OnGetCustomTemplate->Fire(array($this->getType(), $part, $mode, &$result, &$params, $this));

        if ($result) {
            return Path::Combine('custom_templates', $result);
        }

        return $defaultValue;
    }

    public function GetCustomPageHeader() {
        $result = '';
        $this->OnCustomHTMLHeader->Fire(array(&$this, &$result));
        return $result;
    }

    public function getLink() {
        return null;
    }

    public function GetReadyPageList() {
        if (!$this->pageList) {
            $this->pageList = new PageList($this);
        };

        return $this->pageList;
    }

    /** @return boolean */
    public function GetShowPageList() {
        return $this->showPageList;
    }

    /** @param boolean $value */
    public function SetShowPageList($value) {
        $this->showPageList = $value;
    }
}
