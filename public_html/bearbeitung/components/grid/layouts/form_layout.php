<?php

include_once dirname(__FILE__) . '/form_layout_mode.php';
include_once dirname(__FILE__) . '/form_layout_group.php';
include_once dirname(__FILE__) . '/form_layout_row.php';

class FormLayout
{
    /**
     * @var FormLayoutGroup[]
     */
    private $groups = array();

    /**
     * @var string
     */
    private $mode;

    /**
     * @param string $mode
     */
    public function __construct($mode = FormLayoutMode::HORIZONTAL)
    {
        $this->mode = $mode;
        $this->groups[] = new FormLayoutGroup($this, null, 12);
    }

    /**
     * @param string $name
     * @param int    $width
     *
     * @return FormLayoutGroup
     */
    public function addGroup($name = null, $width = 12)
    {
        $group = new FormLayoutGroup($this, $name, $width);
        $this->groups[] = $group;

        return $group;
    }

    /**
     * @return FormLayoutRow
     */
    public function addRow()
    {
        return $this->groups[count($this->groups) - 1]->addRow();
    }

    /**
     * @return array
     */
    public function getColumnNames()
    {
        $names = array();

        foreach ($this->getGroups() as $group) {
            foreach ($group->getRows() as $row) {
                foreach ($row->getCols() as $col) {
                    $names[] = $col->getName();
                }
            }
        }

        return $names;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        $columns = array();

        foreach ($this->getGroups() as $group) {
            foreach ($group->getRows() as $row) {
                foreach ($row->getCols() as $col) {
                    $columns[$col->getName()] = $col->getColumn();
                }
            }
        }

        return $columns;
    }

    /**
     * @return FormLayoutGroup[]
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param string $mode
     *
     * @return FormLayout
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @return boolean
     */
    public function isHorizontal()
    {
        return $this->mode === FormLayoutMode::HORIZONTAL;
    }
}
