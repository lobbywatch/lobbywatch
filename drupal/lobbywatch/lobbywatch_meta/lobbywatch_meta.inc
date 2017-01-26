<?php

function _lobbywatch_meta() {
  $blocks = [];
  $raw_blocks = block_list('sidebar_first');
  foreach ($raw_blocks as $key => &$raw_block) {
    if (!empty($raw_block->content['#markup'])) {
      $blocks[] = array(
        'key' => $key,
        'title' => $raw_block->title,
        'content' => $raw_block->content['#markup'],
      );
    }
  }

  $lang = get_lang();
  $links = [];
  $raw_links = menu_load_links('main-menu');
  foreach ($raw_links as &$raw_link) {
    if (!$raw_link['hidden'] && ($raw_link['language'] == $lang || $raw_link['language'] == 'und')) {
      $links[] = array(
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
