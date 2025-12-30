<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SlotSeeder extends Seeder
{
    public function run()
    {
        Service::create([
            'name' => 'Sertifikat OJT',
            'slug' => 'sertifikat-ojt',
            'title' => 'Syarat & Ketentuan Pengajuan Sertifikat OJT',
            'requirements' => [
                "KTP/Kartu Pelajar/Kartu Mahasiswa", "Pas Foto Berlatar Belakang Merah Menggunakan Kemeja Putih",
            ],
            'steps' => [
                "Mengajukan surat permohonan kepada Kabandara",
                "Verifikasi dokumen dan persyaratan oleh petugas pengembangan usaha",
                "Melengkapi administrasi dan kontrak jika disetujui"
            ],
            'submission_url' => 'dashboard/informasi-publik',
        ]);
    }
}
