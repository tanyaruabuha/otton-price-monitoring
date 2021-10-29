#!/bin/bash

#sudo mkdir -p /var/www/otton-price-monitoring; mv ~/otton-price-monitoring /var/www/

sudo apt update

sudo apt install -y nginx

sudo ufw allow 'Nginx HTTP'

sudo apt install -y php-fpm php-mysql php-mbstring php-xml php-bcmath composer unzip php-curl

sudo apt install -y mysql-server

sudo cp otton /etc/nginx/sites-available/otton

sudo rm /etc/nginx/sites-enabled/default

sudo ln -s /etc/nginx/sites-available/otton /etc/nginx/sites-enabled/otton

sudo service nginx restart

#More info:
# https://www.digitalocean.com/community/tutorials/how-to-install-linux-nginx-mysql-php-lemp-stack-ubuntu-18-04

#Project
# git clone https://AntHouse@bitbucket.org/AntHouse/otton-price-monitoring.git

# sudo chgrp -R www-data storage bootstrap/cache && sudo chmod -R ug+rwx storage bootstrap/cache

# sudo mysql
# CREATE DATABASE otton;
# GRANT ALL ON otton.* TO 'otton'@'localhost' IDENTIFIED BY 'otton' WITH GRANT OPTION;

# crontab -e
# * * * * * php /var/www/otton-price-monitoring/artisan schedule:run >> /dev/null 2>&1
