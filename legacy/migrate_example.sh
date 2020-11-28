#!/bin/sh

echo "Migrating legacy database"

echo "Importing old database"
mysql -uroot serienguide < taces2_serienguide.sql

echo "Adding prefixes"
mysql -uroot serienguide < prefix.sql

echo "artisan migrate"
php ../artisan migrate

echo "Migrating tables"
for file in migrate/*.sql
do
    echo $file
    mysql -uroot serienguide < $file
done
