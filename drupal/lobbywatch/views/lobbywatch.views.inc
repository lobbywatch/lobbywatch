<?php

// Ref. http://www.grasmash.com/article/demystifying-views-api-developers-guide-integrating-views
// https://api.drupal.org/api/views/views.api.php/group/views_handlers/7
// Autocomplete: http://drupal.stackexchange.com/questions/60083/making-text-fields-autocomplete-in-views-exposed-filters
// API: http://lobbywatch.dev/de/help/views/api-tables

/**
 * Implements hook_views_data().
 */
function lobbywatch_views_data() {

//   drupal_set_message("lobbywatch_views_data");


  // Define the base group of this table. Fields that don't
  // have a group defined will go into this field by default.
  $data['v_parlamentarier']['table']['group'] = t('Parlamentarier');

  // Advertise this table as a possible base table
  $data['v_parlamentarier']['table']['base'] = array(
    'field' => 'id',
    'title' => t('Parlamentarier'),
    'weight' => -10,
//     'access query tag' => 'node_access',
//     'defaults' => array( 'field' => 'anzeige_name', ),
    'defaults' => array('field' => 'anzeige_name', ),
    'database' => 'lobbywatch',
  );

  $data['v_parlamentarier']['table']['default_relationship'] = array(
      'v_kommission' => array(
          'table' => 'v_kommission',
          'field' => 'id',
      ),
  );


  //   $data['taxonomy_term_data']['table']['join']['node'] = array(
  //   'left_table' => 'term_node',
  //   'left_field' => 'tid',
  //   'field' => 'tid',
  //   );

  //id
  $data['v_parlamentarier']['id'] = array(
    'title' => t('Id'),
    'help' => t('Schlüssel des Parlamentariers.'), // The help that appears on the UI,
    // Information for displaying the nid
    'field' => array(
      'field' => 'id', // the real field. This could be left out since it is the same.
      'group' => t('Parlamentarier'), // The group it appears in on the UI. Could be left out.
      'handler' => 'views_handler_field_numeric',
  //     'handler' => 'views_handler_field_node',
      'click sortable' => TRUE,
    ),
    // Information for accepting a nid as an argument
    'argument' => array(
      'handler' => 'views_handler_argument_parlamentarier_id',
  //     'name field' => 'anzeige_name', // the field to display in the summary.
      'name field' => 'nachname', // the field to display in the summary.
      'numeric' => TRUE,
      'validate type' => 'nid',
    ),
    // Information for accepting a nid as a filter
    'filter' => array(
      'handler' => 'views_handler_filter_numeric',
    ),
    // Information for sorting on a nid.
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
  );

  // nachname
  // This definition has more items in it than it needs to as an example.
  $data['v_parlamentarier']['nachname'] = array(
    'title' => t('Nachname'), // The item it appears as on the UI,
    'help' => t('Der Nachname des Parlamentariers.'), // The help that appears on the UI,
     // Information for displaying a title as a field
    'field' => array(
      'field' => 'nachname', // the real field. This could be left out since it is the same.
      'group' => t('Parlamentarier'), // The group it appears in on the UI. Could be left out.
//       'handler' => 'views_handler_field_node',
      'handler' => 'views_handler_field_markup',
      'click sortable' => TRUE,
//       'link_to_node default' => TRUE,
      'format' => 'plain_text',
     ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
    // Information for accepting a title as a filter
    'filter' => array(
      'handler' => 'views_handler_filter_string',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_string',
    ),
  );

  // vorname
  // This definition has more items in it than it needs to as an example.
  $data['v_parlamentarier']['vorname'] = array(
    'title' => t('Vorname'), // The item it appears as on the UI,
    'help' => t('Der Vorname des Parlamentariers.'), // The help that appears on the UI,
     // Information for displaying a title as a field
    'field' => array(
      'field' => 'vorname', // the real field. This could be left out since it is the same.
      'group' => t('Parlamentarier'), // The group it appears in on the UI. Could be left out.
//       'handler' => 'views_handler_field_node',
      'handler' => 'views_handler_field_markup',
      'click sortable' => TRUE,
//       'link_to_node default' => TRUE,
      'format' => 'plain_text',
     ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
    // Information for accepting a title as a filter
    'filter' => array(
      'handler' => 'views_handler_filter_string',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_string',
    ),
  );

  // anzeige_name
  // This definition has more items in it than it needs to as an example.
  $data['v_parlamentarier']['anzeige_name'] = array(
    'title' => t('Anzeigename'), // The item it appears as on the UI,
    'help' => t('Der Name des Parlamentariers.'), // The help that appears on the UI,
     // Information for displaying a title as a field
    'field' => array(
      'field' => 'anzeige_name', // the real field. This could be left out since it is the same.
      'group' => t('Parlamentarier'), // The group it appears in on the UI. Could be left out.
//       'handler' => 'views_handler_field_node',
      'handler' => 'views_handler_field_markup',
      'click sortable' => TRUE,
//       'link_to_node default' => TRUE,
      'format' => 'plain_text',
     ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
    // Information for accepting a title as a filter
    'filter' => array(
      'handler' => 'views_handler_filter_string',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_string',
    ),
  );

  // anzeige_name
  // This definition has more items in it than it needs to as an example.
  $data['v_parlamentarier']['name'] = array(
      'title' => t('Name'), // The item it appears as on the UI,
      'help' => t('Der Vorname und Name des Parlamentariers.'), // The help that appears on the UI,
      // Information for displaying a title as a field
      'field' => array(
           'handler' => 'views_handler_field',
          'click sortable' => TRUE,

      ),
      'sort' => array(
          'handler' => 'views_handler_sort',
      ),
      'filter' => array(
          'handler' => 'views_handler_filter_string',
      ),
      'argument' => array(
        'handler' => 'views_handler_argument_string',
      ),
  );

  // ratstyp
  // This definition has more items in it than it needs to as an example.
  $data['v_parlamentarier']['ratstyp'] = array(
      'title' => t('Rat'), // The item it appears as on the UI,
      'help' => t('Ratszugehörigkeit des Parlamentariers.'), // The help that appears on the UI,
      // Information for displaying a title as a field
      'field' => array(
          'field' => 'ratstyp', // the real field. This could be left out since it is the same.
          'group' => t('Parlamentarier'), // The group it appears in on the UI. Could be left out.
          //       'handler' => 'views_handler_field_node',
          'handler' => 'views_handler_field',
//           'handler' => 'views_handler_field_markup',
          'click sortable' => TRUE,
          //       'link_to_node default' => TRUE,
//           'format' => 'plain_text',
      ),
      'sort' => array(
          'handler' => 'views_handler_sort',
      ),
      // Information for accepting a title as a filter
      'filter' => array(
          'handler' => 'lobbywatch_handler_filter_parlamentarier_ratstyp',
      ),
      'argument' => array(
        'handler' => 'views_handler_filter_string',
      ),
  );

  // kanton
  $data['v_parlamentarier']['kanton'] = array(
      'title' => t('Kanton'), // The item it appears as on the UI,
      'help' => t('Kanton des Parlamentariers.'), // The help that appears on the UI,
      'field' => array(
          'handler' => 'views_handler_field',
          'click sortable' => TRUE,
      ),
      'sort' => array(
          'handler' => 'views_handler_sort',
      ),
      'filter' => array(
          'handler' => 'lobbywatch_handler_filter_parlamentarier_kanton',
      ),
      'argument' => array(
          'handler' => 'views_handler_argument_string',
      ),
  );

  // partei
  $data['v_parlamentarier']['partei'] = array(
      'title' => t('Partei'), // The item it appears as on the UI,
      'help' => t('Partei des Parlamentariers.'), // The help that appears on the UI,
      'field' => array(
          'handler' => 'views_handler_field',
          'click sortable' => TRUE,
      ),
      'sort' => array(
          'handler' => 'views_handler_sort',
      ),
      'filter' => array(
          'handler' => 'lobbywatch_handler_filter_parlamentarier_partei',
          'allow empty' => TRUE,
      ),
      'argument' => array(
        'handler' => 'views_handler_filter_string',
      ),
  );

  // kommissionen
  // TODO change to relationship
  $data['v_parlamentarier']['kommissionen'] = array(
      'title' => t('Kommissionen'), // The item it appears as on the UI,
      'help' => t('Kommissionen des Parlamentariers.'), // The help that appears on the UI,
      // Information for displaying a title as a field
      'field' => array(
          'handler' => 'views_handler_field',
          'click sortable' => FALSE,
      ),
      'sort' => array(
          'handler' => 'views_handler_sort',
      ),
      'filter' => array(
//           'handler' => 'lobbywatch_handler_filter_parlamentarier_partei',
//           'handler' => 'lobbywatch_handler_filter_parlamentarier_kommstr',
          'handler' => 'lobbywatch_handler_filter_parlamentarier_kommissionen_string',
//           'handler' => 'views_handler_filter_string',
          'allow empty' => TRUE,
      ),
      'argument' => array(
        'handler' => 'views_handler_filter_string',
      ),
  );

  // kleinbild
  $data['v_parlamentarier']['kleinbild'] = array(
      'title' => t('Bildchen'), // The item it appears as on the UI,
      'help' => t('Bildchen des Parlamentariers.'), // The help that appears on the UI,
      'field' => array(
          'handler' => 'views_handler_field',
          'click sortable' => TRUE,
      ),
      'sort' => array(
          'handler' => 'views_handler_sort',
      ),
      'filter' => array(
          'handler' => 'views_handler_filter_string',
          'allow empty' => TRUE,
      ),
      'argument' => array(
          'handler' => 'views_handler_argument_string',
      ),
  );

  // im_rat_seit
  $data['v_parlamentarier']['im_rat_seit'] = array(
      'title' => t('Im Rat seit'), // The item it appears as on the UI,
      'help' => t('Parlamentarier im Rat seit.'), // The help that appears on the UI,
      'field' => array(
          'handler' => 'views_handler_field_datetime',
          'click sortable' => TRUE,
      ),
      'sort' => array(
          'handler' => 'views_handler_sort_datetime',
      ),
      'filter' => array(
          // Roland: default Views views_handler_filter_date supports only timestamps
          // Roland: Use filter from Date module: date_views_argument_handler_simple
          // Roland: Proposition from https://drupal.org/comment/6880180#comment-6880180
          'handler' => 'views_handler_filter_datetime',
          'allow empty' => TRUE,
      ),
  );

  // im_rat_bis
  $data['v_parlamentarier']['im_rat_bis'] = array(
      'title' => t('Im Rat bis'), // The item it appears as on the UI,
      'help' => t('Parlamentarier im Rat bis. NULL = noch aktiv'), // The help that appears on the UI,
      'field' => array(
          'handler' => 'views_handler_field_datetime',
          'click sortable' => TRUE,
      ),
      'sort' => array(
          'handler' => 'views_handler_sort_datetime',
      ),
      'filter' => array(
          // Roland: default Views views_handler_filter_date supports only timestamps
          // Roland: Use filter from Date module: date_views_argument_handler_simple
          // Roland: Proposition from https://drupal.org/comment/6880180#comment-6880180
          'handler' => 'views_handler_filter_datetime',
          'allow empty' => TRUE,
      ),
  );

//   // im_rat_bis
//   $data['v_parlamentarier']['im_rat_bis_string'] = array(
//       'title' => t('Im Rat bis (String)'), // The item it appears as on the UI,
//       'help' => t('Parlamentarier im Rat bis (Filter String). NULL = noch aktiv'), // The help that appears on the UI,
//       'field' => array(
//           'field' => 'im_rat_bis',
//           'handler' => 'views_handler_field_date',
//           'click sortable' => TRUE,
//       ),
//       'sort' => array(
//           'handler' => 'views_handler_sort_date',
//       ),
//       'filter' => array(
//           'handler' => 'views_handler_filter_string',
//       ),
//   );

  // geburtstag
  $data['v_parlamentarier']['geburtstag'] = array(
      'title' => t('Geburtsdatum'), // The item it appears as on the UI,
      'help' => t('Geburtsdatum des Parlamentariers.'), // The help that appears on the UI,
      'field' => array(
          'handler' => 'views_handler_field_datetime',
          'click sortable' => TRUE,
      ),
      'sort' => array(
          'handler' => 'views_handler_sort_datetime',
      ),
      'filter' => array(
          // Roland: default Views views_handler_filter_date supports only timestamps
          // Roland: Use filter from Date module: date_views_argument_handler_simple
          // Roland: Proposition from https://drupal.org/comment/6880180#comment-6880180
          'handler' => 'views_handler_filter_datetime',
          'allow empty' => TRUE,
      ),
  );

  // sitzplatz
  $data['v_parlamentarier']['sitzplatz'] = array(
      'title' => t('Sitzplatz'), // The item it appears as on the UI,
      'help' => t('Sitzplatz des Parlamentariers.'), // The help that appears on the UI,
      'field' => array(
          'handler' => 'views_handler_field_numeric',
          'click sortable' => TRUE,
      ),
      'sort' => array(
          'handler' => 'views_handler_sort',
      ),
      'filter' => array(
          'handler' => 'views_handler_filter_numeric',
          'allow empty' => TRUE,
      ),
      'argument' => array(
          'handler' => 'views_handler_argument_numeric',
      ),
  );

  // email
  $data['v_parlamentarier']['email'] = array(
      'title' => t('E-Mail'), // The item it appears as on the UI,
      'help' => t('E-Mail des Parlamentariers.'), // The help that appears on the UI,
      'field' => array(
          'handler' => 'views_handler_field',
          'click sortable' => TRUE,
      ),
      'sort' => array(
          'handler' => 'views_handler_sort',
      ),
      'filter' => array(
          'handler' => 'views_handler_filter_string',
          'allow empty' => TRUE,
      ),
      'argument' => array(
          'handler' => 'views_handler_argument_string',
      ),
  );


  // homepage
  $data['v_parlamentarier']['homepage'] = array(
      'title' => t('Homepage'), // The item it appears as on the UI,
      'help' => t('Homepage des Parlamentariers.'), // The help that appears on the UI,
      'field' => array(
          'handler' => 'views_handler_field_url',
          'click sortable' => TRUE,
      ),
      'sort' => array(
          'handler' => 'views_handler_sort',
      ),
      'filter' => array(
          'handler' => 'views_handler_filter_string',
          'allow empty' => TRUE,
      ),
      'argument' => array(
          'handler' => 'views_handler_argument_string',
      ),
  );

  // biografie_id
  $data['v_parlamentarier']['biografie_id'] = array(
      'title' => t('Biografie Id'), // The item it appears as on the UI,
      'help' => t('Biografie Id des Parlamentariers auf Parlament.ch.'), // The help that appears on the UI,
      'field' => array(
          'handler' => 'views_handler_field_numeric',
          'click sortable' => TRUE,
      ),
      'sort' => array(
          'handler' => 'views_handler_sort',
      ),
      'filter' => array(
          'handler' => 'views_handler_filter_numeric',
          'allow empty' => TRUE,
      ),
      'argument' => array(
          'handler' => 'views_handler_argument_numeric',
      ),
  );

  // created field
  $data['v_parlamentarier']['created_date'] = array(
    'title' => t('Created date'), // The item it appears as on the UI,
    'help' => t('The date the content was created.'), // The help that appears on the UI,
    'field' => array(
      'handler' => 'views_handler_field_date',
      'click sortable' => TRUE,
    ),
    'sort' => array(
      'handler' => 'views_handler_sort_date',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_date',
    ),
  );

  // updated_date field
  $data['v_parlamentarier']['updated_date'] = array(
    'title' => t('Updated date'), // The item it appears as on the UI,
    'help' => t('The date the content was last updated.'), // The help that appears on the UI,
    'field' => array(
      'handler' => 'views_handler_field_date',
      'click sortable' => TRUE,
    ),
    'sort' => array(
      'handler' => 'views_handler_sort_date',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_date',
    ),
  );

  // freigabe_datum field
  $data['v_parlamentarier']['freigabe_datum'] = array(
    'title' => t('Released date'), // The item it appears as on the UI,
    'help' => t('The date the content was last updated.'), // The help that appears on the UI,
    'field' => array(
      'handler' => 'views_handler_field_date',
      'click sortable' => TRUE,
    ),
    'sort' => array(
      'handler' => 'views_handler_sort_date',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_date',
    ),
  );

//   $data['node']['status'] = array(
//       'title' => t('Published'),
//       'help' => t('Whether or not the content is published.'),
//       'field' => array(
//           'handler' => 'views_handler_field_boolean',
//           'click sortable' => TRUE,
//           'output formats' => array(
//               'published-notpublished' => array(t('Published'), t('Not published')),
//           ),
//       ),
//       'filter' => array(
//           'handler' => 'views_handler_filter_boolean_operator',
//           'label' => t('Published'),
//           'type' => 'yes-no',
//           'use equal' => TRUE, // Use status = 1 instead of status <> 0 in WHERE statment
//       ),
//       'sort' => array(
//           'handler' => 'views_handler_sort',
//       ),
//   );

  // published status + extra
  $data['v_parlamentarier']['freigabe_datum_extra'] = array(
      'title' => t('Published or admin'),
      'help' => t('Filters out unpublished lobbywatch content if the current user cannot view it.'),
      'filter' => array(
          'field' => 'freigabe_datum',
          'handler' => 'lobbywatch_handler_filter_freigabe_datum',
          'label' => t('Published or admin'),
      ),
  );

//   $data['node']['status_extra'] = array(
//       'title' => t('Published or admin'),
//       'help' => t('Filters out unpublished content if the current user cannot view it.'),
//       'filter' => array(
//           'field' => 'status',
//           'handler' => 'views_handler_filter_node_status',
//           'label' => t('Published or admin'),
//       ),
//   );



  // ==== Kommission ====
  // Define the base group of this table. Fields that don't
  // have a group defined will go into this field by default.
  $data['v_kommission']['table']['group'] = t('Kommission');

  // Advertise this table as a possible base table
  $data['v_kommission']['table']['base'] = array(
      'field' => 'id',
      'title' => t('Kommission'),
      'weight' => -10,
      //     'access query tag' => 'node_access',
  //     'defaults' => array( 'field' => 'anzeige_name', ),
      'defaults' => array('field' => 'abkuerzung', ),
      'database' => 'lobbywatch',
  );

  $data['v_kommission']['table']['join'] = array(
      // This is provided for many_to_one argument
      'v_in_kommission' => array(
          'field' => 'id',
          'left_field' => 'kommission_id',
      ),
  );

  //   $data['taxonomy_term_data']['table']['join']['node'] = array(
  //   'left_table' => 'term_node',
  //   'left_field' => 'tid',
  //   'field' => 'tid',
  //   );

  //id
  $data['v_kommission']['id'] = array(
      'title' => t('Id'),
      'help' => t('Der Schlüssel der Kommission.'), // The help that appears on the UI,
      // Information for displaying the nid
      'field' => array(
          'handler' => 'views_handler_field_numeric',
          'click sortable' => TRUE,
      ),
      // Information for accepting a nid as an argument
      'argument' => array(
          // TODO change to special field
          'handler' => 'views_handler_argument_numeric',
          'name field' => 'name', // the field to display in the summary.
          'numeric' => TRUE,
          'validate type' => 'nid',
      ),
      'filter' => array(
        'handler' => 'views_handler_filter_numeric',
      ),
      'sort' => array(
        'handler' => 'views_handler_sort',
      ),
  );

  // abkuerzung
  $data['v_kommission']['abkuerzung'] = array(
      'title' => t('Abkuerzung'), // The item it appears as on the UI,
      'help' => t('Abkuerzung der Kommission.'), // The help that appears on the UI,
      'field' => array(
          'handler' => 'views_handler_field',
          'click sortable' => TRUE,
      ),
      'sort' => array(
          'handler' => 'views_handler_sort',
      ),
      'filter' => array(
          'handler' => 'views_handler_filter_string',
      ),
      'argument' => array(
        'handler' => 'views_handler_argument_string',
      ),
  );

  // abkuerzung
  $data['v_kommission']['name'] = array(
      'title' => t('Name'), // The item it appears as on the UI,
      'help' => t('Name der Kommission.'), // The help that appears on the UI,
      'field' => array(
          'handler' => 'views_handler_field',
          'click sortable' => TRUE,
      ),
      'sort' => array(
          'handler' => 'views_handler_sort',
      ),
      'filter' => array(
          'handler' => 'views_handler_filter_string',
      ),
      'argument' => array(
        'handler' => 'views_handler_argument_string',
      ),
  );

  // ==== Kommission ====
  // Taken from taxonomy_index

  $data['v_in_kommission']['table']['group']  = t('In Kommission');

  $data['v_in_kommission']['table']['join'] = array(
      'v_parlamentarier' => array(
          // links directly to taxonomy_term_data via tid
          'left_field' => 'id',
          'field' => 'parlamentarier_id',
      ),
      'v_kommission' => array(
          // links directly to node via nid
          'left_field' => 'id',
          'field' => 'kommission_id',
      ),
  );

  $data['v_in_kommission']['parlamentarier_id'] = array(
      'title' => t('Content with term'),
      'help' => t('Relate all content tagged with a term.'),
      'relationship' => array(
          'handler' => 'views_handler_relationship',
          'base' => 'v_parlamentarier',
          'base field' => 'id',
          'label' => t('Parlamentarier'),
          'skip base' => 'v_parlamentarier',
      ),
  );

  // @todo This stuff needs to move to a node field since
  // really it's all about nodes.
  // tid field
  $data['v_in_kommission']['kommission_id'] = array(
      'group' => t('Content'),
          'title' => t('Has taxonomy term ID'),
          'help' => t('Display content if it has the selected taxonomy terms.'),
//           'argument' => array(
//               'handler' => 'views_handler_argument_term_node_tid',
//               'name table' => 'taxonomy_term_data',
//               'name field' => 'name',
//               'empty field name' => t('Uncategorized'),
//               'numeric' => TRUE,
//               'skip base' => 'taxonomy_term_data',
//           ),
          'filter' => array(
              'title' => t('Has taxonomy term'),
              'handler' => 'views_handler_filter_term_node_tid',
              'hierarchy table' => 'taxonomy_term_hierarchy',
              'numeric' => TRUE,
              'skip base' => 'taxonomy_term_data',
              'allow empty' => TRUE,
          ),
      );



  return $data;
}

/**
 * Implements hook_views_query_substitutions().
 */
function lobbywatch_views_query_substitutions() {
  return array(
      '***ACCESS_UNPUBLISHED_LOBBYWATCH_CONTENT***' => intval(user_access('access lobbywatch unpublished content')),
  );
}

