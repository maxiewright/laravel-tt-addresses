<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Enums;

use Filament\Support\Contracts\HasLabel;

enum ServiceRadius: int implements HasLabel
{
    case WALKING = 2;      // 2km - walking distance
    case DRIVING = 10;     // 10km - driving distance
    case REGIONAL = 25;    // 25km - regional coverage
    case ISLAND_WIDE = 100; // Full island coverage

    public function label(): string
    {
        return match ($this) {
            self::WALKING => '2 km (Walking Distance)',
            self::DRIVING => '10 km (Driving Distance)',
            self::REGIONAL => '25 km (Regional)',
            self::ISLAND_WIDE => 'Island Wide',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::WALKING => 'Services you can walk to',
            self::DRIVING => 'Short drive, local area',
            self::REGIONAL => 'Extended regional coverage',
            self::ISLAND_WIDE => 'Anywhere on the island',
        };
    }

    public function kilometers(): int
    {
        return $this->value;
    }
}
