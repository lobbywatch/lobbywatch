<?php
/**
 * @file
 * Callback page that serves custom Data requests on a Drupal installation.
 */

/*
  # Rewrite Data callback URLs of the form data.php?q=x.
  RewriteCond %{REQUEST_URI} ^\/([a-z]{2}\/)?data\/.*
  RewriteRule ^(.*)$ data.php?q=$1 [L,QSA]
  RewriteCond %{QUERY_STRING} (^|&)q=((\/)?[a-z]{2})?(\/)?data\/.*
  RewriteRule .* data.php [L]
 */

/**
 * @var The Drupal root.
 */
define('DRUPAL_ROOT', getcwd());

/**
 * @name Return constants, copies from menu.inc.
 * @{
 * The constants are copied to be able to drop the menu.inc depdendency.
 */
define('JS_MENU_NOT_FOUND', 2);
define('JS_MENU_ACCESS_DENIED', 3);
/**
 * @} End of "Required core files".
 */

/**
 * @name Required core files
 * @{
 * The minimal core files required to be able to run a js request
 */
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
require_once DRUPAL_ROOT . '/includes/common.inc';
require_once DRUPAL_ROOT . '/includes/module.inc';
require_once DRUPAL_ROOT . '/includes/unicode.inc';
require_once DRUPAL_ROOT . '/includes/file.inc';

/**
 * @} End of "Required core files".
 */

// Do basic bootstrap to make sure the database can be accessed.
drupal_bootstrap(DRUPAL_BOOTSTRAP_DATABASE);

// Prevent Devel from hi-jacking our output in any case.
$GLOBALS['devel_shutdown'] = FALSE;

// $delivery_callback = 'drupal_json_output';
$delivery_callback = 'lobbywatch_json_output';

$return = js_execute_callback($delivery_callback);

// Menu status constants are integers; page content is a string. If the delivery
// callback is not custom, then do a full bootstrap and let the normal menu
// system handle delivery of the page.
if (is_int($return) && $delivery_callback === 'drupal_json_output') {
  // Make sure the full bootstrap has ran.
  drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
  // Deliver error page.
  drupal_deliver_page($return);
}
elseif (isset($return)) {
  // If JavaScript callback did not exit, print any value (including an empty
  // string) except NULL or undefined.
  call_user_func_array($delivery_callback, array($return));
}

/**
 * Loads the requested module and executes the requested callback.
 *
 * @param string $delivery_callback
 *   The delivery callback function to use. Defaults to drupal_json_output().
 *
 * @return mixed
 *   The callback function's return value or one of the JS_* constants.
 */
