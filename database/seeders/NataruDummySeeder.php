<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NataruEvent;
use App\Models\NataruFlight;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class NataruDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        


        // 2. BUAT EVENT TAHUN INI (2025/2026) - SESUAI REQUEST
        $startCurrent = '2025-12-18';
        $endCurrent   = '2026-01-05';

        $eventCurrent = NataruEvent::create([
            'name' => 'Posko Nataru 2025/2026',
            'start_date' => $startCurrent,
            'end_date' => $endCurrent,
            'description' => 'Monitoring Posko Nataru tahun ini.',
            'is_active' => true,
            'public_token' => Str::random(32), // Token untuk akses publik
            'compare_event_id' => null, // Link ke tahun lalu
        ]);

        $this->command->info('Generating flights for Current Year (2025/2026)... please wait.');
        $this->generateFlights($eventCurrent, 1.1); // 1.1 multiplier biar datanya terlihat NAIK (hijau)

        $this->command->info('Seeding Selesai! Token Public Tahun Ini: ' . $eventCurrent->public_token);
    }

    /**
     * Helper untuk generate flight harian
     */
    private function generateFlights($event, $multiplier)
    {
        $period = CarbonPeriod::create($event->start_date, $event->end_date);
        
        $airlines = ['Batik Air', 'Citilink', 'Super Air Jet', 'Garuda Indonesia', 'Wings Air', 'Lion Air'];
        $routes = ['CGK', 'SUB', 'YIA', 'BPN', 'DPS', 'BDJ', 'UPG'];

        $dataBatch = [];

        foreach ($period as $date) {
            // Random jumlah penerbangan per hari (misal 10 s/d 18 flight)
            $dailyFlightCount = mt_rand(10, 18);

            for ($i = 0; $i < $dailyFlightCount; $i++) {
                
                $airline = $airlines[array_rand($airlines)];
                $routeCode = $routes[array_rand($routes)];
                $direction = mt_rand(0, 1) ? 'arrival' : 'departure';
                
                // Rute Format
                $route = ($direction == 'arrival') ? "$routeCode-AAP" : "AAP-$routeCode";

                // Random Waktu (06:00 - 18:00)
                $hour = str_pad(mt_rand(6, 18), 2, '0', STR_PAD_LEFT);
                $minute = str_pad(mt_rand(0, 59), 2, '0', STR_PAD_LEFT);
                
                // Random Data Penumpang (Dikalikan multiplier biar tahun ini lebih tinggi)
                $seatCap = ($airline == 'Wings Air') ? 72 : 180;
                $loadFactorRand = mt_rand(60, 95) / 100; // 60% - 95%
                
                $totalPax = round(($seatCap * $loadFactorRand) * $multiplier);
                // Pastikan tidak melebihi seat
                if($totalPax > $seatCap) $totalPax = $seatCap;

                $child = mt_rand(0, 5);
                $infant = mt_rand(0, 2);
                $adult = $totalPax - $child - $infant;
                if($adult < 0) $adult = 0;

                // Cargo & Harga
                $cargo = round(mt_rand(0, 2000) * $multiplier);
                $priceHigh = round(mt_rand(1200000, 2500000), -3); // Bulatkan ke ribuan
                $priceLow = round(mt_rand(600000, 1100000), -3);

                $dataBatch[] = [
                    'nataru_event_id' => $event->id,
                    'flight_date' => $date->format('Y-m-d'),
                    'flight_time' => "$hour:$minute:00",
                    'airline' => $airline,
                    'flight_number' => substr($airline, 0, 2) . '-' . mt_rand(1000, 9999),
                    'status_flight' => 'Berjadwal',
                    'route' => $route,
                    'direction' => $direction,
                    'aircraft_type' => ($airline == 'Wings Air') ? 'ATR-72' : 'A320',
                    'aircraft_registration' => 'PK-' . Str::upper(Str::random(3)),
                    
                    'pax_adult' => $adult,
                    'pax_child' => $child,
                    'pax_infant' => $infant,
                    'pax_total' => ($adult + $child + $infant),
                    
                    'cargo' => $cargo,
                    'baggage' => mt_rand(500, 1500),
                    'load_factor' => round((($adult+$child)/$seatCap)*100, 2),
                    
                    'ticket_price_high' => $priceHigh,
                    'ticket_price_low' => $priceLow,
                    
                    'officer_name' => 'System Seeder',
                    'remarks' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert Batch biar cepat (Chunk per 50 data)
        foreach (array_chunk($dataBatch, 50) as $chunk) {
            NataruFlight::insert($chunk);
        }
    }
}