<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Slider;
use App\Models\News;
use App\Models\Finance;
use App\Models\Complaint;
use App\Models\BudgetExpense;
use App\Models\Visitor;
use App\Models\PublicInformation;
use App\Models\AirFreightTraffic;
use Carbon\Carbon;
use App\Services\AirportApiService;
use Illuminate\Support\Facades\Http;

class LandingPageController extends Controller
{
    protected $airportApi;

    public function __construct(AirportApiService $airportApi)
    {
        $this->airportApi = $airportApi;
    }
    
    public function home(Request $request, AirportApiService $airportApi)
    {
        $ip = $request->ip(); // IP Address pengunjung
        $userAgent = $request->header('User-Agent'); // Informasi browser/device
        $sliders = Slider::where('is_visible_home', 1)->get();

        Visitor::create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);        
        // Panggil API
        $keberangkatan = $airportApi->getKeberangkatan();
        $kedatangan = $airportApi->getKedatangan();
        $totalAngkutanUdara = AirFreightTraffic::sum(DB::raw('arrival + departure'));
        $weatherData = $airportApi->getCuaca();

        $jumlahKeberangkatan = isset($keberangkatan['data']['result']['data']) ? count($keberangkatan['data']['result']['data']) : 0;
        $jumlahKedatangan = isset($kedatangan['data']['result']['data']) ? count($kedatangan['data']['result']['data']) : 0;

