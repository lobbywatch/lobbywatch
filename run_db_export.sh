#!/bin/bash

# Call for production
# ./run_db_export.sh -v -t -d=lobbywat_lobbywatch --user-prefix=reader_ -p

# TODO fix include_once version.php Problem

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
FILE_SECRET_DIR=".exports_secret_dir"
FILE_SECRET_PUBLISH_DIR=".exports_secret_publish_dir"
SECRET_DIR_PATTERN="__secret_dir__"

echo -e "Lobbywatch DB Export" >$EXPORT_LOG
echo -e "====================" >>$EXPORT_LOG
echo >>$EXPORT_LOG

test_parameter=''
export_options=''
publish=false
publish_dir=''
param_schema=''
db_schema='lobbywatchtest'
param_db=''
param_user_prefix=''
all_data='--slow'
export_formats='-c -s -m -g -j -o -l --arangodb -x -t'
basic_export=false
refresh=false
publish_to_secret_dir=false
secret_dir=''

# http://stackoverflow.com/questions/192249/how-do-i-parse-command-line-arguments-in-bash
while test $# -gt 0; do
  case $1 in
  -h | --help)
    echo "Export Lobbywatch DB"
    echo
    echo "$0 [options]"
    echo
    echo "Options:"
    echo "-e LIST                          Type of data to export, add this type of data -e hist, -e intern, -e unpubl, -e hist+unpubl+intern (default: filter at most)"
    echo "-t, --test                       Test mode: print DB views comments JSON (implies -n)"
    echo "-n, --limit                      Limit number of records"
    echo "-p[=DIR], --publish[=DIR]        Publish exports to public folder, $SECRET_DIR_PATTERN is substituted by the generated secret dir (default: env LW_PUBLIC_EXPORTS_DIR='$LW_PUBLIC_EXPORTS_DIR'"
    echo "-s KEY                           Publish to a secret directory using KEY"
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
  -r | --refresh)
    refresh=true
    shift
    ;;
  -b | --basic)
    export_formats='-c -s -m -j'
    basic_export=true
    shift
    ;;
  -p | --publish)
    publish=true
    shift
    ;;
  -p=* | --publish=*)
    publish_dir="${1#*=}"
    publish=true
    shift
    ;;
  -t | --test)
    test_parameter="-n --comments"
    all_data=''
    shift
    ;;
  -n | --limit)
    test_parameter="-n"
    all_data=''
    shift
    ;;
  -e)
    export_options=$2
    shift
    shift
    ;;
  -s)
    publish_to_secret_dir=true
    key=$2
    # %Y: 4 digit year, %m: 2 digit month, %S: 2 digit seconds, %M: 2 digits minutes
    sha_input="${key}_$(date +%Y-%m)"
    secret_dir=$($PHP -r "print(rtrim(strtr(base64_encode(sha1('$sha_input', true)), '+/', '-_'), '='));")
    shift
    shift
    ;;
  -v | --verbose)
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
  *)
    break
    ;;
  esac
done

checkLocalMySQLRunning

START_OVERALL=$(date +%s)

if $refresh; then
  echo -e "$(date '+%F %T') Refresh DB '$db_schema' ..."
  ./run_db_script.sh $db_schema lobbywat_script db_views.sql cronverbose
fi

if [[ "$publish_dir" == "" ]]; then
  if [[ "$LW_PUBLIC_EXPORTS_DIR" == "" ]]; then
    echo -e "\nERROR: LW_PUBLIC_EXPORTS_DIR environment variable is not set"
    abort
  else
    publish_dir=$LW_PUBLIC_EXPORTS_DIR
  fi
fi

base_secret_dir=${publish_dir%$SECRET_DIR_PATTERN*}
publish_dir=${publish_dir/$SECRET_DIR_PATTERN/$secret_dir}

