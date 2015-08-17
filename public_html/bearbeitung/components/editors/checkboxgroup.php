<?php

include_once dirname(__FILE__) . '/' . 'multi_choice_editor.php';
include_once dirname(__FILE__) . '/' . '../renderers/renderer.php';

class CheckBoxGroup extends MultiChoiceEditor {
    /** @var int */
    private $displayMode;

    const StackedMode = 0;
    const InlineMode = 1;

    /** @param string $name */
    public function __construct($name) {
        parent::__construct($name);
        $this->displayMode = self::StackedMode;
    }

    public function GetDataEditorClassName() {
        return 'CheckBoxGroup';
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

    public function Accept(EditorsRenderer $Renderer) {
        $Renderer->RenderCheckBoxGroup($this);
    }
}
