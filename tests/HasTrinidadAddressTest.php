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

    (new DivisionSeeder())->run();
    (new CitySeeder())->run();
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
