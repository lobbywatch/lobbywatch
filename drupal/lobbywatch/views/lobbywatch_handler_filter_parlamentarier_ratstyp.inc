<?php

/**
 * @file
 * Definition of views_handler_filter_node_type.
 */

/**
 * Filter by node type.
 *
 * @ingroup views_filter_handlers
 */
class lobbywatch_handler_filter_parlamentarier_ratstyp extends views_handler_filter_in_operator {

  function get_value_options() {
    if (!isset($this->value_options)) {
      $this->value_title = lt('Rat');
      $options = array();
      $options['NR'] = lt('Nationalrat');
      $options['SR'] = lt('Ständerat');
      asort($options);
      $this->value_options = $options;
    }
  }

}
