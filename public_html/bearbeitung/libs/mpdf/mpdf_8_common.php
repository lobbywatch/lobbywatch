<?php

include_once dirname(__FILE__) . '/' . 'mpdf_8/autoload.php';

function createMPDF($configParams) {
    return new \Mpdf\Mpdf($configParams);
}
