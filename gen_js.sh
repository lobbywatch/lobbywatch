#!/bin/bash

# Abort on errors
set -e

root_dir=public_html
js_dir=$root_dir/bearbeitung/components/js
file=main-bundle-custom.js
bundle_hash_file=$root_dir/custom/hash_js_main_bundle.php

# Uses Require.js http://requirejs.org/docs/optimization.html
r.js -o require-build-custom.js
# baseUrl=$js_dir out=$js_dir/main-bundle-custom.js

mv $bundle_hash_file $bundle_hash_file.bak
echo -e "<?php\n\$hash_js_main_bundle = '`sha1sum $js_dir/$file | cut -c -7`';" > $bundle_hash_file
diff -u0 $bundle_hash_file.bak $bundle_hash_file | tail -2

# Debug mode
if  [[ "$1" == "-d" ]] ; then
    # Create files which are split at define( and function in order to be able to compare the files
    cat $js_dir/main-bundle.js        | sed 's/\(define(\|function\)/\n\1/g' > $js_dir/main-bundle-split.js
    cat $js_dir/main-bundle-custom.js | sed 's/\(define(\|function\)/\n\1/g' > $js_dir/main-bundle-custom-split.js
    echo "diff -u --color=always $js_dir/main-bundle-split.js $js_dir/main-bundle-custom-split.js | less"
fi
