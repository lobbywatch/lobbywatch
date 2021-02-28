<?php

require_once dirname(__FILE__) . '/../../libs/phpoffice/PHPExcel.php';

function CreatePHPExcelObject() {
    return new PHPExcel();
}

function CreatePHPExcelIOWriter($phpExcelObject) {
    return PHPExcel_IOFactory::createWriter($phpExcelObject, 'Excel5');
}
