#!/bin/bash

# Call: ./run_update_ws_parlament.sh
# Test: ./run_update_ws_parlament.sh -a -t -B -S -v
# Only Parlamentarier: ./run_update_ws_parlament.sh -Z
# Only ZB: ./run_update_ws_parlament.sh -P
# ZB Test: ./run_update_ws_parlament.sh -B -P
# ZB Test with DB import: ./run_update_ws_parlament.sh -i -P

# TODO Add cron mode, which checks return codes and sends email in case of problem

# Include common functions
. common.sh

enable_fail_onerror

db=lobbywatchtest
ARCHIVE_PDF_DIR="web_scrapers/archive"
MAIL_TO="redaktion@lobbywatch.ch,roland.kurmann@lobbywatch.ch,bane.lovric@lobbywatch.ch"
subject="Lobbywatch-Import:"
nobackup=false
nodownloadallbaks=false
downloadlastbak=false
import=false
refresh=""
noparlam=false
nozb=false
zb_script_path=web_scrapers
P_CHANGED=false
K_CHANGED=false
ZB_CHANGED=false
KP_ADDED=false
IMAGE_CHANGED=false
nomail=false
noimageupload=false
automatic=false
test=false
nosql=false
kommissionen="k"
verbose=false
tmp_mail_body=/tmp/mail_body.txt
after_import_DB_script=after_import_DB.sql
enable_after_import_script=false

while test $# -gt 0; do
        case "$1" in
                -h|--help)
                        echo "Update Lobbywatch DB from ws.parlament.ch"
                        echo " "
                        echo "$0 [options]"
                        echo " "
                        echo "Options:"
                        echo "-B, --nobackup            No remote prod backup"
                        echo "-d, --downloadlastbak     Only download last data backup (useful for development)"
                        echo "-D, --nodownloadallbaks   No download of all remote backups (useful for development)"
                        echo "-M, --nomail              No email notification"
                        echo "-i, --import              Import last remote prod backup, no backup (implies -B)"
                        echo "-r, --refresh             Refresh views"
                        echo "-P, --noparlam            Do not run parlamentarier script"
                        echo "-K, --nokommissionen      Do not run update Kommissionen"
                        echo "-I, --noimageupload       Do not upload changed images"
                        echo "-Z, --nozb                Do not run zutrittsberechtigten script"
                        echo "-a, --automatic           Automatic"
                        echo "-t, --test                Test mode (no remote changes)"
                        echo "-v, --verbose             Verbose mode"
                        echo "-S, --nosql               Do not execute SQL"
                        quit
                        ;;
                -B|--nobackup)
                        nobackup=true
                        shift
                        ;;
                -d|--downloadlastbak)
                        downloadlastbak=true
                        shift
                        ;;
                -D|--nodownloadallbaks)
                        nodownloadallbaks=true
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
                -K|--nokommissionen)
                        kommissionen=""
                        shift
                        ;;
                -Z|--nozb)
                        nozb=true
                        shift
                        ;;
                -a|--automatic)
                        automatic=true
                        shift
                        ;;
                -t|--test)
                        test=true
                        shift
                        ;;
                -v|--verbose)
                        verbose=true
                        shift
                        ;;
                -I|--noimageupload)
                        noimageupload=true
                        shift
                        ;;
                -S|--nosql)
                        nosql=true
                        shift
                        ;;
                *)
                        break
                        ;;
        esac
done

checkLocalMySQLRunning

###############################################################################
# Backup
###############################################################################

if $import ; then
  if ! $automatic ; then
    askContinueYn "Import 'prod_bak/`cat prod_bak/last_dbdump_data.txt`' to local '$db?'"
  fi

  # ./run_local_db_script.sh $db prod_bak/`cat prod_bak/last_dbdump_data.txt`
  ./deploy.sh -q -l=$db -s prod_bak/`cat prod_bak/last_dbdump_data.txt`

  if $verbose ; then
    echo "DB SQL: prod_bak/`cat prod_bak/last_dbdump_data.txt`"
  fi

  if ! $automatic ; then
    beep
  fi
elif $downloadlastbak ; then
  if ! $automatic ; then
    askContinueYn "Only download 'prod_bak/`cat prod_bak/last_dbdump_data.txt`' to local '$db?'"
  fi

  # Only download last backup (do no create a new backup)
  ./deploy.sh -q -B -o -p

  # ./run_local_db_script.sh $db prod_bak/`cat prod_bak/last_dbdump_data.txt`
  ./deploy.sh -q -l=$db -s prod_bak/`cat prod_bak/last_dbdump_data.txt`

  if $verbose ; then
    echo "DB SQL: prod_bak/`cat prod_bak/last_dbdump_data.txt`"
  fi

  if ! $automatic ; then
    beep
  fi
elif ! $nobackup ; then
  if ! $automatic ; then
    askContinueYn "Import PROD DB to local '$db'?"
  fi

  ./run_db_prod_to_local.sh $db

  # Run for compatibility with current behaviour
  if ! $nodownloadallbaks;  then
    if $verbose ; then
      echo "Download all saved backups"
    fi
    ./deploy.sh -q -B -p
  fi

  if $verbose ; then
    echo "DB SQL: prod_bak/`cat prod_bak/last_dbdump_data.txt`"
  fi

  if ! $automatic ; then
    beep
  fi
