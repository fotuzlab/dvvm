#!/bin/bash

# Install packages.
apt-get install jq -y
apt-get install screen -y
npm install -g webdriver-manager

# Set permissions.
chown -R vagrant:vagrant /home/vagrant/.npm-global