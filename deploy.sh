#!/bin/bash

SCRIPT_DIR=`dirname "$0"`

# Include common functions
. $SCRIPT_DIR/common.sh

# Copy PROD backup to lobbywatchtest
# ./deploy.sh -b -p
# ./deploy.sh -r -s prod_bak/`cat prod_bak/last_dbdump_data.txt`

# fast="--exclude-from './rsync-fast-exclude'"
# # Ref: http://linux.about.com/od/Bash_Scripting_Solutions/a/How-To-Pass-Arguments-To-A-Bash-Script.htm
# while getopts f option
# do
#         case "${option}"
#         in
#                 f) fast="";;
#         esac
# done

public_dir="$SCRIPT_DIR/public_html"    # compiled site directory
db_dir="../data/"
remote_db_dir="/home/csvimsne/sql_scripts"

ssh_user="csvimsne@thar.ch"
ssh_port="22"
document_root="/home/csvimsne/public_html/d7/sites/lobbywatch.ch/app/"
rsync_delete=false
deploy_default="rsync"
run_sql=false
maintenance_mode=false
env="test"
verbose_mode=false
quiet_mode=false
quiet=""
verbose=''
refresh_viws=false
ask_execute_refresh_viws=true
update_triggers=false
backup_db=false
upload_files=false
sql_file=''
compare_db_structs=false
visual=false
local_DB=""

NOW=$(date +"%d.%m.%Y %H:%M");
NOW_SHORT=$(date +"%d.%m.%Y");

# Also in afterburner.sh
# VERSION=$(git describe --abbrev=0 --tags)
# echo -e "<?php\n\$version = '$VERSION';" >  $public_dir/common/version.php;
./set_lobbywatch_version.sh $public_dir

# HINT: Because of --exclude=*, each directory on include path must be included, e.g  --include=/files/parlamentarier_photos --include=/files/parlamentarier_photos/* --include=/files/parlamentarier_photos/klein/*
fast='--include=/* --include=/auswertung/** --include=/common/** --include=/custom/** --include=/settings/** --include=/bearbeitung/* --include=/bearbeitung/components/assets/ --include=/bearbeitung/components/assets/css/ --include=/bearbeitung/components/assets/css/main.css --include=/bearbeitung/components/assets/css/custom/  --include=/bearbeitung/components/assets/css/custom/** --include=/bearbeitung/components/js/ --include=/bearbeitung/components/js/main-bundle-custom.js --include=/bearbeitung/components/js/custom/ --include=/bearbeitung/components/js/custom/** --include=/bearbeitung/components/templates/ --include=/bearbeitung/components/templates/custom_templates/ --include=/bearbeitung/components/templates/custom_templates/** --include=/bearbeitung/auswertung/** --include=/visual/** --include=/bearbeitung/components/lang.* --include=/files/parlamentarier_photos --include=/files/parlamentarier_photos/* --include=/files/parlamentarier_photos/portrait-260/* --exclude-from ./rsync-fast-exclude --exclude=* --prune-empty-dirs'

# --include=/files/parlamentarier_photos/klein/* --include=/files/parlamentarier_photos/mittel/* --include=/files/parlamentarier_photos/gross/* --include=/files/parlamentarier_photos/original/* --include=/files/parlamentarier_photos/225x225/*

dry_run="";
#fast="--exclude-from $(readlink -m ./rsync-fast-exclude)"
#absolute_path=$(readlink -m /home/nohsib/dvc/../bop)
# Ref: http://stackoverflow.com/questions/7069682/how-to-get-arguments-with-flags-in-bash-script
for i in "$@" ;do
      case $i in
# http://stackoverflow.com/questions/192249/how-do-i-parse-command-line-arguments-in-bash
# while test $# -gt 0; do
#         case "$1" in
                -h|--help)
                        echo "Deploy lobbywatch"
                        echo " "
                        echo "$0 [options]"
                        echo " "
                        echo "Options:"
                        echo "-u, --upload              Upload files"
                        echo "-p, --production          Deploy to production, otherwise test"
                        echo "-l=DB, --local=DB         Set env local and local DB (-l and -p are mutual exclusive, -l wins)"
                        echo "-f, --full                Deploy full with system files"
                        echo "-d, --dry-run             Dry run for file upload"
                        echo "-b, --backup              Backup DB"
                        echo "-r, --refresh             Refresh DB MVs (views) (interactively)"
