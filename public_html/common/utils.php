<?php

require_once dirname(__FILE__) . '/simplediff.php';

// https://developer.mozilla.org/en-US/docs/Tools/Web_Console/Console_messages#Server
// https://craig.is/writing/chrome-logger
// https://github.com/ccampbell/chromephp
// include_once dirname(__FILE__) . '/ChromePhp.php';
// ChromePhp::log('Hello console!');
// ChromePhp::log($_SERVER);
// ChromePhp::warn('something went wrong!');

// Call function from command line
// /opt/lampp/bin/php -r "require 'ws_uid_fetcher.php'; print(formatUID('CHE-101.079.31') . \"\n\");"

const FIELD_MODE_OVERWRITE = 0;
const FIELD_MODE_OVERWRITE_MARK = 1;
const FIELD_MODE_OPTIONAL = 2;
const FIELD_MODE_ONLY_NEW = 3;
const FIELD_MODE_OVERWRITE_MARK_LOG = 4;
const FIELD_MODE_OVERWRITE_NULL = 5;

global $today;
global $sql_today;
global $transaction_date;
global $sql_transaction_date;
global $mysql_client_version;
global $mysql_server_version;

$today = date('d.m.Y');
$sql_today = "STR_TO_DATE('$today','%d.%m.%Y')";
$transaction_date = date('d.m.Y H:i:s');
$sql_transaction_date = "STR_TO_DATE('$transaction_date','%d.%m.%Y %T')";

//Ref: http://stackoverflow.com/questions/834303/php-startswith-and-endswith-functions
function utils_startsWith($haystack, $needle, $case_sensitive = true) {
  return $needle === "" || is_string($haystack) && mb_strpos($case_sensitive ? $haystack : mb_strtolower($haystack), $case_sensitive ? $needle : mb_strtolower($needle)) === 0;
}
//Ref: http://stackoverflow.com/questions/834303/php-startswith-and-endswith-functions
function utils_endsWith($haystack, $needle, $case_sensitive = true) {
//   dpm("endsWith " . mb_substr($case_sensitive ? $haystack : mb_strtolower($haystack), -mb_strlen($needle)) . " =? " . ($case_sensitive ? $needle : mb_strtolower($needle)));
  return $needle === "" || is_string($haystack) && mb_substr($case_sensitive ? $haystack : mb_strtolower($haystack), -mb_strlen($needle)) === ($case_sensitive ? $needle : mb_strtolower($needle));
}

//Ref: http://stackoverflow.com/questions/834303/php-startswith-and-endswith-functions
function starts_with($haystack, $needle, $case_sensitive = true) {
  return utils_startsWith($haystack, $needle, $case_sensitive);
}
//Ref: http://stackoverflow.com/questions/834303/php-startswith-and-endswith-functions
function ends_with($haystack, $needle, $case_sensitive = true) {
  return utils_endsWith($haystack, $needle, $case_sensitive);
}

// Logging

function dw($msg, $text = null) {
  global $debug;
  if ($debug !== true)
    return;
  if (is_array($msg) || is_object($msg)) {
    $msg = print_r($msg, true);
  }
  print ("<p style='color:red;'>" . (is_string($text) ? $text . ' => ' : '') . "$msg</p>") ;
}
function dwXXX($msg) {
  // Disabled debug comment: do nothing
}

function dlog($msg) {
  global $debug;
  if ($debug !== true)
    return;
  if (is_array($msg) || is_object($msg)) {
    $msg = print_r($msg, true);
  }
  error_log($msg) ;
}
function dlogXXX($msg) {
  // Disabled debug comment: do nothing
}

function df_clean() {
  global $debug;
  if ($debug !== true)
    return;
  $msg = 'Start logging';
  file_put_contents(dirname(__FILE__) . "/../../logs/bearbeitung.log", date('c') . ': ' . $msg . "\n"/*, FILE_APPEND*/);
}

function df($msg, $text = null) {
  global $debug;
  if ($debug !== true)
    return;
  if (is_array($msg) || is_object($msg)) {
    $msg = print_r($msg, true);
  } else if ($msg === null) {
    $msg = 'null';
  } else if (is_string($msg)) {
    $msg = "'$msg'";
  } else if (is_bool($msg)) {
    $msg = $msg ? 'true' : 'false';
  }
  file_put_contents(dirname(__FILE__) . "/../../logs/bearbeitung.log", date('c') . ': ' . (is_string($text) ? $text . ' => ' : '') . $msg . "\n", FILE_APPEND);
}
function dfXXX($msg, $text = null) {
  // Disabled debug comment: do nothing
}

function dc($msg) {
  if ($debug !== true)
    return;
  if (is_array($msg)) {
    $msg = print_r($msg, true);
  }
  print ("<!-- $msg -->") ;
}
function dcXXX($msg) {
  // Disabled debug comment: do nothing
}

// https://stackify.com/how-to-log-to-console-in-php/
function dj($msg, $text = null, $with_script_tags = true) {
  global $debug;
  if ($debug !== true)
    return;
  if (is_array($msg) || is_object($msg)) {
    $msg = print_r($msg, true);
  } else if ($msg === null) {
    $msg = 'null';
  } else if (is_string($msg)) {
    $msg = "'$msg'";
  } else if (is_bool($msg)) {
    $msg = $msg ? 'true' : 'false';
  }
  $js_code = 'console.log(' . json_encode((is_string($text) ? $text . ' => ' : '') . $msg, JSON_HEX_TAG) .
');';
  if ($with_script_tags) {
    $js_code = '<script>' . $js_code . '</script>';
  }
  echo $js_code;
}
function djXXX($msg, $text = null, $with_script_tags = true) {
  // Disabled debug comment: do nothing
}


//http://stackoverflow.com/questions/2476876/how-do-i-convert-an-object-to-an-array

// http://stackoverflow.com/questions/11648396/php-search-for-a-value-in-an-array-which-contains-objects
function search_objects(&$objects, $key, $value) {
  $return = array();
  foreach ($objects as &$object) {
    $objVars = get_object_vars($object);
    if (isset($objVars[$key]) && $objVars[$key] == $value) {
      $return[] = $object;
    }
  }
  return $return;
}

function is_column_present($columns, $name) {
  foreach ($columns as $column) {
    if ($column->GetFieldName() == $name)
      return true;
  }

  return false;
}

