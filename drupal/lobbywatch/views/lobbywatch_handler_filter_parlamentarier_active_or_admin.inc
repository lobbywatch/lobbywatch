<?php

/**
 * @file
 * Definition of lobbywatch_handler_filter_freigabe_datum.
 */

/**
 * Filter by active parlamentarier or admin.
 *
 * @ingroup views_filter_handlers
 */
class lobbywatch_handler_filter_parlamentarier_active_or_admin extends views_handler_filter {
  function admin_summary() { }
  function operator_form(&$form, &$form_state) { }
  function can_expose() { return FALSE; }

  function query() {
    $table = $this->ensure_my_table();
    $this->query->add_where_expression($this->options['group'], "$table.im_rat_bis IS NULL OR $table.im_rat_bis >= NOW() OR ***ACCESS_ADMIN_LOBBYWATCH_CONTENT*** = 1 OR ***ACCESS_ADVANCED_LOBBYWATCH_CONTENT*** = 1");
  }
}
