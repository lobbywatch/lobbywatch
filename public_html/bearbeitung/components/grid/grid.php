<?php
// Processed by afterburner.sh



include_once dirname(__FILE__) . '/../renderers/renderer.php';
include_once dirname(__FILE__) . '/../component.php';
include_once dirname(__FILE__) . '/../editors/editors.php';
include_once dirname(__FILE__) . '/../editors/dynamic_combobox.php';
include_once dirname(__FILE__) . '/../editors/multivalue_select.php';
include_once dirname(__FILE__) . '/../editors/checkboxgroup.php';
include_once dirname(__FILE__) . '/../utils/array_utils.php';

include_once dirname(__FILE__) . '/action_list.php';
include_once dirname(__FILE__) . '/grid_states/grid_states.php';
require_once dirname(__FILE__) . "/../../../custom/custom_grid_states.php"; // Processed by afterburner.sh

include_once dirname(__FILE__) . '/layouts/form_layout.php';
include_once dirname(__FILE__) . '/columns/view_column_group.php';

include_once dirname(__FILE__) . '/filters/column_filter/column_filter.php';
include_once dirname(__FILE__) . '/filters/filter_builder/filter_builder.php';
include_once dirname(__FILE__) . '/filters/quick_filter.php';
include_once dirname(__FILE__) . '/filters/selection_filter.php';

define('otAscending', 1);
define('otDescending', 2);

function GetOrderTypeAsSQL($orderType) {
    return $orderType == otAscending ? 'ASC' : 'DESC';
}

$orderTypeCaptions = array(
    otAscending => 'a',
    otDescending => 'd');

class SortColumn {

    private $fieldName;

    private $orderType;

    function __construct($fieldName, $orderType) {
        $this->fieldName = $fieldName;
        $this->orderType = $orderType;
    }

    public function getFieldName() {
        return $this->fieldName;
    }

    public function getSQLOrderType() {
        return $this->orderType;
    }

    public function getShortOrderType() {
        return $this->orderType == 'ASC' ? 'a' : 'd';
    }

    public function getOrderType() {
        return $this->orderType;
    }
}

class ViewMode
{
    const TABLE = 0;
    const CARD = 1;

    static function getDefaultMode()
    {
        return self::TABLE;
    }

    static function getList()
    {
        return array(
            self::TABLE => 'TableViewMode',
            self::CARD => 'CardViewMode'
        );
    }
}

class Grid {
    /** @var string */
    private $name;

    /** @var CustomEditColumn[] */
    private $editColumns = array();

    /** @var CustomEditColumn[] */
    private $multiEditColumns = array();

    /** @var AbstractViewColumn[] */
    private $viewColumns = array();

    /** @var AbstractViewColumn[] */
    private $printColumns = array();

    /** @var CustomEditColumn[] */
    private $insertColumns = array();

    /** @var CustomEditColumn[] */
    private $multiUploadColumns = array();

    /** @var AbstractViewColumn[] */
    private $exportColumns = array();

    /** @var AbstractViewColumn[] */
    private $singleRecordViewColumns = array();

    /** @var AbstractViewColumn[] */
    private $compareHeaderColumns = array();

    /** @var AbstractViewColumn[] */
    private $compareColumns = array();

    /** @var IDataset */
    private $dataset;

    /** @var GridState */
    private $gridState;

    /** @var Page */
    private $page;

    /** @var bool */
    private $showAddButton;

    /** @var array */
    private $messages = array();

    /** @var array */
    private $errorMessages = array();

    /** @var bool */
    private $popFlashMessages = true;

    /** @var bool */
    private $allowDeleteSelected = false;

    /** @var bool */
    private $allowCompare = false;

    /** @var bool */
    private $allowAddMultipleRecords = true;

    /** @var bool */
    private $multiEditAllowed = true;

    /** @var bool */
    private $useModalMultiEdit = false;

    /** @var bool */
    private $includeAllFieldsForMultiEditByDefault = true;

    /** @var bool */
    private $allowMultiUpload = false;

    //
    public $Width;
    public $Margin;

    /**
     * @var ColumnFilter
     */
    public $columnFilter;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var QuickFilter
     */
    private $quickFilter;

    /** @var SelectionFilter */
    private $selectionFilter;
    //
    private $orderColumnFieldName;
    private $orderType;

    /** @var SortColumn[] */
    private $sortedColumns;

    /** @var SortColumn[] */
    private $defaultSortedColumns;

    /**
     * @var ViewColumnGroup
     */
    private $viewColumnGroup;

    private $highlightRowAtHover;
    //
    public $OnDisplayText;
    //
    private $defaultOrderColumnFieldName;
    private $defaultOrderType;
    private $useImagesForActions;
    //

    /** @var ActionList */
    private $actions;

    //
    private $editClientValidationScript;
    private $insertClientValidationScript;

    private $editClientFormLoadedScript;
    private $insertClientFormLoadedScript;
    private $editClientEditorValueChangedScript;
    private $insertClientEditorValueChangedScript;
    private $calculateControlValuesScript;

    private $internalName;
    private $showUpdateLink = true;
    private $useFixedHeader;
    private $showLineNumbers;
    private $showKeyColumnsImagesInHeader;
    private $width;

    private $enableRunTimeCustomization = true;
    private $viewMode;
    private $cardCountInRow = array('lg' => 3, 'md' => 3, 'sm' => 2, 'xs' => 1);

    /** @var Aggregate[] */
    private $totals = array();

    /** @var bool */
    private $allowSortingByClick = true;

    /** @var bool */
    private $allowSortingByDialog = true;

    /** @var Event */
    public $OnCustomRenderColumn;

    /** @var Event */
    public $OnExtendedCustomDrawRow;

    /** @var Event */
    public $BeforeShowRecord;

    /** @var Event */
    public $BeforeUpdateRecord;

    /** @var Event */
    public $BeforeInsertRecord;

    /** @var Event */
    public $BeforeDeleteRecord;

    /** @var Event */
    public $AfterUpdateRecord;

    /** @var Event */
    public $AfterInsertRecord;

    /** @var Event */
    public $AfterDeleteRecord;

    /** @var Event */
    public $OnBeforeDataChange;

    /** @var Event */
    public $OnCustomDrawRow;

    /** @var Event */
    public $OnCustomRenderTotal;

    /** @var Event */
    public $OnCustomRenderPrintColumn;

    /** @var Event */
    public $OnCustomRenderExportColumn;

    /** @var Event */
    public $OnGetCustomTemplate;

    /** @var Event */
    public $OnCustomCompareColumn;

    /** @var Event */
    public $OnCustomDefaultValues;

    /** @var Event */
    public $OnGetSelectionFilters;

    /** @var DetailColumn[] */
    private $details;

    /** @var bool */
    private $tableBordered;

    /** @var bool */
    private $tableCondensed;

    /** @var FormLayout */
    private $viewFormLayout;

    /** @var FormLayout */
    private $insertFormLayout;

    /** @var FormLayout */
    private $editFormLayout;

    /** @var FormLayout */
    private $multiEditFormLayout;

    /** @var FormLayout */
    private $multiUploadFormLayout;

    /** @var boolean */
    private $isMaster = false;

    /** @var bool */
    private $reloadPageAfterAjaxOperation = false;

    /** $var FilterComponentInterface[] */
    private $selectionFilters = array();

    /** @var bool */
    private $selectionFilterAllowed = true;

    function __construct(Page $page, Dataset $dataset) {
        $this->page = $page;
        $this->dataset = $dataset;
        $this->internalName = $page->getPageId() . 'Grid';
        //
        $this->editColumns = array();
        $this->multiEditColumns = array();
        $this->viewColumns = array();
        $this->printColumns = array();
        $this->insertColumns = array();
        $this->exportColumns = array();
        $this->singleRecordViewColumns = array();
        $this->details = array();

        $this->showAddButton = false;

        $this->OnCustomRenderTotal = new Event();
        $this->OnExtendedCustomDrawRow = new Event();
        $this->BeforeShowRecord = new Event();

        $this->BeforeUpdateRecord = new Event();
        $this->BeforeInsertRecord = new Event();
        $this->BeforeDeleteRecord = new Event();

        $this->AfterUpdateRecord = new Event();
        $this->AfterInsertRecord = new Event();
        $this->AfterDeleteRecord = new Event();
        $this->OnCustomCompareColumn = new Event();

        $this->OnCustomDrawRow = new Event();
        $this->OnCustomRenderColumn = new Event();
        $this->OnBeforeDataChange = new Event();
        $this->OnDisplayText = new Event();
        $this->OnCustomRenderPrintColumn = new Event();
        $this->OnCustomRenderExportColumn = new Event();
        $this->OnGetCustomTemplate = new Event();
        $this->OnCustomDefaultValues = new Event();
        $this->OnGetSelectionFilters = new Event();
        //
        $this->SetState(OPERATION_VIEWALL);
        $this->highlightRowAtHover = false;

        $this->defaultOrderColumnFieldName = null;
        $this->defaultOrderType = null;
        $this->sortedColumns = array();
        $this->defaultSortedColumns = array();

        $this->actions = new ActionList();

        //
        $this->useImagesForActions = true;
        $this->SetWidth(null);
        $this->SetEditClientValidationScript('');
        $this->SetInsertClientValidationScript('');

        $this->name = 'grid';
        $this->useFixedHeader = false;
        $this->showLineNumbers = false;
        $this->showKeyColumnsImagesInHeader = true;

        $this->viewMode = ViewMode::getDefaultMode();

        $this->tableBordered = false;
        $this->tableCondensed = false;

        $this->filterBuilder = new FilterBuilder();
        $this->columnFilter = new ColumnFilter();
        $this->quickFilter = new QuickFilter();
        $this->selectionFilter = new SelectionFilter($this->page->GetLocalizerCaptions());
    }

    /**
     * @param string $columnName
     * @return \AbstractViewColumn|null
     */
    private function FindViewColumnByName($columnName) {
        $columns = $this->GetViewColumns();
        foreach ($columns as $column) {
            if ($this->GetColumnName($column) == $columnName) {
                return $column;
            }
        }
        return null;
    }

    public function GetTemplate($mode, $defaultTemplate) {
        $template = '';
        $this->OnGetCustomTemplate->Fire(
            array($mode, &$template)
        );
        return ($template != '') ? $template : $defaultTemplate;
    }

    #region Options

    public function GetShowLineNumbers() {
        return $this->showLineNumbers;
    }

    public function SetShowLineNumbers($showLineNumbers) {
        $this->showLineNumbers = $showLineNumbers;
    }

    public function GetShowKeyColumnsImagesInHeader() {
        return $this->showKeyColumnsImagesInHeader;
    }

    public function SetShowKeyColumnsImagesInHeader($showKeyColumnsImagesInHeader) {
        $this->showKeyColumnsImagesInHeader = $showKeyColumnsImagesInHeader;
    }

    public function GetUseFixedHeader() {
        return $this->useFixedHeader;
    }

    public function SetUseFixedHeader($useFixedHeader) {
        $this->useFixedHeader = $useFixedHeader;
    }

    public function GetHighlightRowAtHover() {
        return $this->highlightRowAtHover;
    }

    public function SetHighlightRowAtHover($value) {
        $this->highlightRowAtHover = $value;
    }

    public function GetUseImagesForActions() {
        return $this->useImagesForActions;
    }

    public function SetUseImagesForActions($value) {
        $this->useImagesForActions = $value;
    }

