#!/bin/bash

# Copy production DB to local DB
# Parameter 1: DBname, all (copy to lobbywatchtest and lobbywatch) or nothing (default DB = lobbywatchtest)

# ./run_db_prod_to_local.sh lobbywatch

# Abort on errors
set -e

# Include common functions
. common.sh

DUMP_FILE=prod_bak/last_dbdump_data.txt
FULL_DUMP=false
DUMP_TYPE_PARAMETER='-o'
progress=""

# http://stackoverflow.com/questions/192249/how-do-i-parse-command-line-arguments-in-bash
for i in "$@" ; do
      case $i in
                -h|--help)
                        echo "Import DB from production to local"
                        echo " "
                        echo "$0 [options] [DB]"
                        echo " "
                        echo "Options:"
                        echo "-f, --full-dump           Import full DB dump which replaces the current DB"
                        echo "-P, --progress            Show download progress"
                        quit
                        ;;
                -f|--full-dump)
                        DUMP_FILE=prod_bak/last_dbdump.txt
                        FULL_DUMP=true
                        DUMP_TYPE_PARAMETER='-O'
                        shift
                        ;;
                -P|--progress)
                        progress="--progress"
                        shift
                        ;;
                *)
                        break
                        ;;
        esac
done

DB_PARAM=$1

if [[ "$DB_PARAM" == "all" ]] && $FULL_DUMP && [[ "$HOSTNAME" =~ "abel" ]]; then
  echo "Full dump and all DBs are not allowed on $HOSTNAME"
  exit 1
fi

# Set defaut DB if no parameter given
if [[ "$DB_PARAM" == "all" ]]; then
  db=lobbywatchtest
elif [[ $DB_PARAM ]]; then
  db=$DB_PARAM
else
  db=lobbywatchtest
fi

if [[ "$DB_PARAM" == "lobbywatch" ]] && $FULL_DUMP && [[ "$HOSTNAME" =~ "abel" ]] ; then
  echo "Full dump is not allowed to $DB_PARAM DB on $HOSTNAME"
  exit 1
fi

./deploy.sh -q -b -p $DUMP_TYPE_PARAMETER $progress

# ./run_local_db_script.sh $db prod_bak/`cat $DUMP_FILE`
./deploy.sh -q -l=$db -s prod_bak/`cat $DUMP_FILE`

if [[ "$DB_PARAM" == "all" ]]; then
  db=lobbywatch
  # ./run_local_db_script.sh $db prod_bak/`cat $DUMP_FILE`
  ./deploy.sh -q -l=$db -s prod_bak/`cat $DUMP_FILE`
fi
