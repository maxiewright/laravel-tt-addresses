<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use MaxieWright\TrinidadAndTobagoAddresses\Models\Address;

class GeocodeAddress implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Address $address
    ) {}

    public function handle(): void
    {
        try {
            $this->address->geocode();
        } catch (\Throwable $e) {
            Log::warning('Geocoding failed for address', [
                'address_id' => $this->address->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
