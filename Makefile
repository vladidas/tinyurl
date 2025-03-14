.PHONY: help up down restart build shell test migrate fresh seed npm-install npm-dev npm-build composer-install composer-update dev prod install-vue watch test test-unit test-feature test-filter test-coverage test-parallel test-stop psalm psalm-dry psalm-fix watch-poll dev-server dev install-deps serve clean

# Default target
help:
	@echo "Available commands:"
	@echo "  make up               - Start the application containers"
	@echo "  make down             - Stop the application containers"
	@echo "  make restart          - Restart the application containers"
	@echo "  make build            - Rebuild the application containers"
	@echo "  make shell            - Access the application container shell"
	@echo "  make test             - Run tests"
	@echo "  make migrate          - Run database migrations"
	@echo "  make fresh            - Drop all tables and re-run migrations"
	@echo "  make seed             - Run database seeders"
	@echo "  make npm-install      - Install NPM dependencies"
	@echo "  make npm-dev          - Run NPM dev script"
	@echo "  make npm-build        - Run NPM build script"
	@echo "  make composer-install - Install Composer dependencies"
	@echo "  make composer-update  - Update Composer dependencies"
	@echo "  make init             - Initial setup (copy .env, install dependencies, generate key)"
	@echo "  make dev              - Run NPM dev script"
	@echo "  make prod             - Run NPM build script"
	@echo "  make install-vue      - Install Vue dependencies"
	@echo "  make watch            - Run Vite in watch mode for development"
	@echo "  make test-unit        - Run only unit tests"
	@echo "  make test-feature     - Run only feature tests"
	@echo "  make test-filter      - Run specific test file or method"
	@echo "  make test-coverage    - Run tests with coverage report"
	@echo "  make test-parallel    - Run tests in parallel"
	@echo "  make test-stop        - Run tests and stop on first failure"
	@echo "  make psalm            - Run Psalm static analysis"
	@echo "  make psalm-dry        - Run Psalm without making changes"
	@echo "  make psalm-fix        - Run Psalm with automatic fixes"
	@echo "  make watch-poll       - Run Vite dev server with polling"
	@echo "  make dev-server       - Run Vite dev server"
	@echo "  make dev              - Run NPM dev script"
	@echo "  make prod             - Run NPM build script"
	@echo "  make install-deps     - Install all dependencies"
	@echo "  make serve            - Serve Laravel application"
	@echo "  make clean            - Clean installation"

# Container commands
up:
	./vendor/bin/sail up -d

down:
	./vendor/bin/sail down

restart:
	./vendor/bin/sail restart

build:
	./vendor/bin/sail build --no-cache

shell:
	./vendor/bin/sail shell

# Laravel commands
test:
	./vendor/bin/sail test

migrate:
	./vendor/bin/sail artisan migrate

fresh:
	./vendor/bin/sail artisan migrate:fresh

seed:
	./vendor/bin/sail artisan db:seed

# NPM commands
npm-install:
	./vendor/bin/sail npm install

npm-dev:
	./vendor/bin/sail npm run dev

npm-build:
	./vendor/bin/sail npm run build

dev:
	./vendor/bin/sail npm run dev

prod:
	./vendor/bin/sail npm run build

install-vue:
	./vendor/bin/sail npm install @vitejs/plugin-vue @inertiajs/vue3 @inertiajs/progress vue@^3.4.0 vue-router@4

watch:
	./vendor/bin/sail npm run watch

# Composer commands
composer-install:
	./vendor/bin/sail composer install

composer-update:
	./vendor/bin/sail composer update

# Initial setup
init:
	cp .env.example .env
	./vendor/bin/sail composer install
	./vendor/bin/sail artisan key:generate
	./vendor/bin/sail artisan storage:link

# Run all tests
test:
	php artisan test

# Run only unit tests
test-unit:
	php artisan test --testsuite=Unit

# Run only feature tests
test-feature:
	php artisan test --testsuite=Feature

# Run specific test file or method (usage: make test-filter filter=ProductTest)
test-filter:
	php artisan test --filter=$(filter)

# Run tests with coverage report
test-coverage:
	php artisan test --coverage --min=80

# Run tests in parallel
test-parallel:
	php artisan test --parallel

# Run tests and stop on first failure
test-stop:
	php artisan test --stop-on-failure

# Psalm commands
psalm:
	./vendor/bin/sail php ./vendor/bin/psalm

psalm-dry:
	./vendor/bin/sail php ./vendor/bin/psalm --dry-run

psalm-fix:
	./vendor/bin/sail php ./vendor/bin/psalm --alter --issues=all

# Watch with polling (better for some environments)
watch-poll:
	npm run dev

# Run Vite dev server
dev-server:
	npm run dev -- --host

# Development setup with watching
dev: install-deps
	$(MAKE) -j2 serve watch

# Install all dependencies
install-deps: composer-install npm-install

# Serve Laravel application
serve:
	php artisan serve

# Clean and reinstall
fresh: clean install-deps

# Clean installation
clean:
	rm -rf node_modules
	rm -rf vendor
	rm -rf public/build
