# -*- mode: ruby -*-
# vi: set ft=ruby :

project_root = "#{__dir__}"

vagrantfile = File.expand_path("#{project_root}/.drupalvm/Vagrantfile", __FILE__)
load vagrantfile

# Run custom scripts.
Vagrant.configure("2") do |config|
  config.vm.provision "shell", path: "scripts/dv.sh"
end

# Run custom scripts.
Vagrant.configure("2") do |config|
  config.vm.provision "shell", path: "scripts/aliases.sh", privileged: false
end

