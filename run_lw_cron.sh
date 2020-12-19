#!/bin/bash

SCRIPT_DIR=$(dirname `realpath "$0"`)

test_param=""
LOG="$SCRIPT_DIR/run_update_ws_parlament.sh.log"
DB="lobbywatch"

while test $# -gt 0; do
    case $1 in
        -h|--help)
            echo "Cron script for Lobbywatch"
            echo
            echo "$0 [options]"
            echo
            echo "Options:"
            echo "-v, --verbose           Verbose mode (output to stdout)"
            echo "-t, --test              Test mode (-t, lobbywatchtest)"
            exit
        ;;
        -t|--test)
            test_param="-t"
            DB="lobbywatchtest"
            shift
        ;;
        -v|--verbose)
            LOG="/dev/stdout"
            shift
        ;;
        *)
            break
        ;;
    esac
done

cd $SCRIPT_DIR

# one liner
# (/bin/echo -e "\nCron run" && date -Iseconds && . ~/.keychain/$(hostname)-sh && cd ~/lobbywatch/lobbywatch && ./run_update_ws_parlament.sh -a -v -d -f -G -l=lobbywatch; echo "Cron end" && date -Iseconds) >> ~/lobbywatch/lobbywatch/run_update_ws_parlament.sh.log

echo -e "\nCron run" >> $LOG
date -Iseconds >> $LOG
. ~/.keychain/$(hostname)-sh
./run_update_ws_parlament.sh -a -v -d -f -l=$DB $test_param >> $LOG
echo "Cron end" >> $LOG
date -Iseconds >> $LOG

cd "$OLDPWD"
