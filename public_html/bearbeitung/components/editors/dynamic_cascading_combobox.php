<?php

include_once dirname(__FILE__) . '/' . 'cascading_editor.php';

class DynamicCascadingComboboxLevel extends CascadingEditorLevel {

    /** @var string */
    private $formatResult;

    /** @var string */
    private $formatSelection;

    /** @return string */
    public function getFormatResult()
    {
        return $this->formatResult;
    }

    /** @param string $value */
    public function setFormatResult($value)
    {
        $this->formatResult = $value;
    }

    /** @return string */
    public function getFormatSelection()
    {
        return $this->formatSelection;
    }

    /** @param string $value */
    public function setFormatSelection($value)
    {
        $this->formatSelection = $value;
    }

}

class DynamicCascadingCombobox extends CascadingEditor {
    /** @var bool */
    private $allowClear = false;
    /** @var int */
    private $minimumInputLength = 0;
    /** @var int */
    private $numberOfValuesToDisplay = 20;

    /** @inheritdoc */
    public function createLevel($caption, $levelName, $parentLevelName, $link, $dataset, $idFieldName, $captionFieldName, $foreignKey) {
        return new DynamicCascadingComboboxLevel($caption, $levelName, $parentLevelName, $link, $dataset, $idFieldName, $captionFieldName, $foreignKey);
    }

    /** @inheritdoc */
    public function getEditorName() {
        return 'dynamic_cascading_combobox';
    }

    /** @return bool */
    public function getAllowClear() {
        return $this->allowClear;
    }

    /** @param bool $value */
    public function setAllowClear($value) {
        $this->allowClear = (bool) $value;
    }

    /** @return int */
    public function getMinimumInputLength() {
        return $this->minimumInputLength;
    }

    /** @param int $value */
    public function setMinimumInputLength($value) {
        $this->minimumInputLength = (int) $value;
    }

    /** @inheritdoc */
    public function getNumberOfValuesToDisplay() {
        return $this->numberOfValuesToDisplay;
    }

    /** @param int $value */
    public function setNumberOfValuesToDisplay($value) {
        $this->numberOfValuesToDisplay = (int) $value;
    }

}