// Ref: http://www.cafewebmaster.com/check-password-strength-safety-php-and-regex
function checkPasswordStrengthOneMessage($pwd) {
  if (preg_match("#.*^(?=.{11,1000})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $pwd)){
    return true;
  } else {
    // return false;
    throw new Exception("Password too weak!\n\nPassword must include numbers, special characters, lower and uppercase letters and be at least 10 characters long");
  }
}

// Ref: http://www.cafewebmaster.com/check-password-strength-safety-php-and-regex
function checkPasswordStrength($pwd) {
  $error = '';
  if( strlen($pwd) < 11) {
    $error .= "Password too short! Min 10\n";
  }

  if( strlen($pwd) > 1000 ) {
    $error .= "Password too long!\n";
  }

  if( !preg_match("#[0-9]+#", $pwd) ) {
    $error .= "Password must include at least one number!\n";
  }


  if( !preg_match("#[a-z]+#", $pwd) ) {
    $error .= "Password must include at least one letter!\n";
  }


  if( !preg_match("#[A-Z]+#", $pwd) ) {
    $error .= "Password must include at least one CAPS!\n";
  }

  if( !preg_match("#\W+#", $pwd) ) {
    $error .= "Password must include at least one symbol!\n";
  }

  if($error !== '') {
    throw new Exception("Password too weak:\n\n$error");
  } else {
    return true;
  }

}

/**
 * Provides central static variable storage.
 *
 * Copied from drupal_static().
 *
 * All functions requiring a static variable to persist or cache data within
 * a single page request are encouraged to use this function unless it is
 * absolutely certain that the static variable will not need to be reset during
 * the page request. By centralizing static variable storage through this
 * function, other functions can rely on a consistent API for resetting any
 * other function's static variables.
 *
 * Example:
 * @code
 * function language_list($field = 'language') {
 *   $languages = &drupal_static(__FUNCTION__);
 *   if (!isset($languages)) {
 *     // If this function is being called for the first time after a reset,
 *     // query the database and execute any other code needed to retrieve
 *     // information about the supported languages.
 *     ...
 *   }
 *   if (!isset($languages[$field])) {
 *     // If this function is being called for the first time for a particular
 *     // index field, then execute code needed to index the information already
 *     // available in $languages by the desired field.
 *     ...
 *   }
 *   // Subsequent invocations of this function for a particular index field
 *   // skip the above two code blocks and quickly return the already indexed
 *   // information.
 *   return $languages[$field];
 * }
 * function locale_translate_overview_screen() {
 *   // When building the content for the translations overview page, make
 *   // sure to get completely fresh information about the supported languages.
 *   drupal_static_reset('language_list');
 *   ...
 * }
 * @endcode
 *
 * In a few cases, a function can have certainty that there is no legitimate
 * use-case for resetting that function's static variable. This is rare,
 * because when writing a function, it's hard to forecast all the situations in
 * which it will be used. A guideline is that if a function's static variable
 * does not depend on any information outside of the function that might change
 * during a single page request, then it's ok to use the "static" keyword
 * instead of the drupal_static() function.
 *
 * Example:
 * @code
 * function actions_do(...) {
 *   // $stack tracks the number of recursive calls.
 *   static $stack;
 *   $stack++;
 *   if ($stack > variable_get('actions_max_stack', 35)) {
 *     ...
 *     return;
 *   }
 *   ...
 *   $stack--;
 * }
 * @endcode
 *
 * In a few cases, a function needs a resettable static variable, but the
 * function is called many times (100+) during a single page request, so
 * every microsecond of execution time that can be removed from the function
 * counts. These functions can use a more cumbersome, but faster variant of
 * calling drupal_static(). It works by storing the reference returned by
 * drupal_static() in the calling function's own static variable, thereby
 * removing the need to call drupal_static() for each iteration of the function.
 * Conceptually, it replaces:
 * @code
 * $foo = &drupal_static(__FUNCTION__);
 * @endcode
 * with:
 * @code
 * // Unfortunately, this does not work.
 * static $foo = &drupal_static(__FUNCTION__);
 * @endcode
 * However, the above line of code does not work, because PHP only allows static
 * variables to be initializied by literal values, and does not allow static
 * variables to be assigned to references.
 * - http://php.net/manual/language.variables.scope.php#language.variables.scope.static
 * - http://php.net/manual/language.variables.scope.php#language.variables.scope.references
 * The example below shows the syntax needed to work around both limitations.
 * For benchmarks and more information, see http://drupal.org/node/619666.
 *
 * Example:
 * @code
 * function user_access($string, $account = NULL) {
 *   // Use the advanced drupal_static() pattern, since this is called very often.
 *   static $drupal_static_fast;
 *   if (!isset($drupal_static_fast)) {
 *     $drupal_static_fast['perm'] = &drupal_static(__FUNCTION__);
 *   }
 *   $perm = &$drupal_static_fast['perm'];
 *   ...
 * }
 * @endcode
 *
 * @param $name
 *   Globally unique name for the variable. For a function with only one static,
 *   variable, the function name (e.g. via the PHP magic __FUNCTION__ constant)
 *   is recommended. For a function with multiple static variables add a
 *   distinguishing suffix to the function name for each one.
 * @param $default_value
 *   Optional default value.
 * @param $reset
 *   TRUE to reset a specific named variable, or all variables if $name is NULL.
 *   Resetting every variable should only be used, for example, for running
 *   unit tests with a clean environment. Should be used only though via
 *   function drupal_static_reset() and the return value should not be used in
 *   this case.
 *
 * @return
 *   Returns a variable by reference.
 *
 * @see drupal_static_reset()
 */
function &php_static_cache($name, $default_value = NULL, $reset = FALSE) {
  static $data = array(), $default = array();
  // First check if dealing with a previously defined static variable.
  if (isset($data[$name]) || array_key_exists($name, $data)) {
    // Non-NULL $name and both $data[$name] and $default[$name] statics exist.
    if ($reset) {
      // Reset pre-existing static variable to its default value.
      $data[$name] = $default[$name];
    }
    return $data[$name];
  }
  // Neither $data[$name] nor $default[$name] static variables exist.
  if (isset($name)) {
    if ($reset) {
      // Reset was called before a default is set and yet a variable must be
      // returned.
      return $data;
    }
    // First call with new non-NULL $name. Initialize a new static variable.
    $default[$name] = $data[$name] = $default_value;
    return $data[$name];
  }
  // Reset all: ($name == NULL). This needs to be done one at a time so that
  // references returned by earlier invocations of drupal_static() also get
  // reset.
  foreach ($default as $name => $value) {
    $data[$name] = $value;
  }
  // As the function returns a reference, the return should always be a
  // variable.
  return $data;
}

function get_PDO_lobbywatch_DB_connection($db_name = null, $user_prefix = 'reader_') {
  global $db_connection;
  global $db_connections;
  global $db_con;
  global $db;
  global $mysql_client_version;
  global $mysql_server_version;
  if (empty($db)) {
    if ($db_name != null && $db_name != 'DEFAULT') {
      if (empty($db_connections[$db_name])) {
        print("ERROR: DB connection '$db_name' not in settings.php");
        exit(1);
      }
      $db_con = $db_connections[$db_name];
    } else {
      $db_con = $db_connection;
    }
    $db = new PDO("mysql:host={$db_con['server']};port={$db_con['port']};dbname={$db_con['database']};charset=utf8mb4", $db_con["{$user_prefix}username"], $db_con["${user_prefix}password"], array(PDO::ATTR_PERSISTENT => true));
    // Disable prepared statement emulation, http://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysql_client_version = $db->getAttribute(PDO::ATTR_CLIENT_VERSION);
    $stmt = $db->query("SELECT VERSION();");
    $mysql_server_version = $stmt->fetch()[0];
    $stmt->closeCursor();
  }
  return $db;
}

/**
 * Encodes special characters in a plain-text string for display as HTML.
 *
 * Also validates strings as UTF-8 to prevent cross site scripting attacks on
 * Internet Explorer 6.
 *
 * @param $text
 *   The text to be checked or processed.
 *
 * @return
 *   An HTML safe version of $text, or an empty string if $text is not
 *   valid UTF-8.
 *
 * @see drupal_validate_utf8()
 * @ingroup sanitization
 */
function common_check_plain($text) {
  return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Return data uri.
 * Files can be replaced by data uris.
 *
 * Ref http://stackoverflow.com/questions/2329364/how-to-embed-images-in-a-single-html-php-file
 */
function util_data_uri($file, $mime = '') {
  $relative = preg_replace('/\/?(.*)/u', '$1', $file);
  $contents = file_get_contents($relative);
  $base64   = base64_encode($contents);
  if (!$mime) {
    $mime = 'image/' . substr($file, -3);
  }
  return 'data:' . $mime . ';base64,' . $base64;
}

function _lobbywatch_bindungsart($pers, $ib, $org) {
  $interessenbindungArtList = array('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter');
  $art = " CASE ";
  foreach($interessenbindungArtList as $interessenbindungArt) {
    $art .= "  WHEN $ib.art = '$interessenbindungArt' THEN " . lts("$interessenbindungArt") . "\n";
  }
  $art .= "  ELSE CONCAT(UCASE(LEFT($ib.art, 1)), SUBSTRING($ib.art, 2))
  END ";

  $funktion_im_gremium = "CASE
  WHEN $ib.funktion_im_gremium = 'praesident' AND $pers.geschlecht = 'F' THEN " . lts('Präsidentin') . "
  WHEN $ib.funktion_im_gremium = 'praesident' AND $pers.geschlecht = 'M' THEN " . lts('Präsident') . "
  WHEN $ib.funktion_im_gremium = 'vizepraesident' AND $pers.geschlecht = 'F' THEN " . lts('Vizepräsidentin') . "
  WHEN $ib.funktion_im_gremium = 'vizepraesident' AND $pers.geschlecht = 'M' THEN " . lts('Vizepräsident') . "
  WHEN $ib.funktion_im_gremium = 'mitglied' THEN " . lts('Mitglied') . "
  ELSE $ib.funktion_im_gremium
  END";

  return "CASE
  /* Stiftung */
    WHEN $org.rechtsform = 'Stiftung' AND $ib.art = 'vorstand' AND $ib.funktion_im_gremium = 'praesident' AND $pers.geschlecht = 'F' THEN " . lts('Stiftungsratspräsidentin') . "
    WHEN $org.rechtsform = 'Stiftung' AND $ib.art = 'vorstand' AND $ib.funktion_im_gremium = 'praesident' AND $pers.geschlecht = 'M' THEN " . lts('Stiftungsratspräsident') . "
    WHEN $org.rechtsform = 'Stiftung' AND $ib.art = 'vorstand' AND $ib.funktion_im_gremium = 'vizepraesident' AND $pers.geschlecht = 'F' THEN " . lts('Stiftungsratsvizepräsidentin') . "
    WHEN $org.rechtsform = 'Stiftung' AND $ib.art = 'vorstand' AND $ib.funktion_im_gremium = 'vizepraesident' AND $pers.geschlecht = 'M' THEN " . lts('Stiftungsratsvizepräsident') . "
    WHEN $org.rechtsform = 'Stiftung' AND $ib.art = 'vorstand' AND $pers.geschlecht = 'F' THEN " . lts('Stiftungsrätin') . "
    WHEN $org.rechtsform = 'Stiftung' AND $ib.art = 'vorstand' AND $pers.geschlecht = 'M' THEN " . lts('Stiftungsrat') . "
    WHEN $org.rechtsform = 'Stiftung' AND $ib.art = 'geschaeftsfuehrend' AND $ib.funktion_im_gremium = 'praesident' AND $pers.geschlecht = 'F' THEN " . lts('Geschäftsführerin') . "
    WHEN $org.rechtsform = 'Stiftung' AND $ib.art = 'geschaeftsfuehrend' AND $ib.funktion_im_gremium = 'praesident' AND $pers.geschlecht = 'M' THEN " . lts('Geschäftsführer') . "
    WHEN $org.rechtsform = 'Stiftung' AND $ib.art = 'geschaeftsfuehrend' AND $ib.funktion_im_gremium = 'vizepraesident' AND $pers.geschlecht = 'F' THEN " . lts('Vizegeschäftsführerin') . "
    WHEN $org.rechtsform = 'Stiftung' AND $ib.art = 'geschaeftsfuehrend' AND $ib.funktion_im_gremium = 'vizepraesident' AND $pers.geschlecht = 'M' THEN " . lts('Vizegeschäftsführer') . "
    WHEN $org.rechtsform = 'Stiftung' AND $ib.art = 'geschaeftsfuehrend' AND $pers.geschlecht = 'F' THEN " . lts('Geschäftsleitung') . "
    WHEN $org.rechtsform = 'Stiftung' AND $ib.art = 'geschaeftsfuehrend' AND $pers.geschlecht = 'M' THEN " . lts('Geschäftsleitung') . "
  /* AG */
    WHEN $org.rechtsform IN ('AG', 'Genossenschaft') AND $ib.art = 'vorstand' AND $ib.funktion_im_gremium = 'praesident' AND $pers.geschlecht = 'F' THEN " . lts('Verwaltungsratspräsidentin') . "
    WHEN $org.rechtsform IN ('AG', 'Genossenschaft') AND $ib.art = 'vorstand' AND $ib.funktion_im_gremium = 'praesident' AND $pers.geschlecht = 'M' THEN " . lts('Verwaltungsratspräsident') . "
    WHEN $org.rechtsform IN ('AG', 'Genossenschaft') AND $ib.art = 'vorstand' AND $ib.funktion_im_gremium = 'vizepraesident' AND $pers.geschlecht = 'F' THEN " . lts('Verwaltungsratsvizepräsidentin') . "
    WHEN $org.rechtsform IN ('AG', 'Genossenschaft') AND $ib.art = 'vorstand' AND $ib.funktion_im_gremium = 'vizepraesident' AND $pers.geschlecht = 'M' THEN " . lts('Verwaltungsratsvizepräsident') . "
    WHEN $org.rechtsform IN ('AG', 'Genossenschaft') AND $ib.art = 'vorstand' AND $pers.geschlecht = 'F' THEN " . lts('Verwaltungsrätin') . "
    WHEN $org.rechtsform IN ('AG', 'Genossenschaft') AND $ib.art = 'vorstand' AND $pers.geschlecht = 'M' THEN " . lts('Verwaltungsrat') . "
    WHEN $org.rechtsform IN ('AG', 'Genossenschaft') AND $ib.art = 'geschaeftsfuehrend' AND $ib.funktion_im_gremium = 'praesident' AND $pers.geschlecht = 'F' THEN " . lts('Geschäftsführerin (CEO)') . "
    WHEN $org.rechtsform IN ('AG', 'Genossenschaft') AND $ib.art = 'geschaeftsfuehrend' AND $ib.funktion_im_gremium = 'praesident' AND $pers.geschlecht = 'M' THEN " . lts('Geschäftsführer (CEO)') . "
    WHEN $org.rechtsform IN ('AG', 'Genossenschaft') AND $ib.art = 'geschaeftsfuehrend' AND $ib.funktion_im_gremium = 'vizepraesident' AND $pers.geschlecht = 'F' THEN " . lts('Vizegeschäftsführerin') . "
    WHEN $org.rechtsform IN ('AG', 'Genossenschaft') AND $ib.art = 'geschaeftsfuehrend' AND $ib.funktion_im_gremium = 'vizepraesident' AND $pers.geschlecht = 'M' THEN " . lts('Vizegeschäftsführer') . "
    WHEN $org.rechtsform IN ('AG', 'Genossenschaft') AND $ib.art = 'geschaeftsfuehrend' AND $pers.geschlecht = 'F' THEN " . lts('Geschäftsleitung') . "
    WHEN $org.rechtsform IN ('AG', 'Genossenschaft') AND $ib.art = 'geschaeftsfuehrend' AND $pers.geschlecht = 'M' THEN " . lts('Geschäftsleitung') . "
  /* Verein */
    WHEN $org.rechtsform = 'Verein' AND $ib.art = 'vorstand' AND $ib.funktion_im_gremium = 'praesident' AND $pers.geschlecht = 'F' THEN " . lts('Präsidentin') . "
    WHEN $org.rechtsform = 'Verein' AND $ib.art = 'vorstand' AND $ib.funktion_im_gremium = 'praesident' AND $pers.geschlecht = 'M' THEN " . lts('Präsident') . "
    WHEN $org.rechtsform = 'Verein' AND $ib.art = 'vorstand' AND $ib.funktion_im_gremium = 'vizepraesident' AND $pers.geschlecht = 'F' THEN " . lts('Vizepräsidentin') . "
    WHEN $org.rechtsform = 'Verein' AND $ib.art = 'vorstand' AND $ib.funktion_im_gremium = 'vizepraesident' AND $pers.geschlecht = 'M' THEN " . lts('Vizepräsident') . "
    WHEN $org.rechtsform = 'Verein' AND $ib.art = 'vorstand' AND $pers.geschlecht = 'F' THEN " . lts('Vorstand') . "
    WHEN $org.rechtsform = 'Verein' AND $ib.art = 'vorstand' AND $pers.geschlecht = 'M' THEN " . lts('Vorstand') . "
    WHEN $org.rechtsform = 'Verein' AND $ib.art = 'geschaeftsfuehrend' AND $ib.funktion_im_gremium = 'praesident' AND $pers.geschlecht = 'F' THEN " . lts('Geschäftsführerin') . "
    WHEN $org.rechtsform = 'Verein' AND $ib.art = 'geschaeftsfuehrend' AND $ib.funktion_im_gremium = 'praesident' AND $pers.geschlecht = 'M' THEN " . lts('Geschäftsführer') . "
    WHEN $org.rechtsform = 'Verein' AND $ib.art = 'geschaeftsfuehrend' AND $ib.funktion_im_gremium = 'vizepraesident' AND $pers.geschlecht = 'F' THEN " . lts('Vizegeschäftsführerin') . "
    WHEN $org.rechtsform = 'Verein' AND $ib.art = 'geschaeftsfuehrend' AND $ib.funktion_im_gremium = 'vizepraesident' AND $pers.geschlecht = 'M' THEN " . lts('Vizegeschäftsführer') . "
    WHEN $org.rechtsform = 'Verein' AND $ib.art = 'geschaeftsfuehrend' AND $pers.geschlecht = 'F' THEN " . lts('Geschäftsleitung') . "
    WHEN $org.rechtsform = 'Verein' AND $ib.art = 'geschaeftsfuehrend' AND $pers.geschlecht = 'M' THEN " . lts('Geschäftsleitung') . "
    /* Beirat/Patronatskomitee/Expertenkommission/Advisory Board */
    WHEN $ib.art = 'beirat' THEN CONCAT(" . lts('Beirat/Patronatskomitee/Expertenkommission/Advisory Board') . ",
    IF($ib.funktion_im_gremium IS NULL OR TRIM($ib.funktion_im_gremium) IN ('', 'mitglied'), '', CONCAT(', ',CONCAT(UCASE(LEFT($funktion_im_gremium, 1)), SUBSTRING($funktion_im_gremium, 2)))))
  /* Else */
    ELSE CONCAT(CONCAT(UCASE(LEFT($art, 1)), SUBSTRING($art, 2)),
    IF($ib.funktion_im_gremium IS NULL OR TRIM($ib.funktion_im_gremium) IN ('', 'mitglied'), '', CONCAT(', ',CONCAT(UCASE(LEFT($funktion_im_gremium, 1)), SUBSTRING($funktion_im_gremium, 2)))))
    END";
  // TODO add lts() to funktion_im_gremium
          //   return "CONCAT(UCASE(LEFT(interessenbindung.art, 1)), SUBSTRING(interessenbindung.art, 2)),
//       IF(interessenbindung.funktion_im_gremium IS NULL OR TRIM(interessenbindung.funktion_im_gremium) IN ('', 'mitglied'), '', CONCAT(', ',CONCAT(UCASE(LEFT(interessenbindung.funktion_im_gremium, 1)), SUBSTRING(interessenbindung.funktion_im_gremium, 2))))";
}

function _lobbywatch_get_rechtsform_translation_SQL($org) {
  $rechtsformList = array('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft');
  $sql = " CASE ";
  foreach($rechtsformList as $rechtsform) {
    $sql .= "  WHEN $org.rechtsform = '$rechtsform' THEN " . lts("$rechtsform") . "\n";
  }
  $sql .= "  ELSE CONCAT(UCASE(LEFT($org.rechtsform, 1)), SUBSTRING($org.rechtsform, 2))
  END ";
  return $sql;
}

function _lobbywatch_add_expression_for_freigabe($expression, $table_relation, $else_expression = '', $target_table = null) {
  return " IF($table_relation.freigabe_datum IS NULL OR $table_relation.freigabe_datum > NOW()" . ($target_table ? " OR $target_table.freigabe_datum IS NULL OR $target_table.freigabe_datum > NOW()" : '') . ", $expression, $else_expression) ";
}

function _lobbywatch_add_admin_class_for_freigabe($table_relation, $target_table = null) {
  return _lobbywatch_add_expression_for_freigabe("' class=\"unpublished\"'", $table_relation, "''", $target_table);
}

function _lobbywatch_add_admin_class_value_for_freigabe($table_relation, $target_table = null) {
  return "IF($table_relation.freigabe_datum IS NULL OR $table_relation.freigabe_datum > NOW()" . ($target_table ? " OR $target_table.freigabe_datum IS NULL OR $target_table.freigabe_datum > NOW()" : '') . ", ' unpublished', '')"; //i18n
}

function _lobbywatch_organisation_beziehung_SELECT_SQL($alias_suffix_base, $transitiv_num, $linkOrganisation = true) {
  $lang = get_lang();
  $lang_suffix = get_lang_suffix();
  $admin = function_exists('user_access') && user_access('access lobbywatch admin');
  $adminBool = $admin ? "1" : "0";
  $sql = "";

  for ($i = 0; $i <= $transitiv_num; $i++) {
    $alias_suffix = "${alias_suffix_base}_$i";
    $sql .= "
      GROUP_CONCAT(DISTINCT
      CONCAT('<li'," ._lobbywatch_add_admin_class_for_freigabe("organisation_$alias_suffix") . ", '>', IF(organisation_beziehung_$alias_suffix.bis < NOW(), '<s>', ''), " . ($linkOrganisation ? "'<a href=\"/$lang/daten/organisation/', organisation_$alias_suffix.id, '\">', " : '') . lobbywatch_lang_field("organisation_$alias_suffix.anzeige_name_de") . ", " . ($linkOrganisation ? "'</a>', " : '') . "
      IF(organisation_$alias_suffix.rechtsform IS NULL OR TRIM(organisation_$alias_suffix.rechtsform) = '', '', CONCAT(', ', ". _lobbywatch_get_rechtsform_translation_SQL("organisation_$alias_suffix"). ")),
      IF(organisation_$alias_suffix.ort IS NULL OR TRIM(organisation_$alias_suffix.ort) = '', '', CONCAT(', ', organisation_$alias_suffix.ort)),
      IF(organisation_beziehung_$alias_suffix.bis < NOW(), CONCAT(', '," . lts('bis') . ", ' ', DATE_FORMAT(organisation_beziehung_$alias_suffix.bis, '%Y'), '</s>'), '')
      )
      ORDER BY organisation_$alias_suffix.anzeige_name
      SEPARATOR ' '
      ) $alias_suffix
      " . ($i != $transitiv_num ? ',' : '');
}
return $sql;
}

function _lobbywatch_organisation_beziehung_FROM_SQL($master_organisation_name, $art, $directed, $transitiv_num, $name_suffix, $name_reverse_suffix, $check_unpublished = true) {
  $sql = "";
  $cur_organisation_id = "$master_organisation_name.id";
  $cur_organisation_id_reverse = "$master_organisation_name.id";
  for ($i = 0; $i <= $transitiv_num; $i++) {
    $tech_name = "${name_suffix}_$i";
    $tech_name_reverse = "${name_reverse_suffix}_$i";
    $sql .= "
    LEFT JOIN v_organisation_beziehung organisation_beziehung_$tech_name
    ON organisation_beziehung_$tech_name.organisation_id = $cur_organisation_id
    AND organisation_beziehung_$tech_name.art = '$art'\n"
    . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? " AND organisation_beziehung_$tech_name.freigabe_datum <= NOW() " : '')
    . ($check_unpublished && !user_access('access lobbywatch advanced content') ? " AND (organisation_beziehung_$tech_name.bis IS NULL OR organisation_beziehung_$tech_name.bis > NOW())" : '')
    . ($directed ?
        "  LEFT JOIN v_organisation organisation_$tech_name \n"
        . " ON organisation_beziehung_$tech_name.ziel_organisation_id = organisation_$tech_name.id\n"
        . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? " AND organisation_$tech_name.freigabe_datum <= NOW() " : ''). "\n"
        : '') .
        "LEFT JOIN v_organisation_beziehung organisation_beziehung_$tech_name_reverse
        -- Reverse here: use ziel_organisation_id
        ON organisation_beziehung_$tech_name_reverse.ziel_organisation_id = $cur_organisation_id_reverse
        AND organisation_beziehung_$tech_name_reverse.art = '$art'\n"
        . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? " AND organisation_beziehung_$tech_name_reverse.freigabe_datum <= NOW() " : '')
        . ($check_unpublished && !user_access('access lobbywatch advanced content') ? " AND (organisation_beziehung_$tech_name_reverse.bis IS NULL OR organisation_beziehung_$tech_name_reverse.bis > NOW())" : '')
        . ($directed ?
            "  LEFT JOIN v_organisation organisation_$tech_name_reverse
            ON organisation_beziehung_$tech_name_reverse.organisation_id = organisation_$tech_name_reverse.id\n"
            . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? " AND organisation_$tech_name_reverse.freigabe_datum <= NOW() " : '')
            : "  LEFT JOIN v_organisation organisation_$tech_name
            ON (organisation_beziehung_$tech_name_reverse.organisation_id = organisation_$tech_name.id
            OR organisation_beziehung_$tech_name.ziel_organisation_id = organisation_$tech_name.id)\n"
            . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? " AND organisation_$tech_name.freigabe_datum <= NOW() " : ''));
    $cur_organisation_id = "organisation_beziehung_$tech_name.ziel_organisation_id";
    $cur_organisation_id_reverse = "organisation_beziehung_$tech_name_reverse.organisation_id";
}
return $sql;
}

/**
 * Get value of field depending on language, fallback to de if not in translated language available.
 *
 * @param array $record the array where the localized fields are available
 * @param string $basefield_name the field name of the German field, either name or name_de
 * @param string $hide_german true if instead of the german text a toggle message should be shown
 * @param string $try_lt_if_empty try to translate with empty if $locale_field_name is empty, eg. if beruf_fr is empty, try to translate, e.g Jurist to Advocat
 * @param string $langcode lang ISO code
 * @return either localized field content
 */
function translate_record_field($record, $basefield_name, $hide_german = false, $try_lt_if_empty = false, $langcode = null) {
  global $language;

  // Merge in default.
  if (!isset($langcode)) {
    $langcode = isset($language->language) ? $language->language : 'de';
  }

  $locale_field_name = preg_replace('/_de$/u', '', $basefield_name) . "_$langcode";

  if ($langcode == 'de') {
    return $record[$basefield_name];
  } else {
    if ($hide_german) {
      $replacement_text = textOnlyOneLanguage($record[$basefield_name], 'de');
    }
    // if translation is missing, fallback to default ('de')
    return !empty($record[$locale_field_name]) ? $record[$locale_field_name] : ($hide_german && isset($record[$basefield_name]) ? $replacement_text : ($try_lt_if_empty && isset($record[$basefield_name]) ? lt($record[$basefield_name]) : $record[$basefield_name]));
  }
}

function textOnlyOneLanguage($text, $lang_text = null) {
      $rnd = rand();
      $replacement_text = lt("Text nur auf französisch vorhanden.")
      . " <a id='only-german-show-$rnd' href='javascript:void(0)'>"
          . lt("Anzeigen")
          . "</a>"
      . " <a id='only-german-hide-$rnd' href='javascript:void(0)' style='display:none'>"
          . lt("Verbergen")
          . "</a>"
              . "<div id='german-text-$rnd' style='display:none'" . ($lang_text ? "lang='$lang_text'": '') . ">" . $text . "</div>"
      .'<script>
jQuery(document).ready(function() {
      jQuery("#only-german-show-'. $rnd . ', #only-german-hide-'. $rnd . '").click(function () {
      jQuery("#german-text-'. $rnd . '").slideToggle("slow");
      jQuery("#only-german-show-'. $rnd . '").toggle();
      jQuery("#only-german-hide-'. $rnd . '").toggle();
    });
 });
</script>';
  return $replacement_text;
}

/**
 * Set field name for language dependent field, e.g. name_de or name_fr.
 *
 * @param string $field the field name of the German field, either name or name_de
 * @param string $langcode lang ISO code
 * @return string field name for SQL with fallback
 */
function lobbywatch_lang_field($field, $langcode = null) {
  global $language;

  // Merge in default.
  if (!isset($langcode)) {
    $langcode = isset($language->language) ? $language->language : 'de';
  }

  if ($langcode == 'de') {
    return $field;
  } else {
    // if translation is missing, fallback to default ('de')
    return 'IFNULL(' . preg_replace('/_de$/u', '', $field) . "_$langcode , $field)";
  }
}

/**
 * Translates a string to the current language or to a given language.
 *
 * Copied from Drupal t().
 *
 * The t() function serves two purposes. First, at run-time it translates
 * user-visible text into the appropriate language. Second, various mechanisms
 * that figure out what text needs to be translated work off t() -- the text
 * inside t() calls is added to the database of strings to be translated.
 * These strings are expected to be in English, so the first argument should
 * always be in English. To enable a fully-translatable site, it is important
 * that all human-readable text that will be displayed on the site or sent to
 * a user is passed through the t() function, or a related function. See the
 * @link http://drupal.org/node/322729 Localization API @endlink pages for
 * more information, including recommendations on how to break up or not
 * break up strings for translation.
 *
 * @section sec_translating_vars Translating Variables
 * You should never use t() to translate variables, such as calling
 * @code t($text); @endcode, unless the text that the variable holds has been
 * passed through t() elsewhere (e.g., $text is one of several translated
 * literal strings in an array). It is especially important never to call
 * @code t($user_text); @endcode, where $user_text is some text that a user
 * entered - doing that can lead to cross-site scripting and other security
 * problems. However, you can use variable substitution in your string, to put
 * variable text such as user names or link URLs into translated text. Variable
 * substitution looks like this:
 * @code
 * $text = t("@name's blog", array('@name' => format_username($account)));
 * @endcode
 * Basically, you can put variables like @name into your string, and t() will
 * substitute their sanitized values at translation time. (See the
 * Localization API pages referenced above and the documentation of
 * format_string() for details about how to define variables in your string.)
 * Translators can then rearrange the string as necessary for the language
 * (e.g., in Spanish, it might be "blog de @name").
 *
 * @section sec_alt_funcs_install Use During Installation Phase
 * During the Drupal installation phase, some resources used by t() wil not be
 * available to code that needs localization. See st() and get_t() for
 * alternatives.
 *
 * @param $string
 *   A string containing the English string to translate.
 * @param $args
 *   An associative array of replacements to make after translation. Based
 *   on the first character of the key, the value is escaped and/or themed.
 *   See format_string() for details.
 * @param $options
 *   An associative array of additional options, with the following elements:
 *   - 'langcode' (defaults to the current language): The language code to
 *     translate to a language other than what is used to display the page.
 *   - 'context' (defaults to the empty context): The context the source string
 *     belongs to.
 *
 * @return
 *   The translated string.
 *
 * @see st()
 * @see get_t()
 * @see format_string()
 * @ingroup sanitization
 */
