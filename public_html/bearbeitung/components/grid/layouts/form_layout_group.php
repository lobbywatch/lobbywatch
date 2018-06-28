<?php

class FormLayoutGroup
{
    /**
     * @var FormLayout
     */
    private $layout;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $width;

    /**
     * @var FormLayoutRow[]
     */
    private $rows = array();

    /**
     * @param FormLayout $layout
     * @param string     $name
     * @param int        $width
     */
    public function __construct(FormLayout $layout, $name, $width)
    {
        $this->layout = $layout;
        $this->name = $name;
        $this->width = $width;
    }

    /**
     * @return FormLayoutRow
     */
    public function addRow()
    {
        $row = new FormLayoutRow($this->layout->isHorizontal());

        $this->rows[] = $row;

        return $row;
    }

    /**
     * @return FormLayoutRow[]
     */
    public function getRows()
    {
        $result = array();
        foreach ($this->rows as $row) {
            if (!$row->isEmpty()) {
                $result[] = $row;
            }
        }
        return $result;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return count($this->getRows()) === 0;
    }
}
