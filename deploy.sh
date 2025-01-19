#!/bin/bash

SCRIPT_DIR=`dirname "$0"`

# Include common functions
. $SCRIPT_DIR/common.sh

enable_fail_onerror

set +x

# Copy PROD backup to lobbywatchtest
# ./deploy.sh -b -o -p
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
drupal_dir="$SCRIPT_DIR/drupal"         # Lobbywatch Drupal modules
db_dir="../data/"
remote_db_dir="/home/lobbywat/sql_scripts"

ssh_user="lobbywat@s034.cyon.net"
ssh_port="22"
db_base_name=lobbywat_lobbywatch
db_user=lobbywat_script
document_root="/home/lobbywat/public_html/d7/sites/lobbywatch.ch/app/"
remote_drupal_modules="/home/lobbywat/public_html/d7/sites/all/modules/"
rsync_delete=false
deploy_default="rsync"
run_sql=false
maintenance_mode=false
env="test"
verbose_mode=false
quiet_mode=false
quiet=""
progress=""
verbose=''
refresh_viws=false
ask_execute_refresh_viws=true
update_triggers=false
backup_db=false
upload_files=false
upload_drupal=false
sql_file=''
compare_remote_db_structs=false
compare_local_remote_db_structs=false
visual=false
save_schema=false
local_DB=""
PW=""
askpw=false
onlylastdb=false
downloaddbbaks=false
DUMP_FILE=last_dbdump_data.txt
dry_run="";

local_db_user=script

NOW=$(date +"%d.%m.%Y %H:%M");
NOW_SHORT=$(date +"%d.%m.%Y");

# Also in afterburner.sh
# VERSION=$(git describe --abbrev=0 --tags)
# echo -e "<?php\n\$version = '$VERSION';" >  $public_dir/common/version.php;
./set_lobbywatch_version.sh $public_dir

# https://stackoverflow.com/questions/9952000/using-rsync-include-and-exclude-options-to-include-directory-and-file-by-pattern
# HINT: Because of --exclude=*, each directory on include path must be included, e.g  --include=/files/parlamentarier_photos --include=/files/parlamentarier_photos/* --include=/files/parlamentarier_photos/klein/*
# dir/* or dir/** do nothing, use dir/***
fast='--include=/* --include=/.htaccess --include=/auswertung/** --include=/common/** --include=/custom/** --include=/settings/** --include=/bearbeitung/* --include=/bearbeitung/components/assets/ --include=/bearbeitung/components/assets/css/ --include=/bearbeitung/components/assets/css/main.css --include=/bearbeitung/components/assets/css/custom/  --include=/bearbeitung/components/assets/css/custom/** --include=/bearbeitung/components/js/ --include=/bearbeitung/components/js/main-bundle-custom.js --include=/bearbeitung/components/js/custom/ --include=/bearbeitung/components/js/custom/** --include=/bearbeitung/components/templates/ --include=/bearbeitung/components/templates/custom_templates/ --include=/bearbeitung/components/templates/custom_templates/** --include=/bearbeitung/components/grid --include=/bearbeitung/components/grid/grid.php --include=/bearbeitung/components/page --include=/bearbeitung/components/page/page.php --include=/bearbeitung/auswertung/** --include=/visual/** --include=/bearbeitung/components/lang.* --include=/files/parlamentarier_photos --include=/files/parlamentarier_photos/* --include=/files/parlamentarier_photos/portrait-260/* --include=/bearbeitung/img/icons/ --include=/bearbeitung/img/icons/fugue/*** --exclude-from ./rsync-fast-exclude --exclude=* --prune-empty-dirs'

# For uploading images, use parameter -f instead of adding all dirs to the "fast upload"
# --include=/files/parlamentarier_photos/original/* --include=/files/parlamentarier_photos/gross/* --include=/files/parlamentarier_photos/kein/* --include=/files/parlamentarier_photos/mittel/*
# --include=/files/parlamentarier_photos/klein/* --include=/files/parlamentarier_photos/mittel/* --include=/files/parlamentarier_photos/gross/* --include=/files/parlamentarier_photos/original/* --include=/files/parlamentarier_photos/225x225/*


# https://www.howtogeek.com/435903/what-are-stdin-stdout-and-stderr-on-linux/
# if [ -t 0 ]; then
#   echo stdin coming from keyboard
# else
#   echo stdin coming from a pipe or a file
# fi

