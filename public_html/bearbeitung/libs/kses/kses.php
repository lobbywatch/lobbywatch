<?php

if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    include_once dirname(__FILE__) . '/' . 'kses_5_2.php';
} else {
    include_once dirname(__FILE__) . '/' . 'kses_5_3.php';
}
