<?php

require_once dirname(__FILE__) . '/../../libs/phpoffice/PHPExcel.php';

function CreatePHPExcelObject() {
    return new PHPExcel();
}

function CreatePHPExcelObjectBasedOnHTML($htmlContent) {
    $reader = PHPExcel_IOFactory::createReader('HTML');
    $tempFileName = tempnam(sys_get_temp_dir(), "smpgtemp");
    file_put_contents($tempFileName, $htmlContent);
    $phpExcelObject = $reader->load($tempFileName);
    return $phpExcelObject;
}

function CreatePHPExcelIOWriter($phpExcelObject, $fileFormat) {
    if ($fileFormat == 'xlsx') {
        return PHPExcel_IOFactory::createWriter($phpExcelObject, 'Excel2007');
    } else {
        return PHPExcel_IOFactory::createWriter($phpExcelObject, 'Excel5');
    }
}
