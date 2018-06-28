<?php

include_once dirname(__FILE__) . '/' . 'commit_edited_values_grid_state.php';

class CommitMultiUploadGridState extends CommitInsertedValuesGridState {

    protected function handleError($message, $displayTime = 0)
    {
        $this->ChangeState(OPERATION_MULTI_UPLOAD);
        parent::handleError($message, $displayTime);
    }

    protected function getRealEditColumns()
    {
        return $this->grid->GetMultiUploadColumns();
    }

}
