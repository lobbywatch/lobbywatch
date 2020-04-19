<?php

include_once dirname(__FILE__) . '/column_interface.php';
include_once dirname(__FILE__) . '/view_column_group.php';

abstract class AbstractViewColumn extends ViewColumnGroup implements ColumnInterface
{
    /** @var string */
    private $caption;

    /** @var bool */
    private $visible;

    /** @var null|string */
    private $fixedWidth = null;

    /** @var string */
    private $description;

    /** @var Component */
    public $headerControl;

    /** @var Grid */
    private $grid;

    /** @var CustomEditColumn */
    private $editOperationColumn;

    /** @var CustomEditColumn */
    private $insertOperationColumn;

    /** @var bool */
    private $wordWrap;

    /** @var int */
    private $minimalVisibility;

    /**
     * @param string $caption
     */
    public function __construct($caption)
    {
        $this->caption = $caption;
        $this->visible = true;
        $this->fixedWidth = null;
        $this->insertOperationColumn = null;
        $this->wordWrap = true;
        $this->minimalVisibility = ColumnVisibility::PHONE;
        $this->nullLabel = function_exists('GetNullLabel') ? GetNullLabel() : null;
    }

    /**
     * @return int
     */
    public function getMinimalVisibility()
    {
        return $this->minimalVisibility;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setMinimalVisibility($value)
    {
        $this->minimalVisibility = $value;

        return $this;
    }

    public function getUserClasses()
    {
        return "";
    }

    public function GetDescription()
    {
        return $this->description;
    }

    public function SetDescription($value)
    {
        $this->description = $value;
    }

    public function GetWordWrap()
    {
        return $this->wordWrap;
    }

    public function SetWordWrap($value)
    {
        $this->wordWrap = $value;
    }

    public function GetName()
    {
        return null;
    }

    public function GetCaption()
    {
        return $this->caption;
    }

    public function setCaption($value)
    {
        $this->caption = $value;
    }

    public function getVisible()
    {
        return $this->visible;
    }

    public function setVisible($value)
    {
        $this->visible = $value;
    }

    public function SetGrid(Grid $value)
    {
        $this->grid = $value;
        if ($this->GetEditOperationColumn() != null) {
            $this->GetEditOperationColumn()->SetGrid($this->grid);
        }
        if ($this->GetInsertOperationColumn() != null) {
            $this->GetInsertOperationColumn()->SetGrid($this->grid);
        }
    }

    /**
     * @return Grid
     */
    public function GetGrid()
    {
        return $this->grid;
    }

    abstract public function GetValue();

    /**
     * @param Renderer $renderer
     * @return void
     */
    abstract public function Accept($renderer);

    public function ProcessMessages()
    {
    }

    public function SetFixedWidth($value)
    {
        $this->fixedWidth = $value;
    }

    public function GetFixedWidth()
    {
        return $this->fixedWidth;
    }

    public function IsDataColumn()
    {
        return false;
    }

    public function GetAlign()
    {
        return null;
    }

    #region Edit operation
    public function SetEditOperationColumn(CustomEditColumn $value)
    {
        $this->editOperationColumn = $value;
    }

    /**
     * @return CustomEditColumn
     */
    public function GetEditOperationColumn()
    {
        return $this->editOperationColumn;
    }

    public function GetEditOperationEditor()
    {
        if (isset($this->editOperationColumn)) {
            return $this->editOperationColumn->GetEditControl();
        } else {
            return null;
        }
    }
    #endregion

    #region Insert operation
    public function SetInsertOperationColumn(CustomEditColumn $value)
    {
        $this->insertOperationColumn = $value;
    }

    /**
     * @return CustomEditColumn
     */
    public function GetInsertOperationColumn()
    {
        return $this->insertOperationColumn;
    }

    public function GetInsertOperationEditor()
    {
        if (isset($this->insertOperationColumn)) {
            return $this->insertOperationColumn->GetEditControl();
        } else {
            return null;
        }
    }

    #endregion

    public function getViewData()
    {
        return array();
    }

    protected function IsNull()
    {
        return false;
    }

    public function allowSorting()
    {
        return false;
    }

    public function GetActualKeys()
    {
        return array(
            'Primary' => false,
            'Foreign' => false
        );
    }

    public function getSortIndex()
    {
        return null;
    }

    public function getSortOrderType()
    {
        return null;
    }

    public function getNullLabel()
    {
        return $this->nullLabel;
    }

    public function setNullLabel($nullLabel)
    {
        $this->nullLabel = $nullLabel;
    }

    public function add(ViewColumnGroup $group)
    {
        throw new LogicException('Child cannot be added to ViewColumn');
    }

    public function getChildren()
    {
        return array();
    }

    public function getDepth()
    {
        return 0;
    }
}
