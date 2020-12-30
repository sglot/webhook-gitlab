#!/bin/bash
HOST=$1
NOW=$(date +"%Y-%m-%d")
DIR=/home/bitrix/www/webhook-gitlab/logs/$HOST/

if [[ ! -d ${DIR} ]]; then
    mkdir -p $DIR
fi

echo " " >> $DIR/git_update_$NOW.log 2>&1
echo `date` $HOST >> $DIR/git_update_$NOW.log 2>&1

HOME=/home/bitrix/www/
export HOME
cd $HOME

git reset --hard origin/master >> $DIR/git_update_$NOW.log 2>&1
echo " " >> $DIR/git_update_$NOW.log 2>&1

git pull origin master >> $DIR/git_update_$NOW.log 2>&1
echo " " >> $DIR/git_update_$NOW.log 2>&1

