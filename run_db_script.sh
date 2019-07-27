#!/bin/bash

########
# REMOTE
########

# cron commands:
# Test:
# cd /home/csvimsne/sql_scripts/test/; ./run_db_script.sh csvimsne_lobbywatchtest csvimsne_script dbdump cron; ./run_db_script.sh csvimsne_lobbywatchtest csvimsne_script dbdump_data cron;  /home/csvimsne/sql_scripts/test/run_db_script.sh csvimsne_lobbywatchtest csvimsne_script /home/csvimsne/sql_scripts/test/db_views.sql cron

# Prod:
# cd /home/csvimsne/sql_scripts/; ./run_db_script.sh csvimsne_lobbywatch csvimsne_script dbdump cron; ./run_db_script.sh csvimsne_lobbywatch csvimsne_script dbdump_data cron;  /home/csvimsne/sql_scripts/run_db_script.sh csvimsne_lobbywatch csvimsne_script /home/csvimsne/sql_scripts/db_views.sql cron

# TODO use \ only where necessary http://stackoverflow.com/questions/1455988/commenting-in-bash-script

# For DB operations, add config to ~/.my.cnf:
# This config avoid to show the password in the process list.
#
# [client]
# user = lobbywat_script
# password = CHANGE_IT

# Abort on errors
# set -e

db=$1
username=$2
# script=db_check.sql
# script: dbdump | dbdump_data | dbdump_struct : mysql_dump
script=$3
# mode = cron | interactive | cronverbose
mode=$4
PW=$5
logfile="$script.log"
last_dbdump_file="last_dbdump.txt"
last_dbdump_data_file="last_dbdump_data.txt"
last_dbdump_struct_file="last_dbdump_struct.txt"
last_dbdump_op_file="last_dbdump_file.txt"
SRC_DB="lobbywat_lobbywatch"

HOST=127.0.0.1
PORT=3306
MYSQL_CONTAINER=mysql57

docker exec -it $MYSQL_CONTAINER mysql --help >/dev/null 2>&1 && IS_DOCKER=true || IS_DOCKER=false
if $IS_DOCKER && [ "$PORT" == "3306" ]; then
  # Set the default char-set since it may not be set in the docker container
  MYSQLDUMP="docker exec -it $MYSQL_CONTAINER mysqldump --default-character-set=utf8"
  MYSQL="docker exec -i $MYSQL_CONTAINER mysql --default-character-set=utf8"
else
  MYSQLDUMP=mysqldump
  MYSQL=mysql
fi

# Ref: http://stackoverflow.com/questions/12199631/convert-seconds-to-hours-minutes-seconds-in-bash
# Input: Parameter $1=time in s
convertsecs() {
 ((h=${1}/3600))
 ((m=(${1}%3600)/60))
 ((s=${1}%60))
 printf "%02d:%02d:%02d\n" $h $m $s
}

DATE=`date +"%Y%m%d_%H%M%S"`
BAK_DIR="bak"
DUMP_FILE="$BAK_DIR/${script}_${db}_$DATE.sql"
DUMP_FILE_GZ="$DUMP_FILE.gz"

# Colors,
# http://webhome.csc.uvic.ca/~sae/seng265/fall04/tips/s265s047-tips/bash-using-colors.html
# http://misc.flogisoft.com/bash/tip_colors_and_formatting
# Attribute codes:
# 00=none 01=bold 04=underscore 05=blink 07=reverse 08=concealed
#
# Text color codes:
# 30=black 31=red 32=green 33=yellow 34=blue 35=magenta 36=cyan 37=white
#
# Background color codes:
# 40=black 41=red 42=green 43=yellow 44=blue 45=magenta 46=cyan 47=white

green='\e[0;32m'
greenBold='\e[1;32m'
red='\e[0;31m'
redBold='\e[1;31m'
reset='\e[0m'

# Display welcome message
#echo -e "${green}Welcome \e[5;32;47m $USER \n${reset}"

echo "DB: $db" > $logfile
echo "User: $username" >> $logfile
echo "Mode: $mode" >> $logfile
echo "Script: $script" >> $logfile
date +"%d.%m.%Y %T" >> $logfile
echo -e "" >> $logfile
if  [[ "$mode" != "cron" ]] ; then
  cat $logfile
fi

export LW_SRC_DB=$SRC_DB
export LW_DEST_DB=$db

