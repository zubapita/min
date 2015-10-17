#!/bin/sh
#
# Sync new source code from Latest Min
#
RSYNC_TEST="-auvzn"
RSYNC_EXEC="-auvz"
EXCLUDE="--exclude=.svn --exclude=.git --exclude=.editorconfig --exclude=.gitignore --exclude=var/ --exclude=tmp/"
MIN_DIR="../../min"

echo "---------------"
echo ""
echo "/bin/*"
rsync ${RSYNC_TEST} ${EXCLUDE} ${MIN_DIR}/bin/* ../bin/
echo ""
while : ; do
	echo -n "Are you sure ? [y/n] : "
	read answer
	if [ "$answer" = "y" -o "$answer" = "Y" ] ; then
		echo "sync"
		echo "";
		rsync ${RSYNC_EXEC} ${EXCLUDE} ${MIN_DIR}/bin/* ../bin/

		break
	elif [ "$answer" = "n" -o "$answer" = "N" ] ; then
		echo "don't sync."
		break
	fi
done

echo "---------------"
echo ""
echo "/etc/template/*"
rsync ${RSYNC_TEST} ${EXCLUDE} ${MIN_DIR}/etc/template/* ../etc/template/
echo ""
while : ; do
	echo -n "Are you sure ? [y/n] : "
	read answer
	if [ "$answer" = "y" -o "$answer" = "Y" ] ; then
		echo "sync"
		echo "";
		rsync ${RSYNC_EXEC} ${EXCLUDE} ${MIN_DIR}/etc/template/* ../etc/template/

		break
	elif [ "$answer" = "n" -o "$answer" = "N" ] ; then
		echo "don't sync."
		break
	fi
done

echo "---------------"
echo ""
echo "/lib/*"
rsync ${RSYNC_TEST} ${EXCLUDE} ${MIN_DIR}/lib/* ../lib/
echo ""
while : ; do
	echo -n "Are you sure ? [y/n] : "
	read answer
	if [ "$answer" = "y" -o "$answer" = "Y" ] ; then
		echo "sync"
		echo "";
		rsync ${RSYNC_EXEC} ${EXCLUDE} ${MIN_DIR}/lib/* ../lib/

		break
	elif [ "$answer" = "n" -o "$answer" = "N" ] ; then
		echo "don't sync."
		break
	fi
done

echo "---------------"
echo ""
echo "/view/cmn/*"
rsync ${RSYNC_TEST} ${EXCLUDE} ${MIN_DIR}/view/cmn/* ../view/cmn/
echo ""
while : ; do
	echo -n "Are you sure ? [y/n] : "
	read answer
	if [ "$answer" = "y" -o "$answer" = "Y" ] ; then
		echo "sync"
		echo "";
		rsync ${RSYNC_EXEC} ${EXCLUDE} ${MIN_DIR}/view/cmn/* ../view/cmn/

		break
	elif [ "$answer" = "n" -o "$answer" = "N" ] ; then
		echo "don't sync."
		break
	fi
done


echo ""

