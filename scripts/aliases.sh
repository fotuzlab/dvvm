#!/bin/bash

echo "alias dv.hello=\"sh /var/www/dvvm/scripts/helloworld.sh\"" >> ~/.bashrc
echo "alias dv.behat.start='echo \"Attempting to run webdriver-manager on port 4445. You can track the logs at /tmp/behat\"; screen -dmS behatd bash -c \"webdriver-manager update; webdriver-manager --seleniumPort 4445 start > /tmp/behat\"'" >> ~/.bashrc
echo "alias dv.behat.stop='echo \"Stopping webdriver-manager...saving resources...\"; screen -X -S \"behatd\" quit'" >> ~/.bashrc

echo "Aliases set"
