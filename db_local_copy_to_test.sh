#!/bin/bash

# mysqldump -u admin -p originaldb | mysql -u backup -p password duplicateddb;

user=root
mysql_path=/opt/lampp/bin/

${mysql_path}mysqldump -u $user lobbywatch --skip-extended-insert --dump-date --hex-blob --routines | ${mysql_path}mysql -u $user lobbywatchtest
