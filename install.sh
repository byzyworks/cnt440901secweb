#!/bin/bash

apt-get update
apt-get install apache2 mysql-server libapache2-mod-php5 php5-mysql

ufw allow http
ufw allow https
ufw reload

mysql -u root -p -e "CREATE DATABASE cnt440901secweb; CREATE USER web-user@localhost; GRANT SELECT ON cnt440901secweb TO web-user@localhost;"
mysql -u root -p cnt440901secweb < cnt440901secweb.sql
