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

    /**
     * @var string[]
     */
    private $selectedFieldNames;

    /**
     * @var string
     */
    private $operator;

    public function __construct()
    {
        $this->filterComponent = new FilterGroup();
        $this->value = '';
        $this->selectedFieldNames = array();
        $this->operator = FilterConditionOperator::CONTAINS;
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

    public function Apply() {
        if ($this->operator == FilterConditionOperator::DOES_NOT_EQUAL || $this->operator == FilterConditionOperator::DOES_NOT_CONTAIN) {
            $this->filterComponent = new FilterGroup(FilterGroupOperator::OPERATOR_AND);
        } else {
            $this->filterComponent = new FilterGroup(FilterGroupOperator::OPERATOR_OR);
        }
        if (isset($this->value) && ($this->value != '')) {
            foreach ($this->columns as $column) {
                if (count($this->selectedFieldNames) == 0 || in_array($column->getFieldName(), $this->selectedFieldNames)) {
                    $this->filterComponent->insertChild(new FilterCondition(
                        $column,
                        $this->operator,
                        array($this->value)
                    ));
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->getValue();
    }

    /**
     * @return string[]
     */
    public function getSelectedFieldNames()
    {
        return $this->selectedFieldNames;
    }

    /**
     * @param string[] $selectedFieldNames
     */
    public function setSelectedFieldNames($selectedFieldNames)
    {
        $this->selectedFieldNames = $selectedFieldNames;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    /**
     * @return string[]
     */
    public function getAvailableOperators() {
        return array(
            FilterConditionOperator::CONTAINS,
            FilterConditionOperator::BEGINS_WITH,
            FilterConditionOperator::ENDS_WITH,
            FilterConditionOperator::EQUALS,
            FilterConditionOperator::DOES_NOT_CONTAIN,
            FilterConditionOperator::DOES_NOT_EQUAL
        );
    }
}
