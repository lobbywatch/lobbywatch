<?php
define('NS_LIST', 1);
define('NS_COMBOBOX', 2);

class PageNavigatorPage {

    /** @var boolean */
    private $isCurrent;

    /** @var string */
    private $caption;

    /** @var Page */
    private $page;

    /** @var int */
    private $pageNumber;

    /** @var string */
    private $prefix;

    /** @var LinkBuilder */
    private $linkBuilder;

    /** @var string */
    private $hint;

    /** @var bool */
    private $renderText;

    /** @var null|string */
    private $shortCut = null;

    function __construct($page, $caption, $pageNumber, $isCurrent, $linkBuilder, $prefix = '', $hint = '', $renderText = true) {
        $this->page = $page;
        $this->caption = $caption;
        $this->isCurrent = $isCurrent;
        $this->pageNumber = $pageNumber;
        $this->prefix = $prefix;
        $this->linkBuilder = $linkBuilder;
        $this->hint = $hint;
        $this->renderText = $renderText;
    }

    public function HasShortCut() {
        return isset($this->shortCut) && !is_null($this->shortCut);
    }

    public function GetShortCut() {
        return $this->shortCut;
    }

    public function SetShortCut($value) {
        $this->shortCut = $value;
    }

    function GetHint() {
        return $this->hint;
    }

    function GetPage() {
        return $this->page;
    }

    function IsCurrent() {
        return $this->isCurrent;
    }

    function GetPageCaption() {
        return $this->caption;
    }

    function GetPageLink() {
        if ($this->linkBuilder) {
            $result = $this->linkBuilder;
            if (isset($this->pageNumber))
                $result->AddParameter($this->prefix . 'page', $this->pageNumber);
            else
                $result->RemoveParameter($this->prefix . 'page');
            return $result->GetLink();
        }
        return null;
    }
}

abstract class BasePageNavigator {
    public function __construct() {
    }

    public function AddCurrentPageParameters(&$linkBuilder) {
    }

    public function ProcessMessages() {
    }

    public function Accept($Renderer) {
    }

    public abstract function BuildPages(LinkBuilder $linkBuilder);
}

class AbstractPageNavigator {
    /** @var Page */
    private $page;

    /** @var Dataset */
    private $dataset;

    /** @var string */
    private $name;

    /** @var PageNavigatorPage[] */
    private $pages;

    /** @var string */
    private $prefix;

    /** @var string */
    private $caption;

    /** @var PageNavigator[] */
    private $pageNavigatorList;

    /** @var integer */
    private $currentPageNumber;

    function __construct($name, $page, $dataset, $caption, $pageNavigatorList, $prefix = null) {
        $this->name = $name;
        $this->page = $page;
        $this->dataset = $dataset;
        $this->pages = array();
        $this->prefix = isset($prefix) ? $prefix : $name;
        $this->caption = $caption;
        $this->pages = null;
        $this->pageNavigatorList = $pageNavigatorList;
    }

    function GetName() {
        return $this->name;
    }

    function GetPagaNavigatorList() {
        return $this->pageNavigatorList;
    }

    function GetPage() {
        return $this->page;
    }

    function GetCaption() {
        return $this->caption;
    }

    function CurrentPageNumber() {
        return $this->currentPageNumber;
    }

    function GetPrefix() {
        return $this->prefix;
    }

    function GetPages() {
        assert(isset($this->pages));
        return $this->pages;
    }

    function ApplyPageToDataset($currentPageNumber, Dataset $dataset) { }

    function FillPages(&$pages, $currentPage, $linkBuilder) {
    }

    function HasSetPageRequest() {
        return GetApplication()->IsGETValueSet($this->prefix . 'page');
    }

    function GetPageFromRequest() {
        return GetApplication()->GetGETValue($this->prefix . 'page');
    }

    public function NeedResetPage() {
        return false;
    }

    function SessionContainsStoredPage() {
        return GetApplication()->IsSessionVariableSet($this->getSessionVariableName());
    }

    function StorePageToSession() {
        GetApplication()->SetSessionVariable($this->getSessionVariableName(), $this->currentPageNumber);
    }

    function RestorePageFromSession() {
        $this->currentPageNumber = GetApplication()->GetSessionVariable($this->getSessionVariableName());
    }

    function ResetPageNumber() {
        GetApplication()->UnSetSessionVariable($this->getSessionVariableName());
        $this->currentPageNumber = null;
    }

    private function getSessionVariableName()
    {
        $pagePrefix = get_class($this->page);
        return $pagePrefix . $this->prefix . 'page';
    }

