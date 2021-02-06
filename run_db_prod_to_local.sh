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
backup_param='-b'
onlyimport=false

POSITIONAL=()
# http://stackoverflow.com/questions/192249/how-do-i-parse-command-line-arguments-in-bash
while test $# -gt 0; do
      case $1 in
                -h|--help)
                        echo "Import DB from production to local"
                        echo
                        echo "$0 [options] [DB]"
                        echo
                        echo "DB=all or name, default lobbywatchtest"
                        echo
                        echo "Options:"
                        echo "-f, --full-dump           Import full DB dump which replaces the current DB"
                        echo "-P, --progress            Show download progress"
                        echo "-B, --nobackup            Show download progress"
                        echo "-i, --onlyimport          Import last remote prod backup, no backup (implies -B, production update not possible)"
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
                -B|--nobackup)
                        backup_param=''
                        shift
                        ;;
                -i|--onlyimport)
                        onlyimport=true
                        shift
                        ;;
                *)
                        POSITIONAL+=("$1") # save it in an array for later
                        shift
                        ;;
        esac
done

set -- "${POSITIONAL[@]}" # restore positional parameters

DB_PARAM=$1

if [[ "$DB_PARAM" == "all" ]] && $FULL_DUMP && [[ "$HOSTNAME" =~ "abel" ]]; then
  echo "Full dump and all DBs are not allowed on $HOSTNAME"
  exit 1
elif [[ "$DB_PARAM" == "lobbywatch" ]] && $FULL_DUMP && [[ "$HOSTNAME" =~ "abel" ]] ; then
  echo "Full dump is not allowed to $DB_PARAM DB on $HOSTNAME"
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

if ! $onlyimport ; then
  ./deploy.sh -q $backup_param -p $DUMP_TYPE_PARAMETER $progress
fi

# ./run_local_db_script.sh $db prod_bak/`cat $DUMP_FILE`
./deploy.sh -q -l=$db -s prod_bak/`cat $DUMP_FILE`

if [[ "$DB_PARAM" == "all" ]]; then
  db=lobbywatch
  # ./run_local_db_script.sh $db prod_bak/`cat $DUMP_FILE`
  ./deploy.sh -q -l=$db -s prod_bak/`cat $DUMP_FILE`
fi
