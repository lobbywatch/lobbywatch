<?php

include_once dirname(__FILE__) . '/../abstract_filter.php';
include_once dirname(__FILE__) . '/../filter_column.php';
include_once dirname(__FILE__) . '/column_filter_column.php';
include_once dirname(__FILE__) . '/column_filter_options_creator.php';
include_once dirname(__FILE__) . '/../../../utils/link_builder.php';

class ColumnFilter extends AbstractFilter
{
    const COLUMN_FILTER_HTTP_HANDLER_PARAM_NAME = 'column_filter_hname';

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

        $this->processHTTPHandlers($connection, $sourceSelect, $captions, $childrenOrder);

        return $this;
    }

    private function processHTTPHandlers(
        EngConnection $connection,
        BaseSelectCommand $sourceSelect,
        Captions $captions,
        array $order = array())
    {
        $columnName = $this->getRequestedHTTPHandlerName();
        if (!(isset($columnName) && $this->hasColumn($columnName)))
            return;

        $column = $this->columns[$columnName];
        if (!$column->isDefaultsEnabled())
            return;

        $columnSourceSelect = clone $sourceSelect;
        $this->applySiblingFilters($columnSourceSelect, $columnName, $order);

        if ($column->getFilterColumn()->typeIsDateTime()) {
            $defaultOptions = $this->createDefaultOptions(
                $column,
                $connection,
                $columnSourceSelect,
                $captions);
            $children = $this->filterComponent->getChildren();
            $workingChild = $children[$columnName];
            foreach ($defaultOptions as $key => $component) {
                $this->setEnabledComponentDeep($component, false);
                if ($key == $captions->GetMessageString('IsBlank') || $key == $captions->GetMessageString('IsNotBlank')) {
                    if (!array_key_exists($key, $workingChild->getChildren())) {
                        $workingChild->insertChild($component, $key);
                    }
                } else {
                    $workingChild->insertChild($component, $key);
                }
            }
            $this->processSelectedDateTimeValues($workingChild);
            echo SystemUtils::ToJSON($workingChild->serialize());
            exit;
        } else {
            $handler = new FilterColumnDynamicSearchHandler($connection, $columnSourceSelect, $column, $captions);
            $handler->Execute();
        }
    }

    private function processSelectedDateTimeValues(FilterComponentInterface $root) {
        $children = $root->getChildren();
        $fullYearsChildren = array();
        $fullMonthsChildren = array();
        $daysChildren = array();
        foreach ($children as $key => $child) {
            if (strpos($key, '___year') === 0) {
                $fullYearsChildren[$key] = $child;
            }
            if (strpos($key, '___month') === 0) {
                $fullMonthsChildren[$key] = $child;
            }
            if (strpos($key, '___day') === 0) {
                $daysChildren[$key] = $child;
            }
        }

        foreach ($fullYearsChildren as $fullYearsKey => $fullYearsChild) {
            $year = ' ' . substr($fullYearsKey, strlen('___year'));
            foreach ($children as $key => $child) {
                if ($key === $year) {
                    $this->setEnabledComponentDeep($child, true);
                    break;
                }
            }
        }

        foreach ($fullMonthsChildren as $fullMonthsKey => $fullMonthsChild) {
            $yearPos = strpos($fullMonthsKey, '_year');
            if ($yearPos === false) {
                continue;
            }
            $year = ' ' . substr($fullMonthsKey, $yearPos + strlen('_year'), 4);
            $month = substr($fullMonthsKey, strlen('___month'), 3);
            foreach ($children as $key => $child) {
                if ($key === $year) {
                    $yearChildren = $child->getChildren();
                    foreach ($yearChildren as $yKey => $yChild) {
                        if ($yKey === $month) {
                            $this->setEnabledComponentDeep($yChild, true);
                            break 2;
                        }
                    }
                }
            }
        }

        foreach ($daysChildren as $daysKey => $daysChild) {
            $yearPos = strpos($daysKey, '_year');
            $monthPos = strpos($daysKey, '_month');
            if ($yearPos === false || $monthPos === false) {
                continue;
            }
            $year = ' ' . substr($daysKey, $yearPos + strlen('_year'), 4);
            $month = substr($daysKey, $monthPos + strlen('_month'), 3);
            $day = ' ' . substr($daysKey, strlen('___day'), $monthPos - strlen('___day'));
            foreach ($children as $key => $child) {
                if ($key === $year) {
                    $yearChildren = $child->getChildren();
                    foreach ($yearChildren as $yKey => $yChild) {
                        if ($yKey === $month) {
                            $monthChildren = $yChild->getChildren();
                            foreach ($monthChildren as $mKey => $mChild) {
                                if ($mKey === $day) {
                                    $this->setEnabledComponentDeep($mChild, true);
                                    break 3;
                                }
                            }
                        }
                    }
                }
            }
        }

        foreach ($fullYearsChildren as $fullYearsKey => $fullYearsChild) {
            unset($children[$fullYearsKey]);
        }
        foreach ($fullMonthsChildren as $fullMonthsKey => $fullMonthsChild) {
            unset($children[$fullMonthsKey]);
        }
        foreach ($daysChildren as $daysKey => $daysChild) {
            unset($children[$daysKey]);
        }
        $root->setChildren($children);
    }

    /**
     * @return string || null
     */
    private function getRequestedHTTPHandlerName()
    {
        return GetApplication()->GetGetValue(self::COLUMN_FILTER_HTTP_HANDLER_PARAM_NAME);
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
     * @return string[]
     */
    public function getDefaultsEnabled()
    {
        $result = array();

        foreach ($this->columns as $columnName => $column) {
            if ($column->isDefaultsEnabled() && $this->dynamicallyLoadValuesFor($column->getFilterColumn()))
            {
                $result[] = $columnName;
            }
        }

        return $result;
    }

    /**
     * @param FilterColumn $column
     * @return boolean
     */
    public function dynamicallyLoadValuesFor($column) {
        return !($column->getFieldInfo()->FieldType == ftBoolean || $column->getFieldInfo()->FieldType == ftBlob);
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

    /**
     * @param string $columnName
     * @param int $numberOfValuesToDisplay
     * @return $this
     */
    public function setNumberOfValuesToDisplayFor($columnName, $numberOfValuesToDisplay = 20)
    {
        $columns = new FixedKeysArray($this->columns);
        $columns[$columnName]->setNumberOfValuesToDisplay($numberOfValuesToDisplay);

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

            $defaultOptions = array();
            if (!$this->dynamicallyLoadValuesFor($column->getFilterColumn())) {
                $defaultOptions =
                    $this->createDefaultOptions(
                        $this->columns[$columnName],
                        $connection,
                        $columnSourceSelect,
                        $captions
                    );
            }

            $columnOptions = array_merge(
                $options,
                $defaultOptions
            );

            foreach ($columnOptions as $key => $component) {
                $this->setEnabledComponentDeep($component, false);
                $columnGroup->insertChild($component, $key);
            }

            $result->insertChild($columnGroup->setEnabled(false), $columnName);
        }

        return $result;
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
            $column,
            $connection,
            $sourceSelect,
            $captions
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
                if (array_key_exists('selectedDateTimeValues', $enabledChildren)) {
                    $this->doRestoreSelectedDateTimeValues($child, $this->possibleColumns[$key], $enabledChildren['selectedDateTimeValues']);
                }
                if (array_key_exists('children', $enabledChildren)) {
                    $this->doRestoreEnabledComponents($child, $enabledChildren['children']);
                } else {
                    $this->doRestoreEnabledComponents($child, $enabledChildren);
                }
            } else {
                if (is_array($enabledChildren) && array_key_exists('type', $enabledChildren)) {
                    if ($enabledChildren['type'] == FilterComponentType::GROUP) {
                        $group = FilterGroup::deserialize($this->possibleColumns, $enabledChildren);
                        $root->insertChild($group, $key);
                    } else if ($enabledChildren['type'] == FilterComponentType::CONDITION) {
                        $condition = FilterCondition::deserialize($this->possibleColumns, $enabledChildren);
                        $root->insertChild($condition, $key);
                    }
                }
            }
        }

        return $root;
    }

    private function doRestoreSelectedDateTimeValues(
        FilterComponentInterface $root,
        FilterColumn $column,
        array $selectedDateTimeValues)
    {
        $months = array('Jan' => 1, 'Feb' => 2, 'Mar' => 3, 'Apr' => 4, 'May' => 5, 'Jun' => 6, 'Jul' => 7, 'Aug' => 8, 'Sep' => 9, 'Oct' => 10, 'Nov' => 11, 'Dec' => 12);
        foreach ($selectedDateTimeValues['fullYears'] as $value) {
            $condition = FilterCondition::datePartEquals('YEAR', $value)->setColumn($column);
            $root->insertChild($condition, sprintf('___year%s', $value));
        }
        foreach ($selectedDateTimeValues['fullMonths'] as $object) {
            $group = FilterGroup::andX(array(
                FilterCondition::datePartEquals('YEAR', $object['year']),
                FilterCondition::datePartEquals('MONTH', $months[$object['month']])
                    ->setDisplayValues(array($object['month'])),
            ))
                ->setColumn($column);
            $root->insertChild($group, sprintf('___month%s_year%s', $object['month'], $object['year']));
        }
        foreach ($selectedDateTimeValues['days'] as $object) {
            $year = $object['year'];
            $month = sprintf('%02d', $months[$object['month']]);
            $day = sprintf('%02d', $object['day']);
            $resultDate = sprintf('%s-%s-%s', $year, $month, $day);
            if ($column->typeIsDateTimeExactly()) {
                $condition = FilterCondition::dateEquals($resultDate)->setColumn($column);
            } else {
                $condition = FilterCondition::equals($resultDate)->setColumn($column);
            }
            $root->insertChild($condition, sprintf('___day%s_month%s_year%s', $object['day'], $object['month'], $object['year']));
        }
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
