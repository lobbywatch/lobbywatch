<?php

class StringTransformViewColumn extends TextViewColumn {
    /** @var  string */
    private $stringTransformFunction;

    /** @return string */
    public function getStringTransformFunction() {
        return $this->stringTransformFunction;
    }

    /** @param $value */
    public function setStringTransformFunction($value) {
        $this->stringTransformFunction = $value;
    }

    public function Accept($renderer) {
        $renderer->RenderStringTransformViewColumn($this);
    }
}
