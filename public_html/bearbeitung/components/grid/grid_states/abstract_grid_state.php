<?php

abstract class GridState
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var Event[]
     */
    private $events;

    /**
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
        $this->events = array(
            'BeforeUpdateRecord' => $this->grid->BeforeUpdateRecord,
            'BeforeInsertRecord' => $this->grid->BeforeInsertRecord,
            'BeforeDeleteRecord' => $this->grid->BeforeDeleteRecord,
            'AfterUpdateRecord' => $this->grid->AfterUpdateRecord,
            'AfterInsertRecord' => $this->grid->AfterInsertRecord,
            'AfterDeleteRecord' => $this->grid->AfterDeleteRecord,
        );
        $this->getDataset()->OnBeforePost
            ->AddListener('OnBeforePostHandler', $this);
    }

    public abstract function ProcessMessages();

    /**
     * OnBeforePest event handler
     *
     * @param Dataset $dataset
     */
    public function OnBeforePostHandler(Dataset $dataset)
    {
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

    /**
     * @param string $eventName
     * @param array $args
     */
    protected function fireEvent($eventName, array $args)
    {
        array_unshift($args, $this->grid->GetPage());
        $this->events[$eventName]->Fire($args);
    }

    /**
     * @return string
     */
    protected function getDatasetName() {
        return $this->getDataset()->getName();
    }

    /**
     * @param string $method
     * @return array
     */
    protected function getSingleRowValues($method) {
        $primaryKeyValues = array();
        ExtractPrimaryKeyValues($primaryKeyValues, $method);
        $this->getDataset()->SetSingleRecordState($primaryKeyValues);
        $this->getDataset()->Open();

        if (!$this->getDataset()->Next()) {
            return null;
        }

        return $this->getDataset()->GetCurrentFieldValues();
    }

    /**
     * @return Dataset
     */
    protected function getDataset() {
        return $this->grid->GetDataset();
    }

    /**
     * @param string $message
     * @param int $displayTime
     */
    protected function setGridMessage($message, $displayTime)
    {
        if ($message) {
            $this->grid->addMessage($message, $displayTime);
        }
    }

    /**
     * @param string $message
     * @param int $displayTime
     */
    protected function setGridErrorMessage($message, $displayTime)
    {
        if ($message) {
            $this->grid->addErrorMessage($message, $displayTime);
        }
    }

    /**
     * @param Exception[] $exceptions
     * @return string
     */
    protected function getMessageFromExceptions($exceptions)
    {
        $result = array();

        foreach ($exceptions as $exception) {
            if ($exception instanceOf AbstractLocalizedException) {
                $message = $exception->getLocalizedMessage(
                    $this->grid->GetPage()->GetLocalizerCaptions()
                );
            } else {
                $message = $exception->getMessage();
            }

            $result[] = $message;

            if (defined('DEBUG_LEVEL') && DEBUG_LEVEL > 0) {
                $result[] = 'Program trace: <br>' . FormatExceptionTrace($exception);
            }
        }

        return implode('<br><br>', $result);
    }

    /**
     * @param string $stateIdentifier
     */
    protected function ChangeState($stateIdentifier)
    {
        GetApplication()->SetOperation($stateIdentifier);
        $this->grid->SetState($stateIdentifier);
    }

    /**
     * @param string $stateIdentifier
     */
    protected function ApplyState($stateIdentifier)
    {
        $this->ChangeState($stateIdentifier);
        $this->grid->GetState()->ProcessMessages();
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

    /**
     * @return CustomEditColumn[]
     */
    protected function getRealEditColumns()
    {
        return array();
    }

    /**
     * exits when doesn't have edit permission
     */
    protected function CheckRLSEditGrant()
    {
        $page = $this->grid->getPage();
        $page->RaiseSecurityError(
            !$page->hasRLSEditGrant($this->GetDataset()),
            OPERATION_EDIT
        );
    }

    protected function CheckRLSDeleteGrant()
    {
        $page = $this->grid->getPage();
        $page->RaiseSecurityError(
            !$page->hasRLSDeleteGrant($this->GetDataset()),
            OPERATION_DELETE
        );
    }
}
