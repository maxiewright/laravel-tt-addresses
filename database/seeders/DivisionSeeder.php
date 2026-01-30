<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders;

use Illuminate\Database\Seeder;
use MaxieWright\TrinidadAndTobagoAddresses\Enums\DivisionType;
use MaxieWright\TrinidadAndTobagoAddresses\Models\Division;

/**
 * Division Seeder
 *
 * Seeds all 15 administrative divisions of Trinidad and Tobago.
 * Includes geographic coordinates (latitude/longitude) for each division.
 * Safe to run multiple times due to upsert usage.
 *
 * Coordinates represent the approximate geographic center or main administrative
 * center of each division.
 */
class DivisionSeeder extends Seeder
{
    /**
     * Seed the Trinidad and Tobago administrative divisions.
     *
     * Trinidad has:
     * - 9 Regional Corporations
     * - 3 Boroughs
     * - 2 City Corporations
     *
     * Tobago has:
     * - 1 Ward (Tobago House of Assembly)
     */
    public function run(): void
    {
        $divisions = [
            // ═══════════════════════════════════════════════════════════════
            // Regional Corporations (Trinidad)
            // ═══════════════════════════════════════════════════════════════
            [
                'name' => 'Couva/Tabaquite/Talparo',
                'type' => DivisionType::RegionalCorporation,
                'abbreviation' => 'CTT',
                'island' => 'Trinidad',
                'latitude' => 10.4220,
                'longitude' => -61.4500,
            ],
            [
                'name' => 'Diego Martin',
                'type' => DivisionType::RegionalCorporation,
                'abbreviation' => 'DMN',
                'island' => 'Trinidad',
                'latitude' => 10.7214,
                'longitude' => -61.5661,
            ],
            [
                'name' => 'Mayaro/Rio Claro',
                'type' => DivisionType::RegionalCorporation,
                'abbreviation' => 'MRC',
                'island' => 'Trinidad',
                'latitude' => 10.3060,
                'longitude' => -61.1760,
            ],
            [
                'name' => 'Penal/Debe',
                'type' => DivisionType::RegionalCorporation,
                'abbreviation' => 'PED',
                'island' => 'Trinidad',
                'latitude' => 10.1700,
                'longitude' => -61.4500,
            ],
            [
                'name' => 'Princes Town',
                'type' => DivisionType::RegionalCorporation,
                'abbreviation' => 'PRT',
                'island' => 'Trinidad',
                'latitude' => 10.2667,
                'longitude' => -61.3833,
            ],
            [
                'name' => 'Sangre Grande',
                'type' => DivisionType::RegionalCorporation,
                'abbreviation' => 'SGE',
                'island' => 'Trinidad',
                'latitude' => 10.5871,
                'longitude' => -61.1301,
            ],
            [
                'name' => 'San Juan/Laventille',
                'type' => DivisionType::RegionalCorporation,
                'abbreviation' => 'SJL',
                'island' => 'Trinidad',
                'latitude' => 10.6490,
                'longitude' => -61.4990,
            ],
            [
                'name' => 'Siparia',
                'type' => DivisionType::RegionalCorporation,
                'abbreviation' => 'SIP',
                'island' => 'Trinidad',
                'latitude' => 10.1333,
                'longitude' => -61.5000,
            ],
            [
                'name' => 'Tunapuna/Piarco',
                'type' => DivisionType::RegionalCorporation,
                'abbreviation' => 'TUP',
                'island' => 'Trinidad',
                'latitude' => 10.6520,
                'longitude' => -61.3890,
            ],

            // ═══════════════════════════════════════════════════════════════
            // Boroughs (Trinidad)
            // ═══════════════════════════════════════════════════════════════
            [
                'name' => 'Arima',
                'type' => DivisionType::Borough,
                'abbreviation' => 'ARI',
                'island' => 'Trinidad',
                'latitude' => 10.6172,
                'longitude' => -61.2744,
            ],
            [
                'name' => 'Chaguanas',
                'type' => DivisionType::Borough,
                'abbreviation' => 'CHA',
                'island' => 'Trinidad',
                'latitude' => 10.5173,
                'longitude' => -61.4113,
            ],
            [
                'name' => 'Point Fortin',
                'type' => DivisionType::Borough,
                'abbreviation' => 'PTF',
                'island' => 'Trinidad',
                'latitude' => 10.1740,
                'longitude' => -61.6840,
            ],

            // ═══════════════════════════════════════════════════════════════
            // City Corporations (Trinidad)
            // ═══════════════════════════════════════════════════════════════
            [
                'name' => 'Port of Spain',
                'type' => DivisionType::CityCorporation,
                'abbreviation' => 'POS',
                'island' => 'Trinidad',
                'latitude' => 10.6711,
                'longitude' => -61.5212,
            ],
            [
                'name' => 'San Fernando',
                'type' => DivisionType::CityCorporation,
                'abbreviation' => 'SFO',
                'island' => 'Trinidad',
                'latitude' => 10.2833,
                'longitude' => -61.4667,
            ],

            // ═══════════════════════════════════════════════════════════════
            // Ward (Tobago)
            // ═══════════════════════════════════════════════════════════════
            [
                'name' => 'Tobago',
                'type' => DivisionType::Ward,
                'abbreviation' => 'TOB',
                'island' => 'Tobago',
                'latitude' => 11.1833,
                'longitude' => -60.7333,
            ],
        ];

        $now = now();

        $divisions = array_map(function ($division) use ($now) {
            return array_merge($division, [
                'type' => $division['type']->value,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }, $divisions);

        Division::upsert(
            $divisions,
            ['abbreviation'],
            ['name', 'type', 'island', 'latitude', 'longitude', 'updated_at']
        );
    }
}
