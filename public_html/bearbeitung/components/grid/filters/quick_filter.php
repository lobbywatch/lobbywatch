<?php

include_once dirname(__FILE__) . '/../../superglobal_wrapper.php';
include_once dirname(__FILE__) . '/abstract_filter.php';

class QuickFilter extends AbstractFilter
{
    /**
     * @var FilterColumn[]
     */
    private $columns = array();

    /**
     * @var string
     */
    private $value;

    /** @var boolean */
    private $fullTextSearch;

    public function __construct()
    {
        $this->filterComponent = new FilterGroup();
        $this->fullTextSearch = true;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function getSearchTemplate($value)
    {
        if ($this->fullTextSearch) {
            return '%' . $value . '%';
        }  else {
            return $value . '%';
        }
    }

    /**
     * @param FilterColumn $column
     *
     * @return $this
     */
    public function addColumn(FilterColumn $column)
    {
        $this->columns[$column->getFieldName()] = $column;

        return $this;
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
     * @return string[]
     */
    public function getColumnNames()
    {
        return array_keys($this->columns);
    }

    public function setFullTextSearch($value)
    {
        $this->fullTextSearch = $value;
    }

    public function getFullTextSearch()
    {
        return $this->fullTextSearch;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function createFilterComponent($value)
    {
        $this->filterComponent = new FilterGroup(FilterGroupOperator::OPERATOR_OR);
        $this->value = empty($value) ? null : $value;

        if (is_null($this->value)) {
            return $this;
        }

        foreach ($this->columns as $column) {
            $this->filterComponent->insertChild(new FilterCondition(
                $column,
                FilterConditionOperator::IS_LIKE,
                array($this->getSearchTemplate($value))
            ));
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->getValue();
    }
}
