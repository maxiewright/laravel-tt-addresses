<?php

namespace MaxieWright\TrinidadAndTobagoAddresses;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MaxieWright\TrinidadAndTobagoAddresses\Commands\TrinidadAndTobagoAddressesCommand;

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
            ->hasMigration('create_laravel_tt_addresses_table')
            ->hasCommand(TrinidadAndTobagoAddressesCommand::class);
    }
}
