#!/bin/bash

########
# REMOTE
########

db=$1
username=$2
# script=db_check.sql
# script: dbdump | dbdump_data : mysql_dump
script=$3
# mode = cron | interactive | cronverbose
mode=$4
logfile="$script.log"
last_dbdump_file="last_dbdump.txt"

# Ref: http://stackoverflow.com/questions/12199631/convert-seconds-to-hours-minutes-seconds-in-bash
# Input: Parameter $1=time in s
convertsecs() {
 ((h=${1}/3600))
 ((m=(${1}%3600)/60))
 ((s=${1}%60))
 printf "%02d:%02d:%02d\n" $h $m $s
}

DATEISO=`date --iso-8601=seconds`
DATE="${DATEISO//:/}"
BAK_DIR="bak"
DUMP_FILE="$BAK_DIR/${script}_${db}_$DATE.sql.gz"

echo "DB: $db" > $logfile
echo "User: $username" >> $logfile
echo "Mode: $mode" >> $logfile
echo "Script: $script" >> $logfile
date +"%m.%d.%Y %T" >> $logfile
echo -e "" >> $logfile
if  [[ "$mode" != "cron" ]] ; then
  cat $logfile
fi

# http://www.cyberciti.biz/faq/shell-script-to-get-the-time-difference/
START=$(date +%s)
echo -e "+++++++++++++++++++++++++" >> $logfile
#mysql -vvv -ucsvimsne_script csvimsne_lobbywatch$env_suffix < $script 2>&1 > lobbywatch$env_suffix_sql.log
if [[ "$script" == "dbdump" ]] ; then
  # http://stackoverflow.com/questions/1221833/bash-pipe-output-and-capture-exit-status
  # --add-drop-database --routines --skip-extended-insert
  (set -o pipefail; mysqldump -u$username --databases $db --dump-date --hex-blob --log-error=$logfile 2>>$logfile | gzip -9 >$DUMP_FILE 2>>$logfile)
elif [[ "$script" == "dbdump_data" ]] ; then
  # http://stackoverflow.com/questions/5109993/mysqldump-data-only
  # http://stackoverflow.com/questions/25778365/add-truncate-table-command-in-mysqldump-before-create-table-if-not-exist
  (set -o pipefail; mysqldump -u$username --databases $db --dump-date --hex-blob --no-create-db --no-create-info --skip-triggers --log-error=$logfile 2>>$logfile | sed -r "s/^\s*USE.*;/-- Created: `date +"%m.%d.%Y %T"`\n\n-- \0 -- ibex disabled/i" | sed -r 's/^\s*LOCK TABLES (`[^`]+`) WRITE;/\0\nTRUNCATE \1; -- ibex added/ig' | gzip -9 >$DUMP_FILE 2>>$logfile)
else
  mysql -vvv -u$username $db <$script >>$logfile 2>&1
fi

# MUST DIRECTLY FOLLOW AFTER MySQL command for exit code chekcing
# http://blog.sanctum.geek.nz/testing-exit-values-bash/
if (($? > 0)); then
  echo -e "+++++++++++++++++++++++++" >> $logfile
  date +"%m.%d.%Y %T" >> $logfile
  echo -e "\n*** ERROR ***" >> $logfile
  echo -e "\nFAILED" >> $logfile
  if  [[ "$mode" == "interactive" ]] ; then
    less $logfile
  else
    echo -e "\n*** ERROR, see $logfile ***\n----------------------------------------"
    tail -20 $logfile
    echo -e "----------------------------------------\n*** ERROR, see $logfile ***"
    echo -e "\nFAILED"
  fi
  exit 1
else
  echo -e "+++++++++++++++++++++++++" >> $logfile
  if [[ "$script" == "dbdump" || "$script" == "dbdump_data" ]] ; then
    echo $DUMP_FILE > $last_dbdump_file
    if  [[ "$mode" != "cron" ]] ; then
      echo -e "\nDelete dbdumps older than 7d:" >>$logfile 2>&1
      delete_verbose='-print'
    fi
    # http://unix.stackexchange.com/questions/136804/cron-job-to-delete-files-older-than-3-days
    find $BAK_DIR/*.sql.gz -type f -mtime +7 -delete $delete_verbose >>$logfile 2>&1
    echo -e "" >>$logfile
  fi
  date +"%m.%d.%Y %T" >> $logfile
  END=$(date +%s)
  DIFF=$(( $END - $START ))
  echo "Elapsed: ${DIFF}s" >> $logfile
  echo $(convertsecs $DIFF) >> $logfile

  if  [[ "$mode" != "cron" ]] ; then
    tail -15 $logfile
    echo -e "\nOK"
  fi
fi
