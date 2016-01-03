#!/bin/bash

# Common functions and variables

# Colors,
# http://webhome.csc.uvic.ca/~sae/seng265/fall04/tips/s265s047-tips/bash-using-colors.html
# http://misc.flogisoft.com/bash/tip_colors_and_formatting
# Attribute codes:
# 00=none 01=bold 04=underscore 05=blink 07=reverse 08=concealed
#
# Text color codes:
# 30=black 31=red 32=green 33=yellow 34=blue 35=magenta 36=cyan 37=white
#
# Background color codes:
# 40=black 41=red 42=green 43=yellow 44=blue 45=magenta 46=cyan 47=white

green='\e[0;32m'
greenBold='\e[1;32m'
red='\e[0;31m'
redBold='\e[1;31m'
reset='\e[0m'


# Asks if [Yn] if script shoud continue, otherwise exit 1
# $1: msg or nothing
# Example call 1: askContinueYn
# Example call 1: askContinueYn "Backup DB?"
askContinueYn() {
  if [[ $1 ]]; then
    msg="$1 "
  else
    msg=""
  fi

  # http://stackoverflow.com/questions/3231804/in-bash-how-to-add-are-you-sure-y-n-to-any-command-or-alias
  read -e -p "${msg}Continue? [Y/n] " response
  response=${response,,}    # tolower
  if [[ $response =~ ^(yes|y|)$ ]] ; then
    # echo ""
    # OK
    :
  else
    echo "Aborted"
    exit 1
  fi
}
