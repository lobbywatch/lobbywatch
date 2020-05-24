#!/bin/bash

# Call for production
# ./run_db_export.sh -v -t -d=lobbywat_lobbywatch --user-prefix=reader_ -p

# Include common functions
. common.sh

enable_fail_onerror

if [[ "$USER" == "lobbywat" ]]; then
    PHP='php74'
else
    PHP='php'
fi
LS='ls -Alh'
ZIP='zip -j -9'
ZIP_TREE='zip -9'

EXPORT=export
EXPORT_LOG=$EXPORT/db_export.txt
MERKBLATT="docs/lobbywatch_daten_merkblatt.pdf"
DATAMODEL="lobbywatch_datenmodell_simplified.pdf"
DOCS="$MERKBLATT $DATAMODEL"
DOCU=docu
DOCU_MD="md"

echo -e "Lobbywatch DB Export" > $EXPORT_LOG
echo -e "====================" >> $EXPORT_LOG
echo >> $EXPORT_LOG


test_parameter=''
export_options=''
publish=false
param_schema=''
db_schema='lobbywatchtest'
param_db=''
param_user_prefix=''
all_data='--slow'
export_formats='-c -s -m -g -j -o -l --arangodb -x -t'
basic_export=false
refresh=false

# http://stackoverflow.com/questions/192249/how-do-i-parse-command-line-arguments-in-bash
while test $# -gt 0; do
    case $1 in
        -h|--help)
            echo "Export Lobbywatch DB"
            echo
            echo "$0 [options]"
            echo
            echo "Options:"
            echo "-e LIST                          Type of data to export, add this type of data -e hist, -e intern, -e unpubl, -e hist+unpubl+intern (default: filter at most)"
    echo "-t, --test                       Test mode, currently the same as -n (implies -n)"
            echo "-n, --limit                      Limit number of records"
            echo "-p, --publish                    Publish exports to public folder"
            echo "-r, --refresh                    Refresh views"
    echo "-b, --basic                      Export only basic formats (CSV, SQL, JSON, GraphML)"
            echo "-d=SCHEMA                        DB schema (default SCHEMA: lobbywatchtest)"
            echo "--user-prefix=USER               Prefix for db user in settings.php (default: reader_)"
            echo "--db=DB                          DB name for settings.php"
            echo "-v [LEVEL], --verbose [LEVEL]    Verbose mode (Default level=1)"
            echo "-h, --help                       Show help"
            quit
        ;;
        -d=*)
    db_schema="${1#*=}"
    param_schema="-d=$db_schema"
            shift
        ;;
        --user-prefix=*)
            param_user_prefix="--user-prefix=${1#*=}"
            shift
        ;;
        --db=*)
            param_db="--db=${1#*=}"
            shift
        ;;
        -r|--refresh)
            refresh=true
            shift
        ;;
    export_formats='-c -s -m -j'
            basic_export=true
            shift
        ;;
        -p|--publish)
            publish=true
            shift
        ;;
        -n|--limit|-t|--test)
            test_parameter="-n"
            all_data=''
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
        # -l=*|--local=*)
        #     db="${1#*=}"
        #     if [[ $db == "" ]]; then
        #         db="lobbywatchtest"
        #     fi
        #     env="local_${db}"
        #     shift
        # ;;
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

if $refresh; then
  echo -e "$(date '+%F %T') Refresh DB..."
  ./run_local_db_script.sh $db_schema db_views.sql
fi

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

echo -e "$(date '+%F %T') Start exporting..."

$PHP -f db_export.php --  -v $export_formats -e=$export_options $all_data $test_parameter $param_schema $param_db $param_user_prefix

echo -e "\n$(date '+%F %T') Start packing..."

