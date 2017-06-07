#!/bin/bash

# Copy production DB to local DB
# Parameter 1: DBname, all (copy to lobbywatchtest and lobbywatch) or nothing (default DB = lobbywatchtest)

# ./db_prod_to_local.sh lobbywatch

# Abort on errors
set -e

# Set defaut DB if no parameter given
if [[ "$1" == "all" ]]; then
  db=lobbywatchtest
elif [[ $1 ]]; then
  db=$1
else
  db=lobbywatchtest
fi

./deploy.sh -q -b -p

# ./run_local_db_script.sh $db prod_bak/`cat prod_bak/last_dbdump_data.txt`
./deploy.sh -q -l=$db -s prod_bak/`cat prod_bak/last_dbdump_data.txt`

if [[ "$1" == "all" ]]; then
  db=lobbywatch
  # ./run_local_db_script.sh $db prod_bak/`cat prod_bak/last_dbdump_data.txt`
  ./deploy.sh -q -l=$db -s prod_bak/`cat prod_bak/last_dbdump_data.txt`
fi
