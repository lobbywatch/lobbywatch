<?php

class CommitEditedValuesGridState extends AbstractCommitValuesGridState
{
    protected function getOperation()
    {
        return 'Update';
    }

    protected function getNewRowValues($rowValues)
    {
        return array_merge(
            $rowValues,
            $this->getSingleRowValues(METHOD_POST)
        );
    }

    protected function handleError($errorMessage, $displayTime = 0)
    {
        $this->ChangeState(OPERATION_EDIT);
        parent::handleError($errorMessage, $displayTime);
    }

    protected function getRealEditColumns()
    {
        return $this->grid->GetEditColumns();
    }

    public function ProcessMessages()
    {
        $rowValues = $this->getSingleRowValues(METHOD_POST);
        $this->CheckRLSEditGrant();

        if (!$rowValues) {
            return;
        }

        $this->getDataset()->Edit();
        $this->doProcessMessages($rowValues);
    }

    public function SetInternalStateSwitch($primaryKeys)
    {
        $this->grid->SetInternalStateSwitch($primaryKeys);
    }
}
