<?php

include_once dirname(__FILE__) . '/' . '../renderers/renderer.php';
include_once dirname(__FILE__) . '/' . 'spin.php';

class RangeEdit extends SpinEdit {

    public function GetDataEditorClassName() {
        return 'RangeEdit';
    }

    public function Accept(EditorsRenderer $Renderer) {
        $Renderer->RenderRangeEdit($this);
    }
}
