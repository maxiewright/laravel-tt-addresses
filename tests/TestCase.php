<?php

namespace MaxieWright\TrinidadAndTobagoAddresses\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
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

        $migration = include __DIR__.'/../database/migrations/create_tt_divisions_table.php.stub';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_tt_cities_table.php.stub';
        $migration->up();
    }
}
