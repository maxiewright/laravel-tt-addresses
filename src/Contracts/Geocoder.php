<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Contracts;

use MaxieWright\TrinidadAndTobagoAddresses\DataTransferObjects\GeocodingResult;
use MaxieWright\TrinidadAndTobagoAddresses\DataTransferObjects\ReverseGeocodingResult;

/**
 * Contract for geocoding services (address to coordinates and reverse).
 */
interface Geocoder
{
    /**
     * Convert an address string to coordinates (forward geocoding).
     */
    public function geocode(string $address): ?GeocodingResult;

    /**
     * Convert coordinates to an address string (reverse geocoding).
     */
    public function reverseGeocode(float $latitude, float $longitude): ?ReverseGeocodingResult;
}
