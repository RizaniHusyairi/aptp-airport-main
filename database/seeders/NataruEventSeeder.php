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
        // 1. Pastikan Event Ada
        // Kita gunakan logika firstOrCreate agar tidak duplikat jika dijalankan ulang
        $event = NataruEvent::firstOrCreate(
            ['name' => 'Posko Nataru 2024/2025'],
            [
                'start_date' => '2024-12-19', // Sesuaikan dengan tanggal awal data di CSV
                'end_date' => '2025-01-06',   // Sesuaikan dengan tanggal akhir data di CSV
                'description' => 'Data Real Posko Nataru 2024/2025 (Imported from CSV)',
                'is_active' => true,
            ]
        );

        $this->command->info("Event: {$event->name} (ID: {$event->id}) siap.");

        // 2. Baca File CSV
        // Pastikan Anda sudah menyimpan file CSV dengan nama 'posko_nataru_2024.csv' 
        // di folder 'database/seeders/data/'
        $csvFile = database_path('seeders/nataru_2024_2025.csv'); 

        if (!file_exists($csvFile)) {
            $this->command->error("File CSV tidak ditemukan di: $csvFile");
            $this->command->info("Silakan buat folder 'database/seeders/data/' dan letakkan file CSV Anda di sana dengan nama 'posko_nataru_2024.csv'.");
            return;
        }

        $file = fopen($csvFile, 'r');
        $header = fgetcsv($file); // Skip baris header

        $count = 0;
        
        DB::beginTransaction();
        try {
            while (($row = fgetcsv($file)) !== false) {
                // Mapping index kolom berdasarkan struktur CSV Anda:
                // 0: Timestamp, 1: Tanggal, 2: Pesawat, 3: Status (Arah), 4: Jml Penumpang
                // 5: Jml Cargo, 6: Load Factor, 7: Harga Tiket Tertinggi, 8: Nama Petugas
                // 9: Remark, 10: Destination, 11: Kode Penerbangan, 12: Harga Tiket Terendah

                // Skip baris kosong
                if (empty($row[1]) || empty($row[2])) continue;

                // --- Parsing Data ---
                
                // Tanggal & Waktu
                // Format di CSV: "18/12/2024 8:00:09" atau "18/12/2024"
                try {
                    // Coba parse timestamp lengkap dulu
                    $timestamp = Carbon::createFromFormat('d/m/Y H:i:s', $row[0]);
                    $flightDate = $timestamp->format('Y-m-d');
                    $flightTime = $timestamp->format('H:i:s');
                } catch (\Exception $e) {
                    try {
                         // Fallback jika format beda, misal "2024-12-18"
                         $flightDate = Carbon::parse($row[1])->format('Y-m-d');
                         // Jika jam tidak ada di timestamp, kita random atau set default/ambil dari col 0 jika formatnya jam
                         $flightTime = '00:00:00'; 
                         // Coba extract jam dari col 0 jika mungkin
                         if(strpos($row[0], ':') !== false) {
                             $flightTime = Carbon::parse($row[0])->format('H:i:s');
                         }
                    } catch (\Exception $e2) {
                        $this->command->warn("Gagal parse tanggal baris ke-{$count}: " . $row[0]);
                        continue; 
                    }
                }

                // Bersihkan Angka (Hapus "Rp", ".", spasi)
                $pax = (int) preg_replace('/[^0-9]/', '', $row[4]);
                $cargo = (int) preg_replace('/[^0-9]/', '', $row[5]);
                $loadFactor = (float) $row[6];
                
                $priceHigh = (float) preg_replace('/[^0-9]/', '', $row[7]);
                $priceLow = (float) preg_replace('/[^0-9]/', '', $row[12] ?? '0'); // Kolom 12 mungkin kosong

                // Tentukan Arah (Arrival/Departure)
                // Di CSV kolom 'Status' berisi "Arrival" atau "Departure"
                $directionRaw = strtolower(trim($row[3]));
                $direction = ($directionRaw == 'arrival') ? 'arrival' : 'departure';

                // Tentukan Status Penerbangan (Berjadwal/Perintis)
                // Di CSV ada di kolom 'Remark' (col 9) atau kadang 'Status' tapi disini Status dipakai buat arah.
                // Mari cek kolom Remark. Isinya: "Berjadwal", "Perintis".
                $statusFlight = $row[9] ?? 'Berjadwal';

                // Tentukan Kategori Penumpang (Estimasi karena CSV cuma punya Total)
                // Kita masukkan semua ke pax_adult sementara, atau bagi rata jika mau simulasi.
                // Agar data akurat sesuai total, kita set:
                $paxAdult = $pax;
                $paxChild = 0;
                $paxInfant = 0;

                NataruFlight::create([
                    'nataru_event_id' => $event->id,
                    'flight_date' => $flightDate,
                    'flight_time' => $flightTime,
                    'airline' => $row[2], // Pesawat
                    'flight_number' => $row[11], // Kode Penerbangan
                    'status_flight' => $statusFlight,
                    'route' => $row[10], // Destination (From - To)
                    'direction' => $direction,
                    
                    // Data Pesawat (Default null karena tidak ada di CSV ini)
                    'aircraft_type' => null, 
                    'aircraft_registration' => null,

                    'pax_adult' => $paxAdult,
                    'pax_child' => $paxChild,
                    'pax_infant' => $paxInfant,
                    'pax_total' => $pax,
                    
                    'cargo' => $cargo,
                    'baggage' => 0, // Tidak ada kolom bagasi di CSV ini (hanya cargo)
                    'load_factor' => $loadFactor,

                    'ticket_price_high' => $priceHigh,
                    'ticket_price_low' => $priceLow,

                    'officer_name' => $row[8], // Nama Petugas
                    'remarks' => $row[9], // Remark (kadang berisi status berjadwal)
                    'user_id' => null, // Data historis/import, tidak ada user login spesifik
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $count++;
            }
            
            DB::commit();
            $this->command->info("Berhasil mengimpor {$count} data penerbangan.");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Terjadi kesalahan: " . $e->getMessage());
        }
        
        fclose($file);
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
