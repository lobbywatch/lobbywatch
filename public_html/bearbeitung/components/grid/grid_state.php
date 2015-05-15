<?php

include_once dirname(__FILE__) . '/' . 'commit_values_grid_state.php';

abstract class GridState {
    /**
     * @var Grid
     */
    protected $grid;

    public function __construct(Grid $Grid) {
        $this->grid = $Grid;
        $this->GetDataset()->OnBeforePost->AddListener('OnBeforePostHandler', $this);
    }

    protected function GetPage() {
        return $this->grid->GetPage();
    }

    protected function ChangeState($stateIdentifier) {
        GetApplication()->SetOperation($stateIdentifier);
        $this->grid->SetState($stateIdentifier);
    }

    protected function ApplyState($stateIdentifier) {
        $this->ChangeState($stateIdentifier);
        $this->grid->GetState()->ProcessMessages();
    }

    protected function GetDataset() {
        return $this->grid->GetDataset();
    }

    /**
     * @param array $oldValues associative array (fieldNames => fieldValues) of old values
     * @param array $newValues associative array (fieldNames => fieldValues) of new values
     * @param IDataset|Dataset $dataset dataset where changes between old and new values must be written
     */
    protected function WriteChangesToDataset($oldValues, $newValues, Dataset $dataset) {
        foreach ($newValues as $fieldName => $fieldValue)
            if ($dataset->DoNotRewriteUnchangedValues()) {
                if (!isset($oldValues[$fieldName]) || ($oldValues[$fieldName] != $fieldValue))
                    $dataset->SetFieldValueByName($fieldName, $fieldValue);
            } else {
                $dataset->SetFieldValueByName($fieldName, $fieldValue);
            }
    }

    protected function SetGridSimpleErrorMessage($message, $decodeMessage = true) {
        if ($decodeMessage)
            $this->grid->SetErrorMessage($this->GetPage()->RenderText($message));
        else
            $this->grid->SetErrorMessage($message);
    }

    /**
     * @param Exception $exception
     * @return string
     */
    protected function ExceptionToErrorMessage($exception) {
        $result = $exception->getMessage();
        if (defined('DEBUG_LEVEL') && DEBUG_LEVEL > 0)
            $result .= '<br>Program trace: <br>' . FormatExceptionTrace($exception);
        return $result;
    }

    /**
     * @param SMException[] $exceptions
     * @return string
     */
    protected function ExceptionsToErrorMessage($exceptions) {
        $result = '';
        foreach ($exceptions as $exception) {
            if (is_subclass_of($exception, 'SMException'))
                AddStr($result, $exception->getLocalizedMessage($this->GetPage()->GetLocalizerCaptions()), '<br><br>');
            else
                AddStr($result, $exception->getMessage(), '<br><br>');

            if (defined('DEBUG_LEVEL') && DEBUG_LEVEL > 0)
                $result .= '<br>Program trace: <br>' . FormatExceptionTrace($exception);
        }
        return $result;
    }

    protected function SetGridErrorMessage($exception) {
        $this->SetGridSimpleErrorMessage(
            $this->ExceptionToErrorMessage($exception), false);
    }

    protected function SetGridErrorMessages($exceptions) {
        $this->SetGridSimpleErrorMessage(
            $this->ExceptionsToErrorMessage($exceptions), false);
    }

    protected function DoCanChangeData(&$rowValues, &$message) {
        return true;
    }

    /**
     * @return CustomEditColumn[]
     */
    protected function getRealEditColumns() {
        return array();
    }

    public function OnBeforePostHandler(Dataset $dataset) {
        foreach ($this->getRealEditColumns() as $column) {
            $fieldName = $column->GetFieldName();
            if ($dataset->GetFieldByName($fieldName)) {
                if ($column->getUseHTMLFilter()) {
                    GetApplication()->getHTMLFilter()->setTags($column->getHTMLFilterString());
                    $dataset->SetFieldValueByName($fieldName,
                        GetApplication()->getHTMLFilter()->filter($dataset->GetFieldValueByName($fieldName)));
                }
            }
        }
    }

