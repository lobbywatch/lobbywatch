<?php

/**
 * @file
 * Definition of lobbywatch_handler_filter_freigabe_datum.
 */

/**
 * Filter by published lobbywatch content ("erfasst").
 *
 * @ingroup views_filter_handlers
 */
class lobbywatch_handler_filter_released extends views_handler_filter_boolean_operator {
//   function admin_summary() { }
//   function operator_form(&$form, &$form_state) { }
//   function can_expose() { return FALSE; }

//   function query() {
//     $table = $this->ensure_my_table();
//     $this->query->add_where_expression($this->options['group'], "$table.freigabe_datum <= NOW()");
//   }

  function construct() {
	$this->accept_null = true;
	$this->definition['type'] = 'yes-no';
    parent::construct();
  }

  function query() {
    $this->ensure_my_table();
    $where = "$this->table_alias.$this->real_field ";

    if (empty($this->value)) {
      $where .= " > NOW()";
      if ($this->accept_null) {
        $where = '(' . $where . " OR $this->table_alias.$this->real_field IS NULL)";
      }
    }
    else {
      $where .= " <= NOW()";
    }
    $this->query->add_where_expression($this->options['group'], $where);
  }

}
