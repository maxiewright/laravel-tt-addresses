<?php

declare(strict_types=1);

use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\CitySeeder;
use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\DivisionSeeder;
use MaxieWright\TrinidadAndTobagoAddresses\Models\City;
use MaxieWright\TrinidadAndTobagoAddresses\Models\Division;

it('can run division seeder successfully', function () {
    Division::query()->delete();

    expect(Division::count())->toBe(0);

    (new DivisionSeeder())->run();

    expect(Division::count())->toBe(15);
});

it('can run city seeder successfully', function () {
    (new DivisionSeeder())->run();
    City::query()->delete();

    expect(City::count())->toBe(0);

    (new CitySeeder())->run();

    expect(City::count())->toBeGreaterThan(500);
});

it('can run division seeder multiple times safely', function () {
    (new DivisionSeeder())->run();

    expect(Division::count())->toBe(15);

    // Run again - should not create duplicates
    (new DivisionSeeder())->run();

    expect(Division::count())->toBe(15);
});

it('can run city seeder multiple times safely', function () {
    (new DivisionSeeder())->run();
    (new CitySeeder())->run();

    $countBefore = City::count();

    // Run again - should not create duplicates
    (new CitySeeder())->run();

    expect(City::count())->toBe($countBefore);
});

it('ensures all cities belong to valid divisions', function () {
    (new DivisionSeeder())->run();
    (new CitySeeder())->run();

    $cities = City::all();

    foreach ($cities as $city) {
        expect($city->division)->not->toBeNull()
            ->and($city->division)->toBeInstanceOf(Division::class);
    }
});

it('ensures division abbreviations are unique', function () {
    (new DivisionSeeder())->run();

    $abbreviations = Division::pluck('abbreviation')->toArray();
    $uniqueAbbreviations = array_unique($abbreviations);

    expect(count($abbreviations))->toBe(count($uniqueAbbreviations));
});
