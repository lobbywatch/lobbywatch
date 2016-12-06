<?php

include_once dirname(__FILE__) . '/../../libs/smartylibs/Smarty.class.php';
include_once dirname(__FILE__) . '/../../database_engine/insert_command.php';
include_once dirname(__FILE__) . '/../../database_engine/update_command.php';
include_once dirname(__FILE__) . '/../../database_engine/select_command.php';
include_once dirname(__FILE__) . '/../../database_engine/delete_command.php';
include_once dirname(__FILE__) . '/../captions.php';
include_once dirname(__FILE__) . '/../env_variables.php';
include_once dirname(__FILE__) . '/../charts/chart.php';
include_once dirname(__FILE__) . '/../charts/chart_position.php';
include_once dirname(__FILE__) . '/../grid/grid.php';
include_once dirname(__FILE__) . '/../grid/columns.php';
include_once dirname(__FILE__) . '/../grid/operation_columns.php';
include_once dirname(__FILE__) . '/../grid/edit_columns.php';
include_once dirname(__FILE__) . '/../grid/vertical_grid.php';
include_once dirname(__FILE__) . '/../dataset/dataset.php';
include_once dirname(__FILE__) . '/../dataset/table_dataset.php';
include_once dirname(__FILE__) . '/../dataset/query_dataset.php';
include_once dirname(__FILE__) . '/../renderers/renderer.php';
include_once dirname(__FILE__) . '/../renderers/edit_renderer.php';
include_once dirname(__FILE__) . '/../renderers/list_renderer.php';
include_once dirname(__FILE__) . '/../renderers/view_renderer.php';
include_once dirname(__FILE__) . '/../renderers/print_renderer.php';
include_once dirname(__FILE__) . '/../renderers/insert_renderer.php';
include_once dirname(__FILE__) . '/../renderers/excel_list_renderer.php';
include_once dirname(__FILE__) . '/../renderers/word_list_renderer.php';
include_once dirname(__FILE__) . '/../renderers/xml_list_renderer.php';
include_once dirname(__FILE__) . '/../renderers/csv_list_renderer.php';
include_once dirname(__FILE__) . '/../renderers/pdf_list_renderer.php';
include_once dirname(__FILE__) . '/../renderers/excel_record_renderer.php';
include_once dirname(__FILE__) . '/../renderers/word_record_renderer.php';
include_once dirname(__FILE__) . '/../renderers/xml_record_renderer.php';
include_once dirname(__FILE__) . '/../renderers/csv_record_renderer.php';
include_once dirname(__FILE__) . '/../renderers/pdf_record_renderer.php';
include_once dirname(__FILE__) . '/../renderers/rss_renderer.php';
include_once dirname(__FILE__) . '/../renderers/compare_renderer.php';
include_once dirname(__FILE__) . '/../common.php';
include_once dirname(__FILE__) . '/../dataset_rss_generator.php';
include_once dirname(__FILE__) . '/../error_utils.php';
include_once dirname(__FILE__) . '/../superglobal_wrapper.php';
include_once dirname(__FILE__) . '/../utils/array_utils.php';
include_once dirname(__FILE__) . '/../security/security_info.php';
include_once dirname(__FILE__) . '/../application.php';
include_once dirname(__FILE__) . '/common_page.php';
include_once dirname(__FILE__) . '/page_navigator.php';
include_once dirname(__FILE__) . '/page_list.php';
include_once dirname(__FILE__) . '/page_mode.php';
include_once dirname(__FILE__) . '/page_part.php';
include_once dirname(__FILE__) . '/page_type.php';
include_once dirname(__FILE__) . '/navigation.php';

define('OPERATION_HTTPHANDLER_NAME_PARAMNAME', 'hname');
define('OPERATION_PARAMNAME', 'operation');

define('OPERATION_VIEW', 'view');
define('OPERATION_EDIT', 'edit');
define('OPERATION_INSERT', 'insert');
define('OPERATION_COPY', 'copy');
define('OPERATION_DELETE', 'delete');
define('OPERATION_VIEWALL', 'viewall');
define('OPERATION_RETURN', 'return');
define('OPERATION_COMMIT', 'commit');
define('OPERATION_COMMIT_INSERT', 'commit_new');
define('OPERATION_COMMIT_DELETE', 'commit_delete');
define('OPERATION_PRINT_ALL', 'printall');
define('OPERATION_PRINT_PAGE', 'printpage');
define('OPERATION_PRINT_ONE', 'printrec');
define('OPERATION_DELETE_SELECTED', 'delsel');

define('OPERATION_EXCEL_EXPORT', 'eexcel');
define('OPERATION_WORD_EXPORT', 'eword');
define('OPERATION_XML_EXPORT', 'exml');
define('OPERATION_CSV_EXPORT', 'ecsv');
define('OPERATION_PDF_EXPORT', 'epdf');

define('OPERATION_EXCEL_EXPORT_RECORD', 'eexcel_record');
define('OPERATION_WORD_EXPORT_RECORD', 'eword_record');
define('OPERATION_XML_EXPORT_RECORD', 'exml_record');
define('OPERATION_CSV_EXPORT_RECORD', 'ecsv_record');
define('OPERATION_PDF_EXPORT_RECORD', 'epdf_record');

define('OPERATION_ADVANCED_SEARCH', 'advsrch');

define('OPERATION_RSS', 'rss');

define('OPERATION_HTTPHANDLER_REQUEST', 'httphandler');
define('OPERATION_COMPARE', 'compare');


class Modal {
    const SIZE_SM = 'modal-sm';
    const SIZE_MD = 'modal-md';
    const SIZE_LG = 'modal-lg';
}

function GetOperation()
{
    return GetApplication()->GetOperation();
}

abstract class Page extends CommonPage implements IVariableContainer
{
    private $pageFileName;
    /** @var Renderer */
    protected $renderer;
    private $httpHandlerName;
    /** @var IDataSourceSecurityInfo */
    private $securityInfo;

