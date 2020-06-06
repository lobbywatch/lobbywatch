<?php

class FormLayoutSheet
{
    /** @var string */
    private $mode;

    /** @var FormLayout */
    private $layout;

    /** $var string */
    private $name;

    /** @var FormLayoutGroup[] */
    private $groups = array();

    /** @var FormLayoutGroup */
    private $defaultGroup;

    /**
     * @param string $mode
     * @param string $name
     */
    public function __construct($mode = FormLayoutMode::HORIZONTAL, $name = null) {
        $this->mode = $mode;
        $this->name = $name;
        $this->defaultGroup = $this->addGroup();
    }

    /**
     * @param string $mode
     *
     * @return FormLayout
     */
    public function setMode($mode) {
        $this->mode = $mode;
        $this->defaultGroup->setMode($mode);
        return $this;
    }

    /**
     * @return string
     */
    public function getMode() {
        return $this->mode;
    }

    /**
     * @return boolean
     */
    public function isHorizontal() {
        return $this->mode === FormLayoutMode::HORIZONTAL;
    }

    /**
     * @param string $name
     * @param int    $width
     * @param string $customAttributes
     * @param string $inlineStyles
     *
     * @return FormLayoutGroup
     */
    public function addGroup($name = null, $width = 12, $customAttributes = '', $inlineStyles = '') {
        $group = new FormLayoutGroup($this->mode, $name, $width, $customAttributes, $inlineStyles);
        $this->groups[] = $group;

        return $group;
    }

    /**
     * @return FormLayoutRow
     */
    public function addRow() {
        return $this->groups[count($this->groups) - 1]->addRow();
    }

    /**
     * @return array
     */
    public function getColumnNames() {
        $names = array();

        foreach ($this->groups as $group) {
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
    public function getColumns() {
        $columns = array();

        foreach ($this->groups as $group) {
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
    public function getGroups() {
        return $this->groups;
    }

    /**
     * @param ColumnInterface $column
     *
     * @return FormLayoutSheet
     */
    public function addCol(ColumnInterface $column) {
        $this->defaultGroup->addRow()->addCol($column);
        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

}
