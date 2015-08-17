<?php

include_once dirname(__FILE__) . '/' . 'multi_choice_editor.php';
include_once dirname(__FILE__) . '/' . '../renderers/renderer.php';

class MultiValueSelect extends MultiChoiceEditor {

    /** @var int */
    private $maxSelectionSize = 0;

    public function GetDataEditorClassName() {
        return 'MultiValueSelect';
    }

    public function Accept(EditorsRenderer $Renderer) {
        $Renderer->RenderMultiValueSelect($this);
    }

    /**
     * @param int $value
     */
    public function setMaxSelectionSize($value) {
        $this->maxSelectionSize = (int) $value;
    }

    public function getMaxSelectionSize() {
        return $this->maxSelectionSize;
    }

}
