<?php

include_once dirname(__FILE__) . '/' . 'page.php';
include_once dirname(__FILE__) . '/' . 'common.php';
include_once dirname(__FILE__) . '/' . 'superglobal_wrapper.php';
include_once dirname(__FILE__) . '/' . 'security/base_user_auth.php';
include_once dirname(__FILE__) . '/' . 'security/record_level_permissions.php';
include_once dirname(__FILE__) . '/' . 'security/record_level_permissions_retrieve_strategy.php';
include_once dirname(__FILE__) . '/' . 'security/secure_application.php';

include_once dirname(__FILE__) . '/' . 'utils/array_utils.php';
include_once dirname(__FILE__) . '/' . 'renderers/list_renderer.php';

include_once dirname(__FILE__) . '/' . 'utils/less_utils.php';

class Application extends SecureApplication implements IVariableContainer
{
    /**
     * @var Application
     */
    private static $instance = null;

    /** @var Page */
    private $mainPage;
    /** @var HTTPHandler[] */
    private $httpHandlers;
    /** @var \SuperGlobals */
    private $superGlobals;
    /** @var boolean */
    private $enableLessRunTimeCompile;
    /** @var boolean */
    private $canUserChangeOwnPassword;

    #region IVariableContainer implementation
    private $variableFuncs = array(
        'CURRENT_USER_ID'   => 'return $app->IsCurrentUserLoggedIn() ? $app->GetCurrentUserId() : \'\';',
        'CURRENT_USER_NAME' => 'return $app->IsCurrentUserLoggedIn() ? $app->GetCurrentUser() : \'\';'
        );

    public function FillVariablesValues(&$values)
    {
        $values = array();
        foreach($this->variableFuncs as $name => $code)
        {
            $function = create_function('$app', $code);
            $values[$name] = $function($this);
        }
    }

    public function FillAvailableVariables(&$variables)
    {
        return array_keys($this->variableFuncs);
    }    
    #endregion

    public function __construct()
    {
        parent::__construct();
        session_start();
        $this->enableLessRunTimeCompile = false;
        $this->canUserChangeOwnPassword = false;
        $this->httpHandlers = array();
        $this->superGlobals = new SuperGlobals();
    }

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
        if ($this->enableLessRunTimeCompile)
            // TODO check if the ./css folder is writable
            autoCompileLess(dirname(__FILE__) .'/'.'css/main.less', dirname(__FILE__) .'/'.'css/main.css');

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

    public function RegisterHTTPHandler(HTTPHandler $httpHandler)
    {
        $this->httpHandlers[] = $httpHandler;
    }

    /**
     * @param string $name
     * @return HTTPHandler
     */
    public function GetHTTPHandlerByName($name)
    {
        return ArrayUtils::Find(
            $this->httpHandlers,
            create_function('$handler', "return \$handler->GetName() == '$name';")
            );
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
        return $this->GetUserAuthorizationStrategy()->GetCurrentUser();
    }

    public function HasAdminGrantForCurrentUser()
    {
        return $this->GetUserAuthorizationStrategy()->HasAdminGrant($this->GetCurrentUser());
    }

    public function GetCurrentUserId()
    {
        return $this->GetUserAuthorizationStrategy()->GetCurrentUserId();
    }

    public function IsCurrentUserLoggedIn()
    {
        return $this->GetUserAuthorizationStrategy()->IsCurrentUserLoggedIn();
    }

    public function GetUserRoles($userName, $dataSourceName)
    {
        return $this->GetUserAuthorizationStrategy()->GetUserRoles($userName, $dataSourceName);
    }

    /**
     * GetCurrentUserGrants
     *
     * @param $dataSourceName
     * @return DataSourceSecurityInfo security info for specified datasource and current user
     */
    public function GetCurrentUserGrants($dataSourceName)
    {
        $currentUser = $this->GetCurrentUser();
        return $this->GetUserRoles($currentUser, $dataSourceName);
    }
    
    #endregion

    #region Record level security delegates

    public function GetCurrentUserRecordPermissionsForDataSource($dataSourceName)
    {
        if ($this->GetCurrentUserGrants($dataSourceName)->AdminGrant())
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
     * @param boolean $value
     */
    public function SetEnableLessRunTimeCompile($value)
    {
        $this->enableLessRunTimeCompile = $value;
    }

    /**
     * @param boolean $value
     */
    public function SetCanUserChangeOwnPassword($value)
    {
        $this->canUserChangeOwnPassword = $value;
    }

    /**
     * @return bool
     */
    public function CanUserChangeOwnPassword()
    {
        return $this->canUserChangeOwnPassword;
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
}

/**
 * @return Application
 */
function GetApplication()
{
    return Application::Instance();
}
