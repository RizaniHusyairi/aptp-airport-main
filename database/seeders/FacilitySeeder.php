<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilitySeeder extends Seeder
{
    public function run()
    {
        // Kosongkan tabel sebelum mengisi
        DB::table('facilities')->truncate();

        $facilities = [
            // Fasilitas Udara
            ['name' => 'Runway', 'category' => 'udara', 'details' => ['Ukuran: 2.250 m x 45 m', 'Daya Dukung: PCN 50 F/C/X/T'], 'image_path' => 'assets_landing/img/fasilitas/runway.jpg'],
            ['name' => 'Apron', 'category' => 'udara', 'details' => ['Ukuran: 300 m x 123 m', '8 Parking Stand', 'Daya Dukung: PCN 63 F/C/X/T'], 'image_path' => 'assets_landing/img/fasilitas/apron.jpg'],
            ['name' => 'Garbarata', 'category' => 'udara', 'details' => ['Tersedia: 2 Unit', 'Memudahkan akses ke pesawat.'], 'image_path' => 'assets_landing/img/fasilitas/garbarata.jpg'],
            ['name' => 'Navigasi (PBN)', 'category' => 'udara', 'details' => ['Tersedia untuk Runway 04 & 22', 'Memastikan pendaratan presisi.'], 'image_path' => 'assets_landing/img/fasilitas/navigasi.jpg'],
            
            // Fasilitas Darat
            ['name' => 'Terminal Penumpang', 'category' => 'darat', 'details' => ['Luas: 12.700 m²', 'Kapasitas: 1.5 Juta Penumpang/Tahun'], 'image_path' => 'assets_landing/img/fasilitas/terminal.jpg'],
            ['name' => 'Terminal Kargo', 'category' => 'darat', 'details' => ['Luas: 1.148 m²', 'Mendukung logistik & pengiriman barang.'], 'image_path' => 'assets_landing/img/fasilitas/kargo.jpg'],
            ['name' => 'Gedung VVIP', 'category' => 'darat', 'details' => ['Luas: 743,60 m²', 'Kenyamanan eksklusif untuk tamu penting.'], 'image_path' => 'assets_landing/img/fasilitas/vvip.jpg'],
            ['name' => 'Area Parkir', 'category' => 'darat', 'details' => ['Luas: 30.000 m²', 'Kapasitas luas untuk kendaraan.'], 'image_path' => 'assets_landing/img/fasilitas/parkir.jpg'],
            ['name' => 'Gedung Administrasi', 'category' => 'darat', 'details' => ['Luas: 1.253 m²', 'Pusat administrasi dan operasional.'], 'image_path' => 'assets_landing/img/fasilitas/administrasi.jpg'],
            ['name' => 'Fire Station (ARFF)', 'category' => 'darat', 'details' => ['Luas: 455,52 m²', 'Kategori 6 ARFF.'], 'image_path' => 'assets_landing/img/fasilitas/arff.jpg'],
            ['name' => 'Power Station', 'category' => 'darat', 'details' => ['Luas: 803 m²', 'Menjamin pasokan listrik bandara.'], 'image_path' => 'assets_landing/img/fasilitas/power-station.jpg'],
            ['name' => 'Kantin Bandara', 'category' => 'darat', 'details' => ['Luas: 372,12 m²', 'Menyediakan aneka kuliner.'], 'image_path' => 'assets_landing/img/fasilitas/cafe.jpg'],

            // Fasilitas Umum
            ['name' => 'Check-in Counter', 'category' => 'umum', 'details' => ['Tersedia: 16 Counter', 'Proses check-in yang cepat dan efisien.'], 'image_path' => 'assets_landing/img/fasilitas/checkin.jpg'],
            ['name' => 'Mushola', 'category' => 'umum', 'details' => ['Ruang ibadah yang bersih dan nyaman bagi umat Muslim.'], 'image_path' => 'assets_landing/img/fasilitas/mushola.jpg'],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
    }
}
