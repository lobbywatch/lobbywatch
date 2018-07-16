#!/bin/bash

docker build $1 $2 $3 $4 -t debian-mysql-server-5.7 .

echo "Ignore cache with --no-cache"
