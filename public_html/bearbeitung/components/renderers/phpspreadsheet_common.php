<?php

require_once dirname(__FILE__) . '/../../libs/phpspreadsheet/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

function CreatePHPExcelObject() {
    return new Spreadsheet();
}

function CreatePHPExcelObjectBasedOnHTML($htmlContent) {
    $reader = IOFactory::createReader('Html');
    $spreadsheet = $reader->loadFromString($htmlContent);
    return $spreadsheet;
}

function CreatePHPExcelIOWriter($phpExcelObject, $fileFormat) {
    if ($fileFormat == 'xlsx') {
        return IOFactory::createWriter($phpExcelObject, 'Xlsx');
    } else {
        return IOFactory::createWriter($phpExcelObject, 'Xls');
    }
}
