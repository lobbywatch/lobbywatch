<?php

include_once dirname(__FILE__) . '/filter_component_interface.php';
include_once dirname(__FILE__) . '/filter_component_type.php';
include_once dirname(__FILE__) . '/filter_condition_operator.php';
include_once dirname(__FILE__) . '/filter_column.php';

class FilterCondition implements FilterComponentInterface
{
    /**
     * @var FilterColumn
     */
    private $column;

    /**
     * @var bool
     */
    private $isEnabled = true;

    /**
     * @param FilterColumn $column
     * @param string $operator
     * @param array $values
     * @param array $displayValues
     * @param bool $isEnabled
     */
    public function __construct(
        FilterColumn $column = null,
        $operator = FilterConditionOperator::EQUALS,
        array $values = array(),
        array $displayValues = null,
        $isEnabled = true)
    {
        $this->column = $column;
        $this->operator = $operator;
            $this->values = $values;
        $this->displayValues = is_null($displayValues)
            ? $values
            : $displayValues;
        $this->isEnabled = $isEnabled;
    }

    /**
     * {@inheritdoc}
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * {@inheritdoc}
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    private function implodeValues()
    {
        return implode(',', $this->getActualValues());
    }

    /**
     * {@inheritdoc}
     */
    public function getCommandFilter()
    {
        switch ($this->operator) {
            case FilterConditionOperator::EQUALS:
                return new FieldFilter($this->implodeValues(), '=');
            case FilterConditionOperator::DOES_NOT_EQUAL:
                return new FieldFilter($this->implodeValues(), '<>');
            case FilterConditionOperator::IS_GREATER_THAN:
                return new FieldFilter($this->implodeValues(), '>');
            case FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO:
                return new FieldFilter($this->implodeValues(), '>=');
            case FilterConditionOperator::IS_LESS_THAN:
                return new FieldFilter($this->implodeValues(), '<');
            case FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO:
                return new FieldFilter($this->implodeValues(), '<=');
            case FilterConditionOperator::IS_BETWEEN:
                return new BetweenFieldFilter($this->getValue(0), $this->getValue(1));
            case FilterConditionOperator::IS_NOT_BETWEEN:
                return new NotPredicateFilter(
                    new BetweenFieldFilter($this->getValue(0), $this->getValue(1))
                );
            case FilterConditionOperator::CONTAINS:
                return FieldFilter::Contains($this->implodeValues(), false);
            case FilterConditionOperator::DOES_NOT_CONTAIN:
                return new NotPredicateFilter(
                    FieldFilter::Contains($this->implodeValues(), false)
                );
            case FilterConditionOperator::BEGINS_WITH:
                return new FieldFilter($this->getValue(0) . '%', 'ILIKE');
            case FilterConditionOperator::ENDS_WITH:
                return new FieldFilter('%' . $this->getValue(0), 'ILIKE');
            case FilterConditionOperator::IS_LIKE:
                return new FieldFilter($this->getValue(0), 'ILIKE');
            case FilterConditionOperator::IS_NOT_LIKE:
                return new NotPredicateFilter(
                    new FieldFilter($this->getValue(0), 'ILIKE')
                );
            case FilterConditionOperator::IS_BLANK:
                return new IsBlankFieldFilter();
            case FilterConditionOperator::IS_NOT_BLANK:
                return new NotPredicateFilter(new IsBlankFieldFilter());
            case FilterConditionOperator::DATE_EQUALS:
                return new DateFieldFilter($this->getValue(0), '=');
            case FilterConditionOperator::DATE_DOES_NOT_EQUAL:
                return new NotPredicateFilter(
                    new DateFieldFilter($this->getValue(0), '=')
                );
            case FilterConditionOperator::IN:
                return new InFieldFilter($this->getActualValues());
            case FilterConditionOperator::NOT_IN:
                return new NotPredicateFilter(
                    new InFieldFilter($this->getActualValues())
                );
            case FilterConditionOperator::TODAY:
                return DateFieldFilter::Equals(
                    SMDateTime::Now()->ToAnsiSQLString(false)
                );
            case FilterConditionOperator::THIS_MONTH:
                return $this->createDateBetween(
                    new SMDateTime(strtotime('last day last month')),
                    new SMDateTime(strtotime('first day next month'))
                );
            case FilterConditionOperator::PREV_MONTH:
                return $this->createDateBetween(
                    new SMDateTime(strtotime('last day of -2 month')),
                    new SMDateTime(strtotime('first day of this month'))
                );
            case FilterConditionOperator::THIS_YEAR:
                return $this->createDateBetween(
                    new SMDateTime(strtotime('last year last day of december')),
                    new SMDateTime(strtotime('next year first day of january'))
                );
            case FilterConditionOperator::PREV_YEAR:
                return $this->createDateBetween(
                    new SMDateTime(strtotime('2 years ago last day of december')),
                    new SMDateTime(strtotime('this year first day of january'))
                );
            case FilterConditionOperator::YEAR_EQUALS:
                return DatePartFieldFilter::YearEquals($this->getValue(0));
            case FilterConditionOperator::YEAR_DOES_NOT_EQUAL:
                return DatePartFieldFilter::YearDoesNotEqual($this->getValue(0));
            case FilterConditionOperator::MONTH_EQUALS:
                return DatePartFieldFilter::MonthEquals($this->getValue(0));
            case FilterConditionOperator::MONTH_DOES_NOT_EQUAL:
                return DatePartFieldFilter::MonthDoesNotEqual($this->getValue(0));
            default:
                throw new InvalidArgumentException('Unknown operator');
        }
    }