    public function UseAutoWidth() {
        return !isset($this->width);
    }

    public function GetWidth() {
        return $this->width;
    }

    public function SetWidth($value) {
        $this->width = $value;
    }

    public function GetEditClientValidationScript() {
        return $this->editClientValidationScript;
    }

    public function GetInsertClientValidationScript() {
        return $this->insertClientValidationScript;
    }

    public function SetEditClientValidationScript($value) {
        $this->editClientValidationScript = $value;
    }

    public function SetInsertClientValidationScript($value) {
        $this->insertClientValidationScript = $value;
    }

    #endregion

    #region Session variables

    private function SetSessionVariable($name, $value) {
        GetApplication()->SetSessionVariable($this->GetName() . '_' . $name, $value);
    }

    private function UnSetSessionVariable($name) {
        GetApplication()->UnSetSessionVariable($this->GetName() . '_' . $name);
    }

    private function IsSessionVariableSet($name) {
        return GetApplication()->IsSessionVariableSet($this->GetName() . '_' . $name);
    }

    private function GetSessionVariable($name) {
        return GetApplication()->GetSessionVariable($this->GetName() . '_' . $name);
    }

    #endregion

    public function addErrorMessage($errorMessage, $displayTime) {
        $this->errorMessages[] = array(
            'message' => $errorMessage,
            'displayTime' => $displayTime,
        );
    }

    public function getErrorMessages() {
        return $this->errorMessages;
    }

    public function addMessage($message, $displayTime) {
        $this->messages[] = array(
            'message' => $message,
            'displayTime' => $displayTime,
        );
    }

    public function getMessages() {
        return $this->messages;
    }

    public function getPopFlashMessages()
    {
        return $this->popFlashMessages;
    }

    public function setPopFlashMessages($popFlashMessages)
    {
        $this->popFlashMessages = $popFlashMessages;
    }

    /**
     * @return Page
     */
    function GetPage() {
        return $this->page;
    }

    /**
     * @return Dataset
     */
    function GetDataset() {
        return $this->dataset;
    }

    /**
     * @return ActionList
     */
    public function getActions()
    {
        return $this->actions;
    }

    #endregion

    function CreateLinkBuilder() {
        return $this->GetPage()->CreateLinkBuilder();
    }

    function AddSingleRecordViewColumn($column) {
        $this->singleRecordViewColumns[] = $column;
        $this->DoAddColumn($column);
        return $column;
    }

    #region Columns

    /**
     * @param CustomEditColumn|AbstractViewColumn $column
     * @return void
     */
    private function DoAddColumn($column) {
        $column->SetGrid($this);
    }

    public function AddDetail($column) {
        $this->details[] = $column;
    }

    public function AddViewColumn($column) {
        if ($column instanceof DetailColumn) {
            $this->AddDetail($column);
            $this->DoAddColumn($column);
            return $column;
        }

        $this->viewColumns[] = $column;
        $this->DoAddColumn($column);

        return $column;
    }

    public function AddEditColumn($column) {
        $this->editColumns[] = $column;
        $this->DoAddColumn($column);
        return $column;
    }

    public function AddMultiEditColumn($column) {
        $this->multiEditColumns[] = $column;
        $this->DoAddColumn($column);
        return $column;
    }

    public function AddPrintColumn($column) {
        $this->printColumns[] = $column;
        $this->DoAddColumn($column);
        return $column;
    }

    public function AddInsertColumn($column) {
        $this->insertColumns[] = $column;
        $this->DoAddColumn($column);
        return $column;
    }

    public function AddMultiUploadColumn($column) {
        $this->multiUploadColumns[] = $column;
        $this->DoAddColumn($column);
        return $column;
    }

    public function AddExportColumn($column) {
        $this->exportColumns[] = $column;
        $this->DoAddColumn($column);
        return $column;
    }

    /**
     * @return CustomEditColumn[]
     */
    public function GetEditColumns() {
        return $this->editColumns;
    }

    /**
     * @return CustomEditColumn[]
     */
    public function GetMultiEditColumns() {
        return $this->multiEditColumns;
    }

    /**
     * @param boolean $includeInvisible
     * @return AbstractViewColumn[]
     */
    public function GetViewColumns($includeInvisible = false) {
        return $this->getViewBasedColumns($this->viewColumns, $includeInvisible);
    }

    /**
     * @param boolean $includeInvisible
     * @return AbstractViewColumn[]
     */
    function GetSingleRecordViewColumns($includeInvisible = false) {
        return $this->getViewBasedColumns($this->singleRecordViewColumns, $includeInvisible);
    }

    /**
     * @param boolean $includeInvisible
     * @return AbstractViewColumn[]
     */
    public function GetExportColumns($includeInvisible = false) {
        return $this->getViewBasedColumns($this->exportColumns, $includeInvisible);
    }

    /**
     * @param boolean $includeInvisible
     * @return AbstractViewColumn[]
     */
    public function GetPrintColumns($includeInvisible = false) {
        return $this->getViewBasedColumns($this->printColumns, $includeInvisible);
    }

    /**
     * @param boolean $includeInvisible
     * @return AbstractViewColumn[]
     */
    public function getCompareColumns($includeInvisible = false)
    {
        return $this->getViewBasedColumns($this->compareColumns, $includeInvisible);
    }

    /**
     * @param AbstractViewColumn[] $columns
     * @param boolean $includeInvisible
     * @return AbstractViewColumn[]
     */
    private function getViewBasedColumns($columns, $includeInvisible = false) {
        $result = array();
        foreach ($columns as $column) {
            if ($includeInvisible || $column->getVisible()) {
                $result[] = $column;
            }
        }
        return $result;
    }

    /**
     * @param string $columnName
     * @return AbstractViewColumn|null
     */
    public function getViewColumn($columnName) {
        return $this->getViewBasedColumn($this->GetViewColumns(true), $columnName);
    }

    /**
     * @param string $columnName
     * @return AbstractViewColumn|null
     */
    public function getSingleRecordViewColumn($columnName) {
        return $this->getViewBasedColumn($this->GetSingleRecordViewColumns(true), $columnName);
    }

    /**
     * @param string $columnName
     * @return AbstractViewColumn|null
     */
    public function getExportColumn($columnName) {
        return $this->getViewBasedColumn($this->GetExportColumns(true), $columnName);
    }

    /**
     * @param string $columnName
     * @return AbstractViewColumn|null
     */
    public function getPrintColumn($columnName) {
        return $this->getViewBasedColumn($this->GetPrintColumns(true), $columnName);
    }

    /**
     * @param string $columnName
     * @return AbstractViewColumn|null
     */
    public function getCompareColumn($columnName) {
        return $this->getViewBasedColumn($this->GetCompareColumns(true), $columnName);
    }

    /**
     * @param CustomEditColumn[] $columns
     * @param string $columnName
     * @return CustomEditColumn|null
     */
    private function getEditBasedColumn($columns, $columnName) {
        foreach ($columns as $column) {
            if ($column->GetName() == $columnName) {
                return $column;
            }
        }
        return null;
    }

    /**
     * @param string $columnName
     * @return CustomEditColumn|null
     */
    public function getEditColumn($columnName) {
        return $this->getEditBasedColumn($this->GetEditColumns(), $columnName);
    }

    /**
     * @param string $columnName
     * @return CustomEditColumn|null
     */
    public function getMultiEditColumn($columnName) {
        return $this->getEditBasedColumn($this->GetMultiEditColumns(), $columnName);
    }

    /**
     * @param string $columnName
     * @return CustomEditColumn|null
     */
    public function getInsertColumn($columnName) {
        return $this->getEditBasedColumn($this->GetInsertColumns(), $columnName);
    }

    /**
     * @param AbstractViewColumn[] $columns
     * @param string $columnName
     * @return AbstractViewColumn|null
     */
    private function getViewBasedColumn($columns, $columnName) {
        foreach ($columns as $column) {
            if ($this->GetColumnName($column) == $columnName) {
                return $column;
            }
        }
        return null;
    }

    /**
     * @param AbstractViewColumn[] $compareColumns
     */
    public function setCompareColumns($compareColumns)
    {
        $this->compareColumns = $compareColumns;
    }

    /**
     * @param AbstractViewColumn $compareColumn
     */
    public function addCompareColumn(AbstractViewColumn $compareColumn)
    {
        $compareColumn->setGrid($this);
        $this->compareColumns[] = $compareColumn;
    }

    /**
     * @param AbstractViewColumn[] $compareHeaderColumns
     *
     * @return $this
     */
    public function setCompareHeaderColumns($compareHeaderColumns)
    {
        $this->compareHeaderColumns = $compareHeaderColumns;

        return $this;
    }

    /**
     * @param AbstractViewColumn $compareHeaderColumn
     */
    public function addCompareHeaderColumn(AbstractViewColumn $compareHeaderColumn)
    {
        $compareHeaderColumn->setGrid($this);
        $this->compareHeaderColumns[] = $compareHeaderColumn;
    }

    /**
     * @return AbstractViewColumn[]
     */
    public function getCompareHeaderColumns()
    {
        return $this->compareHeaderColumns;
    }

    /**
     * @return CustomEditColumn[]
     */
    public function GetInsertColumns() {
        return $this->insertColumns;
    }

    /**
     * @return CustomEditColumn[]
     */
    public function GetMultiUploadColumns() {
        return $this->multiUploadColumns;
    }

    #endregion

    /**
     * @param \Renderer $renderer
     * @return void
     */
    public function Accept(Renderer $renderer) {
        $renderer->RenderGrid($this);
    }

