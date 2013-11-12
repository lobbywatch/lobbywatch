<?php

abstract class GridState {
    /**
     * @var Grid
     */
    protected $grid;

    public function __construct(Grid $Grid) {
        $this->grid = $Grid;
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

    protected function CanChangeData(&$rowValues, &$message) {
        $cancel = false;
        $this->grid->OnBeforeDataChange->Fire(array($this->grid->GetPage(), &$rowValues, &$cancel, &$message));
        return !$cancel && $this->DoCanChangeData($rowValues, $message);
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
