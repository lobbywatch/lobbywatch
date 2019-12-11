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

# Do not abort on errors in common.sh
# set -e

black='\e[0;30m'
blackBold='\e[1;30m'
blackBackground='\e[1;40m'
red='\e[0;31m'
redBold='\e[1;31m'
redBackground='\e[0;41m'
green='\e[0;32m'
greenBold='\e[1;32m'
greenBackground='\e[0;42m'
yellow='\e[0;33m'
yellowBold='\e[1;33m'
yellowBackground='\e[0;43m'
blue='\e[0;34m'
blueBold='\e[1;34m'
blueBackground='\e[0;44m'
magenta='\e[0;35m'
magentaBold='\e[1;35m'
magentaBackground='\e[0;45m'
cyan='\e[0;36m'
cyanBold='\e[1;36m'
cyanBackground='\e[0;46m'
white='\e[0;37m'
whiteBold='\e[1;37m'
whiteBackground='\e[0;47m'
reset='\e[0m'

HOST=127.0.0.1
MYSQL_CONTAINER=mysql57
# MYSQLADMIN=mysqladmin
MYSQLADMIN="docker exec -it $MYSQL_CONTAINER mysqladmin"
DB_USER=script

DATE=`date +%Y%m%d_%H%M%S`
DATE_SHORT=`date +%Y%m%d`
HUMAN_NOW=$(date +"%d.%m.%Y %H:%M");
HUMAN_DATE=$(date +"%d.%m.%Y");

# Ref: http://stackoverflow.com/questions/12199631/convert-seconds-to-hours-minutes-seconds-in-bash
convertsecs() {
 ((h=${1}/3600))
 ((m=${1}%3600/60))
 ((s=${1}%60))
 printf "%02d:%02d:%02d\n" $h $m $s
}

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

# http://superuser.com/questions/878640/unix-script-wait-until-a-file-exists
# Wait at most 5 seconds for the server.log file to appear
#
# Example:
# server_log=/var/log/jboss/server.log
# wait_file "$server_log" 5 || {
#   echo "JBoss log file missing after waiting for $? seconds: '$server_log'"
#   exit 1
# }
wait_file() {
  local file="$1"; shift
  local wait_seconds="${1:-10}"; shift # 10 seconds as default timeout
  local max_wait_seconds=wait_seconds

  until test $((wait_seconds--)) -eq 0 -o -e "$file" ; do sleep 1; done

  ((++wait_seconds))
}

# Wait mysql started
# argument 1: wait seconds, default 10s
wait_mysql() {
  local wait_seconds="${1:-10}"; shift # 10 seconds as default timeout
  local max_wait_seconds=wait_seconds

  OK=false
  until test $((wait_seconds--)) -eq 0 -o $OK ; do sleep 1; $MYSQLADMIN -h$HOST -u$DB_USER processlist >/dev/null 2>&1 && OK=true; done
  # $MYSQLADMIN -h$HOST -u$DB_USER processlist >/dev/null 2>&1
  # until test $((wait_seconds--)) -eq 0 -o $? -eq 0 ; do sleep 1; $MYSQLADMIN -h$HOST -u$DB_USER processlist >/dev/null 2>&1; done

  ((++wait_seconds))
}


