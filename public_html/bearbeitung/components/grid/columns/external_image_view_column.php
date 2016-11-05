<?php

include_once dirname(__FILE__) . '/image_view_column.php';

class ExternalImageViewColumn extends ImageViewColumn
{
    public function GetImageLink()
    {
        return $this->getWrappedValue();
    }

    public function Accept($renderer)
    {
        $renderer->RenderImageViewColumn($this);
    }
}
