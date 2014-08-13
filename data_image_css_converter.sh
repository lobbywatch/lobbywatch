#!/bin/bash

# Copied from http://stackoverflow.com/questions/5465446/replacing-all-images-in-a-css-file-with-base64-encoded-strings-from-the-command

# $ data_image_css_converter.sh file

awk -F'[()]' '

/background-image: url(.*)/ {
  cmd=sprintf("base64 -w0 %s",$2)
  cmd | getline b64
  close(cmd)
  $0=$1 "(data:image/png;base64," b64 ");"
}1' $1