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
nomail=false
quiet=false
test=false

while test $# -gt 0; do
        case "$1" in
                -h|--help)
                        echo "Update Lobbywatch DB from ws.parlament.ch"
                        echo " "
                        echo "$0 [options]"
                        echo " "
                        echo "Options:"
                        echo "-B, --nobackup            No remote prod backup"
                        echo "-M, --nomail              No email notification"
                        echo "-i, --import              Import last remote prod backup, no backup (implies -B)"
                        echo "-r, --refresh             Refresh views"
                        echo "-P, --noparlam            Do not run parlamentarier script"
                        echo "-Z, --nozb                Do not run zutrittsberechtigten script"
                        echo "-q                        Quiet and automatic"
                        echo "-t                        Test mode (no changes on production)"
                        exit 0
                        ;;
                -B|--nobackup)
                        nobackup=true
                        shift
                        ;;
                -M|--nomail)
                        nomail=true
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
                -q)
                        quiet=true
                        shift
                        ;;
                -t)
                        test=true
                        shift
                        ;;
                *)
                        break
                        ;;
        esac
done

checkLocalMySQLRunning

if $import ; then
  if ! $quiet ; then
    askContinueYn "Import 'prod_bak/`cat prod_bak/last_dbdump_data.txt`' to local '$db?'"
  fi

  # ./run_local_db_script.sh $db prod_bak/`cat prod_bak/last_dbdump_data.txt`
  ./deploy.sh -q -l=$db -s prod_bak/`cat prod_bak/last_dbdump_data.txt`

  if ! $quiet ; then
    beep
  fi
elif ! $nobackup ; then
  if ! $quiet ; then
    askContinueYn "Import PROD DB to local '$db'?"
  fi

  ./run_db_prod_to_local.sh $db

  if ! $quiet ; then
    beep
  fi
fi

if ! $noparlam ; then
  if ! $quiet ; then
    askContinueYn "Run ws_parlament_fetcher.php?"
  fi
  export SYNC_FILE=sql/ws_parlament_ch_sync_`date +"%Y%m%d"`.sql; php -f ws_parlament_fetcher.php -- -pks | tee $SYNC_FILE

  if ! $quiet ; then
    beep
    less $SYNC_FILE
    askContinueYn "Run SQL in local $db?"
  fi

  # ./run_local_db_script.sh $db $SYNC_FILE
  ./deploy.sh -q -l=$db -s $SYNC_FILE
fi

if ! $nozb ; then
  if ! $quiet ; then
    askContinueYn "Run zutrittsberechtigten (zb) python?"
  fi
  echo "Writing zb.json..."
  python3 $zb_script_path/create_json.py
  echo "Writing zb_delta.sql..."
  export ZB_DELTA_FILE=sql/zb_delta_`date +"%Y%m%d"`.sql; python3 $zb_script_path/create_delta.py | tee $ZB_DELTA_FILE

  grep -q "DATA CHANGED" $ZB_DELTA_FILE
  STATUS=$?
  if (($STATUS == 0)) ; then
    ZB_CHANGED=true
    if ! $quiet ; then
      less $ZB_DELTA_FILE
    fi
    echo -e "\nZutrittsberechtigten data ${greenBold}CHANGED${reset}"
    if ! $quiet ; then
      askContinueYn "Run zb SQL in local $db?"
    fi
    ./deploy.sh -q -l=$db -s $ZB_DELTA_FILE
  else
      ZB_CHANGED=false
      echo -e "\nZutrittsberechtigten data ${greenBold}UNCHANGED${reset}"
  fi
fi

if [[ "$refresh" == "-r" ]] ; then
  # ./run_local_db_script.sh $db
  ./deploy.sh -q -l=$db -r

  if ! $quiet ; then
    beep
  fi
fi

if ($import || ! $nobackup) && ! $test ; then
  if ! $quiet ; then
    askContinueYn "Import DB in remote TEST?"
  fi
  ./deploy.sh -q -s prod_bak/`cat prod_bak/last_dbdump_data.txt`

  if ! $quiet ; then
    beep
  fi
fi

if ! $noparlam && ! $test ; then
  if ! $quiet ; then
    askContinueYn "Run parlam SQL in remote TEST?"
  fi
  ./deploy.sh $refresh -q -s $SYNC_FILE
fi

if ! $nozb && $ZB_CHANGED && ! $test ; then
  if ! $quiet ; then
    askContinueYn "Run zb SQL in remote TEST?"
  fi
  ./deploy.sh $refresh -q -s $ZB_DELTA_FILE
fi

if ! $noparlam && ! $test; then
  if ! $quiet ; then
    askContinueYn "Run parlam SQL in remote PROD?"
  fi
  ./deploy.sh -p $refresh -q -s $SYNC_FILE
fi

if ! $nozb && $ZB_CHANGED && ! $test ; then
  if ! $quiet ; then
    askContinueYn "Run zb SQL in remote PROD?"
  fi
  ./deploy.sh -p $refresh -q -s $ZB_DELTA_FILE
fi

# SYNC_FILE=sql/ws_parlament_ch_sync_20170601.sql
# ZB_DELTA_FILE=sql/zb_delta_20170606.sql
# ZB_CHANGED=true
if ! $nomail ; then
    if $test ; then
        to=test@lobbywatch.ch
    else
        to=redaktion@lobbwatch.ch,admin@lobbywatch.ch
    fi
    subject="Lobbywatch-Import: Parlamentarier"
    tmp_body=/tmp/mail_body.txt
    echo > $tmp_body
    cat $SYNC_FILE >> $tmp_body
    fzb=""
    if $ZB_CHANGED ; then
        subject="$subject + Zutrittsberechtigte"
        fzb=$ZB_DELTA_FILE
         echo >> $tmp_body
         printf "%0.s*" {1..50} >> $tmp_body
         echo >> $tmp_body
         cat $fzb >> $tmp_body
    fi
    cat $tmp_body | php -f mail_notification.php -- -s"$subject" -t"$to" "$SYNC_FILE" "$fzb"
fi
