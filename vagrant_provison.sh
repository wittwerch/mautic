#!/usr/bin/env bash

# Symlink code into wwwroot
sudo ln -s /vagrant/ /var/www/public

# Symlink robo.phar to PATH
chmod +x bin/robo.phar
sudo cp bin/robo.phar /usr/bin/

# Update composer
sudo composer self-update

