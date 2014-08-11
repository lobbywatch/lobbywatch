<?php

// Organisation

$tmp = array();
$this->GetConnection()->ExecQueryToArray("
SELECT DISTINCT
left(o.name_de, 1) as first_letter
FROM organisation o
ORDER BY first_letter", $tmp
);

foreach($tmp as $letter) {
  $partitions[$letter['first_letter']] = convert_ansi(strtoupper($letter['first_letter']));
}

//
$condition = "left(organisation.name_de, 1) = '$partitionKey'";


// Parlamentarier

$tmp = array();
$this->GetConnection()->ExecQueryToArray("
SELECT DISTINCT
left(p.nachname, 1) as first_letter
FROM parlamentarier p
ORDER BY first_letter", $tmp
);

foreach($tmp as $letter) {
  $partitions[$letter['first_letter']] = convert_ansi(strtoupper($letter['first_letter']));
}

//
$condition = "left(parlamentarier.nachname, 1) = '$partitionKey'";

// Interessengruppe

$tmp = array();
$this->GetConnection()->ExecQueryToArray("
SELECT DISTINCT
left(i.name, 1) as first_letter
FROM interessengruppe i
ORDER BY first_letter", $tmp
);

foreach($tmp as $letter) {
  $partitions[$letter['first_letter']] = convert_ansi(strtoupper($letter['first_letter']));
}

//
$condition = "left(interessengruppe.name, 1) = '$partitionKey'";

// Interessenbindung

$tmp = array();
$this->GetConnection()->ExecQueryToArray("
SELECT DISTINCT
left(p.nachname, 1) as first_letter
FROM parlamentarier p
ORDER BY first_letter", $tmp
);

foreach($tmp as $letter) {
  $partitions[$letter['first_letter']] = convert_ansi(strtoupper($letter['first_letter']));
}

//
$condition = "interessenbindung.parlamentarier_id IN (SELECT `id` FROM `parlamentarier` s WHERE left(s.nachname, 1) = '$partitionKey')";

// Zutrittsberechtigung

$tmp = array();
$this->GetConnection()->ExecQueryToArray("
SELECT DISTINCT
left(i.nachname, 1) as first_letter
FROM zutrittsberechtigung i
ORDER BY first_letter", $tmp
);

foreach($tmp as $letter) {
  $partitions[$letter['first_letter']] = convert_ansi(strtoupper($letter['first_letter']));
}

//
$condition = "left(zutrittsberechtigung.nachname, 1) = '$partitionKey'";

// Mandat

$tmp = array();
$this->GetConnection()->ExecQueryToArray("
SELECT DISTINCT
left(p.nachname, 1) as first_letter
FROM zutrittsberechtigung p
ORDER BY first_letter", $tmp
);

foreach($tmp as $letter) {
  $partitions[$letter['first_letter']] = convert_ansi(strtoupper($letter['first_letter']));
}

//
$condition = "mandat.zutrittsberechtigung_id IN (SELECT `id` FROM `zutrittsberechtigung` s WHERE left(s.nachname, 1) = '$partitionKey')";
