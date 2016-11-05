<?php

class IsBlankFieldFilter {
    /**
     * @param FilterConditionGenerator $filterVisitor
     * @return void
     */
    public function Accept($filterVisitor)
    {
        $filterVisitor->VisitIsBlankFieldFilter($this);
    }
}
