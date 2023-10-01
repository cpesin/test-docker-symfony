A simple project to test Docker, Symfony and other stuff...
===========

![GitHub](https://img.shields.io/github/license/cpesin/test-docker-symfony)
![GitHub last commit](https://img.shields.io/github/last-commit/cpesin/test-docker-symfony)
![Publish Docker image](https://github.com/cpesin/test-docker-symfony/actions/workflows/build-docker-image.yml/badge.svg)

## Features

You can use this project to play with :
* Docker
* Symfony 6.3
* Mysql / PhpMyAdmin
* Sqlite / Adminer
* Redis
* Mailcatcher
* Traefik
* Bootstrap
* Webpack Encore

## Requirements

You need following sofwares to run this project : 
* Docker
* Makefile

## Installation

Clone the project :
``` Bash
git clone https://github.com/cpesin/test-docker-symfony.git
```

Run containers :
``` Bash
make up
```

Install the project :
``` Bash
make install
```

Use `make bash` to enter in main container (server).

Stop docker's containers with 
``` Bash
make stop
```

## Links

Website : https://test-docker-symfony.traefik.me/

Website's admin : https://test-docker-symfony.traefik.me/admin

PHPMyAdmin : http://localhost:8090

Mailcatcher : http://localhost:1080

Traefik : http://localhost:8081

Adminer : http://localhost:8080

With :
- System : SQLite 3
- Utilisateur : root
- Mot de passe : test
- Base de données : var/db/app_test.db

## Run tests

Run phpUnit's tests :
``` Bash
make phpunit
```

Run phpUnit's tests with code coverage :
``` Bash
make coverage
```

Code coverage is available at : `./coverage/index.html`

Run phpCS (dry-run) :
``` Bash
make phpcs
```

Run phpCS (fix automatically) :
``` Bash
make phpcs_fix
```

Run phpStan :
``` Bash
make phpstan
```
