<?php

class NumberViewColumn extends TextViewColumn
{
    private $numberAfterDecimal = 2;
    private $thousandsSeparator = ',';
    private $decimalSeparator = ' ';

    public function SetNumberAfterDecimal($numberAfterDecimal)
    {
        $this->numberAfterDecimal = $numberAfterDecimal;
    }

    public function SetDecimalSeparator($decimalSeparator)
    {
        $this->decimalSeparator = $decimalSeparator;
    }

    public function SetThousandsSeparator($thousandsSeparator)
    {
        $this->thousandsSeparator = $thousandsSeparator;
    }

    public function GetNumberAfterDecimal()
    {
        return $this->numberAfterDecimal;
    }

    public function GetDecimalSeparator()
    {
        return $this->decimalSeparator;
    }

    public function GetThousandsSeparator()
    {
        return $this->thousandsSeparator;
    }

    public function Accept($renderer)
    {
        $renderer->RenderNumberViewColumn($this);
    }
}
