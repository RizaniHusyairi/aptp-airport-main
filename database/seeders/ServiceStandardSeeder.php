<?php

namespace Database\Seeders;

use App\Models\ServiceStandard;
use Illuminate\Database\Seeder;

class ServiceStandardSeeder extends Seeder
{
    /**
     * Data contoh agar halaman publik Standar Pelayanan tidak kosong.
     * Dokumen sesungguhnya diunggah/ditautkan melalui dashboard admin.
     *
     * Idempoten: updateOrCreate berdasarkan `title`, sehingga aman dijalankan
     * ulang di server tanpa menghapus dokumen yang sudah diunggah admin.
     */
    public function run(): void
    {
        $standards = [
            [
                'type' => 'Standar Pelayanan',
                'title' => 'Standar Pelayanan Publik Bandar Udara APT Pranoto',
                'document_number' => 'SK.01/APTP/2026',
                'description' => 'Tolok ukur penyelenggaraan pelayanan di Bandar Udara APT Pranoto Samarinda.',
                'document_link' => 'https://drive.google.com/drive/folders/example-standar-pelayanan',
                'published_date' => '2026-01-15',
                'is_active' => true,
            ],
            [
                'type' => 'Maklumat Pelayanan',
                'title' => 'Maklumat Pelayanan Bandar Udara APT Pranoto',
                'document_number' => 'SK.02/APTP/2026',
                'description' => 'Pernyataan kesanggupan menyelenggarakan pelayanan sesuai standar yang ditetapkan.',
                'document_link' => 'https://drive.google.com/drive/folders/example-maklumat-pelayanan',
                'published_date' => '2026-01-15',
                'is_active' => true,
            ],
            [
                'type' => 'Survei Kepuasan Masyarakat',
                'title' => 'Laporan Survei Kepuasan Masyarakat Semester I Tahun 2026',
                'document_number' => null,
                'description' => 'Hasil pengukuran tingkat kepuasan pengguna jasa sebagai bahan evaluasi pelayanan.',
                'document_link' => 'https://drive.google.com/drive/folders/example-skm-2026',
                'published_date' => '2026-07-01',
                'is_active' => true,
            ],
        ];

        foreach ($standards as $standard) {
            ServiceStandard::updateOrCreate(['title' => $standard['title']], $standard);
        }
    }
}
