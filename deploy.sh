#!/bin/bash

SCRIPT_DIR=`dirname "$0"`

# Include common functions
. $SCRIPT_DIR/common.sh

enable_fail_onerror

# Copy PROD backup to lobbywatchtest
# ./deploy.sh -b -p
# ./deploy.sh -r -s prod_bak/`cat prod_bak/last_dbdump_data.txt`
# ./deploy.sh -l= -s prod_bak/`cat prod_bak/last_dbdump.txt`
# ./deploy.sh -l= -s prod_bak/bak/dbdump_data_lobbywat_lobbywatch_20170617_075334.sql.gz # many changes

# For DB operations, add config to ~/.my.cnf:
# This config avoid to show the password in the process list.
#
# [client]
# user = lobbywat_script
# password = CHANGE_IT

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
remote_db_dir="/home/lobbywat/sql_scripts"

ssh_user="lobbywat@s034.cyon.net"
ssh_port="22"
db_base_name=lobbywat_lobbywatch
db_user=lobbywat_script
document_root="/home/lobbywat/public_html/d7/sites/lobbywatch.ch/app/"
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
compare_LP_db_structs=false
visual=false
local_DB=""
PW=""
askpw=false
onlylastdb=false
downloaddbbaks=false

NOW=$(date +"%d.%m.%Y %H:%M");
NOW_SHORT=$(date +"%d.%m.%Y");

# Also in afterburner.sh
# VERSION=$(git describe --abbrev=0 --tags)
# echo -e "<?php\n\$version = '$VERSION';" >  $public_dir/common/version.php;
./set_lobbywatch_version.sh $public_dir

# HINT: Because of --exclude=*, each directory on include path must be included, e.g  --include=/files/parlamentarier_photos --include=/files/parlamentarier_photos/* --include=/files/parlamentarier_photos/klein/*
fast='--include=/* --include=/auswertung/** --include=/common/** --include=/custom/** --include=/settings/** --include=/bearbeitung/* --include=/bearbeitung/components/assets/ --include=/bearbeitung/components/assets/css/ --include=/bearbeitung/components/assets/css/main.css --include=/bearbeitung/components/assets/css/custom/  --include=/bearbeitung/components/assets/css/custom/** --include=/bearbeitung/components/js/ --include=/bearbeitung/components/js/main-bundle-custom.js --include=/bearbeitung/components/js/custom/ --include=/bearbeitung/components/js/custom/** --include=/bearbeitung/components/templates/ --include=/bearbeitung/components/templates/custom_templates/ --include=/bearbeitung/components/templates/custom_templates/** --include=/bearbeitung/auswertung/** --include=/visual/** --include=/bearbeitung/components/lang.* --include=/files/parlamentarier_photos --include=/files/parlamentarier_photos/* --include=/files/parlamentarier_photos/portrait-260/* --exclude-from ./rsync-fast-exclude --exclude=* --prune-empty-dirs'

# For uploading images, use parameter -f instead of adding all dirs to the "fast upload"
# --include=/files/parlamentarier_photos/original/* --include=/files/parlamentarier_photos/gross/* --include=/files/parlamentarier_photos/kein/* --include=/files/parlamentarier_photos/mittel/*
# --include=/files/parlamentarier_photos/klein/* --include=/files/parlamentarier_photos/mittel/* --include=/files/parlamentarier_photos/gross/* --include=/files/parlamentarier_photos/original/* --include=/files/parlamentarier_photos/225x225/*

dry_run="";
#fast="--exclude-from $(readlink -m ./rsync-fast-exclude)"
#absolute_path=$(readlink -m /home/nohsib/dvc/../bop)
# http://stackoverflow.com/questions/192249/how-do-i-parse-command-line-arguments-in-bash
for i in "$@" ; do
      case $i in
                -h|--help)
                        echo "Deploy lobbywatch"
                        echo " "
                        echo "$0 [options]"
                        echo " "
                        echo "Options:"
                        echo "-u, --upload              Upload files"
                        echo "-p, --production          Deploy to production, otherwise test"
                        echo "-l=DB, --local=DB         Set env local and local DB (-l and -p are mutual exclusive, -l wins)"
                        echo "-w=PW, --pw=PW            Set DB password (Alternative: Setup ~/.my.cnf)"
                        echo "-W, --askpw               Ask DB password (Alternative: Setup ~/.my.cnf) (-W wins over -w)"
                        echo "-f, --full                Deploy full with system files"
                        echo "-d, --dry-run             Dry run for file upload"
                        echo "-b, --backup              Backup DB (implies -B)"
                        echo "-B, --downloaddbbaks      Download DB backups from server"
                        echo "-o, --onlylastdb          Download only last data DB backup file"
                        echo "-r, --refresh             Refresh DB MVs (views) (interactively)"
