#!/bin/sh
cd ../../../bin
./makeDbClassFile.php -d mindb -u mindb -s mysql
./makeTableClassFiles.php -d mindb
./makeModelClassFiles.php -d mindb
./makeCtlAndView.php -m BooksList
./makeCtlAndView.php -m BooksRecord
./makeCtlAndView.php -m SpotsList
./makeCtlAndView.php -m SpotsRecord

