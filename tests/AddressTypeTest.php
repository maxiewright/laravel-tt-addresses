<?php

use MaxieWright\TrinidadAndTobagoAddresses\Enums\AddressType;

it('has five address types', function () {
    expect(AddressType::cases())->toHaveCount(5);
});

it('has home type', function () {
    $type = AddressType::Home;

    expect($type->value)->toBe('home')
        ->and($type->label())->toBe('Home');
});

it('has work type', function () {
    $type = AddressType::Work;

    expect($type->value)->toBe('work')
        ->and($type->label())->toBe('Work');
});

it('has billing type', function () {
    $type = AddressType::Billing;

    expect($type->value)->toBe('billing')
        ->and($type->label())->toBe('Billing');
});

it('has shipping type', function () {
    $type = AddressType::Shipping;

    expect($type->value)->toBe('shipping')
        ->and($type->label())->toBe('Shipping');
});

it('has other type', function () {
    $type = AddressType::Other;

    expect($type->value)->toBe('other')
        ->and($type->label())->toBe('Other');
});

it('has filament getLabel support', function () {
    $type = AddressType::Home;

    expect($type->getLabel())->toBe('Home');
});

it('has filament getColor support', function () {
    expect(AddressType::Home->getColor())->toBe('success')
        ->and(AddressType::Work->getColor())->toBe('info')
        ->and(AddressType::Billing->getColor())->toBe('warning')
        ->and(AddressType::Shipping->getColor())->toBe('primary')
        ->and(AddressType::Other->getColor())->toBe('gray');
});
