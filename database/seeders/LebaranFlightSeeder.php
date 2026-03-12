<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NataruEvent;
use App\Models\NataruFlight;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LebaranFlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = database_path('seeders/lebaran_2024_2025.csv');

        if (!file_exists($csvFile)) {
            $this->command->error("CSV file not found at: {$csvFile}");
            return;
        }

        // Create the Nataru Event
        $event = NataruEvent::updateOrCreate(
            ['name' => 'Posko Lebaran 2024/2025'],
            [
                'start_date' => '2025-03-20', // Estimate based on data
                'end_date' => '2025-04-12',   // Estimate based on data
                'description' => 'Monitoring Posko Lebaran 2024/2025.',
                'is_active' => true,
                'public_token' => Str::random(32),
            ]
        );

        $this->command->info('Parsing and inserting data from CSV... please wait.');

        $file = fopen($csvFile, 'r');
        
        // Skip header
        $header = fgetcsv($file, 1000, ';');

        $dataBatch = [];
        $count = 0;

        while (($row = fgetcsv($file, 1000, ';')) !== false) {
            // Count must match 13 columns as seen in the CSV:
            // 0: Timestamp
            // 1: Tanggal
            // 2: Pesawat
            // 3: Status 
            // 4: Jumlah Penumpang
            // 5: Jumlah Cargo
            // 6: Load Factor
            // 7: Harga Tiket Tertinggi
            // 8: Nama Petugas Posko
            // 9: Status 2
            // 10: Destination
            // 11: Kode Penerbangan
            // 12: Harga Tiket Terendah

            if (count($row) < 12) {
                continue;
            }

            // Extract Time from Timestamp if available using regex or split
            $timestampRaw = trim($row[0]);
            $flightTime = '00:00:00';
            if (preg_match('/\d{1,2}:\d{2}:\d{2}/', $timestampRaw, $m)) {
                $flightTime = $m[0];
            }

            // Parse Date from Tanggal or Timestamp. Tanggal format varies: '09/04/2025', '22 March 2025'
            $tanggalRaw = trim($row[1]);
            $flightDate = null;
            try {
                if (str_contains($tanggalRaw, '/')) {
                    // Could be d/m/Y or m/d/Y. Let's try parsing safely
                    $parts = explode('/', $tanggalRaw);
                    if (count($parts) == 3) {
                        // Let's assume d/m/Y based on '09/04/2025' and '07/04/2025'
                        // Wait, looking at Timestamp: '4/9/2025 11:50:36' -> m/d/Y
                        // In Timestamp it's 4/9/2025, in Tanggal it's 09/04/2025.
                        // Better use Carbon parse if it's alphanumeric, or try creating from format
                        if(strlen($parts[2]) == 4) {
                            $flightDate = Carbon::createFromFormat('d/m/Y', $tanggalRaw)->format('Y-m-d');
                        }
                    }
                }
                
                if (!$flightDate) {
                    $flightDate = Carbon::parse($tanggalRaw)->format('Y-m-d');
                }
            } catch (\Exception $e) {
                // If parsing fails, skip or use a default
                $flightDate = '2025-01-01'; 
            }

            $airline = trim($row[2]);
            
            $directionRaw = strtolower(trim($row[3]));
            $direction = 'arrival';
            if (str_contains($directionRaw, 'dep') || str_contains($directionRaw, 'berangkat')) {
                $direction = 'departure';
            }

            $paxTotal = (int) trim($row[4]);
            $cargo = (int) trim($row[5]);
            
            $lfRaw = trim($row[6]);
            $lfRaw = str_replace(['%', ','], ['', '.'], $lfRaw);
            $loadFactor = (float) $lfRaw;

            $priceHighRaw = trim($row[7]);
            $priceHighRaw = preg_replace('/[^0-9]/', '', $priceHighRaw);
            $priceHigh = $priceHighRaw === '' ? null : (int) $priceHighRaw;

            $officerName = trim($row[8]);
            
            $statusFlight = trim($row[9]);
            if (empty($statusFlight)) $statusFlight = 'Berjadwal';

            $route = trim($row[10]);
            
            $flightNumber = trim($row[11]);
            
            $priceLowRaw = isset($row[12]) ? trim($row[12]) : null;
            $priceLowRaw = preg_replace('/[^0-9]/', '', $priceLowRaw);
            $priceLow = empty($priceLowRaw) ? null : (int) $priceLowRaw;

            // Optional defaults
            $aircraftType = (stripos($airline, 'wing') !== false) ? 'ATR-72' : 'A320';

            $dataBatch[] = [
                'nataru_event_id' => $event->id,
                'flight_date'     => $flightDate,
                'flight_time'     => $flightTime,
                'airline'         => $airline,
                'flight_number'   => rtrim($flightNumber, ';- '), // Fix any trailing symbols
                'status_flight'   => $statusFlight,
                'route'           => $route,
                'direction'       => $direction,
                'aircraft_type'   => $aircraftType,
                'aircraft_registration' => null,
                'pax_adult'       => $paxTotal,
                'pax_child'       => 0,
                'pax_infant'      => 0,
                'pax_total'       => $paxTotal,
                'cargo'           => $cargo,
                'baggage'         => 0,
                'load_factor'     => $loadFactor,
                'ticket_price_high' => $priceHigh,
                'ticket_price_low'  => $priceLow,
                'officer_name'    => empty($officerName) ? 'Unknown' : rtrim($officerName, ';'),
                'created_at'      => now(),
                'updated_at'      => now(),
            ];

            $count++;
        }

        fclose($file);

        // Chunk insert
        foreach (array_chunk($dataBatch, 50) as $chunk) {
            NataruFlight::insert($chunk);
        }

        $this->command->info("Seeding Lebaran Flights Completed! Total {$count} records inserted.");
    }
}
