#!/bin/bash

# ## -- Rsync Deploy config -- ##
# # Be sure your public key is listed in your server's ~/.ssh/authorized_keys file
# ssh_user       = "csvimsne@csvi-ms.net"
# ssh_port       = "22"
# document_root  = "/home/csvimsne/public_html/rk/"
# rsync_delete   = true
# deploy_default = "rsync"
# 
# desc "Deploy website via rsync"
# task :rsync do
#   exclude = ""
#   if File.exists?('./rsync-exclude')
#     exclude = "--exclude-from '#{File.expand_path('./rsync-exclude')}'"
#   end
#   puts "## Deploying website via Rsync"
#   ok_failed system("rsync -avze 'ssh -p #{ssh_port}' #{exclude} #{"--delete" unless rsync_delete == false} #{public_dir}/ #{ssh_user}:#{document_root}")
# end

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
document_root="/home/csvimsne/public_html/lobbycontrol/"
rsync_delete=false
deploy_default="rsync"


fast="--exclude-from ./rsync-fast-exclude"
dry_run="";
#fast="--exclude-from $(readlink -m ./rsync-fast-exclude)"
#absolute_path=$(readlink -m /home/nohsib/dvc/../bop)
# Ref: http://stackoverflow.com/questions/7069682/how-to-get-arguments-with-flags-in-bash-script
while test $# -gt 0; do
        case "$1" in
                -h|--help)
                        echo "deploy lobbycontrol"
                        echo " "
                        echo "$0 [options]"
                        echo " "
                        echo "options:"
                        echo "-h, --help                show brief help"
                        echo "-f, --full                deploy full with system files"
                        echo "-d, --dry-run             dry run"
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


echo "## Prepare release"
./prepare_release.sh

echo "## Deploying website via Rsync"
rsync -avze "ssh -p $ssh_port" $exclude $fast $delete --backup-dir=bak $dry_run $public_dir/ $ssh_user:$document_root

echo "## Copy DB via Rsync"
include_db="--include prod*"
rsync -avze "ssh -p $ssh_port" $include_db --exclude '*' --backup-dir=bak $dry_run $db_dir/ $ssh_user:$remote_db_dir
