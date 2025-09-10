<?php

namespace Database\Seeders;

use App\Models\InformationServiceReport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InformationServiceReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mengosongkan tabel untuk mencegah data duplikat jika seeder dijalankan lagi
        DB::table('information_service_reports')->truncate();

        InformationServiceReport::create([
            'title' => 'Laporan Tahunan PPID 2024',
            'publication_year' => 2024,
            'document_link' => 'https://drive.google.com/file/d/1_mgSX3_tW0V8m9QkbkZj_HDONLFemz3k/view?usp=drive_link',
        ]);

        // Anda bisa menambahkan data laporan untuk tahun-tahun lain di sini
        // Contoh:
        // InformationServiceReport::create([
        //     'title' => 'Laporan Tahunan PPID 2023',
        //     'publication_year' => 2023,
        //     'document_link' => 'https://link-drive-laporan-2023.com',
        // ]);
    }
}
