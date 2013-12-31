<?php


// Run: php -f phpass_checker.php

require_once dirname(__FILE__) .  '/public_html/common/utils.php';
require_once dirname(__FILE__) . '/public_html/bearbeitung/components/utils/hash_utils.php';

$debug = true;

$stored_hash = '$2a$08$SPgG41mQK/F8xCtpbV16OeYUuuzGXI6biAESRJIvzYLeAnf3RLEm2';

print "Input Password: ";

$in = rtrim(fgets(STDIN), "\r\n");
//print "In: $in\n";
$hasher = new PHPassStringHasher();
// $hash = $hasher->GetHash($in);
print "PHPass: $stored_hash\n";
print 'OK: ' . $hasher->CompareHash($stored_hash, $in) . "\n";