#fast="--exclude-from $(readlink -m ./rsync-fast-exclude)"
#absolute_path=$(readlink -m /home/nohsib/dvc/../bop)
# https://stackoverflow.com/questions/192249/how-do-i-parse-command-line-arguments-in-bash

POSITIONAL=()
while test $# -gt 0; do
      case $1 in
                -h|--help)
                        echo "Deploy lobbywatch"
                        echo
                        echo "$0 [options]"
                        echo
                        echo "Options:"
                        echo "-u, --upload              Upload files"
                        echo "-U, --upload-drupal       Upload Drupal modules"
                        echo "-p, --production          Deploy to production, otherwise test"
                        echo "-l[=DB], -L, --local[=DB] Set env local and local DB (-l only is 'lobbywatchtest', -L only is 'lobbywatch') (-l and -p are mutual exclusive, -l wins)"
                        echo "-w=PW, --pw=PW            Set DB password (Alternative: Setup ~/.my.cnf)"
                        echo "-W, --askpw               Ask DB password (Alternative: Setup ~/.my.cnf) (-W wins over -w)"
                        echo "-f, --full                Deploy full with system files"
                        echo "-d, --dry-run             Dry run for file upload"
                        echo "-b, --backup              Backup DB"
                        echo "-B, --downloaddbbaks      Download all DB backups from server"
                        echo "-o, --onlylastdbdata      Download only last data DB backup file (cannot be used with -O)"
                        echo "-O, --onlylastdbfull      Download only last full DB backup file (cannot be used with -o)"
                        echo "-0, --onlylastdbdef       Download only last DB structure backup file (cannot be used with -o or -O)"
                        echo "-P, --progress            Show download progress"
                        echo "-r, --refresh             Refresh DB MVs (views) (interactively) (mutually exclusive -s -t -r)"
#                         echo "-R, --refreshDirectly     Refresh DB MVs (views) and execute (non-ineractively)"
                        echo "-t, --trigger             Update triggers and procedures (mutually exclusive -s -t -r)"
                        echo "-S, --saveschema          Save DB schema as lobbywatch.sql"
                        echo "-s, --sql file            Copy and run sql file (mutually exclusive -s -t -r)"
                        echo "-c, --compare             Compare remote DB structs (TEST and PROD)"
                        echo "-C, --compareLR           Compare local and remote DB structs"
                        echo "-x, --visual              Visual compare remote DB structs (TEST and PROD)"
                        echo "-X, --visualLR            Visual compare local and remote DB structs (choose DBs with -l or -p)"
                        echo "-m, --maintenance         Set maintenance mode"
                        echo "-q, --quiet               Execute quiet, less questions"
                        echo "-v, --verbose             Verbose mode"
                        echo "-h, --help                Show brief help"
                        echo
                        echo -e "Example with real last value: ./deploy.sh -r -s prod_bak/`cat prod_bak/last_dbdump_data.txt`"
                        echo -e 'Example                     : ./deploy.sh -r -s prod_bak/`cat prod_bak/last_dbdump_data.txt`'
                        echo -e 'Example                     : ./deploy.sh -r -l -q && ./deploy.sh -r -L -q && ./deploy.sh -S -L -q'
                        quit
                        ;;
                -u|--upload)
                        upload_files=true
                        shift
                        ;;
                -U|--upload-drupal)
                        upload_drupal=true
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
                -B|--downloaddbbaks)
                        downloaddbbaks=true
                        shift
                        ;;
                -0|--onlylastdbdef)
                        downloaddbbaks=true
                        onlylastdb=true
                        DUMP_FILE=last_dbdump_struct.txt
                        shift
                        ;;
                -O|--onlylastdbfull)
                        downloaddbbaks=true
                        onlylastdb=true
                        DUMP_FILE=last_dbdump.txt
                        shift
                        ;;
                -o|--onlylastdbdata)
                        downloaddbbaks=true
                        onlylastdb=true
                        DUMP_FILE=last_dbdump_data.txt
                        shift
                        ;;
                -c|--compare)
                        compare_remote_db_structs=true
                        shift
                        ;;
                -x|--visual)
                        compare_remote_db_structs=true
                        visual=true
                        shift
                        ;;
                -C|--compareLR)
                        compare_local_remote_db_structs=true
                        shift
                        ;;
                -X|--visualLR)
                        compare_local_remote_db_structs=true
                        visual=true
                        shift
                        ;;
                -S|--saveschema)
                        save_schema=true
                        shift
                        ;;
                -d|--dry-run)
                        dry_run="--dry-run"
                        shift
                        ;;
                -P|--progress)
                        progress="--progress"
                        shift
                        ;;
                -s|--sql)
                        sql_file=$2
                        run_sql=true
                        shift
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
                        env="PROD"
                        shift
                        ;;
                -l|--local)
                        local_DB="lobbywatchtest"
                        env="local_${local_DB}"
                        shift
                        ;;
                -L)
                        local_DB="lobbywatch"
                        env="local_${local_DB}"
                        shift
                        ;;
                -l=*|--local=*)
                        local_DB="${1#*=}"
                        if [[ $local_DB == "" ]]; then
                          local_DB="lobbywatchtest"
                        fi
                        env="local_${local_DB}"
                        shift
                        ;;
                -w=*|--pw=*)
                        PW="${1#*=}"
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
                        POSITIONAL+=("$1") # save it in an array for later
                        shift
                        # echo "Unknown parameter: '$1'"
                        # abort
                        ;;
        esac
