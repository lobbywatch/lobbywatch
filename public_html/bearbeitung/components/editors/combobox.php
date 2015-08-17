<?php

include_once dirname(__FILE__) . '/' . '../renderers/renderer.php';
include_once dirname(__FILE__) . '/' . 'custom.php';

class ComboBox extends CustomEditor {
    private $values;
    private $selectedValue;
    private $emptyValue;
    //
    private $mfuValues;
    private $preparedMfuValues;
    private $displayValues;

    public function __construct($name, $emptyValue = '') {
        parent::__construct($name);
        $this->values = array();
        $this->selectedValue = null;
        $this->emptyValue = $emptyValue;
        $this->values[''] = $emptyValue;
        $this->displayValues = array();
        $this->mfuValues = array();
        $this->preparedMfuValues = null;
    }

    public function GetSelectedValue() {
        return $this->selectedValue;
    }

    public function SetSelectedValue($selectedValue) {
        $this->selectedValue = $selectedValue;
    }

    public function AddValue($name, $value) {
        $this->values[$name] = $value;
        $this->displayValues[$name] = $value;
    }

    public function GetValues() {
        return $this->values;
    }

    public function GetDisplayValues() {
        return $this->displayValues;
    }

    public function ShowEmptyValue() {
        return true;
    }

    public function GetEmptyValue() {
        return $this->emptyValue;
    }

    public function AddMFUValue($value) {
        $this->mfuValues[] = $value;
    }

    private function PrepareMFUValues() {
        $this->preparedMfuValues = array();
        foreach ($this->mfuValues as $mfuValue) {
            if (array_key_exists($mfuValue, $this->values))
                $this->preparedMfuValues[$mfuValue] = $this->values[$mfuValue];
            elseif (in_array($mfuValue, $this->values)) {
                $key = array_search($mfuValue, $this->values);
                $this->preparedMfuValues[$key] = $mfuValue;
            }
        }
    }

    public function HasMFUValues() {
        return count($this->mfuValues) > 0;
    }

    public function GetMFUValues() {
        if ($this->HasMFUValues()) {
            if (!isset($this->preparedMfuValues))
                $this->PrepareMFUValues();
            return $this->preparedMfuValues;
        } else
            return array();
    }

    public function GetValue() {
        return $this->selectedValue;
    }

    public function SetValue($value) {
        $this->selectedValue = $value;
    }

    public function GetDataEditorClassName() {
        return 'ComboBox';
    }

    protected function DoSetAllowNullValue($value) {
        if ($value) {
            $this->values[''] = $this->emptyValue;
        } else {
            if (isset($this->values['']))
                unset($this->values['']);
        }
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

    public function CanSetupNullValues() {
        return false;
    }

    public function Accept(EditorsRenderer $Renderer) {
        $Renderer->RenderComboBox($this);
    }

    public function IsSelectedValue($value) {
        return (isset($this->selectedValue)) && ($this->GetSelectedValue() == $value);
    }

}
