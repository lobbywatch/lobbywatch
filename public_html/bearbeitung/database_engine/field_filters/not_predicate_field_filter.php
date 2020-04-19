<?php

class NotPredicateFilter extends CompositeFilter{
    public $InnerFilter;

    public function __construct($innerFilter)
    {
        parent::__construct('OR');
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
