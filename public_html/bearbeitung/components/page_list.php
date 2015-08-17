<?php

class PageLink {
    private $caption;
    private $link;
    private $hint;
    private $showAsText;
    private $beginNewGroup;
    private $groupName;

    /**
     * @param string $caption
     * @param string $link
     * @param string $hint
     * @param bool $showAsText
     * @param bool $beginNewGroup
     * @param string $groupName
     */
    public function __construct($caption, $link, $hint = '', $showAsText = false, $beginNewGroup = false, $groupName = '') {
        $this->caption = $caption;
        $this->link = $link;
        $this->hint = $hint;
        $this->showAsText = $showAsText;
        $this->beginNewGroup = $beginNewGroup;
        $this->groupName = $groupName;
    }

    public function GetGroupName() {
        return $this->groupName;
    }

    public function GetBeginNewGroup() {
        return $this->beginNewGroup;
    }

    public function GetCaption() {
        return $this->caption;
    }

    public function GetHint() {
        return $this->hint;
    }

    public function GetShowAsText() {
        return $this->showAsText;
    }

    public function GetLink() {
        return $this->link;
    }

    public function GetViewData() {
        return array(
            'Caption' => $this->GetCaption(),
            'Hint' => $this->GetHint(),
            'IsCurrent' => $this->GetShowAsText(),
            'Href' => $this->GetLink(),
            'BeginNewGroup' => $this->GetBeginNewGroup(),
            'GroupName' => $this->GetGroupName()
        );
    }
}

class PageList {
    /**
     * @var PageLink[]
     */
    private $pages;
    private $currentPageOptions;
    private $currentPageRss;
    private $groups;

    public function __construct($parentPage) {
        $this->parentPage = $parentPage;
        $this->pages = array();
        $this->currentPageOptions = array();
        $this->currentPageRss = null;
        $this->groups = array();
    }

    /**
     * @return Page
     */
    public function GetParentPage() {
        return $this->parentPage;
    }

    /**
     * @param PageLink $page
     */
    public function AddPage($page) {
        $this->pages[] = $page;
    }

    /**
     * @return PageLink[]
     */
    public function GetPages() {
        return $this->pages;
    }

    /**
     * @param string $group
     */
    public function AddGroup($group) {
        $this->groups[] = $group;
    }

    public function GetGroups() {
        return $this->groups;
    }

    public function GetVisibleGroups() {
        $result = array();
        foreach ($this->groups as $group) {
            foreach ($this->pages as $page) {
                if ($page->GetGroupName() == $group) {
                    $result[] = $group;
                    break;
                }
            }
        }
        return $result;
    }

    public function Accept(Renderer $renderer) {
        $renderer->RenderPageList($this);
    }

    public function AddRssLinkForCurrentPage($rssLink) {
        $this->currentPageRss = $rssLink;
    }

    public function AddCurrentPageOption($groupName, $optionsCaption, $linkClass, $link) {
        if (!isset($this->currentPageOptions)) {
            $this->currentPageOptions[$groupName] = array();
        }
        $this->currentPageOptions[$groupName][] = array(
            'Caption' => $optionsCaption,
            'LinkClass' => $linkClass,
            'Href' => $link
        );
    }

    public function GetPagesViewData() {
        $result = array();
        foreach ($this->GetPages() as $page) {
            $result[] = $page->GetViewData();
        }
        return $result;
    }

    public function GetViewData() {
        return array(
            'Pages' => $this->GetPagesViewData(),
            'RSSLink' => $this->currentPageRss,
            'Groups' => $this->GetVisibleGroups()
            // 'Groups' => $this->GetGroups()
        );
    }
}
