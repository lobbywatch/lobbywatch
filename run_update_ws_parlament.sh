#!/bin/bash

# Call: ./run_update_ws_parlament.sh
# Test: ./run_update_ws_parlament.sh -a -t -B -S -v
# Only Parlamentarier: ./run_update_ws_parlament.sh -Z
# Only ZB: ./run_update_ws_parlament.sh -P
# ZB Test: ./run_update_ws_parlament.sh -B -P
# ZB Test with DB import: ./run_update_ws_parlament.sh -i -P

# crontab
# 0 20 * * 5 (/bin/echo -e "\nCron run" && date -Iseconds && . ~/.keychain/$(hostname)-sh && cd /home/rkurmann/lobbywatch/lobbywatch && ./run_update_ws_parlament.sh -a -v -d -f; echo "Cron end" && date -Iseconds) >> /home/rkurmann/lobbywatch/lobbywatch/run_update_ws_parlament.sh.log

# run in background
# nohup bash -c '(/bin/echo -e "\nCron run" && date -Iseconds && . ~/.keychain/$(hostname)-sh && cd /home/rkurmann/lobbywatch/lobbywatch && ./run_update_ws_parlament.sh -a -o -f -V -M -t -XS; echo "Cron end" && date -Iseconds)' &> /tmp/run_update_ws_parlament.sh.log &
# [1] 14296

# Include common functions
. common.sh

enable_fail_onerror

PHP=php
#PHP=/usr/bin/php
#PHP=/opt/lampp/bin/php
db=lobbywatchtest
env="local_${db}"
ARCHIVE_PDF_DIR="web_scrapers/archive"
MAIL_TO="redaktion@lobbywatch.ch,roland.kurmann@lobbywatch.ch"
subject="Lobbywatch-Import:"
nobackup=false
downloadallbak=false
lastpdf=false
onlydownloadlastbak=false
import=false
refresh=""
progress="--progress"
noparlam=false
nozb=false
nopg=false
zb_script_path=web_scrapers
pg_script_path=web_scrapers
P_CHANGED=false
K_CHANGED=false
ZB_CHANGED=false
PG_CHANGED=false
KP_ADDED=false
IMAGE_CHANGED=false
nomail=false
noimageupload=false
automatic=false
test=false
nosql=false
kommissionen="k"
verbose=false
verbose_level=0
verbose_mode=""
tmp_mail_body=/tmp/mail_body.txt
after_import_DB_script=after_import_DB.sql
enable_after_import_script=false
DUMP_FILE=prod_bak/last_dbdump_data.txt
DUMP_TYPE_PARAMETER='-o'
FULL_DUMP_PARAMETER=''
DUMP_TYPE_NAME='DATA dump'

# http://stackoverflow.com/questions/192249/how-do-i-parse-command-line-arguments-in-bash
for i in "$@" ; do
      case $i in
                -h|--help)
                        echo "Update Lobbywatch DB from ws.parlament.ch"
                        echo " "
                        echo "$0 [options]"
                        echo " "
                        echo "Options:"
                        echo "-B, --nobackup                   No remote prod backup or import"
                        echo "-o, --onlydownloadlastbak        No remote prod backup, only download (and import) last remote prod backup (useful for development, production update not possible)"
                        echo "-d, --downloadallbak             Download all remote backups"
                        echo "-f, --full-dump                  Import full DB dump which replaces the current DB"
                        echo "-i, --import                     Import last remote prod backup, no backup (implies -B, production update not possible)"
                        echo "-D, --no-dl-pdf                  No download PDFs, use last PDFs"
                        echo "-r, --refresh                    Refresh views"
                        echo "-P, --noparlam                   Do not run parlamentarier script"
                        echo "-K, --nokommissionen             Do not run update Kommissionen"
                        echo "-I, --noimageupload              Do not upload changed images"
                        echo "-Z, --nozb                       Do not run zutrittsberechtigten script"
                        echo "-G, --nopg                       Do not run parlamentarische Gruppen script"
                        echo "-a, --automatic                  Automatic"
                        echo "-M, --nomail                     No email notification"
                        echo "-t, --test                       Test mode (no remote changes)"
                        echo "-v [LEVEL], --verbose [LEVEL]    Verbose mode (Default level=1)"
                        echo "-S, --nosql                      Do not execute SQL"
                        echo "-l=DB, --local=DB                Local DB to use (Default: lobbywatchtest)"
                        quit
                        ;;
                -B|--nobackup)
                        nobackup=true
                        shift
                        ;;
                -o|--onlydownloadlastbak)
                        onlydownloadlastbak=true
                        shift
                        ;;
                -d|--downloadallbak)
                        downloadallbak=true
                        shift
                        ;;
                -M|--nomail)
                        nomail=true
                        shift
                        ;;
                -f|--full-dump)
                        DUMP_FILE=prod_bak/last_dbdump.txt
                        DUMP_TYPE_PARAMETER='-O'
                        FULL_DUMP_PARAMETER='-f'
                        DUMP_TYPE_NAME='FULL dump'
                        shift
                        ;;
                -D|--no-dl-pdf)
                        lastpdf=true
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
                -G|--nopg)
                        nopg=true
                        shift
                        ;;
                -a|--automatic)
                        automatic=true
                        progress=""
                        shift
                        ;;
                -t|--test)
                        test=true
                        shift
                        ;;
                -v|--verbose)
                        verbose=true
                        if [[ $2 =~ ^-?[0-9]+$ ]]; then
                          verbose_level=$2
                          verbose_mode="-v=$verbose_level"
                          shift
                        else
                          verbose_level=1
                        fi
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
                -l=*|--local=*)
                        db="${i#*=}"
                        if [[ $db == "" ]]; then
                          db="lobbywatchtest"
                        fi
                        env="local_${db}"
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
    askContinueYn "Import 'prod_bak/`cat $DUMP_FILE`' to LOCAL '$db' on '$HOSTNAME'?"
  fi

  # ./run_local_db_script.sh $db prod_bak/`cat $DUMP_FILE`
  ./deploy.sh -q -l=$db -s prod_bak/`cat $DUMP_FILE`

  if $verbose ; then
    echo "DB SQL: prod_bak/`cat $DUMP_FILE`"
  fi

  if ! $automatic ; then
    beep
  fi
