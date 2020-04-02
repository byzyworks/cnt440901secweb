#!/bin/bash

# Get the necessary packages, namely Oracle Apache2, MySQL Server, and PHP
apt-get update
apt-get install apache2 mysql-server libapache2-mod-php5 php5-mysql

# Load the given database into MySQL for use by the web server
mysql -u root -p -e "CREATE DATABASE cnt440901secweb;"
mysql -u root -p cnt440901secweb < cnt440901secweb.sql

# Copy all the necessary files given here, overriding anything already there
cp -rf var/* /var

# Set the permissions for the web root to ensure anyone can access the website
chmod 777 -R /var/www

# Get rid of the default web page separately since index.php is now used over index.html
rm /var/www/html/index.html

# Restart the web server to apply the changes
service apache2 restart