<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        DB::table('services')->delete();

        Service::create([
            'name' => 'Tenant',
            'slug' => 'tenant',
            'title' => 'Syarat & Ketentuan Pengajuan Tenant',
            'requirements' =>[
                "Nomor Induk Berusaha", "Kartu Tanda Penduduk (KTP)", "Akta Pendirian Perusahaan", "NPWP",
                "Proposal Usaha", "Desain Teknis Booth/Tempat Usaha", "Surat Pernyataan Mengikuti Aturan (bermaterai)",
                "Laporan Keuangan", "Sertifikat Penjamah Makanan (jika F&B)", "Bukti Bayar Pajak 3 Bulan Terakhir",
                "Service Level Agreement (jika Maskapai)"
            ],
            'steps' => [
                "Mengajukan surat permohonan kepada Kabandara",
                "Verifikasi dokumen dan persyaratan oleh petugas pengembangan usaha",
                "Presentasi bisnis sesuai dengan bidang usaha yang diajukan",
                "Melengkapi administrasi dan kontrak jika disetujui"
            ],
            'has_pricing' => true,
            'pricing_info' => [
                ["name" => "Terbuka tanpa AC", "price" => "Rp. 31.000/m²"],
                ["name" => "Tertutup tanpa AC", "price" => "Rp. 48.000/m²"],
                ["name" => "Terbuka dengan AC", "price" => "Rp. 65.000/m²"],
                ["name" => "Tertutup dengan AC", "price" => "Rp. 82.000/m²"]
            ],
            'submission_url' => 'dashboard/tenant'
        ]);
        
        Service::create([
            'name' => 'Sewa',
            'slug' => 'sewa',
            'title' => 'Syarat & Ketentuan Pengajuan Sewa',
            'requirements' => [
                "Nomor Induk Berusaha", "Kartu Tanda Penduduk (KTP)", "Akta Pendirian Perusahaan", "NPWP",
                "Proposal Usaha", "Desain Teknis Booth/Tempat Usaha", "Surat Pernyataan Mengikuti Aturan (bermaterai)",
                "Laporan Keuangan", "Sertifikat Penjamah Makanan (jika F&B)", "Bukti Bayar Pajak 3 Bulan Terakhir",
                "Service Level Agreement (jika Maskapai)"
            ],
            'steps' => [
                "Mengajukan surat permohonan kepada Kabandara",
                
                "Verifikasi dokumen dan persyaratan oleh petugas pengembangan usaha",
                "Presentasi bisnis sesuai dengan bidang usaha yang diajukan",
                "Melengkapi administrasi dan kontrak jika disetujui"
            ],
            'submission_url' => 'dashboard/sewa'
        ]);
        Service::create([
            'name' => 'Perijinan Usaha',
            'slug' => 'perijinan-usaha',
            'title' => 'Syarat & Ketentuan Perijinan Usaha',
            'requirements' => [
                "Nomor Induk Berusaha", "Kartu Tanda Penduduk (KTP)", "Akta Pendirian Perusahaan", "NPWP",
                "Proposal Usaha", "Desain Teknis Booth/Tempat Usaha", "Surat Pernyataan Mengikuti Aturan (bermaterai)",
                "Laporan Keuangan", "Bukti Bayar Pajak 3 Bulan Terakhir",
                "Service Level Agreement (jika Maskapai)"
            ],
            'steps' => [
                "Mengajukan surat permohonan kepada Kabandara",
                "Verifikasi dokumen dan persyaratan oleh petugas pengembangan usaha",
                "Presentasi bisnis sesuai dengan bidang usaha yang diajukan",
                "Melengkapi administrasi dan kontrak jika disetujui"
            ],
            'submission_url' => 'dashboard/perijinan'
        ]);
        Service::create([
            'name' => 'Pengiklanan',
            'slug' => 'pengiklanan',
            'title' => 'Syarat & Ketentuan Pengajuan Pengiklanan',
            'requirements' => [
                "Nomor Induk Berusaha", "Kartu Tanda Penduduk (KTP)", "Akta Pendirian Perusahaan", "NPWP",
                "Proposal Usaha", "Surat Pernyataan Mengikuti Aturan (bermaterai)",
                "Laporan Keuangan", "Bukti Bayar Pajak 3 Bulan Terakhir",
                "Service Level Agreement (jika Maskapai)"
            ],
            'steps' => [
                "Mengajukan surat permohonan kepada Kabandara",
                "Verifikasi dokumen dan persyaratan oleh petugas pengembangan usaha",
                "Presentasi bisnis sesuai dengan bidang usaha yang diajukan",
                "Melengkapi administrasi dan kontrak jika disetujui"
            ],
            'submission_url' => 'dashboard/pengiklanan'
        ]);
        Service::create([
            'name' => 'Field Trip',
            'slug' => 'field-trip',
            'title' => 'Syarat & Ketentuan Pengajuan Field Trip',
            'requirements' => [
                "Surat Permohonan"
            ],
            'steps' => [
                "Mengajukan surat permohonan kepada Kabandara",
                "Verifikasi dokumen dan persyaratan oleh petugas pengembangan usaha",
                "Presentasi bisnis sesuai dengan bidang usaha yang diajukan",
                "Melengkapi administrasi dan kontrak jika disetujui"
            ],
            'submission_url' => 'dashboard/fieldtrip',
        ]);
        Service::create([
            'name' => 'Beauty Contest',
            'slug' => 'beauty-contest',
            'title' => 'Syarat & Ketentuan Pengajuan Beauty Contest',
            'requirements' => [
                "Nomor Induk Berusaha", "Kartu Tanda Penduduk (KTP)", "Akta Pendirian Perusahaan", "NPWP",
                "Proposal Usaha", "Surat Pernyataan Mengikuti Aturan (bermaterai)",
                "Laporan Keuangan", "Bukti Bayar Pajak 3 Bulan Terakhir",
                "Service Level Agreement (jika Maskapai)"
            ],
            'steps' => [
                "Mengajukan surat permohonan kepada Kabandara",
                "Verifikasi dokumen dan persyaratan oleh petugas pengembangan usaha",
                "Presentasi bisnis sesuai dengan bidang usaha yang diajukan",
                "Melengkapi administrasi dan kontrak jika disetujui"
            ],
            'submission_url' => 'dashboard/beauty-contest',
        ]);
        Service::create([
            'name' => 'Slot Charter',
            'slug' => 'slot-charter',
            'title' => 'Syarat & Ketentuan Pengajuan Slot Charter',
            'requirements' => [
                "Dokumen dari aplikasi crounus"
            ],
            'steps' => [
                "Mengajukan surat permohonan kepada Kabandara",
                "Verifikasi dokumen dan persyaratan oleh petugas pengembangan usaha",
                "Presentasi bisnis sesuai dengan bidang usaha yang diajukan",
                "Melengkapi administrasi dan kontrak jika disetujui"
            ],
            'submission_url' => 'dashboard/slot',
        ]);

        Service::create([
            'name' => 'Extend Advance',
            'slug' => 'extend-advance',
            'title' => 'Syarat & Ketentuan Pengajuan Extend Advance',
            
            'steps' => [
                "Mengisi Formulir Permohonan Awal",
                "Ekspor, Cetak, dan Tandatangani Dokumen",
                "Unggah Kembali Dokumen yang Sudah Ditandatangani",
                "Menunggu Verifikasi Staf"
            ],
            'submission_url' => 'dashboard/extend-advance',
        ]);
        Service::create([
            'name' => 'Pengajuan Informasi Publik',
            'slug' => 'informasi-publik',
            'title' => 'Syarat & Ketentuan Pengajuan Informasi Publik',
            'requirements' => [
                "Kartu Tanda Penduduk (KTP)", "Surat Permintaan Informasi", "Rincian Informasi yang Diminta",
                "Tujuan Penggunaan Informasi", "Cara Memperoleh Informasi", "Cara Mendapatkan Salinan Informasi"
            ],
            'steps' => [
                "Mengajukan surat permohonan kepada Kabandara",
                "Verifikasi dokumen dan persyaratan oleh petugas pengembangan usaha",
                "Melengkapi administrasi dan kontrak jika disetujui"
            ],
            'submission_url' => 'dashboard/informasi-publik',
        ]);

        // === TAMBAHAN BARU: SERTIFIKAT OJT ===
        Service::create([
            'name' => 'Sertifikat OJT',
            'slug' => 'sertifikat-ojt',
            'title' => 'Syarat & Ketentuan Pengajuan Sertifikat OJT',
            'requirements' => [
                "Scan KTP / Kartu Mahasiswa / Kartu Pelajar",
                "Pas Foto Ukuran 4x6 (Latar Belakang Merah, Kemeja Putih)"
            ],
            'steps' => ["Mendaftar akun pengaju dan login","Mengisi formulir data diri, data akademik, dan data magang","Mengunggah dokumen persyaratan (KTP & Foto)","Menunggu verifikasi data dan input nilai oleh Staff","Mengunduh sertifikat digital pada dashboard jika status 'Selesai'"],
            'submission_url' => 'pengajuan-ojt', // Sesuai dengan route 'user.ojt.index'
        ]);
        

        
    }
}