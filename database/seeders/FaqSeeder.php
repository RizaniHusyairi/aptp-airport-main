<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Service;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Draf FAQ berdasarkan layanan yang benar-benar ada di website ini.
     *
     * PENTING — pertanyaan yang jawabannya memuat angka atau ketentuan spesifik
     * (jam operasional, tarif, ketersediaan fasilitas tertentu) di-seed dengan
     * is_active = false dan diawali penanda [PERIKSA]. Data itu tidak diketahui
     * secara pasti, jadi sengaja TIDAK ditayangkan ke publik sampai admin
     * mengoreksi lalu mengaktifkannya lewat dashboard.
     *
     * Idempoten: updateOrCreate berdasarkan `question`.
     */
    public function run(): void
    {
        // Slug layanan -> id, agar FAQ bisa dikaitkan. Layanan yang belum ada
        // di database (mis. pada lingkungan lokal yang kosong) cukup dilewati.
        $services = Service::pluck('id', 'slug');

        foreach ($this->faqs() as $faq) {
            $slug = $faq['service_slug'] ?? null;
            unset($faq['service_slug']);

            $faq['service_id'] = $slug ? ($services[$slug] ?? null) : null;

            Faq::updateOrCreate(['question' => $faq['question']], $faq);
        }
    }

    protected function faqs(): array
    {
        return [
            // ---------- Penerbangan & Keberangkatan ----------
            [
                'question' => 'Di mana saya bisa melihat jadwal keberangkatan dan kedatangan?',
                'answer' => '<p>Jadwal penerbangan hari ini dapat dilihat langsung di website ini melalui halaman <a href="/keberangkatan">Keberangkatan</a> dan <a href="/kedatangan">Kedatangan</a>. Ringkasan jadwal juga tampil di halaman beranda.</p><p>Jadwal bersifat dinamis dan dapat berubah sewaktu-waktu mengikuti operasional maskapai.</p>',
                'category' => 'Penerbangan & Keberangkatan',
                'sort_order' => 1,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana jika penerbangan saya terlambat atau dibatalkan?',
                'answer' => '<p>Keterlambatan dan pembatalan penerbangan merupakan kewenangan maskapai. Silakan menghubungi langsung petugas maskapai yang bersangkutan di terminal atau melalui kanal layanan pelanggan maskapai untuk informasi penjadwalan ulang dan kompensasi.</p><p>Bila Anda memerlukan bantuan lain selama berada di bandara, petugas kami di terminal siap membantu.</p>',
                'category' => 'Penerbangan & Keberangkatan',
                'sort_order' => 2,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'question' => 'Berapa lama sebelum keberangkatan saya harus tiba di bandara?',
                'answer' => '<p>[PERIKSA] Jawaban ini masih berupa draf dan perlu disesuaikan dengan ketentuan resmi bandara sebelum ditayangkan.</p><p>Umumnya penumpang disarankan tiba lebih awal untuk proses lapor diri dan pemeriksaan keamanan. Mohon lengkapi durasi yang berlaku di Bandar Udara APT Pranoto.</p>',
                'category' => 'Penerbangan & Keberangkatan',
                'sort_order' => 3,
                'is_featured' => false,
                'is_active' => false,
            ],
            [
                'question' => 'Berapa jam operasional terminal penumpang?',
                'answer' => '<p>[PERIKSA] Jam operasional terminal belum tercantum di website dan perlu diisi oleh pengelola sebelum pertanyaan ini ditayangkan.</p>',
                'category' => 'Penerbangan & Keberangkatan',
                'sort_order' => 4,
                'is_featured' => false,
                'is_active' => false,
            ],

            // ---------- Fasilitas Bandara ----------
            [
                'question' => 'Fasilitas apa saja yang tersedia di Bandar Udara APT Pranoto?',
                'answer' => '<p>Daftar fasilitas yang tersedia — meliputi fasilitas sisi udara, sisi darat, dan fasilitas umum — dapat dilihat selengkapnya di halaman <a href="/fasilitas">Fasilitas Bandara</a>.</p>',
                'category' => 'Fasilitas Bandara',
                'sort_order' => 1,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'question' => 'Apakah tersedia fasilitas untuk penyandang disabilitas?',
                'answer' => '<p>[PERIKSA] Daftar fasilitas ramah disabilitas (kursi roda, jalur khusus, toilet difabel, dan pendampingan petugas) perlu dipastikan oleh pengelola sebelum pertanyaan ini ditayangkan.</p>',
                'category' => 'Fasilitas Bandara',
                'sort_order' => 2,
                'is_featured' => false,
                'is_active' => false,
            ],
            [
                'question' => 'Berapa tarif parkir kendaraan di bandara?',
                'answer' => '<p>[PERIKSA] Tarif parkir belum tercantum di website dan perlu diisi oleh pengelola sebelum pertanyaan ini ditayangkan.</p>',
                'category' => 'Fasilitas Bandara',
                'sort_order' => 3,
                'is_featured' => false,
                'is_active' => false,
            ],

            // ---------- Layanan & Perizinan ----------
            [
                'question' => 'Bagaimana cara mengurus PAS Bandara?',
                'answer' => '<p>Pengurusan PAS Bandara dilakukan melalui sistem PAS pada tautan yang tersedia di menu <strong>Layanan</strong>. Persyaratan dan alur pengajuan mengikuti ketentuan yang berlaku pada sistem tersebut.</p>',
                'category' => 'Layanan & Perizinan',
                'sort_order' => 1,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana cara mengajukan sewa lahan atau menjadi tenant?',
                'answer' => '<p>Pengajuan tenant dapat dilakukan secara daring melalui halaman layanan <a href="/layanan/tenant">Tenant</a>. Untuk penyewaan lahan atau ruang, silakan gunakan layanan <a href="/layanan/sewa">Sewa</a>.</p><p>Setiap halaman layanan memuat dokumen yang diperlukan dan tahapan pendaftarannya.</p>',
                'category' => 'Layanan & Perizinan',
                'service_slug' => 'tenant',
                'sort_order' => 2,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana cara mengajukan permohonan slot penerbangan?',
                'answer' => '<p>Permohonan slot dapat diajukan melalui halaman layanan <a href="/layanan/slot-charter">Slot Charter</a>. Dokumen persyaratan dan alur pengajuannya tercantum di halaman tersebut.</p>',
                'category' => 'Layanan & Perizinan',
                'service_slug' => 'slot-charter',
                'sort_order' => 3,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana cara mengajukan kunjungan atau field trip ke bandara?',
                'answer' => '<p>Pengajuan kunjungan edukasi dapat dilakukan melalui halaman layanan <a href="/layanan/field-trip">Field Trip</a>. Mohon ajukan permohonan jauh hari sebelum tanggal kunjungan yang direncanakan.</p>',
                'category' => 'Layanan & Perizinan',
                'service_slug' => 'field-trip',
                'sort_order' => 4,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana cara mengajukan izin pemasangan iklan di area bandara?',
                'answer' => '<p>Permohonan pengiklanan diajukan melalui halaman layanan <a href="/layanan/pengiklanan">Pengiklanan</a>, yang memuat persyaratan dokumen dan tahapan prosesnya.</p>',
                'category' => 'Layanan & Perizinan',
                'service_slug' => 'pengiklanan',
                'sort_order' => 5,
                'is_featured' => false,
                'is_active' => true,
            ],

            // ---------- Informasi Publik & Pengaduan ----------
            [
                'question' => 'Bagaimana cara mengajukan permohonan informasi publik?',
                'answer' => '<p>Permohonan informasi publik diajukan melalui layanan <a href="/layanan/informasi-publik">Pengajuan Informasi Publik</a> yang dikelola PPID Bandar Udara APT Pranoto.</p><p>Dasar hukum dan prosedurnya dapat dibaca pada halaman <a href="/informasi-publik/sop-ppid">SOP PPID</a> dan <a href="/informasi-publik/regulasi-ppid">Regulasi PPID</a>.</p>',
                'category' => 'Informasi Publik & Pengaduan',
                'service_slug' => 'informasi-publik',
                'sort_order' => 1,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'question' => 'Di mana saya bisa melihat Standar Pelayanan bandara?',
                'answer' => '<p>Dokumen Standar Pelayanan, Maklumat Pelayanan, dan laporan Survei Kepuasan Masyarakat tersedia di halaman <a href="/informasi-publik/standar-pelayanan">Standar Pelayanan</a>.</p>',
                'category' => 'Informasi Publik & Pengaduan',
                'sort_order' => 2,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana cara menyampaikan pengaduan atau aspirasi?',
                'answer' => '<p>Pengaduan dapat disampaikan melalui formulir kontak di halaman beranda website ini, atau melalui kanal resmi nasional <strong>SP4N-LAPOR!</strong> yang tautannya tersedia di halaman <a href="/tautan-terkait">Tautan Terkait</a>.</p>',
                'category' => 'Informasi Publik & Pengaduan',
                'sort_order' => 3,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'question' => 'Di mana saya bisa mengisi Survei Kepuasan Masyarakat?',
                'answer' => '<p>Tautan Survei Kepuasan Masyarakat tersedia di halaman beranda, pada halaman <a href="/informasi-publik/standar-pelayanan">Standar Pelayanan</a>, dan di bagian footer setiap halaman. Masukan Anda menjadi dasar perbaikan layanan kami.</p>',
                'category' => 'Informasi Publik & Pengaduan',
                'sort_order' => 4,
                'is_featured' => false,
                'is_active' => true,
            ],
        ];
    }
}
