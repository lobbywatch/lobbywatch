<?php
// Processed by afterburner.sh



// require_once 'libs/smartylibs/Smarty.class.php';
include_once dirname(__FILE__) . '/' . '../libs/smartylibs/Smarty.class.php';

// require_once 'database_engine/insert_command.php';
// require_once 'database_engine/update_command.php';
// require_once 'database_engine/select_command.php';
// require_once 'database_engine/delete_command.php';
include_once dirname(__FILE__) . '/' . '../database_engine/insert_command.php';
include_once dirname(__FILE__) . '/' . '../database_engine/update_command.php';
include_once dirname(__FILE__) . '/' . '../database_engine/select_command.php';
include_once dirname(__FILE__) . '/' . '../database_engine/delete_command.php';

// require_once 'captions.php';
// require_once 'env_variables.php';
include_once dirname(__FILE__) . '/' . 'captions.php';
include_once dirname(__FILE__) . '/' . 'env_variables.php';

// require_once 'grid/grid.php';
// require_once 'grid/columns.php';
// require_once 'grid/operation_columns.php';
// require_once 'grid/edit_columns.php';
include_once dirname(__FILE__) . '/' . 'grid/grid.php';
include_once dirname(__FILE__) . '/' . 'grid/columns.php';
include_once dirname(__FILE__) . '/' . 'grid/operation_columns.php';
include_once dirname(__FILE__) . '/' . 'grid/edit_columns.php';

// require_once 'dataset/dataset.php';
// require_once 'dataset/table_dataset.php';
// require_once 'dataset/query_dataset.php';
include_once dirname(__FILE__) . '/' . 'dataset/dataset.php';
include_once dirname(__FILE__) . '/' . 'dataset/table_dataset.php';
include_once dirname(__FILE__) . '/' . 'dataset/query_dataset.php';

// require_once 'renderers/renderer.php';
// require_once 'renderers/edit_renderer.php';
// require_once 'renderers/list_renderer.php';
// require_once 'renderers/view_renderer.php';
// require_once 'renderers/print_renderer.php';
// require_once 'renderers/insert_renderer.php';
// require_once 'renderers/excel_renderer.php';
// require_once 'renderers/word_renderer.php';
// require_once 'renderers/xml_renderer.php';
// require_once 'renderers/csv_renderer.php';
// require_once 'renderers/pdf_renderer.php';
// require_once 'renderers/inline_operation_renderers.php';
// require_once 'renderers/rss_renderer.php';

include_once dirname(__FILE__) . '/' . 'renderers/renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/edit_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/list_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/view_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/print_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/insert_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/excel_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/word_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/xml_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/csv_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/pdf_renderer.php';
include_once dirname(__FILE__) . '/' . 'renderers/inline_operation_renderers.php';
include_once dirname(__FILE__) . '/' . 'renderers/rss_renderer.php';

// require_once 'common.php';
// require_once 'page.php';
// require_once 'page_navigator.php';
// require_once 'simple_search_control.php';
// require_once 'advanced_search_page.php';
// require_once 'page_list.php';
// require_once 'dataset_rss_generator.php';
// require_once 'error_utils.php';
// require_once 'superglobal_wrapper.php';
// require_once 'utils/array_utils.php';

include_once dirname(__FILE__) . '/' . 'common.php';
include_once dirname(__FILE__) . '/' . 'page.php';
include_once dirname(__FILE__) . '/' . 'page_navigator.php';
include_once dirname(__FILE__) . '/' . 'simple_search_control.php';
include_once dirname(__FILE__) . '/' . 'advanced_search_page.php';
include_once dirname(__FILE__) . '/' . 'page_list.php';
include_once dirname(__FILE__) . '/' . 'dataset_rss_generator.php';
include_once dirname(__FILE__) . '/' . 'error_utils.php';
include_once dirname(__FILE__) . '/' . 'superglobal_wrapper.php';
include_once dirname(__FILE__) . '/' . 'utils/array_utils.php';

// require_once 'security/security_info.php';
include_once dirname(__FILE__) . '/' . 'security/security_info.php';

// require_once 'application.php';
include_once dirname(__FILE__) . '/' . 'application.php';

define('OPERATION_HTTPHANDLER_NAME_PARAMNAME', 'hname');
define('OPERATION_PARAMNAME', 'operation');

define('OPERATION_VIEW', 'view');
define('OPERATION_EDIT', 'edit');
define('OPERATION_INSERT', 'insert');
define('OPERATION_COPY', 'copy');
define('OPERATION_DELETE', 'delete');
define('OPERATION_VIEWALL', 'viewall');
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

