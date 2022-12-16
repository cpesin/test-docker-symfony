## —— Misc 🛠️️ —————————————————————————————————————————————————————————————————
.DEFAULT_GOAL = help
.PHONY: # complete if needed

## —— Global 🛠️️ —————————————————————————————————————————————————————————————————
help: ## Commands list
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Executables 🛠️️ —————————————————————————————————————————————————————————————————
DOCKER_COMPOSE = docker-compose -f docker-compose.yml
DOCKER_EXEC = docker exec -ti
SERVER = test-server
REDIS = test-redis
SYMFONY = @$(DOCKER_EXEC) $(SERVER) /bin/bash -c

## —— Docker 🐳  ———————————————————————————————————————————————————————————————
build: ## Build dev docker images
	@$(DOCKER_COMPOSE) build

build_no_cache: ## Build dev docker images without cache
	@$(DOCKER_COMPOSE) build --no-cache

up: ## Run project containers
	@$(DOCKER_COMPOSE) up -d --remove-orphans

stop: ## Stop project containers
	@$(DOCKER_COMPOSE) stop

bash: up ## Run bash in server container
	@$(DOCKER_EXEC) $(SERVER) /bin/bash

redis: up ## Run bash in redis container
	@$(DOCKER_EXEC) $(REDIS) /bin/bash

## —— Symfony 🎶 ———————————————————————————————————————————————————————————————
composer_update: composer.jon ## Run composer update
	@$(DOCKER_EXEC) $(SERVER) /bin/bash -c 'composer update'

composer_install: composer.lock ## run composer install
	@$(DOCKER_EXEC) $(SERVER) /bin/bash -c 'composer install'

install: composer_install ## Alias of composer_install / composer_update

database_create: ## Create database define in .env file
	@$(SYMFONY) 'bin/console doctrine:database:create --if-not-exists --no-interaction'

schema_update: ## Create database schema define in app
	@$(SYMFONY) 'bin/console doctrine:schema:update --force --no-interaction'

load_fixtures: ## Load database's fixtures
	@$(SYMFONY) 'bin/console doctrine:fixtures:load'

## —— Tests ✅ ———————————————————————————————————————————————————————————————
phpunit: ## Run phpunit
	@$(DOCKER_EXEC) $(SERVER) /bin/bash -c 'bin/phpunit'

phpcs_fix: ## Run php-cs-fixer 
	@$(DOCKER_EXEC) $(SERVER) /bin/bash -c 'tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src --diff --config=.php-cs-fixer.dist.php'

phpcs: ## Run php-cs-fixer with dry run
	@$(DOCKER_EXEC) $(SERVER) /bin/bash -c 'tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src --dry-run --diff --config=.php-cs-fixer.dist.php'

phpstan: 
	@$(DOCKER_EXEC) $(SERVER) /bin/bash -c 'vendor/bin/phpstan analyse'
