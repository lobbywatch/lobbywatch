<?php

include_once dirname(__FILE__) . '/../abstract_filter.php';
include_once dirname(__FILE__) . '/../filter_column.php';
include_once dirname(__FILE__) . '/column_filter_column.php';
include_once dirname(__FILE__) . '/column_filter_options_creator.php';

class ColumnFilter extends AbstractFilter
{
    /**
     * @var ColumnFilterColumn[]
     */
    private $columns = array();

    /**
     * @var FilterColumn[]|FixedKeysArray
     */
    private $possibleColumns = array();

    /**
     * @var array
     */
    private $excludedComponents = array();

    /**
     * @param FixedKeysArray|null $possibleColumns
     */
    public function __construct(FixedKeysArray $possibleColumns = null)
    {
        $this->possibleColumns = $possibleColumns;
        $this->filterComponent = new FilterGroup(
            FilterGroupOperator::OPERATOR_AND
        );
    }

    /**
     * @param string $columnName
     * @param FilterComponentInterface[] $options
     * @param bool $includeDefaultOptions
     * @param bool $enableSearch
     * @param string $order ASC|DESC
     * @return $this
     */
    public function setOptionsFor(
        $columnName,
        array $options = array(),
        $includeDefaultOptions = null,
        $enableSearch = null,
        $order = null)
    {
        $filterColumn = $this->possibleColumns[$columnName];

        $columnOptions = array();
        foreach ($options as $key => $option) {
            $columnOption = clone $option;
            $columnOptions[$key] = $columnOption
                ->setColumn($filterColumn)
                ->setEnabled(false);
        }

        $column = array_key_exists($filterColumn->getFieldName(), $this->columns)
            ? $this->columns[$filterColumn->getFieldName()]
            : new ColumnFilterColumn($filterColumn, $columnOptions);

        $column
            ->setOptions($columnOptions)
            ->setIsDefaultsEnabled($this->getDefaultIfNull(
                $includeDefaultOptions,
                $column->isDefaultsEnabled()
            ))
            ->setIsSearchEnabled($this->getDefaultIfNull(
                $enableSearch,
                $column->isSearchEnabled()
            ))
            ->setOrder($this->getDefaultIfNull(
                $order,
                $column->getOrder()
            ));

        $this->columns[$filterColumn->getFieldName()] = $column;

        return $this;
    }

    private function getDefaultIfNull($value, $default)
    {
        return is_null($value) ? $default : $value;
    }

    /**
     * @param FixedKeysArray $possibleColumns
     * @return $this
     */
    public function setPossibleColumns(FixedKeysArray $possibleColumns)
    {
        $this->possibleColumns = $possibleColumns;

        return $this;
    }

    /**
     * @return FixedKeysArray
     */
    public function getPossibleColumns()
    {
        return $this->possibleColumns;
    }

    /**
     * @return FilterColumn[]
     */
    public function getColumns()
    {
        $result = array();

        foreach ($this->columns as $columnName => $column) {
            $result[$columnName] = $column->getFilterColumn();
        }

        return $result;
    }

    /**
     * @return boolean
     */
    public function hasColumns()
    {
        return count($this->columns) > 0;
    }

    /**
     * @param string $columnName
     * @return bool
     */
    public function hasColumn($columnName)
    {
        return array_key_exists($columnName, $this->columns);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $result = array();

        foreach ($this->columns as $columnName => $column) {
            $result[$columnName] = $column->getOptions();
        }

        return $result;
    }

    /**
     * @param string $columnName
     * @return FilterComponentInterface[]
     */
    public function getOptionsFor($columnName)
    {
        $columns = new FixedKeysArray($this->columns);

        return $columns[$columnName]->getOptions();
    }

    /**
     * {@inheritdoc}
     */
    public function toString(Captions $captions)
    {
        return $this->filterComponent->toString($captions, true);
    }

    /**
     * @param string $columnName
     * @param Captions $captions
     * @return string
     */
    public function toStringFor($columnName, Captions $captions)
    {
        if (!$this->hasColumn($columnName)) {
            return '';
        }

        $children = $this->filterComponent->getChildren();

        return $children[$columnName]->toString($captions, true);
    }

