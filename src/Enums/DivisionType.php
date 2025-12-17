<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Enums;

/**
 * Division Type Enum
 *
 * Represents the administrative division types in Trinidad and Tobago.
 * Includes Filament support for labels and colors.
 */
enum DivisionType: string
{
    case RegionalCorporation = 'regional_corporation';
    case Borough = 'borough';
    case CityCorporation = 'city_corporation';
    case Ward = 'ward';

    /**
     * Get the human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::RegionalCorporation => 'Regional Corporation',
            self::Borough => 'Borough',
            self::CityCorporation => 'City Corporation',
            self::Ward => 'Ward',
        };
    }

    /**
     * Get the island this division type is typically found on.
     */
    public function island(): string
    {
        return match ($this) {
            self::Ward => 'Tobago',
            default => 'Trinidad',
        };
    }

    /**
     * Filament HasLabel support.
     */
    public function getLabel(): string
    {
        return $this->label();
    }

    /**
     * Filament HasColor support.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::RegionalCorporation => 'info',
            self::Borough => 'success',
            self::CityCorporation => 'warning',
            self::Ward => 'primary',
        };
    }

    /**
     * Get all division types for a specific island.
     *
     * @return array<self>
     */
    public static function forIsland(string $island): array
    {
        return match (strtolower($island)) {
            'tobago' => [self::Ward],
            'trinidad' => [self::RegionalCorporation, self::Borough, self::CityCorporation],
            default => self::cases(),
        };
    }
}