    private function createDateBetween(SMDateTime $a, SMDateTime $b)
    {
        $result = new CompositeFilter('AND');
        $fieldInfo = $this->getFieldInfo();

        return $result
            ->addFilter($fieldInfo, new DateFieldFilter($a->ToAnsiSQLString(false), '>'))
            ->addFilter($fieldInfo, new DateFieldFilter($b->ToAnsiSQLString(false), '<'));
    }

    /**
     * @param FilterColumn $column
     *
     * @return $this
     */
    public function setColumn(FilterColumn $column)
    {
        $this->column = $column;

        return $this;
    }

    /**
     * @return FilterColumn
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param array $values
     *
     * @return $this
     */
    public function setValues(array $values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @return array
     */
    public function getDisplayValues()
    {
        return $this->displayValues;
    }

    /**
     * @param array $displayValues
     *
     * @return $this
     */
    public function setDisplayValues(array $displayValues)
    {
        $this->displayValues = $displayValues;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldInfo()
    {
        return $this->column->getDisplayFieldInfo();
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return array(
            'type' => FilterComponentType::CONDITION,
            'isEnabled' => $this->isEnabled,
            'column' => $this->column->getFieldName(),
            'operator' => $this->operator,
            'values' => $this->values,
            'displayValues' => $this->displayValues,
        );
    }

    /**
     * {@inheritdoc}
     */
    static public function deserialize(
        FixedKeysArray $columns,
        array $serializedComponent)
    {
        $instance =  new self(
            $columns[$serializedComponent['column']],
            $serializedComponent['operator'],
            $serializedComponent['values'],
            $serializedComponent['displayValues']
        );

        $instance->setEnabled($serializedComponent['isEnabled']);

        return $instance;
    }

    /**
     * {@inheritdoc}
     */
    public function setEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isCommandFilterEmpty()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setChildren(array $children)
    {
        throw new LogicException('FilterCondition cannot contain children');
    }

    /**
     * {@inheritdoc}
     */
    public function insertChild(FilterComponentInterface $child, $key = null)
    {
        throw new LogicException('FilterCondition cannot contain children');
    }

    /**
     * {@inheritdoc}
     */
    public function toString(
        Captions $captions,
        $ignoreDisabled = true,
        $disabledTemplate = '%s')
    {
        if (!$this->isEnabled() && $ignoreDisabled) {
            return '';
        }

        $result = trim(sprintf(
            '%s %s %s',
            $this->column->getCaption(),
            $captions->getMessageString('FilterOperator' . $this->operator . 'Short'),
            $this->getDisplayValuesAsString($captions)
        ));

        if (!$this->isEnabled()) {
            return sprintf($disabledTemplate, $result);
        }

        return $result;
    }

    private function getDisplayValuesAsString(Captions $captions)
    {
        switch ($this->operator) {
            case FilterConditionOperator::IN:
            case FilterConditionOperator::NOT_IN:
                return '(' . implode(', ', $this->displayValues) . ')';
            default:
                return implode(
                    ' ' . $captions->GetMessageString('And') . ' ',
                    $this->displayValues
                );
        }
    }

    private function getActualValues() {
        return $this->useDisplayValues()
            ? $this->displayValues
            : $this->values;
    }

    private function getValue($index)
    {
        $values = $this->getActualValues();

        return isset($values[$index]) ? $values[$index] : null;
    }

    private function useDisplayValues()
    {
        return $this->column->getFieldName() !== $this->column->getDisplayFieldName();
    }

    /**
     * @return mixed $value
     * @return $this
     */
    static public function equals($value)
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::EQUALS,
            array($value)
        );
    }

