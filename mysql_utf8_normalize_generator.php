<?php
declare(strict_types=1);

// php -f mysql_utf8mb4_normalize_generator.php
// php -f mysql_utf8mb4_normalize_generator.php -- -s > sql/mysql_utf8mb4_normalize_query.sql
// php -f mysql_utf8mb4_normalize_generator.php -- -u > sql/mysql_utf8mb4_normalize_update.sql
// php mysql_utf8_normalize_generator.php -v -s  --db=lobbywatchtest > sql/20210103_mysql_utf8_normalize_query.sql && ./deploy.sh -s sql/20210103_mysql_utf8_normalize_query.sql -l && less sql/20210103_mysql_utf8_normalize_query.sql.log
// php mysql_utf8_normalize_generator.php -v -u  --db=lobbywatchtest > sql/20210103_mysql_utf8_normalize_update.sql && ./deploy.sh -s sql/20210103_mysql_utf8_normalize_update.sql -l && less sql/20210103_mysql_utf8_normalize_update.sql.log

// MySQL: set in my.cnf: thread_stack=4194304

require_once dirname(__FILE__) . '/public_html/settings/settings.php';
require_once dirname(__FILE__) . '/public_html/common/utils.php';


// ini_set('memory_limit','1024M');

/*

CREATE TABLE lw_analysis.normalize_20210103 SELECT * FROM (
...
  ) big_union;

SELECT COUNT(*) FROM `normalize_20210103`;
SELECT `CHAR_FROM`, `CHAR_FROM_UCP`, COUNT(*) FROM `normalize_20210103` GROUP BY `CHAR_FROM`, `CHAR_FROM_UCP` ORDER BY `CHAR_FROM`;;
SELECT `TABLE_NAME`, COUNT(*) FROM `normalize_20210103` GROUP BY `TABLE_NAME` ORDER BY `TABLE_NAME`;
SELECT `TABLE_NAME`, `CHAR_FROM`, `CHAR_FROM_UCP`, COUNT(*) FROM `normalize_20210103` GROUP BY `TABLE_NAME`, `CHAR_FROM`, `CHAR_FROM_UCP` ORDER BY `TABLE_NAME`, `CHAR_FROM`;
SELECT `COLUMN_NAME`, COUNT(*) FROM `normalize_20210103` GROUP BY `COLUMN_NAME` ORDER BY `COLUMN_NAME`;
SELECT `COLUMN_NAME`,`CHAR_FROM`, `CHAR_FROM_UCP`, COUNT(*) FROM `normalize_20210103` GROUP BY `COLUMN_NAME`,`CHAR_FROM`, `CHAR_FROM_UCP` ORDER BY `COLUMN_NAME`,`CHAR_FROM`;
SELECT `TABLE_NAME`, `COLUMN_NAME`, `CHAR_FROM`, `CHAR_FROM_UCP`, COUNT(*) FROM `normalize_20210103` GROUP BY `TABLE_NAME`, `COLUMN_NAME`, `CHAR_FROM`, `CHAR_FROM_UCP` ORDER BY `TABLE_NAME`, `COLUMN_NAME`, `CHAR_FROM`;

*/

/*
MySQL Special Character Escape Sequences
https://dev.mysql.com/doc/refman/5.7/en/string-literals.html

\0                  An ASCII NUL (X'00') character
\'                  A single quote (') character
\"                  A double quote (") character
\b                  A backspace character
\n                  A newline (linefeed) character
\r                  A carriage return character
\t                  A tab character
\Z                  ASCII 26 (Control+Z); see note following the table
\\                  A backslash (\) character
\%                  A % character; see note following the table
\_                  A _ character; see note following the table

*/

/* PHP double quote strings (")
https://www.php.net/manual/de/language.types.string.php

Sequence 	Meaning
\n                  linefeed (LF or 0x0A (10) in ASCII)
\r                  carriage return (CR or 0x0D (13) in ASCII)
\t                  horizontal tab (HT or 0x09 (9) in ASCII)
\v                  vertical tab (VT or 0x0B (11) in ASCII)
\e                  escape (ESC or 0x1B (27) in ASCII)
\f                  form feed (FF or 0x0C (12) in ASCII)
\\                  backslash
\$                  dollar sign
\"                  double-quote
\[0-7]{1,3}         the sequence of characters matching the regular expression is a character in octal notation, which silently overflows to fit in a byte (e.g. "\400" === "\000")
\x[0-9A-Fa-f]{1,2}  the sequence of characters matching the regular expression is a character in hexadecimal notation
\u{[0-9A-Fa-f]+}    the sequence of characters matching the regular expression is a Unicode codepoint, which will be output to the string as that codepoint's UTF-8 representation

*/

