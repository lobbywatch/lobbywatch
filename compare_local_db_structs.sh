#!/bin/bash

# ./run_local_db_script.sh lobbywatchtest db_procedures_triggers.sql ; ./compare_local_db_structs.sh -x

# \s*-- .*

# http://unix.stackexchange.com/questions/17040/how-to-diff-files-ignoring-comments-lines-starting-with

. common.sh

verbose_mode=false
verbose=""
visual=false

# Ref: http://stackoverflow.com/questions/7069682/how-to-get-arguments-with-flags-in-bash-script
while test $# -gt 0; do
        case "$1" in
                -h|--help)
                        echo "compare local DB structs"
                        echo " "
                        echo "$0 [options]"
                        echo " "
                        echo "options:"
                        echo "-x, --visual              Visual compare"
                        echo "-h, --help                Show brief help"
                        exit 0
                        ;;
                -x|--visual)
                        shift
                        visual=true
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


./run_local_db_script.sh lobbywatch dbdump_struct
db1_struct=`cat last_dbdump_file.txt`
db1_struct_tmp=/tmp/$db1_struct
mkdir -p `dirname $db1_struct_tmp`
grep -vE '^\s*(\/\*!50003 SET (sql_mode|character_set_client|character_set_results|collation_connection))|FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+' $db1_struct > $db1_struct_tmp

./run_local_db_script.sh lobbywatchtest dbdump_struct
db2_struct=`cat last_dbdump_file.txt`
db2_struct_tmp=/tmp/$db2_struct
mkdir -p `dirname $db2_struct_tmp`
grep -vE '^\s*(\/\*!50003 SET (sql_mode|character_set_client|character_set_results|collation_connection))|FOR EACH ROW thisTrigger: begin$|FOR EACH ROW$|for each row\s*$|thisTrigger: begin\s*$|thisTrigger: BEGIN$|--\s+' $db2_struct > $db2_struct_tmp

echo "diff -u -w $db1_struct_tmp $db2_struct_tmp | less"

# grep -vE '^\s*(\/\*!50003 SET sql_mode)' `cat last_dbdump_file.txt`
if $visual ; then
  kompare $db1_struct_tmp $db2_struct_tmp &
else
  diff -u -w $db1_struct_tmp $db2_struct_tmp | less
fi
