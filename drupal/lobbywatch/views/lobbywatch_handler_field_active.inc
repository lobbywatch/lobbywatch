<?php

// Copied from https://drupal.org/comment/6880180#comment-6880180

/**
* A handler to display if is active: value IS NULL OR value > NOW().
*
* @ingroup views_field_handlers
*/
class lobbywatch_handler_field_active extends views_handler_field_boolean {
  function get_value($values, $field = NULL) {
    $value = parent::get_value($values, $field);
    if (!empty($value)) {
      // Convert database datetime value to timestamp, so that the date handler
      // understands it.
      $datetime = new DateTime($value);
      $value = $datetime->getTimestamp() > time();
    } else {
      $value = true;
    }
    return $value;
  }
}