<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AirTrafficLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FebruariLLAUSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Path ke file CSV. Pastikan nama filenya sesuai.
        // Saya sarankan ubah nama file menjadi 'februari_llau.csv' agar lebih mudah.
        $csvFile = database_path('seeders/agustus_llau.csv'); 

        if (!file_exists($csvFile)) {
            $this->command->error("File CSV tidak ditemukan di: $csvFile");
            return;
        }

        $file = fopen($csvFile, 'r');
        $rowNumber = 0;

        while (($line = fgetcsv($file, 0, ';')) !== FALSE) { // <<< PERUBAHAN: Tambahkan delimiter ';'
            $rowNumber++;

            // Lewati header (baris 1-9)
            if ($rowNumber < 10) {
                continue;
            }

            // 1. Cek jumlah kolom. 
            // Karena pemisah ';', PHP akan membaca seluruh baris sebagai 1 kolom jika pemisahnya salah.
            // Jadi pengecekan ini sangat penting.
            if (count($line) < 12) {
                continue;
            }

            // 2. Cek kolom pertama (Tanggal/No).
            if (empty($line[0])) {
                continue;
            }

            // 3. Cek jika ini baris 'TOTAL' atau 'BADAN LAYANAN...' (jika ada di tengah)
            $firstCol = strtolower(trim($line[0]));
            if ($firstCol == 'total' || !is_numeric($firstCol)) {
                continue;
            }

            // Mapping data
            $day = (int) $this->cleanNumber($line[0]);
            
            // Validasi tanggal bulan Februari
            if ($day < 1 || $day > 31) { 
                 continue;
            }

            try {
                $date = Carbon::create(2025, 8, $day)->toDateString();
            } catch (\Exception $e) {
                continue;
            }

            // Data Pesawat
            $aircraft_arrival = $this->cleanNumber($line[1]);
            $aircraft_departure = $this->cleanNumber($line[2]);

            // Data Penumpang (Arr=4, Dept=5) - Kolom 3 adalah Jumlah
            $passenger_arrival = $this->cleanNumber($line[4]);
            $passenger_departure = $this->cleanNumber($line[5]);

            // Data Bagasi (Arr=7, Dept=8) - Kolom 6 adalah Jumlah
            $baggage_arrival = $this->cleanNumber($line[7]);
            $baggage_departure = $this->cleanNumber($line[8]);

            // Data Kargo (Arr=10, Dept=11) - Kolom 9 adalah Jumlah
            $cargo_arrival = $this->cleanNumber($line[10]);
            $cargo_departure = $this->cleanNumber($line[11]);

            AirTrafficLog::updateOrCreate(
                ['date' => $date],
                [
                    'aircraft_arrival' => $aircraft_arrival,
                    'aircraft_departure' => $aircraft_departure,
                    'passenger_arrival' => $passenger_arrival,
                    'passenger_departure' => $passenger_departure,
                    'baggage_arrival' => $baggage_arrival,
                    'baggage_departure' => $baggage_departure,
                    'cargo_arrival' => $cargo_arrival,
                    'cargo_departure' => $cargo_departure,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        fclose($file);
        $this->command->info('Data LLAU Februari 2025 berhasil diimpor!');
    }

    private function cleanNumber($value)
    {
        if (!isset($value)) return 0;
        
        $value = trim($value);
        
        // Hapus titik (pemisah ribuan). 
        // Contoh: "1.044" -> "1044"
        $value = str_replace('.', '', $value);
        
        // Hapus karakter non-angka lainnya
        $value = preg_replace('/[^0-9]/', '', $value);

        // Jika kosong setelah dibersihkan, kembalikan 0
        if ($value === '') return 0;

        return (int) $value;
    }
}