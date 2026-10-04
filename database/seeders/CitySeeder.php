<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders;

use Illuminate\Database\Seeder;
use MaxieWright\TrinidadAndTobagoAddresses\Models\City;
use MaxieWright\TrinidadAndTobagoAddresses\Models\Division;
use RuntimeException;

/**
 * City Seeder
 *
 * Seeds 500+ cities, towns, and villages across Trinidad and Tobago.
 * Includes geographic coordinates (latitude/longitude) for each city.
 * Safe to run multiple times due to upsert usage.
 *
 * Coordinates represent the approximate geographic center of each city/town/village.
 */
class CitySeeder extends Seeder
{
    /**
     * Seed the Trinidad and Tobago cities/towns/villages.
     *
     * Each city names its division by abbreviation, independent of database IDs.
     */
    public function run(): void
    {
        $cities = $this->getCities();
        $divisionIds = Division::query()->pluck('id', 'abbreviation')->all();
        $now = now();

        $cities = array_map(function ($city) use ($divisionIds, $now) {
            $abbreviation = $this->divisionAbbreviation($city);

            if (! isset($divisionIds[$abbreviation])) {
                throw new RuntimeException("Division {$abbreviation} is missing. Run DivisionSeeder first.");
            }

            unset($city['division']);

            return array_merge($city, [
                'division_id' => $divisionIds[$abbreviation],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }, $cities);

        foreach (array_chunk($cities, 500) as $chunk) {
            City::upsert(
                $chunk,
                ['division_id', 'name'],
                ['latitude', 'longitude', 'updated_at']
            );
        }
    }

    /** @param array<string, mixed> $city */
    private function divisionAbbreviation(array $city): string
    {
        if (! isset($city['division']) || ! is_string($city['division'])) {
            throw new RuntimeException('CitySeeder entries must use a division abbreviation in the division field; numeric division_id entries are no longer supported.');
        }

        return $city['division'];
    }

    /**
     * Get all cities organised alphabetically.
     *
     * @return array<int, array{name: string, division: string, latitude: float, longitude: float}>
     */
    protected function getCities(): array
    {
        return [
            // ═══════════════════════════════════════════════════════════════
            // A
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Adelphi', 'division' => 'TOB', 'latitude' => 11.2167, 'longitude' => -60.6333],
            ['name' => 'Adventure', 'division' => 'TOB', 'latitude' => 11.2500, 'longitude' => -60.5667],
            ['name' => 'Agostini', 'division' => 'CTT', 'latitude' => 10.4167, 'longitude' => -61.3500],
            ['name' => 'Anse Fourmi', 'division' => 'TOB', 'latitude' => 11.3167, 'longitude' => -60.5500],
            ['name' => 'Anse Noire', 'division' => 'SGE', 'latitude' => 10.7500, 'longitude' => -61.0167],
            ['name' => 'Arden', 'division' => 'TOB', 'latitude' => 11.2333, 'longitude' => -60.6167],
            ['name' => 'Arima', 'division' => 'ARI', 'latitude' => 10.6172, 'longitude' => -61.2744],
            ['name' => 'Arnos Vale', 'division' => 'TOB', 'latitude' => 11.2167, 'longitude' => -60.7833],
            ['name' => 'Arouca', 'division' => 'TUP', 'latitude' => 10.6333, 'longitude' => -61.3333],
            ['name' => 'Arundel', 'division' => 'TUP', 'latitude' => 10.6500, 'longitude' => -61.3500],
            ['name' => 'Auchenskeoch', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7167],
            ['name' => 'Avocat', 'division' => 'SIP', 'latitude' => 10.1833, 'longitude' => -61.4500],

            // ═══════════════════════════════════════════════════════════════
            // B
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Bacolet', 'division' => 'TOB', 'latitude' => 11.1833, 'longitude' => -60.7167],
            ['name' => 'Bakhen', 'division' => 'PED', 'latitude' => 10.1667, 'longitude' => -61.4333],
            ['name' => 'Balmain', 'division' => 'CTT', 'latitude' => 10.4833, 'longitude' => -61.4167],
            ['name' => 'Balmain Village', 'division' => 'CTT', 'latitude' => 10.4833, 'longitude' => -61.4167],
            ['name' => 'Bamboo', 'division' => 'SIP', 'latitude' => 10.1667, 'longitude' => -61.5000],
            ['name' => "Bande-de-l'Est", 'division' => 'MRC', 'latitude' => 10.2833, 'longitude' => -61.0500],
            ['name' => 'Barataria', 'division' => 'SJL', 'latitude' => 10.6500, 'longitude' => -61.4833],
            ['name' => 'Barrackpore', 'division' => 'PED', 'latitude' => 10.2500, 'longitude' => -61.4333],
            ['name' => 'Barrackpore Settlement', 'division' => 'PED', 'latitude' => 10.2500, 'longitude' => -61.4333],
            ['name' => 'Basse Terre', 'division' => 'PRT', 'latitude' => 10.2667, 'longitude' => -61.3500],
            ['name' => 'Basseterre', 'division' => 'PRT', 'latitude' => 10.2667, 'longitude' => -61.3500],
            ['name' => 'Basterhall', 'division' => 'CTT', 'latitude' => 10.4333, 'longitude' => -61.4000],
            ['name' => 'Bayshore', 'division' => 'DMN', 'latitude' => 10.7167, 'longitude' => -61.5500],
            ['name' => 'Belle Garden', 'division' => 'TOB', 'latitude' => 11.2667, 'longitude' => -60.5500],
            ['name' => 'Belle Vue', 'division' => 'TUP', 'latitude' => 10.6500, 'longitude' => -61.3833],
            ['name' => 'Belmont', 'division' => 'POS', 'latitude' => 10.6667, 'longitude' => -61.5000],
            ['name' => 'Belmont', 'division' => 'TOB', 'latitude' => 11.1833, 'longitude' => -60.7333],
            ['name' => 'Bethel', 'division' => 'TOB', 'latitude' => 11.2333, 'longitude' => -60.6833],
            ['name' => 'Biche', 'division' => 'MRC', 'latitude' => 10.4333, 'longitude' => -61.1167],
            ['name' => 'Biche Village', 'division' => 'MRC', 'latitude' => 10.4333, 'longitude' => -61.1167],
            ['name' => 'Black Rock', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7833],
            ['name' => 'Blanchisseuse', 'division' => 'TUP', 'latitude' => 10.7833, 'longitude' => -61.3000],
            ['name' => 'Blue Basin', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Boissiere', 'division' => 'POS', 'latitude' => 10.6833, 'longitude' => -61.5333],
            ['name' => 'Bon Accord', 'division' => 'TOB', 'latitude' => 11.1667, 'longitude' => -60.8167],
            ['name' => 'Bonasse', 'division' => 'SIP', 'latitude' => 10.0833, 'longitude' => -61.5000],
            ['name' => 'Bonne Aventure', 'division' => 'CTT', 'latitude' => 10.4667, 'longitude' => -61.4333],
            ['name' => 'Bonne Terre', 'division' => 'PRT', 'latitude' => 10.2500, 'longitude' => -61.3667],
            ['name' => 'Brasso', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4000],
            ['name' => 'Brasso Caparo Village', 'division' => 'CTT', 'latitude' => 10.4667, 'longitude' => -61.4167],
            ['name' => 'Brasso Piedra', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.3833],
            ['name' => 'Brasso Seco', 'division' => 'TUP', 'latitude' => 10.7500, 'longitude' => -61.2500],
            ['name' => 'Brasso Venado', 'division' => 'CTT', 'latitude' => 10.4333, 'longitude' => -61.3667],
            ['name' => 'Brasso Village', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4000],
            ['name' => 'Brazil', 'division' => 'CTT', 'latitude' => 10.4667, 'longitude' => -61.4500],
            ['name' => 'Brickfield', 'division' => 'CTT', 'latitude' => 10.4833, 'longitude' => -61.4000],
            ['name' => 'Brighton', 'division' => 'SIP', 'latitude' => 10.2333, 'longitude' => -61.6333],
            ['name' => 'Bronte', 'division' => 'PED', 'latitude' => 10.1833, 'longitude' => -61.4333],
            ['name' => 'Buccoo', 'division' => 'TOB', 'latitude' => 11.1833, 'longitude' => -60.8167],
            ['name' => 'Buen Intento', 'division' => 'PRT', 'latitude' => 10.2500, 'longitude' => -61.3833],
            ['name' => 'Buenos Aires', 'division' => 'SIP', 'latitude' => 10.1500, 'longitude' => -61.4667],
            ['name' => 'Busy Corner', 'division' => 'PRT', 'latitude' => 10.2667, 'longitude' => -61.3667],
            ['name' => 'Butler', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4333],

            // ═══════════════════════════════════════════════════════════════
            // C
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Cacandee Settlement', 'division' => 'CHA', 'latitude' => 10.5167, 'longitude' => -61.4000],
            ['name' => 'Caigual', 'division' => 'SGE', 'latitude' => 10.5667, 'longitude' => -61.1333],
            ['name' => 'Calcutta Settlement', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4500],
            ['name' => 'Calder Hall', 'division' => 'TOB', 'latitude' => 11.1833, 'longitude' => -60.7500],
            ['name' => 'California', 'division' => 'CTT', 'latitude' => 10.4167, 'longitude' => -61.4333],
            ['name' => 'California Village', 'division' => 'CTT', 'latitude' => 10.4167, 'longitude' => -61.4333],
            ['name' => 'Cambleton', 'division' => 'TOB', 'latitude' => 11.2167, 'longitude' => -60.6500],
            ['name' => 'Cameron', 'division' => 'DMN', 'latitude' => 10.7167, 'longitude' => -61.5833],
            ['name' => 'Campbeltown', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.6833],
            ['name' => 'Canaan', 'division' => 'PED', 'latitude' => 10.1667, 'longitude' => -61.4500],
            ['name' => 'Canaan', 'division' => 'TOB', 'latitude' => 11.1667, 'longitude' => -60.8000],
            ['name' => 'Cantaro', 'division' => 'SJL', 'latitude' => 10.6500, 'longitude' => -61.4667],
            ['name' => 'Caparo', 'division' => 'CTT', 'latitude' => 10.4833, 'longitude' => -61.3500],
            ['name' => 'Cape-de-Ville', 'division' => 'PTF', 'latitude' => 10.1667, 'longitude' => -61.6667],
            ['name' => 'Carapichaima', 'division' => 'CTT', 'latitude' => 10.4667, 'longitude' => -61.4333],
            ['name' => 'Carapo', 'division' => 'TUP', 'latitude' => 10.6000, 'longitude' => -61.3167],
            ['name' => 'Caratal', 'division' => 'CTT', 'latitude' => 10.4333, 'longitude' => -61.4167],
            ['name' => 'Cardiff', 'division' => 'TOB', 'latitude' => 11.2500, 'longitude' => -60.6000],
            ['name' => 'Carenage', 'division' => 'DMN', 'latitude' => 10.7000, 'longitude' => -61.5667],
            ['name' => 'Carmichael', 'division' => 'SGE', 'latitude' => 10.5500, 'longitude' => -61.1000],
            ['name' => 'Carnbee Village', 'division' => 'TOB', 'latitude' => 11.1833, 'longitude' => -60.7667],
            ['name' => 'Carolina', 'division' => 'CTT', 'latitude' => 10.4833, 'longitude' => -61.4333],
            ['name' => 'Caroni', 'division' => 'TUP', 'latitude' => 10.6000, 'longitude' => -61.4000],
            ['name' => 'Castara', 'division' => 'TOB', 'latitude' => 11.2667, 'longitude' => -60.7000],
            ['name' => 'Caura', 'division' => 'TUP', 'latitude' => 10.7000, 'longitude' => -61.3667],
            ['name' => 'Centeno', 'division' => 'TUP', 'latitude' => 10.5833, 'longitude' => -61.2500],
            ['name' => 'Chaguanas', 'division' => 'CHA', 'latitude' => 10.5173, 'longitude' => -61.4113],
            ['name' => 'Chaguaramas', 'division' => 'DMN', 'latitude' => 10.6833, 'longitude' => -61.6333],
            ['name' => 'Charlotteville', 'division' => 'TOB', 'latitude' => 11.3167, 'longitude' => -60.5500],
            ['name' => 'Charuma', 'division' => 'MRC', 'latitude' => 10.3500, 'longitude' => -61.1500],
            ['name' => 'Chase', 'division' => 'CTT', 'latitude' => 10.5167, 'longitude' => -61.4333],
            ['name' => 'Chase Village', 'division' => 'CTT', 'latitude' => 10.5167, 'longitude' => -61.4333],
            ['name' => 'Chatham', 'division' => 'SIP', 'latitude' => 10.1167, 'longitude' => -61.4667],
            ['name' => 'Cheeyou', 'division' => 'SGE', 'latitude' => 10.5833, 'longitude' => -61.1167],
            ['name' => 'Chickland', 'division' => 'CTT', 'latitude' => 10.4667, 'longitude' => -61.4000],
            ['name' => 'Chin Chin Savanna Village', 'division' => 'CTT', 'latitude' => 10.4833, 'longitude' => -61.3833],
            ['name' => 'Cinnamon Hill', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7000],
            ['name' => 'Cipero-Sainte Croix', 'division' => 'PED', 'latitude' => 10.2167, 'longitude' => -61.4500],
            ['name' => 'City of Port-of-Spain', 'division' => 'POS', 'latitude' => 10.6711, 'longitude' => -61.5212],
            ['name' => 'Claxton Bay', 'division' => 'CTT', 'latitude' => 10.3667, 'longitude' => -61.4667],
            ['name' => 'Cochrane', 'division' => 'PTF', 'latitude' => 10.1833, 'longitude' => -61.6833],
            ['name' => 'Coco', 'division' => 'DMN', 'latitude' => 10.7167, 'longitude' => -61.5667],
            ['name' => 'Cocorite', 'division' => 'DMN', 'latitude' => 10.6833, 'longitude' => -61.5500],
            ['name' => 'Coffee', 'division' => 'SFO', 'latitude' => 10.2667, 'longitude' => -61.4500],
            ['name' => 'Colconda', 'division' => 'PED', 'latitude' => 10.1500, 'longitude' => -61.4500],
            ['name' => 'Comparo', 'division' => 'SGE', 'latitude' => 10.5667, 'longitude' => -61.1500],
            ['name' => 'Concord', 'division' => 'PED', 'latitude' => 10.1833, 'longitude' => -61.4667],
            ['name' => 'Concordia', 'division' => 'TOB', 'latitude' => 11.1667, 'longitude' => -60.7667],
            ['name' => 'Coolie Block', 'division' => 'DMN', 'latitude' => 10.7000, 'longitude' => -61.5833],
            ['name' => 'Corbeaux Town', 'division' => 'POS', 'latitude' => 10.6667, 'longitude' => -61.5167],
            ['name' => 'Coromandel Settlement', 'division' => 'SIP', 'latitude' => 10.1500, 'longitude' => -61.5167],
            ['name' => 'Coryal', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.3500],
            ['name' => 'Coryal', 'division' => 'SGE', 'latitude' => 10.5500, 'longitude' => -61.1333],
            ['name' => 'Couva', 'division' => 'CTT', 'latitude' => 10.4220, 'longitude' => -61.4500],
            ['name' => 'Couva Savannah', 'division' => 'CTT', 'latitude' => 10.4167, 'longitude' => -61.4333],
            ['name' => 'Courland', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7667],
            ['name' => 'Cove', 'division' => 'TOB', 'latitude' => 11.1833, 'longitude' => -60.8333],
            ['name' => 'Craignish', 'division' => 'PRT', 'latitude' => 10.2333, 'longitude' => -61.3833],
            ['name' => 'Crown', 'division' => 'TUP', 'latitude' => 10.6333, 'longitude' => -61.3500],
            ['name' => 'Cuche', 'division' => 'MRC', 'latitude' => 10.3667, 'longitude' => -61.1833],
            ['name' => 'Culloden', 'division' => 'TOB', 'latitude' => 11.2333, 'longitude' => -60.7000],
            ['name' => 'Cumaca', 'division' => 'SGE', 'latitude' => 10.7167, 'longitude' => -61.1500],
            ['name' => 'Cumberbatch', 'division' => 'CHA', 'latitude' => 10.5000, 'longitude' => -61.3833],
            ['name' => 'Cumuto', 'division' => 'SGE', 'latitude' => 10.5500, 'longitude' => -61.1500],
            ['name' => 'Cumuto Village', 'division' => 'SGE', 'latitude' => 10.5500, 'longitude' => -61.1500],
            ['name' => 'Cunapo', 'division' => 'SGE', 'latitude' => 10.5833, 'longitude' => -61.1833],
            ['name' => 'Cunapo', 'division' => 'TUP', 'latitude' => 10.6167, 'longitude' => -61.2667],
            ['name' => 'Cunaripa', 'division' => 'SGE', 'latitude' => 10.5667, 'longitude' => -61.1667],
            ['name' => 'Cunupia', 'division' => 'CHA', 'latitude' => 10.5500, 'longitude' => -61.4000],
            ['name' => 'Curepe', 'division' => 'TUP', 'latitude' => 10.6333, 'longitude' => -61.4000],
            ['name' => 'Curucaye', 'division' => 'SJL', 'latitude' => 10.6333, 'longitude' => -61.4667],

            // ═══════════════════════════════════════════════════════════════
            // D
            // ═══════════════════════════════════════════════════════════════
            ['name' => "D'Abadie", 'division' => 'TUP', 'latitude' => 10.6333, 'longitude' => -61.3000],
            ['name' => 'Debe', 'division' => 'PED', 'latitude' => 10.2000, 'longitude' => -61.4500],
            ['name' => 'Debe Village', 'division' => 'PED', 'latitude' => 10.2000, 'longitude' => -61.4500],
            ['name' => 'Delaford', 'division' => 'TOB', 'latitude' => 11.2667, 'longitude' => -60.5500],
            ['name' => 'Delhi Settlement', 'division' => 'SIP', 'latitude' => 10.1500, 'longitude' => -61.4833],
            ['name' => 'Diamond', 'division' => 'CTT', 'latitude' => 10.4333, 'longitude' => -61.4333],
            ['name' => 'Diamond', 'division' => 'PED', 'latitude' => 10.1833, 'longitude' => -61.4500],
            ['name' => 'Dibe', 'division' => 'DMN', 'latitude' => 10.7000, 'longitude' => -61.5833],
            ['name' => 'Diego Martin', 'division' => 'DMN', 'latitude' => 10.7214, 'longitude' => -61.5661],
            ['name' => 'Dinsley', 'division' => 'TUP', 'latitude' => 10.6500, 'longitude' => -61.3333],
            ['name' => 'Dow', 'division' => 'CTT', 'latitude' => 10.4667, 'longitude' => -61.4167],
            ['name' => 'Duncan', 'division' => 'PED', 'latitude' => 10.1667, 'longitude' => -61.4333],

            // ═══════════════════════════════════════════════════════════════
            // E
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Earthigg', 'division' => 'SJL', 'latitude' => 10.6500, 'longitude' => -61.4500],
            ['name' => 'East Dry River', 'division' => 'POS', 'latitude' => 10.6667, 'longitude' => -61.5000],
            ['name' => 'Easterfield', 'division' => 'TOB', 'latitude' => 11.2167, 'longitude' => -60.6167],
            ['name' => 'Eckel Ville', 'division' => 'PRT', 'latitude' => 10.2500, 'longitude' => -61.3500],
            ['name' => 'Edinburgh', 'division' => 'CHA', 'latitude' => 10.5333, 'longitude' => -61.4167],
            ['name' => 'El Chorro', 'division' => 'TUP', 'latitude' => 10.6667, 'longitude' => -61.3500],
            ['name' => 'El Dorado', 'division' => 'TUP', 'latitude' => 10.6333, 'longitude' => -61.2833],
            ['name' => 'El Quemado', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4000],
            ['name' => 'El Socorro', 'division' => 'SJL', 'latitude' => 10.6333, 'longitude' => -61.4333],
            ['name' => "Englishman's Bay", 'division' => 'TOB', 'latitude' => 11.2833, 'longitude' => -60.6333],
            ['name' => 'Enterprise', 'division' => 'CHA', 'latitude' => 10.5000, 'longitude' => -61.4167],
            ['name' => 'Erin', 'division' => 'SIP', 'latitude' => 10.0833, 'longitude' => -61.5500],
            ['name' => 'Erthig', 'division' => 'SJL', 'latitude' => 10.6500, 'longitude' => -61.4500],
            ['name' => 'Esperance', 'division' => 'PED', 'latitude' => 10.1833, 'longitude' => -61.4500],
            ['name' => 'Exchange', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4167],

            // ═══════════════════════════════════════════════════════════════
            // F
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Febeau', 'division' => 'SJL', 'latitude' => 10.6333, 'longitude' => -61.4500],
            ['name' => 'Felicity', 'division' => 'CHA', 'latitude' => 10.5000, 'longitude' => -61.4000],
            ['name' => 'Felicity Hall', 'division' => 'CTT', 'latitude' => 10.4833, 'longitude' => -61.4167],
            ['name' => 'Fifth Company', 'division' => 'PRT', 'latitude' => 10.2167, 'longitude' => -61.3167],
            ['name' => 'Fillette', 'division' => 'SJL', 'latitude' => 10.7167, 'longitude' => -61.4833],
            ['name' => 'Flanagin Town', 'division' => 'CTT', 'latitude' => 10.4667, 'longitude' => -61.4000],
            ['name' => 'Florida', 'division' => 'TOB', 'latitude' => 11.1833, 'longitude' => -60.7000],
            ['name' => 'Fonrose', 'division' => 'MRC', 'latitude' => 10.3333, 'longitude' => -61.1333],
            ['name' => 'Forres Park', 'division' => 'CTT', 'latitude' => 10.3833, 'longitude' => -61.4500],
            ['name' => 'Four Roads', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4333],
            ['name' => 'Four Roads', 'division' => 'DMN', 'latitude' => 10.7000, 'longitude' => -61.5667],
            ['name' => 'Fourth Company', 'division' => 'PRT', 'latitude' => 10.2333, 'longitude' => -61.3167],
            ['name' => 'Francique Village', 'division' => 'SIP', 'latitude' => 10.1167, 'longitude' => -61.5000],
            ['name' => 'Franklyns', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7167],
            ['name' => 'Frederick Village', 'division' => 'TUP', 'latitude' => 10.6500, 'longitude' => -61.3667],
            ['name' => 'Freeport', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Friendsfield', 'division' => 'TOB', 'latitude' => 11.2333, 'longitude' => -60.6333],
            ['name' => 'Friendship', 'division' => 'CTT', 'latitude' => 10.4667, 'longitude' => -61.4333],
            ['name' => 'Friendship', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7333],
            ['name' => 'Fullarton', 'division' => 'SIP', 'latitude' => 10.1333, 'longitude' => -61.5000],
            ['name' => 'Fyzabad', 'division' => 'SIP', 'latitude' => 10.1742, 'longitude' => -61.5283],

            // ═══════════════════════════════════════════════════════════════
            // G
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Gasparillo', 'division' => 'CTT', 'latitude' => 10.3167, 'longitude' => -61.4333],
            ['name' => 'George Village', 'division' => 'PRT', 'latitude' => 10.2500, 'longitude' => -61.3667],
            ['name' => 'Glamorgan', 'division' => 'TOB', 'latitude' => 11.2500, 'longitude' => -60.5833],
            ['name' => 'Glencoe', 'division' => 'DMN', 'latitude' => 10.6833, 'longitude' => -61.5667],
            ['name' => 'Golconda', 'division' => 'PED', 'latitude' => 10.1500, 'longitude' => -61.4500],
            ['name' => 'Golden Grove', 'division' => 'TOB', 'latitude' => 11.1833, 'longitude' => -60.7500],
            ['name' => 'Golden Lane', 'division' => 'TOB', 'latitude' => 11.2167, 'longitude' => -60.6667],
            ['name' => 'Goldsborough', 'division' => 'TOB', 'latitude' => 11.2500, 'longitude' => -60.5667],
            ['name' => 'Gonzales', 'division' => 'SJL', 'latitude' => 10.6667, 'longitude' => -61.4833],
            ['name' => 'Goodwood', 'division' => 'TOB', 'latitude' => 11.1667, 'longitude' => -60.7833],
            ['name' => 'Goodwood Park', 'division' => 'DMN', 'latitude' => 10.7167, 'longitude' => -61.5500],
            ['name' => 'Gordon Settlement', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4500],
            ['name' => 'Gordon Settlement', 'division' => 'PRT', 'latitude' => 10.2333, 'longitude' => -61.3500],
            ['name' => 'Grafton', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7833],
            ['name' => 'Gran Couva', 'division' => 'CTT', 'latitude' => 10.4667, 'longitude' => -61.4000],
            ['name' => 'Grand Fond', 'division' => 'MRC', 'latitude' => 10.2667, 'longitude' => -61.0667],
            ['name' => 'Grande Riviere', 'division' => 'SGE', 'latitude' => 10.8333, 'longitude' => -61.0500],
            ['name' => 'Grange', 'division' => 'TOB', 'latitude' => 11.1667, 'longitude' => -60.7500],
            ['name' => 'Granville', 'division' => 'SIP', 'latitude' => 10.1333, 'longitude' => -61.5167],
            ['name' => 'Green Hill', 'division' => 'DMN', 'latitude' => 10.7000, 'longitude' => -61.5833],
            ['name' => 'Green Hill', 'division' => 'TOB', 'latitude' => 11.2167, 'longitude' => -60.6833],
            ['name' => 'Greenhill Village', 'division' => 'DMN', 'latitude' => 10.7000, 'longitude' => -61.5833],
            ['name' => 'Groogroo', 'division' => 'TUP', 'latitude' => 10.6333, 'longitude' => -61.3167],
            ['name' => 'Guaico', 'division' => 'SGE', 'latitude' => 10.5667, 'longitude' => -61.1333],
            ['name' => 'Guaico Tamana', 'division' => 'SGE', 'latitude' => 10.5500, 'longitude' => -61.1500],
            ['name' => 'Guamal', 'division' => 'SJL', 'latitude' => 10.6500, 'longitude' => -61.4333],
            ['name' => 'Guanapo', 'division' => 'TUP', 'latitude' => 10.6167, 'longitude' => -61.3000],
            ['name' => 'Guapo', 'division' => 'PTF', 'latitude' => 10.1833, 'longitude' => -61.6667],
            ['name' => 'Guaracara Junction', 'division' => 'CTT', 'latitude' => 10.4000, 'longitude' => -61.4333],
            ['name' => 'Guarata', 'division' => 'TUP', 'latitude' => 10.6333, 'longitude' => -61.3333],
            ['name' => 'Guayaguayare', 'division' => 'MRC', 'latitude' => 10.1333, 'longitude' => -61.0333],
            ['name' => 'Gunapo', 'division' => 'TUP', 'latitude' => 10.6167, 'longitude' => -61.3000],

            // ═══════════════════════════════════════════════════════════════
            // H
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Hardbargain', 'division' => 'PRT', 'latitude' => 10.2167, 'longitude' => -61.3333],
            ['name' => 'Harmony Hall', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7167],
            ['name' => 'Harts Cut', 'division' => 'DMN', 'latitude' => 10.6833, 'longitude' => -61.6000],
            ['name' => 'Hasnally', 'division' => 'SGE', 'latitude' => 10.5667, 'longitude' => -61.1500],
            ['name' => 'Haswaron', 'division' => 'PRT', 'latitude' => 10.2500, 'longitude' => -61.3667],
            ['name' => 'Hermitage', 'division' => 'CTT', 'latitude' => 10.4333, 'longitude' => -61.4167],
            ['name' => 'Hermitage', 'division' => 'PED', 'latitude' => 10.1833, 'longitude' => -61.4500],
            ['name' => 'Hermitage', 'division' => 'TOB', 'latitude' => 11.1833, 'longitude' => -60.7333],
            ['name' => 'Hillsborough', 'division' => 'TOB', 'latitude' => 11.1667, 'longitude' => -60.7833],
            ['name' => 'Hindustan', 'division' => 'PRT', 'latitude' => 10.2500, 'longitude' => -61.3833],
            ['name' => 'Homard', 'division' => 'SGE', 'latitude' => 10.5833, 'longitude' => -61.1333],
            ['name' => 'Hope', 'division' => 'TOB', 'latitude' => 11.2167, 'longitude' => -60.6333],
            ['name' => 'Howson', 'division' => 'SGE', 'latitude' => 10.5667, 'longitude' => -61.1167],
            ['name' => "Hubert's Town", 'division' => 'PTF', 'latitude' => 10.1833, 'longitude' => -61.6833],

            // ═══════════════════════════════════════════════════════════════
            // I
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Icacos', 'division' => 'SIP', 'latitude' => 10.0667, 'longitude' => -61.8667],
            ['name' => 'Iere', 'division' => 'PRT', 'latitude' => 10.2500, 'longitude' => -61.3500],
            ['name' => 'Indian Chain', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4000],
            ['name' => 'Indian Walk', 'division' => 'PRT', 'latitude' => 10.2333, 'longitude' => -61.3500],
            ['name' => 'Irois', 'division' => 'SIP', 'latitude' => 10.1167, 'longitude' => -61.5333],

            // ═══════════════════════════════════════════════════════════════
            // J
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Jaitoo', 'division' => 'CTT', 'latitude' => 10.4333, 'longitude' => -61.4000],
            ['name' => 'James Smart', 'division' => 'SGE', 'latitude' => 10.5833, 'longitude' => -61.1333],
            ['name' => 'James Stewart', 'division' => 'SGE', 'latitude' => 10.5833, 'longitude' => -61.1333],
            ['name' => 'Jaraysingh', 'division' => 'SGE', 'latitude' => 10.5667, 'longitude' => -61.1500],
            ['name' => 'Jerningham Junction', 'division' => 'CHA', 'latitude' => 10.5000, 'longitude' => -61.3833],

            // ═══════════════════════════════════════════════════════════════
            // K
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Kelly Junction', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Kelly Village', 'division' => 'TUP', 'latitude' => 10.6000, 'longitude' => -61.3833],
            ['name' => 'Kilgwyn', 'division' => 'TOB', 'latitude' => 11.1833, 'longitude' => -60.7500],
            ['name' => "King's Bay", 'division' => 'TOB', 'latitude' => 11.2667, 'longitude' => -60.5333],

            // ═══════════════════════════════════════════════════════════════
            // L
            // ═══════════════════════════════════════════════════════════════
            ['name' => "L'Anse Noire", 'division' => 'SGE', 'latitude' => 10.7500, 'longitude' => -61.0167],
            ['name' => 'La Basse', 'division' => 'SJL', 'latitude' => 10.6667, 'longitude' => -61.4667],
            ['name' => 'La Brea', 'division' => 'SIP', 'latitude' => 10.2333, 'longitude' => -61.6167],
            ['name' => 'La Carriere', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4000],
            ['name' => 'La Finette', 'division' => 'DMN', 'latitude' => 10.7000, 'longitude' => -61.5667],
            ['name' => 'La Lune', 'division' => 'PRT', 'latitude' => 10.2333, 'longitude' => -61.3667],
            ['name' => 'La Pastora', 'division' => 'SJL', 'latitude' => 10.6833, 'longitude' => -61.5000],
            ['name' => 'La Pastora', 'division' => 'TUP', 'latitude' => 10.6500, 'longitude' => -61.3667],
            ['name' => 'La Pastoria', 'division' => 'TUP', 'latitude' => 10.6500, 'longitude' => -61.3667],
            ['name' => 'La Pique', 'division' => 'SFO', 'latitude' => 10.2833, 'longitude' => -61.4833],
            ['name' => 'La Plata', 'division' => 'TUP', 'latitude' => 10.6500, 'longitude' => -61.3500],
            ['name' => 'La Retraite', 'division' => 'DMN', 'latitude' => 10.7000, 'longitude' => -61.5833],
            ['name' => 'La Romain', 'division' => 'SFO', 'latitude' => 10.2667, 'longitude' => -61.4667],
            ['name' => 'La Veronica', 'division' => 'TUP', 'latitude' => 10.6500, 'longitude' => -61.3667],
            ['name' => 'Lambeau', 'division' => 'TOB', 'latitude' => 11.1667, 'longitude' => -60.7500],
            ['name' => 'Lapai Village', 'division' => 'TUP', 'latitude' => 10.6333, 'longitude' => -61.3500],
            ['name' => 'Las Cuevas', 'division' => 'SJL', 'latitude' => 10.7667, 'longitude' => -61.3833],
            ['name' => 'Las Lomas', 'division' => 'CTT', 'latitude' => 10.4667, 'longitude' => -61.4000],
            ['name' => 'Laventille', 'division' => 'SJL', 'latitude' => 10.6490, 'longitude' => -61.4990],
            ['name' => 'Lendor', 'division' => 'CHA', 'latitude' => 10.5167, 'longitude' => -61.4000],
            ['name' => 'Lengua', 'division' => 'PRT', 'latitude' => 10.2333, 'longitude' => -61.3500],
            ['name' => 'Les Coteaux', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7500],
            ['name' => 'Libertville', 'division' => 'MRC', 'latitude' => 10.3333, 'longitude' => -61.1667],
            ['name' => 'Loango', 'division' => 'TUP', 'latitude' => 10.7000, 'longitude' => -61.3167],
            ['name' => 'Longdenville', 'division' => 'CHA', 'latitude' => 10.5167, 'longitude' => -61.3833],
            ['name' => 'Lopinot', 'division' => 'TUP', 'latitude' => 10.7000, 'longitude' => -61.3167],
            ['name' => 'Los Atajos', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Los Bajos', 'division' => 'SIP', 'latitude' => 10.1000, 'longitude' => -61.5500],
            ['name' => 'Lothian', 'division' => 'PRT', 'latitude' => 10.2500, 'longitude' => -61.3667],
            ['name' => 'Lower Fishing Pond', 'division' => 'SGE', 'latitude' => 10.5667, 'longitude' => -61.0667],
            ['name' => 'Lower Manzanilla', 'division' => 'SGE', 'latitude' => 10.4667, 'longitude' => -61.0333],
            ['name' => 'Lower Quarter', 'division' => 'TOB', 'latitude' => 11.1833, 'longitude' => -60.7167],
            ['name' => 'Lower Town', 'division' => 'TOB', 'latitude' => 11.1833, 'longitude' => -60.7333],
            ['name' => 'Lowlands', 'division' => 'TOB', 'latitude' => 11.1500, 'longitude' => -60.8333],

            // ═══════════════════════════════════════════════════════════════
            // M
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Madras Settlement', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4333],
            ['name' => 'Mairad Village', 'division' => 'PRT', 'latitude' => 10.2500, 'longitude' => -61.3667],
            ['name' => 'Malabar Settlement', 'division' => 'ARI', 'latitude' => 10.6167, 'longitude' => -61.2667],
            ['name' => 'Mamon', 'division' => 'SGE', 'latitude' => 10.6000, 'longitude' => -61.1500],
            ['name' => 'Mamoral', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.3833],
            ['name' => 'Mamural', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.3833],
            ['name' => 'Manahambre', 'division' => 'PRT', 'latitude' => 10.2333, 'longitude' => -61.3667],
            ['name' => 'Marabella', 'division' => 'SFO', 'latitude' => 10.2833, 'longitude' => -61.4500],
            ['name' => 'Maracas', 'division' => 'TUP', 'latitude' => 10.6833, 'longitude' => -61.4000],
            ['name' => 'Maracas Bay', 'division' => 'SJL', 'latitude' => 10.7500, 'longitude' => -61.4333],
            ['name' => 'Maraval', 'division' => 'DMN', 'latitude' => 10.7000, 'longitude' => -61.5333],
            ['name' => "Mary's Hill", 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7000],
            ['name' => 'Mason Hall', 'division' => 'TOB', 'latitude' => 11.2333, 'longitude' => -60.7000],
            ['name' => 'Matelot', 'division' => 'SGE', 'latitude' => 10.8167, 'longitude' => -60.9833],
            ['name' => 'Matura', 'division' => 'SGE', 'latitude' => 10.7333, 'longitude' => -61.0333],
            ['name' => 'Maturita', 'division' => 'TUP', 'latitude' => 10.6833, 'longitude' => -61.3167],
            ['name' => 'Mayaro', 'division' => 'MRC', 'latitude' => 10.2833, 'longitude' => -61.0000],
            ['name' => 'Mayo', 'division' => 'CTT', 'latitude' => 10.4667, 'longitude' => -61.4000],
            ['name' => 'Mc Bean', 'division' => 'CTT', 'latitude' => 10.4167, 'longitude' => -61.4333],
            ['name' => 'Merchiston', 'division' => 'TOB', 'latitude' => 11.2333, 'longitude' => -60.6500],
            ['name' => 'Mesopotamia', 'division' => 'TOB', 'latitude' => 11.1833, 'longitude' => -60.7667],
            ['name' => 'Mitan', 'division' => 'MRC', 'latitude' => 10.3500, 'longitude' => -61.1667],
            ['name' => 'Mon Plaisir', 'division' => 'TUP', 'latitude' => 10.6333, 'longitude' => -61.3833],
            ['name' => 'Mon Repos', 'division' => 'SFO', 'latitude' => 10.3000, 'longitude' => -61.4667],
            ['name' => 'Monkey Town', 'division' => 'PED', 'latitude' => 10.1833, 'longitude' => -61.4333],
            ['name' => 'Monkey Town', 'division' => 'PRT', 'latitude' => 10.2333, 'longitude' => -61.3500],
            ['name' => 'Monte Video', 'division' => 'SGE', 'latitude' => 10.5667, 'longitude' => -61.1333],
            ['name' => 'Montgomery', 'division' => 'TOB', 'latitude' => 11.2333, 'longitude' => -60.6667],
            ['name' => 'Montrose', 'division' => 'CHA', 'latitude' => 10.5167, 'longitude' => -61.4000],
            ['name' => 'Montrose', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.6833],
            ['name' => 'Montserrat Village', 'division' => 'CTT', 'latitude' => 10.4667, 'longitude' => -61.4167],
            ['name' => 'Moos', 'division' => 'PRT', 'latitude' => 10.2500, 'longitude' => -61.3833],
            ['name' => 'Moque Point', 'division' => 'PED', 'latitude' => 10.1500, 'longitude' => -61.4333],
            ['name' => 'Moriah', 'division' => 'TOB', 'latitude' => 11.2333, 'longitude' => -60.7167],
            ['name' => 'Morichal', 'division' => 'CTT', 'latitude' => 10.4333, 'longitude' => -61.4000],
            ['name' => 'Morne Cabrite', 'division' => 'SGE', 'latitude' => 10.7333, 'longitude' => -61.0833],
            ['name' => 'Morne Diablo', 'division' => 'PED', 'latitude' => 10.1667, 'longitude' => -61.4500],
            ['name' => 'Morne Quiton', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7000],
            ['name' => 'Moruga', 'division' => 'PRT', 'latitude' => 10.1333, 'longitude' => -61.3167],
            ['name' => 'Morvant', 'division' => 'SJL', 'latitude' => 10.6500, 'longitude' => -61.4833],
            ['name' => 'Mount Dillon', 'division' => 'TOB', 'latitude' => 11.2833, 'longitude' => -60.6000],
            ['name' => 'Mount Grace', 'division' => 'TOB', 'latitude' => 11.1833, 'longitude' => -60.7500],
            ['name' => 'Mount Harris', 'division' => 'SGE', 'latitude' => 10.7167, 'longitude' => -61.0667],
            ['name' => 'Mount Pleasant', 'division' => 'DMN', 'latitude' => 10.7167, 'longitude' => -61.5667],
            ['name' => 'Mount Pleasant', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7333],
            ['name' => 'Mount Saint George', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.6833],
            ['name' => 'Mount Stewart', 'division' => 'PRT', 'latitude' => 10.2333, 'longitude' => -61.3500],
            ['name' => 'Mount Stewart Village', 'division' => 'PRT', 'latitude' => 10.2333, 'longitude' => -61.3500],
            ['name' => 'Mount Thomas', 'division' => 'TOB', 'latitude' => 11.2167, 'longitude' => -60.6667],
            ['name' => 'Mouville', 'division' => 'MRC', 'latitude' => 10.3333, 'longitude' => -61.1500],
            ['name' => 'Mucurapo', 'division' => 'POS', 'latitude' => 10.6667, 'longitude' => -61.5333],
            ['name' => 'Mundo Nuevo', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4000],

            // ═══════════════════════════════════════════════════════════════
            // N
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Nancoo Village', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4333],
            ['name' => 'Naranjo', 'division' => 'SGE', 'latitude' => 10.5833, 'longitude' => -61.1500],
            ['name' => 'Navet', 'division' => 'MRC', 'latitude' => 10.3333, 'longitude' => -61.1833],
            ['name' => 'Nestor', 'division' => 'SGE', 'latitude' => 10.5667, 'longitude' => -61.1333],
            ['name' => 'New Grant', 'division' => 'PRT', 'latitude' => 10.2500, 'longitude' => -61.3333],
            ['name' => 'New Jersey', 'division' => 'SIP', 'latitude' => 10.1333, 'longitude' => -61.5167],
            ['name' => 'Newtown', 'division' => 'POS', 'latitude' => 10.6667, 'longitude' => -61.5167],
            ['name' => 'Noire Bay', 'division' => 'SGE', 'latitude' => 10.7500, 'longitude' => -61.0167],
            ['name' => 'North Manzanilla', 'division' => 'SGE', 'latitude' => 10.5000, 'longitude' => -61.0333],
            ['name' => 'Nutmeg Grove', 'division' => 'TOB', 'latitude' => 11.2167, 'longitude' => -60.7000],

            // ═══════════════════════════════════════════════════════════════
            // O
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Ogis', 'division' => 'SGE', 'latitude' => 10.5833, 'longitude' => -61.1333],
            ['name' => 'Orange Hill', 'division' => 'TOB', 'latitude' => 11.2167, 'longitude' => -60.6500],
            ['name' => 'Orange Valley', 'division' => 'CTT', 'latitude' => 10.4667, 'longitude' => -61.4333],
            ['name' => 'Oropuche', 'division' => 'SGE', 'latitude' => 10.5833, 'longitude' => -61.1667],
            ['name' => 'Oropuche', 'division' => 'SIP', 'latitude' => 10.1500, 'longitude' => -61.5000],
            ['name' => 'Ortinola', 'division' => 'TUP', 'latitude' => 10.6833, 'longitude' => -61.3667],
            ['name' => 'Ouplay', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4000],

            // ═══════════════════════════════════════════════════════════════
            // P
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Palmiste', 'division' => 'CTT', 'latitude' => 10.4333, 'longitude' => -61.4167],
            ['name' => 'Palmiste', 'division' => 'SFO', 'latitude' => 10.2667, 'longitude' => -61.4667],
            ['name' => 'Palmyra', 'division' => 'PRT', 'latitude' => 10.2167, 'longitude' => -61.3500],
            ['name' => 'Palo Seco', 'division' => 'SIP', 'latitude' => 10.1833, 'longitude' => -61.5833],
            ['name' => 'Paradise', 'division' => 'TUP', 'latitude' => 10.6500, 'longitude' => -61.3500],
            ['name' => 'Parlatuvier', 'division' => 'TOB', 'latitude' => 11.2833, 'longitude' => -60.6500],
            ['name' => 'Parrot Hall', 'division' => 'TOB', 'latitude' => 11.2500, 'longitude' => -60.5833],
            ['name' => 'Parry Lands', 'division' => 'SIP', 'latitude' => 10.1333, 'longitude' => -61.5000],
            ['name' => 'Parry Lands', 'division' => 'TUP', 'latitude' => 10.6333, 'longitude' => -61.3333],
            ['name' => 'Patience Hill', 'division' => 'TOB', 'latitude' => 11.2167, 'longitude' => -60.7833],
            ['name' => 'Pembroke', 'division' => 'TOB', 'latitude' => 11.2333, 'longitude' => -60.6500],
            ['name' => 'Penal', 'division' => 'PED', 'latitude' => 10.1667, 'longitude' => -61.4667],
            ['name' => 'Penal Village', 'division' => 'PED', 'latitude' => 10.1667, 'longitude' => -61.4667],
            ['name' => 'Pepper', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Pepper Village', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Peters', 'division' => 'SGE', 'latitude' => 10.5667, 'longitude' => -61.1333],
            ['name' => 'Petit Bourg', 'division' => 'DMN', 'latitude' => 10.7000, 'longitude' => -61.5667],
            ['name' => 'Petit Bourg', 'division' => 'SJL', 'latitude' => 10.6833, 'longitude' => -61.4833],
            ['name' => 'Petit Trou', 'division' => 'SGE', 'latitude' => 10.7000, 'longitude' => -61.0500],
            ['name' => 'Petit Valley', 'division' => 'DMN', 'latitude' => 10.7167, 'longitude' => -61.5500],
            ['name' => 'Phoenix Park', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4333],
            ['name' => 'Piarco', 'division' => 'TUP', 'latitude' => 10.5958, 'longitude' => -61.3372],
            ['name' => 'Piarco Savanna Village', 'division' => 'TUP', 'latitude' => 10.5958, 'longitude' => -61.3372],
            ['name' => 'Pierreville', 'division' => 'MRC', 'latitude' => 10.3167, 'longitude' => -61.1833],
            ['name' => 'Piparo', 'division' => 'PRT', 'latitude' => 10.2833, 'longitude' => -61.3500],
            ['name' => 'Piparo Settlement', 'division' => 'PRT', 'latitude' => 10.2833, 'longitude' => -61.3500],
            ['name' => 'Plaisance', 'division' => 'MRC', 'latitude' => 10.3333, 'longitude' => -61.1500],
            ['name' => 'Plaisance', 'division' => 'PED', 'latitude' => 10.1833, 'longitude' => -61.4500],
            ['name' => 'Plaisance Park', 'division' => 'PRT', 'latitude' => 10.2500, 'longitude' => -61.3667],
            ['name' => 'Platanal', 'division' => 'SGE', 'latitude' => 10.6000, 'longitude' => -61.1667],
            ['name' => 'Pleasantville', 'division' => 'SFO', 'latitude' => 10.2667, 'longitude' => -61.4500],
            ['name' => 'Pluck', 'division' => 'SIP', 'latitude' => 10.1333, 'longitude' => -61.5333],
            ['name' => 'Plum', 'division' => 'SGE', 'latitude' => 10.5833, 'longitude' => -61.1500],
            ['name' => 'Plum Mitan', 'division' => 'MRC', 'latitude' => 10.3500, 'longitude' => -61.1667],
            ['name' => 'Plum Mitan Settlement', 'division' => 'MRC', 'latitude' => 10.3500, 'longitude' => -61.1667],
            ['name' => 'Plymouth', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7833],
            ['name' => 'Point Fortin', 'division' => 'PTF', 'latitude' => 10.1740, 'longitude' => -61.6840],
            ['name' => 'Point Ligoure', 'division' => 'PTF', 'latitude' => 10.1667, 'longitude' => -61.6500],
            ['name' => 'Pointe-a-Pierre', 'division' => 'CTT', 'latitude' => 10.3333, 'longitude' => -61.4667],
            ['name' => 'Poole', 'division' => 'MRC', 'latitude' => 10.3333, 'longitude' => -61.1333],
            ['name' => 'Port Louis', 'division' => 'TOB', 'latitude' => 11.2167, 'longitude' => -60.6667],
            ['name' => 'Port-of-Spain', 'division' => 'POS', 'latitude' => 10.6711, 'longitude' => -61.5212],
            ['name' => 'Preau', 'division' => 'PRT', 'latitude' => 10.2500, 'longitude' => -61.3667],
            ['name' => 'Preysal', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Princes Town', 'division' => 'PRT', 'latitude' => 10.2667, 'longitude' => -61.3833],
            ['name' => 'Prospect', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7167],
            ['name' => 'Providence', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7333],

            // ═══════════════════════════════════════════════════════════════
            // Q
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Quarry Village', 'division' => 'SIP', 'latitude' => 10.1500, 'longitude' => -61.5167],

            // ═══════════════════════════════════════════════════════════════
            // R
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Rambert', 'division' => 'PED', 'latitude' => 10.1833, 'longitude' => -61.4500],
            ['name' => 'Rampanalgas', 'division' => 'SGE', 'latitude' => 10.7500, 'longitude' => -61.0667],
            ['name' => 'Ravin Anglais', 'division' => 'SGE', 'latitude' => 10.5667, 'longitude' => -61.1333],
            ['name' => 'Red Hill', 'division' => 'TUP', 'latitude' => 10.6500, 'longitude' => -61.3500],
            ['name' => 'Redhead', 'division' => 'SGE', 'latitude' => 10.7333, 'longitude' => -61.0333],
            ['name' => 'Reform', 'division' => 'PRT', 'latitude' => 10.2167, 'longitude' => -61.3333],
            ['name' => 'Riche Plaine', 'division' => 'DMN', 'latitude' => 10.7000, 'longitude' => -61.5667],
            ['name' => 'Richmond', 'division' => 'TOB', 'latitude' => 11.2167, 'longitude' => -60.6500],
            ['name' => 'Rio Claro', 'division' => 'MRC', 'latitude' => 10.3060, 'longitude' => -61.1760],
            ['name' => 'Riseland', 'division' => 'TOB', 'latitude' => 11.2167, 'longitude' => -60.6833],
            ['name' => 'Riversdale', 'division' => 'TOB', 'latitude' => 11.2500, 'longitude' => -60.6000],
            ['name' => 'Robert', 'division' => 'PRT', 'latitude' => 10.2500, 'longitude' => -61.3667],
            ['name' => 'Rockly Vale', 'division' => 'TOB', 'latitude' => 11.1667, 'longitude' => -60.7667],
            ['name' => 'Roselle', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7000],
            ['name' => 'Roussillac', 'division' => 'SIP', 'latitude' => 10.1333, 'longitude' => -61.5333],
            ['name' => 'Roussillac Settlement', 'division' => 'SIP', 'latitude' => 10.1333, 'longitude' => -61.5333],
            ['name' => 'Roxborough', 'division' => 'TOB', 'latitude' => 11.2500, 'longitude' => -60.5833],
            ['name' => 'Roxborough Village', 'division' => 'TOB', 'latitude' => 11.2500, 'longitude' => -60.5833],
            ['name' => 'Runnemede', 'division' => 'TOB', 'latitude' => 11.2167, 'longitude' => -60.7000],
            ['name' => 'Runnymede', 'division' => 'TOB', 'latitude' => 11.2167, 'longitude' => -60.7000],
            ['name' => 'Rushville', 'division' => 'MRC', 'latitude' => 10.3167, 'longitude' => -61.1667],

            // ═══════════════════════════════════════════════════════════════
            // S
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Sadhoowa', 'division' => 'PED', 'latitude' => 10.1667, 'longitude' => -61.4500],
            ['name' => 'Saint Andrew', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4000],
            ['name' => 'Saint Anns', 'division' => 'SJL', 'latitude' => 10.6833, 'longitude' => -61.5000],
            ['name' => 'Saint Augustine', 'division' => 'TUP', 'latitude' => 10.6333, 'longitude' => -61.3833],
            ['name' => 'Saint Clair', 'division' => 'POS', 'latitude' => 10.6667, 'longitude' => -61.5167],
            ['name' => 'Saint Croix', 'division' => 'PRT', 'latitude' => 10.2333, 'longitude' => -61.3667],
            ['name' => 'Saint Elizabeth', 'division' => 'SJL', 'latitude' => 10.6500, 'longitude' => -61.4667],
            ['name' => 'Saint Helena', 'division' => 'TUP', 'latitude' => 10.5833, 'longitude' => -61.3167],
            ['name' => 'Saint James', 'division' => 'POS', 'latitude' => 10.6667, 'longitude' => -61.5333],
            ['name' => 'Saint John', 'division' => 'PRT', 'latitude' => 10.2333, 'longitude' => -61.3500],
            ['name' => 'Saint Joseph', 'division' => 'MRC', 'latitude' => 10.3333, 'longitude' => -61.1500],
            ['name' => 'Saint Joseph', 'division' => 'TUP', 'latitude' => 10.6667, 'longitude' => -61.4000],
            ['name' => 'Saint Joseph', 'division' => 'SFO', 'latitude' => 10.2700, 'longitude' => -61.4700],
            ['name' => 'Saint Julien', 'division' => 'PRT', 'latitude' => 10.2333, 'longitude' => -61.3667],
            ['name' => 'Saint Madeleine', 'division' => 'PRT', 'latitude' => 10.2833, 'longitude' => -61.4000],
            ['name' => 'Saint Margaret', 'division' => 'CTT', 'latitude' => 10.4667, 'longitude' => -61.4167],
            ['name' => 'Saint Margaret', 'division' => 'MRC', 'latitude' => 10.3333, 'longitude' => -61.1333],
            ['name' => 'Saint Mary', 'division' => 'SIP', 'latitude' => 10.1333, 'longitude' => -61.5167],
            ['name' => 'Saint Marys', 'division' => 'CTT', 'latitude' => 10.4667, 'longitude' => -61.4167],
            ['name' => 'Saint Pierre', 'division' => 'DMN', 'latitude' => 10.7000, 'longitude' => -61.5667],
            ['name' => 'Saint Thomas', 'division' => 'CHA', 'latitude' => 10.5000, 'longitude' => -61.3833],
            ['name' => 'Sainte Croix', 'division' => 'PRT', 'latitude' => 10.2333, 'longitude' => -61.3667],
            ['name' => 'Sainte Madeleine', 'division' => 'PRT', 'latitude' => 10.2833, 'longitude' => -61.4000],
            ['name' => 'Salybia', 'division' => 'SGE', 'latitude' => 10.7500, 'longitude' => -61.0667],
            ['name' => 'San Fernando', 'division' => 'SFO', 'latitude' => 10.2833, 'longitude' => -61.4667],
            ['name' => 'San Francique', 'division' => 'SIP', 'latitude' => 10.1167, 'longitude' => -61.5000],
            ['name' => 'San Francisco Settlement', 'division' => 'CTT', 'latitude' => 10.4667, 'longitude' => -61.4333],
            ['name' => 'San Joachim', 'division' => 'TUP', 'latitude' => 10.6500, 'longitude' => -61.3667],
            ['name' => 'San José de Oruña', 'division' => 'TUP', 'latitude' => 10.6667, 'longitude' => -61.4000],
            ['name' => 'San Juan', 'division' => 'SJL', 'latitude' => 10.6490, 'longitude' => -61.4990],
            ['name' => 'San Rafael', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Sangre Chiquita', 'division' => 'SGE', 'latitude' => 10.5833, 'longitude' => -61.1333],
            ['name' => 'Sangre Chiquito', 'division' => 'SGE', 'latitude' => 10.5833, 'longitude' => -61.1333],
            ['name' => 'Sangre Grande', 'division' => 'SGE', 'latitude' => 10.5871, 'longitude' => -61.1301],
            ['name' => 'Sans Souci', 'division' => 'SGE', 'latitude' => 10.8000, 'longitude' => -60.9833],
            ['name' => 'Santa Cruz', 'division' => 'SJL', 'latitude' => 10.7167, 'longitude' => -61.4667],
            ['name' => 'Scarborough', 'division' => 'TOB', 'latitude' => 11.1833, 'longitude' => -60.7333],
            ['name' => 'Sea View Gardens', 'division' => 'DMN', 'latitude' => 10.7000, 'longitude' => -61.5833],
            ['name' => 'Shirvan', 'division' => 'TOB', 'latitude' => 11.1833, 'longitude' => -60.7833],
            ['name' => 'Shore Park', 'division' => 'TOB', 'latitude' => 11.1667, 'longitude' => -60.8000],
            ['name' => 'Sierra Leone', 'division' => 'DMN', 'latitude' => 10.7167, 'longitude' => -61.5500],
            ['name' => 'Siparia', 'division' => 'SIP', 'latitude' => 10.1333, 'longitude' => -61.5000],
            ['name' => 'Sixth Company', 'division' => 'PRT', 'latitude' => 10.2000, 'longitude' => -61.3167],
            ['name' => 'Skarboras', 'division' => 'TOB', 'latitude' => 11.1833, 'longitude' => -60.7333],
            ['name' => 'Soledad', 'division' => 'CTT', 'latitude' => 10.4333, 'longitude' => -61.4000],
            ['name' => 'South Oropouche', 'division' => 'SIP', 'latitude' => 10.1500, 'longitude' => -61.5000],
            ['name' => 'Speyside', 'division' => 'TOB', 'latitude' => 11.3000, 'longitude' => -60.5333],
            ['name' => 'Speyside Village', 'division' => 'TOB', 'latitude' => 11.3000, 'longitude' => -60.5333],
            ['name' => 'Spring', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Spring Vale', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Spring Vale', 'division' => 'SFO', 'latitude' => 10.2833, 'longitude' => -61.4667],
            ['name' => 'Spring Village', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Starwood', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7167],
            ['name' => 'St Madeleine', 'division' => 'PRT', 'latitude' => 10.2833, 'longitude' => -61.4000],
            ['name' => 'Studley Park', 'division' => 'TOB', 'latitude' => 11.2500, 'longitude' => -60.5667],
            ['name' => 'Success', 'division' => 'SJL', 'latitude' => 10.6333, 'longitude' => -61.4500],
            ['name' => 'Sum Sum Hill', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4000],

            // ═══════════════════════════════════════════════════════════════
            // T
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Tabaquite', 'division' => 'CTT', 'latitude' => 10.4333, 'longitude' => -61.3167],
            ['name' => 'Tableland', 'division' => 'PRT', 'latitude' => 10.2333, 'longitude' => -61.3500],
            ['name' => 'Tacarigua', 'division' => 'TUP', 'latitude' => 10.6333, 'longitude' => -61.3167],
            ['name' => 'Talparo', 'division' => 'CTT', 'latitude' => 10.5000, 'longitude' => -61.2833],
            ['name' => 'Tarouba', 'division' => 'PRT', 'latitude' => 10.2667, 'longitude' => -61.4000],
            ['name' => 'The Mission', 'division' => 'SGE', 'latitude' => 10.5667, 'longitude' => -61.1333],
            ['name' => 'The Whim', 'division' => 'TOB', 'latitude' => 11.2167, 'longitude' => -60.7000],
            ['name' => 'Thick', 'division' => 'SIP', 'latitude' => 10.1167, 'longitude' => -61.5000],
            ['name' => 'Third Company', 'division' => 'PRT', 'latitude' => 10.2500, 'longitude' => -61.3167],
            ['name' => 'Tierra Nueva', 'division' => 'TUP', 'latitude' => 10.6500, 'longitude' => -61.3667],
            ['name' => 'Toco', 'division' => 'SGE', 'latitude' => 10.7667, 'longitude' => -60.9833],
            ['name' => 'Todds Road', 'division' => 'CTT', 'latitude' => 10.4667, 'longitude' => -61.4167],
            ['name' => 'Tortuga', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4000],
            ['name' => 'Town of Arima', 'division' => 'ARI', 'latitude' => 10.6172, 'longitude' => -61.2744],
            ['name' => 'Trafford', 'division' => 'TUP', 'latitude' => 10.6333, 'longitude' => -61.3500],
            ['name' => 'Trois Rivieres', 'division' => 'TOB', 'latitude' => 11.2833, 'longitude' => -60.5667],
            ['name' => 'Tulls', 'division' => 'TUP', 'latitude' => 10.6500, 'longitude' => -61.3667],
            ['name' => 'Tunapuna', 'division' => 'TUP', 'latitude' => 10.6520, 'longitude' => -61.3890],
            ['name' => 'Tyson Hall', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.6833],

            // ═══════════════════════════════════════════════════════════════
            // U
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Union', 'division' => 'SFO', 'latitude' => 10.2833, 'longitude' => -61.4667],
            ['name' => 'Union', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7167],
            ['name' => 'Upper Carapichaima', 'division' => 'CTT', 'latitude' => 10.4833, 'longitude' => -61.4500],
            ['name' => 'Upper Fishing Pond', 'division' => 'SGE', 'latitude' => 10.5833, 'longitude' => -61.0833],
            ['name' => 'Upper Manzanilla', 'division' => 'SGE', 'latitude' => 10.5167, 'longitude' => -61.0500],
            ['name' => 'Usine', 'division' => 'PED', 'latitude' => 10.2000, 'longitude' => -61.4500],

            // ═══════════════════════════════════════════════════════════════
            // V
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Valencia', 'division' => 'SGE', 'latitude' => 10.6500, 'longitude' => -61.1833],
            ['name' => 'Valsayn', 'division' => 'TUP', 'latitude' => 10.6333, 'longitude' => -61.4167],
            ['name' => 'Vance River', 'division' => 'SIP', 'latitude' => 10.1333, 'longitude' => -61.5167],
            ['name' => 'Verdant Vale', 'division' => 'TUP', 'latitude' => 10.6333, 'longitude' => -61.3667],
            ['name' => 'Veronica', 'division' => 'TUP', 'latitude' => 10.6500, 'longitude' => -61.3667],
            ['name' => 'Vessigny', 'division' => 'SIP', 'latitude' => 10.2000, 'longitude' => -61.6333],
            ['name' => 'Victoria', 'division' => 'SFO', 'latitude' => 10.2833, 'longitude' => -61.4667],
            ['name' => 'Vista Bella', 'division' => 'SFO', 'latitude' => 10.2833, 'longitude' => -61.4833],

            // ═══════════════════════════════════════════════════════════════
            // W
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Warners', 'division' => 'SJL', 'latitude' => 10.6500, 'longitude' => -61.4667],
            ['name' => 'Waterloo', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4833],
            ['name' => 'Williamsville', 'division' => 'PRT', 'latitude' => 10.2833, 'longitude' => -61.3667],
            ['name' => 'Williamsville Station', 'division' => 'PRT', 'latitude' => 10.2833, 'longitude' => -61.3667],
            ['name' => 'Windsor', 'division' => 'TOB', 'latitude' => 11.2333, 'longitude' => -60.6833],
            ['name' => 'Windsor Park', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Woodbrook', 'division' => 'POS', 'latitude' => 10.6667, 'longitude' => -61.5333],
            ['name' => 'Woodland', 'division' => 'SIP', 'latitude' => 10.1333, 'longitude' => -61.5167],
            ['name' => 'Woodlands', 'division' => 'PRT', 'latitude' => 10.2500, 'longitude' => -61.3667],
            ['name' => 'Woodlands', 'division' => 'TOB', 'latitude' => 11.2000, 'longitude' => -60.7000],
            ['name' => 'Wyaby', 'division' => 'CTT', 'latitude' => 10.4500, 'longitude' => -61.4167],

            // ═══════════════════════════════════════════════════════════════
            // Z
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Zion Hill', 'division' => 'TOB', 'latitude' => 11.2167, 'longitude' => -60.7167],
        ];
    }
}
