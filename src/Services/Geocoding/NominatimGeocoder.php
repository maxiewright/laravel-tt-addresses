<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Services\Geocoding;

use Illuminate\Support\Facades\Http;
use MaxieWright\TrinidadAndTobagoAddresses\Contracts\Geocoder;
use MaxieWright\TrinidadAndTobagoAddresses\DataTransferObjects\GeocodingResult;
use MaxieWright\TrinidadAndTobagoAddresses\DataTransferObjects\ReverseGeocodingResult;

/**
 * OpenStreetMap Nominatim geocoding implementation.
 * Respects usage policy: 1 request per second.
 */
class NominatimGeocoder implements Geocoder
{
    private const GEOCODE_URL = 'https://nominatim.openstreetmap.org/search';

    private const REVERSE_URL = 'https://nominatim.openstreetmap.org/reverse';

    public function __construct(
        private readonly string $userAgent,
    ) {}

    public function geocode(string $address): ?GeocodingResult
    {
        $fullAddress = trim($address);
        if (str_ends_with(strtolower($fullAddress), 'trinidad and tobago') === false) {
            $fullAddress .= ', Trinidad and Tobago';
        }

        $response = Http::withHeaders([
            'User-Agent' => $this->userAgent,
        ])->get(self::GEOCODE_URL, [
            'q' => $fullAddress,
            'format' => 'json',
            'countrycodes' => 'tt',
            'limit' => 1,
        ]);

        $data = $response->json();
        if (! is_array($data) || empty($data)) {
            return null;
        }

        $first = $data[0];
        $lat = (float) ($first['lat'] ?? 0);
        $lng = (float) ($first['lon'] ?? 0);
        $formattedAddress = $first['display_name'] ?? null;

        return new GeocodingResult(
            latitude: $lat,
            longitude: $lng,
            formattedAddress: $formattedAddress,
        );
    }

    public function reverseGeocode(float $latitude, float $longitude): ?ReverseGeocodingResult
    {
        $response = Http::withHeaders([
            'User-Agent' => $this->userAgent,
        ])->get(self::REVERSE_URL, [
            'lat' => $latitude,
            'lon' => $longitude,
            'format' => 'json',
        ]);

        $data = $response->json();
        if (! is_array($data)) {
            return null;
        }

        $formattedAddress = $data['display_name'] ?? '';
        $address = $data['address'] ?? [];
        $street = $address['road'] ?? $address['street'] ?? null;
        $city = $address['city'] ?? $address['town'] ?? $address['village'] ?? $address['municipality'] ?? null;
        $region = $address['state'] ?? $address['county'] ?? null;

        return new ReverseGeocodingResult(
            formattedAddress: $formattedAddress,
            street: $street,
            city: $city,
            region: $region,
        );
    }
}