#                         echo "-R, --refreshDirectly     Refresh DB MVs (views) and execute (non-ineractively)"
                        echo "-t, --trigger             Update triggers and procedures"
                        echo "-s, --sql file            Copy and run sql file"
                        echo "-c, --compare             Compare remote DB structs"
                        echo "-C, --compareLP           Compare local and remote DB structs"
                        echo "-x, --visual              Visual compare remote DB structs"
                        echo "-X, --visualLP            Visual compare local and remote DB structs"
                        echo "-m, --maintenance         Set maintenance mode"
                        echo "-q, --quiet               Execute quiet, less questions"
                        echo "-v, --verbose             Verbose mode"
                        echo "-h, --help                Show brief help"
                        echo ""
                        echo -e "Example with real last value: ./deploy.sh -r -s prod_bak/`cat prod_bak/last_dbdump_data.txt`"
                        echo -e 'Example                     : ./deploy.sh -r -s prod_bak/`cat prod_bak/last_dbdump_data.txt`'
                        quit
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
                        downloaddbbaks=true
                        shift
                        ;;
                -B|--downloaddbbaks)
                        downloaddbbaks=true
                        shift
                        ;;
                -o|--onlylastdb)
                        downloaddbbaks=true
                        onlylastdb=true
                        shift
                        ;;
                -c|--compare)
                        compare_db_structs=true
                        shift
                        ;;
                -x|--visual)
                        compare_db_structs=true
                        visual=true
                        shift
                        ;;
                -C|--compareLP)
                        compare_LP_db_structs=true
                        shift
                        ;;
                -X|--visualLP)
                        compare_LP_db_structs=true
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
                -w=*|--pw=*)
                        PW="${i#*=}"
                        shift
                        ;;
                -W|--askpw)
                        askpw=true
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

echo -e "\nEnvironment: ${blackBold}$env${reset}"
echo -e "Document root: $document_root\n"

# read -s -p "Password: " passw
# Ref http://blog.sanctum.geek.nz/testing-exit-values-bash/
if ! ssh-add -l | grep id_rsa_lobbywat; then
    ssh-add ~/.ssh/id_rsa_lobbywat
    ssh-add ~/.ssh/id_rsa_github
    # ssh-add ~/.ssh/id_rsa_csvimsne
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
    ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"./run_db_script.sh $db_base_name$env_suffix $db_user dbdump_struct interactive\""
    echo "## Backup DB structure and data"
    ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"./run_db_script.sh $db_base_name$env_suffix $db_user dbdump interactive\""
    echo "## Backup DB data"
    ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"./run_db_script.sh $db_base_name$env_suffix $db_user dbdump_data interactive\""
  fi
fi