define('OPERATION_AJAX_REQUERT_INLINE_EDIT', 'arqie');
define('OPERATION_AJAX_REQUERT_INLINE_INSERT', 'arqii');
define('OPERATION_AJAX_REQUERT_INLINE_EDIT_COMMIT', 'arqiec');
define('OPERATION_AJAX_REQUERT_INLINE_INSERT_COMMIT', 'arqiic');
define('OPERATION_ADVANCED_SEARCH', 'advsrch');

define('OPERATION_RSS', 'rss');

define('OPERATION_HTTPHANDLER_REQUEST', 'httphandler');

// require_once 'components/grid/vertical_grid.php';
// require_once 'components/grid/modal_edit_handler.php';

include_once dirname(__FILE__) . '/' . 'grid/vertical_grid.php';
include_once dirname(__FILE__) . '/' . 'grid/modal_edit_handler.php';

class PagePart {
    const Grid = 'grid';
    const GridRow = 'grid-row';
    const VerticalGrid = 'vertical-grid';
    const PageList = 'page-list';
    const Layout = 'layout';
    const RecordCard = 'record-card';
}

class PageMode {
    const ViewAll = 'view-all';
    const View = 'view';
    const Edit = 'edit';
    const Insert = 'insert';
    const ModalView = 'modal-view';
    const ModalEdit = 'modal-edit';
    const ModalInsert = 'modal-insert';
}

function GetOperation()
{
    return GetApplication()->GetOperation();
}

interface IPage
{
    public function GetCustomClientScript();

    public function GetOnPageLoadedClientScript();

    public function GetValidationScripts();
}

abstract class Page implements IPage, IVariableContainer
{
    private $pageFileName;
    /** @var Renderer */
    protected $renderer;
    private $httpHandlerName;
    /** @var IDataSourceSecurityInfo */
    private $securityInfo;
    private $contentEncoding;
    /**
     * @var IRecordPermissions
     */
    private $recordPermission;

    private $message;
    private $errorMessage;
    private $columnVariableContainer;
    private $localizerCaptions;

    #region Page parts
    /** @var Dataset */
    protected $dataset;

    /** @var Grid */
    private $grid;

    /** @var AbstractPageNavigator */
    private $pageNavigator;
    /** @var DatasetRssGenerator */
    private $rssGenerator;

    /** @var AdvancedSearchControl */
    public $AdvancedSearchControl;
    private $pageNavigatorStack;
    private $paginationControl;
    #endregion

    #region Option fields
    private $gridHeader;
    private $header;
    private $footer;
    private $caption;
    private $shortCaption;
    private $showUserAuthBar = false;
    private $showTopPageNavigator = true;
    private $showBottomPageNavigator = false;
    private $showPageList;
    private $hidePageListByDefault;
    private $exportToExcelAvailable;
    private $exportToWordAvailable;
    private $exportToXmlAvailable;
    private $exportToCsvAvailable;
    private $exportToPdfAvailable;
    private $printerFriendlyAvailable;
    private $simpleSearchAvailable;
    private $advancedSearchAvailable;
    private $filterRowAvailable;
    private $visualEffectsEnabled;
    public $Margin;
    public $Padding;
    #endregion

    #region Events
    public $BeforePageRender;
    public $OnCustomHTMLHeader;
    public $OnPageLoadedClientScript;
    public $OnGetCustomTemplate;
    #endregion
    
    #region IVariableContainer implementation
    private $variableFuncs = array(
        'PAGE_SHORT_CAPTION'    => 'return $page->GetCaption();',
        'PAGE_CAPTION'          => 'return $page->GetShortCaption();',
        'PAGE_CSV_EXPORT_LINK'  => 'return $page->GetExportToCsvLink();',
        'PAGE_XLS_EXPORT_LINK'  => 'return $page->GetExportToExcelLink();',
        'PAGE_PDF_EXPORT_LINK'  => 'return $page->GetExportToPdfLink();',
        'PAGE_XML_EXPORT_LINK'  => 'return $page->GetExportToXmlLink();',
        'PAGE_WORD_EXPORT_LINK' => 'return $page->GetExportToWordLink();'
        );

    #region ViewData

