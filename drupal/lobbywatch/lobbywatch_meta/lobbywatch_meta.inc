<?php

function _lobbywatch_meta() {
  $blocks = [];
  $block_regions = array(
    'rooster_home',
    'rooster_parliamentarians', 'rooster_lobbygroups', 'rooster_guests',
    'rooster_noresults'
  );
  foreach ($block_regions as $region) {
    $raw_blocks = block_list($region);
    foreach ($raw_blocks as $key => &$raw_block) {
      if (!empty($raw_block->content['#markup'])) {
        $blocks[] = array(
          'region' => $region,
          'key' => $key,
          'title' => $raw_block->title,
          'content' => $raw_block->content['#markup'],
        );
      }
    }
  }

  $lang = get_lang();
  $links = [];
  $raw_links = menu_load_links('menu-rooster');
  foreach ($raw_links as &$raw_link) {
    if (!$raw_link['hidden'] && ($raw_link['language'] == $lang || $raw_link['language'] == 'und')) {
      $translated_link = menu_link_load($raw_link['mlid']);

      $translation_nodes = translation_path_get_translations($translated_link['link_path']);
      $translated_node = $translation_nodes[$lang];

      $path = empty($translated_node) ? $translated_link['link_path'] : $translated_node;
      $links[] = array(
        'id' => $raw_link['mlid'],
        'parentId' => $raw_link['plid'],
        'title' => $translated_link['title'],
        'href' => url($path, array(
          'query' => isset($translated_link['query']) ? $translated_link['query'] : []
        ))
      );
    }
  }

  drupal_json_output(array(
    'blocks' => $blocks,
    'links' => $links
  ));
  drupal_exit();
}

function _lobbywatch_meta_property_access_filter($wrapper) {
  $filtered = array();
  foreach ($wrapper as $name => $property) {
    try {
      if ($property->access('view')) {
        $filtered[$name] = $property;
      }
    }
    // Some properties like entity_metadata_book_get_properties() throw
    // exceptions, so we catch them here and ignore the property (deny access).
    catch (EntityMetadataWrapperException $e) {}
  }
  return $filtered;
}

function _lobbywatch_meta_get_data($wrapper, $recursive = FALSE, $load_types = array(), $data = array()) {
  $filtered = _lobbywatch_meta_property_access_filter($wrapper);

  foreach ($filtered as $name => $property) {
    try {
      if ($property instanceof EntityDrupalWrapper) {
        if ($recursive && in_array($property->type(), $load_types)) {
          $data[$name] = _lobbywatch_meta_get_data(
            entity_metadata_wrapper($property->type(), $property->value())
          );
          if ($property->type() === 'file') {
            $data = $data[$name];
          }
        }
      }
      elseif ($property instanceof EntityValueWrapper) {
        $data[$name] = $property->value();
      }
      elseif ($property instanceof EntityListWrapper || $property instanceof EntityStructureWrapper) {
        $data[$name] = _lobbywatch_meta_get_data($property, $recursive, $load_types);
      }
    }
    catch (EntityMetadataWrapperException $e) {
      // A property causes problems - ignore that.
    }
  }
  return $data;
}

function _lobbywatch_meta_get_node_data($values) {
  $wrapper = entity_metadata_wrapper('node', $values);

  $translations = translation_path_get_translations('node/'.$values->nid);
  foreach ($translations as $lang => $path) {
    $translations[$lang] = drupal_get_path_alias($path, $lang);
  }

  $load_types = isset($_GET['load-entity-refs'])
    ? explode(',', $_GET['load-entity-refs'])
    : array();

  $data = _lobbywatch_meta_get_data($wrapper, TRUE, $load_types, array(
    'path' => drupal_get_path_alias('node/'.$values->nid),
    'translations' => $translations
  ));

  if (empty($data['field_author'])) {
    $data['field_author'] = $wrapper->author->name->value();
  }

  return $data;
}

function _lobbywatch_meta_load_nodes($ids) {
  $dataSet = entity_load('node', $ids);

  return array_map('_lobbywatch_meta_get_node_data', array_values($dataSet));
}

function _lobbywatch_meta_page() {
  list($type, $id) = explode('/', drupal_get_normal_path($_GET['url']));
  $data = _lobbywatch_meta_load_nodes([$id]);

  if (!$data[0]) {
    drupal_add_http_header('Status', '404', $append = FALSE);
  }
  drupal_json_output($data[0]);
  drupal_exit();
}

function _lobbywatch_meta_articles() {
  $lang = get_lang();
  $query = new EntityFieldQuery();
  $query
    ->entityCondition('entity_type', 'node')
    ->entityCondition('bundle', 'article')
    ->propertyCondition('language', $lang)
    ->propertyCondition('status', NODE_PUBLISHED)
    ->propertyCondition('promote', 1)
    ->propertyOrderBy('created', 'DESC');

  $limit = min(array(100, isset($_GET['limit']) ? intval($_GET['limit']) : 10));
  $offset = isset($_GET['page']) ? intval($_GET['page']) : 0;
  $offset *= $limit;

  $query->range($offset, $limit);

  try {
    $query_result = $query->execute();
  }
  catch (PDOException $exception) {
    throw new RestWSException('Query failed.', 400);
  }
  $query_result = isset($query_result['node']) ? $query_result['node'] : array();

  $articleIds = array_keys($query_result);

  $query = new EntityFieldQuery();
  $query
    ->entityCondition('entity_type', 'node')
    ->entityCondition('bundle', 'article')
    ->propertyCondition('language', $lang)
    ->propertyCondition('status', NODE_PUBLISHED)
    ->propertyCondition('promote', 1);
  $total = $query->count()->execute();

  drupal_json_output(array(
    'total' => intval($total),
    'pages' => floor($total / $limit),
    'list' => _lobbywatch_meta_load_nodes($articleIds)
  ));
  drupal_exit();
}
function _lobbywatch_meta_knowledge_articles() {
  $lang = get_lang();
  $query = new EntityFieldQuery();
  $query
    ->entityCondition('entity_type', 'node')
    ->entityCondition('bundle', 'knowledge_article')
    ->propertyCondition('language', $lang)
    ->propertyCondition('status', NODE_PUBLISHED)
    ->propertyOrderBy('nid', 'ASC');

  $limit = min(array(100, isset($_GET['limit']) ? intval($_GET['limit']) : 10));
  $offset = isset($_GET['page']) ? intval($_GET['page']) : 0;
  $offset *= $limit;

  $query->range($offset, $limit);

  try {
    $query_result = $query->execute();
  }
  catch (PDOException $exception) {
    throw new RestWSException('Query failed.', 400);
  }
  $query_result = isset($query_result['node']) ? $query_result['node'] : array();

  $articleIds = array_keys($query_result);

  $query = new EntityFieldQuery();
  $query
    ->entityCondition('entity_type', 'node')
    ->entityCondition('bundle', 'article')
    ->propertyCondition('language', $lang)
    ->propertyCondition('status', NODE_PUBLISHED)
    ->propertyCondition('promote', 1);
  $total = $query->count()->execute();

  drupal_json_output(array(
    'total' => intval($total),
    'pages' => floor($total / $limit),
    'list' => _lobbywatch_meta_load_nodes($articleIds)
  ));
  drupal_exit();
}
