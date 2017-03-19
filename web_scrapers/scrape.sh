#!/bin/bash
source virtual_environment_lobbywatch/bin/activate
python3 scrape_zutrittsberechtigte.py
echo ""
echo "writing delta.sql..."
python3 create_zutrittsberechtigte_update.py > delta.sql
deactivate
echo "done!"
