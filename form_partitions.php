<?php

// Organisation

$tmp = array();
$this->GetConnection()->ExecQueryToArray("
SELECT DISTINCT
upper(left(o.name_de, 1)) as first_letter
FROM v_organisation o
ORDER BY first_letter", $tmp
);

foreach($tmp as $letter) {
  $partitions[$letter['first_letter']] = convert_ansi($letter['first_letter']);
}

//
$condition = "upper(left(name_de, 1)) = '$partitionKey'";


// Parlamentarier

$tmp = array();
$this->GetConnection()->ExecQueryToArray("
SELECT DISTINCT
upper(left(p.nachname, 1)) as first_letter
FROM v_parlamentarier p
ORDER BY first_letter", $tmp
);

foreach($tmp as $letter) {
  $partitions[$letter['first_letter']] = convert_ansi($letter['first_letter']);
}

//
$condition = "upper(left(nachname, 1)) = '$partitionKey'";

// Interessengruppe

$tmp = array();
$this->GetConnection()->ExecQueryToArray("
SELECT DISTINCT
upper(left(i.name, 1)) as first_letter
FROM v_interessengruppe i
ORDER BY first_letter", $tmp
);

foreach($tmp as $letter) {
  $partitions[$letter['first_letter']] = convert_ansi($letter['first_letter']);
}

//
$condition = "upper(left(interessengruppe.name, 1)) = '$partitionKey'";
