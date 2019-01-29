#!/usr/bin/env bash

source /app/vagrant/provision/common.sh

#== Import script args ==

timezone=$(echo "$1")

#== Provision script ==

info "Provision-script user: `whoami`"

export DEBIAN_FRONTEND=noninteractive

info "Configure timezone"
timedatectl set-timezone ${timezone} --no-ask-password

info "Add Oracle JDK repository"
add-apt-repository ppa:webupd8team/java -y

info "Add ElasticSearch sources"
wget -qO - https://artifacts.elastic.co/GPG-KEY-elasticsearch | apt-key add -
echo "deb https://artifacts.elastic.co/packages/5.x/apt stable main" | tee -a /etc/apt/sources.list.d/elastic-5.x.list

info "Update OS software"
apt-get update
apt-get upgrade -y

info "Install Oracle JDK"
debconf-set-selections <<< "oracle-java8-installer shared/accepted-oracle-license-v1-1 select true"
debconf-set-selections <<< "oracle-java8-installer shared/accepted-oracle-license-v1-1 seen true"
apt-get install -y oracle-java8-installer

info "Install ElasticSearch"
apt-get install -y elasticsearch
sed -i 's/-Xms2g/-Xms64m/' /etc/elasticsearch/jvm.options
sed -i 's/-Xmx2g/-Xmx64m/' /etc/elasticsearch/jvm.options
service elasticsearch restart

info "Install Redis"
apt-get install -y redis-server

info "Install Supervisor"
apt-get install -y supervisor

info "Enabling supervisor processes"
ln -s /app/vagrant/supervisor/queue.conf /etc/supervisor/conf.d/queue.conf
echo "Done!"
