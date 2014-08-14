<?php

/**
 * $ /opt/lampp/bin/php -f minify_css.php public_html/bearbeitung/components/css/user.css
 */

/**
* Processes the contents of a stylesheet for aggregation.
*
* @param $contents
*   The contents of the stylesheet.
* @param $optimize
*   (optional) Boolean whether CSS contents should be minified. Defaults to
*   FALSE.
*
* @return
*   Contents of the stylesheet including the imported stylesheets.
*/
function drupal_load_stylesheet_content($contents, $optimize = FALSE) {
  // Remove multiple charset declarations for standards compliance (and fixing Safari problems).
  $contents = preg_replace('/^@charset\s+[\'"](\S*?)\b[\'"];/i', '', $contents);

  if ($optimize) {
    // Perform some safe CSS optimizations.
    // Regexp to match comment blocks.
    $comment     = '/\*[^*]*\*+(?:[^/*][^*]*\*+)*/';
    // Regexp to match double quoted strings.
    $double_quot = '"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"';
    // Regexp to match single quoted strings.
    $single_quot = "'[^'\\\\]*(?:\\\\.[^'\\\\]*)*'";
    // Strip all comment blocks, but keep double/single quoted strings.
    $contents = preg_replace(
        "<($double_quot|$single_quot)|$comment>Ss",
        "$1",
        $contents
    );
    // Remove certain whitespace.
    // There are different conditions for removing leading and trailing
    // whitespace.
    // @see http://php.net/manual/regexp.reference.subpatterns.php
    $contents = preg_replace('<
      # Strip leading and trailing whitespace.
        \s*([@{};,])\s*
      # Strip only leading whitespace from:
      # - Closing parenthesis: Retain "@media (bar) and foo".
      | \s+([\)])
      # Strip only trailing whitespace from:
      # - Opening parenthesis: Retain "@media (bar) and foo".
      # - Colon: Retain :pseudo-selectors.
      | ([\(:])\s+
    >xS',
        // Only one of the three capturing groups will match, so its reference
        // will contain the wanted value and the references for the
        // two non-matching groups will be replaced with empty strings.
        '$1$2$3',
        $contents
    );
    // End the file with a new line.
    $contents = trim($contents);
    $contents .= "\n";
  }

  // Replaces @import commands with the actual stylesheet content.
  // This happens recursively but omits external files.
  // ibex
//   $contents = preg_replace_callback('/@import\s*(?:url\(\s*)?[\'"]?(?![a-z]+:)([^\'"\()]+)[\'"]?\s*\)?\s*;/', '_drupal_load_stylesheet', $contents);
  return $contents;
}


echo drupal_load_stylesheet_content(file_get_contents($argv[1]), true);