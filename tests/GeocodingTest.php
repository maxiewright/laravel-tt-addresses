<?php

use MaxieWright\TrinidadAndTobagoAddresses\Contracts\Geocoder;
use MaxieWright\TrinidadAndTobagoAddresses\DataTransferObjects\GeocodingResult;
use MaxieWright\TrinidadAndTobagoAddresses\DataTransferObjects\ReverseGeocodingResult;
use MaxieWright\TrinidadAndTobagoAddresses\Facades\Geocoder as GeocoderFacade;
use MaxieWright\TrinidadAndTobagoAddresses\Services\Geocoding\NullGeocoder;

beforeEach(function () {
    config(['tt-addresses.geocoding.driver' => 'null']);
    $this->app->instance(Geocoder::class, new NullGeocoder);
});

it('resolves geocoder from container', function () {
    $geocoder = app(Geocoder::class);

    expect($geocoder)->toBeInstanceOf(Geocoder::class)
        ->and($geocoder)->toBeInstanceOf(NullGeocoder::class);
});

it('null geocoder returns null for geocode', function () {
    $geocoder = app(Geocoder::class);

    $result = $geocoder->geocode('18 Sackville Street, Port of Spain');

    expect($result)->toBeNull();
});

it('null geocoder returns null for reverseGeocode', function () {
    $geocoder = app(Geocoder::class);

    $result = $geocoder->reverseGeocode(10.6549, -61.5019);

    expect($result)->toBeNull();
});

it('facade geocode returns null when using null driver', function () {
    $result = GeocoderFacade::geocode('Port of Spain, Trinidad');

    expect($result)->toBeNull();
});

it('facade reverseGeocode returns null when using null driver', function () {
    $result = GeocoderFacade::reverseGeocode(10.6549, -61.5019);

    expect($result)->toBeNull();
});

it('GeocodingResult DTO has correct structure', function () {
    $dto = new GeocodingResult(
        latitude: 10.6549,
        longitude: -61.5019,
        formattedAddress: 'Port of Spain, Trinidad and Tobago',
        accuracy: 0.5
    );

    expect($dto->latitude)->toBe(10.6549)
        ->and($dto->longitude)->toBe(-61.5019)
        ->and($dto->formattedAddress)->toBe('Port of Spain, Trinidad and Tobago')
        ->and($dto->accuracy)->toBe(0.5);
});

it('GeocodingResult toArray returns expected keys', function () {
    $dto = new GeocodingResult(
        latitude: 10.6549,
        longitude: -61.5019,
        formattedAddress: 'Port of Spain',
        accuracy: null
    );

    $array = $dto->toArray();

    expect($array)->toHaveKeys(['latitude', 'longitude', 'formatted_address', 'accuracy'])
        ->and($array['latitude'])->toBe(10.6549)
        ->and($array['longitude'])->toBe(-61.5019);
});

it('ReverseGeocodingResult DTO has correct structure', function () {
    $dto = new ReverseGeocodingResult(
        formattedAddress: 'Port of Spain, Trinidad and Tobago',
        street: 'Sackville Street',
        city: 'Port of Spain',
        region: 'Port of Spain'
    );

    expect($dto->formattedAddress)->toBe('Port of Spain, Trinidad and Tobago')
        ->and($dto->street)->toBe('Sackville Street')
        ->and($dto->city)->toBe('Port of Spain')
        ->and($dto->region)->toBe('Port of Spain');
});
