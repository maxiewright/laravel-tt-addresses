<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use MaxieWright\TrinidadAndTobagoAddresses\Concerns\HasTrinidadAndTobagoAddress;
use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\CitySeeder;
use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\DivisionSeeder;
use MaxieWright\TrinidadAndTobagoAddresses\Models\City;
use MaxieWright\TrinidadAndTobagoAddresses\Models\Division;

// Create a test model that uses the trait
class TestAddress extends Model
{
    use HasTrinidadAndTobagoAddress;

    protected $table = 'test_addresses';

    protected $fillable = [
        'address_line_1',
        'address_line_2',
        'division_id',
        'city_id',
    ];
}

beforeEach(function () {
    // Create test table
    Schema::create('test_addresses', function (Blueprint $table) {
        $table->id();
        $table->string('address_line_1')->nullable();
        $table->string('address_line_2')->nullable();
        $table->foreignId('division_id')->nullable();
        $table->foreignId('city_id')->nullable();
        $table->timestamps();
    });

    (new DivisionSeeder)->run();
    (new CitySeeder)->run();
});

afterEach(function () {
    Schema::dropIfExists('test_addresses');
});

it('has division relationship', function () {
    $division = Division::where('abbreviation', 'CHA')->first();

    $address = TestAddress::create([
        'address_line_1' => '123 Main Street',
        'division_id' => $division->id,
    ]);

    expect($address->division)->toBeInstanceOf(Division::class)
        ->and($address->division->name)->toBe('Chaguanas');
});

it('has city relationship', function () {
    $city = City::where('name', 'Chaguanas')->first();

    $address = TestAddress::create([
        'address_line_1' => '123 Main Street',
        'city_id' => $city->id,
    ]);

    expect($address->city)->toBeInstanceOf(City::class)
        ->and($address->city->name)->toBe('Chaguanas');
});

it('has formatted address attribute', function () {
    $division = Division::where('abbreviation', 'CHA')->first();
    $city = City::where('name', 'Chaguanas')->first();

    $address = TestAddress::create([
        'address_line_1' => '123 Main Street',
        'address_line_2' => 'Building A',
        'division_id' => $division->id,
        'city_id' => $city->id,
    ]);

    expect($address->formatted_address)->toBe('123 Main Street, Building A, Chaguanas, Chaguanas');
});

it('handles formatted address with missing fields', function () {
    $division = Division::where('abbreviation', 'CHA')->first();

    $address = TestAddress::create([
        'address_line_1' => '123 Main Street',
        'division_id' => $division->id,
    ]);

    expect($address->formatted_address)->toBe('123 Main Street, Chaguanas');
});

it('has formatted address multiline attribute', function () {
    $division = Division::where('abbreviation', 'CHA')->first();
    $city = City::where('name', 'Chaguanas')->first();

    $address = TestAddress::create([
        'address_line_1' => '123 Main Street',
        'division_id' => $division->id,
        'city_id' => $city->id,
    ]);

    $expected = "123 Main Street\nChaguanas\nChaguanas\nTrinidad and Tobago";

    expect($address->formatted_address_multiline)->toBe($expected);
});

it('has island attribute via division', function () {
    $trinidadDivision = Division::where('abbreviation', 'CHA')->first();
    $tobagoDivision = Division::where('abbreviation', 'TOB')->first();

    $trinidadAddress = TestAddress::create([
        'division_id' => $trinidadDivision->id,
    ]);

    $tobagoAddress = TestAddress::create([
        'division_id' => $tobagoDivision->id,
    ]);

    expect($trinidadAddress->island)->toBe('Trinidad')
        ->and($tobagoAddress->island)->toBe('Tobago');
});

