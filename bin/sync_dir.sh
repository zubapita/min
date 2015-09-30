#!/bin/sh
#
# Sync to deploy server
#
RSYNC_TEST="-auvzn -e ssh -O"
RSYNC_EXEC="-auvz -e ssh -O"
EXCLUDE="--exclude=.svn --exclude=.git --exclude=.editorconfig --exclude=.gitignore --exclude=var/ --exclude=tmp/"
APP_NAME=booklover

rsync ${RSYNC_TEST} ${EXCLUDE} ../* edit@dev01.minutesbook.jp:~/bin/report/app/
echo ""
while : ; do
	echo -n "Are you sure ? [y/n] : "
	read answer
	if [ "$answer" = "y" -o "$answer" = "Y" ] ; then
		echo "sync"
		echo "";
		rsync ${RSYNC_EXEC} ${EXCLUDE} ../* edit@dev01.minutesbook.jp:~/bin/report/app/

		exit
	elif [ "$answer" = "n" -o "$answer" = "N" ] ; then
		exit
	fi
done
echo ""

