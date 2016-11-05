<?php

include_once dirname(__FILE__) . '/abstract_excel_renderer.php';

class ExcelRecordRenderer extends AbstractExcelRenderer
{
    protected function getGridPagePart()
    {
        return PagePart::RecordCard;
    }
}
