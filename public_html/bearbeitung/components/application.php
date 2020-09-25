<?php

include_once dirname(__FILE__) . '/page/page.php';
include_once dirname(__FILE__) . '/common.php';
include_once dirname(__FILE__) . '/superglobal_wrapper.php';
include_once dirname(__FILE__) . '/security/record_level_permissions.php';
include_once dirname(__FILE__) . '/security/record_level_permissions_retrieve_strategy.php';
include_once dirname(__FILE__) . '/security/secure_application.php';
include_once dirname(__FILE__) . '/utils/array_utils.php';
include_once dirname(__FILE__) . '/renderers/list_renderer.php';
include_once dirname(__FILE__) . '/html_filter/html_filter.php';
include_once dirname(__FILE__) . '/html_filter/kses_filter.php';

class Application extends SecureApplication implements IVariableContainer
{
    /** @var Application */
    private static $instance = null;

    /** @var Page */
    private $mainPage;
    /** @var AbstractHTTPHandler[] */
    private $httpHandlers;
    /** @var \SuperGlobals */
    private $superGlobals;

    private $htmlFilter;

    /** @var IVariableContainer */
    private $variableContainer;

    public function __construct()
    {
        parent::__construct();
        $this->httpHandlers = array();
        $this->superGlobals = new SuperGlobals();
    }

    #region IVariableContainer implementation
    public function FillVariablesValues(&$values) {
        $values['CURRENT_USER_ID'] = $this->IsCurrentUserLoggedIn() ? $this->GetCurrentUserId() : '';
        $values['CURRENT_USER_NAME'] = $this->IsCurrentUserLoggedIn() ? $this->GetCurrentUser() : '';
        if (function_exists('Global_AddEnvironmentVariablesHandler')) {
            Global_AddEnvironmentVariablesHandler($values);
        }
    }

    /**
     * @return IVariableContainer
     */
    public function getVariableContainer() {
        if (!isset($this->variableContainer))
            $this->variableContainer = new CompositeVariableContainer(
                $this, new ServerVariablesContainer(), new SystemFunctionsVariablesContainer()
            );
        return $this->variableContainer;
    }

    public function GetEnvVar($name) {
        $variables = array();
        $this->getVariableContainer()->FillVariablesValues($variables);
        return $variables[$name];
    }
    #endregion

    #region SuperGlobals delegates

