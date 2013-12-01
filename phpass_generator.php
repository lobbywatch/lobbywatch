<?php

// Run: php -f phpass_generator.php

require_once dirname(__FILE__) . '/public_html/bearbeitung/components/utils/hash_utils.php';

print "Input Password: ";

$in = fgets(STDIN);
//print "In: $in\n";
$hasher = new PHPassStringHasher();
$out = $hasher->GetHash($in);
print "PasswordHash: $out\n";
