<?php


// Run: php -f phpass_generator.php

require_once dirname(__FILE__) . '/public_html/common/utils.php';
require_once dirname(__FILE__) . '/public_html/bearbeitung/components/utils/hash_utils.php';

$debug = true;

print "Input Password: ";

$in = rtrim(fgets(STDIN), "\r\n");

//print "In: $in\n";
$hasher = new PHPassStringHasher();
$hash = $hasher->GetHash($in);
print "PHPass: $hash\n";
print 'OK: ' . $hasher->CompareHash($hash, $in) . "\n";

$hasher = new MD5StringHasher();
$hash = $hasher->GetHash($in);
print "MD5: $hash\n";

$hasher = new SHA1StringHasher();
$hash = $hasher->GetHash($in);
print "SHA1: $hash\n";

$hasher = new CryptStringHasher();
$hash = $hasher->GetHash($in);
print "Crypt: $hash\n";
