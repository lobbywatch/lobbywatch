<?php

class IsNullFieldFilter {
    /**
     * @param FilterConditionGenerator $filterVisitor
     * @return void
     */
    public function Accept($filterVisitor)
    {
        $filterVisitor->VisitIsNullFieldFilter($this);
    }
}