elif $onlydownloadlastbak ; then

  if ! $automatic ; then
    askContinueYn "Only download last $DUMP_TYPE_NAME to LOCAL '$db' on '$HOSTNAME'?"
  fi

  # Only download last backup (do no create a new backup)
  ./deploy.sh -q $progress $DUMP_TYPE_PARAMETER -p

  # ./run_local_db_script.sh $db prod_bak/`cat $DUMP_FILE`
  ./deploy.sh -q -l=$db -s prod_bak/`cat $DUMP_FILE`

  if $verbose ; then
    echo "DB SQL: prod_bak/`cat $DUMP_FILE`"
  fi

  if ! $automatic ; then
    beep
  fi
elif ! $nobackup ; then
  if ! $automatic ; then
    askContinueYn "Import PROD DB to LOCAL '$db' on '$HOSTNAME'?"
  fi

  ./run_db_prod_to_local.sh $FULL_DUMP_PARAMETER $progress $db

  # Run for compatibility with current behaviour
  if $downloadallbak;  then
    if $verbose ; then
      echo "Download all saved backups"
    fi
    ./deploy.sh -q -B -p $progress
  fi

  if $verbose ; then
    echo "DB SQL: prod_bak/`cat $DUMP_FILE`"
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
    askContinueYn "Run ws_parlament_fetcher.php for '$db' on '$HOSTNAME'?"
  fi
  export P_FILE=sql/ws_parlament_ch_sync_`date +"%Y%m%dT%H%M%S"`.sql; $PHP -f ws_parlament_fetcher.php -- --db=$db -ps$kommissionen $verbose_mode | tee $P_FILE

  if $verbose ; then
    echo "Parlamentarier SQL: $P_FILE"
  fi

  grep -q "DATA CHANGED" $P_FILE && P_CHANGED=true
  if $P_CHANGED ; then
    if ! $automatic && ! $nosql ; then
        beep
        less -r $P_FILE
        askContinueYn "Run SQL in LOCAL $db?"
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

      export IK_FILE=sql/ws_parlament_ch_sync_inkommission_`date +"%Y%m%dT%H%M%S"`.sql; $PHP -f ws_parlament_fetcher.php -- --db=$db -s$kommissionen $verbose_mode | tee $IK_FILE

      if $verbose ; then
        echo "InKommission SQL: $IK_FILE"
      fi

      grep -q "DATA CHANGED" $IK_FILE && K_CHANGED=true
      if $K_CHANGED ; then
        echo -e "\nInKommission data ${greenBold}CHANGED${reset}"
      else
        echo -e "\nInKommission data ${greenBold}UNCHANGED${reset}"
      fi
      if $K_CHANGED && ! $nosql ; then

        if ! $automatic && ! $nosql ; then
            beep
            less -r $IK_FILE
            askContinueYn "Run SQL in LOCAL $db?"
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
    askContinueYn "Run zutrittsberechtigten (zb) python for '$db' on '$HOSTNAME'?"
  fi
  echo "Writing zb.json..."
  python3 $zb_script_path/zb_create_json.py
  echo "Writing zb_delta.sql based on $db..."
  export ZB_DELTA_FILE=sql/zb_delta_`date +"%Y%m%dT%H%M%S"`.sql; python3 $zb_script_path/zb_create_delta.py --db=$db | tee $ZB_DELTA_FILE

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
      askContinueYn "Run zb SQL in LOCAL $db?"
    fi
    if ! $nosql ; then
      ./deploy.sh -q -l=$db -s $ZB_DELTA_FILE
    fi
  else
    echo -e "\nZutrittsberechtigten data ${greenBold}UNCHANGED${reset}"
  fi
