<?php

class SingleRecordGridState extends GridState
{
    public function ProcessMessages()
    {
        $primaryKeyValues = array();
        ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);
        $this->getDataset()->SetSingleRecordState($primaryKeyValues);
    }
}
