#!/bin/bash

echo "hello" > home/ubuntu/initprocess.text

sudo apt-get update -y
sudo apt-get install -y python-setuptools python-pip
sudo pip install awscli
sudo apt-get install -y apache2 php-xml php php-mysql mysql-client-5.7 curl php-curl zip unzip git php7.0-xml libapache2
-mod-php php-gd

sudo systemctl enable apache2
sudo systemctl start apache2


export COMPOSER_HOME=/root && /usr/bin/composer.phar self-update 1.0.0-alpha11

sudo curl -sS https://getcomposer.org/installer | php
sudo php composer.phar require aws/aws-sdk-php
echo "composer created"



echo "vendor file moved successfully"

sudo git clone git@github.com:illinoistech-itm/cpai1.git