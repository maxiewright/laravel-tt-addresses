<?php

use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\CitySeeder;
use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\DivisionSeeder;
use MaxieWright\TrinidadAndTobagoAddresses\Models\City;
use MaxieWright\TrinidadAndTobagoAddresses\Models\Division;

beforeEach(function () {
    new DivisionSeeder()->run();
    new CitySeeder()->run();
});

it('can create a city', function () {
    $division = Division::where('abbreviation', 'CHA')->first();

    $city = City::create([
        'name' => 'Test City',
        'division_id' => $division->id,
    ]);

    expect($city)->toBeInstanceOf(City::class)
        ->and($city->name)->toBe('Test City')
        ->and($city->division_id)->toBe($division->id);
});

it('seeds over 500 cities', function () {
    expect(City::count())->toBeGreaterThan(500);
});

it('belongs to a division', function () {
    $city = City::where('name', 'Chaguanas')->first();

    expect($city->division)->toBeInstanceOf(Division::class)
        ->and($city->division->name)->toBe('Chaguanas');
});

it('can filter trinidad cities', function () {
    $trinidadCities = City::query()->trinidad()->get();
    $totalCount = City::count();

    expect($trinidadCities)->toHaveCount($trinidadCities->count())
        ->and($trinidadCities->count())->toBeLessThan($totalCount)
        ->and($trinidadCities->count())->toBeGreaterThan(0);
});

it('can filter tobago cities', function () {
    $tobagoCities = City::query()->tobago()->get();

    expect($tobagoCities->count())->toBeGreaterThan(0);
});

it('can filter cities by division', function () {
    $division = Division::where('abbreviation', 'CHA')->first();
    $cities = City::query()->inDivision($division)->get();

    expect($cities)->not->toBeEmpty()
        ->and($cities->every(fn ($city) => $city->division_id === $division->id))->toBeTrue();
});

it('can filter cities by division id', function () {
    $division = Division::where('abbreviation', 'CHA')->first();
    $cities = City::query()->inDivision($division->id)->get();

    expect($cities)->not->toBeEmpty()
        ->and($cities->every(fn ($city) => $city->division_id === $division->id))->toBeTrue();
});

it('can search cities by name', function () {
    $results = City::query()->search('Port')->get();

    expect($results)->not->toBeEmpty()
        ->and($results->pluck('name')->toArray())->toContain('Port-of-Spain');
});

it('has full location attribute', function () {
    $city = City::where('name', 'Scarborough')->first();

    expect($city->full_location)->toBe('Scarborough, Tobago');
});

it('has island attribute via division', function () {
    $trinidadCity = City::where('name', 'Chaguanas')->first();
    $tobagoCity = City::where('name', 'Scarborough')->first();

    expect($trinidadCity->island)->toBe('Trinidad')
        ->and($tobagoCity->island)->toBe('Tobago');
});

it('can check if city is in tobago', function () {
    $trinidadCity = City::where('name', 'Chaguanas')->first();
    $tobagoCity = City::where('name', 'Scarborough')->first();

    expect($trinidadCity->isTobago())->toBeFalse()
        ->and($tobagoCity->isTobago())->toBeTrue();
});

it('can check if city is in trinidad', function () {
    $trinidadCity = City::where('name', 'Chaguanas')->first();
    $tobagoCity = City::where('name', 'Scarborough')->first();

    expect($trinidadCity->isTrinidad())->toBeTrue()
        ->and($tobagoCity->isTrinidad())->toBeFalse();
});

it('has port of spain with correct spelling', function () {
    $city = City::where('name', 'Port-of-Spain')->first();

    expect($city)->not->toBeNull();
});

it('has d abadie with correct apostrophe', function () {
    $city = City::where('name', "D'Abadie")->first();

    expect($city)->not->toBeNull();
});

it('has san jose de oruna with correct accents', function () {
    $city = City::where('name', 'San José de Oruña')->first();

    expect($city)->not->toBeNull();
});

it('allows duplicate city names in different divisions', function () {
    // Belmont exists in both Port of Spain (13) and Tobago (15)
    $belmontCities = City::where('name', 'Belmont')->get();

    expect($belmontCities)->toHaveCount(2)
        ->and($belmontCities->pluck('division_id')->unique()->count())->toBe(2);
});
