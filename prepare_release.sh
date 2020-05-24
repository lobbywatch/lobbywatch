#!/bin/bash

env_suffix=$1
env_dir=$2
env_dir_escaped=${env_dir//\//\\\/}
env_dir2=$3
env_dir2_escaped=${env_dir2//\//\\\/}
db_base_name=lobbywat_lobbywatch

# echo "$env_dir2 -> $env_dir2_escaped"

# Serveral automatic CURRENT_TIMESTAMP are only supported for MySQL 5.6.5 or later, https://dev.mysql.com/doc/refman/5.6/en/timestamp-initialization.html
cat lobbywatch.sql \
| perl -p -e's/timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP/timestamp NULL DEFAULT NULL/ig' \
| perl -p -e's/timestamp NULL DEFAULT CURRENT_TIMESTAMP/timestamp NULL DEFAULT NULL/ig' \
| perl -p -e"s/\`lobbywatch\`/\`$db_base_name$env_suffix\`/ig" \
| perl -p -e's/(lobbywatch\.ch\/app\/)(files)/\1'''$env_dir_escaped'''\2/ig' \
| perl -p -e's/(\/lobbywatch_db_files)/\1'''$env_dir2_escaped'''/ig' \
| perl -p -e's/(UNIQUE KEY `\w*?` \(`\w*?`\)) COMMENT '.*?',/\1,/ig' \
| perl -p -e's/COMMENT .Fachlicher unique constraint: Name muss einzigartig sein.//ig' \
| perl -p -e's/COMMENT .Fachlicher unique constraint.//ig' \
| perl -p -e's/DEFINER=.*? SQL SECURITY DEFINER//ig' \
| perl -p -e's/DEFINER=`.*?`@`localhost` //ig' \
| perl -p -e's/^USE /SET collation_connection = '\''utf8mb4_unicode_ci'\'';\nUSE /ig' \
> lobbywatch_cleaned.sql

cp -u lobbywatch_datenmodell.pdf public_html/
cp -u lobbywatch_datenmodell_1page.pdf public_html/
cp -u lobbywatch_datenmodell_simplified.pdf public_html/

