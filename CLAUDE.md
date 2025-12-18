# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel package that provides Trinidad and Tobago address management functionality, including administrative divisions (Regional Corporations, Boroughs, City Corporations, and Wards), cities/towns/villages, and address formatting utilities.

**Package Namespace**: `MaxieWright\TrinidadAndTobagoAddresses`

## Development Commands

### Testing
```bash
# Run all tests
composer test

# Run tests with coverage
composer test-coverage

# Run a specific test file
vendor/bin/pest tests/CityTest.php

# Run a specific test
vendor/bin/pest --filter "test_name"
```

### Code Quality
```bash
# Run PHPStan static analysis
composer analyse

# Fix code style issues automatically
composer format
```

### Package Installation & Setup
```bash
# Publish migrations
php artisan vendor:publish --tag="laravel-tt-addresses-migrations"

# Publish config
php artisan vendor:publish --tag="laravel-tt-addresses-config"

# Run migrations
php artisan migrate

# Seed data
php artisan db:seed --class="MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\DivisionSeeder"
php artisan db:seed --class="MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\CitySeeder"
```

## Architecture

### Core Models

**Division Model** (`src/Models/Division.php`): Represents administrative divisions in Trinidad and Tobago.
- Trinidad: 9 Regional Corporations, 3 Boroughs, 2 City Corporations
- Tobago: 1 Ward
- Key fields: `name`, `type` (enum), `abbreviation`, `island`
- Scopes: `trinidad()`, `tobago()`, `ofType()`, `search()`
- Relationships: `hasMany(City::class)`
- Uses configurable table name from `config('tt-addresses.tables.divisions')`

**City Model** (`src/Models/City.php`): Represents cities, towns, and villages.
- Key fields: `division_id`, `name`
- Scopes: `trinidad()`, `tobago()`, `inDivision()`, `search()`
- Relationships: `belongsTo(Division::class)`
- Accessors: `full_location`, `island`
- Uses configurable table name from `config('tt-addresses.tables.cities')`

**DivisionType Enum** (`src/Enums/DivisionType.php`): Backed enum for administrative division types.
- Cases: `RegionalCorporation`, `Borough`, `CityCorporation`, `Ward`
- Methods: `label()`, `island()`, `forIsland()`
- Includes Filament support methods: `getLabel()`, `getColor()`

### Trait for Address Management

**HasTrinidadAndTobagoAddress** (`src/Concerns/HasTrinidadAndTobagoAddress.php`): Add this trait to any model that needs address fields.
- Required columns: `division_id` (nullable FK), `city_id` (nullable FK)
- Optional columns: `address_line_1`, `address_line_2`
- Methods: `division()`, `city()`, `isInTrinidad()`, `isInTobago()`, `hasCompleteAddress()`
- Accessors: `formatted_address`, `formatted_address_multiline`, `island`, `country_code`

### Configuration

Config file (`config/tt-addresses.php`):
- Customizable table names (default: `tt_divisions`, `tt_cities`)
- Country code setting (default: `TT`)

### Data Seeding

The package includes seeders with real Trinidad and Tobago data:
- **DivisionSeeder**: Seeds all 15 administrative divisions with correct names, types, and abbreviations
- **CitySeeder**: Seeds cities/towns/villages linked to their respective divisions

Seeders use `updateOrCreate()` to be safely re-runnable.

## Testing Setup

Tests extend `Orchestra\Testbench\TestCase` via `TestCase` base class. The test setup:
- Automatically runs migrations in `getEnvironmentSetUp()`
- Configures factory namespace for model factories
- Uses in-memory SQLite database for testing

## Code Standards

- **PHPStan Level**: 5 (configured in `phpstan.neon.dist`)
- **Code Style**: Laravel Pint (run with `composer format`)
- **Architecture Tests**: Pest Arch tests in `tests/ArchTest.php`
- **CI/CD**: GitHub Actions workflows for tests, PHPStan, and code style

## Important Notes

- Table names are configurable - always use `getTable()` method or model relationships, never hardcode table names
- All division and city models support island-specific queries via scopes
- The package is designed to work with Laravel 11+ and PHP 8.4+
- Config key prefix is `tt-addresses` (note: hyphenated, not snake_case)
- When referencing models in seeders or commands, use the full namespace under `Database\Seeders` subdirectory
