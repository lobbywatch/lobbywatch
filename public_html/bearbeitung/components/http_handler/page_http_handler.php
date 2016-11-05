<?php

include_once dirname(__FILE__) . '/abstract_http_handler.php';

class PageHttpHandler extends AbstractHTTPHandler
{
    /** @var Page */
    private $page;

    public function __construct($name, $page)
    {
        parent::__construct($name);
        $this->page = $page;
    }

    public function Render(Renderer $renderer)
    {
        $this->page->BeginRender();
        $this->page->EndRender();
    }

    public function getPage() {
        return $this->page;
    }
}
