#!/bin/bash

root_dir=public_html
dir=$root_dir/bearbeitung
assets_dir=$dir/components/assets
main_css=$assets_dir/css/main.css

# https://www.sqlmaestro.com/products/mysql/phpgenerator/help/02_04_00_style_sheets_internals/
# https://github.com/dotless/dotless/wiki/Using-.less
# http://lesscss.org/features/#features-overview-feature
WINE="wine"
WINE_LESS=~/.wine/drive_c/Program\ Files\ \(x86\)/Common\ Files/SQL\ Maestro\ Group/DotLess/dotless.Compiler.exe

# Overwrite main.css since generator did not include custom/custom.less
mv $main_css ${main_css}.bak
#echo $WINE "$WINE_LESS -r $assets_dir/less/main.less $main_css"
$WINE "$WINE_LESS" -r "$@" $assets_dir/less/main.less $main_css

echo -e "<?php\n\$hash_css_main = '`sha1sum $main_css | cut -c -7`';" > $root_dir/custom/hash_css_main.php
echo -e "<?php\n\$hash_css_custom = '`sha1sum $assets_dir/css/custom/custom.css | cut -c -7`';" > $root_dir/custom/hash_css_custom.php
