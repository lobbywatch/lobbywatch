<?php

class PercentViewColumn extends NumberViewColumn
{
    public function Accept($renderer)
    {
        $renderer->RenderPercentViewColumn($this);
    }
}