    function ProcessMessages() {
        if ($this->HasSetPageRequest() && !$this->NeedResetPage()) {
            $this->currentPageNumber = $this->GetPageFromRequest();
            $this->StorePageToSession();
        } elseif (!$this->NeedResetPage() && $this->SessionContainsStoredPage()) {
            $this->RestorePageFromSession();
        } else {
            $this->ResetPageNumber();
        }

        $this->ApplyPageToDataset($this->currentPageNumber, $this->dataset);
    }

    public function BuildPages(LinkBuilder $linkBuilder) {
        $this->pages = array();
        $this->FillPages($this->pages, $this->currentPageNumber, $linkBuilder);
    }

    /**
     * @param LinkBuilder $linkBuilder
     */
    function AddCurrentPageParameters(&$linkBuilder) {
        $linkBuilder->AddParameter($this->prefix . 'page', $this->CurrentPageNumber());
    }

    /**
     * @param Renderer $Renderer
     */
    function Accept($Renderer) {
        $Renderer->RenderCustomPageNavigator($this);
    }
}

class PageNavigator {
    private $name;
    /** @var Dataset */
    private $dataset;
    private $rowsPerPage;
    private $pageNumber;
    private $pages;
    /** @var Page */
    private $page;
    private $rowCount = null;
    private $pageCount = null;
    private $recordsPerPageValues;

    private $ignorePageNavigationOperations = array(OPERATION_PRINT_ALL, OPERATION_EXCEL_EXPORT, OPERATION_WORD_EXPORT, OPERATION_XML_EXPORT, OPERATION_CSV_EXPORT, OPERATION_PDF_EXPORT, OPERATION_COMPARE);

    private $defaultRowsPerPage;

    function __construct($name, $page, $Dataset, $defaultRowsPerPage = 20, $recordsPerPageValues = null) {
        $this->name = $name;
        $this->page = $page;
        $this->dataset = $Dataset;
        $this->defaultRowsPerPage = $defaultRowsPerPage;
        $this->pageNumber = 0;
        if ($recordsPerPageValues == null)
            $this->recordsPerPageValues = array(10, 20, 50, 100, 0);
        else
            $this->recordsPerPageValues = $recordsPerPageValues;
    }

    function GetName() {
        return $this->name;
    }

    function GetRecordsPerPageValues() {
        $result = array();
        foreach ($this->recordsPerPageValues as $value)
            $result[$value] = $value == 0 ? 'ALL' : $value;
        return $result;
    }

    public function GetRowsPerPage() {
        return $this->rowsPerPage;
    }

    function SetRowsPerPage($RowsPerPage) {
        $this->rowsPerPage = $RowsPerPage;
        $this->defaultRowsPerPage = $RowsPerPage;
    }

    private function NeedResetPage() {
        $result = false; //(!GetApplication()->HasPostGetRequestParameters());
        return $result;
    }

    function ResetPageNumber() {
        GetApplication()->UnSetSessionVariable($this->GetSessionPrefix() . 'page');
        $this->pageNumber = 0;
    }

    function ResetRowsPerPage() {
        GetApplication()->UnSetSessionVariable($this->GetSessionPrefix() . 'recperpage');
        $this->rowsPerPage = $this->defaultRowsPerPage;
    }

    private function GetSessionPrefix() {

        return $this->page->GetGrid()->GetId();
    }

    function ProcessMessages() {
        if (GetApplication()->IsGETValueSet('page')) {
            $this->pageNumber = GetApplication()->GetGETValue('page') - 1;
            GetApplication()->SetSessionVariable($this->GetSessionPrefix() . 'page', $this->pageNumber);
        } elseif (!$this->NeedResetPage() && GetApplication()->IsSessionVariableSet($this->GetSessionPrefix() . 'page')) {
            $this->pageNumber = GetApplication()->GetSessionVariable($this->GetSessionPrefix() . 'page');
        } else {
            $this->ResetPageNumber();
        }

        if (GetApplication()->IsGETValueSet('recperpage')) {
            $this->rowsPerPage = abs((int) GetApplication()->GetGETValue('recperpage'));
            GetApplication()->SetSessionVariable($this->GetSessionPrefix() . 'recperpage', $this->rowsPerPage);
        } elseif (GetApplication()->IsSessionVariableSet($this->GetSessionPrefix() . 'recperpage')) {
            $this->rowsPerPage = GetApplication()->GetSessionVariable($this->GetSessionPrefix() . 'recperpage');
        } else {
            $this->ResetRowsPerPage();
        }

        if ($this->pageNumber >= $this->GetPageCount())
            $this->pageNumber = $this->GetPageCount() - 1;
        elseif ($this->pageNumber < 0)
            $this->pageNumber = 0;

        if (!in_array(GetOperation(), $this->ignorePageNavigationOperations)) {
            if (($this->rowsPerPage != 0) && ($this->GetRowCount() != 0)) {
                $this->dataset->SetUpLimit($this->pageNumber * $this->rowsPerPage);
                $this->dataset->SetLimit($this->rowsPerPage);
            }
        }
    }

