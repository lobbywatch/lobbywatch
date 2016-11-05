<?php

class EditGridState extends GridState
{
    public function ProcessMessages()
    {
        $primaryKeyValues = $this->grid->GetPrimaryKeyValuesFromGet();

        $this->getDataset()->SetSingleRecordState($primaryKeyValues);
        $this->getDataset()->Open();

        if ($this->getDataset()->Next()) {

            $this->CheckRLSEditGrant();
            $columns = $this->grid->GetEditColumns();
            foreach ($columns as $column) {
                $column->ProcessMessages();
            }

        } else {
            RaiseCannotRetrieveSingleRecordError();
        }

        $this->getDataset()->Close();
    }
}
