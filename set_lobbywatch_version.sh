#!/bin/bash

# Abort on errors
set -e

dir=$1

if [[ "$dir" = "" ]] ; then
  dir=public_html
fi

# NOW=$(date +"%d.%m.%Y %H:%M");
# NOW_SHORT=$(date +"%d.%m.%Y");
# echo -e "<?php\n\$deploy_date = '$NOW';\n\$deploy_date_short = '$NOW_SHORT';" > $public_dir/common/deploy_date.php;

# Also in deploy.sh
# VERSION=$(git describe --abbrev=0 --tags)
# echo -e "<?php\n\$version = '$VERSION';" >  $root_dir/common/version.php;

# Also in afterburner.sh
VERSION=$(git describe --abbrev=0 --tags)
echo -e "<?php\n/**\n * The current system version.\n */\ndefine('LOBBYWATCH_VERSION', '$VERSION');\n\$version = LOBBYWATCH_VERSION;\n\$lobbywatch_version = LOBBYWATCH_VERSION;" >  $dir/common/version.php;

