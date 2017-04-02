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
                        echo "-B, --nobackup            No remote prod backup"
                        echo "-i, --import              Import last remote prod backup, no backup (implies -B)"
                        echo "-r, --refresh             Refresh views"
                        exit 0
                        ;;
                -B|--nobackup)
                        nobackup=true
                        shift
                        ;;
                -r|--refresh)
                        refresh="-r"
                        shift
                        ;;
                -i|--import)
                        import=true
                        shift
                        ;;
                *)
                        break
                        ;;
        esac
done

if $import ; then
  askContinueYn "Import 'prod_bak/`cat prod_bak/last_dbdump_data.txt`' to local '$db?'"
  # ./run_local_db_script.sh $db prod_bak/`cat prod_bak/last_dbdump_data.txt`
  ./deploy.sh -q -l=$db -s prod_bak/`cat prod_bak/last_dbdump_data.txt`
elif ! $nobackup ; then
  askContinueYn "Import PROD DB to local '$db'?"
  ./run_db_prod_to_local.sh $db
fi

askContinueYn "Run ws_parlament_fetcher.php?"
export SYNC_FILE=sql/ws_parlament_ch_sync_`date +"%Y%m%d"`.sql; php -f ws_parlament_fetcher.php -- -pks | tee $SYNC_FILE; less $SYNC_FILE

askContinueYn "Run SQL in local $db?"
# ./run_local_db_script.sh $db $SYNC_FILE
./deploy.sh -q -l=$db -s $SYNC_FILE

if [[ "$refresh" == "-r" ]] ; then
  # ./run_local_db_script.sh $db
  ./deploy.sh -q -l=$db -r
fi

if $import || ! $nobackup ; then
  askContinueYn "Import DB in remote TEST?"
  ./deploy.sh -q -s prod_bak/`cat prod_bak/last_dbdump_data.txt`
fi

askContinueYn "Run SQL in remote TEST?"
./deploy.sh $refresh -q -s $SYNC_FILE

askContinueYn "Run SQL in remote PROD?"
./deploy.sh -p $refresh -q -s $SYNC_FILE
