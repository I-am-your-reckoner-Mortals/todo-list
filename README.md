# todo-list

## Description

Based on [PHP][PHP-LINK] and [Symfony][SYMFONY-LINK] framework

## How to install

### Requirements

#### Linux

* Install [docker][DOCKER-LINK] (19.03.12+) / [docker-compose][DOCKERCOMPOSE-LINK] (v1.26.2+).
* To use docker-container without switching to superuser mode, you need to add a group for the current system user: `sudo usermod -aG docker $USER`. Otherwise, you will have to run all the docker commands through the `sudo` command
* Log out and log back in so that your group membership is re-evaluated.
  If testing on a virtual machine, it may be necessary to restart the virtual machine for changes to take effect.
  On a desktop Linux environment such as X Windows, log out of your session completely and then log back in.
  On Linux, you can also run the following command to activate the changes to groups: `newgrp docker`.

### Setting Up local environment

1. Copy `.env.example` file to `.env` and set your environment variables.
2. Run
   >sh run.sh