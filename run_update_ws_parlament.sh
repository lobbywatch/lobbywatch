#!/bin/bash

# Call: ./run_update_ws_parlament.sh

# Include common functions
. common.sh

db=lobbywatchtest
nobackup=false
import=false
refresh=""

while test $# -gt 0; do
        case "$1" in
                -h|--help)
                        echo "Update Lobbywatch DB from ws.parlament.ch"
                        echo " "
                        echo "$0 [options]"
                        echo " "
                        echo "Options:"
                        echo "-i, --import              Import last remote prod backup (implies -B)"
                        echo "-B, --nobackup            No remote prod backup"
                        echo "-r, --refresh             Refresh views"
                        exit 0
                        ;;
                -B|--nobackup)
                        shift
                        nobackup=true
                        ;;
                -r|--refresh)
                        shift
                        refresh="-r"
                        ;;
                -i|--import)
                        shift
                        import=true
                        ;;
                *)
                        break
                        ;;
        esac
done

if $import ; then
  ./run_local_db_script.sh $db prod_bak/`cat prod_bak/last_dbdump_data.txt`
elif ! $nobackup ; then
  ./db_prod_to_local.sh $db
fi

askContinueYn "Run ws_parlament_fetcher.php?"

export SYNC_FILE=sql/ws_parlament_ch_sync_`date +"%Y%m%d"`.sql; php -f ws_parlament_fetcher.php -- -pks | tee $SYNC_FILE; less $SYNC_FILE

askContinueYn "Run SQL in local $db?"

./run_local_db_script.sh $db $SYNC_FILE

if [[ "$refresh" == "-r" ]] ; then
  ./run_local_db_script.sh $db
fi

askContinueYn "Update DB in remote TEST?"

./deploy.sh -s prod_bak/`cat prod_bak/last_dbdump_data.txt`

askContinueYn "Run SQL in remote TEST?"

./deploy.sh $refresh -s $SYNC_FILE

askContinueYn "Run SQL in remote PROD?"

./deploy.sh -p $refresh -s $SYNC_FILE

