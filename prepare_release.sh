#!/bin/bash

env_suffix=$1

cat ../data/lobbycontrol.sql \
| perl -p -e's/timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP/timestamp NULL DEFAULT NULL/ig' \
| perl -p -e"s/\`lobbycontrol\`/\`csvimsne_lobbycontrol$env_suffix\`/ig" \
| perl -p -e's/(UNIQUE KEY `\w*?` \(`\w*?`\)) COMMENT '.*?',/\1,/ig' \
| perl -p -e's/COMMENT .Fachlicher unique constraint.//ig' \
| perl -p -e's/DEFINER=.*? SQL SECURITY DEFINER//ig' \
> ../data/deploy_lobbycontrol.sql;
