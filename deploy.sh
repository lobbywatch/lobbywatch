#!/bin/bash

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
load_sql=false
maintenance_mode=false
env="test"
verbose_mode=false
verbose=''

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
                        echo "deploy lobbywatch"
                        echo " "
                        echo "$0 [options]"
                        echo " "
                        echo "options:"
                        echo "-h, --help                show brief help"
                        echo "-f, --full                deploy full with system files"
                        echo "-d, --dry-run             dry run"
                        echo "-s, --sql                 copy and run sql"
                        echo "-m, --maintenance         set maintenance mode"
                        echo "-p, --production          deploy to production, otherwise test"
                        echo "-v, --verbose             verbose"
                        exit 0
                        ;;
                -f|--full)
                        shift
                        fast=""
                        ;;
                -d|--dry-run)
                        shift
                        dry_run="--dry-run"
                        ;;
                -s|--sql)
                        shift
                        load_sql=true
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

echo "## Prepare release"
./prepare_release.sh $env_suffix $env_dir $env_dir2

# read -s -p "Password: " passw

echo "## Deploying website via Rsync"
if $verbose_mode ; then
  echo rsync $verbose -avze "ssh -p $ssh_port" $exclude $fast $delete --backup --backup-dir=bak $dry_run $public_dir/ $ssh_user:$document_root$env_dir
fi
rsync $verbose -avze "ssh -p $ssh_port" $exclude $fast $delete --backup --backup-dir=bak $dry_run $public_dir/ $ssh_user:$document_root$env_dir

if $load_sql ; then
  echo "## Copy DB via Rsync"
  include_db="--include deploy_*"
  rsync -avze "ssh -p $ssh_port" $include_db --exclude '*' --backup --backup-dir=bak $dry_run $db_dir/ $ssh_user:$remote_db_dir

  echo "## Run SQL script"
  #ssh $ssh_user -t -p $ssh_port "cd $remote_db_dir; bash -s" < $db_dir/deploy_load_db.sh
  ssh $ssh_user -t -p $ssh_port "cd $remote_db_dir; bash -c ./deploy_load_db.sh"
fi
