#!/bin/bash

# Abort on errors
set -e

./gen_lobbywatch_edit.sh
./afterburner.sh "$@"
