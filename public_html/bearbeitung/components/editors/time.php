<?php

include_once dirname(__FILE__) . '/' . 'custom.php';
include_once dirname(__FILE__) . '/' . '../utils/array_wrapper.php';

class TimeEdit extends CustomEditor {
    private $value;
    /** @var string */
    private $format;

    public function __construct($name, $format = null) {
        parent::__construct($name);
        if (isset($format))
            $this->format = $format;
        else
            $this->format = 'H:i:s';
    }

    public function GetValue() {
        return $this->value;
    }

    public function SetValue($value) {
        $this->value = $value;
    }

    public function GetFormat() {
        return ServerToClientConvertFormatDate($this->format);
    }

    public function SetFormat($value) {
        $this->format = $value;
    }

    public function extractValueFromArray(ArrayWrapper $arrayWrapper, &$valueChanged) {
        if ($arrayWrapper->IsValueSet($this->GetName())) {
            $valueChanged = true;
            return $arrayWrapper->GetValue($this->GetName());
        } else {
            $valueChanged = false;
            return null;
        }
    }

    /**
     * @return string
     */
    public function getEditorName()
    {
        return 'time';
    }
}
