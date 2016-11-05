<?php

class CommitInsertedValuesGridState extends AbstractCommitValuesGridState
{
    protected function getOperation()
    {
        return 'Insert';
    }

    protected function handleError($message, $displayTime = 0)
    {
        $this->ChangeState(OPERATION_INSERT);
        parent::handleError($message, $displayTime);
    }

    protected function getNewRowValues($rowValues)
    {
        return $this->GetDataset()->GetCurrentFieldValues();
    }

    protected function refreshRowValues($rowValues)
    {
        return array_merge(
            $rowValues,
            $this->getDataset()->getInsertFieldValues()
        );
    }

    public function ProcessMessages()
    {
        $this->getDataset()->Insert();
        $this->doProcessMessages(array());
    }

    protected function getRealEditColumns()
    {
        return $this->grid->GetInsertColumns();
    }

    public function SetInternalStateSwitch($primaryKeys)
    {
        $this->grid->SetInternalStateSwitch($primaryKeys);
    }
}
