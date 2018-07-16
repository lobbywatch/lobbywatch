#!/bin/bash

# Copy production DB to local DB
# Parameter 1: DBname, all (copy to lobbywatchtest and lobbywatch) or nothing (default DB = lobbywatchtest)

# ./db_prod_to_local.sh lobbywatch

# Abort on errors
set -e

DUMP_FILE=prod_bak/last_dbdump_data.txt
FULL_DUMP=false
DUMP_TYPE_PARAMETER='-o'

# http://stackoverflow.com/questions/192249/how-do-i-parse-command-line-arguments-in-bash
for i in "$@" ; do
      case $i in
                -h|--help)
                        echo "Import DB from production to local"
                        echo " "
                        echo "$0 [options]"
                        echo " "
                        echo "Options:"
                        echo "-f, --full-dump           Import full DB dump which replaces the current DB"
                        quit
                        ;;
                -f|--full-dump)
                        DUMP_FILE=prod_bak/last_dbdump.txt
                        FULL_DUMP=true
                        DUMP_TYPE_PARAMETER='-O'
                        shift
                        ;;
                *)
                        break
                        ;;
        esac
done

if [[ "$1" == "all" ]] && $FULL_DUMP ; then
  echo "Full dump and all DBs are not allowed"
  exit 1
fi

# Set defaut DB if no parameter given
if [[ "$1" == "all" ]]; then
  db=lobbywatchtest
elif [[ $1 ]]; then
  db=$1
else
  db=lobbywatchtest
fi

if [[ "$1" == "lobbywatch" ]] && $FULL_DUMP ; then
  echo "Full dump is not allowed to lobbywatch DB"
  exit 1
fi

./deploy.sh -q -b -p $DUMP_TYPE_PARAMETER

# ./run_local_db_script.sh $db prod_bak/`cat $DUMP_FILE`
./deploy.sh -q -l=$db -s prod_bak/`cat $DUMP_FILE`

if [[ "$1" == "all" ]]; then
  db=lobbywatch
  # ./run_local_db_script.sh $db prod_bak/`cat $DUMP_FILE`
  ./deploy.sh -q -l=$db -s prod_bak/`cat $DUMP_FILE`
fi
