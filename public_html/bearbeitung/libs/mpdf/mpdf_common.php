<?php

if (version_compare(PHP_VERSION, '7.0', '<')) {
    include_once dirname(__FILE__) . '/' . 'mpdf_6_common.php';
} else {
    include_once dirname(__FILE__) . '/' . 'mpdf_8_common.php';
}
