<?php

include_once dirname(__FILE__) . '/' . 'custom.php';
include_once dirname(__FILE__) . '/' . '../utils/array_wrapper.php';

include_once dirname(__FILE__) . '/' . '../utils/string_utils.php';
include_once dirname(__FILE__) . '/' . '../utils/sm_datetime.php';
include_once dirname(__FILE__) . '/' . '../common.php';


class DateTimeEdit extends CustomEditor {
    /** @var SMDateTime */
    private $value;
    private $showsTime;
    private $format;

    public function __construct($name, $showsTime = false, $format = null) {
        parent::__construct($name);
        $this->showsTime = $showsTime;

        if (!isset($format))
            $this->format = $this->showsTime ? 'Y-m-d H:i:s' : 'Y-m-d';
        else
            $this->format = $format;
    }

    public function GetValue() {
        if (isset($this->value))
            return $this->value->ToString($this->format);
        else
            return '';
    }

    public function SetValue($value) {
        if (!StringUtils::IsNullOrEmpty($value))
            $this->value = SMDateTime::Parse($value, $this->format);
        else
            $this->value = null;
    }

    public function GetFormat() {
        return ServerToClientConvertFormatDate($this->format);
    }

    public function SetFormat($value) {
        $this->format = $value;
    }

    public function GetShowsTime() {
        return $this->showsTime;
    }

    public function SetShowsTime($value) {
        $this->showsTime = $value;
    }

    public function extractValueFromArray(ArrayWrapper $arrayWrapper, &$valueChanged) {
        if ($arrayWrapper->IsValueSet($this->GetName())) {
            $valueChanged = true;
            $value = $arrayWrapper->GetValue($this->GetName());
            if ($value == '')
                return null;
            else
                return $value;
        } else {
            $valueChanged = false;
            return null;
        }
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function prepareValueForDataset($value) {
        return SMDateTime::Parse($value, $this->format);
    }

    /**
     * @return string
     */
    public function getEditorName()
    {
        return 'datetime';
    }
}
