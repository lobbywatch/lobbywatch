<?php

class DeleteSelectedGridState extends AbstractCommitValuesGridState
{
    protected function getOperation()
    {
        return 'Delete';
    }

    protected function commitValues($rowValues, $newRowValues)
    {
        $this->getDataset()->Delete();
    }

    protected function handleError($message, $displayTime = 0)
    {
        $this->getDataset()->SetAllRecordsState();
        $this->ChangeState(OPERATION_VIEWALL);
        $this->setGridErrorMessage($message, $displayTime);
    }

    public function ProcessMessages()
    {
        $primaryKeys = $this->getPrimaryKeys();

        foreach ($primaryKeys as $primaryKeyValues) {
            $this->getDataset()->SetSingleRecordState($primaryKeyValues);
            $this->getDataset()->Open();

            if ($this->getDataset()->Next()) {
                $this->doProcessMessages($this->getDataset()->GetCurrentFieldValues());
            }

            $this->getDataset()->Close();
        }

        $this->ApplyState(OPERATION_VIEWALL);
    }

    private function getPrimaryKeys()
    {
        $primaryKeysArray = array();

        for ($i = 0; $i < 6; $i++) {
            if (GetApplication()->IsPOSTValueSet('rec' . $i)) {
                // TODO : move GetPrimaryKeyFieldNames function to private
                $primaryKeys = array();
                $primaryKeyNames = $this->getDataset()->GetPrimaryKeyFieldNames();
                for ($j = 0; $j < count($primaryKeyNames); $j++)
                    $primaryKeys[] = GetApplication()->GetPOSTValue('rec' . $i . '_pk' . $j);
                $primaryKeysArray[] = $primaryKeys;
            }
        }

        return $primaryKeysArray;
    }
}
