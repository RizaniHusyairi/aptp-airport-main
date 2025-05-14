<?php

namespace Database\Seeders;

use App\Models\Letter;
use Illuminate\Database\Seeder;

class LetterSeeder extends Seeder
{
    public function run()
    {
        $letters = [
            [
                'type' => 'edaran',
                'number' => 'SE/001/2025',
                'title' => 'Protokol Kesehatan di Area Bandara APT Pranoto',
                'issue_date' => '2025-01-10',
                'file_path' => 'uploads/letters/se001.pdf',
                'created_at' => '2025-01-10 08:00:00',
                'updated_at' => '2025-01-10 08:00:00',
            ],
            [
                'type' => 'edaran',
                'number' => 'SE/002/2025',
                'title' => 'Jadwal Pemeliharaan Fasilitas Terminal',
                'issue_date' => '2025-02-15',
                'file_path' => 'uploads/letters/se002.pdf',
                'created_at' => '2025-02-15 09:00:00',
                'updated_at' => '2025-02-15 09:00:00',
            ],
            [
                'type' => 'edaran',
                'number' => 'SE/003/2025',
                'title' => 'Pedoman Keamanan Penerbangan',
                'issue_date' => '2025-03-20',
                'file_path' => 'uploads/letters/se003.pdf',
                'created_at' => '2025-03-20 10:00:00',
                'updated_at' => '2025-03-20 10:00:00',
            ],
            [
                'type' => 'edaran',
                'number' => 'SE/004/2025',
                'title' => 'Aturan Penggunaan Lounge Penumpang',
                'issue_date' => '2025-04-05',
                'file_path' => 'uploads/letters/se004.pdf',
                'created_at' => '2025-04-05 11:00:00',
                'updated_at' => '2025-04-05 11:00:00',
            ],
            [
                'type' => 'edaran',
                'number' => 'SE/005/2025',
                'title' => 'Prosedur Evakuasi Darurat Bandara',
                'issue_date' => '2025-05-01',
                'file_path' => 'uploads/letters/se005.pdf',
                'created_at' => '2025-05-01 12:00:00',
                'updated_at' => '2025-05-01 12:00:00',
            ],
            [
                'type' => 'utusan',
                'number' => 'SU/001/2025',
                'title' => 'Undangan Rapat Koordinasi Keselamatan Penerbangan',
                'issue_date' => '2025-01-15',
                'file_path' => 'uploads/letters/su001.pdf',
                'created_at' => '2025-01-15 08:30:00',
                'updated_at' => '2025-01-15 08:30:00',
            ],
            [
                'type' => 'utusan',
                'number' => 'SU/002/2025',
                'title' => 'Pemberitahuan Audit Operasional Bandara',
                'issue_date' => '2025-02-20',
                'file_path' => 'uploads/letters/su002.pdf',
                'created_at' => '2025-02-20 09:30:00',
                'updated_at' => '2025-02-20 09:30:00',
            ],
            [
                'type' => 'utusan',
                'number' => 'SU/003/2025',
                'title' => 'Surat Tugas Pelatihan Petugas Bandara',
                'issue_date' => '2025-03-25',
                'file_path' => 'uploads/letters/su003.pdf',
                'created_at' => '2025-03-25 10:30:00',
                'updated_at' => '2025-03-25 10:30:00',
            ],
            [
                'type' => 'utusan',
                'number' => 'SU/004/2025',
                'title' => 'Permintaan Data Statistik Penerbangan',
                'issue_date' => '2025-04-10',
                'file_path' => 'uploads/letters/su004.pdf',
                'created_at' => '2025-04-10 11:30:00',
                'updated_at' => '2025-04-10 11:30:00',
            ],
            [
                'type' => 'utusan',
                'number' => 'SU/005/2025',
                'title' => 'Undangan Seminar Lingkungan Bandara',
                'issue_date' => '2025-05-05',
                'file_path' => 'uploads/letters/su005.pdf',
                'created_at' => '2025-05-05 12:30:00',
                'updated_at' => '2025-05-05 12:30:00',
            ],
        ];

        foreach ($letters as $letter) {
            Letter::create($letter);
        }
    }
}
