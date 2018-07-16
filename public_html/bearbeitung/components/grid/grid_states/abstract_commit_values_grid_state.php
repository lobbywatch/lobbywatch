<?php

include_once dirname(__FILE__) . '/../../utils/fixed_keys_array.php';

abstract class AbstractCommitValuesGridState extends GridState
{
    /**
     * @return string Insert|Update
     */
    abstract protected function getOperation();

    /**
     * @param array $rowValues
     *
     * @return array
     */
    protected function getNewRowValues($rowValues)
    {
        return $rowValues;
    }

    /**
     * @param array $rowValues
     *
     * @return array
     */
    protected function refreshRowValues($rowValues)
    {
        return $rowValues;
    }

    /**
     * @param array $rowValues
     * @param array $newRowValues
     *
     * @return null
     */
    protected function commitValues($rowValues, $newRowValues)
    {
        $this->grid->GetPage()->UpdateValuesFromUrl();
        $this->WriteChangesToDataset($rowValues, $newRowValues, $this->getDataset());
        $this->GetDataset()->Post();
    }

    /**
     * @param FixedKeysArray $oldRowValues
     * @param FixedKeysArray &$newRowValues
     * @param bool &$cancel
     * @param string &$message
     * @param int    &$messageDisplayTime
     * @return bool
     */
    protected function doBeforeCommit(FixedKeysArray $oldRowValues, FixedKeysArray &$newRowValues, &$cancel, &$message, &$messageDisplayTime)
    {
        $this->doProcessRecordEvent('Before', $oldRowValues, $newRowValues, $cancel, $message, $messageDisplayTime);
    }

    /**
     * @param FixedKeysArray $oldRowValues
     * @param FixedKeysArray $newRowValues
     * @param bool &$success
     * @param string &$message
     * @param int &$messageDisplayTime
     */
    protected function doAfterCommit(FixedKeysArray $oldRowValues, FixedKeysArray $newRowValues, &$success, &$message, &$messageDisplayTime)
    {
        $this->doProcessRecordEvent('After', $oldRowValues, $newRowValues, $success, $message, $messageDisplayTime);
    }

    /**
     * @param string $eventTime
     * @param FixedKeysArray $oldRowValues
     * @param FixedKeysArray $newRowValues
     * @param bool &$result
     * @param string &$message
     * @param int &$messageDisplayTime
     */
    protected function doProcessRecordEvent($eventTime, FixedKeysArray $oldRowValues, FixedKeysArray &$newRowValues, &$result, &$message, &$messageDisplayTime)
    {
        $this->fireEvent($this->getProcessRecordEventName($eventTime), array(
            &$newRowValues,
            $this->getDatasetName(),
            &$result,
            &$message,
            &$messageDisplayTime
        ));
    }

    /**
     * @param string $eventTime
     * @return string
     */
    protected function getProcessRecordEventName($eventTime) {
        return $eventTime . $this->getOperation() . 'Record';
    }

    /**
     * @param string $errorMessage
     * @param int    $displayTime
     *
     * @return null
     */
    protected function handleError($errorMessage, $displayTime = 0)
    {
        $this->setGridErrorMessage($errorMessage, $displayTime);

        foreach ($this->getRealEditColumns() as $column) {
            $column->PrepareEditorControl();
        }

        $this->getDataset()->Close();
    }

    /**
     * @return Exception[]
     */
    protected function processColumns()
    {
        $exceptions = array();

        $columns = $this->getRealEditColumns();
        foreach ($columns as $column) {
            try {
                $column->ProcessMessages();
            } catch (Exception $e) {
                $exceptions[] = $e;
            }
        }

        foreach ($columns as $column) {
            try {
                $column->AfterSetAllDatasetValues();
            } catch (Exception $e) {
                $exceptions[] = $e;
            }
        }

        return $exceptions;
    }

    public function doProcessMessages($rowValues)
    {
        $oldRowValues = new FixedKeysArray($rowValues);

        $exceptions = $this->processColumns();
        if (count($exceptions) > 0) {
            $this->handleError($this->getMessageFromExceptions($exceptions));
            return false;
        }

        $newRowValues = new FixedKeysArray($this->getNewRowValues($rowValues));

        $messageDisplayTime = 0;
        $success = true;
        $message = '';

        try {
            try {
                $cancel = false;
                $this->doBeforeCommit($oldRowValues, $newRowValues, $cancel, $message, $messageDisplayTime);
                if ($cancel) {
                    $this->handleError($message, $messageDisplayTime);
                    return false;
                } else {
                    $message = '';
                }
            } catch (InvalidArgumentException $e) {
                $message = $this->getRowDataHasInvalidKeyErrorMessage('Before', $e);
                $this->handleError($message, $messageDisplayTime);
                return false;
            }

            try {
                $this->commitValues($rowValues, $this->prepareNewRowValuesToCommit($newRowValues));
                $newRowValues = new FixedKeysArray(
                    $this->refreshRowValues(array_merge($rowValues, $newRowValues->toArray()))
                );
            } catch (Exception $e) {
                $success = false;
                $message = $this->getMessageFromExceptions(array($e));
            }

            try {
                $this->doAfterCommit($oldRowValues, $newRowValues, $success, $message, $messageDisplayTime);
            } catch (InvalidArgumentException $e) {
                $message = $this->getRowDataHasInvalidKeyErrorMessage('After', $e);
                $this->handleError($message, $messageDisplayTime);
                return false;
            }

        } catch (Exception $e) {
            $success = false;
            $message = $this->getMessageFromExceptions(array($e));
        }

        if (!$success) {
            $this->handleError($message, $messageDisplayTime);
            return false;
        }

        $this->setGridMessage($message, $messageDisplayTime);

        return true;
    }

    private function getRowDataHasInvalidKeyErrorMessage($eventPrefix, $e) {
        return 'An error occurred in On' . $eventPrefix . $this->getOperation() . 'Record event. $rowData array has ' .
            lcfirst($this->getMessageFromExceptions(array($e)));
    }

    /**
     * @param FixedKeysArray $rowValues
     *
     * @return array
     */
    protected function prepareNewRowValuesToCommit($rowValues) {
        return $rowValues->toArray();
    }
}
