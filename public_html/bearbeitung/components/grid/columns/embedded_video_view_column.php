<?php

class EmbeddedVideoViewColumn extends AbstractDatasetFieldViewColumn
{
    public function Accept($renderer)
    {
        $renderer->RenderEmbeddedVideoViewColumn($this);
    }
}
