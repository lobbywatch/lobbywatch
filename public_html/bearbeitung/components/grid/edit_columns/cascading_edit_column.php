<?php

include_once dirname(__FILE__) . '/custom_edit_column.php';

class CascadingEditColumn extends CustomEditColumn
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
        /** @var CascadingEditor $editControl */
        $editControl = $this->GetEditControl();
        $editControl->SetValue($this->GetDataset()->GetFieldValueByName($this->GetFieldName()));
        $editControl->ProcessLevelValues();
    }

    /** @inheritdoc */
    public function setControlValue($value) {
        parent::setControlValue($value);

        /** @var CascadingEditor $editControl */
        $editControl = $this->GetEditControl();
        $editControl->ProcessLevelValues();
    }

    public function SetControlValuesFromDataset()
    {
        if (in_array(GetOperation(), array(OPERATION_INSERT, OPERATION_EDIT, OPERATION_COPY)))
        {
            if (GetOperation() == OPERATION_INSERT) {
                $insertValue = $this->GetInsertDefaultValue();
                if (isset($insertValue))
                    $this->setControlValue($insertValue);
                else
                    $this->setControlValue($this->GetDataset()->GetFieldValueByName($this->GetFieldName()));
            } else {
                $this->setControlValue($this->GetDataset()->GetFieldValueByName($this->GetFieldName()));
            }
        }
    }
}
