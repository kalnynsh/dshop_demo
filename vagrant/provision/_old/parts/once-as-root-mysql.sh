#!/usr/bin/env bash

source /app/vagrant/provision/common.sh

#== Import script args ==

timezone=$(echo "$1")

#== Provision script ==

info "Provision-script user: `whoami`"

export DEBIAN_FRONTEND=noninteractive

info "Configure timezone"
timedatectl set-timezone ${timezone} --no-ask-password

info "Update OS software"
apt update
apt upgrade -y

info "Prepare root password for MySQL"
debconf-set-selections <<< "mysql-community-server mysql-community-server/root-pass password \"''\""
debconf-set-selections <<< "mysql-community-server mysql-community-server/re-root-pass password \"''\""
echo "Done!"

info "Install Mysql"
apt install -y mysql-server

info "Configure MySQL"
sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf
mysql -uroot <<< "CREATE USER 'root'@'%' IDENTIFIED BY ''"
mysql -uroot <<< "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%'"
mysql -uroot <<< "DROP USER 'root'@'localhost'"
mysql -uroot <<< "FLUSH PRIVILEGES"
echo "Done!"

info "Initailize databases for MySQL"
mysql -uroot <<< "CREATE DATABASE shop"
mysql -uroot <<< "CREATE DATABASE shop_test"
echo "Done!"
