<?php

namespace App\Providers;

use App\Models\News;
use App\Models\Slider;
use App\Models\Service;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {

            // --- AWAL BAGIAN YANG DIUBAH ---

            // 2. Ambil semua layanan yang aktif dari database, diurutkan berdasarkan nama
            $activeServices = Service::where('is_active', true)->orderBy('name', 'asc')->get();

            // 3. Siapkan item menu statis yang ingin dipertahankan di atas
            $serviceMenuItems = [
                ['name' => 'PAS', 'route' => 'https://pas.aptpairport.id/website/layanan/pas_orang.html', 'external' => true],
                ['name' => 'TIM', 'route' => 'https://pas.aptpairport.id/website/layanan/tim.html', 'external' => true],
                ['name' => 'Keuangan dan Penagihan', 'route' => 'https://sikeren.aptpairport.id', 'external' => true],
            ];

            // 4. Loop hasil query dan ubah menjadi format array menu, lalu gabungkan
            foreach ($activeServices as $service) {
                if($service->slug == "informasi-publik") {
                    continue; // Lewati layanan tanpa slug
                }

                // Jika submission_url berupa URL eksternal lengkap, arahkan menu langsung ke sana
                if (\Illuminate\Support\Str::startsWith($service->submission_url, ['http://', 'https://'])) {
                    $serviceMenuItems[] = [
                        'name' => $service->name,
                        'route' => $service->submission_url, // Tautan eksternal langsung
                        'external' => true,
                    ];
                } else {
                    $serviceMenuItems[] = [
                        'name' => $service->name,
                        'route' => route('layanan.show', $service->slug) // Menggunakan route dinamis yang baru
                    ];
                }
            }

            $menuItems = [
                'header' => [
                    ['name' => 'Beranda', 'route' => route('home')],

                    ['name' => 'Informasi Publik', 'dropdown' => [
                        ['name' => 'Profil Bandara', 'route' => route('profilBandara')],
                        ['name' => 'Struktur Organisasi', 'route' => route('strukturOrganisasi')],
                        ['name' => 'Pejabat Bandara', 'route' => route('pejabatBandara')],
                        ['name' => 'Fasilitas Bandara' ,'route' => route('fasilitas')]

                        ],

                    ],

                    ['name' => 'PPID', 'dropdown' => [
                        [
                            'name' => 'Profil PPID BLU',
                            'route' => route('profilPPID'),
                        ],
                        [
                            'name' => 'SOP PPID',
                            'route' => route('sopPpid'),
                        ],
                        [
                            'name' => 'Standar Pelayanan',
                            'route' => route('standarPelayanan'),
                        ],
                        [
                            'name' => 'Pengajuan Informasi Publik',
                            'route' => route('layanan.show', 'informasi-publik'),
                        ],
                        [
                            'name' => 'Regulasi PPID',
                            'route' => route('regulasi.ppid'),
                        ],
                        ['name' => 'Layanan Informasi', 'dropdown' => [
                            ['name' => 'Laporan Layanan Informasi', 'route' => route('laporan.layanan.informasi')],
                            ['name' => 'Informasi Berkala', 'route' => route('informasiBerkala')],
                            ['name' => 'Informasi Serta Merta', 'route' => route('informasi.serta-merta')],
                            ['name' => 'Informasi Setiap Saat', 'route' => route('informasi.setiap-saat')],
                        ]],
                    ]],
                    ['name' => 'Informasi', 'dropdown' => [
                        ['name' => 'Berita', 'route' => route('berita')],
                        ['name' => 'Kinerja Keuangan', 'route' => route('laporanKeuangan')],
                        ['name' => 'FAQ', 'route' => route('faq')],
                    ]],


                    ['name' => 'Regulasi','dropdown' =>[
                        ['name' => 'Surat Keputusan', 'route' => route('letters.utusan')],
                        ['name' => 'Surat Edaran', 'route' => route('letters.edaran')],
                        ]
                    ],

                    ['name' => 'Layanan', 'dropdown' => $serviceMenuItems]
                ]
            ];

            // Menu Tautan Terkait, dibangun dari helper externalLinks() yang ter-cache.
            // Submenu sengaja dibuat rata (tanpa sarang per kelompok) karena renderer
            // header hanya meneruskan target="_blank" pada tingkat kedua.
            $tautanMenuItems = [];
            foreach (externalLinks() as $links) {
                foreach ($links as $link) {
                    $tautanMenuItems[] = [
                        'name' => $link['name'],
                        'route' => $link['url'],
                        'external' => true,
                    ];
                }
            }

            if (!empty($tautanMenuItems)) {
                $tautanMenuItems[] = ['name' => 'Semua Tautan Terkait', 'route' => route('tautanTerkait')];
                $menuItems['header'][] = ['name' => 'Tautan Terkait', 'dropdown' => $tautanMenuItems];
            }

            $view->with(compact( 'menuItems'));
        });

        View::composer('layouts-V2.sidebars.pengaju', function ($view) {
            $userRoutes = [
                'Ajukan Tenant' => ['route' => 'tenant.index', 'icon' => 'bi bi-shop', 'label' => 'Ajukan Tenant'],
                'Ajukan Sewa' => ['route' => 'sewa.index', 'icon' => 'bi bi-cart', 'label' => 'Ajukan Sewa'],
                'Ajukan Perijinan Usaha' => ['route' => 'perijinan.index', 'icon' => 'bi bi-file-earmark-text', 'label' => 'Ajukan Perijinan Usaha'],
                'Ajukan Pengiklanan' => ['route' => 'pengiklanan.index', 'icon' => 'bi bi-megaphone', 'label' => 'Ajukan Pengiklanan'],
                'Ajukan Field Trip' => ['route' => 'fieldtrip.index', 'icon' => 'bi bi-bus-front', 'label' => 'Ajukan Field Trip'],
                'Ajukan Lelang' => ['route' => 'lelang.index', 'icon' => 'bi bi-hammer', 'label' => 'Ajukan Beauty Contest'],
                'Ajukan Slot Charter' => ['route' => 'slot.index', 'icon' => 'bi bi-clock', 'label' => 'Ajukan Slot Charter'],
                'Ajukan Extend Advance' => ['route' => 'extend-advance.index', 'icon' => 'bi bi-clock-history', 'label' => 'Ajukan Extend Advance'],
                'Ajukan Perijinan Kerja' => ['route' => 'kerja.userindex', 'icon' => 'bi bi-person-workspace', 'label' => 'Ajukan Perizinan Kerja'],
                'Ajukan Informasi Publik' => ['route' => 'informasiPublik.index', 'icon' => 'bi bi-info-circle', 'label' => 'Ajukan Informasi Publik'],
                'Ajukan Sertifikat OJT' => ['route' => 'user.ojt.index', 'icon' => 'bi bi-award', 'label' => 'Ajukan Sertifikat OJT'],
            ];  
            $view->with('userRoutes', $userRoutes);
        });

        View::composer('layouts-V2.sidebars.staff', function($view){
            $permissionRoutes = [
                'Manajemen Berita' => ['route' => 'berita.staffIndex', 'icon' => 'bi bi-newspaper', 'label' => 'Berita'],
                'Manajemen Tenant' => ['route' => 'tenant.staffIndex', 'icon' => 'bi bi-shop', 'label' => 'Tenant'],
                'Manajemen Sewa' => ['route' => 'staffSewa.index', 'icon' => 'bi bi-building', 'label' => 'Penyewaan'],
                'Manajemen Perijinan Usaha' => ['route' => 'perijinan.staffIndex', 'icon' => 'bi bi-file-earmark-check', 'label' => 'Perijinan Usaha'],
                'Manajemen Pengiklanan' => ['route' => 'pengiklanan.staffIndex', 'icon' => 'bi bi-megaphone', 'label' => 'Pengiklanan'],
                'Manajemen Field Trip' => ['route' => 'fieldtrip.staffIndex', 'icon' => 'bi bi-geo-alt', 'label' => 'Field Trip'],
                'Manajemen Lelang' => ['route' => 'lelang.staffIndex', 'icon' => 'bi bi-hammer', 'label' => 'Beauty Contest'],
                'Manajemen Slot Charter' => ['route' => 'slot.staffIndex', 'icon' => 'bi bi-clock', 'label' => 'Slot Charter'],
                'Manajemen Extend Advance' => ['route' => 'extend-advance.staffIndex', 'icon' => 'bi bi-clock-history', 'label' => 'Extend Advance'],
                'Manajemen Perijinan Kerja' => ['route' => 'kerja.index', 'icon' => 'bi bi-person-workspace', 'label' => 'Perizinan Kerja'],
                'Manajemen Kinerja Keuangan' => ['route' => 'keuangan.staffIndex', 'icon' => 'bi bi-graph-up', 'label' => 'Kinerja Keuangan'],
                'Manajemen Ajuan Informasi Publik' => ['route' => 'informasiPublik.staffIndex', 'icon' => 'bi bi-info-circle', 'label' => 'Informasi Publik'],
                'Manajemen Lalu Lintas Angkutan Udara' => ['route' => 'staff.air-traffic.index', 'icon' => 'bi bi-graph-up-arrow', 'label' => 'Data LLAU'],
                // 'Manajemen Lalu Lintas Angkutan Udara' => ['route' => 'laluLintas.staffIndex', 'icon' => 'bi bi-airplane', 'label' => 'Lalu Lintas Angkutan Udara'],
                'Manajemen Pengaduan' => ['route' => 'pengaduan.staffIndex', 'icon' => 'bi bi-exclamation-triangle', 'label' => 'Pengaduan'],
                'Manajemen Regulasi' => ['route' => 'letters.staff.index', 'icon' => 'bi bi-book', 'label' => 'Regulasi'],
                'Manajemen Program Kerja' => ['route' => 'staff.work-programs.index', 'icon' => 'bi bi-clipboard-check', 'label' => 'Program Kerja'],
 'Manajemen Inventaris' => ['route' => 'staff.inventories.index', 'icon' => 'bi bi-box-seam', 'label' => 'Inventaris'],
                'Manajemen Suku Cadang' => ['route' => 'staff.spare-parts.index', 'icon' => 'bi bi-gear-wide-connected', 'label' => 'Suku Cadang'],
                'Permintaan Suku Cadang' => ['route' => 'staff.spare-part-requests.index', 'icon' => 'bi bi-send-check', 'label' => 'Permintaan Suku Cadang'],
                'Manajemen Absensi Rapat' => ['route' => 'staff.meetings.index', 'icon' => 'bi bi-calendar-event', 'label' => 'Absensi Rapat'],
                'Manajemen Posko Nataru' => ['route' => 'staff.nataru.index', 'icon' => 'bi bi-airplane-engines', 'label' => 'Posko Nataru'],
                'Manajemen Sertifikat OJT' => ['route' => 'staff.ojt.index', 'icon' => 'bi bi-mortarboard', 'label' => 'Data OJT'],

            ];

            $view->with('permissionRoutes',$permissionRoutes);
        });
    }
}
