#!/bin/bash
source virtual_environment_lobbywatch/bin/activate

echo ""
echo "ZUTRITTSBERECHTIGUNGEN"
echo "======================"

echo "creating json..."
python3 zb_create_json.py 
echo "writing delta.sql..."
python3 zb_create_delta.py > zutrittsberechtigungen-delta.sql
grep -q "DATA CHANGED" zutrittsberechtigungen-delta.sql
STATUS=$?
if (($STATUS == 0)) ; then
    echo -e "\nData changed"
    CHANGED=true
else
    echo -e "\nData not changed"
    CHANGED=false
fi

echo ""
echo "PARLAMENTARISCHE GRUPPEN"
echo "======================"

echo "creating json..."
python3 pg_create_json.py 
echo "writing delta.sql..."
python3 pg_create_delta.py > parlamentarische_gruppen-delta.sql
grep -q "DATA CHANGED" parlamentarische_gruppen-delta.sql
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
