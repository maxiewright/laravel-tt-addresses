<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * City/Town/Village Model
 *
 * Represents cities, towns, and villages in Trinidad and Tobago.
 *
 * @property int $id
 * @property int $division_id
 * @property string $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read string $full_location
 * @property-read string $island
 * @property-read Division $division
 */
class City extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'division_id',
        'name',
    ];

    protected $appends = [
        'full_location',
        'island',
    ];

    /**
     * Get the table name from config.
     */
    public function getTable(): string
    {
        return config('trinidad-and-tobago-addresses.tables.cities', 'tt_cities');
    }

    /* ───────────────────────────────────────────────────────────
     * Relationships
     * ─────────────────────────────────────────────────────────── */

    /**
     * Get the division this city belongs to.
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    /* ───────────────────────────────────────────────────────────
     * Scopes
     * ─────────────────────────────────────────────────────────── */

    /**
     * Scope to only Trinidad cities.
     */
    #[Scope]
    public function trinidad(Builder $query): void
    {
        $query->whereHas('division', function (Builder $q) {
            $q->where('island', 'Trinidad');
        });
    }

    /**
     * Scope to only Tobago cities.
     */
    #[Scope]
    public function tobago(Builder $query): void
    {
        $query->whereHas('division', function (Builder $q) {
            $q->where('island', 'Tobago');
        });
    }

    /**
     * Scope to filter by division.
     */
    #[Scope]
    public function inDivision(Builder $query, int|Division $division): void
    {
        $divisionId = $division instanceof Division ? $division->id : $division;

        $query->where('division_id', $divisionId);
    }

    /**
     * Scope to search by name.
     */
    #[Scope]
    public function search(Builder $query, string $search): void
    {
        $query->where('name', 'like', "%{$search}%");
    }

    /* ───────────────────────────────────────────────────────────
     * Accessors
     * ─────────────────────────────────────────────────────────── */

    /**
     * Get the full location string: "City Name, Division Name"
     */
    public function fullLocation(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->name}, {$this->division->name}",
        );
    }

    /**
     * Get the island via the division.
     */
    public function island(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->division->island,
        );
    }

    /**
     * Check if this city is in Tobago.
     */
    public function isTobago(): bool
    {
        return $this->division->isTobago();
    }

    /**
     * Check if this city is in Trinidad.
     */
    public function isTrinidad(): bool
    {
        return $this->division->isTrinidad();
    }
}