/* PHP single quote strings (')
https://www.php.net/manual/de/language.types.string.php

\\                  backslash
\'                  single-quote

*/

/* PCRE regex escaping
https://www.php.net/manual/en/regexp.reference.escape.php

Single and double quoted PHP strings have special meaning of backslash. Thus if \ has to be matched with a regular expression \\, then "\\\\" or '\\\\' must be used in PHP code.

\a    alarm, that is, the BEL character (hex 07)
\cx   "control-x", where x is any character
\e    escape (hex 1B)
\f    formfeed (hex 0C)
\n    newline (hex 0A)
\p{xx}    a character with the xx property, see unicode properties for more info
\P{xx}    a character without the xx property, see unicode properties for more info
\r    carriage return (hex 0D)
\R    line break: matches \n, \r and \r\n
\t    tab (hex 09)
\xhh    character with hex code hh
\ddd    character with octal code ddd, or backreference

*/

global $sql_today;
global $sql_transaction_date;

global $context;
global $db;
global $db_connection;
global $today;
global $transaction_date;
global $errors;
global $verbose;
global $env;
global $user;
global $db_name;
global $db_con;

// $optionen = array (
//     PDO::ATTR_PERSISTENT => true
// );
// $db = new PDO ( 'mysql:host=127.0.0.1;dbname=' . $db_connection['database'] . ';charset=utf8mb4', $db_connection['reader_username'], $db_connection['reader_password'], $optionen );
// // Disable prepared statement emulation, http://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php
// $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
// $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$workflow_tables = Constants::$workflow_tables;
// delete tables to save memory
unset($workflow_tables['rat']);
unset($workflow_tables['wissensartikel_link']);

$meta_tables = Constants::$meta_tables;
$tables = array_merge($workflow_tables, $meta_tables);

//     var_dump($argc); //number of arguments passed
//     var_dump($argv); //the arguments passed

