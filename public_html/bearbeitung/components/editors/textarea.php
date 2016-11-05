<?php

include_once dirname(__FILE__) . '/' . 'custom.php';
include_once dirname(__FILE__) . '/' . '../utils/array_wrapper.php';

class TextAreaEdit extends CustomEditor {
    private $value;
    private $columnCount;
    private $rowCount;
    private $allowHtmlCharacters = true;
    /** @var string */
    private $placeholder = null;

    public function __construct($name, $columnCount = null, $rowCount = null, $customAttributes = null) {
        parent::__construct($name, $customAttributes);
        $this->columnCount = $columnCount;
        $this->rowCount = $rowCount;
    }

    public function GetValue() {
        return $this->value;
    }

    public function SetValue($value) {
        $this->value = $value;
    }

    #region Editor options

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

    public function GetAllowHtmlCharacters() {
        return $this->allowHtmlCharacters;
    }

    public function SetAllowHtmlCharacters($value) {
        $this->allowHtmlCharacters = $value;
    }

    public function getPlaceholder() {
        return $this->placeholder;
    }

    public function setPlaceholder($value) {
        $this->placeholder = $value;
        return $this;
    }

    #endregion

    public function extractValueFromArray(ArrayWrapper $arrayWrapper, &$valueChanged) {
        if ($arrayWrapper->IsValueSet($this->GetName())) {
            $valueChanged = true;
            $value = $arrayWrapper->GetValue($this->GetName());
            if (!$this->allowHtmlCharacters)
                $value = htmlspecialchars($value, ENT_QUOTES);
            return $value;
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
        return 'textarea';
    }
}
