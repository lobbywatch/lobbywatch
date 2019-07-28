#!/bin/bash

# run_local_db_copy_to_test.sh [dest]
# run_local_db_copy_to_test.sh source dest

# run_local_db_copy_to_test.sh                                    → Copies lobbywatch to lobbywatchtest
# run_local_db_copy_to_test.sh lobbywatchtest2                    → Copies lobbywatch to lobbywatchtest2
# run_local_db_copy_to_test.sh lobbywatchtest2 lobbywatchtest3    → Copies lobbywatch2 to lobbywatchtest3

# Abort on errors
set -e

if [[ $# = 0 ]]; then
  db_src="lobbywatch"
  db_dest="lobbywatchtest"
elif [[ $# = 1 ]]; then
  db_src="lobbywatch"
  db_dest="$1"
elif [[ $# = 2 ]]; then
  db_src="$1"
  db_dest="$2"
else
  echo "ERROR: wrong options"
  exit 1
fi

if [[ "$db_dest" == "lobbywatch" ]] && [[ "$HOSTNAME" =~ "abel" ]] ; then
  echo "Full dump is not allowed to $db_dest DB on $HOSTNAME"
  exit 1
fi

echo "Copy from '$db_src' to '$db_dest'..."

charset="utf8mb4"

# mysqldump -u admin -p originaldb | mysql -u backup -p password duplicateddb;

MYSQL_CONTAINER=mysql57

docker exec -it $MYSQL_CONTAINER mysql --help >/dev/null 2>&1 && IS_DOCKER=true || IS_DOCKER=false
if $IS_DOCKER ; then
  MYSQLDUMP="docker exec -it $MYSQL_CONTAINER mysqldump --default-character-set=$charset"
  MYSQL="docker exec -i $MYSQL_CONTAINER mysql --default-character-set=$charset"
else
  MYSQLDUMP=mysqldump
  MYSQL=mysql
fi


user=root

# $MYSQLDUMP -u $user --skip-extended-insert --dump-date --hex-blob --routines --databases lobbywatch --add-drop-database > /tmp/db_out.sql
$MYSQL -u $user -e "DROP DATABASE IF EXISTS $db_dest; CREATE DATABASE IF NOT EXISTS $db_dest DEFAULT CHARACTER SET $charset;"
#  --skip-extended-insert
$MYSQLDUMP -u $user $db_src --dump-date --hex-blob --routines | $MYSQL -u $user $db_dest