fi

if ! $nopg ; then
  if ! $automatic ; then
    askContinueYn "Run parlamentarische Gruppen (pg) python '$db' on '$HOSTNAME'?"
  fi
  echo "Writing pg.json..."
  if $lastpdf ; then
    last_pg_pdf=$(ls -t web_scrapers/backup/*gruppen.pdf | head -1)
    echo "Last PDF $last_pg_pdf"
  else
    last_pg_pdf=''
  fi
  python3 $pg_script_path/pg_create_json.py $last_pg_pdf
  echo "Writing pg_delta.sql..."
  export PG_DELTA_FILE=sql/pg_delta_`date +"%Y%m%dT%H%M%S"`.sql; python3 $pg_script_path/pg_create_delta.py --db=$db | tee $PG_DELTA_FILE

  if $verbose ; then
    echo "Parlamentarische Gruppen SQL: $PG_DELTA_FILE"
  fi

  grep -q "DATA CHANGED" $PG_DELTA_FILE && PG_CHANGED=true
  if $PG_CHANGED ; then
    if ! $automatic ; then
      less -r $PG_DELTA_FILE
    fi
    echo -e "\nParlamentarische Gruppen data ${greenBold}CHANGED${reset}"
    if ! $automatic ; then
      askContinueYn "Run pg SQL in local $db?"
    fi
    if ! $nosql ; then
      ./deploy.sh -q -l=$db -s $PG_DELTA_FILE
    fi
  else
    echo -e "\nParlamentarische Gruppen data ${greenBold}UNCHANGED${reset}"
  fi
fi

# Run after import DB script for fixes
if $enable_after_import_script && ! $nosql ; then
  if ! $automatic ; then
      less -r $after_import_DB_script
      askContinueYn "Run $after_import_DB_script in LOCAL '$db' on '$HOSTNAME'?"
  fi
  echo "Run $after_import_DB_script in LOCAL '$db' on '$HOSTNAME'"
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

if ($import || ! $nobackup || $onlydownloadlastbak) ; then # && ! $test
  if ! $automatic ; then
    askContinueYn "Import DB 'prod_bak/`cat $DUMP_FILE`' to REMOTE TEST?"
  fi
  if ! $nosql ; then
    echo "Import DB 'prod_bak/`cat $DUMP_FILE`' to REMOTE TEST"
    ./deploy.sh -q $progress -s prod_bak/`cat $DUMP_FILE`
  fi

  if ! $automatic ; then
    beep
  fi
fi

# Run parlam SQL in any case in order to set the imported data
if ! $noparlam && ! $nosql ; then
  if ! $automatic ; then
    askContinueYn "Run parlam SQL in REMOTE TEST?"
  fi
  ./deploy.sh -q -s $P_FILE

  if $KP_ADDED && $K_CHANGED; then
    ./deploy.sh -q -s $IK_FILE
  fi
fi

if ! $nozb && $ZB_CHANGED && ! $nosql ; then
  if ! $automatic ; then
    askContinueYn "Run zb SQL in REMOTE TEST?"
  fi
  ./deploy.sh -q -s $ZB_DELTA_FILE
fi

if ! $nopg && $PG_CHANGED && ! $nosql ; then
  if ! $automatic ; then
    askContinueYn "Run pg SQL in REMOTE TEST?"
  fi
  ./deploy.sh -q -s $PG_DELTA_FILE
fi

# Run after import DB script for fixes
if $enable_after_import_script && ! $nosql ; then
  if ! $automatic ; then
      askContinueYn "Run $after_import_DB_script in REMOTE TEST $db?"
  fi
  ./deploy.sh $refresh -q -s $after_import_DB_script
fi

# Upload images of new paramentarier
if $IMAGE_CHANGED && ! $noimageupload ; then
  if ! $automatic ; then
    askContinueYn "Upload images to REMOTE TEST?"
  fi
  ./deploy.sh -u -f -q
fi

###############################################################################
# Remote PROD
###############################################################################

# boolean variable does not work as expected in bash
# update_prod=! $test && ! $nosql && ! $onlydownloadlastbak && ! $import

if ! $test && ! $nosql && ! $onlydownloadlastbak && ! $import ; then
  echo "Remote PROD will not be updated"
  $test && echo 'Parameter test is set'
  $nosql && echo 'Parameter nosql is set'
  $onlydownloadlastbak && echo 'Parameter onlydownloadlastbak is set'
  $import && echo 'Parameter import is set'
fi

if ! $noparlam && ! $test && ! $nosql && ! $onlydownloadlastbak && ! $import ; then
  if ! $automatic ; then
    askContinueYn "Run parlam SQL in REMOTE PROD?"
  fi
  ./deploy.sh -p -q -s $P_FILE

  if $KP_ADDED && $K_CHANGED; then
    ./deploy.sh -p -q -s $IK_FILE
  fi
fi

if ! $nozb && $ZB_CHANGED && ! $test && ! $nosql && ! $onlydownloadlastbak && ! $import ; then
  if ! $automatic ; then
    askContinueYn "Run zb SQL in REMOTE PROD?"
  fi
  ./deploy.sh -p -q -s $ZB_DELTA_FILE
fi

if ! $nopg && $PG_CHANGED && ! $test && ! $nosql && ! $onlydownloadlastbak && ! $import ; then
  if ! $automatic ; then
    askContinueYn "Run pg SQL in remote PROD?"
  fi
  ./deploy.sh -p -q -s $PG_DELTA_FILE
fi

# Run after import DB script for fixes
if $enable_after_import_script && ! $test && ! $nosql && ! $onlydownloadlastbak && ! $import ; then
  if ! $automatic ; then
      askContinueYn "Run $after_import_DB_script in REMOTE PROD $db?"
  fi
  ./deploy.sh -p $refresh -q -s $after_import_DB_script
fi

# Upload images of new paramentarier
if $IMAGE_CHANGED && ! $noimageupload && ! $test && ! $onlydownloadlastbak && ! $import ; then
  if ! $automatic ; then
    askContinueYn "Upload images to REMOTE PROD?"
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
if ! $nomail && ($P_CHANGED || $ZB_CHANGED || $PG_CHANGED); then

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
        ZB_PDFS=$(cat $ZB_DELTA_FILE | grep "PDF archive file: " | perl -pe's%-- PDF archive file: (.*)%\1%gm' | perl -pe"s%^%$ARCHIVE_PDF_DIR/%" | tr '\n' ' ')
        if $verbose; then echo "Archive PDFs: $ZB_PDFS"; fi
    fi

    if $ZB_CHANGED && $PG_CHANGED ; then
      subject="$subject +"
      echo >> $tmp_mail_body
      (printf "%0.s*" {1..50} && echo) >> $tmp_mail_body
      (printf "%0.s*" {1..50} && echo) >> $tmp_mail_body
      (printf "%0.s*" {1..50} && echo) >> $tmp_mail_body
    fi

    fpg=""
    if $PG_CHANGED ; then
        fpg=$PG_DELTA_FILE
        subject="$subject Parlamentarische Gruppen"
        echo -e "\n= PARLAMENTARISCHE GRUPPEN\n" >> $tmp_mail_body
        cat $fpg |
        perl -p -e's%(/\*|\*/)%%' >> $tmp_mail_body

        # Get archive files
        PG_PDFS=$(cat $PG_DELTA_FILE | grep "PDF archive file: " | perl -pe's%-- PDF archive file: (.*)%\1%gm' | perl -pe"s%^%$ARCHIVE_PDF_DIR/%" | tr '\n' ' ')
        if $verbose; then echo "Archive PDFs: $PG_PDFS"; fi
    fi

    if ($PG_CHANGED || $ZB_CHANGED) && $P_CHANGED ; then
      subject="$subject +"
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
    if $verbose; then echo "cat $tmp_mail_body | $PHP -f mail_notification.php -- -s\"$subject\" -t\"$to\" \"$P_FILE\" \"$fzb\" \"$fpg\" $ZB_PDFS $PG_PDFS"; fi
    cat $tmp_mail_body | $PHP -f mail_notification.php -- -s"$subject" -t"$to" "$P_FILE" "$fzb" "$fpg" $ZB_PDFS $PG_PDFS
fi

quit
