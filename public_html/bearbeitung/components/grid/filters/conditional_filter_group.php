<?php

include_once dirname(__FILE__) . '/filter_group.php';

class ConditionalFilterGroup extends FilterGroup
{
    /**
     * @var FilterComponentInterface
     */
    private $filterComponent;

    /**
     * @var FilterColumn
     */
    private $column;

    /**
     * @var bool
     */
    private $isDateTreePart = false;

    /**
     * @param string                     $operator
     * @param FilterComponentInterface[] $children
     * @param FilterComponentInterface   $filterComponent
     * @param bool                       $isEnabled
     * @param bool                       $isDateTreePart
     */
    public function __construct(
        $operator = FilterGroupOperator::OPERATOR_AND,
        array $children = array(),
        FilterComponentInterface $filterComponent = null,
        $isEnabled = true,
        $isDateTreePart = false)
    {
        parent::__construct($operator, $children, $isEnabled);
        $this->filterComponent = $filterComponent;
        $this->isDateTreePart = $isDateTreePart;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldInfo()
    {
        return $this->filterComponent->getFieldInfo();
    }

    /**
     * @param FilterComponentInterface $filterComponent
     *
     * @return $this
     */
    public function setFilterComponent(FilterComponentInterface $filterComponent)
    {
        $this->filterComponent = $filterComponent;

        return $this;
    }

    /**
     * @return FilterComponentInterface
     */
    public function getFilterComponent()
    {
        return $this->filterComponent;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommandFilter()
    {
        if ($this->hasDisabledChild()) {
            return parent::getCommandFilter();
        }

        return $this->filterComponent->getCommandFilter();
    }

    /**
     * {@inheritdoc}
     */
    public function toString(
        Captions $captions,
        $ignoreDisabled = false,
        $disabledTemplate = '%s')
    {
        if ($this->hasDisabledChild()) {
            return parent::toString($captions, $ignoreDisabled, $disabledTemplate);
        }

        return $this->filterComponent->toString($captions, $ignoreDisabled, $disabledTemplate);
    }

    public function hasDisabledChild()
    {
        foreach ($this->getChildren() as $child) {
            if ($child instanceOf ConditionalFilterGroup && $child->hasDisabledChild() || !$child->isEnabled())
                return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    static public function orX(
        array $children,
        $isEnabled = true)
    {
        return new self(
            FilterGroupOperator::OPERATOR_OR,
            $children,
            null,
            $isEnabled
        );
    }

    /**
     * {@inheritdoc}
     */
    static public function andX(
        array $children,
        $isEnabled = true)
    {
        return new self(
            FilterGroupOperator::OPERATOR_AND,
            $children,
            null,
            $isEnabled
        );
    }

    /**
     * {@inhertidoc}
     */
    public function setColumn(FilterColumn $column)
    {
        parent::setColumn($column);
        $this->column = $column;

        return $this;
    }

    public function setIsDateTreePart($value)
    {
        $this->isDateTreePart = $value;

        return $this;
    }


    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        $result = parent::serialize();
        $result['isDateTreePart'] = $this->isDateTreePart;
        return $result;
    }

}
