# dvvm Drupal 8 website
Vagrant/Drupalvm enabled Drupal 8 Framework.

This repository is configured to be run on an Ubuntu 16.04 virtual machine powered by Vagrant.

REQUIREMENTS
============
1. Vagrant
2. Access to github repository
3. Patience

SETUP
=====
1. Clone this repo on your machine
2. Copy `docroot/sites/default/dv.settings.local.php` to `docroot/sites/default/settings.local.php`
3. Get `dv_credentials.json` from a peer and place it in default directory. The path of the file should be `docroot/sites/default/dv_credentials.json`
4. From any directory, run `vagrant up`. First time set up may take 15-20 minutes but subsequent provisioning will be lot faster.
5. Once done, go to `http://local.dvvm.dvdev.net` and start building :)

USEFUL COMMANDS
===============
C1. Login inside VM - `vagrant ssh`
C2. Clear drupal cache from commandline - C1 + `drush cr`
C3. Add linux packages to VM for testing (like upgrading PHP) - C1 + `apt install <package name>`
C4. Reset/Provision VM - `vagrant reload --provision`
C5. Destroy VM - `vagrant destroy`
C6. Suspend VM temporarily(sometimes to save resources on physical machine) - `vagrant halt`

IMPORTING DATABASE
==================
From Matrix
-----------
**Method A** (Import database only):
1. `vagrant ssh`
2. `dv.db.sandbox <sandbox name>` e.g. `dv.db.sandbox pr123`
Note:- If no `<sandbox name>` is provided, database will be imported from `develop` sandbox.

**Method B** (Import database and provision VM):
1. `SBOX=<sandbox name> vagrant reload --provision`

From Acquia
-----------
To be done.

CONNECTING TO REMOTE DATABASE
=============================
1. Open `docroot/sites/default/settings.local.php` with text editor of your choice.
2. Set `$testing` and `$remote_testing` to TRUE
3. Set `$sandbox` to name of the sandbox e.g. develop or pr261
4. Run `drush cr` inside VM to clear cache if there is any error. If error persists, check your VPN connection.

TESTING ON LOCAL
================
If you are testing features on local, you may want to disable dev tools, debug etc and enable Drupal caching and memcached to replicate production to and extent. To do so, change `$testing` in `docroot/sites/default/settings.local.php` to TRUE.
Note:- Keep `$remote_testing` to FALSE.

TROUBLESHOOTING
===============
1. Ensure *settings.local.php* and *dv_credentials.json* exist in `sites/default` directory.

CUSTOMIZE
=========
1. Vagrant machine configuration can be modified at `.drupalvm/config.yml`. For all configurable settings, refer `.drupalvm/default.config.yml` (but do not modify this file).
2. Vhosts can be changed in `.drupalvm/config.yml` by changing the `project_name` on line 1. Only use lowercase names without any special characters and spaces. This will spin up "http://local.projectname.dvdev.net".

HOW TO USE THIS REPO
====================
1. Clone this repo. Run `git clone git@github.com:fotuzlab/dvvm.git`
2. Delete .git directory. Run `rm -rf .git`
3. Replace files under docroot with files of your project. composer.json defaults to drupal 8 setup. You may need to modify based on drupal version of your project.
4. Run `composer update && composer install`
5. Run `git init` to initialise git.
6. Run `git remote add origin <ssh url of github repo>`
7. Run `git add . && git commit -am "Initial commit" && git push origin master`
8. Run `git checkout -b develop && git push origin develop`. Develop serves as the active development branch.
9. Get the vm up and running. Run `vagrant up`.
