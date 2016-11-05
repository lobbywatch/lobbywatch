<?php

class BetweenFieldFilter {
    private $startValue;
    private $endValue;

    public function  __construct($startValue, $endValue)
    {
        $this->startValue = $startValue;
        $this->endValue = $endValue;
    }

    public function GetStartValue()
    {
        return $this->startValue;
    }

    public function GetEndValue()
    {
        return $this->endValue;
    }

    /**
     * @param FilterConditionGenerator $filterVisitor
     * @return void
     */
    public function Accept($filterVisitor)
    {
        $filterVisitor->VisitBetweenFieldFilter($this);
    }
}
