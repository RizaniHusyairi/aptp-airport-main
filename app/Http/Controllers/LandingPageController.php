<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\News;
use App\Models\Letter;
use App\Models\Slider;
use App\Models\Finance;
use App\Models\Service;
use App\Models\Tourism;
use App\Models\Visitor;
use App\Models\Complaint;
use App\Jobs\LogVisitorJob;
use Illuminate\Http\Request;
use App\Models\BudgetExpense;
use App\Models\AirFreightTraffic;
use App\Models\PublicInformation;
use Illuminate\Support\Facades\DB;
use App\Services\AirportApiService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
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
            'data' => $departures
        ]);

    }

    /**
     * Get arrivals list for frontend
     */
    public function getArrivals()
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
            'data' => $arrivals
        ]);
    }
    
    public function home(Request $request)
    {
        // BARU: Cache query untuk destinasi wisata selama 1 jam
        $destinations = Cache::remember('home_destinations', now()->addHour(), function() {
            return Tourism::where('status', 'published')->latest()->take(3)->get();
        });

        // BARU: Cache query untuk sliders selama 1 jam
        $sliders = Cache::remember('home_sliders', now()->addHour(), function() {
            return Slider::where('is_visible_home', 1)->take(3)->get();
        });

        // BARU: Cache query untuk total angkutan udara selama 3 jam
        $totalAngkutanUdara = Cache::remember('total_air_freight_monthly', now()->addHours(3), function() {
            return AirFreightTraffic::whereYear('date', now()->year)
                                      ->whereMonth('date', now()->month)
                                      ->sum(DB::raw('arrival + departure'));
        });

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
    

        
        // SESUDAH:
        LogVisitorJob::dispatch($request->ip(), $request->header('User-Agent'));
        // =========================
        // Panggil API
        $flightStats = $this->airportApi->getFlightStats();
        $weather = $this->airportApi->getCurrentWeather();
        
        
        $meta = [
            'title' => 'APT Pranoto - Bandara Samarinda',
            'description' => 'Sistem Informasi Bandara APT Pranoto, menyediakan data lalu lintas, cuaca, dan berita.',
            'keywords' => 'bandara, APT Pranoto, Samarinda, cuaca, lalu lintas',
        ];
        return view('landing-menu.beranda.index', 
        compact(
            'sliders',
            'flightStats', 
            'totalAngkutanUdara',
            'headlines',
            'destinations',
            'weather',
            'meta'
        ));
    }

    // app/Http/Controllers/LandingPageController.php

    // METHOD BARU UNTUK MENYEDIAKAN DATA RUTE DOMESTIK
    public function getDomesticRoutesData()
    {
        $routesData = [
            [
                'kota' => 'Jakarta (CGK)',
                'provinsi' => 'Banten',
                'coords' => ['cx' => 320, 'cy' => 375], // Menggunakan format coords {cx, cy}
                'maskapai' => [
                    ['nama' => 'Batik Air', 'logo' => asset('assets_landing/img/mitra/logo-batik.png')],
                    ['nama' => 'Citilink', 'logo' => asset('assets_landing/img/mitra/logo-citilink.png')],
                ]
            ],
            [
                'kota' => 'Surabaya (SUB)',
                'provinsi' => 'Jawa Timur',
                'coords' => ['cx' => 500, 'cy' => 412 ],
                'maskapai' => [
                    ['nama' => 'Super Air Jet', 'logo' => asset('assets_landing/img/mitra/logo-SAJ.png')],
                    ['nama' => 'Citilink', 'logo' => asset('assets_landing/img/mitra/logo-citilink.png')],
                ]
            ],
             [
                'kota' => 'Yogyakarta (YIA)',
                'provinsi' => 'DI Yogyakarta',
                'coords' => ['cx' => 430, 'cy' => 423],
                'maskapai' => [
                    ['nama' => 'Super Air Jet', 'logo' => asset('assets_landing/img/mitra/logo-SAJ.png')],
                ]
            ],
             [
                'kota' => 'Berau (BEJ)',
                'provinsi' => 'Kalimantan Timur',
                'coords' => ['cx' => 630, 'cy' => 130],
                'maskapai' => [
                    ['nama' => 'Wings Air', 'logo' => asset('assets_landing/img/mitra/logo-wings.png')],
                ]
            ],
             [
                'kota' => 'Maratua (RTU)',
                'provinsi' => 'Kalimantan Timur',
                'coords' => ['cx' => 678, 'cy' => 123],
                'maskapai' => [
                    ['nama' => 'Smart Aviation', 'logo' => asset('assets_landing/img/mitra/logo-smart.jpg')],
                ]
            ],
            [
                'kota' => 'Long Apung (LPU)',
                'provinsi' => 'Kalimantan Utara',
                'coords' => ['cx' => 570, 'cy' => 148],
                'maskapai' => [
                    ['nama' => 'Smart Aviation', 'logo' => asset('assets_landing/img/mitra/logo-smart.jpg')],
                ]
            ],
            [
                'kota' => 'Datah Dawai (DTD)',
                'provinsi' => 'Kalimantan Timur',
                'coords' => ['cx' => 557, 'cy' => 165],
                'maskapai' => [
                    ['nama' => 'Smart Aviation', 'logo' => asset('assets_landing/img/mitra/logo-smart.jpg')],
                ]
            ],
             [
                'kota' => 'Muara Wahau (MWV)',
                'provinsi' => 'Kalimantan Timur',
                'coords' => ['cx' => 615, 'cy' => 150],
                'maskapai' => [
                    ['nama' => 'Smart Aviation', 'logo' => asset('assets_landing/img/mitra/logo-smart.jpg')],
                ]
            ],
        ];

        return response()->json(['success' => true, 'data' => $routesData]);
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

    public function getMonthlyTrafficStats()
    {
        try {
            // Cache hasil ini untuk mengurangi beban database
            $stats = Cache::remember('monthly_traffic_stats_full', now()->addHours(3), function () {
                $now = \Carbon\Carbon::now();
                $query = \App\Models\AirFreightTraffic::whereYear('date', $now->year)
                                                    ->whereMonth('date', $now->month);

                // Ambil semua data dalam satu query untuk efisiensi
                $monthlyData = (clone $query)
                    ->groupBy('type')
                    ->select('type', DB::raw('SUM(arrival + departure) as total'))
                    ->pluck('total', 'type');

                // Siapkan data dengan nilai default 0
                $data = [
                    'aircraft'   => (int) ($monthlyData['Pesawat'] ?? 0),
                    'passengers' => (int) ($monthlyData['Penumpang'] ?? 0),
                    'transit'    => (int) ($monthlyData['Penumpang Transit'] ?? 0),
                    'baggage'    => (int) ($monthlyData['Bagasi'] ?? 0),
                    'cargo'      => (int) ($monthlyData['Kargo'] ?? 0),
                    'mail'       => (int) ($monthlyData['Pos'] ?? 0),
                ];
                
                // Hitung total semua aktivitas
                $data['total'] = array_sum($data);

                return $data;
            });

            return response()->json([
                'success' => true,
                'data'    => $stats,
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching monthly traffic stats: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data statistik.'], 500);
        }
    }

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
        // dd($service->requairements);
        return view('landing-menu.layanan.index', compact('service'));
    }


    
    public function profilBandara(){return view('landing-menu.informasi-publik.profil-bandara.index');}
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

    public function suratUtusan()
    {

        $type = 'utusan';
        $letters = Letter::where('type', $type)->get();
        return view('landing-menu.regulasi.index', compact('letters', 'type'));
    }

    public function getLettersUtusan(Request $request)
    {

        $letters = Letter::where('type', 'utusan')->get();
        return response()->json($letters);
    }
    
    public function suratEdaran()
    {
        $type = 'edaran';

        $letters = Letter::where('type', $type)->get();
        return view('landing-menu.regulasi.index', compact('letters', 'type'));
    }

    public function getLettersEdaran(Request $request)
    {

        $letters = Letter::where('type', 'edaran')->get();
        return response()->json($letters);
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


    public function keberangkatan()
    {
        return view('landing-menu.beranda.keberangkatan');
    }
    public function kedatangan()
    {
        return view('landing-menu.beranda.kedatangan');
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
               $month = $request->input('month', 'all');

               $query = Finance::with('budgetExpenses');

               if ($year !== 'all') {
                   $query->whereYear('date', $year);
                   if ($month !== 'all') {
                       $month = (int) $month;
                       if ($month < 1 || $month > 12) {
                           throw new \Exception("Bulan tidak valid: {$month}");
                       }
                       $query->whereMonth('date', $month);
                   }
               }

               $finances = $query->get();

               $incomeData = [];
               $budgetData = [];
               $expenseData = [];
               $labels = [];

               if ($year === 'all') {
                   $years = Finance::selectRaw('YEAR(date) as year')->distinct()->pluck('year')->toArray();
                   sort($years); // Urutkan tahun dari terkecil ke terbesar
                   $labels = $years;
                   foreach ($years as $y) {
                       $yearFinances = $finances->where('date', '>=', "{$y}-01-01")->where('date', '<=', "{$y}-12-31");
                       $incomeData[] = $yearFinances->where('flow_type', 'in')->sum('amount') / 1000000;
                       $budgetData[] = $yearFinances->where('flow_type', 'budget')->sum('amount') / 1000000;
                       $expenseData[] = $yearFinances->where('flow_type', 'budget')->sum(function ($finance) {
                           return $finance->budgetExpenses->sum('amount') ?? 0;
                       }) / 1000000;
                   }
               } elseif ($month === 'all') {
                   $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                   $financesGrouped = $finances->groupBy(function ($item) {
                       return $item->date->month;
                   });
                   foreach (range(1, 12) as $monthIndex) {
                       $monthIndex -= 1;
                       $group = $financesGrouped->get($monthIndex + 1, collect());
                       $incomeData[$monthIndex] = $group->where('flow_type', 'in')->sum('amount') / 1000000;
                       $budgetData[$monthIndex] = $group->where('flow_type', 'budget')->sum('amount') / 1000000;
                       $expenseData[$monthIndex] = $group->where('flow_type', 'budget')->sum(function ($finance) {
                           return $finance->budgetExpenses->sum('amount') ?? 0;
                       }) / 1000000;
                   }
               } else {
                   $labels = [Carbon::createFromDate($year, $month, 1)->format('M')];
                   $incomeData[] = $finances->where('flow_type', 'in')->sum('amount') / 1000000;
                   $budgetData[] = $finances->where('flow_type', 'budget')->sum('amount') / 1000000;
                   $expenseData[] = $finances->where('flow_type', 'budget')->sum(function ($finance) {
                       return $finance->budgetExpenses->sum('amount') ?? 0;
                   }) / 1000000;
               }

               return response()->json([
                   'labels' => $labels,
                   'income' => $incomeData,
                   'budget' => $budgetData,
                   'expense' => $expenseData,
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
    
    
}
