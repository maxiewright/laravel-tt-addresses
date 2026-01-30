<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use MaxieWright\TrinidadAndTobagoAddresses\Models\City;
use MaxieWright\TrinidadAndTobagoAddresses\Models\Division;

/**
 * Trait for models that have Trinidad and Tobago address fields.
 *
 * @property int|null $division_id
 * @property int|null $city_id
 * @property string|null $address_line_1
 * @property string|null $address_line_2
 * @property-read Division|null $division
 * @property-read City|null $city
 * @property-read string $formatted_address
 * @property-read string $formatted_address_multiline
 * @property-read string|null $island
 * @property-read string $country_code
 * @property-read array|null $coordinates
 */
trait HasTrinidadAndTobagoAddress
{
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function getFormattedAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line_1 ?? null,
            $this->address_line_2 ?? null,
            $this->city?->name,
            $this->division?->name,
        ]);

        return implode(', ', $parts);
    }

    public function getFormattedAddressMultilineAttribute(): string
    {
        $lines = array_filter([
            $this->address_line_1 ?? null,
            $this->address_line_2 ?? null,
            $this->city?->name,
            $this->division?->name,
            config('tt-addresses.country_name', 'Trinidad and Tobago'),
        ]);

        return implode("\n", $lines);
    }

    public function getIslandAttribute(): ?string
    {
        return $this->division?->island ?? $this->city?->division?->island;
    }

    public function isInTobago(): bool
    {
        return $this->island === 'Tobago';
    }

    public function isInTrinidad(): bool
    {
        return $this->island === 'Trinidad';
    }

    public function hasCompleteAddress(): bool
    {
        return $this->division_id !== null
            && $this->city_id !== null
            && ! empty($this->address_line_1);
    }

    public function getCountryCodeAttribute(): string
    {
        return config('tt-addresses.country_code', 'TT');
    }

    /* ───────────────────────────────────────────────────────────
     * Geolocation Methods
     * ─────────────────────────────────────────────────────────── */

    /**
     * Get coordinates from the associated city.
     */
    public function getCoordinatesAttribute(): ?array
    {
        return $this->city?->coordinates;
    }

    /**
     * Get latitude from the associated city.
     */
    public function getLatitudeAttribute(): ?float
    {
        return $this->city?->latitude;
    }

    /**
     * Get longitude from the associated city.
     */
    public function getLongitudeAttribute(): ?float
    {
        return $this->city?->longitude;
    }

    /**
     * Check if the address has coordinate data.
     */
    public function hasCoordinates(): bool
    {
        return $this->city?->hasCoordinatesData() ?? false;
    }

    /**
     * Calculate distance to another address or coordinates (in kilometers).
     */
    public function distanceTo(self|City|array $target): ?float
    {
        if (! $this->hasCoordinates()) {
            return null;
        }

        if ($target instanceof self) {
            return $this->city->distanceTo($target->city);
        }

        return $this->city->distanceTo($target);
    }

    /**
     * Get Google Maps URL for this address.
     */
    public function getGoogleMapsUrl(): ?string
    {
        return $this->city?->getGoogleMapsUrl();
    }

    /**
     * Get OpenStreetMap URL for this address.
     */
    public function getOpenStreetMapUrl(): ?string
    {
        return $this->city?->getOpenStreetMapUrl();
    }

    /**
     * Find nearby cities within a radius (in kilometers).
     */
    public function findNearbyCities(float $radiusKm = 10, ?int $limit = null): Collection
    {
        if (! $this->hasCoordinates()) {
            return collect();
        }

        $query = City::query()
            ->withinRadius($this->latitude, $this->longitude, $radiusKm)
            ->where('id', '!=', $this->city_id)
            ->orderByDistanceFrom($this->latitude, $this->longitude);

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Detect the most likely City for the given coordinates.
     *
     * This uses the Cities table's geospatial helpers: it first looks for
     * a city within the provided radius (in km), ordered by distance, and
     * returns the closest match. If none are found within the radius, it
     * falls back to the closest city overall.
     *
     * Example:
     *   $city = $model->detectCityFromCoordinates($lat, $lng);
     *
     * @param  float  $radiusKm  Search radius in kilometers (default: 25)
     * @return City|null The detected city or null if none found
     */
    public function detectCityFromCoordinates(float $latitude, float $longitude, float $radiusKm = 25): ?City
    {
        // First, try within the given radius
        $nearestInRadius = City::query()
            ->hasCoordinates()
            ->withinRadius($latitude, $longitude, $radiusKm)
            ->orderByDistanceFrom($latitude, $longitude)
            ->first();

        if ($nearestInRadius) {
            return $nearestInRadius;
        }

        // Fallback: nearest city overall (no radius restriction)
        return City::query()
            ->hasCoordinates()
            ->orderByDistanceFrom($latitude, $longitude)
            ->first();
    }
}
