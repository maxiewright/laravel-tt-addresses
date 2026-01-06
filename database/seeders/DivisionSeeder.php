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
 * Safe to run multiple times due to updateOrCreate usage.
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
            ],
            [
                'name' => 'Diego Martin',
                'type' => DivisionType::RegionalCorporation,
                'abbreviation' => 'DMN',
                'island' => 'Trinidad',
            ],
            [
                'name' => 'Mayaro/Rio Claro',
                'type' => DivisionType::RegionalCorporation,
                'abbreviation' => 'MRC',
                'island' => 'Trinidad',
            ],
            [
                'name' => 'Penal/Debe',
                'type' => DivisionType::RegionalCorporation,
                'abbreviation' => 'PED',
                'island' => 'Trinidad',
            ],
            [
                'name' => 'Princes Town',
                'type' => DivisionType::RegionalCorporation,
                'abbreviation' => 'PRT',
                'island' => 'Trinidad',
            ],
            [
                'name' => 'Sangre Grande',
                'type' => DivisionType::RegionalCorporation,
                'abbreviation' => 'SGE',
                'island' => 'Trinidad',
            ],
            [
                'name' => 'San Juan/Laventille',
                'type' => DivisionType::RegionalCorporation,
                'abbreviation' => 'SJL',
                'island' => 'Trinidad',
            ],
            [
                'name' => 'Siparia',
                'type' => DivisionType::RegionalCorporation,
                'abbreviation' => 'SIP',
                'island' => 'Trinidad',
            ],
            [
                'name' => 'Tunapuna/Piarco',
                'type' => DivisionType::RegionalCorporation,
                'abbreviation' => 'TUP',
                'island' => 'Trinidad',
            ],

            // ═══════════════════════════════════════════════════════════════
            // Boroughs (Trinidad)
            // ═══════════════════════════════════════════════════════════════
            [
                'name' => 'Arima',
                'type' => DivisionType::Borough,
                'abbreviation' => 'ARI',
                'island' => 'Trinidad',
            ],
            [
                'name' => 'Chaguanas',
                'type' => DivisionType::Borough,
                'abbreviation' => 'CHA',
                'island' => 'Trinidad',
            ],
            [
                'name' => 'Point Fortin',
                'type' => DivisionType::Borough,
                'abbreviation' => 'PTF',
                'island' => 'Trinidad',
            ],

            // ═══════════════════════════════════════════════════════════════
            // City Corporations (Trinidad)
            // ═══════════════════════════════════════════════════════════════
            [
                'name' => 'Port of Spain',
                'type' => DivisionType::CityCorporation,
                'abbreviation' => 'POS',
                'island' => 'Trinidad',
            ],
            [
                'name' => 'San Fernando',
                'type' => DivisionType::CityCorporation,
                'abbreviation' => 'SFO',
                'island' => 'Trinidad',
            ],

            // ═══════════════════════════════════════════════════════════════
            // Ward (Tobago)
            // ═══════════════════════════════════════════════════════════════
            [
                'name' => 'Tobago',
                'type' => DivisionType::Ward,
                'abbreviation' => 'TOB',
                'island' => 'Tobago',
            ],
        ];

        $now = now();

        $divisions = array_map(function ($division) use ($now) {
            return array_merge($division, [
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }, $divisions);

        Division::upsert(
            $divisions,
            ['abbreviation'],
            ['name', 'type', 'island', 'updated_at']
        );
    }
}
