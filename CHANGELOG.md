# Changelog

All notable changes to this project will be documented in this file.
This project adheres to Keep a Changelog and follows Semantic Versioning.

## [Unreleased]
### Added
- Bind facade accessor for package facade to improve app container resolution.

### Changed
- Package naming and documentation updated to `maxiewright/laravel-tt-addresses` (README badges, Packagist links, and installation instructions).
- Default config file/key renamed to `tt-addresses` (from `trinidad-and-tobago-addresses`) across code and docs; legacy key continues to be read for backward compatibility.
- Artisan install command in README updated to `php artisan tt-addresses:install`.
- Vendor publish tags in README updated to `laravel-tt-addresses-*`.

### Fixed
- Minor styling/formatting fixes in docs.

## [0.1.0] - 2025-12-17
### Added
- 15 administrative divisions (Regional Corporations, Boroughs, City Corporations, Tobago)
- 500+ cities/towns/villages with Eloquent relationships
- `Division` and `City` models; division type enum with Filament labels
- Config (`tt-addresses.php`) with table names and `country_code`
- Install artisan command to publish config and migrations
- `DivisionSeeder` and `CitySeeder`
- Pest tests; support for PHP 8.4+ and Laravel 10–12
