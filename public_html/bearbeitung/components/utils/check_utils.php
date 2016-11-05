<?php

function CheckTemplatesCacheFolderIsExistsAndWritable() {
    $templatesCacheFolder = 'templates_c';
    if (!file_exists($templatesCacheFolder) || !is_writable($templatesCacheFolder)) {

        header('Content-Type: text/html; charset=UTF-8');

        $result = file_get_contents('components/templates/templates_c_folder_warning.html');
        $result = str_replace('{MESSAGE}', 'Error: the templates_c directory does not exist or is not writable', $result);
        $result = str_replace('{DETAILS}', 'Please make sure that the templates_c directory does exist in the root directory of the generated application and it is writable by the web server user.', $result);
        echo $result;
        exit;
    }
}

function CheckPHPVersion() {
    if (version_compare(PHP_VERSION, '5.2.0', '<=')) {
        header('Content-Type: text/html; charset=UTF-8');
        echo str_replace('{PHP_VERSION}', PHP_VERSION,
            file_get_contents('components/templates/unsupported_php_version.html'));
        exit;
    }
}

function CheckMbStringExtension() {
    if (!function_exists("mb_strlen")) {
        header('Content-Type: text/html; charset=UTF-8');
        $result = file_get_contents('components/templates/required_extension.html');
        $result = str_replace('{MESSAGE}', 'mPDF requires mb_string functions', $result);
        $result = str_replace('{DETAILS}', 'Ensure that PHP is compiled with <strong>php_mbstring.dll</strong> enabled. <br/>See more: ' .
            '<a href="http://php.net/manual/en/mbstring.installation.php">http://php.net/manual/en/mbstring.installation.php</a>', $result);
        echo $result;
        exit;
    }
}

function CheckIconvExtension() {
    if (!function_exists("iconv")) {
        header('Content-Type: text/html; charset=UTF-8');
        $result = file_get_contents('components/templates/required_extension.html');
        $result = str_replace('{MESSAGE}', 'mPDF requires iconv extension', $result);
        $result = str_replace('{DETAILS}', 'Ensure that PHP is compiled with <strong>php_iconv</strong> enabled. <br/>See more: ' .
            '<a href="http://www.php.net/manual/en/iconv.installation.php">http://www.php.net/manual/en/iconv.installation.php</a>', $result);
        echo $result;
        exit;
    }
}
