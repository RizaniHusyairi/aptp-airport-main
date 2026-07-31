<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Isi FAQ Bandar Udara A.P.T. Pranoto.
     *
     * Seluruh isinya bersumber dari dokumen resmi bandara
     * `docs/FAQ BANDARA.docx` — tidak ada pertanyaan tambahan.
     *
     * Idempoten: updateOrCreate berdasarkan `question`, dan pertanyaan lama
     * di luar dokumen dihapus lewat daftar removed() sehingga aman dijalankan
     * berulang, termasuk di server yang sudah pernah di-seed versi sebelumnya.
     */
    public function run(): void
    {
        foreach ($this->faqs() as $faq) {
            Faq::updateOrCreate(['question' => $faq['question']], $faq);
        }

        // Bersihkan pertanyaan dari versi seeder terdahulu yang tidak ada di dokumen
        Faq::whereIn('question', $this->removed())->delete();
    }

    /**
     * Pertanyaan yang pernah di-seed sebelumnya namun tidak bersumber dari
     * dokumen resmi, sehingga dihapus.
     */
    protected function removed(): array
    {
        return [
            // Pengarah ke halaman/layanan website
            'Di mana saya bisa melihat jadwal keberangkatan dan kedatangan?',
            'Bagaimana jika penerbangan saya terlambat atau dibatalkan?',
            'Fasilitas apa saja yang tersedia di Bandar Udara APT Pranoto?',
            'Bagaimana cara mengurus PAS Bandara?',
            'Bagaimana cara mengajukan sewa lahan atau menjadi tenant?',
            'Bagaimana cara mengajukan permohonan slot penerbangan?',
            'Bagaimana cara mengajukan kunjungan atau field trip ke bandara?',
            'Bagaimana cara mengajukan izin pemasangan iklan di area bandara?',
            'Bagaimana cara mengajukan permohonan informasi publik?',
            'Di mana saya bisa melihat Standar Pelayanan bandara?',
            'Di mana saya bisa mengisi Survei Kepuasan Masyarakat?',
            // Draf yang jawabannya tidak tersedia di dokumen
            'Berapa lama sebelum keberangkatan saya harus tiba di bandara?',
            // Rumusan lama yang sudah digantikan versi dokumen
            'Berapa jam operasional terminal penumpang?',
            'Apakah tersedia fasilitas untuk penyandang disabilitas?',
            'Berapa tarif parkir kendaraan di bandara?',
            'Bagaimana cara menyampaikan pengaduan atau aspirasi?',
        ];
    }

    protected function faqs(): array
    {
        return [
            // ==================================================
            // Penerbangan & Keberangkatan
            // ==================================================
            [
                'question' => 'Apa saja rute penerbangan yang tersedia di Bandara A.P.T. Pranoto Samarinda?',
                'answer' => '<p>Bandara A.P.T. Pranoto Samarinda melayani penerbangan menuju <strong>Jakarta, Surabaya, Yogyakarta, Banjarmasin, Berau,</strong> dan <strong>Melak</strong>.</p><p>Selain itu tersedia rute penerbangan perintis menuju <strong>Long Apung, Maratua, Datah Dawai,</strong> dan <strong>Muara Wahau</strong>, serta koneksi antarwilayah <strong>Datah Dawai–Melak</strong> dan <strong>Maratua–Berau</strong>.</p>',
                'category' => 'Penerbangan & Keberangkatan',
                'service_id' => null,
                'sort_order' => 1,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'question' => 'Berapa jam operasional Bandara A.P.T. Pranoto?',
                'answer' => '<p>Jam operasional Bandara A.P.T. Pranoto mengikuti jadwal operasional yang telah ditetapkan, yaitu pukul <strong>07.00 WITA hingga 20.00 WITA</strong>.</p>',
                'category' => 'Penerbangan & Keberangkatan',
                'service_id' => null,
                'sort_order' => 2,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'question' => 'Identitas apa yang dapat digunakan untuk pemeriksaan tiket pesawat di bandara?',
                'answer' => '<p>Identitas yang dapat digunakan untuk pemeriksaan tiket adalah <strong>Kartu Tanda Penduduk (KTP), Surat Izin Mengemudi (SIM), paspor</strong>, atau identitas resmi lainnya yang masih berlaku dan sesuai dengan data pada tiket.</p><p>Untuk bayi dan anak yang belum memiliki KTP, dapat menggunakan <strong>Kartu Identitas Anak (KIA), Akta Kelahiran,</strong> dan <strong>Kartu Keluarga</strong>.</p>',
                'category' => 'Penerbangan & Keberangkatan',
                'service_id' => null,
                'sort_order' => 3,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                // Tautan diambil dari hyperlink di dalam dokumen
                'question' => 'Bagaimana cara memesan tiket pesawat untuk rute penerbangan perintis?',
                'answer' => '<p>Pemesanan tiket penerbangan perintis dapat dilakukan dengan menghubungi kontak resmi yang tersedia pada <a href="https://www.instagram.com/p/DTQC0UUEZ6D/?img_index=1" target="_blank" rel="noopener">tautan berikut</a>.</p><p>Untuk informasi lebih lanjut mengenai jadwal dan ketersediaan kursi, silakan menghubungi kontak yang tercantum pada tautan tersebut.</p>',
                'category' => 'Penerbangan & Keberangkatan',
                'service_id' => null,
                'sort_order' => 4,
                'is_featured' => false,
                'is_active' => true,
            ],

            // ==================================================
            // Fasilitas Bandara
            // ==================================================
            [
                'question' => 'Apakah bandara menyediakan fasilitas bagi penyandang disabilitas?',
                'answer' => '<p>Ya. Bandara menyediakan fasilitas ramah disabilitas seperti <strong>jalur khusus, toilet difabel, area parkir khusus,</strong> dan layanan bantuan sesuai kebutuhan.</p><p>Penumpang dapat menghubungi petugas atau <em>Customer Service</em> setibanya di bandara maupun sebelum keberangkatan, agar petugas dapat memberikan pendampingan dan bantuan sesuai kebutuhan.</p>',
                'category' => 'Fasilitas Bandara',
                'service_id' => null,
                'sort_order' => 1,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana jika saya kehilangan barang di bandara?',
                'answer' => '<p>Segera hubungi petugas <strong>Lost and Found</strong> atau Pusat Informasi bandara, atau kontak resmi bandara, dengan menyampaikan <strong>ciri-ciri barang, lokasi terakhir,</strong> dan <strong>waktu kehilangan</strong>.</p>',
                'category' => 'Fasilitas Bandara',
                'service_id' => null,
                'sort_order' => 2,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                // Tautan diambil dari hyperlink di dalam dokumen
                'question' => 'Berapa tarif parkir inap kendaraan di Bandara A.P.T. Pranoto?',
                'answer' => '<p>Bandara A.P.T. Pranoto menyediakan layanan <strong>parkir inap 24 jam</strong> setiap hari dengan tarif <strong>Rp75.000 per hari</strong>, dilengkapi <em>shuttle car</em> menuju <em>Drop Zone</em> dan <em>Pick Up Zone</em>.</p><p>Kendaraan wajib diparkir di area yang telah disediakan. Informasi lengkap mengenai lokasi parkir inap dapat dilihat melalui <a href="https://www.instagram.com/reels/DIcjgt2B4nL/" target="_blank" rel="noopener">tautan berikut</a>.</p>',
                'category' => 'Fasilitas Bandara',
                'service_id' => null,
                'sort_order' => 3,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'question' => 'Berapa tarif taksi bandara?',
                'answer' => '<p>Berikut tarif Taksi Resmi Bandara A.P.T. Pranoto Samarinda.</p>'
                    . '<p><strong>Tarif Dalam Kota</strong></p>'
                    . '<ul>'
                    . '<li>Zona 1 (Depan Bandara): Rp50.000–Rp60.000</li>'
                    . '<li>Zona 2 (Sungai Siring – Tanah Merah): Rp100.000</li>'
                    . '<li>Zona 3 (Lempake, Juanda, Pasar Pagi, Sungai Dama): Rp185.000</li>'
                    . '<li>Zona 4 (Antasari, Pelita, Suryanata, Loa Bakung): Rp225.000</li>'
                    . '<li>Zona 5 (Samarinda Seberang, Palaran, Loa Buah): Rp275.000</li>'
                    . '</ul>'
                    . '<p><strong>Tarif Luar Kota (Shuttle)</strong></p>'
                    . '<ul>'
                    . '<li>Sangatta: Rp275.000</li>'
                    . '<li>Simpang 3 Bontang: Rp250.000</li>'
                    . '</ul>',
                'category' => 'Fasilitas Bandara',
                'service_id' => null,
                'sort_order' => 4,
                'is_featured' => true,
                'is_active' => true,
            ],

            // ==================================================
            // Layanan & Perizinan
            // ==================================================
            [
                'question' => 'Apakah Bandara A.P.T. Pranoto Samarinda menyediakan layanan kargo?',
                'answer' => '<p>Ya. Bandara A.P.T. Pranoto Samarinda memiliki <strong>Gedung Kargo Lini 2</strong> yang melayani kegiatan Ekspedisi Muatan Pesawat Udara (EMPU).</p><p>Layanan kargo beroperasi setiap hari pada pukul <strong>08.00–17.00 WITA</strong> dan didukung oleh beberapa perusahaan kargo untuk melayani kebutuhan pengiriman barang melalui transportasi udara, termasuk informasi terkait tarif, pengiriman, dan jenis layanan.</p>',
                'category' => 'Layanan & Perizinan',
                'service_id' => null,
                'sort_order' => 1,
                'is_featured' => true,
                'is_active' => true,
            ],

            // ==================================================
            // Informasi Publik & Pengaduan
            // ==================================================
            [
                'question' => 'Bagaimana cara mengajukan pengaduan atau saran?',
                'answer' => '<p>Pengaduan dan saran dapat disampaikan melalui:</p>'
                    . '<ul>'
                    . '<li>Media sosial resmi bandara</li>'
                    . '<li>WhatsApp pengaduan: <a href="https://wa.me/62811551944" target="_blank" rel="noopener">+62 811-551-944</a></li>'
                    . '<li>Website resmi bandara</li>'
                    . '<li>Kotak saran yang tersedia di terminal</li>'
                    . '<li><a href="https://www.lapor.go.id/" target="_blank" rel="noopener">SP4N-LAPOR!</a></li>'
                    . '</ul>',
                'category' => 'Informasi Publik & Pengaduan',
                'service_id' => null,
                'sort_order' => 1,
                'is_featured' => true,
                'is_active' => true,
            ],
        ];
    }
}
