#!/bin/bash

root_dir=public_html
dir=$root_dir/bearbeitung

# https://www.sqlmaestro.com/products/mysql/phpgenerator/help/02_04_00_style_sheets_internals/
# https://github.com/dotless/dotless/wiki/Using-.less
# http://lesscss.org/features/#features-overview-feature
WINE="wine"
WINE_LESS=~/.wine/drive_c/Program\ Files\ \(x86\)/Common\ Files/SQL\ Maestro\ Group/DotLess/dotless.Compiler.exe

# Overwrite main.css since generator did not include custom/custom.less
assets_dir=$dir/components/assets
mv $assets_dir/css/main.css $assets_dir/css/main.css.bak
#echo $WINE "$WINE_LESS -r $assets_dir/less/main.less $assets_dir/css/main.css"
$WINE "$WINE_LESS" -r $assets_dir/less/main.less $assets_dir/css/main.css
