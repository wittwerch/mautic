#!/usr/bin/env bash

# Update composer
sudo composer self-update

sudo ln -s /vagrant/55hubs.app /etc/nginx/sites-enabled/55hubs.conf
sudo service nginx restart