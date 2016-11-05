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
     * @param FixedKeysArray  &$rowValues
     * @param string &$message
     * @param int    &$messageDisplayTime
     *
     * @return bool
     */
    protected function isCanceledByEvent(FixedKeysArray &$rowValues, &$message, &$messageDisplayTime)
    {
        $cancel = false;

        $this->fireEvent('Before' . $this->getOperation() . 'Record', array(
            &$rowValues,
            &$cancel,
            &$message,
            &$messageDisplayTime,
            $this->getDatasetName(),
        ));

        return $cancel;
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
        $exceptions = $this->processColumns();
        if (count($exceptions) > 0) {
            $this->handleError($this->getMessageFromExceptions($exceptions));
            return false;
        }

        $newRowValues = new FixedKeysArray($this->getNewRowValues($rowValues));

        $messageDisplayTime = 0;
        $success = true;
        $message = '';

        if ($this->isCanceledByEvent($newRowValues, $message, $messageDisplayTime)) {
            $this->handleError($message, $messageDisplayTime);
            return false;
        } else {
            $message = '';
        }

        try {
            $this->commitValues($rowValues, $newRowValues->toArray());
            $newRowValues = new FixedKeysArray(
                $this->refreshRowValues(array_merge($rowValues, $newRowValues->toArray()))
            );
        } catch (Exception $e) {
            $success = false;
            $message = $this->getMessageFromExceptions(array($e));
        }

        $this->fireEvent('After' . $this->getOperation() . 'Record', array(
            $newRowValues,
            $this->getDatasetName(),
            &$success,
            &$message,
            &$messageDisplayTime,
        ));

        if (!$success) {
            $this->handleError($message, $messageDisplayTime);
            return false;
        }

        $this->setGridMessage($message, $messageDisplayTime);

        return true;
    }
}
