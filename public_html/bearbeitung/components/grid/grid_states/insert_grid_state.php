<?php

class InsertGridState extends GridState {

    private function processParentFieldValue() {
        $getWrapper = ArrayWrapper::createGetWrapper();
        $parentFieldName = $getWrapper->getValue('parent-field-name');
        $parentFieldValue = $getWrapper->getValue('parent-field-value');
        if (isset($parentFieldName) && isset($parentFieldValue)) {
            $column = $this->grid->getInsertColumn($parentFieldName);
            if (isset($column)) {
                $column->setControlValue($parentFieldValue);
            }
        }
    }

    private function customizeColumnDefaultValues(FixedKeysArray $defaultValues) {
        $handled = false;
        $this->grid->OnCustomDefaultValues->Fire(array(&$defaultValues, &$handled));
        if ($handled) {
            foreach ($this->grid->GetInsertColumns() as $column) {
                $column->GetEditControl()->SetValue($defaultValues[$column->GetFieldName()]);
            }
        }
    }

    public function ProcessMessages()
    {
        $columnDefaultValues = array();
        foreach ($this->grid->GetInsertColumns() as $column) {
            $column->ProcessMessages();
            $columnDefaultValues[$column->GetFieldName()] = $column->GetEditControl()->GetDisplayValue();
        }

        $this->processParentFieldValue();

        $this->customizeColumnDefaultValues(new FixedKeysArray($columnDefaultValues));
    }
}
