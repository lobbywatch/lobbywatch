#!/bin/bash

# Call: ./run_update_ws_parlament.sh
# Only Parlamentarier: ./run_update_ws_parlament.sh -Z
# Only ZB: ./run_update_ws_parlament.sh -P
# ZB Test: ./run_update_ws_parlament.sh -B -P
# ZB Test with DB import: ./run_update_ws_parlament.sh -i -P

# TODO Add cron mode, which checks return codes and sends email in case of problem
# TODO Add automatic mode without user interaction

# Include common functions
. common.sh

db=lobbywatchtest
nobackup=false
import=false
refresh=""
noparlam=false
nozb=false
zb_script_path=web_scrapers
ZB_CHANGED=false

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
                        echo "-P, --noparlam            Do not run parlamentarier script"
                        echo "-Z, --nozb                Do not run zutrittsberechtigten script"
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
                -P|--noparlam)
                        noparlam=true
                        shift
                        ;;
                -Z|nozb)
                        nozb=true
                        shift
                        ;;
                *)
                        break
                        ;;
        esac
done

checkLocalMySQLRunning

if $import ; then
  askContinueYn "Import 'prod_bak/`cat prod_bak/last_dbdump_data.txt`' to local '$db?'"

  # ./run_local_db_script.sh $db prod_bak/`cat prod_bak/last_dbdump_data.txt`
  ./deploy.sh -q -l=$db -s prod_bak/`cat prod_bak/last_dbdump_data.txt`

  beep
elif ! $nobackup ; then
  askContinueYn "Import PROD DB to local '$db'?"

  ./run_db_prod_to_local.sh $db

  beep
fi

if ! $noparlam ; then
  askContinueYn "Run ws_parlament_fetcher.php?"
  export SYNC_FILE=sql/ws_parlament_ch_sync_`date +"%Y%m%d"`.sql; php -f ws_parlament_fetcher.php -- -pks | tee $SYNC_FILE

  beep
  less $SYNC_FILE

  askContinueYn "Run SQL in local $db?"
  # ./run_local_db_script.sh $db $SYNC_FILE
  ./deploy.sh -q -l=$db -s $SYNC_FILE
fi

if ! $nozb ; then
  askContinueYn "Run zutrittsberechtigten (zb) python?"
  echo "Writing zb.json..."
  python3 $zb_script_path/create_json.py
  echo "Writing zb_delta.sql..."
  export ZB_DELTA_FILE=sql/zb_delta_`date +"%Y%m%d"`.sql; python3 $zb_script_path/create_delta.py | tee $ZB_DELTA_FILE
  
  grep -q "DATA CHANGED" $ZB_DELTA_FILE
  STATUS=$?
  if (($STATUS == 0)) ; then
    ZB_CHANGED=true
    less $ZB_DELTA_FILE
    echo -e "\nZutrittsberechtigten data ${greenBold}CHANGED${reset}"
    askContinueYn "Run zb SQL in local $db?"
    ./deploy.sh -q -l=$db -s $ZB_DELTA_FILE
  else
      ZB_CHANGED=false
      echo -e "\nZutrittsberechtigten data ${greenBold}UNCHANGED${reset}"
  fi
fi

if [[ "$refresh" == "-r" ]] ; then
  # ./run_local_db_script.sh $db
  ./deploy.sh -q -l=$db -r

  beep
fi

if $import || ! $nobackup ; then
  askContinueYn "Import DB in remote TEST?"
  ./deploy.sh -q -s prod_bak/`cat prod_bak/last_dbdump_data.txt`

  beep
fi

if ! $noparlam ; then
  askContinueYn "Run parlam SQL in remote TEST?"
  ./deploy.sh $refresh -q -s $SYNC_FILE
fi

if ! $nozb && $ZB_CHANGED ; then
  askContinueYn "Run zb SQL in remote TEST?"
  ./deploy.sh $refresh -q -s $ZB_DELTA_FILE
fi

if ! $noparlam ; then
  askContinueYn "Run parlam SQL in remote PROD?"
  ./deploy.sh -p $refresh -q -s $SYNC_FILE
fi

if ! $nozb && $ZB_CHANGED ; then
  askContinueYn "Run zb SQL in remote PROD?"
  ./deploy.sh -p $refresh -q -s $ZB_DELTA_FILE
fi
