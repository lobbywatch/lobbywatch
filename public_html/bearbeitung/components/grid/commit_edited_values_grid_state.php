<?php

include_once dirname(__FILE__) . '/' . 'commit_values_grid_state.php';

class CommitEditedValuesGridState extends CommitValuesGridState {
    private $isInline = false;

    public function SetIsInlineOperation($value) {
        $this->isInline = $value;
    }

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
        $this->ChangeState(OPERATION_EDIT);
        $this->SetGridSimpleErrorMessage($message, $decode);
        $columns = $this->grid->GetEditColumns();
        array_walk($columns, create_function('$column', '$column->PrepareEditorControl();'));
        $this->GetDataset()->Close();
    }

    public function ProcessMessages() {
        $primaryKeyValues = array();
        ExtractPrimaryKeyValues($primaryKeyValues, METHOD_POST);

        $action = '';
        if (GetApplication()->GetSuperGlobals()->IsPostValueSet('submit1'))
            $action = GetApplication()->GetSuperGlobals()->GetPostValue('submit1');

        $redirect = null;
        $detailToRedirect = null;
        if (GetApplication()->GetSuperGlobals()->IsGetValueSet('details-redirect'))
            $detailToRedirect = GetApplication()->GetSuperGlobals()->GetGetValue('details-redirect');

        $this->GetDataset()->SetSingleRecordState($primaryKeyValues);
        $this->GetDataset()->Open();

        if ($this->GetDataset()->Next()) {
            $this->CheckRLSEditGrant();
            $this->GetDataset()->Edit();

            $exceptions = array();
            foreach ($this->grid->GetEditColumns() as $column) {
                try {
                    $column->ProcessMessages();
                } catch (Exception $e) {
                    $exceptions[] = $e;
                }
            }
            foreach ($this->grid->GetEditColumns() as $column) {
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
                    $this->GetDataset()->Post();
                    $this->DoAfterChangeData($fieldValues);

                    if ($detailToRedirect) {
                        $detail = $this->grid->FindDetail($detailToRedirect);
                        $redirect = $detail->GetSeparateViewLink();
                    }

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

        if ($action != 'saveedit') {
            if (!$this->isInline) {
                header('Location: ' . $this->grid->GetReturnUrl());
                if ($redirect) {
                    header('Location: ' . $redirect);
                }
                exit();
            }
        } else {
            if (!$this->isInline) {
                $newPrimaryKeyValues = $this->grid->GetDataset()->GetPrimaryKeyValuesAfterEdit();
                header('Location: ' . $this->grid->GetEditCurrentRecordLink($newPrimaryKeyValues));

                $this->grid->GetDataset()->Close();
                exit();
            }
        }
    }

    function SetInternalStateSwitch($primaryKeys) {
        $this->grid->SetInternalStateSwitch($primaryKeys);
    }
}
