<?php

include_once dirname(__FILE__) . '/../libs/smartylibs/Smarty.class.php';
include_once dirname(__FILE__) . '/renderers/list_renderer.php';
include_once dirname(__FILE__) . '/page/common_page.php';
include_once dirname(__FILE__) . '/captions.php';

function RaiseError($message = '', $destroySession = true)
{
    if ($destroySession) {
        @session_destroy();
    }
    throw new Exception($message);
}

function RaiseCannotRetrieveSingleRecordError() {
    RaiseError('Cannot retrieve single record. Check the primary key fields.', false);
}

/**
 * @param Page $parentPage
 * @param string $message
 */
function ShowSecurityErrorPage($parentPage, $message)
{
    $urlToRedirect = '';
    if ($parentPage instanceof Page) {
        $linkBuilder = $parentPage->CreateLinkBuilder();
        GetApplication()->GetSuperGlobals()->fillGetParams($linkBuilder);
        $urlToRedirect = '?redirect='.urlencode($linkBuilder->GetLink());
    }

    $renderer = new ViewAllRenderer($parentPage->GetLocalizerCaptions());

    $errorPage = new CustomErrorPage(
        $parentPage->GetLocalizerCaptions()->GetMessageString('AccessDenied'),
        $parentPage->GetContentEncoding(),
        $message,
        sprintf($parentPage->GetLocalizerCaptions()->GetMessageString('AccessDeniedErrorSuggestions'),
            'login.php'.$urlToRedirect),
        $parentPage
    );

    $errorPage->OnCustomHTMLHeader->AddListener('Global_CustomHTMLHeaderHandler');

    echo $renderer->Render($errorPage);
}

function RaiseSecurityError($parentPage, $message = '')
{
    @session_destroy();
    ShowSecurityErrorPage($parentPage, $message);
    exit;
}

function ShowErrorPage(Exception $exception)
{
    $smarty = new Smarty();
    $smarty->template_dir = '/components/templates';

    $captions = Captions::getInstance('UTF-8');
    $smarty->assign('Captions', $captions);
    $smarty->assign('Message', $exception->getMessage());

    $displayDebugInfo = DebugUtils::GetDebugLevel();
    $smarty->assign('DisplayDebugInfo', $displayDebugInfo);
    if ($displayDebugInfo == 1) {
        $smarty->assign('File', $exception->getFile());
        $smarty->assign('Line', $exception->getLine());
        $smarty->assign('Trace', $exception->getTraceAsString());
    }

    $common = new CommonPageViewData();
    $common
        ->setTitle($captions->GetMessageString('Error'))
        ->setHeader(GetPagesHeader())
        ->setFooter(GetPagesFooter());

    $smarty->assign('common', $common);
    $smarty->display('error_page.tpl');
}

class CustomErrorPage extends CommonPage
{
    private $parentPage;
    private $message;
    private $description;

    /**
     * @param string $title
     * @param string $contentEncoding
     * @param string $message
     * @param string $description
     * @param Page   $parentPage
     */
    public function __construct($title, $contentEncoding, $message, $description, $parentPage)
    {
        parent::__construct($title, $contentEncoding);
        $this->parentPage = $parentPage;
        $this->message = $message;
        $this->description = $description;
    }

    public function GetContentEncoding() { return $this->parentPage->GetContentEncoding(); }
    public function GetHeader() { return $this->parentPage->GetHeader(); }
    public function GetFooter() { return $this->parentPage->GetFooter(); }

    public function GetMessage() { return $this->message; }
    public function GetDescription() { return $this->description; }

    public function GetCommonViewData() {
        return $this->parentPage->GetCommonViewData()
            ->setCustomHead($this->GetCustomPageHeader())
            ->setTitle($this->GetTitle());
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

    public function GetReadyPageList()
    {
        return null;
    }

    public function GetPageFileName() {
        return '';
    }

    public function GetTitle() {
        return $this->GetLocalizerCaptions()->GetMessageString('Error');
    }

    public function getType()
    {
        return PageType::Data;
    }

    public function getLink()
    {
        return $this->parentPage->getLink();
    }
}