    /**
     * @return array
     */
    public function serializeEnabledComponents()
    {
        return array(
            'isEnabled' => $this->filterComponent->isEnabled(),
            'children' => $this->doSerializeEnabledComponents(
                $this->filterComponent
            )
        );
    }

    /**
     * @param EngConnection $connection
     * @param BaseSelectCommand $sourceSelect
     * @param Captions $captions
     * @return $this
     */
    public function createFilterComponent(
        EngConnection $connection,
        BaseSelectCommand $sourceSelect,
        Captions $captions)
    {
        $this->setFilterComponent($this->doCreateFilterComponent(
            $connection,
            $sourceSelect,
            $captions
        ));

        return $this;
    }

    /**
     * @param EngConnection $connection
     * @param BaseSelectCommand $sourceSelect
     * @param Captions $captions
     * @param array $enabledComponents
     * @return $this
     */
    public function restoreEnabledComponents(
        EngConnection $connection,
        BaseSelectCommand $sourceSelect,
        Captions $captions,
        array $enabledComponents)
    {
        $isEnabled = isset($enabledComponents['isEnabled'])
            ? $enabledComponents['isEnabled']
            : true;

        $enabledChildren = isset($enabledComponents['children'])
            ? $enabledComponents['children']
            : array();

        $childrenOrder = isset($enabledComponents['order'])
            ? $enabledComponents['order']
            : array();

        $this->doRestoreEnabledComponents(
            $this->filterComponent->setEnabled($isEnabled),
            $enabledChildren
        );

        $this->excludedComponents = $this->createExcludedComponents(
            $this->doRestoreEnabledComponents(
                $this->doCreateFilterComponent(
                    $connection,
                    $sourceSelect,
                    $captions,
                    $childrenOrder,
                    false
                ),
                $enabledChildren
            )
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getExcludedComponents()
    {
        return $this->excludedComponents;
    }

    /**
     * @return string[]
     */
    public function getSearchEnabled()
    {
        $result = array();

        foreach ($this->columns as $columnName => $column) {
            if ($column->isSearchEnabled()) {
                $result[] = $columnName;
            }
        }

        return $result;
    }

    /**
     * @param string $columnName
     * @param bool $isEnabled
     * @return $this
     */
    public function enableSearchFor($columnName, $isEnabled = true)
    {
        $columns = new FixedKeysArray($this->columns);
        $columns[$columnName]->setIsSearchEnabled($isEnabled);

        return $this;
    }

    /**
     * @param string $columnName
     * @return bool
     */
    public function isSearchEnabledFor($columnName)
    {
        $columns = new FixedKeysArray($this->columns);
        return $columns[$columnName]->isSearchEnabled();
    }

    /**
     * @return boolean
     */
    public function isColumnActive($columnName)
    {
        if (!$this->hasColumn($columnName)) {
            return false;
        }

        $children = $this->filterComponent->getChildren();

        return !$children[$columnName]->isCommandFilterEmpty();
    }

    /**
     * @param string $columnName
     * @param string $dir
     * @return $this
     */
    public function setOrderFor($columnName, $dir)
    {
        $columns = new FixedKeysArray($this->columns);
        $columns[$columnName]->setOrder($dir);

        return $this;
    }

    private function doCreateFilterComponent(
        EngConnection $connection,
        BaseSelectCommand $sourceSelect,
        Captions $captions,
        array $order = array(),
        $forced = true)
    {
        $result = new FilterGroup(FilterGroupOperator::OPERATOR_AND);
        $selectHasCondition = $sourceSelect->hasCondition();

        foreach ($this->columns as $columnName => $column) {
            $options = $column->getOptions();
            $columnSourceSelect = clone $sourceSelect;
            $hasFilters = $this->applySiblingFilters($columnSourceSelect, $columnName, $order)
                || $selectHasCondition;

            if (!$forced && !$hasFilters) {
                $currentChildren = $this->filterComponent->getChildren();
                if (array_key_exists($columnName, $currentChildren)) {
                    $result->insertChild(
                        $currentChildren[$columnName],
                        $columnName
                    );
                }
                continue;
            }

            $columnGroup = new FilterGroup(FilterGroupOperator::OPERATOR_OR);

            $columnOptions = array_merge(
                $options,
                $this->createDefaultOptions(
                    $this->columns[$columnName],
                    $connection,
                    $columnSourceSelect,
                    $captions
                )
            );

            foreach ($columnOptions as $key => $component) {
                $this->setEnabledComponentDeep($component, false);
                $columnGroup->insertChild($component, $key);
            }

            $result->insertChild($columnGroup->setEnabled(false), $columnName);
        }

        return $result;
    }

    private function createExcludedComponents(
        FilterComponentInterface $actualFilterComponent)
    {
        $current = $this->doSerializeComponents($this->filterComponent);
        $actual = $this->doSerializeComponents($actualFilterComponent);

        return $this->arrayDiffRecursive($current, $actual);
    }

    private function arrayDiffRecursive(array $arr1, array $arr2)
    {
        $diff = array_diff_key($arr1, $arr2);
        $intersect = array_intersect_key($arr1, $arr2);

        foreach (array_keys($intersect) as $k) {
            if (is_array($arr1[$k]) && is_array($arr2[$k])) {
                $d = $this->arrayDiffRecursive($arr1[$k], $arr2[$k]);

                if ($d) {
                    $diff[$k] = $d;
                }
            }
        }

        return $diff;
    }

    private function setEnabledComponentDeep(
        FilterComponentInterface $component,
        $isEnabled)
    {
        $component->setEnabled($isEnabled);
        foreach ($component->getChildren() as $child) {
            $this->setEnabledComponentDeep($child, $isEnabled);
        }
    }

    private function createDefaultOptions(
        ColumnFilterColumn $column,
        EngConnection $connection,
        BaseSelectCommand $sourceSelect,
        Captions $captions)
    {
        if (!$column->isDefaultsEnabled()) {
            return array();
        }

        return ColumnFilterOptionsCreator::create(
            $column->getFilterColumn(),
            $connection,
            $sourceSelect,
            $captions,
            $column->getOrder()
        );
    }

    private function doSerializeComponents(
        FilterComponentInterface $root)
    {
        $result = array();

        $children = $root->getChildren();
        foreach ($children as $key => $child) {
            $result[$key] = $this->doSerializeComponents($child);
        }

        return $result;
    }

    private function doSerializeEnabledComponents(
        FilterComponentInterface $root)
    {
        $result = array();

        $children = $root->getChildren();
        foreach ($children as $key => $child) {
            if ($child->isEnabled()) {
                $result[$key] = $this->doSerializeEnabledComponents($child);
            }
        }

        return $result;
    }

    private function doRestoreEnabledComponents(
        FilterComponentInterface $root,
        array $enabledComponents)
    {
        $children = $root->getChildren();
        foreach ($enabledComponents as $key => $enabledChildren) {
            if (array_key_exists($key, $children)) {
                $child = $children[$key];
                $child->setEnabled(true);
                $this->doRestoreEnabledComponents($child, $enabledChildren);
            }
        }

        return $root;
    }

    private function applySiblingFilters(
        BaseSelectCommand $sourceSelect,
        $columnName,
        array $order = array())
    {
        $result = false;

        $reversedOrder = array_reverse($order);
        $index = array_search($columnName, $reversedOrder);
        $index = $index === false ? 0 : $index + 1;

        $children = $this->filterComponent->getChildren();
        foreach (array_slice($reversedOrder, $index) as $siblingColumnName) {
            if (!isset($children[$siblingColumnName])) {
                continue;
            }

            $sibling = $children[$siblingColumnName];
            if (!$sibling->isCommandFilterEmpty()) {
                $sourceSelect->addCompositeFilter(
                    $sibling->getCommandFilter()
                );

                $result = true;
            }
        }

        return $result;
    }
}
