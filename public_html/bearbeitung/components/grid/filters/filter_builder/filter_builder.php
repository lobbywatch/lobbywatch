<?php

include_once dirname(__FILE__) . '/../abstract_filter.php';
include_once dirname(__FILE__) . '/../filter_group.php';
include_once dirname(__FILE__) . '/../filter_condition.php';
include_once dirname(__FILE__) . '/../../../editors/custom.php';
include_once dirname(__FILE__) . '/../filter_column.php';

class FilterBuilder extends AbstractFilter
{
    /**
     * @var FilterColumn[]
     */
    private $columns = array();

    /**
     * @var CustomEditor[]
     */
    private $operators = array();

    public function __construct()
    {
        $this->filterComponent = new FilterGroup();
    }

    /**
     * @param FilterColumn   $column
     * @param CustomEditor[] $operators
     *
     * @return $this
     */
    public function addColumn(
        FilterColumn $column,
        array $operators)
    {
        $columnName = $column->getFieldName();
        $this->columns[$columnName] = $column;
        $this->operators[$columnName] = $operators;

        foreach ($operators as $editor) {
            if ($editor instanceof CustomEditor) {
                $editor->setFieldName($column->getFieldName());
            }
        }

        return $this;
    }

    /** @param string   $columnName */
    public function removeColumn($columnName) {
        unset($this->columns[$columnName]);
    }

    /**
     * @return FilterColumn[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return boolean
     */
    public function hasColumns()
    {
        return count($this->columns) > 0;
    }

    /**
     * @param FilterColumn $column
     *
     * @return boolean
     */
    public function hasColumn(FilterColumn $column)
    {
        return array_key_exists(
            $column->getFieldName(),
            $this->columns
        );
    }

    /**
     * @param FilterColumn $column
     *
     * @return string[]
     */
    public function getOperators(FilterColumn $column)
    {
        return $this->operators[$column->getFieldName()];
    }

    /**
     * @param FilterColumn    $column
     * @param string|string[] $operators
     * @param CustomEditor    $editor
     *
     * @return $this
     */
    public function setEditorFor(
        FilterColumn $column,
        $operators,
        CustomEditor $editor = null)
    {
        $fieldName = $column->getFieldName();

        if (!$this->hasColumn($column)) {
            $this->columns[$fieldName] = $column;
            $this->operators[$fieldName] = array();
        }

        if (!is_null($editor)) {
            $editor->setFieldName($fieldName);
        }

        $operators = !is_array($operators)
            ? array($operators)
            : $operators;

        foreach ($operators as $operator) {
            $this->operators[$fieldName][$operator] = $editor;
        }

        return $this;
    }

    /**
     * @param Captions $captions
     * @param string   $disabledTemplate
     *
     * @return string
     */
    public function toString(Captions $captions, $disabledTemplate = '%s')
    {
        return $this->filterComponent->toString($captions, false, $disabledTemplate);
    }
}
