<?php

class ExternalAudioViewColumn extends AbstractWrappedDatasetFieldViewColumn
{
    public function Accept($renderer)
    {
        $renderer->RenderExternalAudioViewColumn($this);
    }
}
