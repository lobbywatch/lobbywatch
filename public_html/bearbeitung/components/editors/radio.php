<?php

include_once dirname(__FILE__) . '/' . '../renderers/renderer.php';
include_once dirname(__FILE__) . '/' . 'custom.php';

class RadioEdit extends CustomEditor {
    /** @var array */
    private $values;
    /** @var null|string */
    private $selectedValue;
    /** @var int */
    private $displayMode;

    const StackedMode = 0;
    const InlineMode = 1;

    public function __construct($name) {
        parent::__construct($name);
        $this->values = array();
        $this->selectedValue = null;
        $this->displayMode = self::StackedMode;
    }

    public function GetSelectedValue() {
        return $this->selectedValue;
    }

    public function SetSelectedValue($selectedValue) {
        $this->selectedValue = $selectedValue;
    }

    public function AddValue($value, $name) {
        $this->values[$value] = $name;
    }

    public function GetValues() {
        return $this->values;
    }

    public function GetValue() {
        return $this->selectedValue;
    }

    public function SetValue($value) {
        $this->selectedValue = $value;
    }

    public function GetDataEditorClassName() {
        return 'RadioGroup';
    }

    public function GetDisplayMode() {
        return $this->displayMode;
    }

    public function SetDisplayMode($value) {
        $this->displayMode = $value;
    }

    /** @return bool */
    public function IsInlineMode() {
        return $this->displayMode == self::InlineMode;
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

    public function Accept(EditorsRenderer $Renderer) {
        $Renderer->RenderRadioEdit($this);
    }

}
