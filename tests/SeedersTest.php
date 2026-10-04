<?php

declare(strict_types=1);

use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\CitySeeder;
use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\DivisionSeeder;
use MaxieWright\TrinidadAndTobagoAddresses\Models\City;
use MaxieWright\TrinidadAndTobagoAddresses\Models\Division;

it('can run division seeder successfully', function () {
    Division::query()->delete();

    expect(Division::count())->toBe(0);

    (new DivisionSeeder)->run();

    expect(Division::count())->toBe(15);
});

it('can run city seeder successfully', function () {
    (new DivisionSeeder)->run();
    City::query()->delete();

    expect(City::count())->toBe(0);

    (new CitySeeder)->run();

    expect(City::count())->toBeGreaterThan(500);
});

it('can run division seeder multiple times safely', function () {
    (new DivisionSeeder)->run();

    expect(Division::count())->toBe(15);

    // Run again - should not create duplicates
    (new DivisionSeeder)->run();

    expect(Division::count())->toBe(15);
});

it('can run city seeder multiple times safely', function () {
    (new DivisionSeeder)->run();
    (new CitySeeder)->run();

    $countBefore = City::count();

    // Run again - should not create duplicates
    (new CitySeeder)->run();

    expect(City::count())->toBe($countBefore);
});

it('seeds every city in its division after division ids have changed', function () {
    (new DivisionSeeder)->run();
    (new CitySeeder)->run();
    $expectedCount = City::count();

    City::query()->delete();
    Division::query()->delete();
    (new DivisionSeeder)->run();

    expect(Division::min('id'))->toBeGreaterThan(15);

    (new CitySeeder)->run();

    expect(City::count())->toBe($expectedCount);

    $sourceCities = (new class extends CitySeeder
    {
        public function cities(): array
        {
            return $this->getCities();
        }
    })->cities();
    $expectedLocations = array_map(fn (array $city) => $city['division'].':'.$city['name'], $sourceCities);
    $actualLocations = City::with('division')->get()
        ->map(fn (City $city) => $city->division->abbreviation.':'.$city->name)
        ->all();
    sort($expectedLocations);
    sort($actualLocations);

    expect($actualLocations)->toBe($expectedLocations);

    foreach ([
        'Chaguaramas' => 'DMN',
        'Cumuto' => 'SGE',
        'Port-of-Spain' => 'POS',
        'Scarborough' => 'TOB',
    ] as $cityName => $abbreviation) {
        expect(City::where('name', $cityName)->firstOrFail()->division->abbreviation)
            ->toBe($abbreviation);
    }
});

it('updates coordinates without duplicating cities when seeded again', function () {
    (new DivisionSeeder)->run();
    (new CitySeeder)->run();
    $expectedCount = City::count();
    $chaguaramas = City::where('name', 'Chaguaramas')->firstOrFail();
    $chaguaramas->update(['latitude' => 0, 'longitude' => 0]);

    (new CitySeeder)->run();

    expect(City::count())->toBe($expectedCount)
        ->and($chaguaramas->fresh()->latitude)->toBe(10.6833)
        ->and($chaguaramas->fresh()->longitude)->toBe(-61.6333);
});

it('fails clearly when a required division is missing', function () {
    (new DivisionSeeder)->run();
    Division::where('abbreviation', 'POS')->delete();

    expect(fn () => (new CitySeeder)->run())
        ->toThrow(RuntimeException::class, 'Run DivisionSeeder first');

    expect(City::count())->toBe(0);
});

it('keeps getCities overridable with division abbreviations', function () {
    (new DivisionSeeder)->run();

    $seeder = new class extends CitySeeder
    {
        protected function getCities(): array
        {
            return [[
                'name' => 'Custom Community',
                'division' => 'TOB',
                'latitude' => 11.2,
                'longitude' => -60.7,
            ]];
        }
    };

    $seeder->run();

    expect(City::where('name', 'Custom Community')->firstOrFail()->division->abbreviation)
        ->toBe('TOB');
});

it('rejects the legacy numeric override shape instead of using a database id', function () {
    (new DivisionSeeder)->run();

    $seeder = new class extends CitySeeder
    {
        protected function getCities(): array
        {
            return [[
                'name' => 'Legacy Community',
                'division_id' => 15,
                'latitude' => 11.2,
                'longitude' => -60.7,
            ]];
        }
    };

    expect(fn () => $seeder->run())
        ->toThrow(RuntimeException::class, 'division abbreviation');

    expect(City::count())->toBe(0);
});

it('ensures all cities belong to valid divisions', function () {
    (new DivisionSeeder)->run();
    (new CitySeeder)->run();

    $cities = City::all();

    foreach ($cities as $city) {
        expect($city->division)->not->toBeNull()
            ->and($city->division)->toBeInstanceOf(Division::class);
    }
});

it('ensures division abbreviations are unique', function () {
    (new DivisionSeeder)->run();

    $abbreviations = Division::pluck('abbreviation')->toArray();
    $uniqueAbbreviations = array_unique($abbreviations);

    expect(count($abbreviations))->toBe(count($uniqueAbbreviations));
});