#                         echo "-R, --refreshDirectly     Refresh DB MVs (views) and execute (non-ineractively)"
                        echo "-t, --trigger             Update triggers and procedures"
                        echo "-s, --sql file            Copy and run sql file"
                        echo "-c, --compare             Compare DB structs"
                        echo "-x, --visual              Visual compare"
                        echo "-m, --maintenance         Set maintenance mode"
                        echo "-q, --quiet               Execute quiet, less questions"
                        echo "-v, --verbose             Verbose mode"
                        echo "-h, --help                Show brief help"
                        echo ""
                        echo -e "Example with real last value: ./deploy.sh -r -s prod_bak/`cat prod_bak/last_dbdump_data.txt`"
                        echo -e 'Example                     : ./deploy.sh -r -s prod_bak/`cat prod_bak/last_dbdump_data.txt`'
                        exit 0
                        ;;
                -u|--upload)
                        upload_files=true
                        shift
                        ;;
                -f|--full)
                        fast=""
                        shift
                        ;;
                -b|--backup)
                        backup_db=true
                        shift
                        ;;
                -c|--compare)
                        compare_db_structs=true
                        shift
                        ;;
                -x|--visual)
                        visual=true
                        shift
                        ;;
                -d|--dry-run)
                        dry_run="--dry-run"
                        shift
                        ;;
                -s|--sql)
                        sql_file=$2
                        run_sql=true
                        shift
                        ;;
                -r|--refresh)
                        refresh_viws=true
                        sql_file="db_views.sql"
                        run_sql=true
                        shift
                        ;;
#                 -R|----refreshDirectly)
#                         refresh_viws=true
#                         sql_file="db_views.sql"
#                         run_sql=true
#                         ask_execute_refresh_viws=false
#                         shift
#                         ;;
                -t|--trigger)
                        update_triggers=true
                        sql_file="db_procedures_triggers.sql"
                        run_sql=true
                        shift
                        ;;
                -m|--maintenance)
                        maintenance_mode=true
                        shift
                        ;;
                -p|--production)
                        env="production"
                        shift
                        ;;
                -l=*|--local=*)
                        local_DB="${i#*=}"
                        if [[ $local_DB == "" ]]; then
                          local_DB="lobbywatchtest"
                        fi
                        env="local_${local_DB}"
                        shift
                        ;;
                -v|--verbose)
                        verbose_mode=true
                        verbose='-v'
                        shift
                        ;;
                -q|--quiet)
                        quiet_mode=true
                        quiet="-q"
                        shift
                        ;;
                *)
                        break
                        ;;
        esac
done

exclude=""
#if [ -f './rsync-exclude' ] ; then exclude="--exclude-from $(readlink -m ./rsync-exclude)" ; fi
if [ -f './rsync-exclude' ] ; then exclude="--exclude-from ./rsync-exclude" ; fi

delete=""
if $rsync_delete ; then delete='--delete --dry-run'; fi

echo -e "<?php\n\$maintenance_mode = $maintenance_mode;" > $public_dir/settings/maintenance_mode.php;

if [[ "$env" = "production" ]] ; then
  env_suffix=
  env_dir=
  env_dir2=
else
  env_suffix=$env
  env_dir=$env/
  env_dir2=/$env
fi

echo -e "\nEnvironment: $env"
echo -e "Document root: $document_root\n"

# read -s -p "Password: " passw
# Ref http://blog.sanctum.geek.nz/testing-exit-values-bash/
if ! ssh-add -l | grep id_rsa_csvimsne; then
    ssh-add ~/.ssh/id_rsa_csvimsne
    ssh-add ~/.ssh/id_rsa_github
fi

if ! $quiet_mode ; then
  askContinueYn
fi

if $upload_files ; then
  ensure_remote
  echo "## Prepare release"
  echo -e "<?php\n\$deploy_date = '$NOW';\n\$deploy_date_short = '$NOW_SHORT';" > $public_dir/common/deploy_date.php
  ./prepare_release.sh $env_suffix $env_dir $env_dir2

  echo "## Deploying website via Rsync"
  if $verbose_mode ; then
    echo rsync $verbose -avze "ssh -p $ssh_port $quiet" $exclude $fast $delete --backup --backup-dir=bak $dry_run $public_dir/ $ssh_user:$document_root$env_dir
  fi
  rsync $verbose -avze "ssh -p $ssh_port $quiet" $exclude $fast $delete --backup --backup-dir=bak $dry_run $public_dir/ $ssh_user:$document_root$env_dir
