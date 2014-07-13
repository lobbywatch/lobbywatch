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

function dt($msg, $text = null) {
  global $debug;
  if ($debug !== true)
    return;
  if (is_array($msg) || is_object($msg)) {
    $msg = print_r($msg, true);
  }
  print ("<p style='color:red;'>" . (is_string($text) ? $text . ' => ' : '') . "$msg</p>") ;
}
function dtXXX($msg) {
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
function search_objects($objects, $key, $value) {
  $return = array();
  foreach ($objects as $object) {
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