    public function SetState($name)
    {
        $map = array(
            OPERATION_VIEW => 'SingleRecordGridState',
            OPERATION_PRINT_ONE => 'SingleRecordGridState',
            OPERATION_EDIT => 'EditGridState',
            OPERATION_MULTI_EDIT => 'MultiEditGridState',
            OPERATION_VIEWALL => 'ViewAllGridState',
            OPERATION_INSERT => 'InsertGridState',
            OPERATION_COPY => 'CopyGridState',
            OPERATION_DELETE => 'SingleRecordGridState',
            OPERATION_COMMIT_EDIT => 'CommitEditedValuesGridState',
            OPERATION_COMMIT_MULTI_EDIT => 'CommitMultiEditGridState',
            OPERATION_COMMIT_INSERT => 'CommitInsertedValuesGridState',
            OPERATION_COMMIT_MULTI_UPLOAD => 'CommitMultiUploadGridState',
            OPERATION_COMMIT_DELETE => 'CommitDeleteGridState',
            OPERATION_EXCEL_EXPORT_RECORD => 'SingleRecordGridState',
            OPERATION_WORD_EXPORT_RECORD => 'SingleRecordGridState',
            OPERATION_XML_EXPORT_RECORD => 'SingleRecordGridState',
            OPERATION_CSV_EXPORT_RECORD => 'SingleRecordGridState',
            OPERATION_PDF_EXPORT_RECORD => 'SingleRecordGridState',
            OPERATION_COMPARE => 'SelectedRecordsGridState',
            OPERATION_EXCEL_EXPORT_SELECTED => 'SelectedRecordsGridState',
            OPERATION_WORD_EXPORT_SELECTED => 'SelectedRecordsGridState',
            OPERATION_XML_EXPORT_SELECTED => 'SelectedRecordsGridState',
            OPERATION_CSV_EXPORT_SELECTED => 'SelectedRecordsGridState',
            OPERATION_PDF_EXPORT_SELECTED => 'SelectedRecordsGridState',
            OPERATION_PRINT_SELECTED => 'SelectedRecordsGridState',
            OPERATION_DELETE_SELECTED => 'DeleteSelectedGridState',
            OPERATION_INPUT_FINISHED_SELECTED => 'InputFinishedSelectedGridState', // Afterburner
            OPERATION_DE_INPUT_FINISHED_SELECTED => 'DeInputFinishedSelectedGridState', // Afterburner
            OPERATION_CONTROLLED_SELECTED => 'ControlledSelectedGridState', // Afterburner
            OPERATION_DE_CONTROLLED_SELECTED => 'DeControlledSelectedGridState', // Afterburner
            OPERATION_AUTHORIZATION_SENT_SELECTED => 'AuthorizationSentSelectedGridState', // Afterburner
            OPERATION_DE_AUTHORIZATION_SENT_SELECTED => 'DeAuthorizationSentSelectedGridState', // Afterburner
            OPERATION_AUTHORIZE_SELECTED => 'AuthorizeSelectedGridState', // Afterburner
            OPERATION_DE_AUTHORIZE_SELECTED => 'DeAuthorizeSelectedGridState', // Afterburner
            OPERATION_RELEASE_SELECTED => 'ReleaseSelectedGridState', // Afterburner
            OPERATION_DE_RELEASE_SELECTED => 'DeReleaseSelectedGridState', // Afterburner
            OPERATION_SET_IMRATBIS_SELECTED => 'SetImRatBisSelectedGridState', // Afterburner
            OPERATION_CLEAR_IMRATBIS_SELECTED => 'ClearImRatBisSelectedGridState', // Afterburner
            OPERATION_SET_EHRENAMTLICH_SELECTED => 'SetEhrenamtlichSelectedGridState', // Afterburner
            OPERATION_SET_ZAHLEND_SELECTED => 'SetZahlendSelectedGridState', // Afterburner
            OPERATION_SET_BEZAHLT_SELECTED => 'SetBezahltSelectedGridState', // Afterburner
            OPERATION_CREATE_VERGUETUNGSTRANSPARENZLISTE => 'CreateVerguetungstransparenzliste', // Afterburner
        );

        if (isset($map[$name])) {
            $className = $map[$name];
            $this->gridState = new $className($this);
        }
    }

    /**
     * @return GridState
     */
    public function GetState() {
        return $this->gridState;
    }

    public function GetInsertPageAction() {
        return $this->getActionLink(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->GetPage()->GetGridInsertHandler());
    }

    public function GetMultiUploadPageAction() {
        return $this->getActionLink(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->GetPage()->GetGridMultiUploadHandler());
    }

    public function GetEditPageAction() {
        return $this->getActionLink(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->GetPage()->GetGridEditHandler());
    }

    public function GetMultiEditPageAction() {
        return $this->getActionLink(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->GetPage()->GetGridMultiEditHandler());
    }

    public function GetReturnUrl() {
        return $this->getActionLink(OPERATION_PARAMNAME, OPERATION_RETURN);
    }

    public function GetInsertUrl() {
        return $this->getActionLink(OPERATION_PARAMNAME, OPERATION_INSERT);
    }

    private function getActionLink($parameterName, $parameterValue) {
        $linkBuilder = $this->CreateLinkBuilder();
        $linkBuilder->AddParameter($parameterName, $parameterValue);
        return $linkBuilder->GetLink();
    }

    #region Ordering

    public function GetOrderType() {
        return $this->orderType;
    }

    public function SetOrderType($value) {
        $this->orderType = $value;
    }

    public function setOrderBy($sortedColumns) {
        $this->sortedColumns = $sortedColumns;
    }

    public function setOrderByParameter($sortedColumns) {
        $newSortedColumns = array();
        foreach ($sortedColumns as $value) {
            $fieldName = urldecode(substr($value, 1, strlen($value) - 1));
            $orderType = $value[0] == 'a' ? 'ASC' : 'DESC';
            $newSortedColumns[] = new SortColumn($fieldName, $orderType);
        }
        $this->setOrderBy($newSortedColumns);
    }

    public function setDefaultOrdering($sortedColumns) {
        $this->defaultSortedColumns = $sortedColumns;
    }

    public function getSortedColumns() {
        return $this->sortedColumns;
    }

    private function ApplyDefaultOrder() {
        $this->setOrderBy($this->defaultSortedColumns);
    }

    /*
     * @param string $fieldName
     * return null|string
     */
    public function GetOrderTypeByFieldName($fieldName) {
        foreach ($this->sortedColumns as $value) {
            if ($value->getFieldName() == $fieldName) {
                return $value->getOrderType();
            }
        }

        return null;
    }

    public function getSortIndexByFieldName($fieldName)
    {
        foreach ($this->sortedColumns as $key => $value) {
            if ($value->getFieldName() == $fieldName) {
                return $key;
            }
        }

        return null;
    }

    public function getSortOrderTypeByFieldName($fieldName)
    {
        foreach ($this->sortedColumns as $value) {
            if ($value->getFieldName() == $fieldName) {
                return $value->getOrderType();
            }
        }

        return null;
    }

    public function SetOrderColumnFieldName($value) {
        $this->orderColumnFieldName = $value;
    }

    private function ExtractOrderValues() {
        if (GetApplication()->IsGETValueSet('order')) {
            $orderValue = GetApplication()->GetGETValue('order');
            if (!is_array($orderValue)) {
                $orderValue = array($orderValue);
            }
            $this->setOrderByParameter($orderValue);
            $this->SetSessionVariable($this->internalName . '_sorting', $orderValue);
        } elseif (GetOperation() == 'resetorder') {
            $this->UnSetSessionVariable($this->internalName . '_sorting');
            $this->ApplyDefaultOrder();
        } elseif ($this->IsSessionVariableSet($this->internalName . '_orderColumnFieldName')) {
            // TODO: this condition was added to support version 14.10.0.7 where sorting was realized by one column only.
            // In that version field name and order type of sorted column saved to session in parameters .._orderColumnFieldName and.. _orderType respectively
            // if these parameters were set we use it for sorting one time, deleted it from session and saved sorted columns by a new way.
            $orderColumnFieldName = $this->GetSessionVariable($this->internalName . '_orderColumnFieldName');
            $orderType = $this->GetSessionVariable($this->internalName . '_orderType');

            $this->UnSetSessionVariable($this->internalName . '_orderColumnFieldName');
            $this->UnSetSessionVariable($this->internalName . '_orderType');

            $orderValue = array(substr($orderType, 0, 1) . $orderColumnFieldName);
            $this->setOrderByParameter($orderValue);
            $this->SetSessionVariable($this->internalName . '_sorting', $orderValue);
        } elseif ($this->IsSessionVariableSet($this->internalName . '_sorting')) {
            $sessionValue = $this->GetSessionVariable($this->internalName . '_sorting');
            $this->setOrderByParameter($sessionValue);
        } else {
            $this->ApplyDefaultOrder();
        }
    }

    #endregion

    public function ExtractViewMode() {
        $sessionVariableKey = $this->GetId() . 'viewmode';
        $getParam = ($this->isMaster() ? 'master_' : '') . 'viewmode';

        if (GetApplication()->IsGETValueSet($getParam)) {
            $this->viewMode = GetApplication()->GetGETValue($getParam) == ViewMode::CARD ? ViewMode::CARD : ViewMode::TABLE;
            GetApplication()->SetSessionVariable($sessionVariableKey, $this->viewMode);
        } elseif (GetApplication()->IsSessionVariableSet($sessionVariableKey)) {
            $this->viewMode = GetApplication()->GetSessionVariable($sessionVariableKey);
        }

        if (!$this->isMaster()) {
            $sessionVariableKey = $this->GetId() . 'cardcountinrow';
            if (GetApplication()->IsGETValueSet('cardcountinrow')) {
                $this->setCardCountInRow(GetApplication()->GetGETValue('cardcountinrow'));
                GetApplication()->SetSessionVariable($sessionVariableKey, $this->cardCountInRow);
            } elseif (GetApplication()->IsSessionVariableSet($sessionVariableKey)) {
                $this->SetCardCountInRow(GetApplication()->GetSessionVariable($sessionVariableKey));
            }
        }
    }

    #region Buttons

    public function SetShowAddButton($value) {
        $this->showAddButton = $value;
    }

    public function GetShowAddButton() {
        return $this->showAddButton;
    }

    function GetPrintRecordLink() {
        $result = $this->CreateLinkBuilder();
        return $result->GetLink();
    }

    public function GetAddRecordLink() {
        $result = $this->CreateLinkBuilder();
        $result->AddParameter(OPERATION_PARAMNAME, OPERATION_INSERT);
        return $result->GetLink();
    }

    public function GetMultiUploadLink() {
        $result = $this->CreateLinkBuilder();
        $result->AddParameter(OPERATION_PARAMNAME, OPERATION_MULTI_UPLOAD);
        return $result->GetLink();
    }

    function SetShowUpdateLink($value) {
        $this->showUpdateLink = $value;
    }

    function GetShowUpdateLink() {
        return $this->showUpdateLink;
    }

    function SetAllowDeleteSelected($value) {
        $this->allowDeleteSelected = $value;
    }

    function GetAllowInputFinishedSelected() { // Afterburner
      $columns = $this->GetEditColumns(); // Afterburner
      $datasetName = preg_replace('/[`]/i', '', $this->GetDataset()->GetName()); // Afterburner
      return ($this->getMultiEditAllowed()) && is_column_present($columns,'eingabe_abgeschlossen_datum') && is_column_present($columns,'eingabe_abgeschlossen_visa'); // Afterburner
    }

    function GetAllowControlledSelected() { // Afterburner
      $columns = $this->GetEditColumns(); // Afterburner
      $datasetName = preg_replace('/[`]/i', '', $this->GetDataset()->GetName()); // Afterburner
      return ($this->getMultiEditAllowed()) && is_column_present($columns,'kontrolliert_datum') && is_column_present($columns,'kontrolliert_visa') && isFullWorkflowUser(); // Afterburner
    }

    function GetAllowAuthorizationSentSelected() { // Afterburner
      $columns = $this->GetEditColumns(); // Afterburner
      return $this->GetAllowDeleteSelected() && is_column_present($columns,'autorisierung_verschickt_datum') && is_column_present($columns,'autorisierung_verschickt_visa') && isFullWorkflowUser(); // Afterburner
    }

    function GetAllowAuthorizeSelected() { // Afterburner
      $columns = $this->GetEditColumns(); // Afterburner
      return $this->GetAllowDeleteSelected() && is_column_present($columns,'autorisiert_datum') && is_column_present($columns,'autorisiert_visa') /* && is_column_present($columns,'autorisierung_verschickt_datum') && isFullWorkflowUser() */; // Afterburner
    }

    function GetAllowReleaseSelected() { // Afterburner
      $columns = $this->GetEditColumns();
      $datasetName = preg_replace('/[`]/i', '', $this->GetDataset()->GetName()); // Afterburner
      return ($this->getMultiEditAllowed()) && is_column_present($columns,'freigabe_datum') && is_column_present($columns,'freigabe_visa') && isFullWorkflowUser(); // Afterburner
    }

    function GetAllowImRatBisSelected() { // Afterburner
      $columns = $this->GetEditColumns();
      return $this->GetAllowDeleteSelected() && is_column_present($columns,'im_rat_bis'); // Afterburner
    }

