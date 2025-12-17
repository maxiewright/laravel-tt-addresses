<?php

use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\DivisionSeeder;
use MaxieWright\TrinidadAndTobagoAddresses\Enums\DivisionType;
use MaxieWright\TrinidadAndTobagoAddresses\Models\Division;

beforeEach(function () {
    new DivisionSeeder()->run();
});

it('can create a division', function () {
    $division = Division::create([
        'name' => 'Test Division',
        'type' => DivisionType::RegionalCorporation,
        'abbreviation' => 'TST',
        'island' => 'Trinidad',
    ]);

    expect($division)->toBeInstanceOf(Division::class)
        ->and($division->name)->toBe('Test Division')
        ->and($division->type)->toBe(DivisionType::RegionalCorporation)
        ->and($division->abbreviation)->toBe('TST')
        ->and($division->island)->toBe('Trinidad');
});

it('seeds 15 divisions', function () {
    expect(Division::count())->toBe(15);
});

it('has 9 regional corporations', function () {
    $divisions = Division::query()->ofType(DivisionType::RegionalCorporation)->get();

    expect($divisions)->toHaveCount(9);
});

it('has 3 boroughs', function () {
    $divisions = Division::query()->ofType(DivisionType::Borough)->get();

    expect($divisions)->toHaveCount(3);
});

it('has 2 city corporations', function () {
    $divisions = Division::query()->ofType(DivisionType::CityCorporation)->get();

    expect($divisions)->toHaveCount(2);
});

it('has 1 ward for tobago', function () {
    $divisions = Division::query()->ofType(DivisionType::Ward)->get();

    expect($divisions)->toHaveCount(1);
});

it('can filter trinidad divisions', function () {
    $divisions = Division::query()->trinidad()->get();

    expect($divisions)->toHaveCount(14);
});

it('can filter tobago divisions', function () {
    $divisions = Division::query()->tobago()->get();

    expect($divisions)->toHaveCount(1);
});

it('can check if division is in tobago', function () {
    $tobago = Division::where('abbreviation', 'TOB')->first();
    $chaguanas = Division::where('abbreviation', 'CHA')->first();

    expect($tobago->isTobago())->toBeTrue()
        ->and($chaguanas->isTobago())->toBeFalse();
});

it('can check if division is in trinidad', function () {
    $tobago = Division::where('abbreviation', 'TOB')->first();
    $chaguanas = Division::where('abbreviation', 'CHA')->first();

    expect($tobago->isTrinidad())->toBeFalse()
        ->and($chaguanas->isTrinidad())->toBeTrue();
});

it('can search by name', function () {
    $results = Division::query()->search('Port')->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->name)->toBe('Port of Spain');
});

it('can search by name for Point Fortin', function () {
    $results = Division::query()->search('Point')->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->name)->toBe('Point Fortin');
});

it('can search by abbreviation', function () {
    $results = Division::query()->search('POS')->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->name)->toBe('Port of Spain');
});

it('has full name attribute', function () {
    $division = Division::where('abbreviation', 'CHA')->first();

    expect($division->full_name)->toBe('Chaguanas (Borough)');
});

it('casts type to DivisionType enum', function () {
    $division = Division::where('abbreviation', 'CHA')->first();

    expect($division->type)->toBeInstanceOf(DivisionType::class)
        ->and($division->type)->toBe(DivisionType::Borough);
});
