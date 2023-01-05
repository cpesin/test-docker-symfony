A simple project to test Docker, Symfony and other stuff...
===========

![GitHub](https://img.shields.io/github/license/cpesin/test-docker-symfony)
![GitHub last commit](https://img.shields.io/github/last-commit/cpesin/test-docker-symfony)

## Features

You can use this project to play with :
* Docker
* Symfony 6
* Mysql / PhpMyAdmin
* Sqlite / Adminer
* Redis
* Mailcatcher
* Traefik
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

## Links

Website :
`https://test-docker-symfony.traefik.me/`

PHPMyAdmin : 
`http://localhost:8090`

Mailcatcher : 
`http://localhost:1080`

Traefik : 
`http://localhost:8081`

Adminer : 
`http://localhost:8080`

With :
- System : SQLite 3
- Utilisateur : root
- Mot de passe : test
- Base de données : var/db/app_test.db

## Run tests

Run phpUnit's tests :
`make phpunit`

Run phpUnit's tests with code coverage :
`make coverage`

Code coverage is available at : `./coverage/index.html`

Run phpCs (dry-run) :
`make phpcs`

Run phpCs (fix automatically) :
`make phpcs_fix`

Run phpStan :
`make phpstan`