it('has island attribute via city', function () {
    $trinidadCity = City::where('name', 'Chaguanas')->first();
    $tobagoCity = City::where('name', 'Scarborough')->first();

    $trinidadAddress = TestAddress::create([
        'city_id' => $trinidadCity->id,
    ]);

    $tobagoAddress = TestAddress::create([
        'city_id' => $tobagoCity->id,
    ]);

    expect($trinidadAddress->island)->toBe('Trinidad')
        ->and($tobagoAddress->island)->toBe('Tobago');
});

it('can check if address is in tobago', function () {
    $tobagoDivision = Division::where('abbreviation', 'TOB')->first();

    $address = TestAddress::create([
        'division_id' => $tobagoDivision->id,
    ]);

    expect($address->isInTobago())->toBeTrue()
        ->and($address->isInTrinidad())->toBeFalse();
});

it('can check if address is in trinidad', function () {
    $trinidadDivision = Division::where('abbreviation', 'CHA')->first();

    $address = TestAddress::create([
        'division_id' => $trinidadDivision->id,
    ]);

    expect($address->isInTrinidad())->toBeTrue()
        ->and($address->isInTobago())->toBeFalse();
});

it('can check if address is complete', function () {
    $division = Division::where('abbreviation', 'CHA')->first();
    $city = City::where('name', 'Chaguanas')->first();

    $completeAddress = TestAddress::create([
        'address_line_1' => '123 Main Street',
        'division_id' => $division->id,
        'city_id' => $city->id,
    ]);

    $incompleteAddress = TestAddress::create([
        'division_id' => $division->id,
    ]);

    expect($completeAddress->hasCompleteAddress())->toBeTrue()
        ->and($incompleteAddress->hasCompleteAddress())->toBeFalse();
});

it('has country code attribute', function () {
    $address = TestAddress::create([]);

    expect($address->country_code)->toBe('TT');
});

// ───────────────────────────────────────────────────────────────
// Geolocation Tests
// ───────────────────────────────────────────────────────────────

it('can detect city from coordinates within radius', function () {
    // Clear all coordinates first to have controlled test
    City::query()->update(['latitude' => null, 'longitude' => null]);

    // Only set Port of Spain coordinates
    City::where('name', 'Port-of-Spain')->update([
        'latitude' => 10.6596,
        'longitude' => -61.5086,
    ]);

    $address = TestAddress::create([]);

    // Coordinates very close to Port of Spain (within default 25km radius)
    $city = $address->detectCityFromCoordinates(10.6600, -61.5090);

    expect($city)->not->toBeNull()
        ->and($city->name)->toBe('Port-of-Spain');
});

it('can detect city from coordinates using fallback when outside radius', function () {
    // Clear all coordinates first
    City::query()->update(['latitude' => null, 'longitude' => null]);

    // Only set coordinates for San Fernando (far from test point)
    City::where('name', 'San Fernando')->update([
        'latitude' => 10.2833,
        'longitude' => -61.4667,
    ]);

    $address = TestAddress::create([]);

    // Coordinates near Port of Spain, but using tiny radius (0.1km) so nothing matches
    // Should fallback to San Fernando as it's the only city with coordinates
    $city = $address->detectCityFromCoordinates(10.6596, -61.5086, radiusKm: 0.1);

    expect($city)->not->toBeNull()
        ->and($city->name)->toBe('San Fernando');
});

it('can detect city with custom radius', function () {
    // Clear all coordinates first
    City::query()->update(['latitude' => null, 'longitude' => null]);

    // Set coordinates for multiple cities
    City::where('name', 'Port-of-Spain')->update([
        'latitude' => 10.6596,
        'longitude' => -61.5086,
    ]);

    City::where('name', 'San Fernando')->update([
        'latitude' => 10.2833,
        'longitude' => -61.4667,
    ]);

    $address = TestAddress::create([]);

    // With a 50km radius from Port of Spain coordinates, should find Port of Spain
    $city = $address->detectCityFromCoordinates(10.6596, -61.5086, radiusKm: 50);

    expect($city)->not->toBeNull()
        ->and($city->name)->toBe('Port-of-Spain');
});

