<?php

include_once dirname(__FILE__) . '/../columns/column_interface.php';

class FormLayoutColumn implements ColumnInterface
{
    /**
     * @var ColumnInterface
     */
    private $column;

    /**
     * @var int
     */
    private $inputWidth;

    /**
     * @var int
     */
    private $labelWidth;

    /**
     * @param bool            $hasLabelWidth
     * @param ColumnInterface $column
     * @param int             $inputWidth
     * @param int             $labelWidth
     */
    public function __construct(
        $hasLabelWidth,
        ColumnInterface $column,
        $inputWidth = null,
        $labelWidth = null)
    {
        if ($hasLabelWidth) {
            $inputWidth = is_null($inputWidth) ? 9 : $inputWidth;
            $labelWidth = is_null($labelWidth) ? 3 : $labelWidth;
        } else {
            $inputWidth = is_null($inputWidth) ? 12 : $inputWidth;
            $labelWidth = 0;
        }

        $this->column = $column;
        $this->inputWidth = $inputWidth;
        $this->labelWidth = $labelWidth;
    }

    /**
     * @return ColumnInterface
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->column->getName();
    }

    /**
     * @return string
     */
    public function getCaption()
    {
        return $this->column->getCaption();
    }

    /**
     * @param string
     */
    public function setCaption($value) {
        $this->column->setCaption($value);
    }

    /**
     * @param Renderer $renderer
     *
     * @return string
     */
    public function getDisplayValue(Renderer $renderer)
    {
        return $this->column->getDisplayValue($renderer);
    }

    /**
     * @return array
     */
    public function getViewData()
    {
        return $this->column->getViewData();
    }

    /**
     * @param Grid $grid
     */
    public function SetGrid(Grid $grid)
    {
        $this->column->SetGrid($grid);
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->inputWidth + $this->labelWidth;
    }

    /**
     * @return int
     */
    public function getInputWidth()
    {
        return $this->inputWidth;
    }

    /**
     * @return int
     */
    public function getLabelWidth()
    {
        return $this->labelWidth;
    }
}
