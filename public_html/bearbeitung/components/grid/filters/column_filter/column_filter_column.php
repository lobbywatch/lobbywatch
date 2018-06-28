<?php

class ColumnFilterColumn
{
    /**
     * @var FilterColumn
     */
    private $filterColumn;

    /**
     * @var FilterComponentInterface[]
     */
    private $options = array();

    /**
     * @var bool
     */
    private $isSearchEnabled = false;

    /** @var int */
    private $numberOfValuesToDisplay = 20;

    /**
     * @var bool
     */
    private $isDefaultsEnabled = true;

    /**
     * @var string
     */
    private $order = 'ASC';


    /**
     * @param FilterColumn $filterColumn
     * @param FilterComponentInterface[] $options
     * @param bool $isDefaultsEnabled
     * @param bool $isSearchEnabled
     * @param string $order
     */
    public function __construct(
        FilterColumn $filterColumn,
        array $options,
        $isDefaultsEnabled = true,
        $isSearchEnabled = false,
        $order = 'ASC')
    {
        $this->filterColumn = $filterColumn;
        $this->options = $options;
        $this->isDefaultsEnabled = $isDefaultsEnabled;
        $this->isSearchEnabled = $isSearchEnabled;
        $this->setOrder($order);
    }

    /**
     * @return bool
     */
    public function isDefaultsEnabled()
    {
        return $this->isDefaultsEnabled;
    }

    /**
     * @param bool $isDefaultsEnabled
     * @return $this
     */
    public function setIsDefaultsEnabled($isDefaultsEnabled)
    {
        $this->isDefaultsEnabled = $isDefaultsEnabled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSearchEnabled()
    {
        return $this->isSearchEnabled;
    }

    /**
     * @param bool $isSearchEnabled
     * @return $this
     */
    public function setIsSearchEnabled($isSearchEnabled)
    {
        $this->isSearchEnabled = $isSearchEnabled;
        return $this;
    }

    /**
     * @param string $numberOfValuesToDisplay
     * @return $this
     */
    public function setNumberOfValuesToDisplay($numberOfValuesToDisplay) {
        $this->numberOfValuesToDisplay = $numberOfValuesToDisplay;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumberOfValuesToDisplay() {
        return $this->numberOfValuesToDisplay;
    }

    /**
     * @param FilterComponentInterfaces[] $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return FilterComponentInterfaces[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return FilterColumn
     */
    public function getFilterColumn()
    {
        return $this->filterColumn;
    }

    /**
     * @param FilterColumn $filterColumn
     * @return $this
     */
    public function setFilterColumn(FilterColumn $filterColumn)
    {
        $this->filterColumn = $filterColumn;

        return $this;
    }

    /**
     * @param string $dir ASC|DESC
     * @return $this
     */
    public function setOrder($dir)
    {
        $this->order = strtoupper($dir) === 'ASC' ? 'ASC' : 'DESC';

        return $this;
    }

    /**
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }
}
