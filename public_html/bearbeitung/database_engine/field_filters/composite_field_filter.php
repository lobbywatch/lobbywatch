<?php

class CompositeFilter {
    private $filterLinkType;
    private $innerFilters;

    // AND | OR
    public function __construct($filterLinkType)
    {
        $this->filterLinkType = $filterLinkType;
        $this->innerFilters = array();
    }

    public function GetFilterLinkType()
    {
        return $this->filterLinkType;
    }

    public function GetInnerFilters()
    {
        return $this->innerFilters;
    }

    /**
     * @param FieldInfo $field
     * @param mixed     $filter
     * @return $this
     */
    public function AddFilter($field, $filter)
    {
        $this->innerFilters[] = array(
            'field' => $field,
            'filter' => $filter
        );

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->innerFilters) === 0;
    }

    /**
     * @param FilterConditionGenerator $filterVisitor
     * @return void
     */
    public function Accept($filterVisitor)
    {
        $filterVisitor->VisitCompositeFilter($this);
    }
}
