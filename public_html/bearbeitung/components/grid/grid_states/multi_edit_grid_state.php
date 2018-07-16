<?php

include_once dirname(__FILE__) . '/' . 'selected_records_grid_state.php';

class MultiEditGridState extends SelectedRecordsGridState
{
    public function ProcessMessages()
    {
        parent::ProcessMessages();

        $columns = $this->grid->GetMultiEditColumns();

        $fieldNames = array();
        foreach ($columns as $column) {
            $fieldNames[] = $column->GetFieldName();
        }

        $identicalFieldsValues = $this->getDataset()->getIdenticalFieldsValues($fieldNames);

        foreach ($columns as $column) {
            $controlValue = array_key_exists($column->GetName(), $identicalFieldsValues) ? $identicalFieldsValues[$column->GetName()] : null;
            $column->setControlValue($controlValue);
        }
    }
}
