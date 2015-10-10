#!/bin/sh
#
# Back sync update lib code to Min 
#
RSYNC_TEST="-auvzn"
RSYNC_EXEC="-auvz"
EXCLUDE="--exclude=.svn --exclude=.git --exclude=.editorconfig --exclude=.gitignore --exclude=var/ --exclude=tmp/"
MIN_DIR="../../min"

echo "---------------"
echo ""
echo "/bin/*"
rsync ${RSYNC_TEST} ${EXCLUDE} ../bin/* ${MIN_DIR}/bin/
echo ""
while : ; do
	echo -n "Are you sure ? [y/n] : "
	read answer
	if [ "$answer" = "y" -o "$answer" = "Y" ] ; then
		echo "sync"
		echo "";
		rsync ${RSYNC_EXEC} ${EXCLUDE} ../bin/* ${MIN_DIR}/bin/

		break
	elif [ "$answer" = "n" -o "$answer" = "N" ] ; then
		echo "don't sync."
		break
	fi
done

echo "---------------"
echo ""
echo "/etc/template/*"
rsync ${RSYNC_TEST} ${EXCLUDE} ../etc/template/* ${MIN_DIR}/etc/template/
echo ""
while : ; do
	echo -n "Are you sure ? [y/n] : "
	read answer
	if [ "$answer" = "y" -o "$answer" = "Y" ] ; then
		echo "sync"
		echo "";
		rsync ${RSYNC_EXEC} ${EXCLUDE} ../etc/template/* ${MIN_DIR}/etc/template/

		break
	elif [ "$answer" = "n" -o "$answer" = "N" ] ; then
		echo "don't sync."
		break
	fi
done

echo "---------------"
echo ""
echo "/lib/*"
rsync ${RSYNC_TEST} ${EXCLUDE} ../lib/* ${MIN_DIR}/lib/
echo ""
while : ; do
	echo -n "Are you sure ? [y/n] : "
	read answer
	if [ "$answer" = "y" -o "$answer" = "Y" ] ; then
		echo "sync"
		echo "";
		rsync ${RSYNC_EXEC} ${EXCLUDE} ../lib/* ${MIN_DIR}/lib/

		break
	elif [ "$answer" = "n" -o "$answer" = "N" ] ; then
		echo "don't sync."
		break
	fi
done

echo "---------------"
echo ""
echo "/view/cmn/*"
rsync ${RSYNC_TEST} ${EXCLUDE} ../view/cmn/* ${MIN_DIR}/view/cmn/
echo ""
while : ; do
	echo -n "Are you sure ? [y/n] : "
	read answer
	if [ "$answer" = "y" -o "$answer" = "Y" ] ; then
		echo "sync"
		echo "";
		rsync ${RSYNC_EXEC} ${EXCLUDE} ../view/cmn/* ${MIN_DIR}/min/view/cmn/

		break
	elif [ "$answer" = "n" -o "$answer" = "N" ] ; then
		echo "don't sync."
		break
	fi
done


echo ""