    public function GetPageCount() {
        if (!isset($this->pageCount)) {
            if ($this->rowsPerPage != 0) {
                $this->pageCount = floor($this->GetRowCount() / $this->rowsPerPage) +
                    ((floor($this->GetRowCount() / $this->rowsPerPage) == ($this->GetRowCount() / $this->rowsPerPage)) ? 0 : 1);
            } else
                $this->pageCount = 1;
        }
        return $this->pageCount;
    }

    public function CurrentPageNumber() {
        return $this->pageNumber + 1;
    }

    public function GetRowCount() {
        if ($this->page->GetGrid()->RequestFilterFromUser()) {
            return 0;
        }
        if (!isset($this->rowCount))
            $this->rowCount = $this->RetrieveRowCount();
        return $this->rowCount;
    }

    /**
     * @return mixed|void
     */
    protected function RetrieveRowCount() {
        return $this->dataset->GetTotalRowCount();
    }

    function GetHintForPage($number, $shortCut = null) {
        $page = $number - 1;
        $rowCount = $this->GetRowCount();
        $rowsPerPage = $this->rowsPerPage;

        $startRecord = $page * $rowsPerPage + 1;
        if ($rowsPerPage == 0) {
            $endRecord = $rowCount;
        } else {
            $endRecord = min(array(($page + 1) * $rowsPerPage, $rowCount));
        }

        $result = sprintf($this->page->GetLocalizerCaptions()->GetMessageString('RecordsMtoKFromN'),
            $startRecord, $endRecord, $rowCount);
        if (isset($shortCut))
            $result .= ";\n" . $shortCut;
        return $result;
    }

    function GetPageCountForPageSize($pageSize) {
        if ($pageSize != 0) {
            return floor($this->GetRowCount() / $pageSize) +
                ((floor($this->GetRowCount() / $pageSize) == ($this->GetRowCount() / $pageSize)) ? 0 : 1);
        } else
            return 1;
    }

    private function CreateNavigationPageSpacer() {
        return new PageNavigatorPage($this->page, '...', 1, 0, false);
    }

