<?php

namespace Database\Seeders;

use App\Models\ImmediateInformation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImmediateInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel terlebih dahulu untuk menghindari duplikat data
        DB::table('immediate_information')->truncate();

        $data = [
            [
                'uraian' => 'Bahaya Membakar Lahan Di Sekitar Bandara',
                'keterangan' => '#SobatAAP Kebakaran lahan dan hutan selain dapat membahayakan lingkungan sekitar, juga dapat membahayakan keselamatan dan keamanan penerbangan. Asap yang ditimbulkan dari kebakaran lahan dan hutan juga dapat mengganggu jarak pandang sehingga dapat menyebabkan delay ataupun pembatalan penerbangan.',
                'link_url' => 'https://www.instagram.com/p/C6DZzb4hUiy/',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 01:43:54',
                'updated_at' => '2025-09-10 01:43:54',
            ],
            [
                'uraian' => 'Jangan Menerima Titipan Bagasi',
                'keterangan' => '#SobatAviasi Jangan pernah menerima titipan bagasi dari orang tak dikenal di bandara! Waspada dan laporkan hal mencurigakan kepada petugas yah sobat',
                'link_url' => 'https://www.instagram.com/p/DHtTF7cB5tC/',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 01:46:46',
                'updated_at' => '2025-09-10 01:46:46',
            ],
            [
                'uraian' => 'Membeli Tiket Pada Situs Resmi Maskapai',
                'keterangan' => '#SobatAAP Guna Menghindari penipuan penjualan tiket yang dilakukan oleh beberapa oknum yang memanfaatkan arus mudik dan arus balik yang tinggi, maka #SobatAAP dapat membeli tiket pada situs resmi maskapai atau travel agent resmi.',
                'link_url' => 'https://www.instagram.com/p/C5Zxy1FhlO7/',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 01:47:45',
                'updated_at' => '2025-09-10 01:47:45',
            ],
            [
                'uraian' => 'Penerapan Parkir Non Tunai di Bandara',
                'keterangan' => '#SobatAAP mulai tanggal 1 April 2024, Bandara A.P.T. Pranoto Memberlakukan Tap Kartu Uang Elektronik Pada Saat Masuk Dan Keluar. Jadi, sobat dapat menyiapkan Kartu Uang Elektroniknya yah.',
                'link_url' => 'https://www.instagram.com/p/C4eaCReBYNf/',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 01:48:50',
                'updated_at' => '2025-09-10 01:48:50',
            ],
            [
                'uraian' => 'Siapa yang boleh duduk di Kursi Pintu Darurat Pesawat',
                'keterangan' => 'Siapa diantara #SobatAviasi yang suka pilih tempat duduk di kursi dekat pintu darurat pesawat (emergency exit row)? Posisi duduk ini memang paling banyak diminati karena ruang antar kursi lebih luas . Namun, penumpang yang duduk di baris pintu keluar darurat wajib memenuhi kriteria berikut ini.',
                'link_url' => 'https://www.instagram.com/p/C9Tumf8hc4-/',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 01:50:04',
                'updated_at' => '2025-09-10 01:50:04',
            ],
            [
                'uraian' => 'Panduan Penurunan dan Penjemputan Penumpang',
                'keterangan' => '#SobatAviasi berikut adalah panduan lajur penurunan dan penjemputan penumpang pada area drop zone dan pickup zonedi Bandara A.P.T. Pranoto yah. Dan jangan lupa selalu patuhi instruksi petugas yang ada dilapangan yah sobat',
                'link_url' => 'https://www.instagram.com/p/C9eF89bBXgt/',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 01:50:51',
                'updated_at' => '2025-09-10 01:50:51',
            ],
            [
                'uraian' => 'Bahaya Judi Online',
                'keterangan' => 'KEMENTERIAN PERHUBUNGAN Menerbitkan Surat Edaran Larangan Judi Online No. SE-MHB 3Tahun 2024 tentang PENCEGAHAN DAN PENANGGULANGAN JUDI ONLINE DAN SEGALA BENTUK PERJUDIAN LAINNYA DI LINGKUNGAN KEMENTERIAN PERHUBUNGAN.',
                'link_url' => 'https://www.instagram.com/p/C-G69pqB4Fj/',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 01:53:26',
                'updated_at' => '2025-09-10 01:53:26',
            ],
            [
                'uraian' => 'Pelayanan Penumpang Disabilitas',
                'keterangan' => 'Alur pelayanan penumpang Disabilitas di Bandara A.P.T. Pranoto',
                'link_url' => 'https://www.instagram.com/p/DAQIPFFhLi1/',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 01:54:16',
                'updated_at' => '2025-09-10 01:54:16',
            ],
            [
                'uraian' => 'Jalur Alternatif Menuju Bandara',
                'keterangan' => '#sobataviasi sudah tau belum kalo ada jalan alternatif menuju bandar udara A.P.T. Pranoto - Samarinda ??? Akses jalur ini bisa menjadi pilihan #sobataviasi untuk menghindari kemacetan dengan kondisi jalannya yang cukup baik. #sobataviasi bisa simak rutenya melalui video ini yaa',
                'link_url' => 'https://www.instagram.com/p/DBGv0aZhutm/',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 01:55:30',
                'updated_at' => '2025-09-10 01:55:30',
            ],
            [
                'uraian' => 'Layanan Parkir VIP',
                'keterangan' => '#SobatAviasi mulai hari ini, Rabu 13 November 2024 telah tersedia layanan Parkir VIP hasil kerjasama antara Bandara A.P.T. Pranoto dengan CV.KKBS. Layanan ini merupakan layanan premium bagi pengguna jasa yang anti ribet untuk memarkirkan kendaraannya di area parkir. Silahkan sobat dapat menghubungi petugas dari CV.KKBS pada area drop zone untuk menggunakan layanan ini.',
                'link_url' => 'https://www.instagram.com/p/DCTZEQHhM8i/',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 01:56:24',
                'updated_at' => '2025-09-10 01:56:24',
            ],
            [
                'uraian' => 'Aturan Baru Penerapan Bagasi Lion Air',
                'keterangan' => 'BERLAKU 1 DESEMBER 2024, ATURAN BARU PENERAPAN BAGASI MASKAPAI LION GROUP. Berlaku untuk maskapai Lion Air, Batik Air, Super Air Jet dan Wings Air *Sumber Lion Air Public Relations Ketentuan bagi Bagasi yang Melebihi Ukuran atau Jenis Tertentu',
                'link_url' => 'https://www.instagram.com/p/DCnaCtyhhth/?img_index=1',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 01:57:39',
                'updated_at' => '2025-09-10 01:57:39',
            ],
            [
                'uraian' => 'Waspada Cuaca Buruk',
                'keterangan' => 'Himbauan Penting! Kepada seluruh penumpang di Bandara A.P.T. Pranoto Samarinda, mengingat cuaca buruk dan hujan lebat yang sering terjadi akhir-akhir ini, kami mengimbau agar tiba di bandara lebih awal untuk mengantisipasi keterlambatan. Pastikan Anda memiliki waktu cukup untuk proses check-in dan pemeriksaan keamanan. Terima kasih atas kerja samanya',
                'link_url' => 'https://www.instagram.com/p/DE1qgkBh54_/',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 01:58:27',
                'updated_at' => '2025-09-10 01:58:27',
            ],
            [
                'uraian' => 'Larangan melakukan aktifitas bahaya di sekitar Bandara',
                'keterangan' => '#SobatAviasi yuk kita sama-sama menjaga keselamatan dan keamanan penerbangan dengan tidak melakukan aktivitas yang dapat membahayakan keselamatan penerbangan.',
                'link_url' => 'https://www.instagram.com/p/DFXBwyJh2Hr/',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 01:59:07',
                'updated_at' => '2025-09-10 01:59:07',
            ],
            [
                'uraian' => 'Waspada Penjualan Tiket Palsu',
                'keterangan' => '#SobatAviasi menjelang libur mudik lebaran tentu banyak sekali modus penjualan tiket palsu yang beredar pada sosial media, sobat perlu mencermati terlebih dahulu sebelum membeli tiket untuk mudik lebaran, jangan sampai tergiur dengan harga yang terlalu murah yang nantinya bisa menjadi tanda bahaya. Yuk beli tiket hanya di travel agent resmi, agar perjalananmu Selamat, Aman, dan nyaman',
                'link_url' => 'https://www.instagram.com/p/DHSNMGGvvtW/',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 02:05:25',
                'updated_at' => '2025-09-10 02:05:25',
            ],
            [
                'uraian' => 'Tips Mudik Anti Ribet Dengan Pesawat',
                'keterangan' => '#SobatAviasi Siap mudik tanpa ribet? Ikuti tips mudik anti ribet dengan pesawat berikut ini yah.. Selamat mudik, semoga perjalanan sobat lancar dan menyenangkan! #MudikAntiRibet #TipsMudik',
                'link_url' => 'https://www.instagram.com/p/DHVik9IhCJE/?img_index=1',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 02:12:59',
                'updated_at' => '2025-09-10 02:12:59',
            ],
            [
                'uraian' => 'Himbauan mematuhi rambu pada area drop zone dan pick up zone',
                'keterangan' => '#SobatAviasi selalu patuhi rambu-rambu dan petugas yang mengatur arus lalu lintas pada area drop zone maupun pick up zone agar tercipta kondisi yang tertib dan Teratur',
                'link_url' => 'https://www.instagram.com/p/DIKpDxghCXX/',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 02:14:10',
                'updated_at' => '2025-09-10 02:14:10',
            ],
            [
                'uraian' => 'Perubahan Ketentuan Bagasi Lion Group',
                'keterangan' => 'KETENTUAN TERBARU BAGASI MASKAPAI LION AIR DAN SUPER AIR JET. Berlaku untuk pembelian tiket tanggal 17 Juli 2025',
                'link_url' => 'https://www.instagram.com/p/DLhrKkuhL5u/',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 02:14:45',
                'updated_at' => '2025-09-10 02:14:45',
            ],
            [
                'uraian' => 'Penyesuaian Tarif BLU',
                'keterangan' => 'Halo #SobatAviasi. Berlaku Mulai tanggal 1 Agustus 2025, akan diberlakukan penyesuaian tarif jasa kebandarudaraan di Bandara A.P.T. Pranoto Samarinda yah. Penyesuaian ini dilakukan sebagai upaya untuk terus meningkatkan kenyamanan, keamanan, dan pelayanan terbaik bagi para penumpang. Yuk simak beberapa tarif barunya pada postingan ini yah sobat.',
                'link_url' => 'https://www.instagram.com/p/DL84BWBPYqm/?img_index=1',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 02:15:45',
                'updated_at' => '2025-09-10 02:15:45',
            ],
            [
                'uraian' => 'Waspada Bahaya Layang-Layang',
                'keterangan' => 'Tahukah #SobatAviasi. Bermain layang-layang di sekitar bandara bisa mengancam keselamatan penerbangan! Benang atau layang-layang yang tersangkut di jalur pesawat dapat menyebabkan kerusakan hingga keterlambatan jadwal penerbangan.',
                'link_url' => 'https://www.instagram.com/p/DMEokeXvjHH/?img_index=1',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 02:16:35',
                'updated_at' => '2025-09-10 02:16:35',
            ],
            [
                'uraian' => 'Bahaya Bercanda Tentang Bom',
                'keterangan' => 'Undang-undang melarang bercanda soal bom di area bandara & pesawat. Ancaman bercanda bisa jadi nyata: hukuman pidana menanti',
                'link_url' => 'https://www.instagram.com/p/DM9pTcaRFTi/?img_index=1',
                'link_text' => 'Lihat Detail',
                'created_at' => '2025-09-10 02:17:24',
                'updated_at' => '2025-09-10 02:17:24',
            ],
        ];

        // Masukkan data ke dalam database
        foreach ($data as $item) {
            ImmediateInformation::create($item);
        }
    }
}