if $downloaddbbaks ; then
  if ! is_local; then
    echo "## Saved backups"
    ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"/bin/ls -hAlt bak/*.sql.gz | head -10\""
    if $onlylastdb ; then
      echo "## Only download last DB backup file to prod_bak"
      # https://superuser.com/questions/297342/rsync-files-newer-than-1-week
      # --files-from=<(ssh $ssh_user -n -p $ssh_port $quiet "cd $remote_db_dir$env_dir2 && /bin/find bak/* -mmin -180 -print -type f")
      last_dbdump_data_file='last_dbdump_data.txt'
      minimal_db_sync="--files-from=:$remote_db_dir$env_dir2/$last_dbdump_data_file"
      last_db_sync_files=$last_dbdump_data_file
    else
      echo "## Download all backup files to prod_bak"
      minimal_db_sync=""
      last_db_sync_files='last_dbdump*.txt'
    fi
    rsync $verbose -avze "ssh -p $ssh_port $quiet" --include='bak/' --include='bak/*.sql.gz' --include='bak/dbdump*.sql' --exclude '*' $minimal_db_sync $dry_run $ssh_user:$remote_db_dir$env_dir2/ prod_bak$env_dir2/
    # Sync last db dump files separaty in order not to be blocked by minimal sync
    rsync $verbose -avze "ssh -p $ssh_port $quiet" --include='bak/' --include=$last_db_sync_files --exclude '*' $dry_run $ssh_user:$remote_db_dir$env_dir2/ prod_bak$env_dir2/

    if ! $onlylastdb ; then
      echo "## Archive file of each month from prod_bak$env_dir2/bak to prod_bak$env_dir2/archive"
      (mkdir -p prod_bak$env_dir2/archive/ && ls -r prod_bak$env_dir2/bak/dbdump_struct_* | uniq -w53 && ls -r prod_bak$env_dir2/bak/dbdump_data_* | uniq -w51 && (ls -r prod_bak$env_dir2/bak/dbdump_l*) | uniq -w46) | xargs cp -ua -t prod_bak$env_dir2/archive
    fi
    echo "## Delete bak files > 45d from prod_bak$env_dir2/bak"
    find prod_bak$env_dir2/bak -mtime +45 -type f -delete
  fi
fi

if $compare_db_structs ; then
  ensure_remote
  echo "## Upload run_db_script.sh"
  include_db="--include run_db_script.sh"
  rsync -avze "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak . $ssh_user:$remote_db_dir

  echo "## Backup DB structure remote lobbywatch"
  ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir; bash -c \"./run_db_script.sh $db_base_name $db_user dbdump_struct interactive\""
  echo "## Download backup files to prod_bak"
  rsync $verbose -avze "ssh -p $ssh_port $quiet" --include='bak/' --include='bak/*.sql.gz' --include='bak/dbdump*.sql' --include='last_dbdump*.txt' --exclude '*' $dry_run $ssh_user:$remote_db_dir/ prod_bak/
  db1_struct=prod_bak/`cat prod_bak/last_dbdump_file.txt`
  db1_struct_tmp=/tmp/$db1_struct
  mkdir -p `dirname $db1_struct_tmp`
#   grep -vE '^\s*(\/\*!50003 SET (sql_mode|character_set_client|character_set_results|collation_connection)|FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+)' $db1_struct > $db1_struct_tmp
#   grep -vE '^\s*(FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+)' $db1_struct > $db1_struct_tmp
  cat $db1_struct > $db1_struct_tmp

  echo "## Backup DB structure remote lobbywatchtest"
  ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir; bash -c \"./run_db_script.sh $db_base_nametest $db_user dbdump_struct interactive\""
  echo "## Download backup files to prod_bak"
  rsync $verbose -avze "ssh -p $ssh_port $quiet" --include='bak/' --include='bak/*.sql.gz' --include='bak/dbdump*.sql' --include='last_dbdump*.txt' --exclude '*' $dry_run $ssh_user:$remote_db_dir/ prod_bak/
  db2_struct=prod_bak/`cat prod_bak/last_dbdump_file.txt`
  db2_struct_tmp=/tmp/$db2_struct
  mkdir -p `dirname $db2_struct_tmp`
#   grep -vE '^\s*(\/\*!50003 SET (sql_mode|character_set_client|character_set_results|collation_connection)|FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+)' $db2_struct > $db2_struct_tmp
#   grep -vE '^\s*(FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+)' $db2_struct > $db2_struct_tmp
  cat $db2_struct > $db2_struct_tmp

  echo "diff -u -w $db1_struct_tmp $db2_struct_tmp | less -r"

  # grep -vE '^\s*(\/\*!50003 SET sql_mode)' `cat last_dbdump_file.txt`
  if $visual ; then
    kompare $db1_struct_tmp $db2_struct_tmp &
  else
    diff -u -w $db1_struct_tmp $db2_struct_tmp | less -r
  fi

fi

