#!/bin/bash

# $1: /path/to/css/file

awk -F'[()]' '

/background-image: url(.*)/ {
  cmd=sprintf("base64 -w0 %s",$2)
  cmd | getline b64
  close(cmd)
  $0=$1 "(data:image/png;base64," b64 ");"
}1' $1