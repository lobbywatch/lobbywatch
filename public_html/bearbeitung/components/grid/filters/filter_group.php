<?php

include_once dirname(__FILE__) . '/filter_component_interface.php';
include_once dirname(__FILE__) . '/filter_component_type.php';
include_once dirname(__FILE__) . '/filter_group_operator.php';

class FilterGroup implements FilterComponentInterface
{
    /**
     * @var FilterComponentInterface[]
     */
    private $children = array();

    /**
     * @var bool
     */
    private $isEnabled = true;

    /**
     * @param string                     $operator
     * @param FilterComponentInterface[] $children
     * @param bool                       $isEnabled
     */
    public function __construct(
        $operator = FilterGroupOperator::OPERATOR_AND,
        array $children = array(),
        $isEnabled = true)
    {
        $this->operator = $operator;
        $this->children = $children;
        $this->isEnabled = $isEnabled;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * {@inheritdoc}
     */
    public function setChildren(array $children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function insertChild(FilterComponentInterface $child, $key = null)
    {
        $keyToInsert = is_null($key)
            ? count($this->children)
            : $key;

        $this->children[$keyToInsert] = $child;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * {@inheritdoc}
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommandFilter()
    {
        $isNone = $this->operator === FilterGroupOperator::OPERATOR_NONE;
        $result = new CompositeFilter($isNone ? 'OR' : $this->operator);

        foreach ($this->children as $child) {
            if ($child->isEnabled() && !$child->isCommandFilterEmpty()) {
                $result->addFilter(
                    $child->getFieldInfo(),
                    $child->getCommandFilter()
                );
            }
        }

        return $isNone ? new NotPredicateFilter($result) : $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldInfo()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        foreach ($this->children as $child) {
            if (!$child->isEmpty()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isCommandFilterEmpty()
    {
        return $this->getCommandFilter()->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function setEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        $serializedChildren = array();
        foreach ($this->children as $key => $child) {
            $serializedChildren[$key] = $child->serialize();
        }

        return array(
            'type' => FilterComponentType::GROUP,
            'isEnabled' => $this->isEnabled,
            'operator' => $this->operator,
            'children' => $serializedChildren,
        );
    }

    /**
     * {@inheritdoc}
     */
    static public function deserialize(
        FixedKeysArray $columns,
        array $serializedComponent)
    {
        $keys = array_keys($serializedComponent);
        sort($keys);
        if (implode(',', $keys) !== 'children,isEnabled,operator,type') {
            return new self();
        }

        $children = array();
        foreach ($serializedComponent['children'] as $key => $serializedChild) {
            $children[$key] = $serializedChild['type'] === FilterComponentType::GROUP
                ? FilterGroup::deserialize($columns, $serializedChild)
                : FilterCondition::deserialize($columns, $serializedChild);
        }

        $instance = new self(
            $serializedComponent['operator'],
            $children
        );

        $instance->setEnabled($serializedComponent['isEnabled']);

        return $instance;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(
        Captions $captions,
        $ignoreDisabled = false,
        $disabledTemplate = '%s')
    {
        $isNone = $this->operator === FilterGroupOperator::OPERATOR_NONE;
        $childrenConditions = array();

        foreach ($this->getVisibleChildren($ignoreDisabled) as $child) {
            $childCondition = $child->toString(
                $captions,
                $ignoreDisabled,
                $this->isEnabled() ? $disabledTemplate : '%s'
            );

            if (!empty($childCondition)) {
                $childrenConditions[] = $childCondition;
            }
        }

        $operator = $isNone
            ? $captions->GetMessageString('OperatorStringOr')
            : $captions->getMessageString('OperatorString' . ucfirst(strtolower($this->operator)));

        if (count($childrenConditions) > 1) {
            $result = '(' . implode(') ' . $operator . ' (', $childrenConditions) . ')';
        } else {
            $result = implode(' ' . $operator . ' ', $childrenConditions);
        }

        if (empty($result)) {
            return '';
        }

        if ($isNone) {
            $result = sprintf(
                '%s (%s)',
                $captions->GetMessageString('OperatorStringNone'),
                $result
            );
        }

        if (!$this->isEnabled()) {
            return sprintf($disabledTemplate, $result);
        }

        return $result;
    }

    private function getVisibleChildren($ignoreDisabled = false)
    {
        $result = array();
        $suitableForIn = $this->getOperator() === FilterGroupOperator::OPERATOR_OR;

        foreach ($this->children as $child) {
            if ($child->isEmpty() || (!$child->isEnabled() && $ignoreDisabled)) {
                continue;
            }

            $result[] = $child;

            if ($child instanceof FilterGroup
                || $child->getOperator() !== FilterConditionOperator::EQUALS
                || !$child->isEnabled()) {
                $suitableForIn = false;
            }
        }

        if (!$suitableForIn || count($result) <= 1) {
            return $result;
        }

        $column = $result[0]->getColumn();
        $values = array();
        foreach ($result as $child) {
            if (is_null($child->getColumn()) || $child->getColumn() != $column) {
                return $result;
            }

            $values[] = current($child->getDisplayValues());
        }

        return array(
            FilterCondition::in($values)->setColumn($column)
        );
    }

    /**
     * @param array $children
     * @param bool $isEnabled
     * @return FilterGroup
     */
    static public function orX(array $children, $isEnabled = true)
    {
        return new self(
            FilterGroupOperator::OPERATOR_OR,
            $children,
            $isEnabled
        );
    }

    /**
     * @param array $children
     * @param bool $isEnabled
     * @return FilterGroup
     */
    static public function andX(array $children, $isEnabled = true)
    {
        return new self(
            FilterGroupOperator::OPERATOR_AND,
            $children,
            $isEnabled
        );
    }

    /**
     * @param FilterColumn $column
     * @return $this
     */
    public function setColumn(FilterColumn $column)
    {
        foreach ($this->children as $child) {
            $child->setColumn($column);
        }

        return $this;
    }
}