if $compare_LP_db_structs ; then

  if [[ $local_DB == "" ]]; then
    local_DB="lobbywatch"
  fi

  echo "## Backup DB structure local '$local_DB'"
  ./run_local_db_script.sh $local_DB dbdump_struct
  db1_struct=`cat last_dbdump_file.txt`
  db1_struct_tmp=/tmp/$db1_struct
  mkdir -p `dirname $db1_struct_tmp`
  # grep -vE '^\s*(\/\*!50003 SET (sql_mode|character_set_client|character_set_results|collation_connection)|FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+)' $db1_struct > $db1_struct_tmp
  cat $db1_struct > $db1_struct_tmp

  echo "## Upload run_db_script.sh"
  include_db="--include run_db_script.sh"
  rsync -avze "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak . $ssh_user:$remote_db_dir$env_dir2

  echo "## Backup DB structure remote '$db_base_name$env_suffix'"
  ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"./run_db_script.sh $db_base_name$env_suffix $db_user dbdump_struct interactive\""
  echo "## Download backup files to prod_bak$env_dir2"
  last_dbdump_struct_file='last_dbdump_struct.txt'
  minimal_db_sync="--files-from=:$remote_db_dir$env_dir2/$last_dbdump_struct_file"
  last_db_sync_files=$last_dbdump_struct_file

  rsync $verbose -avze "ssh -p $ssh_port $quiet" --include='bak/' --include='bak/dbdump*.sql' --exclude '*' $minimal_db_sync $dry_run $ssh_user:$remote_db_dir$env_dir2/ prod_bak$env_dir2/
  rsync $verbose -avze "ssh -p $ssh_port $quiet" --include='bak/' --include=$last_db_sync_files --exclude '*' $dry_run $ssh_user:$remote_db_dir$env_dir2/ prod_bak$env_dir2/

  db2_struct=prod_bak$env_dir2/`cat prod_bak$env_dir2/$last_dbdump_struct_file`
  db2_struct_tmp=/tmp/$db2_struct
  mkdir -p `dirname $db2_struct_tmp`
#   grep -vE '^\s*(\/\*!50003 SET (sql_mode|character_set_client|character_set_results|collation_connection)|FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+)' $db2_struct > $db2_struct_tmp
#   grep -vE '^\s*(FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+)' $db2_struct > $db2_struct_tmp
  cat $db2_struct > $db2_struct_tmp

  echo "diff -u -w $db1_struct_tmp $db2_struct_tmp | less -r"

  # grep -vE '^\s*(\/\*!50003 SET sql_mode)' `cat last_dbdump_file.txt`
  if $visual ; then
    kompare $db1_struct_tmp $db2_struct_tmp &
  else
   (set +o pipefail; diff -u -w $db1_struct_tmp $db2_struct_tmp | less -r)
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
    if [[ $sql_file == *.gz ]] ; then
      zcat $sql_file | less -r
    else
      less -r $sql_file
    fi
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

  if $askpw ; then
    ask_PW
  fi

  if [[ "$PW" != "" ]]; then
    PW="-p$PW"
  fi

  if is_local; then
    # local_DB=$(get_local_DB)
    # echo "DB: $local_DB"
    # ./run_local_db_script.sh $local_DB $sql_file interactive
    ./run_db_script.sh $local_DB root $sql_file interactive $PW && OK=true || OK=false
    if ! $OK ; then
      echo "$sql_file FAILED"
      exit 1
    fi
  else
    echo "## Copy SQL files: $sql_file"
    include_db="--include run_db_script.sh --include sql --include prod_bak --include prod_bak/bak --include $sql_file"
  #   rsync -avze "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak $dry_run $db_dir/ $ssh_user:$remote_db_dir$env_dir2
    rsync -avze "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak . $ssh_user:$remote_db_dir$env_dir2
    
    echo "## Run SQL file: $sql_file"
    #ssh $ssh_user -t -p $ssh_port "cd $remote_db_dir; bash -s" < $db_dir/deploy_load_db.sh
  #   ssh $ssh_user -t -p $ssh_port "cd $remote_db_dir$env_dir2; bash -c ./deploy_load_db.sh"
    ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"./run_db_script.sh $db_base_name$env_suffix $db_user $sql_file interactive $PW\""
  fi
fi

quit

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
#     ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"./run_db_script.sh $db_base_name$env_suffix $db_user db_procedures_triggers.sql interactive\""
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
#     ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"./run_db_script.sh $db_base_name$env_suffix $db_user $sql_file interactive\""
#   fi
# fi
