<?php

include_once dirname(__FILE__) . '/abstract_csv_renderer.php';

class CsvRecordRenderer extends AbstractCsvRenderer
{
    protected function getGridPagePart()
    {
        return PagePart::RecordCard;
    }
}
