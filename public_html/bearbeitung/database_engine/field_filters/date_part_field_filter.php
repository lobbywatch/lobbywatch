<?php

include_once dirname(__FILE__) . '/field_filter.php';

class DatePartFieldFilter
{
    private $value;
    private $part;
    private $operator;

    static public function YearEquals($value)
    {
        return new self($value, 'YEAR', '=');
    }

    static public function YearDoesNotEqual($value)
    {
        return new self($value, 'YEAR', '=');
    }

    static public function MonthEquals($value)
    {
        return new self($value, 'MONTH', '=');
    }

    static public function MonthDoesNotEqual($value)
    {
        return new self($value, 'MONTH', '=');
    }


    public static function Equals($value)
    {
        return new self($value, '=');
    }

    /**
     * @param integer $value
     * @param integer $part
     * @param string  $operator ('=', '>', '<')
     */
    public function  __construct($value, $part, $operator)
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
        $this->part = $part;
        $this->operator = $operator;
    }

    public function GetOperator()
    {
        return $this->operator;
    }

    public function GetPart()
    {
        return $this->part;
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
        $filterVisitor->VisitDatePartFieldFilter($this);
    }

    public function isEmpty()
    {
        return false;
    }
}
