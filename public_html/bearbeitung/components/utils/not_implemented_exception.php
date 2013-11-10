<?php

class NotImplementedException extends LogicException
{
    public function __construct()
    {
        parent::__construct('Not implemented');
    }
}