function lt($string, array $args = array(), array $options = array()) {
  global $language;
  static $custom_strings;

  // Merge in default.
  if (empty($options['langcode'])) {
    $options['langcode'] = isset($language->language) ? $language->language : 'de';
  }
  if (empty($options['context'])) {
    $options['context'] = '';
  }

//   // First, check for an array of customized strings. If present, use the array
//   // *instead of* database lookups. This is a high performance way to provide a
//   // handful of string replacements. See settings.php for examples.
//   // Cache the $custom_strings variable to improve performance.
//   if (!isset($custom_strings[$options['langcode']])) {
//     $custom_strings[$options['langcode']] = variable_get('locale_custom_strings_' . $options['langcode'], array());
//   }
//   // Custom strings work for English too, even if locale module is disabled.
//   if (isset($custom_strings[$options['langcode']][$options['context']][$string])) {
//     $string = $custom_strings[$options['langcode']][$options['context']][$string];
//   }
//   // Translate with locale module if enabled.
//   elseif ($options['langcode'] != 'en' && function_exists('locale')) {
//     $string = locale($string, $options['context'], $options['langcode']);
//   }
  $string = lobbywatch_translate($string, $options['context'], $options['langcode']);
  if (empty($args)) {
    return $string;
  }
  else {
    return lobbywatch_format_string($string, $args);
  }
}

/**
 * Calls lt and encloses with single quotes. This is useful for SQL.
 *
 * See lt() function.
 *
 * @param unknown $string
 * @param array $args
 * @param array $options
 * @return string
 */
function lts($string, array $args = array(), array $options = array()) {
  return "'" . str_replace("'", "\'", lt($string, $args, $options)) . "'";
}

/**
 * Formats a string for HTML display by replacing variable placeholders.
 *
 * Copied from format_string()
 *
 * This function replaces variable placeholders in a string with the requested
 * values and escapes the values so they can be safely displayed as HTML. It
 * should be used on any unknown text that is intended to be printed to an HTML
 * page (especially text that may have come from untrusted users, since in that
 * case it prevents cross-site scripting and other security problems).
 *
 * In most cases, you should use t() rather than calling this function
 * directly, since it will translate the text (on non-English-only sites) in
 * addition to formatting it.
 *
 * @param $string
 *   A string containing placeholders.
 * @param $args
 *   An associative array of replacements to make. Occurrences in $string of
 *   any key in $args are replaced with the corresponding value, after optional
 *   sanitization and formatting. The type of sanitization and formatting
 *   depends on the first character of the key:
 *   - @variable: Escaped to HTML using check_plain(). Use this as the default
 *     choice for anything displayed on a page on the site.
 *   - %variable: Escaped to HTML and formatted using drupal_placeholder(),
 *     which makes it display as <em>emphasized</em> text.
 *   - !variable: Inserted as is, with no sanitization or formatting. Only use
 *     this for text that has already been prepared for HTML display (for
 *     example, user-supplied text that has already been run through
 *     check_plain() previously, or is expected to contain some limited HTML
 *     tags and has already been run through filter_xss() previously).
 *
 * @see t()
 * @ingroup sanitization
 */
function lobbywatch_format_string($string, array $args = array()) {
  // Transform arguments before inserting them.
  foreach ($args as $key => $value) {
    switch ($key[0]) {
      case '@':
        // Escaped only.
        $args[$key] = check_plain($value);
        break;

      case '%':
      default:
        // Escaped and placeholder.
        $args[$key] = lobbywatch_placeholder($value);
        break;

      case '!':
        // Pass-through.
    }
  }
  return strtr($string, $args);
}

/**
 * Formats text for emphasized display in a placeholder inside a sentence.
 *
 * Copied from drupal_placeholder()
 *
 * Used automatically by format_string().
 *
 * @param $text
 *   The text to format (plain-text).
 *
 * @return
 *   The formatted text (html).
 */
function lobbywatch_placeholder($text) {
  return '<em class="placeholder">' . check_plain($text) . '</em>';
}

/**
 * Formats a string containing a count of items.
 *
 * Copied from format_plural().
 *
 * This function ensures that the string is pluralized correctly. Since t() is
 * called by this function, make sure not to pass already-localized strings to
 * it.
 *
 * For example:
 * @code
 *   $output = format_plural($node->comment_count, '1 comment', '@count comments');
 * @endcode
 *
 * Example with additional replacements:
 * @code
 *   $output = format_plural($update_count,
 *     'Changed the content type of 1 post from %old-type to %new-type.',
 *     'Changed the content type of @count posts from %old-type to %new-type.',
 *     array('%old-type' => $info->old_type, '%new-type' => $info->new_type));
 * @endcode
 *
 * @param $count
 *   The item count to display.
 * @param $singular
 *   The string for the singular case. Make sure it is clear this is singular,
 *   to ease translation (e.g. use "1 new comment" instead of "1 new"). Do not
 *   use @count in the singular string.
 * @param $plural
 *   The string for the plural case. Make sure it is clear this is plural, to
 *   ease translation. Use @count in place of the item count, as in
 *   "@count new comments".
 * @param $args
 *   An associative array of replacements to make after translation. Instances
 *   of any key in this array are replaced with the corresponding value.
 *   Based on the first character of the key, the value is escaped and/or
 *   themed. See format_string(). Note that you do not need to include @count
 *   in this array; this replacement is done automatically for the plural case.
 * @param $options
 *   An associative array of additional options. See t() for allowed keys.
 *
 * @return
 *   A translated string.
 *
 * @see t()
 * @see format_string()
 */
function lobbywatch_format_plural($count, $singular, $plural, array $args = array(), array $options = array()) {
  $args['@count'] = $count;
  if ($count == 1) {
    return lt($singular, $args, $options);
  }

  // Get the plural index through the gettext formula.
  $index = (function_exists('lobbywatch_translation_get_plural')) ? lobbywatch_translation_get_plural($count, isset($options['langcode']) ? $options['langcode'] : NULL) : -1;
  // If the index cannot be computed, use the plural as a fallback (which
  // allows for most flexiblity with the replaceable @count value).
  if ($index < 0) {
    return lt($plural, $args, $options);
  }
  else {
    switch ($index) {
      case "0":
        return lt($singular, $args, $options);
      case "1":
        return lt($plural, $args, $options);
      default:
        unset($args['@count']);
        $args['@count[' . $index . ']'] = $count;
        return lt(strtr($plural, array('@count' => '@count[' . $index . ']')), $args, $options);
    }
  }
}

// ---------------------------------------------------------------------------------
// Locale core functionality

/**
 * Provides interface translation services.
 *
 * This function is called from lt() to translate a string if needed.
 *
 * Copied from locale() in locale.module.
 *
 * @param $string
 *   A string to look up translation for. If omitted, all the
 *   cached strings will be returned in all languages already
 *   used on the page.
 * @param $context
 *   The context of this string.
 * @param $langcode
 *   Language code to use for the lookup.
 */
function lobbywatch_translate($string = NULL, $context = NULL, $langcode = NULL, $textgroup = 'default', $location = null) {
  global $language;

  // Use the advanced drupal_static() pattern, since this is called very often.
  static $drupal_static_fast;
  if (!isset($drupal_static_fast)) {
//     $drupal_static_fast['locale'] = &drupal_static(__FUNCTION__);
    $drupal_static_fast['lobbywatch_translate'] = &php_static_cache(__FUNCTION__);
  }
  $locale_t = &$drupal_static_fast['lobbywatch_translate'];


  if (!isset($string)) {
    // Return all cached strings if no string was specified
    return $locale_t;
  }

  $langcode = isset($langcode) ? $langcode : $language->language;

  // Store database cached translations in a static variable. Only build the
  // cache after $language has been set to avoid an unnecessary cache rebuild.
  if (!isset($locale_t[$langcode]) && isset($language)) {
    $locale_t[$langcode] = array();
    // Disabling the usage of string caching allows a module to watch for
    // the exact list of strings used on a page. From a performance
    // perspective that is a really bad idea, so we have no user
    // interface for this. Be careful when turning this option off!
    if (!is_lobbywatch_forms() && variable_get('locale_cache_strings', 1) == 1) {
      $old_db = db_set_active();
      try {
        if ($cache = cache_get('translation:' . $langcode, 'cache')) {
          $locale_t[$langcode] = $cache->data;
        }
        elseif (lock_acquire('translation_cache_' . $langcode)) {
          db_set_active('lobbywatch');
          // Refresh database stored cache of translations for given language.
          // We only store short strings used in current version, to improve
          // performance and consume less memory.
          $result = db_query("SELECT s.source, s.context, t.translation, t.lang FROM {translation_source} s LEFT JOIN {translation_target} t ON s.id = t.translation_source_id AND t.lang = :language WHERE s.textgroup = '$textgroup' AND LENGTH(s.source) < :length", array(':language' => $langcode, ':length' => variable_get('locale_cache_length', 75)));
          // ':version' => VERSION,
          // s.textgroup = 'default' AND s.version = :version AND
          foreach ($result as $data) {
            $locale_t[$langcode][$data->context][$data->source] = (empty($data->translation) ? TRUE : $data->translation);
          }
          db_set_active();
          cache_set('translation:' . $langcode, $locale_t[$langcode]);
          lock_release('translation_cache_' . $langcode);
        }
      } finally {
        db_set_active($old_db);
      }
    }
  }

  // If we have the translation cached, skip checking the database
  if (!isset($locale_t[$langcode][$context][$string])) {

    $query = "SELECT s.id, t.translation, s.version FROM {translation_source} s LEFT JOIN {translation_target} t ON s.id = t.translation_source_id AND t.lang = :language WHERE s.source = :source AND s.context = :context AND s.textgroup = '$textgroup'";

    if (is_lobbywatch_forms()) {
          // We do not have this translation cached, so get it from the DB.
          $translation = lobbywatch_forms_db_query($query, array(
              ':language' => $langcode,
              ':source' => $string,
              ':context' => (string) $context,
          ))->fetchObject();
    } else {
      $old_db = db_set_active('lobbywatch');
      try {
          // We do not have this translation cached, so get it from the DB.
          $translation = db_query($query, array(
              ':language' => $langcode,
              ':source' => $string,
              ':context' => (string) $context,
          ))->fetchObject();
      } finally {
        // Go back to the previous database,
        // otherwise Drupal will not be able to access it's own data later on.
        db_set_active($old_db);
      }
    }

    if ($translation) {
      // We have the source string at least.
      // Cache translation string or TRUE if no translation exists.
      $locale_t[$langcode][$context][$string] = (empty($translation->translation) ? TRUE : $translation->translation);

      if (!is_lobbywatch_forms()) {
          // i18n write new translation in DB
          if ($translation->version != LOBBYWATCH_VERSION) {
            $old_db = db_set_active('lobbywatch_adv');
            try {
            // This is the first use of this string under current Drupal version. Save version
            // and clear cache, to include the string into caching next time. Saved version is
            // also a string-history information for later pruning of the tables.
            db_update('translation_source')
              ->fields(array('version' => LOBBYWATCH_VERSION))
              ->condition('id', $translation->id)
              ->execute();
            db_set_active();  // Switch to Drupal DB for cache clearing
            cache_clear_all('translation:', 'cache', TRUE);
          } finally {
            // Go back to the previous database,
            // otherwise Drupal will not be able to access it's own data later on.
            db_set_active($old_db);
          }
        }
      }
    }
    else {
      if (!is_lobbywatch_forms()) {
        $old_db = db_set_active('lobbywatch_adv');
        try {
          // We don't have the source string, cache this as untranslated.
          if ($location == null) {
            $location = request_uri();
          }
          db_merge('translation_source')
          ->insertFields(array(
            'location' => $location,
            'version' => LOBBYWATCH_VERSION,
            'created_visa' => 'drupal',
            'updated_visa' => 'drupal',
          ))
          ->key(array(
            'source' => $string,
            'context' => (string) $context,
            'textgroup' => $textgroup,
          ))
          ->execute();
          $locale_t[$langcode][$context][$string] = TRUE;
          db_set_active();  // Switch to Drupal DB for cache clearing
          // Clear locale cache so this string can be added in a later request.
          cache_clear_all('translation:', 'cache', TRUE);
        } finally {
          // Go back to the previous database,
          // otherwise Drupal will not be able to access it's own data later on.
          db_set_active($old_db);
        }
      } else {
        $locale_t[$langcode][$context][$string] = TRUE;
      }
    }
  }

  return ($locale_t[$langcode][$context][$string] === TRUE ? $string : $locale_t[$langcode][$context][$string]);
}

function is_lobbywatch_forms() {
  global $lobbywatch_is_forms;
  return isset($lobbywatch_is_forms) && $lobbywatch_is_forms === true;
}
/**
 * Reset static variables used by locale().
 *
 * Copied from locale_reset().
 */
function lobbywatch_translate_reset() {
  drupal_static_reset('lobbywatch_translate');
}

// /**
//  * Returns plural form index for a specific number.
//  *
//  * The index is computed from the formula of this language.
//  *
//  * @param $count
//  *   Number to return plural for.
//  * @param $langcode
//  *   Optional language code to translate to a language other than
//  *   what is used to display the page.
//  *
//  * @return
//  *   The numeric index of the plural variant to use for this $langcode and
//  *   $count combination or -1 if the language was not found or does not have a
//  *   plural formula.
//  */
// function locale_get_plural($count, $langcode = NULL) {
//   global $language;

//   // Used to locally cache the plural formulas for all languages.
//   $plural_formulas = &drupal_static(__FUNCTION__, array());

//   // Used to store precomputed plural indexes corresponding to numbers
//   // individually for each language.
//   $plural_indexes = &drupal_static(__FUNCTION__ . ':plurals', array());

//   $langcode = $langcode ? $langcode : $language->language;

//   if (!isset($plural_indexes[$langcode][$count])) {
//     // Retrieve and statically cache the plural formulas for all languages.
//     if (empty($plural_formulas)) {
//       foreach (language_list() as $installed_language) {
//         $plural_formulas[$installed_language->language] = $installed_language->formula;
//       }
//     }
//     // If there is a plural formula for the language, evaluate it for the given
//     // $count and statically cache the result for the combination of language
//     // and count, since the result will always be identical.
//     if (!empty($plural_formulas[$langcode])) {
//       // $n is used inside the expression in the eval().
//       $n = $count;
//       $plural_indexes[$langcode][$count] = @eval('return intval(' . $plural_formulas[$langcode] . ');');
//     }
//     // In case there is no plural formula for English (no imported translation
//     // for English), use a default formula.
//     elseif ($langcode == 'en') {
//       $plural_indexes[$langcode][$count] = (int) ($count != 1);
//     }
//     // Otherwise, return -1 (unknown).
//     else {
//       $plural_indexes[$langcode][$count] = -1;
//     }
//   }
//   return $plural_indexes[$langcode][$count];
// }


// /**
//  * Returns a language name
//  */
// function locale_language_name($lang) {
//   $list = &drupal_static(__FUNCTION__);
//   if (!isset($list)) {
//     $list = locale_language_list();
//   }
//   return ($lang && isset($list[$lang])) ? $list[$lang] : t('All');
// }

// /**
//  * Returns array of language names
//  *
//  * @param $field
//  *   'name' => names in current language, localized
//  *   'native' => native names
//  * @param $all
//  *   Boolean to return all languages or only enabled ones
//  */
// function locale_language_list($field = 'name', $all = FALSE) {
//   if ($all) {
//     $languages = language_list();
//   }
//   else {
//     $languages = language_list('enabled');
//     $languages = $languages[1];
//   }
//   $list = array();
//   foreach ($languages as $language) {
//     $list[$language->language] = ($field == 'name') ? t($language->name) : $language->$field;
//   }
//   return $list;
// }

function get_lang_suffix($lang = null) {
  if ($lang === null) {
    $lang = get_lang();
  }

  if ($lang == 'fr') {
    return '_fr';
  } else {
    return '_de';
  }
}

function get_lang() {
  global $language;

//   df($language, '$language');
  $langcode = isset($language->language) ? $language->language : 'de';
  return $langcode;
}

function lobbywatch_set_lang($lang) {
  global $language;
  $old_lang = $language;
  $langs = language_list();
  $language = $langs[$lang];
  return $old_lang;
}

/**
 * Fetch a setting parameter.
 * @return value or default value if nothing found
 */
function getSettingValue($key, $json = false, $defaultValue = null) {
  $settings = &php_static_cache(__FUNCTION__);
  if (!isset($settings)) {
    // Initially, fetch all at once
//     $eng_con = getDBConnection();
    $values = array();
    try {
      $con = get_PDO_lobbywatch_DB_connection();
//       $con->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
      $sql = "SELECT id, key_name, value FROM settings"; // v_settings does not work: SQLSTATE[HY000]: General error: 1615 Prepared statement needs to be re-prepared
//       $values = $con->query($sql);
      $sth = $con->prepare($sql);
      $sth->execute(array());
      $values = $sth->fetchAll();
    } finally {
      // Connection will automatically be closed at the end of the request.
      //       $eng_con->Disconnect();
    }

    //   df($values, '$values');
    //   df($defaultValue, '$defaultValue');
    //   df($values[0]['value'], '$values[0][value]');

    //   df(getSettingCategoryValues('Test'), 'Test');
    //   df(getSettingCategoryValues('Test3', 'nothing'), 'Test nothing');

    foreach($values as $value) {
      // Take the first result
      $settings[$value['key_name']] =  $value['value'];
    }
    //     df($settings, 'settings');
  }
  if (!isset($settings[$key])) {
    // If this function is being called for the first time for a particular
    // index field, then execute code needed to index the information already
    // available in $settings by the desired field.
//     $eng_con = getDBConnection();
    $values = array();
    try {
      $con = get_PDO_lobbywatch_DB_connection();
      $sql = "SELECT id, value
          FROM v_settings settings
          WHERE settings.key_name=:key";

      $sth = $con->prepare($sql);
      $sth->execute(array(':key' => $key));
      $values = $sth->fetchAll();
    } finally {
      // Connection will automatically be closed at the end of the request.
      //       $eng_con->Disconnect();
    }

    //   df($values, '$values');
    //   df($defaultValue, '$defaultValue');
    //   df($values[0]['value'], '$values[0][value]');

    //   df(getSettingCategoryValues('Test'), 'Test');
    //   df(getSettingCategoryValues('Test3', 'nothing'), 'Test nothing');

    if (count($values) > 1) {
      throw new Exception('Too many values for setting "' . $key . '""');
    } else if (count($values) == 0) {
      // Nothing found, return defaultValue
      $settings[$key] = $defaultValue;
    } else {
      // Take the first result
      $settings[$key] =  $values[0]['value'];
    }
  }
  // Subsequent invocations of this function for a particular index field
  // skip the above two code blocks and quickly return the already indexed
  // information.
  $setting = $settings[$key];

  return $json ? json_decode($setting, true) : $setting;
}

