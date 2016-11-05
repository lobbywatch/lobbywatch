<?php

include dirname(__FILE__) . '/../conditional_filter_group.php';
include dirname(__FILE__) . '/column_filter_component.php';

class ColumnFilterOptionsCreator
{
    private function __construct()
    {
    }

    /**
     * @param FilterColumn      $column
     * @param EngConnection     $connection
     * @param BaseSelectCommand $sourceSelect
     * @param Captions          $captions
     * @param string            $order
     *
     * @return FilterGroup
     */
    static public function create(
        FilterColumn $column,
        EngConnection $connection,
        BaseSelectCommand $sourceSelect,
        Captions $captions,
        $order = 'ASC')
    {
        switch ($column->getFieldInfo()->FieldType) {
            case ftDate:
                return self::createDateOptionsFromRows(
                    $column,
                    self::getRows($column, $connection, $sourceSelect, 'ASC'),
                    $captions,
                    $order
                );
            case ftDateTime:
                return self::createDateOptionsFromRows(
                    $column,
                    self::getRows($column, $connection, $sourceSelect, 'ASC'),
                    $captions,
                    $order,
                    true
                );
            case ftBoolean:
                return self::createScalarOptionsFromRows(
                    $column,
                    self::getRows($column, $connection, $sourceSelect, $order),
                    $captions
                );
            case ftBlob:
                return self::createBlankOptions($column, $captions);
            default:
                return self::createScalarOptionsFromRows(
                    $column,
                    self::getRows($column, $connection, $sourceSelect, $order),
                    $captions
                );
        }
    }

    static private function createScalarOptionsFromRows(
        FilterColumn $column,
        array $rows,
        Captions $captions)
    {
        $result = array();
        $hasBlank = false;

        foreach ($rows as $row) {
            $value = $row[$column->getFieldInfo()->Name];

            if (is_null($value)) {
                $hasBlank = true;
                continue;
            }

            $displayValue = self::getValueFromMap(
                $row[$column->getDisplayFieldName()],
                $captions
            );

            $result[$displayValue] = !is_null($value)
                ? new FilterCondition(
                    $column,
                    FilterConditionOperator::EQUALS,
                    array($value),
                    array(trim($displayValue))
                ) : new FilterCondition(
                    $column,
                    FilterConditionOperator::IS_BLANK
                );
        }

        if ($hasBlank && count($rows) > 1) {
            return array_merge($result, self::createBlankOptions(
                $column,
                $captions
            ));
        }

        return $result;
    }

    static private function getValueFromMap($value, Captions $captions)
    {
        if ($value === true) {
            return $captions->getMessageString('True');
        } elseif ($value === false) {
            return $captions->getMessageString('False');
        }

        return " $value";
    }

    static private function createBlankOptions(
        FilterColumn $column,
        Captions $captions)
    {
        return array(
            $captions->GetMessageString('IsBlank') => new ColumnFilterComponent(new FilterCondition(
               $column,
               FilterConditionOperator::IS_BLANK
            ), true, true),
            $captions->GetMessageString('IsNotBlank') => new ColumnFilterComponent(new FilterCondition(
               $column,
               FilterConditionOperator::IS_NOT_BLANK
            ), false, true),
        );
    }

    static private function createDateOptionsFromRows(
        FilterColumn $column,
        array $rows,
        Captions $captions,
        $order,
        $isDateTime = false)
    {
        $result = self::createDateComponentsFromTree(
            $column,
            self::createDatesTree($column->getFieldName(), $rows, $order),
            $isDateTime
                ? FilterConditionOperator::DATE_EQUALS
                : FilterConditionOperator::EQUALS
        );

        $hasBlank = false;
        foreach ($rows as $row) {
            foreach ($row as $value) {
                if (is_null($value)) {
                    $hasBlank = true;
                    break 2;
                }
            }
        }

        if ($hasBlank && count($rows) > 1) {
            return array_merge($result, self::createBlankOptions(
                $column,
                $captions
            ));
        }

        return $result;
    }

    static private function createDateComponentsFromTree(
        FilterColumn $column,
        array $tree,
        $conditionOperator)
    {
        $result = array();

        foreach ($tree as $year => $months) {
            $yearChildren = array();

            foreach ($months as $month => $days) {
                $monthChildren = array();

                foreach ($days as $day => $date) {
                    $monthChildren[" $day"] = new FilterCondition(
                        $column,
                        $conditionOperator,
                        array($date)
                    );
                }

                $monthDate = date_parse($month);
                $yearChildren[$month] = ConditionalFilterGroup::orX($monthChildren)
                    ->setFilterComponent(FilterGroup::andX(array(
                        FilterCondition::datePartEquals('YEAR', $year)->setColumn($column),
                        FilterCondition::datePartEquals('MONTH', $monthDate['month'])
                            ->setDisplayValues(array($month))
                            ->setColumn($column),
                    )))
                    ->setColumn($column);
            }

            $result[" $year"] = ConditionalFilterGroup::orX($yearChildren)
                ->setFilterComponent(
                    FilterCondition::datePartEquals('YEAR', $year)->setColumn($column)
                )
                ->setColumn($column);
        }

        return $result;
    }

    static private function createDatesTree($fieldName, $rows, $order)
    {
        $result = array();

        foreach ($rows as $row) {
            $date = $row[$fieldName];

            if (!$date instanceof SMDateTime) {
                continue;
            }

            $dateAsString = $date->ToAnsiSQLString(false);

            $year = $date->format('Y');
            $month = $date->format('M');
            $day = $date->format('j');

            if (!isset($result[$year])) {
                $result[$year] = array(
                    $month => array($day => $dateAsString),
                );
            } elseif (!isset($result[$year][$month])) {
                $result[$year][$month] = array($day => $dateAsString);
            } else {
                $result[$year][$month][$day] = $dateAsString;
            }
        }

        ksort($result);

        if ($order === 'DESC') {
            return array_reverse($result, true);
        }

        return $result;
    }

    static private function getRows(
        FilterColumn $column,
        EngConnection $connection,
        BaseSelectCommand $sourceSelect,
        $order)
    {
        $fieldInfo = $column->getFieldInfo();
        $displayFieldInfo = $column->getDisplayFieldInfo();

        $sourceSelect->addFieldInfo($fieldInfo);
        $sourceSelect->addFieldInfo($displayFieldInfo);
        $sourceSelect->addDistinct($fieldInfo->getNameInDataset(), $fieldInfo->FieldType == ftDateTime);
        $sourceSelect->setSelects(array(
            $fieldInfo->getNameInDataset(),
            $displayFieldInfo->getNameInDataset()
        ));

        $sourceSelect->setOrderBy(array(
            new SortColumn($fieldInfo === $displayFieldInfo ? 1 : 2, $order),
        ));

        $dataReader = $connection->CreateDataReader($sourceSelect->getSQL(false));
        $dataReader->addFieldInfo($displayFieldInfo);
        $dataReader->addFieldInfo($fieldInfo);

        $result = array();
        $dataReader->Open();

        while($dataReader->Next()) {
            $result[] = array(
                $displayFieldInfo->getNameInDataset() => $dataReader->GetFieldValueByName(
                    $displayFieldInfo->getNameInDataset()
                ),
                $fieldInfo->getNameInDataset() => $dataReader->GetFieldValueByName(
                    $fieldInfo->getNameInDataset()
                ),
            );
        }

        $dataReader->Close();

        return $result;
    }
}
