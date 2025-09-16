<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExtendAdvanceSetting;

class ExtendAdvanceSettingSeeder extends Seeder
{
    public function run()
    {
        $defaultStatement = "Mendasari NOTAM Nomor C1070/25 NOTAMN (Perubahan Sementara Jam Operasi Bandara), Bandar Udara APT Pranoto Samarinda hanya melayani penerbangan yang beroperasi mulai pukul 23.00 s/d 12.00 UTC. Bila kegiatan operasi penerbangan dilaksanakan setelah pukul 12.00 UTC atau sebelum pukul 23.00 UTC, maka segala resiko menjadi tanggung jawab pihak maskapai yang dalam hal ini oleh Pilot In Command (PIC).";

        ExtendAdvanceSetting::updateOrCreate(
            ['key' => 'statement_notes'],
            ['value' => $defaultStatement]
        );
    }
}