    protected function CanChangeData(&$rowValues, &$message) {
        return $this->DoCanChangeData($rowValues, $message);
    }

    public abstract function ProcessMessages();

    // exits when doesn't have edit permission
    protected function CheckRLSEditGrant()
    {
        if ($this->GetPage()->GetRecordPermission() != null) {
            $this->GetPage()->RaiseSecurityError(
                !$this->GetPage()->GetRecordPermission()->HasEditGrant($this->GetDataset()), OPERATION_EDIT);
        }
    }
}

class ViewAllGridState extends GridState {
    function ProcessMessages() {
        /* $primaryKeyValues = array();
        ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);
        if (count($primaryKeyValues) > 0)
            $this->grid->GetDataset()->SetSingleRecordState($primaryKeyValues);
        */
        $orderColumn = $this->grid->GetOrderColumnFieldName();
        $orderType = $this->grid->GetOrderType();
        if (isset($orderType) && isset($orderColumn))
            $this->grid->GetDataset()->SetOrderBy($orderColumn, GetOrderTypeAsSQL($orderType));

        foreach ($this->grid->GetViewColumns() as $column)
            $column->ProcessMessages();
    }
}

class OpenInlineInsertEditorsGridState extends GridState {
    private $nameSuffix;

    function ProcessMessages() {
        $this->nameSuffix = '_inline_' . mt_rand();

        $columns = $this->grid->GetViewColumns();
        foreach ($columns as $column) {
            $inlineEditColumn = $column->GetInsertOperationColumn();
            if (isset($inlineEditColumn)) {
                $editControl = $inlineEditColumn->GetEditControl();
                $editControl->SetName($editControl->GetName() . $this->GetNameSuffix());
                $inlineEditColumn->ProcessMessages();
            }
        }
    }

    public function GetNameSuffix() {
        return $this->nameSuffix;
    }
}

class OpenInlineEditorsGridState extends GridState {
    private $nameSuffix;

    function ProcessMessages() {
        $primaryKeyValues = $this->grid->GetPrimaryKeyValuesFromGet();

        $this->nameSuffix = '_inline_' . mt_rand();

        $this->grid->GetDataset()->SetSingleRecordState($primaryKeyValues);
        $this->grid->GetDataset()->Open();

        if ($this->grid->GetDataset()->Next()) {
            $columns = $this->grid->GetViewColumns();
            foreach ($columns as $column) {
                $inlineEditColumn = $column->GetEditOperationColumn();
                if (isset($inlineEditColumn)) {
                    $editControl = $inlineEditColumn->GetEditControl();
                    $editControl->SetName($editControl->GetName() . $this->GetNameSuffix());
                }
            }
            array_walk($columns, create_function('$column', '$column->ProcessMessages();'));
        }

        //$this->grid->GetDataset()->Close();
    }

    public function GetNameSuffix() {
        return $this->nameSuffix;
    }
}

class EditGridState extends GridState {
    function ProcessMessages() {
        $primaryKeyValues = $this->grid->GetPrimaryKeyValuesFromGet();

        $this->grid->GetDataset()->SetSingleRecordState($primaryKeyValues);
        $this->grid->GetDataset()->Open();

        if ($this->grid->GetDataset()->Next()) {
            $this->CheckRLSEditGrant();
            $columns = $this->grid->GetEditColumns();
            array_walk($columns, create_function('$column', '$column->ProcessMessages();'));
        }
        else {
            RaiseCannotRetrieveSingleRecordError();
        }
        $this->grid->GetDataset()->Close();
    }
}

class CopyGridState extends GridState {
    function ProcessMessages() {
        $primaryKeyValues = $this->grid->GetPrimaryKeyValuesFromGet();

        $this->grid->GetDataset()->SetSingleRecordState($primaryKeyValues);
        $this->grid->GetDataset()->Open();

        if ($this->grid->GetDataset()->Next())
            foreach ($this->grid->GetInsertColumns() as $column)
                $column->ProcessMessages();
    }
}

class InsertGridState extends GridState {
    function ProcessMessages() {
        foreach ($this->grid->GetInsertColumns() as $column)
            $column->ProcessMessages();
    }
}


