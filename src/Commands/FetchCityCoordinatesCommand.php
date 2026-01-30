<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use MaxieWright\TrinidadAndTobagoAddresses\Models\City;

class FetchCityCoordinatesCommand extends Command
{
    public $signature = 'tt-addresses:fetch-coordinates
                        {--dry-run : Show what would be fetched without saving}
                        {--limit= : Limit number of cities to process}';

    public $description = 'Fetch coordinates for cities using OpenStreetMap Nominatim API';

    public function handle(): int
    {
        $query = City::query()->whereNull('latitude');

        if ($limit = $this->option('limit')) {
            $query->limit((int) $limit);
        }

        $cities = $query->get();
        $this->info("Processing {$cities->count()} cities without coordinates...");

        $progressBar = $this->output->createProgressBar($cities->count());
        $updated = 0;
        $failed = [];

        foreach ($cities as $city) {
            $searchQuery = "{$city->name}, {$city->division->name}, Trinidad and Tobago";

            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'Laravel-TT-Addresses/1.0',
                ])->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $searchQuery,
                    'format' => 'json',
                    'limit' => 1,
                    'countrycodes' => 'tt',
                ]);

                if ($response->successful() && count($response->json()) > 0) {
                    $result = $response->json()[0];

                    if (! $this->option('dry-run')) {
                        $city->update([
                            'latitude' => (float) $result['lat'],
                            'longitude' => (float) $result['lon'],
                        ]);
                    }

                    $updated++;
                    $this->line(" ✓ {$city->name}: {$result['lat']}, {$result['lon']}");
                } else {
                    $failed[] = $city->name;
                }

                // Respect Nominatim rate limit (1 request per second)
                sleep(1);
            } catch (\Exception $e) {
                $failed[] = "{$city->name} (Error: {$e->getMessage()})";
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("Updated: {$updated} cities");

        if (count($failed) > 0) {
            $this->warn('Failed to find coordinates for:');
            foreach ($failed as $name) {
                $this->line("  - {$name}");
            }
        }

        return self::SUCCESS;
    }
}
