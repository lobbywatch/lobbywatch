<?php

include_once dirname(__FILE__) . '/custom_edit_column.php';

class MultiLevelLookupEditColumn extends CustomEditColumn
{
    public function __construct($caption,
        $fieldName,
        $editControl,
        $dataset,
        $allowSetToNull = false, $allowSetToDefault = false)
    {
        parent::__construct($caption, $fieldName, $editControl, $dataset, $allowSetToNull, $allowSetToDefault);
    }

    public function PrepareEditorControl()
    {
        $this->GetEditControl()->SetValue($this->GetDataset()->GetFieldValueByName($this->GetFieldName()));
        $this->GetEditControl()->ProcessLevelValues();
    }

    public function SetControlValuesFromDataset()
    {
        if (in_array(GetOperation(), array(OPERATION_EDIT, OPERATION_COPY)))
        {
            $this->GetEditControl()->SetValue(
                $this->GetDataset()->GetFieldValueByName($this->GetFieldName())
            );
            $this->GetEditControl()->ProcessLevelValues();
        }
    }
}
