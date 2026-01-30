<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Services\Geocoding;

use Illuminate\Support\Facades\Http;
use MaxieWright\TrinidadAndTobagoAddresses\Contracts\Geocoder;
use MaxieWright\TrinidadAndTobagoAddresses\DataTransferObjects\GeocodingResult;
use MaxieWright\TrinidadAndTobagoAddresses\DataTransferObjects\ReverseGeocodingResult;

/**
 * Google Maps Geocoding API implementation.
 */
class GoogleMapsGeocoder implements Geocoder
{
    private const GEOCODE_URL = 'https://maps.googleapis.com/maps/api/geocode/json';

    public function __construct(
        private readonly string $apiKey,
    ) {}

    public function geocode(string $address): ?GeocodingResult
    {
        $fullAddress = trim($address);
        if (str_ends_with(strtolower($fullAddress), 'trinidad and tobago') === false) {
            $fullAddress .= ', Trinidad and Tobago';
        }

        $response = Http::get(self::GEOCODE_URL, [
            'address' => $fullAddress,
            'key' => $this->apiKey,
            'components' => 'country:TT',
        ]);

        $data = $response->json();
        if ($data['status'] !== 'OK' || empty($data['results'])) {
            return null;
        }

        $location = $data['results'][0]['geometry']['location'];
        $formattedAddress = $data['results'][0]['formatted_address'] ?? null;

        return new GeocodingResult(
            latitude: (float) $location['lat'],
            longitude: (float) $location['lng'],
            formattedAddress: $formattedAddress,
        );
    }

    public function reverseGeocode(float $latitude, float $longitude): ?ReverseGeocodingResult
    {
        $response = Http::get(self::GEOCODE_URL, [
            'latlng' => "{$latitude},{$longitude}",
            'key' => $this->apiKey,
        ]);

        $data = $response->json();
        if ($data['status'] !== 'OK' || empty($data['results'])) {
            return null;
        }

        $result = $data['results'][0];
        $formattedAddress = $result['formatted_address'] ?? '';
        $street = null;
        $city = null;
        $region = null;

        foreach ($result['address_components'] ?? [] as $component) {
            if (in_array('street_number', $component['types']) || in_array('route', $component['types'])) {
                $street = ($street ?? '').($component['long_name'] ?? '').' ';
            }
            if (in_array('locality', $component['types'])) {
                $city = $component['long_name'] ?? null;
            }
            if (in_array('administrative_area_level_1', $component['types'])) {
                $region = $component['long_name'] ?? null;
            }
        }

        $street = $street !== null ? trim($street) : null;

        return new ReverseGeocodingResult(
            formattedAddress: $formattedAddress,
            street: $street,
            city: $city,
            region: $region,
        );
    }
}
