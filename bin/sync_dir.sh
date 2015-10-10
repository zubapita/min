#!/bin/sh
#
# Deploy
# Sync to production run server
#
RSYNC_TEST="-auvzn -e ssh -O"
RSYNC_EXEC="-auvz -e ssh -O"
EXCLUDE="--exclude=.svn --exclude=.git --exclude=.editorconfig --exclude=.gitignore --exclude=var/ --exclude=tmp/"
DEPLOY_ROOT="account@domain:~/Web/{$appName}"

rsync ${RSYNC_TEST} ${EXCLUDE} ../* ${DEPLOY_ROOT}/
echo ""
while : ; do
	echo -n "Are you sure ? [y/n] : "
	read answer
	if [ "$answer" = "y" -o "$answer" = "Y" ] ; then
		echo "sync"
		echo "";
		rsync ${RSYNC_EXEC} ${EXCLUDE} ../* ${DEPLOY_ROOT}/

		exit
	elif [ "$answer" = "n" -o "$answer" = "N" ] ; then
		exit
	fi
done
echo ""