    /**
     * @var IRecordPermissions
     */
    private $recordPermission;

    private $message;
    private $errorMessage;
    private $columnVariableContainer;

    #region Page parts
    /** @var Dataset */
    protected $dataset;

    /** @var Grid */
    protected $grid;

    /** @var AbstractPageNavigator */
    private $pageNavigator;
    /** @var DatasetRssGenerator */
    private $rssGenerator;

    private $pageNavigatorStack;
    #endregion

    #region Option fields

    private $menuLabel;
    private $editFormTitle;
    private $viewFormTitle;
    private $insertFormTitle;
    private $showUserAuthBar = false;
    private $showTopPageNavigator = true;
    private $showBottomPageNavigator = false;
    private $showPageList;
    private $showGrid = true;
    private $hidePageListByDefault;
    private $showNavigation;

    private $printListAvailable = true;
    private $printListRecordAvailable = false;
    private $printOneRecordAvailable = true;

    private $exportListAvailable = array('excel', 'pdf', 'csv', 'xml', 'word');
    private $exportListRecordAvailable = array();
    private $exportOneRecordAvailable = array('excel', 'pdf', 'csv', 'xml', 'word');

    private $visualEffectsEnabled;
    private $detailedDescription;
    private $description;

    private $modalFormSize = Modal::SIZE_MD;
    private $modalViewSize = Modal::SIZE_MD;
    #endregion
    #
    private $charts;
    private $addNewChoices = array();

    #region Events
    public $BeforePageRender;
    public $OnCustomHTMLHeader;
    public $OnPageLoadedClientScript;
    public $OnGetCustomTemplate;
    public $OnPrepareChart;
    public $OnGetCustomUploadFilename;
    #endregion

    #region IVariableContainer implementation
    private $variableFuncs = array(
        'PAGE_SHORT_CAPTION'    => 'return $page->GetMenuLabel();',
        'PAGE_CAPTION'          => 'return $page->GetTitle();',
        'PAGE_CSV_EXPORT_LINK'  => 'return $page->GetExportToCsvLink();',
        'PAGE_XLS_EXPORT_LINK'  => 'return $page->GetExportToExcelLink();',
        'PAGE_PDF_EXPORT_LINK'  => 'return $page->GetExportToPdfLink();',
        'PAGE_XML_EXPORT_LINK'  => 'return $page->GetExportToXmlLink();',
        'PAGE_WORD_EXPORT_LINK' => 'return $page->GetExportToWordLink();'
        );

    /**
     * @param string $id
     * @param string $pageFileName
     * @param DataSourceSecurityInfo $dataSourceSecurityInfo
     * @param string $contentEncoding
     */
    public function __construct($id, $pageFileName, $dataSourceSecurityInfo = null, $contentEncoding=null)
    {
        parent::__construct($id, $contentEncoding);
        $this->BeforePageRender = new Event();
        $this->OnCustomHTMLHeader = new Event();
        $this->OnGetCustomTemplate = new Event();
        $this->OnPrepareChart = new Event();
        $this->OnGetCustomExportOptions = new Event();
        $this->OnGetCustomUploadFilename = new Event();

        $this->securityInfo = $dataSourceSecurityInfo;
        $this->pageFileName = $pageFileName;
        $this->showPageList = true;
        $this->showNavigation = true;
        $this->visualEffectsEnabled = true;
        $this->rssGenerator = null;
        $this->detailedDescription = null;

        $this->charts = array(
            ChartPosition::BEFORE_GRID => array(),
            ChartPosition::AFTER_GRID => array(),
        );

        $this->recordPermission = null;
        $this->message = null;
        $this->pageNavigatorStack = array();

        $this->BeforeCreate();

        $this->CreateComponents();
        $this->setupCharts();
        $this->setupGridLayouts();
        $this->setupGridColumnGroup($this->grid);

        $this->Prepare();
    }

    #region ViewData

    public function GetSeparatedEditViewData() {
        return $this->GetCommonViewData()
            ->setEntryPoint('form');
    }

    public function GetSeparatedInsertViewData() {
        return $this->GetCommonViewData()
            ->setEntryPoint('form');
    }

    public function GetSingleRecordViewData() {
        return $this->GetCommonViewData()
            ->setEntryPoint('view');
    }

    public function GetListViewData() {
        return $this->GetCommonViewData()
            ->setEntryPoint('list');
    }

    public function getCompareViewData()
    {
        return $this->GetCommonViewData()
            ->setEntryPoint('compare');
    }

    #endregion

    public function FillVariablesValues(&$values)
    {
        $values = array();
        foreach($this->variableFuncs as $name => $code)
        {
            $function = create_function('$page', $code);
            $values[$name] = $function($this);
        }
    }

    public function GetAuthenticationViewData() {
        return array(
            'Enabled' => $this->GetShowUserAuthBar(),
            'LoggedIn' => $this->IsCurrentUserLoggedIn(),
            'CurrentUser' => array(
                'Name' => $this->GetCurrentUserName(),
                'Id' => $this->GetCurrentUserId(),
            ),
            'CanChangeOwnPassword' => GetApplication()->GetUserManager()->CanChangeUserPassword() &&
                    GetApplication()->CanUserChangeOwnPassword(),
            'isAdminPanelVisible' => GetApplication()->HasAdminPanelForCurrentUser(),
        );
    }

    public function FillAvailableVariables(&$variables)
    {
        return array_keys($this->variableFuncs);
    }

    protected function ApplyCommonColumnEditProperties(CustomEditColumn $editColumn)
    {
    }

    #endregion

    /**
     * @return IVariableContainer
     */
    public function GetColumnVariableContainer()
    {
        if (!isset($this->columnVariableContainer))
            $this->columnVariableContainer = new CompositeVariableContainer(
                $this, GetApplication(),
                new ServerVariablesContainer(),
                new SystemFunctionsVariablesContainer()
                );
        return $this->columnVariableContainer;
    }

