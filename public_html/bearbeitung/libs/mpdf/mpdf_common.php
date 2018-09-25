<?php

if (version_compare(PHP_VERSION, '5.6.0', '<')) {
    include_once dirname(__FILE__) . '/' . 'mpdf_6_common.php';
} else {
    include_once dirname(__FILE__) . '/' . 'mpdf_7_common.php';
}
