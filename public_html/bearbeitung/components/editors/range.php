<?php

include_once dirname(__FILE__) . '/' . 'spin.php';

class RangeEdit extends SpinEdit
{
    /**
     * @return string
     */
    public function getEditorName()
    {
        return 'range';
    }
}