    #region RSS

    public function HasRss()
    {
        $rssGenerator = $this->GetRssGenerator();
        return isset($rssGenerator) && (get_class($rssGenerator) != 'NullRssGenerator');
    }

    public function GetRssLink()
    {
        if ($this->HasRss())
        {
            $linkBuilder = $this->CreateLinkBuilder();
            $linkBuilder->AddParameter(OPERATION_PARAMNAME, 'rss');
            return $linkBuilder->GetLink();
        }
        return null;
    }

    public function GetRssGenerator()
    {
        return $this->rssGenerator;
    }

    protected function CreateRssGenerator()
    {
        return null;
    }

    #endregion

    public function GetEnvVar($name)
    {
        $vars = array();
        $this->GetColumnVariableContainer()->FillVariablesValues($vars);
        return $vars[$name];
    }

    public function GetCustomPageHeader()
    {
        $result = '';
        $this->OnCustomHTMLHeader->Fire(array(&$this, &$result));
        return $result;
    }

    /**
     * @return Grid
     */
    protected abstract function CreateGrid();

    protected function CreatePageNavigator()
    {
        return null;
    }

    protected function AddPageNavigatorToStack($pageNavigator)
    {
        $this->pageNavigatorStack[] = $pageNavigator;
    }

    protected function FillPageNavigatorStack()
    { }

    protected function DoBeforeCreate()
    { }

    protected function DoPrepare()
    {}

    protected function CreateComponents()
    {
        $this->grid = $this->CreateGrid();
        $this->attachGridEventHandlers($this->grid);
        $this->attachEventHandlers();
        $this->setClientSideEvents($this->grid);
        $this->pageNavigator = $this->CreatePageNavigator();
        $this->httpHandlerName = null;
        $this->FillPageNavigatorStack();
        $this->RegisterHandlers();
    }

    protected function attachGridEventHandlers(Grid $grid) {
        $grid->OnCustomRenderColumn->AddListener('Grid_OnCustomRenderColumnHandler', $this);
        $grid->OnCustomRenderPrintColumn->AddListener('Grid_OnCustomRenderPrintColumnHandler', $this);
        $grid->OnCustomRenderExportColumn->AddListener('Grid_OnCustomRenderExportColumnHandler', $this);
        $grid->OnCustomDrawRow->AddListener('Grid_OnCustomDrawRowHandler', $this);
        $grid->OnExtendedCustomDrawRow->AddListener('Grid_OnExtendedCustomDrawRowHandler', $this);
        if (!$grid->isMaster()) {
            $grid->OnCustomRenderTotal->AddListener('Grid_OnCustomRenderTotalHandler', $this);
            $grid->OnCustomCompareColumn->AddListener('Grid_OnCustomCompareColumnHandler', $this);
            $grid->BeforeInsertRecord->AddListener('Grid_OnBeforeInsertRecordHandler', $this);
            $grid->BeforeUpdateRecord->AddListener('Grid_OnBeforeUpdateRecordHandler', $this);
            $grid->BeforeDeleteRecord->AddListener('Grid_OnBeforeDeleteRecordHandler', $this);
            $grid->AfterInsertRecord->AddListener('Grid_OnAfterInsertRecordHandler', $this);
            $grid->AfterUpdateRecord->AddListener('Grid_OnAfterUpdateRecordHandler', $this);
            $grid->AfterDeleteRecord->AddListener('Grid_OnAfterDeleteRecordHandler', $this);
        }
    }

    protected function attachEventHandlers() {
        $this->OnCustomHTMLHeader->AddListener('OnCustomHTMLHeaderHandler', $this);
        $this->OnGetCustomTemplate->AddListener('OnGetCustomTemplateHandler', $this);
        $this->OnGetCustomExportOptions->AddListener('OnGetCustomExportOptionsHandler', $this);
        $this->OnGetCustomUploadFilename->AddListener('OnGetCustomUploadFilenameHandler', $this);
        $this->OnPrepareChart->AddListener('OnPrepareChartHandler', $this);
    }

    public function Grid_OnCustomRenderColumnHandler($fieldName, $fieldData, $rowData, &$customText, &$handled) {
        $this->doCustomRenderColumn($fieldName, $fieldData, $rowData, $customText, $handled);
    }

