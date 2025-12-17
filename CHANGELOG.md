# Changelog

## [0.1.0] - 2025-12-17
### Added
- 15 administrative divisions (Regional Corporations, Boroughs, City Corporations, Tobago)
- 500+ cities/towns/villages with Eloquent relationships
- `Division` and `City` models; division type enum with Filament labels
- Config (`trinidad-and-tobago-addresses.php`) with table names and `country_code`
- Install artisan command to publish config and migrations
- `DivisionSeeder` and `CitySeeder`
- Pest tests; support for PHP 8.4+ and Laravel 10–12
