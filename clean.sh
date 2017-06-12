#!/bin/bash

# Abort on errors
set -e

root_dir=public_html

rm `find $root_dir -name '*.bak'` -v