    public function Grid_OnCustomRenderPrintColumnHandler($fieldName, $fieldData, $rowData, &$customText, &$handled) {
        $this->doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, $customText, $handled);
    }

    public function Grid_OnCustomRenderExportColumnHandler($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled) {
        $this->doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, $customText, $handled);
    }

    public function Grid_OnCustomDrawRowHandler($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr) {
        $this->doCustomDrawRow($rowData, $cellFontColor, $cellFontSize, $cellBgColor, $cellItalicAttr, $cellBoldAttr);
    }

    public function Grid_OnExtendedCustomDrawRowHandler($rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses) {
        $this->doExtendedCustomDrawRow($rowData, $rowCellStyles, $rowStyles, $rowClasses, $cellClasses);
    }

    public function Grid_OnCustomRenderTotalHandler($totalValue, $aggregate, $columnName, &$customText, &$handled) {
        $this->doCustomRenderTotal($totalValue, $aggregate, $columnName, $customText, $handled);
    }

    public function Grid_OnCustomCompareColumnHandler($columnName, $valueA, $valueB, &$result) {
        $this->doCustomCompareColumn($columnName, $valueA, $valueB, $result);
    }

    public function Grid_OnBeforeInsertRecordHandler($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName) {
        $this->doBeforeInsertRecord($page, $rowData, $cancel, $message, $messageDisplayTime, $tableName);
    }

    public function Grid_OnBeforeUpdateRecordHandler($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName) {
        $this->doBeforeUpdateRecord($page, $rowData, $cancel, $message, $messageDisplayTime, $tableName);
    }

    public function Grid_OnBeforeDeleteRecordHandler($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName) {
        $this->doBeforeDeleteRecord($page, $rowData, $cancel, $message, $messageDisplayTime, $tableName);
    }

    public function Grid_OnAfterInsertRecordHandler($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime) {
        $this->doAfterInsertRecord($page, $rowData, $tableName, $success, $message, $messageDisplayTime);
    }

    public function Grid_OnAfterUpdateRecordHandler($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime) {
        $this->doAfterUpdateRecord($page, $rowData, $tableName, $success, $message, $messageDisplayTime);
    }

    public function Grid_OnAfterDeleteRecordHandler($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime) {
        $this->doAfterDeleteRecord($page, $rowData, $tableName, $success, $message, $messageDisplayTime);
    }

    public function OnCustomHTMLHeaderHandler($page, &$customHtmlHeaderText) {
        $this->doCustomHTMLHeader($page, $customHtmlHeaderText);
    }

    public function OnGetCustomTemplateHandler($type, $part, $mode, &$result, &$params) {
        $this->doGetCustomTemplate($type, $part, $mode, $result, $params);
    }

    public function OnGetCustomExportOptionsHandler(Page $page, $exportType, $rowData, &$options) {
        $this->doGetCustomExportOptions($page, $exportType, $rowData, $options);
    }

    public function OnGetCustomUploadFilenameHandler($fieldName, &$result, &$handled, $originalFileName, $originalFileExtension, $fileSize) {
        $this->doGetCustomUploadFileName(
            $fieldName,
            $this->dataset->getCurrentFieldValues(),
            $result,
            $handled,
            $originalFileName,
            $originalFileExtension,
            $fileSize
        );
    }

    public function OnPrepareChartHandler(Chart $chart) {
        $this->doPrepareChart($chart);
    }

    protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled) {
    }

    protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled) {
    }

    protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled) {
    }

    protected function doCustomDrawRow($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr) {
    }

    protected function doExtendedCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses) {
    }

    protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled) {
    }

    protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result) {
    }

    protected function doBeforeInsertRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName) {
    }

    protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName) {
    }

    protected function doBeforeDeleteRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName) {
    }

    protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime) {
    }

    protected function doAfterUpdateRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime) {
    }

    protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime) {
    }

    protected function doCustomHTMLHeader($page, &$customHtmlHeaderText) {
    }

    protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params) {
    }

    protected function doGetCustomExportOptions(Page $page, $exportType, $rowData, &$options) {
    }

    protected function doGetCustomUploadFileName($fieldName, $rowData, &$result, &$handled, $originalFileName, $originalFileExtension, $fileSize) {
    }

    protected function doPrepareChart(Chart $chart) {
    }

    protected function setClientSideEvents(Grid $grid) {
    }

    public function GetHttpHandlerName() {
        return $this->httpHandlerName;
    }

    public function RegisterHandlers()
    {
        $handler = new GridEditHandler($this->GetGridEditHandler(), new VerticalGrid($this->GetGrid(), OPERATION_EDIT));
        GetApplication()->RegisterHTTPHandler($handler);

        $handler = new GridEditHandler($this->GetGridInsertHandler(), new VerticalGrid($this->GetGrid(), OPERATION_INSERT));
        GetApplication()->RegisterHTTPHandler($handler);

        if ($this->GetEnableModalSingleRecordView()) {
            $handler = new ModalGridViewHandler($this->GetModalGridViewHandler(), new RecordCardView($this->GetGrid()));
            GetApplication()->RegisterHTTPHandler($handler);
        }

        if ($this->GetEnableModalGridCopy()) {
            $handler = new GridEditHandler($this->GetModalGridCopyHandler(), new VerticalGrid($this->GetGrid(), OPERATION_COPY));
            GetApplication()->RegisterHTTPHandler($handler);
        }

        if ($this->GetEnableModalGridDelete()) {
            $handler = new ModalDeleteHandler($this->GetModalGridDeleteHandler(), $this->GetGrid());
            GetApplication()->RegisterHTTPHandler($handler);
        }

        $this->doRegisterHandlers();
    }

    protected function doRegisterHandlers() {
    }

    public function GetGridEditHandler()
    {
        return get_class($this) . '_form_edit';
    }

    public function GetGridInsertHandler()
    {
        return get_class($this) . '_form_insert';
    }

    public function GetModalGridViewHandler()
    {
        return get_class($this) . '_modal_view';
    }

    public function GetModalGridCopyHandler()
    {
        return get_class($this) . '_modal_copy';
    }

    public function GetModalGridDeleteHandler()
    {
        return get_class($this) . '_modal_delete';
    }

    public function GetEnableModalSingleRecordView()
    {
        return false;
    }

    public function GetEnableModalGridInsert()
    {
        return false;
    }

    public function GetEnableInlineGridInsert()
    {
        return false;
    }

    public function GetEnableModalGridEdit()
    {
        return false;
    }

    public function GetEnableModalGridCopy()
    {
        return false;
    }

    protected function GetEnableModalGridDelete()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function getShowNavigation()
    {
        return $this->showNavigation;
    }

    /**
     * @param bool $value
     */
    public function setShowNavigation($value) {
        $this->showNavigation = $value;
    }

    /**
     * @param bool $showGrid
     */
    public function setShowGrid($showGrid)
    {
        $this->showGrid = $showGrid;
    }

    /**
     * @return bool
     */
    public function getShowGrid()
    {
        return $this->showGrid;
    }

    /**
     * @param Grid $grid
     */
    public function setupFilters(Grid $grid)
    {
        $filtersColumns = array();
        foreach ($this->getFiltersColumns() as $column) {
            $filtersColumns[$column->getFieldName()] = $column;
        }

        $columns = new FixedKeysArray($filtersColumns);

        $this->setupQuickFilter($grid->getQuickFilter(), $columns);

        $this->setupFilterBuilder($grid->getFilterBuilder(), $columns);
        $this->OnPrepareFilterBuilder($grid->getFilterBuilder(), $columns);

        $columnFilter = $grid->getColumnFilter()->setPossibleColumns($columns);
        $this->setupColumnFilter($columnFilter);
        $this->OnPrepareColumnFilter($columnFilter);
    }

    protected function getFiltersColumns()
    {
        return array();
    }

    protected function setupColumnFilter(ColumnFilter $columnFilter)
    {
    }

    protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
    {
    }

    protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
    {
    }

    protected function OnPrepareColumnFilter(ColumnFilter $columnFilter)
    {
    }

    protected function OnPrepareFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
    {
    }

    protected function setupCharts()
    {
    }

    private function setupGridLayouts()
    {
        $this->grid->SetViewFormLayout($this->callFormLayoutEvent(
            'view',
            $this->grid->GetSingleRecordViewColumns(),
            new FormLayout()
        ));

        $this->grid->SetInsertFormLayout($this->callFormLayoutEvent(
            'insert',
            $this->grid->GetInsertColumns(),
            new FormLayout()
        ));

        $this->grid->SetEditFormLayout($this->callFormLayoutEvent(
            'edit',
            $this->grid->GetEditColumns(),
            new FormLayout()
        ));

        $this->grid->SetInlineInsertFormLayout($this->callFormLayoutEvent(
            'inline_insert',
            $this->grid->GetInsertColumns(),
            new FormLayout(FormLayoutMode::VERTICAL)
        ));

        $this->grid->SetInlineEditFormLayout($this->callFormLayoutEvent(
            'inline_edit',
            $this->grid->GetEditColumns(),
            new FormLayout(FormLayoutMode::VERTICAL)
        ));
    }

    private function callFormLayoutEvent($mode, $columns, FormLayout $layout)
    {
        $this->OnGetCustomFormLayout(
            $mode,
            new FixedKeysArray(Grid::getNamedColumns($columns)),
            $layout
        );

        return $layout;
    }

    protected function OnGetCustomFormLayout($mode, FixedKeysArray $columns, FormLayout $layout)
    {
    }

    protected function setupGridColumnGroup(Grid $grid)
    {
        $columnGroup = new ViewColumnGroup(null, array());
        $columns = Grid::getNamedColumns($grid->getViewColumns());
        $this->OnGetCustomColumnGroup(new FixedKeysArray($columns), $columnGroup);
        $grid->setViewColumnGroup($columnGroup);
    }

    protected function OnGetCustomColumnGroup(FixedKeysArray $columns, ViewColumnGroup $columnGroup)
    {
    }

    protected function addChart(Chart $chart, $index = 0, $position = ChartPosition::BEFORE_GRID, $cols = 6)
    {
        $this->charts[$position][$index] = array(
            'chart' => $chart,
            'cols' => $cols,
        );
    }

    public function getCharts()
    {
        return $this->charts;
    }

    public function hasCharts()
    {
        return 0 < count($this->charts[ChartPosition::BEFORE_GRID])
            || 0 < count($this->charts[ChartPosition::AFTER_GRID]);
    }

    public function GetCustomExportOptions($exportType, $rowData, &$options)
    {
        $this->OnGetCustomExportOptions->Fire(array($this, $exportType, $rowData, &$options));
    }

    /**
     * @return null|PageNavigator
     */
    public function GetPaginationControl()
    {
        $pageNavigators = $this->GetPageNavigator();
        if (SMReflection::ClassName($pageNavigators) == 'CompositePageNavigator')
        {
            /** @var CompositePageNavigator $pageNavigators */
            foreach($pageNavigators->GetPageNavigators() as $pageNavigator)
                if (SMReflection::ClassName($pageNavigator) == 'PageNavigator')
                    return $pageNavigator;

        }
        return null;
    }

    public function GetExportListButtonsViewData()
    {
        return $this->GetExportButtonsViewData(
            $this->getExportListAvailable(),
            $this->getPrintListAvailable()
        );
    }

    public function getExportOneRecordButtonsViewData($primaryKeyValues)
    {
        return $this->GetExportButtonsViewData(
            $this->getExportOneRecordAvailable(),
            $this->getPrintOneRecordAvailable(),
            $primaryKeyValues
        );
    }

    private function GetExportButtonsViewData($exportsAvailable, $printAvailable, $primaryKeyValues = array())
    {
        $result = array();

        foreach ($exportsAvailable as $export) {
            $result[$export] = array(
                'Caption' =>    $this->GetLocalizerCaptions()->GetMessageString('ExportTo' . ucfirst($export)),
                'IconClass' => 'icon-export-' . $export,
                'Href' =>       $this->getExportLink($export, $primaryKeyValues),
            );
        }

        if ($printAvailable) {
            $result['print_page'] = array(
                'Caption' =>   $this->GetLocalizerCaptions()->GetMessageString('PrintCurrentPage'),
                'IconClass' => 'icon-print-page',
                'Href' =>      $this->GetPrintCurrentPageLink()
            );

            $result['print_all'] = array(
                'Caption' =>   $this->GetLocalizerCaptions()->GetMessageString('PrintAllPages'),
                'IconClass' => 'icon-print-page',
                'Href' =>      $this->GetPrintAllLink(),
                'BeginNewGroup' => true
            );
        }
        return $result;
    }

    private function GetPageList()
    {
        return PageList::createForPage($this);
    }

    public function GetReadyPageList() {
        $pageList = $this->GetPageList();

        if (!$pageList) {
            return null;
        }

        $pageList->AddRssLinkForCurrentPage($this->GetRssLink());

        return $pageList;
    }

    public function GetForeignKeyFields()
    {
        return array();
    }

    public function BeforeCreate()
    {
        try
        {
            $this->DoBeforeCreate();
            $this->rssGenerator = $this->CreateRssGenerator();
            // throw new Exception('abc');
        }
        catch(Exception $e)
        {
            $message = $this->GetLocalizerCaptions()->GetMessageString('GuestAccessDenied');
            ShowSecurityErrorPage($this, $message);
            die();
        }
    }

    public function Prepare()
    {
        if ($this->GetSecurityInfo()->HasViewGrant()) {
            $this->addExportOperationsColumns();
            $this->addPrintOperationsColumns();
        }

        $this->DoPrepare();
    }

    private function addExportOperationsColumns()
    {
        $actions = $this->getGrid()->getActions();
        foreach ($this->getExportListRecordAvailable() as $export) {
            $operation = new LinkOperation(
                $this->GetLocalizerCaptions()->GetMessageString('ExportTo' . ucfirst($export)),
                constant('OPERATION_'.strtoupper($export).'_EXPORT_RECORD'),
                $this->dataset,
                $this->grid
            );

            $operation->setUseImage(true);
            $actions->addOperation($operation);
        }
    }

    private function addPrintOperationsColumns()
    {
        if (!$this->getPrintListRecordAvailable()) {
            return;
        }

        $actions = $this->grid->getActions();
        $operation = new LinkOperation(
            $this->GetLocalizerCaptions()->GetMessageString('PrintOneRecord'),
            OPERATION_PRINT_ONE,
            $this->dataset,
            $this->grid
        );

        $operation->setUseImage(true);
        $actions->addOperation($operation);
    }

    public function GetConnection()
    {
        $this->dataset->Connect();
        return $this->dataset->GetConnection();
    }

    public function PrepareTextForSQL($text)
    {
        return ConvertTextToEncoding($text, GetAnsiEncoding(), $this->GetContentEncoding());
    }

    public function SetErrorMessage($value)
    { $this->errorMessage = $value; }
    public function GetErrorMessage()
    { return $this->errorMessage; }

    public function SetMessage($value)
    { $this->message = $value; }
    public function GetMessage()
    { return $this->RenderText($this->message); }

    #region Options

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function GetShowUserAuthBar()
    {
        return $this->showUserAuthBar;
    }

    public function SetShowUserAuthBar($value)
    {
        $this->showUserAuthBar = $value;
    }

    public function GetMenuLabel()
    {
        return $this->RenderText($this->menuLabel);
    }

    public function SetMenuLabel($value)
    {
        $this->menuLabel = $value;
    }

    public function GetEditFormTitle()
    {
        return $this->editFormTitle;
    }

    public function SetEditFormTitle($editFormTitle)
    {
        $this->editFormTitle = $editFormTitle;
    }

    public function GetInsertFormTitle()
    {
        return $this->insertFormTitle;
    }

    public function SetInsertFormTitle($insertFormTitle)
    {
        $this->insertFormTitle = $insertFormTitle;
    }

    public function GetViewFormTitle()
    {
        return $this->viewFormTitle;
    }

    public function SetViewFormTitle($viewFormTitle)
    {
        $this->viewFormTitle = $viewFormTitle;
    }

    function GetShowTopPageNavigator()
    { return $this->showTopPageNavigator; }
    function SetShowTopPageNavigator($value)
    { $this->showTopPageNavigator = $value; }
    function GetShowBottomPageNavigator()
    { return $this->showBottomPageNavigator; }
    function SetShowBottomPageNavigator($value)
    { $this->showBottomPageNavigator = $value; }
    function GetShowPageList()
    { return $this->showPageList; }
    function GetHidePageListByDefault()
    { return $this->hidePageListByDefault; }
    function SetShowPageList($value)
    { $this->showPageList = $value; }
    function SetHidePageListByDefault($value)
    { $this->hidePageListByDefault = $value; }

    function SetVisualEffectsEnabled($value)
    { $this->visualEffectsEnabled = $value; }

    public function setExportListAvailable(array $exportListAvailable)
    {
        $this->exportListAvailable = $exportListAvailable;
    }

    public function getExportListAvailable()
    {
        return $this->exportListAvailable;
    }

    public function setExportListRecordAvailable(array $exportListRecordAvailable)
    {
        $this->exportListRecordAvailable = $exportListRecordAvailable;
    }

    public function getExportListRecordAvailable()
    {
        return $this->exportListRecordAvailable;
    }

    public function setExportOneRecordAvailable(array $exportOneRecordAvailable)
    {
        $this->exportOneRecordAvailable = $exportOneRecordAvailable;
    }

    public function getExportOneRecordAvailable()
    {
        return $this->exportOneRecordAvailable;
    }

    public function getPrintListAvailable()
    {
        return $this->printListAvailable;
    }

    public function setPrintListAvailable($printListAvailable)
    {
        $this->printListAvailable = $printListAvailable;
    }

    public function getPrintListRecordAvailable()
    {
        return $this->printListRecordAvailable;
    }

    public function setPrintListRecordAvailable($printListRecordAvailable)
    {
        $this->printListRecordAvailable = $printListRecordAvailable;
    }

    public function setPrintOneRecordAvailable($printOneRecordAvailable)
    {
        $this->printOneRecordAvailable = $printOneRecordAvailable;
    }

    public function getPrintOneRecordAvailable()
    {
        return $this->printOneRecordAvailable;
    }

    #endregion

    function IsCurrentUserLoggedIn()
    {
        return GetApplication()->IsCurrentUserLoggedIn();
    }

    function GetCurrentUserId() {
        return GetApplication()->GetCurrentUserId();
    }

    function GetCurrentUserName()
    {
        return GetApplication()->GetCurrentUser();
    }

    public function GetSecurityInfo()
    { return $this->securityInfo; }

    /**
     * @return IRecordPermissions|null
     */
    public function GetRecordPermission()
    { return $this->recordPermission; }

    public function SetRecordPermission(IRecordPermissions $value = null)
    { $this->recordPermission = $value; }

    function RaiseSecurityError($condition, $operation)
    {
        if ($condition)
        {
            if ($operation === OPERATION_EDIT)
                $message = $this->GetLocalizerCaptions()->GetMessageString('EditOperationNotPermitted');
            elseif ($operation === OPERATION_VIEW)
                $message = $this->GetLocalizerCaptions()->GetMessageString('ViewOperationNotPermitted');
            elseif ($operation === OPERATION_DELETE)
                $message = $this->GetLocalizerCaptions()->GetMessageString('DeleteOperationNotPermitted');
            elseif ($operation === OPERATION_INSERT)
                $message = $this->GetLocalizerCaptions()->GetMessageString('InsertOperationNotPermitted');
            else
                $message = $this->GetLocalizerCaptions()->GetMessageString('OperationNotPermitted');
            ShowSecurityErrorPage($this, $message);
            exit;
        }
    }

    function CheckOperationPermitted()
    {
        $operation = GetOperation();
        if ($this->securityInfo->AdminGrant())
            return true;
        switch ($operation)
        {
            case OPERATION_EDIT:
                $this->RaiseSecurityError(!$this->securityInfo->HasEditGrant(), OPERATION_EDIT);
                break;
            case OPERATION_VIEW:
            case OPERATION_PRINT_ONE:
            case OPERATION_PRINT_ALL:
            case OPERATION_PRINT_PAGE:
            case OPERATION_EXCEL_EXPORT:
            case OPERATION_WORD_EXPORT:
            case OPERATION_XML_EXPORT:
            case OPERATION_CSV_EXPORT:
            case OPERATION_PDF_EXPORT:
                $this->RaiseSecurityError(!$this->securityInfo->HasViewGrant(), OPERATION_VIEW);
                break;
            case OPERATION_DELETE:
            case OPERATION_DELETE_SELECTED:
                $this->RaiseSecurityError(!$this->securityInfo->HasDeleteGrant(), OPERATION_DELETE);
                break;
            case OPERATION_INSERT:
            case OPERATION_COPY:
                $this->RaiseSecurityError(!$this->securityInfo->HasAddGrant(), OPERATION_INSERT);
                break;
            default:
                $this->RaiseSecurityError(!$this->securityInfo->HasViewGrant(), OPERATION_VIEW);
                break;
        }
        return true;
    }

    function SelectRenderer()
    {
        switch (GetOperation())
        {
            case OPERATION_EDIT:
                $this->renderer = new EditRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_VIEW:
                $this->renderer = new ViewRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_PRINT_ONE:
                $this->renderer = new PrintOneRecordRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_DELETE:
                $this->renderer = new DeleteRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_INSERT:
                $this->renderer = new InsertRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_COPY:
                $this->renderer = new InsertRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_PRINT_ALL:
                $this->renderer = new PrintRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_PRINT_PAGE:
                $this->renderer = new PrintRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_EXCEL_EXPORT:
                $this->renderer = new ExcelListRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_EXCEL_EXPORT_RECORD:
                $this->renderer = new ExcelRecordRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_WORD_EXPORT:
                $this->renderer = new WordListRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_WORD_EXPORT_RECORD:
                $this->renderer = new WordRecordRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_XML_EXPORT:
                $this->renderer = new XmlListRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_XML_EXPORT_RECORD:
                $this->renderer = new XmlRecordRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_CSV_EXPORT:
                $this->renderer = new CsvListRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_CSV_EXPORT_RECORD:
                $this->renderer = new CsvRecordRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_PDF_EXPORT:
                $this->renderer = new PdfListRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_PDF_EXPORT_RECORD:
                $this->renderer = new PdfRecordRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_DELETE_SELECTED:
                $this->renderer = new ViewAllRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_RSS:
                $this->renderer = new RssRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_COMPARE:
                $this->renderer = new CompareRenderer($this->GetLocalizerCaptions());
                break;
            default:
                $this->renderer = new ViewAllRenderer($this->GetLocalizerCaptions());
                break;
        }
    }

    function isFilterConditionRequired()
    {
        return false;
    }

    function DoProcessMessages()
    {
        if (GetOperation() != OPERATION_RSS) {
            $this->grid->SetState(GetOperation());
            $this->grid->ProcessMessages();
        }
    }

    function ProcessMessages()
    {
        if (GetApplication()->GetSuperGlobals()->IsGetValueSet('clear_options')) {
            $this->grid->clearFilters();
            header('Location: ' . $this->CreateLinkBuilder()->getLink());
            exit;
        }

        try {
            $this->DoProcessMessages();
        } catch(Exception $e) {
            $this->DisplayErrorPage($e);
            die();
        }
    }

    function BeginRender()
    {
        $this->BeforeBeginRenderPage();
        $this->ProcessMessages();
    }

    function EndRender()
    {
        try
        {
            $this->CheckOperationPermitted();
            $this->SelectRenderer();
            $this->BeforeRenderPageRender();
            echo $this->renderer->Render($this);
        }
        catch(Exception $e)
        {
            $this->DisplayErrorPage($e);
            die();
        }
    }

    function BeforeBeginRenderPage()
    { }

    function BeforeRenderPageRender()
    { }

    function DisplayErrorPage($exception)
    {
        $errorStateRenderer = new ErrorStateRenderer($this->GetLocalizerCaptions(), $exception);
        echo $errorStateRenderer->Render($this);
    }

    /**
     * @param Renderer $visitor
     */
    function Accept($visitor)
    {
        $visitor->RenderPage($this);
    }

    #region Page parts

    /**
     * @return Dataset
     */
    function GetDataset()
    {
        return $this->dataset;
    }

    /**
     * @return Grid
     */
    function GetGrid()
    {
        return $this->grid;
    }

    /**
     * @return PageNavigator
     */
    function GetPageNavigator()
    {
        return $this->pageNavigator;
    }

    function GetPageNavigatorStack()
    {
        return $this->pageNavigatorStack;
    }

    #endregion

    function GetPageFileName()
    {
        return $this->pageFileName;
    }

    function SetHttpHandlerName($name)
    {
        $this->httpHandlerName = $name;
    }

    public function GetHiddenGetParameters()
    {
        $result = array();
        if (isset($this->httpHandlerName))
            $result['hname'] = $this->httpHandlerName;
        return $result;
    }

    public function CreateLinkBuilder()
    {
        $result = new LinkBuilder($this->GetPageFileName());

        if (isset($this->httpHandlerName)) {
            $result->AddParameter('hname', $this->httpHandlerName);
        }

        return $result;
    }

    public function getLink()
    {
        $result = new LinkBuilder($this->GetPageFileName());

        return $result->getLink();
    }

    #region Export links

    public function GetHandlerLink($handlerName)
    {
        $linkBuilder = $this->CreateLinkBuilder();
        $linkBuilder->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $handlerName);

        return $linkBuilder->GetLink();
    }

    public function GetOperationLink($operationName, $operationForAllPages = false, $primaryKeyValues = array())
    {
        $result = $this->CreateLinkBuilder();
        $result->AddParameter(OPERATION_PARAMNAME, $operationName);
        if ($operationForAllPages) {
            if (isset($this->pageNavigator)) {
                $this->pageNavigator->AddCurrentPageParameters($result);
            }
        }

        if ($primaryKeyValues) {
            foreach ($primaryKeyValues as $i => $value) {
                $result->addParameter("pk$i", $value);
            }
        }

        return $result->GetLink();
    }

    function GetPrintAllLink()
    {
        return $this->GetOperationLink(OPERATION_PRINT_ALL);
    }

    function GetPrintCurrentPageLink()
    {
        return $this->GetOperationLink(OPERATION_PRINT_PAGE, true);
    }

    function GetExportToExcelLink()
    {
        return $this->getExportLink('excel');
    }

    function GetExportToWordLink()
    {
        return $this->getExportLink('word');
    }

    function GetExportToXmlLink()
    {
        return $this->getExportLink('xml');
    }

    function GetExportToCsvLink()
    {
        return $this->getExportLink('csv');
    }

    function GetExportToPdfLink()
    {
        return $this->getExportLink('pdf');
    }

    private function getExportLink($type, $primaryKeyValues = array())
    {
        $exportMap = $primaryKeyValues ? array(
            'excel' => OPERATION_EXCEL_EXPORT_RECORD,
            'pdf' => OPERATION_PDF_EXPORT_RECORD,
            'csv' => OPERATION_CSV_EXPORT_RECORD,
            'xml' => OPERATION_XML_EXPORT_RECORD,
            'word' => OPERATION_WORD_EXPORT_RECORD,
        ) : array(
            'excel' => OPERATION_EXCEL_EXPORT,
            'pdf' => OPERATION_PDF_EXPORT,
            'csv' => OPERATION_CSV_EXPORT,
            'xml' => OPERATION_XML_EXPORT,
            'word' => OPERATION_WORD_EXPORT,
        );

        return $this->GetOperationLink($exportMap[$type], false, $primaryKeyValues);
    }

    #endregion

    private function GetCurrentPageMode() {
        switch (GetApplication()->GetOperation()) {
            case OPERATION_VIEWALL:
            case OPERATION_RETURN:
                return PageMode::ViewAll;
            case OPERATION_VIEW:
                return PageMode::View;
            case OPERATION_EDIT:
                return PageMode::Edit;
            case OPERATION_INSERT:
                return PageMode::Insert;
        }
        return null;
    }

    public function GetCustomTemplate($part, $mode, $defaultValue, &$params = null) {
        return parent::GetCustomTemplate(
            $part,
            $mode ? $mode : $this->GetCurrentPageMode(),
            $defaultValue,
            $params
        );
    }

    /**
     * @return string
     */
    public function getDetailedDescription() {
        return $this->detailedDescription;
    }

    /**
     * @param string $value
     */
    public function setDetailedDescription($value) {
        $this->detailedDescription = $value;
    }

    /**
     * @param bool $modalViewSize
     *
     * @return $this
     */
    public function setModalViewSize($modalViewSize)
    {
        $this->modalViewSize = $modalViewSize;
    }

    /**
     * @return bool
     */
    public function getModalViewSize()
    {
        return $this->modalViewSize;
    }

    /**
     * @param bool $modalFormSize
     *
     * @return $this
     */
    public function setModalFormSize($modalFormSize)
    {
        $this->modalFormSize = $modalFormSize;
    }

    /**
     * @return bool
     */
    public function getModalFormSize()
    {
        return $this->modalFormSize;

    }

    /**
     * @param string $fieldName
     * @param mixed  &$value
     */
    public function OnGetFieldValue($fieldName, &$value)
    {
    }

    public function UpdateValuesFromUrl()
    {
    }

    public function setAddNewChoices(array $choices)
    {
        $this->addNewChoices = $choices;
    }

    public function getAddNewChoices()
    {
        return $this->addNewChoices;
    }

    public function isInline()
    {
        return false;
    }

    /**
     * @return Navigation
     */
    public function getNavigation(array $fieldValues = array())
    {
        $url = $this->CreateLinkBuilder()->getLink();
        $result = new Navigation($this);


        $groupName = null;
        $siblings = new Navigation($this);
        foreach ($this->getPageList()->GetPagesViewData() as $page) {
            if ($page['Href'] === $url && $page['GroupName'] !== 'Default') {
                $groupName = $page['GroupName'];
            }
        }

        foreach ($this->getPageList()->GetPagesViewData() as $page) {
            if ($page['Href'] !== $url && $page['GroupName'] === $groupName) {
                $siblings->append($page['Caption'], $page['Href']);
            }
        }

        $result->prepend($this->getTitle(), $url, count($siblings) > 0 ? $siblings : null);

        if (!is_null($groupName)) {
            $result->prepend(
                $groupName,
                HasHomePage() ? 'index.php?group=' . urlencode($groupName) : null
            );
        }

        return $result;
    }

    public function getViewId()
    {
        return '';
    }


    public function getType()
    {
        return PageType::Data;
    }
}