# Check if local MySQL is running, if not, ask starting
checkLocalMySQLRunning() {
  wait_secs=15

  mysqladmin -h$HOST -u$DB_USER processlist >/dev/null 2>&1 && OK=true || OK=false
  if $OK ; then
    # default MySQL is running, return
    return
  fi
  # else try docker

    case "$USER" in
      "rkurmann" )
        # mysqlSock="/home/rkurmann/dev/web/mysql/mysql57/data/mysql.sock"
        # if [ ! -e "$mysqlSock" ]; then
        $MYSQLADMIN -h$HOST -u$DB_USER processlist >/dev/null 2>&1 && OK=true || OK=false
        if ! $OK ; then
          askContinueYn "Docker $MYSQL_CONTAINER not running. Start?"

          docker start $MYSQL_CONTAINER

          echo "Wait MySQL starting..."
          wait_mysql $wait_secs || {
            echo "DB not running after $wait_secs s"
            exit 1
          }
          docker ps
        fi
        ;;
      "rkurmannXampp" )
        $MYSQLADMIN -h$HOST -u$DB_USER processlist >/dev/null 2>&1 && OK=true || OK=false
        if ! $OK ; then
          askContinueYn "DB not running. Start?"

          # start xampp
          sudo /opt/lampp/xampp restart

          sudo mv /usr/bin/mysql /usr/bin/~mysql.bak
          sudo ln -s /opt/lampp/bin/mysql /usr/bin/mysql

          sudo mv /usr/bin/mysqladmin /usr/bin/~mysqladmin.bak
          sudo ln -s /opt/lampp/bin/mysqladmin /usr/bin/mysqladmin

          sudo mv /usr/bin/mysqldump /usr/bin/~mysqldump.bak
          sudo ln -s /opt/lampp/bin/mysqldump /usr/bin/mysqldump

          sudo mv /usr/bin/mysql_config /usr/bin/~mysql_config.bak
          sudo ln -s /opt/lampp/bin/mysql_config /usr/bin/mysql_config
          # end xampp

          echo "Wait MySQL starting..."
          wait_mysql $wait_secs || {
            echo "MySQL not running after $wait_secs s"
            exit 1
          }
        fi
        ;;
      "lobbywat" )
        # Do nothing
        ;;
      * )
        askContinueYn "MySQL not running. Start MySQL manually. Ready?"
    esac
}

ensure_remote() {
  # echo "Env: [$env]"
  if [[ $env == local_* ]]; then
    echo "Local environment not supported for this operation"
    exit 2
  fi
}

ensure_local() {
  # echo "Env: [$env]"
  if [[ $env != local_* ]]; then
    echo "Remote environment not supported for this operation"
    exit 3
  fi
}

# http://stackoverflow.com/questions/5431909/bash-functions-return-boolean-to-be-used-in-if
# Use 0 for true and 1 for false.
# Usage: if is_local; then echo "local"; else echo "remote"; fi
is_local() {
  # echo "Env: [$env]"
  if [[ $env == local_* ]]; then
    # 0 = true
    return 0
  else
    # 1 = false
    return 1
  fi
}

get_local_DB() {
  ensure_local
  echo "${env#*_}"
}

# _alarm 400 200
# http://unix.stackexchange.com/questions/1974/how-do-i-make-my-pc-speaker-beep
_alarm() {
# play -n synth 0.400 sin 200 > /dev/null 2> /dev/null
  play -n synth 0.${2} sin $1 > /dev/null 2> /dev/null || true
#   ( \speaker-test --frequency $1 --test sine > /dev/null ) > /dev/null 2> /dev/null &
#   pid=$!
#   \sleep 0.${2}s
#   \kill -9 $pid > /dev/null 2> /dev/null
}

beep() {
  _alarm 400 200
}

ask_PW() {
    if [[ $1 ]]; then
        msg="$1 "
    else
        msg="Password:"
    fi

    # http://stackoverflow.com/questions/3231804/in-bash-how-to-add-are-you-sure-y-n-to-any-command-or-alias
    read -e -p "${msg} " PW; echo
}

ask_DB_PW() {
    ask_PW

    if [[ "$PW" != "" ]]; then
        PW="-p$PW"
    fi
}

# https://stackoverflow.com/questions/2870992/automatic-exit-from-bash-shell-script-on-error
abort() {
  line=$1
  caller=$(caller)
    echo '
***************
*** ABORTED ***
***************
' >&2
    echo "An error occurred on line $caller. Exiting..." >&2
    date -Iseconds >&2
    exit 1
}

#quit or trap 'abort' 0 must be called, for sucessful exit
enable_fail_onerror() {
  # Abort on errors
  # https://stackoverflow.com/questions/2870992/automatic-exit-from-bash-shell-script-on-error
  trap 'abort $LINENO' ERR
  # https://sipb.mit.edu/doc/safe-shell/
  set -e -o pipefail
  # set -u
}

quit() {
  trap : 0
  exit 0
}

# https://stackoverflow.com/questions/2990414/echo-that-outputs-to-stderr
echoerr() {
  echo "$@" 1>&2;
  # printf "%s\n" "$*" >&2;
}