    function GetAllowEhrenamtlichSelected() { // Afterburner
      $columns = $this->GetEditColumns(); // Afterburner
      $datasetName = preg_replace('/[`]/i', '', $this->GetDataset()->GetName()); // Afterburner
      return $this->GetAllowDeleteSelected() && ($datasetName == "interessenbindung" || $datasetName == "mandat"); // Afterburner
    }

    function GetAllowBezahltSelected() { // Afterburner
      $columns = $this->GetEditColumns(); // Afterburner
      $datasetName = preg_replace('/[`]/i', '', $this->GetDataset()->GetName()); // Afterburner
      return $this->GetAllowDeleteSelected() && ($datasetName == "interessenbindung" || $datasetName == "mandat"); // Afterburner
    }

    function GetAllowZahlendSelected() { // Afterburner
      $columns = $this->GetEditColumns(); // Afterburner
      $datasetName = preg_replace('/[`]/i', '', $this->GetDataset()->GetName()); // Afterburner
      return $this->GetAllowDeleteSelected() && ($datasetName == "interessenbindung" || $datasetName == "mandat"); // Afterburner
    }

    function GetAllowCreateVerguetungstransparenzliste() { // Afterburner
      $columns = $this->GetEditColumns(); // Afterburner
      $datasetName = preg_replace('/[`]/i', '', $this->GetDataset()->GetName()); // Afterburner
      return $this->GetAllowDeleteSelected() && ($datasetName == "parlamentarier"); // Afterburner
    }

    function GetAllowDeleteSelected() {
        return $this->allowDeleteSelected;
    }

    /**
     * @param bool $allowCompare
     *
     * @return $this
     */
    public function setAllowCompare($allowCompare)
    {
        $this->allowCompare = $allowCompare;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAllowCompare()
    {
        return $this->allowCompare;
    }

    /**
     * @param bool $allowAddMultipleRecords
     */
    public function setAllowAddMultipleRecords($allowAddMultipleRecords)
    {
        $this->allowAddMultipleRecords = $allowAddMultipleRecords;
    }

    /**
     * @param bool $allowAddMultipleRecords
     */
    public function getAllowAddMultipleRecords()
    {
        return $this->allowAddMultipleRecords;
    }

    #endregion

    function ProcessMessages() {
        $this->ExtractOrderValues();

        if (!$this->getPage()->isInline()) {
            $this->ExtractViewMode();
        }

        $this->gridState->ProcessMessages();

        if ($this->getPopFlashMessages()) {
            $this->restoreFlashMessageFromSession();
        }
    }

    public function processFilters()
    {
        $this->dataset->Connect();
        $masterConnection = $this->dataset->getConnectionFactory()->getMasterConnection();
        $sourceSelect = $this->dataset->getSelectCommand();
        $superGlobals = new SuperGlobals();
        $captions = $this->GetPage()->GetLocalizerCaptions();

        $isSubmitted = false;

        $isSubmitted = $isSubmitted || $this->processFilterBuilder(
            $this->filterBuilder,
            $superGlobals
        );
        $this->applyFilterComponent(
            $sourceSelect,
            $this->filterBuilder->getFilterComponent()
        );

        $isSubmitted = $isSubmitted || $this->processQuickFilter(
            $this->quickFilter,
            $superGlobals
        );

        $this->applyFilterComponent(
            $sourceSelect,
            $this->quickFilter->getFilterComponent()
        );

        $this->columnFilter->createFilterComponent(
            $masterConnection,
            $sourceSelect,
            $captions
        );

        $isSubmitted = $isSubmitted || $this->processColumnFilter(
            $this->columnFilter,
            $masterConnection,
            $sourceSelect,
            $superGlobals
        );

        $this->applyFilterComponent(
            $sourceSelect,
            $this->columnFilter->getFilterComponent()
        );

        $this->processSelectionFilter($superGlobals);

        return $isSubmitted;
    }

    private function processSelectionFilter(SuperGlobals $superGlobals) {
        $selectionFilter = array(
            'condition' => '',
            'keys' => array()
        );
        $id = 'selection_filter';

        if ($superGlobals->isGetValueSet($id)) {
            $selectionFilter['condition'] = $superGlobals->getGetValue($id);
            $selectionFilter['keys'] = $superGlobals->getGETValueDef('keys', array());
        } else {
            $selectionFilter = array_merge($selectionFilter, $superGlobals->getSessionVariableDef($this->getId() . $id, array()));
        }

        $this->selectionFilter->deserialize($selectionFilter);
        $this->selectionFilter->applyFilter($this->dataset);
        $superGlobals->setSessionVariable($this->getId() . $id, $this->selectionFilter->serialize());
    }

    public function clearFilters()
    {
        $superGlobals = new SuperGlobals();
        $superGlobals->UnSetSessionVariable($this->getId() . 'filter_builder');
        $superGlobals->UnSetSessionVariable($this->getId() . 'column_filter');
        $superGlobals->UnSetSessionVariable($this->getId() . 'quick_filter');
    }

    private function processFilterBuilder(
        FilterBuilder $filterBuilder,
        SuperGlobals $superGlobals)
    {
        $id = 'filter_builder';
        $filterData = $this->getFilterDataFromGlobals($superGlobals, $id);
        if (is_null($filterData)) {
            return false;
        }

        $filterBuilder->setFilterComponent(FilterGroup::deserialize(
            new FixedKeysArray($filterBuilder->getColumns()),
            $filterData
        ));

        $superGlobals->setSessionVariable(
            $this->getId() . $id,
            $filterBuilder->serialize()
        );

        return $superGlobals->isPostValueSet($id);
    }

    private function processColumnFilter(
        ColumnFilter $columnFilter,
        EngConnection $connection,
        BaseSelectCommand $sourceSelect,
        SuperGlobals $superGlobals)
    {
        $id = 'column_filter';
        $captions = $this->GetPage()->GetLocalizerCaptions();

        $filterData = $this->getColumnFilterData($id, $superGlobals);

        $columnFilter->restoreEnabledComponents(
            $connection,
            $sourceSelect,
            $captions,
            $filterData
        );

        $superGlobals->setSessionVariable(
            $this->getId() . $id,
            array_merge($columnFilter->serializeEnabledComponents(), $filterData)
        );

        return $superGlobals->isPostValueSet($id);
    }

    private function getColumnFilterData($id, SuperGlobals $superGlobals)
    {
        $prevData = array_merge(array(
            'children' => array(),
            'order' => array(),
        ), $superGlobals->getSessionVariableDef(
            $this->getId() . $id,
            array()
        ));

        try {
            $nextData = $superGlobals->isPostValueSet($id)
                ? SystemUtils::FromJSON($superGlobals->getPostValue($id), true)
                : $prevData;
        } catch (Exception $e) {
            $nextData = null;
        }

        if (is_null($nextData)) {
            return array();
        }

        $disabledChildren = array();
        $nextData['order'] = $prevData['order'];
        foreach ($nextData['children'] as $columnName => $enabledOptions) {
            $selectedOptions = array_key_exists('children', $enabledOptions) ? $enabledOptions['children'] : $enabledOptions;
            if ((count($selectedOptions) > 0) || (array_key_exists('selectedDateTimeValues', $enabledOptions))) {
                if (!in_array($columnName, $prevData['order'])) {
                    $nextData['order'][] = $columnName;
                }
            } else {
                $disabledChildren[] = $columnName;
            }
        }

        $nextData['order'] = array_diff($nextData['order'], $disabledChildren);
        $nextData['order'] = array_intersect($nextData['order'], array_keys($nextData['children']));
        return $nextData;
    }

    private function processQuickFilter(
        QuickFilter $quickFilter,
        SuperGlobals $superGlobals)
    {
        $id = 'quick_filter';
        $filterValue = $superGlobals->isGetValueSet($id)
            ? $superGlobals->getGetValue($id)
            : $superGlobals->getSessionVariableDef($this->getId() . $id);

        $filterFieldsParam = 'quick_filter_fields';
        if ($superGlobals->isGetValueSet($filterFieldsParam)) {
            $filterFields = $superGlobals->getGetValue($filterFieldsParam);
        } elseif (!$superGlobals->isGetValueSet($id) && ($superGlobals->IsSessionVariableSet($this->getId() . $filterFieldsParam) )) {
            $filterFields = $superGlobals->getSessionVariable($this->getId() . $filterFieldsParam);
        } else {
            $filterFields = array();
        }

        if (!is_array($filterFields)) {
            $filterFields = array();
        }

        $filterOperatorParam = 'quick_filter_operator';
        $filterOperator = $superGlobals->isGetValueSet($filterOperatorParam)
            ? $superGlobals->getGetValue($filterOperatorParam)
            : $superGlobals->getSessionVariableDef($this->getId() . $filterOperatorParam, FilterConditionOperator::CONTAINS);

        $quickFilter->setValue($filterValue);
        $quickFilter->setSelectedFieldNames($filterFields);
        $quickFilter->setOperator($filterOperator);
        $quickFilter->Apply();

        $superGlobals->setSessionVariable($this->getId() . $id, $filterValue);
        $superGlobals->setSessionVariable($this->getId() . $filterFieldsParam, $filterFields);
        $superGlobals->setSessionVariable($this->getId() . $filterOperatorParam, $filterOperator);

        return false;
    }

    private function applyFilterComponent(
        BaseSelectCommand $sourceSelect,
        FilterComponentInterface $filterComponent)
    {
        if ($filterComponent->isEnabled() && !$filterComponent->isCommandFilterEmpty()) {
            $sourceSelect->AddCompositeFilter($filterComponent->getCommandFilter());
        }
    }

    private function getFilterDataFromGlobals(SuperGlobals $superGlobals, $id, $default = null)
    {
        return $superGlobals->isPostValueSet($id)
            ? SystemUtils::FromJSON($superGlobals->getPostValue($id), true)
            : $superGlobals->getSessionVariableDef($this->getId() . $id, $default);
    }

    private function restoreFlashMessageFromSession() {
        $session = ArrayWrapper::createSessionWrapperForDirectory();
        $id = get_class($this->getPage()) . '_messages';

        if ($session->isValueSet($id)) {
            foreach ($session->getValue($id, array()) as $item) {
                $this->addMessage($item['message'], $item['displayTime']);
            }

            $session->unsetValue($id);
        }
    }

    public function addFlashMessage($message, $displayTime) {
        $session = ArrayWrapper::createSessionWrapperForDirectory();
        $id = get_class($this->getPage()) . '_messages';
        $messages = $session->getValue($id, array());
        $messages[] = array('message' => $message, 'displayTime' => $displayTime);
        $session->setValue($id, $messages);
    }

    function GetPrimaryKeyValuesFromGet() {
        $primaryKeyValues = array();
        ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);
        return $primaryKeyValues;
    }

    public function GetName() {
        return $this->name;
    }

    public function SetName($value) {
        $this->name = $value;
    }

    #region Totals

    public function HasTotals() {
        return count($this->totals) > 0;
    }

    public function SetTotal($columnName, Aggregate $aggregate) {
        $this->totals[$columnName] = $aggregate;
    }

    /**
     * @param AbstractViewColumn $column
     * @return Aggregate
     */
    public function GetAggregateFor(AbstractViewColumn $column) {
        return ArrayUtils::GetArrayValueDef($this->totals, $column->GetName());
    }

