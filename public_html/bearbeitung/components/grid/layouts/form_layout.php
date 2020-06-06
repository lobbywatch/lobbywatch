<?php

include_once dirname(__FILE__) . '/form_layout_mode.php';
include_once dirname(__FILE__) . '/form_layout_sheet.php';
include_once dirname(__FILE__) . '/form_layout_group.php';
include_once dirname(__FILE__) . '/form_layout_row.php';

class FormLayout extends FormLayoutSheet
{
    /**
     * @var bool
     */
    public $tabsEnabled = false;

    /**
     * @var string
     */
    private $tabsStyle = FormTabsStyle::TABS;

    /**
     * @var FormLayoutSheet[]
    */
    private $tabs = array();

    /**
     * @param string $name
     * @return FormLayoutSheet
     */
    public function addTab($name) {
        $tab = new FormLayoutSheet($this->getMode(), $name);
        $this->tabs[] = $tab;

        return $tab;
    }

    /**
     * @return array
     */
    public function getColumnNames()
    {
        if (!$this->tabsEnabled) {
            return parent::getColumnNames();
        };

        $names = array();
        foreach ($this->tabs as $tab) {
            $sheetColumnNames = $tab->getColumnNames();
            $names = array_merge($names, $sheetColumnNames);
        }
        return $names;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        if (!$this->tabsEnabled) {
            return parent::getColumns();
        };

        $columns = array();
        foreach ($this->tabs as $tab) {
            $tabColumns = $tab->getColumns();
            $columns = array_merge($columns, $tabColumns);
        }
        return $columns;
    }

    /**
     * @return FormLayoutGroup[]
     */
    public function getGroups()
    {
        if (!$this->tabsEnabled) {
            return parent::getGroups();
        };

        $groups = array();
        foreach ($this->tabs as $tab) {
            $sheetGroups = $tab->getGroups();
            $groups = array_merge($groups, $sheetGroups);
        }
        return $groups;
    }

    /**
     * @return FormLayoutSheet[]
     */
    public function getTabs() {
        return $this->tabs;
    }

    /**
     * @return FormLayoutSheet[]
     */
    public function getNonEmptyTabs() {
        $nonEmptyTabs = array();
        foreach ($this->tabs as $tab) {
            if (count($tab->getColumns()) > 0) {
                $nonEmptyTabs[] = $tab;
            }
        }
        return $nonEmptyTabs;
    }

    /**
     * @param string $tabsStyle
     */
    public function enableTabs($tabsStyle = FormTabsStyle::TABS) {
        $this->tabsEnabled = true;
        $this->tabsStyle = $tabsStyle;
    }

    public function disableTabs() {
        $this->tabsEnabled = false;
    }

    /**
     * @return bool
     */
    public function tabsEnabled() {
        return $this->tabsEnabled;
    }

    /**
     * @param string $tabsStyle
     */
    public function setTabsStyle($tabsStyle) {
        $this->tabsStyle = $tabsStyle;
    }

    /**
     * @return string
     */
    public function getTabsStyle() {
        return $this->tabsStyle;
    }

    /** @return string */
    public function getTabbedNavigationStyle() {
        $result = '';
        if ($this->tabsEnabled) {
            if (strpos($this->tabsStyle, 'tabs') !== false) {
                $result = 'nav-tabs';
            } else {
                $result = 'nav-pills';
            }
            if (strpos($this->tabsStyle, 'justified') !== false) {
                $result.= ' nav-justified';
            } elseif (strpos($this->tabsStyle, 'stacked') !== false) {
                $result.= ' nav-stacked';
            }
        }
        return $result;
    }

    /** @return string */
    public function getTabType() {
        if (strpos($this->tabsStyle, 'tab') !== false) {
            return 'tab';
        } else {
            return 'pill';
        }
    }
}
