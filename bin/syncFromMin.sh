#!/bin/sh
#
# Back Sync to Min original Lib
#
RSYNC_TEST="-auvzn"
RSYNC_EXEC="-auvz"
EXCLUDE="--exclude=.svn --exclude=.git --exclude=.editorconfig --exclude=.gitignore --exclude=var/ --exclude=tmp/"
APP_NAME=booklover

echo "---------------"
echo ""
echo "/bin/*"
rsync ${RSYNC_TEST} ${EXCLUDE} ../bin/* ../../min/bin/
echo ""
while : ; do
	echo -n "Are you sure ? [y/n] : "
	read answer
	if [ "$answer" = "y" -o "$answer" = "Y" ] ; then
		echo "sync"
		echo "";
		rsync ${RSYNC_EXEC} ${EXCLUDE} ../bin/* ../../min/bin/

		break
	elif [ "$answer" = "n" -o "$answer" = "N" ] ; then
		echo "don't sync."
		break
	fi
done

echo "---------------"
echo ""
echo "/etc/template/*"
rsync ${RSYNC_TEST} ${EXCLUDE} ../etc/template/* ../../min/etc/template/
echo ""
while : ; do
	echo -n "Are you sure ? [y/n] : "
	read answer
	if [ "$answer" = "y" -o "$answer" = "Y" ] ; then
		echo "sync"
		echo "";
		rsync ${RSYNC_EXEC} ${EXCLUDE} ../etc/template/* ../../min/etc/template/

		break
	elif [ "$answer" = "n" -o "$answer" = "N" ] ; then
		echo "don't sync."
		break
	fi
done

echo "---------------"
echo ""
echo "/lib/*"
rsync ${RSYNC_TEST} ${EXCLUDE} ../lib/* ../../min/lib/
echo ""
while : ; do
	echo -n "Are you sure ? [y/n] : "
	read answer
	if [ "$answer" = "y" -o "$answer" = "Y" ] ; then
		echo "sync"
		echo "";
		rsync ${RSYNC_EXEC} ${EXCLUDE} ../lib/* ../../min/lib/

		break
	elif [ "$answer" = "n" -o "$answer" = "N" ] ; then
		echo "don't sync."
		break
	fi
done

echo "---------------"
echo ""
echo "/view/cmn/*"
rsync ${RSYNC_TEST} ${EXCLUDE} ../view/cmn/* ../../min/view/cmn/
echo ""
while : ; do
	echo -n "Are you sure ? [y/n] : "
	read answer
	if [ "$answer" = "y" -o "$answer" = "Y" ] ; then
		echo "sync"
		echo "";
		rsync ${RSYNC_EXEC} ${EXCLUDE} ../view/cmn/* ../../min/view/cmn/

		break
	elif [ "$answer" = "n" -o "$answer" = "N" ] ; then
		echo "don't sync."
		break
	fi
done



echo ""

