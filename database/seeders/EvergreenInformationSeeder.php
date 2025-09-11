<?php

namespace Database\Seeders;

use App\Models\EvergreenInformation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EvergreenInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mengosongkan tabel untuk memastikan data selalu baru saat seeder dijalankan
        DB::table('evergreen_information')->truncate();

        $data = [
            // Kategori SOP Pelayanan Publik
            [
                'category' => 'SOP Pelayanan Publik',
                'title' => 'SOP PENANGANAN PENGADUAN, SARAN DAN MASUKAN',
                'published_date' => '2024-03-27',
                'document_link' => 'https://drive.google.com/file/d/1kNFzGMtnftxVLULUM-yivIERFLgYTo0a/view?usp=drive_link'
            ],
            [
                'category' => 'SOP Pelayanan Publik',
                'title' => 'SOP PENYAMPAIAN INFORMASI ATAS DASAR PERMINTAAN',
                'published_date' => '2024-03-27',
                'document_link' => 'https://drive.google.com/file/d/1bFFsJg45B6K-RivhOZTsM60ka392srUD/view?usp=drive_link'
            ],

            // Kategori Inventaris BMN
            [
                'category' => 'Inventaris BMN',
                'title' => 'Catatan Atas Laporan Barang Milik Negara (Tahun Anggaran 2024)',
                'published_date' => '2025-02-03',
                'document_link' => 'https://drive.google.com/file/d/110LHF69FitT83sWsim10jbsWMOa0CC65/view?usp=drive_link'
            ],

            // Kategori Persuratan
            [
                'category' => 'Persuratan',
                'title' => 'Berita Acara Pengawasan Penyelenggaraan Angkutan Udara PT.Batik Air Indonesia',
                'published_date' => '2023-12-18',
                'document_link' => 'https://drive.google.com/file/d/1v0iiznMLccWIwShjlix1n8GtY_xH4H6V/view?usp=sharing'
            ],
            [
                'category' => 'Persuratan',
                'title' => 'Surat Edaran Tentang Hari Libur Nasional dan Cuti Bersama Hari Raya Natal',
                'published_date' => '2023-12-21',
                'document_link' => 'https://drive.google.com/file/d/1ziPRlVjWaW7310xZu8eEN3KXi7BolM77/view?usp=sharing'
            ],
            [
                'category' => 'Persuratan',
                'title' => 'Pembukaan OJT Mahasiswa/i Prodi D3 TNU XV dan Prodi D3 MTU VIII Politeknik Penerbangan Surabaya',
                'published_date' => '2024-12-06',
                'document_link' => 'https://drive.google.com/file/d/165e2Yg1kPzOYezyLyR5A4ygcNnAu3QOB/view?usp=sharing'
            ],
            [
                'category' => 'Persuratan',
                'title' => 'Surat Edaran Tentang Hari Libur Nasional dan Cuti Bersama Tahun 2024',
                'published_date' => '2024-12-24',
                'document_link' => 'https://drive.google.com/file/d/1J9Z-gj5ViIO48ww7Sj-IVzxAbcHfe7KH/view?usp=sharing'
            ],
            [
                'category' => 'Persuratan',
                'title' => 'Hasil Cek Fisik Kendaraan Minibus PT. Indonesia Logistik Service (ILS)',
                'published_date' => '2025-05-19',
                'document_link' => 'https://drive.google.com/file/d/1OuEX5APUh5nyasHvUeMT20O0Fe8rlao9/view?usp=sharing'
            ],
            [
                'category' => 'Persuratan',
                'title' => 'Surat Edaran Tentang Hari Libur Nasional Dalam Rangka Hari Wafat Yesus Kristus',
                'published_date' => '2025-04-17',
                'document_link' => 'https://drive.google.com/file/d/1ocTc6JjepNhIFiKYY1F7dz1BSQM1xywb/view?usp=sharing'
            ],
        ];

        // Memasukkan semua data ke dalam database menggunakan model
        foreach ($data as $item) {
            EvergreenInformation::create($item);
        }
    }
}