function js_execute_callback(&$delivery_callback = 'drupal_json_output') {
  $args = explode('/', $_GET['q']);

  // If i18n is enabled and therefore the js module should boot
  // to DRUPAL_BOOTSTRAP_LANGUAGE.
  $i18n = FALSE;

  // Validate if there is a language prefix in the path.
  if (!empty($args[0]) && !empty($args[1]) && $args[1] == 'data') {
    // Language string detected, strip off the language code.
    $language_code = array_shift($args);

    // Enable language detection to make sure i18n is enabled.
    $i18n = TRUE;
  }

  // Strip first argument 'data'.
  if (!empty($args[0]) && $args[0] == 'data') {
    array_shift($args);
  }

  // Determine module to load.
  $interface = check_plain(array_shift($args));
  if ($interface == 'interface') {
    $module = 'lobbywatch_data';
  } else {
    $module = $interface;
  }

  if (!$module || !drupal_load('module', $module)) {
//     print '111 JS_MENU_ACCESS_DENIED';
    return JS_MENU_ACCESS_DENIED;
  }

  // Get info hook function name.
  $function = $module . '_data';
  if (!function_exists($function)) {
//     print '118 JS_MENU_NOT_FOUND';
    return JS_MENU_NOT_FOUND;
  }

  // Get valid callbacks.
  $valid_callbacks = $function();

  // Get the callback.
  $callback = check_plain(array_shift($args));

  // Validate the callback.
  if (!isset($valid_callbacks[$callback])) {
    return JS_MENU_NOT_FOUND;
  }

  // If the callback function is located in another file, load that file now.
  if (isset($valid_callbacks[$callback]['file']) && ($filepath = drupal_get_path('module', $module) . '/' . $valid_callbacks[$callback]['file']) && file_exists($filepath)) {
    require_once $filepath;
  }

  // Bootstrap to required level.
  $full_boostrap = FALSE;
  if (!empty($valid_callbacks[$callback]['bootstrap'])) {
    drupal_bootstrap($valid_callbacks[$callback]['bootstrap']);
    $full_boostrap = ($valid_callbacks[$callback]['bootstrap'] == DRUPAL_BOOTSTRAP_FULL);
  }

  // Validate if the callback uses i18n.
  if (isset($valid_callbacks[$callback]['i18n'])) {
    $i18n = $valid_callbacks[$callback]['i18n'];
  }

  if (!$full_boostrap) {
    // The following mimics the behavior of _drupal_bootstrap_full().
    // The difference is that not all modules and includes are loaded.
    // @see _drupal_bootstrap_full().

    // If i18n is enabled, boot to the language phase and make
    // sure the required modules are enabled.
    if ($i18n) {
      // First boot to the variables to make sure drupal_multilingual() works.
      drupal_bootstrap(DRUPAL_BOOTSTRAP_VARIABLES);

      // As the variables bootstrap phase loads all core modules, we have to
      // add the user module and the path include as a dependencies because they
      // are required by some core modules.
      if (empty($valid_callbacks[$callback]['dependencies'])) {
        $valid_callbacks[$callback]['dependencies'] = [];
      }
      if (empty($valid_callbacks[$callback]['includes'])) {
        $valid_callbacks[$callback]['includes'] = [];
      }
      if (!in_array('user', $valid_callbacks[$callback]['dependencies'])) {
        $valid_callbacks[$callback]['dependencies'][] = 'user';
      }
      if (!in_array('path', $valid_callbacks[$callback]['includes'])) {
        $valid_callbacks[$callback]['includes'][] = 'path';
      }

      // Then check if it's a multilingual site. If so, boot to the language
      // phase.
      if (drupal_multilingual()) {
        drupal_bootstrap(DRUPAL_BOOTSTRAP_LANGUAGE);
      }
    }

    // Load required include files based on the callback.
    if (isset($valid_callbacks[$callback]['includes']) && is_array($valid_callbacks[$callback]['includes'])) {
      foreach ($valid_callbacks[$callback]['includes'] as $include) {
        if (file_exists("./includes/$include.inc")) {
          require_once "./includes/$include.inc";
        }
      }
    }

    // Detect string handling method.
    unicode_check();

    // Undo magic quotes.
    fix_gpc_magic();

    // Load required modules.
    $modules = array($module => 0);
    if (isset($valid_callbacks[$callback]['dependencies']) && is_array($valid_callbacks[$callback]['dependencies'])) {
      foreach ($valid_callbacks[$callback]['dependencies'] as $dependency) {
        if (!drupal_load('module', $dependency)) {
          // Do a boot up till SESSION to be sure the drupal_set_message()
          // function works.
          drupal_bootstrap(DRUPAL_BOOTSTRAP_SESSION);

          // Create an error message with information for the user to be able
          // to fix the dependency.
          $error = t(
            'The dependency :dependency for the callback :callback in :module is not installed.',
            array(
              ':dependency' => $dependency,
              ':callback' => $callback,
              ':module' => $module,
            )
          );

          // Let the user know what's wrong and throw an exception to stop the
          // callback.
          drupal_set_message($error, 'error');
          throw new Exception($error);
        }
        $modules[$dependency] = 0;
      }
    }
    // Reset module list.
    module_list(FALSE, TRUE, FALSE, $modules);

    // Make sure all stream wrappers are registered.
    file_get_stream_wrappers();

    // Ensure the language variable is set, if not it might cause problems (e.g.
    // entity info).
    global $language;
    if (!isset($language)) {
      $language = language_default();

      $types = language_types();
      foreach ($types as $type) {
        $GLOBALS[$type] = $language;
      }
    }

    // If access arguments are passed, boot to SESSION and validate if the user
    // has access to this callback.
    if (!empty($valid_callbacks[$callback]['access arguments']) || !empty($valid_callbacks[$callback]['access callback'])) {
      drupal_bootstrap(DRUPAL_BOOTSTRAP_SESSION);

      // If no callback is provided, default to user_access.
      if (!isset($valid_callbacks[$callback]['access callback'])) {
        $valid_callbacks[$callback]['access callback'] = 'user_access';
      }

      if ($valid_callbacks[$callback]['access callback'] == 'user_access') {
        // Ensure the user module is available.
        drupal_load('module', 'user');
      }

      if (!call_user_func_array($valid_callbacks[$callback]['access callback'], js_replace_callback_args(!empty($valid_callbacks[$callback]['access arguments']) ? $valid_callbacks[$callback]['access arguments'] : []))) {
// 		    print 'XXX 259 JS_MENU_ACCESS_DENIED';
        return JS_MENU_ACCESS_DENIED;
      }
    }

    // Invoke implementations of hook_init() if the callback doesn't indicate it
    // should be skipped.
    if (!isset($valid_callbacks[$callback]['skip_hook_init']) || $valid_callbacks[$callback]['skip_hook_init'] == FALSE) {
      module_invoke_all('init');
    }
  }

  // Validate the existance of the defined callback.
  if (!function_exists($valid_callbacks[$callback]['callback'])) {
    return JS_MENU_NOT_FOUND;
  }

  // If there are page arguments defined add them to the callback call.
  if (isset($valid_callbacks[$callback]['page arguments'])) {
    // Replace the numerical arguments with the current argument values.
    $args = js_replace_callback_args($valid_callbacks[$callback]['page arguments']);
  }

  if (isset($valid_callbacks[$callback]['delivery callback'])) {
    $delivery_callback = $valid_callbacks[$callback]['delivery callback'];
  }

  // Invoke callback function.
  return call_user_func_array($valid_callbacks[$callback]['callback'], $args);
}

/**
 * Helper function for replacing integer based arguments with path values.
 *
 * @param array $args
 *   An array of arguments to walk through and replace values.
 *
 * @return array
 *   The arguments array replaced with correct path values.
 */
function js_replace_callback_args(array $args = []) {
  // Retrieve the original arguments again, but strip first and second
  // arguments ('data' and 'module').
  $original_args = array_slice(explode('/', $_GET['q']), 2);
  foreach ($args as $key => $value) {
    // Numeric argument exists, replace it.
    if (is_int($value) && !empty($original_args[$value])) {
      $args[$key] = $original_args[$value];
    } else if (is_int($value) && $value === -1) {
      $args[$key] = $_GET['q'];
    }
    // Numeric argument does not exist, remove it.
    elseif (is_int($value)) {
      unset($args[$key]);
    }
  }

  return $args;
}
