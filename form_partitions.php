<?php

// Organisation

$tmp = array();
$this->GetConnection()->ExecQueryToArray("
SELECT DISTINCT
left(o.name_de, 1) as first_letter
FROM v_organisation o
ORDER BY first_letter", $tmp
);

foreach($tmp as $letter) {
  $partitions[$letter['first_letter']] = $letter['first_letter'];
}

//
$condition = "left(name_de, 1) = '$partitionKey'";
