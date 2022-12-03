.PHONY = help build up stop bash composer_update composer_install install database_create schema_update load_fixtures phpunit 
.DEFAULT_GOAL = help


DOCKER_COMPOSE = docker-compose -f docker-compose.yml
DOCKER_EXEC = docker exec -ti
SERVER = test-server


## —— Docker 🐳  ———————————————————————————————————————————————————————————————
build: ## Build dev docker images
	@$(DOCKER_COMPOSE) build --no-cache

up: ## Run project containers
	@$(DOCKER_COMPOSE) up -d --remove-orphans

stop: ## Stop project containers
	@$(DOCKER_COMPOSE) stop

bash: up ## Run bash in server container
	@$(DOCKER_EXEC) $(SERVER) /bin/bash

## —— Symfony 🎶 ———————————————————————————————————————————————————————————————
composer_update: composer.jon
	@$(DOCKER_EXEC) $(SERVER) /bin/bash -c 'composer update'

composer_install: composer.lock
	@$(DOCKER_EXEC) $(SERVER) /bin/bash -c 'composer install'

install: composer_install

database_create:
	@$(DOCKER_EXEC) $(SERVER) /bin/bash -c 'bin/console doctrine:database:create'

schema_update:
	@$(DOCKER_EXEC) $(SERVER) /bin/bash -c 'bin/console doctrine:schema:update -f'

load_fixtures:
	@$(DOCKER_EXEC) $(SERVER) /bin/bash -c 'bin/console doctrine:fixtures:load'

phpunit:
	@$(DOCKER_EXEC) $(SERVER) /bin/bash -c 'bin/phpunit'

phpcs:
	@$(DOCKER_EXEC) $(SERVER) /bin/bash -c 'tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src --diff --config=.php-cs-fixer.dist.php'

phpcs_dry_run:
	@$(DOCKER_EXEC) $(SERVER) /bin/bash -c 'tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src --dry-run --diff --config=.php-cs-fixer.dist.php'

## —— Others 🛠️️ —————————————————————————————————————————————————————————————————
help: ## Commands list
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