done

set -- "${POSITIONAL[@]}" # restore positional parameters

exclude=""
#if [ -f './rsync-exclude' ] ; then exclude="--exclude-from $(readlink -m ./rsync-exclude)" ; fi
if [ -f './rsync-exclude' ] ; then exclude="--exclude-from ./rsync-exclude" ; fi

delete=""
if $rsync_delete ; then delete='--delete --dry-run'; fi

echo -e "<?php\n\$maintenance_mode = $maintenance_mode;" > $public_dir/settings/maintenance_mode.php;

if [[ "$env" = "PROD" ]] ; then
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
if ! ssh-add -l | grep -E 'id_rsa_r|id_ed25519'; then
    ssh-add ~/.ssh/id_rsa_rk || ssh-add ~/.ssh/id_rsa_rpiw || ssh-add ~/.ssh/id_ed25519
    checkLocalMySQLRunning
fi

if $upload_files ; then
  ensure_remote

  if ! $quiet_mode ; then
    askContinueYn "Upload forms file to REMOTE $env?"
  fi

  echo "## Prepare release"
  echo -e "<?php\n// Generated file\n\$deploy_date = '$NOW';\n\$deploy_date_short = '$NOW_SHORT';\n\$deploy_last_commit = '`git rev-parse HEAD`';" > $public_dir/custom/deploy.php
  ./prepare_release.sh $env_suffix $env_dir $env_dir2

  echo "## Deploying DB forms via rsync"
  cmd="rsync $verbose -avzce 'ssh -p $ssh_port $quiet' $exclude $fast $delete --backup --backup-dir=bak $dry_run $public_dir/ $ssh_user:$document_root$env_dir"
  if $verbose_mode ; then
    # echo rsync $verbose -avzce "ssh -p $ssh_port $quiet" $exclude $fast $delete --backup --backup-dir=bak $dry_run $public_dir/ $ssh_user:$document_root$env_dir
    echo "$cmd"
  fi
  # rsync $verbose -avzce "ssh -p $ssh_port $quiet" $exclude $fast $delete --backup --backup-dir=bak $dry_run $public_dir/ $ssh_user:$document_root$env_dir
  eval "$cmd"
fi

if $upload_drupal ; then
  ensure_remote

  echo -e "Drupal modules: $remote_drupal_modules\n"

  if ! $quiet_mode ; then
    askContinueYn "Upload Drupal modules to REMOTE PROD?"
  fi

  echo "## Deploying Drupal modules via rsync"
  cmd="rsync $verbose -avzce 'ssh -p $ssh_port $quiet' $exclude --backup --backup-dir=../bak $dry_run $drupal_dir/lobbywatch $ssh_user:$remote_drupal_modules"
  if $verbose_mode ; then
    echo "$cmd"
  fi
  eval "$cmd"
fi

