<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use MaxieWright\TrinidadAndTobagoAddresses\Models\City;

class OptimizeSearchDataCommand extends Command
{
    protected $signature = 'tt-addresses:optimize-search 
                          {--clear-cache : Clear search caches}
                          {--warm-cache : Warm up search caches}';

    protected $description = 'Optimize search data and manage caches for better performance';

    public function handle(): void
    {
        if ($this->option('clear-cache')) {
            $this->info('🗑️  Clearing search caches...');
            $this->clearSearchCaches();
            $this->info('✅ Search caches cleared');
        }

        if ($this->option('warm-cache')) {
            $this->info('🔥 Warming up search caches...');
            $this->warmUpCaches();
            $this->info('✅ Search caches warmed');
        }

        if (!$this->option('clear-cache') && !$this->option('warm-cache')) {
            // Default: clear then warm
            $this->info('🔄 Optimizing search caches...');
            $this->clearSearchCaches();
            $this->warmUpCaches();
            $this->info('✅ Search optimization complete');
        }

        $this->displayStats();
    }

    private function clearSearchCaches(): void
    {
        $cacheKeys = [
            'tt_addresses.popular_cities',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // Clear any search result caches (pattern-based)
        // Note: This is simplified - in production you might want more sophisticated cache clearing
        $this->comment('   Cache keys cleared: ' . count($cacheKeys));
    }

    private function warmUpCaches(): void
    {
        // Warm up popular cities cache
        $this->comment('   Loading popular cities...');
        $popularCities = City::getPopularCached();
        $this->comment("   ✓ {$popularCities->count()} popular cities cached");

        // Pre-cache common search patterns if needed
        $this->comment('   Caches warmed successfully');
    }

    private function displayStats(): void
    {
        $totalCities = City::count();
        $citiesWithCoordinates = City::hasCoordinates()->count();
        $popularCities = config('tt-addresses.popular_cities', []);

        $this->newLine();
        $this->info('📊 Search Data Statistics:');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Cities', number_format($totalCities)],
                ['Cities with Coordinates', number_format($citiesWithCoordinates) . ' (' . round(($citiesWithCoordinates / max($totalCities, 1)) * 100, 1) . '%)'],
                ['Configured Popular Cities', count($popularCities)],
                ['Cache TTL (Popular Cities)', config('tt-addresses.search.popular_cities_cache_ttl', 3600) . ' seconds'],
                ['Autocomplete Limit', config('tt-addresses.search.autocomplete_limit', 10)],
            ]
        );
    }
}