/**
 * Useful for color values.
 * @return key=value array
 */
function getSettingCategoryValues($categoryName, $defaultValue = null) {
  $settings = &php_static_cache(__FUNCTION__);
  //   if (!isset($settings)) {
  //     // If this function is being called for the first time after a reset,
  //     // query the database and execute any other code needed to retrieve
  //     // information about the supported languages.
  //   }
  if (!isset($settings[$categoryName])) {
//     $eng_con = getDBConnection();
    $values = array();
    try {
      $con = get_PDO_lobbywatch_DB_connection();
      // TODO close connection
      $sql = "SELECT id, key_name, value
    FROM v_settings settings
    WHERE settings.category_name=:categoryName";

      $sth = $con->prepare($sql);
      $sth->execute(array(':categoryName' => $categoryName));
      $values = $sth->fetchAll();
    } finally {
      // Connection will automatically be closed at the end of the request.
      //       $eng_con->Disconnect();
    }

    if (count($values) == 0) {
      // Nothing found, return defaultValue
      $settings[$categoryName] = $defaultValue;
    } else {
      $simple = array();
      foreach ($values as $rec) {
        $simple[$rec['key_name']] = $rec['value'];
      }
      $settings[$categoryName] = $simple;
    }
  }

  // Subsequent invocations of this function for a particular index field
  // skip the above two code blocks and quickly return the already indexed
  // information.
  return $settings[$categoryName];
}

function cut($str, $maxLength = 20) {
  if (!isset($str)) {
    $s = 'NULL';
  } elseif (is_array($str) || is_object($str)) {
    $s = json_encode($str, JSON_UNESCAPED_UNICODE);
  } else {
    $s = $str;
  }

  if (mb_strlen($s) > $maxLength) {
    return mb_substr($s, 0, $maxLength) . '…';
  } else {
    return $s;
  }
}

// http://stackoverflow.com/questions/14773072/php-str-pad-unicode-issue
// function mb_str_pad($str, $pad_len, $pad_str = ' ', $dir = STR_PAD_RIGHT, $encoding = NULL) {
//   $encoding = $encoding === NULL ? mb_internal_encoding() : $encoding;
//   $padBefore = $dir === STR_PAD_BOTH || $dir === STR_PAD_LEFT;
//   $padAfter = $dir === STR_PAD_BOTH || $dir === STR_PAD_RIGHT;
//   $pad_len -= mb_strlen($str, $encoding);
//   $targetLen = $padBefore && $padAfter ? $pad_len / 2 : $pad_len;
//   $strToRepeatLen = mb_strlen($pad_str, $encoding);
//   $repeatTimes = ceil($targetLen / $strToRepeatLen);
//   $repeatedString = str_repeat($pad_str, max(0, $repeatTimes)); // safe if used with valid unicode sequences (any charset)
//   $before = $padBefore ? mb_substr($repeatedString, 0, floor($targetLen), $encoding) : '';
//   $after = $padAfter ? mb_substr($repeatedString, 0, ceil($targetLen), $encoding) : '';
//   return $before . $str . $after;
// }

// http://stackoverflow.com/questions/17851138/strpad-with-non-english-characters
function mb_str_pad ($input, $pad_length, $pad_string = null, $pad_style = STR_PAD_RIGHT, $encoding="UTF-8") {
  return str_pad($input,
      strlen($input) - mb_strlen($input, $encoding) + $pad_length,
      $pad_string, $pad_style);
}

// function to replace file_get_contents()
function new_get_file_contents($url) {
  $ch = curl_init();
  $timeout = 10; // set to zero for no timeout
  curl_setopt ($ch, CURLOPT_URL, $url);
  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $file_contents = curl_exec($ch); // take out the spaces of curl statement!!
  curl_close($ch);
  return $file_contents;
}

// See also addslashes()
function escape_string($string) {
  // return mysql_escape_string($string);
  // mysql_real_escape_string requires the connection

  $replacements = array(
      "\x00" => '\x00',
      "\n" => '\n',
      "\r" => '\r',
      "\\" => '\\\\',
      "'" => "\'",
      '"' => '\"',
      "\x1a" => '\x1a'
  );
  return strtr($string, $replacements);
}

function _lobbywatch_clean_rat_suffix($str) {
  return preg_replace('/( |-)(NR|SR|V|CN|CE)$/', '', $str);
}

function custom_GetConnectionOptions() {
  global $db_connection;

  if (function_exists('GetGlobalConnectionOptions')) {
    $result = GetGlobalConnectionOptions();
  } else {
    $result = $db_connection;
  }
  $result['client_encoding'] = 'utf8';
  if (function_exists('GetApplication')) {
    GetApplication()->GetUserAuthentication()->ApplyIdentityToConnectionOptions($result);
  }
  return $result;
}

function getDBConnection() {
  $con_factory = MyPDOConnectionFactory::getInstance();

  $options = function_exists('GetConnectionOptions') ? GetConnectionOptions() : custom_GetConnectionOptions();
  $eng_con = $con_factory->CreateConnection($options);
  $eng_con->Connect();
  // TODO close connection
  //$con = $eng_con->GetConnectionHandle();
  return $eng_con;
}

function getDBConnectionHandle() {
  return getDBConnection()->GetConnectionHandle();
}

// set_db_session_parameters() must not be in utils.php since in Drupal we use a similar function
function utils_set_db_session_parameters($con) {
  $session_sql = "SET SESSION group_concat_max_len=50000;" .
    "SET sql_mode='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';";
  $con->query($session_sql);
}

// set_db_session_parameters() must not be in utils.php since in Drupal we use a similar function
function utils_set_db_session_parameters_exec($con) {
  $session_sql_1 = "SET SESSION group_concat_max_len=50000;";
  $session_sql_2 = "SET sql_mode='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';";
  $con->exec($session_sql_1);
  $con->exec($session_sql_2);
}

/**
 * Executes an arbitrary query string against the active database.
 *
 * Copied from Drupal db_query();
 *
 * Use this function for SELECT queries if it is just a simple query string.
 * If the caller or other modules need to change the query, use db_select()
 * instead.
 *
 * Do not use this function for INSERT, UPDATE, or DELETE queries. Those should
 * be handled via db_insert(), db_update() and db_delete() respectively.
 *
 * @param $query
 *   The prepared statement query to run. Although it will accept both named and
 *   unnamed placeholders, named placeholders are strongly preferred as they are
 *   more self-documenting.
 * @param $args
 *   An array of values to substitute into the query. If the query uses named
 *   placeholders, this is an associative array in any order. If the query uses
 *   unnamed placeholders (?), this is an indexed array and the order must match
 *   the order of placeholders in the query string.
 * @param $options
 *   An array of options to control how the query operates.
 *
 * @return DatabaseStatementInterface
 *   A prepared statement object, already executed.
 *
 * @see DatabaseConnection::defaultOptions()
 */
function lobbywatch_forms_db_query($query, array $args = array(), array $options = array()) {

//   if (empty($options['target'])) {
//     $options['target'] = 'default';
//   }

  $con_factory = MyPDOConnectionFactory::getInstance();
  $con_options =   function_exists('GetConnectionOptions') ? GetConnectionOptions() : custom_GetConnectionOptions();

  $eng_con = $con_factory->CreateConnection($con_options);
  try {
    $eng_con->Connect();
    $con = $eng_con->GetConnectionHandle();
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    //         df($eng_con->Connected(), 'connected');
    //         df($con, 'con');
    $cmd = $con_factory->CreateEngCommandImp();

    if (function_exists('set_db_session_parameters')) {
      set_db_session_parameters($con);
    } else {
      utils_set_db_session_parameters($con);
    }

//   return Database::getConnection($options['target'])->query($query, $args, $options);
    // Use default values if not already set.
    $options += lobbywatch_PDO_defaultOptions();

    lobbywatch_DB_expandArguments($query, $args);
//     $stmt = $this->prepareQuery($query);
    $query = lobbywatch_prefixTables($query);
    $stmt = $con->prepare($query);

    if (isset($options['fetch'])) {
      if (is_string($options['fetch'])) {
        // Default to an object. Note: db fields will be added to the object
        // before the constructor is run. If you need to assign fields after
        // the constructor is run, see http://drupal.org/node/315092.
        $stmt->setFetchMode(PDO::FETCH_CLASS, $options['fetch']);
      }
      else {
        $stmt->setFetchMode($options['fetch']);
      }
    }

    $stmt->execute($args);

    // Depending on the type of query we may need to return a different value.
    // See DatabaseConnection::defaultOptions() for a description of each
    // value.
    switch ($options['return']) {
      case LW_DB_RETURN_STATEMENT:
        return $stmt;
      case LW_DB_RETURN_AFFECTED:
        return $stmt->rowCount();
      case LW_DB_RETURN_INSERT_ID:
//         return $this->lastInsertId();
        return;
      case LW_DB_RETURN_NULL:
        return;
      default:
        throw new PDOException('Invalid return directive: ' . $options['return']);
    }
    }
    catch (PDOException $e) {
      if ($options['throw_exception']) {
        // Add additional debug information.
//         if ($query instanceof DatabaseStatementInterface) {
//           $e->query_string = $stmt->getQueryString();
//         }
//         else {
          $e->query_string = $query;
//         }
        $e->args = $args;
        throw $e;
      }
      return NULL;
//     }


//     //         df($sql);
//     $result = array();

//     $result = $sth->fetchAll();

//     if (!$result) {
//       df($eng_con->LastError());
//       throw new Exception('ID not found');
//     }
  } finally {
    // Connection will automatically be closed at the end of the request.
    //     $eng_con->Disconnect();
  }
}

/**
 * Set the list of prefixes used by this database connection.
 *
 * Copied from setPrefix().
 *
 * @param $prefix
 *   The prefixes, in any of the multiple forms documented in
 *   default.settings.php.
 */
function lobbywatch_DB_setPrefix($prefix) {
  if (is_array($prefix)) {
    $prefixes = $prefix + array('default' => '');
  }
  else {
    $prefixes = array('default' => $prefix);
  }

  // Set up variables for use in prefixTables(). Replace table-specific
  // prefixes first.
  $prefixSearch = array();
  $prefixReplace = array();
  foreach ($prefixes as $key => $val) {
    if ($key != 'default') {
      $prefixSearch[] = '{' . $key . '}';
      $prefixReplace[] = $val . $key;
    }
  }
  // Then replace remaining tables with the default prefix.
  $prefixSearch[] = '{';
  $prefixReplace[] = $this->prefixes['default'];
  $prefixSearch[] = '}';
  $prefixReplace[] = '';
}

/**
 * Appends a database prefix to all tables in a query.
 *
 * Copied from prefixTables().
 *
 * Queries sent to Drupal should wrap all table names in curly brackets. This
 * function searches for this syntax and adds Drupal's table prefix to all
 * tables, allowing Drupal to coexist with other systems in the same database
 * and/or schema if necessary.
 *
 * @param $sql
 *   A string containing a partial or entire SQL query.
 *
 * @return
 *   The properly-prefixed string.
 */
function lobbywatch_prefixTables($sql, $prefix = '') {
  if (is_array($prefix)) {
    $prefixes = $prefix + array('default' => '');
  }
  else {
    $prefixes = array('default' => $prefix);
  }

  // Set up variables for use in prefixTables(). Replace table-specific
  // prefixes first.
  $prefixSearch = array();
  $prefixReplace = array();
  foreach ($prefixes as $key => $val) {
    if ($key != 'default') {
      $prefixSearch[] = '{' . $key . '}';
      $prefixReplace[] = $val . $key;
    }
  }
  // Then replace remaining tables with the default prefix.
  $prefixSearch[] = '{';
  $prefixReplace[] = $prefixes['default'];
  $prefixSearch[] = '}';
  $prefixReplace[] = '';

  return str_replace($prefixSearch, $prefixReplace, $sql);
}

/**
 * Flag to indicate a query call should simply return NULL.
 *
 * This is used for queries that have no reasonable return value anyway, such
 * as INSERT statements to a table without a serial primary key.
 */
define('LW_DB_RETURN_NULL', 0);

/**
 * Flag to indicate a query call should return the prepared statement.
 */
define('LW_DB_RETURN_STATEMENT', 1);

/**
 * Flag to indicate a query call should return the number of affected rows.
 */
define('LW_DB_RETURN_AFFECTED', 2);

/**
 * Flag to indicate a query call should return the "last insert id".
 */
define('LW_DB_RETURN_INSERT_ID', 3);

/**
 * Returns the default query options for any given query.
 *
 * A given query can be customized with a number of option flags in an
 * associative array:
 * - target: The database "target" against which to execute a query. Valid
 *   values are "default" or "slave". The system will first try to open a
 *   connection to a database specified with the user-supplied key. If one
 *   is not available, it will silently fall back to the "default" target.
 *   If multiple databases connections are specified with the same target,
 *   one will be selected at random for the duration of the request.
 * - fetch: This element controls how rows from a result set will be
 *   returned. Legal values include PDO::FETCH_ASSOC, PDO::FETCH_BOTH,
 *   PDO::FETCH_OBJ, PDO::FETCH_NUM, or a string representing the name of a
 *   class. If a string is specified, each record will be fetched into a new
 *   object of that class. The behavior of all other values is defined by PDO.
 *   See http://php.net/manual/pdostatement.fetch.php
 * - return: Depending on the type of query, different return values may be
 *   meaningful. This directive instructs the system which type of return
 *   value is desired. The system will generally set the correct value
 *   automatically, so it is extremely rare that a module developer will ever
 *   need to specify this value. Setting it incorrectly will likely lead to
 *   unpredictable results or fatal errors. Legal values include:
 *   - Database::RETURN_STATEMENT: Return the prepared statement object for
 *     the query. This is usually only meaningful for SELECT queries, where
 *     the statement object is how one accesses the result set returned by the
 *     query.
 *   - Database::RETURN_AFFECTED: Return the number of rows affected by an
 *     UPDATE or DELETE query. Be aware that means the number of rows actually
 *     changed, not the number of rows matched by the WHERE clause.
 *   - Database::RETURN_INSERT_ID: Return the sequence ID (primary key)
 *     created by an INSERT statement on a table that contains a serial
 *     column.
 *   - Database::RETURN_NULL: Do not return anything, as there is no
 *     meaningful value to return. That is the case for INSERT queries on
 *     tables that do not contain a serial column.
 * - throw_exception: By default, the database system will catch any errors
 *   on a query as an Exception, log it, and then rethrow it so that code
 *   further up the call chain can take an appropriate action. To suppress
 *   that behavior and simply return NULL on failure, set this option to
 *   FALSE.
 *
 * @return
 *   An array of default query options.
 */
function lobbywatch_PDO_defaultOptions() {
  return array(
      'target' => 'default',
      'fetch' => PDO::FETCH_OBJ,
      'return' => LW_DB_RETURN_STATEMENT,
      'throw_exception' => TRUE,
  );
}

/**
 * Expands out shorthand placeholders.
 *
 * Copied from expandArguments()
 *
 * Drupal supports an alternate syntax for doing arrays of values. We
 * therefore need to expand them out into a full, executable query string.
 *
 * @param $query
 *   The query string to modify.
 * @param $args
 *   The arguments for the query.
 *
 * @return
 *   TRUE if the query was modified, FALSE otherwise.
 */
function lobbywatch_DB_expandArguments(&$query, &$args) {
  $modified = FALSE;

  // If the placeholder value to insert is an array, assume that we need
  // to expand it out into a comma-delimited set of placeholders.
  foreach (array_filter($args, 'is_array') as $key => $data) {
    $new_keys = array();
    foreach (array_values($data) as $i => $value) {
      // This assumes that there are no other placeholders that use the same
      // name.  For example, if the array placeholder is defined as :example
      // and there is already an :example_2 placeholder, this will generate
      // a duplicate key.  We do not account for that as the calling code
      // is already broken if that happens.
      $new_keys[$key . '_' . $i] = $value;
    }

    // Update the query with the new placeholders.
    // preg_replace is necessary to ensure the replacement does not affect
    // placeholders that start with the same exact text. For example, if the
    // query contains the placeholders :foo and :foobar, and :foo has an
    // array of values, using str_replace would affect both placeholders,
    // but using the following preg_replace would only affect :foo because
    // it is followed by a non-word character.
    $query = preg_replace('#' . $key . '\b#', implode(', ', array_keys($new_keys)), $query);

    // Update the args array with the new placeholders.
    unset($args[$key]);
    $args += $new_keys;

    $modified = TRUE;
  }

  return $modified;
}

function _lobbywatch_ws_get_rechtsform($rechtsform_handelsregister) {
  switch($rechtsform_handelsregister) {
        //  Rechtsformen des Privatrechts, im Handelsregister angewendet
    case '0101': $val = 'Einzelunternehmen'; break; // 0101 Einzelunternehmen
    case '0103': $val = 'KG'; break; // 0103 Kollektivgesellschaft
        // 0104 Kommanditgesellschaft
        // 0105 Kommanditaktiengesellschaft
    case '0106': $val = 'AG'; break; // 0106 Aktiengesellschaft
    case '0107': $val = 'GmbH'; break; // 0107 Gesellschaft mit beschränkter Haftung GMBH / SARL
    case '0108': $val = 'Genossenschaft'; break; // 0108 Genossenschaft
    case '0109': $val = 'Verein'; break; // 0109 Verein (hier werden auch staatlich anerkannte Kirchen geführt)
    case '0110': $val = 'Stiftung'; break; // 0110 Stiftung
        // 0111 Ausländische Niederlassung im Handelsregister eingetragen
        // 0113 Besondere Rechtsform Rechtsformen, die unter keiner anderen Kategorie aufgeführt werden können.
        // 0114 Kommanditgesellschaft für kollektive Kapitalanlagen
        // 0115 Investmentgesellschaft mit variablem Kapital (SICAV)
        // 0116 Investmentgesellschaft mit festem Kapital (SICAF)
    case '0117': $val = 'Oeffentlich-rechtlich'; break; // 0117 Institut des öffentlichen Rechts
        // 0118 Nichtkaufmännische Prokuren
        // 0119 Haupt von Gemeinderschaften
        // 0151 Schweizerische Zweigniederlassung im Handelsregister eingetragen
        //  Rechtsformen des öffentlichen Rechts, nicht im Handelsregister angewendet
    case '0220': $val = 'Staatlich'; break; // 0220 Verwaltung des Bundes
    case '0221': $val = 'Staatlich'; break; // 0221 Verwaltung des Kantons
    case '0222': $val = 'Staatlich'; break; // 0222 Verwaltung des Bezirks
    case '0223': $val = 'Staatlich'; break; // 0223 Verwaltung der Gemeinde
    case '0224': $val = 'Staatlich'; break; // 0224 öffentlich-rechtliche Körperschaft (Verwaltung) Hier werden die öffentlich-rechtlichen Körperschaften aufgeführt, die nicht un-  ter den Punkten Verwaltung des Bundes, des Kantons, des Bezirks oder der  Gemeinde aufgelistet werden können. Z.B. Gemeindeverbände, Schulge-  meinden, Kreise und von mehreren Körperschaften geführte Verwaltungen.
    case '0230': $val = 'Staatlich'; break; // 0230 Unternehmen des Bundes
    case '0231': $val = 'Staatlich'; break; // 0231 Unternehmen des Kantons
    case '0232': $val = 'Staatlich'; break; // 0232 Unternehmen des Bezirks
    case '0233': $val = 'Staatlich'; break; // 0233 Unternehmen der Gemeinde
    case '0234': $val = 'Oeffentlich-rechtlich'; break; // 0234 öffentlich-rechtliche Körperschaft (Unternehmen) Hierzu zählen alle öffentlich-rechtlichen Unternehmen, die nicht unter den  Punkten Unternehmen des Bundes, des Kantons, des Bezirks oder der Ge-  meinde ausgelistet werden können, z.B. die Forstbetriebe von Ortsbürgerge-  meinden.
        //  Andere  Rechtsformen nicht im Handelsregister angewendet
    case '0302': $val = 'Einfache Gesellschaft'; break; // 0302 Einfache Gesellschaft
        // 0312 Ausländische Niederlassung nicht im Handelsregister eingetragen
        // 0327 Ausländisches öffentliches Unternehmen  Staatlich geführte ausländische Unternehmen, z.B. Niederlassungen von aus-  ländischen Eisenbahnen und Tourismusbehörden.
        // 0328 Ausländische öffentliche Verwaltung  Insbesondere Botschaften, Missionen und Konsulate.
        // 0329 Internationale Organisation
        //  Ausländische Unternehmen
        // 0441 Ausländische Unternehmen (Entreprise étrangère, impresa straniera)

    default: $val = '';
  }
  return $val;
}

