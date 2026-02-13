# Changelog

## v0.2.0 — Search Enhancements for High-Performance Applications - 2026-02-13

### Added

- **Autocomplete Search**: Fast prefix-matching with `City::autocomplete()` scope
- **Popular Cities**: Configurable list of major cities with `City::popular()` scope and caching
- **Search Radius Enum**: Predefined distance ranges (`WALKING`, `DRIVING`, `REGIONAL`, `ISLAND_WIDE`)
- **Search Radius Filtering**: `City::withinSearchRadius()` scope with SearchRadius integration
- **API Response Helpers**: `toSearchResult()` and `toAutocompleteOption()` methods
- **Nearby Location Suggestions**: `getSuggestedNearbyCities()` for proximity-based recommendations
- **Performance Optimization Command**: `tt-addresses:optimize-search` for cache management
- **Enhanced Configuration**: Search settings, performance options, and popular cities list

### Improved

- **Search Performance**: Optimized queries with proper indexing suggestions
- **Caching Strategy**: Built-in cache management for popular searches
- **Documentation**: Comprehensive search feature documentation with examples

### Technical Details

- New `SearchRadius` enum with Filament `HasLabel` support
- Enhanced `City` model with 6 new scopes and helper methods
- Performance-focused database query optimization
- Comprehensive test coverage for new features
- Cache-aware popular cities functionality

**Perfect for:** Location-based applications, mapping systems, proximity search, and any system requiring fast Trinidad & Tobago location search.

## Initial release: Trinidad & Tobago divisions, 500+ cities, models, seeders, install command - 2025-12-17

### v0.1.0 — Initial release

Released: 2025-12-17

#### Summary

First public release of a Laravel package that ships Trinidad & Tobago administrative divisions and 500+ cities/towns/villages with ready-to-use Eloquent models, relationships, seeders, and a guided install command. This release focuses on giving you reliable, queryable data and a minimal API to integrate addresses quickly.

#### Highlights (what’s new)

- 15 administrative divisions (Regional Corporations, Boroughs, City Corporations, Tobago)
- 500+ cities/towns/villages linked to their divisions
- Eloquent models (`Division`, `City`) and relationships
- Enum for division type with Filament-friendly labels
- Configurable table names and ISO `country_code`
- Install artisan command to publish config and migrations
- Seeders for divisions and cities

#### Who is this for?

- You need authoritative, structured Trinidad & Tobago address data in Laravel.
- You want a drop-in dataset with Eloquent models and relationships instead of bespoke CSV imports.

#### Compatibility

- PHP: 8.4+
- Laravel: 11.x–12.x (see `composer.json` for exact constraints)

#### Breaking changes / deprecations

- None (first public release). Note: Pre-1.0.0 SemVer rules apply; breaking changes may occur in minor `0.x` updates.

#### Known issues

- None reported yet. Please open an issue with steps to reproduce if you find a problem.

#### Install / upgrade

- New install (short version):
  ```bash
  composer require maxiewright/trinidad-and-tobago-addresses
  php artisan trinidad-and-tobago-addresses:install
  
  ```
- For details (manual publish, seeding, examples), see the README.

#### Changelog

- See `CHANGELOG.md` for the full list of added items in 0.1.0.

#### Credits

- Author: @maxiewright
- Thanks to all future contributors — GitHub will list them below automatically.

#### Feedback

If you use this in production or notice missing places, please open an issue or pull request with sources so we can improve coverage.

Links: Packagist, repo, and docs are available on the project README.