        return view('home', 
        compact(
            'sliders', 
            'jumlahKeberangkatan', 
            'jumlahKedatangan',
            'totalAngkutanUdara',
            'weatherData',
        ));
    }

    public function berita()
    {
        $headlines = News::where('is_published', true)
                        ->where('is_headline', true)
                        ->latest()
                        ->first();

        if ($headlines) {
            $headline       = News::where('is_published', true)
                                ->where('is_headline', true)
                                ->latest()
                                ->first();
            $subHeadlines   = News::where('is_published', true)
                                ->where('is_headline', true)
                                ->latest()
                                ->skip(1)
                                ->take(3)
                                ->get();
        } else {
            $headline       = News::where('is_published', true)
                                ->inRandomOrder()
                                ->latest()
                                ->first();
            $subHeadlines   = News::where('is_published', true)
                                ->inRandomOrder()
                                ->take(3)
                                ->get();
        }

        $latestArticles = News::where('is_published', true)->orderBy('created_at', 'desc')->take(6)->get();
        $otherArticles = News::where('is_published', true)
                        ->orderBy('created_at', 'desc')
                        ->skip(6)
                        ->take(30)
                        ->get();

        return view('navigation.informasi.berita.index', compact('headline','subHeadlines','latestArticles', 'otherArticles'));
    }

    public function showNews($slug)
    {
        $news = News::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $latestArticles = News::where('is_published', true)->orderBy('created_at', 'desc')->take(6)->get();
        return view('navigation.informasi.berita.show', compact('news', 'latestArticles'));
    
    }
    
    public function tenant(){return view('navigation.informasi.ajuan.index');}
    public function sewa(){return view('navigation.informasi.ajuan.index');}
    public function perijinanUsaha(){return view('navigation.informasi.ajuan.index');}
    public function pengiklanan(){return view('navigation.informasi.ajuan.index');}
    public function fieldTrip(){return view('navigation.informasi.ajuan.index');}
    public function lelang(){return view('navigation.informasi.ajuan.index');}

    public function profilBandara(){return view('navigation.informasi-publik.profil-bandara.index');}
    public function strukturOrganisasi(){return view('navigation.informasi-publik.struktur-organisasi.index');}
    public function pejabatBandara(){return view('navigation.informasi-publik.pejabat-bandara.index');}
    public function profilPPID(){return view('navigation.informasi-publik.profil-ppid-blu.index');}
    public function sopPpid(){return view('navigation.informasi-publik.sop-ppid.index');}
    public function pengajuanInformasiPublik(){return view('navigation.informasi-publik.pengajuan-informasi-publik.index');}
    
    // aktivitas bandara
    public function keberangkatan(){
        $keberangkatan = $this->airportApi->getKeberangkatan();
        
        return view('navigation.aktivitas-bandara.keberangkatan.index', compact('keberangkatan'));
    }
    public function kedatangan(){
        $kedatangan = $this->airportApi->getKedatangan();
        
        return view('navigation.aktivitas-bandara.kedatangan.index', compact('kedatangan'));
    }

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

    public function laluLintas(Request $request)
    {
        $filterType = $request->get('filter_type', 'year');
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');

        // Ambil semua data sekali saja
        $data = AirFreightTraffic::all();

        // Label & judul grafik
        if ($filterType === 'year') {
            $filtered = $data->filter(fn($item) => Carbon::parse($item->date)->year == $year);
            $labels = range(1, 12);
            $labelNames = collect($labels)->map(fn($m) => Carbon::create()->month($m)->translatedFormat('F'))->toArray();
            $title = "Tahun $year";
        } else {
            $filtered = $data->filter(function ($item) use ($year, $month) {
                $date = Carbon::parse($item->date);
                return $date->year == $year && $date->month == $month;
            });

            $labels = $filtered->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d'))->unique()->values()->toArray();
            $labelNames = $labels;
            $title = "Bulan " . Carbon::create()->month($month)->translatedFormat('F') . " $year";
        }

        // Jenis angkutan dan warna
        $types = ['Pesawat', 'Penumpang', 'Penumpang Transit', 'Kargo', 'Bagasi', 'Pos'];
        $colors = ['#A052AA', '#339AF0', '#666', '#FF4D6D', '#FFD43B', '#69DB7C'];

        $datasets = [];

        foreach ($types as $i => $type) {
            $dataPoints = [];

            foreach ($labels as $label) {
                $sum = $filtered->filter(function ($item) use ($filterType, $label, $type, $month) {
                    $date = Carbon::parse($item->date);
                    return $item->type === $type &&
                        ($filterType === 'year'
                            ? $date->month == $label
                            : $date->day == $label && $date->month == $month);
                })->sum(fn($item) => $item->arrival + $item->departure);

                $dataPoints[] = $sum;
            }

            $datasets[] = [
                'label' => $type,
                'data' => $dataPoints,
                'borderColor' => $colors[$i],
                'backgroundColor' => 'transparent',
                'tension' => 0.3,
                'fill' => false,
                'pointBorderColor' => $colors[$i],
                'pointBackgroundColor' => '#fff',
                'pointRadius' => 5
            ];
        }

        return view('navigation.aktivitas-bandara.lalu-lintas.index', [
            'chartData' => [
                'labels' => $labelNames,
                'datasets' => $datasets,
                'title' => $title,
            ]
        ]);
    }

    public function laporanKeuangan(Request $request)
    {
        // Ambil semua tahun unik dari tabel finances
        $years = Finance::selectRaw('YEAR(date) as year')
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year')
        ->toArray();
        
        $filterTahun = $request->get('tahun', date('Y'));
        $filterTahunPie = $request->get('tahun_pie', date('Y'));
        $jenis_filter = $request->get('jenis_filter', 'bulan');
     

        // 1. DATA GRAFIK BAR (PEMASUKAN)
        $query = Finance::where('flow_type', 'in');
        if ($jenis_filter == 'bulan') {
            $query->whereYear('date', $filterTahun);
            $labels = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            $dataPemasukan = array_fill(0, 12, 0);
            foreach ($query->get() as $finance) {
                $bulan = (int) date('n', strtotime($finance->date)) - 1;
                $dataPemasukan[$bulan] += $finance->amount;
            }
        } else { // jenis_filter == tahun
            $labels = [];
            $dataPemasukan = [];
    
            $tahunRange = Finance::where('flow_type', 'in')
                ->selectRaw('YEAR(date) as year')
                ->distinct()
                ->orderBy('year')
                ->pluck('year')
                ->toArray();
    
            foreach ($tahunRange as $year) {
                $labels[] = $year;
                $total = Finance::whereYear('date', $year)
                    ->where('flow_type', 'in')
                    ->sum('amount');
                $dataPemasukan[] = $total;
            }
        }
    
        // 2. DATA GRAFIK Line (ANGGARAN VS PENGELUARAN)
    
        // Ambil total Anggaran (dari tabel finances flow_type = 'budget')
        $anggaran = Finance::where('flow_type', 'budget')
            ->whereYear('date', $filterTahunPie)
            ->sum('amount');
    
        // Ambil total Pengeluaran (dari tabel budget_expenses join finance)
        $totalPengeluaran = BudgetExpense::whereHas('finance', function($query) use ($filterTahunPie) {
            $query->whereYear('date', $filterTahunPie);
        })->sum('amount');
    
        $showPieChart = $anggaran > 0; // Hanya tampilkan grafik Pie jika anggaran ada

        //3. grafil line
        
     
        return view('navigation.informasi.laporan-keuangan.index', compact(
            'years', 'filterTahun', 'filterTahunPie',
            'jenis_filter', 'labels', 'dataPemasukan',
            'anggaran', 'totalPengeluaran', 'showPieChart'
        ));
    }

    public function storePengajuanInformasiPublik(Request $request)
    {
        
        $validated = $request->validate([
            
            'ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'surat_pertanggungjawaban' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'surat_permintaan' => 'required|string',
            
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'npwp' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'email' => 'required|email',

            'rincian_informasi' => 'required|string',
            'tujuan_informasi' => 'required|string',
            'cara_memperoleh' => 'required|string',
            'cara_salinan' => 'required|string',
        ], [
            'required' => ':attribute wajib diisi.',
            'email' => 'Format email tidak valid.',
            'file' => ':attribute harus berupa file.',
            'mimes' => ':attribute harus berupa file dengan format: :values.',
            'max' => ':attribute tidak boleh lebih dari :max kilobyte.',
        ]);

        
        $ktpPath = $request->file('ktp')->storeAs(
            'documents/pengajuan-informasi/ktp',
            time() . '_' . $request->file('ktp')->getClientOriginalName(),
            'public'
        );

        $suratPertanggungjawabanPath = $request->file('surat_pertanggungjawaban')->storeAs(
            'documents/pengajuan-informasi/surat-pertanggung-jawaban',
            time() . '_' . $request->file('surat_pertanggungjawaban')->getClientOriginalName(),
            'public'
        );

        
        PublicInformation::create([
            'ktp' => $ktpPath,
            'surat_pertanggungjawaban' => $suratPertanggungjawabanPath,
            'surat_permintaan' => $validated['surat_permintaan'],
            'nama' => $validated['nama'],
            'alamat' => $validated['alamat'],
            'pekerjaan' => $validated['pekerjaan'],
            'npwp' => $validated['npwp'],
            'no_hp' => $validated['no_hp'],
            'email' => $validated['email'],
            'rincian_informasi' => $validated['rincian_informasi'],
            'tujuan_informasi' => $validated['tujuan_informasi'],
            'cara_memperoleh' => $validated['cara_memperoleh'],
            'cara_salinan' => $validated['cara_salinan'],
        ]);

        return redirect()->back()->with('success', 'Pengajuan informasi berhasil dikirim.');
    }

    public function kontak(){
        return view('navigation.kontak.index');
    }
    
    public function storePengaduan(Request $request)
    {
        // Validasi form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:15',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'g-recaptcha-response' => 'required',
        ],[
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.max' => 'Nomor telepon maksimal 15 karakter.',
            'subject.required' => 'Topik wajib diisi.',
            'message.required' => 'Pesan wajib diisi.',
            'g-recaptcha-response.required' => 'Silakan centang reCAPTCHA.',
        ]);

        

        // Simpan pengaduan
        Complaint::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return redirect()->route('kontak')->with('success', 'Pengaduan Anda telah dikirim. Kami akan segera menindaklanjuti.');
    }
}
