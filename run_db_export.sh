#!/bin/bash

# Include common functions
. common.sh

enable_fail_onerror

PHP='php'
LS='ls -Alh'
ZIP='zip -j'

test_parameter=''
export_options=''
publish=false

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
            echo "-t, --test                       Test mode (implies -n)"
            echo "-n, --limit                      Limit number of records"
            echo "-p, --publish                    Publish exports to public folder"
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
        -p|--publish)
            publish=true
            shift
        ;;
        -n|--limit|-t|--test)
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

if [[ "$LW_PUBLIC_EXPORTS_DIR" == "" ]]; then
    echo -e "\nERROR: LW_PUBLIC_EXPORTS_DIR environment variable is not set"
    abort
else
    PUBLIC_EXPORTS_DIR=$LW_PUBLIC_EXPORTS_DIR
fi

export_type=''
if [ "$export_options" != "" ];then
    export_type="_$export_options"
fi

echo -e "$(date +%T) Start exporting..."

$PHP -f db_export.php -- -c -s -v -e=$export_options $test_parameter

echo -e "\n$(date +%T) Start packing..."

EXPORT=export
MERKBLATT="docs/lobbywatch_daten_merkblatt.pdf"
DATAMODEL="lobbywatch_datenmodell_simplified.pdf"
DOCS="$MERKBLATT $DATAMODEL"

format=csv
base_name=lobbywatch_export_all
echo -e "\nPack $base_name.$format"
archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
archive=$EXPORT/$base_name$export_type.$format.zip
[ -f "$archive_with_date" ] && rm $archive_with_date
$ZIP $archive_with_date $DOCS $EXPORT/*.$format
cp $archive_with_date $archive
$LS $archive_with_date $archive
if $publish; then
    cp $archive $PUBLIC_EXPORTS_DIR
fi

base_name=lobbywatch_export_flat
echo -e "\nPack $base_name.$format"
archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
archive=$EXPORT/$base_name$export_type.$format.zip
[ -f "$archive_with_date" ] && rm $archive_with_date
$ZIP $archive_with_date $DOCS $EXPORT/flat*.$format
cp $archive_with_date $archive
$LS $archive_with_date $archive
if $publish; then
    cp $archive $PUBLIC_EXPORTS_DIR
fi

base_name=lobbywatch_export_parlamentarier
echo -e "\nPack $base_name.$format"
archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
archive=$EXPORT/$base_name$export_type.$format.zip
[ -f "$archive_with_date" ] && rm $archive_with_date
$ZIP $archive_with_date $DOCS $EXPORT/cartesian_essential_parlamentarier_interessenbindung.csv $EXPORT/cartesian_minimal_parlamentarier_interessenbindung.csv $EXPORT/cartesian_parlamentarier_verguetungstransparenz.csv $EXPORT/cartesian_minimal_parlamentarier_zutrittsberechtigung.csv $EXPORT/cartesian_minimal_parlamentarier_zutrittsberechtigung_mandat.csv
cp $archive_with_date $archive
$LS $archive_with_date $archive
if $publish; then
    cp $archive $PUBLIC_EXPORTS_DIR
fi

base_name=lobbywatch_export_parlamentarier_transparenzliste
echo -e "\nPack $base_name.$format"
archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
archive=$EXPORT/$base_name$export_type.$format.zip
[ -f "$archive_with_date" ] && rm $archive_with_date
$ZIP $archive_with_date $DOCS $EXPORT/cartesian_parlamentarier_verguetungstransparenz.csv
cp $archive_with_date $archive
$LS $archive_with_date $archive
if $publish; then
    cp $archive $PUBLIC_EXPORTS_DIR
fi

format=sql
base_name=lobbywatch
echo -e "\nPack $base_name.$format"
archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
archive=$EXPORT/$base_name$export_type.$format.zip
[ -f "$archive_with_date" ] && rm $archive_with_date
$ZIP $archive_with_date $DOCS $EXPORT/*.$format
cp $archive_with_date $archive
$LS $archive_with_date $archive
if $publish; then
    cp $archive $PUBLIC_EXPORTS_DIR
fi

if $publish; then
    cp $DOCS $PUBLIC_EXPORTS_DIR
    echo -e "\nPublished exports:"
    $LS $PUBLIC_EXPORTS_DIR
fi

END_OVERALL=$(date +%s)
DIFF=$(( $END_OVERALL - $START_OVERALL ))
echo -e "\n$(date +%T)" "Overall elapsed:" $(convertsecs $DIFF) "(${DIFF}s)"

quit
