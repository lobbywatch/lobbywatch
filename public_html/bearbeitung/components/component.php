<?php

class Component
{
    /** @var string */
    private $name;

    /** @var boolean */
    private $allowNullValue;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->allowNullValue = true;
    }

    /**
     * @return string
     */
    function GetName() { return $this->name; }

    /**
     * @param string $value
     * @return void
     */
    function SetName($value) { $this->name = $value; }

    public function ProcessMessages()
    { }

    public function CanSetupNullValues()
    { return false; }

    protected function DoSetAllowNullValue($value)
    { }
    public function SetAllowNullValue($value)
    {
        if ($this->allowNullValue != $value)
        {
            $this->allowNullValue = $value;
            $this->DoSetAllowNullValue($value);
        }
    }
    public function GetAllowNullValue()
    { return $this->allowNullValue; }
}