/**
 * Returns the description to the handelsregister rechtsform code.
 */
function _lobbywatch_get_rechtsform_handelsregister_code_name($rechtsform_handelsregister) {
  switch($rechtsform_handelsregister) {
    case '01': return '01: Rechtsformen des Privatrechts, im Handelsregister angewendet';
    case '0101': return '0101: Einzelunternehmen';
    case '0103': return '0103: Kollektivgesellschaft';
    case '0104': return '0104: Kommanditgesellschaft';
    case '0105': return '0105: Kommanditaktiengesellschaft';
    case '0106': return '0106: Aktiengesellschaft';
    case '0107': return '0107: Gesellschaft mit beschränkter Haftung GMBH / SARL';
    case '0108': return '0108: Genossenschaft';
    case '0109': return '0109: Verein (hier werden auch staatlich anerkannte Kirchen geführt)';
    case '0110': return '0110: Stiftung';
    case '0111': return '0111: Ausländische Niederlassung im Handelsregister eingetragen';
    case '0113': return '0113: Besondere Rechtsform';
    case '0114': return '0114: Kommanditgesellschaft für kollektive Kapitalanlagen';
    case '0115': return '0115: Investmentgesellschaft mit variablem Kapital (SICAV)';
    case '0116': return '0116: Investmentgesellschaft mit festem Kapital (SICAF)';
    case '0117': return '0117: Institut des öffentlichen Rechts';
    case '0118': return '0118: Nichtkaufmännische Prokuren';
    case '0119': return '0119: Haupt von Gemeinderschaften';
    case '0151': return '0151: Schweizerische Zweigniederlassung im Handelsregister eingetragen';
    case '02': return '02: Rechtsformen des öffentlichen Rechts, nicht im Handelsregister angewendet';
    case '0220': return '0220: Verwaltung des Bundes';
    case '0221': return '0221: Verwaltung des Kantons';
    case '0222': return '0222: Verwaltung des Bezirks';
    case '0223': return '0223: Verwaltung der Gemeinde';
    case '0224': return '0224: öffentlich-rechtliche Körperschaft (Verwaltung)';
    case '0230': return '0230: Unternehmen des Bundes';
    case '0231': return '0231: Unternehmen des Kantons';
    case '0232': return '0232: Unternehmen des Bezirks';
    case '0233': return '0233: Unternehmen der Gemeinde';
    case '0234': return '0234: öffentlich-rechtliche Körperschaft (Unternehmen)';
    case '03': return '03: Andere  Rechtsformen nicht im Handelsregister angewendet';
    case '0302': return '0302: Einfache Gesellschaft';
    case '0312': return '0312: Ausländische Niederlassung nicht im Handelsregister eingetragen';
    case '0327': return '0327: Ausländisches öffentliches Unternehmen';
    case '0328': return '0328: Ausländische öffentliche Verwaltung (Botschaften, Missionen und Konsulate)';
    case '0329': return '0329: Internationale Organisation';
    case '04': return '04: Ausländische Unternehmen';
    case '0441': return '0441: Ausländische Unternehmen (Entreprise étrangère, impresa straniera)';

    default: return null;
  }
}


function _lobbywatch_ws_get_land_id($iso2) {
  $table = 'country';
  $ret = null;
  try {
    $sql = "
      SELECT id
      FROM v_$table $table
      WHERE $table.`iso-2`=:iso2";

//     df($sql , 'sql');
    if (is_lobbywatch_forms()) {
        $result = lobbywatch_forms_db_query($sql, array(':iso2' => $iso2));
    } else {
      $old_db = db_set_active('lobbywatch');
      try {
        $result = db_query($sql, array(':iso2' => $iso2));
      } finally {
        // Go back to the previous database,
        // otherwise Drupal will not be able to access it's own data later on.
        db_set_active($old_db);
      }
    }

    $items = $result->fetchColumn(0);
//     df($items, 'items');

//     $count = count($items);
//     $success = $count == 1;
//     $message .= count($items) . " record(s) found";
    $ret = $items;
  } catch(Exception $e) {
//     $message .= _utils_get_exeption($e);
//     $success = false;
    $ret = null;
  } finally {
  }
  return $ret;
}

function _utils_get_exeption($e) {
  global $show_stacktrace;
  return $show_stacktrace ? $e->getMessage() . "\n------\n" . $e->getTraceAsString() : $e->getMessage();
}

function _lobbywatch_check_uid_format($uid_raw, &$uid, &$message) {
    $matches = array();
    $success = true;
    if (preg_match('/^CHE-(\d{3})\.(\d{3}).(\d{3})$/', $uid_raw, $matches)) {
      $uid = $matches[1] . $matches[2] . $matches[3];
    } else if (preg_match('/^(\d{9})$/', $uid_raw, $matches)) {
      $uid = $matches[1];
    } else {
      $message = "Wrong UID format: $uid_raw, correct: (9-digits or CHE-000.000.000)";
      $success = false;
    }
    return $success;
}

function _lobbywatch_calculate_uid_check_digit($uid_number) {
  if (!is_numeric($uid_number) || strlen($uid_number) < 8 || strlen($uid_number) > 9) {
    return null;
  }
  $digits = str_split(substr($uid_number, 0, 8));
  $weight = array(5,4,3,2,7,6,5,4);
  // http://c2.com/cgi/wiki?DotProductInManyProgrammingLanguages
  $dot_product = array_sum(array_map(function($a,$b) { return $a*$b; }, $digits, $weight));
  $check_digit = 11 - ($dot_product % 11);
  $check_digit = $check_digit == 11 ? 0 : $check_digit;

  return $check_digit;
}

function _lobbywatch_check_uid_check_digit($uid_number, &$message) {
  $uid_check_digit = substr($uid_number, 8, 1);
  $check_digit = _lobbywatch_calculate_uid_check_digit($uid_number);

  if ($uid_check_digit != $check_digit || $check_digit == 10) {
    $message = "Wrong UID check digit: $uid_check_digit, correct: $check_digit" /*. ", sum=$dot_product"*/;
    return false;
  }
  return true;
}

function initDataArray() {
  $data = array();
  $data['message'] = '';
  $data['sql'] = '';
  $data['data'] = array();
  $data['success'] = true;
  $data['count'] = 0;
  return $data;
}

function getUidWsLogin($test_mode = false) {
  if ($test_mode) {
    $host = 'www.uid-wse-a.admin.ch';
  } else {
    $host = 'www.uid-wse.admin.ch';
  }
  $wsdl = "https://$host/V3.0/PublicServices.svc?wsdl";
  $response = array(
    'wsdl' => $wsdl,
    'login' => null,
    'password' => null,
    'host' => $host,
  );
  return $response;
}

function getZefixWsLogin($test_mode = false) {
  global $zefix_ws_login;
  $username = $zefix_ws_login['username'];
  $password = $zefix_ws_login['password'];

//   print_r($zefix_ws_login);

  if ($test_mode) {
//     $wsdl = "http://" . urlencode($username) . ':' . urlencode($password) . "@test-e-service.fenceit.ch/ws-zefix-1.6/ZefixService?wsdl";
//     $wsdl = "https://www.e-service.admin.ch/wiki/download/attachments/44827026/ZefixService.wsdl?version=2&modificationDate=1428391225000";
    // Workaround PHP bug https://bugs.php.net/bug.php?id=61463
    $wsdl = "https://cms.lobbywatch.ch/d7/sites/lobbywatch.ch/app/common/ZefixService16Test.wsdl";
//     $host = 'test-e-service.fenceit.ch';
    $host = 'cms.lobbywatch.ch';
  } else {
//     $wsdl = "http://" . urlencode($username) . ':' . urlencode($password) . "@www.e-service.admin.ch/ws-zefix-1.6/ZefixService?wsdl";
//     $wsdl = "https://www.e-service.admin.ch/wiki/download/attachments/44827026/ZefixService.wsdl?version=2&modificationDate=1428391225000";
    // Workaround PHP bug https://bugs.php.net/bug.php?id=61463
    $wsdl = "https://cms.lobbywatch.ch/sites/lobbywatch.ch/app/common/ZefixService16.wsdl";
//     $host = 'www.e-service.admin.ch';
    $host = 'cms.lobbywatch.ch';
  }
  $response = array(
    'wsdl' => $wsdl,
    'username' => $username,
    'password' => $password,
    'host' => $host,
  );
  return $response;
}

function initSoapClient(&$data, $login, $verbose = 0, $ssl = true) {
  if ($ssl) {
    $ssl_config = array(
      "verify_peer"=>true,
      "allow_self_signed"=>false,
      "cafile"=> dirname(__FILE__) . "/../settings/cacert.pem",
      "verify_depth"=>5,
      "peer_name"=> $login['host'],
      'disable_compression' => true,
      'SNI_enabled'         => true,
      'ciphers'             => 'ALL!EXPORT!EXPORT40!EXPORT56!aNULL!LOW!RC4',
//       'ciphers'             => 'ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:DHE-DSS-AES128-GCM-SHA256:kEDH+AESGCM:ECDHE-RSA-AES128-SHA256:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA:ECDHE-ECDSA-AES128-SHA:ECDHE-RSA-AES256-SHA384:ECDHE-ECDSA-AES256-SHA384:ECDHE-RSA-AES256-SHA:ECDHE-ECDSA-AES256-SHA:DHE-RSA-AES128-SHA256:DHE-RSA-AES128-SHA:DHE-DSS-AES128-SHA256:DHE-RSA-AES256-SHA256:DHE-DSS-AES256-SHA:DHE-RSA-AES256-SHA:AES128-GCM-SHA256:AES256-GCM-SHA384:ECDHE-RSA-RC4-SHA:ECDHE-ECDSA-RC4-SHA:AES128:AES256:RC4-SHA:HIGH:!aNULL:!eNULL:!EXPORT:!DES:!3DES:!MD5:!PSK',
      );
  } else {
    $ssl_config = array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    );
  }

  $context = stream_context_create(array(
    'ssl' => $ssl_config,
    'http' => array(
      'user_agent' => 'PHPSoapClient',
    ),
  ));

//   $wsdl = "https://www.uid-wse.admin.ch/V3.0/PublicServices.svc?wsdl";
  $soapParam = array(
    "stream_context" => $context,
    "trace"          => true,
    "exceptions"     => true,
  );

  if (isset($login['username']) && isset($login['password'])) {
    $soapParam['login'] = $login['username'];
    $soapParam['password'] = $login['password'];
  }

  $wsdl = $login['wsdl'];

//    print_r($soapParam);
//   print($wsdl);

  $data['sql'] .= " | wsdl=$wsdl";

  /* Initialize webservice with your WSDL */
  $client = new SoapClient($wsdl, $soapParam);
//   var_dump($client->__getFunctions());
  return $client;
}

function _lobbywatch_fetch_ws_uid_data($uid_raw, $verbose = 0, $ssl = true, $test_mode = false) {
  $data = initDataArray();

  if (!_lobbywatch_check_uid_format($uid_raw, $uid, $data['message'])) {
    $data['data'] = array();
    $data['success'] = false;
    return $data;
  }

  $data['sql'] .= "uid=$uid";

  if (!_lobbywatch_check_uid_check_digit($uid, $data['message'])) {
    $data['data'] = array();
    $data['success'] = false;
    return $data;
  }

  $client = initSoapClient($data, getUidWsLogin($test_mode), $verbose, $ssl);

  /*
  Parameter: uid
  Datentyp: eCH-0097:uidStructureType
      http://www.ech.ch/vechweb/page?p=dossier&documentNumber=eCH-0097&documentVersion=2.0
      http://www.ech.ch/alfresco/guestDownload/attach/workspace/SpacesStore/978ac878-a051-401d-b219-f6e540cadab5/STAN_d_REP_2015-11-26_eCH-0097_V2.0_Datenstandard%20Unternehmensidentifikation.pdf
  Beschreibung: UID des gesuchten Unternehmens

  Rückgabewert: eCH-0108:organisationType Array
      http://www.ech.ch/vechweb/page?p=dossier&documentNumber=eCH-0108&documentVersion=3.0
      http://www.ech.ch/alfresco/guestDownload/attach/workspace/SpacesStore/bc371174-261e-4152-9d60-3b5a4e79ce7b/STAN_d_DEF_2014-04-11_eCH-0108_V3.0_Unternehmens-Identifikationsregister.pdf

  Mögliche Fehlermeldungen:
  - Data_validation_failed
  - Request_limit_exceeded

  <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:uid="http://www.uid.admin.ch/xmlns/uid-wse" xmlns:ns="http://www.ech.ch/xmlns/eCH-0097-f/2">
   <soapenv:Header/>
   <soapenv:Body>
      <uid:GetByUID>
         <!--Optional:-->
         <uid:uid>
            <!--Optional:-->
            <ns:uidOrganisationIdCategorie>CHE</ns:uidOrganisationIdCategorie>
            <!--Optional:-->
            <ns:uidOrganisationId>107810911</ns:uidOrganisationId>
         </uid:uid>
      </uid:GetByUID>
   </soapenv:Body>
  </soapenv:Envelope>
  */

  ws_get_organization_from_uid($uid, $client, $data, $verbose);
  return $data;
}

function _lobbywatch_fetch_ws_zefix_data($uid_raw, $verbose = 0, $ssl = true, $test_mode = false) {
  $data = initDataArray();

  if (!_lobbywatch_check_uid_format($uid_raw, $uid, $data['message'])) {
    $data['data'] = array();
    $data['success'] = false;
    return $data;
  }

  $data['sql'] .= "uid=$uid";

  if (!_lobbywatch_check_uid_check_digit($uid, $data['message'])) {
    $data['data'] = array();
    $data['success'] = false;
    return $data;
  }

  $client = initSoapClient($data, getZefixWsLogin($test_mode), $verbose, $ssl);

  ws_get_organization_from_zefix($uid, $client, $data, $verbose);
  return $data;
}

function ws_get_organization_from_zefix($uid_raw, $client, &$data, $verbose) {
  /* Invoke webservice method with your parameters. */
  $response = null;
  try {
    $uid = getUIDnumber($uid_raw);
    /* Set your parameters for the request */
    $params = array(
      'uid' => $uid,
    );
    $response = $client->GetByUidFull($params);
    if (isset($response->result)) {
      fillDataFromZefixResult($response->result, $data);
    } else {
      $data['message'] .= 'No Result from zefix webservice. ';
      $data['success'] = false;
      $data['sql'] = "uid=$uid";
    }
  } catch(Exception $e) {
    $data['message'] .= _utils_get_exeption($e);
    $data['success'] = false;
    $data['sql'] = "uid=$uid";
  } finally {
    ws_verbose_logging($client, $response, $data, $verbose);
  }
  return $response;
}

function ws_get_organization_from_uid($uid_raw, $client, &$data, $verbose) {
  /* Invoke webservice method with your parameters. */
  $response = null;
  try {
    $uid = getUIDnumber($uid_raw);
    /* Set your parameters for the request */
    $params = array(
      'uid' => array(
        'uidOrganisationIdCategorie' => 'CHE',
        'uidOrganisationId' => $uid,
      ),
    );
//     $response = $client->__soapCall("GetByUID", array($params));
    $response = $client->GetByUID($params);
    if (isset($response->GetByUIDResult)) {
      fillDataFromUIDResult($response->GetByUIDResult, $data);
    } else {
      $data['message'] .= 'No Result from uid webservice. ';
      $data['success'] = false;
      $data['sql'] = "uid=$uid";
    }
  } catch(Exception $e) {
    $data['message'] .= _utils_get_exeption($e);
    $data['success'] = false;
    $data['sql'] = "uid=$uid";
  } finally {
    ws_verbose_logging($client, $response, $data, $verbose);
  }
  return $response;
}

function _lobbywatch_fetch_ws_uid_data_from_old_hr_id($old_hr_id_raw, $verbose = 0, $ssl = true, $test_mode = false) {
  $data = initDataArray();

  $old_hr_id = formatOldHandelsregisterID($old_hr_id_raw);

  $data['sql'] .= "hr-id=$old_hr_id | hr-id-raw=$old_hr_id_raw";

  $client = initSoapClient($data, getUidWsLogin($test_mode), $verbose, $ssl);

  /* Invoke webservice method with your parameters. */
  try {
    $params = array(
      'searchParameters' => array(
        'organisation' => array(
          'organisationIdentification' => array(
            'OtherOrganisationId' => array(
              'organisationIdCategory' => 'CH.HR',
              'organisationId' => $old_hr_id,
            )
          )
        )
      ),
    );
    $response = $client->Search($params);
    fillDataFromUIDResult($response->SearchResult, $data);
  } catch(Exception $e) {
    $data['message'] .= _utils_get_exeption($e);
    $data['success'] = false;
  } finally {
    ws_verbose_logging($client, $response, $data, $verbose);
  }
  return $data;
}