it('returns nearest city when multiple cities within radius', function () {
    // Clear all coordinates first
    City::query()->update(['latitude' => null, 'longitude' => null]);

    // Set coordinates for Port of Spain and Diego Martin (nearby)
    City::where('name', 'Port-of-Spain')->update([
        'latitude' => 10.6596,
        'longitude' => -61.5086,
    ]);

    City::where('name', 'Diego Martin')->update([
        'latitude' => 10.7214,
        'longitude' => -61.5661,
    ]);

    $address = TestAddress::create([]);

    // Coordinates closer to Port of Spain
    $city = $address->detectCityFromCoordinates(10.6600, -61.5100, radiusKm: 50);

    expect($city)->not->toBeNull()
        ->and($city->name)->toBe('Port-of-Spain');
});

it('has coordinates accessor from city', function () {
    $city = City::where('name', 'Port-of-Spain')->first();
    $city->update([
        'latitude' => 10.6596,
        'longitude' => -61.5086,
    ]);

    $address = TestAddress::create([
        'city_id' => $city->id,
    ]);

    expect($address->coordinates)->toBe([
        'latitude' => 10.6596,
        'longitude' => -61.5086,
    ]);
});

it('has latitude and longitude accessors from city', function () {
    $city = City::where('name', 'Port-of-Spain')->first();
    $city->update([
        'latitude' => 10.6596,
        'longitude' => -61.5086,
    ]);

    $address = TestAddress::create([
        'city_id' => $city->id,
    ]);

    expect($address->latitude)->toBe(10.6596)
        ->and($address->longitude)->toBe(-61.5086);
});

it('returns null coordinates when city has no coordinates', function () {
    $city = City::where('name', 'Chaguanas')->first();
    $city->update(['latitude' => null, 'longitude' => null]);

    $address = TestAddress::create([
        'city_id' => $city->id,
    ]);

    expect($address->coordinates)->toBeNull()
        ->and($address->latitude)->toBeNull()
        ->and($address->longitude)->toBeNull();
});

it('can check if address has coordinates', function () {
    $cityWithCoords = City::where('name', 'Port-of-Spain')->first();
    $cityWithCoords->update([
        'latitude' => 10.6596,
        'longitude' => -61.5086,
    ]);

    $cityWithoutCoords = City::where('name', 'Chaguanas')->first();
    $cityWithoutCoords->update(['latitude' => null, 'longitude' => null]);

    $addressWithCoords = TestAddress::create(['city_id' => $cityWithCoords->id]);
    $addressWithoutCoords = TestAddress::create(['city_id' => $cityWithoutCoords->id]);
    $addressNoCity = TestAddress::create([]);

    expect($addressWithCoords->hasCoordinates())->toBeTrue()
        ->and($addressWithoutCoords->hasCoordinates())->toBeFalse()
        ->and($addressNoCity->hasCoordinates())->toBeFalse();
});

it('can get google maps url from address', function () {
    $city = City::where('name', 'Port-of-Spain')->first();
    $city->update([
        'latitude' => 10.6596,
        'longitude' => -61.5086,
    ]);

    $address = TestAddress::create(['city_id' => $city->id]);

    expect($address->getGoogleMapsUrl())
        ->toBe('https://www.google.com/maps?q=10.6596,-61.5086');
});

it('can get openstreetmap url from address', function () {
    $city = City::where('name', 'Port-of-Spain')->first();
    $city->update([
        'latitude' => 10.6596,
        'longitude' => -61.5086,
    ]);

    $address = TestAddress::create(['city_id' => $city->id]);

    expect($address->getOpenStreetMapUrl())
        ->toBe('https://www.openstreetmap.org/?mlat=10.6596&mlon=-61.5086&zoom=15');
});

it('returns null for map urls when address has no coordinates', function () {
    $address = TestAddress::create([]);

    expect($address->getGoogleMapsUrl())->toBeNull()
        ->and($address->getOpenStreetMapUrl())->toBeNull();
});
