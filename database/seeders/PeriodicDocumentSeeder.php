<?php

namespace Database\Seeders;

use App\Models\PeriodicDocument;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodicDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel terlebih dahulu agar tidak ada data duplikat
        DB::table('periodic_documents')->truncate();

        $documents = [
            [
                'category' => 'Survey Kepuasan',
                'title' => 'SPAK dan SPKP 2024',
                'published_date' => '2025-04-20',
                'document_path' => 'https://drive.google.com/file/d/1xxnsH3zXXW-ihucKAKU3O5dsKyOT2pmk/view?usp=drive_link'
            ],
            [
                'category' => 'Survey Kepuasan',
                'title' => 'SPAK dan SPKP 2023',
                'published_date' => '2025-04-20',
                'document_path' => 'https://drive.google.com/file/d/143Anexwgt_6C_J0oaC7scj7uVPDJuLtl/view?usp=drive_link'
            ],
            [
                'category' => 'Survey Kepuasan',
                'title' => 'SPAK dan SPKP 2022',
                'published_date' => '2025-04-20',
                'document_path' => 'https://drive.google.com/file/d/1Z-DDZlN8__2AGZvmrMFRuSzlYp__A54x/view?usp=drive_link'
            ],
            [
                'category' => 'Survey Kepuasan',
                'title' => 'SPAK dan SPKP 2021',
                'published_date' => '2025-04-20',
                'document_path' => 'https://drive.google.com/file/d/1Ud38ytY2QH-akCBH3EMYMkdx5L0y0AIY/view?usp=drive_link'
            ],
            [
                'category' => 'Rencana Kinerja Anggaran',
                'title' => 'RKA 2024',
                'published_date' => '2025-01-22',
                'document_path' => 'https://drive.google.com/file/d/1g4nygxekP4kex1tZhMv8tAloekE4gr7k/view'
            ],
            [
                'category' => 'Laporan Tahunan',
                'title' => 'LAKIP BADAN LAYANAN UMUM UNIT PENYELENGGARA BANDAR UDARA KELAS I A.P.T. PRANOTO SAMARINDA TAHUN 2024',
                'published_date' => '2025-01-06',
                'document_path' => 'https://drive.google.com/file/d/1Kf7da9xqBPdFpxJPMOxqJ0HVaxDz1Tdc/view?usp=drive_link'
            ],
            [
                'category' => 'LHKPN',
                'title' => 'LHKPN 2024',
                'pejabat_name' => 'I Kadek Yuli Sastrawan, S.Ikom., S.iT.',
                'published_date' => '2025-02-28',
                'document_path' => 'https://drive.google.com/file/d/1WMw9nikfcKEm-pwM03ndBzCe5Ro5vjAp/view?usp=drive_link'
            ],
            [
                'category' => 'Laporan Keuangan',
                'title' => 'Laporan Keuangan 2024',
                'published_date' => '2025-01-02',
                'document_path' => 'https://drive.google.com/file/d/1_b4ZxEqwQEOEySWdob_OPkbqMhTrjj5v/view?usp=drive_link'
            ],
            [
                'category' => 'Data Statistik Kepegawaian',
                'title' => 'Data ABK berdasarkan Jabatan(2020-2024) Proyeksi (2025-2029)',
                'published_date' => '2025-04-24',
                'document_path' => 'https://docs.google.com/spreadsheets/d/1BKwXfvaWeyLPjXzsdJcZZEvJU9bjoPYd/edit?usp=drive_link&ouid=108559757573979060792&rtpof=true&sd=true'
            ]
        ];

        // Masukkan data ke dalam database menggunakan model
        foreach ($documents as $doc) {
            PeriodicDocument::create($doc);
        }
    }
}