# http://www.cyberciti.biz/faq/shell-script-to-get-the-time-difference/
START=$(date +%s)
echo -e "+++++++++++++++++++++++++" >> $logfile
#mysql -vvv -ucsvimsne_script csvimsne_lobbywatch$env_suffix < $script 2>&1 > lobbywatch$env_suffix_sql.log
if [[ "$script" == "dbdump" ]] ; then
  # http://stackoverflow.com/questions/1221833/bash-pipe-output-and-capture-exit-status
  # --add-drop-database --routines --skip-extended-insert
  # Add --skip-quote-names http://www.iheavy.com/2012/08/09/5-things-you-overlooked-with-mysql-dumps/
  # http://unix.stackexchange.com/questions/20573/sed-insert-something-to-the-last-line
  # --opt is the default which is --add-drop-table, --add-locks, --create-options, --disable-keys, --extended-insert, --lock-tables, --quick, and --set-charset
  (set -o pipefail; $MYSQLDUMP -h $HOST -P $PORT -u$username $PW --databases $db --dump-date --hex-blob --complete-insert --skip-lock-tables --single-transaction --routines --add-drop-trigger --log-error=$logfile 2>>$logfile |
   sed -r "s/^\s*USE.*;/-- Created: `date +"%d.%m.%Y %T"`\n\n\0\n\nSET @disable_triggers = 1; -- ibex disable triggers/i" |
   sed -e "\$aSET @disable_triggers = NULL; -- ibex enable triggers" |
   perl -p -e's/DEFINER=.*? SQL SECURITY DEFINER//ig' |
   perl -p -e's/DEFINER=`.*?`@`[a-zA-Z0-9_.-]+` //ig' |
   perl -p -e's/\r//ig' |
   grep -v -E "ALTER DATABASE \`?\w+\`? CHARACTER SET" |
   gzip -9 >$DUMP_FILE_GZ 2>>$logfile)
elif [[ "$script" == "dbdump_data" ]] ; then
  # http://stackoverflow.com/questions/5109993/mysqldump-data-only
  # http://stackoverflow.com/questions/25778365/add-truncate-table-command-in-mysqldump-before-create-table-if-not-exist
  # Add --skip-quote-names http://www.iheavy.com/2012/08/09/5-things-you-overlooked-with-mysql-dumps/
  # http://unix.stackexchange.com/questions/20573/sed-insert-something-to-the-last-line
  (set -o pipefail; $MYSQLDUMP -h $HOST -P $PORT -u$username $PW --databases $db --dump-date --hex-blob --complete-insert --skip-lock-tables --single-transaction --no-create-db --no-create-info --skip-triggers --log-error=$logfile 2>>$logfile |
   sed -r "s/^\s*USE.*;/-- Created: `date +"%d.%m.%Y %T"`\n\n-- \0 -- ibex Disable setting of original DB\n\nSET @disable_triggers = 1; -- ibex disable triggers/i" |
   sed -r 's/^\s*LOCK TABLES (`[^`]+`) WRITE;/\0\nTRUNCATE \1; -- ibex added/ig' |
   sed -e "\$aSET @disable_triggers = NULL; -- ibex enable triggers" |
   perl -p -e's/\r//ig' |
   grep -v -E "ALTER DATABASE \`?\w+\`? CHARACTER SET" |
   gzip -9 >$DUMP_FILE_GZ 2>>$logfile)
elif [[ "$script" == "dbdump_struct" ]] ; then
  # http://stackoverflow.com/questions/2389468/compare-structures-of-two-databases
  #  --routines : Routines may need additional permissions, otherwise "mysqldump: csvimsne_script has insufficent privileges to SHOW CREATE PROCEDURE"
  # Conditional MySQL execution comments are sometimes multiline comments, thus use perl -0 -pe with modifier s
  # http://stackoverflow.com/questions/1916392/how-can-i-get-rid-of-these-comments-in-a-mysql-dump
  # http://stackoverflow.com/questions/1103149/non-greedy-regex-matching-in-sed
  # mysqldump -u$username --databases $db --dump-date --no-data --skip-lock-tables --routines --log-error=$logfile >$DUMP_FILE 2>>$logfile
  (set -o pipefail; $MYSQLDUMP -h $HOST -P $PORT -u$username $PW --databases $db --dump-date --no-data --skip-lock-tables --routines --log-error=$logfile |
   perl -0 -pe 's|/\*![0-5][0-9]{4} (.*?)\*/|\1|sg' |
   perl -p -e's/DEFINER=.*? SQL SECURITY DEFINER//ig' |
   perl -p -e's/DEFINER=`.*?`@`[a-zA-Z0-9_.-]+` //ig' |
   perl -p -e's/\r//ig' |
   grep -v -E "ALTER DATABASE \`?\w+\`? CHARACTER SET" |
   # perl -pe's/ALTER DATABASE `\w+` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;//ig' |
   perl -p -e's/ALGORITHM=UNDEFINED//ig' >$DUMP_FILE 2>>$logfile)
