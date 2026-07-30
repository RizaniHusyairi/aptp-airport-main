<?php

namespace Database\Seeders;

use App\Models\ExternalLink;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class ExternalLinkSeeder extends Seeder
{
    /**
     * Empat portal pemerintah yang wajib ditautkan.
     * Memakai updateOrCreate berdasarkan url agar idempoten (aman dijalankan ulang)
     * dan tidak menghapus tautan lain yang ditambahkan admin lewat dashboard.
     *
     * Catatan sort_order: nomor diberikan berurutan per kelompok, karena urutan
     * kelompok di halaman publik mengikuti nomor terkecil di dalam kelompok itu.
     */
    public function run(): void
    {
        $links = [
            [
                'name' => 'SIPPN',
                'url' => 'https://sippn.menpan.go.id/',
                'description' => 'Direktori nasional informasi pelayanan publik Kementerian PANRB.',
                'icon' => 'bi-journal-bookmark-fill',
                'group' => 'Layanan Pengaduan & Informasi Publik',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'SP4N-LAPOR!',
                'url' => 'https://www.lapor.go.id/',
                'description' => 'Kanal resmi penyampaian aspirasi dan pengaduan pelayanan publik.',
                'icon' => 'bi-megaphone-fill',
                'group' => 'Layanan Pengaduan & Informasi Publik',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'SIK',
                'url' => 'https://sik.dephub.go.id/',
                'description' => 'Sistem Informasi Kepegawaian Kementerian Perhubungan.',
                'icon' => 'bi-people-fill',
                'group' => 'Aplikasi Internal Pegawai',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'e-Kinerja',
                'url' => 'https://e-kinerja.kemenhub.go.id/',
                'description' => 'Aplikasi penilaian kinerja pegawai Kementerian Perhubungan.',
                'icon' => 'bi-graph-up-arrow',
                'group' => 'Aplikasi Internal Pegawai',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($links as $link) {
            ExternalLink::updateOrCreate(['url' => $link['url']], $link);
        }

        Cache::forget('external_links');
    }
}
