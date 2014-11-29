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

include_once dirname(__FILE__) . '/' . 'authorization.php';

include_once dirname(__FILE__) . '/' . 'components/application.php';
include_once dirname(__FILE__) . '/' . 'components/captions.php';
include_once dirname(__FILE__) . '/' . 'components/error_utils.php';

include_once dirname(__FILE__) . '/' . 'components/utils/system_utils.php';
include_once dirname(__FILE__) . '/' . 'components/utils/string_utils.php';

include_once dirname(__FILE__) . '/' . 'components/security/base_user_auth.php';
include_once dirname(__FILE__) . '/' . 'components/security/table_based_user_grants_manager.php';

SetUpUserAuthorization();

class AdminPanelView
{
    private $localizerCaptions = null;

    /** @var TableBasedUserGrantsManager */
    private $tableBasedGrantsManager;

    public function __construct($tableBasedGrantsManager)
    {
        $this->tableBasedGrantsManager = $tableBasedGrantsManager;
    }

    public function GetContentEncoding()
    {
        return 'UTF-8';
    }

    public function GetLocalizerCaptions()
    {
        if (!isset($this->localizerCaptions))
            $this->localizerCaptions = new Captions($this->GetContentEncoding());
        return $this->localizerCaptions;
    }

    public function RenderText($text)
    {
        return ConvertTextToEncoding($text, GetAnsiEncoding(), $this->GetContentEncoding());
    }

    public function GetHeader()
    {
        return GetPagesHeader();
    }

    public function GetFooter()
    {
        return GetPagesFooter();
    }

    public function Render()
    {
        include_once 'libs/smartylibs/Smarty.class.php';
                
        $smarty = new Smarty();
        $smarty->template_dir = 'components/templates';
        $smarty->assign_by_ref('Page', $this);

        $users = $this->tableBasedGrantsManager->GetAllUsersAsJson();
        $smarty->assign_by_ref('Users', $users);

        $localizerCaptions = $this->GetLocalizerCaptions();
        $smarty->assign_by_ref('Captions', $localizerCaptions);

        /* $roles = $this->tableBasedGrantsManager->GetAllRolesAsJson();
        $smarty->assign_by_ref('Roles', $roles); */

        $headerString = 'Content-Type: text/html';
        if ($this->GetContentEncoding() != null)
            StringUtils::AddStr($headerString, 'charset=' . $this->GetContentEncoding(), ';');
        header($headerString);

        $pageInfos = GetPageInfos();
        $pageListViewData = array(
            'Pages' => array(),
            'CurrentPageOptions' => array());
        foreach($pageInfos as $pageInfo)
            $pageListViewData['Pages'][] =
                array(
                    'Caption' => $this->RenderText($pageInfo['caption']),
                    'Hint' => $this->RenderText($pageInfo['short_caption']),
                    'Href' => $pageInfo['filename'],
                    'GroupName' => $pageInfo['group_name'],
                    'BeginNewGroup' => $pageInfo['add_separator'] 
                );
        $pageListViewData['Groups'] = GetPageGroups();

        $smarty->assign_by_ref('PageList', $pageListViewData);
        $authenticationViewData =  $this->GetAuthenticationViewData();
        $smarty->assign_by_ref('Authentication', $authenticationViewData);
        
        $smarty->display('admin_panel.tpl');
    }

    public function GetAuthenticationViewData() {
        return array(
            'Enabled' => true,
            'LoggedIn' => GetApplication()->IsCurrentUserLoggedIn(),
            'CurrentUser' => array(
                'Name' => GetApplication()->GetCurrentUser(),
                'Id' => GetApplication()->GetCurrentUserId(),
            )
        );
    }

    public function GetCommonViewData() {
        return array(
            'ContentEncoding' => $this->GetContentEncoding()
        );
    }

}

$tableBasedGrants = CreateTableBasedGrantsManager();

$view = new AdminPanelView($tableBasedGrants);

if (!GetApplication()->GetUserAuthorizationStrategy()->HasAdminGrant(GetApplication()->GetCurrentUser()))
{
    RaiseSecurityError($view, 'You do not have permission to access this page.');
}

$view->Render();
