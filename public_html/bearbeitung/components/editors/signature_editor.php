<?php

include_once dirname(__FILE__) . '/' . '../utils/array_wrapper.php';
include_once dirname(__FILE__) . '/' . 'custom.php';

class SignatureEditor extends CustomEditor {
    /** @var  string */
    private $value;

    /** @var int */
    private $drawAreaWidth = 300;

    /** @var int */
    private $drawAreaHeight = 150;

    /** @var string */
    private $penColor;

    /** @var string */
    private $backgroundColor;

    /** @var string */
    private $formatForSaving = 'png';

    /**
     * @param string $name
     * @param int $drawAreaWidth
     * @param int $drawAreaHeight
     * @param string $penColor
     * @param string $backgroundColor
     * @param string $formatForSaving
     */
    public function __construct($name, $drawAreaWidth, $drawAreaHeight, $penColor, $backgroundColor, $formatForSaving)
    {
        parent::__construct($name);
        $this->drawAreaWidth = $drawAreaWidth;
        $this->drawAreaHeight = $drawAreaHeight;
        $this->penColor = $penColor;
        $this->backgroundColor = $backgroundColor;
        $this->formatForSaving = $formatForSaving;
    }

    /** @inheritdoc */
    public function GetValue() {
        return $this->value;
    }

    /** @inheritdoc */
    public function SetValue($value) {
        $this->value = $value;
    }

    /** @inheritdoc */
    public function extractValueFromArray(ArrayWrapper $arrayWrapper, &$valueChanged) {
        if ($arrayWrapper->IsValueSet($this->GetName())) {
            $valueChanged = true;
            return $arrayWrapper->GetValue($this->GetName());
        } else {
            $valueChanged = false;
            return null;
        }
    }

    /** @return string */
    public function getEditorName() {
        return 'signature';
    }

    public function getDrawAreaHeight() {
        return $this->drawAreaHeight;
    }

    public function setDrawAreaHeight($value) {
        $this->drawAreaHeight = $value;
    }

    public function getDrawAreaWidth() {
        return $this->drawAreaWidth;
    }

    public function setDrawAreaWidth($value) {
        $this->drawAreaWidth = $value;
    }

    public function setPenColor($value) {
        $this->penColor = $value;
    }

    public function getPenColor() {
        return $this->penColor;
    }

    public function setBackgroundColor($value) {
        $this->backgroundColor = $value;
    }

    public function getBackgroundColor() {
        return $this->backgroundColor;
    }

    public function setFormatForSaving($value) {
        $this->formatForSaving = $value;
    }

    public function getFormatForSaving() {
        return $this->formatForSaving;
    }
}
