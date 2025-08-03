<?php

namespace Database\Seeders;

use App\Models\Tourism;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TourismSeeder extends Seeder
{
    public function run()
    {
        // Kosongkan tabel sebelum diisi untuk menghindari duplikasi data saat seeder dijalankan ulang
        DB::table('tourisms')->delete();

        // Siapkan data dummy dalam bentuk array
        $destinations = [
            [
                'name' => 'Masjid Islamic Center Samarinda',
                'slug' => 'masjid-islamic-center-samarinda',
                'category' => 'Religi',
                'cover_image' => 'documents/pariwisata/islamic.jpg',
                'gallery' => [
                    'documents/pariwisata/islamic.jpg.jpg',
                    'documents/pariwisata/islamic.jpg.jpg',
                    'documents/pariwisata/islamic.jpg.jpg',
                ],
                'short_desc' => 'Menara dan kubah megah yang menjadi ikon kota di tepi Sungai Mahakam.',
                'description' => 'Masjid Islamic Center Samarinda adalah salah satu masjid termegah dan terbesar kedua di Asia Tenggara setelah Masjid Istiqlal. Dengan arsitektur yang menawan, menara utama setinggi 99 meter yang melambangkan Asmaul Husna, dan tujuh menara yang melambangkan ayat-ayat dalam Surat Al-Fatihah, tempat ini tidak hanya menjadi pusat ibadah tetapi juga destinasi wisata religi yang populer dan wajib dikunjungi.',
                'address' => 'Jl. Slamet Riyadi No.1, Karang Asam Ulu, Kec. Sungai Kunjang, Kota Samarinda, Kalimantan Timur',
                'gmaps_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.677595287849!2d117.12644231529688!3d-0.514467999602434!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df67f3391b11393%3A0x4a7e93c1533c148b!2sSamarinda%20Islamic%20Center%20Mosque!5e0!3m2!1sen!2sid!4v1678886455937!5m2!1sen!2sid',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Desa Budaya Pampang',
                'slug' => 'desa-budaya-pampang',
                'category' => 'Budaya',
                'cover_image' => 'documents/pariwisata/desa-budaya-pampang.jpg',
                'gallery' => [
                    'documents/pariwisata/desa-budaya-pampang.jpg',
                    'documents/pariwisata/desa-budaya-pampang.jpg',
                ],
                'short_desc' => 'Rasakan kearifan lokal dan lihat pertunjukan seni dari Suku Dayak Kenyah.',
                'description' => 'Desa Pampang adalah sebuah desa budaya yang menjadi rumah bagi Suku Dayak Kenyah. Setiap hari Minggu siang, desa ini menggelar pertunjukan tari tradisional yang memukau di rumah adat (Lamin Adat). Pengunjung dapat berinteraksi langsung dengan warga lokal yang masih memegang teguh adat istiadat, melihat keunikan telinga panjang, dan membeli kerajinan tangan khas Dayak sebagai cinderamata.',
                'address' => 'Jl. Wisata Budaya Pampang, No. 3, RT. 03, Kelurahan Pampang, Samarinda Utara, Kota Samarinda, Kalimantan Timur',
                'gmaps_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.761062085359!2d117.2185013152968!3d-0.4284589996615715!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df678b668d4a9f3%3A0x6730784402a452a3!2sDesa%20Budaya%20Pampang!5e0!3m2!1sen!2sid!4v1678886574136!5m2!1sen!2sid',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Air Terjun Tanah Merah',
                'slug' => 'air-terjun-tanah-merah',
                'category' => 'Alam',
                'cover_image' => 'documents/pariwisata/airTerjun.jpg',
                'gallery' => [
                    'documents/pariwisata/airTerjun.jpg.jpg',
                    'documents/pariwisata/airTerjun.jpg.jpg',
                ],
                'short_desc' => 'Sebuah oase kesejukan yang menyegarkan tidak jauh dari pusat kota.',
                'description' => 'Berlokasi sekitar 14 km dari pusat kota Samarinda, Air Terjun Tanah Merah menawarkan suasana alam yang asri dan sejuk. Air terjun ini memiliki ketinggian sekitar 15 meter dengan air yang jernih dan kolam alami di bawahnya yang aman untuk bermain air. Tempat ini menjadi pilihan favorit warga lokal untuk bersantai dan menikmati keindahan alam di akhir pekan.',
                'address' => 'Jl. Muara Badak, Tanah Merah, Kec. Samarinda Utara, Kota Samarinda, Kalimantan Timur',
                'gmaps_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.77123456789!2d117.25123451529678!3d-0.418912999665789!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df6792a54366a7b%3A0x9565551930d66a2c!2sAir%20Terjun%20Tanah%20Merah!5e0!3m2!1sen!2sid!4v1678886634567!5m2!1sen!2sid',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Looping untuk memasukkan data ke database
        foreach ($destinations as $destination) {
            Tourism::create($destination);
        }
    }
}
