#!/bin/bash

# mysqldump -u admin -p originaldb | mysql -u backup -p password duplicateddb;

user=root
mysql_path=/opt/lampp/bin/

# ${mysql_path}mysqldump -u $user --skip-extended-insert --dump-date --hex-blob --routines --databases lobbywatch --add-drop-database > /tmp/db_out.sql
${mysql_path}mysql -u $user -e 'DROP DATABASE IF EXISTS `lobbywatchtest`; CREATE DATABASE IF NOT EXISTS `lobbywatchtest` DEFAULT CHARACTER SET utf8;' lobbywatchtest
${mysql_path}mysqldump -u $user lobbywatch --skip-extended-insert --dump-date --hex-blob --routines | ${mysql_path}mysql -u $user lobbywatchtest
