#!/bin/bash
source virtual_environment_lobbywatch/bin/activate
python3 create_json.py 
echo ""
echo "writing delta.sql..."
python3 create_delta.py > delta.sql
deactivate
echo "done!"
