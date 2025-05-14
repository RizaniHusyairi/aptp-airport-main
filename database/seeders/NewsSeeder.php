<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run()
    {
        $newsData = [
            [
                'title' => 'Peningkatan Fasilitas Lounge di Bandara APT Pranoto',
                'slug' => 'peningkatan-fasilitas-lounge-apt-pranoto',
                'image' => 'documents/news/lounge-upgrade.jpg',
                'content' => 'Bandara APT Pranoto meluncurkan lounge baru dengan fasilitas modern untuk meningkatkan kenyamanan penumpang. Lounge ini dilengkapi Wi-Fi gratis dan area relaksasi.',
                'is_published' => true,
                'is_headline' => true,
                'created_at' => '2025-05-01 08:00:00',
                'updated_at' => '2025-05-01 08:00:00',
            ],
            [
                'title' => 'Latihan Evakuasi Darurat di Terminal Bandara',
                'slug' => 'latihan-evakuasi-darurat-2025',
                'image' => 'documents/news/evacuation-drill.jpg',
                'content' => 'Tim bandara mengadakan latihan evakuasi darurat untuk memastikan kesiapan dalam situasi krisis. Kegiatan ini melibatkan petugas dan penumpang sukarela.',
                'is_published' => true,
                'is_headline' => false,
                'created_at' => '2025-05-02 09:15:00',
                'updated_at' => '2025-05-02 09:15:00',
            ],
            [
                'title' => 'Penerbangan Baru ke Surabaya Dibuka',
                'slug' => 'penerbangan-baru-surabaya',
                'image' => 'documents/news/flight-surabaya.jpg',
                'content' => 'Maskapai Lion Air membuka rute baru Samarinda-Surabaya mulai Juni 2025, dengan frekuensi tiga kali seminggu.',
                'is_published' => true,
                'is_headline' => true,
                'created_at' => '2025-05-03 10:30:00',
                'updated_at' => '2025-05-03 10:30:00',
            ],
            [
                'title' => 'Pameran Produk UMKM di Area Check-in',
                'slug' => 'pameran-umkm-apt-pranoto',
                'image' => 'documents/news/umkm-exhibition.jpg',
                'content' => 'Bandara APT Pranoto menggelar pameran UMKM lokal untuk mempromosikan produk khas Samarinda. Pameran berlangsung hingga akhir Mei.',
                'is_published' => true,
                'is_headline' => false,
                'created_at' => '2025-05-04 11:45:00',
                'updated_at' => '2025-05-04 11:45:00',
            ],
            [
                'title' => 'Pemeliharaan Landasan Pacu Selesai',
                'slug' => 'pemeliharaan-landasan-pacu-2025',
                'image' => null,
                'content' => 'Pemeliharaan landasan pacu selesai lebih cepat dari jadwal, memastikan operasional penerbangan tetap lancar.',
                'is_published' => true,
                'is_headline' => false,
                'created_at' => '2025-05-05 13:00:00',
                'updated_at' => '2025-05-05 13:00:00',
            ],
            [
                'title' => 'Pelatihan Keselamatan Penerbangan untuk Petugas',
                'slug' => 'pelatihan-keselamatan-penerbangan',
                'image' => 'documents/news/safety-training.jpg',
                'content' => 'Petugas bandara mengikuti pelatihan keselamatan penerbangan untuk meningkatkan standar keamanan operasional.',
                'is_published' => false,
                'is_headline' => false,
                'created_at' => '2025-05-06 14:20:00',
                'updated_at' => '2025-05-06 14:20:00',
            ],
            [
                'title' => 'Festival Kuliner di Area Food Court Bandara',
                'slug' => 'festival-kuliner-bandara-2025',
                'image' => 'documents/news/food-festival.jpg',
                'content' => 'Festival kuliner di food court bandara menawarkan berbagai makanan khas Kalimantan Timur, berlangsung selama dua minggu.',
                'is_published' => true,
                'is_headline' => true,
                'created_at' => '2025-05-07 15:40:00',
                'updated_at' => '2025-05-07 15:40:00',
            ],
            [
                'title' => 'Penambahan Mesin Check-in Otomatis',
                'slug' => 'mesin-checkin-otomatis',
                'image' => 'documents/news/checkin-machine.jpg',
                'content' => 'Bandara APT Pranoto menambah mesin check-in otomatis untuk mempercepat proses boarding penumpang.',
                'is_published' => false,
                'is_headline' => false,
                'created_at' => '2025-05-08 16:55:00',
                'updated_at' => '2025-05-08 16:55:00',
            ],
            [
                'title' => 'Kunjungan Delegasi Kemenhub ke Bandara',
                'slug' => 'kunjungan-delegasi-kemenhub',
                'image' => 'documents/news/delegation-visit.jpg',
                'content' => 'Delegasi dari Kementerian Perhubungan mengunjungi Bandara APT Pranoto untuk mengevaluasi operasional dan fasilitas.',
                'is_published' => true,
                'is_headline' => false,
                'created_at' => '2025-05-09 18:10:00',
                'updated_at' => '2025-05-09 18:10:00',
            ],
            [
                'title' => 'Hari Lingkungan Hidup di Bandara',
                'slug' => 'hari-lingkungan-hidup-2025',
                'image' => 'documents/news/environment-day.jpg',
                'content' => 'Bandara APT Pranoto memperingati Hari Lingkungan Hidup dengan aksi bersih-bersih dan penanaman pohon di area bandara.',
                'is_published' => true,
                'is_headline' => false,
                'created_at' => '2025-05-10 19:25:00',
                'updated_at' => '2025-05-10 19:25:00',
            ],
        ];

        foreach ($newsData as $news) {
            News::create($news);
        }
    }
}
