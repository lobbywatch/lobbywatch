#!/bin/bash

# Copy production DB to local DB
# Parameter 1: DBname or nothing (default DB = lobbywatchtest)

# ./db_prod_to_local.sh lobbywatch

# Set defaut DB if no parameter given
if [[ $1 ]]; then
  db=$1
else
  db=lobbywatchtest
fi

./deploy.sh -b -p
./run_local_db_script.sh $db prod_bak/`cat prod_bak/last_dbdump_data.txt`
