<?php

class PageLink {
    private $caption;
    private $link;
    private $hint;
    private $showAsText;
    private $beginNewGroup;
    private $groupName;
    private $description;
    private $classAttribute;
    private $target;
    private $pageId;

    /**
     * @param string $caption
     * @param string $link
     * @param string $hint
     * @param bool $showAsText
     * @param bool $beginNewGroup
     * @param string $groupName
     * @param string $description
     * @param string $target
     * @param string $pageId
     */
    public function __construct($caption, $link, $hint = '', $showAsText = false, $beginNewGroup = false, $groupName = '', $description = '', $classAttribute = '', $target = null, $pageId = '')
    {
        $this->caption = $caption;
        $this->link = $link;
        $this->hint = $hint;
        $this->showAsText = $showAsText;
        $this->beginNewGroup = $beginNewGroup;
        $this->groupName = $groupName;
        $this->description = $description;
        $this->classAttribute = $classAttribute;
        $this->target = $target;
        $this->pageId = $pageId;
    }

    public function GetGroupName()
    {
        return $this->groupName;
    }

    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;

        return $this;
    }

    public function GetBeginNewGroup()
    {
        return $this->beginNewGroup;
    }

    public function SetBeginNewGroup($beginNewGroup)
    {
        $this->beginNewGroup = $beginNewGroup;

        return $this;
    }

    public function GetCaption()
    {
        return $this->caption;
    }

    public function SetCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    public function GetHint()
    {
        return $this->hint;
    }

    public function SetHint($hint)
    {
        $this->hint = $hint;

        return $this;
    }

    public function GetShowAsText()
    {
        return $this->showAsText;
    }

    public function SetShowAsText($showAsText)
    {
        $this->showAsText = $showAsText;

        return $this;
    }

    public function GetLink()
    {
        return $this->link;
    }

    public function SetLink($link)
    {
        $this->link = $link;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function SetDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getClassAttribute()
    {
        return $this->classAttribute;
    }

    public function SetClassAttribute($classAttribute)
    {
        $this->classAttribute = $classAttribute;

        return $this;
    }

    /**
     * @param string $target
     *
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string $pageId
     *
     * @return $this
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;

        return $this;
    }

    /**
     * @return string
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    public function GetViewData()
    {
        return array(
            'Caption' => $this->GetCaption(),
            'Hint' => $this->GetHint(),
            'IsCurrent' => $this->GetShowAsText(),
            'Href' => $this->GetLink(),
            'BeginNewGroup' => $this->GetBeginNewGroup(),
            'GroupName' => $this->GetGroupName(),
            'Description' => $this->getDescription(),
            'ClassAttribute' => $this->getClassAttribute(),
            'Target' => $this->getTarget(),
        );
    }
}

class PageGroup {

    private $caption;
    private $description;

    function __construct($caption, $description = '') {
        $this->caption = $caption;
        $this->description = $description;
    }

    public function getCaption() {
        return $this->caption;
    }

    public function setCaption($caption) {
        $this->caption = $caption;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }
}

class PageList {
    const TYPE_SIDEBAR = 'type_sidebar';
    const TYPE_MENU = 'type_menu';

    /** @var CommonPage */
    private $parentPage;
    /** @var PageLink[] */
    private $pages;
    private $currentPageOptions;
    private $currentPageRss;
    /** @var PageGroup[] */
    private $groups;

    public function __construct($parentPage)
    {
        $this->parentPage = $parentPage;
        $this->pages = array();
        $this->currentPageOptions = array();
        $this->currentPageRss = null;
        $this->groups = array();
        if ($this->parentPage) {
            $this->createMenu();
        }
    }

    private function createMenu() {
        $currentPageFilename = $this->parentPage->GetPageFileName();

        $pageGroups = GetPageGroups();
        foreach ($pageGroups as $group) {
            $this->addGroupEx(new PageGroup($group['caption'], $group['description']));
        }

        $pageInfos = GetPageInfos();
        foreach($pageInfos as $pageInfo) {
            if (!GetCurrentUserPermissionsForPage($pageInfo['name'])->HasViewGrant()) {
                continue;
            }

            $groupName = $pageInfo['group_name'];
            if (!$this->hasGroup($groupName)) {
                $this->AddGroup($groupName);
            }

            $shortCaption = $pageInfo['short_caption'];
            $this->AddPage(new PageLink(
                $pageInfo['caption'],
                $pageInfo['filename'],
                $shortCaption,
                $currentPageFilename == $pageInfo['filename'],
                $pageInfo['add_separator'],
                $pageInfo['group_name'],
                isset($pageInfo['description']) ? $pageInfo['description'] : '',
                isset($pageInfo['class_attribute']) ? $pageInfo['class_attribute'] : '',
                null,
                $pageInfo['name']
            ));
        }

        if (function_exists('Global_GetCustomPageList')) {
            Global_GetCustomPageList($this->parentPage, $this);
        }
    }

    /**
     * @return bool
     */
    public function isTypeMenu()
    {
        return GetPageListType() === self::TYPE_MENU;
    }

    /**
     * @return bool
     */
    public function isTypeSidebar()
    {
        return GetPageListType() === self::TYPE_SIDEBAR;
    }

    /**
     * @return Page
     */
    public function GetParentPage()
    {
        return $this->parentPage;
    }

    /**
     * @param PageLink $page
     */
    public function AddPage(PageLink $page)
    {
        $this->pages[] = $page;
    }

    /**
     * @param string $pageId
     * @return $this
     */
    public function removePage($pageId)
    {
        foreach ($this->pages as $key => $page) {
            if ($page->getPageId() == $pageId) {
                unset($this->pages[$key]);
                break;
            }
        }

        return $this;
    }

    /**
     * @param PageLink $page
     * @param int $index
     */
    public function addPageAt(PageLink $page, $index)
    {
        array_splice($this->pages, $index, 0, array($page));
    }

    /**
     * @return PageLink[]
     */
    public function GetPages()
    {
        return $this->pages;
    }

    /**
     * @param string $caption
     */
    public function AddGroup($caption)
    {
        $this->addGroupEx(new PageGroup($caption));
    }

    /**
     * @param PageGroup $group
     */
    public function addGroupEx($group)
    {
        $this->groups[] = $group;
    }

    /**
     * @param string $caption
     * @param int $index
     */
    public function addGroupAt($caption, $index)
    {
        array_splice($this->groups, $index, 0, array(new PageGroup($caption)));
    }

    /**
     * @param string $caption
     *
     * @return boolean
     */
    public function hasGroup($caption)
    {
        $result = false;
        foreach ($this->groups as $group) {
            $result = ($group->getCaption() == $caption);
            if ($result) {
                break;
            }
        }
        return $result;
    }

    public function GetGroups()
    {
        return $this->groups;
    }

    public function GetVisibleGroups()
    {
        $result = array();
        foreach ($this->groups as $group)
        {
            foreach ($this->pages as $page)
            {
                if ($page->GetGroupName() == $group->getCaption())
                {
                    $result[] = $group;
                    break;
                }
            }
        }
        return $result;
    }

    public function Accept(Renderer $renderer)
    {
        $renderer->RenderPageList($this);
    }

    public function AddRssLinkForCurrentPage($rssLink)
    {
        $this->currentPageRss = $rssLink;
    }

    public function AddCurrentPageOption($groupName, $optionsCaption, $linkClass, $link)
    {
        if (!isset($this->currentPageOptions))
        {
            $this->currentPageOptions[$groupName] = array();
        }
        $this->currentPageOptions[$groupName][] = array(
            'Caption' => $optionsCaption,
            'LinkClass' => $linkClass,
            'Href' => $link
        );
    }

    public function GetPagesViewData()
    {
        $result = array();
        foreach ($this->GetPages() as $page)
        {
            $result[] = $page->GetViewData();
        }
        return $result;
    }

    public function GetViewData()
    {
        return array(
            'Pages' => $this->GetPagesViewData(),
            'RSSLink' => $this->currentPageRss,
            'Groups' => $this->GetVisibleGroups()
        );
    }
}
