#!/bin/bash

# Include common functions
. common.sh

enable_fail_onerror

PHP='php'

test_parameter=''
export_options=''

# http://stackoverflow.com/questions/192249/how-do-i-parse-command-line-arguments-in-bash
while test $# -gt 0; do
    case $1 in
        -h|--help)
            echo "Export Lobbywatch DB"
            echo
            echo "$0 [options]"
            echo
            echo "Options:"
            echo "-e LIST                          Type of data to export, add this type of data -e=hist, -e=intern, -e=unpubl, -e=hist+unpubl+intern (default: filter at most)"
            echo "-t, --test                       Test mode (no remote changes)"
            echo "-r, --refresh                    Refresh views"
            echo "-a, --automatic                  Automatic"
            echo "-v [LEVEL], --verbose [LEVEL]    Verbose mode (Default level=1)"
            echo "-l=DB, --local=DB                Local DB to use (Default: lobbywatchtest)"
            quit
        ;;
        -r|--refresh)
            refresh="-r"
            shift
        ;;
        -t|--test)
            test_parameter="-n"
            shift
        ;;
        -e)
            export_options=$2
            shift
            shift
        ;;
        -v|--verbose)
            verbose=true
            if [[ $2 =~ ^-?[0-9]+$ ]]; then
                verbose_level=$2
                verbose_mode="-v=$verbose_level"
                shift
            else
                verbose_level=1
            fi
            shift
        ;;
        -l=*|--local=*)
            db="${1#*=}"
            if [[ $db == "" ]]; then
                db="lobbywatchtest"
            fi
            env="local_${db}"
            shift
        ;;
        *)
            break
        ;;
    esac
done

checkLocalMySQLRunning

# if $import ; then
#   if ! $automatic ; then
#     askContinueYn "Import 'prod_bak/`cat $DUMP_FILE`' to LOCAL '$db' on '$HOSTNAME'?"
#   fi
# fi

START_OVERALL=$(date +%s)

export_type=''
if [ "$export_options" != "" ];then
    export_type="_$export_options"
fi

$PHP -f db_export.php -- -c -v -e=$export_options $test_parameter

EXPORT=export

archive_date=$EXPORT/${DATE_SHORT}_lobbywatch_export$export_type.csv.zip
archive=$EXPORT/lobbywatch_export$export_type.csv.zip
[ -f "$archive_date" ] && rm $archive_date
zip $archive_date export/*.csv
cp $archive_date $archive

ls -Alh $archive_date $archive
