# —— Misc 🛠️️ —————————————————————————————————————————————————————————————————
.DEFAULT_GOAL = help
.PHONY: coverage # complete if needed

# —— Executables 🛠️️ —————————————————————————————————————————————————————————————————
DOCKER_COMPOSE = docker-compose -f docker-compose.yml
DOCKER_EXEC = docker exec -ti
SERVER = test-server
REDIS = test-redis
SYMFONY = @$(DOCKER_EXEC) $(SERVER) /bin/bash -c

## —— Global 🛠️️ —————————————————————————————————————————————————————————————————
help: ## Commands list
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

install: composer_install check_permissions npm_install database_create migrations_run npm_build fixtures_load ## Install the project

tests: phpcs phpstan phpunit ## Run all tests

migrations_reset: database_drop migrations_delete database_create schema_update migrations_dump ## Reset migrations files

## —— Docker 🐳  ———————————————————————————————————————————————————————————————
build: ## Build dev docker images
	@$(DOCKER_COMPOSE) build

build_no_cache: ## Build dev docker images without cache
	@$(DOCKER_COMPOSE) build --no-cache

up: ## Run project containers
	@$(DOCKER_COMPOSE) up -d --remove-orphans

stop: ## Stop project containers
	@$(DOCKER_COMPOSE) stop

restart: stop up ## Restart project containers

bash: up ## Run bash in server container
	@$(DOCKER_EXEC) $(SERVER) /bin/bash

redis: up ## Run bash in redis container
	@$(DOCKER_EXEC) $(REDIS) /bin/bash

## —— Symfony 🎶 ———————————————————————————————————————————————————————————————
composer_install: composer.lock ## run composer install
	@$(SYMFONY) 'composer install'

composer_update: composer.json ## Run composer update
	@$(SYMFONY) 'composer update'

database_create: ## Create database define in .env file
	@$(SYMFONY) 'bin/console doctrine:database:create --if-not-exists --no-interaction'
	@$(SYMFONY) 'bin/console doctrine:database:create --env=test --if-not-exists --no-interaction'

database_drop: ## Drop database define in .env file
	@$(SYMFONY) 'bin/console doctrine:database:drop --force --no-interaction'
	@$(SYMFONY) 'bin/console doctrine:database:drop --env=test --force --no-interaction'

migrations_delete: ## Delete migrations files
	@$(SYMFONY) 'rm -rf migrations/*'

migrations_dump: ## Create à new migration
	@$(SYMFONY) 'bin/console doctrine:migration:dump-schema'

migrations_run: ## Run Doctrine migrations
	@$(SYMFONY) 'bin/console doctrine:migration:migrate --no-interaction'
	@$(SYMFONY) 'bin/console doctrine:migration:migrate --env=test --no-interaction'

schema_update: ## Create database schema define in app
	@$(SYMFONY) 'bin/console doctrine:schema:update --force --no-interaction'
	@$(SYMFONY) 'bin/console doctrine:schema:update --env=test --force --no-interaction'

fixtures_load: ## Load database's fixtures
	@$(SYMFONY) 'bin/console doctrine:fixtures:load'

doctrine_clear_all_cache: doctrine_clear_cache doctrine_clear_sl_cache ## Clear all doctrine caches

doctrine_clear_cache: ## Clear doctrine caches
	@$(SYMFONY) 'bin/console doctrine:cache:clear-metadata'
	@$(SYMFONY) 'bin/console doctrine:cache:clear-query'
	@$(SYMFONY) 'bin/console doctrine:cache:clear-result'

doctrine_clear_sl_cache: ## Clear doctrine caches second level
	@$(SYMFONY) 'bin/console doctrine:cache:clear-collection-region'
	@$(SYMFONY) 'bin/console doctrine:cache:clear-entity-region'
	@$(SYMFONY) 'bin/console doctrine:cache:clear-query-region'

npm_install: ## Install npm
	@$(SYMFONY) 'npm install'

npm_build: ## Build assets
	@$(SYMFONY) 'npm run build'

npm_watch: 
	@$(SYMFONY) 'npm run watch'
	
check_permissions: ## Check files and forders permissions (dev only)
	@$(SYMFONY) 'chmod 0777 var/* -R'
	@$(SYMFONY) 'chmod +x bin/* -R'

## —— Tests ✅ ———————————————————————————————————————————————————————————————
phpunit: phpunit_unit phpunit_integration phpunit_functionnal ## Run phpunit

phpunit_unit: ## Run phpunit unit tests
	@$(SYMFONY) 'bin/phpunit --testsuite Unit --testdox'

phpunit_integration: ## Run phpunit integration tests
	@$(SYMFONY) 'bin/phpunit --testsuite Integration --testdox'

phpunit_functionnal: ## Run phpunit functionnal test
	@$(SYMFONY) 'bin/phpunit --testsuite Functionnal --testdox'

coverage: ## Run phpunit with code coverage
	@$(SYMFONY) 'bin/phpunit --coverage-html coverage'

phpcs_fix: ## Run php-cs-fixer 
	@$(SYMFONY) 'vendor/bin/php-cs-fixer fix src --diff --config=.php-cs-fixer.dist.php'

phpcs: ## Run php-cs-fixer with dry run
	@$(SYMFONY) 'vendor/bin/php-cs-fixer fix src --dry-run --diff --config=.php-cs-fixer.dist.php'

phpstan: ## RUN phpstan
	@$(SYMFONY) 'vendor/bin/phpstan analyse'
