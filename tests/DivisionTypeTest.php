<?php

use MaxieWright\TrinidadAndTobagoAddresses\Enums\DivisionType;

it('has four division types', function () {
    expect(DivisionType::cases())->toHaveCount(4);
});

it('has regional corporation type', function () {
    $type = DivisionType::RegionalCorporation;

    expect($type->value)->toBe('regional_corporation')
        ->and($type->label())->toBe('Regional Corporation')
        ->and($type->island())->toBe('Trinidad');
});

it('has borough type', function () {
    $type = DivisionType::Borough;

    expect($type->value)->toBe('borough')
        ->and($type->label())->toBe('Borough')
        ->and($type->island())->toBe('Trinidad');
});

it('has city corporation type', function () {
    $type = DivisionType::CityCorporation;

    expect($type->value)->toBe('city_corporation')
        ->and($type->label())->toBe('City Corporation')
        ->and($type->island())->toBe('Trinidad');
});

it('has ward type for tobago', function () {
    $type = DivisionType::Ward;

    expect($type->value)->toBe('ward')
        ->and($type->label())->toBe('Ward')
        ->and($type->island())->toBe('Tobago');
});

it('has filament getLabel support', function () {
    $type = DivisionType::RegionalCorporation;

    expect($type->getLabel())->toBe('Regional Corporation');
});

it('has filament getColor support', function () {
    expect(DivisionType::RegionalCorporation->getColor())->toBe('info')
        ->and(DivisionType::Borough->getColor())->toBe('success')
        ->and(DivisionType::CityCorporation->getColor())->toBe('warning')
        ->and(DivisionType::Ward->getColor())->toBe('primary');
});

it('can filter types by island trinidad', function () {
    $trinidadTypes = DivisionType::forIsland('Trinidad');

    expect($trinidadTypes)->toHaveCount(3)
        ->and($trinidadTypes)->toContain(DivisionType::RegionalCorporation)
        ->and($trinidadTypes)->toContain(DivisionType::Borough)
        ->and($trinidadTypes)->toContain(DivisionType::CityCorporation)
        ->and($trinidadTypes)->not->toContain(DivisionType::Ward);
});

it('can filter types by island tobago', function () {
    $tobagoTypes = DivisionType::forIsland('Tobago');

    expect($tobagoTypes)->toHaveCount(1)
        ->and($tobagoTypes)->toContain(DivisionType::Ward);
});

it('returns all types for unknown island', function () {
    $types = DivisionType::forIsland('Unknown');

    expect($types)->toHaveCount(4);
});
