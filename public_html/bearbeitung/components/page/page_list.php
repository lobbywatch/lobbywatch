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

    /**
     * @param string $caption
     * @param string $link
     * @param string $hint
     * @param bool $showAsText
     * @param bool $beginNewGroup
     * @param string $groupName
     * @param string $description
     * @param string $target
     */
    public function __construct($caption, $link, $hint = '', $showAsText = false, $beginNewGroup = false, $groupName = '', $description = '', $classAttribute = '', $target = null)
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

class PageList {
    const TYPE_SIDEBAR = 'type_sidebar';
    const TYPE_MENU = 'type_menu';

    /**
     * @var PageLink[]
     */
    private $pages;
    private $currentPageOptions;
    private $currentPageRss;
    private $groups;

    public function __construct($parentPage)
    {
        $this->parentPage = $parentPage;
        $this->pages = array();
        $this->currentPageOptions = array();
        $this->currentPageRss = null;
        $this->groups = array();
    }

    /**
     * @param CommonPage $page
     *
     * @return PageList
     */
    public static function createForPage(CommonPage $page)
    {
        $currentPageFilename = $page->GetPageFileName();
        $pageList = new PageList($page);

        $pageGroups = GetPageGroups();
        foreach ($pageGroups as $group) {
            $pageList->AddGroup($page->RenderText($group));
        }

        $pageInfos = GetPageInfos();
        foreach($pageInfos as $pageInfo) {
            if (!GetCurrentUserGrantForDataSource($pageInfo['name'])->HasViewGrant()) {
                continue;
            }

            $groupName = $page->RenderText($pageInfo['group_name']);
            if (!$pageList->hasGroup($groupName)) {
                $pageList->AddGroup($groupName);
            }

            $shortCaption = $page->RenderText($pageInfo['short_caption']);
            $pageList->AddPage(new PageLink(
                $page->RenderText($pageInfo['caption']),
                $pageInfo['filename'],
                $shortCaption,
                $currentPageFilename == $pageInfo['filename'],
                $pageInfo['add_separator'],
                $page->RenderText($pageInfo['group_name']),
                isset($pageInfo['description']) ? $page->RenderText($pageInfo['description']) : '',
                isset($pageInfo['class_attribute']) ? $pageInfo['class_attribute'] : ''
            ));
        }

        if (function_exists('Global_GetCustomPageList')) {
            Global_GetCustomPageList($page, $pageList);
        }

        return $pageList;
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
     * @param string $group
     */
    public function AddGroup($group)
    {
        $this->groups[] = $group;
    }

    /**
     * @param string $group
     * @param int $index
     */
    public function addGroupAt($group, $index)
    {
        array_splice($this->groups, $index, 0, $group);
    }

    /**
     * @param string $group
     *
     * @return boolean
     */
    public function hasGroup($group)
    {
        return in_array($group, $this->groups);
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
                if ($page->GetGroupName() == $group)
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
            // 'Groups' => $this->GetGroups()
        );
    }
}
