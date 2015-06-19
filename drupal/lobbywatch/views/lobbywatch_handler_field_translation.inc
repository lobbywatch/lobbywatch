<?php

/**
* A handler to translate with content with lt().
*
* @ingroup views_field_handlers
*/
class lobbywatch_handler_field_translation extends views_handler_field {

  /**
   * Called to add the field to a query.
   */
  function query() {
//     $this->ensure_my_table();
//     // Add the field.
//     $params = $this->options['group_type'] != 'group' ? array('function' => $this->options['group_type']) : array();
//     $this->field_alias = $this->query->add_field($this->table_alias, $this->real_field, NULL, $params);

//     $this->add_additional_fields();
    parent::query();
//     df($this->field_alias, 'lobbywatch_handler_field_translation: $this->field_alias');
  }

  function get_value($values, $field = NULL) {
    $value = parent::get_value($values, $field);
//     $trans = lt($value);
//     df($trans, 'lobbywatch_handler_field_translation: get_value lt');
    return lt($value);
  }
}
