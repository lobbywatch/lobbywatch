<?php

include_once dirname(__FILE__) . '/' . 'custom.php';
include_once dirname(__FILE__) . '/' . '../utils/array_wrapper.php';

class CheckBox extends CustomEditor {

    private $value;

    public function GetValue() {
        return $this->value;
    }

    public function SetValue($value) {
        $this->value = $value;
    }

    protected function SuppressRequiredValidation() {
        return true;
    }

    public function extractValueFromArray(ArrayWrapper $arrayWrapper, &$valueChanged) {
        $valueChanged = true;
        return $arrayWrapper->isValueSet($this->GetName()) ? '1' : '0';
    }

    public function isChecked() {
        return (isset($this->value) && !empty($this->value));
    }

    public function isInlineLabel()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getEditorName()
    {
        return 'checkbox';
    }
}
