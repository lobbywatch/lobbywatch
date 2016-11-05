<?php

include_once dirname(__FILE__) . '/' . 'abstract_multi_choice_editor.php';

class MultiValueSelect extends AbstractMultiChoiceEditor {

    /** @var int */
    private $maxSelectionSize = 0;

    /**
     * @param int $value
     */
    public function setMaxSelectionSize($value) {
        $this->maxSelectionSize = (int) $value;
    }

    public function getMaxSelectionSize() {
        return $this->maxSelectionSize;
    }

    /**
     * @return string
     */
    public function getEditorName()
    {
        return 'multivalue_select';
    }
}
