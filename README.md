# Laravel Trinidad & Tobago Addresses

[![Latest Version on Packagist](https://img.shields.io/packagist/v/maxiewright/trinidad-and-tobago-addresses.svg?style=flat-square)](https://packagist.org/packages/maxiewright/trinidad-and-tobago-addresses)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/maxiewright/trinidad-and-tobago-addresses/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/maxiewright/trinidad-and-tobago-addresses/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/maxiewright/trinidad-and-tobago-addresses.svg?style=flat-square)](https://packagist.org/packages/maxiewright/trinidad-and-tobago-addresses)

A Laravel package providing Trinidad and Tobago administrative divisions and cities/towns/villages for address management. Includes all 15 administrative divisions and 500+ communities.

## Features

- 🏛️ **15 Administrative Divisions** - All Regional Corporations, Boroughs, City Corporations, and Tobago
- 🏘️ **500+ Cities/Towns/Villages** - Comprehensive coverage of Trinidad and Tobago communities
- 🔗 **Eloquent Relationships** - Ready-to-use models with proper relationships
- 🎨 **Filament Support** - Enums implement `HasLabel` for seamless Filament integration
- ⚙️ **Configurable** - Customise table names to avoid conflicts
- 🧪 **Tested** - Full test coverage with Pest

## Requirements

- PHP 8.4+
- Laravel 10.x, 11.x, or 12.x

## Installation

Install the package via Composer:

```bash
composer require maxiewright/trinidad-and-tobago-addresses
```

### Quick Install (Recommended)

Run the install command which will guide you through the setup:

```bash
php artisan trinidad-and-tobago-addresses:install
```

This will:
1. Publish the configuration file
2. Publish the migrations
3. Optionally run the migrations

### Manual Installation

If you prefer to install manually:

```bash
# Publish the config file
php artisan vendor:publish --tag="trinidad-and-tobago-addresses-config"

# Publish the migrations
php artisan vendor:publish --tag="trinidad-and-tobago-addresses-migrations"

# Run migrations
php artisan migrate
```

### Seed the Data

After running migrations, seed the divisions and cities:

```bash
php artisan db:seed --class="MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\DivisionSeeder"
php artisan db:seed --class="MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\CitySeeder"
```

Or add them to your `DatabaseSeeder.php`:

```php
use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\DivisionSeeder;
use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\CitySeeder;

public function run(): void
{
    $this->call([
        DivisionSeeder::class,
        CitySeeder::class,
        // ... your other seeders
    ]);
}
```

## Configuration

The configuration file is located at `config/trinidad-and-tobago-addresses.php`:

```php
return [
    // Customise table names if they conflict with existing tables
    'tables' => [
        'divisions' => 'tt_divisions',
        'cities' => 'tt_cities',
    ],

    // ISO country code
    'country_code' => 'TT',
];
```

## Usage

### Basic Queries

```php
use MaxieWright\TrinidadAndTobagoAddresses\Models\Division;
use MaxieWright\TrinidadAndTobagoAddresses\Models\City;
use MaxieWright\TrinidadAndTobagoAddresses\Enums\DivisionType;

// Get all divisions
$divisions = Division::all();

// Get only Trinidad divisions
$trinidadDivisions = Division::trinidad()->get();

// Get only Tobago
$tobago = Division::tobago()->first();

// Get divisions by type
$boroughs = Division::ofType(DivisionType::Borough)->get();
$regionalCorporations = Division::ofType(DivisionType::RegionalCorporation)->get();

// Get all cities in a division
$chaguanasCities = Division::where('abbreviation', 'CHA')
    ->first()
    ->cities;

// Find a city
$portOfSpain = City::where('name', 'Port-of-Spain')->first();
$portOfSpain->division->name; // "Port of Spain"
$portOfSpain->island; // "Trinidad"

// Get full location string
$city = City::where('name', 'Scarborough')->first();
$city->full_location; // "Scarborough, Tobago"
```

### Division Types

The package includes a `DivisionType` enum with the following values:

| Value | Label | Island |
|-------|-------|--------|
| `RegionalCorporation` | Regional Corporation | Trinidad |
| `Borough` | Borough | Trinidad |
| `CityCorporation` | City Corporation | Trinidad |
| `Ward` | Ward | Tobago |

```php
use MaxieWright\TrinidadAndTobagoAddresses\Enums\DivisionType;

$type = DivisionType::RegionalCorporation;
$type->label();  // "Regional Corporation"
$type->island(); // "Trinidad"

// Filament support
$type->getLabel(); // "Regional Corporation"
```

### Adding Addresses to Your Models

Use the `HasTrinidadAndTobagoAddress` trait on any model that needs Trinidad and Tobago address fields:

```php
use Illuminate\Database\Eloquent\Model;
use MaxieWright\TrinidadAndTobagoAddresses\Concerns\HasTrinidadAndTobagoAddress;

class Customer extends Model
{
    use HasTrinidadAndTobagoAddress;

    protected $fillable = [
        'name',
        'address_line_1',
        'address_line_2',
        'division_id',
        'city_id',
    ];
}
```

Create a migration for your model:

```php
Schema::create('customers', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('address_line_1')->nullable();
    $table->string('address_line_2')->nullable();
    $table->foreignId('division_id')
          ->nullable()
          ->constrained(config('trinidad-and-tobago-addresses.tables.divisions'))
          ->nullOnDelete();
    $table->foreignId('city_id')
          ->nullable()
          ->constrained(config('trinidad-and-tobago-addresses.tables.cities'))
          ->nullOnDelete();
    $table->timestamps();
});
```

Then use it:

```php
$customer = Customer::create([
    'name' => 'John Doe',
    'address_line_1' => '123 Main Street',
    'division_id' => 11, // Chaguanas
    'city_id' => 88,     // Chaguanas city
]);

$customer->division->name;       // "Chaguanas"
$customer->city->name;           // "Chaguanas"
$customer->formatted_address;    // "123 Main Street, Chaguanas, Chaguanas"
$customer->island;               // "Trinidad"
```

### Filament Integration

The models and enums are designed to work seamlessly with [FilamentPHP](https://filamentphp.com/).

#### Select Fields

```php
use Filament\Forms\Components\Select;
use MaxieWright\TrinidadAndTobagoAddresses\Models\Division;
use MaxieWright\TrinidadAndTobagoAddresses\Models\City;

// Division select
Select::make('division_id')
    ->label('Division')
    ->options(Division::pluck('name', 'id'))
    ->searchable()
    ->preload()
    ->live(),

// City select (filtered by division)
Select::make('city_id')
    ->label('City/Town/Village')
    ->options(function (callable $get) {
        $divisionId = $get('division_id');
        if (!$divisionId) {
            return City::pluck('name', 'id');
        }
        return City::where('division_id', $divisionId)
            ->pluck('name', 'id');
    })
    ->searchable()
    ->preload(),
```

#### Using the Enum in Filters

```php
use Filament\Tables\Filters\SelectFilter;
use MaxieWright\TrinidadAndTobagoAddresses\Enums\DivisionType;

SelectFilter::make('type')
    ->options(DivisionType::class),
```

### Administrative Divisions Reference

| ID | Name | Type | Abbreviation |
|----|------|------|--------------|
| 1 | Couva/Tabaquite/Talparo | Regional Corporation | CTT |
| 2 | Diego Martin | Regional Corporation | DMN |
| 3 | Mayaro/Rio Claro | Regional Corporation | MRC |
| 4 | Penal/Debe | Regional Corporation | PED |
| 5 | Princes Town | Regional Corporation | PRT |
| 6 | Sangre Grande | Regional Corporation | SGE |
| 7 | San Juan/Laventille | Regional Corporation | SJL |
| 8 | Siparia | Regional Corporation | SIP |
| 9 | Tunapuna/Piarco | Regional Corporation | TUP |
| 10 | Arima | Borough | ARI |
| 11 | Chaguanas | Borough | CHA |
| 12 | Point Fortin | Borough | PTF |
| 13 | Port of Spain | City Corporation | POS |
| 14 | San Fernando | City Corporation | SFO |
| 15 | Tobago | Ward | TOB |

## API Reference

### Division Model

#### Attributes
- `name` - Division name
- `type` - DivisionType enum
- `abbreviation` - Short code (e.g., "POS", "CHA")
- `island` - "Trinidad" or "Tobago"

#### Relationships
- `cities()` - HasMany relationship to City

#### Scopes
- `scopeTrinidad()` - Filter to Trinidad divisions only
- `scopeTobago()` - Filter to Tobago only
- `scopeOfType(DivisionType $type)` - Filter by division type
- `scopeSearch(string $search)` - Search by name or abbreviation

#### Methods
- `isTobago(): bool` - Check if division is in Tobago
- `isTrinidad(): bool` - Check if division is in Trinidad

#### Accessors
- `full_name` - Returns "Division Name (Type Label)"

### City Model

#### Attributes
- `name` - City/town/village name
- `division_id` - Foreign key to Division

#### Relationships
- `division()` - BelongsTo relationship to Division

#### Scopes
- `scopeTrinidad()` - Filter to Trinidad cities only
- `scopeTobago()` - Filter to Tobago cities only
- `scopeInDivision(int|Division $division)` - Filter by division
- `scopeSearch(string $search)` - Search by name

#### Methods
- `isTobago(): bool` - Check if city is in Tobago
- `isTrinidad(): bool` - Check if city is in Trinidad

#### Accessors
- `full_location` - Returns "City Name, Division Name"
- `island` - Returns "Trinidad" or "Tobago" via division

### HasTrinidadAndTobagoAddress Trait

#### Relationships
- `division()` - BelongsTo relationship to Division
- `city()` - BelongsTo relationship to City

#### Methods
- `isInTobago(): bool` - Check if address is in Tobago
- `isInTrinidad(): bool` - Check if address is in Trinidad
- `hasCompleteAddress(): bool` - Check if address has all required fields

#### Accessors
- `formatted_address` - Returns formatted single-line address string
- `formatted_address_multiline` - Returns formatted multi-line address string
- `island` - Returns "Trinidad" or "Tobago"
- `country_code` - Returns "TT"

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Contributions are welcome! Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Adding Missing Cities

If you notice a city/town/village is missing, please submit a pull request with:
1. The city name (correctly spelled)
2. The correct `division_id` (see Administrative Divisions Reference)

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Max Wright](https://github.com/maxiewright)
- [Trinidad and Tobago Regiment](https://ttdf.mil.tt)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

---

Made with 🇹🇹 for Trinidad and Tobago developers.
