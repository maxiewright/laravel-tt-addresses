<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Services\Geocoding;

use MaxieWright\TrinidadAndTobagoAddresses\Contracts\Geocoder;
use MaxieWright\TrinidadAndTobagoAddresses\DataTransferObjects\GeocodingResult;
use MaxieWright\TrinidadAndTobagoAddresses\DataTransferObjects\ReverseGeocodingResult;

/**
 * Null geocoder for testing or when geocoding is disabled.
 */
class NullGeocoder implements Geocoder
{
    public function geocode(string $address): ?GeocodingResult
    {
        return null;
    }

    public function reverseGeocode(float $latitude, float $longitude): ?ReverseGeocodingResult
    {
        return null;
    }
}
