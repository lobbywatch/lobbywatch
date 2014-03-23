#!/bin/bash

env_suffix=$1
env_dir=$2
env_dir_escaped=${env_dir//\//\\\/}
env_dir2=$3
env_dir2_escaped=${env_dir2//\//\\\/}
# echo "$env_dir2 -> $env_dir2_escaped"

cat ../data/lobbywatch.sql \
| perl -p -e's/timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP/timestamp NULL DEFAULT NULL/ig' \
| perl -p -e"s/\`lobbywatch\`/\`csvimsne_lobbywatch$env_suffix\`/ig" \
| perl -p -e's/(lobbywatch\.ch\/app\/)(files)/\1'''$env_dir_escaped'''\2/ig' \
| perl -p -e's/(\/lobbywatch_db_files)/\1'''$env_dir2_escaped'''/ig' \
| perl -p -e's/(UNIQUE KEY `\w*?` \(`\w*?`\)) COMMENT '.*?',/\1,/ig' \
| perl -p -e's/COMMENT .Fachlicher unique constraint.//ig' \
| perl -p -e's/DEFINER=.*? SQL SECURITY DEFINER//ig' \
| perl -p -e's/DEFINER=`root`@`localhost` //ig' \
> ../data/deploy_lobbywatch.sql;

cp lobbywatch_datenmodell.pdf public_html/
cp lobbywatch_datenmodell_1page.pdf public_html/
cp lobbywatch_datenmodell_simplified.pdf public_html/

