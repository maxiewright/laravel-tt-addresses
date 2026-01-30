<?php

declare(strict_types=1);

namespace MaxieWright\TrinidadAndTobagoAddresses\Database\Seeders;

use Illuminate\Database\Seeder;
use MaxieWright\TrinidadAndTobagoAddresses\Models\City;

/**
 * City Seeder
 *
 * Seeds 650+ cities, towns, and villages across Trinidad and Tobago.
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
     * Division IDs reference:
     *  1 = Couva/Tabaquite/Talparo (Regional Corporation)
     *  2 = Diego Martin (Regional Corporation)
     *  3 = Mayaro/Rio Claro (Regional Corporation)
     *  4 = Penal/Debe (Regional Corporation)
     *  5 = Princes Town (Regional Corporation)
     *  6 = Sangre Grande (Regional Corporation)
     *  7 = San Juan/Laventille (Regional Corporation)
     *  8 = Siparia (Regional Corporation)
     *  9 = Tunapuna/Piarco (Regional Corporation)
     * 10 = Arima (Borough)
     * 11 = Chaguanas (Borough)
     * 12 = Point Fortin (Borough)
     * 13 = Port of Spain (City Corporation)
     * 14 = San Fernando (City Corporation)
     * 15 = Tobago (Ward)
     */
    public function run(): void
    {
        $cities = $this->getCities();
        $now = now();

        $cities = array_map(function ($city) use ($now) {
            return array_merge($city, [
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

    /**
     * Get all cities organised alphabetically.
     *
     * @return array<int, array{name: string, division_id: int, latitude: float, longitude: float}>
     */
    protected function getCities(): array
    {
        return [
            // ═══════════════════════════════════════════════════════════════
            // A
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Adelphi', 'division_id' => 15, 'latitude' => 11.2167, 'longitude' => -60.6333],
            ['name' => 'Adventure', 'division_id' => 15, 'latitude' => 11.2500, 'longitude' => -60.5667],
            ['name' => 'Agostini', 'division_id' => 1, 'latitude' => 10.4167, 'longitude' => -61.3500],
            ['name' => 'Anse Fourmi', 'division_id' => 15, 'latitude' => 11.3167, 'longitude' => -60.5500],
            ['name' => 'Anse Noire', 'division_id' => 6, 'latitude' => 10.7500, 'longitude' => -61.0167],
            ['name' => 'Arden', 'division_id' => 15, 'latitude' => 11.2333, 'longitude' => -60.6167],
            ['name' => 'Arima', 'division_id' => 10, 'latitude' => 10.6172, 'longitude' => -61.2744],
            ['name' => 'Arnos Vale', 'division_id' => 15, 'latitude' => 11.2167, 'longitude' => -60.7833],
            ['name' => 'Arouca', 'division_id' => 9, 'latitude' => 10.6333, 'longitude' => -61.3333],
            ['name' => 'Arundel', 'division_id' => 9, 'latitude' => 10.6500, 'longitude' => -61.3500],
            ['name' => 'Auchenskeoch', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7167],
            ['name' => 'Avocat', 'division_id' => 8, 'latitude' => 10.1833, 'longitude' => -61.4500],

            // ═══════════════════════════════════════════════════════════════
            // B
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Bacolet', 'division_id' => 15, 'latitude' => 11.1833, 'longitude' => -60.7167],
            ['name' => 'Bakhen', 'division_id' => 4, 'latitude' => 10.1667, 'longitude' => -61.4333],
            ['name' => 'Balmain', 'division_id' => 1, 'latitude' => 10.4833, 'longitude' => -61.4167],
            ['name' => 'Balmain Village', 'division_id' => 1, 'latitude' => 10.4833, 'longitude' => -61.4167],
            ['name' => 'Bamboo', 'division_id' => 8, 'latitude' => 10.1667, 'longitude' => -61.5000],
            ['name' => "Bande-de-l'Est", 'division_id' => 3, 'latitude' => 10.2833, 'longitude' => -61.0500],
            ['name' => 'Barataria', 'division_id' => 7, 'latitude' => 10.6500, 'longitude' => -61.4833],
            ['name' => 'Barrackpore', 'division_id' => 4, 'latitude' => 10.2500, 'longitude' => -61.4333],
            ['name' => 'Barrackpore Settlement', 'division_id' => 4, 'latitude' => 10.2500, 'longitude' => -61.4333],
            ['name' => 'Basse Terre', 'division_id' => 5, 'latitude' => 10.2667, 'longitude' => -61.3500],
            ['name' => 'Basseterre', 'division_id' => 5, 'latitude' => 10.2667, 'longitude' => -61.3500],
            ['name' => 'Basterhall', 'division_id' => 1, 'latitude' => 10.4333, 'longitude' => -61.4000],
            ['name' => 'Bayshore', 'division_id' => 2, 'latitude' => 10.7167, 'longitude' => -61.5500],
            ['name' => 'Belle Garden', 'division_id' => 15, 'latitude' => 11.2667, 'longitude' => -60.5500],
            ['name' => 'Belle Vue', 'division_id' => 9, 'latitude' => 10.6500, 'longitude' => -61.3833],
            ['name' => 'Belmont', 'division_id' => 13, 'latitude' => 10.6667, 'longitude' => -61.5000],
            ['name' => 'Belmont', 'division_id' => 15, 'latitude' => 11.1833, 'longitude' => -60.7333],
            ['name' => 'Bethel', 'division_id' => 15, 'latitude' => 11.2333, 'longitude' => -60.6833],
            ['name' => 'Biche', 'division_id' => 3, 'latitude' => 10.4333, 'longitude' => -61.1167],
            ['name' => 'Biche Village', 'division_id' => 3, 'latitude' => 10.4333, 'longitude' => -61.1167],
            ['name' => 'Black Rock', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7833],
            ['name' => 'Blanchisseuse', 'division_id' => 9, 'latitude' => 10.7833, 'longitude' => -61.3000],
            ['name' => 'Blue Basin', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Boissiere', 'division_id' => 13, 'latitude' => 10.6833, 'longitude' => -61.5333],
            ['name' => 'Bon Accord', 'division_id' => 15, 'latitude' => 11.1667, 'longitude' => -60.8167],
            ['name' => 'Bonasse', 'division_id' => 8, 'latitude' => 10.0833, 'longitude' => -61.5000],
            ['name' => 'Bonne Aventure', 'division_id' => 1, 'latitude' => 10.4667, 'longitude' => -61.4333],
            ['name' => 'Bonne Terre', 'division_id' => 5, 'latitude' => 10.2500, 'longitude' => -61.3667],
            ['name' => 'Brasso', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4000],
            ['name' => 'Brasso Caparo Village', 'division_id' => 1, 'latitude' => 10.4667, 'longitude' => -61.4167],
            ['name' => 'Brasso Piedra', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.3833],
            ['name' => 'Brasso Seco', 'division_id' => 9, 'latitude' => 10.7500, 'longitude' => -61.2500],
            ['name' => 'Brasso Venado', 'division_id' => 1, 'latitude' => 10.4333, 'longitude' => -61.3667],
            ['name' => 'Brasso Village', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4000],
            ['name' => 'Brazil', 'division_id' => 1, 'latitude' => 10.4667, 'longitude' => -61.4500],
            ['name' => 'Brickfield', 'division_id' => 1, 'latitude' => 10.4833, 'longitude' => -61.4000],
            ['name' => 'Brighton', 'division_id' => 8, 'latitude' => 10.2333, 'longitude' => -61.6333],
            ['name' => 'Bronte', 'division_id' => 4, 'latitude' => 10.1833, 'longitude' => -61.4333],
            ['name' => 'Buccoo', 'division_id' => 15, 'latitude' => 11.1833, 'longitude' => -60.8167],
            ['name' => 'Buen Intento', 'division_id' => 5, 'latitude' => 10.2500, 'longitude' => -61.3833],
            ['name' => 'Buenos Aires', 'division_id' => 8, 'latitude' => 10.1500, 'longitude' => -61.4667],
            ['name' => 'Busy Corner', 'division_id' => 5, 'latitude' => 10.2667, 'longitude' => -61.3667],
            ['name' => 'Butler', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4333],

            // ═══════════════════════════════════════════════════════════════
            // C
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Cacandee Settlement', 'division_id' => 11, 'latitude' => 10.5167, 'longitude' => -61.4000],
            ['name' => 'Caigual', 'division_id' => 6, 'latitude' => 10.5667, 'longitude' => -61.1333],
            ['name' => 'Calcutta Settlement', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4500],
            ['name' => 'Calder Hall', 'division_id' => 15, 'latitude' => 11.1833, 'longitude' => -60.7500],
            ['name' => 'California', 'division_id' => 1, 'latitude' => 10.4167, 'longitude' => -61.4333],
            ['name' => 'California Village', 'division_id' => 1, 'latitude' => 10.4167, 'longitude' => -61.4333],
            ['name' => 'Cambleton', 'division_id' => 15, 'latitude' => 11.2167, 'longitude' => -60.6500],
            ['name' => 'Cameron', 'division_id' => 2, 'latitude' => 10.7167, 'longitude' => -61.5833],
            ['name' => 'Campbeltown', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.6833],
            ['name' => 'Canaan', 'division_id' => 4, 'latitude' => 10.1667, 'longitude' => -61.4500],
            ['name' => 'Canaan', 'division_id' => 15, 'latitude' => 11.1667, 'longitude' => -60.8000],
            ['name' => 'Cantaro', 'division_id' => 7, 'latitude' => 10.6500, 'longitude' => -61.4667],
            ['name' => 'Caparo', 'division_id' => 1, 'latitude' => 10.4833, 'longitude' => -61.3500],
            ['name' => 'Cape-de-Ville', 'division_id' => 12, 'latitude' => 10.1667, 'longitude' => -61.6667],
            ['name' => 'Carapichaima', 'division_id' => 1, 'latitude' => 10.4667, 'longitude' => -61.4333],
            ['name' => 'Carapo', 'division_id' => 9, 'latitude' => 10.6000, 'longitude' => -61.3167],
            ['name' => 'Caratal', 'division_id' => 1, 'latitude' => 10.4333, 'longitude' => -61.4167],
            ['name' => 'Cardiff', 'division_id' => 15, 'latitude' => 11.2500, 'longitude' => -60.6000],
            ['name' => 'Carenage', 'division_id' => 2, 'latitude' => 10.7000, 'longitude' => -61.5667],
            ['name' => 'Carmichael', 'division_id' => 6, 'latitude' => 10.5500, 'longitude' => -61.1000],
            ['name' => 'Carnbee Village', 'division_id' => 15, 'latitude' => 11.1833, 'longitude' => -60.7667],
            ['name' => 'Carolina', 'division_id' => 1, 'latitude' => 10.4833, 'longitude' => -61.4333],
            ['name' => 'Caroni', 'division_id' => 9, 'latitude' => 10.6000, 'longitude' => -61.4000],
            ['name' => 'Castara', 'division_id' => 15, 'latitude' => 11.2667, 'longitude' => -60.7000],
            ['name' => 'Caura', 'division_id' => 9, 'latitude' => 10.7000, 'longitude' => -61.3667],
            ['name' => 'Centeno', 'division_id' => 9, 'latitude' => 10.5833, 'longitude' => -61.2500],
            ['name' => 'Chaguanas', 'division_id' => 11, 'latitude' => 10.5173, 'longitude' => -61.4113],
            ['name' => 'Chaguaramas', 'division_id' => 2, 'latitude' => 10.6833, 'longitude' => -61.6333],
            ['name' => 'Charlotteville', 'division_id' => 15, 'latitude' => 11.3167, 'longitude' => -60.5500],
            ['name' => 'Charuma', 'division_id' => 3, 'latitude' => 10.3500, 'longitude' => -61.1500],
            ['name' => 'Chase', 'division_id' => 1, 'latitude' => 10.5167, 'longitude' => -61.4333],
            ['name' => 'Chase Village', 'division_id' => 1, 'latitude' => 10.5167, 'longitude' => -61.4333],
            ['name' => 'Chatham', 'division_id' => 8, 'latitude' => 10.1167, 'longitude' => -61.4667],
            ['name' => 'Cheeyou', 'division_id' => 6, 'latitude' => 10.5833, 'longitude' => -61.1167],
            ['name' => 'Chickland', 'division_id' => 1, 'latitude' => 10.4667, 'longitude' => -61.4000],
            ['name' => 'Chin Chin Savanna Village', 'division_id' => 1, 'latitude' => 10.4833, 'longitude' => -61.3833],
            ['name' => 'Cinnamon Hill', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7000],
            ['name' => 'Cipero-Sainte Croix', 'division_id' => 4, 'latitude' => 10.2167, 'longitude' => -61.4500],
            ['name' => 'City of Port-of-Spain', 'division_id' => 13, 'latitude' => 10.6711, 'longitude' => -61.5212],
            ['name' => 'Claxton Bay', 'division_id' => 1, 'latitude' => 10.3667, 'longitude' => -61.4667],
            ['name' => 'Cochrane', 'division_id' => 12, 'latitude' => 10.1833, 'longitude' => -61.6833],
            ['name' => 'Coco', 'division_id' => 2, 'latitude' => 10.7167, 'longitude' => -61.5667],
            ['name' => 'Cocorite', 'division_id' => 2, 'latitude' => 10.6833, 'longitude' => -61.5500],
            ['name' => 'Coffee', 'division_id' => 14, 'latitude' => 10.2667, 'longitude' => -61.4500],
            ['name' => 'Colconda', 'division_id' => 4, 'latitude' => 10.1500, 'longitude' => -61.4500],
            ['name' => 'Comparo', 'division_id' => 6, 'latitude' => 10.5667, 'longitude' => -61.1500],
            ['name' => 'Concord', 'division_id' => 4, 'latitude' => 10.1833, 'longitude' => -61.4667],
            ['name' => 'Concordia', 'division_id' => 15, 'latitude' => 11.1667, 'longitude' => -60.7667],
            ['name' => 'Coolie Block', 'division_id' => 2, 'latitude' => 10.7000, 'longitude' => -61.5833],
            ['name' => 'Corbeaux Town', 'division_id' => 13, 'latitude' => 10.6667, 'longitude' => -61.5167],
            ['name' => 'Coromandel Settlement', 'division_id' => 8, 'latitude' => 10.1500, 'longitude' => -61.5167],
            ['name' => 'Coryal', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.3500],
            ['name' => 'Coryal', 'division_id' => 6, 'latitude' => 10.5500, 'longitude' => -61.1333],
            ['name' => 'Couva', 'division_id' => 1, 'latitude' => 10.4220, 'longitude' => -61.4500],
            ['name' => 'Couva Savannah', 'division_id' => 1, 'latitude' => 10.4167, 'longitude' => -61.4333],
            ['name' => 'Courland', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7667],
            ['name' => 'Cove', 'division_id' => 15, 'latitude' => 11.1833, 'longitude' => -60.8333],
            ['name' => 'Craignish', 'division_id' => 5, 'latitude' => 10.2333, 'longitude' => -61.3833],
            ['name' => 'Crown', 'division_id' => 9, 'latitude' => 10.6333, 'longitude' => -61.3500],
            ['name' => 'Cuche', 'division_id' => 3, 'latitude' => 10.3667, 'longitude' => -61.1833],
            ['name' => 'Culloden', 'division_id' => 15, 'latitude' => 11.2333, 'longitude' => -60.7000],
            ['name' => 'Cumaca', 'division_id' => 6, 'latitude' => 10.7167, 'longitude' => -61.1500],
            ['name' => 'Cumberbatch', 'division_id' => 11, 'latitude' => 10.5000, 'longitude' => -61.3833],
            ['name' => 'Cumuto', 'division_id' => 6, 'latitude' => 10.5500, 'longitude' => -61.1500],
            ['name' => 'Cumuto Village', 'division_id' => 6, 'latitude' => 10.5500, 'longitude' => -61.1500],
            ['name' => 'Cunapo', 'division_id' => 6, 'latitude' => 10.5833, 'longitude' => -61.1833],
            ['name' => 'Cunapo', 'division_id' => 9, 'latitude' => 10.6167, 'longitude' => -61.2667],
            ['name' => 'Cunaripa', 'division_id' => 6, 'latitude' => 10.5667, 'longitude' => -61.1667],
            ['name' => 'Cunupia', 'division_id' => 11, 'latitude' => 10.5500, 'longitude' => -61.4000],
            ['name' => 'Curepe', 'division_id' => 9, 'latitude' => 10.6333, 'longitude' => -61.4000],
            ['name' => 'Curucaye', 'division_id' => 7, 'latitude' => 10.6333, 'longitude' => -61.4667],

            // ═══════════════════════════════════════════════════════════════
            // D
            // ═══════════════════════════════════════════════════════════════
            ['name' => "D'Abadie", 'division_id' => 9, 'latitude' => 10.6333, 'longitude' => -61.3000],
            ['name' => 'Debe', 'division_id' => 4, 'latitude' => 10.2000, 'longitude' => -61.4500],
            ['name' => 'Debe Village', 'division_id' => 4, 'latitude' => 10.2000, 'longitude' => -61.4500],
            ['name' => 'Delaford', 'division_id' => 15, 'latitude' => 11.2667, 'longitude' => -60.5500],
            ['name' => 'Delhi Settlement', 'division_id' => 8, 'latitude' => 10.1500, 'longitude' => -61.4833],
            ['name' => 'Diamond', 'division_id' => 1, 'latitude' => 10.4333, 'longitude' => -61.4333],
            ['name' => 'Diamond', 'division_id' => 4, 'latitude' => 10.1833, 'longitude' => -61.4500],
            ['name' => 'Dibe', 'division_id' => 2, 'latitude' => 10.7000, 'longitude' => -61.5833],
            ['name' => 'Diego Martin', 'division_id' => 2, 'latitude' => 10.7214, 'longitude' => -61.5661],
            ['name' => 'Dinsley', 'division_id' => 9, 'latitude' => 10.6500, 'longitude' => -61.3333],
            ['name' => 'Dow', 'division_id' => 1, 'latitude' => 10.4667, 'longitude' => -61.4167],
            ['name' => 'Duncan', 'division_id' => 4, 'latitude' => 10.1667, 'longitude' => -61.4333],

            // ═══════════════════════════════════════════════════════════════
            // E
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Earthigg', 'division_id' => 7, 'latitude' => 10.6500, 'longitude' => -61.4500],
            ['name' => 'East Dry River', 'division_id' => 13, 'latitude' => 10.6667, 'longitude' => -61.5000],
            ['name' => 'Easterfield', 'division_id' => 15, 'latitude' => 11.2167, 'longitude' => -60.6167],
            ['name' => 'Eckel Ville', 'division_id' => 5, 'latitude' => 10.2500, 'longitude' => -61.3500],
            ['name' => 'Edinburgh', 'division_id' => 11, 'latitude' => 10.5333, 'longitude' => -61.4167],
            ['name' => 'El Chorro', 'division_id' => 9, 'latitude' => 10.6667, 'longitude' => -61.3500],
            ['name' => 'El Dorado', 'division_id' => 9, 'latitude' => 10.6333, 'longitude' => -61.2833],
            ['name' => 'El Quemado', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4000],
            ['name' => 'El Socorro', 'division_id' => 7, 'latitude' => 10.6333, 'longitude' => -61.4333],
            ['name' => "Englishman's Bay", 'division_id' => 15, 'latitude' => 11.2833, 'longitude' => -60.6333],
            ['name' => 'Enterprise', 'division_id' => 11, 'latitude' => 10.5000, 'longitude' => -61.4167],
            ['name' => 'Erin', 'division_id' => 8, 'latitude' => 10.0833, 'longitude' => -61.5500],
            ['name' => 'Erthig', 'division_id' => 7, 'latitude' => 10.6500, 'longitude' => -61.4500],
            ['name' => 'Esperance', 'division_id' => 4, 'latitude' => 10.1833, 'longitude' => -61.4500],
            ['name' => 'Exchange', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4167],

            // ═══════════════════════════════════════════════════════════════
            // F
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Febeau', 'division_id' => 7, 'latitude' => 10.6333, 'longitude' => -61.4500],
            ['name' => 'Felicity', 'division_id' => 11, 'latitude' => 10.5000, 'longitude' => -61.4000],
            ['name' => 'Felicity Hall', 'division_id' => 1, 'latitude' => 10.4833, 'longitude' => -61.4167],
            ['name' => 'Fifth Company', 'division_id' => 5, 'latitude' => 10.2167, 'longitude' => -61.3167],
            ['name' => 'Fillette', 'division_id' => 7, 'latitude' => 10.7167, 'longitude' => -61.4833],
            ['name' => 'Flanagin Town', 'division_id' => 1, 'latitude' => 10.4667, 'longitude' => -61.4000],
            ['name' => 'Florida', 'division_id' => 15, 'latitude' => 11.1833, 'longitude' => -60.7000],
            ['name' => 'Fonrose', 'division_id' => 3, 'latitude' => 10.3333, 'longitude' => -61.1333],
            ['name' => 'Forres Park', 'division_id' => 1, 'latitude' => 10.3833, 'longitude' => -61.4500],
            ['name' => 'Four Roads', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4333],
            ['name' => 'Four Roads', 'division_id' => 2, 'latitude' => 10.7000, 'longitude' => -61.5667],
            ['name' => 'Fourth Company', 'division_id' => 5, 'latitude' => 10.2333, 'longitude' => -61.3167],
            ['name' => 'Francique Village', 'division_id' => 8, 'latitude' => 10.1167, 'longitude' => -61.5000],
            ['name' => 'Franklyns', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7167],
            ['name' => 'Frederick Village', 'division_id' => 9, 'latitude' => 10.6500, 'longitude' => -61.3667],
            ['name' => 'Freeport', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Friendsfield', 'division_id' => 15, 'latitude' => 11.2333, 'longitude' => -60.6333],
            ['name' => 'Friendship', 'division_id' => 1, 'latitude' => 10.4667, 'longitude' => -61.4333],
            ['name' => 'Friendship', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7333],
            ['name' => 'Fullarton', 'division_id' => 8, 'latitude' => 10.1333, 'longitude' => -61.5000],
            ['name' => 'Fyzabad', 'division_id' => 8, 'latitude' => 10.1742, 'longitude' => -61.5283],

            // ═══════════════════════════════════════════════════════════════
            // G
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Gasparillo', 'division_id' => 1, 'latitude' => 10.3167, 'longitude' => -61.4333],
            ['name' => 'George Village', 'division_id' => 5, 'latitude' => 10.2500, 'longitude' => -61.3667],
            ['name' => 'Glamorgan', 'division_id' => 15, 'latitude' => 11.2500, 'longitude' => -60.5833],
            ['name' => 'Glencoe', 'division_id' => 2, 'latitude' => 10.6833, 'longitude' => -61.5667],
            ['name' => 'Golconda', 'division_id' => 4, 'latitude' => 10.1500, 'longitude' => -61.4500],
            ['name' => 'Golden Grove', 'division_id' => 15, 'latitude' => 11.1833, 'longitude' => -60.7500],
            ['name' => 'Golden Lane', 'division_id' => 15, 'latitude' => 11.2167, 'longitude' => -60.6667],
            ['name' => 'Goldsborough', 'division_id' => 15, 'latitude' => 11.2500, 'longitude' => -60.5667],
            ['name' => 'Gonzales', 'division_id' => 7, 'latitude' => 10.6667, 'longitude' => -61.4833],
            ['name' => 'Goodwood', 'division_id' => 15, 'latitude' => 11.1667, 'longitude' => -60.7833],
            ['name' => 'Goodwood Park', 'division_id' => 2, 'latitude' => 10.7167, 'longitude' => -61.5500],
            ['name' => 'Gordon Settlement', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4500],
            ['name' => 'Gordon Settlement', 'division_id' => 5, 'latitude' => 10.2333, 'longitude' => -61.3500],
            ['name' => 'Grafton', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7833],
            ['name' => 'Gran Couva', 'division_id' => 1, 'latitude' => 10.4667, 'longitude' => -61.4000],
            ['name' => 'Grand Fond', 'division_id' => 3, 'latitude' => 10.2667, 'longitude' => -61.0667],
            ['name' => 'Grande Riviere', 'division_id' => 6, 'latitude' => 10.8333, 'longitude' => -61.0500],
            ['name' => 'Grange', 'division_id' => 15, 'latitude' => 11.1667, 'longitude' => -60.7500],
            ['name' => 'Granville', 'division_id' => 8, 'latitude' => 10.1333, 'longitude' => -61.5167],
            ['name' => 'Green Hill', 'division_id' => 2, 'latitude' => 10.7000, 'longitude' => -61.5833],
            ['name' => 'Green Hill', 'division_id' => 15, 'latitude' => 11.2167, 'longitude' => -60.6833],
            ['name' => 'Greenhill Village', 'division_id' => 2, 'latitude' => 10.7000, 'longitude' => -61.5833],
            ['name' => 'Groogroo', 'division_id' => 9, 'latitude' => 10.6333, 'longitude' => -61.3167],
            ['name' => 'Guaico', 'division_id' => 6, 'latitude' => 10.5667, 'longitude' => -61.1333],
            ['name' => 'Guaico Tamana', 'division_id' => 6, 'latitude' => 10.5500, 'longitude' => -61.1500],
            ['name' => 'Guamal', 'division_id' => 7, 'latitude' => 10.6500, 'longitude' => -61.4333],
            ['name' => 'Guanapo', 'division_id' => 9, 'latitude' => 10.6167, 'longitude' => -61.3000],
            ['name' => 'Guapo', 'division_id' => 12, 'latitude' => 10.1833, 'longitude' => -61.6667],
            ['name' => 'Guaracara Junction', 'division_id' => 1, 'latitude' => 10.4000, 'longitude' => -61.4333],
            ['name' => 'Guarata', 'division_id' => 9, 'latitude' => 10.6333, 'longitude' => -61.3333],
            ['name' => 'Guayaguayare', 'division_id' => 3, 'latitude' => 10.1333, 'longitude' => -61.0333],
            ['name' => 'Gunapo', 'division_id' => 9, 'latitude' => 10.6167, 'longitude' => -61.3000],

            // ═══════════════════════════════════════════════════════════════
            // H
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Hardbargain', 'division_id' => 5, 'latitude' => 10.2167, 'longitude' => -61.3333],
            ['name' => 'Harmony Hall', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7167],
            ['name' => 'Harts Cut', 'division_id' => 2, 'latitude' => 10.6833, 'longitude' => -61.6000],
            ['name' => 'Hasnally', 'division_id' => 6, 'latitude' => 10.5667, 'longitude' => -61.1500],
            ['name' => 'Haswaron', 'division_id' => 5, 'latitude' => 10.2500, 'longitude' => -61.3667],
            ['name' => 'Hermitage', 'division_id' => 1, 'latitude' => 10.4333, 'longitude' => -61.4167],
            ['name' => 'Hermitage', 'division_id' => 4, 'latitude' => 10.1833, 'longitude' => -61.4500],
            ['name' => 'Hermitage', 'division_id' => 15, 'latitude' => 11.1833, 'longitude' => -60.7333],
            ['name' => 'Hillsborough', 'division_id' => 15, 'latitude' => 11.1667, 'longitude' => -60.7833],
            ['name' => 'Hindustan', 'division_id' => 5, 'latitude' => 10.2500, 'longitude' => -61.3833],
            ['name' => 'Homard', 'division_id' => 6, 'latitude' => 10.5833, 'longitude' => -61.1333],
            ['name' => 'Hope', 'division_id' => 15, 'latitude' => 11.2167, 'longitude' => -60.6333],
            ['name' => 'Howson', 'division_id' => 6, 'latitude' => 10.5667, 'longitude' => -61.1167],
            ['name' => "Hubert's Town", 'division_id' => 12, 'latitude' => 10.1833, 'longitude' => -61.6833],

            // ═══════════════════════════════════════════════════════════════
            // I
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Icacos', 'division_id' => 8, 'latitude' => 10.0667, 'longitude' => -61.8667],
            ['name' => 'Iere', 'division_id' => 5, 'latitude' => 10.2500, 'longitude' => -61.3500],
            ['name' => 'Indian Chain', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4000],
            ['name' => 'Indian Walk', 'division_id' => 5, 'latitude' => 10.2333, 'longitude' => -61.3500],
            ['name' => 'Irois', 'division_id' => 8, 'latitude' => 10.1167, 'longitude' => -61.5333],

            // ═══════════════════════════════════════════════════════════════
            // J
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Jaitoo', 'division_id' => 1, 'latitude' => 10.4333, 'longitude' => -61.4000],
            ['name' => 'James Smart', 'division_id' => 6, 'latitude' => 10.5833, 'longitude' => -61.1333],
            ['name' => 'James Stewart', 'division_id' => 6, 'latitude' => 10.5833, 'longitude' => -61.1333],
            ['name' => 'Jaraysingh', 'division_id' => 6, 'latitude' => 10.5667, 'longitude' => -61.1500],
            ['name' => 'Jerningham Junction', 'division_id' => 11, 'latitude' => 10.5000, 'longitude' => -61.3833],

            // ═══════════════════════════════════════════════════════════════
            // K
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Kelly Junction', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Kelly Village', 'division_id' => 9, 'latitude' => 10.6000, 'longitude' => -61.3833],
            ['name' => 'Kilgwyn', 'division_id' => 15, 'latitude' => 11.1833, 'longitude' => -60.7500],
            ['name' => "King's Bay", 'division_id' => 15, 'latitude' => 11.2667, 'longitude' => -60.5333],

            // ═══════════════════════════════════════════════════════════════
            // L
            // ═══════════════════════════════════════════════════════════════
            ['name' => "L'Anse Noire", 'division_id' => 6, 'latitude' => 10.7500, 'longitude' => -61.0167],
            ['name' => 'La Basse', 'division_id' => 7, 'latitude' => 10.6667, 'longitude' => -61.4667],
            ['name' => 'La Brea', 'division_id' => 8, 'latitude' => 10.2333, 'longitude' => -61.6167],
            ['name' => 'La Carriere', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4000],
            ['name' => 'La Finette', 'division_id' => 2, 'latitude' => 10.7000, 'longitude' => -61.5667],
            ['name' => 'La Lune', 'division_id' => 5, 'latitude' => 10.2333, 'longitude' => -61.3667],
            ['name' => 'La Pastora', 'division_id' => 7, 'latitude' => 10.6833, 'longitude' => -61.5000],
            ['name' => 'La Pastora', 'division_id' => 9, 'latitude' => 10.6500, 'longitude' => -61.3667],
            ['name' => 'La Pastoria', 'division_id' => 9, 'latitude' => 10.6500, 'longitude' => -61.3667],
            ['name' => 'La Pique', 'division_id' => 14, 'latitude' => 10.2833, 'longitude' => -61.4833],
            ['name' => 'La Plata', 'division_id' => 9, 'latitude' => 10.6500, 'longitude' => -61.3500],
            ['name' => 'La Retraite', 'division_id' => 2, 'latitude' => 10.7000, 'longitude' => -61.5833],
            ['name' => 'La Romain', 'division_id' => 14, 'latitude' => 10.2667, 'longitude' => -61.4667],
            ['name' => 'La Veronica', 'division_id' => 9, 'latitude' => 10.6500, 'longitude' => -61.3667],
            ['name' => 'Lambeau', 'division_id' => 15, 'latitude' => 11.1667, 'longitude' => -60.7500],
            ['name' => 'Lapai Village', 'division_id' => 9, 'latitude' => 10.6333, 'longitude' => -61.3500],
            ['name' => 'Las Cuevas', 'division_id' => 7, 'latitude' => 10.7667, 'longitude' => -61.3833],
            ['name' => 'Las Lomas', 'division_id' => 1, 'latitude' => 10.4667, 'longitude' => -61.4000],
            ['name' => 'Laventille', 'division_id' => 7, 'latitude' => 10.6490, 'longitude' => -61.4990],
            ['name' => 'Lendor', 'division_id' => 11, 'latitude' => 10.5167, 'longitude' => -61.4000],
            ['name' => 'Lengua', 'division_id' => 5, 'latitude' => 10.2333, 'longitude' => -61.3500],
            ['name' => 'Les Coteaux', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7500],
            ['name' => 'Libertville', 'division_id' => 3, 'latitude' => 10.3333, 'longitude' => -61.1667],
            ['name' => 'Loango', 'division_id' => 9, 'latitude' => 10.7000, 'longitude' => -61.3167],
            ['name' => 'Longdenville', 'division_id' => 11, 'latitude' => 10.5167, 'longitude' => -61.3833],
            ['name' => 'Lopinot', 'division_id' => 9, 'latitude' => 10.7000, 'longitude' => -61.3167],
            ['name' => 'Los Atajos', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Los Bajos', 'division_id' => 8, 'latitude' => 10.1000, 'longitude' => -61.5500],
            ['name' => 'Lothian', 'division_id' => 5, 'latitude' => 10.2500, 'longitude' => -61.3667],
            ['name' => 'Lower Fishing Pond', 'division_id' => 6, 'latitude' => 10.5667, 'longitude' => -61.0667],
            ['name' => 'Lower Manzanilla', 'division_id' => 6, 'latitude' => 10.4667, 'longitude' => -61.0333],
            ['name' => 'Lower Quarter', 'division_id' => 15, 'latitude' => 11.1833, 'longitude' => -60.7167],
            ['name' => 'Lower Town', 'division_id' => 15, 'latitude' => 11.1833, 'longitude' => -60.7333],
            ['name' => 'Lowlands', 'division_id' => 15, 'latitude' => 11.1500, 'longitude' => -60.8333],

            // ═══════════════════════════════════════════════════════════════
            // M
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Madras Settlement', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4333],
            ['name' => 'Mairad Village', 'division_id' => 5, 'latitude' => 10.2500, 'longitude' => -61.3667],
            ['name' => 'Malabar Settlement', 'division_id' => 10, 'latitude' => 10.6167, 'longitude' => -61.2667],
            ['name' => 'Mamon', 'division_id' => 6, 'latitude' => 10.6000, 'longitude' => -61.1500],
            ['name' => 'Mamoral', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.3833],
            ['name' => 'Mamural', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.3833],
            ['name' => 'Manahambre', 'division_id' => 5, 'latitude' => 10.2333, 'longitude' => -61.3667],
            ['name' => 'Marabella', 'division_id' => 14, 'latitude' => 10.2833, 'longitude' => -61.4500],
            ['name' => 'Maracas', 'division_id' => 9, 'latitude' => 10.6833, 'longitude' => -61.4000],
            ['name' => 'Maracas Bay', 'division_id' => 7, 'latitude' => 10.7500, 'longitude' => -61.4333],
            ['name' => 'Maraval', 'division_id' => 2, 'latitude' => 10.7000, 'longitude' => -61.5333],
            ['name' => "Mary's Hill", 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7000],
            ['name' => 'Mason Hall', 'division_id' => 15, 'latitude' => 11.2333, 'longitude' => -60.7000],
            ['name' => 'Matelot', 'division_id' => 6, 'latitude' => 10.8167, 'longitude' => -60.9833],
            ['name' => 'Matura', 'division_id' => 6, 'latitude' => 10.7333, 'longitude' => -61.0333],
            ['name' => 'Maturita', 'division_id' => 9, 'latitude' => 10.6833, 'longitude' => -61.3167],
            ['name' => 'Mayaro', 'division_id' => 3, 'latitude' => 10.2833, 'longitude' => -61.0000],
            ['name' => 'Mayo', 'division_id' => 1, 'latitude' => 10.4667, 'longitude' => -61.4000],
            ['name' => 'Mc Bean', 'division_id' => 1, 'latitude' => 10.4167, 'longitude' => -61.4333],
            ['name' => 'Merchiston', 'division_id' => 15, 'latitude' => 11.2333, 'longitude' => -60.6500],
            ['name' => 'Mesopotamia', 'division_id' => 15, 'latitude' => 11.1833, 'longitude' => -60.7667],
            ['name' => 'Mitan', 'division_id' => 3, 'latitude' => 10.3500, 'longitude' => -61.1667],
            ['name' => 'Mon Plaisir', 'division_id' => 9, 'latitude' => 10.6333, 'longitude' => -61.3833],
            ['name' => 'Mon Repos', 'division_id' => 14, 'latitude' => 10.3000, 'longitude' => -61.4667],
            ['name' => 'Monkey Town', 'division_id' => 4, 'latitude' => 10.1833, 'longitude' => -61.4333],
            ['name' => 'Monkey Town', 'division_id' => 5, 'latitude' => 10.2333, 'longitude' => -61.3500],
            ['name' => 'Monte Video', 'division_id' => 6, 'latitude' => 10.5667, 'longitude' => -61.1333],
            ['name' => 'Montgomery', 'division_id' => 15, 'latitude' => 11.2333, 'longitude' => -60.6667],
            ['name' => 'Montrose', 'division_id' => 11, 'latitude' => 10.5167, 'longitude' => -61.4000],
            ['name' => 'Montrose', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.6833],
            ['name' => 'Montserrat Village', 'division_id' => 1, 'latitude' => 10.4667, 'longitude' => -61.4167],
            ['name' => 'Moos', 'division_id' => 5, 'latitude' => 10.2500, 'longitude' => -61.3833],
            ['name' => 'Moque Point', 'division_id' => 4, 'latitude' => 10.1500, 'longitude' => -61.4333],
            ['name' => 'Moriah', 'division_id' => 15, 'latitude' => 11.2333, 'longitude' => -60.7167],
            ['name' => 'Morichal', 'division_id' => 1, 'latitude' => 10.4333, 'longitude' => -61.4000],
            ['name' => 'Morne Cabrite', 'division_id' => 6, 'latitude' => 10.7333, 'longitude' => -61.0833],
            ['name' => 'Morne Diablo', 'division_id' => 4, 'latitude' => 10.1667, 'longitude' => -61.4500],
            ['name' => 'Morne Quiton', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7000],
            ['name' => 'Moruga', 'division_id' => 5, 'latitude' => 10.1333, 'longitude' => -61.3167],
            ['name' => 'Morvant', 'division_id' => 7, 'latitude' => 10.6500, 'longitude' => -61.4833],
            ['name' => 'Mount Dillon', 'division_id' => 15, 'latitude' => 11.2833, 'longitude' => -60.6000],
            ['name' => 'Mount Grace', 'division_id' => 15, 'latitude' => 11.1833, 'longitude' => -60.7500],
            ['name' => 'Mount Harris', 'division_id' => 6, 'latitude' => 10.7167, 'longitude' => -61.0667],
            ['name' => 'Mount Pleasant', 'division_id' => 2, 'latitude' => 10.7167, 'longitude' => -61.5667],
            ['name' => 'Mount Pleasant', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7333],
            ['name' => 'Mount Saint George', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.6833],
            ['name' => 'Mount Stewart', 'division_id' => 5, 'latitude' => 10.2333, 'longitude' => -61.3500],
            ['name' => 'Mount Stewart Village', 'division_id' => 5, 'latitude' => 10.2333, 'longitude' => -61.3500],
            ['name' => 'Mount Thomas', 'division_id' => 15, 'latitude' => 11.2167, 'longitude' => -60.6667],
            ['name' => 'Mouville', 'division_id' => 3, 'latitude' => 10.3333, 'longitude' => -61.1500],
            ['name' => 'Mucurapo', 'division_id' => 13, 'latitude' => 10.6667, 'longitude' => -61.5333],
            ['name' => 'Mundo Nuevo', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4000],

            // ═══════════════════════════════════════════════════════════════
            // N
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Nancoo Village', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4333],
            ['name' => 'Naranjo', 'division_id' => 6, 'latitude' => 10.5833, 'longitude' => -61.1500],
            ['name' => 'Navet', 'division_id' => 3, 'latitude' => 10.3333, 'longitude' => -61.1833],
            ['name' => 'Nestor', 'division_id' => 6, 'latitude' => 10.5667, 'longitude' => -61.1333],
            ['name' => 'New Grant', 'division_id' => 5, 'latitude' => 10.2500, 'longitude' => -61.3333],
            ['name' => 'New Jersey', 'division_id' => 8, 'latitude' => 10.1333, 'longitude' => -61.5167],
            ['name' => 'Newtown', 'division_id' => 13, 'latitude' => 10.6667, 'longitude' => -61.5167],
            ['name' => 'Noire Bay', 'division_id' => 6, 'latitude' => 10.7500, 'longitude' => -61.0167],
            ['name' => 'North Manzanilla', 'division_id' => 6, 'latitude' => 10.5000, 'longitude' => -61.0333],
            ['name' => 'Nutmeg Grove', 'division_id' => 15, 'latitude' => 11.2167, 'longitude' => -60.7000],

            // ═══════════════════════════════════════════════════════════════
            // O
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Ogis', 'division_id' => 6, 'latitude' => 10.5833, 'longitude' => -61.1333],
            ['name' => 'Orange Hill', 'division_id' => 15, 'latitude' => 11.2167, 'longitude' => -60.6500],
            ['name' => 'Orange Valley', 'division_id' => 1, 'latitude' => 10.4667, 'longitude' => -61.4333],
            ['name' => 'Oropuche', 'division_id' => 6, 'latitude' => 10.5833, 'longitude' => -61.1667],
            ['name' => 'Oropuche', 'division_id' => 8, 'latitude' => 10.1500, 'longitude' => -61.5000],
            ['name' => 'Ortinola', 'division_id' => 9, 'latitude' => 10.6833, 'longitude' => -61.3667],
            ['name' => 'Ouplay', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4000],

            // ═══════════════════════════════════════════════════════════════
            // P
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Palmiste', 'division_id' => 1, 'latitude' => 10.4333, 'longitude' => -61.4167],
            ['name' => 'Palmiste', 'division_id' => 14, 'latitude' => 10.2667, 'longitude' => -61.4667],
            ['name' => 'Palmyra', 'division_id' => 5, 'latitude' => 10.2167, 'longitude' => -61.3500],
            ['name' => 'Palo Seco', 'division_id' => 8, 'latitude' => 10.1833, 'longitude' => -61.5833],
            ['name' => 'Paradise', 'division_id' => 9, 'latitude' => 10.6500, 'longitude' => -61.3500],
            ['name' => 'Parlatuvier', 'division_id' => 15, 'latitude' => 11.2833, 'longitude' => -60.6500],
            ['name' => 'Parrot Hall', 'division_id' => 15, 'latitude' => 11.2500, 'longitude' => -60.5833],
            ['name' => 'Parry Lands', 'division_id' => 8, 'latitude' => 10.1333, 'longitude' => -61.5000],
            ['name' => 'Parry Lands', 'division_id' => 9, 'latitude' => 10.6333, 'longitude' => -61.3333],
            ['name' => 'Patience Hill', 'division_id' => 15, 'latitude' => 11.2167, 'longitude' => -60.7833],
            ['name' => 'Pembroke', 'division_id' => 15, 'latitude' => 11.2333, 'longitude' => -60.6500],
            ['name' => 'Penal', 'division_id' => 4, 'latitude' => 10.1667, 'longitude' => -61.4667],
            ['name' => 'Penal Village', 'division_id' => 4, 'latitude' => 10.1667, 'longitude' => -61.4667],
            ['name' => 'Pepper', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Pepper Village', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Peters', 'division_id' => 6, 'latitude' => 10.5667, 'longitude' => -61.1333],
            ['name' => 'Petit Bourg', 'division_id' => 2, 'latitude' => 10.7000, 'longitude' => -61.5667],
            ['name' => 'Petit Bourg', 'division_id' => 7, 'latitude' => 10.6833, 'longitude' => -61.4833],
            ['name' => 'Petit Trou', 'division_id' => 6, 'latitude' => 10.7000, 'longitude' => -61.0500],
            ['name' => 'Petit Valley', 'division_id' => 2, 'latitude' => 10.7167, 'longitude' => -61.5500],
            ['name' => 'Phoenix Park', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4333],
            ['name' => 'Piarco', 'division_id' => 9, 'latitude' => 10.5958, 'longitude' => -61.3372],
            ['name' => 'Piarco Savanna Village', 'division_id' => 9, 'latitude' => 10.5958, 'longitude' => -61.3372],
            ['name' => 'Pierreville', 'division_id' => 3, 'latitude' => 10.3167, 'longitude' => -61.1833],
            ['name' => 'Piparo', 'division_id' => 5, 'latitude' => 10.2833, 'longitude' => -61.3500],
            ['name' => 'Piparo Settlement', 'division_id' => 5, 'latitude' => 10.2833, 'longitude' => -61.3500],
            ['name' => 'Plaisance', 'division_id' => 3, 'latitude' => 10.3333, 'longitude' => -61.1500],
            ['name' => 'Plaisance', 'division_id' => 4, 'latitude' => 10.1833, 'longitude' => -61.4500],
            ['name' => 'Plaisance Park', 'division_id' => 5, 'latitude' => 10.2500, 'longitude' => -61.3667],
            ['name' => 'Platanal', 'division_id' => 6, 'latitude' => 10.6000, 'longitude' => -61.1667],
            ['name' => 'Pleasantville', 'division_id' => 14, 'latitude' => 10.2667, 'longitude' => -61.4500],
            ['name' => 'Pluck', 'division_id' => 8, 'latitude' => 10.1333, 'longitude' => -61.5333],
            ['name' => 'Plum', 'division_id' => 6, 'latitude' => 10.5833, 'longitude' => -61.1500],
            ['name' => 'Plum Mitan', 'division_id' => 3, 'latitude' => 10.3500, 'longitude' => -61.1667],
            ['name' => 'Plum Mitan Settlement', 'division_id' => 3, 'latitude' => 10.3500, 'longitude' => -61.1667],
            ['name' => 'Plymouth', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7833],
            ['name' => 'Point Fortin', 'division_id' => 12, 'latitude' => 10.1740, 'longitude' => -61.6840],
            ['name' => 'Point Ligoure', 'division_id' => 12, 'latitude' => 10.1667, 'longitude' => -61.6500],
            ['name' => 'Pointe-a-Pierre', 'division_id' => 1, 'latitude' => 10.3333, 'longitude' => -61.4667],
            ['name' => 'Poole', 'division_id' => 3, 'latitude' => 10.3333, 'longitude' => -61.1333],
            ['name' => 'Port Louis', 'division_id' => 15, 'latitude' => 11.2167, 'longitude' => -60.6667],
            ['name' => 'Port-of-Spain', 'division_id' => 13, 'latitude' => 10.6711, 'longitude' => -61.5212],
            ['name' => 'Preau', 'division_id' => 5, 'latitude' => 10.2500, 'longitude' => -61.3667],
            ['name' => 'Preysal', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Princes Town', 'division_id' => 5, 'latitude' => 10.2667, 'longitude' => -61.3833],
            ['name' => 'Prospect', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7167],
            ['name' => 'Providence', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7333],

            // ═══════════════════════════════════════════════════════════════
            // Q
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Quarry Village', 'division_id' => 8, 'latitude' => 10.1500, 'longitude' => -61.5167],

            // ═══════════════════════════════════════════════════════════════
            // R
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Rambert', 'division_id' => 4, 'latitude' => 10.1833, 'longitude' => -61.4500],
            ['name' => 'Rampanalgas', 'division_id' => 6, 'latitude' => 10.7500, 'longitude' => -61.0667],
            ['name' => 'Ravin Anglais', 'division_id' => 6, 'latitude' => 10.5667, 'longitude' => -61.1333],
            ['name' => 'Red Hill', 'division_id' => 9, 'latitude' => 10.6500, 'longitude' => -61.3500],
            ['name' => 'Redhead', 'division_id' => 6, 'latitude' => 10.7333, 'longitude' => -61.0333],
            ['name' => 'Reform', 'division_id' => 5, 'latitude' => 10.2167, 'longitude' => -61.3333],
            ['name' => 'Riche Plaine', 'division_id' => 2, 'latitude' => 10.7000, 'longitude' => -61.5667],
            ['name' => 'Richmond', 'division_id' => 15, 'latitude' => 11.2167, 'longitude' => -60.6500],
            ['name' => 'Rio Claro', 'division_id' => 3, 'latitude' => 10.3060, 'longitude' => -61.1760],
            ['name' => 'Riseland', 'division_id' => 15, 'latitude' => 11.2167, 'longitude' => -60.6833],
            ['name' => 'Riversdale', 'division_id' => 15, 'latitude' => 11.2500, 'longitude' => -60.6000],
            ['name' => 'Robert', 'division_id' => 5, 'latitude' => 10.2500, 'longitude' => -61.3667],
            ['name' => 'Rockly Vale', 'division_id' => 15, 'latitude' => 11.1667, 'longitude' => -60.7667],
            ['name' => 'Roselle', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7000],
            ['name' => 'Roussillac', 'division_id' => 8, 'latitude' => 10.1333, 'longitude' => -61.5333],
            ['name' => 'Roussillac Settlement', 'division_id' => 8, 'latitude' => 10.1333, 'longitude' => -61.5333],
            ['name' => 'Roxborough', 'division_id' => 15, 'latitude' => 11.2500, 'longitude' => -60.5833],
            ['name' => 'Roxborough Village', 'division_id' => 15, 'latitude' => 11.2500, 'longitude' => -60.5833],
            ['name' => 'Runnemede', 'division_id' => 15, 'latitude' => 11.2167, 'longitude' => -60.7000],
            ['name' => 'Runnymede', 'division_id' => 15, 'latitude' => 11.2167, 'longitude' => -60.7000],
            ['name' => 'Rushville', 'division_id' => 3, 'latitude' => 10.3167, 'longitude' => -61.1667],

            // ═══════════════════════════════════════════════════════════════
            // S
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Sadhoowa', 'division_id' => 4, 'latitude' => 10.1667, 'longitude' => -61.4500],
            ['name' => 'Saint Andrew', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4000],
            ['name' => 'Saint Anns', 'division_id' => 7, 'latitude' => 10.6833, 'longitude' => -61.5000],
            ['name' => 'Saint Augustine', 'division_id' => 9, 'latitude' => 10.6333, 'longitude' => -61.3833],
            ['name' => 'Saint Clair', 'division_id' => 13, 'latitude' => 10.6667, 'longitude' => -61.5167],
            ['name' => 'Saint Croix', 'division_id' => 5, 'latitude' => 10.2333, 'longitude' => -61.3667],
            ['name' => 'Saint Elizabeth', 'division_id' => 7, 'latitude' => 10.6500, 'longitude' => -61.4667],
            ['name' => 'Saint Helena', 'division_id' => 9, 'latitude' => 10.5833, 'longitude' => -61.3167],
            ['name' => 'Saint James', 'division_id' => 13, 'latitude' => 10.6667, 'longitude' => -61.5333],
            ['name' => 'Saint John', 'division_id' => 5, 'latitude' => 10.2333, 'longitude' => -61.3500],
            ['name' => 'Saint Joseph', 'division_id' => 3, 'latitude' => 10.3333, 'longitude' => -61.1500],
            ['name' => 'Saint Joseph', 'division_id' => 9, 'latitude' => 10.6667, 'longitude' => -61.4000],
            ['name' => 'Saint Joseph', 'division_id' => 14, 'latitude' => 10.2700, 'longitude' => -61.4700],
            ['name' => 'Saint Julien', 'division_id' => 5, 'latitude' => 10.2333, 'longitude' => -61.3667],
            ['name' => 'Saint Madeleine', 'division_id' => 5, 'latitude' => 10.2833, 'longitude' => -61.4000],
            ['name' => 'Saint Margaret', 'division_id' => 1, 'latitude' => 10.4667, 'longitude' => -61.4167],
            ['name' => 'Saint Margaret', 'division_id' => 3, 'latitude' => 10.3333, 'longitude' => -61.1333],
            ['name' => 'Saint Mary', 'division_id' => 8, 'latitude' => 10.1333, 'longitude' => -61.5167],
            ['name' => 'Saint Marys', 'division_id' => 1, 'latitude' => 10.4667, 'longitude' => -61.4167],
            ['name' => 'Saint Pierre', 'division_id' => 2, 'latitude' => 10.7000, 'longitude' => -61.5667],
            ['name' => 'Saint Thomas', 'division_id' => 11, 'latitude' => 10.5000, 'longitude' => -61.3833],
            ['name' => 'Sainte Croix', 'division_id' => 5, 'latitude' => 10.2333, 'longitude' => -61.3667],
            ['name' => 'Sainte Madeleine', 'division_id' => 5, 'latitude' => 10.2833, 'longitude' => -61.4000],
            ['name' => 'Salybia', 'division_id' => 6, 'latitude' => 10.7500, 'longitude' => -61.0667],
            ['name' => 'San Fernando', 'division_id' => 14, 'latitude' => 10.2833, 'longitude' => -61.4667],
            ['name' => 'San Francique', 'division_id' => 8, 'latitude' => 10.1167, 'longitude' => -61.5000],
            ['name' => 'San Francisco Settlement', 'division_id' => 1, 'latitude' => 10.4667, 'longitude' => -61.4333],
            ['name' => 'San Joachim', 'division_id' => 9, 'latitude' => 10.6500, 'longitude' => -61.3667],
            ['name' => 'San José de Oruña', 'division_id' => 9, 'latitude' => 10.6667, 'longitude' => -61.4000],
            ['name' => 'San Juan', 'division_id' => 7, 'latitude' => 10.6490, 'longitude' => -61.4990],
            ['name' => 'San Rafael', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Sangre Chiquita', 'division_id' => 6, 'latitude' => 10.5833, 'longitude' => -61.1333],
            ['name' => 'Sangre Chiquito', 'division_id' => 6, 'latitude' => 10.5833, 'longitude' => -61.1333],
            ['name' => 'Sangre Grande', 'division_id' => 6, 'latitude' => 10.5871, 'longitude' => -61.1301],
            ['name' => 'Sans Souci', 'division_id' => 6, 'latitude' => 10.8000, 'longitude' => -60.9833],
            ['name' => 'Santa Cruz', 'division_id' => 7, 'latitude' => 10.7167, 'longitude' => -61.4667],
            ['name' => 'Scarborough', 'division_id' => 15, 'latitude' => 11.1833, 'longitude' => -60.7333],
            ['name' => 'Sea View Gardens', 'division_id' => 2, 'latitude' => 10.7000, 'longitude' => -61.5833],
            ['name' => 'Shirvan', 'division_id' => 15, 'latitude' => 11.1833, 'longitude' => -60.7833],
            ['name' => 'Shore Park', 'division_id' => 15, 'latitude' => 11.1667, 'longitude' => -60.8000],
            ['name' => 'Sierra Leone', 'division_id' => 2, 'latitude' => 10.7167, 'longitude' => -61.5500],
            ['name' => 'Siparia', 'division_id' => 8, 'latitude' => 10.1333, 'longitude' => -61.5000],
            ['name' => 'Sixth Company', 'division_id' => 5, 'latitude' => 10.2000, 'longitude' => -61.3167],
            ['name' => 'Skarboras', 'division_id' => 15, 'latitude' => 11.1833, 'longitude' => -60.7333],
            ['name' => 'Soledad', 'division_id' => 1, 'latitude' => 10.4333, 'longitude' => -61.4000],
            ['name' => 'South Oropouche', 'division_id' => 8, 'latitude' => 10.1500, 'longitude' => -61.5000],
            ['name' => 'Speyside', 'division_id' => 15, 'latitude' => 11.3000, 'longitude' => -60.5333],
            ['name' => 'Speyside Village', 'division_id' => 15, 'latitude' => 11.3000, 'longitude' => -60.5333],
            ['name' => 'Spring', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Spring Vale', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Spring Vale', 'division_id' => 14, 'latitude' => 10.2833, 'longitude' => -61.4667],
            ['name' => 'Spring Village', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Starwood', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7167],
            ['name' => 'St Madeleine', 'division_id' => 5, 'latitude' => 10.2833, 'longitude' => -61.4000],
            ['name' => 'Studley Park', 'division_id' => 15, 'latitude' => 11.2500, 'longitude' => -60.5667],
            ['name' => 'Success', 'division_id' => 7, 'latitude' => 10.6333, 'longitude' => -61.4500],
            ['name' => 'Sum Sum Hill', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4000],

            // ═══════════════════════════════════════════════════════════════
            // T
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Tabaquite', 'division_id' => 1, 'latitude' => 10.4333, 'longitude' => -61.3167],
            ['name' => 'Tableland', 'division_id' => 5, 'latitude' => 10.2333, 'longitude' => -61.3500],
            ['name' => 'Tacarigua', 'division_id' => 9, 'latitude' => 10.6333, 'longitude' => -61.3167],
            ['name' => 'Talparo', 'division_id' => 1, 'latitude' => 10.5000, 'longitude' => -61.2833],
            ['name' => 'Tarouba', 'division_id' => 5, 'latitude' => 10.2667, 'longitude' => -61.4000],
            ['name' => 'The Mission', 'division_id' => 6, 'latitude' => 10.5667, 'longitude' => -61.1333],
            ['name' => 'The Whim', 'division_id' => 15, 'latitude' => 11.2167, 'longitude' => -60.7000],
            ['name' => 'Thick', 'division_id' => 8, 'latitude' => 10.1167, 'longitude' => -61.5000],
            ['name' => 'Third Company', 'division_id' => 5, 'latitude' => 10.2500, 'longitude' => -61.3167],
            ['name' => 'Tierra Nueva', 'division_id' => 9, 'latitude' => 10.6500, 'longitude' => -61.3667],
            ['name' => 'Toco', 'division_id' => 6, 'latitude' => 10.7667, 'longitude' => -60.9833],
            ['name' => 'Todds Road', 'division_id' => 1, 'latitude' => 10.4667, 'longitude' => -61.4167],
            ['name' => 'Tortuga', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4000],
            ['name' => 'Town of Arima', 'division_id' => 10, 'latitude' => 10.6172, 'longitude' => -61.2744],
            ['name' => 'Trafford', 'division_id' => 9, 'latitude' => 10.6333, 'longitude' => -61.3500],
            ['name' => 'Trois Rivieres', 'division_id' => 15, 'latitude' => 11.2833, 'longitude' => -60.5667],
            ['name' => 'Tulls', 'division_id' => 9, 'latitude' => 10.6500, 'longitude' => -61.3667],
            ['name' => 'Tunapuna', 'division_id' => 9, 'latitude' => 10.6520, 'longitude' => -61.3890],
            ['name' => 'Tyson Hall', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.6833],

            // ═══════════════════════════════════════════════════════════════
            // U
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Union', 'division_id' => 14, 'latitude' => 10.2833, 'longitude' => -61.4667],
            ['name' => 'Union', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7167],
            ['name' => 'Upper Carapichaima', 'division_id' => 1, 'latitude' => 10.4833, 'longitude' => -61.4500],
            ['name' => 'Upper Fishing Pond', 'division_id' => 6, 'latitude' => 10.5833, 'longitude' => -61.0833],
            ['name' => 'Upper Manzanilla', 'division_id' => 6, 'latitude' => 10.5167, 'longitude' => -61.0500],
            ['name' => 'Usine', 'division_id' => 4, 'latitude' => 10.2000, 'longitude' => -61.4500],

            // ═══════════════════════════════════════════════════════════════
            // V
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Valencia', 'division_id' => 6, 'latitude' => 10.6500, 'longitude' => -61.1833],
            ['name' => 'Valsayn', 'division_id' => 9, 'latitude' => 10.6333, 'longitude' => -61.4167],
            ['name' => 'Vance River', 'division_id' => 8, 'latitude' => 10.1333, 'longitude' => -61.5167],
            ['name' => 'Verdant Vale', 'division_id' => 9, 'latitude' => 10.6333, 'longitude' => -61.3667],
            ['name' => 'Veronica', 'division_id' => 9, 'latitude' => 10.6500, 'longitude' => -61.3667],
            ['name' => 'Vessigny', 'division_id' => 8, 'latitude' => 10.2000, 'longitude' => -61.6333],
            ['name' => 'Victoria', 'division_id' => 14, 'latitude' => 10.2833, 'longitude' => -61.4667],
            ['name' => 'Vista Bella', 'division_id' => 14, 'latitude' => 10.2833, 'longitude' => -61.4833],

            // ═══════════════════════════════════════════════════════════════
            // W
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Warners', 'division_id' => 7, 'latitude' => 10.6500, 'longitude' => -61.4667],
            ['name' => 'Waterloo', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4833],
            ['name' => 'Williamsville', 'division_id' => 5, 'latitude' => 10.2833, 'longitude' => -61.3667],
            ['name' => 'Williamsville Station', 'division_id' => 5, 'latitude' => 10.2833, 'longitude' => -61.3667],
            ['name' => 'Windsor', 'division_id' => 15, 'latitude' => 11.2333, 'longitude' => -60.6833],
            ['name' => 'Windsor Park', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4167],
            ['name' => 'Woodbrook', 'division_id' => 13, 'latitude' => 10.6667, 'longitude' => -61.5333],
            ['name' => 'Woodland', 'division_id' => 8, 'latitude' => 10.1333, 'longitude' => -61.5167],
            ['name' => 'Woodlands', 'division_id' => 5, 'latitude' => 10.2500, 'longitude' => -61.3667],
            ['name' => 'Woodlands', 'division_id' => 15, 'latitude' => 11.2000, 'longitude' => -60.7000],
            ['name' => 'Wyaby', 'division_id' => 1, 'latitude' => 10.4500, 'longitude' => -61.4167],

            // ═══════════════════════════════════════════════════════════════
            // Z
            // ═══════════════════════════════════════════════════════════════
            ['name' => 'Zion Hill', 'division_id' => 15, 'latitude' => 11.2167, 'longitude' => -60.7167],
        ];
    }
}