if $backup_db ; then
  if is_local; then

    if ! $quiet_mode ; then
      askContinueYn "Backup LOCAL DB $local_DB?"
    fi

    echo "## Backup DB structure"
    ./run_db_script.sh $local_DB $local_db_user dbdump_struct interactive
    echo "## Backup DB structure and data"
     ./run_db_script.sh $local_DB $local_db_user dbdump interactive
    echo "## Backup DB data"
    ./run_db_script.sh $local_DB $local_db_user dbdump_data interactive
  else
    if ! $quiet_mode ; then
      askContinueYn "Backup REMOTE DB $db_base_name$env_suffix?"
    fi

    echo "## Upload run_db_script.sh"
    include_db="--include run_db_script.sh"
    rsync -avzce "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak . $ssh_user:$remote_db_dir$env_dir2

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
    if ! $quiet_mode ; then
      askContinueYn "Download backups from REMOTE $env?"
    fi

    echo "## Saved backups"
    ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"/bin/ls -hAlt bak/*.sql.gz | head -10\""
    if $onlylastdb ; then
      echo "## Only download last DB backup file to prod_bak"
      # https://superuser.com/questions/297342/rsync-files-newer-than-1-week
      # --files-from=<(ssh $ssh_user -n -p $ssh_port $quiet "cd $remote_db_dir$env_dir2 && /bin/find bak/* -mmin -180 -print -type f")
      last_dbdump_data_file=$DUMP_FILE
      minimal_db_sync="--files-from=:$remote_db_dir$env_dir2/$last_dbdump_data_file"
      last_db_sync_files=$last_dbdump_data_file
    else
      echo "## Download all backup files to prod_bak"
      minimal_db_sync=""
      last_db_sync_files='last_dbdump*.txt'
    fi
    rsync $verbose -avzce "ssh -p $ssh_port $quiet" $progress --include='bak/' --include='bak/*.sql.gz' --include='bak/dbdump*.sql' --exclude '*' $minimal_db_sync $dry_run $ssh_user:$remote_db_dir$env_dir2/ prod_bak$env_dir2/
    # Sync last db dump files separaty in order not to be blocked by minimal sync
    rsync $verbose -avzce "ssh -p $ssh_port $quiet" --include='bak/' --include=$last_db_sync_files --exclude '*' $dry_run $ssh_user:$remote_db_dir$env_dir2/ prod_bak$env_dir2/

    if ! $onlylastdb ; then
      echo "## Archive file of each month from prod_bak$env_dir2/bak to prod_bak$env_dir2/archive"
      (mkdir -p prod_bak$env_dir2/archive/ && ls -r prod_bak$env_dir2/bak/dbdump_struct_* | uniq -w53 && ls -r prod_bak$env_dir2/bak/dbdump_data_* | uniq -w51 && (ls -r prod_bak$env_dir2/bak/dbdump_l*) | uniq -w46) | xargs cp -ua -t prod_bak$env_dir2/archive
    fi
    echo "## Delete bak files > 45d from prod_bak$env_dir2/bak"
    find prod_bak$env_dir2/bak -mtime +45 -type f -delete
  fi
fi

if $compare_remote_db_structs ; then
  ensure_remote

  if ! $quiet_mode ; then
    askContinueYn "Compare DB structs REMOTE PROD and TEST?"
  fi

  echo "## Upload run_db_script.sh"
  include_db="--include run_db_script.sh"
  rsync -avzce "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak . $ssh_user:$remote_db_dir

  echo "## Backup DB structure remote lobbywatch"
  echo ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir; bash -c \"./run_db_script.sh $db_base_name $db_user dbdump_struct interactive\""
  ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir; bash -c \"./run_db_script.sh $db_base_name $db_user dbdump_struct interactive\""
  echo "## Download backup files to prod_bak"
  echo rsync $verbose -avzce "ssh -p $ssh_port $quiet" --include='bak/' --include='bak/*.sql.gz' --include='bak/dbdump*.sql' --include='last_dbdump*.txt' --exclude '*' $dry_run $ssh_user:$remote_db_dir/ prod_bak/
  rsync $verbose -avzce "ssh -p $ssh_port $quiet" --include='bak/' --include='bak/*.sql.gz' --include='bak/dbdump*.sql' --include='last_dbdump*.txt' --exclude '*' $dry_run $ssh_user:$remote_db_dir/ prod_bak/
  db1_struct=prod_bak/`cat prod_bak/last_dbdump_file.txt`
  db1_struct_tmp=/tmp/$db1_struct
  mkdir -p `dirname $db1_struct_tmp`
