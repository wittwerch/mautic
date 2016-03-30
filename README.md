# 55hubs

## How to get started

Requirements:
* Vagrant installed

Checkout the repository

    git clone https://github.com/diginlab/55hubs-mautic.git
    
Launch vagrant box and ssh into

    vagrant up
    vagrant ssh
    
Bootstrap local environment

    cd /vagrant
    robo bootstrap

Note: if you don't have Github API token yet, create one here: https://github.com/settings/tokens

Import a database dump or initalize it with an default 55hubs installation

    robo init
