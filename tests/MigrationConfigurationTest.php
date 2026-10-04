<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\CitySeeder;
use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\DivisionSeeder;
use MaxieWright\TrinidadAndTobagoAddresses\Models\City;
use MaxieWright\TrinidadAndTobagoAddresses\Models\Division;
use MaxieWright\TrinidadAndTobagoAddresses\TrinidadAndTobagoAddressesServiceProvider;

it('publishes only migrations needed for a fresh installation', function () {
    $sources = array_keys(ServiceProvider::pathsToPublish(
        TrinidadAndTobagoAddressesServiceProvider::class,
        'tt-addresses-migrations'
    ));

    expect(array_map('basename', $sources))->toBe([
        'create_tt_divisions_table.php.stub',
        'create_tt_cities_table.php.stub',
    ]);
});

it('uses configured table names in migrations, foreign keys, and seeders', function () {
    config([
        'tt-addresses.tables.divisions' => 'custom_divisions',
        'tt-addresses.tables.cities' => 'custom_cities',
        'tt-addresses.tables.addresses' => 'custom_addresses',
    ]);

    $divisionsMigration = include __DIR__.'/../database/migrations/create_tt_divisions_table.php.stub';
    $citiesMigration = include __DIR__.'/../database/migrations/create_tt_cities_table.php.stub';
    $addressesMigration = include __DIR__.'/../database/migrations/create_tt_addresses_table.php.stub';

    $divisionsMigration->up();
    $citiesMigration->up();
    $addressesMigration->up();

    expect(Schema::hasTable('custom_divisions'))->toBeTrue()
        ->and(Schema::hasTable('custom_cities'))->toBeTrue()
        ->and(Schema::hasTable('custom_addresses'))->toBeTrue()
        ->and(collect(Schema::getForeignKeys('custom_cities'))->pluck('foreign_table', 'columns.0')->get('division_id'))
        ->toBe('custom_divisions')
        ->and(collect(Schema::getForeignKeys('custom_addresses'))->pluck('foreign_table', 'columns.0')->get('division_id'))
        ->toBe('custom_divisions')
        ->and(collect(Schema::getForeignKeys('custom_addresses'))->pluck('foreign_table', 'columns.0')->get('city_id'))
        ->toBe('custom_cities');

    (new DivisionSeeder)->run();
    (new CitySeeder)->run();

    expect(Division::count())->toBe(15)
        ->and(City::count())->toBeGreaterThan(500);

    $addressesMigration->down();
    $citiesMigration->down();
    $divisionsMigration->down();

    expect(Schema::hasTable('custom_divisions'))->toBeFalse()
        ->and(Schema::hasTable('custom_cities'))->toBeFalse()
        ->and(Schema::hasTable('custom_addresses'))->toBeFalse();
});

it('uses configured table names in optional coordinate upgrade migrations', function () {
    config([
        'tt-addresses.tables.divisions' => 'legacy_divisions',
        'tt-addresses.tables.cities' => 'legacy_cities',
    ]);

    Schema::create('legacy_divisions', function (Blueprint $table) {
        $table->id();
        $table->string('island');
    });
    Schema::create('legacy_cities', function (Blueprint $table) {
        $table->id();
        $table->string('name');
    });

    $divisionsMigration = include __DIR__.'/../database/migrations/add_coordinates_to_tt_divisions_table.php.stub';
    $citiesMigration = include __DIR__.'/../database/migrations/add_coordinates_to_tt_cities_table.php.stub';

    $divisionsMigration->up();
    $citiesMigration->up();

    expect(Schema::hasColumns('legacy_divisions', ['latitude', 'longitude']))->toBeTrue()
        ->and(Schema::hasColumns('legacy_cities', ['latitude', 'longitude']))->toBeTrue();

    $citiesMigration->down();
    $divisionsMigration->down();

    expect(Schema::hasColumn('legacy_divisions', 'latitude'))->toBeFalse()
        ->and(Schema::hasColumn('legacy_cities', 'latitude'))->toBeFalse();

    Schema::dropIfExists('legacy_cities');
    Schema::dropIfExists('legacy_divisions');
});
