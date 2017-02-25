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
