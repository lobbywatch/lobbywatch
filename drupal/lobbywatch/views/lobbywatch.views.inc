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
  $data['v_parlamentarier']['table']['group'] = lt('Parlamentarier');

  // Advertise this table as a possible base table
  $data['v_parlamentarier']['table']['base'] = array(
    'field' => 'id',
    'title' => lt('Parlamentarier'),
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
    'title' => lt('Id'),
    'help' => lt('Schlüssel des Parlamentariers.'), // The help that appears on the UI,
    // Information for displaying the nid
    'field' => array(
      'field' => 'id', // the real field. This could be left out since it is the same.
      'group' => lt('Parlamentarier'), // The group it appears in on the UI. Could be left out.
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
    'title' => lt('Nachname'), // The item it appears as on the UI,
    'help' => lt('Der Nachname des Parlamentariers.'), // The help that appears on the UI,
     // Information for displaying a title as a field
    'field' => array(
      'field' => 'nachname', // the real field. This could be left out since it is the same.
      'group' => lt('Parlamentarier'), // The group it appears in on the UI. Could be left out.
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
    'title' => lt('Vorname'), // The item it appears as on the UI,
    'help' => lt('Der Vorname des Parlamentariers.'), // The help that appears on the UI,
     // Information for displaying a title as a field
    'field' => array(
      'field' => 'vorname', // the real field. This could be left out since it is the same.
      'group' => lt('Parlamentarier'), // The group it appears in on the UI. Could be left out.
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
    'title' => lt('Anzeigename'), // The item it appears as on the UI,
    'help' => lt('Der Name des Parlamentariers.'), // The help that appears on the UI,
     // Information for displaying a title as a field
    'field' => array(
      'field' => 'anzeige_name', // the real field. This could be left out since it is the same.
      'group' => lt('Parlamentarier'), // The group it appears in on the UI. Could be left out.
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
      'title' => lt('Name'), // The item it appears as on the UI,
      'help' => lt('Der Vorname und Name des Parlamentariers.'), // The help that appears on the UI,
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
      'title' => lt('Rat'), // The item it appears as on the UI,
      'help' => lt('Ratszugehörigkeit des Parlamentariers.'), // The help that appears on the UI,
      // Information for displaying a title as a field
      'field' => array(
          'field' => 'rat', // the real field. This could be left out since it is the same.
          'group' => lt('Parlamentarier'), // The group it appears in on the UI. Could be left out.
          //       'handler' => 'views_handler_field_node',
          'handler' => 'lobbywatch_handler_field_translation',
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
      'title' => lt('Kanton'), // The item it appears as on the UI,
      'help' => lt('Kanton des Parlamentariers.'), // The help that appears on the UI,
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
      'title' => lt('Partei'), // The item it appears as on the UI,
      'help' => lt('Partei des Parlamentariers.'), // The help that appears on the UI,
      'field' => array(
//           'field' => 'partei***LANG_SUFFIX***', // the real field. This could be left out since it is the same.
          'handler' => 'lobbywatch_handler_field_translation',
          'click sortable' => TRUE,
          'translatable' => TRUE,
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
      'title' => lt('Kommissionen'), // The item it appears as on the UI,
      'help' => lt('Kommissionen des Parlamentariers.'), // The help that appears on the UI,
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
      'title' => lt('Bildchen'), // The item it appears as on the UI,
      'help' => lt('Bildchen des Parlamentariers.'), // The help that appears on the UI,
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
      'title' => lt('Im Rat bis'), // The item it appears as on the UI,
      'help' => lt('Parlamentarier im Rat bis. NULL = noch aktiv'), // The help that appears on the UI,
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
  $data['v_parlamentarier']['active'] = array(
      'title' => lt('Active'), // The item it appears as on the UI,
      'help' => lt('Parlamentarier im Rat'), // The help that appears on the UI,
      'field' => array(
          'field' => 'im_rat_bis',
          'handler' => 'lobbywatch_handler_field_active',
      ),
      'filter' => array(
          'field' => 'im_rat_bis',
          'handler' => 'lobbywatch_handler_filter_parlamentarier_active',
          'label' => t('Active or admin'),
      ),
  );

  $data['v_parlamentarier']['active_or_admin'] = array(
      'title' => t('Active or admin'),
      'help' => t('Filters out inactive parlamentarier if the current user cannot view it.'),
      'filter' => array(
          'field' => 'im_rat_bis',
          'handler' => 'lobbywatch_handler_filter_parlamentarier_active_or_admin',
          'label' => t('Active or admin'),
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
      'title' => lt('Geburtsdatum'), // The item it appears as on the UI,
      'help' => lt('Geburtsdatum des Parlamentariers.'), // The help that appears on the UI,
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
      'title' => lt('Sitzplatz'), // The item it appears as on the UI,
      'help' => lt('Sitzplatz des Parlamentariers.'), // The help that appears on the UI,
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

  // lobbyfaktor
  $data['v_parlamentarier']['lobbyfaktor'] = array(
      'title' => lt('Lobbyfaktor'), // The item it appears as on the UI,
      'help' => lt('Lobbyfaktor des Parlamentariers.'), // The help that appears on the UI,
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
      'title' => lt('E-Mail'), // The item it appears as on the UI,
      'help' => lt('E-Mail des Parlamentariers.'), // The help that appears on the UI,
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
      'title' => lt('Homepage'), // The item it appears as on the UI,
      'help' => lt('Homepage des Parlamentariers.'), // The help that appears on the UI,
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
      'title' => lt('Biografie Id'), // The item it appears as on the UI,
      'help' => lt('Biografie Id des Parlamentariers auf Parlament.ch.'), // The help that appears on the UI,
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
    'title' => lt('Erstellungsdatum'), // The item it appears as on the UI,
    'help' => lt('Erstellungsdatum des Parlamentariers.'), // The help that appears on the UI,
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
    'title' => lt('Aktualisierungsdatum'), // The item it appears as on the UI,
    'help' => lt('Aktualisierungsdatum des Parlamentariers.'), // The help that appears on the UI,
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
    'title' => lt('Freigabedatum'), // The item it appears as on the UI,
    'help' => lt('Veröffentlichungsdatum des Parlamentariers.'), // The help that appears on the UI,
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
      'title' => lt('Freigegeben oder Admin'),
      'help' => lt('Filters out unpublished lobbywatch content if the current user cannot view it.'),
      'filter' => array(
          'field' => 'freigabe_datum',
          'handler' => 'lobbywatch_handler_filter_released_or_admin',
          'label' => t('Released or admin'),
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

  $data['v_parlamentarier']['released'] = array(
      'title' => lt('Veröffentlicht'), // The item it appears as on the UI,
      'help' => lt('Veröffentlicht'), // The help that appears on the UI,
      'field' => array(
          'field' => 'freigabe_datum',
          'handler' => 'lobbywatch_handler_field_released',
      ),
      'filter' => array(
          'field' => 'freigabe_datum',
          'handler' => 'lobbywatch_handler_filter_released',
          'label' => t('Released'),
      ),
  );



  // ==== Kommission ====
  // Define the base group of this table. Fields that don't
  // have a group defined will go into this field by default.
  $data['v_kommission']['table']['group'] = lt('Kommission');

  // Advertise this table as a possible base table
  $data['v_kommission']['table']['base'] = array(
      'field' => 'id',
      'title' => lt('Kommission'),
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
      'title' => lt('Id'),
      'help' => lt('Der Schlüssel der Kommission.'), // The help that appears on the UI,
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
      'title' => lt('Abkürzung'), // The item it appears as on the UI,
      'help' => lt('Abkürzung der Kommission.'), // The help that appears on the UI,
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
      'title' => lt('Name'), // The item it appears as on the UI,
      'help' => lt('Name der Kommission.'), // The help that appears on the UI,
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

  $data['v_in_kommission']['table']['group']  = lt('In Kommission');

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
      'title' => lt('Parlamentarier in Kommission'),
      'help' => lt('Parlamentarier in Kommission'),
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
      '***ACCESS_ADVANCED_LOBBYWATCH_CONTENT***' => intval(user_access('access lobbywatch advanced content')),
      '***ACCESS_ADMIN_LOBBYWATCH_CONTENT***' => intval(user_access('access lobbywatch admin')),
      '***LANG_SUFFIX***' => get_lang_suffix(),
  );
}

