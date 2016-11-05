<?php

include_once dirname(__FILE__) . '/' . 'custom.php';
include_once dirname(__FILE__) . '/' . '../utils/array_wrapper.php';

class TimeEdit extends CustomEditor {
    private $value;

    public function __construct($name) {
        parent::__construct($name);
    }

    public function GetValue() {
        return $this->value;
    }

    public function SetValue($value) {
        $this->value = $value;
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
