<?php

class CommitDeleteGridState extends AbstractCommitValuesGridState
{
    private $useGetToExtractPrimaryKeys = false;

    protected function getOperation()
    {
        return 'Delete';
    }

    protected function handleError($message, $displayTime = 0)
    {
        $this->ChangeState(OPERATION_DELETE);
        $this->setGridErrorMessage($message, $displayTime);
    }

    protected function commitValues($rowValues, $newRowValues)
    {
        $this->getDataset()->Delete();
    }

    public function ProcessMessages()
    {
        $method = $this->useGetToExtractPrimaryKeys ? METHOD_GET : METHOD_POST;
        $rowValues = $this->getSingleRowValues($method);

        if (!$rowValues) {
            $this->getDataset()->Close();
            $this->getDataset()->Open();
            $this->ApplyState(OPERATION_VIEWALL);
            return;
        }

        $this->doProcessMessages($rowValues);
    }

    public function SetUseGetToExtractPrimaryKeys($value)
    {
        $this->useGetToExtractPrimaryKeys = $value;
    }
}