    private function CreateNavigatorPages($currentPage, $pageCount, $linkBuilder) {
        $nextPages = array();
        $prevPages = array();

        for ($i = $currentPage - 1; $i > max($currentPage - 2, 0); $i--) {
            $prevPage = new PageNavigatorPage($this->page, $i, $i, false, $linkBuilder, '', $this->GetHintForPage($i), false);
            $prevPages[] = $prevPage;
            if ($i = ($currentPage - 1))
                $prevPage->SetShortCut('Ctrl+left');
        }

        if ($currentPage - 10 > 1) {
            $prevPages[] = $this->CreateNavigationPageSpacer();
            $prevPages[] = new PageNavigatorPage($this->page, $currentPage - 10, $currentPage - 10, false, $linkBuilder, '', $this->GetHintForPage($currentPage - 10), false);
        }

        if ($currentPage - 50 > 1) {
            $prevPages[] = $this->CreateNavigationPageSpacer();
            $prevPages[] = new PageNavigatorPage($this->page, $currentPage - 50, $currentPage - 50, false, $linkBuilder, '', $this->GetHintForPage($currentPage - 50), false);
        }

        if ($currentPage - 100 > 1) {
            $prevPages[] = $this->CreateNavigationPageSpacer();
            $prevPages[] = new PageNavigatorPage($this->page, $currentPage - 100, $currentPage - 100, false, $linkBuilder, '', $this->GetHintForPage($currentPage - 100), false);
        }

        if ($currentPage - 1 > 1) {
            if ($currentPage - 2 > 1)
                $prevPages[] = $this->CreateNavigationPageSpacer();
            $prevPages[] = new PageNavigatorPage($this->page, '1', 1, false, $linkBuilder, '', $this->GetHintForPage(1), false);;
        }


        for ($i = $currentPage + 1; $i < min($currentPage + 2, $pageCount + 1); $i++) {
            $pageLink = new PageNavigatorPage($this->page, $i, $i, false, $linkBuilder, '', $this->GetHintForPage($i), false);
            $nextPages[] = $pageLink;
            if ($i = $currentPage + 1)
                $pageLink->SetShortCut('Ctrl+right');
        }

        if ($currentPage + 10 < $pageCount) {
            $nextPages[] = $this->CreateNavigationPageSpacer();
            $nextPages[] = new PageNavigatorPage($this->page, $currentPage + 10, $currentPage + 10, false, $linkBuilder, '', $this->GetHintForPage($currentPage + 10), false);
        }

        if ($currentPage + 50 < $pageCount) {
            $nextPages[] = $this->CreateNavigationPageSpacer();
            $nextPages[] = new PageNavigatorPage($this->page, $currentPage + 50, $currentPage + 50, false, $linkBuilder, '', $this->GetHintForPage($currentPage + 50), false);
        }

        if ($currentPage + 100 < $pageCount) {
            $nextPages[] = $this->CreateNavigationPageSpacer();
            $nextPages[] = new PageNavigatorPage($this->page, $currentPage + 100, $currentPage + 100, false, $linkBuilder, '', $this->GetHintForPage($currentPage + 100), false);
        }

        if ($currentPage + 1 < $pageCount) {
            if ($currentPage + 2 < $pageCount)
                $nextPages[] = $this->CreateNavigationPageSpacer();
            $nextPages[] = new PageNavigatorPage($this->page, $pageCount, $pageCount, false, $linkBuilder, '', $this->GetHintForPage($pageCount), false);
        }

        $result = array();
        for ($i = (count($prevPages) - 1); $i >= 0; $i--)
            $result[] = $prevPages[$i];
        $result[] = new PageNavigatorPage($this->page, $currentPage, $currentPage, true, $linkBuilder, '', $this->GetHintForPage($currentPage), false);
        for ($i = 0; $i < count($nextPages); $i++)
            $result[] = $nextPages[$i];

        return $result;
    }

    public function HasPreviousPage() {
        return $this->CurrentPageNumber() > 1;
    }

    public function HasNextPage() {
        return $this->CurrentPageNumber() < $this->GetPageCount();
    }

    public function GetPreviousPageLink() {
        if (!$this->HasPreviousPage()) {

            return '#';
        }

        $previous = false;
        foreach ($this->GetPages() as $page) {
            if ($page->IsCurrent()) {
                break;
            }
            $previous = $page;
        }

        return $previous ? $previous->GetPageLink() : '#';
    }

    public function GetNextPageLink() {
        if (!$this->HasNextPage()) {

            return '#';
        }

        $next = false;
        $previousIsCurrent = false;
        foreach ($this->GetPages() as $page) {
            if ($previousIsCurrent) {
                $next = $page;
                break;
            }
            if ($page->IsCurrent()) {
                $previousIsCurrent = true;
            }
        }

        return $next ? $next->GetPageLink() : '#';
    }

    function BuildPages($linkBuilder) {
        $this->pages = array();
        $this->FillPages($linkBuilder);
    }

    private function FillPages($linkBuilder) {
        $this->pages = $this->CreateNavigatorPages($this->CurrentPageNumber(), $this->GetPageCount(), $linkBuilder);
    }

    public function GetCurrentPageGetParameters() {
        $result = $this->page->CreateLinkBuilder();
        return $result->GetParameters();
    }

    function GetPages() {
        assert(isset($this->pages));
        return $this->pages;
    }

    /**
     * @param LinkBuilder $linkBuilder
     */
    function AddCurrentPageParameters(&$linkBuilder) {
        $linkBuilder->AddParameter('page', $this->CurrentPageNumber());
    }

    /**
     * @param Renderer $renderer
     */
    function Accept($renderer) {
        $renderer->RenderPageNavigator($this);
    }
}

class CustomPageNavigator extends AbstractPageNavigator {
    private $userPartitions;
    public $OnGetPartitions;
    public $OnGetPartitionCondition;
    private $allowViewAllRecords;
    private $navigationStyle;

    function __construct($name, $page, $dataset, $caption, $pageNavigatorList, $prefix = null) {
        parent::__construct($name, $page, $dataset, $caption, $prefix);
        $this->OnGetPartitions = new Event();
        $this->OnGetPartitionCondition = new Event();
        $this->userPartitions = null;
        $this->allowViewAllRecords = false;
        $this->navigationStyle = NS_LIST;
    }

