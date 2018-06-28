<?php

include_once dirname(__FILE__) . '/' . 'custom.php';
include_once dirname(__FILE__) . '/' . '../utils/array_wrapper.php';

class StaticEditor extends CustomEditor {
    /** @inheritdoc */
    public function getEditorName()
    {
        return 'static_editor';
    }

    /** @inheritdoc */
    public function extractValueFromArray(ArrayWrapper $arrayWrapper, &$valueChanged) {
        $valueChanged = false;
    }

    /** @inheritdoc */
    public function GetValue() {
        return null;
    }

    /** @inheritdoc */
    public function SetValue($value) {
    }
}
