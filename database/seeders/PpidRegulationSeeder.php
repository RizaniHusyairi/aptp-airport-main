<?php

namespace Database\Seeders;

use App\Models\PpidRegulation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PpidRegulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mengosongkan tabel untuk mencegah duplikasi data saat seeder dijalankan kembali
        DB::table('ppid_regulations')->truncate();

        $regulations = [
            // Kategori Peraturan Undang-undang
            [
                'category' => 'Peraturan Undang-undang',
                'title' => 'Undang-Undang Nomor 25 Tahun 2009 tentang Pelayanan Publik',
                'published_date' => '2009-07-18',
                'document_link' => 'https://drive.google.com/file/d/1YxQPvR9MT8wxQVhapac-V0eER0qjC8iU/view?usp=drive_link',
            ],
            [
                'category' => 'Peraturan Undang-undang',
                'title' => 'Undang-Undang Nomor 43 Tahun 2009 tentang Kearsipan',
                'published_date' => '2009-10-23',
                'document_link' => 'https://drive.google.com/file/d/1Hp20E3TsDN-yXq3BKK-2zoA-6GMndcrK/view?usp=drive_link',
            ],
            [
                'category' => 'Peraturan Undang-undang',
                'title' => 'Undang-Undang Nomor 14 Tahun 2008 tentang Keterbukaan Informasi Publik',
                'published_date' => '2008-04-30',
                'document_link' => 'https://drive.google.com/file/d/1nh2_N9rpuPH5xXZo4UNCHtmmZyQK7Pqn/view?usp=drive_link',
            ],

            // Kategori Peraturan Komisi Informasi Pusat
            [
                'category' => 'Peraturan Komisi Informasi Pusat',
                'title' => 'Peraturan Komisi Informasi Pusat Nomor 1 Tahun 2021 Tentang Standar Layanan Informasi Publik',
                'published_date' => '2021-06-30',
                'document_link' => 'https://drive.google.com/file/d/1efgk9QoriElK3vLceRA8xN2kAt9WhNoy/view?usp=drive_link',
            ],
            [
                'category' => 'Peraturan Komisi Informasi Pusat',
                'title' => 'Peraturan Komisi Informasi Pusat Nomor 1 Tahun 2013 Tentang Prosedur Penyelesaian Sengketa Informasi Publik',
                'published_date' => '2013-04-29',
                'document_link' => 'https://drive.google.com/file/d/1Rw3Z_dkGAGXHglG_Gbc92QS2L-pDC3bJ/view?usp=drive_link',
            ],

            // Kategori Peraturan Kementrian Perhubungan
            [
                'category' => 'Peraturan Kementrian Perhubungan Terkait Keterbukaan Informasi Publik',
                'title' => 'Peraturan Menteri Perhubungan Nomor PM 46 Tahun 2018 tentang Pedoman Pengelolaan Informasi dan Dokumentasi di Lingkungan Kementerian Perhubungan',
                'published_date' => '2018-05-22',
                'document_link' => 'https://drive.google.com/file/d/1RY9_1phqfBRVdq1llTc7_Fqkrsmw6wGQ/view?usp=drive_link',
            ],
            [
                'category' => 'Peraturan Kementrian Perhubungan Terkait Keterbukaan Informasi Publik',
                'title' => 'Keputusan Menteri Perhubungan Nomor KM 117 Tahun 2022 tentang SOP Pejabat Pengelola Informasi dan Dokumentasi di Lingkungan Kementerian Perhubungan',
                'published_date' => '2022-07-15',
                'document_link' => 'https://drive.google.com/file/d/1zS667DcshXtCDA200KKTa1IbJw4n7JgC/view?usp=drive_link',
            ],
            [
                'category' => 'Peraturan Kementrian Perhubungan Terkait Keterbukaan Informasi Publik',
                'title' => 'Keputusan Sekretaris Jenderal Kementerian Perhubungan Nomor KP-SKJ 25 Tahun 2024 Tentang Daftar Informasi Publik Tahun 2024',
                'published_date' => '2024-05-29',
                'document_link' => 'https://drive.google.com/file/d/1XQpGdqfDuRjcPdvEZbazB2l7IzxaXS7N/view?usp=drive_link',
            ],
            [
                'category' => 'Peraturan Kementrian Perhubungan Terkait Keterbukaan Informasi Publik',
                'title' => 'Keputusan Sekretaris Jenderal Nomor KP 591 Tahun 2023 tentang Informasi Yang Dikecualikan',
                'published_date' => '2025-07-18',
                'document_link' => 'https://drive.google.com/file/d/1grsb2RqicPKw2V9hjTiCrN6kP66bqPkq/view?usp=drive_link',
            ],
        ];

        // Memasukkan data ke dalam database menggunakan model
        foreach ($regulations as $regulation) {
            PpidRegulation::create($regulation);
        }
    }
}
