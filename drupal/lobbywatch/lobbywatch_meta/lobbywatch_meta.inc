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
      $links[] = array(
        'id' => $raw_link['mlid'],
        'parentId' => $raw_link['plid'],
        'title' => t($raw_link['link_title']),
        'href' => url($raw_link['link_path'], array(
          'query' => isset($raw_link['query']) ? $raw_link['query'] : []
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