function ws_verbose_logging($client, $response, &$data, $verbose) {
  if ($verbose >= 11) {
    print_r($client->__getLastRequestHeaders());
    print_r($client->__getLastRequest());
  }
  if ($verbose >= 10) {
    $data['client'] = $client;
    $data['response'] = $response;
  }
  if ($verbose >= 12) {
    print_r($response);
  }
}

function fillDataFromUIDResult($object, &$data) {
    if (!empty((array) $object)) {
//       print_r($object);
      if (is_array($object->organisationType)) {
        $ot = $object->organisationType[0];
        $data['count'] = count($object->organisationType);
      } else {
        $ot = $object->organisationType;
        $data['count'] = 1;
      }
      $oid = $ot->organisation->organisationIdentification;
      $uid_ws = $oid->uid->uidOrganisationId;
      $base_address = $ot->organisation->contact->address;
      $address = is_array($base_address) ? $base_address[0]->postalAddress->addressInformation : $base_address->postalAddress->addressInformation;
      $address2 = is_array($base_address) && isset($base_address[1]->postalAddress->addressInformation) ? $base_address[1]->postalAddress->addressInformation : null;
      $old_hr_id = isset($oid->OtherOrganisationId) ? (is_array($oid->OtherOrganisationId) ? $oid->OtherOrganisationId[0] : $oid->OtherOrganisationId) : null;
      $legel_form = isset($oid->legalForm) ? $oid->legalForm : null;
      $data['data'] = array(
        'uid' => formatUID($uid_ws),
        'uid_zahl' => $uid_ws,
        'alte_hr_id' => isset($old_hr_id->organisationId) && substr($old_hr_id->organisationId, 0, 2) == 'CH' ? $old_hr_id->organisationId : null,
        'name' => $oid->organisationName,
        'name_de' => $oid->organisationName,
    // TODO 'name_fr' => $ot->organisation->organisationIdentification->organisationName,
        'rechtsform_handelsregister' => $legel_form,
        'rechtsform' => _lobbywatch_ws_get_rechtsform($legel_form),
        'adresse_strasse' => $address->street . (isset($address->houseNumber) ? ' ' . $address->houseNumber : ''),
        'adresse_zusatz' => isset($address->addressLine1) ? $address->addressLine1 : (isset($address2->postOfficeBoxNumber) ? 'Postfach ' . $address2->postOfficeBoxNumber : null),
        'ort' => $address->town,
        'adresse_plz' => $address->swissZipCode,
        'land_iso2' => $address->country->countryIdISO2,
        'land_id' => _lobbywatch_ws_get_land_id($address->country->countryIdISO2),
    //     'handelsregister_url' => ,
        'register_kanton' => $ot->cantonAbbreviationMainAddress,
      );
    } else {
      $data['message'] .= 'Nothing found';
      $data['success'] = false;
    }
}

function fillDataFromZefixResult($object, &$data) {
    if (!empty((array) $object)) {
//       print_r($object);
      if (is_array($object->companyInfo)) {
        $ot = $object->companyInfo[0];
        $data['count'] = count($object->companyInfo);
      } else {
        $ot = $object->companyInfo;
        $data['count'] = 1;
      }
      $oid = $ot;
      $uid_ws = $oid->uid;
      if (isset($ot->address)) {
        $base_address = $ot->address;
        $address = is_array($base_address) ? $base_address[0]->addressInformation : $base_address->addressInformation;
        $address2 = is_array($base_address) && isset($base_address[1]->addressInformation) ? $base_address[1]->addressInformation : null;
      } else {
        $base_address = $address = $address2 = null;
      }
      $old_hr_id = isset($oid->chid) ? $oid->chid : null;
      $legel_form_handelsregister = isset($oid->legalform->legalFormUid) ? $oid->legalform->legalFormUid : null;
      $data['data'] = array(
        'uid' => formatUID($uid_ws),
        'uid_zahl' => $uid_ws,
        'alte_hr_id' => isset($old_hr_id) ? $old_hr_id : null,
        'name' => $oid->name,
        'name_de' => $oid->name,
    // TODO 'name_fr' => $ot->organisation->organisationIdentification->organisationName, TODO
        'rechtsform_handelsregister' => $legel_form_handelsregister,
        'rechtsform' => _lobbywatch_ws_get_rechtsform($legel_form_handelsregister),
        'rechtsform_zefix' => isset($oid->legalform->legalFormId) ? $oid->legalform->legalFormId : null,
        'adresse_strasse' => isset($address->street) ? ($address->street . (isset($address->houseNumber) ? ' ' . $address->houseNumber : '')) : null,
        'adresse_zusatz' => isset($address->addressLine1) ? $address->addressLine1 : (isset($address2->postOfficeBoxNumber) ? 'Postfach ' . $address2->postOfficeBoxNumber : null),
        'ort' => isset($address->town) ? $address->town : null,
        'adresse_plz' => isset($address->swissZipCode) ? $address->swissZipCode : null,
        'land_iso2' => isset($address->country) ? $address->country : null,
        'land_id' => isset($address->country) ? _lobbywatch_ws_get_land_id($address->country) : null,
        'handelsregister_url' => isset($ot->webLink) ? $ot->webLink : null,
        'handelsregister_ws_url' => isset($ot->wsLink) ? $ot->wsLink : null,
        'zweck' => isset($ot->purpose) ? $ot->purpose : null,
        'register_kanton' => getCantonCodeFromZefixRegistryId($ot->registerOfficeId),
      );
    } else {
      $data['message'] .= 'Nothing found';
      $data['success'] = false;
    }
}

 /**
   * Generiert Prüfsumme nach ESR-Verfahren.
   *
   * Used in old Handelsregister ID.
   *
   * <ol>
   * <li> Beginne mit der höchstwertigen Ziffer (links) und dem Übertrag 0.
   * </li>
   * <li> Wiederhole für jede Ziffer: Aus jeder Ziffer (Spalte) und dem letzten
   * Übertrag (Zeile) entnimm der Tabelle den nächsten Übertrag. </li>
   * <li> Die Ergänzung des entnommenen Übertrags der letzten Ziffer zu 10
   * ergibt die Prüfziffer. </li>
   * <li> Ergänzung 10 ergibt 0.</li>
   * </ol>
   *
   * Ref https://www.e-service.admin.ch/wiki/display/firmenidentifikation/Home
   *
   * @param nbr
   *          Zahl, für die die Prüfsumme gerechnet werden soll. Zahl muss als
   *          String übergeben werden.
   */
function getESRChecksum($nbr) {
  $checksum = 0;
  $uebertrag = 0;
  $table = array(
    array( 0, 9, 4, 6, 8, 2, 7, 1, 3, 5 ),
    array( 9, 4, 6, 8, 2, 7, 1, 3, 5, 0 ),
    array( 4, 6, 8, 2, 7, 1, 3, 5, 0, 9 ),
    array( 6, 8, 2, 7, 1, 3, 5, 0, 9, 4 ),
    array( 8, 2, 7, 1, 3, 5, 0, 9, 4, 6 ),
    array( 2, 7, 1, 3, 5, 0, 9, 4, 6, 8 ),
    array( 7, 1, 3, 5, 0, 9, 4, 6, 8, 2 ),
    array( 1, 3, 5, 0, 9, 4, 6, 8, 2, 7 ),
    array( 3, 5, 0, 9, 4, 6, 8, 2, 7, 1 ),
    array( 5, 0, 9, 4, 6, 8, 2, 7, 1, 3 ) );

  // loop over digits of the given string, start on the left side
  $numberLength = strlen($nbr);
  for ($i = 0; $i < $numberLength; $i++) {
    $currentDigit = substr($nbr, $i, 1);
//     print($currentDigit);
    $uebertrag = $table[$currentDigit][$uebertrag];
  }

  // 10 -> 0
  // wenn uebertrag = 0, dann waere checksun 10, daraus ergibt sich 0,
  if ($uebertrag != 0) {
    $checksum = 10 - $uebertrag;
  }

  // return result as string
  return $checksum;
}

function formatUID($uid_raw) {
  $matches = array();
  if (is_numeric($uid_raw) && strlen($uid_raw) == 8) {
    $check_digit = _lobbywatch_calculate_uid_check_digit($uid_raw);
    $uid_raw .= $check_digit;
    $uid = formatUIDnumber($uid_raw);
  } else if (preg_match('/^CHE-(\d{3}[.]\d{3}[.]\d{2})$/', $uid_raw, $matches)) {
    $uid_raw = str_replace('.', '', $matches[1]);
    $check_digit = _lobbywatch_calculate_uid_check_digit($uid_raw);
    $uid_raw .= $check_digit;
    $uid = formatUIDnumber($uid_raw);
  } else if (is_numeric($uid_raw) && strlen($uid_raw) == 9) {
    $uid = formatUIDnumber($uid_raw);
  } else if (preg_match('/^CHE(\d{9})$/',$uid_raw, $matches)) {
    $uid = formatUIDnumber($matches[1]);
  } else if (preg_match('/^CHE-\d{3}[.]\d{3}[.]\d{3}$/',$uid_raw, $matches)) {
    $uid = $matches[0];
  } else {
    //throw new Exception("Not an UID: $uid_raw");
    $uid = null;
  }
  return $uid;
}

function getUIDnumber($uid_raw) {
  $matches = array();

  if (is_numeric($uid_raw) && strlen($uid_raw) == 9) {
    return $uid_raw;
  }

  $formatted_uid = formatUID($uid_raw);
  if (preg_match('/^CHE-(\d{3})[.](\d{3})[.](\d{3})$/', $formatted_uid, $matches)) {
    $uid = $matches[1] . $matches[2] . $matches[3];
  } else {
    //throw new Exception("Not an UID: $uid_raw");
    $uid = null;
  }

  return $uid;
}

function formatUIDnumber($uid_number) {
  if (!is_numeric($uid_number) || strlen($uid_number) != 9) {
    throw new Exception("Not an UID number: $uid_number");
  }
  return 'CHE-' . substr($uid_number, 0, 3) . '.' . substr($uid_number, 3, 3) . '.' . substr($uid_number, 6, 3);
}

function formatOldHandelsregisterID($old_hr_id_raw) {
  $matches = array();
  // TODO check
  if (is_numeric($old_hr_id_raw) && strlen($old_hr_id_raw) == 10) {
    $old_hr_id = 'CH' . $old_hr_id_raw . getESRChecksum($old_hr_id_raw);
  } else if (is_numeric($old_hr_id_raw) && strlen($old_hr_id_raw) == 11) {
    $old_hr_id = 'CH' . $old_hr_id_raw;
  } else if (preg_match('/^CH\d{11}$/',$old_hr_id_raw)) {
    $old_hr_id = $old_hr_id_raw;
  } else if (preg_match('/^(CH)[-]?(\d{3})[.-]?(\d)[.-]?(\d{3})[.-]?(\d{3})[.-]?(\d)$/',$old_hr_id_raw, $matches)) {
    $old_hr_id = $matches[1] . $matches[2] .$matches[3] .$matches[4] .$matches[5] .$matches[6];
  } else {
//     throw new Exception("Not an HR-ID: $old_hr_id_raw");
    $old_hr_id = null;
  }
  return $old_hr_id;
}

function getCantonCodeFromZefixRegistryId($id) {
  $canton = null;
  switch ($id) {
    case 20: return 'ZH';
    case 36: return 'BE';
    case 100: return 'LU';
    case 120: return 'UR';
    case 130: return 'SZ';
    case 140: return 'OW';
    case 150: return 'NW';
    case 160: return 'GL';
    case 170: return 'ZG';
    case 217: return 'FR';
    case 241: return 'SO';
    case 270: return 'BS';
    case 280: return 'BL';
    case 290: return 'SH';
    case 200: return 'AR';
    case 310: return 'AI';
    case 320: return 'SG';
    case 350: return 'GR';
    case 400: return 'AG';
    case 440: return 'TG';
    case 501: return 'TI';
    case 550: return 'VD';
    case 600: return 'VS';
    case 621: return 'VS';
    case 626: return 'VS';
    case 645: return 'NE';
    case 660: return 'GE';
    case 670: return 'JU';
    default: return null;
  }
}

/**
 * @return true: db values different, not overwritten, false: no difference
 */
function checkField($field, $field_ws, $parlamentarier_db_obj, $parlamentarier_ws, &$update, &$update_optional, &$fields, $mode = FIELD_MODE_OPTIONAL, $id_function = null, $updated_date_field = null, $db_normalize_function = null) {
  global $verbose;

  // ----------------------------------------------------------
  // DO NOT FORGET TO ADD NEW DB FIELDS TO SELECT IN syncParlamentarier()
  // ----------------------------------------------------------

  if ($verbose >= 9) {
    $max_output_length = 100000;
  } else if ($verbose > 5) {
    $max_output_length = 1000;
  } else if ($verbose > 2) {
    $max_output_length = 100;
  } else if ($verbose > 1) {
    $max_output_length = 25;
  } else if ($mode == FIELD_MODE_OVERWRITE_MARK_LOG) {
    $max_output_length = 20;
  } else {
    $max_output_length = 10;
  }

  $db_val_raw = isset($parlamentarier_db_obj->$field) ? $parlamentarier_db_obj->$field : null;
  if ($db_normalize_function != null) {
    $db_val = $db_normalize_function($db_val_raw, $parlamentarier_db_obj, $field, $fields);
  } else {
    $db_val = $db_val_raw;
  }

  $val_raw = !empty($parlamentarier_ws->$field_ws) ? $parlamentarier_ws->$field_ws : null;
  $is_date = !is_array($val_raw) && /*isset($parlamentarier_db_obj->field) && is_string($db_val) &&*/ preg_match('/^\d{4}-\d{2}-\d{2}/', $val_raw);
  if ($is_date) {
    $val = substr($val_raw, 0, 10);
  } elseif ($id_function != null) {
    $val = $id_function($val_raw, $parlamentarier_ws, $field_ws, $parlamentarier_db_obj, $field, $fields);
  } elseif (is_array($val_raw)) {
    $val = implode(', ', $val_raw);
  } else {
    $val = $val_raw;
  }

  // TODO enhance to support also dates with time
  if ((isset($val) && (empty($db_val) || ($db_val != $val && !starts_with($val, 'STR_TO_DATE(')) || (is_string($db_val) && "STR_TO_DATE('{$db_val}','%Y-%m-%d')" != $val && starts_with($val, 'STR_TO_DATE('))))
      || ($mode == FIELD_MODE_OVERWRITE_NULL && is_null($val) && isset($db_val)))  {
    $msg = $verbose || $mode == FIELD_MODE_OVERWRITE_MARK_LOG ? " (" . (isset($db_val) ? cut($db_val, $max_output_length) . " → " : '') . (isset($val) ? cut($val, $max_output_length) : 'null') .  ")" : '';
    if ($mode == FIELD_MODE_OPTIONAL && !empty($db_val)) {
      $fields[] = "[{$field}{$msg}]";
      add_field_to_update($parlamentarier_db_obj, $field, $val, $update_optional, $updated_date_field);
      return true;
    } else if ((($mode == FIELD_MODE_OVERWRITE || $mode == FIELD_MODE_OVERWRITE_MARK ||  $mode == FIELD_MODE_OVERWRITE_NULL || $mode == FIELD_MODE_OVERWRITE_MARK_LOG) && (!empty($db_val) || !empty($val))) || (($mode == FIELD_MODE_ONLY_NEW || $mode == FIELD_MODE_OPTIONAL) && empty($db_val))) {
      $mark = ($mode == FIELD_MODE_OVERWRITE_MARK || $mode == FIELD_MODE_OVERWRITE_NULL || $mode == FIELD_MODE_OVERWRITE_MARK_LOG) && isset($db_val) ? '**' : '';

      $fields[] = "$mark{$field}{$msg}$mark";
      add_field_to_update($parlamentarier_db_obj, $field, $val, $update, $updated_date_field);
      return true;
    }
  }
  return false;
}

function add_field_to_update($parlamentarier_db_obj, $field, $val, &$update, $updated_date_field) {
global $today;
global $sql_today;
global $transaction_date;
global $sql_transaction_date;
global $errors;

// Check for !empty($parlamentarier_db_obj->$field) for new DB entries
//   df($parlamentarier_db_obj, '$parlamentarier_db_obj');
//   df($field, '$field');
//   df($val, '$val');
//   df($parlamentarier_db_obj->$field, '$parlamentarier_db_obj->$field');

  $db_val = $parlamentarier_db_obj->$field;

  if ($val == null) {
    $update[$field] = "$field = NULL";
  } elseif ((!empty($db_val) && is_int($db_val)) || starts_with($val, 'STR_TO_DATE(')) {
    $update[$field] = "$field = $val";
  } elseif (isset($val) && (is_array($val) || is_object($val))) {
    $update[$field] = "$field = '" . escape_string(json_encode($val, JSON_UNESCAPED_UNICODE)) . "'";
    if (json_last_error() != 0) {
      $errors[] = 'json_encode ERROR: ' . json_last_error() . ', ' . json_last_error_msg() . ", id=" . $parlamentarier_db_obj->id . " '" . $val . "'";
    }
  } else {
    $update[$field] = "$field = '" . escape_string($val) . "'";
  }
  if ($updated_date_field) {
    $update[$updated_date_field] = "$updated_date_field = $sql_transaction_date";
  }
}

function _lobbywatch_fetch_organisation_title($table, $id) {
  $title = '';

  $lang_suffix = get_lang_suffix();

  // Use the database we set up earlier
  // Ref: https://drupal.org/node/18429
  $old_db = db_set_active('lobbywatch');

  try {
    // i18n anzeige_name
    $sql = "SELECT id, name_de, name_fr, ort FROM v_$table WHERE id=:id";
    $result = db_query($sql, array(':id' => check_plain($id)));

    if ($result) {
      $record = $result->fetchAssoc();
      $title = translate_record_field($record, 'name_de') . ($record['ort'] ? ', ' . $record['ort'] : '');
    }
    //     df($record, '$record');
    //     df($url_name, '$url_name');
  } finally {
    // Go back to the default database,
    // otherwise Drupal will not be able to access it's own data later on.
    db_set_active($old_db);
  }
  return $title;
}

function _lobbywatch_clean_for_url($name){
  $url_name = preg_replace('/[.,]/i', '', $name);
  $url_name = preg_replace('/[\/]/i', '-', $url_name);
  return check_plain($url_name);
}

function _lobbywatch_anzeige_name_for_url($table, $id, $lang = null) {
  return _lobbywatch_clean_for_url(_lobbywatch_fetch_anzeige_name($table, $id, $lang));
}

