<?php

include_once dirname(__FILE__) . '/' . 'custom.php';
include_once dirname(__FILE__) . '/' . '../utils/array_wrapper.php';

class SpinEdit extends CustomEditor {
    private $value;
    private $useConstraints = false;
    private $minValue;
    private $maxValue;
    private $step;

    public function GetMaxValue() {
        return $this->maxValue;
    }

    public function SetMaxValue($value) {
        $this->maxValue = $value;
    }

    public function GetMinValue() {
        return $this->minValue;
    }

    public function SetMinValue($value) {
        $this->minValue = $value;
    }

    public function GetStep() {
        return $this->step;
    }

    public function SetStep($value) {
        $this->step = $value;
    }

    public function GetValue() {
        return $this->value;
    }

    public function SetValue($value) {
        $this->value = $value;
    }

    public function SetUseConstraints($value) {
        $this->useConstraints = $value;
    }

    public function GetUseConstraints() {
        return $this->useConstraints;
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
        return 'spin';
    }
}