fi

###############################################################################
# Local
###############################################################################

if ! $noparlam ; then
  if ! $automatic ; then
    askContinueYn "Run ws_parlament_fetcher.php?"
  fi
  export P_FILE=sql/ws_parlament_ch_sync_`date +"%Y%m%dT%H%M%S"`.sql; php -f ws_parlament_fetcher.php -- -ps$kommissionen | tee $P_FILE

  if $verbose ; then
    echo "Parlamentarier SQL: $P_FILE"
  fi

  grep -q "DATA CHANGED" $P_FILE && P_CHANGED=true
  if $P_CHANGED ; then
    if ! $automatic && ! $nosql ; then
        beep
        less -r $P_FILE
        askContinueYn "Run SQL in local $db?"
    fi
    echo -e "\nParlamentarier data ${greenBold}CHANGED${reset}"
  else
    echo -e "\nParlamentarier data ${greenBold}UNCHANGED${reset}"
  fi

  # Upload images of new paramentarier
  grep -q "downloadImage" $P_FILE && IMAGE_CHANGED=true
  if $IMAGE_CHANGED && ! $noimageupload ; then
    echo -e "\nImages ${greenBold}CHANGED${reset}"
  fi

  if ! $nosql ; then
    # Run anyway to set the imported date
    # ./run_local_db_script.sh $db $P_FILE
    ./deploy.sh -q -l=$db -s $P_FILE
  fi


  if [[ "$kommissionen" == "k" ]] ; then
    grep -q "\(PARLAMENTARIER\|KOMMISSION\) ADDED" $P_FILE && KP_ADDED=true
    if $KP_ADDED ; then
      echo "Kommission or parlamentarier added, check for in_kommission additions (after first SQL has already been executed)."

      export IK_FILE=sql/ws_parlament_ch_sync_inkommission_`date +"%Y%m%dT%H%M%S"`.sql; php -f ws_parlament_fetcher.php -- -s$kommissionen | tee $IK_FILE

      if $verbose ; then
        echo "InKommission SQL: $IK_FILE"
      fi

      grep -q "DATA CHANGED" $IK_FILE && K_CHANGED=true
      if $K_CHANGED && ! $nosql ; then
        if $K_CHANGED ; then
          echo -e "\nInKommission data ${greenBold}CHANGED${reset}"
        else
          echo -e "\nInKommission data ${greenBold}UNCHANGED${reset}"
        fi

        if ! $automatic && ! $nosql ; then
            beep
            less -r $IK_FILE
            askContinueYn "Run SQL in local $db?"
        fi

        # Run anyway to set the imported date
        # ./run_local_db_script.sh $db $P_FILE
        ./deploy.sh -q -l=$db -s $IK_FILE
      fi
    fi
  fi
fi

if ! $nozb ; then
  if ! $automatic ; then
    askContinueYn "Run zutrittsberechtigten (zb) python?"
  fi
  echo "Writing zb.json..."
  python3 $zb_script_path/create_json.py
  echo "Writing zb_delta.sql..."
  export ZB_DELTA_FILE=sql/zb_delta_`date +"%Y%m%dT%H%M%S"`.sql; python3 $zb_script_path/create_delta.py | tee $ZB_DELTA_FILE

  if $verbose ; then
    echo "Zutrittsberechtigung SQL: $ZB_DELTA_FILE"
  fi

  grep -q "DATA CHANGED" $ZB_DELTA_FILE && ZB_CHANGED=true
  if $ZB_CHANGED ; then
    if ! $automatic ; then
      less -r $ZB_DELTA_FILE
    fi
    echo -e "\nZutrittsberechtigten data ${greenBold}CHANGED${reset}"
    if ! $automatic ; then
      askContinueYn "Run zb SQL in local $db?"
    fi
    if ! $nosql ; then
      ./deploy.sh -q -l=$db -s $ZB_DELTA_FILE
    fi
  else
    echo -e "\nZutrittsberechtigten data ${greenBold}UNCHANGED${reset}"
  fi
fi

# Run after import DB script for fixes
if $enable_after_import_script && ! $nosql ; then
  if ! $automatic ; then
      less -r $after_import_DB_script
      askContinueYn "Run $after_import_DB_script in local $db?"
  fi
  echo "Run $after_import_DB_script in local $db"
  ./deploy.sh $refresh -q -l=$db -s $after_import_DB_script

  if [[ "$refresh" == "-r" ]] ; then
    # ./run_local_db_script.sh $db
    # ./deploy.sh -q -l=$db -r # refresh is called with $after_import_DB_script

    if ! $automatic ; then
      beep
    fi
  fi
fi

###############################################################################
# Remote TEST
###############################################################################

if ($import || ! $nobackup) && ! $test ; then
  if ! $automatic ; then
    askContinueYn "Import DB in remote TEST?"
  fi
  if ! $nosql ; then
    echo "Import DB to 'prod_bak/`cat prod_bak/last_dbdump_data.txt`' to remote TEST"
    ./deploy.sh -q -s prod_bak/`cat prod_bak/last_dbdump_data.txt`
  fi

  if ! $automatic ; then
    beep
  fi