class CommitNewValuesGridState extends CommitValuesGridState {
    private $isInline = false;

    public function SetIsInlineOperation($value) {
        $this->isInline = $value;
    }

    protected function DoCanChangeData(&$rowValues, &$message) {
        $cancel = false;
        $this->grid->BeforeInsertRecord->Fire(array($this->GetPage(), &$rowValues, &$cancel, &$message,
            $this->GetDataset()->GetName()));
        return !$cancel;
    }

    protected function DoAfterChangeData($rowValues) {
        $this->grid->AfterInsertRecord->Fire(array($this->GetPage(), $rowValues, $this->GetDataset()->GetName()));
    }

    function ProcessMessages() {
        $action = '';
        if (GetApplication()->GetSuperGlobals()->IsPostValueSet('submit1'))
            $action = GetApplication()->GetSuperGlobals()->GetPostValue('submit1');

        $redirect = null;
        $detailToRedirect = null;
        if (GetApplication()->GetSuperGlobals()->IsGetValueSet('details-redirect'))
            $detailToRedirect = GetApplication()->GetSuperGlobals()->GetGetValue('details-redirect');

        $this->grid->GetDataset()->Insert();

        $exceptions = array();
        foreach ($this->grid->GetInsertColumns() as $column) {
            try {
                $column->ProcessMessages();
            } catch (Exception $e) {
                $exceptions[] = $e;
            }
        }
        foreach ($this->grid->GetInsertColumns() as $column) {
            try {
                $column->AfterSetAllDatasetValues();
            } catch (Exception $e) {
                $exceptions[] = $e;
            }
        }


        $message = '';
        $oldFieldValues = $this->GetDataset()->GetCurrentFieldValues();
        $fieldValues = $this->GetDataset()->GetCurrentFieldValues();
        if ($this->CanChangeData($fieldValues, $message)) {
            if (count($exceptions) > 0) {
                $this->ChangeState(OPERATION_INSERT);
                $this->SetGridErrorMessages($exceptions);
                return;
            }
            try {
                $this->WriteChangesToDataset($oldFieldValues, $fieldValues, $this->GetDataset());

                $this->GetDataset()->Post();

                if ($detailToRedirect) {
                    $detail = $this->grid->FindDetail($detailToRedirect);
                    $redirect = $detail->GetSeparateViewLink();
                }

                $fieldValues = ArrayUtils::Merge(
                    $fieldValues,
                    $this->GetDataset()->GetInsertFieldValues()
                );
                $this->DoAfterChangeData($fieldValues);

                $newPrimaryKeyValues = $this->grid->GetDataset()->GetPrimaryKeyValues();
            } catch (Exception $e) {
                $this->ChangeState(OPERATION_INSERT);
                $columns = $this->grid->GetInsertColumns();
                array_walk($columns, create_function('$column', '$column->PrepareEditorControl();'));
                $this->SetGridErrorMessage($e);
                return;
            }
        } else {
            $this->ChangeState(OPERATION_INSERT);
            $this->SetGridSimpleErrorMessage($message);
            $columns = $this->grid->GetInsertColumns();
            array_walk($columns, create_function('$column', '$column->PrepareEditorControl();'));
            return;
        }
        $this->grid->SetGridMessage($message);

        if ($redirect) {
            header('Location: ' . $redirect);
            exit();
        }


        if (!$this->isInline) {
            if ($action == 'saveinsert') {
                $this->ApplyState(OPERATION_INSERT);
                header('Location: ' . $this->grid->GetAddRecordLink());
                exit();
            } else if ($action == 'saveedit') {
                header('Location: ' . $this->grid->GetEditCurrentRecordLink($newPrimaryKeyValues));
                exit();
            } else {
                header('Location: ' . $this->grid->GetReturnUrl());
                exit();
            }
        }

    }

    function SetInternalStateSwitch($primaryKeys) {
        $this->grid->SetInternalStateSwitch($primaryKeys);
    }

    protected function getRealEditColumns() {
        return $this->grid->GetInsertColumns();
    }

}


