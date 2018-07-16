<?php

class FieldFilter
{
    private $value;
    private $filterType;
    private $ignoreFieldDataType;

    public static function Contains($value, $ignoreFieldDataType = true)
    {
        return new FieldFilter('%' . $value . '%', 'ILIKE', $ignoreFieldDataType);
    }

    public static function Equals($value, $ignoreFieldDataType = true)
    {
        return new FieldFilter($value, '=', $ignoreFieldDataType);
    }

    public static function DoesNotEqual($value, $ignoreFieldDataType = true)
    {
        return new FieldFilter($value, '<>', $ignoreFieldDataType);
    }

    /**
     * @param mixed $value
     * @param string $filterType ('=', '<>', 'LIKE', 'ILIKE')
     * @param bool $ignoreFieldDataType
     */
    public function  __construct($value, $filterType, $ignoreFieldDataType = false)
    {
        $this->value = $value;
        $this->filterType = $filterType;
        $this->ignoreFieldDataType = $ignoreFieldDataType;
    }

    public function GetFilterType()
    {
        return $this->filterType;
    }

    public function GetValue()
    {
        return $this->value;
    }

    public function GetIgnoreFieldDataType()
    {
        return $this->ignoreFieldDataType;
    }

    /**
     * @param FilterConditionGenerator $filterVisitor
     * @return void
     */
    public function Accept($filterVisitor)
    {
        $filterVisitor->VisitFieldFilter($this);
    }
}
