#!/bin/bash

# Abort on errors
set -e

root_dir=public_html
dir=$root_dir/bearbeitung
assets_dir=$dir/components/assets
main_css=$assets_dir/css/main.css
bundle_hash_file_main=$root_dir/custom/hash_css_main.php
bundle_hash_file_custom=$root_dir/custom/hash_css_custom.php

# https://www.sqlmaestro.com/products/mysql/phpgenerator/help/02_04_00_style_sheets_internals/
# https://github.com/dotless/dotless/wiki/Using-.less
# http://lesscss.org/features/#features-overview-feature
WINE="wine"
WINE_LESS=~/.wine/drive_c/Program\ Files\ \(x86\)/Common\ Files/SQL\ Maestro\ Group/DotLess/dotless.Compiler.exe

# Overwrite main.css since generator did not include custom/custom.less
mv $main_css ${main_css}.bak || echo "'$main_css' did not exist"
# echo $WINE "$WINE_LESS -r $assets_dir/less/main.less $main_css"
$WINE "$WINE_LESS" -r "$@" $assets_dir/less/main.less $main_css

mv $bundle_hash_file_main $bundle_hash_file_main.bak
echo -e "<?php\n\$hash_css_main = '`sha1sum $main_css | cut -c -7`';" > $bundle_hash_file_main
diff -u0 $bundle_hash_file_main.bak $bundle_hash_file_main | tail -2

mv $bundle_hash_file_custom $bundle_hash_file_custom.bak
echo -e "<?php\n\$hash_css_custom = '`sha1sum $assets_dir/css/custom/custom.css | cut -c -7`';" > $bundle_hash_file_custom
diff -u0 $bundle_hash_file_custom.bak $bundle_hash_file_custom | tail -2

