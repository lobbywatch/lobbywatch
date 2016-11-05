<?php

include_once dirname(__FILE__) . '/abstract_http_handler.php';
include_once dirname(__FILE__) . '/../grid/vertical_grid.php';

class GridEditHandler extends AbstractHTTPHandler
{
    /** @var VerticalGrid */
    private $grid;

    public function __construct($name, VerticalGrid $grid)
    {
        parent::__construct($name);
        $this->grid = $grid;
    }

    public function Render(Renderer $renderer)
    {
        $this->grid->GetGrid()->getPage()->UpdateValuesFromUrl();
        $this->grid->ProcessMessages();
        echo $renderer->Render($this->grid);
    }
}
