<?php

include_once dirname(__FILE__) . '/view_column_utils.php';

class ExternalVideoViewColumn extends AbstractWrappedDatasetFieldViewColumn
{
    /** @var string */
    private $videoPlayerHeight = '';
    /** @var string */
    private $videoPlayerWidth = '';

    /**
     * @param string $value
     */
    public function setVideoPlayerHeight($value)
    {
        $this->videoPlayerHeight = $value;
    }

    /**
     * @return string
     */
    public function getVideoPlayerHeight()
    {
        return $this->videoPlayerHeight;
    }

    /**
     * @param string $value
     */
    public function setVideoPlayerWidth($value)
    {
        $this->videoPlayerWidth = $value;
    }

    /**
     * @return string
     */
    public function getVideoPlayerWidth()
    {
        return $this->videoPlayerWidth;
    }

    /**
     * @return string
     */
    public function generateVideoPlayerSizeString()
    {
        return generateDimensionString($this->videoPlayerHeight, $this->videoPlayerWidth);
    }

    public function Accept($renderer)
    {
        $renderer->RenderExternalVideoViewColumn($this);
    }
}
