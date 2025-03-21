# Product Management System

A scalable Laravel-based product management system built with Domain-Driven Design principles.

## Features

- Product CRUD operations with categories
- Rating system (0-100)
- Recently viewed products tracking with Redis
- Related products functionality
- Efficient caching system
- Handles large-scale data (100M+ products)
- API-first design
- Domain-Driven Design architecture

## Tech Stack

- PHP 8.2
- Laravel 10
- MySQL
- Redis
- Docker (Laravel Sail)
- PHPUnit for testing
- Psalm for static analysis

## Requirements

- Docker
- Docker Compose
- Make

## Available Commands

### Container Management
- `make up` - Start the application containers
- `make down` - Stop the application containers
- `make restart` - Restart the application containers
- `make build` - Rebuild the application containers
- `make shell` - Access the application container shell

### Database Commands
- `make migrate` - Run database migrations
- `make fresh` - Drop all tables and re-run migrations
- `make seed` - Run database seeders

### Development Commands
- `make npm-install` - Install NPM dependencies
- `make npm-dev` - Run NPM dev script
- `make npm-build` - Run NPM build script
- `make composer-install` - Install Composer dependencies
- `make composer-update` - Update Composer dependencies
- `make dev` - Run NPM dev script
- `make prod` - Run NPM build script
- `make watch` - Run Vite in watch mode for development

### Testing Commands
- `make test` - Run all tests
- `make test-unit` - Run only unit tests
- `make test-feature` - Run only feature tests
- `make test-filter filter=TestName` - Run specific test file or method
- `make test-coverage` - Run tests with coverage report
- `make test-parallel` - Run tests in parallel
- `make test-stop` - Run tests and stop on first failure

### Static Analysis
- `make psalm` - Run Psalm static analysis
- `make psalm-dry` - Run Psalm without making changes
- `make psalm-fix` - Run Psalm with automatic fixes

## Project Structure
```
app/
├── Domain/
│ └── Product/
│ ├── Models/ # Domain models
│ ├── Services/ # Business logic services
│ ├── DTOs/ # Data Transfer Objects
│ ├── Repositories/ # Data access layer
│ ├── Events/ # Domain events
│ └── Validators/ # Domain validation
├── Http/
│ └── Controllers/ # API Controllers
└── Infrastructure/
└── Database/ # Database related code
```
## API Endpoints

### Products
- `GET /api/v1/products` - List products (paginated)
- `POST /api/v1/products` - Create new product
- `PUT /api/v1/products/{id}` - Update product
- `DELETE /api/v1/products/{id}` - Delete product
- `GET /api/v1/products/{id}` - Show product details
