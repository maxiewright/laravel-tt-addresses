<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use MaxieWright\TrinidadAndTobagoAddresses\Enums\DivisionType;
use MaxieWright\TrinidadAndTobagoAddresses\Enums\SearchRadius;
use MaxieWright\TrinidadAndTobagoAddresses\Models\City;
use MaxieWright\TrinidadAndTobagoAddresses\Models\Division;

beforeEach(function () {
    // Ensure a clean cache for each test
    Cache::flush();

    // Create test division
    $division = Division::create([
        'name' => 'Test Regional Corporation',
        'type' => DivisionType::RegionalCorporation,
        'abbreviation' => 'TRC',
        'island' => 'Trinidad',
        'latitude' => 10.5,
        'longitude' => -61.5,
    ]);

    // Create test cities
    City::create([
        'division_id' => $division->id,
        'name' => 'Port of Spain',
        'latitude' => 10.6596,
        'longitude' => -61.5089,
    ]);

    City::create([
        'division_id' => $division->id,
        'name' => 'San Fernando',
        'latitude' => 10.2759,
        'longitude' => -61.4616,
    ]);

    City::create([
        'division_id' => $division->id,
        'name' => 'Chaguanas',
        'latitude' => 10.5186,
        'longitude' => -61.4107,
    ]);
});

it('can autocomplete city names', function () {
    $results = City::query()->autocomplete('Port', 5)->get();

    expect($results)->toHaveCount(1);
    expect($results->first()->name)->toBe('Port of Spain');
});

it('can get popular cities', function () {
    config(['tt-addresses.popular_cities' => ['Port of Spain', 'San Fernando', 'Chaguanas']]);

    $results = City::query()->popular()->get();

    expect($results->count())->toBeGreaterThan(0);
    expect($results->first()->name)->toBe('Port of Spain');
});

it('can find cities within search radius', function () {
    $portOfSpainLat = 10.6596;
    $portOfSpainLng = -61.5089;

    $results = City::query()->withinSearchRadius($portOfSpainLat, $portOfSpainLng, SearchRadius::REGIONAL)->get();

    expect($results->count())->toBeGreaterThan(0);
});

it('can convert city to search result', function () {
    $city = City::with('division')->first();
    $result = $city->toSearchResult();

    expect($result)->toBeArray()
        ->toHaveKeys(['id', 'name', 'full_location', 'coordinates', 'division_type']);
});

it('can convert city to autocomplete option', function () {
    $city = City::with('division')->first();
    $option = $city->toAutocompleteOption();

    expect($option)->toBeArray()
        ->toHaveKeys(['value', 'label', 'description', 'coordinates']);
});

it('validates the SearchRadius enum', function () {
    $walking = SearchRadius::WALKING;
    $driving = SearchRadius::DRIVING;

    expect($walking->kilometers())->toBe(2);
    expect($driving->kilometers())->toBe(10);
    expect($walking->label())->toBe('2 km (Walking Distance)');
    expect($driving->label())->toBe('10 km (Driving Distance)');
});

it('can get suggested nearby cities', function () {
    $portOfSpainLat = 10.6596;
    $portOfSpainLng = -61.5089;

    $suggestions = City::getSuggestedNearbyCities($portOfSpainLat, $portOfSpainLng, 5);

    expect($suggestions->count())->toBeLessThanOrEqual(5)
        ->and($suggestions->count())->toBeGreaterThan(0);
});

it('can cache popular cities', function () {
    config(['tt-addresses.popular_cities' => ['Port of Spain', 'San Fernando']]);

    // First call - should cache
    $first = City::getPopularCached(60);

    // Second call - should use cache
    $second = City::getPopularCached(60);

    expect($second->count())->toBe($first->count());
});