function fillZutrittsberechtigterEmail($i) {
  global $zbList;
  global $emailEndZb;
  global $mailtoZb;
  global $emailIntroZb;
  global $rowCellStylesZb;
  global $rowData;

  if (isset($zbList[$i])) {
    $lang = $zbList[$i]['arbeitssprache'];
    $oldlang = lobbywatch_set_language($lang);
    $lang_suffix = get_lang_suffix($lang);

    $state = '<table style="margin-top: 1em; margin-bottom: 1em;">
                    <tr><td style="padding: 16px; '. $rowCellStylesZb[$i]['id'] . '" title="Status des Arbeitsablaufes dieses Zutrittsberechtigten">Arbeitsablauf</td><td style="padding: 16px; ' . (!empty($rowCellStylesZb[$i]['nachname']) ? $rowCellStylesZb[$i]['nachname'] : '') . '" title="Status der Vollständigkeit der Felder dieses Zutrittsberechtigten">Vollständigkeit</td></tr></table>';
    $res = array(
        'Id'  => $zbList[$i]['id'],
        'Title' => 'Vorschau: ' . $zbList[$i]["zutrittsberechtigung_name"],
        'State' =>  $state,
        'Preview' =>  '<p>Zutrittsberechtigung von '. $rowData["parlamentarier_name2"] . '<br><b>Funktion</b>: ' . $zbList[$i]['funktion'] . '<br><b>Beruf</b>: ' . $zbList[$i]['beruf'] . '</p>' . '<h4>Mandate</h4><ul>' . $zbList[$i]['mandate'] . '</ul>',
        'EmailTitle' => 'Autorisierungs-E-Mail: ' . '<a href="' . $mailtoZb[$i]. '" target="_blank">' . $zbList[$i]["zutrittsberechtigung_name"] . '</a>',
        'EmailText' => '<div>' . $zbList[$i]['anrede'] . '' .  $emailIntroZb[$i] . '' . (isset($zbList[$i]['funktion']) ? '<br><b>' . lt('Deklarierte Funktion:') . '</b> ' . $zbList[$i]['funktion'] . '' : '') . (isset($zbList[$i]['beruf']) ? '<br><b>' . lt('Beruf:') . '</b> ' . translate_record_field($zbList[$i], 'beruf', false, true) . '' : ''). '<br><br><b>' . lt('Ihre Tätigkeiten:') . '</b><br>' . ($zbList[$i]['mandate'] ? '<ul>' . $zbList[$i]['mandate'] . '</ul>' : lt('keine') . '<br>') . /*$zbList[$i]['organisationsbeziehungen'] .*/
        '' . $emailEndZb[$i] . '</div>',
        // '<p><b>Mandate</b> Ihrer Gäste:<p>' . gaesteMitMandaten($con, $id, true)
        'MailTo' => $mailtoZb[$i],
        'ParlamentarierName' => $rowData["parlamentarier_name"]
    );

    // Reset language
    lobbywatch_set_language($oldlang);

  } else {
    $res = array();
  }
  return $res;
}

function organisationsbeziehungen($con, $organisationen_id_comma_list, $for_email = false, $check_unpublished = true) {
//   df($organisationen_id_comma_list);
  $admin = false;
  $num_arbeitet_fuer = $admin ? 2 : 0;
  $num_tochtergesellschaft_von = $admin ? 3 : 0;

  $organistionen_ids = explode(',', $organisationen_id_comma_list);

  $found = false;


  $inner_markup = '';

  foreach ($organistionen_ids as $organisation_id) {

      $sql = "SELECT organisation.id, organisation.freigabe_datum_unix, organisation.lobbyeinfluss, organisation.anzahl_interessenbindung_hoch, organisation.anzahl_interessenbindung_mittel, organisation.anzahl_interessenbindung_tief, organisation.anzahl_interessenbindung_hoch_nach_wahl, organisation.anzahl_interessenbindung_mittel_nach_wahl, organisation.anzahl_interessenbindung_tief_nach_wahl, organisation.anzahl_mandat_hoch, organisation.anzahl_mandat_mittel, organisation.anzahl_mandat_tief, organisation.rechtsform, " . _lobbywatch_get_rechtsform_translation_SQL('organisation') . " as rechtsform_translated, organisation.ort, organisation.anzeige_name_de, organisation.anzeige_name_fr, "
  ." CONCAT('<li>', " . lobbywatch_lang_field('organisation.name_de') . ",
    IF(FALSE AND (organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = ''), '', CONCAT(', ', ". _lobbywatch_get_rechtsform_translation_SQL("organisation") . ")),
    IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort))
) organisation, "
  // . _lobbywatch_get_workflow_state_SELECT_SQL('organisation') . " "
  . _lobbywatch_organisation_beziehung_SELECT_SQL('arbeitet_fuer', $num_arbeitet_fuer, false) . ", "
  // . _lobbywatch_organisation_beziehung_SELECT_SQL('auftraggeber_fuer', $num_arbeitet_fuer)  . ", "
  // . _lobbywatch_organisation_beziehung_SELECT_SQL('mitglied_von', $num_mitglied_von) . ", "
  // . _lobbywatch_organisation_beziehung_SELECT_SQL('mitglieder', $num_mitglied_von)  . ", "
  . _lobbywatch_organisation_beziehung_SELECT_SQL('tochtergesellschaften', $num_tochtergesellschaft_von, false) . ", "
  . _lobbywatch_organisation_beziehung_SELECT_SQL('muttergesellschaften', $num_tochtergesellschaft_von, false)  // . ", "
  // . _lobbywatch_organisation_beziehung_SELECT_SQL('partner', $num_partner) . ", "
  // . _lobbywatch_organisation_beziehung_SELECT_SQL('beteiligungen_an', $num_beiteiligung_an) . ", "
  // . _lobbywatch_organisation_beziehung_SELECT_SQL('beteiligungen_von', $num_beiteiligung_an) . ", "
  // . _lobbywatch_verbundene_parlamentarier_SELECT_SQL(false) //. ", "
  . "
    FROM v_organisation organisation "
  //     . _lobbywatch_verbundene_parlamentarier_FROM_SQL()
      . _lobbywatch_organisation_beziehung_FROM_SQL('organisation', 'arbeitet fuer', true, $num_arbeitet_fuer, 'arbeitet_fuer', 'auftraggeber_fuer')
  //     . _lobbywatch_organisation_beziehung_FROM_SQL('organisation', 'mitglied von', true, $num_mitglied_von, 'mitglied_von', 'mitglieder')
      . _lobbywatch_organisation_beziehung_FROM_SQL('organisation', 'tochtergesellschaft von', true, $num_tochtergesellschaft_von, 'muttergesellschaften', 'tochtergesellschaften')
  //     . _lobbywatch_organisation_beziehung_FROM_SQL('organisation', 'partner von', false, $num_partner, 'partner', 'partner_reverse')
  //     . _lobbywatch_organisation_beziehung_FROM_SQL('organisation', 'beteiligt an', true, $num_beiteiligung_an, 'beteiligungen_an', 'beteiligungen_von')
    . "
    WHERE organisation.id IN (:ids) "
         . ($check_unpublished && !user_access('access lobbywatch unpublished content') ? ' AND organisation.freigabe_datum <= NOW() ' : '') . "
    GROUP BY organisation.id";

//     df($sql, 'sql');
    $result = lobbywatch_forms_db_query($sql, array(':ids' => $organisation_id), array('fetch' => PDO::FETCH_ASSOC))->fetch();
//     df($result, 'result');

    $inner_markup .= print_organisation_beziehung($result, lt('arbeitet für'), 'arbeitet_fuer', $num_arbeitet_fuer);
    $inner_markup .= print_organisation_beziehung($result, lt('Tochtergesellschaften'), 'tochtergesellschaften', $num_tochtergesellschaft_von);
    $inner_markup .= print_organisation_beziehung($result, lt('Muttergesellschaften'), 'muttergesellschaften', $num_tochtergesellschaft_von);
  }
  if ($inner_markup) {
    $markup = '<b>' . lt('Mit diesen Organisationen sind die oben erwähnten Institutionen verbunden:') . '</b>'
      . '<ul>'
      . $inner_markup
      . '</ul>';
  } else {
    $markup = '';
  }
  return $markup;
}

function print_organisation_beziehung($record, $relation, $field_name_base, $transitiv_num) {
  $lang = get_lang();
  $lang_suffix = get_lang_suffix();
  $admin = user_access('access lobbywatch admin');
  $adminBool = $admin ? "1" : "0";

  $markup = '';
  if ($record["${field_name_base}_0"]) {
    $markup .= $record['organisation'] . " <b>$relation</b>"
    . '<ul>'
        . $record["${field_name_base}_0"]
        . '</ul>';
//     if ($admin) {
//       $markup .= "<div class='admin'>";
//       for($i = 1; $i <= $transitiv_num; $i++) {
//         if ($record["${field_name_base}_$i"]) {
//           $markup .= '<h4>'. lt('Transitiv') . ' ' . $i . '</h4>'
//               . '<ul>'
//                   . $record["${field_name_base}_$i"]
//                   . '</ul>';
//           } else {
//             break;
//           }
//       }
//       $markup .= "</div>";
//     }
  }
  return $markup;
}

function zutrittsberechtigteForParlamentarier($con, $parlamentarier_id, $for_email = false) {

  $sql = "SELECT zutrittsberechtigung.id, zutrittsberechtigung.person_id, zutrittsberechtigung.arbeitssprache FROM v_zutrittsberechtigung_simple_compat zutrittsberechtigung
          WHERE
  (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW())
  AND zutrittsberechtigung.parlamentarier_id=:id
GROUP BY zutrittsberechtigung.id;";

  //         df($sql);
  //         $eng_con->ExecQueryToArray($sql, $result);
  //          df($eng_con->LastError(), 'last error');
  //         $eng_con->Disconnect();
  //         df($result, 'result');
  //         $preview = $rowData['email_text_html'];

  //         $q = $con->query($sql);
  //         $result2 = $q->fetchAll();
  //         df($eng_con->LastError(), 'last error');
  //         df($q, 'q');
  //         df($result2, 'result2');

  //       $sth = $con->prepare($sql);
  //       $sth->execute(array(':id' => $id));
  $zbs = lobbywatch_forms_db_query($sql, array(':id' => $parlamentarier_id));

  $gaeste = array();

  $organisationsbeziehungen = array();

  foreach ($zbs as $zb) {
    $id = $zb->id;
    $lang = $zb->arbeitssprache;
    $oldlang = lobbywatch_set_language($lang);
    $lang_suffix = get_lang_suffix($lang);

    $sql = "SELECT zutrittsberechtigung.id, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.geschlecht, zutrittsberechtigung.funktion, zutrittsberechtigung.beruf, zutrittsberechtigung.beruf_fr, zutrittsberechtigung.email, zutrittsberechtigung.arbeitssprache, zutrittsberechtigung.nachname,
  GROUP_CONCAT(DISTINCT
      CONCAT('<li>', " . (!$for_email ? "IF(mandat.bis IS NOT NULL AND mandat.bis < NOW(), '<s>', ''), " : "") . lobbywatch_lang_field('organisation.name_de') . ",
      IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', " . (!$for_email ? "'<span class=\"preview-missing-data\">, Rechtsform fehlt</span>'" : "''") . ", CONCAT(', ', ". _lobbywatch_get_rechtsform_translation_SQL("organisation") . ")), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)),
      " . (!$for_email ?
          "', ', IF(mandat.beschreibung IS NULL OR TRIM(mandat.beschreibung) = '', " . _lobbywatch_bindungsart('zutrittsberechtigung', 'mandat', 'organisation') . ", CONCAT(mandat.beschreibung)),"
          . "IF(NOT mandat.hauptberuflich, '', CONCAT('<small class=\"desc\">, hauptberuflich</small>')),"
          : ""
        ) . "
      IF(mandat_jahr_grouped.jahr_grouped IS NULL OR TRIM(mandat_jahr_grouped.jahr_grouped) = '', '', CONCAT('<ul class=\"jahr\">', mandat_jahr_grouped.jahr_grouped, '</ul>')),
      IF(mandat.bis IS NOT NULL AND mandat.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(mandat.bis, '%Y'), '</s>'), ''))
      ORDER BY organisation.anzeige_name
      SEPARATOR ' '
  ) mandate,
  GROUP_CONCAT(DISTINCT
      IF(mandat.bis IS NULL OR mandat.bis > NOW(), CONCAT(organisation.id), '')
      ORDER BY organisation.anzeige_name
      SEPARATOR ','
  ) organisationen_from_mandate,
    CASE zutrittsberechtigung.geschlecht
      WHEN 'M' THEN CONCAT(" . lts('Sehr geehrter Herr') . ",' ', zutrittsberechtigung.nachname)
      WHEN 'F' THEN CONCAT(" . lts('Sehr geehrte Frau') . ",' ', zutrittsberechtigung.nachname)
      ELSE CONCAT(" . lts('Sehr geehrte(r) Herr/Frau') . ",' ', zutrittsberechtigung.nachname)
  END anrede
  FROM v_zutrittsberechtigung_simple_compat zutrittsberechtigung
  LEFT JOIN v_mandat_simple mandat
    ON mandat.person_id = zutrittsberechtigung.id " . ($for_email ? 'AND mandat.bis IS NULL' : '') . "
  LEFT JOIN (
      SELECT mandat_jahr.mandat_id, GROUP_CONCAT(DISTINCT CONCAT('<li>', mandat_jahr.jahr, ': ', mandat_jahr.verguetung, ' CHF ', IF(mandat_jahr.beschreibung IS NULL OR TRIM(mandat_jahr.beschreibung) = '', '', CONCAT('<small class=\"desc\">, &quot;', mandat_jahr.beschreibung, '&quot;</small>'))) ORDER BY mandat_jahr.jahr SEPARATOR ' ') jahr_grouped
      FROM `v_mandat_jahr` mandat_jahr
      GROUP BY mandat_jahr.mandat_id) mandat_jahr_grouped
    ON mandat.id = mandat_jahr_grouped.mandat_id
  LEFT JOIN v_organisation_simple organisation
    ON mandat.organisation_id = organisation.id
  WHERE
    zutrittsberechtigung.id=:id
  GROUP BY zutrittsberechtigung.id;";

    //     df($sql, 'sql');

    $res = array();
    $sth = $con->prepare($sql);
    $sth->execute(array(':id' => $id));
    $gast = $sth->fetchAll();

//     df($gast, 'gast');


    $gast[0]['organisationsbeziehungen'] = organisationsbeziehungen($con, $gast[0]["organisationen_from_mandate"]);

    $gaeste = array_merge($gaeste, $gast);

    $gaesteMitMandaten = '';

    // Reset language
    lobbywatch_set_language($oldlang);
  }

  if (!$gaeste) {
    $gaesteMitMandaten = '<p>' . lt('keine') . '</p>';
    //      throw new Exception('Parlamentarier ID not found');
  } else {
    foreach($gaeste as $gast) {
      $gaesteMitMandaten .= '<h5>' . $gast['zutrittsberechtigung_name'] . '</h5>';
      //$gaesteMitMandaten .= mandateList($con, $gast['id']);
      $gaesteMitMandaten .= "<ul>\n" . $gast['mandate'] . "\n</ul>";
    }
  }

  $res['gaesteMitMandaten'] = $gaesteMitMandaten;
  $res['zutrittsberechtigte'] = $gaeste;

  return $res;
}

function get_parlamentarier_id_for_zutrittsberechtige_person($con, $id) {
  $sql = "SELECT parlamentarier.id
  FROM zutrittsberechtigung zb
    JOIN v_parlamentarier_simple parlamentarier ON zb.parlamentarier_id = parlamentarier.id
  WHERE zb.person_id=:id
  ;";

  $obj = lobbywatch_forms_db_query($sql, array(':id' => $id))->fetch();
  if (!$obj) {
    throw new Exception("ID not found '$id'");
  }
  $pid = $obj->id;
  return $pid;
}

function get_parlamentarier_lang($con, $id) {
    $sql = "SELECT parlamentarier.arbeitssprache FROM v_parlamentarier_simple parlamentarier
          WHERE
  parlamentarier.id=:id;";

//         df($sql);
//         $eng_con->ExecQueryToArray($sql, $result);
//          df($eng_con->LastError(), 'last error');
//         $eng_con->Disconnect();
//         df($result, 'result');
//         $preview = $rowData['email_text_html'];

//         $q = $con->query($sql);
//         $result2 = $q->fetchAll();
//         df($eng_con->LastError(), 'last error');
//         df($q, 'q');
//         df($result2, 'result2');

//       $sth = $con->prepare($sql);
//       $sth->execute(array(':id' => $id));
      $obj = lobbywatch_forms_db_query($sql, array(':id' => $id))->fetch();
      if (!$obj) {
        throw new Exception("ID not found '$id'");
      }
      $lang = $obj->arbeitssprache;
      return $lang;
}

