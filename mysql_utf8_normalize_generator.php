<?php

// php -f mysql_utf8_normalize_generator.php

require_once dirname(__FILE__) . '/public_html/settings/settings.php';
require_once dirname(__FILE__) . '/public_html/common/utils.php';

global $today;
global $sql_today;
global $transaction_date;
global $sql_transaction_date;

$optionen = array (
    PDO::ATTR_PERSISTENT => true
);
$db = new PDO ( 'mysql:host=localhost;dbname=' . $db_connection['database'] . ';charset=utf8', $db_connection['reader_username'], $db_connection['reader_password'], $optionen );
// Disable prepared statement emulation, http://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$workflow_tables = array(
    'branche' => 'Branche',
    'interessenbindung' => 'Interessenbindung',
    'interessenbindung_jahr' => 'Interessenbindungsverguetung',
    'interessengruppe' => 'Lobbygruppe',
    'in_kommission' => 'In Kommission',
    'kommission' => 'Kommission',
    'mandat' => 'Mandat',
    'mandat_jahr' => 'Mandatsverguetung',
    'organisation' => 'Organisation',
    'organisation_beziehung' => 'Organisation Beziehung',
    'organisation_jahr' => 'Organisationsjahr',
    'parlamentarier' => 'Parlamentarier',
    'partei' => 'Partei',
    'fraktion' => 'Fraktion',
    'rat' => 'Rat',
    'kanton' => 'Kanton',
    'kanton_jahr' => 'Kantonjahr',
    'zutrittsberechtigung' => 'Zutrittsberechtigung',
    'person' => 'Person',
);

$meta_tables = array(
    'parlamentarier_anhang' => 'Parlamentarieranhang',
    'organisation_anhang' => 'Organisationsanhang',
    'person_anhang' => 'Personenanhang',
    'settings' => 'Einstellungen',
    'settings_category' => 'Einstellungskategorien',
);

$tables = array_merge($workflow_tables, $meta_tables);

print("Tables: " . implode(array_keys($tables), ', ') . "\n");

$to_normalize = 'äöüéèêàç';
// $to_normalize = 'äöü';
$to_replace = [];

$replace_sql = [];

foreach (mb_str_split($to_normalize) as $base_char) {
    for ($i = 0; $i < 2; $i++) {
        $char = $i == 0 ? $base_char : mb_strtoupper($base_char);
        $unnormalized_char = Normalizer::normalize($char, Normalizer::FORM_D);
        print($char . ": UTF-8: " . get_hex($char) . ", " . urlencode($char) . ", Unicode: " . get_unicode_code_point($char)  . " | " . $unnormalized_char . ": UTF-8: " . get_hex($unnormalized_char) . ", " . urlencode($unnormalized_char) . ", Unicode: " . get_unicode_code_point($unnormalized_char) . "\n");
        
        $to_replace_row = ['findUtf8Hex' => get_hex($unnormalized_char), 'replaceUtf8Hex' => get_hex($char), 'findUCP' => get_unicode_code_point($char), 'replaceUCP' => get_unicode_code_point($unnormalized_char), 'find' => $unnormalized_char, 'replace' => $char];
        $to_replace[] = $to_replace_row;
        
        $update_char_sqls[] = "'UPDATE ', TABLE_NAME, ' SET ', COLUMN_NAME, '=REPLACE(', COLUMN_NAME, ', _utf8 x''{$to_replace_row['findUtf8Hex']}'', _utf8 x''{$to_replace_row['replaceUtf8Hex']}''), updated_visa=''roland'', updated_date=" . str_replace("'", "''", $sql_transaction_date) . " WHERE ', COLUMN_NAME, ' LIKE BINARY CONCAT(''%'', _utf8 x''{$to_replace_row['findUtf8Hex']}'', ''%''); -- {$to_replace_row['find']} → {$to_replace_row['replace']}, {$to_replace_row['findUCP']} → {$to_replace_row['replaceUCP']}', '\n'";
        
        $select_char_sqls[] = "'SELECT ''', TABLE_NAME, ''' AS TABLE_NAME, ID, ''', COLUMN_NAME, ''' AS COLUMN_NAME, ''{$to_replace_row['replace']}'' AS CHAR_NAME, ', COLUMN_NAME, ' FROM ', TABLE_NAME, ' WHERE ', COLUMN_NAME, ' LIKE BINARY CONCAT(''%'', _utf8 x''{$to_replace_row['findUtf8Hex']}'', ''%'') -- {$to_replace_row['find']} → {$to_replace_row['replace']}, {$to_replace_row['findUCP']} → {$to_replace_row['replaceUCP']}', '\n'";
    }
}

$sql = "
SELECT
    CONCAT('SELECT * FROM ', TABLE_NAME, ' WHERE ',TABLE_NAME,'.',COLUMN_NAME, ' LIKE BINARY CONCAT(''%'', _utf8 x''CC88'', ''%'')', '\n') AS simple_select_stmt,
    CONCAT(" . implode($update_char_sqls, "\n") . ") AS update_stmt,
    CONCAT(" . implode($select_char_sqls, "\n, 'UNION ', ") . ") AS select_stmt
FROM
    INFORMATION_SCHEMA.COLUMNS
WHERE
    TABLE_SCHEMA = '${db_connection['database']}' AND
    DATA_TYPE IN ('VARCHAR', 'TEXT') AND
    -- TABLE_NAME = 'organisation' AND
    TABLE_NAME IN ('" . implode(array_keys($tables), "', '") . "') AND
    -- COLUMN_NAME LIKE 'name%'
    ;" ;

print("\n" . $sql . "\n");

$simple_union = [];
$select_union = [];
$update_stmts = [];
foreach ($db->query($sql) as $row) {
    $union[] = $row['simple_select_stmt'];
    $update_stmts[] = $row['update_stmt'];
    $select_union[] = $row['select_stmt'];
}

print("\nSIMPLE SELECT\n-------\n" . implode($union, " UNION \n")) . "\n";
print("\nSELECT\n-------\n" . implode($select_union, " UNION \n")) . "\n";
print("\nUPDATE\n-------\n" . implode($update_stmts, "\n")) . "\n";

// http://stackoverflow.com/questions/7106470/utf-8-to-unicode-code-points
function get_unicode_code_point($str) {
    $codes = [];
    foreach(mb_str_split($str) as $char) {
        $codes[] = sprintf('U+%04X', IntlChar::ord($char));
    }
    return implode($codes, ' ');
}

// http://stackoverflow.com/questions/4601032/php-iterate-on-string-characters
function mb_str_split($str) { 
    # Split at all position not after the start: ^ 
    # and not before the end: $ 
    return preg_split('/(?<!^)(?!$)/u', $str); 
}

function get_hex($str) {
    $code = "";
    for ($i = 0; $i < strlen($str); $i++){
        $code .= sprintf('%02X', ord($str[$i]));
    }
    return $code;
}
