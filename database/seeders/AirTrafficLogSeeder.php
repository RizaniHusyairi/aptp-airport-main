<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\AirTrafficLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AirTrafficLogSeeder extends Seeder
{
    public function run()
    {
        // Kosongkan tabel untuk data baru, agar tidak duplikat jika seeder dijalankan lagi
        DB::table('air_traffic_logs')->truncate();

        // Tentukan periode (September dan Oktober 2025)
        $startDate = Carbon::create(2025, 9, 1);
        $endDate = Carbon::create(2025, 10, 31);
        $endDate = Carbon::create(2025, 11, 30);
        
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            
            // Buat data sedikit lebih tinggi di akhir pekan (Jumat, Sabtu, Minggu)
            $isWeekend = $date->isFriday() || $date->isSaturday() || $date->isSunday();
            $multiplier = $isWeekend ? 1.4 : 1.0; // 40% lebih ramai di akhir pekan

            // 1. Pesawat (Base: 10-14 pergerakan)
            $aircraft_arrival = (int) (rand(10, 14) * $multiplier);
            $aircraft_departure = (int) (rand(10, 14) * $multiplier);

            // 2. Penumpang (Base: 100-130 per pesawat)
            $passenger_arrival = (int) ($aircraft_arrival * rand(100, 130) + rand(-100, 100));
            $passenger_departure = (int) ($aircraft_departure * rand(100, 130) + rand(-100, 100));
            
            // Pastikan tidak negatif
            if ($passenger_arrival < 0) $passenger_arrival = 0;
            if ($passenger_departure < 0) $passenger_departure = 0;

            // 3. Bagasi (Base: 12-18kg per penumpang)
            $baggage_arrival = (int) ($passenger_arrival * rand(12, 18));
            $baggage_departure = (int) ($passenger_departure * rand(12, 18));

            // 4. Kargo (Base: 5-15 ton per hari)
            $cargo_arrival = (int) (rand(5000, 15000) * $multiplier);
            $cargo_departure = (int) (rand(5000, 15000) * $multiplier);

            AirTrafficLog::create([
                'date' => $date->toDateString(),
                'aircraft_arrival' => $aircraft_arrival,
                'aircraft_departure' => $aircraft_departure,
                'passenger_arrival' => $passenger_arrival,
                'passenger_departure' => $passenger_departure,
                'baggage_arrival' => $baggage_arrival,
                'baggage_departure' => $baggage_departure,
                'cargo_arrival' => $cargo_arrival,
                'cargo_departure' => $cargo_departure,
                'created_at' => $date, // Gunakan tanggal log sebagai created_at
                'updated_at' => $date,
            ]);
        }
    }
}