    public function GetTotalValues() {
        $command = new AggregationValuesQuery(
            $this->GetDataset()->GetSelectCommand(),
            $this->GetDataset()->GetCommandImp()
        );
        foreach ($this->totals as $columnName => $aggregate)
            $command->AddAggregate($columnName, $aggregate, $columnName);

        $result = array();
        $this->GetDataset()->GetConnection()->ExecQueryToArray(
            $command->GetSQL(), $result
        );
        return $result[0];
    }

    public function getAllowSortingByClick() {
        return $this->allowSortingByClick;
    }

    public function setAllowSortingByClick($value) {
        $this->allowSortingByClick = $value;
    }

    public function getAllowSortingByDialog() {
        return $this->allowSortingByDialog;
    }

    public function setAllowSortingByDialog($value) {
        $this->allowSortingByDialog = $value;
    }

    public function allowSorting() {
        return $this->allowSortingByClick || $this->allowSortingByDialog;
    }

    public function GetEditClientFormLoadedScript() {
        return $this->editClientFormLoadedScript;
    }

    public function SetEditClientFormLoadedScript($editClientFormLoadedScript) {
        $this->editClientFormLoadedScript = $editClientFormLoadedScript;
    }

    public function GetInsertClientFormLoadedScript() {
        return $this->insertClientFormLoadedScript;
    }

    public function SetInsertClientFormLoadedScript($insertClientFormLoadedScript) {
        $this->insertClientFormLoadedScript = $insertClientFormLoadedScript;
    }

    public function GetEditClientEditorValueChangedScript() {
        return $this->editClientEditorValueChangedScript;
    }

    public function SetEditClientEditorValueChangedScript($editClientEditorValueChangedScript) {
        $this->editClientEditorValueChangedScript = $editClientEditorValueChangedScript;
    }

    public function GetInsertClientEditorValueChangedScript() {
        return $this->insertClientEditorValueChangedScript;
    }

    public function SetInsertClientEditorValueChangedScript($insertClientEditorValueChangedScript) {
        $this->insertClientEditorValueChangedScript = $insertClientEditorValueChangedScript;
    }

    public function getCalculateControlValuesScript() {
        return $this->calculateControlValuesScript;
    }

    public function setCalculateControlValuesScript($value) {
        $this->calculateControlValuesScript = $value;
    }

    #endregion

    public function GetId() {
        return $this->internalName;
    }

    public function SetId($value) {
        $this->internalName = $value;
    }

    public function GetHiddenValues() {
        return $this->GetPage()->GetHiddenGetParameters();
    }

    /**
     * @return DetailColumn[]
     */
    public function getDetails()
    {
        return $this->details;
    }

    public function GetHasDetails() {
        return count($this->details) > 0;
    }

    private function IsShowCurrentRecord() {
        $show = true;
        $this->BeforeShowRecord->Fire(array(&$show));
        return $show;
    }

    public function GetColumnName(AbstractViewColumn $column) {
        return $column->GetName();
    }

    public function RenderColumn(Renderer $renderer, AbstractViewColumn $column) {
        return $this->renderCell($renderer, $column, $this->GetDataset()->GetFieldValues());
    }

    public function renderCell(Renderer $renderer, AbstractViewColumn $column, $rowValues) {
        $handled = false;
        $defaultRenderingResult = $renderer->Render($column);
        $result = $defaultRenderingResult;
        $this->OnCustomRenderColumn->Fire(array(
            $this->GetColumnName($column),
            $column->GetValue(),
            $rowValues, &$result, &$handled));
        $result = $handled ? $result : $defaultRenderingResult;
        return $result;
    }

    public function renderExportCell(Renderer $renderer, AbstractViewColumn $column, $rowValues, $exportType) {
        $handled = false;
        $defaultRenderingResult = $renderer->Render($column);
        $result = $defaultRenderingResult;
        $this->OnCustomRenderExportColumn->Fire(array(
            $exportType,
            $this->GetColumnName($column),
            $column->GetValue(),
            $rowValues, &$result, &$handled));
        $result = $handled ? $result : $defaultRenderingResult;
        return $result;
    }

    private function GetStylesForColumn(Grid $grid, $rowData) {
        $cellFontColor = array();
        $cellFontSize = array();
        $cellBgColor = array();
        $cellItalicAttr = array();
        $cellBoldAttr = array();

        $grid->OnCustomDrawRow->Fire(array($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr));

        $result = array();
        $fieldNames = array_unique(array_merge(
            array_keys($cellFontColor),
            array_keys($cellFontSize),
            array_keys($cellBgColor),
            array_keys($cellItalicAttr),
            array_keys($cellBoldAttr)));

        $fieldStylesBuilder = new StyleBuilder();
        foreach ($fieldNames as $fieldName) {
            $fieldStylesBuilder->Clear();
            if (array_key_exists($fieldName, $cellFontColor))
                $fieldStylesBuilder->Add('color', $cellFontColor[$fieldName]);
            if (array_key_exists($fieldName, $cellFontSize))
                $fieldStylesBuilder->Add('font-size', $cellFontSize[$fieldName]);
            if (array_key_exists($fieldName, $cellBgColor))
                $fieldStylesBuilder->Add('background-color', $cellBgColor[$fieldName]);
            if (array_key_exists($fieldName, $cellItalicAttr))
                $fieldStylesBuilder->Add('font-style',
                    $cellItalicAttr[$fieldName] ? 'italic' : 'normal');
            if (array_key_exists($fieldName, $cellBoldAttr)) {
                $fieldStylesBuilder->Add('font-weight',
                    $cellBoldAttr[$fieldName] ? 'bold' : 'normal');
            }
            $result[$fieldName] = $fieldStylesBuilder->GetStyleString();
        }

        return $result;
    }

    public function GetRowStylesByColumn($rowValues, &$cellClasses) {
        $result = array();
        $cellCssStyles = array();
        $rowCssStyle = '';
        $rowClasses = '';
        $this->OnExtendedCustomDrawRow->Fire(array($rowValues, &$cellCssStyles, &$rowCssStyle, &$rowClasses, &$cellClasses));
        $cellCssStyles_Simple = $this->GetStylesForColumn($this, $rowValues);
        $cellCssStyles = array_merge($cellCssStyles_Simple, $cellCssStyles);

        foreach ($this->GetViewColumns() as $column) {
            $columnName = $this->GetColumnName($column);

            if (array_key_exists($columnName, $cellCssStyles)) {
                $styleBuilder = new StyleBuilder();
                $styleBuilder->AddStyleString($rowCssStyle);
                $styleBuilder->AddStyleString($cellCssStyles[$columnName]);
                $result[$columnName] = $styleBuilder->GetStyleString();
            } else
                $result[$columnName] = $rowCssStyle;
            if (!(array_key_exists($columnName, $cellClasses))) {
               $cellClasses[$columnName] = '';
            }
        }

        return $result;
    }

    private function GetRowStyles($rowValues, &$rowStyle, &$rowClasses) {
        $cellCssStyles = array();
        $cellClasses = array();
        $this->OnExtendedCustomDrawRow->Fire(array($rowValues, &$cellCssStyles, &$rowStyle, &$rowClasses, &$cellClasses));
    }

    private function GetRowsViewData(Renderer $renderer) {
        $result = array();
        $dataset = $this->GetDataset();

        $editColumnNames = array();
        foreach ($this->GetEditColumns() as $column) {
            if ($column->getAllowListCellEdit()) {
                $editColumnNames[] = $column->GetName();
            }
        }

        $dataset->Open();
        $lineNumber = $this->GetStartLineNumber();
        while ($dataset->Next()) {

            if (!$this->IsShowCurrentRecord())
                continue;

            $primaryKeys = $dataset->GetPrimaryKeyValues();
            $rowViewData = array();

            $rowStyle = '';
            $rowClasses = '';
            $this->GetRowStyles($this->GetDataset()->GetFieldValues(), $rowStyle, $rowClasses);

            $cellClasses = array();
            $rowStyleByColumns = $this->GetRowStylesByColumn($this->GetDataset()->GetFieldValues(), $cellClasses);
            $hasEditGrant = $this->allowDisplayEditButtonOnViewForm();

            foreach ($this->GetViewColumns() as $column) {

                $columnName = $column->GetName();

                $columnRenderResult = $this->RenderColumn($renderer, $column);

                $rowViewData[$columnName] = array(
                    'ColumnName' => $columnName,
                    'ColumnCaption' => $column->GetCaption(),
                    'Data' => $columnRenderResult,
                    'Value' => $column->getValue(),
                    'FieldName' => $columnName,
                    'Classes' => $column->GetGridColumnClass(),
                    'CellClasses' => $this->getEffectiveCellClasses($column->GetGridColumnClass(), $cellClasses[$columnName]),
                    'Style' => $rowStyleByColumns[$columnName],
                    'EditUrl' => $hasEditGrant && in_array($columnName, $editColumnNames)
                        ? $this->getColumnEditUrl($primaryKeys, $column)
                        : null,
                );
            }

            $actionsRowViewData = array();

            foreach ($this->getActions()->getOperations() as $operation) {
                $operationName = $dataset->IsLookupField($operation->GetName()) ?
                    $dataset->IsLookupFieldNameByDisplayFieldName($operation->GetName()) :
                    $operation->GetName();

                $actionsRowViewData[$operationName] = array(
                    'IconClass' => $operation->GetIconClassByOperationName(),
                    'OperationName' => $operationName,
                    'Data' => $operation->GetValue(),
                    'LinkTarget' => $this->getOperationLinkTarget($operationName)
                );
            }

            $detailsViewData = array();
            foreach ($this->details as $detail) {
                $detailsViewData[] = $detail->GetViewData();
            }

            $result[] = array(
                'DataCells' => $rowViewData,
                'ActionsDataCells' => $actionsRowViewData,
                'LineNumber' => $lineNumber,
                'PrimaryKeys' => $dataset->GetPrimaryKeyValues(),
                'Style' => $rowStyle,
                'Classes' => $rowClasses,
                'Details' => array(
                    'Items' => $detailsViewData,
                    'JSON' => htmlspecialchars(SystemUtils::ToJSON($detailsViewData))
                )
            );
            $lineNumber++;
        }
        return $result;
    }

    private function getEffectiveCellClasses($columnClasses, $cellClasses) {
        $result = '';
        if ($this->GetViewMode() === ViewMode::TABLE) {
            StringUtils::AddStr($result, $columnClasses, ' ');
        }
        StringUtils::AddStr($result, $cellClasses, ' ');
        return $result;
    }

    private function GetTotalDataForColumn(AbstractViewColumn $column, $totalValues) {

        if (isset($totalValues[$column->GetName()])) {
            $aggregate = $this->GetAggregateFor($column)->AsString();

            $totalValue = $totalValues[$column->GetName()];

            $customTotalValue = '';
            $handled = false;
            $this->OnCustomRenderTotal->Fire(array(
                $totalValue,
                $aggregate,
                $column->GetName(),
                &$customTotalValue,
                &$handled
            ));

            if ($handled) {
                return $customTotalValue;
            }

            if ($column instanceof NumberViewColumn) {
                $totalValue = number_format(
                    (double) $totalValue,
                    $column->GetNumberAfterDecimal(),
                    $column->GetDecimalSeparator(),
                    $column->GetThousandsSeparator()
                );

            }

            return StringUtils::Format('%s = %s', $aggregate, $totalValue);
        }
        return '';
    }