    public function GetCommonViewData() {
        return array(
            'Direction' => $this->GetPageDirection(),
            'ContentEncoding' => $this->GetContentEncoding(),
            'PageCaption' => $this->GetCaption(),
            'ValidationScripts' => $this->GetValidationScripts(),
            'Header' => $this->GetHeader(),
            'CustomHtmlHeadSection' => $this->GetCustomPageHeader(),
            'ClientSideEvents' => array(
                'OnAfterLoadEvent' => $this->GetOnPageLoadedClientScript(),
                'OnBeforeLoadEvent' => $this->GetCustomClientScript()
            )
        );
    }

    public function GetSeparatedEditViewData() {
        return array_merge($this->GetCommonViewData(), array(
            'MainScript' => 'pgui.edit-page-main'
        ));
    }

    public function GetSeparatedInsertViewData() {
        return array_merge($this->GetCommonViewData(), array(
            'MainScript' => 'pgui.insert-page-main'
        ));
    }

    public function GetSingleRecordViewData() {
        return array_merge($this->GetCommonViewData(), array());
    }

    public function GetListViewData() {
        return array_merge($this->GetCommonViewData(), array());
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

    public function GetValidationScripts()
    {
        return StringUtils::Format(
            "function EditValidation(fieldValues, errorInfo) { %s; return true; } " .
            " function InsertValidation(fieldValues, errorInfo) { %s; return true; }".
            " function EditForm_EditorValuesChanged(sender, editors) { %s; return true; }".
            " function InsertForm_EditorValuesChanged(sender, editors) { %s; return true; }".
            " function EditForm_Initialized(editors) { %s; return true; }".
            " function InsertForm_Initialized(editors) { %s; return true; }",

            $this->GetGrid()->GetEditClientValidationScript(),
            $this->GetGrid()->GetInsertClientValidationScript(),

            $this->GetGrid()->GetEditClientEditorValueChangedScript(),
            $this->GetGrid()->GetInsertClientEditorValueChangedScript(),
            $this->GetGrid()->GetEditClientFormLoadedScript(),
            $this->GetGrid()->GetInsertClientFormLoadedScript()
        );

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
                    GetApplication()->CanUserChangeOwnPassword()
        );
    }

