<?php

include_once dirname(__FILE__) . '/' . '../renderers/renderer.php';
include_once dirname(__FILE__) . '/' . 'custom.php';
include_once dirname(__FILE__) . '/' . '../common.php';
include_once dirname(__FILE__) . '/' . '../superglobal_wrapper.php';

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

    public function GetDataEditorClassName() {
        return 'TimeEdit';
    }

    public function Accept(EditorsRenderer $Renderer) {
        $Renderer->RenderTimeEdit($this);
    }

    public function ExtractsValueFromPost(&$valueChanged) {
        if (GetApplication()->IsPOSTValueSet($this->GetName())) {
            $valueChanged = true;
            $value = GetApplication()->GetPOSTValue($this->GetName());
            if (StringUtils::IsNullOrEmpty($value))
                return null;
            else
                return GetApplication()->GetPOSTValue($this->GetName());
        } else {
            $valueChanged = false;
            return null;
        }
    }
}
