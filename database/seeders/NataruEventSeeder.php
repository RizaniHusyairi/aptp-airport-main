<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NataruEvent;
use App\Models\NataruFlight;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class NataruEventSeeder extends Seeder
{
    public function run()
    {
        // Bersihkan data lama (Opsional, hati-hati jika sudah ada data riil)
        // DB::table('nataru_flights')->truncate();
        // DB::table('nataru_events')->truncate();

        $this->command->info('Membuat data dummy Posko Nataru...');

        // --- 1. Buat Event Tahun Lalu (2023/2024) ---
        // Periode: 19 Des 2023 (H-6 Natal) s/d 4 Jan 2024 (H+3 Tahun Baru)
        // Kita asumsikan H-0 Natal adalah 25 Des.
        $startDate23 = Carbon::create(2023, 12, 15); // H-10
        $endDate23   = Carbon::create(2024, 1, 4);   // H+10
        
        $event23 = NataruEvent::create([
            'name' => 'Posko Nataru 2023/2024',
            'start_date' => $startDate23,
            'end_date' => $endDate23,
            'description' => 'Data historis untuk pembanding.',
            'is_active' => false, // Sudah selesai
        ]);

        $this->generateFlightsForEvent($event23);

        // --- 2. Buat Event Tahun Ini (2024/2025) ---
        $startDate24 = Carbon::create(2024, 12, 15); // H-10
        $endDate24   = Carbon::create(2025, 1, 4);   // H+10

        $event24 = NataruEvent::create([
            'name' => 'Posko Nataru 2024/2025',
            'start_date' => $startDate24,
            'end_date' => $endDate24,
            'description' => 'Posko monitoring tahun ini.',
            'is_active' => true,
            'compare_event_id' => $event23->id, // Set pembanding otomatis ke tahun lalu
        ]);

        $this->generateFlightsForEvent($event24);
        
        $this->command->info('Selesai! Data dummy berhasil dibuat.');
    }

    /**
     * Fungsi helper untuk generate penerbangan harian
     */
    private function generateFlightsForEvent($event)
    {
        $startDate = Carbon::parse($event->start_date);
        $endDate = Carbon::parse($event->end_date);
        
        // Loop dari hari pertama sampai terakhir
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            
            // Tentukan jumlah penerbangan per hari (acak antara 10 - 20 flight)
            // Kita buat tahun 2024 sedikit lebih ramai dari 2023 untuk simulasi pertumbuhan
            $flightCount = rand(10, 20);
            if ($event->name == 'Posko Nataru 2024/2025') {
                $flightCount += rand(2, 5); 
            }

            for ($i = 0; $i < $flightCount; $i++) {
                $this->createOneFlight($event->id, $date->copy());
            }
        }
    }

    private function createOneFlight($eventId, $date)
    {
        $airlines = [
            ['code' => 'ID', 'name' => 'Batik Air', 'type' => 'A320', 'cap' => 150],
            ['code' => 'JT', 'name' => 'Lion Air', 'type' => 'B737-900', 'cap' => 200],
            ['code' => 'QG', 'name' => 'Citilink', 'type' => 'A320', 'cap' => 180],
            ['code' => 'IU', 'name' => 'Super Air Jet', 'type' => 'A320', 'cap' => 180],
            ['code' => 'IW', 'name' => 'Wings Air', 'type' => 'ATR-72', 'cap' => 72],
            ['code' => 'SI', 'name' => 'Susi Air', 'type' => 'Cessna', 'cap' => 12], // Perintis
        ];

        $routes = ['CGK', 'SUB', 'YIA', 'BPN', 'BEJ', 'MHU']; // Asumsi AAP adalah homebase

        $airline = $airlines[array_rand($airlines)];
        $route = $routes[array_rand($routes)];
        $direction = rand(0, 1) ? 'arrival' : 'departure';
        
        // Tentukan Route String (From-To)
        $routeString = ($direction == 'departure') ? "AAP-$route" : "$route-AAP";
        
        // Status penerbangan
        $status = ($airline['name'] == 'Susi Air') ? 'Perintis' : 'Berjadwal';

        // Generate Pax (Penumpang)
        // Random occupancy antara 70% - 100%
        $occupancy = rand(70, 100) / 100;
        $totalPax = round($airline['cap'] * $occupancy);
        
        // Pecah pax (Dewasa, Anak, Bayi)
        $paxInfant = rand(0, 3);
        $paxChild = rand(0, 10);
        $paxAdult = $totalPax - $paxChild; // Infant tidak mengurangi seat biasanya, tapi utk simplifikasi kita anggap totalPax = kursi terisi + infant

        // Generate Cargo & Bagasi
        $cargo = rand(0, 500); // kg
        $baggage = $totalPax * rand(10, 15); // kg

        // Harga Tiket (Randomize sekitar 1jt - 2jt)
        $priceHigh = rand(1500000, 2500000);
        $priceLow = $priceHigh - rand(200000, 500000);

        NataruFlight::create([
            'nataru_event_id' => $eventId,
            'flight_date' => $date->format('Y-m-d'),
            'flight_time' => sprintf("%02d:%02d", rand(6, 21), rand(0, 59)), // Jam 06:00 - 21:59
            'airline' => $airline['name'],
            'flight_number' => $airline['code'] . '-' . rand(1000, 9999),
            'aircraft_type' => $airline['type'],
            'aircraft_registration' => 'PK-' . strtoupper(\Illuminate\Support\Str::random(3)),
            'direction' => $direction,
            'route' => $routeString,
            'status_flight' => $status,
            
            'pax_adult' => $paxAdult,
            'pax_child' => $paxChild,
            'pax_infant' => $paxInfant,
            'pax_total' => ($paxAdult + $paxChild + $paxInfant),
            
            'cargo' => $cargo,
            'baggage' => $baggage,
            'load_factor' => ($occupancy * 100), // Dalam persen

            'ticket_price_high' => $priceHigh,
            'ticket_price_low' => $priceLow,

            'officer_name' => 'System Seeder',
            'remarks' => 'Data Dummy',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