    /**
     * @param AbstractViewColumn[] $columns
     * @return array|null
     */
    public function getTotalsViewData($columns) {
        if (!$this->RequestFilterFromUser() and $this->HasTotals()) {
            $result = array();
            $totalValues = $this->GetTotalValues();
            foreach ($columns as $column) {
                $result[] = array(
                    'Classes' => $column->GetGridColumnClass(),
                    'Caption' => $column->getCaption(),
                    'Value' => $this->GetTotalDataForColumn($column, $totalValues),
                    'Align' => $column->GetAlign()
                );
            }
            return $result;
        }
        return null;
    }

    private function GetStartLineNumber() {
        $startLineNumber = 1;
        $paginationControl = $this->GetPage()->GetPaginationControl();
        if (isset($paginationControl)) {
            $startLineNumber =
                ($paginationControl->CurrentPageNumber() - 1) * ($paginationControl->GetRowsPerPage()) + 1;
        }
        return $startLineNumber;
    }

    private function GetAdditionalAttributes() {
        $result = new AttributesBuilder();
        if ($this->GetShowLineNumbers()) {
            $result->AddAttrValue('data-start-line-number', $this->GetStartLineNumber());
        }
        return $result->GetAsString();
    }

    public function RequestFilterFromUser() {
        return !$this->isMaster()
            && $this->GetPage()->isFilterConditionRequired()
            && $this->getFilterBuilder()->isCommandFilterEmpty()
            && $this->getColumnFilter()->isCommandFilterEmpty()
            && $this->getQuickFilter()->isCommandFilterEmpty();
    }

    private function GetHiddenValuesJson() {
        return SystemUtils::ToJSON($this->GetHiddenValues());
    }

    public function GetViewData(Renderer $renderer) {
        $actionsViewData = $this->getActions()->getViewData();

        $rows = array();
        $parentPage = $this->GetPage();
        $emptyGridMessage = $parentPage->GetLocalizerCaptions()->GetMessageString('NoDataToDisplay');
        if ($this->RequestFilterFromUser()) {
            $emptyGridMessage = $parentPage->GetLocalizerCaptions()->GetMessageString('CreateFilterConditionFirst');
        } else {
            $rows = $this->GetRowsViewData($renderer);
        }

        $sortableColumns = array();
        $sortableColumnsForJSON = array();
        $viewColumnGroup = $this->getViewColumnGroup();
        $columns = $viewColumnGroup->getLeafs();
        foreach($columns as $column) {
            if ($column->allowSorting()) {
                $sortableColumn = array(
                    'name' => $column->getFieldName(),
                    'index' => $column->getSortIndex(),
                    'caption' => $column->getCaption(),
                );
                $sortableColumns[$column->getFieldName()] = $sortableColumn;
                $sortableColumnsForJSON[$column->getName()] = array_merge($sortableColumn, array(
                    'caption' => StringUtils::ConvertTextToEncoding($column->getCaption(), $parentPage->getContentEncoding(), 'UTF-8')
                ));
            }
        }

        return array(
            'SortableColumns' => $sortableColumns,
            'SortableColumnsJSON' => SystemUtils::ToJSON($sortableColumnsForJSON),

            'Id' => $this->GetId() . '_' . uniqid(),
            'SelectionId' => $this->getSelectionId(),
            'MaxWidth' => $this->GetWidth(),
            'Classes' => $this->GetGridClasses(),
            'Attributes' => $this->GetAdditionalAttributes(),
            'HiddenValuesJson' => $this->GetHiddenValuesJson(),
            'EmptyGridMessage' => $emptyGridMessage,

            'FilterBuilder' => $this->filterBuilder,
            'QuickFilter' => $this->quickFilter,
            'ColumnFilter' => $this->columnFilter,
            'SelectionFilter' => $this->selectionFilter,

            'AllowDeleteSelected' => $this->GetAllowDeleteSelected(),
            'AllowCompare' => $this->GetAllowCompare(),
            'AllowPrintSelected' => $this->GetPage()->getAllowPrintSelectedRecords(),
            'PrintLinkTarget' => $this->GetPage()->getPrintLinkTarget(),
            'MultiEditAllowed' => $this->getMultiEditAllowed(),
            'UseModalMultiEdit' => $this->getUseModalMultiEdit(),
            'AllowSelect' => $this->getAllowSelect(),
            'AllowExportSelected' => $this->GetPage()->getAllowExportSelectedRecords(),
            'AllowMultiUpload' => $this->getAllowMultiUpload(),
            'ReloadPageAfterAjaxOperation' => $this->getReloadPageAfterAjaxOperation(),
            'SelectionFilters' => array_keys($this->selectionFilters),
            'SelectionFilterAllowed' => $this->selectionFilterAllowed,
            // Action panel
            'ActionsPanelAvailable' => $this->getActionsPanelAvailability(),

            'Links' => array(
                'ModalInsertDialog' => $this->GetInsertPageAction(),
                'SimpleAddNewRow' => $this->GetAddRecordLink(),
                'Refresh' => $parentPage->GetLink(),
                'MultiUpload' => $this->GetMultiUploadLink(),
            ),

            'ActionsPanel' => array(
                'AddNewButton' => $this->GetShowAddButton() ? (
                    $parentPage->GetEnableModalGridInsert()
                        ? 'modal'
                        : ($parentPage->GetEnableInlineGridInsert() ? 'inline' : 'simple')
                ) : null,
                'RefreshButton' => $this->GetShowUpdateLink(),
                'InputFinishedSelectedButton' => $this->GetAllowInputFinishedSelected(), // Afterburner
                'ControlledSelectedButton' => $this->GetAllowControlledSelected(), // Afterburner
                'AuthorizationSentSelectedButton' => $this->GetAllowAuthorizationSentSelected(), // Afterburner
                'AuthorizeSelectedButton' => $this->GetAllowAuthorizeSelected(), // Afterburner
                'ReleaseSelectedButton' => $this->GetAllowReleaseSelected(), // Afterburner
                'ImRatBisSelectedButton' => $this->GetAllowImRatBisSelected(), // Afterburner
                'EhrenamtlichSelectedButton' => $this->GetAllowEhrenamtlichSelected(), // Afterburner
                'BezahltSelectedButton' => $this->GetAllowBezahltSelected(), // Afterburner
                'ZahlendSelectedButton' => $this->GetAllowZahlendSelected(), // Afterburner
                'CreateVerguetungstransparenzListButton' => $this->GetAllowCreateVerguetungstransparenzliste(), // Afterburner

            ),

            'ColumnCount' => count($this->GetViewColumns()) +
                ($this->getAllowSelect() ? 1 : 0) +
                ($this->GetShowLineNumbers() ? 1 : 0) +
                ($this->GetHasDetails() ? 1 : 0) +
                ($actionsViewData ? 1 : 0),
            'ColumnGroup' => $viewColumnGroup,
            'Actions' => $actionsViewData,
            'AddNewChoices' => $parentPage->getAddNewChoices(),

            'HasDetails' => $this->GetHasDetails(),
            'HighlightRowAtHover' => $this->GetHighlightRowAtHover(),


            'ShowLineNumbers' => $this->GetShowLineNumbers(),

            'Rows' => $rows,
            'Totals' => $this->getTotalsViewData($this->GetViewColumns()),

            'Messages' => $this->getMessages(),
            'ErrorMessages' => $this->getErrorMessages(),

            'DataSortPriority' => $this->getSortedColumns(),

            'EnableRunTimeCustomization' => $this->enableRunTimeCustomization,
            'EnableSortDialog' => $this->getEnableSortDialog(),
            'allowSortingByClick' => !$this->isMaster() && $this->allowSorting() && $this->allowSortingByClick,
            'ViewModeList' => ViewMode::getList(),
            'ViewMode' => $this->GetViewMode(),
            'CardCountInRow' => $this->GetCardCountInRow(),
            'CardClasses' => $this->getCardClasses(),

            'TableIsBordered' => $this->isTableBordered(),
            'TableIsCondensed' => $this->isTableCondensed()
        );
    }

    private function getAllowSelect() {
        return !$this->isMaster() && ($this->GetAllowCompare() || $this->GetAllowDeleteSelected() ||
            $this->GetPage()->getAllowPrintSelectedRecords() || $this->GetPage()->getAllowExportSelectedRecords() ||
            $this->getMultiEditAllowed() || (count($this->selectionFilters) > 0));
    }

    private function getActionsPanelAvailability() {
        return ($this->GetShowAddButton()) ||
            ($this->getAllowMultiUpload()) ||
            ($this->GetShowUpdateLink()) ||
            ($this->GetPage()->getExportListAvailable()) ||
            ($this->GetPage()->getPrintListAvailable()) ||
            ($this->getAllowSelect()) ||
            ($this->filterBuilder->hasColumns()) ||
            ($this->getEnableSortDialog()) ||
            ($this->enableRunTimeCustomization) ||
            ($this->GetPage()->getDetailedDescription() !== '') ||
            ($this->quickFilter->hasColumns());
    }

    public function GetInsertViewData() {
        $detailViewData = array();
        foreach ($this->details as $detail) {
            $detailViewData[] = array(
                'Caption' => $detail->GetCaption()
            );
        }

        return array(
            'FormId' => 'Form' . uniqid(),
            'FormAction' => $this->GetInsertPageAction(),
            'FormLayout' => $this->getInsertFormLayout(),
            'ClientOnLoadScript' => $this->GetInsertClientFormLoadedScript(),
            'ClientValidationScript' => $this->GetInsertClientValidationScript(),
            'ClientValueChangedScript' => $this->GetInsertClientEditorValueChangedScript(),
            'ClientCalculateControlValuesScript' => $this->getCalculateControlValuesScript(),
            'CancelUrl' => $this->GetReturnUrl(),
            'InsertUrl' => $this->GetInsertUrl(),
            'Title' => $this->resolveFormTitle(
                $this->GetPage()->GetLocalizerCaptions()->GetMessageString('AddNewRecord'),
                $this->GetPage()->GetInsertFormTitle(),
                array()
            ),
            'Details' => $detailViewData,
            'Messages' => $this->getMessages(),
            'ErrorMessages' => $this->getErrorMessages(),
            'AllowAddMultipleRecords' => $this->allowAddMultipleRecords,
        );
    }

    public function GetMultiUploadViewData() {
        return
            array(
                'FormId' => 'Form' . uniqid(),
                'FormAction' => $this->GetMultiUploadPageAction(),
                'FormLayout' => $this->getMultiUploadFormLayout(),
                'Title' => $this->GetPage()->GetLocalizerCaptions()->GetMessageString('UploadFiles'),
                'CancelUrl' => $this->GetReturnUrl()
            );
    }

    public function GetInlineInsertViewData() {
        return array_merge($this->GetInsertViewData(), array(
            'FormLayout' => $this->getInlineInsertFormLayout(),
        ));
    }

