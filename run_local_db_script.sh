#!/bin/bash

########
# LOCAL
########

# run_local_db_script.sh [db] [script.sql] [mode]
# db: lobbywatch | lobbywatchtest
# mode: cron | interactive | cronverbose
# ./run_local_db_script.sh lobbywatchtest
# ./run_local_db_script.sh lobbywatchtest db_procedures_triggers.sql
# ./run_local_db_script.sh lobbywatchtest db_check.sql cron
# ./run_local_db_script.sh lobbywatchtest db_fail.sql cron
# ./run_local_db_script.sh lobbywatchtest dbdump interactive ; less `cat last_dbdump_file.txt`
# ./run_local_db_script.sh lobbywatchtest dbdump_data interactive ; less `cat last_dbdump_file.txt`
# ./run_local_db_script.sh lobbywatchtest dbdump_struct interactive ; less `cat last_dbdump_file.txt`
# ./run_local_db_script.sh lobbywatchtest dbdump_struct interactive ; cp `cat last_dbdump_file.txt` lobbywatch.sql
# ./run_local_db_script.sh lobbywatchtest prod_bak/`cat prod_bak/last_dbdump_data.txt`

# less prod_bak/`cat prod_bak/last_dbdump_data.txt`

# deploy.sh -l=lobbywatchtest -s [file] is similar

. common.sh

# Set defaut DB if no parameter given
if [[ $1 ]]; then
  db=$1
else
  db=lobbywatchtest
fi
if [[ $2 ]]; then
  script=$2
#   less $script
else
  script=db_views.sql
#   script=db_check.sql
fi
if [[ $3 ]]; then
  mode=$3
else
  mode=interactive
#   script=db_check.sql
fi
username=root

checkLocalMySQLRunning

# script=db_views.sql
# echo "DB: $db"
#
# #mysql -vvv -ucsvimsne_script csvimsne_lobbywatch$env_suffix < $script 2>&1 > lobbywatch$env_suffix_sql.log
# mysql -vvv -u$username $db < $script 2>&1 > $db_sql.log
#
# # tail $db_sql.log
#
# echo "Done"


# mode = cron | interactive | cronverbose
if ! ./run_db_script.sh $db $username $script $mode ; then
  exit 1
fi
