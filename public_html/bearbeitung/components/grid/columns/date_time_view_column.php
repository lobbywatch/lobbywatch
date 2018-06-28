<?php

include_once dirname(__FILE__) . '/' . '../../utils/sm_datetime.php';

class DateTimeViewColumn extends AbstractDatasetFieldViewColumn
{
    private $dateTimeFormat;

    public function __construct($fieldName, $datasetFieldName, $caption, $dataset, $orderable = true)
    {
        parent::__construct($fieldName, $datasetFieldName, $caption, $dataset, $orderable);
        $this->dateTimeFormat = 'Y-m-d';
    }

    public function SetDateTimeFormat($value)
    {
        $this->dateTimeFormat = $value;
    }

    public function GetDateTimeFormat()
    {
        return $this->dateTimeFormat;
    }

    public function GetOSDateTimeFormat()
    {
        return ServerToClientConvertFormatDate($this->dateTimeFormat);
    }

    public function GetValue()
    {
        $field = $this->GetDataset()->GetFieldByName($this->getFieldName());
        if ($field instanceof DateTimeBasedField) {
            $value = $this->GetDataset()->GetFieldValueByName($this->GetFieldName());
            if (isset($value)) {
                $datetimeObject = SMDateTime::Parse($value, $field->getDefaultFormat());
                $stringValue = $datetimeObject->ToString($this->dateTimeFormat);
                return isset($stringValue) ? $stringValue : null;
            }
        }
        return null;
    }
}
