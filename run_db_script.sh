#!/bin/bash

########
# REMOTE
########

db=$1
username=$2
# script=db_check.sql
script=$3
# mode = cron | interactive
mode=$4
logfile="$script.log"

# Ref: http://stackoverflow.com/questions/12199631/convert-seconds-to-hours-minutes-seconds-in-bash
convertsecs() {
 ((h=${1}/3600))
 ((m=(${1}%3600)/60))
 ((s=${1}%60))
 printf "%02d:%02d:%02d\n" $h $m $s
}

DATEISO=`date --iso-8601=seconds`
DATE="${DATEISO//:/}"

echo "DB: $db"
echo "DB: $db" > $logfile
echo "user: $username"
echo "user: $username" >> $logfile
date +"%m.%d.%Y %T"
date +"%m.%d.%Y %T" >> $logfile

# http://www.cyberciti.biz/faq/shell-script-to-get-the-time-difference/
START=$(date +%s)

#mysql -vvv -ucsvimsne_script csvimsne_lobbywatch$env_suffix < $script 2>&1 > lobbywatch$env_suffix_sql.log
mysql -vvv -u$username $db <$script >>$logfile 2>&1

# MUST DIRECTLY FOLLOW AFTER MySQL command for exit code chekcing
# http://blog.sanctum.geek.nz/testing-exit-values-bash/
if (($? > 0)); then
  if  [[ "$mode" != "cron" ]] ; then
    less $logfile
  else
    tail $logfile
    echo "MySQL errors, see $logfile"
  fi
  exit 1
else
  date +"%m.%d.%Y %T" >> $logfile
  END=$(date +%s)
  DIFF=$(( $END - $START ))
#   echo "It took $DIFF seconds"
#   echo $(convertsecs $DIFF)

  echo "It took $DIFF seconds" >> $logfile
  echo $(convertsecs $DIFF) >> $logfile
  tail $logfile
  echo "MySQL OK"
fi
echo "Run DB script done"
