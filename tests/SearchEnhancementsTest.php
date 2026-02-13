<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Tests;

use MaxieWright\TrinidadAndTobagoAddresses\Enums\ServiceRadius;
use MaxieWright\TrinidadAndTobagoAddresses\Models\City;
use MaxieWright\TrinidadAndTobagoAddresses\Models\Division;
use Orchestra\Testbench\TestCase;

class SearchEnhancementsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        
        // Create test division
        $division = Division::create([
            'name' => 'Test Regional Corporation',
            'type' => 'Regional Corporation',
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
    }

    /** @test */
    public function it_can_autocomplete_city_names()
    {
        $results = City::autocomplete('Port', 5)->get();
        
        $this->assertCount(1, $results);
        $this->assertEquals('Port of Spain', $results->first()->name);
    }

    /** @test */
    public function it_can_get_popular_cities()
    {
        config(['tt-addresses.popular_cities' => ['Port of Spain', 'San Fernando', 'Chaguanas']]);
        
        $results = City::popular()->get();
        
        $this->assertGreaterThan(0, $results->count());
        $this->assertEquals('Port of Spain', $results->first()->name);
    }

    /** @test */
    public function it_can_find_cities_within_service_area()
    {
        $portOfSpainLat = 10.6596;
        $portOfSpainLng = -61.5089;
        
        $results = City::withinServiceArea($portOfSpainLat, $portOfSpainLng, ServiceRadius::REGIONAL)->get();
        
        $this->assertGreaterThan(0, $results->count());
    }

    /** @test */
    public function it_can_convert_city_to_search_result()
    {
        $city = City::with('division')->first();
        $result = $city->toSearchResult();
        
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('full_location', $result);
        $this->assertArrayHasKey('coordinates', $result);
        $this->assertArrayHasKey('division_type', $result);
    }

    /** @test */
    public function it_can_convert_city_to_autocomplete_option()
    {
        $city = City::with('division')->first();
        $option = $city->toAutocompleteOption();
        
        $this->assertArrayHasKey('value', $option);
        $this->assertArrayHasKey('label', $option);
        $this->assertArrayHasKey('description', $option);
        $this->assertArrayHasKey('coordinates', $option);
    }

    /** @test */
    public function service_radius_enum_works_correctly()
    {
        $walking = ServiceRadius::WALKING;
        $driving = ServiceRadius::DRIVING;
        
        $this->assertEquals(2, $walking->value);
        $this->assertEquals(10, $driving->value);
        $this->assertEquals('2 km (Walking Distance)', $walking->label());
        $this->assertEquals('10 km (Driving Distance)', $driving->label());
    }

    /** @test */
    public function it_can_get_suggested_service_cities()
    {
        $portOfSpainLat = 10.6596;
        $portOfSpainLng = -61.5089;
        
        $suggestions = City::getSuggestedServiceCities($portOfSpainLat, $portOfSpainLng, 5);
        
        $this->assertLessThanOrEqual(5, $suggestions->count());
        $this->assertGreaterThan(0, $suggestions->count());
    }

    /** @test */
    public function it_can_cache_popular_cities()
    {
        config(['tt-addresses.popular_cities' => ['Port of Spain', 'San Fernando']]);
        
        // First call - should cache
        $first = City::getPopularCached(60);
        
        // Second call - should use cache
        $second = City::getPopularCached(60);
        
        $this->assertEquals($first->count(), $second->count());
    }

    protected function getPackageProviders($app)
    {
        return [
            \MaxieWright\TrinidadAndTobagoAddresses\TrinidadAndTobagoAddressesServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}