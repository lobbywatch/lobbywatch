<?php

include_once dirname(__FILE__) . '/../filter_component_interface.php';

class ColumnFilterComponent implements FilterComponentInterface
{
    /**
     * @var FilterComponentInterface
     */
    private $component;

    /**
     * @var bool
     */
    private $hasDivider = false;

    /**
     * @var bool
     */
    private $ignoreSelectAll = false;

    /**
     * @param FilterComponentInterface $component
     * @param bool $hasDivider
     * @param bool $ignoreSelectAll
     */
    public function __construct(
        FilterComponentInterface $component,
        $hasDivider = false,
        $ignoreSelectAll = false)
    {
        $this->component = $component;
        $this->hasDivider = $hasDivider;
        $this->ignoreSelectAll = $ignoreSelectAll;
    }

    /**
     * @param bool $ignoreSelectAll
     *
     * @return $this
     */
    public function setIgnoreSelectAll($ignoreSelectAll)
    {
        $this->ignoreSelectAll = $ignoreSelectAll;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIgnoreSelectAll()
    {
        return $this->ignoreSelectAll;
    }

    /**
     * @param bool $hasDivider
     */
    public function setHasDivider($hasDivider)
    {
        $this->hasDivider = $hasDivider;

        return $this;
    }

    public function hasDivider()
    {
        return $this->hasDivider;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldInfo()
    {
        return $this->component->getFieldInfo();
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        return $this->component->getChildren();
    }

    /**
     * {@inheritdoc}
     */
    public function setChildren(array $children)
    {
        return $this->component->setChildren($children);
    }

    /**
     * {@inheritdoc}
     */
    public function insertChild(FilterComponentInterface $child, $key = null)
    {
        return $this->component->insertChild($child, $key);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return $this->component->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function isCommandFilterEmpty()
    {
        return $this->component->isCommandFilterEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function getOperator()
    {
        return $this->component->getOperator();
    }

    /**
     * {@inheritdoc}
     */
    public function setOperator($operator)
    {
        return $this->component->setOperator($operator);
    }

    /**
     * {@inheritdoc}
     */
    public function getCommandFilter()
    {
        return $this->component->getCommandFilter();
    }

    /**
     * {@inheritdoc}
     */
    public function setEnabled($isEnabled)
    {
        return $this->component->setEnabled($isEnabled);
    }

     /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->component->isEnabled();
    }

    /**
     * {@inheritdoc}
     */
    public function toString(
        Captions $captions,
        $ignoreDisabled = false,
        $disabledTemplate = '%s')
    {
        return $this->component->toString($captions, $ignoreDisabled, $disabledTemplate);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return array_merge(
            $this->component->serialize(),
            array(
                'hasDivider' => $this->hasDivider,
                'ignoreSelectAll' => $this->ignoreSelectAll,
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    static public function deserialize(
        FixedKeysArray $columns,
        array $serializedComponent)
    {
        /*return new self(
            $this->component->deserialize($columns, $serializedComponent),
            isset($serializedComponent['hasDivider']) && $serializedComponent['hasDivider'],
            isset($serializedComponent['ignoreSelectAll']) && $serializedComponent['ignoreSelectAll']
        );*/
        assert(false, 'ColumnFilterComponent::deserialize: This method should never be called');
    }
}
