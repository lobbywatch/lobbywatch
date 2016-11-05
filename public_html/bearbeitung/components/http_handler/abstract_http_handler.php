<?php

abstract class AbstractHTTPHandler
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function GetName()
    {
        return $this->name;
    }

    public abstract function Render(Renderer $renderer);
}