else
  CAT="cat"
  if [[ "$script" == *.sql.gz ]] ; then
    CAT="zcat"
  elif [[ "$script" == *.sql.zst ]] ; then
    CAT="zstdcat"
  fi
  (set -o pipefail; $CAT $script |
   perl -p -e's/DEFINER=.*? SQL SECURITY DEFINER//ig' |
   perl -p -e's/DEFINER=`.*?`@`[a-zA-Z0-9_.-]+` ?//ig' |
   perl -p -e's/csvimsne/lobbywat/ig' |
   perl -p -e's/$ENV{LW_SRC_DB}/$ENV{LW_DEST_DB}/ig' |
   grep -v -E "ALTER DATABASE \`?\w+\`? CHARACTER SET" |
   $MYSQL -h $HOST -P $PORT -u$username $PW $db >>$logfile 2>&1)
   # -vvv --comments
   # less)
fi

OK=$?

echo -e "+++++++++++++++++++++++++" >> $logfile

# http://blog.sanctum.geek.nz/testing-exit-values-bash/
if (($OK != 0)); then
  date +"%d.%m.%Y %T" >> $logfile
  echo -e "\n*** ERROR ***" >> $logfile
  echo -e "\nFAILED" >> $logfile
  END=$(date +%s)
  DIFF=$(( $END - $START ))
  echo "Elapsed: ${DIFF}s" >> $logfile
  echo $(convertsecs $DIFF) >> $logfile

  if  [[ "$mode" == "interactive" ]] ; then
    less $logfile
    echo -e "\n*** ERROR, see $logfile ***\n----------------------------------------"
    tail -20 $logfile
    echo -e "----------------------------------------\n*** ERROR, see $logfile ***"
    echo -e "\n${redBold}FAILED${reset}"
  else
    echo -e "\n*** ERROR, see $logfile ***\n----------------------------------------"
    tail -20 $logfile
    echo -e "----------------------------------------\n*** ERROR, see $logfile ***"
    echo -e "\nFAILED"
  fi

  echo "less $logfile"

  exit 1
else
  if [[ "$script" == "dbdump" || "$script" == "dbdump_data" || "$script" == "dbdump_struct" ]] ; then
    if [[ "$script" == "dbdump_data" ]] ; then
      echo $DUMP_FILE_GZ > $last_dbdump_data_file
      echo $DUMP_FILE_GZ > $last_dbdump_op_file
      written_dump_file=$DUMP_FILE_GZ
    elif [[ "$script" == "dbdump_struct" ]] ; then
      echo $DUMP_FILE > $last_dbdump_struct_file
      echo $DUMP_FILE > $last_dbdump_op_file
      written_dump_file=$DUMP_FILE
    else
      echo $DUMP_FILE_GZ > $last_dbdump_file
      echo $DUMP_FILE_GZ > $last_dbdump_op_file
      written_dump_file=$DUMP_FILE_GZ
    fi
    if  [[ "$mode" != "cron" ]] ; then
      echo -e "\nDelete dbdumps older than 7d:" >>$logfile 2>&1
      delete_verbose='-print'
    fi
    # http://unix.stackexchange.com/questions/136804/cron-job-to-delete-files-older-than-3-days
    find $BAK_DIR/*.sql.gz $BAK_DIR/*.sql -type f -mtime +7 -delete $delete_verbose >>$logfile 2>&1
    echo "File written: $written_dump_file" >>$logfile
    echo -e "" >>$logfile
  fi
  date +"%d.%m.%Y %T" >> $logfile
  END=$(date +%s)
  DIFF=$(( $END - $START ))
  echo "Elapsed: ${DIFF}s" >> $logfile
  echo $(convertsecs $DIFF) >> $logfile

  if  [[ "$mode" != "cron" ]] ; then
    tail -15 $logfile
    echo -e "\nless $logfile"
    echo -e "\n${greenBold}OK${reset}"
  fi
fi
