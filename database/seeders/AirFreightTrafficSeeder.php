<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AirFreightTraffic;

class AirFreightTrafficSeeder extends Seeder
{
    public function run()
    {
        $types = ['Pesawat', 'Penumpang', 'Penumpang Transit', 'Kargo', 'Bagasi', 'Pos'];
        $years = [ 2025];

        foreach ($years as $year) {
            for ($month = 1; $month <= 2; $month++) {
                foreach ($types as $type) {
                    AirFreightTraffic::create([
                        'date' => \Carbon\Carbon::create($year, rand(1,12), 1),
                        'type' => $type,
                        'arrival' => rand(1, 10),
                        'departure' => rand(1, 10),
                    ]);
                }
            }
        }
    }
}
