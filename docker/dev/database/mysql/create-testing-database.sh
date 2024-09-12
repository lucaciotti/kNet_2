#!/usr/bin/env bash

mariadb --user=root --password="$MYSQL_ROOT_PASSWORD" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS $MYSQL_DATABASE;
    GRANT ALL PRIVILEGES ON *.* TO '$MYSQL_USER'@'%';
EOSQL