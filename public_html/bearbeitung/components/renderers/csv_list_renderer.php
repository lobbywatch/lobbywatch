<?php

include_once dirname(__FILE__) . '/abstract_csv_renderer.php';

class CsvListRenderer extends AbstractCsvRenderer
{
    protected function getGridPagePart()
    {
        return PagePart::Grid;
    }

    protected function getCurrentRowData(Grid $grid)
    {
        return null;
    }
}
