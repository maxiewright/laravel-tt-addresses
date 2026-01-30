<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\DataTransferObjects;

/**
 * Result of a reverse geocoding request (coordinates to address).
 */
readonly class ReverseGeocodingResult
{
    public function __construct(
        public string $formattedAddress,
        public ?string $street = null,
        public ?string $city = null,
        public ?string $region = null,
    ) {}
}