class CommitInlineInsertedValuesGridState extends CommitValuesGridState {
    protected function DoCanChangeData(&$rowValues, &$message) {
        $cancel = false;
        $this->grid->BeforeInsertRecord->Fire(array($this->GetPage(), &$rowValues, &$cancel, &$message,
            $this->GetDataset()->GetName()));
        return !$cancel;
    }

    protected function DoAfterChangeData($rowValues) {
        $this->grid->AfterInsertRecord->Fire(array($this->GetPage(), $rowValues, $this->GetDataset()->GetName()));
    }

    private function HandleError($message, $decode = true) {
        $this->SetGridSimpleErrorMessage($message, $decode);
        $columnsToWalk = $this->GetColumns();
        array_walk($columnsToWalk, create_function('$column', '$column->PrepareEditorControl();'));
    }

    /**
     * @return CustomEditColumn[]
     */
    private function GetColumns() {
        $result = array();

        foreach ($this->grid->GetViewColumns() as $column) {
            $editColumn = $column->GetInsertOperationColumn();
            if (isset($editColumn))
                $result[] = $editColumn;
        }
        return $result;
    }

    public function ProcessMessages() {
        $nameSuffix = ExtractInputValue('namesuffix', METHOD_POST);
        $columns = $this->grid->GetViewColumns();
        foreach ($columns as $column) {
            $inlineEditColumn = $column->GetInsertOperationColumn();
            if (isset($inlineEditColumn)) {
                $editControl = $inlineEditColumn->GetEditControl();
                $editControl->SetName($editControl->GetName() . $nameSuffix);
            }
        }

        $this->GetDataset()->Insert();

        $exceptions = array();

        foreach ($this->GetColumns() as $column) {
            try {
                $column->ProcessMessages();
            } catch (Exception $e) {
                $exceptions[] = $e;
            }
        }

        foreach ($this->GetColumns() as $column) {
            try {
                $column->AfterSetAllDatasetValues();
            } catch (Exception $e) {
                $exceptions[] = $e;
            }
        }


        $message = '';
        $oldFieldValues = $this->GetDataset()->GetCurrentFieldValues();
        $fieldValues = $this->GetDataset()->GetCurrentFieldValues();

        if ($this->CanChangeData($fieldValues, $message)) {
            if (count($exceptions) > 0) {
                $this->HandleError($this->ExceptionsToErrorMessage($exceptions), false);
                return;
            }
            try {
                $this->WriteChangesToDataset($oldFieldValues, $fieldValues, $this->GetDataset());
                $this->GetDataset()->Post();
                $fieldValues = ArrayUtils::Merge(
                    $fieldValues,
                    $this->GetDataset()->GetInsertFieldValues()
                );
                $this->DoAfterChangeData($fieldValues);

                $primaryKeyValues = $this->GetDataset()->GetPrimaryKeyValues();
                $this->GetDataset()->SetSingleRecordState($primaryKeyValues);
                $this->GetDataset()->Open();
                $this->GetDataset()->Next();
            } catch (Exception $e) {
                $this->HandleError($this->ExceptionToErrorMessage($e), false);
                return;
            }
        } else {
            $this->HandleError($message, true);
            return;
        }
        $this->grid->SetGridMessage($message);

    }

    protected function getRealEditColumns() {
        return $this->GetColumns();
    }
}

class CommitInlineEditedValuesGridState extends CommitValuesGridState {
    protected function DoCanChangeData(&$rowValues, &$message) {
        $cancel = false;
        $this->grid->BeforeUpdateRecord->Fire(array($this->GetPage(), &$rowValues, &$cancel, &$message,
            $this->GetDataset()->GetName()));
        return !$cancel;
    }

    protected function DoAfterChangeData($rowValues) {
        $this->grid->AfterUpdateRecord->Fire(array($this->GetPage(), $rowValues, $this->GetDataset()->GetName()));
    }

    private function HandleError($message, $decode = true) {
        $this->SetGridSimpleErrorMessage($message, $decode);
        $columnsToWalk = $this->GetColumns();
        array_walk($columnsToWalk, create_function('$column', '$column->PrepareEditorControl();'));
        $this->GetDataset()->Close();
    }

