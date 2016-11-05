<?php

class NotPredicateFilter {
    public $InnerFilter;

    public function __construct($innerFilter)
    {
        $this->InnerFilter = $innerFilter;
    }

    /**
     * @param FilterConditionGenerator $filterVisitor
     * @return void
     */
    public function Accept($filterVisitor)
    {
        $filterVisitor->VisitNotPredicateFilter($this);
    }
}
