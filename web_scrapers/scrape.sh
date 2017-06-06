#!/bin/bash
source virtual_environment_lobbywatch/bin/activate
python3 create_json.py 
echo ""
echo "writing delta.sql..."
python3 create_delta.py > delta.sql
grep -q "DATA CHANGED" delta.sql
STATUS=$?
if (($STATUS == 0)) ; then
    echo -e "\nData changed"
    CHANGED=true
else
    echo -e "\nData not changed"
    CHANGED=false
fi
deactivate
echo "done!"
