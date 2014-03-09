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
class lobbywatch_handler_filter_parlamentarier_kanton extends views_handler_filter_in_operator {
  function get_value_options() {
    if (!isset($this->value_options)) {
      $this->value_title = t('Ratstyp');
      //TODO convert to table query
      $kantone = array('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH');
//       $types = node_type_get_types();
      $options = array();
      foreach ($kantone as $kanton) {
        $options[$kanton] = t($kanton);
      }
      asort($options);
      $this->value_options = $options;
    }
  }
}
