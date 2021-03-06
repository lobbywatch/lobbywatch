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
class lobbywatch_handler_filter_parlamentarier_partei extends views_handler_filter_in_operator {
  function get_value_options() {
    if (!isset($this->value_options)) {
      $this->value_title = lt('Partei');
      $parteien = lobbywatch_get_parteien();
      $options = [];
      foreach ($parteien as $partei => $info) {
        $options[$info['abkuerzung']] = translate_record_field($info, 'abkuerzung');
      }
      asort($options);
      $this->value_options = $options;
    }
  }
}
