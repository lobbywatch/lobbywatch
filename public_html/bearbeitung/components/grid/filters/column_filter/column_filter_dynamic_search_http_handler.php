<?php

class FilterColumnDynamicSearchHandler
{
    /**
     * @param EngConnection $connection
     * @param BaseSelectCommand $sourceSelect
     * @param ColumnFilterColumn $column
     * @param Captions $captions
     */
    public function __construct($connection, $sourceSelect, ColumnFilterColumn $column, Captions $captions) {
        $this->connection = $connection;
        $this->sourceSelect = $sourceSelect;
        $this->column = $column;
        $this->captions = $captions;
    }

    /**
     * @param SMDateTime|string $value
     * @param  FieldType $fieldType
     * @return string
     */
    private function getValueFromMap($value, $fieldType) {
        if ($value === true) {
            return $this->captions->getMessageString('True');
        } elseif ($value === false) {
            return $this->captions->getMessageString('False');
        } elseif ($this->isTimeObject($value, $fieldType)) {
            return $value->ToString('H:i:s');
        }

        return "$value";
    }

    /**
     * @param SMDateTime|string $value
     * @param  FieldType $fieldType
     * @return string
     */
    private function getIdValueFromMap($value, $fieldType) {
        if ($this->isTimeObject($value, $fieldType)) {
            return $value->ToString('H:i:s');
        } else {
            return $value;
        }
    }

    /**
     * @param SMDateTime|string $value
     * @param  FieldType $fieldType
     * @return boolean
     */
    private function isTimeObject($value, $fieldType) {
        return $fieldType == FieldType::Time && is_object($value) && ($value instanceof SMDateTime);
    }

    public function Execute() {
        $filterColumn = $this->column->getFilterColumn();
        $fieldInfo = $filterColumn->getFieldInfo();
        $displayFieldInfo = $filterColumn->getDisplayFieldInfo();

        $this->sourceSelect->addFieldInfo($fieldInfo);
        $this->sourceSelect->addFieldInfo($displayFieldInfo);
        $this->sourceSelect->addDistinct($fieldInfo->getNameInDataset(), $fieldInfo->FieldType == ftDateTime);
        $this->sourceSelect->setSelects(array(
            $fieldInfo->getNameInDataset(),
            $displayFieldInfo->getNameInDataset()
        ));

        $this->sourceSelect->setOrderBy(array(
            new SortColumn($fieldInfo === $displayFieldInfo ? 1 : 2, $this->column->getOrder()),
        ));

        $getWrapper = ArrayWrapper::createGetWrapper();

        $term = trim($getWrapper->getValue('term', ''));
        if (!empty($term)) {
            $this->sourceSelect->AddFieldFilter(
                $displayFieldInfo->getNameInDataset(),
                new FieldFilter('%'.$term.'%', 'ILIKE', true)
            );
        }

        $excludedValues = $getWrapper->getValue('excludedValues', array());
        foreach ($excludedValues as $value) {
            $this->sourceSelect->AddFieldFilter($displayFieldInfo->getNameInDataset(), FieldFilter::DoesNotEqual($value, false));
        }

        header('Content-Type: application/json; charset=utf-8');

        $dataReader = $this->connection->CreateDataReader($this->sourceSelect->getSQL(false));
        $dataReader->addFieldInfo($displayFieldInfo);
        $dataReader->addFieldInfo($fieldInfo);

        $dataReader->Open();

        $result = array();
        $valueCount = 0;

        while($dataReader->Next()) {
            $result[] = array(
                'id' => $this->getIdValueFromMap($dataReader->GetFieldValueByName($fieldInfo->getNameInDataset()), $fieldInfo->FieldType),
                'value' => $this->getValueFromMap($dataReader->GetFieldValueByName($displayFieldInfo->getNameInDataset()), $displayFieldInfo->FieldType)
            );

            if (++$valueCount >= $this->column->getNumberOfValuesToDisplay()) {
                break;
            }
        }

        $dataReader->Close();

        echo SystemUtils::ToJSON($result);

        exit;
    }

}
