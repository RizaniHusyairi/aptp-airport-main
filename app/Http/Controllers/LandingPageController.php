<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\News;
use App\Models\Letter;
use App\Models\Slider;
use App\Models\Finance;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Tourism;
use App\Models\Visitor;
use App\Models\Facility;
use App\Models\Complaint;
use App\Models\InfoSlide;
use App\Jobs\LogVisitorJob;
use Illuminate\Http\Request;
use App\Models\AirTrafficLog;
use App\Models\BudgetExpense;
use App\Models\Faq;
use App\Models\PpidRegulation;
use App\Models\ServiceStandard;
use App\Models\PeriodicDocument;
use App\Models\AirFreightTraffic;
use App\Models\PublicInformation;
use Illuminate\Support\Facades\DB;
use App\Services\AirportApiService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\EvergreenInformation;
use App\Models\ImmediateInformation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\InformationServiceReport;
use Illuminate\Support\Facades\Validator;



class LandingPageController extends Controller
{
    protected $airportApi;

    public function __construct(AirportApiService $airportApi)
    {
        $this->airportApi = $airportApi;
    }

    public function getFlightStats()
    {
        $stats = $this->airportApi->getFlightStats();
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get departures list
     */
    public function getDepartures(Request $request)
    {
        $departures = $this->airportApi->getDeparturesList();

        if (empty($departures)) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data keberangkatan. Silakan coba lagi nanti.',
                'data' => []
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $this->filterFlights($departures, $request),
        ]);
    }

    /**
     * Get arrivals list for frontend
     */
    public function getArrivals(Request $request)
    {
        $arrivals = $this->airportApi->getArrivalsList();

        if (empty($arrivals)) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kedatangan. Silakan coba lagi nanti.',
                'data' => []
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $this->filterFlights($arrivals, $request),
        ]);
    }

    /**
     * Saring daftar penerbangan sebelum dikirim ke frontend.
     *
     * API hulu mengirim data lebih dari satu hari, sehingga tanpa penyaringan
     * halaman ikut menampilkan jadwal kemarin.
     *
     * Penyaringan dilakukan di server, bukan di JavaScript, karena "hari ini"
     * harus mengikuti waktu bandara (Asia/Makassar). Bila dihitung di peramban,
     * hasilnya akan berbeda bagi pengunjung di zona waktu lain atau yang jam
     * perangkatnya tidak tepat.
     *
     * Parameter `recent=1` dipakai beranda: penerbangan yang sudah berstatus
     * selesai (Departed/Arrived/Landed) dan jadwalnya lewat lebih dari 2 jam
     * ikut disembunyikan agar papan ringkas hanya memuat yang masih relevan.
     */
    protected function filterFlights(array $flights, Request $request): array
    {
        $now = Carbon::now('Asia/Makassar');
        $today = $now->toDateString();
        $hideCompleted = $request->boolean('recent');

        $filtered = array_filter($flights, function ($flight) use ($today, $now, $hideCompleted) {
            // 1. Hanya jadwal hari ini
            if (($flight['tanggal'] ?? null) !== $today) {
                return false;
            }

            if (! $hideCompleted) {
                return true;
            }

            // 2. Khusus beranda: buang yang sudah selesai lebih dari 2 jam lalu
            $status = strtolower((string) ($flight['remark']['status'] ?? ''));
            $isCompleted = str_contains($status, 'depart')
                || str_contains($status, 'arriv')
                || str_contains($status, 'land');

            if (! $isCompleted) {
                return true;
            }

            $time = $flight['jam'] ?? null;
            if (! $time) {
                return true;
            }

            try {
                $schedule = Carbon::createFromFormat('Y-m-d H:i', $today . ' ' . substr($time, 0, 5), 'Asia/Makassar');
            } catch (\Exception $e) {
                // Format jam tak terduga: tampilkan saja daripada menyembunyikan data
                return true;
            }

            return $schedule->greaterThan($now->copy()->subHours(2));
        });

        return array_values($filtered);
    }
    
    public function home(Request $request)
    {
        // BARU: Cache query untuk destinasi wisata selama 1 jam
        $destinations = Cache::remember('home_destinations', now()->addHour(), function() {
            return Tourism::where('status', 'published')->latest()->take(3)->get();
        });

        // // BARU: Cache query untuk sliders selama 1 jam
        // $sliders = Slider::where('is_visible_home', 1)->get();
        // Ambil data sliders (dari kode Anda sebelumnya)
        $sliders = Slider::all(); // Atau query lain sesuai kebutuhan


        // Ambil data statistik penerbangan (dari kode Anda sebelumnya)
        $flightStats = $this->airportApi->getFlightStats();
        // Contoh data dummy jika API belum siap:
        // $flightStats = ['total_flights' => 120, 'total_passengers' => 15000, 'cargo_volume' => 50000];
        $totalAngkutanUdara = $flightStats['total_flights'] ?? 0; // Sesuaikan key jika berbeda

        $heroSettings = Setting::whereIn('key', [
                'hero_type',
                'hero_image_path',
                'hero_video_path'
            ])
            ->pluck('value', 'key')
            ->all(); // Ambil sebagai array

        // Berikan nilai default jika pengaturan belum ada
        $heroSettings = array_merge([
            'hero_type' => 'image', // Default ke gambar jika belum diatur
            'hero_image_path' => null,
            'hero_video_path' => null,
        ], $heroSettings);

        // BARU: Cache query untuk total angkutan udara selama 3 jam
        // $totalAngkutanUdara = Cache::remember('total_air_freight_monthly', now()->addHours(3), function() {
        //     return AirFreightTraffic::whereYear('date', now()->year)
        //                               ->whereMonth('date', now()->month)
        //                               ->sum(DB::raw('arrival + departure'));
        // });

        // BARU: Cache query untuk berita utama selama 15 menit
        $headlines = Cache::remember('home_headlines', now()->addMinutes(15), function() {
            return News::where('is_published', true)
                       ->where('is_headline', true)
                       ->orderBy('created_at', 'desc')
                       ->take(3)
                       ->get();
        });

        $ip = $request->ip(); // IP Address pengunjung
        $userAgent = $request->header('User-Agent'); // Informasi browser/device
    
        $facilityImages = [
            'udara' => Facility::where('category', 'udara')->latest()->first(),
            'darat' => Facility::where('category', 'darat')->latest()->first(),
            'umum'  => Facility::where('category', 'umum')->latest()->first(),
        ];
        
        // SESUDAH:
        LogVisitorJob::dispatch($request->ip(), $request->header('User-Agent'));
        // =========================
        // Panggil API
        $flightStats = $this->airportApi->getFlightStats();
        $weather = $this->airportApi->getCurrentWeather();
           // ### BARU: Ambil data untuk info slider ###
        $infoSlides = InfoSlide::where('is_visible', true)->latest()->get();

        
        $meta = [
            'title' => 'APT Pranoto - Bandara Samarinda',
            'description' => 'Sistem Informasi Bandara APT Pranoto, menyediakan data lalu lintas, cuaca, dan berita.',
            'keywords' => 'bandara, APT Pranoto, Samarinda, cuaca, lalu lintas',
        ];

        // FAQ unggulan untuk section ringkas di beranda
        $featuredFaqs = Faq::active()
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        return view('landing-menu.beranda.index',
        compact(
            'sliders',
            'infoSlides',
            'flightStats',
            'totalAngkutanUdara',
            'headlines',
            'destinations',
            'weather',
            'meta',
            'facilityImages',
            'heroSettings',
            'featuredFaqs'
        ));
    }

    // app/Http/Controllers/LandingPageController.php

    // METHOD BARU UNTUK MENYEDIAKAN DATA RUTE DOMESTIK
    /**
     * Data jaringan rute Bandar Udara A.P.T. Pranoto.
     *
     * Disamakan dengan dokumen resmi bandara (lihat halaman Profil Bandara
     * dan FAQ): enam rute utama dan empat rute perintis.
     *
     * Koordinat memakai lintang/bujur sesungguhnya karena peta pada beranda
     * digambar di atas peta geografis, bukan lagi gambar latar statis.
     *
     * CATATAN: koordinat bandara perintis (Melak, Datah Dawai, Muara Wahau)
     * masih perlu diverifikasi pengelola. Bila ada titik yang meleset,
     * cukup sesuaikan nilai lat/lng di bawah ini.
     */
    public function getDomesticRoutesData()
    {
        $logo = fn (string $file) => asset('assets_landing/img/mitra/' . $file);

        $batik = ['nama' => 'Batik Air', 'logo' => $logo('logo-batik.png')];
        $citilink = ['nama' => 'Citilink', 'logo' => $logo('logo-citilink.png')];
        $garuda = ['nama' => 'Garuda Indonesia', 'logo' => $logo('logo-garuda.png')];
        $saj = ['nama' => 'Super Air Jet', 'logo' => $logo('logo-SAJ.png')];
        $wings = ['nama' => 'Wings Air', 'logo' => $logo('logo-wings.png')];
        $smart = ['nama' => 'Smart Aviation', 'logo' => $logo('logo-smart.jpg')];

        $routesData = [
            // ---------- Rute utama ----------
            [
                'kota' => 'Jakarta',
                'kode' => 'CGK',
                'provinsi' => 'Banten',
                'jenis' => 'utama',
                'lat' => -6.1256,
                'lng' => 106.6559,
                'maskapai' => [$batik, $citilink, $garuda],
            ],
            [
                'kota' => 'Surabaya',
                'kode' => 'SUB',
                'provinsi' => 'Jawa Timur',
                'jenis' => 'utama',
                'lat' => -7.3798,
                'lng' => 112.7869,
                'maskapai' => [$saj, $citilink],
            ],
            [
                'kota' => 'Yogyakarta',
                'kode' => 'YIA',
                'provinsi' => 'DI Yogyakarta',
                'jenis' => 'utama',
                'lat' => -7.9055,
                'lng' => 110.0572,
                'maskapai' => [$saj, $batik],
            ],
            [
                'kota' => 'Banjarmasin',
                'kode' => 'BDJ',
                'provinsi' => 'Kalimantan Selatan',
                'jenis' => 'utama',
                'lat' => -3.4424,
                'lng' => 114.7625,
                // Maskapai belum ditetapkan — silakan lengkapi lewat data ini
                'maskapai' => [],
            ],
            [
                'kota' => 'Berau',
                'kode' => 'BEJ',
                'provinsi' => 'Kalimantan Timur',
                'jenis' => 'utama',
                'lat' => 2.1555,
                'lng' => 117.4324,
                'maskapai' => [$wings],
            ],
            [
                'kota' => 'Melak',
                'kode' => '',
                'provinsi' => 'Kalimantan Timur',
                'jenis' => 'utama',
                'lat' => -0.2130,
                'lng' => 115.7800,
                'maskapai' => [],
            ],

            // ---------- Rute perintis ----------
            [
                'kota' => 'Long Apung',
                'kode' => 'LPU',
                'provinsi' => 'Kalimantan Utara',
                'jenis' => 'perintis',
                'lat' => 1.7000,
                'lng' => 114.9700,
                'maskapai' => [$smart],
            ],
            [
                'kota' => 'Maratua',
                'kode' => 'RTU',
                'provinsi' => 'Kalimantan Timur',
                'jenis' => 'perintis',
                'lat' => 2.2300,
                'lng' => 118.5200,
                'maskapai' => [$smart],
            ],
            [
                'kota' => 'Datah Dawai',
                'kode' => 'DTD',
                'provinsi' => 'Kalimantan Timur',
                'jenis' => 'perintis',
                'lat' => 0.8280,
                'lng' => 114.5320,
                'maskapai' => [$smart],
            ],
            [
                'kota' => 'Muara Wahau',
                'kode' => 'MHU',
                'provinsi' => 'Kalimantan Timur',
                'jenis' => 'perintis',
                'lat' => 1.1000,
                'lng' => 116.7500,
                'maskapai' => [$smart],
            ],
        ];

        return response()->json([
            'success' => true,
            // Titik pusat jaringan
            'hub' => [
                'kota' => 'Samarinda',
                'kode' => 'AAP',
                'provinsi' => 'Kalimantan Timur',
                'lat' => -0.3745,
                'lng' => 117.2500,
            ],
            'data' => $routesData,
        ]);
    }

    

    // METHOD BARU UNTUK MENGHUBUNGI GEMINI API
    public function generateTripPlan(Request $request)
    {
        // 1. Validasi input dari frontend
        $validator = Validator::make($request->all(), [
            'tujuan' => 'required|string|max:100',
            'durasi' => 'required|integer|min:1|max:10',
        ]);
        // dd($request);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => 'Input tidak valid.'], 422);
        }

        $tujuan = $request->input('tujuan');
        $durasi = $request->input('durasi');
        
        // 2. Ambil API Key dari config (yang membaca .env)
        $apiKey = config('services.gemini.api_key');

        if (!$apiKey) {
            return response()->json(['success' => false, 'error' => 'Kunci API Gemini tidak dikonfigurasi.'], 500);
        }

        // 3. Buat prompt untuk Gemini
        $prompt = "Anda adalah asisten perjalanan yang ramah dan antusias. Buatkan contoh rencana perjalanan (itinerary) yang menarik dan detail untuk liburan ke kota \"{$tujuan}\" selama {$durasi} hari. Berikan jawaban dalam format Markdown. Untuk setiap hari, buat judul (misal: \"**Hari 1: Petualangan Kuliner dan Sejarah**\") diikuti dengan daftar kegiatan dalam bentuk unordered list (menggunakan tanda -). Sertakan juga beberapa rekomendasi tempat makan khas di setiap harinya.";

        // 4. Kirim permintaan ke Google menggunakan Laravel HTTP Client
        $response = Http::timeout(60)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]);

        // 5. Periksa dan teruskan jawaban kembali ke frontend
        if ($response->successful() && isset($response->json()['candidates'][0]['content']['parts'][0]['text'])) {
            $generatedText = $response->json()['candidates'][0]['content']['parts'][0]['text'];
            return response()->json(['success' => true, 'plan' => $generatedText]);
        }
        
        // Tangani jika ada error dari API Google
        Log::error('Gemini API Error:', ['response' => $response->body()]);
        return response()->json(['success' => false, 'error' => 'Gagal mendapatkan jawaban dari AI.'], 500);
    }

    // public function getMonthlyTrafficStats()
    // {
    //     try {
    //         // Cache hasil ini untuk mengurangi beban database
    //         $stats = Cache::remember('monthly_traffic_stats_full', now()->addHours(3), function () {
    //             $now = \Carbon\Carbon::now();
    //             $query = \App\Models\AirFreightTraffic::whereYear('date', $now->year)
    //                                                 ->whereMonth('date', $now->month);

    //             // Ambil semua data dalam satu query untuk efisiensi
    //             $monthlyData = (clone $query)
    //                 ->groupBy('type')
    //                 ->select('type', DB::raw('SUM(arrival + departure) as total'))
    //                 ->pluck('total', 'type');

    //             // Siapkan data dengan nilai default 0
    //             $data = [
    //                 'aircraft'   => (int) ($monthlyData['Pesawat'] ?? 0),
    //                 'passengers' => (int) ($monthlyData['Penumpang'] ?? 0),
    //                 'transit'    => (int) ($monthlyData['Penumpang Transit'] ?? 0),
    //                 'baggage'    => (int) ($monthlyData['Bagasi'] ?? 0),
    //                 'cargo'      => (int) ($monthlyData['Kargo'] ?? 0),
    //                 'mail'       => (int) ($monthlyData['Pos'] ?? 0),
    //             ];
                
    //             // Hitung total semua aktivitas
    //             $data['total'] = array_sum($data);

    //             return $data;
    //         });

    //         return response()->json([
    //             'success' => true,
    //             'data'    => $stats,
    //         ]);

    //     } catch (\Exception $e) {
    //         Log::error('Error fetching monthly traffic stats: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Gagal mengambil data statistik.'], 500);
    //     }
    // }

    public function pariwisata(Request $request){
        $query = Tourism::where('status', 'published');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $destinations = $query->latest()->paginate(9);

        // INI BAGIAN KUNCI-NYA
        if ($request->ajax()) {
            // Jika ini adalah request AJAX, kembalikan hanya partial view
            return view('landing-menu.pariwisata.partials.destination_list', compact('destinations'))->render();
        }

    // Jika ini adalah request halaman penuh (load pertama kali)
        return view('landing-menu.pariwisata.index', compact('destinations'));
    }

    public function detailPariwisata($slug){
        // CONTOH DATA: Logika untuk menemukan data berdasarkan slug
        
        // Cari destinasi berdasarkan slug yang unik, jika tidak ada akan menampilkan error 404
        $destination = Tourism::where('slug', $slug)
                              ->where('status', 'published')
                              ->firstOrFail();
        
        return view('landing-menu.pariwisata.detail', compact('destination'));

        return view('landing-menu.pariwisata.detail', compact('destination'));

    }

    public function getFeaturedTourism(){
        try {
            $destinations = Cache::remember('featured_tourism', now()->addHour(), function () {
                return Tourism::where('status', 'published')->latest()->take(3)->get();
            });
            return response()->json(['success' => true, 'data' => $destinations]);
        } catch (\Exception $e) {
            Log::error('Error fetching featured tourism: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }


    //berita
    public function berita()
    {
        
        // Ambil 3 berita headline pertama untuk newsFirstSwiper
        $topHeadlines = News::where('is_headline', true)
                           ->where('is_published', true)
                           ->orderBy('created_at', 'desc')
                           ->take(3)
                           ->get();

        // Ambil 5 berita headline berikutnya untuk news-swiper (skip 3 pertama)
        $nextHeadlines = News::where('is_headline', true)
                            ->where('is_published', true)
                            ->orderBy('created_at', 'desc')
                            ->skip(3)
                            ->take(5)
                            ->get();

        // Ambil berita lainnya (is_headline = false dan is_published = true)
        $otherNews = News::where('is_headline', false)
                        ->where('is_published', true)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('landing-menu.informasi.berita.index', 
        compact('topHeadlines', 'nextHeadlines', 'otherNews'));    
    }


    public function showNews($slug)
    {
        // Ambil berita utama yang sedang dibuka
        $news = News::where('slug', $slug)->where('is_published', true)->firstOrFail();

        // Ambil 3 berita terbaru lainnya sebagai "Berita Terkait"
        // Pastikan untuk tidak menyertakan berita yang sedang dibuka
        $relatedNews = News::where('is_published', true)
                            ->where('id', '!=', $news->id) // Exclude the current news
                            ->latest() // Ambil yang paling baru
                            ->take(3)  // Batasi hanya 3 berita
                            ->get();
        return view('landing-menu.informasi.berita.detail', compact('news','relatedNews'));
    
    }

    public function showServicePage($slug)
    {
        $service = Service::where('slug', $slug)->where('is_active', true)->firstOrFail();

        // FAQ yang dikaitkan ke layanan ini
        $serviceFaqs = Faq::active()
            ->where('service_id', $service->id)
            ->orderBy('sort_order')
            ->get();

        return view('landing-menu.layanan.index', compact('service', 'serviceFaqs'));
    }


    
    public function profilBandara()
    {
        // Ambil pengaturan profil dari database
        $settings = Setting::whereIn('key', [
            'profile_sejarah',
            'profile_status',
            'profile_rute',
            'profile_tugas',
            'profile_fungsi',
            'profile_visi',
            'profile_misi',
        ])->pluck('value', 'key');

        return view('landing-menu.informasi-publik.profil-bandara.index', compact('settings'));
    }
    public function strukturOrganisasi(){return view('landing-menu.informasi-publik.struktur-organisasi.index');}
    public function pejabatBandara(){return view('landing-menu.informasi-publik.pejabat.index');}
    public function profilPPID(){return view('landing-menu.informasi-publik.profile-ppid.index');}
    public function sopPpid(){return view('landing-menu.informasi-publik.sop-ppid.index');}
    
    public function pengajuanInformasiPublik(){return view('landing-menu.informasi-publik.pengajuan.index');}
    
    

    public function getFinanceData(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $year = $request->input('year', date('Y'));
        // Anggaran dari finances (flow_type = 'budget')

        if($period === 'monthly'){
        $budget = Finance::where('flow_type', 'budget')
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'))
            ->selectRaw('MONTH(date) as month, SUM(amount) as total')
            ->pluck('total', 'month')
            ->toArray();

        // Pengeluaran dari budget_expenses, join dengan finances
        $expense = BudgetExpense::join('finances', 'budget_expenses.finance_id', '=', 'finances.id')
            ->whereYear('finances.date', $year)
            ->groupBy(DB::raw('MONTH(finances.date)'))
            ->selectRaw('MONTH(finances.date) as month, SUM(budget_expenses.amount) as total')
            ->pluck('total', 'month')
            ->toArray();

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $budgetData = array_fill(1, 12, 0);
        $expenseData = array_fill(1, 12, 0);

        foreach ($budget as $month => $total) {
            $budgetData[$month] = $total;
        }
        foreach ($expense as $month => $total) {
            $expenseData[$month] = $total;
        }

        $budgetData = array_values($budgetData);
        $expenseData = array_values($expenseData);
        } else {
        // Anggaran tahunan
        $budget = Finance::where('flow_type', 'budget')
            ->groupBy(DB::raw('YEAR(date)'))
            ->selectRaw('YEAR(date) as year, SUM(amount) as total')
            ->pluck('total', 'year')
            ->toArray();

        // Pengeluaran tahunan
        $expense = BudgetExpense::join('finances', 'budget_expenses.finance_id', '=', 'finances.id')
            ->groupBy(DB::raw('YEAR(finances.date)'))
            ->selectRaw('YEAR(finances.date) as year, SUM(budget_expenses.amount) as total')
            ->pluck('total', 'year')
            ->toArray();

        $labels = array_unique(array_merge(array_keys($budget), array_keys($expense)));
            sort($labels);
        $budgetData = [];
        $expenseData = [];
        foreach ($labels as $year) {
            $budgetData[] = $budget[$year] ?? 0;
            $expenseData[] = $expense[$year] ?? 0;
        }
        }

        return response()->json([
            'labels' => $labels,
            'budget' => $budgetData,
            'expense' => $expenseData
        ]);
    }

    /**
     * Ambil surat menurut jenisnya, HANYA yang berkas PDF-nya benar-benar ada.
     *
     * Kolom `file_path` dapat menunjuk ke berkas yang tidak pernah diunggah
     * (mis. baris dari seeder contoh) atau yang sudah terhapus dari server.
     * Tanpa penyaringan ini, tombol "Lihat Dokumen" di halaman publik
     * menghasilkan 404.
     *
     * Penyaringan dilakukan di PHP, bukan SQL, karena keberadaan berkas hanya
     * bisa diperiksa lewat disk.
     */
    protected function lettersWithFile(string $type)
    {
        return Letter::where('type', $type)
            ->orderBy('issue_date', 'desc')
            ->get()
            ->filter(fn (Letter $letter) => $letter->has_file)
            ->values();
    }

    public function suratUtusan()
    {
        $type = 'keputusan';
        $letters = $this->lettersWithFile($type);

        return view('landing-menu.regulasi.index', compact('letters', 'type'));
    }

    public function getLettersUtusan(Request $request)
    {
        return response()->json($this->lettersWithFile('keputusan'));
    }

    public function suratEdaran()
    {
        $type = 'edaran';
        $letters = $this->lettersWithFile($type);

        return view('landing-menu.regulasi.index', compact('letters', 'type'));
    }

    public function getLettersEdaran(Request $request)
    {
        return response()->json($this->lettersWithFile('edaran'));
    }

    public function lalulintas() 
    {
        // Ambil daftar tahun yang tersedia
        $years = AirFreightTraffic::selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        return view('landing-menu.beranda.lalulintas', compact('years'));
    }

    /**
     * Mengambil data lalu lintas untuk grafik
     */
    public function getTrafficData(Request $request)
    {
        $year = $request->query('year', 'all');
        $month = $request->query('month', 'all');

        // Query dasar
        $query = AirFreightTraffic::select(
            DB::raw('YEAR(date) as year'),
            DB::raw('MONTH(date) as month'),
            'type',
            DB::raw('SUM(arrival + departure) as total')
        )
        ->groupBy('year', 'month', 'type');

        // Filter tahun
        if ($year !== 'all') {
            $query->whereYear('date', $year);
        }

        // Filter bulan
        if ($month !== 'all') {
            $query->whereMonth('date', $month);
        }

        $data = $query->get();

        // Format data untuk frontend
        $formattedData = [];
        $availableYears = $data->pluck('year')->unique()->sort()->values()->toArray();

        foreach ($availableYears as $y) {
            $formattedData[$y] = [
                'aircraft' => array_fill(0, 12, 0),
                'passengers' => array_fill(0, 12, 0),
                'transit' => array_fill(0, 12, 0),
                'cargo' => array_fill(0, 12, 0),
                'baggage' => array_fill(0, 12, 0),
                'mail' => array_fill(0, 12, 0),
            ];

            $yearData = $data->where('year', $y);
            foreach ($yearData as $row) {
                $monthIndex = $row->month - 1;
                $typeKey = match ($row->type) {
                    'Pesawat' => 'aircraft',
                    'Penumpang' => 'passengers',
                    'Penumpang Transit' => 'transit',
                    'Kargo' => 'cargo',
                    'Bagasi' => 'baggage',
                    'Pos' => 'mail',
                };
                $formattedData[$y][$typeKey][$monthIndex] = $row->total;
            }
        }

        return response()->json([
            'success' => true,
            'data' => $formattedData,
            'years' => $availableYears,
        ]);
    }


    /**
     * Halaman gabungan jadwal keberangkatan dan kedatangan.
     * Data diambil di sisi klien dari /api/departures dan /api/arrivals.
     */
    public function jadwalPenerbangan()
    {
        return view('landing-menu.beranda.jadwal-penerbangan');
    }

    
    public function laporanKeuangan(){

        $years = Finance::selectRaw('YEAR(date) as year')->distinct()->pluck('year')->toArray();
        sort($years);
        return view('landing-menu.informasi.keuangan.index', compact('years'));

    }

    public function getFinancialData(Request $request)
    {
        try {
               $year = $request->input('year', 'all');

            // Hapus logika filter bulan
            $query = Finance::with('budgetExpenses');

            if ($year !== 'all') {
                $query->whereYear('date', $year);
            }

            $finances = $query->get();

            $incomeData = [];
            $budgetData = [];
            $expenseData = [];
            $labels = [];
            $sourceTotals = [];

               if ($year === 'all') {
                $years = $finances->pluck('date')->map(fn($date) => $date->year)->unique()->sort()->values();
                $labels = $years->toArray();
                
                foreach ($years as $y) {
                    $yearFinances = $finances->where('date.year', $y);
                    // --- Ubah pembagi menjadi 1 Miliar ---
                    $incomeData[] = $yearFinances->where('flow_type', 'in')->sum('amount') / 1000000000;
                    $budgetData[] = $yearFinances->where('flow_type', 'budget')->sum('amount') / 1000000000;
                    $expenseData[] = $yearFinances->where('flow_type', 'budget')->sum(fn($f) => $f->budgetExpenses->sum('amount')) / 1000000000;
                }
            } else {
                $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                $financesGrouped = $finances->groupBy(fn($item) => $item->date->month);

                foreach (range(1, 12) as $monthIndex) {
                    $group = $financesGrouped->get($monthIndex, collect());
                    // --- Ubah pembagi menjadi 1 Miliar ---
                    $incomeData[] = $group->where('flow_type', 'in')->sum('amount') / 1000000000;
                    $budgetData[] = $group->where('flow_type', 'budget')->sum('amount') / 1000000000;
                    $expenseData[] = $group->where('flow_type', 'budget')->sum(fn($f) => $f->budgetExpenses->sum('amount')) / 1000000000;
                }
            }

                // ========================================================== //
                // ===          LOGIKA BARU UNTUK SUMBER DANA             === //
                // ========================================================== //
                $sourceDataQuery = $finances->whereNotNull('source')->where('source', '!=', '');
                
                $sourceTotals = $sourceDataQuery
                    ->groupBy('source')
                    ->map(function ($group) {
                        return $group->sum('amount');
                    });

                $sourceLabels = $sourceTotals->keys()->all();
                $sourceValues = $sourceTotals->values()->all();
            

               return response()->json([
                   'labels' => $labels,
                   'income' => $incomeData,
                   'budget' => $budgetData,
                   'expense' => $expenseData,
                   // Tambahkan data baru ke response JSON
                'sourceData' => [
                    'labels' => $sourceLabels,
                    'values' => $sourceValues,
                ]
               ]);
           } catch (\Exception $e) {
               \Log::error('Error in getFinancialData: ' . $e->getMessage());
               return response()->json(['error' => 'Terjadi kesalahan saat mengambil data keuangan.'], 500);
           }
    }

    
    public function storePengajuanInformasiPublik(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'ktp' => 'required|file|mimes:jpg,png,pdf|max:2048',
            'surat_pertanggungjawaban' => 'required|file|mimes:jpg,png,pdf|max:2048',
            'surat_permintaan' => 'required|string',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'npwp' => 'required|string|max:100',
            'no_hp' => 'required|string|regex:/^\+?\d{10,13}$/|max:20',
            'email' => 'required|email|max:255',
            'rincian_informasi' => 'required|string',
            'tujuan_informasi' => 'required|string',
            'cara_memperoleh' => 'required|string',
            'cara_salinan' => 'required|string',
        ], [
            'ktp.required' => 'Scan KTP wajib diunggah.',
            'ktp.file' => 'Scan KTP harus berupa file.',
            'ktp.mimes' => 'Scan KTP harus berupa file dengan format: JPG, PNG, atau PDF.',
            'ktp.max' => 'Ukuran file KTP tidak boleh melebihi 2MB.',
            'surat_pertanggungjawaban.required' => 'Surat pernyataan pertanggung jawaban wajib diunggah.',
            'surat_pertanggungjawaban.file' => 'Surat pernyataan harus berupa file.',
            'surat_pertanggungjawaban.mimes' => 'Surat pernyataan harus berupa file dengan format: JPG, PNG, atau PDF.',
            'surat_pertanggungjawaban.max' => 'Ukuran file surat pernyataan tidak boleh melebihi 2MB.',
            'surat_permintaan.required' => 'Surat Permintaan wajib diisi.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'pekerjaan.required' => 'Pekerjaan wajib diisi.',
            'npwp.required' => 'Nomor NPWP wajib diisi.',
            'no_hp.required' => 'Nomor HP/WA wajib diisi.',
            'no_hp.regex' => 'Nomor HP/WA tidak valid.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email tidak valid.',
            'rincian_informasi.required' => 'Rincian informasi wajib diisi.',
            'tujuan_informasi.required' => 'Tujuan penggunaan informasi wajib diisi.',
            'cara_memperoleh.required' => 'Cara memperoleh informasi wajib dipilih.',
            'cara_salinan.required' => 'Cara mendapat salinan informasi wajib dipilih.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ], 422);
        }

        try {
            // Pastikan file ada
            if (!$request->hasFile('ktp') || !$request->hasFile('surat_pertanggungjawaban')) {
                return response()->json([
                    'success' => false,
                    'errors' => ['File KTP atau surat pernyataan tidak ditemukan.']
                ], 422);
            }

            // Simpan file KTP dengan nama kustom
            $ktpFile = $request->file('ktp');
            $ktpFileName = time() . '_' . $ktpFile->getClientOriginalName();
            $ktpPath = $ktpFile->storeAs('documents/pengajuan-informasi/ktp', $ktpFileName, 'public');

            // Simpan file surat pernyataan dengan nama kustom
            $suratFile = $request->file('surat_pertanggungjawaban');
            $suratFileName = time() . '_' . $suratFile->getClientOriginalName();
            $suratPertanggungjawabanPath = $suratFile->storeAs('documents/pengajuan-informasi/surat-pertanggung-jawaban', $suratFileName, 'public');
            $publicInformation = PublicInformation::create([
                'ktp' => $ktpPath,
                'surat_pertanggungjawaban' => $suratPertanggungjawabanPath,
                'surat_permintaan' => $request->surat_permintaan,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'pekerjaan' => $request->pekerjaan,
                'npwp' => $request->npwp,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'rincian_informasi' => $request->rincian_informasi,
                'tujuan_informasi' => $request->tujuan_informasi,
                'cara_memperoleh' => $request->cara_memperoleh,
                'cara_salinan' => $request->cara_salinan,
                'status' => 'Belum dibalas',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan informasi publik berhasil dikirim.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => ['Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]
            ], 500);
        }
        
        
    }

    public function kontak(){
        return view('navigation.kontak.index');
    }

    public function submitContact(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone_number' => 'required|string|max:20',
                'subject' => 'required|string|in:Informasi,Keluhan,Saran,Apresiasi',
                'message' => 'required|string',
                'g-recaptcha-response' => 'required',
            ], [
                'name.required' => 'Nama wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Email tidak valid.',
                'phone_number.required' => 'Nomor Telepon wajib diisi.',
                'phone_number.max' => 'Nomor telepon maksimal 15 karakter.',
                'subject.required' => 'Kategori wajib dipilih.',
                'message.required' => 'Pesan wajib diisi.',
                'g-recaptcha-response.required' => 'Harap verifikasi bahwa Anda bukan robot.',
            ]);
    
    
            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()->all()
                    ], 422);
                }
            return redirect()->back()->withErrors($validator)->withInput();
            }

            // Cek apakah data identik sudah disimpan dalam 5 detik terakhir
            $recentComplaint = Complaint::where('email', $request->email)
                ->where('message', $request->message)
                ->where('created_at', '>=', now()->subSeconds(5))
                ->first();
            
           if ($recentComplaint) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Pesan Anda telah terkirim. Terima kasih!'
                    ]);
                }
            return redirect()->back()->with('sent-message', 'Pesan Anda telah terkirim. Terima kasih!');
            }
    
            // Simpan pengaduan
            Complaint::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'subject' => $request->subject,
                'message' => $request->message,
                'status' => 'Menunggu',
            ]);
    
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesan Anda telah terkirim. Terima kasih!'
                ]);
            }
    
            return redirect()->back()->with('sent-message', 'Pesan Anda telah terkirim. Terima kasih!');
        }catch (\Exception $e) {
            Log::error('Form submission error: ' . $e->getMessage(), ['exception' => $e]);
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['Terjadi kesalahan di server. Silakan coba lagi nanti.']
                ], 500);
            }
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan di server. Silakan coba lagi nanti.'])->withInput();
        }
    }

    // app/Http/Controllers/LandingPageController.php

    public function fasilitas()
    {
   
        $facilities = Facility::whereIn('category', ['udara','darat', 'umum'])
                                ->get()
                                ->groupBy('category');

        return view('landing-menu.informasi-publik.fasilitas.index', compact('facilities'));
    }

    /**
     * Bertindak sebagai perantara untuk mengambil gambar dari API HTTP
     * dan menyajikannya melalui koneksi HTTPS yang aman.
     */
    public function imageProxy($filename)
    {
        /*
         * Logo maskapai berada di host API yang hanya melayani HTTP, sehingga
         * tidak bisa dimuat langsung dari situs HTTPS (diblokir sebagai mixed
         * content). Berkas diambil di sisi server lalu disajikan ulang.
         *
         * Nilai `maskapai.logo` dari API berbentuk "/storage/airlines/xxx.png".
         * Hanya nama berkasnya yang dipakai — basename() sekaligus menutup
         * upaya path traversal, dan direktori tujuannya dikunci.
         */
        $file = basename($filename);

        // Hanya nama berkas gambar yang wajar yang diteruskan (cegah SSRF)
        if (! preg_match('/^[A-Za-z0-9._-]+\.(png|jpg|jpeg|webp|svg)$/i', $file)) {
            abort(404);
        }

        $cacheKey = 'airline_logo:' . $file;

        // Logo jarang berubah; disimpan sehari agar tidak menembak API pada
        // setiap kunjungan halaman.
        $image = Cache::remember($cacheKey, now()->addDay(), function () use ($file) {
            $url = 'http://103.210.122.2/storage/airlines/' . $file;

            try {
                $response = Http::timeout(10)->get($url);

                if ($response->failed()) {
                    Log::warning('Proxy logo maskapai gagal: ' . $url);
                    return null;
                }

                return [
                    'body' => $response->body(),
                    'type' => $response->header('Content-Type') ?: 'image/png',
                ];
            } catch (\Exception $e) {
                Log::warning('Proxy logo maskapai error: ' . $e->getMessage());
                return null;
            }
        });

        if (! $image) {
            // Jangan simpan kegagalan selama sehari — beri kesempatan coba lagi
            Cache::forget($cacheKey);
            abort(404);
        }

        return response($image['body'])
            ->header('Content-Type', $image['type'])
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * Menyiapkan dan menampilkan halaman Informasi Berkala.
     */
    public function informasiBerkala()
    {
        // Ambil semua dokumen, urutkan berdasarkan kategori lalu tanggal terbit terbaru
        $documents = PeriodicDocument::orderBy('category')->orderBy('published_date', 'desc')->get();

        // Kelompokkan dokumen berdasarkan kategori
        $documentCategories = $documents->groupBy('category');

        return view('landing-menu.informasi-publik.informasi-berkala.index', compact('documentCategories'));
    }

    public function informasiSertaMerta()
    {
        $informations = ImmediateInformation::latest()->get();
        return view('landing-menu.informasi-publik.informasi-serta-merta.index', compact('informations'));
    }

    public function informasiSetiapSaat()
    {
        $informationGroups = EvergreenInformation::orderBy('published_date', 'desc')
        ->get()
        ->groupBy('category'); // Kunci utamanya di sini
        
        return view('landing-menu.informasi-publik.informasi-setiap-saat.index', compact('informationGroups'));
    }

    public function laporanLayananInformasi()
    {
        $reports = InformationServiceReport::orderBy('publication_year', 'desc')->get();
        return view('landing-menu.informasi-publik.laporan-layanan-informasi.index', compact('reports'));
    }
    public function regulasiPpid()
    {
        $regulationCategories = PpidRegulation::orderBy('category')
                                ->orderBy('published_date', 'desc')
                                ->get()
                                ->groupBy('category');
        
        return view('landing-menu.informasi-publik.regulasi-ppid.index', compact('regulationCategories'));
    }

    public function standarPelayanan()
    {
        $documentGroups = ServiceStandard::where('is_active', true)
                                ->orderBy('type')
                                ->orderBy('published_date', 'desc')
                                ->get()
                                ->groupBy('type');

        return view('landing-menu.informasi-publik.standar-pelayanan.index', compact('documentGroups'));
    }

    public function tautanTerkait()
    {
        // Data diambil langsung di blade lewat helper externalLinks() yang ter-cache.
        return view('landing-menu.tautan-terkait.index');
    }

    public function faq()
    {
        $faqs = Faq::active()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get();

        $categories = $faqs->pluck('category')->unique()->values();

        return view('landing-menu.faq.index', compact('faqs', 'categories'));
    }


    /**
     * API Endpoint untuk halaman LLAU (grafik detail).
     * Mengambil data dari tabel baru dan mentransformasikannya
     * ke format lama yang diharapkan oleh lalu-lintas.js
     */
    public function getAirFreightTraffic(Request $request)
    {
        $year = $request->input('year', 'all');
        $month = $request->input('month', 'all');

        $query = AirTrafficLog::query();

        // Ambil semua tahun unik untuk filter di frontend
        $availableYears = AirTrafficLog::selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Filter berdasarkan tahun
        if ($year !== 'all') {
            $query->whereYear('date', $year);
        }

        // Filter berdasarkan bulan (jika tahun juga dipilih)
        if ($year !== 'all' && $month !== 'all') {
            $query->whereMonth('date', $month);
        }

        $trafficData = $query->get();

        // --- TITIK DEBUG 1 ---
        // Hentikan eksekusi dan tampilkan data mentah dari database
        // dd($trafficData[0]); 

        // Siapkan data dalam format yang diharapkan JavaScript
        $data = [];
        
        if ($year === 'all') {
            // Jika semua tahun, kelompokkan per tahun
            $grouped = $trafficData->groupBy(fn($item) => $item->date->year);
            foreach ($availableYears as $y) {
                $yearData = $grouped->get($y, collect());
                $data[$y] = $this->formatDataForJs($yearData,false);
            }
        } else {
            // Jika satu tahun, kelompokkan per bulan
            $grouped = $trafficData->groupBy(fn($item) => $item->date->month);
            $data[$year] = $this->formatDataForJs($grouped, true); // true = format bulanan
        }



        return response()->json([
            'success' => true,
            'data' => $data,
            'years' => $availableYears
        ]);
    }

    /**
     * Helper function untuk mentransformasi data ke format JS
     */
    private function formatDataForJs($data, $isMonthly = false)
    {
        // Kunci (key) adalah 'aircraft', 'passengers', dll. (sesuai ekspektasi JS)
        // Nilai (value) adalah 'aircraft', 'passenger', dll. (basis nama kolom di DB)
        $categories = [
            'aircraft' => 'aircraft',
            'passengers' => 'passenger',
            'baggage' => 'baggage',
            'cargo' => 'cargo',
        ];

        $result = [];
        
        if ($isMonthly) {
            // Inisialisasi array 12 bulan untuk setiap kategori
            foreach ($categories as $jsKey => $dbKey) {
                $result[$jsKey] = array_fill(0, 12, 0);
            }

            // Isi data bulanan
            foreach ($data as $month => $logs) {
                $monthIndex = $month - 1; // Konversi bulan (1-12) ke index (0-11)
                
                // === PERBAIKAN DI SINI ===
                // Gunakan $jsKey ('aircraft', 'passengers') sebagai kunci array $result
                $result['aircraft'][$monthIndex] = $logs->sum(fn($log) => $log->aircraft_arrival + $log->aircraft_departure);
                $result['passengers'][$monthIndex] = $logs->sum(fn($log) => $log->passenger_arrival + $log->passenger_departure);
                $result['baggage'][$monthIndex] = $logs->sum(fn($log) => $log->baggage_arrival + $log->baggage_departure);
                $result['cargo'][$monthIndex] = $logs->sum(fn($log) => $log->cargo_arrival + $log->cargo_departure);
            }
        } else {
            // Format tahunan (hanya total)
            foreach ($categories as $jsKey => $dbKey) {
                // Gunakan $jsKey ('aircraft') sebagai kunci dan $dbKey ('aircraft') untuk nama kolom
                $result[$jsKey] = $data->sum(fn($log) => $log->{$dbKey.'_arrival'} + $log->{$dbKey.'_departure'});
            }
        }
        
        // Tambahkan data kosong untuk kategori yang hilang (Transit & Pos)
        $result['transit'] = $isMonthly ? array_fill(0, 12, 0) : 0;
        $result['mail'] = $isMonthly ? array_fill(0, 12, 0) : 0;

        return $result;
    }



    /**
     * API Endpoint untuk statistik di Halaman Beranda.
     */
    public function getMonthlyTrafficStats()
    {
        $now = Carbon::now();
        $currentMonthStats = AirTrafficLog::whereYear('date', $now->year)
            ->whereMonth('date', $now->month)
            ->select(
                DB::raw('SUM(aircraft_arrival + aircraft_departure) as aircraft'),
                DB::raw('SUM(passenger_arrival + passenger_departure) as passengers'),
                DB::raw('SUM(baggage_arrival + baggage_departure) as baggage'),
                DB::raw('SUM(cargo_arrival + cargo_departure) as cargo')
                // Tambahkan transit dan pos jika ada
                // DB::raw('SUM(transit_arrival + transit_departure) as transit'),
                // DB::raw('SUM(mail_arrival + mail_departure) as mail')
            )
            ->first();
            

        $stats = [
            'aircraft' => $currentMonthStats->aircraft ?? 0,
            'passengers' => $currentMonthStats->passengers ?? 0,
            'baggage' => $currentMonthStats->baggage ?? 0,
            'cargo' => $currentMonthStats->cargo ?? 0,
            // Data statis untuk yang hilang
            'transit' => 0, 
            'mail' => 0,
        ];

        // Hitung total (hanya dari 4 kategori utama)
        $stats['total'] = $stats['aircraft'] + $stats['passengers'] + $stats['baggage'] + $stats['cargo'];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
    
}