function get_parlamentarier($con, $id) {
      $result = array();
      $sql = "SELECT parlamentarier.id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.name as parlamentarier_name2, parlamentarier.email, parlamentarier.geschlecht, parlamentarier.beruf, parlamentarier.beruf_fr, parlamentarier.eingabe_abgeschlossen_datum, parlamentarier.kontrolliert_datum, parlamentarier.freigabe_datum, parlamentarier.autorisierung_verschickt_datum, parlamentarier.autorisiert_datum, parlamentarier.kontrolliert_visa, parlamentarier.eingabe_abgeschlossen_visa, parlamentarier.im_rat_bis, parlamentarier.sitzplatz, parlamentarier.geburtstag, parlamentarier.im_rat_bis, parlamentarier.kleinbild, parlamentarier.parlament_biografie_id, parlamentarier.arbeitssprache, parlamentarier.aemter, parlamentarier.weitere_aemter, parlamentarier.parlament_interessenbindungen, parlamentarier.parlament_interessenbindungen_updated, DATE_FORMAT(parlament_interessenbindungen_updated, '%d.%m.%Y') as parlament_interessenbindungen_updated_formatted,
GROUP_CONCAT(DISTINCT
    CONCAT('<li>',
    IF(interessenbindung.bis IS NOT NULL AND interessenbindung.bis < NOW(), '<s>', ''),
    organisation.anzeige_name,
    IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '<span class=\"preview-missing-data\">, Rechtsform fehlt</span>', CONCAT(', ', ". _lobbywatch_get_rechtsform_translation_SQL("organisation") . ")),
    IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ',
    " . _lobbywatch_bindungsart('parlamentarier', 'interessenbindung', 'organisation') . ", ' [',
    CONCAT(UCASE(LEFT(interessenbindung.art, 1)), SUBSTRING(interessenbindung.art, 2)),
    IF(interessenbindung.funktion_im_gremium IS NULL OR TRIM(interessenbindung.funktion_im_gremium) = '', '', CONCAT(', ',CONCAT(UCASE(LEFT(interessenbindung.funktion_im_gremium, 1)), SUBSTRING(interessenbindung.funktion_im_gremium, 2)))), ']',
    IF(interessenbindung.beschreibung IS NULL OR TRIM(interessenbindung.beschreibung) = '', '', CONCAT('<small class=\"desc\">, &quot;', interessenbindung.beschreibung, '&quot;</small>')),
    IF(NOT interessenbindung.hauptberuflich, '', CONCAT('<small class=\"desc\">, hauptberuflich</small>')),
    ' <small class=\"desc\">(', interessenbindung.id, ')</small>',
    IF(interessenbindung_jahr_grouped.jahr_grouped IS NULL OR TRIM(interessenbindung_jahr_grouped.jahr_grouped) = '', '', CONCAT('<ul class=\"jahr\">', interessenbindung_jahr_grouped.jahr_grouped, '</ul>')),
    IF(interessenbindung.bis IS NOT NULL AND interessenbindung.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(interessenbindung.bis, '%Y'), '</s>'), '')
    )
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
) interessenbindungen,
GROUP_CONCAT(DISTINCT
    IF(interessenbindung.bis IS NULL OR interessenbindung.bis > NOW(), CONCAT('<li>', " . lobbywatch_lang_field('organisation.name_de') . ",
    IF(FALSE AND (organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = ''), '', CONCAT(', ', ". _lobbywatch_get_rechtsform_translation_SQL("organisation") . ")),
    IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ',
    IF(" . lobbywatch_lang_field('interessenbindung.beschreibung') . " IS NULL OR TRIM(" . lobbywatch_lang_field('interessenbindung.beschreibung') . ") = '', " . _lobbywatch_bindungsart('parlamentarier', 'interessenbindung', 'organisation') . ", CONCAT(" . lobbywatch_lang_field('interessenbindung.beschreibung') . "))
    ), '')
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
) interessenbindungen_for_email,
GROUP_CONCAT(DISTINCT
    IF(interessenbindung.bis IS NULL OR interessenbindung.bis > NOW(), CONCAT(organisation.id), '')
    ORDER BY organisation.anzeige_name
    SEPARATOR ','
) organisationen_from_interessenbindungen,
GROUP_CONCAT(DISTINCT
    CONCAT('<li>', IF(zutrittsberechtigung.bis IS NOT NULL AND zutrittsberechtigung.bis < NOW(), '<s>', '<!-- [VALID_Zutrittsberechtigung] -->'),
    zutrittsberechtigung.name,
    IF(zutrittsberechtigung.funktion IS NULL OR TRIM(zutrittsberechtigung.funktion) = '', ', <small><em>Funktion fehlt</em></small>', CONCAT(', ', zutrittsberechtigung.funktion)),
    IF(zutrittsberechtigung.beruf IS NULL OR TRIM(zutrittsberechtigung.beruf) = '', ', <small><em>Beruf fehlt</em></small>', CONCAT(', ', zutrittsberechtigung.beruf)),
    IF(zutrittsberechtigung.bis IS NOT NULL AND zutrittsberechtigung.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(zutrittsberechtigung.bis, '%Y'), '</s>'), '')
    )
  ORDER BY zutrittsberechtigung.name
  SEPARATOR ' '
) zutrittsberechtigungen,
GROUP_CONCAT(DISTINCT
    IF(zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW(), CONCAT('<li>',
    zutrittsberechtigung.name, ', ',
    zutrittsberechtigung.funktion,
    IF(zutrittsberechtigung.beruf IS NULL OR TRIM(zutrittsberechtigung.beruf) = '', '', CONCAT(', ', zutrittsberechtigung.beruf))
    ), '')
  ORDER BY zutrittsberechtigung.name
  SEPARATOR ' '
) zutrittsberechtigungen_for_email,
CASE parlamentarier.geschlecht
  WHEN 'M' THEN CONCAT(" . lts('Sehr geehrter Herr') . ",' ', parlamentarier.nachname)
  WHEN 'F' THEN CONCAT(" . lts('Sehr geehrte Frau') . ",' ', parlamentarier.nachname)
  ELSE CONCAT(" . lts('Sehr geehrte(r) Herr/Frau') . ",' ', parlamentarier.nachname)
END anrede,
GROUP_CONCAT(DISTINCT
    CONCAT('<li>',
    IF(in_kommission.bis IS NOT NULL AND in_kommission.bis < NOW(), '<s>', ''),
    in_kommission.name, ' (', in_kommission.abkuerzung, ') ',
    ', ', CONCAT(UCASE(LEFT(in_kommission.funktion, 1)), SUBSTRING(in_kommission.funktion, 2)),
    IF(in_kommission.bis IS NOT NULL AND in_kommission.bis < NOW(), CONCAT(', bis ', DATE_FORMAT(in_kommission.bis, '%Y'), '</s>'), '')
    )
    ORDER BY in_kommission.abkuerzung
    SEPARATOR ' '
) kommissionen
FROM v_parlamentarier_simple parlamentarier
LEFT JOIN v_interessenbindung_simple interessenbindung
  ON interessenbindung.parlamentarier_id = parlamentarier.id -- AND interessenbindung.bis IS NULL
LEFT JOIN (
    SELECT interessenbindung_jahr.interessenbindung_id, GROUP_CONCAT(DISTINCT CONCAT('<li>', interessenbindung_jahr.jahr, ': ', interessenbindung_jahr.verguetung, ' CHF ',
    IF(interessenbindung_jahr.beschreibung IS NULL OR TRIM(interessenbindung_jahr.beschreibung) = '', '', CONCAT('<small class=\"desc\">, &quot;', interessenbindung_jahr.beschreibung, '&quot;</small>')),
    IF(interessenbindung_jahr.quelle IS NULL OR TRIM(interessenbindung_jahr.quelle) = '', '', CONCAT('<small class=\"desc\">, ', interessenbindung_jahr.quelle, '</small>')),
    IF(interessenbindung_jahr.quelle_url IS NULL OR TRIM(interessenbindung_jahr.quelle_url) = '', '', CONCAT('<small class=\"desc\">, <a href=\"', interessenbindung_jahr.quelle_url, '\">',
    interessenbindung_jahr.quelle_url, '</a></small>'))
    ) ORDER BY interessenbindung_jahr.jahr SEPARATOR ' '
    ) jahr_grouped
    FROM `v_interessenbindung_jahr` interessenbindung_jahr
    GROUP BY interessenbindung_jahr.interessenbindung_id) interessenbindung_jahr_grouped
  ON interessenbindung.id = interessenbindung_jahr_grouped.interessenbindung_id
LEFT JOIN v_organisation_simple organisation
  ON interessenbindung.organisation_id = organisation.id
LEFT JOIN v_zutrittsberechtigung_simple_compat zutrittsberechtigung
  ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id -- AND zutrittsberechtigung.bis IS NULL
LEFT JOIN v_in_kommission_liste in_kommission
  ON in_kommission.parlamentarier_id = parlamentarier.id -- AND interessenbindung.bis IS NULL
WHERE
  parlamentarier.id=:id
GROUP BY parlamentarier.id;";

//         df($sql);
        $result = array();
//         $eng_con->ExecQueryToArray($sql, $result);
//          df($eng_con->LastError(), 'last error');
//         $eng_con->Disconnect();
//         df($result, 'result');
//         $preview = $rowData['email_text_html'];

//         $q = $con->query($sql);
//         $result2 = $q->fetchAll();
//         df($eng_con->LastError(), 'last error');
//         df($q, 'q');
//         df($result2, 'result2');

//       $sth = $con->prepare($sql);
//       $sth->execute(array(':id' => $id));
      $options = array(
        'fetch' => PDO::FETCH_BOTH, // for compatibility with existing code
      );
        $result = lobbywatch_forms_db_query($sql, array(':id' => $id), $options)->fetchAll();
//       df($sql, 'sql');
//       $result = $sth->fetchAll();

      if (!$result) {
//         df($eng_con->LastError());
        throw new Exception("ID not found '$id'");
      }
//     } finally {
//       // Connection will automatically be closed at the end of the request.
// //       $eng_con->Disconnect();
//     }

    $rowData = $result[0];
    return $rowData;
}

// SELECT seq1.rowid, seq1.id, seq1.log_id, seq1.parlament_interessenbindungen_normalized, seq2.rowid, seq2.id, seq2.log_id, seq2.parlament_interessenbindungen_normalized FROM
// (SELECT @rowid:=@rowid+1 as rowid, parlamentarier_log.*, REPLACE(REPLACE(REPLACE(REPLACE(parlament_interessenbindungen, '"', '\''), '\r\n', '\n'), '\r', '\n'), '</table>\n', '</table>') parlament_interessenbindungen_normalized
// FROM `parlamentarier_log`, (SELECT @rowid:=0) as init
// WHERE id=:id
// ORDER BY log_id DESC) seq1
// INNER JOIN
// (SELECT @rowid2:=@rowid2+1 as rowid, parlamentarier_log.*, REPLACE(REPLACE(REPLACE(REPLACE(parlament_interessenbindungen, '"', '\''), '\r\n', '\n'), '\r', '\n'), '</table>\n', '</table>') parlament_interessenbindungen_normalized
// FROM `parlamentarier_log`, (SELECT @rowid2:=0) as init
// WHERE id=:id
// ORDER BY log_id DESC) seq2
// ON seq1.rowid = seq2.rowid + 1
// WHERE seq1.parlament_interessenbindungen_normalized != seq2.parlament_interessenbindungen_normalized
// -- LIMIT 100
// ;

function get_parlamentarier_log_last_changed_parlament_interessenbindungen($con, $id) {
  $result = array();
  $sql = "SELECT parlamentarier.id parlamentarier_id, parlamentarier_log.*, REPLACE(REPLACE(REPLACE(parlamentarier_log.parlament_interessenbindungen, '\"', '\\''), '\\n', ''), '\\r', '') parlament_interessenbindungen_normalized
FROM parlamentarier LEFT OUTER JOIN `parlamentarier_log` ON parlamentarier.id = parlamentarier_log.id  AND REPLACE(REPLACE(REPLACE(parlamentarier.parlament_interessenbindungen, '\"', '\\''), '\\n', ''), '\\r', '') != REPLACE(REPLACE(REPLACE(parlamentarier_log.parlament_interessenbindungen, '\"', '\\''), '\\n', ''), '\\r', '')
WHERE parlamentarier.id=:id
ORDER BY log_id DESC
LIMIT 1;
";
//         df($sql);
  $result = array();
  $options = array(
    'fetch' => PDO::FETCH_BOTH, // for compatibility with existing code
  );
  $result = lobbywatch_forms_db_query($sql, array(':id' => $id), $options)->fetchAll();

  if (!$result) {
//         df($eng_con->LastError());
    throw new Exception("ID not found '$id'");
  }
  $rowData = $result[0];
  return $rowData;
}

function get_parlamentarier_transparenz($con, $id) {
      $result = array();
//       $sql = "SELECT parlamentarier.parlamentarier_id as id, parlamentarier.*
//       FROM v_parlamentarier_transparenz_calculated parlamentarier
//       WHERE parlamentarier.parlamentarier_id=:id;";

      $sql = "SELECT interessenbindung.parlamentarier_id as id,
COUNT(IF(interessenbindung.parlamentarier_id IS NOT NULL AND (interessenbindung.bis IS NULL OR interessenbindung.bis > NOW()), 1, NULL)) anzahl_interessenbindungen,
COUNT(IF(interessenbindung.parlamentarier_id IS NOT NULL AND (interessenbindung.bis IS NULL OR interessenbindung.bis > NOW()) AND interessenbindung.hauptberuflich, 1, NULL)) anzahl_hauptberufliche_interessenbindungen,
COUNT(IF(interessenbindung.parlamentarier_id IS NOT NULL AND (interessenbindung.bis IS NULL OR interessenbindung.bis > NOW()) AND NOT interessenbindung.hauptberuflich, 1, NULL)) anzahl_nicht_hauptberufliche_interessenbindungen,
COUNT(IF(interessenbindung.parlamentarier_id IS NOT NULL AND (interessenbindung.bis IS NOT NULL AND interessenbindung.bis < NOW()), 1, NULL)) anzahl_abgelaufene_interessenbindungen,
COUNT(IF(interessenbindung.parlamentarier_id IS NOT NULL, 1, NULL)) anzahl_interessenbindungen_alle,
COUNT(IF(interessenbindung.parlamentarier_id IS NOT NULL AND (interessenbindung.bis IS NULL OR interessenbindung.bis > NOW()) AND interessenbindung.verguetung IS NOT NULL, 1, NULL)) anzahl_erfasste_verguetungen,
COUNT(IF(interessenbindung.parlamentarier_id IS NOT NULL AND (interessenbindung.bis IS NULL OR interessenbindung.bis > NOW()) AND interessenbindung.verguetung IS NOT NULL AND interessenbindung.hauptberuflich, 1, NULL)) anzahl_erfasste_hauptberufliche_verguetungen,
COUNT(IF(interessenbindung.parlamentarier_id IS NOT NULL AND (interessenbindung.bis IS NULL OR interessenbindung.bis > NOW()) AND interessenbindung.verguetung IS NOT NULL AND NOT interessenbindung.hauptberuflich, 1, NULL)) anzahl_erfasste_nicht_hauptberufliche_verguetungen,
parlamentarier_transparenz.stichdatum,
parlamentarier_transparenz.verguetung_transparent
FROM v_parlamentarier_simple parlamentarier
-- LEFT JOIN v_interessenbindung_liste interessenbindung
LEFT JOIN v_interessenbindung_jahr_raw interessenbindung
  ON interessenbindung.parlamentarier_id = parlamentarier.id
LEFT JOIN v_parlamentarier_transparenz_last_stichdatum_all parlamentarier_transparenz ON parlamentarier_transparenz.parlamentarier_id = parlamentarier.id
WHERE
  parlamentarier.id=:id
GROUP BY parlamentarier.id;";

// COUNT(IF(interessenbindung.parlamentarier_id IS NOT NULL AND (interessenbindung.bis IS NULL OR interessenbindung.bis > NOW()), 1, NULL)) interessenbindungen_count,
// COUNT(IF(interessenbindung.parlamentarier_id IS NOT NULL AND (interessenbindung.bis IS NOT NULL AND interessenbindung.bis < NOW()), 1, NULL)) interessenbindungen_count_old,
// COUNT(IF(interessenbindung.parlamentarier_id IS NOT NULL, 1, NULL)) interessenbindungen_count_all,
// COUNT(IF(interessenbindung.parlamentarier_id IS NOT NULL AND (interessenbindung.bis IS NULL OR interessenbindung.bis > NOW()) AND interessenbindung.verguetung IS NOT NULL, 1, NULL)) verguetung_count,
// SUM(IF(interessenbindung.parlamentarier_id IS NOT NULL AND (interessenbindung.bis IS NULL OR interessenbindung.bis > NOW()), 1, 0)) interessenbindungen_sum,
// SUM(IF(interessenbindung.parlamentarier_id IS NOT NULL AND (interessenbindung.bis IS NOT NULL AND interessenbindung.bis < NOW()), 1, 0)) interessenbindungen_sum_old,
// SUM(IF(interessenbindung.parlamentarier_id IS NOT NULL, 1, 0)) interessenbindungen_sum_all,
// SUM(IF(interessenbindung.parlamentarier_id IS NOT NULL AND (interessenbindung.bis IS NULL OR interessenbindung.bis > NOW()) AND interessenbindung.verguetung IS NOT NULL, 1, 0)) verguetung_sum


//         df($sql);
  $result = array();
  $options = array(
    'fetch' => PDO::FETCH_BOTH, // for compatibility with existing code
  );
  $result = lobbywatch_forms_db_query($sql, array(':id' => $id), $options)->fetchAll();
//       df($sql, 'sql');
//       $result = $sth->fetchAll();

  if (!$result) {
//         df($eng_con->LastError());
    throw new Exception("ID not found '$id'");
  }
  $rowData = $result[0];
  return $rowData;
}

function create_dir_if_not_exists($path) {
  if (!file_exists($path)) {
    mkdir($path, 0777, true);
  }
}

function create_parent_dir_if_not_exists($filename) {
  create_dir_if_not_exists(dirname($filename));
}

/**
 * Convert " to ' in HTML.
 * Forms save it with " instead of '.
 */
function normalizeParlamentInteressenbindungen($str) {
  $normalized = str_replace(array("\r\n","\n","\r"), "\n", $str);
  $normalized = preg_replace('%<table border="0"><thead><tr><th>Name</th><th>Rechtsform</th><th><abbr title="Gremium">Gr.</abbr></th><th><abbr title="Funktion">F.</abbr></th></tr></thead>%',
      "<table border='0'><thead><tr><th>Name</th><th>Rechtsform</th><th><abbr title='Gremium'>Gr.</abbr></th><th><abbr title='Funktion'>F.</abbr></th></tr></thead>",
      $normalized);
  $normalized = str_replace("</table>\n", "</table>", $normalized);
  return $normalized;
}

function htmlDiffStyled($old, $new) {
  $styled = $diff_raw = htmlDiffTd($old, $new);
  $styled = preg_replace("%<(/?table|thead|/?tbody|/tr)>%i", "$0\n", $styled);
  $styled = preg_replace("%^\s(.*)\s*$%im", "$1", $styled);
  return $styled;
}

function htmlDiffTd($old, $new){
  $ret = '';
  $diff = diff(preg_split("/[\s]+/", $old), preg_split("/[\s]+/", $new));
  foreach ($diff as $k){
    if (is_array($k))
      $ret .= (!empty($k['d'])?"<!--del-->" . styleDel(implode(' ',$k['d'])) . "<!--/del--> ":'').
        (!empty($k['i'])?"<!--ins-->" . styleIns(implode(' ',$k['i'])) . "<!--/ins--> ":'');
    else $ret .= $k . ' ';
  }
  return $ret;
}

function styleIns($str) {
  $styled = $str;
  $styled = preg_replace("|</td>|i", "</i></td>", preg_replace("|<td>|i", "<td><i>", $styled));
  $styled = preg_replace("%<tr>%i", "<tr style='font-style: italic; color: blue;'>", $styled);
  return $styled;
}

function styleDel($str) {
  $styled = $str;
  $styled = preg_replace("|</td>|i", "</s></td>", preg_replace("|<td>|i", "<td><s>", $styled));
  $styled = preg_replace("%<tr>%i", "<tr style='text-decoration: line-through; color: red;'>", $styled);
  return $styled;
}

function getNotizenPlaceholder() {
  return "22.07.2019/name: Beispiel einer internen Notiz&#10;&#10;15.07.2019/name: Neue oben hinzufügen und eine Zeile Abstand zu letztem Eintrag&#10;&#10;10.07.2019/name: Interne Notizen dienen zur Erläuterung von Änderungen oder Daten.&#10;&#10;05.06.2018/name: Das ergibt eine Art Protokoll.";
}

// https://stackoverflow.com/questions/2791998/convert-dashes-to-camelcase-in-php
function camelize($input, $separator = '_', $capitalizeFirstCharacter = true) {
  $str = str_replace($separator, '', ucwords($input, $separator));

  if (!$capitalizeFirstCharacter) {
    $str = lcfirst($str);
  }

  return $str;
}