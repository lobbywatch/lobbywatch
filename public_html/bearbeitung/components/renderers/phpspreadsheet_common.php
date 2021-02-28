<?php

require_once dirname(__FILE__) . '/../../libs/phpspreadsheet/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

function CreatePHPExcelObject() {
    return new Spreadsheet();
}

function CreatePHPExcelIOWriter($phpExcelObject) {
    return IOFactory::createWriter($phpExcelObject, 'Xls');
}
