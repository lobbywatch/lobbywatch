#!/bin/bash

cat ../data/lobbycontrol.sql \
| perl -p -e's/timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP/timestamp NULL DEFAULT NULL/ig' \
| perl -p -e's/`lobbycontrol`/`csvimsne_lobbycontrol`/ig' \
| perl -p -e's/(UNIQUE KEY `\w*?` \(`\w*?`\)) COMMENT '.*?',/\1,/ig' \
| perl -p -e's/COMMENT .Fachlicher unique constraint.//ig' \
> ../data/prod_lobbycontrol.sql;