$options = getopt('suvh', ['db:', 'help']);

 if (isset($options['h']) || isset($options['help'])) {
    print("Unicode/UTF-8 normalize SQL generator for Lobbywatch.ch.
Clean strings.

Parameters:
-s              Generate SELECT query
-u              Generate UPDATE query
--db=db_name    Name of DB to use
-v              Verbose
-h, --help      This help
");
    exit(0);
  }

if (isset($options['db'])) {
  $db_name = $options['db'];
} else {
  $db_name = null;
}
get_PDO_lobbywatch_DB_connection($db_name);

print("-- $env: {$db_con['database']}\n");
print("-- Executing user=$user\n");

//   var_dump($options);

print("-- Tables: " . implode(', ', array_keys($workflow_tables)) . "\n");

// https://www.php.net/manual/de/migration70.new-features.php#migration70.new-features.unicode-codepoint-escape-syntax
// \u{} since PHP 7.0
// Bash: for (( i=$((0x2018)); i <= $((0x201B)); i++ )); do printf '\\u{%X}' $i; done && echo
// TODO remove ® from Namen

// split on "," or each char
$chars = [
  'normalize' => 'î',
  '>' => "›",
  // "\n" => "\r\n,\r",
  // 'normalize' => 'äöüéèêàçáìíîòóùú', # œ only 1 form
  '"' => "\u{201C}\u{201D}\u{201E}\u{201F}«»“”„",
  "'" => "\u{2018}\u{2019}\u{201A}\u{201B}`‘’‚‹›",
  "-" => "\u{2010}\u{2011}\u{2012}\u{2013}\u{2014}\u{202F}",
  " " => "\u{2000}\u{2001}\u{2002}\u{2003}\u{2004}\u{2005}\u{2006}\u{2007}\u{2008}\u{2009}\u{200A}",
  '' => "\u{2028}\u{2029}\u{200B}\u{2063}\r",
];

$update_char_sqls = [];
$select_char_sqls = [];

foreach ($chars as $key => $value) {
    foreach (strpos($value, ',') === false ? array_unique(mb_str_split($value)) : explode(',', $value) as $char_raw) {
        if ($char_raw === '') continue;
        for ($i = 0; $i < ($key == 'normalize' ? 2 : 1); $i++) {
            $char = $i == 0 ? $char_raw : mb_strtoupper($char_raw);
            if ($key == 'normalize') {
                $char_from = Normalizer::normalize($char, Normalizer::FORM_D);
                $char_to = $char;
            } else {
                $char_from = $char;
                $char_to = $key;
            }

            if (isset($options['v'])) {
                print('-- ' . clean_for_display($char_from) . ": UTF-8: " . get_hex($char_from) . ", " . urlencode($char_from) . ", Unicode: " . get_unicode_code_point($char_from) . ' → ' . clean_for_display($char_to) . ": UTF-8: " . get_hex($char_to) . ", " . urlencode($char_to) . ", Unicode: " . get_unicode_code_point($char_to) . "\n");
            }

            $to_replace_row = [
              'findUtf8Hex' => get_hex($char_from),
              'replaceUtf8Hex' => get_hex($char_to),
              'findUCP' => get_unicode_code_point($char_from),
              'replaceUCP' => get_unicode_code_point($char_to),
              'find' => $char_from,
              'replace' => $char_to,
              'findSQLDisplay' => escape_string(escape_string(clean_for_display($char_from))),
              'replaceSQLDisplay' => escape_string(escape_string(clean_for_display($char_to))),
              'findSQLComment' => escape_string(clean_for_display($char_from)),
              'replaceSQLComment' => escape_string(clean_for_display($char_to)),
            ];

            // MySQL Character Set Introducers
            // https://dev.mysql.com/doc/refman/5.7/en/charset-introducer.html
            // https://dev.mysql.com/doc/refman/5.7/en/charset-literal.html

            // TODO check/test escaping for update
            $update_char_sqls[] = "'UPDATE ', TABLE_NAME, ' SET ', COLUMN_NAME, '=REPLACE(', COLUMN_NAME, ', _utf8mb4 x''{$to_replace_row['findUtf8Hex']}'', _utf8mb4 x''{$to_replace_row['replaceUtf8Hex']}''), updated_visa=''roland'', updated_date=" . str_replace("'", "''", $sql_transaction_date) . ", notizen=CONCAT_WS(''\\\\n\\\\n'', ''$today/Roland: Normalize special characters in field \"', COLUMN_NAME, '\": [\"{$to_replace_row['findSQLDisplay']}\", {$to_replace_row['findUCP']}, utf8 0x{$to_replace_row['findUtf8Hex']} → \"{$to_replace_row['replaceSQLDisplay']}\", {$to_replace_row['replaceUCP']}, utf8 0x{$to_replace_row['replaceUtf8Hex']}]'',`notizen`)" . " WHERE ', COLUMN_NAME, ' LIKE BINARY CONCAT(''%'', _utf8mb4 x''{$to_replace_row['findUtf8Hex']}'', ''%''); -- \"{$to_replace_row['findSQLComment']}\" → \"{$to_replace_row['replaceSQLComment']}\", {$to_replace_row['findUCP']} → {$to_replace_row['replaceUCP']}', '\n'";

            $select_char_sqls[] = "'SELECT ''', TABLE_NAME, ''' AS TABLE_NAME, ID, ''', COLUMN_NAME, ''' AS COLUMN_NAME, ''{$to_replace_row['findSQLDisplay']}'' AS CHAR_FROM, ''{$to_replace_row['findUCP']}'' AS CHAR_FROM_UCP, ''{$to_replace_row['findUtf8Hex']}'' AS CHAR_FROM_UTF8, ''{$to_replace_row['replaceSQLDisplay']}'' AS CHAR_TO, ''{$to_replace_row['replaceUCP']}'' AS CHAR_TO_UCP, ''{$to_replace_row['replaceUtf8Hex']}'' AS CHAR_TO_UTF8, ', COLUMN_NAME, ' FROM ', TABLE_NAME, ' WHERE ', COLUMN_NAME, ' LIKE BINARY CONCAT(''%'', _utf8mb4 x''{$to_replace_row['findUtf8Hex']}'', ''%'') -- \"{$to_replace_row['findSQLComment']}\" → \"{$to_replace_row['replaceSQLComment']}\", {$to_replace_row['findUCP']} → {$to_replace_row['replaceUCP']}', '\n'";
        }
    }
}

$update_char_sqls[] = "'UPDATE ', TABLE_NAME, ' SET ', COLUMN_NAME, '=TRIM(', COLUMN_NAME, '), updated_visa=''roland'', updated_date=" . str_replace("'", "''", $sql_transaction_date) . ", notizen=CONCAT_WS(''\\\\n\\\\n'', ''$today/Roland: Trim ', COLUMN_NAME, ''',`notizen`) WHERE ', COLUMN_NAME, ' <> TRIM(', COLUMN_NAME, ');\n'";

$select_char_sqls[] = "'SELECT ''', TABLE_NAME, ''' AS TABLE_NAME, ID, ''', COLUMN_NAME, ''' AS COLUMN_NAME, NULL AS CHAR_FROM, NULL AS CHAR_FROM_UCP, NULL AS CHAR_FROM_UTF8, ''trim'' AS CHAR_TO, NULL AS CHAR_TO_UCP, NULL AS CHAR_TO_UTF8, ', COLUMN_NAME, ' FROM ', TABLE_NAME, ' WHERE ', COLUMN_NAME, ' <> TRIM(', COLUMN_NAME, ')\n'";

// It was a bad idea to generate the SQL in SQL. Welcome escaping hell!
// Just the meta data should be queried and the generation should be in PHP.

$sql = "
SELECT
    CONCAT('SELECT * FROM ', TABLE_NAME, ' WHERE ',TABLE_NAME,'.',COLUMN_NAME, ' LIKE BINARY CONCAT(''%'', _utf8mb4 x''CC88'', ''%'')', '\n') AS simple_select_stmt,
    CONCAT('      ', " . implode("\n, 'UNION ', ", $select_char_sqls) . ") AS select_stmt,
    CONCAT(" . implode("\n", $update_char_sqls) . ") AS update_stmt,
    CONCAT(TABLE_NAME,'.',COLUMN_NAME) AS col
FROM
    INFORMATION_SCHEMA.COLUMNS
WHERE
    TABLE_SCHEMA = '${db_connection['database']}' AND
    DATA_TYPE IN ('VARCHAR', 'TEXT', 'LONGTEXT', 'MEDIUMTEXT', 'TINYTEXT') AND
    TABLE_NAME IN ('" . implode("', '", array_keys($workflow_tables)) . "') AND
    EXTRA NOT LIKE '%GENERATED%' AND
    COLUMN_NAME NOT LIKE '%_visa' AND
    COLUMN_NAME NOT LIKE '%_svg' AND
    COLUMN_NAME NOT LIKE 'wappen_%' AND
    COLUMN_NAME NOT LIKE 'lagebild' AND
    COLUMN_NAME NOT LIKE 'photo_%' AND
    COLUMN_NAME NOT IN ('notizen', 'farbcode', 'parlament_committee_function_name', 'uid', 'rechtsform_handelsregister', 'kommissionen', 'photo', 'kleinbild', 'parlamentarier_kommissionen', 'zutrittsberechtigung_von')
    -- AND COLUMN_NAME NOT LIKE 'beschreibung%'
    -- AND TABLE_NAME = 'organisation'
    -- AND COLUMN_NAME LIKE 'name%'
    ;" ;

if (isset($options['v'])) {
    print("\n/*" . $sql . "*/\n");
}

$simple_union = [];
$select_union = [];
$update_stmts = [];
$cols = [];
foreach ($db->query($sql) as $row) {
    $union[] = $row['simple_select_stmt'];
    $update_stmts[] = $row['update_stmt'];
    $select_union[] = $row['select_stmt'];
    $cols[] = $row['col'];
}

if (isset($options['v'])) {
    print("\n-- COLS\n-- -----\n/*\n" . implode("\n", $cols)) . "\n*/\n";
}


// print("\n-- SIMPLE SELECT\n-- -----\n" . implode(" UNION \n", $union)) . "\n";
if (isset($options['s'])) {
    print("\n-- SELECT\n-- -----\n

" . implode("UNION \n", $select_union)) . ";\n";
}
if (isset($options['u'])) {
    print("\n-- UPDATE\n-- -----\n" . implode("\n", $update_stmts)) . ";\n";
}