    public function GetCommonEditViewData() {
        $detailViewData = array();
        foreach ($this->details as $detail) {
            $linkBuilder = $this->CreateLinkBuilder();
            $detail->DecorateLinkForPostMasterRecord($linkBuilder);

            $detailViewData[] = array(
                'Link' => $linkBuilder->GetLink(),
                'SeparatedPageLink' => $detail->GetSeparateViewLink(),
                'Caption' => $detail->GetCaption()
            );
        }

        return array(
            'FormId' => 'Form' . uniqid(),
            'ClientOnLoadScript' => $this->GetEditClientFormLoadedScript(),
            'ClientValidationScript' => $this->GetEditClientValidationScript(),
            'ClientValueChangedScript' => $this->GetEditClientEditorValueChangedScript(),
            'ClientCalculateControlValuesScript' => $this->getCalculateControlValuesScript(),
            'CancelUrl' => $this->GetReturnUrl(),
            'InsertUrl' => $this->GetInsertUrl(),
            'Details' => $detailViewData,
            'Messages' => $this->getMessages(),
            'ErrorMessages' => $this->getErrorMessages(),
            'AllowAddMultipleRecords' => false,
        );
    }

    public function GetEditViewData() {
        return
            array_merge(
                $this->GetCommonEditViewData(),
                array(
                    'FormAction' => $this->GetEditPageAction(),
                    'FormLayout' => $this->getEditFormLayout(),
                    'Title' => $this->resolveFormTitle(
                        $this->GetPage()->GetLocalizerCaptions()->GetMessageString('Edit'),
                        $this->GetPage()->GetEditFormTitle(),
                        $this->getFormColumnsReplacements($this->GetEditColumns())
                    )
                )
            );
    }

    public function GetMultiEditViewData() {
        if (!$this->getIncludeAllFieldsForMultiEditByDefault()) {
            foreach ($this->GetMultiEditColumns() as $column) {
                $column->setEnabled(false);
            }
        }
        return
            array_merge(
                $this->GetCommonEditViewData(),
                array(
                    'FormAction' => $this->GetMultiEditPageAction(),
                    'FormLayout' => $this->getMultiEditFormLayout(),
                    'Title' => $this->GetPage()->GetLocalizerCaptions()->GetMessageString('MultiEdit'),
                    'MultiEditColumns' => $this->GetMultiEditColumns(),
                    'AllFieldsToBeUpdatedByDefault' => $this->getIncludeAllFieldsForMultiEditByDefault()
                )
            );
    }

    public function GetInlineEditViewData() {
        return array_merge($this->GetEditViewData(), array(
            'FormLayout' => $this->getInlineEditFormLayout(),
        ));
    }

    public function GetExportSingleRowColumnViewData(Renderer $renderer, $exportType) {
        $Row = array();
        $rowValues = $this->GetDataset()->GetFieldValues();
        foreach ($this->GetExportColumns() as $Column) {
            $columnName = $this->GetColumnName($Column);
            $columnRenderResult = $this->renderExportCell($renderer, $Column, $rowValues, $exportType);

            $Row[$columnName] = array(
                'Caption' => $Column->GetCaption(),
                'Value' => $Column->getValue(),
                'DisplayValue' => $columnRenderResult
            );
        }

        return $Row;
    }

    public function GetExportSingleRowViewData(Renderer $renderer, $exportType)
    {
        $this->GetDataset()->Open();

        if ($this->GetDataset()->Next()) {
            $primaryKeyMap = $this->GetDataset()->GetPrimaryKeyValuesMap();
            $titleReplacements = array();
            foreach ($this->GetExportColumns(true) as $column) {
                $titleReplacements['%' . $this->GetColumnName($column) . '%'] = $column->getValue();
            }

            return array(
                'Title' => $this->resolveFormTitle($this->GetPage()->GetTitle(), $this->GetPage()->GetViewFormTitle(), $titleReplacements),
                'PrimaryKeyMap' => $primaryKeyMap,
                'Row' => $this->GetExportSingleRowColumnViewData($renderer, $exportType)
            );

        } else {
            RaiseCannotRetrieveSingleRecordError();
            return null;
        }
    }

    public function GetViewSingleRowViewData()
    {
        $detailViewData = array();
        $this->GetDataset()->Open();
        $linkBuilder = null;
        if ($this->GetDataset()->Next()) {
            $primaryKeys = $this->GetDataset()->GetPrimaryKeyValues();

            $linkBuilder = $this->CreateLinkBuilder();
            $linkBuilder->AddParameter(OPERATION_PARAMNAME, OPERATION_PRINT_ONE);
            foreach ($primaryKeys as $i => $value) {
                $linkBuilder->AddParameter("pk$i", $value);
            }

            $primaryKeyMap = $this->GetDataset()->GetPrimaryKeyValuesMap();
            foreach ($this->details as $detail) {
                $detailViewData[] = array(
                    'Link' => $detail->GetSeparateViewLink(),
                    'Caption' => $detail->GetCaption()
                );
            }

            $layout = $this->getViewFormLayout();
            $cellEditUrls = array();

            if ($this->allowDisplayEditButtonOnViewForm()) {
                $editColumnNames = array();
                foreach ($this->GetEditColumns() as $column) {
                    if ($column->getAllowSingleViewCellEdit()) {
                        $editColumnNames[] = $column->GetName();
                    }
                }

                $viewColumns = $layout->getColumns();
                foreach (array_intersect(array_keys($viewColumns), $editColumnNames) as $name) {
                    $cellEditUrls[$name] = $this->getColumnEditUrl($primaryKeys, $viewColumns[$name]);
                }
            }

            return array(
                'Details' => $detailViewData,
                'HasEditGrant' => $this->allowDisplayEditButtonOnViewForm(),
                'CancelUrl' => $this->GetReturnUrl(),
                'EditUrl' => $this->GetEditCurrentRecordLink($primaryKeys),
                'PrintOneRecord' => $this->GetPage()->GetPrintOneRecordAvailable(),
                'PrintRecordLink' => $linkBuilder->GetLink(),
                'PrintLinkTarget' => $this->GetPage()->getPrintLinkTarget(),
                'ExportButtons' => $this->GetPage()->getExportOneRecordButtonsViewData($primaryKeys),
                'Title' => $this->resolveFormTitle(
                    $this->GetPage()->GetLocalizerCaptions()->GetMessageString('View'),
                    $this->GetPage()->GetViewFormTitle(),
                    $this->getViewColumnsReplacements($this->GetSingleRecordViewColumns(true))
                ),
                'CellEditUrls' => $cellEditUrls,
                'PrimaryKeyMap' => $primaryKeyMap,
                'FormLayout' => $layout,
                'Columns' => new FixedKeysArray($layout->getColumns()),
            );

        } else {
            RaiseCannotRetrieveSingleRecordError();
            return null;
        }
    }

    /**
     * @return array
     */
    public function getCompareViewData(Renderer $renderer)
    {
        $columns = array(
            'DataColumns' => $this->getCompareColumns(),
            'HeaderColumns' => $this->getCompareHeaderColumns(),
        );

        $records = array();
        $primaryKeyValues = array();
        $columnsDiff = array();
        $dataset = $this->GetDataset();
        $dataset->Open();
        $primaryKeyFields = $dataset->getPrimaryKeyFieldNames();
        $isDiffers = false;

        if (!is_null(ArrayWrapper::createGetWrapper()->getValue('keys'))) {
            while ($dataset->Next()) {
                $recordKeys = array();

                foreach ($primaryKeyFields as $fieldName) {
                    $recordKeys[] = $dataset->GetFieldValueByName($fieldName);
                }

                $recordViewData = array(
                    'Keys' => $recordKeys,
                    'DataColumns' => array(),
                    'HeaderColumns' => array(),
                );

                foreach ($columns as $key => $typeColumns) {
                    foreach ($typeColumns as $column) {
                        $columnName = $column->GetName();
                        $columnRenderResult = $this->RenderColumn($renderer, $column);

                        $recordViewData[$key][$columnName] = array(
                            'ColumnName' => $columnName,
                            'Data' => $columnRenderResult,
                            'Value' => $column->getValue(),
                            'FieldName' => $columnName,
                        );
                    }
                }

                $records[] = $recordViewData;
                $primaryKeyValues[] = $recordKeys;
            }

            foreach ($records as $recordIndex => &$record) {
                $builder = $this->CreateLinkBuilder();
                $builder->AddParameter(OPERATION_PARAMNAME, OPERATION_COMPARE);

                foreach ($primaryKeyValues as $primaryKeyIndex => $keyValues) {
                    if ($primaryKeyIndex !== $recordIndex) {
                        foreach ($keyValues as $valueIndex => $value) {
                            $builder->AddParameter("keys[$primaryKeyIndex][$valueIndex]", $value);
                        }
                    }
                }

                $record['RemoveLink'] = $builder->getLink();
            }

            if (count($records) > 0) {
                foreach ($columns['DataColumns'] as $column) {
                    $columnName = $column->getName();
                    $isSame = true;

                    $value = $records[0]['DataColumns'][$columnName]['Value'];
                    for ($i = 1; $i < count($records); $i++) {
                        if ($value != $records[$i]['DataColumns'][$columnName]['Value']) {
                            $isSame = false;
                        }

                        $this->OnCustomCompareColumn->Fire(array(
                            $columnName,
                            $value,
                            $records[$i]['DataColumns'][$columnName]['Value'],
                            &$isSame,
                        ));

                        if (!$isSame) {
                            $isDiffers = true;
                            break;
                        }
                    }

                    $columnsDiff[$columnName] = !$isSame;
                }
            }
        }

        return array(
            'columns' => $columns,
            'columnsDiff'  => $columnsDiff,
            'isDiffers' => $isDiffers,
            'records' => $records,
            'SelectionId' => $this->getSelectionId(),
        );
    }

    private function getViewColumnsReplacements($columns)
    {
        $replacements = array();

        foreach ($columns as $column) {
            $replacements['%' . $this->GetColumnName($column) . '%'] = $column->getValue();
        }

        return $replacements;
    }

    private function getFormColumnsReplacements($columns)
    {
        $replacements = array();

        foreach ($columns as $column) {
            $replacements['%' . $column->GetFieldName() . '%'] = $column->GetEditControl()->GetDisplayValue();
        }

        return $replacements;
    }

    private function resolveFormTitle($defaultTitle, $title, $replacements)
    {
        $title = is_null($title) ? $defaultTitle : $title;

        return str_replace(array_keys($replacements), array_values($replacements), $title);
    }

    /**
     * @return boolean
     */
    private function allowDisplayEditButtonOnViewForm() {
        return
            $this->actions->hasEditOperation() &&
            $this->hasEditColumns() &&
            $this->hasEditPermission();
    }

    private function hasEditColumns() {
        return count($this->editColumns) > 0;
    }

    private function hasEditPermission() {
        return
            $this->GetPage()->GetSecurityInfo()->HasEditGrant() && $this->GetPage()->hasRLSEditGrant($this->GetDataset());
    }

    public function GetDetailLinksViewData() {
        $result = array();
        foreach ($this->details as $detail) {
            $result[] = array(
                'Caption' => $detail->GetCaption(),
                'Link' => $detail->GetSeparateViewLink(),
                'Name' => $detail->GetPageHandlerName(),
            );
        }
        return $result;
    }

    public function GetDetailsViewData()
    {
        $result = array();
        foreach ($this->details as $detail) {
            $result[] = $detail->GetViewData();
        }

        return $result;
    }

    public function FindDetail($detailEditHandlerName) {
        foreach ($this->details as $detail) {
            if ($detail->GetPageHandlerName() == $detailEditHandlerName)
                return $detail;
        }
        return null;
    }

    private function GetLinkParametersForPrimaryKey($primaryKeyValues) {
        $result = array();
        $keyValues = $primaryKeyValues;
        for ($i = 0; $i < count($keyValues); $i++)
            $result["pk$i"] = $keyValues[$i];
        return $result;
    }

