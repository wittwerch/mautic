#!/usr/bin/env bash

# Symlink code into wwwroot
sudo ln -s /vagrant/ /var/www/public

# Install robo
curl -O http://robo.li/robo.phar
chmod +x robo.phar && sudo mv robo.phar /usr/bin/robo

# Update composer
sudo composer self-update

