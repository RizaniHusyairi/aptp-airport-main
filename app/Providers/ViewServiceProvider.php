<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Slider;
use App\Models\News;

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
                        // ['name' => 'Profil PPID BLU', 'route' => route('profilPPID')],
                        // ['name' => 'SOP PPID', 'route' => route('sopPpid')],
                        ],

                    ],
                    
                    ['name' => 'Informasi', 'dropdown' => [
                        ['name' => 'Berita', 'route' => route('berita')],
                        ['name' => 'Laporan Keuangan', 'route' => route('laporanKeuangan')],
                    ]],
                    
                    ['name' => 'Regulasi','dropdown' =>[
                        ['name' => 'Surat Utusan', 'route' => route('letters.utusan')],
                        ['name' => 'Surat Edaran', 'route' => route('letters.edaran')],
                        ]
                    ],

                    ['name' => 'Layanan', 'dropdown' => [
                        ['name' => 'PAS', 'route' => 'https://aptpranoto.id/website/layanan/pas_orang.html'],
                        ['name' => 'Tenant', 'route' => route('tenant')],
                        ['name' => 'Sewa', 'route' => route('sewa')],
                        ['name' => 'Perijinan Usaha', 'route' => route('perijinanUsaha')],
                        ['name' => 'Pengiklanan', 'route' => route('pengiklanan')],
                        ['name' => 'Field Trip', 'route' => route('fieldTrip')],
                        ['name' => 'Beauty Contest', 'route' => route('lelang')],
                        ['name' => 'Pengajuan Slot', 'route' => route('slot')],
                    ]
                    ]
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