    function GetPageFromRequest() {
        if (parent::HasSetPageRequest())
            return parent::GetPageFromRequest();
        else
            return null;
    }

    function DoOnGetPartitions() {
        $result = array();
        $this->OnGetPartitions->Fire(array(&$result));
        return $result;
    }

    function DoOnGetPartitionCondition($currentPageNumber) {
        $condition = '';
        $this->OnGetPartitionCondition->Fire(array($currentPageNumber, &$condition));
        return $condition;
    }

    function FillUserPartitions() {
        if (!isset($this->userPartitions))
            $this->userPartitions = $this->DoOnGetPartitions();
    }

    public function NeedResetPage() {
        $result =
            (GetApplication()->IsGETValueSet($this->GetPrefix() . 'page')) &&
                (GetApplication()->GetGETValue($this->GetPrefix() . 'page')) == 'reset';
        return $result;
    }

    function FillPages(&$pages, $currentPage, $linkBuilder) {
        $this->FillUserPartitions();
        if (!isset($currentPage) || $currentPage == '') {
            $userPartitionsKeys = array_keys($this->userPartitions);
            if ($this->GetAllowViewAllRecords())
                $currentPage = null;
            else
                $currentPage = $userPartitionsKeys[0];
        }
        if ($this->GetAllowViewAllRecords())
            $pages[] = new PageNavigatorPage(
                $this->GetPage(),
                $this->GetPage()->GetLocalizerCaptions()->GetMessageString('All'),
                'reset',
                $currentPage == null,
                $linkBuilder,
                $this->GetPrefix(), '',
                false);
        foreach ($this->userPartitions as $partitionName => $partitionCaption)
            $pages[] = new PageNavigatorPage($this->GetPage(), $partitionCaption, $partitionName, ($currentPage != null) && $partitionName == $currentPage, $linkBuilder, $this->GetPrefix());
    }

    /**
     * @param mixed $currentPageNumber
     * @param Dataset $dataset
     */
    function ApplyPageToDataset($currentPageNumber, Dataset $dataset) {
        if (!isset($currentPageNumber) || $currentPageNumber == '') {
            $this->FillUserPartitions();
            $userPartitionsKeys = array_keys($this->userPartitions);
            if ($this->GetAllowViewAllRecords())
                $currentPageNumber = null;
            else
                $currentPageNumber = $userPartitionsKeys[0];
        }
        if (isset($currentPageNumber)) {
            $condition = $this->DoOnGetPartitionCondition($currentPageNumber);
            if (isset($condition) && $condition != '')
                $dataset->AddCustomCondition($condition);
        }
    }

    function GetAllowViewAllRecords() {
        return $this->allowViewAllRecords;
    }

    function SetAllowViewAllRecords($value) {
        $this->allowViewAllRecords = $value;
    }

    function GetNavigationStyle() {
        return $this->navigationStyle;
    }

    function SetNavigationStyle($value) {
        $this->navigationStyle = $value;
    }
}

class CompositePageNavigator extends BasePageNavigator {

    /** @var Page */
    private $page;

    /** @var BasePageNavigator[] */
    private $pageNavigators;

    function __construct($page) {
        parent::__construct();
        $this->page = $page;
        $this->pageNavigators = array();
    }

    function AddPageNavigator($pageNavigator) {
        $this->pageNavigators[] = $pageNavigator;
    }

    function AddCurrentPageParameters(&$linkBuilder) {
        foreach ($this->pageNavigators as $pageNavigator)
            $pageNavigator->AddCurrentPageParameters($linkBuilder);
    }

    /**
     * @return LinkBuilder
     */
    function CreateLinkBuilder() {
        return $this->page->CreateLinkBuilder();
    }

    function ProcessMessages() {
        foreach ($this->pageNavigators as $pageNavigator) {
            $pageNavigator->ProcessMessages();
        }

        $linkBuilder = $this->CreateLinkBuilder();
        $this->BuildPages($linkBuilder);
    }

    /**
     * @param Renderer $renderer
     */
    function Accept($renderer) {
        $renderer->RenderCompositePageNavigator($this);
    }

    function GetPageNavigators() {
        return $this->pageNavigators;
    }

    public function BuildPages(LinkBuilder $linkBuilder) {
        foreach ($this->pageNavigators as $pageNavigator) {
            $pageNavigator->AddCurrentPageParameters($linkBuilder);
            $pageNavigator->BuildPages($linkBuilder->CloneLinkBuilder());
        }
    }
}