#   grep -vE '^\s*(\/\*!50003 SET (sql_mode|character_set_client|character_set_results|collation_connection)|FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+)' $db1_struct > $db1_struct_tmp
#   grep -vE '^\s*(FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+)' $db1_struct > $db1_struct_tmp
  cat $db1_struct |
  grep -v -E "ALTER DATABASE \`?\w+\`? CHARACTER SET" |
  perl -p -e's/AUTO_INCREMENT=\d+//ig' \
  > $db1_struct_tmp

  echo "## Backup DB structure remote lobbywatchtest"
  ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir; bash -c \"./run_db_script.sh ${db_base_name}test $db_user dbdump_struct interactive\""
  echo "## Download backup files to prod_bak"
  rsync $verbose -avzce "ssh -p $ssh_port $quiet" --include='bak/' --include='bak/*.sql.gz' --include='bak/dbdump*.sql' --include='last_dbdump*.txt' --exclude '*' $dry_run $ssh_user:$remote_db_dir/ prod_bak/
  db2_struct=prod_bak/`cat prod_bak/last_dbdump_file.txt`
  db2_struct_tmp=/tmp/$db2_struct
  mkdir -p `dirname $db2_struct_tmp`
#   grep -vE '^\s*(\/\*!50003 SET (sql_mode|character_set_client|character_set_results|collation_connection)|FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+)' $db2_struct > $db2_struct_tmp
#   grep -vE '^\s*(FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+)' $db2_struct > $db2_struct_tmp
  cat $db2_struct |
  grep -v -E "ALTER DATABASE \`?\w+\`? CHARACTER SET" |
  perl -p -e's/AUTO_INCREMENT=\d+//ig' \
  > $db2_struct_tmp

  echo "diff -u -w --color=always $db1_struct_tmp $db2_struct_tmp | less"

  # grep -vE '^\s*(\/\*!50003 SET sql_mode)' `cat last_dbdump_file.txt`
  if $visual ; then
    kompare $db1_struct_tmp $db2_struct_tmp &
  else
    (set +o pipefail; diff -u -w --color=always $db1_struct_tmp $db2_struct_tmp | less)
  fi

fi

if $compare_local_remote_db_structs ; then
  if [[ $local_DB == "" ]]; then
    local_DB="lobbywatch"
  fi

  if ! $quiet_mode ; then
    askContinueYn "Compare struct LOCAL DB $local_DB with REMOTE $db_base_name$env_suffix?"
  fi

  echo "## Backup DB structure local '$local_DB'"
  ./run_local_db_script.sh $local_DB dbdump_struct
  db1_struct=`cat last_dbdump_file.txt`
  db1_struct_tmp=/tmp/local/$db1_struct
  mkdir -p `dirname $db1_struct_tmp`
  # grep -vE '^\s*(\/\*!50003 SET (sql_mode|character_set_client|character_set_results|collation_connection)|FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+)' $db1_struct > $db1_struct_tmp
  cat $db1_struct |
  grep -v -E "ALTER DATABASE \`?\w+\`? CHARACTER SET" |
  perl -p -e's/AUTO_INCREMENT=\d+//ig' \
  > $db1_struct_tmp

  echo "## Upload run_db_script.sh"
  include_db="--include run_db_script.sh"
  rsync -avzce "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak . $ssh_user:$remote_db_dir$env_dir2

  echo "## Backup DB structure remote '$db_base_name$env_suffix'"
  ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"./run_db_script.sh $db_base_name$env_suffix $db_user dbdump_struct interactive\""
  echo "## Download backup files to prod_bak$env_dir2"
  last_dbdump_struct_file='last_dbdump_struct.txt'
  minimal_db_sync="--files-from=:$remote_db_dir$env_dir2/$last_dbdump_struct_file"
  last_db_sync_files=$last_dbdump_struct_file

  rsync $verbose -avzce "ssh -p $ssh_port $quiet" --include='bak/' --include='bak/dbdump*.sql' --exclude '*' $minimal_db_sync $dry_run $ssh_user:$remote_db_dir$env_dir2/ prod_bak$env_dir2/
  rsync $verbose -avzce "ssh -p $ssh_port $quiet" --include='bak/' --include=$last_db_sync_files --exclude '*' $dry_run $ssh_user:$remote_db_dir$env_dir2/ prod_bak$env_dir2/

  db2_struct=prod_bak$env_dir2/`cat prod_bak$env_dir2/$last_dbdump_struct_file`
  db2_struct_tmp=/tmp/remote/$db2_struct
  mkdir -p `dirname $db2_struct_tmp`
