#!/bin/bash

# Abort on errors
set -e

NPM=npm20

$NPM i

root_dir=public_html
js_dir=$root_dir/bearbeitung/components/js
file=main-bundle-custom.js
bundle_hash_file=$root_dir/custom/hash_js_main_bundle.php

# Uglify file: /home/rkurmann/dev/web/lobbywatch/lobbydev/public_html/bearbeitung/components/js/main-bundle-custom.js
# Error: Cannot uglify file: /home/rkurmann/dev/web/lobbywatch/lobbydev/public_html/bearbeitung/components/js/main-bundle-custom.js. Skipping it. Error is:
# SyntaxError: Unexpected token operator «=», expected punc «,»

# If the source uses ES2015 or later syntax, please pass "optimize: 'none'" to r.js and use an ES2015+ compatible minifier after running r.js. The included UglifyJS only understands ES5 or earlier syntax.

# Uses Require.js http://requirejs.org/docs/optimization.html
node_modules/requirejs/bin/r.js -o require-build-custom.js
# baseUrl=$js_dir out=$js_dir/main-bundle-custom.js

node_modules/terser/bin/terser public_html/bearbeitung/components/js/main-bundle-custom.raw.js -c ecma=2016 > public_html/bearbeitung/components/js/main-bundle-custom.js

mv $bundle_hash_file $bundle_hash_file.bak
echo -e "<?php\n// Generated file\n\$hash_js_main_bundle = '`sha1sum $js_dir/$file | cut -c -7`';" > $bundle_hash_file
diff -u0 $bundle_hash_file.bak $bundle_hash_file | tail -2

# Debug mode
if  [[ "$1" == "-d" ]] ; then
    # Create files which are split at define( and function in order to be able to compare the files
    cat $js_dir/main-bundle.js        | sed 's/\(define(\|function\)/\n\1/g' > $js_dir/main-bundle-split.js
    cat $js_dir/main-bundle-custom.js | sed 's/\(define(\|function\)/\n\1/g' > $js_dir/main-bundle-custom-split.js
    echo "diff -u --color=always $js_dir/main-bundle-split.js $js_dir/main-bundle-custom-split.js | less"
fi
