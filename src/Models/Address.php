<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use MaxieWright\TrinidadAndTobagoAddresses\Contracts\Geocoder;
use MaxieWright\TrinidadAndTobagoAddresses\Enums\AddressType;
use MaxieWright\TrinidadAndTobagoAddresses\Jobs\GeocodeAddress;

/**
 * Polymorphic Address Model
 *
 * Represents an address linked to any model via morph relationship.
 *
 * @property int $id
 * @property string $addressable_type
 * @property int $addressable_id
 * @property AddressType $type
 * @property bool $is_primary
 * @property string $line_1
 * @property string|null $line_2
 * @property int|null $division_id
 * @property int|null $city_id
 * @property string|null $postal_code
 * @property float|null $latitude
 * @property float|null $longitude
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Model $addressable
 * @property-read Division|null $division
 * @property-read City|null $city
 * @property-read string $formatted_address
 * @property-read string $formatted_address_multiline
 * @property-read string|null $island
 * @property-read array|null $coordinates
 */
class Address extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'addressable_type',
        'addressable_id',
        'type',
        'is_primary',
        'line_1',
        'line_2',
        'division_id',
        'city_id',
        'postal_code',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => AddressType::class,
        'is_primary' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Get the table name from config.
     */
    public function getTable(): string
    {
        return config('tt-addresses.tables.addresses', 'tt_addresses');
    }

    protected static function booted(): void
    {
        static::saved(function (Address $address) {
            if (! config('tt-addresses.geocoding.enabled', false)) {
                return;
            }

            $addressDirty = $address->wasChanged(['line_1', 'line_2', 'division_id', 'city_id']);
            if (! $addressDirty || $address->latitude !== null) {
                return;
            }

            if (config('tt-addresses.geocoding.queue', true)) {
                GeocodeAddress::dispatch($address);
            } else {
                $address->geocode();
            }
        });
    }

    /* ───────────────────────────────────────────────────────────
     * Relationships
     * ─────────────────────────────────────────────────────────── */

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /* ───────────────────────────────────────────────────────────
     * Accessors
     * ─────────────────────────────────────────────────────────── */

    public function formattedAddress(): Attribute
    {
        return Attribute::make(
            get: function () {
                $parts = array_filter([
                    $this->line_1,
                    $this->line_2,
                    $this->city?->name,
                    $this->division?->name,
                ]);

                return implode(', ', $parts);
            },
        );
    }

    public function formattedAddressMultiline(): Attribute
    {
        return Attribute::make(
            get: function () {
                $lines = array_filter([
                    $this->line_1,
                    $this->line_2,
                    $this->city?->name,
                    $this->division?->name,
                    config('tt-addresses.country_name', 'Trinidad and Tobago'),
                ]);

                return implode("\n", $lines);
            },
        );
    }

    public function island(): Attribute
    {
        return Attribute::make(
            get: function () {
                $division = $this->division;
                if ($division !== null) {
                    return $division->island;
                }
                $city = $this->city;
                if ($city?->division !== null) {
                    return $city->division->island;
                }

                return null;
            },
        );
    }

    public function coordinates(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->hasCoordinatesData()
                ? ['latitude' => $this->latitude, 'longitude' => $this->longitude]
                : null,
        );
    }

    /* ───────────────────────────────────────────────────────────
     * Helper Methods
     * ─────────────────────────────────────────────────────────── */

    public function hasCoordinatesData(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    /**
     * Geocode this address and update latitude/longitude.
     */
    public function geocode(): bool
    {
        $geocoder = app(Geocoder::class);
        $result = $geocoder->geocode($this->formatted_address);

        if ($result === null) {
            return false;
        }

        $this->latitude = $result->latitude;
        $this->longitude = $result->longitude;
        $this->saveQuietly();

        return true;
    }
}
