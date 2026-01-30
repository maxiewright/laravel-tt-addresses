<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TrinidadAndTobagoAddressesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-tt-addresses')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations([
                'create_tt_divisions_table',
                'create_tt_cities_table',
                'add_coordinates_to_tt_cities_table', // For upgrades
            ])
            ->hasCommand(Commands\FetchCityCoordinatesCommand::class)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('maxiewright/laravel-tt-addresses')
                    ->endWith(function (InstallCommand $command) {
                        $command->info('');
                        $command->info('📍 Trinidad & Tobago Addresses installed!');
                        $command->info('');
                        $command->info('Run the seeders to populate data:');
                        $command->info('  php artisan db:seed --class="MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\DivisionSeeder"');
                        $command->info('  php artisan db:seed --class="MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\CitySeeder"');
                        $command->info('');
                    });
            });
    }

    public function registeringPackage(): void
    {
        // Bind the package's main class into the container for the facade to resolve.
        $this->app->singleton('tt-addresses', function () {
            return new TrinidadAndTobagoAddresses;
        });
    }
}
