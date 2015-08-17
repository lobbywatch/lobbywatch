<?php

include_once dirname(__FILE__) . '/' . 'editors.php';
include_once dirname(__FILE__) . '/' . '../common.php';
include_once dirname(__FILE__) . '/' . '../utils/string_utils.php';
include_once dirname(__FILE__) . '/' . '../superglobal_wrapper.php';

abstract class MultiChoiceEditor extends CustomEditor {
    private $values;
    private $selectedValues;

    /** @param string $name */
    public function __construct($name) {
        parent::__construct($name);
        $this->values = array();
        $this->selectedValues = array();
    }

    /**
     * @param string $value
     * @return bool
     */
    public function IsValueSelected($value) {
        return in_array($value, $this->selectedValues);
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function AddValue($name, $value) {
        $this->values[$name] = $value;
    }

    /**
     * @return array
     */
    public function GetValues() {
        return $this->values;
    }

    /**
     * @return mixed|string
     */
    public function GetValue() {
        $result = '';
        foreach ($this->selectedValues as $selectedValue)
            AddStr($result, $selectedValue, ',');
        return $result;
    }

    /**
     * @param mixed $value
     */
    public function SetValue($value) {
        $this->selectedValues = explode(',', $value);
    }

    /**
     * @{inheritdoc}
     */
    public function extractValueFromArray(ArrayWrapper $arrayWrapper, &$valueChanged) {
        $valueChanged = true;
        if ($arrayWrapper->isValueSet($this->GetName())) {
            $valuesArray = $arrayWrapper->GetValue($this->GetName());
            $result = '';
            foreach ($valuesArray as $value)
                AddStr($result, $value, ',');
            return $result;
        } else {
            return '';
        }
    }
}