chmod 755 $EXPORT/*.sh

format=csv
type=all
base_name=lobbywatch_export_$type
echo -e "\nPack $base_name.$format"
archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
archive=$EXPORT/$base_name$export_type.$format.zip
[ -f "$archive_with_date" ] && rm $archive_with_date
$ZIP $archive_with_date $DOCS $EXPORT/*.$format
$ZIP_TREE $archive_with_date       $EXPORT/$DOCU/*.$format.$DOCU_MD
cp $archive_with_date $archive
$LS $archive_with_date $archive
if $publish; then
    cp $archive $PUBLIC_EXPORTS_DIR
fi

format=csv
type=flat
base_name=lobbywatch_export_$type
echo -e "\nPack $base_name.$format"
archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
archive=$EXPORT/$base_name$export_type.$format.zip
[ -f "$archive_with_date" ] && rm $archive_with_date
$ZIP $archive_with_date $DOCS $EXPORT/$type*.$format
$ZIP_TREE $archive_with_date       $EXPORT/$DOCU/$type*.$format.$DOCU_MD
cp $archive_with_date $archive
$LS $archive_with_date $archive
if $publish; then
    cp $archive $PUBLIC_EXPORTS_DIR
fi

format=csv
type=parlamentarier
base_name=lobbywatch_export_$type
echo -e "\nPack $base_name.$format"
archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
archive=$EXPORT/$base_name$export_type.$format.zip
[ -f "$archive_with_date" ] && rm $archive_with_date
$ZIP $archive_with_date $DOCS $EXPORT/cartesian_essential_parlamentarier_interessenbindung.csv $EXPORT/cartesian_minimal_parlamentarier_interessenbindung.csv $EXPORT/cartesian_parlamentarier_verguetungstransparenz.csv $EXPORT/cartesian_minimal_parlamentarier_zutrittsberechtigung.csv $EXPORT/cartesian_minimal_parlamentarier_zutrittsberechtigung_mandat.csv
$ZIP_TREE $archive_with_date       $EXPORT/$DOCU/cartesian_essential_parlamentarier_interessenbindung.csv.$DOCU_MD $EXPORT/$DOCU/cartesian_minimal_parlamentarier_interessenbindung.csv.$DOCU_MD $EXPORT/$DOCU/cartesian_parlamentarier_verguetungstransparenz.csv.$DOCU_MD $EXPORT/$DOCU/cartesian_minimal_parlamentarier_zutrittsberechtigung.csv.$DOCU_MD $EXPORT/$DOCU/cartesian_minimal_parlamentarier_zutrittsberechtigung_mandat.csv.$DOCU_MD
cp $archive_with_date $archive
$LS $archive_with_date $archive
if $publish; then
    cp $archive $PUBLIC_EXPORTS_DIR
fi

format=csv
base_name=lobbywatch_export_parlamentarier_transparenzliste
echo -e "\nPack $base_name.$format"
archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
archive=$EXPORT/$base_name$export_type.$format.zip
[ -f "$archive_with_date" ] && rm $archive_with_date
$ZIP $archive_with_date $DOCS $EXPORT/cartesian_parlamentarier_verguetungstransparenz.csv
$ZIP_TREE $archive_with_date       $EXPORT/$DOCU/cartesian_parlamentarier_verguetungstransparenz.csv.$DOCU_MD
cp $archive_with_date $archive
$LS $archive_with_date $archive
if $publish; then
    cp $archive $PUBLIC_EXPORTS_DIR
fi

format=sql
base_name=lobbywatch_export
echo -e "\nPack $base_name.$format"
archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
archive=$EXPORT/$base_name$export_type.$format.zip
[ -f "$archive_with_date" ] && rm $archive_with_date
$ZIP $archive_with_date $DOCS $EXPORT/*.$format
$ZIP_TREE $archive_with_date       $EXPORT/$DOCU/*.$format.$DOCU_MD
cp $archive_with_date $archive
$LS $archive_with_date $archive
if $publish; then
    cp $archive $PUBLIC_EXPORTS_DIR
fi

format=graphml
base_name=lobbywatch_export
echo -e "\nPack $base_name.$format"
archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
archive=$EXPORT/$base_name$export_type.$format.zip
[ -f "$archive_with_date" ] && rm $archive_with_date
$ZIP $archive_with_date $DOCS $EXPORT/*.$format
$ZIP_TREE $archive_with_date       $EXPORT/$DOCU/*.$format.$DOCU_MD
cp $archive_with_date $archive
$LS $archive_with_date $archive
if $publish; then
  cp $archive $PUBLIC_EXPORTS_DIR
fi

format=json
type=aggregated
base_name=lobbywatch_export_$type
echo -e "\nPack $base_name.$format"
archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
archive=$EXPORT/$base_name$export_type.$format.zip
[ -f "$archive_with_date" ] && rm $archive_with_date
$ZIP $archive_with_date $DOCS $EXPORT/$type*.$format
$ZIP_TREE $archive_with_date $EXPORT/$DOCU/$type*.$format.$DOCU_MD
cp $archive_with_date $archive
$LS $archive_with_date $archive
if $publish; then
    cp $archive $PUBLIC_EXPORTS_DIR
fi

if ! $basic_export; then

  format=csv
  type=neo4j
  base_name=lobbywatch_export_$type
  echo -e "\nPack $base_name.$format"
  archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
  archive=$EXPORT/$base_name$export_type.$format.zip
  [ -f "$archive_with_date" ] && rm $archive_with_date
  $ZIP $archive_with_date $DOCS $EXPORT/$type*.sh
  $ZIP_TREE $archive_with_date $EXPORT/node*.$format $EXPORT/relationship*.$format
  $ZIP_TREE $archive_with_date $EXPORT/$DOCU/node*.$format.$DOCU_MD $EXPORT/$DOCU/relationship*.$format.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
      cp $archive $PUBLIC_EXPORTS_DIR
  fi

  format=json
  type=orientdb
  base_name=lobbywatch_export_$type
  echo -e "\nPack $base_name.$format"
  archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
  archive=$EXPORT/$base_name$export_type.$format.zip
  [ -f "$archive_with_date" ] && rm $archive_with_date
  $ZIP $archive_with_date $DOCS $EXPORT/$type*.sh
  $ZIP_TREE $archive_with_date $EXPORT/node*.$format $EXPORT/relationship*.$format
  $ZIP_TREE $archive_with_date $EXPORT/$DOCU/node*.$format.$DOCU_MD $EXPORT/$DOCU/relationship*.$format.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
      cp $archive $PUBLIC_EXPORTS_DIR
  fi

  format=jsonl
  type=arangodb
  base_name=lobbywatch_export_$type
  echo -e "\nPack $base_name.$format"
  archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
  archive=$EXPORT/$base_name$export_type.$format.zip
  [ -f "$archive_with_date" ] && rm $archive_with_date
  $ZIP $archive_with_date $DOCS $EXPORT/$type*.sh
  $ZIP_TREE $archive_with_date $EXPORT/node*.$type.jsonl $EXPORT/relationship*.$type.jsonl
  $ZIP_TREE $archive_with_date $EXPORT/$DOCU/node*.$type.jsonl.$DOCU_MD $EXPORT/$DOCU/relationship*.$type.jsonl.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
      cp $archive $PUBLIC_EXPORTS_DIR
  fi

  # Aggreagted json is basic exports, see above
  # format=json
  # type=aggregated

  format=json
  type=all
  base_name=lobbywatch_export_$type
  echo -e "\nPack $base_name.$format"
  archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
  archive=$EXPORT/$base_name$export_type.$format.zip
  [ -f "$archive_with_date" ] && rm $archive_with_date
  $ZIP $archive_with_date $DOCS $EXPORT/*.$format
  $ZIP_TREE $archive_with_date       $EXPORT/$DOCU/*.$format.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
      cp $archive $PUBLIC_EXPORTS_DIR
  fi

  format=json
  type=flat
  base_name=lobbywatch_export_$type
  echo -e "\nPack $base_name.$format"
  archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
  archive=$EXPORT/$base_name$export_type.$format.zip
  [ -f "$archive_with_date" ] && rm $archive_with_date
  $ZIP $archive_with_date $DOCS $EXPORT/$type*.$format
  $ZIP_TREE $archive_with_date       $EXPORT/$DOCU/$type*.$format.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
      cp $archive $PUBLIC_EXPORTS_DIR
  fi

  format=jsonl
  type=flat
  base_name=lobbywatch_export_$type
  echo -e "\nPack $base_name.$format"
  archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
  archive=$EXPORT/$base_name$export_type.$format.zip
  [ -f "$archive_with_date" ] && rm $archive_with_date
  $ZIP $archive_with_date $DOCS $EXPORT/$type*.$format
  $ZIP_TREE $archive_with_date       $EXPORT/$DOCU/$type*.$format.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
      cp $archive $PUBLIC_EXPORTS_DIR
  fi

  format=xml
  type=aggregated
  base_name=lobbywatch_export_$type
  echo -e "\nPack $base_name.$format"
  archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
  archive=$EXPORT/$base_name$export_type.$format.zip
  [ -f "$archive_with_date" ] && rm $archive_with_date
  $ZIP $archive_with_date $DOCS $EXPORT/$type*.$format
  $ZIP_TREE $archive_with_date       $EXPORT/$DOCU/$type*.$format.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
      cp $archive $PUBLIC_EXPORTS_DIR
  fi

  format=xml
  type=all
  base_name=lobbywatch_export_$type
  echo -e "\nPack $base_name.$format"
  archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
  archive=$EXPORT/$base_name$export_type.$format.zip
  [ -f "$archive_with_date" ] && rm $archive_with_date
  $ZIP $archive_with_date $DOCS $EXPORT/*.$format
  $ZIP_TREE $archive_with_date       $EXPORT/$DOCU/*.$format.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
      cp $archive $PUBLIC_EXPORTS_DIR
  fi

  format=xml
  type=flat
  base_name=lobbywatch_export_$type
  echo -e "\nPack $base_name.$format"
  archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
  archive=$EXPORT/$base_name$export_type.$format.zip
  [ -f "$archive_with_date" ] && rm $archive_with_date
  $ZIP $archive_with_date $DOCS $EXPORT/$type*.$format
  $ZIP_TREE $archive_with_date       $EXPORT/$DOCU/$type*.$format.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
      cp $archive $PUBLIC_EXPORTS_DIR
  fi

  format=yaml
  type=aggregated
  base_name=lobbywatch_export_$type
  echo -e "\nPack $base_name.$format"
  archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
  archive=$EXPORT/$base_name$export_type.$format.zip
  [ -f "$archive_with_date" ] && rm $archive_with_date
  $ZIP $archive_with_date $DOCS $EXPORT/$type*.$format
  $ZIP_TREE $archive_with_date       $EXPORT/$DOCU/$type*.$format.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
      cp $archive $PUBLIC_EXPORTS_DIR
  fi

  format=yaml
  type=all
  base_name=lobbywatch_export_$type
  echo -e "\nPack $base_name.$format"
  archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
  archive=$EXPORT/$base_name$export_type.$format.zip
  [ -f "$archive_with_date" ] && rm $archive_with_date
  $ZIP $archive_with_date $DOCS $EXPORT/*.$format
  $ZIP_TREE $archive_with_date       $EXPORT/$DOCU/*.$format.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
      cp $archive $PUBLIC_EXPORTS_DIR
  fi

  format=yaml
  type=flat
  base_name=lobbywatch_export_$type
  echo -e "\nPack $base_name.$format"
  archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
  archive=$EXPORT/$base_name$export_type.$format.zip
  [ -f "$archive_with_date" ] && rm $archive_with_date
  $ZIP $archive_with_date $DOCS $EXPORT/$type*.$format
  $ZIP_TREE $archive_with_date       $EXPORT/$DOCU/$type*.$format.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
      cp $archive $PUBLIC_EXPORTS_DIR
  fi

  format=md
  type=aggregated
  base_name=lobbywatch_export_$type
  echo -e "\nPack $base_name.$format"
  archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
  archive=$EXPORT/$base_name$export_type.$format.zip
  [ -f "$archive_with_date" ] && rm $archive_with_date
  $ZIP $archive_with_date $DOCS $EXPORT/$type*.$format
  $ZIP_TREE $archive_with_date       $EXPORT/$DOCU/$type*.$format.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
      cp $archive $PUBLIC_EXPORTS_DIR
  fi

  format=md
  type=all
  base_name=lobbywatch_export_$type
  echo -e "\nPack $base_name.$format"
  archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
  archive=$EXPORT/$base_name$export_type.$format.zip
  [ -f "$archive_with_date" ] && rm $archive_with_date
  $ZIP $archive_with_date $DOCS $EXPORT/*.$format
  $ZIP_TREE $archive_with_date       $EXPORT/$DOCU/*.$format.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
      cp $archive $PUBLIC_EXPORTS_DIR
  fi

  format=md
  type=flat
  base_name=lobbywatch_export_$type
  echo -e "\nPack $base_name.$format"
  archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
  archive=$EXPORT/$base_name$export_type.$format.zip
  [ -f "$archive_with_date" ] && rm $archive_with_date
  $ZIP $archive_with_date $DOCS $EXPORT/$type*.$format
  $ZIP_TREE $archive_with_date       $EXPORT/$DOCU/$type*.$format.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
      cp $archive $PUBLIC_EXPORTS_DIR
  fi

fi

END_OVERALL=$(date +%s)
DIFF=$(( $END_OVERALL - $START_OVERALL ))
echo -e "\n$(date '+%F %T')" "Overall elapsed:" $(convertsecs $DIFF) "(${DIFF}s)"
echo -e "\n$(date '+%F %T')" "Overall elapsed:" $(convertsecs $DIFF) "(${DIFF}s)" >> $EXPORT_LOG

if $publish; then
    cp $DOCS $PUBLIC_EXPORTS_DIR
    echo -e "\nPublished exports to $PUBLIC_EXPORTS_DIR:"
    cp $EXPORT_LOG $PUBLIC_EXPORTS_DIR
    $LS $PUBLIC_EXPORTS_DIR
fi

quit
