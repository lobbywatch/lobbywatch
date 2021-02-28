#!/bin/bash

SCRIPT_DIR=`dirname "$0"`
. $SCRIPT_DIR/phpgen_config.sh

cd $HOME/dev/web/lobbywatch/lobbydev/
wine "$PHPGEN_EXE" "Z:\home\rkurmann\dev\web\lobbywatch\lobbydev\lobbywatch_bearbeitung.pgtm"
