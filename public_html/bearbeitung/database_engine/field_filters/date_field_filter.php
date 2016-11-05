<?php

include_once dirname(__FILE__) . '/field_filter.php';

class DateFieldFilter
{
    private $value;
    private $operator;

    public static function Equals($value)
    {
        return new self($value, '=');
    }

    /**
     * @param integer $value
     * @param string  $operator ('=', '>', '<')
     */
    public function  __construct($value, $operator)
    {
        $availableOperators = array('=', '<', '>', '<=', '>=');
        if (!in_array($operator, $availableOperators)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid operator "%s". Expected one of "%s"',
                $operator,
                implode('", "', $availableOperators)
            ));
        }

        $this->value = $value;
        $this->operator = $operator;
    }

    public function GetOperator()
    {
        return $this->operator;
    }

    public function GetValue()
    {
        return $this->value;
    }

    /**
     * @param FilterConditionGenerator $filterVisitor
     */
    public function Accept($filterVisitor)
    {
        $filterVisitor->VisitDateFieldFilter($this);
    }
}
