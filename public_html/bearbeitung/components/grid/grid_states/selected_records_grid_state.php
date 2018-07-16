<?php

class SelectedRecordsGridState extends GridState
{
    public function ProcessMessages()
    {
        $primaryKeyValuesSet = ArrayWrapper::createGetWrapper()->getValue('keys', array());
        $this->getDataset()->applyFilterBasedOnPrimaryKeyValuesSet($primaryKeyValuesSet);
        $this->getDataset()->setOrderByFields($this->grid->getSortedColumns());
    }
}