fi

if ! $noparlam && ! $nosql ; then
  if ! $automatic ; then
    askContinueYn "Run parlam SQL in remote TEST?"
  fi
  ./deploy.sh -q -s $P_FILE

  if $KP_ADDED ; then
    ./deploy.sh -q -s $IK_FILE
  fi
fi

if ! $nozb && $ZB_CHANGED && ! $nosql ; then
  if ! $automatic ; then
    askContinueYn "Run zb SQL in remote TEST?"
  fi
  ./deploy.sh -q -s $ZB_DELTA_FILE
fi

# Run after import DB script for fixes
if $enable_after_import_script && ! $nosql ; then
  if ! $automatic ; then
      askContinueYn "Run $after_import_DB_script in remote TEST $db?"
  fi
  ./deploy.sh $refresh -q -s $after_import_DB_script
fi

# Upload images of new paramentarier
if $IMAGE_CHANGED && ! $noimageupload ; then
  if ! $automatic ; then
    askContinueYn "Upload images to remote TEST?"
  fi
  ./deploy.sh -u -f -q
fi

###############################################################################
# Remote PROD
###############################################################################

if ! $noparlam && ! $test && ! $nosql; then
  if ! $automatic ; then
    askContinueYn "Run parlam SQL in remote PROD?"
  fi
  ./deploy.sh -p -q -s $P_FILE

  if $KP_ADDED ; then
    ./deploy.sh -p -q -s $IK_FILE
  fi
fi

if ! $nozb && $ZB_CHANGED && ! $test && ! $nosql ; then
  if ! $automatic ; then
    askContinueYn "Run zb SQL in remote PROD?"
  fi
  ./deploy.sh -p -q -s $ZB_DELTA_FILE
fi

# Run after import DB script for fixes
if $enable_after_import_script && ! $nosql && ! $test ; then
  if ! $automatic ; then
      askContinueYn "Run $after_import_DB_script in remote PROD $db?"
  fi
  ./deploy.sh -p $refresh -q -s $after_import_DB_script
fi

# Upload images of new paramentarier
if $IMAGE_CHANGED && ! $noimageupload && ! $test ; then
  if ! $automatic ; then
    askContinueYn "Upload images to remote PROD?"
  fi
  ./deploy.sh -p -u -f -q
fi

###############################################################################
# Mail
###############################################################################

# P_FILE=sql/ws_parlament_ch_sync_20170601.sql
# ZB_DELTA_FILE=sql/zb_delta_20170606.sql
# P_CHANGED = true
# ZB_CHANGED=true
# echo "Mail state: $nomail $P_CHANGED $ZB_CHANGED"
if ! $nomail && ($P_CHANGED || $ZB_CHANGED); then

    if ! $automatic ; then
      askContinueYn "Send email?"
    fi

    if $test ; then
      to="test@lobbywatch.ch"
    else
      to=$MAIL_TO
    fi
    echo > $tmp_mail_body

    fzb=""
    if $ZB_CHANGED ; then
        fzb=$ZB_DELTA_FILE
        subject="$subject Zutrittsberechtigte"
        echo -e "\n= ZUTRITTSBERECHTIGTE\n" >> $tmp_mail_body
        cat $fzb |
        perl -p -e's%(/\*|\*/)%%' >> $tmp_mail_body

        # Get archive files
        PDFS=$(cat $ZB_DELTA_FILE | grep "PDF archive file: " | perl -pe's%-- PDF archive file: (.*)%\1%gm' | perl -pe"s%^%$ARCHIVE_PDF_DIR/%" | tr '\n' ' ')
        if $verbose; then echo "Archive PDFs: $PDFS"; fi
    fi

    if $ZB_CHANGED && $P_CHANGED ; then
      subject="$subject + "
      echo >> $tmp_mail_body
      (printf "%0.s*" {1..50} && echo) >> $tmp_mail_body
      (printf "%0.s*" {1..50} && echo) >> $tmp_mail_body
      (printf "%0.s*" {1..50} && echo) >> $tmp_mail_body
    fi

    if $P_CHANGED ; then
      subject="$subject Parlamentarier"
      echo -e "\n= PARLAMENTARIER\n" >> $tmp_mail_body
      cat $P_FILE |
      perl -p -e's%(/\*|\*/)%%' |
      perl -0 -p -e's%^(Kommissionen \d{2}\.\d{2}\.\d{4} \d{2}:\d{2}:\d{2}).*?^(Kommissionen:)$%\1\n\2%gms' >> $tmp_mail_body
    fi
    # cat $tmp_mail_body
    if $verbose; then echo "cat $tmp_mail_body | php -f mail_notification.php -- -s\"$subject\" -t\"$to\" \"$P_FILE\" \"$fzb\" $PDFS"; fi
    cat $tmp_mail_body | php -f mail_notification.php -- -s"$subject" -t"$to" "$P_FILE" "$fzb" $PDFS
fi

quit