    public function GetEditCurrentRecordLink($primaryKeyValues) {
        $linkBuilder = $this->CreateLinkBuilder();
        $linkBuilder->AddParameter(OPERATION_PARAMNAME, OPERATION_EDIT);
        $linkBuilder->AddParameters($this->GetLinkParametersForPrimaryKey($primaryKeyValues));
        return $linkBuilder->GetLink();
    }

    private function GetGridClasses() {
        $result = '';

        StringUtils::AddStr($result, 'table-striped', ' ');

        if ($this->GetHighlightRowAtHover()) {
            StringUtils::AddStr($result, 'table-hover', ' ');
        }

        if ($this->GetUseFixedHeader()) {
            StringUtils::AddStr($result, 'fixed-header', ' ');
        }

        return $result;
    }

    public function SetViewMode($value) {
        $this->viewMode = $value;
    }

    public function GetViewMode() {
        return $this->viewMode;
    }

    public function setEnableRunTimeCustomization($value)
    {
        $this->enableRunTimeCustomization = (bool) $value;
    }

    public function getEnableRunTimeCustomization()
    {
        return $this->enableRunTimeCustomization;
    }

    public function SetCardCountInRow($value) {
        if (is_array($value)) {
            $this->cardCountInRow = $value;
        } else {
            $this->cardCountInRow = array(
                'lg' => $value,
                'md' => $value,
                'sm' => $value,
                'xs' => $value,
            );
        }
    }

    public function GetCardCountInRow() {
        return $this->cardCountInRow;
    }

    public function GetAvailableCardCountInRow() {
        return array('1', '2', '3', '4', '6');
    }

    public function getAvailableScreenSizes()
    {
        return array('lg', 'md', 'sm', 'xs');
    }

    private function getCardClasses() {
        $result = array();

        $previousCount = 3;
        foreach ($this->getAvailableScreenSizes() as $size) {
            $count = (isset($this->cardCountInRow[$size]) && ($this->cardCountInRow[$size] > 0))
                ? $this->cardCountInRow[$size]
                : $previousCount;

            $previousCount = $count;

            $result[] = "col-$size-" . ceil(12 / $count);
        }

        return implode(' ', $result);
    }

    /**
     * @return boolean
     */
    public function isTableBordered()
    {
        return $this->tableBordered;
    }

    /**
     * @param boolean $value
     */
    public function setTableBordered($value)
    {
        $this->tableBordered = $value;
    }

    /**
     * @return boolean
     */
    public function isTableCondensed()
    {
        return $this->tableCondensed;
    }

    /**
     * @param boolean $value
     */
    public function setTableCondensed($value)
    {
        $this->tableCondensed = $value;
    }

    /**
     * @param ColumnFilter $columnFilter
     *
     * @return $this
     */
    public function setColumnFilter(ColumnFilter $columnFilter)
    {
        $this->columnFilter = $columnFilter;

        return $this;
    }

    /**
     * @return ColumnFilter
     */
    public function getColumnFilter()
    {
        return $this->columnFilter;
    }

    /**
     * @param FilterBuilder $filterBuilder
     *
     * @return $this
     */
    public function setFilterBuilder(FilterBuilder $filterBuilder)
    {
        $this->filterBuilder = $filterBuilder;

        return $this;
    }

    /**
     * @return FilterBuilder
     */
    public function getFilterBuilder()
    {
        return $this->filterBuilder;
    }

    /**
     * @param QuickFilter $quickFilter
     *
     * @return $this
     */
    public function setQuickFilter(QuickFilter $quickFilter)
    {
        $this->quickFilter = $quickFilter;

        return $this;
    }

    /**
     * @return QuickFilter
     */
    public function getQuickFilter()
    {
        return $this->quickFilter;
    }

    /**
     * @param ViewColumnGroup $viewColumnGroup
     */
    public function setViewColumnGroup(ViewColumnGroup $viewColumnGroup)
    {
        $this->viewColumnGroup = $viewColumnGroup;
    }

    /**
     * @return ViewColumnGroup
     */
    public function getViewColumnGroup()
    {
        $columns = $this->GetViewColumns();
        $columnNames = array();
        foreach ($this->viewColumnGroup->getLeafs() as $leaf) {
            $columnNames[] = $leaf->getName();
        }

        foreach ($columns as $column) {
            if (!in_array($column->getName(), $columnNames)) {
                $this->viewColumnGroup->add($column);
            }
        }

        return $this->viewColumnGroup;
    }

    public function getColumnEditUrl($primaryKeys, $column)
    {
        $linkBuilder = $this->GetPage()->CreateLinkBuilder();
        $linkBuilder->AddParameter('hname', $this->GetPage()->GetGridEditHandler());
        $linkBuilder->AddParameter('column', $column->GetName());
        foreach ($primaryKeys as $key => $value) {
            $linkBuilder->AddParameter("pk$key", $value);
        }


        return $linkBuilder->GetLink();
    }

    /**
     * @param FormLayout $layout
     */
    public function setViewFormLayout(FormLayout $layout)
    {
        $this->viewFormLayout = $layout;
    }

    /**
     * @return FormLayout
     */
    public function getViewFormLayout()
    {
        return $this->fillFormLayout($this->viewFormLayout, $this->GetSingleRecordViewColumns());
    }

    /**
     * @param FormLayout $layout
     */
    public function setInsertFormLayout(FormLayout $layout)
    {
        $this->insertFormLayout = $layout;
    }

    /**
     * @return FormLayout
     */
    public function getInsertFormLayout()
    {
        return $this->fillFormLayout($this->insertFormLayout, $this->GetInsertColumns());
    }

    /**
     * @param FormLayout $layout
     */
    public function setMultiUploadFormLayout(FormLayout $layout)
    {
        $this->multiUploadFormLayout = $layout;
    }

    /**
     * @return FormLayout
     */
    public function getMultiUploadFormLayout()
    {
        return $this->fillFormLayout($this->multiUploadFormLayout, $this->GetMultiUploadColumns());
    }

    /**
     * @param FormLayout $layout
     */
    public function setEditFormLayout(FormLayout $layout)
    {
        $this->editFormLayout = $layout;
    }

    /**
     * @return FormLayout
     */
    public function getEditFormLayout()
    {
        return $this->fillFormLayout($this->editFormLayout, $this->GetEditColumns());
    }

    /**
     * @param FormLayout $layout
     */
    public function setMultiEditFormLayout(FormLayout $layout)
    {
        $this->multiEditFormLayout = $layout;
    }

    /**
     * @return FormLayout
     */
    public function getMultiEditFormLayout()
    {
        return $this->fillFormLayout($this->multiEditFormLayout, $this->GetMultiEditColumns());
    }

    /**
     * @param FormLayout $layout
     */
    public function setInlineInsertFormLayout(FormLayout $layout)
    {
        $this->inlineInsertFormLayout = $layout;
    }

    /**
     * @param FormLayout $layout
     */
    public function setInlineEditFormLayout(FormLayout $layout)
    {
        $this->inlineEditFormLayout = $layout;
    }

    /**
     * @return FormLayout
     */
    public function getInlineInsertFormLayout()
    {
        return $this->fillInlineFormLayout($this->inlineInsertFormLayout, $this->GetInsertColumns());
    }

    /**
     * @return FormLayout
     */
    public function getInlineEditFormLayout()
    {
        return $this->fillInlineFormLayout($this->inlineEditFormLayout, $this->GetEditColumns());
    }

    /**
     * @param FormLayout        $layout
     * @param ColumnInterface[] $columns
     *
     * @return FormLayout
     */
    private function fillFormLayout(FormLayout $layout, $columns)
    {
        $columnNames = $layout->getColumnNames();
        if ($layout->tabsEnabled()) {
            $tab = $layout->addTab('Default');
            $group = $tab->addGroup();
        } else {
            $group = $layout->addGroup();
        }

        foreach ($columns as $column) {
            if (!in_array($column->GetName(), $columnNames)) {
                $group->addRow()->addCol($column);
            }
        }

        return $layout;
    }

    private function fillInlineFormLayout(FormLayout $layout, $columns)
    {
        if ($this->GetViewMode() === ViewMode::CARD) {
            return $this->fillFormLayout($layout, $columns);
        }

        $columnNames = $layout->getColumnNames();

        if (count($columnNames) > 0) {
            return $this->fillFormLayout($layout, $columns);
        }

        $groups = array(
            $layout->addGroup(null, 6),
            $layout->addGroup(null, 6)
        );

        foreach ($columns as $i => $column) {
            $groups[$i%2]->addRow()->addCol($column);
        }

        return $layout;
    }

    /**
     * @param ColumnInterface[] $columns
     *
     * @return array
     */
    static public function getNamedColumns($columns)
    {
        $namedColumns = array();

        foreach ($columns as $column) {
            $namedColumns[$column->getName()] = $column;
        }

        return $namedColumns;
    }

    /**
     * @param bool $isMaster
     *
     * @return $this
     */
    public function setIsMaster($isMaster)
    {
        $this->isMaster = $isMaster;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMaster()
    {
        return $this->isMaster;
    }

    private function getSelectionId()
    {
        return $this->GetId() . '_' . $this->getPage()->getViewId();
    }

    private function getEnableSortDialog() {
        return !$this->isMaster() && $this->allowSorting() && $this->allowSortingByDialog;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setMultiEditAllowed($value) {
        $this->multiEditAllowed = $value;
        return $this;
    }

    /** @return bool */
    public function getMultiEditAllowed() {
        return $this->multiEditAllowed;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setUseModalMultiEdit($value) {
        $this->useModalMultiEdit = $value;
        return $this;
    }

    /** @return bool */
    public function getUseModalMultiEdit() {
        return $this->useModalMultiEdit;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setIncludeAllFieldsForMultiEditByDefault($value) {
        $this->includeAllFieldsForMultiEditByDefault = $value;
        return $this;
    }

    /** @return bool */
    public function getIncludeAllFieldsForMultiEditByDefault() {
        return $this->includeAllFieldsForMultiEditByDefault;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setAllowMultiUpload($value) {
        $this->allowMultiUpload = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getAllowMultiUpload() {
        return $this->allowMultiUpload && !$this->isMaster();
    }

    /** @return bool */
    public function getReloadPageAfterAjaxOperation() {
        return $this->reloadPageAfterAjaxOperation;
    }

    /** @var bool @value */
    public function setReloadPageAfterAjaxOperation($value) {
        $this->reloadPageAfterAjaxOperation = $value;
    }

    public function prepareSelectionFilters(FixedKeysArray $columns) {
        $this->OnGetSelectionFilters->Fire(array(
            $columns,
            &$this->selectionFilters
        ));
    }

    /** @return FilterComponentInterface[] */
    public function getSelectionFilters() {
        return $this->selectionFilters;
    }

    /** @return bool */
    public function getSelectionFilterAllowed() {
        return $this->selectionFilterAllowed;
    }

    /** @var bool @value */
    public function setSelectionFilterAllowed($value) {
        $this->selectionFilterAllowed = $value;
    }

    private function getOperationLinkTarget($operation) {
        if ($operation == OPERATION_PRINT_ONE) {
            return $this->GetPage()->getPrintLinkTarget();
        } elseif ($operation == OPERATION_PDF_EXPORT_RECORD) {
            return $this->GetPage()->getExportToPdfLinkTarget();
        } else {
            return '';
        }
    }
}
