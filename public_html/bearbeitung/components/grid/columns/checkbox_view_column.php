<?php

class CheckboxViewColumn extends AbstractDatasetFieldViewColumn
{
    private $trueValue;
    private $falseValue;

    public function SetDisplayValues($trueValue, $falseValue)
    {
        $this->trueValue = $trueValue;
        $this->falseValue = $falseValue;
    }

    public function GetTrueValue()
    {
        return $this->trueValue;
    }

    public function GetFalseValue()
    {
        return $this->falseValue;
    }

    public function Accept($renderer)
    {
        $renderer->RenderCheckboxViewColumn($this);
    }
}
