<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MaxieWright\TrinidadAndTobagoAddresses\Enums\DivisionType;

/**
 * Administrative Division Model
 *
 * Represents administrative divisions in Trinidad and Tobago.
 * Trinidad has 9 Regional Corporations, 3 Boroughs, and 2 City Corporations.
 * Tobago has 1 Ward.
 *
 * @property int $id
 * @property string $name
 * @property DivisionType $type
 * @property string $abbreviation
 * @property string $island
 * @property float|null $latitude
 * @property float|null $longitude
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read string $full_name
 * @property-read array|null $coordinates
 * @property-read \Illuminate\Database\Eloquent\Collection<int, City> $cities
 */
class Division extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'type',
        'abbreviation',
        'island',
        'latitude',
        'longitude',
    ];

    protected $appends = [
        'full_name',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => DivisionType::class,
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Get the table name from config.
     */
    public function getTable(): string
    {
        return config('tt-addresses.tables.divisions', 'tt_divisions');
    }

    /* ───────────────────────────────────────────────────────────
     * Relationships
     * ─────────────────────────────────────────────────────────── */

    /**
     * Get the cities/towns/villages in this division.
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    /* ───────────────────────────────────────────────────────────
     * Scopes
     * ─────────────────────────────────────────────────────────── */

    /**
     * Scope to only Trinidad divisions.
     */
    #[Scope]
    public function trinidad(Builder $query): void
    {
        $query->where('island', 'Trinidad');
    }

    /**
     * Scope to only Tobago.
     */
    #[Scope]
    public function tobago(Builder $query): void
    {
        $query->where('island', 'Tobago');
    }

    /**
     * Scope to filter by division type.
     */
    #[Scope]
    public function ofType(Builder $query, DivisionType $type): void
    {
        $query->where('type', $type->value);
    }

    /**
     * Scope to search by name or abbreviation.
     */
    #[Scope]
    public function search(Builder $query, string $search): void
    {
        $query->where(function (Builder $q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('abbreviation', 'like', "%{$search}%");
        });
    }

    /* ───────────────────────────────────────────────────────────
     * Helpers
     * ─────────────────────────────────────────────────────────── */

    /**
     * Check if this division is in Tobago.
     */
    public function isTobago(): bool
    {
        return $this->island === 'Tobago';
    }

    /**
     * Check if this division is in Trinidad.
     */
    public function isTrinidad(): bool
    {
        return $this->island === 'Trinidad';
    }

    /**
     * Get the full name with type.
     */
    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->name} ({$this->type->label()})",
        );
    }

    /**
     * Get coordinates as an array [latitude, longitude].
     */
    public function coordinates(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->hasCoordinatesData()
                ? ['latitude' => $this->latitude, 'longitude' => $this->longitude]
                : null,
        );
    }

    /**
     * Check if this division has coordinate data.
     */
    public function hasCoordinatesData(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    /**
     * Get the Google Maps URL for this division.
     */
    public function getGoogleMapsUrl(): ?string
    {
        if (! $this->hasCoordinatesData()) {
            return null;
        }

        return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
    }

    /**
     * Get the OpenStreetMap URL for this division.
     */
    public function getOpenStreetMapUrl(): ?string
    {
        if (! $this->hasCoordinatesData()) {
            return null;
        }

        return "https://www.openstreetmap.org/?mlat={$this->latitude}&mlon={$this->longitude}&zoom=12";
    }
}
