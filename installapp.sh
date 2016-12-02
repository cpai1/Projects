#!/bin/bash

echo "hello" > home/ubuntu/installapp.text

sudo apt-get update -y
sudo apt-get install -y python-setuptools python-pip
sudo pip install awscli
sudo apt-get install -y apache2 php-xml php php-mysql curl php-curl zip unzip git php7.0-xml php-gd libapache2-mod-php
sudo systemctl enable apache2
sudo systemctl start apache2


export COMPOSER_HOME=/root && /usr/bin/composer.phar self-update 1.0.0-alpha11

sudo curl -sS https://getcomposer.org/installer | php
sudo php composer.phar require aws/aws-sdk-php
echo "composer created"
cd /
sudo cp -r vendor/ /var/www/html

echo "vendor file moved successfully"

sudo git clone git@github.com:illinoistech-itm/cpai1.git

sudo cp cpai1/switchonarex.png /var/www/html
sudo cp cpai1/s3test.php /var/www/html
sudo cp cpai1/dbtest.php /var/www/html



