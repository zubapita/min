#!/bin/sh
mysql -u root -p < mindb.sql
mysql -u mindb -D mindb < books.sql
mysql -u mindb -D mindb < spots.sql
mysql -u mindb -D mindb < location.sql

