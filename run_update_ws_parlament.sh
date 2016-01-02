#!/bin/bash

# Call: ./run_update_ws_parlament.sh

# Asks if [Yn] if script shoud continue, otherwise exit 1
# $1: msg or nothing
# Example call 1: askContinueYn
# Example call 1: askContinueYn "Backup DB?"
askContinueYn() {
  if [[ $1 ]]; then
    msg="$1 "
  else
    msg=""
  fi

  # http://stackoverflow.com/questions/3231804/in-bash-how-to-add-are-you-sure-y-n-to-any-command-or-alias
  read -e -p "${msg}Continue? [Y/n] " response
  response=${response,,}    # tolower
  if [[ $response =~ ^(yes|y|)$ ]] ; then
    # echo ""
    # OK
    :
  else
    echo "Aborted"
    exit 1
  fi
}

db=lobbywatchtest

./db_prod_to_local.sh $db

export SYNC_FILE=sql/ws_parlament_ch_sync_`date +"%Y%m%d"`.sql; php -f ws_parlament_fetcher.php -- -pks | tee $SYNC_FILE; less $SYNC_FILE

askContinueYn "Run SQL in local $db?"

./run_local_db_script.sh $db $SYNC_FILE

askContinueYn "Run SQL in remote TEST?"

./deploy.sh -r -s $SYNC_FILE

askContinueYn "Run SQL in remote PROD?"

./deploy.sh -p -r -s $SYNC_FILE

