#!/bin/bash

# ./run_local_db_script.sh lobbywatchtest db_procedures.sql ; ./compare_local_db_structs.sh -x

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

./run_local_db_script.sh lobbywatchtest dbdump_struct
db2_struct=`cat last_dbdump_file.txt`

echo "diff -u -w $db1_struct $db2_struct | less"

if $visual ; then
  kompare $db1_struct $db2_struct
else
  diff -u -w $db1_struct $db2_struct | less
fi
