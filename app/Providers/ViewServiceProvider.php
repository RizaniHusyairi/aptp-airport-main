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
            $headlineCount = News::where('is_published', true)
                          ->where('is_headline', true)
                          ->count();

            $topikUtama = $headlineCount > 0
                            ? News::where('is_published', true)
                                ->where('is_headline', true)
                                ->latest()
                                ->take(3)
                                ->get()
                            : News::where('is_published', true)
                                ->inRandomOrder()
                                ->latest()
                                ->take(3)
                                ->get();

            $view->with(compact('topikUtama', 'menuItems'));
        });
    }
}
