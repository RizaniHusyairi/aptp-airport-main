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
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

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
                ['name' => 'PAS', 'route' => 'https://aptpranoto.id/website/layanan/pas_orang.html', 'external' => true],
                ['name' => 'TIM', 'route' => 'https://aptpranoto.id/website/layanan/tim.html', 'external' => true],
            ];

            // 4. Loop hasil query dan ubah menjadi format array menu, lalu gabungkan
            foreach ($activeServices as $service) {
                $serviceMenuItems[] = [
                    'name' => $service->name,
                    'route' => route('layanan.show', $service->slug) // Menggunakan route dinamis yang baru
                ];
            }
            
            $menuItems = [
                'header' => [
                    ['name' => 'Beranda', 'route' => route('home')],
                    
                    ['name' => 'Informasi Publik', 'dropdown' => [
                        ['name' => 'Profil Bandara', 'route' => route('profilBandara')],
                        ['name' => 'Struktur Organisasi', 'route' => route('strukturOrganisasi')],
                        ['name' => 'Pejabat Bandara', 'route' => route('pejabatBandara')],
                        [
                            'name' => 'PPID',
                            'dropdown' => [
                                [
                                    'name' => 'Profil PPID BLU',
                                    'route' => route('profilPPID'),
                                ],
                                [
                                    'name' => 'SOP PPID',
                                    'route' => route('sopPpid'),
                                ],
                                
                                [
                                    'name' => 'Pengajuan Informasi Publik', 
                                    'route' => route('pengajuanInformasiPublik')
                                ],
                            ],
                        ]
                        ],

                    ],
                    
                    ['name' => 'Informasi', 'dropdown' => [
                        ['name' => 'Berita', 'route' => route('berita')],
                        ['name' => 'Laporan Keuangan', 'route' => route('laporanKeuangan')],
                    ]],
                    
                    ['name' => 'Regulasi','dropdown' =>[
                        ['name' => 'Surat Keputusan', 'route' => route('letters.utusan')],
                        ['name' => 'Surat Edaran', 'route' => route('letters.edaran')],
                        ]
                    ],

                    ['name' => 'Layanan', 'dropdown' => $serviceMenuItems]
                ]
            ];
            
            $view->with(compact( 'menuItems'));
        });

        View::composer('layouts-V2.sidebars.pengaju', function ($view) {
            $userRoutes = [
                'Ajukan Tenant' => ['route' => 'tenant.index', 'icon' => 'bi bi-shop', 'label' => 'Ajukan Tenant'],
                'Ajukan Sewa' => ['route' => 'sewa.index', 'icon' => 'bi bi-cart', 'label' => 'Ajukan Sewa'],
                'Ajukan Perijinan Usaha' => ['route' => 'perijinan.index', 'icon' => 'bi bi-file-earmark-text', 'label' => 'Ajukan Perijinan Usaha'],
                'Ajukan Pengiklanan' => ['route' => 'pengiklanan.index', 'icon' => 'bi bi-megaphone', 'label' => 'Ajukan Pengiklanan'],
                'Ajukan Field Trip' => ['route' => 'fieldtrip.index', 'icon' => 'bi bi-bus-front', 'label' => 'Ajukan Field Trip'],
                'Ajukan Lelang' => ['route' => 'lelang.index', 'icon' => 'bi bi-hammer', 'label' => 'Ajukan Lelang/Beauty Contest'],
                'Ajukan Slot Charter' => ['route' => 'slot.index', 'icon' => 'bi bi-clock', 'label' => 'Ajukan Slot Charter'],
                'Ajukan Perijinan Kerja' => ['route' => 'kerja.userindex', 'icon' => 'bi bi-person-workspace', 'label' => 'Ajukan Perizinan Kerja'],
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
                'Manajemen Lelang' => ['route' => 'lelang.staffIndex', 'icon' => 'bi bi-hammer', 'label' => 'Lelang/Beauty Contest'],
                'Manajemen Slot Charter' => ['route' => 'slot.staffIndex', 'icon' => 'bi bi-clock', 'label' => 'Slot Charter'],
                'Manajemen Perijinan Kerja' => ['route' => 'kerja.index', 'icon' => 'bi bi-person-workspace', 'label' => 'Perizinan Kerja'],
                'Manajemen Laporan Keuangan' => ['route' => 'keuangan.staffIndex', 'icon' => 'bi bi-graph-up', 'label' => 'Laporan Keuangan'],
                'Manajemen Slider' => ['route' => 'slider.staffIndex', 'icon' => 'bi bi-image', 'label' => 'Slider'],
                'Manajemen Ajuan Informasi Publik' => ['route' => 'informasiPublik.staffIndex', 'icon' => 'bi bi-info-circle', 'label' => 'Informasi Publik'],
                'Manajemen Lalu Lintas Angkutan Udara' => ['route' => 'laluLintas.staffIndex', 'icon' => 'bi bi-airplane', 'label' => 'Lalu Lintas Angkutan Udara'],
                'Manajemen Pengaduan' => ['route' => 'pengaduan.staffIndex', 'icon' => 'bi bi-exclamation-triangle', 'label' => 'Pengaduan'],
                'Manajemen Regulasi' => ['route' => 'letters.staff.index', 'icon' => 'bi bi-book', 'label' => 'Regulasi'],
            ];

            $view->with('permissionRoutes',$permissionRoutes);
        });
    }
}