if $publish && $publish_to_secret_dir; then
  [ -f $FILE_SECRET_DIR ] && old_secret_dir=$(cat $FILE_SECRET_DIR) || old_secret_dir=''
  [ -f $FILE_SECRET_PUBLISH_DIR ] && old_secret_export_dir=$(cat $FILE_SECRET_PUBLISH_DIR) || old_secret_export_dir=''

  if [[ "$old_secret_dir" == "" ]]; then
    echo "'$FILE_SECRET_DIR' does not exist"
    abort
  elif [[ "$old_secret_export_dir" != "$publish_dir" ]]; then
    echo "Secret dir changed: mv $base_secret_dir$old_secret_dir $base_secret_dir$secret_dir"
    mv "$base_secret_dir$old_secret_dir" "$base_secret_dir$secret_dir"
  fi
  echo "$secret_dir" >$FILE_SECRET_DIR
  echo "$publish_dir" >$FILE_SECRET_PUBLISH_DIR
fi

export_type=''
if [ "$export_options" != "" ]; then
  export_type="_$export_options"
fi

echo -e "$(date '+%F %T') Start exporting..."

$PHP -f db_export.php -- -v $export_formats -e=$export_options $all_data $test_parameter $param_schema $param_db $param_user_prefix

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
$ZIP_TREE $archive_with_date $EXPORT/$DOCU/*.$format.$DOCU_MD
cp $archive_with_date $archive
$LS $archive_with_date $archive
if $publish; then
  cp $archive $publish_dir
fi

format=csv
type=flat
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
  cp $archive $publish_dir
fi

format=csv
type=parlamentarier
base_name=lobbywatch_export_$type
echo -e "\nPack $base_name.$format"
archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
archive=$EXPORT/$base_name$export_type.$format.zip
[ -f "$archive_with_date" ] && rm $archive_with_date
$ZIP $archive_with_date $DOCS $EXPORT/cartesian_essential_parlamentarier_interessenbindung.csv $EXPORT/cartesian_minimal_parlamentarier_interessenbindung.csv $EXPORT/cartesian_parlamentarier_verguetungstransparenz.csv $EXPORT/cartesian_minimal_parlamentarier_zutrittsberechtigung.csv $EXPORT/cartesian_minimal_parlamentarier_zutrittsberechtigung_mandat.csv
$ZIP_TREE $archive_with_date $EXPORT/$DOCU/cartesian_essential_parlamentarier_interessenbindung.csv.$DOCU_MD $EXPORT/$DOCU/cartesian_minimal_parlamentarier_interessenbindung.csv.$DOCU_MD $EXPORT/$DOCU/cartesian_parlamentarier_verguetungstransparenz.csv.$DOCU_MD $EXPORT/$DOCU/cartesian_minimal_parlamentarier_zutrittsberechtigung.csv.$DOCU_MD $EXPORT/$DOCU/cartesian_minimal_parlamentarier_zutrittsberechtigung_mandat.csv.$DOCU_MD
cp $archive_with_date $archive
$LS $archive_with_date $archive
if $publish; then
  cp $archive $publish_dir
fi

format=csv
base_name=lobbywatch_export_parlamentarier_transparenzliste
echo -e "\nPack $base_name.$format"
archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
archive=$EXPORT/$base_name$export_type.$format.zip
[ -f "$archive_with_date" ] && rm $archive_with_date
$ZIP $archive_with_date $DOCS $EXPORT/cartesian_parlamentarier_verguetungstransparenz.csv
$ZIP_TREE $archive_with_date $EXPORT/$DOCU/cartesian_parlamentarier_verguetungstransparenz.csv.$DOCU_MD
cp $archive_with_date $archive
$LS $archive_with_date $archive
if $publish; then
  cp $archive $publish_dir
fi

format=sql
base_name=lobbywatch_export
echo -e "\nPack $base_name.$format"
archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
archive=$EXPORT/$base_name$export_type.$format.zip
[ -f "$archive_with_date" ] && rm $archive_with_date
$ZIP $archive_with_date $DOCS $EXPORT/*.$format
$ZIP_TREE $archive_with_date $EXPORT/$DOCU/*.$format.$DOCU_MD
cp $archive_with_date $archive
$LS $archive_with_date $archive
if $publish; then
  cp $archive $publish_dir
fi

format=graphml
base_name=lobbywatch_export
echo -e "\nPack $base_name.$format"
archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
archive=$EXPORT/$base_name$export_type.$format.zip
[ -f "$archive_with_date" ] && rm $archive_with_date
$ZIP $archive_with_date $DOCS $EXPORT/*.$format
$ZIP_TREE $archive_with_date $EXPORT/$DOCU/*.$format.$DOCU_MD
cp $archive_with_date $archive
$LS $archive_with_date $archive
if $publish; then
  cp $archive $publish_dir
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
  cp $archive $publish_dir
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
    cp $archive $publish_dir
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
    cp $archive $publish_dir
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
    cp $archive $publish_dir
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
  $ZIP_TREE $archive_with_date $EXPORT/$DOCU/*.$format.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
    cp $archive $publish_dir
  fi

  format=json
  type=flat
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
    cp $archive $publish_dir
  fi

  format=jsonl
  type=flat
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
    cp $archive $publish_dir
  fi

  format=xml
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
    cp $archive $publish_dir
  fi

  format=xml
  type=all
  base_name=lobbywatch_export_$type
  echo -e "\nPack $base_name.$format"
  archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
  archive=$EXPORT/$base_name$export_type.$format.zip
  [ -f "$archive_with_date" ] && rm $archive_with_date
  $ZIP $archive_with_date $DOCS $EXPORT/*.$format
  $ZIP_TREE $archive_with_date $EXPORT/$DOCU/*.$format.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
    cp $archive $publish_dir
  fi

  format=xml
  type=flat
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
    cp $archive $publish_dir
  fi

  format=yaml
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
    cp $archive $publish_dir
  fi

  format=yaml
  type=all
  base_name=lobbywatch_export_$type
  echo -e "\nPack $base_name.$format"
  archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
  archive=$EXPORT/$base_name$export_type.$format.zip
  [ -f "$archive_with_date" ] && rm $archive_with_date
  $ZIP $archive_with_date $DOCS $EXPORT/*.$format
  $ZIP_TREE $archive_with_date $EXPORT/$DOCU/*.$format.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
    cp $archive $publish_dir
  fi

  format=yaml
  type=flat
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
    cp $archive $publish_dir
  fi

  format=md
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
    cp $archive $publish_dir
  fi

  format=md
  type=all
  base_name=lobbywatch_export_$type
  echo -e "\nPack $base_name.$format"
  archive_with_date=$EXPORT/${DATE_SHORT}_$base_name$export_type.$format.zip
  archive=$EXPORT/$base_name$export_type.$format.zip
  [ -f "$archive_with_date" ] && rm $archive_with_date
  $ZIP $archive_with_date $DOCS $EXPORT/*.$format
  $ZIP_TREE $archive_with_date $EXPORT/$DOCU/*.$format.$DOCU_MD
  cp $archive_with_date $archive
  $LS $archive_with_date $archive
  if $publish; then
    cp $archive $publish_dir
  fi

  format=md
  type=flat
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
    cp $archive $publish_dir
  fi

fi

END_OVERALL=$(date +%s)
DIFF=$(($END_OVERALL - $START_OVERALL))
echo -e "\n$(date '+%F %T')" "Overall elapsed:" $(convertsecs $DIFF) "(${DIFF}s)"
echo -e "\n$(date '+%F %T')" "Overall elapsed:" $(convertsecs $DIFF) "(${DIFF}s)" >>$EXPORT_LOG

if $publish; then
  cp $DOCS $publish_dir
  echo -e "\nPublished exports to $publish_dir:"
  cp $EXPORT_LOG $publish_dir
  $LS $publish_dir

  # Clean up histoised files as they consume a lot of memory
  rm $EXPORT/202*hist.*.zip
fi

quit
