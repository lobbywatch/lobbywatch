<?php

include_once dirname(__FILE__) . '/build_date.php';
include_once dirname(__FILE__) . '/deploy_date.php';
include_once dirname(__FILE__) . '/version.php';

//Ref: http://stackoverflow.com/questions/834303/php-startswith-and-endswith-functions
function utils_startsWith($haystack, $needle)
{
  return $needle === "" || strpos($haystack, $needle) === 0;
}
//Ref: http://stackoverflow.com/questions/834303/php-startswith-and-endswith-functions
function utils_endsWith($haystack, $needle)
{
  return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
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
  if (preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $pwd)){
    return true;
  } else {
    // return false;
    throw new Exception("Password too weak!\n\nPassword must include numbers, special characters and lower and uppercase letters");
  }
}

// Ref: http://www.cafewebmaster.com/check-password-strength-safety-php-and-regex
function checkPasswordStrength($pwd) {
  $error = '';
  if( strlen($pwd) < 8 ) {
    $error .= "Password too short!\n";
  }

  if( strlen($pwd) > 20 ) {
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

function get_PDO_lobbywatch_DB_connection() {
  global $db_connection;
  global $db;
  if (empty($db)) {
    $db = new PDO('mysql:host=localhost;dbname=' . $db_connection['database'] . ';charset=utf8', $db_connection['reader_username'], $db_connection['reader_password'], array(PDO::ATTR_PERSISTENT => true));
    // Disable prepared statement emulation, http://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
  //   return "'XXX'";
  $art = "CASE $ib.art
  WHEN 'taetig' THEN " . lts('tätig') . "
  WHEN 'geschaeftsfuehrend' THEN " . lts('geschäftsführend') . "
  ELSE $ib.art
  END";

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

/**
 * Get value of field depending on language, fallback to de if not in translated language available.
 *
 * @param array $record the array where the localized fields are available
 * @param string $basefield_name the field name of the German field, either name or name_de
 * @param string $langcode lang ISO code
 * @return either localized field content
 */
function translate_record_field($record, $basefield_name, $langcode = null) {
  global $language;

  // Merge in default.
  if (!isset($langcode)) {
    $langcode = isset($language->language) ? $language->language : 'de';
  }

  $locale_field_name = preg_replace('/_de$/u', '', $basefield_name) . "_$langcode";

  if ($langcode == 'de') {
    return $record[$basefield_name];
  } else {
    // if translation is missing, fallback to default ('de')
    return !empty($record[$locale_field_name]) ? $record[$locale_field_name] : $record[$basefield_name];
  }
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
      $sql = "SELECT id, key_name, value
          FROM v_settings settings
          -- WHERE settings.key_name=:key";

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