fi

if $backup_db ; then
  if is_local; then
    echo "## Backup DB structure"
    ./run_db_script.sh $local_DB root dbdump_struct interactive
    echo "## Backup DB structure and data"
     ./run_db_script.sh $local_DB root dbdump interactive
    echo "## Backup DB data"
    ./run_db_script.sh $local_DB root dbdump_data interactive
  else
    echo "## Upload run_db_script.sh"
    include_db="--include run_db_script.sh"
    rsync -avze "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak . $ssh_user:$remote_db_dir$env_dir2

    echo "## Backup DB structure"
    ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"./run_db_script.sh csvimsne_lobbywatch$env_suffix csvimsne_script dbdump_struct interactive\""
    echo "## Backup DB structure and data"
    ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"./run_db_script.sh csvimsne_lobbywatch$env_suffix csvimsne_script dbdump interactive\""
    echo "## Backup DB data"
    ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"./run_db_script.sh csvimsne_lobbywatch$env_suffix csvimsne_script dbdump_data interactive\""
    echo "## Saved backups"
    ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"/bin/ls -hAlt bak/*.sql.gz | head -10\""
    echo "## Download backup files to prod_bak"
    rsync $verbose -avze "ssh -p $ssh_port $quiet" --include='bak/' --include='bak/*.sql.gz' --include='bak/dbdump*.sql' --include='last_dbdump*.txt' --exclude '*' $dry_run $ssh_user:$remote_db_dir$env_dir2/ prod_bak$env_dir2/
  fi
fi

if $compare_db_structs ; then
  ensure_remote
  echo "## Upload run_db_script.sh"
  include_db="--include run_db_script.sh"
  rsync -avze "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak . $ssh_user:$remote_db_dir

  echo "## Backup DB structure lobbywatch"
  ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir; bash -c \"./run_db_script.sh csvimsne_lobbywatch csvimsne_script dbdump_struct interactive\""
  echo "## Download backup files to prod_bak"
  rsync $verbose -avze "ssh -p $ssh_port $quiet" --include='bak/' --include='bak/*.sql.gz' --include='bak/dbdump*.sql' --include='last_dbdump*.txt' --exclude '*' $dry_run $ssh_user:$remote_db_dir/ prod_bak/
  db1_struct=prod_bak/`cat prod_bak/last_dbdump_file.txt`
  db1_struct_tmp=/tmp/$db1_struct
  mkdir -p `dirname $db1_struct_tmp`
#   grep -vE '^\s*(\/\*!50003 SET (sql_mode|character_set_client|character_set_results|collation_connection)|FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+)' $db1_struct > $db1_struct_tmp
#   grep -vE '^\s*(FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+)' $db1_struct > $db1_struct_tmp
  cat $db1_struct > $db1_struct_tmp

  echo "## Backup DB structure lobbywatchtest"
  ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir; bash -c \"./run_db_script.sh csvimsne_lobbywatchtest csvimsne_script dbdump_struct interactive\""
  echo "## Download backup files to prod_bak"
  rsync $verbose -avze "ssh -p $ssh_port $quiet" --include='bak/' --include='bak/*.sql.gz' --include='bak/dbdump*.sql' --include='last_dbdump*.txt' --exclude '*' $dry_run $ssh_user:$remote_db_dir/ prod_bak/
  db2_struct=prod_bak/`cat prod_bak/last_dbdump_file.txt`
  db2_struct_tmp=/tmp/$db2_struct
  mkdir -p `dirname $db2_struct_tmp`
#   grep -vE '^\s*(\/\*!50003 SET (sql_mode|character_set_client|character_set_results|collation_connection)|FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+)' $db2_struct > $db2_struct_tmp
#   grep -vE '^\s*(FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+)' $db2_struct > $db2_struct_tmp
  cat $db2_struct > $db2_struct_tmp

  echo "diff -u -w $db1_struct_tmp $db2_struct_tmp | less"

  # grep -vE '^\s*(\/\*!50003 SET sql_mode)' `cat last_dbdump_file.txt`
  if $visual ; then
    kompare $db1_struct_tmp $db2_struct_tmp &
  else
    diff -u -w $db1_struct_tmp $db2_struct_tmp | less
  fi

fi

