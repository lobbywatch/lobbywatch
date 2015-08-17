<?php

include_once dirname(__FILE__) . '/' . '../renderers/renderer.php';
include_once dirname(__FILE__) . '/' . 'custom.php';
include_once dirname(__FILE__) . '/' . '../common.php';

class AutocomleteComboBox extends CustomEditor {
    /** @var string */
    private $value;

    /** @var string */
    private $displayValue;

    /** @var string */
    private $handlerName;

    /** @var string */
    private $size;

    /** @var \LinkBuilder */
    private $linkBuilder;

    /** @var bool */
    private $allowClear = false;

    /** @var integer */
    private $minimumInputLength = 0;

    /** @var string */
    private $formatResult;

    /** @var string */
    private $formatSelection;

    /**
     * @param string $name
     * @param LinkBuilder $linkBuilder
     */
    public function __construct($name, LinkBuilder $linkBuilder) {
        parent::__construct($name);
        $this->size = '260px';
        $this->linkBuilder = $linkBuilder;
    }

    /**
     * @return string
     */
    public function GetDisplayValue() {
        return $this->displayValue;
    }

    /**
     * @param string $value
     * @return void
     */
    public function SetDisplayValue($value) {
        $this->displayValue = $value;
    }

    /**
     * @return string
     */
    public function GetValue() {
        return $this->value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function SetValue($value) {
        $this->value = $value;
    }

    public function GetDataEditorClassName() {
        return 'Autocomplete';
    }

    #region Options

    /**
     * @return string
     */
    public function GetSize() {
        return $this->size;
    }

    /**
     * @param string $value
     * @return void
     */
    public function SetSize($value) {
        $this->size = $value;
    }

    #endregion

    /**
     * @{inheritdoc}
     */
    public function extractValueFromArray(ArrayWrapper $arrayWrapper, &$valueChanged)
    {
        if ($arrayWrapper->isValueSet($this->GetName())) {
            $valueChanged = true;
            return $arrayWrapper->getValue($this->GetName());
        } else {
            $valueChanged = false;
            return null;
        }
    }

    /**
     * @param string $value
     * @return void
     */
    public function SetHandlerName($value) {
        $this->handlerName = $value;
    }

    /**
     * @return string
     */
    public function GetHandlerName() {
        return $this->handlerName;
    }

    /**
     * @return string
     */
    public function GetDataUrl() {
        $linkBuilder = $this->linkBuilder->CloneLinkBuilder();
        $linkBuilder->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->GetHandlerName());
        return $linkBuilder->GetLink();
    }

    /**
     * @param EditorsRenderer $Renderer
     * @return void
     */
    public function Accept(EditorsRenderer $Renderer) {
        $Renderer->RenderAutocompleteComboBox($this);
    }

    /**
     * @return bool
     */
    public function CanSetupNullValues() {
        return true;
    }

    /**
     * @param bool $value
     */
    public function setAllowClear($value)
    {
        $this->allowClear = (bool) $value;
    }

    /**
     * @return bool
     */
    public function getAllowClear()
    {
        return $this->allowClear;
    }

    /**
     * @param int $value
     */
    public function setMinimumInputLength($value)
    {
        $this->minimumInputLength = (int) $value;
    }

    /**
     * @return int
     */
    public function getMinimumInputLength()
    {
        return $this->minimumInputLength;
    }

    /**
     * @param string $formatResult
     */
    public function setFormatResult($formatResult)
    {
        $this->formatResult = $formatResult;
    }

    /**
     * @return string
     */
    public function getFormatResult()
    {
        return $this->formatResult;
    }

    /**
     * @param string $formatSelection
     */
    public function setFormatSelection($formatSelection)
    {
        $this->formatSelection = $formatSelection;
    }

    /**
     * @return string
     */
    public function getFormatSelection()
    {
        return $this->formatSelection;
    }
}
