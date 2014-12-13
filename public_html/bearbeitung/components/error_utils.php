<?php

// require_once 'libs/smartylibs/Smarty.class.php';
// require_once 'components/renderers/list_renderer.php';
// require_once 'components/captions.php';

include_once dirname(__FILE__) . '/' . '../libs/smartylibs/Smarty.class.php';
include_once dirname(__FILE__) . '/' . 'renderers/list_renderer.php';
include_once dirname(__FILE__) . '/' . 'captions.php';

function RaiseError($message = '')
{
    @session_destroy();
    throw new Exception($message);
}

function RaiseCannotRetrieveSingleRecordError() {
    RaiseError('Cannot retrieve single record. Check the primary key fields.');
}

/**
 * @param Page $parentPage
 * @param string $message
 */
function ShowSecurityErrorPage($parentPage, $message)
{
    $renderer = new ViewAllRenderer($parentPage->GetLocalizerCaptions());
    $errorPage = new CustomErrorPage($parentPage, $parentPage->GetLocalizerCaptions()->GetMessageString('AccessDenied'), $message,
        sprintf(
            $parentPage->GetLocalizerCaptions()->GetMessageString('AccessDeniedErrorSuggesstions'),
            'login.php'
            ));
    echo $renderer->Render($errorPage);
}

function RaiseSecurityError($parentPage, $message = '')
{
    @session_destroy();
    ShowSecurityErrorPage($parentPage, $message);
    exit;
}

function ShowErrorPage($message)
{
    $smarty = new Smarty();
    $smarty->template_dir = '/components/templates';
    $smarty->assign('Message', $message);
    $captions = GetCaptions('UTF-8');
    $smarty->assign('Captions', $captions);
    $smarty->assign('App', array(
        'ContentEncoding' => 'UTF-8',
        'PageCaption' => $captions->GetMessageString('Error')
    ));
    $smarty->display('error_page.tpl');
}

class CustomErrorPage
{
    private $parentPage;
    private $caption;
    private $message;
    private $description;

    /**
     * @param Page $parentPage
     * @param string $caption
     * @param string $message
     * @param string $description
     */
    public function __construct($parentPage, $caption, $message, $description)
    {
        $this->parentPage = $parentPage;
        $this->caption = $caption;
        $this->message = $message;
        $this->description = $description;
    }

    public function GetPageDirection()
    {
        return null;
    }

    public function GetOnPageLoadedClientScript()
    {
        return '';
    }

    public function GetCustomClientScript()
    {
        return '';
    }

    public function GetCaption() { return $this->caption; }
    public function GetCustomPageHeader() { return ''; }
    public function GetContentEncoding() { return $this->parentPage->GetContentEncoding(); }

    public function GetHeader() { return $this->parentPage->GetHeader(); }
    public function GetFooter() { return $this->parentPage->GetFooter(); }

    public function GetMessage() { return $this->message; }
    public function GetDescription() { return $this->description; }

    public function GetViewData() {
        $result = array(
            'CustomHtmlHeadSection' => '',
            'PageCaption' => $this->GetCaption()
        );
        if ($this->parentPage) {
            $result = array_merge($this->parentPage->GetCommonViewData(), $result);
        }
        return $result;
    }

    public function GetAuthenticationViewData() {
        return $this->parentPage->GetAuthenticationViewData();
    }

    /**
     * @param Renderer $renderer
     */
    public function Accept($renderer)
    {
        $renderer->RenderCustomErrorPage($this);
    }

    public function GetValidationScripts()
    {
        return '';
    }
}
