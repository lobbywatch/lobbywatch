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
class lobbywatch_handler_filter_parlamentarier_kommissionen_string extends views_handler_filter_in_operator {
//   function get_value_options() {
//     if (!isset($this->value_options)) {
//       $this->value_title = t('Ratstyp');
// //       $types = node_type_get_types();
// //       $options = array();
// //       foreach ($types as $type => $info) {
// //         $options[$type] = t($info->name);
// //       }
//       $options['NR'] = t('a');
//       $options['SR'] = t('b');
//       asort($options);
//       $this->value_options = $options;
//     }
//   }

  function get_value_options() {
    if (!isset($this->value_options)) {
      $this->value_title = t('Kommissionen');
      $kommissionen = lobbywatch_get_kommissionen();
//       dpm($kommissionen);
      $options = array();
      foreach ($kommissionen as $kommission => $info) {
        $name = mb_substr($info['name'], 0, 100);
        $options[$info['abkuerzung']] = $name . ($name != $info['name'] ? '…' : '') . ' (' . $info['abkuerzung'] . ')';
      }
//       dpm($options);
//       $options['NR'] = t('Nationalrat');
//       $options['SR'] = t('Ständerat');
      asort($options);
      $this->value_options = $options;
    }
  }

  /**
   * This kind of construct makes it relatively easy for a child class
   * to add or remove functionality by overriding this function and
   * adding/removing items from this array.
   */
  function operators() {
//     $operators = parent::operators();
    $operators = array(
      'in' => array(
        'title' => t('Is one of'),
        'short' => t('in'),
        'short_single' => t('='),
        'method' => 'op_simple_param',
        'values' => 1,
      ),
      'not in' => array(
        'title' => t('Is not one of'),
        'short' => t('not in'),
        'short_single' => t('<>'),
        'method' => 'op_simple_param',
        'values' => 1,
      ),
    );

    $operators += array(
//         '=' => array(
//             'title' => t('Is equal to'),
//             'short' => t('='),
//             'method' => 'op_equal',
//             'values' => 1,
//         ),
//         '!=' => array(
//             'title' => t('Is not equal to'),
//             'short' => t('!='),
//             'method' => 'op_equal',
//             'values' => 1,
//         ),
        'contains' => array(
            'title' => t('Contains'),
            'short' => t('contains'),
            'method' => 'op_contains',
            'values' => 1,
        ),
        'word' => array(
            'title' => t('Contains any word'),
            'short' => t('has word'),
            'method' => 'op_word',
            'values' => 1,
        ),
        'allwords' => array(
            'title' => t('Contains all words'),
            'short' => t('has all'),
            'method' => 'op_word',
            'values' => 1,
        ),
//         'starts' => array(
//             'title' => t('Starts with'),
//             'short' => t('begins'),
//             'method' => 'op_starts',
//             'values' => 1,
//         ),
//         'not_starts' => array(
//             'title' => t('Does not start with'),
//             'short' => t('not_begins'),
//             'method' => 'op_not_starts',
//             'values' => 1,
//         ),
//         'ends' => array(
//             'title' => t('Ends with'),
//             'short' => t('ends'),
//             'method' => 'op_ends',
//             'values' => 1,
//         ),
//         'not_ends' => array(
//             'title' => t('Does not end with'),
//             'short' => t('not_ends'),
//             'method' => 'op_not_ends',
//             'values' => 1,
//         ),
        'not' => array(
            'title' => t('Does not contain'),
            'short' => t('!has'),
            'method' => 'op_not',
            'values' => 1,
        ),
//         'shorterthan' => array(
//             'title' => t('Length is shorter than'),
//             'short' => t('shorter than'),
//             'method' => 'op_shorter',
//             'values' => 1,
//         ),
//         'longerthan' => array(
//             'title' => t('Length is longer than'),
//             'short' => t('longer than'),
//             'method' => 'op_longer',
//             'values' => 1,
//         ),
     );
// //     // if the definition allows for the empty operator, add it.
// //     if (!empty($this->definition['allow empty'])) {
// //       $operators += array(
// //           'empty' => array(
// //               'title' => t('Is empty (NULL)'),
// //               'method' => 'op_empty',
// //               'short' => t('empty'),
// //               'values' => 0,
// //           ),
// //           'not empty' => array(
// //               'title' => t('Is not empty (NOT NULL)'),
// //               'method' => 'op_empty',
// //               'short' => t('not empty'),
// //               'values' => 0,
// //           ),
// //       );
// //     }
    // Add regexp support for MySQL.
    if (Database::getConnection()->databaseType() == 'mysql') {
      $operators += array(
          'regular_expression' => array(
              'title' => t('Regular expression'),
              'short' => t('regex'),
              'method' => 'op_regex',
              'values' => 1,
          ),
      );
    }

//     dpm($operators);
    return $operators;
  }

  // TODO from views_handler_filter_string copied
  /**
   * Add this filter to the query.
   *
   * Due to the nature of fapi, the value and the operator have an unintended
   * level of indirection. You will find them in $this->operator
   * and $this->value respectively.
   */
  function query() {
    $this->ensure_my_table();
    $field = "$this->table_alias.$this->real_field";

    $info = $this->operators();
    if (!empty($info[$this->operator]['method'])) {
      $this->{$info[$this->operator]['method']}($field);
    }
  }

  function op_simple_param($field) {
    if (empty($this->value)) {
      return;
    }
    $this->ensure_my_table();

    // We use array_values() because the checkboxes keep keys and that can cause
    // array addition problems.
    $this->query->add_where($this->options['group'], $field, array_values($this->value), $this->operator);
  }

  function op_contains($field) {
//     $this->query->add_where($this->options['group'], $field, '%' . db_like($this->value) . '%', 'LIKE');
// TODO a hack
    $this->query->add_where($this->options['group'], $field, '%' . db_like(implode(',', $this->value)) . '%', 'LIKE');
  }

  function op_word($field) {
    $where = $this->operator == 'word' ? db_or() : db_and();

    // Don't filter on empty strings.
    if (empty($this->value)) {
      return;
    }

    preg_match_all('/ (-?)("[^"]+"|[^" ]+)/i', ' ' . $this->value, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
      $phrase = false;
      // Strip off phrase quotes
      if ($match[2]{0} == '"') {
        $match[2] = substr($match[2], 1, -1);
        $phrase = true;
      }
      $words = trim($match[2], ',?!();:-');
      $words = $phrase ? array($words) : preg_split('/ /', $words, -1, PREG_SPLIT_NO_EMPTY);
      foreach ($words as $word) {
        $placeholder = $this->placeholder();
        $where->condition($field, '%' . db_like(trim($word, " ,!?")) . '%', 'LIKE');
      }
    }

    if (!$where) {
      return;
    }

    // previously this was a call_user_func_array but that's unnecessary
    // as views will unpack an array that is a single arg.
    $this->query->add_where($this->options['group'], $where);
  }

  function op_not_ends($field) {
    $this->query->add_where($this->options['group'], $field, '%' . db_like($this->value), 'NOT LIKE');
  }

  function op_not($field) {
    $this->query->add_where($this->options['group'], $field, '%' . db_like($this->value) . '%', 'NOT LIKE');
  }

  function op_regex($field) {
    $this->query->add_where($this->options['group'], $field, $this->value, 'RLIKE');
  }

}
