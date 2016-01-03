#!/bin/bash

. common.sh

./run_local_db_script.sh lobbywatch dbdump_struct
db1_struct=`cat last_dbdump_file.txt`

./run_local_db_script.sh lobbywatchtest dbdump_struct
db2_struct=`cat last_dbdump_file.txt`

echo "diff -u -w $db1_struct $db2_struct | less"

diff -u -w $db1_struct $db2_struct | less

# kompare $db1_struct $db2_struct
