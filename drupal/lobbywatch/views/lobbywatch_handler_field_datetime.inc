<?php

// Copied from https://drupal.org/comment/6880180#comment-6880180

/**
* A handler to display dates that are DATETIME instead of unix timestamp.
*
* @ingroup views_field_handlers
*/
class views_handler_field_datetime extends views_handler_field_date {
  function get_value($values, $field = NULL) {
    $value = parent::get_value($values, $field);
    if (!empty($value)) {
      // Convert database datetime value to timestamp, so that the date handler
      // understands it.
      $datetime = new DateTime($value);
      $value = $datetime->getTimestamp();
    }
    return $value;
  }
}

/**
* A handler to filter dates that are DATETIME instead of unix timestamp.
*
* @ingroup views_filter_handlers
*/
class views_handler_filter_datetime extends views_handler_filter_date {
  function op_between($field) {
    parent::op_between("UNIX_TIMESTAMP(" . $field . ")");
  }
  function op_simple($field) {
    parent::op_simple("UNIX_TIMESTAMP(" . $field . ")");
  }
}

/**
* A handler to sort dates that are DATETIME instead of unix timestamp.
*
* @ingroup views_sort_handlers
*/
class views_handler_sort_datetime extends views_handler_sort_date {
  /**
   * Called to add the sort to a query.
   */
  function query() {
    $this->ensure_my_table();
    switch ($this->options['granularity']) {
      case 'second':
      default:
        $this->query->add_orderby($this->table_alias, $this->real_field, $this->options['order']);
        return;
      case 'minute':
        $formula = "DATE_FORMAT({$this->table_alias}.{$this->real_field}, '%Y%m%d%H%i')";
        break;
      case 'hour':
        $formula = "DATE_FORMAT({$this->table_alias}.{$this->real_field}, '%Y%m%d%H')";
        break;
      case 'day':
        $formula = "DATE_FORMAT({$this->table_alias}.{$this->real_field}, '%Y%m%d')";
        break;
      case 'month':
        $formula = "DATE_FORMAT({$this->table_alias}.{$this->real_field}, '%Y%m')";
        break;
      case 'year':
        $formula = "DATE_FORMAT({$this->table_alias}.{$this->real_field}, '%Y')";
        break;
    }
    // Add the field.
    $this->query->add_orderby(NULL, $formula, $this->options['order'], $this->table_alias . '_' . $this->field . '_' . $this->options['granularity']);
  }
}
