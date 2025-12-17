<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MaxieWright\TrinidadAndTobagoAddresses\Models\City;
use MaxieWright\TrinidadAndTobagoAddresses\Models\Division;

/**
 * Trait for models that have Trinidad and Tobago address fields.
 *
 * Requires the following columns on your model's table:
 * - division_id (nullable, foreign key to tt_divisions)
 * - city_id (nullable, foreign key to tt_cities)
 *
 * Optionally supports:
 * - address_line_1
 * - address_line_2
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
 */
trait HasTrinidadAndTobagoAddress
{
    /**
     * Get the division (administrative area) relationship.
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get the city/town/village relationship.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the formatted address as a single line.
     */
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

    /**
     * Get the formatted address as multiple lines.
     */
    public function getFormattedAddressMultilineAttribute(): string
    {
        $lines = array_filter([
            $this->address_line_1 ?? null,
            $this->address_line_2 ?? null,
            $this->city?->name,
            $this->division?->name,
            config('trinidad-and-tobago-addresses.country_name', 'Trinidad and Tobago'),
        ]);

        return implode("\n", $lines);
    }

    /**
     * Get the island (Trinidad or Tobago).
     */
    public function getIslandAttribute(): ?string
    {
        return $this->division?->island ?? $this->city?->division?->island;
    }

    /**
     * Check if the address is in Tobago.
     */
    public function isInTobago(): bool
    {
        return $this->island === 'Tobago';
    }

    /**
     * Check if the address is in Trinidad.
     */
    public function isInTrinidad(): bool
    {
        return $this->island === 'Trinidad';
    }

    /**
     * Check if address information is complete.
     */
    public function hasCompleteAddress(): bool
    {
        return $this->division_id !== null
            && $this->city_id !== null
            && ! empty($this->address_line_1);
    }

    /**
     * Get the country code.
     */
    public function getCountryCodeAttribute(): string
    {
        return config('trinidad-and-tobago-addresses.country_code', 'TT');
    }
}
