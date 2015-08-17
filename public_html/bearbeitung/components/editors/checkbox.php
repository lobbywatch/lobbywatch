<?php

include_once dirname(__FILE__) . '/' . '../renderers/renderer.php';
include_once dirname(__FILE__) . '/' . 'custom.php';

class CheckBox extends CustomEditor {
    private $value;

    public function GetValue() {
        return $this->value;
    }

    public function SetValue($value) {
        $this->value = $value;
    }

    public function GetDataEditorClassName() {
        return 'CheckBox';
    }

    protected function SuppressRequiredValidation() {
        return true;
    }

    public function extractValueFromArray(ArrayWrapper $arrayWrapper, &$valueChanged) {
        if ($this->GetReadOnly()) {
            $valueChanged = false;
            return null;
        } else {
            $valueChanged = true;
            return $arrayWrapper->isValueSet($this->GetName()) ? '1' : '0';
        }
    }

    public function Accept(EditorsRenderer $renderer) {
        $renderer->RenderCheckBox($this);
    }

    public function isChecked() {
        return (isset($this->value) && !empty($this->value));
    }
}
