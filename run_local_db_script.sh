#!/bin/bash

########
# LOCAL
########

# run_local_db_script.sh [db] [script.sql] [mode]
# db: lobbywatch | lobbywatchtest
# mode: cron | interactive | cronverbose
# ./run_local_db_script.sh lobbywatchtest
# ./run_local_db_script.sh lobbywatchtest prod_bak/`cat prod_bak/last_dbdump_data.txt`
# ./run_local_db_script.sh lobbywatchtest db_procedures_triggers.sql
# ./run_local_db_script.sh lobbywatchtest db_check.sql cron
# ./run_local_db_script.sh lobbywatchtest db_fail.sql cron
# ./run_local_db_script.sh lobbywatchtest dbdump interactive ; less `cat last_dbdump_file.txt`
# ./run_local_db_script.sh lobbywatchtest dbdump_data interactive ; less `cat last_dbdump_file.txt`
# ./run_local_db_script.sh lobbywatchtest dbdump_struct interactive ; less `cat last_dbdump_file.txt`
# ./run_local_db_script.sh lobbywatch dbdump_struct interactive ; mv `cat last_dbdump_file.txt` lobbywatch.sql ; diff -u -w --color=always -B <(git show HEAD:lobbywatch.sql | perl -p -e's/AUTO_INCREMENT=\d+//ig') <(cat lobbywatch.sql | perl -p -e's/AUTO_INCREMENT=\d+//ig') | less
# ./run_local_db_script.sh lobbywatchtest prod_bak/`cat prod_bak/last_dbdump_data.txt`

# less prod_bak/`cat prod_bak/last_dbdump_data.txt`

# deploy.sh -l=lobbywatchtest -s [file] is similar

. common.sh

enable_fail_onerror

# Set defaut DB if no parameter given
[ -z "${1-}"  ] && db=lobbywatchtest || db=$1
[ -z "${2-}"  ] && script=db_views.sql || script=$2
[ -z "${3-}"  ] && mode=interactive || mode=$3

username=script

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
  abort
fi

quit
