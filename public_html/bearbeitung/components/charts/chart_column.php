<?php

class ChartColumn
{
    private $column;
    private $label;
    private $type;
    private $format;
    private $helperColumns = array();

    /**
     * @param string $column
     * @param string $label
     * @param string $type
     * @param string $format
     */
    public function __construct($column, $label, $type = 'float', $format = '')
    {
        $this->column = $column;
        $this->label = $label;
        $this->type = $type;
        $this->format = $format;
    }

    /**
     * @param string $column
     *
     * @return $this
     */
    public function setTooltipColumn($column)
    {
        $this->helperColumns['tooltip'] = $column;

        return $this;
    }

    /**
     * @param string $column
     *
     * @return $this
     */
    public function setAnnotationColumn($column)
    {
        $this->helperColumns['annotation'] = $column;

        return $this;
    }

    /**
     * @param string $column
     *
     * @return $this
     */
    public function setAnnotationTextColumn($column)
    {
        $this->helperColumns['annotationText'] = $column;

        return $this;
    }

    /**
     * @return array
     */
    public function getHelperColumns()
    {
        return $this->helperColumns;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return array(
            'column' => $this->column,
            'label' => $this->label,
            'type' => $this->type,
            'format' => $this->format,
            'role' => 'data',
        );
    }

    /**
     * @return array
     */
    public function getHelperColumnsData()
    {
        $data = array();

        foreach ($this->helperColumns as $role => $column) {
            $data[] = array('role' => $role, 'column' => $column);
        }

        return $data;
    }
}