    /**
     * @return mixed $value
     * @return $this
     */
    static public function notEquals($value)
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::DOES_NOT_EQUAL,
            array($value)
        );
    }

    /**
     * @return mixed $value
     * @return $this
     */
    static public function greaterThan($value)
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::IS_GREATER_THAN,
            array($value)
        );
    }

    /**
     * @return mixed $value
     * @return $this
     */
    static public function greaterThanOrEqualTo($value)
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO,
            array($value)
        );
    }

    /**
     * @return mixed $value
     * @return $this
     */
    static public function lessThan($value)
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::IS_LESS_THAN,
            array($value)
        );
    }

    /**
     * @return mixed $value
     * @return $this
     */
    static public function lessThanOrEqualTo($value)
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO,
            array($value)
        );
    }

    /**
     * @return mixed $a
     * @return mixed $b
     * @return $this
     */
    static public function between($a, $b)
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::IS_BETWEEN,
            array($a, $b)
        );
    }

    /**
     * @return mixed $a
     * @return mixed $b
     * @return $this
     */
    static public function notBetween($a, $b)
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::IS_NOT_BETWEEN,
            array($a, $b)
        );
    }

    /**
     * @return string $value
     * @return $this
     */
    static public function contains($value)
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::CONTAINS,
            array($value)
        );
    }

    /**
     * @return string $value
     * @return $this
     */
    static public function notContains($value)
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::DOES_NOT_CONTAIN,
            array($value)
        );
    }

    /**
     * @return string $value
     * @return $this
     */
    static public function begins($value)
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::BEGINS_WITH,
            array($value)
        );
    }

    /**
     * @return string $value
     * @return $this
     */
    static public function ends($value)
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::ENDS_WITH,
            array($value)
        );
    }

    /**
     * @return string $value
     * @return $this
     */
    static public function like($value)
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::IS_LIKE,
            array($value)
        );
    }

    /**
     * @return string $value
     * @return $this
     */
    static public function notLike($value)
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::IS_NOT_LIKE,
            array($value)
        );
    }

    /**
     * @return string $value
     * @return $this
     */
    static public function blank()
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::IS_BLANK
        );
    }

    /**
     * @return $this
     */
    static public function notBlank()
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::IS_NOT_BLANK
        );
    }

    /**
     * @return string $value
     * @return $this
     */
    static public function dateEquals($value)
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::DATE_EQUALS,
            array($value)
        );
    }

    /**
     * @return string $value
     * @return $this
     */
    static public function dateNotEquals($value)
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::DATE_DOES_NOT_EQUAL,
            array($value)
        );
    }

    /**
     * @param array $values
     * @return $this
     */
    static public function in(array $values)
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::IN,
            $values
        );
    }

    /**
     * @return array $values
     * @return $this
     */
    static public function notIn(array $values)
    {
        return new FilterCondition(
            null,
            FilterConditionOperator::NOT_IN,
            $values
        );
    }

    /**
     * @return string $part
     * @return mixed  $value
     * @return $this
     */
    static public function datePartEquals($part, $value)
    {
        switch ($part) {
            case 'YEAR':
                $operator = FilterConditionOperator::YEAR_EQUALS;
                break;
            case 'MONTH':
                $operator = FilterConditionOperator::MONTH_EQUALS;
                break;
            default:
                throw new InvalidArgumentException('Unknown date part');

        }

        return new FilterCondition(
            null,
            $operator,
            array($value)
        );
    }

    /**
     * @return string $part
     * @return mixed  $value
     * @return $this
     */
    static public function datePartNotEquals($part, $value)
    {
        switch ($part) {
            case 'YEAR':
                $operator = FilterConditionOperator::YEAR_DOES_NOT_EQUAL;
                break;
            case 'MONTH':
                $operator = FilterConditionOperator::MONTH_DOES_NOT_EQUAL;
                break;
            default:
                throw new InvalidArgumentException('Unknown date part');
        }

        return new FilterCondition(
            null,
            $operator,
            array($value)
        );
    }
}
