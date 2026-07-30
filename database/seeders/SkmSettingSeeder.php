<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class SkmSettingSeeder extends Seeder
{
    /**
     * Nilai awal tautan Survei Kepuasan Masyarakat.
     * Memakai updateOrCreate, BUKAN truncate, karena tabel settings dipakai
     * bersama oleh pengaturan hero dan teks profil bandara.
     */
    public function run(): void
    {
        $defaults = [
            'skm_url' => 'https://skm.dephub.go.id/ly/ApfkINxw',
            'skm_label' => 'Isi Survei Kepuasan Masyarakat',
            'skm_is_active' => '1',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Cache::forget('skm_setting');
    }
}
