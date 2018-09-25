<?php

include_once dirname(__FILE__) . '/' . 'mpdf_6/mpdf.php';

function createMPDF($configParams) {
    return new mPDF(
        $configParams['mode'],
        $configParams['format'],
        $configParams['default_font_size'],
        $configParams['default_font'],
        $configParams['margin_left'],
        $configParams['margin_right'],
        $configParams['margin_top'],
        $configParams['margin_bottom'],
        $configParams['margin_header'],
        $configParams['margin_footer']
    );
}
