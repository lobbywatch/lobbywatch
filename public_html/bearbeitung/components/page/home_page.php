<?php

include_once dirname(__FILE__) . '/common_page.php';

class HomePage extends CommonPage
{
    /** @var string */
    private $selectedGroup;

    /** @var string */
    private $banner;

    public function __construct($grants, $contentEncoding)
    {
        parent::__construct('', $contentEncoding);
        $this->selectedGroup = ArrayWrapper::createGetWrapper()->getValue('group');

        if (function_exists('GetHomePageBanner')) {
            $this->banner = GetHomePageBanner();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function GetPageFileName()
    {
        return basename(__FILE__);
    }

    function GetTitle() {
        return (is_null($this->selectedGroup))
            ? $this->GetLocalizerCaptions()->getMessageString('HomePage')
            : $this->selectedGroup;
    }

    public function getSelectedGroup()
    {
        return $this->selectedGroup;
    }

    public function Accept(Renderer $renderer)
    {
        $renderer->RenderHomePage($this);
    }

    public function GetAuthenticationViewData() {
        return array(
            'Enabled' => GetApplication()->isAuthenticationEnabled(),
            'LoggedIn' => GetApplication()->IsCurrentUserLoggedIn(),
            'CurrentUser' => array(
                'Name' => GetApplication()->GetCurrentUser(),
                'Id' => GetApplication()->GetCurrentUserId(),
            ),
            'CanChangeOwnPassword' => GetApplication()->GetUserAuthentication()->canUserChangeOwnPassword(),
            'isAdminPanelVisible' => GetApplication()->HasAdminPanelForCurrentUser(),
        );
    }

    public function getType()
    {
        return PageType::Home;
    }

    public function getLink()
    {
        return GetHomeURL();
    }

    public function setBanner($value)
    {
        $this->banner = $value;
    }

    public function getBanner()
    {
        return $this->banner;
    }

}
