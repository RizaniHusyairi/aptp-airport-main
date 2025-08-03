<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Plane;
use App\Models\Flight;
use App\Models\Slider;
use App\Models\Ticket;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Finance;
use App\Models\Visitor;
use Illuminate\Http\Request;
use App\Models\BudgetExpense;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (view()->exists('template.' . $request->path())) {
            return view('template.' . $request->path());
        }
        return abort(404);
    }
    public function home()
    {
        return view('home');
    }

    public function root(Request $request)
    {
        // Total Pengunjung
        $totalVisitors = Visitor::count();
        
        // 1. DATA GRAFIK BAR (PEMASUKAN)
        // Ambil semua tahun unik dari tabel finances
        $years = Finance::selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        $filterTahun = $request->get('tahun', date('Y'));
        $filterTahunPie = $request->get('tahun_pie', date('Y'));
        $jenis_filter = $request->get('jenis_filter', 'bulan');

        // Grafik Pemasukan
        // Untuk semua tahun
        // Grafik Pemasukan
        // Untuk semua tahun
        $allYearsPemasukan = Finance::select(
            DB::raw('YEAR(date) as year'),
            DB::raw('SUM(amount) / 1000000 as total') // Bagi 1 juta
        )
            ->where('flow_type', 'in')
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->pluck('total', 'year')
            ->toArray();

        // Untuk setiap tahun (data bulanan)
        $pemasukanByYear = [];
        foreach ($years as $year) {
            $monthlyPemasukan = Finance::select(
                DB::raw('DATE_FORMAT(date, "%b %Y") as month'),
                DB::raw('SUM(amount) / 1000000 as total') // Bagi 1 juta
            )
                ->where('flow_type', 'in')
                ->whereYear('date', $year)
                ->groupBy('month')
                ->orderBy(DB::raw('MIN(date)'))
                ->get()
                ->pluck('total', 'month')
                ->toArray();

            $months = array_map(function ($i) use ($year) {
                return Carbon::create($year, $i, 1)->format('M Y');
            }, range(1, 12));

            $data = array_fill(0, 12, 0);
            foreach ($monthlyPemasukan as $month => $total) {
                $index = array_search($month, $months);
                if ($index !== false) {
                    $data[$index] = (float)$total; // Gunakan float untuk presisi
                }
            }

            $pemasukanByYear[$year] = [
                'categories' => $months,
                'data' => $data,
            ];
        }

        $pemasukanData = [
            'all' => [
                'categories' => $years,
                'data' => array_map(function ($year) use ($allYearsPemasukan) {
                    return isset($allYearsPemasukan[$year]) ? (float)$allYearsPemasukan[$year] : 0;
                }, $years),
            ],
        ] + $pemasukanByYear;


        // 2. DATA GRAFIK PIE (ANGGARAN VS PENGELUARAN)
// Grafik Anggaran dan Belanja
        // Untuk semua tahun
        $allYearsAnggaran = Finance::select(
            DB::raw('YEAR(date) as year'),
            DB::raw('SUM(amount) / 1000000 as total') // Bagi 1 juta
        )
            ->where('flow_type', 'budget')
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->pluck('total', 'year')
            ->toArray();

        $allYearsBelanja = BudgetExpense::select(
            DB::raw('YEAR(finances.date) as year'),
            DB::raw('SUM(budget_expenses.amount) / 1000000 as total') // Bagi 1 juta
        )
            ->join('finances', 'budget_expenses.finance_id', '=', 'finances.id')
            ->where('finances.flow_type', 'budget')
            ->groupBy('year')
            ->orderBy(DB::raw('MIN(finances.date)'))
            ->get()
            ->pluck('total', 'year')
            ->toArray();

        // Untuk setiap tahun (data bulanan)
        $anggaranBelanjaByYear = [];
        foreach ($years as $year) {
            $monthlyAnggaran = Finance::select(
                DB::raw('DATE_FORMAT(date, "%b %Y") as month'),
                DB::raw('SUM(amount) / 1000000 as total') // Bagi 1 juta
            )
                ->where('flow_type', 'budget')
                ->whereYear('date', $year)
                ->groupBy('month')
                ->orderBy(DB::raw('MIN(date)'))
                ->get()
                ->pluck('total', 'month')
                ->toArray();

            $monthlyBelanja = BudgetExpense::select(
                DB::raw('DATE_FORMAT(finances.date, "%b %Y") as month'),
                DB::raw('SUM(budget_expenses.amount) / 1000000 as total') // Bagi 1 juta
            )
            ->join('finances', 'budget_expenses.finance_id', '=', 'finances.id')
            ->where('finances.flow_type', 'budget')
            ->whereYear('finances.date', $year)
            ->groupBy('month')
            ->orderBy(DB::raw('MIN(finances.date)'))
            ->get()
            ->pluck('total', 'month')
            ->toArray();

            $months = array_map(function ($i) use ($year) {
                return Carbon::create($year, $i, 1)->format('M Y');
            }, range(1, 12));

            $anggaranData = array_fill(0, 12, 0);
            $belanjaData = array_fill(0, 12, 0);

            foreach ($monthlyAnggaran as $month => $total) {
                $index = array_search($month, $months);
                if ($index !== false) {
                    $anggaranData[$index] = (float)$total;
                }
            }

            foreach ($monthlyBelanja as $month => $total) {
                $index = array_search($month, $months);
                if ($index !== false) {
                    $belanjaData[$index] = (float)$total;
                }
            }

            $anggaranBelanjaByYear[$year] = [
                'categories' => $months,
                'anggaran' => $anggaranData,
                'belanja' => $belanjaData,
            ];
        }

        $anggaranBelanjaData = [
            'all' => [
                'categories' => $years,
                'anggaran' => array_map(function ($year) use ($allYearsAnggaran) {
                    return isset($allYearsAnggaran[$year]) ? (float)$allYearsAnggaran[$year] : 0;
                }, $years),
                'belanja' => array_map(function ($year) use ($allYearsBelanja) {
                    return isset($allYearsBelanja[$year]) ? (float)$allYearsBelanja[$year] : 0;
                }, $years),
            ],
        ] + $anggaranBelanjaByYear;

        // 3. DATA KUNJUNGAN (VISITORS)
        $visitorData = Visitor::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', Carbon::today()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $visitorCategories = [];
        $visitorSeries = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('d/m/Y');
            $visitorCategories[] = $date;
            $visitorSeries[] = isset($visitorData[Carbon::today()->subDays($i)->format('Y-m-d')]) 
                ? $visitorData[Carbon::today()->subDays($i)->format('Y-m-d')] 
                : 0;
        }

        // 4. DATA SUMMARY
        $totalAirline = Airline::count();
        $totalCustomer = User::whereIsAdmin(0)->count();
        $totalPlane = Plane::count();
        $totalAirport = Airport::count();
        $totalFlight = Flight::count();
        $totalTicket = Ticket::count();

        $lastFlights = Flight::orderBy('id', 'desc')->take(10)->get();

        $activeAirlines = Airline::query()
            ->withCount('flights')
            ->withCount('planes')
            ->orderBy('flights_count', 'desc')
            ->take(6)
            ->get();

        // 5. CHART STATUS FLIGHTS
        $flightStatusChart = DB::table('flights')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderBy('status', 'desc')
            ->get()
            ->map(function ($item) {
                switch (trim($item->status)) {
                    case 0:
                        $item->label = "Land";
                        $item->color = "#ea868f";
                        break;
                    case 1:
                        $item->label = "Take Off";
                        $item->color = "#20c997";
                        break;
                }
                return (array) $item;
            })->toArray();

        // 6. COMPACT DATA KE VIEW
        $data = [
            'totalAirline'      => $totalAirline,
            'totalPlane'        => $totalPlane,
            'totalAirport'      => $totalAirport,
            'totalFlight'       => $totalFlight,
            'totalTicket'       => $totalTicket,
            'totalCustomer'     => $totalCustomer,
            'lastFlights'       => $lastFlights,
            "activeAirlines"    => $activeAirlines,
            "flightStatusChart" => $flightStatusChart,
        ];

        // return view('admin.index', compact(
        //     'data', 'dates', 'totals', 'years', 'filterTahun', 'filterTahunPie',
        //     'jenis_filter', 'labels', 'dataPemasukan',
        //     'anggaran', 'totalPengeluaran', 'showPieChart'
        // ));
        return view('admin2.dashboard.index', compact(
            'totalVisitors',
            'visitorCategories',
            'visitorSeries',
            'years',
            'pemasukanData',
            'anggaranBelanjaData'
        ));
    }


    public function storeTempFile(Request $request)
    {

        $path = storage_path('tmp/uploads');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function deleteTempFile(Request $request)
    {
        $path = storage_path('tmp/uploads');
        if (file_exists($path . '/' . $request->fileName)) {
            unlink($path . '/' . $request->fileName);
        }
    }

    /*Language Translation*/
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }

    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $user = User::find($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if ($request->file('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = public_path('/images/');
            $avatar->move($avatarPath, $avatarName);
            $user->avatar = '/images/' . $avatarName;
        }

        $user->update();
        if ($user) {
            Session::flash('message', 'User Details Updated successfully!');
            Session::flash('alert-class', 'alert-success');
            return response()->json([
                'isSuccess' => true,
                'Message' => "User Details Updated successfully!"
            ], 200); // Status code here
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return response()->json([
                'isSuccess' => true,
                'Message' => "Something went wrong!"
            ], 200); // Status code here
        }
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            return response()->json([
                'isSuccess' => false,
                'Message' => "Your Current password does not matches with the password you provided. Please try again."
            ], 200); // Status code
        } else {
            $user = User::find($id);
            $user->password = Hash::make($request->get('password'));
            $user->update();
            if ($user) {
                Session::flash('message', 'Password updated successfully!');
                Session::flash('alert-class', 'alert-success');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Password updated successfully!"
                ], 200); // Status code here
            } else {
                Session::flash('message', 'Something went wrong!');
                Session::flash('alert-class', 'alert-danger');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Something went wrong!"
                ], 200); // Status code here
            }
        }
    }
}
