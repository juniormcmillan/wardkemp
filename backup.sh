#!/bin/bash
#

echo "BACKING UP FOR STARTING";


MYVAR=`date '+%d' |cut -f1 -d' '`
MYTIME=`date +"%k" |cut -f2 -d' '`

cd /home/ppisolicitors/backup

echo "BACKING UP FOR $MYVAR " >> backup.log

mysqlcheck -u7apO3nBbB2a -pPIKkip91sD312 -o ppisolicitors >> backup.log

mysqldump --create-options --lock-tables=false --flush-logs --add-drop-table ppisolicitors -u7apO3nBbB2a -pPIKkip91sD312 > /home/ppisolicitors/backup/$MYVAR-$MYTIME.ppisolicitorsDB.sql

gzip -f /home/ppisolicitors/backup/$MYVAR-$MYTIME.ppisolicitorsDB.sql >> backup.log

echo "Finished Backup for $MYVAR" >> backup.log