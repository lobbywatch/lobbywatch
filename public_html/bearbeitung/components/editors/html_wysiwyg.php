<?php

include_once dirname(__FILE__) . '/' . 'custom.php';
include_once dirname(__FILE__) . '/' . '../utils/array_wrapper.php';

class HtmlWysiwygEditor extends CustomEditor {
    private $value;
    private $columnCount;
    private $rowCount;

    public function GetValue() {
        return $this->value;
    }

    public function SetValue($value) {
        $this->value = $value;
    }

    public function SetColumnCount($value) {
        $this->columnCount = $value;
    }

    public function GetColumnCount() {
        return $this->columnCount;
    }

    public function SetRowCount($value) {
        $this->rowCount = $value;
    }

    public function GetRowCount() {
        return $this->rowCount;
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
        return 'html_wysiwyg';
    }
}
