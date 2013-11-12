#!/bin/bash

root_dir=public_html
dir=$root_dir/settings

rm `find $root_dir -name '*.bak'` -v

cat ../data/lobbycontrol.sql \
| perl -p -e's/timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP/timestamp NULL DEFAULT NULL/ig' \
> ../data/prod_lobbycontrol.sql;
