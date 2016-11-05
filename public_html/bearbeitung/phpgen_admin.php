<?php
// Processed by afterburner.sh



/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */
include_once dirname(__FILE__) . '/components/startup.php';
include_once dirname(__FILE__) . '/authorization.php';
include_once dirname(__FILE__) . '/components/error_utils.php';
include_once dirname(__FILE__) . '/components/utils/system_utils.php';
include_once dirname(__FILE__) . '/components/utils/string_utils.php';
include_once dirname(__FILE__) . '/components/security/base_user_auth.php';
include_once dirname(__FILE__) . '/components/security/grant_manager/table_based_user_grant_manager.php';

SetUpUserAuthorization();

class AdminPage extends CommonPage
{
    /** @var TableBasedUserGrantManager */
    private $tableBasedGrantManager;

    public function __construct($tableBasedGrantsManager)
    {
        parent::__construct('Admin panel', 'UTF-8');
        $this->tableBasedGrantManager = $tableBasedGrantsManager;
    }

    public function GetAllUsersAsJson()
    {
        return $this->tableBasedGrantManager->GetAllUsersAsJson();
    }

    public function GetAuthenticationViewData() {
        return array(
            'Enabled' => true,
            'LoggedIn' => GetApplication()->IsCurrentUserLoggedIn(),
            'CurrentUser' => array(
                'Name' => GetApplication()->GetCurrentUser(),
                'Id' => GetApplication()->GetCurrentUserId(),
            ),
            'isAdminPanelVisible' => GetApplication()->HasAdminPanelForCurrentUser(),
            'canManageUsers' => GetApplication()->HasAdminGrantForCurrentUser(),
        );
    }

    public function GetReadyPageList()
    {
        return PageList::createForPage($this);
    }

    public function Accept(Renderer $renderer)
    {
        $renderer->RenderAdminPage($this);
    }

    private function GetCurrentPageMode() {
        switch (GetApplication()->GetOperation()) {
            case OPERATION_VIEWALL:
                return PageMode::ViewAll;
        }
        return null;
    }

    public function GetCustomTemplate($part, $mode, $defaultValue, &$params = null)
    {
        return parent::GetCustomTemplate(
            $part,
            $mode ? $mode : $this->GetCurrentPageMode(),
            $defaultValue,
            $params
        );
    }

    public function GetShowPageList()
    {
        return true;
    }

    public function GetTitle()
    {
        return $this->GetLocalizerCaptions()->GetMessageString('AdminPage');
    }

    public function GetPageFileName() {
        return basename(__FILE__);
    }

    public function getType()
    {
        return PageType::Admin;
    }

}

$tableBasedGrants = CreateTableBasedGrantManager();

$page = new AdminPage($tableBasedGrants);
$page->setHeader(GetPagesHeader());
$page->setFooter(GetPagesFooter());
$page->OnGetCustomTemplate->AddListener('Global_GetCustomTemplateHandler');
$page->OnCustomHTMLHeader->AddListener('Global_CustomHTMLHeaderHandler');

if (!GetApplication()->HasAdminPanelForCurrentUser()) {
    RaiseSecurityError($page, 'You do not have permission to access this page.');
}


$renderer = new ViewRenderer($page->GetLocalizerCaptions());
echo $renderer->Render($page);
