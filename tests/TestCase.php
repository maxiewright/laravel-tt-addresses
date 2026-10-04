<?php

namespace MaxieWright\TrinidadAndTobagoAddresses\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;
use MaxieWright\TrinidadAndTobagoAddresses\TrinidadAndTobagoAddressesServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'MaxieWright\\TrinidadAndTobagoAddresses\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            TrinidadAndTobagoAddressesServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        if (getenv('TT_ADDRESSES_TEST_DB') === 'pgsql') {
            $connection = config('database.connections.pgsql');
            $connection['database'] = 'tt_addresses_package_test';
            config()->set('database.connections.testing', $connection);

            foreach ([
                'custom_addresses', 'custom_cities', 'custom_divisions',
                'legacy_cities', 'legacy_divisions',
                'test_addresses', 'test_has_addresses', 'test_addressables',
                'tt_addresses', 'tt_cities', 'tt_divisions',
            ] as $table) {
                Schema::dropIfExists($table);
            }
        }

        $migration = include __DIR__.'/../database/migrations/create_tt_divisions_table.php.stub';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_tt_cities_table.php.stub';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_tt_addresses_table.php.stub';
        $migration->up();
    }
}
