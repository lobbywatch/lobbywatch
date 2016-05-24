#!/bin/bash

# Include common functions
. common.sh

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

public_dir="public_html"    # compiled site directory
db_dir="../data/"
remote_db_dir="/home/csvimsne/sql_scripts"

ssh_user="csvimsne@csvi-ms.net"
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

NOW=$(date +"%d.%m.%Y %H:%M");
NOW_SHORT=$(date +"%d.%m.%Y");
echo -e "<?php\n\$deploy_date = '$NOW';\n\$deploy_date_short = '$NOW_SHORT';" > $public_dir/common/deploy_date.php

# Also in afterburner.sh
# VERSION=$(git describe --abbrev=0 --tags)
# echo -e "<?php\n\$version = '$VERSION';" >  $public_dir/common/version.php;
./set_lobbywatch_version.sh $public_dir

# HINT: Because of --exclude=*, each directory on include path must be included, e.g  --include=/files/parlamentarier_photos --include=/files/parlamentarier_photos/* --include=/files/parlamentarier_photos/klein/*
fast='--include=/* --include=/auswertung/** --include=/common/** --include=/custom/** --include=/settings/** --include=/bearbeitung/* --include=/bearbeitung/components/css/ --include=/bearbeitung/components/css/aggregated.css.gz --include=/bearbeitung/components/js/ --include=/bearbeitung/components/js/custom.js --include=/bearbeitung/components/templates/  --include=/bearbeitung/components/templates/common/ --include=/bearbeitung/components/templates/common/layout.tpl --include=/bearbeitung/components/templates/custom_templates/ --include=/bearbeitung/components/templates/custom_templates/** --include=/bearbeitung/auswertung/** --include=/visual/** --include=/bearbeitung/components/lang.* --include=/files/parlamentarier_photos --include=/files/parlamentarier_photos/* --include=/files/parlamentarier_photos/klein/* --include=/files/parlamentarier_photos/mittel/* --include=/files/parlamentarier_photos/gross/* --include=/files/parlamentarier_photos/original/* --include=/files/parlamentarier_photos/225x225/* --exclude-from ./rsync-fast-exclude --exclude=* --prune-empty-dirs'

dry_run="";
#fast="--exclude-from $(readlink -m ./rsync-fast-exclude)"
#absolute_path=$(readlink -m /home/nohsib/dvc/../bop)
# Ref: http://stackoverflow.com/questions/7069682/how-to-get-arguments-with-flags-in-bash-script
while test $# -gt 0; do
        case "$1" in
                -h|--help)
                        echo "Deploy lobbywatch"
                        echo " "
                        echo "$0 [options]"
                        echo " "
                        echo "Options:"
                        echo "-u, --upload              Upload files"
                        echo "-p, --production          Deploy to production, otherwise test"
                        echo "-f, --full                Deploy full with system files"
                        echo "-d, --dry-run             Dry run for file upload"
                        echo "-b, --backup              Backup DB"
                        echo "-r, --refresh             Refresh DB MVs (views)"
                        echo "-R, --refreshDirectly     Refresh DB MVs (views) and execute directly"
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
                        shift
                        upload_files=true
                        ;;
                -f|--full)
                        shift
                        fast=""
                        ;;
                -b|--backup)
                        shift
                        backup_db=true
                        ;;
                -c|--compare)
                        shift
                        compare_db_structs=true
                        ;;
                -x|--visual)
                        shift
                        visual=true
                        ;;
                -d|--dry-run)
                        shift
                        dry_run="--dry-run"
                        ;;
                -s|--sql)
                        sql_file=$2
                        run_sql=true
                        shift
                        ;;
                -r|--refresh)
                        shift
                        refresh_viws=true
                        ;;
                -R|----refreshDirectly)
                        shift
                        refresh_viws=true
                        ask_execute_refresh_viws=false
                        ;;
                -t|--trigger)
                        shift
                        update_triggers=true
                        ;;
                -m|--maintenance)
                        shift
                        maintenance_mode=true
                        ;;
                -p|--production)
                        shift
                        env="production"
                        ;;
                -v|--verbose)
                        shift
                        verbose_mode=true
                        verbose='-v'
                        ;;
                -q|--quiet)
                        shift
                        quiet_mode=true
                        quiet="-q"
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
  echo "## Prepare release"
  ./prepare_release.sh $env_suffix $env_dir $env_dir2

  echo "## Deploying website via Rsync"
  if $verbose_mode ; then
    echo rsync $verbose -avze "ssh -p $ssh_port $quiet" $exclude $fast $delete --backup --backup-dir=bak $dry_run $public_dir/ $ssh_user:$document_root$env_dir
  fi
  rsync $verbose -avze "ssh -p $ssh_port $quiet" $exclude $fast $delete --backup --backup-dir=bak $dry_run $public_dir/ $ssh_user:$document_root$env_dir
fi

if $backup_db ; then
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

if $compare_db_structs ; then
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
  echo "## Copy SQL files: $sql_file"
#   read -e -p "Wait [Enter] " response

  include_db="--include run_db_script.sh --include sql --include prod_bak --include prod_bak/bak --include $sql_file"
#   rsync -avze "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak $dry_run $db_dir/ $ssh_user:$remote_db_dir$env_dir2
  rsync -avze "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak . $ssh_user:$remote_db_dir$env_dir2

  echo "## Run SQL file: $sql_file"
  #ssh $ssh_user -t -p $ssh_port "cd $remote_db_dir; bash -s" < $db_dir/deploy_load_db.sh
#   ssh $ssh_user -t -p $ssh_port "cd $remote_db_dir$env_dir2; bash -c ./deploy_load_db.sh"
  ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"./run_db_script.sh csvimsne_lobbywatch$env_suffix csvimsne_script $sql_file interactive\""
fi

if $update_triggers ; then
  echo "## Copy DB trigger script"
  include_db="--include db_procedures_triggers.sql --include db_check.sql --include run_db_script.sh"
  rsync -avze "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak . $ssh_user:$remote_db_dir$env_dir2

  echo "## Run DB trigger script"
  ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"./run_db_script.sh csvimsne_lobbywatch$env_suffix csvimsne_script db_procedures_triggers.sql interactive\""
fi

if $refresh_viws ; then
  echo "## Copy DB views script"
  include_db="--include db_views.sql --include db_check.sql --include run_db_script.sh"
  rsync -avze "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak . $ssh_user:$remote_db_dir$env_dir2

  if $ask_execute_refresh_viws ; then
    askContinueYn "Execute db_views.sql?"
  fi

  echo "## Run DB views script"
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

  ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"./run_db_script.sh csvimsne_lobbywatch$env_suffix csvimsne_script db_views.sql interactive\""
fi
