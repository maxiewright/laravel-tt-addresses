<?php

use Illuminate\Database\Eloquent\Model;
use MaxieWright\TrinidadAndTobagoAddresses\Concerns\HasAddresses;
use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\CitySeeder;
use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\DivisionSeeder;
use MaxieWright\TrinidadAndTobagoAddresses\Enums\AddressType;
use MaxieWright\TrinidadAndTobagoAddresses\Models\Address;
use MaxieWright\TrinidadAndTobagoAddresses\Models\City;
use MaxieWright\TrinidadAndTobagoAddresses\Models\Division;

class TestAddressable extends Model
{
    use HasAddresses;

    protected $table = 'test_addressables';

    protected $fillable = ['name'];
}

beforeEach(function () {
    \Illuminate\Support\Facades\Schema::create('test_addressables', function (\Illuminate\Database\Schema\Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });

    (new DivisionSeeder)->run();
    (new CitySeeder)->run();
});

afterEach(function () {
    \Illuminate\Support\Facades\Schema::dropIfExists('test_addressables');
});

it('belongs to addressable polymorphically', function () {
    $model = TestAddressable::create(['name' => 'Test User']);
    $division = Division::where('abbreviation', 'CHA')->first();
    $city = City::where('name', 'Chaguanas')->first();

    $address = $model->addresses()->create([
        'type' => AddressType::Home,
        'line_1' => '123 Main Street',
        'division_id' => $division->id,
        'city_id' => $city->id,
        'is_primary' => true,
    ]);

    expect($address->addressable)->toBeInstanceOf(TestAddressable::class)
        ->and($address->addressable->id)->toBe($model->id)
        ->and($address->addressable_type)->toBe(TestAddressable::class);
});

it('belongs to division and city', function () {
    $model = TestAddressable::create(['name' => 'Test']);
    $division = Division::where('abbreviation', 'CHA')->first();
    $city = City::where('name', 'Chaguanas')->first();

    $address = $model->addresses()->create([
        'type' => AddressType::Work,
        'line_1' => '456 Work Ave',
        'division_id' => $division->id,
        'city_id' => $city->id,
    ]);

    expect($address->division)->toBeInstanceOf(Division::class)
        ->and($address->division->name)->toBe('Chaguanas')
        ->and($address->city)->toBeInstanceOf(City::class)
        ->and($address->city->name)->toBe('Chaguanas');
});

it('casts type to AddressType enum', function () {
    $model = TestAddressable::create(['name' => 'Test']);
    $division = Division::where('abbreviation', 'CHA')->first();
    $city = City::where('name', 'Chaguanas')->first();

    $address = $model->addresses()->create([
        'type' => AddressType::Home,
        'line_1' => '123 Main',
        'division_id' => $division->id,
        'city_id' => $city->id,
    ]);

    expect($address->type)->toBe(AddressType::Home)
        ->and($address->type->value)->toBe('home');
});

it('has formatted address attribute', function () {
    $model = TestAddressable::create(['name' => 'Test']);
    $division = Division::where('abbreviation', 'CHA')->first();
    $city = City::where('name', 'Chaguanas')->first();

    $address = $model->addresses()->create([
        'type' => AddressType::Home,
        'line_1' => '123 Main Street',
        'line_2' => 'Building A',
        'division_id' => $division->id,
        'city_id' => $city->id,
    ]);

    expect($address->formatted_address)->toBe('123 Main Street, Building A, Chaguanas, Chaguanas');
});

it('has formatted address multiline attribute', function () {
    $model = TestAddressable::create(['name' => 'Test']);
    $division = Division::where('abbreviation', 'CHA')->first();
    $city = City::where('name', 'Chaguanas')->first();

    $address = $model->addresses()->create([
        'type' => AddressType::Home,
        'line_1' => '123 Main Street',
        'division_id' => $division->id,
        'city_id' => $city->id,
    ]);

    $expected = "123 Main Street\nChaguanas\nChaguanas\nTrinidad and Tobago";
    expect($address->formatted_address_multiline)->toBe($expected);
});

it('has island attribute', function () {
    $model = TestAddressable::create(['name' => 'Test']);
    $trinidadDivision = Division::where('abbreviation', 'CHA')->first();
    $tobagoDivision = Division::where('abbreviation', 'TOB')->first();

    $trinidadAddress = $model->addresses()->create([
        'type' => AddressType::Home,
        'line_1' => '123 Main',
        'division_id' => $trinidadDivision->id,
    ]);

    $tobagoAddress = $model->addresses()->create([
        'type' => AddressType::Work,
        'line_1' => '456 Work',
        'division_id' => $tobagoDivision->id,
    ]);

    expect($trinidadAddress->island)->toBe('Trinidad')
        ->and($tobagoAddress->island)->toBe('Tobago');
});

it('has coordinates accessor', function () {
    $model = TestAddressable::create(['name' => 'Test']);
    $division = Division::where('abbreviation', 'CHA')->first();
    $city = City::where('name', 'Chaguanas')->first();

    $address = $model->addresses()->create([
        'type' => AddressType::Home,
        'line_1' => '123 Main',
        'division_id' => $division->id,
        'city_id' => $city->id,
        'latitude' => 10.5,
        'longitude' => -61.3,
    ]);

    expect($address->coordinates)->toBe([
        'latitude' => 10.5,
        'longitude' => -61.3,
    ]);
});

it('returns null coordinates when no lat lng', function () {
    $model = TestAddressable::create(['name' => 'Test']);
    $division = Division::where('abbreviation', 'CHA')->first();

    $address = $model->addresses()->create([
        'type' => AddressType::Home,
        'line_1' => '123 Main',
        'division_id' => $division->id,
    ]);

    expect($address->coordinates)->toBeNull()
        ->and($address->hasCoordinatesData())->toBeFalse();
});

it('uses configurable table name', function () {
    config(['tt-addresses.tables.addresses' => 'tt_addresses']);
    expect((new Address)->getTable())->toBe('tt_addresses');
});

it('soft deletes', function () {
    $model = TestAddressable::create(['name' => 'Test']);
    $division = Division::where('abbreviation', 'CHA')->first();

    $address = $model->addresses()->create([
        'type' => AddressType::Home,
        'line_1' => '123 Main',
        'division_id' => $division->id,
    ]);

    $id = $address->id;
    $address->delete();

    expect(Address::find($id))->toBeNull()
        ->and(Address::withTrashed()->find($id))->not->toBeNull();
});
