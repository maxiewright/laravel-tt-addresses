<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\DataTransferObjects;

/**
 * Result of a forward geocoding request (address to coordinates).
 */
readonly class GeocodingResult
{
    public function __construct(
        public float $latitude,
        public float $longitude,
        public ?string $formattedAddress = null,
        public ?float $accuracy = null,
    ) {}

    /**
     * Convert to array for storage or serialization.
     *
     * @return array{latitude: float, longitude: float, formatted_address: string|null, accuracy: float|null}
     */
    public function toArray(): array
    {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'formatted_address' => $this->formattedAddress,
            'accuracy' => $this->accuracy,
        ];
    }
}