# if $load_sql ; then
#   echo "## Copy DB via Rsync"
#   include_db="--include deploy_*"
#   rsync -avze "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak $dry_run $db_dir/ $ssh_user:$remote_db_dir$env_dir2
#
#   echo "## Run SQL script"
#   #ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir; bash -s" < $db_dir/deploy_load_db.sh
#   ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c ./deploy_load_db.sh"
# fi

if $run_sql ; then
  if [ ! -f $sql_file ] || [ "$sql_file" == "" ]; then
    echo "SQL file '$sql_file' not found!"
    exit 1
  fi
  if ! $quiet_mode ; then
    less $sql_file
    askContinueYn "Run script '$sql_file'?"
  fi
#   read -e -p "Wait [Enter] " response

  if $refresh_viws ; then
    START=$(date +%s)
    if [[ "$env" = "production" ]] ; then
      DURATION=$((2 * 60))
    else
      DURATION=$((1 * 60))
    fi
    ESTIMATED_END_TIME_SECS=$(($START + $DURATION))
    # Ref http://stackoverflow.com/questions/13422743/convert-seconds-to-formatted-time-in-shell
    ESTIMATED_END_TIME=$(date -d @${ESTIMATED_END_TIME_SECS} +"%T")
    echo -e "${blackBold}Estimated time: $ESTIMATED_END_TIME${reset}"
  fi

  if is_local; then
    # local_DB=$(get_local_DB)
    # echo "DB: $local_DB"
    # ./run_local_db_script.sh $local_DB $sql_file interactive
    ./run_db_script.sh $local_DB root $sql_file interactive
  else
    echo "## Copy SQL files: $sql_file"
    include_db="--include run_db_script.sh --include sql --include prod_bak --include prod_bak/bak --include $sql_file"
  #   rsync -avze "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak $dry_run $db_dir/ $ssh_user:$remote_db_dir$env_dir2
    rsync -avze "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak . $ssh_user:$remote_db_dir$env_dir2

    echo "## Run SQL file: $sql_file"
    #ssh $ssh_user -t -p $ssh_port "cd $remote_db_dir; bash -s" < $db_dir/deploy_load_db.sh
  #   ssh $ssh_user -t -p $ssh_port "cd $remote_db_dir$env_dir2; bash -c ./deploy_load_db.sh"
    ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"./run_db_script.sh csvimsne_lobbywatch$env_suffix csvimsne_script $sql_file interactive\""
  fi
fi

# if $update_triggers ; then
#   if is_local; then
#     local_DB=$(get_local_DB)
#     # echo "DB: $local_DB"
#     # ./run_local_db_script.sh $local_DB $sql_file interactive
#     ./run_db_script.sh $local_DB root $sql_file interactive
#   else
#     echo "## Copy DB trigger script"
#     include_db="--include db_procedures_triggers.sql --include db_check.sql --include run_db_script.sh"
#     rsync -avze "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak . $ssh_user:$remote_db_dir$env_dir2
#
#     echo "## Run DB trigger script"
#     ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"./run_db_script.sh csvimsne_lobbywatch$env_suffix csvimsne_script db_procedures_triggers.sql interactive\""
#   fi
# fi

# if $refresh_viws ; then
#   sql_file="db_views.sql"
#   if is_local; then
#     local_DB=$(get_local_DB)
#     # echo "DB: $local_DB"
#     # ./run_local_db_script.sh $local_DB $sql_file interactive
#     ./run_db_script.sh $local_DB root $sql_file interactive
#   else
#     echo "## Copy DB views script"
#     include_db="--include $sql_file --include db_check.sql --include run_db_script.sh"
#     rsync -avze "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak . $ssh_user:$remote_db_dir$env_dir2
#
#     if $ask_execute_refresh_viws ; then
#       askContinueYn "Execute $sql_file?"
#     fi
#
#     echo "## Run DB views script"
#     START=$(date +%s)
#     if [[ "$env" = "production" ]] ; then
#       DURATION=$((2 * 60))
#     else
#       DURATION=$((1 * 60))
#     fi
#     ESTIMATED_END_TIME_SECS=$(($START + $DURATION))
#     # Ref http://stackoverflow.com/questions/13422743/convert-seconds-to-formatted-time-in-shell
#     ESTIMATED_END_TIME=$(date -d @${ESTIMATED_END_TIME_SECS} +"%T")
#     echo -e "${blackBold}Estimated time: $ESTIMATED_END_TIME${reset}"
#
#     ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"./run_db_script.sh csvimsne_lobbywatch$env_suffix csvimsne_script $sql_file interactive\""
#   fi
# fi
