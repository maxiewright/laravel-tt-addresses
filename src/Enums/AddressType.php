<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Enums;

/**
 * Address Type Enum
 *
 * Represents the type of address (home, work, billing, etc.).
 * Includes Filament support for labels and colors.
 */
enum AddressType: string
{
    case Home = 'home';
    case Work = 'work';
    case Billing = 'billing';
    case Shipping = 'shipping';
    case Other = 'other';

    /**
     * Get the human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::Home => 'Home',
            self::Work => 'Work',
            self::Billing => 'Billing',
            self::Shipping => 'Shipping',
            self::Other => 'Other',
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
            self::Home => 'success',
            self::Work => 'info',
            self::Billing => 'warning',
            self::Shipping => 'primary',
            self::Other => 'gray',
        };
    }
}
