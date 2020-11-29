<?php

/**
 * @file
 * Provide parlamentarier id argument handler.
 */

/**
 * Argument handler to accept a parlamentarier id.
 */
class lobbywatch_handler_argument_parlamentarier_id extends views_handler_argument_numeric {
  /**
   * Override the behavior of title(). Get the title of the node.
   */
  function title_query() {
    $titles = [];

    $result = db_query("SELECT parlamentarier.anzeige_name FROM {v_parlamentarier} parlamentarier WHERE parlamentarier.id IN (:ids)", array(':ids' => $this->value));
    foreach ($result as $term) {
      $titles[] = check_plain($term->anzeige_name);
    }
    return $titles;
  }
}