    /**
     * @return CustomEditColumn[]
     */
    private function GetColumns() {
        $result = array();

        foreach ($this->grid->GetViewColumns() as $column) {
            $editColumn = $column->GetEditOperationColumn();
            if (isset($editColumn))
                $result[] = $editColumn;
        }
        return $result;
    }

    public function ProcessMessages() {
        $primaryKeyValues = array();
        ExtractPrimaryKeyValues($primaryKeyValues, METHOD_POST);

        $nameSuffix = ExtractInputValue('namesuffix', METHOD_POST);
        $columns = $this->grid->GetViewColumns();
        foreach ($columns as $column) {
            $inlineEditColumn = $column->GetEditOperationColumn();
            if (isset($inlineEditColumn)) {
                $editControl = $inlineEditColumn->GetEditControl();
                $editControl->SetName($editControl->GetName() . $nameSuffix);
            }
        }

        $this->GetDataset()->SetSingleRecordState($primaryKeyValues);
        $this->GetDataset()->Open();

        if ($this->GetDataset()->Next()) {

            $this->GetDataset()->Edit();

            $exceptions = array();

            foreach ($this->GetColumns() as $column) {
                try {
                    $column->ProcessMessages();
                } catch (Exception $e) {
                    $exceptions[] = $e;
                }
            }
            foreach ($this->GetColumns() as $column) {
                try {
                    $column->AfterSetAllDatasetValues();
                } catch (Exception $e) {
                    $exceptions[] = $e;
                }
            }

            $message = '';
            $oldFieldValues = array_merge($this->GetDataset()->GetFieldValues(), $this->GetDataset()->GetCurrentFieldValues());
            $fieldValues = array_merge($this->GetDataset()->GetFieldValues(), $this->GetDataset()->GetCurrentFieldValues());

            if ($this->CanChangeData($fieldValues, $message)) {
                if (count($exceptions) > 0) {
                    $this->HandleError($this->ExceptionsToErrorMessage($exceptions), false);
                    return;
                }
                try {
                    $this->WriteChangesToDataset($oldFieldValues, $fieldValues, $this->GetDataset());

                    $primaryKeyValues = $this->GetDataset()->GetPrimaryKeyValues();
                    $this->GetDataset()->Post();
                    $this->DoAfterChangeData($fieldValues);
                    $this->GetDataset()->Close();
                    $this->GetDataset()->SetSingleRecordState($primaryKeyValues);
                } catch (Exception $e) {
                    $this->HandleError($this->ExceptionToErrorMessage($e), false);
                    return;
                }
            } else {
                $this->HandleError($message, true);
                return;
            }
            $this->grid->SetGridMessage($message);
            $this->grid->GetDataset()->Close();
            $this->GetDataset()->SetSingleRecordState($primaryKeyValues);
        }
    }

    protected function getRealEditColumns() {
        return $this->GetColumns();
    }

}

class DeleteGridState extends GridState {
    public function ProcessMessages() {
        $primaryKeyValues = array();
        ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);
        $this->grid->GetDataset()->SetSingleRecordState($primaryKeyValues);
    }
}

class ViewGridState extends GridState {
    public function ProcessMessages() {
        $primaryKeyValues = array();
        ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);
        $this->grid->GetDataset()->SetSingleRecordState($primaryKeyValues);
    }
}

class CommitDeleteGridState extends GridState {
    private $useGetToExtractPrimaryKeys = false;

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
        $primaryKeyValues = array();
        if ($this->useGetToExtractPrimaryKeys)
            ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);
        else
            ExtractPrimaryKeyValues($primaryKeyValues, METHOD_POST);

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

                    $this->ChangeState(OPERATION_DELETE);
                    $this->SetGridErrorMessage($e);
                    return;
                }
            } else {
                $this->ChangeState(OPERATION_DELETE);
                $this->SetGridSimpleErrorMessage($message);
                return;
            }
            $this->grid->SetGridMessage($message);
        }
        $this->grid->GetDataset()->Close();

        $this->grid->GetDataset()->Open();
        $this->ApplyState(OPERATION_VIEWALL);
    }

    public function SetUseGetToExtractPrimaryKeys($value) {
        $this->useGetToExtractPrimaryKeys = $value;
    }
}
