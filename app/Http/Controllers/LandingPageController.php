<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\News;
use App\Models\Letter;
use App\Models\Slider;
use App\Models\Finance;
use App\Models\Visitor;
use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Models\BudgetExpense;
use App\Models\AirFreightTraffic;
use App\Models\PublicInformation;
use Illuminate\Support\Facades\DB;
use App\Services\AirportApiService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
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
        $ip = $request->ip(); // IP Address pengunjung
        $userAgent = $request->header('User-Agent'); // Informasi browser/device
        $sliders = Slider::where('is_visible_home', 1)
                        ->take(3)
                        ->get();

        Visitor::create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        // Panggil API
        $flightStats = $this->airportApi->getFlightStats();
        $totalAngkutanUdara = AirFreightTraffic::sum(DB::raw('arrival + departure'));
        $weather = $this->airportApi->getCurrentWeather();
        $headlines = News::where('is_published', true)
                        ->where('is_headline', true)
                        ->orderBy('created_at', 'desc')
                        ->take(3)
                        ->get();
        
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
            'weather',
            'meta'
        ));
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

    // public function berita()
    // {
    //     $headlines = News::where('is_published', true)
    //                     ->where('is_headline', true)
    //                     ->latest()
    //                     ->first();

    //     if ($headlines) {
    //         $headline       = News::where('is_published', true)
    //                             ->where('is_headline', true)
    //                             ->latest()
    //                             ->first();
    //         $subHeadlines   = News::where('is_published', true)
    //                             ->where('is_headline', true)
    //                             ->latest()
    //                             ->skip(1)
    //                             ->take(3)
    //                             ->get();
    //     } else {
    //         $headline       = News::where('is_published', true)
    //                             ->inRandomOrder()
    //                             ->latest()
    //                             ->first();
    //         $subHeadlines   = News::where('is_published', true)
    //                             ->inRandomOrder()
    //                             ->take(3)
    //                             ->get();
    //     }

    //     $latestArticles = News::where('is_published', true)->orderBy('created_at', 'desc')->take(6)->get();
    //     $otherArticles = News::where('is_published', true)
    //                     ->orderBy('created_at', 'desc')
    //                     ->skip(6)
    //                     ->take(30)
    //                     ->get();

    //     return view('navigation.informasi.berita.index', compact('headline','subHeadlines','latestArticles', 'otherArticles'));
    // }

    public function showNews($slug)
    {
        $news = News::where('slug', $slug)->where('is_published', true)->firstOrFail();
        return view('landing-menu.informasi.berita.detail', compact('news'));
        // $news = News::where('slug', $slug)->where('is_published', true)->firstOrFail();
        // $latestArticles = News::where('is_published', true)->orderBy('created_at', 'desc')->take(6)->get();
        // return view('navigation.informasi.berita.show', compact('news', 'latestArticles'));
    
    }


    public function tenant(){return view('landing-menu.layanan.index');}
    public function sewa(){return view('landing-menu.layanan.index');}
    public function perijinanUsaha(){return view('landing-menu.layanan.index');}
    public function pengiklanan(){return view('landing-menu.layanan.index');}
    public function fieldTrip(){return view('landing-menu.layanan.index');}
    public function lelang(){return view('landing-menu.layanan.index');}
    public function slot(){return view('landing-menu.layanan.index');}

    
    public function profilBandara(){return view('landing-menu.informasi-publik.profil-bandara.index');}
    public function strukturOrganisasi(){return view('landing-menu.informasi-publik.struktur-organisasi.index');}
    public function pejabatBandara(){return view('landing-menu.informasi-publik.pejabat.index');}
    public function profilPPID(){return view('landing-menu.informasi-publik.profile-ppid.index');}
    public function sopPpid(){return view('landing-menu.informasi-publik.sop-ppid.index');}
    
    // public function tenant(){return view('navigation.informasi.ajuan.index');}
    // public function sewa(){return view('navigation.informasi.ajuan.index');}
    // public function perijinanUsaha(){return view('navigation.informasi.ajuan.index');}
    // public function pengiklanan(){return view('navigation.informasi.ajuan.index');}
    // public function fieldTrip(){return view('navigation.informasi.ajuan.index');}
    // public function lelang(){return view('navigation.informasi.ajuan.index');}

    // public function profilBandara(){return view('navigation.informasi-publik.profil-bandara.index');}
    // public function strukturOrganisasi(){return view('navigation.informasi-publik.struktur-organisasi.index');}
    // public function pejabatBandara(){return view('navigation.informasi-publik.pejabat-bandara.index');}
    // public function profilPPID(){return view('navigation.informasi-publik.profil-ppid-blu.index');}
    // public function sopPpid(){return view('navigation.informasi-publik.sop-ppid.index');}
    public function pengajuanInformasiPublik(){return view('landing-menu.informasi-publik.pengajuan.index');}
    
    // aktivitas bandara
    // public function keberangkatan(){
    //     $keberangkatan = $this->airportApi->getKeberangkatan();
        
    //     return view('navigation.aktivitas-bandara.keberangkatan.index', compact('keberangkatan'));
    // }
    // public function kedatangan(){
    //     $kedatangan = $this->airportApi->getKedatangan();
        
    //     return view('navigation.aktivitas-bandara.kedatangan.index', compact('kedatangan'));
    // }

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

    // public function laluLintas(Request $request)
    // {
    //     $filterType = $request->get('filter_type', 'year');
    //     $year = $request->get('year', date('Y'));
    //     $month = $request->get('month');

    //     // Ambil semua data sekali saja
    //     $data = AirFreightTraffic::all();

    //     // Label & judul grafik
    //     if ($filterType === 'year') {
    //         $filtered = $data->filter(fn($item) => Carbon::parse($item->date)->year == $year);
    //         $labels = range(1, 12);
    //         $labelNames = collect($labels)->map(fn($m) => Carbon::create()->month($m)->translatedFormat('F'))->toArray();
    //         $title = "Tahun $year";
    //     } else {
    //         $filtered = $data->filter(function ($item) use ($year, $month) {
    //             $date = Carbon::parse($item->date);
    //             return $date->year == $year && $date->month == $month;
    //         });

    //         $labels = $filtered->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d'))->unique()->values()->toArray();
    //         $labelNames = $labels;
    //         $title = "Bulan " . Carbon::create()->month($month)->translatedFormat('F') . " $year";
    //     }

    //     // Jenis angkutan dan warna
    //     $types = ['Pesawat', 'Penumpang', 'Penumpang Transit', 'Kargo', 'Bagasi', 'Pos'];
    //     $colors = ['#A052AA', '#339AF0', '#666', '#FF4D6D', '#FFD43B', '#69DB7C'];

    //     $datasets = [];

    //     foreach ($types as $i => $type) {
    //         $dataPoints = [];

    //         foreach ($labels as $label) {
    //             $sum = $filtered->filter(function ($item) use ($filterType, $label, $type, $month) {
    //                 $date = Carbon::parse($item->date);
    //                 return $item->type === $type &&
    //                     ($filterType === 'year'
    //                         ? $date->month == $label
    //                         : $date->day == $label && $date->month == $month);
    //             })->sum(fn($item) => $item->arrival + $item->departure);

    //             $dataPoints[] = $sum;
    //         }

    //         $datasets[] = [
    //             'label' => $type,
    //             'data' => $dataPoints,
    //             'borderColor' => $colors[$i],
    //             'backgroundColor' => 'transparent',
    //             'tension' => 0.3,
    //             'fill' => false,
    //             'pointBorderColor' => $colors[$i],
    //             'pointBackgroundColor' => '#fff',
    //             'pointRadius' => 5
    //         ];
    //     }

    //     return view('navigation.aktivitas-bandara.lalu-lintas.index', [
    //         'chartData' => [
    //             'labels' => $labelNames,
    //             'datasets' => $datasets,
    //             'title' => $title,
    //         ]
    //     ]);
    // }

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

    // public function laporanKeuangan(Request $request)
    // {
    //     // Ambil semua tahun unik dari tabel finances
    //     $years = Finance::selectRaw('YEAR(date) as year')
    //     ->distinct()
    //     ->orderBy('year', 'desc')
    //     ->pluck('year')
    //     ->toArray();
        
    //     $filterTahun = $request->get('tahun', date('Y'));
    //     $filterTahunPie = $request->get('tahun_pie', date('Y'));
    //     $jenis_filter = $request->get('jenis_filter', 'bulan');
     

    //     // 1. DATA GRAFIK BAR (PEMASUKAN)
    //     $query = Finance::where('flow_type', 'in');
    //     if ($jenis_filter == 'bulan') {
    //         $query->whereYear('date', $filterTahun);
    //         $labels = [
    //             'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    //             'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    //         ];
    //         $dataPemasukan = array_fill(0, 12, 0);
    //         foreach ($query->get() as $finance) {
    //             $bulan = (int) date('n', strtotime($finance->date)) - 1;
    //             $dataPemasukan[$bulan] += $finance->amount;
    //         }
    //     } else { // jenis_filter == tahun
    //         $labels = [];
    //         $dataPemasukan = [];
    
    //         $tahunRange = Finance::where('flow_type', 'in')
    //             ->selectRaw('YEAR(date) as year')
    //             ->distinct()
    //             ->orderBy('year')
    //             ->pluck('year')
    //             ->toArray();
    
    //         foreach ($tahunRange as $year) {
    //             $labels[] = $year;
    //             $total = Finance::whereYear('date', $year)
    //                 ->where('flow_type', 'in')
    //                 ->sum('amount');
    //             $dataPemasukan[] = $total;
    //         }
    //     }
    
    //     // 2. DATA GRAFIK Line (ANGGARAN VS PENGELUARAN)
    
    //     // Ambil total Anggaran (dari tabel finances flow_type = 'budget')
    //     $anggaran = Finance::where('flow_type', 'budget')
    //         ->whereYear('date', $filterTahunPie)
    //         ->sum('amount');
    
    //     // Ambil total Pengeluaran (dari tabel budget_expenses join finance)
    //     $totalPengeluaran = BudgetExpense::whereHas('finance', function($query) use ($filterTahunPie) {
    //         $query->whereYear('date', $filterTahunPie);
    //     })->sum('amount');
    
    //     $showPieChart = $anggaran > 0; // Hanya tampilkan grafik Pie jika anggaran ada

    //     //3. grafil line
        
     
    //     return view('navigation.informasi.laporan-keuangan.index', compact(
    //         'years', 'filterTahun', 'filterTahunPie',
    //         'jenis_filter', 'labels', 'dataPemasukan',
    //         'anggaran', 'totalPengeluaran', 'showPieChart'
    //     ));
    // }

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
                'status' => 'belum_dibalas',
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
                'status' => 'pending',
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
    
    // public function storePengaduan(Request $request)
    // {
    //     // Validasi form
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|max:255',
    //         'phone_number' => 'required|string|max:15',
    //         'subject' => 'required|string|max:255',
    //         'message' => 'required|string',
    //         'g-recaptcha-response' => 'required',
    //     ],[
    //         'name.required' => 'Nama lengkap wajib diisi.',
    //         'email.required' => 'Email wajib diisi.',
    //         'email.email' => 'Format email tidak valid.',
    //         'phone_number.required' => 'Nomor telepon wajib diisi.',
    //         'phone_number.max' => 'Nomor telepon maksimal 15 karakter.',
    //         'subject.required' => 'Topik wajib diisi.',
    //         'message.required' => 'Pesan wajib diisi.',
    //         'g-recaptcha-response.required' => 'Silakan centang reCAPTCHA.',
    //     ]);

        

    //     // Simpan pengaduan
    //     Complaint::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'phone_number' => $request->phone_number,
    //         'subject' => $request->subject,
    //         'message' => $request->message,
    //         'status' => 'pending',
    //     ]);

    //     return redirect()->route('kontak')->with('success', 'Pengaduan Anda telah dikirim. Kami akan segera menindaklanjuti.');
    // }
}