    public function HasPostGetRequestParameters()
    {
        if (count($_POST) == 0 && count($_GET) == 0)
        {
            return false;
        }
        elseif (count($_POST) == 0 && (count($_GET) == 1 && isset($_GET['hname'])))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function GetSuperGlobals()
    {
        return $this->superGlobals;
    }

    public function RefineInputValue($value)
    {
        return $this->superGlobals->RefineInputValue($value);
    }

    public function IsPOSTValueSet($name)
    {
        return $this->superGlobals->IsPostValueSet($name);
    }

    public function GetPOSTValue($name)
    {
        return $this->superGlobals->GetPostValue($name);
    }

    public function IsGETValueSet($name)
    {
        return $this->superGlobals->IsGetValueSet($name);
    }

    public function GetGETValue($name)
    {
        return $this->superGlobals->GetGetValue($name);
    }

    public function IsSessionVariableSet($name)
    {
        return $this->GetSuperGlobals()->IsSessionVariableSet($name);
    }

    public function SetSessionVariable($name, $value)
    {
        $this->GetSuperGlobals()->SetSessionVariable($name, $value);
    }

    public function GetSessionVariable($name)
    {
        return $this->GetSuperGlobals()->GetSessionVariable($name);
    }

    public function UnSetSessionVariable($name)
    {
        $this->GetSuperGlobals()->UnSetSessionVariable($name);
    }

    #endregion

    private function IsHTTPHandlerProcessingRequested()
    {
        return $this->GetSuperGlobals()->IsGetValueSet('hname');
    }

    private function GetRequestedHTTPHandlerName()
    {
        return $this->GetSuperGlobals()->GetGetValue('hname');
    }

    public function Run()
    {
        if ($this->IsHTTPHandlerProcessingRequested())
        {
            $this->ProcessHTTPHandlers();
        }
        else
        {
            $this->mainPage->BeginRender();
            $this->mainPage->EndRender();
        }
    }

    public function SetMainPage(Page $page)
    {
        $this->mainPage = $page;
    }

    public function RegisterHTTPHandler(AbstractHTTPHandler $httpHandler)
    {
        $this->httpHandlers[] = $httpHandler;
    }

    /**
     * @param string $name
     * @return AbstractHTTPHandler|null
     */
    public function GetHTTPHandlerByName($name)
    {
        foreach($this->httpHandlers as $handler) {
            if ($handler->GetName() == $name)
                return $handler;
        }
        return null;
    }

    public function ProcessHTTPHandlers()
    {
        $renderer = new ViewAllRenderer($this->mainPage->GetLocalizerCaptions());
        $HTTPHandler = $this->GetHTTPHandlerByName($this->GetRequestedHTTPHandlerName());
        if (isset($HTTPHandler))
        {
            echo $HTTPHandler->Render($renderer);
        }
    }

    #region Security delegates

    public function GetCurrentUser()
    {
        return $this->GetUserAuthentication()->getCurrentUserName();
    }

    public function GetCurrentUserId()
    {
        return $this->GetUserAuthentication()->getCurrentUserId();
    }

    public function IsCurrentUserLoggedIn()
    {
        return $this->GetUserAuthentication()->isCurrentUserLoggedIn();
    }

    public function GetUserPermissionSet($userName, $dataSourceName)
    {
        return $this->GetUserGrantManager()->GetPermissionSet($userName, $dataSourceName);
    }

    public function HasAdminGrantForCurrentUser()
    {
        return $this->GetUserGrantManager()->HasAdminGrant($this->GetCurrentUser());
    }

    public function HasAdminPanelForCurrentUser()
    {
        return HasAdminPage() && $this->GetUserGrantManager()->HasAdminPanel($this->GetCurrentUser());
    }

    public function IsLoggedInAsAdmin() {
        return $this->HasAdminGrantForCurrentUser();
    }

    /**
     * Get current user permission set
     *
     * @param $dataSourceName
     * @return PermissionSet permission set for specified datasource and current user
     */
    public function GetCurrentUserPermissionSet($dataSourceName)
    {
        $currentUser = $this->GetCurrentUser();
        return $this->GetUserPermissionSet($currentUser, $dataSourceName);
    }

    #endregion

    #region Record level security delegates

    public function GetCurrentUserRecordPermissionsForDataSource($dataSourceName)
    {
        if ($this->GetCurrentUserPermissionSet($dataSourceName)->HasAdminGrant())
            return new AdminRecordPermissions();
        else
            return $this->GetUserRecordPermissionsForDataSource($dataSourceName, $this->GetCurrentUserId());
    }

    public function GetUserRecordPermissionsForDataSource($dataSourceName, $userId)
    {
        return $this->GetDataSourceRecordPermissionRetrieveStrategy()->
        GetUserRecordPermissionsForDataSource($dataSourceName, $userId);
    }

    #endregion

    private $settedOperation = null;

    function SetOperation($value)
    {
        $this->settedOperation = $value;
    }

    public function GetOperation()
    {
        if (isset($this->settedOperation))
            return $this->settedOperation;
        else
        {
            if(isset($_GET[OPERATION_PARAMNAME]))
            {
                return $_GET[OPERATION_PARAMNAME];
            }
            else if (isset($_POST[OPERATION_PARAMNAME]))
            {
                return $_POST[OPERATION_PARAMNAME];
            }
            else
            {
                return OPERATION_VIEWALL;
            }
        }
    }

    /**
     * @return Application
     */
    public static function Instance()
    {
        if (is_null(self::$instance))
            self::$instance = new Application();
        return self::$instance;
    }

    /**
     * return HTMLFilter
     */
    public function getHTMLFilter() {
        if (!$this->htmlFilter) {
            $this->htmlFilter = new KsesHTMLFilter();
        }
        return $this->htmlFilter;
    }
}

/**
 * @return Application
 */
function GetApplication()
{
    return Application::Instance();
}

function GetCurrentUserRecordPermissionsForDataSource($dataSourceName)
{
    return GetApplication()->GetCurrentUserRecordPermissionsForDataSource($dataSourceName);
}
