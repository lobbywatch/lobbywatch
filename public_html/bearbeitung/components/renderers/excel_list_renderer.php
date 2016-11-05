<?php

include_once dirname(__FILE__) . '/abstract_excel_renderer.php';

class ExcelListRenderer extends AbstractExcelRenderer
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
