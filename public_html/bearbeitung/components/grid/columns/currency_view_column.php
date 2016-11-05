<?php

class CurrencyViewColumn extends NumberViewColumn
{
    private $currencySign;

    public function setCurrencySign($currencySign)
    {
        $this->currencySign = $currencySign;
    }

    public function getCurrencySign()
    {
        return $this->currencySign;
    }

    public function Accept($renderer)
    {
        $renderer->RenderCurrencyViewColumn($this);
    }
}
