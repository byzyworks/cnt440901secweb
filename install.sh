#!/bin/bash

# Get the necessary packages, namely Oracle Apache2, MySQL Server, and PHP
apt-get update
apt-get install apache2 mysql-server libapache2-mod-php5 php5-mysql
apt-get install php5-mysqlnd

# Open to both HTTP and HTTPS traffic. Both must be enabled, even if HTTP just redirects to HTTPS
ufw enable
ufw allow http
ufw allow https
ufw reload

# Load the given database into MySQL for use by the web server
mysql -u root -p -e "CREATE DATABASE cnt440901secweb; CREATE USER 'web-user'@'localhost'; GRANT SELECT, INSERT, UPDATE ON cnt440901secweb.* TO 'web-user'@'localhost';"
mysql -u root -p cnt440901secweb < cnt440901secweb.sql

# Copy all the necessary files given here, overriding anything already there
cp -rf etc/* /etc
cp -rf var/* /var

# Set the permissions for SSL files
chmod 600 /etc/apache2/svr-key.key
chmod 644 /etc/apache2/svr-crt.crt

# Set the permissions for the server pepper
groupadd www-special
usermod -a -G www-special www-data
chown root:www-special /etc/apache2/.phrase
chmod 640 /etc/apache2/.phrase

# Set the permissions for the web root to ensure anyone can access the website
chmod 755 -R /var/www

# Create a symbolic link to the HTTPS site in sites-enabled for it to become available
ln -s /etc/apache2/sites-available/000-default-ssl.conf /etc/apache2/sites-enabled/000-default-ssl.conf

# Get rid of the default web page separately since index.php is now used over index.html according to .htaccess
rm /var/www/html/index.html

# Enable the site to use SSL and the redirect functionalities inside .htaccess
a2enmod ssl
a2enmod rewrite

# Restart the web server to apply the changes
#service apache2 restart

# Restart the server
reboot