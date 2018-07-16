#!/bin/bash

# Abort on errors
set -e

# mysqldump -u admin -p originaldb | mysql -u backup -p password duplicateddb;

MYSQL_CONTAINER=mysql57

docker exec -it $MYSQL_CONTAINER mysql --help >/dev/null 2>&1 && IS_DOCKER=true || IS_DOCKER=false
if $IS_DOCKER ; then
  MYSQLDUMP="docker exec -it $MYSQL_CONTAINER mysqldump"
  MYSQL="docker exec -i $MYSQL_CONTAINER mysql"
else
  MYSQLDUMP=mysqldump
  MYSQL=mysql
fi


user=root

# $MYSQLDUMP -u $user --skip-extended-insert --dump-date --hex-blob --routines --databases lobbywatch --add-drop-database > /tmp/db_out.sql
$MYSQL -u $user -e 'DROP DATABASE IF EXISTS `lobbywatchtest`; CREATE DATABASE IF NOT EXISTS `lobbywatchtest` DEFAULT CHARACTER SET utf8;' lobbywatchtest
#  --skip-extended-insert
$MYSQLDUMP -u $user lobbywatch --dump-date --hex-blob --routines | $MYSQL -u $user lobbywatchtest
