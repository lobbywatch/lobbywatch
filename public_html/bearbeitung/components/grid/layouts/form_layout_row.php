<?php

include_once dirname(__FILE__) . '/form_layout_column.php';

class FormLayoutRow
{
    /**
     * @var FormLayoutColumn[]
     */
    private $cols = array();

    /**
     * @var bool
     */
    private $isHorizontal;

    /**
     * @param bool $isHorizontal
     */
    public function __construct($isHorizontal)
    {
        $this->isHorizontal = $isHorizontal;
    }

    /**
     * @param ColumnInterface $column
     * @param integer         $inputWidth
     * @param integer         $labelWidth
     *
     * @return FormLayoutRow
     */
    public function addCol(ColumnInterface $column, $inputWidth = null, $labelWidth = null)
    {
        $column = new FormLayoutColumn($this->isHorizontal, $column, $inputWidth, $labelWidth);

        if ($this->getWidth() + $column->getWidth() > 12) {
            throw new LogicException('Row width should not be greater than 12');
        }

        $this->cols[] = $column;

        return $this;
    }

    private function getWidth()
    {
        $width = 0;
        foreach ($this->cols as $col) {
            $width += $col->getWidth();
        }
        return $width;
    }

    /**
     * @return FormLayoutColumn[]
     */
    public function getCols()
    {
        return $this->cols;
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return count($this->cols) === 0;
    }
}
