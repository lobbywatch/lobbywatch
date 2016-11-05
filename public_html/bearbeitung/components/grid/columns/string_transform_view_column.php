<?php

class StringTransformViewColumn extends TextViewColumn
{
    private $stringTransformFunction;

    public function getStringTransformFunction()
    {
        if (function_exists($this->stringTransformFunction)) {
            return $this->stringTransformFunction;
        }

        return create_function('$x', 'return $x;');
    }

    public function setStringTransformFunction($stringTransformFunction)
    {
        $this->stringTransformFunction = $stringTransformFunction;
    }

    public function Accept($renderer)
    {
        $renderer->RenderStringTransformViewColumn($this);
    }
}
