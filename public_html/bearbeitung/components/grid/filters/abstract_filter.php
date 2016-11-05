<?php

include_once dirname(__FILE__) . '/filter_component_interface.php';

abstract class AbstractFilter
{
    /**
     * @var FilterComponentInterface
     */
    protected $filterComponent;

    /**
     * @return boolean
     */
    abstract function hasColumns();

    /**
     * @param bool $isEnabled
     *
     * @return $this
     */
    public function setEnabled($isEnabled)
    {
        $this->filterComponent->setEnabled($isEnabled);

        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->filterComponent->isEnabled();
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->filterComponent->isEmpty();
    }

    /**
     * @return boolean
     */
    public function isCommandFilterEmpty()
    {
        return $this->filterComponent->isCommandFilterEmpty();
    }

    /**
     * @return FilterComponentInterface
     */
    public function getFilterComponent()
    {
        return $this->filterComponent;
    }

    /**
     * @param FilterComponentInterface $filterComponent
     *
     * @return $this
     */
    public function setFilterComponent(FilterComponentInterface $filterComponent)
    {
        $this->filterComponent = $filterComponent;
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return $this->filterComponent->serialize();
    }
}
