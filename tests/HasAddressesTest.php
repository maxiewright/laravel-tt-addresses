<?php

use Illuminate\Database\Eloquent\Model;
use MaxieWright\TrinidadAndTobagoAddresses\Concerns\HasAddresses;
use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\CitySeeder;
use MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders\DivisionSeeder;
use MaxieWright\TrinidadAndTobagoAddresses\Enums\AddressType;
use MaxieWright\TrinidadAndTobagoAddresses\Models\City;
use MaxieWright\TrinidadAndTobagoAddresses\Models\Division;

class TestHasAddressesModel extends Model
{
    use HasAddresses;

    protected $table = 'test_has_addresses';

    protected $fillable = ['name'];
}

beforeEach(function () {
    \Illuminate\Support\Facades\Schema::create('test_has_addresses', function (\Illuminate\Database\Schema\Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });

    (new DivisionSeeder)->run();
    (new CitySeeder)->run();
});

afterEach(function () {
    \Illuminate\Support\Facades\Schema::dropIfExists('test_has_addresses');
});

it('has addresses relationship', function () {
    $model = TestHasAddressesModel::create(['name' => 'User']);
    $division = Division::where('abbreviation', 'CHA')->first();
    $city = City::where('name', 'Chaguanas')->first();

    $model->addresses()->create([
        'type' => AddressType::Home,
        'line_1' => '123 Main',
        'division_id' => $division->id,
        'city_id' => $city->id,
    ]);

    $model->addresses()->create([
        'type' => AddressType::Work,
        'line_1' => '456 Work',
        'division_id' => $division->id,
        'city_id' => $city->id,
    ]);

    expect($model->addresses)->toHaveCount(2);
});

it('has primary address relationship', function () {
    $model = TestHasAddressesModel::create(['name' => 'User']);
    $division = Division::where('abbreviation', 'CHA')->first();
    $city = City::where('name', 'Chaguanas')->first();

    $model->addresses()->create([
        'type' => AddressType::Work,
        'line_1' => '456 Work',
        'division_id' => $division->id,
        'city_id' => $city->id,
        'is_primary' => false,
    ]);

    $primary = $model->addresses()->create([
        'type' => AddressType::Home,
        'line_1' => '123 Main',
        'division_id' => $division->id,
        'city_id' => $city->id,
        'is_primary' => true,
    ]);

    expect($model->primaryAddress)->not->toBeNull()
        ->and($model->primaryAddress->id)->toBe($primary->id)
        ->and($model->primaryAddress->line_1)->toBe('123 Main');
});

it('returns null primary address when none set', function () {
    $model = TestHasAddressesModel::create(['name' => 'User']);
    $division = Division::where('abbreviation', 'CHA')->first();
    $city = City::where('name', 'Chaguanas')->first();

    $model->addresses()->create([
        'type' => AddressType::Home,
        'line_1' => '123 Main',
        'division_id' => $division->id,
        'city_id' => $city->id,
        'is_primary' => false,
    ]);

    expect($model->primaryAddress)->toBeNull();
});

it('has addressOfType relationship', function () {
    $model = TestHasAddressesModel::create(['name' => 'User']);
    $division = Division::where('abbreviation', 'CHA')->first();
    $city = City::where('name', 'Chaguanas')->first();

    $model->addresses()->create([
        'type' => AddressType::Billing,
        'line_1' => '789 Billing',
        'division_id' => $division->id,
        'city_id' => $city->id,
    ]);

    $homeAddress = $model->addresses()->create([
        'type' => AddressType::Home,
        'line_1' => '123 Home',
        'division_id' => $division->id,
        'city_id' => $city->id,
    ]);

    $found = $model->addressOfType(AddressType::Home)->first();

    expect($found)->not->toBeNull()
        ->and($found->id)->toBe($homeAddress->id)
        ->and($found->line_1)->toBe('123 Home');
});

it('has homeAddress shortcut', function () {
    $model = TestHasAddressesModel::create(['name' => 'User']);
    $division = Division::where('abbreviation', 'CHA')->first();
    $city = City::where('name', 'Chaguanas')->first();

    $model->addresses()->create([
        'type' => AddressType::Home,
        'line_1' => '123 Home',
        'division_id' => $division->id,
        'city_id' => $city->id,
    ]);

    $home = $model->homeAddress()->first();
    expect($home)->not->toBeNull()
        ->and($home->type)->toBe(AddressType::Home);
});

it('has workAddress shortcut', function () {
    $model = TestHasAddressesModel::create(['name' => 'User']);
    $division = Division::where('abbreviation', 'CHA')->first();
    $city = City::where('name', 'Chaguanas')->first();

    $model->addresses()->create([
        'type' => AddressType::Work,
        'line_1' => '456 Work',
        'division_id' => $division->id,
        'city_id' => $city->id,
    ]);

    $work = $model->workAddress()->first();
    expect($work)->not->toBeNull()
        ->and($work->type)->toBe(AddressType::Work);
});
