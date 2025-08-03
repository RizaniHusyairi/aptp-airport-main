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
                "Mengajukan surat permohonan kepada Kasi Pelayanan dan Kerjasama",
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
                "Mengajukan surat permohonan kepada Kasi Pelayanan dan Kerjasama",
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
                "Mengajukan surat permohonan kepada Kasi Pelayanan dan Kerjasama",
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
                "Surat Permohonan"
            ],
            'steps' => [
                "Mengajukan surat permohonan kepada Kasi Pelayanan dan Kerjasama",
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
                "Mengajukan surat permohonan kepada Kasi Pelayanan dan Kerjasama",
                "Verifikasi dokumen dan persyaratan oleh petugas pengembangan usaha",
                "Presentasi bisnis sesuai dengan bidang usaha yang diajukan",
                "Melengkapi administrasi dan kontrak jika disetujui"
            ],
            'submission_url' => 'dashboard/fieldtrip',
        ]);
        Service::create([
            'name' => 'Lelang',
            'slug' => 'lelang',
            'title' => 'Syarat & Ketentuan Pengajuan Lelang',
            'requirements' => [
                "Nomor Induk Berusaha", "Kartu Tanda Penduduk (KTP)", "Akta Pendirian Perusahaan", "NPWP",
                "Proposal Usaha", "Desain Teknis Booth/Tempat Usaha","Sertifikat Penjamah Makanan (jika F&B)", "Surat Pernyataan Mengikuti Aturan (bermaterai)",
                "Laporan Keuangan", "Bukti Bayar Pajak 3 Bulan Terakhir",
                "Service Level Agreement (jika Maskapai)"
            ],
            'steps' => [
                "Mengajukan surat permohonan kepada Kasi Pelayanan dan Kerjasama",
                "Verifikasi dokumen dan persyaratan oleh petugas pengembangan usaha",
                "Presentasi bisnis sesuai dengan bidang usaha yang diajukan",
                "Melengkapi administrasi dan kontrak jika disetujui"
            ],
            'submission_url' => 'dashboard/lelang',
        ]);
        Service::create([
            'name' => 'Slot Charter',
            'slug' => 'slot-charter',
            'title' => 'Syarat & Ketentuan Pengajuan Slot Charter',
            'requirements' => [
                "Nomor Induk Berusaha", "Kartu Tanda Penduduk (KTP)", "Surat Permohonan Slot Charter", "Sertifikat Kelaikan Udara Pesawat",
                "Proposal Oprasional Penerbangan", "Surat Izin Operasi Penerbangan (untuk Operator)","Sertifikat Penjamah Makanan (jika F&B)",
                 "Bukti Bayar Pajak 3 Bulan Terakhir",
                "Service Level Agreement (jika Maskapai)"
            ],
            'steps' => [
                "Mengajukan surat permohonan kepada Kasi Pelayanan dan Kerjasama",
                "Verifikasi dokumen dan persyaratan oleh petugas pengembangan usaha",
                "Presentasi bisnis sesuai dengan bidang usaha yang diajukan",
                "Melengkapi administrasi dan kontrak jika disetujui"
            ],
            'submission_url' => 'dashboard/slot',
        ]);

        
    }
}