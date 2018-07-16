<?php
// Processed by afterburner.sh



include_once dirname(__FILE__) . '/' . 'renderer.php';
include_once dirname(__FILE__) . '/' . '../dataset_rss_generator.php';

// require_once 'components/renderers/renderer.php';
// require_once 'components/dataset_rss_generator.php';

class RssRenderer extends Renderer
{
    public function RenderPage(Page $Page)
    {
        header('Content-type: text/xml');
        $rssGenerator = $Page->GetRssGenerator();
        header("Content-Type: application/rss+xml;charset= utf-8 ");
        $this->result = $rssGenerator->Generate();
    }

    public function RenderGrid(Grid $Grid)
    { }
}
