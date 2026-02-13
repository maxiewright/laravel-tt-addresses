<?php

// config for MaxieWright/TrinidadAndTobagoAddresses
return [
    /*
    |--------------------------------------------------------------------------
    | Table Names
    |--------------------------------------------------------------------------
    |
    | Customise table names if they conflict with existing tables in your
    | application. The default uses a 'tt_' prefix to avoid collisions.
    |
    */
    'tables' => [
        'divisions' => 'tt_divisions',
        'cities' => 'tt_cities',
        'addresses' => 'tt_addresses',
    ],

    /*
    |--------------------------------------------------------------------------
    | Geocoding
    |--------------------------------------------------------------------------
    |
    | Configure geocoding (address to coordinates). The Geocoder can be used
    | standalone via the Geocoder facade or with the Address model.
    |
    | After publishing, you may use env(): TT_ADDRESSES_GEOCODING_ENABLED,
    | TT_ADDRESSES_GEOCODING_DRIVER, TT_ADDRESSES_GEOCODING_QUEUE,
    | GOOGLE_MAPS_API_KEY, APP_NAME (for Nominatim user_agent).
    |
    */
    'geocoding' => [
        'enabled' => false,
        'driver' => 'null',
        'queue' => true,
        'cache' => [
            'enabled' => true,
            'ttl' => 60 * 60 * 24 * 30, // 30 days
        ],
        'drivers' => [
            'google' => [
                'api_key' => '',
            ],
            'nominatim' => [
                'user_agent' => 'Laravel',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Country Code
    |--------------------------------------------------------------------------
    |
    | ISO 3166-1 alpha-2 country code for Trinidad and Tobago.
    |
    */
    'country_code' => 'TT',

    /*
    |--------------------------------------------------------------------------
    | Country Name
    |--------------------------------------------------------------------------
    |
    | Full country name used in formatted addresses.
    |
    */
    'country_name' => 'Trinidad and Tobago',

    /*
    |--------------------------------------------------------------------------
    | Search Configuration
    |--------------------------------------------------------------------------
    */
    'search' => [
        'autocomplete_limit' => 10,
        'cache_ttl' => 900, // 15 minutes
        'popular_cities_cache_ttl' => 3600, // 1 hour
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Configuration
    |--------------------------------------------------------------------------
    */
    'performance' => [
        'enable_query_caching' => env('TT_ADDRESSES_QUERY_CACHE', true),
        'max_search_radius_km' => 100,
    ],

    /*
    |--------------------------------------------------------------------------
    | Popular Cities
    |--------------------------------------------------------------------------
    |
    | List of major cities that should appear first in autocomplete/suggestions
    |
    */
    'popular_cities' => [
        'Port of Spain',
        'San Fernando',
        'Chaguanas',
        'Arima',
        'Point Fortin',
        'Couva',
        'Sangre Grande',
        'Tunapuna',
        'Marabella',
        'St. Joseph',
        'Diego Martin',
        'Penal',
        'Rio Claro',
        'Princes Town',
        'Siparia',
    ],
];