    public function FillAvailableVariables(&$variables)
    {
        return array_keys($this->variableFuncs);
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

    protected function CreateComponents()
    {
        $this->grid = $this->CreateGrid();
        $this->grid->SetFilterRowAvailable($this->GetFilterRowAvailable());

        // remove: search controls must be configured from the generated code
        $this->grid->UpdateSearchControls();

        $this->pageNavigator = $this->CreatePageNavigator();
        $this->httpHandlerName = null;
        $this->FillPageNavigatorStack();
        $this->RegisterHandlers();
    }

    protected function GetEnableModalGridDelete()
    {
        return false;
    }

    public function GetModalGridDeleteHandler()
    {
        return 'inline_grid';
    }

    public function GetHttpHandlerName() {
        return $this->httpHandlerName;
    }

    private function RegisterHandlers()
    {
        if ($this->GetEnableModalSingleRecordView())
        {
            $handler = new InlineGridViewHandler($this->GetModalGridViewHandler(), new RecordCardView($this->GetGrid()));
            GetApplication()->RegisterHTTPHandler($handler);
        }
        if ($this->GetEnableModalGridCopy())
        {
            $handler = new InlineGridHandler($this->GetModalGridCopyHandler(), new VerticalGrid($this->GetGrid()));
            GetApplication()->RegisterHTTPHandler($handler);
        }
        if ($this->GetEnableModalGridEditing())
        {
            $handler = new InlineGridHandler($this->GetModalGridEditingHandler(), new VerticalGrid($this->GetGrid()));
            GetApplication()->RegisterHTTPHandler($handler);
        }
        if ($this->GetEnableModalGridDelete())
        {
            $handler = new ModalDeleteHandler($this->GetModalGridDeleteHandler(), $this->GetGrid());
            GetApplication()->RegisterHTTPHandler($handler);
        }
    }

    public function GetModalGridCopyHandler()
    {
        return 'modal_copy';
    }
    
    public function GetModalGridViewHandler()
    {
        return 'inline_grid_view';
    }

    public function GetModalGridEditingHandler()
    {
        return 'inline_grid';
    }

    protected function GetEnableModalGridCopy()
    {
        return true;
    }

    protected function GetEnableModalGridEditing()
    {
        return false;
    }

    protected function GetEnableModalSingleRecordView()
    {
        return true;
    }

    function __construct($pageFileName, $caption = null, $dataSourceSecurityInfo = null, $contentEncoding=null)
    {
        $this->BeforePageRender = new Event();
        $this->OnCustomHTMLHeader = new Event();
        $this->OnGetCustomTemplate = new Event();

        $this->contentEncoding = $contentEncoding;
        $this->securityInfo = $dataSourceSecurityInfo;
        $this->pageFileName = $pageFileName;
        $this->caption = $caption;
        $this->shortCaption = $caption;
        $this->showPageList = true;
        $this->exportToExcelAvailable = true;
        $this->exportToWordAvailable = true;
        $this->exportToXmlAvailable = true;
        $this->exportToCsvAvailable = true;
        $this->exportToPdfAvailable = true;
        $this->printerFriendlyAvailable = true;
        $this->simpleSearchAvailable = true;
        $this->advancedSearchAvailable = true;
        $this->filterRowAvailable = true;
        $this->visualEffectsEnabled = true;
        $this->rssGenerator = null;

        $this->BeforeCreate();
        $this->CreateComponents();
        $this->gridHeader = '';
        $this->recordPermission = null;
        $this->message = null;
        $this->pageNavigatorStack = array();
    }

    public function UpdateValuesFromUrl()
    { }

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

    public function GetExportButtonsViewData() {

        $result = array();

        if ($this->GetExportToExcelAvailable())
            $result['excel'] = array(
                'Caption' =>    $this->GetLocalizerCaptions()->GetMessageString('ExportToExcel'),
                'IconClass' => 'export-excel',
                'Href' =>       $this->GetExportToExcelLink()
            );

        if ($this->GetExportToPdfAvailable())
            $result['pdf'] = array(
                'Caption' =>   $this->GetLocalizerCaptions()->GetMessageString('ExportToPdf'),
                'IconClass' => 'export-pdf',
                'Href' =>      $this->GetExportToPdfLink()
            );

        if ($this->GetExportToCsvAvailable())
            $result['csv'] = array(
                'Caption' =>   $this->GetLocalizerCaptions()->GetMessageString('ExportToCsv'),
                'IconClass' => 'export-csv',
                'Href' =>      $this->GetExportToCsvLink()
            );

        if ($this->GetExportToXmlAvailable())
            $result['xml'] = array(
                'Caption' =>   $this->GetLocalizerCaptions()->GetMessageString('ExportToXml'),
                'IconClass' => 'export-xml',
                'Href' =>      $this->GetExportToXmlLink()
            );

        if ($this->GetExportToWordAvailable())
            $result['word'] = array(
                'Caption' =>   $this->GetLocalizerCaptions()->GetMessageString('ExportToWord'),
                'IconClass' => 'export-word',
                'Href' =>      $this->GetExportToWordLink()
            );

        if ($this->GetPrinterFriendlyAvailable()) {

            $result['print_page'] = array(
                'Caption' =>   $this->GetLocalizerCaptions()->GetMessageString('PrintAllPages'),
                'IconClass' => 'print-all-pages',
                'Href' =>      $this->GetPrintAllLink(),
                'BeginNewGroup' => true
            );
            $result['print_all'] = array(
                'Caption' =>   $this->GetLocalizerCaptions()->GetMessageString('PrintCurrentPage'),
                'IconClass' => 'print-page',
                'Href' =>      $this->GetPrintCurrentPageLink()
            );
        }
        return $result;
    }

    public function GetReadyPageList() {
        $result = $this->GetPageList();
        if ($result)
            $result->AddRssLinkForCurrentPage($this->GetRssLink());

        return $result;
    }

    public function GetForeingKeyFields()
    { 
        return array(); 
    }

    public function BeforeCreate()
    {
        try
        {
            $this->DoBeforeCreate();
            $this->rssGenerator = $this->CreateRssGenerator();
        }
        catch(Exception $e)
        {
            $message = $this->GetLocalizerCaptions()->GetMessageString('GuestAccessDenied');
            ShowSecurityErrorPage($this, $message);
            die();
        }
    }

    /**
     * @return Captions
     */
    public function GetLocalizerCaptions()
    {
        if (!isset($this->localizerCaptions))
            $this->localizerCaptions = new Captions($this->GetContentEncoding());
        return $this->localizerCaptions;
    }

    public function GetConnection()
    {
        $this->dataset->Connect();
        return $this->dataset->GetConnection();
    }

    public function RenderText($text)
    {
        return ConvertTextToEncoding($text, GetAnsiEncoding(), $this->GetContentEncoding());
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

    protected function DoGetGridHeader()
    { 
        return ''; 
    }

    public function GetGridHeader()
    { 
        return $this->RenderText($this->DoGetGridHeader()); 
    }

    public function GetCustomClientScript()
    {
        return '';
    }

    public function GetOnPageLoadedClientScript()
    {
        return '';
    }

    public function GetPageDirection()
    {
        return null;
    }

    public function GetShowUserAuthBar()
    { 
        return $this->showUserAuthBar;
    }

    public function SetShowUserAuthBar($value)
    { 
        $this->showUserAuthBar = $value;
    }

    public function GetShortCaption()
    { 
        return $this->RenderText($this->shortCaption); 
    }
    
    public function SetShortCaption($value)
    { 
        $this->shortCaption = $value; 
    }

    public function GetCaption()
    { 
        return $this->RenderText($this->caption); 
    }

    public function SetCaption($value)
    { 
        $this->caption = $value; 
    }

    public function GetHeader()
    { 
        return $this->RenderText($this->header); 
    }

    public function SetHeader($value)
    { 
        $this->header = $value; 
    }

    public function GetFooter()
    { 
        return $this->RenderText($this->footer); 
    }

    public function SetFooter($value)
    { 
        $this->footer = $value; 
    }

    public function GetContentEncoding()
    { 
        return $this->contentEncoding; 
    }

    public function SetContentEncoding($value)
    { 
        $this->contentEncoding = $value; 
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

    function GetExportToExcelAvailable()
    { return $this->exportToExcelAvailable; }
    function GetExportToWordAvailable()
    { return $this->exportToWordAvailable; }
    function GetExportToXmlAvailable()
    { return $this->exportToXmlAvailable; }
    function GetExportToCsvAvailable()
    { return $this->exportToCsvAvailable; }
    function GetExportToPdfAvailable()
    { return $this->exportToPdfAvailable; }
    function GetPrinterFriendlyAvailable()
    { return $this->printerFriendlyAvailable; }
    function GetSimpleSearchAvailable()
    { return $this->simpleSearchAvailable; }
    function GetAdvancedSearchAvailable()
    { return $this->advancedSearchAvailable; }
    function GetVisualEffectsEnabled()
    { return $this->visualEffectsEnabled; }

    function SetShowPageList($value)
    { $this->showPageList = $value; }
    function SetHidePageListByDefault($value)
    { $this->hidePageListByDefault = $value; }
    function SetExportToExcelAvailable($value)
    { $this->exportToExcelAvailable = $value; }
    function SetExportToWordAvailable($value)
    { $this->exportToWordAvailable = $value; }
    function SetExportToXmlAvailable($value)
    { $this->exportToXmlAvailable = $value; }
    function SetExportToCsvAvailable($value)
    { $this->exportToCsvAvailable = $value; }
    function SetExportToPdfAvailable($value)
    { $this->exportToPdfAvailable = $value; }
    function SetPrinterFriendlyAvailable($value)
    { $this->printerFriendlyAvailable = $value; }
    function SetSimpleSearchAvailable($value)
    { $this->simpleSearchAvailable = $value; }
    function SetAdvancedSearchAvailable($value)
    { $this->advancedSearchAvailable = $value; }
    function SetFilterRowAvailable($value)
    { $this->filterRowAvailable = $value; }
    function GetFilterRowAvailable()
    { return $this->filterRowAvailable; }
    function SetVisualEffectsEnabled($value)
    { $this->visualEffectsEnabled = $value; }

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

    protected function GetSecurityInfo()
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
            case OPERATION_INPUT_FINISHED_SELECTED: // Afterburner
            case OPERATION_DE_INPUT_FINISHED_SELECTED: // Afterburner
            case OPERATION_CONTROLLED_SELECTED: // Afterburner
            case OPERATION_DE_CONTROLLED_SELECTED: // Afterburner
            case OPERATION_AUTHORIZATION_SENT_SELECTED: // Afterburner
            case OPERATION_DE_AUTHORIZATION_SENT_SELECTED: // Afterburner
            case OPERATION_AUTHORIZE_SELECTED: // Afterburner
            case OPERATION_DE_AUTHORIZE_SELECTED: // Afterburner
            case OPERATION_RELEASE_SELECTED: // Afterburner
            case OPERATION_DE_RELEASE_SELECTED: // Afterburner
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
                $this->renderer = new ExcelRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_WORD_EXPORT:
                $this->renderer = new WordRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_XML_EXPORT:
                $this->renderer = new XmlRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_CSV_EXPORT:
                $this->renderer = new CsvRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_PDF_EXPORT:
                $this->renderer = new PdfRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_DELETE_SELECTED:
            case OPERATION_INPUT_FINISHED_SELECTED: // Afterburner
            case OPERATION_DE_INPUT_FINISHED_SELECTED: // Afterburner
            case OPERATION_CONTROLLED_SELECTED: // Afterburner
            case OPERATION_DE_CONTROLLED_SELECTED: // Afterburner
            case OPERATION_AUTHORIZATION_SENT_SELECTED: // Afterburner
            case OPERATION_DE_AUTHORIZATION_SENT_SELECTED: // Afterburner
            case OPERATION_AUTHORIZE_SELECTED: // Afterburner
            case OPERATION_DE_AUTHORIZE_SELECTED: // Afterburner
            case OPERATION_RELEASE_SELECTED: // Afterburner
            case OPERATION_DE_RELEASE_SELECTED: // Afterburner
                $this->renderer = new ViewAllRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_AJAX_REQUERT_INLINE_EDIT:
                $this->renderer = new InlineEditRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_AJAX_REQUERT_INLINE_EDIT_COMMIT:
                $this->renderer = new CommitInlineEditRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_AJAX_REQUERT_INLINE_INSERT:
                $this->renderer = new InlineInsertRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_AJAX_REQUERT_INLINE_INSERT_COMMIT:
                $this->renderer = new CommitInlineInsertRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_ADVANCED_SEARCH:
                $this->renderer = new SingleAdvancedSearchRenderer($this->GetLocalizerCaptions());
                break;
            case OPERATION_RSS:
                $this->renderer = new RssRenderer($this->GetLocalizerCaptions());
                break;
            default:
                $this->renderer = new ViewAllRenderer($this->GetLocalizerCaptions());
                break;
        }
    }

     function OpenAdvancedSearchByDefault()
    {
        return false;
    }

    function DoProcessMessages()
    {
        if (GetOperation() != OPERATION_RSS)
        {
            //if (isset($this->AdvancedSearchControl) && $this->OpenAdvancedSearchByDefault())
                //if (!$this->AdvancedSearchControl->HasCondition())
                //    GetApplication()->SetOperation(OPERATION_ADVANCED_SEARCH);

            $this->grid->SetState(GetOperation());
            $advancedSearchApplied = false;
            if (isset($this->AdvancedSearchControl)) {
                $advancedSearchApplied = $this->AdvancedSearchControl->ProcessMessages();
            }

            $this->grid->ProcessMessages();
            if (isset($this->pageNavigator))
                $this->pageNavigator->ProcessMessages();

            if ($advancedSearchApplied) {
                $link = $this->CreateLinkBuilder();
                header('Location: ' . $link->GetLink());
                exit();
            }
        }
    }

    function ProcessMessages()
    {
        try
        {
            $this->DoProcessMessages();
        }
        catch(Exception $e)
        {
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

    function CreateLinkBuilder()
    {
        $result = new LinkBuilder($this->GetPageFileName());

        if (isset($this->httpHandlerName))
            $result->AddParameter('hname', $this->httpHandlerName);

        return $result;
    }

    #region Export links

    function GetOperationLink($operationName, $operationForAllPages = false)
    {
        $result = $this->CreateLinkBuilder();
        $result->AddParameter(OPERATION_PARAMNAME, $operationName);
        if ($operationForAllPages)
            if (isset($this->pageNavigator))
                $this->pageNavigator->AddCurrentPageParameters($result);
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
        return $this->GetOperationLink(OPERATION_EXCEL_EXPORT, true);
    }

    function GetExportToWordLink()
    {
        return $this->GetOperationLink(OPERATION_WORD_EXPORT, true);
    }

    function GetExportToXmlLink()
    {
        return $this->GetOperationLink(OPERATION_XML_EXPORT, true);
    }

    function GetExportToCsvLink()
    {
        return $this->GetOperationLink(OPERATION_CSV_EXPORT, true);
    }

    function GetExportToPdfLink()
    {
        return $this->GetOperationLink(OPERATION_PDF_EXPORT, true);
    }

    #endregion

    private function GetCurrentPageMode() {
        switch (GetApplication()->GetOperation()) {
            case OPERATION_VIEWALL:
                return PageMode::ViewAll;
        }
        return null;
    }

    public function GetCustomTemplate($part, $mode, $defaultValue, &$params = null) {
        $result = null;

        if (!$mode) // for PageList
            $mode = $this->GetCurrentPageMode();
        if (!$params)
            $params = array();

        $this->OnGetCustomTemplate->Fire(array($part, $mode, &$result, &$params));
        if ($result)
            return Path::Combine('custom_templates', $result);
        else
            return $defaultValue;
    }

    public function GetBackFromAdvancedSearchAddress()
    {
        $result = $this->CreateLinkBuilder();
        return $result->GetLink();
    }

    /**
     * @return PageList
     */
    public abstract function GetPageList();
}

abstract class DetailPage extends Page
{
    private $foreingKeyValues;
    private $foreingKeyFields;
    private $recordLimit;
    private $totalRowCount;
    private $fullViewHandlerName;
    /** @var Page */
    private $parentPage;

    public $DetailRowNumber;

    public function __construct($parentPage, $caption, $shortCaption, $foreingKeyFields, $dataSourceSecurityInfo, $contentEncoding = null, $recordLimit = 0, $fullViewHandlerName)
    {
        $this->foreingKeyFields = $foreingKeyFields;
        $this->parentPage = $parentPage;
        parent::__construct('', $caption, $dataSourceSecurityInfo, $contentEncoding);
        $this->SetShortCaption($shortCaption);
        $this->recordLimit = $recordLimit;
        $this->fullViewHandlerName = $fullViewHandlerName;
    }

    public function GetPageFileName() {
        return $this->parentPage->GetPageFileName();
    }

    public function GetForeingKeyFields()
    { return $this->foreingKeyFields; }

    public function GetParentPage() {
        return $this->parentPage;
    }

    public function GetPageList() {
        return $this->parentPage->GetPageList();
    }

    public function ProcessMessages()
    {
        if ($this->recordLimit)
        {
            $this->dataset->SetUpLimit(0);
            $this->dataset->SetLimit($this->recordLimit);
        }
        $this->DetailRowNumber = $_GET['detailrow'];
        $this->GetGrid()->SetId($this->DetailRowNumber . $this->GetGrid()->GetId());
        $this->GetGrid()->ProcessMessages();
        $this->renderer = new ViewAllRenderer($this->GetLocalizerCaptions());
        for($i = 0; $i < count($this->foreingKeyFields); $i++)
        {
            $this->dataset->AddFieldFilter($this->foreingKeyFields[$i], new FieldFilter($_GET['fk' . $i], '='));
            $this->foreingKeyValues[] = $_GET['fk' . $i];
        }
        $this->totalRowCount = $this->dataset->GetTotalRowCount();
        $this->GetGrid()->SetShowUpdateLink(false);
    }

    protected function CreatePageNavigator()
    { }

    public function GetHiddenGetParameters()
    {
        $result = parent::GetHiddenGetParameters();
        for($i = 0; $i < count($this->foreingKeyValues); $i++)
            $result['fk' . $i] = $this->foreingKeyValues[$i];
        return $result;
    }

    protected function CreateComponents() {
        parent::CreateComponents();
        $this->GetGrid()->SetShowFilterBuilder(false);
    }

    function CreateLinkBuilder()
    {
        $result = parent::CreateLinkBuilder();
        $result->AddParameter('hname', $this->fullViewHandlerName);
        for($i = 0; $i < count($this->foreingKeyValues); $i++)
            $result->AddParameter('fk' . $i, $this->foreingKeyValues[$i]);
        return $result;
    }

    public function GetFullRecordCount()
    { return $this->totalRowCount; }
    public function GetRecordLimit()
    { return $this->recordLimit; }
    public function GetFullViewLink()
    {
        $result = $this->CreateLinkBuilder();
        $result->AddParameter('hname', $this->fullViewHandlerName);
        return $result->GetLink();
    }

    /** @inheritdoc */
    function Accept($visitor)
    {
        $visitor->RenderDetailPage($this);
    }

    function EndRender()
    {
        echo $this->renderer->Render($this);
    }
}

abstract class DetailPageEdit extends Page
{
    private $foreingKeyValues;
    private $foreingKeyFields;
    private $masterKeyFields;
    /** @var Dataset */
    private $masterDataset;
    private $masterGrid;
    /** @var Page */
    private $parentPage;
    private $parentMasterKeyFields;
    private $parentMasterKeyValues;

    public function __construct($parentPage, $foreingKeyFields, $masterKeyFields, $parentMasterKeyFields, $masterGrid, $masterDataset, $dataSourceSecurityInfo, $contentEncoding = null)
    {
        $this->foreingKeyFields = $foreingKeyFields;
        $this->parentPage = $parentPage;
        parent::__construct('', '', $dataSourceSecurityInfo, $contentEncoding);
        $this->masterKeyFields = $masterKeyFields;
        $this->masterGrid = $masterGrid;
        $this->masterDataset = $masterDataset;
        $this->foreingKeyValues = array();
        $this->parentMasterKeyFields = $parentMasterKeyFields;
    }

    public function GetReadyPageList() {
        return $this->parentPage->GetReadyPageList();
    }

    public function GetParentPage() {
        return $this->parentPage;
    }

    public function GetForeingKeyFields()
    { return $this->foreingKeyFields; }

    public function GetMasterGrid()
    { return $this->masterGrid; }

    public function ProcessMessages()
    {
        $this->UpdateValuesFromUrl();
        parent::ProcessMessages();
    }

    public function UpdateValuesFromUrl()
    {
        for($i = 0; $i < count($this->foreingKeyFields); $i++)
        {
            if (GetApplication()->GetSuperGlobals()->IsGetValueSet('fk' . $i))
            {
                $this->dataset->AddFieldFilter($this->foreingKeyFields[$i], new FieldFilter($_GET['fk' . $i], '='));
                $this->dataset->SetMasterFieldValue($this->foreingKeyFields[$i], $_GET['fk' . $i]);
                $this->foreingKeyValues[] = $_GET['fk' . $i];
            }
        }

        $this->RetrieveMasterDatasetValues();
    }
    

    private function RetrieveMasterDatasetValues()
    {
        $hasForeignKeyValues = true;

        for($i = 0; $i < count($this->masterKeyFields); $i++)
        {
            if (GetApplication()->GetSuperGlobals()->IsGetValueSet('fk' . $i))
            {
                $this->masterDataset->AddFieldFilter($this->masterKeyFields[$i], new FieldFilter($_GET['fk' . $i], '='));
            }
            else
            {
                $hasForeignKeyValues = false;
            }
        }

        if ($hasForeignKeyValues)
        {
            $this->masterDataset->Open();
            if ($this->masterDataset->Next())
            {
                for($i = 0; $i < count($this->parentMasterKeyFields); $i++)
                    $this->parentMasterKeyValues[] = $this->masterDataset->GetFieldValueByName($this->parentMasterKeyFields[$i]);
            }
            $this->masterDataset->Close();
        }
    }

    /** @inheritdoc */
    function Accept($visitor)
    {
        $visitor->RenderDetailPageEdit($this);
    }

    public function GetHiddenGetParameters()
    {
        $result = parent::GetHiddenGetParameters();
        for($i = 0; $i < count($this->foreingKeyValues); $i++)
            $result['fk' . $i] = $this->foreingKeyValues[$i];
        return $result;
    }

    function GetParentPageLink()
    {
        $result = $this->parentPage->CreateLinkBuilder();

        for($i = 0; $i < count($this->parentMasterKeyFields); $i++)
            $result->AddParameter('fk'.$i, $this->parentMasterKeyValues[$i]);

        return $result->GetLink();
    }

    function CreateLinkBuilder()
    {
        $result = parent::CreateLinkBuilder();
        for($i = 0; $i < count($this->foreingKeyValues); $i++)
            $result->AddParameter('fk' . $i, $this->foreingKeyValues[$i]);
        return $result;
    }

    function GetOperationLink($operationName, $operationForAllPages = false)
    {
        $result = $this->CreateLinkBuilder();
        $result->AddParameter(OPERATION_PARAMNAME, $operationName);

        for($i = 0; $i < count($this->foreingKeyValues); $i++)
            $result->AddParameter('fk' . $i, $this->foreingKeyValues[$i]);

        $pageNavigator = $this->GetPageNavigator();
        if ($operationForAllPages && isset($pageNavigator))
            $pageNavigator->AddCurrentPageParameters($result);
        return $result->GetLink();

    }
}

class CustomLoginPage implements IPage
{
    public function __construct()
    { }

    public function GetValidationScripts()
    {
        return '';
    }

    public function GetPageDirection()
    {
        return null;
    }

    public function GetCustomPageHeader()
    {
        return '';
    }

    public function GetOnPageLoadedClientScript()
    {
        return '';
    }

    public function GetCustomClientScript()
    {
        return '';
    }

    public function RenderText($text)
    {
        return ConvertTextToEncoding($text, GetAnsiEncoding(), $this->GetContentEncoding());
    }

    public function GetContentEncoding() { return 'UTF-8'; }
}
