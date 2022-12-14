A simple project to test Docker, Symfony and other stuff...
===========

## Features

You can use this project to play with :
* Docker
* Symfony 6
* Mysql / PhpMyAdmin
* Sqlite / Adminer
* Redis
* Mailcatcher
* Bootstrap

## Requirements

You need following sofwares to run this project : 
* Docker
* Makefile

## Installation

Clone the project :
`git clone https://github.com/cpesin/test-docker-symfony.git`

Run containers :
`make up`

Install Symfony bundles :
`make install`

Create the database : 
`make database_create`

Create database's tables :
`make schema_update`

Load fixtures :
`make load_fixtures`

Use `make bash` to enter in main container (server).

Stop docker's containers with `make stop`.

## Run tests

Run phpUnit's tests :
`make phpunit`

Run phpCs (dry-run) :
`make phpcs`

Run phpCs (fix automatically) :
`make phpcs_fix`