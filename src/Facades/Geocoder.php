<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Facades;

use Illuminate\Support\Facades\Facade;
use MaxieWright\TrinidadAndTobagoAddresses\Contracts\Geocoder as GeocoderContract;
use MaxieWright\TrinidadAndTobagoAddresses\DataTransferObjects\GeocodingResult;
use MaxieWright\TrinidadAndTobagoAddresses\DataTransferObjects\ReverseGeocodingResult;

/**
 * @method static GeocodingResult|null geocode(string $address)
 * @method static ReverseGeocodingResult|null reverseGeocode(float $latitude, float $longitude)
 *
 * @see \MaxieWright\TrinidadAndTobagoAddresses\Contracts\Geocoder
 */
class Geocoder extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return GeocoderContract::class;
    }
}
