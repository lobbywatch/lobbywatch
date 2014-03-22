<?php

include_once dirname(__FILE__) . '/' . 'grid_state.php';

class DeleteSelectedGridState extends GridState {
    protected function DoCanChangeData(&$rowValues, &$message) {
        $cancel = false;
        $this->grid->BeforeDeleteRecord->Fire(array($this->GetPage(), &$rowValues, &$cancel, &$message,
            $this->GetDataset()->GetName()));
        return !$cancel;
    }

    protected function DoAfterChangeData($rowValues) {
        $this->grid->AfterDeleteRecord->Fire(array($this->GetPage(), &$rowValues, $this->GetDataset()->GetName()));
    }

    public function ProcessMessages() {
        $primaryKeysArray = array();
        for ($i = 0; $i < GetApplication()->GetPOSTValue('recordCount'); $i++) {
            if (GetApplication()->IsPOSTValueSet('rec' . $i)) {
                // TODO : move GetPrimaryKeyFieldNames function to private
                $primaryKeys = array();
                $primaryKeyNames = $this->grid->GetDataset()->GetPrimaryKeyFieldNames();
                for ($j = 0; $j < count($primaryKeyNames); $j++)
                    $primaryKeys[] = GetApplication()->GetPOSTValue('rec' . $i . '_pk' . $j);
                $primaryKeysArray[] = $primaryKeys;
            }
        }

        $inlineInsertedRecordPrimaryKeyNames = GetApplication()->GetSuperGlobals()->GetPostVariablesIf(
            create_function('$str', 'return StringUtils::StartsWith($str, \'inline_inserted_rec_\') && !StringUtils::Contains($str, \'pk\');')
        );

        foreach ($inlineInsertedRecordPrimaryKeyNames as $name => $value) {
            $primaryKeys = array();
            $primaryKeyNames = $this->grid->GetDataset()->GetPrimaryKeyFieldNames();
            for ($i = 0; $i < count($primaryKeyNames); $i++)
                $primaryKeys[] = GetApplication()->GetSuperGlobals()->GetPostValue($name . '_pk' . $i);
            $primaryKeysArray[] = $primaryKeys;
        }

        foreach ($primaryKeysArray as $primaryKeyValues) {
            $this->grid->GetDataset()->SetSingleRecordState($primaryKeyValues);
            $this->grid->GetDataset()->Open();

            if ($this->grid->GetDataset()->Next()) {
                $message = '';

                $fieldValues = $this->grid->GetDataset()->GetCurrentFieldValues();
                if ($this->CanChangeData($fieldValues, $message)) {
                    try {
                        $this->grid->GetDataset()->Delete();
                        $this->DoAfterChangeData($fieldValues);
                    } catch (Exception $e) {
                        $this->grid->GetDataset()->SetAllRecordsState();
                        $this->ChangeState(OPERATION_VIEWALL);
                        $this->SetGridErrorMessage($e);
                        return;
                    }
                } else {
                    $this->grid->GetDataset()->SetAllRecordsState();
                    $this->ChangeState(OPERATION_VIEWALL);
                    $this->SetGridSimpleErrorMessage($message);
                    return;
                }
            }
            $this->grid->GetDataset()->Close();
        }

        $this->ApplyState(OPERATION_VIEWALL);
    }
}
