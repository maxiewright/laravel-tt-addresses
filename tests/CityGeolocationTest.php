<?php

use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\CitySeeder;
use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\DivisionSeeder;
use MaxieWright\TrinidadAndTobagoAddresses\Models\City;

beforeEach(function () {
    (new DivisionSeeder)->run();
    (new CitySeeder)->run();

    // Add coordinates to test cities
    City::where('name', 'Port-of-Spain')->update([
        'latitude' => 10.6596,
        'longitude' => -61.5086,
    ]);

    City::where('name', 'San Fernando')->update([
        'latitude' => 10.2833,
        'longitude' => -61.4667,
    ]);

    City::where('name', 'Scarborough')->update([
        'latitude' => 11.1833,
        'longitude' => -60.7333,
    ]);
});

it('has latitude and longitude attributes', function () {
    $city = City::where('name', 'Port-of-Spain')->first();

    expect($city->latitude)->toBe(10.6596)
        ->and($city->longitude)->toBe(-61.5086);
});

it('has coordinates accessor', function () {
    $city = City::where('name', 'Port-of-Spain')->first();

    expect($city->coordinates)->toBe([
        'latitude' => 10.6596,
        'longitude' => -61.5086,
    ]);
});

it('returns null coordinates when not set', function () {
    $city = City::where('name', 'Chaguanas')->first();
    $city->update(['latitude' => null, 'longitude' => null]);

    expect($city->fresh()->coordinates)->toBeNull();
});

it('can check if city has coordinates', function () {
    $withCoords = City::where('name', 'Port-of-Spain')->first();
    $withoutCoords = City::where('name', 'Chaguanas')->first();
    $withoutCoords->update(['latitude' => null, 'longitude' => null]);

    expect($withCoords->hasCoordinatesData())->toBeTrue()
        ->and($withoutCoords->fresh()->hasCoordinatesData())->toBeFalse();
});

it('can calculate distance between cities', function () {
    $pos = City::where('name', 'Port-of-Spain')->first();
    $sfo = City::where('name', 'San Fernando')->first();

    $distance = $pos->distanceTo($sfo);

    // Port of Spain to San Fernando is approximately 45-50km
    expect($distance)->toBeGreaterThan(40)
        ->and($distance)->toBeLessThan(55);
});

it('can calculate distance to coordinates', function () {
    $pos = City::where('name', 'Port-of-Spain')->first();

    $distance = $pos->distanceTo([
        'latitude' => 10.2833,
        'longitude' => -61.4667,
    ]);

    expect($distance)->toBeGreaterThan(40);
});

it('can filter cities with coordinates', function () {
    $citiesWithCoords = City::query()->hasCoordinates()->get();

    expect($citiesWithCoords->count())->toBeGreaterThan(0)
        ->and($citiesWithCoords->every(fn ($c) => $c->hasCoordinatesData()))->toBeTrue();
});

it('can find cities within radius', function () {
    // Find cities within 50km of Port of Spain
    $nearbyCities = City::query()
        ->withinRadius(10.6596, -61.5086, 50)
        ->get();

    expect($nearbyCities->count())->toBeGreaterThan(0);
});

it('can order cities by distance', function () {
    $cities = City::query()
        ->hasCoordinates()
        ->orderByDistanceFrom(10.6596, -61.5086)
        ->get();

    // First city should be Port of Spain (closest to itself)
    expect($cities->first()->name)->toBe('Port-of-Spain');
});

it('can find nearest city', function () {
    // Coordinates near San Fernando
    $nearest = City::findNearest(10.29, -61.47);

    expect($nearest->name)->toBe('San Fernando');
});

it('can generate google maps url', function () {
    $city = City::where('name', 'Port-of-Spain')->first();

    expect($city->getGoogleMapsUrl())
        ->toBe('https://www.google.com/maps?q=10.6596,-61.5086');
});

it('can generate openstreetmap url', function () {
    $city = City::where('name', 'Port-of-Spain')->first();

    expect($city->getOpenStreetMapUrl())
        ->toBe('https://www.openstreetmap.org/?mlat=10.6596&mlon=-61.5086&zoom=15');
});

it('returns null for map urls when coordinates missing', function () {
    $city = City::where('name', 'Chaguanas')->first();
    $city->update(['latitude' => null, 'longitude' => null]);

    expect($city->fresh()->getGoogleMapsUrl())->toBeNull()
        ->and($city->fresh()->getOpenStreetMapUrl())->toBeNull();
});
