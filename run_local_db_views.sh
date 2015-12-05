#!/bin/bash

########
# LOCAL
########

# ./run_local_db_views.sh lobbywatchtest

# Set defaut DB if no parameter given
if [[ $1 ]]; then
  db=$1
else
  db=lobbywatchtest
fi
username=root
#script=db_check.sql
script=db_views.sql

# script=db_views.sql
# echo "DB: $db"
#
# #mysql -vvv -ucsvimsne_script csvimsne_lobbywatch$env_suffix < $script 2>&1 > lobbywatch$env_suffix_sql.log
# mysql -vvv -u$username $db < $script 2>&1 > $db_sql.log
#
# # tail $db_sql.log
#
# echo "Done"


# mode = cron | interactive
if ./run_db_script.sh $db $username $script interactive ; then
  exit 1
fi
