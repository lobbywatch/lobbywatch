<?php

class NotPredicateFilter {
    public $InnerFilter;

    public function __construct($innerFilter)
    {
        $this->InnerFilter = $innerFilter;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->InnerFilter);
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
