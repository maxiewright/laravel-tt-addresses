<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MaxieWright\TrinidadAndTobagoAddresses\Enums\SearchRadius;

/**
 * City/Town/Village Model
 *
 * @property int $id
 * @property int $division_id
 * @property string $name
 * @property float|null $latitude
 * @property float|null $longitude
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read string $full_location
 * @property-read string $island
 * @property-read array|null $coordinates
 * @property-read Division $division
 */
class City extends Model
{
    protected $fillable = [
        'division_id',
        'name',
        'latitude',
        'longitude',
    ];

    protected $appends = [
        'full_location',
        'island',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function getTable(): string
    {
        return config('tt-addresses.tables.cities', 'tt_cities');
    }

    /* ───────────────────────────────────────────────────────────
     * Relationships
     * ─────────────────────────────────────────────────────────── */

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    /* ───────────────────────────────────────────────────────────
     * Scopes
     * ─────────────────────────────────────────────────────────── */

    #[Scope]
    public function trinidad(Builder $query): void
    {
        $query->whereHas('division', fn (Builder $q) => $q->where('island', 'Trinidad'));
    }

    #[Scope]
    public function tobago(Builder $query): void
    {
        $query->whereHas('division', fn (Builder $q) => $q->where('island', 'Tobago'));
    }

    #[Scope]
    public function inDivision(Builder $query, int|Division $division): void
    {
        $divisionId = $division instanceof Division ? $division->id : $division;
        $query->where('division_id', $divisionId);
    }

    #[Scope]
    public function search(Builder $query, string $search): void
    {
        $query->where('name', 'like', "%{$search}%");
    }

    #[Scope]
    public function hasCoordinates(Builder $query): void
    {
        $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    /**
     * Scope to find cities within a radius (in kilometers) of a point.
     * Uses the Haversine formula approximation for performance.
     */
    #[Scope]
    public function withinRadius(Builder $query, float $latitude, float $longitude, float $radiusKm): void
    {
        // Approximate bounding box for initial filter (faster)
        $latDelta = $radiusKm / 111.32; // 1 degree latitude ≈ 111.32 km
        $lonDelta = $radiusKm / (111.32 * cos(deg2rad($latitude)));

        $query->whereBetween('latitude', [$latitude - $latDelta, $latitude + $latDelta])
            ->whereBetween('longitude', [$longitude - $lonDelta, $longitude + $lonDelta])
            ->whereRaw(
                '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?',
                [$latitude, $longitude, $latitude, $radiusKm]
            );
    }

    /**
     * Scope to order cities by distance from a point (nearest first).
     */
    #[Scope]
    public function orderByDistanceFrom(Builder $query, float $latitude, float $longitude, string $direction = 'asc'): void
    {
        $query->selectRaw(
            '*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
            [$latitude, $longitude, $latitude]
        )->orderBy('distance', $direction);
    }

    /**
     * Scope for autocomplete/typeahead search with optimized results.
     */
    #[Scope]
    public function autocomplete(Builder $query, string $search, int $limit = 10): void
    {
        $query->where(function (Builder $q) use ($search) {
            $q->where('name', 'like', $search.'%')  // Prefix match is faster
                ->orWhere('name', 'like', '% '.$search.'%');  // Word boundary match
        })
            ->hasCoordinates()
            ->orderByRaw('
            CASE
                WHEN name LIKE ? THEN 1
                WHEN name LIKE ? THEN 2
                ELSE 3
            END, name
        ', [$search.'%', '% '.$search.'%'])
            ->limit($limit);
    }

    /**
     * Scope for popular/major cities (for default suggestions).
     */
    #[Scope]
    public function popular(Builder $query): void
    {
        $popularCities = config('tt-addresses.popular_cities', [
            'Port of Spain', 'San Fernando', 'Chaguanas', 'Arima', 'Point Fortin',
            'Couva', 'Sangre Grande', 'Tunapuna', 'Marabella', 'St. Joseph',
        ]);

        // Portable ordering for SQLite/MySQL/Postgres using CASE expression
        $caseParts = [];
        $bindings = [];
        foreach ($popularCities as $index => $cityName) {
            $caseParts[] = 'WHEN name = ? THEN '.($index + 1);
            $bindings[] = $cityName;
        }
        $caseSql = 'CASE '.implode(' ', $caseParts).' ELSE 999 END';

        $query->whereIn('name', $popularCities)
            ->hasCoordinates()
            ->orderByRaw($caseSql, $bindings);
    }

    /**
     * Scope cities by island with distance from point.
     */
    #[Scope]
    public function byIslandDistance(Builder $query, float $latitude, float $longitude, ?string $island = null): void
    {
        if ($island) {
            $query->whereHas('division', fn (Builder $q) => $q->where('island', $island));
        }

        $query->orderByDistanceFrom($latitude, $longitude);
    }

    /**
     * Scope cities within a predefined search radius.
     */
    #[Scope]
    public function withinSearchRadius(Builder $query, float $latitude, float $longitude, SearchRadius $radius): void
    {
        $query->withinRadius($latitude, $longitude, $radius->kilometers());
    }

    /* ───────────────────────────────────────────────────────────
     * Accessors
     * ─────────────────────────────────────────────────────────── */

    public function fullLocation(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->name}, {$this->division->name}",
        );
    }

    public function island(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->division->island,
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

    /* ───────────────────────────────────────────────────────────
     * Helper Methods
     * ─────────────────────────────────────────────────────────── */

    public function isTobago(): bool
    {
        return $this->division->isTobago();
    }

    public function isTrinidad(): bool
    {
        return $this->division->isTrinidad();
    }

    /**
     * Check if this city has coordinate data.
     */
    public function hasCoordinatesData(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    /**
     * Convert to search result format for APIs.
     */
    public function toSearchResult(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'division' => $this->division->name,
            'full_location' => $this->full_location,
            'island' => $this->island,
            'coordinates' => $this->coordinates,
            'division_type' => $this->division->type->label(),
        ];
    }

    /**
     * Convert to autocomplete option format.
     */
    public function toAutocompleteOption(): array
    {
        return [
            'value' => $this->id,
            'label' => $this->name,
            'description' => $this->division->name.', '.$this->island,
            'coordinates' => $this->coordinates,
        ];
    }

    /**
     * Get suggested cities near a given location.
     */
    public static function getSuggestedNearbyCities(float $latitude, float $longitude, int $maxCities = 10): Collection
    {
        return static::query()
            ->hasCoordinates()
            ->withinRadius($latitude, $longitude, 50) // 50km max
            ->orderByDistanceFrom($latitude, $longitude)
            ->limit($maxCities)
            ->get();
    }

    /**
     * Get popular cities with caching.
     */
    public static function getPopularCached(int $ttl = 3600): Collection
    {
        return cache()->remember('tt_addresses.popular_cities', $ttl, function () {
            return static::query()->popular()->get();
        });
    }

    /**
     * Calculate distance to another city or coordinates (in kilometers).
     */
    public function distanceTo(City|array $target): ?float
    {
        if (! $this->hasCoordinatesData()) {
            return null;
        }

        if ($target instanceof City) {
            if (! $target->hasCoordinatesData()) {
                return null;
            }
            $targetLat = $target->latitude;
            $targetLon = $target->longitude;
        } else {
            $targetLat = $target['latitude'] ?? $target[0] ?? null;
            $targetLon = $target['longitude'] ?? $target[1] ?? null;
        }

        if ($targetLat === null || $targetLon === null) {
            return null;
        }

        return $this->haversineDistance($this->latitude, $this->longitude, $targetLat, $targetLon);
    }

    /**
     * Get the Google Maps URL for this city.
     */
    public function getGoogleMapsUrl(): ?string
    {
        if (! $this->hasCoordinatesData()) {
            return null;
        }

        return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
    }

    /**
     * Get the OpenStreetMap URL for this city.
     */
    public function getOpenStreetMapUrl(): ?string
    {
        if (! $this->hasCoordinatesData()) {
            return null;
        }

        return "https://www.openstreetmap.org/?mlat={$this->latitude}&mlon={$this->longitude}&zoom=15";
    }

    /**
     * Calculate distance using Haversine formula.
     */
    protected function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // km

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) ** 2 +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) ** 2;

        $c = 2 * asin(sqrt($a));

        return $earthRadius * $c;
    }

    /**
     * Find the nearest city to given coordinates.
     *
     * @return City|Collection<int, City>|null Returns null if no cities have coordinates and limit is 1
     */
    public static function findNearest(float $latitude, float $longitude, ?int $limit = 1): City|Collection|null
    {
        $query = static::query()
            ->hasCoordinates()
            ->orderByDistanceFrom($latitude, $longitude);

        return $limit === 1 ? $query->first() : $query->limit($limit)->get();
    }
}
