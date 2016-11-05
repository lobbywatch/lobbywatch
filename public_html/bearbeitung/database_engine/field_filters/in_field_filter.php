<?php

class InFieldFilter
{
    /**
     * @var array
     */
    private $values;

    /**
     * @param array $values
     */
    public function  __construct($values)
    {
        $this->values = $values;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param FilterConditionGenerator $filterVisitor
     */
    public function Accept($filterVisitor)
    {
        $filterVisitor->VisitInFieldFilter($this);
    }
}
