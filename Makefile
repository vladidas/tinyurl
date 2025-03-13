.PHONY: help up down restart build shell test migrate fresh seed npm-install npm-dev npm-build composer-install composer-update dev prod install-vue watch

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
