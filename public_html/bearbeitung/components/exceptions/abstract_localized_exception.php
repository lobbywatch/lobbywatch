<?php

abstract class AbstractLocalizedException extends Exception
{
    /**
     * @param Captions $captions
     *
     * @return string
     */
    abstract public function getLocalizedMessage(Captions $captions);
}
