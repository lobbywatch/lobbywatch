#!/bin/bash

root_dir=public_html
js_dir=$root_dir/bearbeitung/components/js

# Uses Require.js http://requirejs.org/docs/optimization.html
r.js -o require-build-custom.js
# baseUrl=$js_dir out=$js_dir/main-bundle-custom.js

# Debug mode
if  [[ "$1" != "-d" ]] ; then
    # Create files which are split at define( and function in order to be able to compare the files
    cat $js_dir/main-bundle.js | sed 's/\(define(\|function\)/\n\1/g' > $js_dir/main-bundle-split.js
    cat $js_dir/main-bundle-custom.js | sed 's/\(define(\|function\)/\n\1/g' > $js_dir/main-bundle-custom-split.js
fi