#   grep -vE '^\s*(\/\*!50003 SET (sql_mode|character_set_client|character_set_results|collation_connection)|FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+)' $db2_struct > $db2_struct_tmp
#   grep -vE '^\s*(FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+)' $db2_struct > $db2_struct_tmp
  cat $db2_struct |
  grep -v -E "ALTER DATABASE \`?\w+\`? CHARACTER SET" |
  perl -p -e's/AUTO_INCREMENT=\d+//ig' \
  > $db2_struct_tmp

  echo "diff -u -w --color=always $db1_struct_tmp $db2_struct_tmp | less"

  # grep -vE '^\s*(\/\*!50003 SET sql_mode)' `cat last_dbdump_file.txt`
  if $visual ; then
    kompare $db1_struct_tmp $db2_struct_tmp &
  else
    (set +o pipefail; diff -u -w --color=always $db1_struct_tmp $db2_struct_tmp | less)
  fi
fi

# if $load_sql ; then
#   echo "## Copy DB via Rsync"
#   include_db="--include deploy_*"
#   rsync -avzce "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak $dry_run $db_dir/ $ssh_user:$remote_db_dir$env_dir2
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
      zcat $sql_file | less || true # || true to avoid failure when quitting big files that are not fully loaded yet
    else
      less $sql_file
    fi
    [[ "$local_DB" != "" ]] && db="LOCAL $local_DB" || db="REMOTE $db_base_name$env_suffix"
    askContinueYn "Run script '$sql_file' in $db?"
  fi
#   read -e -p "Wait [Enter] " response

  if $refresh_viws ; then
    START=$(date +%s)
    if [[ "$env" = "PROD" ]] ; then
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
    checkLocalMySQLRunning

    # local_DB=$(get_local_DB)
    # echo "DB: $local_DB"
    # ./run_local_db_script.sh $local_DB $sql_file interactive
    ./run_db_script.sh $local_DB $local_db_user $sql_file interactive $PW && OK=true || OK=false
    if ! $OK ; then
      echo "$sql_file FAILED"
      exit 1
    fi
  else
    echo "## Copy SQL files: $sql_file"
    include_db="--include run_db_script.sh --include sql --include prod_bak --include prod_bak/bak --include $sql_file"
  #   rsync -avzce "ssh -p $ssh_port $quiet" $include_db --exclude '*' --backup --backup-dir=bak $dry_run $db_dir/ $ssh_user:$remote_db_dir$env_dir2
    rsync -avzce "ssh -p $ssh_port $quiet" $progress $include_db --exclude '*' --backup --backup-dir=bak . $ssh_user:$remote_db_dir$env_dir2

    echo "## Run SQL file: $sql_file"
    #ssh $ssh_user -t -p $ssh_port "cd $remote_db_dir; bash -s" < $db_dir/deploy_load_db.sh
  #   ssh $ssh_user -t -p $ssh_port "cd $remote_db_dir$env_dir2; bash -c ./deploy_load_db.sh"
    ssh $ssh_user -t -p $ssh_port $quiet "cd $remote_db_dir$env_dir2; bash -c \"./run_db_script.sh $db_base_name$env_suffix $db_user $sql_file interactive $PW\""
  fi
fi

if $save_schema ; then
  checkLocalMySQLRunning

  if [[ $local_DB == "" ]]; then
    local_DB="lobbywatch"
  fi

  if ! $quiet_mode ; then
    askContinueYn "Save lobbywatch.sql from $local_DB?"
  fi

  echo "## Backup DB structure local '$local_DB'"
  ./run_local_db_script.sh $local_DB dbdump_struct interactive && mv `cat last_dbdump_file.txt` lobbywatch.sql

  if ! $quiet_mode ; then
    enable_fail_onerror_no_pipe
    diff -u -w --color=always -B <(git show HEAD:lobbywatch.sql | perl -p -e's/AUTO_INCREMENT=\d+//ig') <(cat lobbywatch.sql | perl -p -e's/AUTO_INCREMENT=\d+//ig') | less
    enable_fail_onerror
  fi
fi

echo "Terminated"
quit
