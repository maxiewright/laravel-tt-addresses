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
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read string $full_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, City> $cities
 */
class Division extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'abbreviation',
        'island',
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
    ];

    /**
     * Get the table name from config.
     */
    public function getTable(): string
    {
        return config('trinidad-and-tobago-addresses.tables.divisions', 'tt_divisions');
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
}
