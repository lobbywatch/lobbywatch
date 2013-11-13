#!/bin/bash

cat ../data/lobbycontrol.sql \
| perl -p -e's/timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP/timestamp NULL DEFAULT NULL/ig' \
| perl -p -e's/`lobbycontrol`/`csvimsne_lobbycontrol`/ig' \
> ../data/prod_lobbycontrol.sql;
