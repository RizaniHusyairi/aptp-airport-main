<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Finance;
use App\Models\Meeting;
use Illuminate\Http\Request;
use App\Models\RoleWorkCategory;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Ad;        // <-- TAMBAHKAN
use App\Models\Fieldtrip; // <-- TAMBAHKAN
use App\Models\Lelang;    // <-- TAMBAHKAN
use App\Models\Rental; // Model untuk Sewa
use App\Models\License;    // <-- TAMBAHKAN
use App\Models\Tenant; // Model untuk Tenant
use App\Models\slot; // Model untuk Slot Charter
use App\Models\AirTrafficLog; // Model untuk LLAU
use App\Models\Complaint; // Model untuk Pengaduan
use App\Models\Task;       // <<< TAMBAHKAN MODEL TUGAS
use App\Models\ExtendAdvance; // Model untuk Extend Advance
use App\Models\Inventory;  // <<< TAMBAHKAN MODEL INVENTARIS
use App\Models\WorkProgram; // <<< TAMBAHKAN MODEL PROGRAM KERJA
use App\Models\PublicInformation; // Model untuk Ajuan Informasi Publik

class StaffDashboardController extends Controller
{
    /**
     * Menampilkan dasbor dinamis untuk staf.
     */
    public function index()
    {


        $user = Auth::user();
        // Ambil daftar nama permission yang dimiliki user
        $permissions = $user->getAllPermissions()->pluck('permission_name');

        $data = [];

        
        // ========================================================== //
        // ===        LOGIKA UNTUK MANAJEMEN RAPAT ABSENSI        === //
        // ========================================================== //
        if ($permissions->contains('Manajemen Absensi Rapat')) {
            // 1. Ambil Daftar Rapat yang Sedang Aktif (Absensi Dibuka)
            // Kita ambil data lengkapnya, bukan cuma count()
            $data['active_meetings'] = Meeting::withCount('attendances')
                                        ->where('is_active', true)
                                        ->latest('date')
                                        ->get();

            // 2. Ambil Daftar Agenda Rapat Hari Ini
            $data['today_meetings'] = Meeting::withCount('attendances')
                                        ->whereDate('date', Carbon::today())
                                        ->orderBy('start_time', 'asc')
                                        ->get();
        }
        
        // 1. Ambil data Pengaduan (jika punya izin)
        if ($permissions->contains('Manajemen Pengaduan')) {
            $data['pending_complaints_count'] = Complaint::where('status', 'Menunggu')->count();
            $data['recent_complaints'] = Complaint::where('status', 'Menunggu')
                                            ->latest()->take(5)->get();
        }

        // 2. Ambil data Ajuan Informasi Publik (jika punya izin)
        if ($permissions->contains('Manajemen Ajuan Informasi Publik')) {
            $data['pending_public_info_count'] = PublicInformation::where('status', 'Diajukan')->count();
            $data['recent_public_info'] = PublicInformation::where('status', 'Diajukan')
                                            ->with('user') // Ambil data pengaju
                                            ->latest()->take(5)->get();
        }

        // 3. Ambil data LLAU (jika punya izin)
        if ($permissions->contains('Manajemen Lalu Lintas Angkutan Udara')) {
            $data['llau_logs_this_month'] = AirTrafficLog::whereMonth('date', now()->month)
                                            ->whereYear('date', now()->year)
                                            ->count();

            // === LOGIKA BARU UNTUK GRAFIK 7 HARI ===
            $startDate = now()->subDays(6);
            $endDate = now();
            
            // Ambil data log 7 hari terakhir
            $logs = AirTrafficLog::whereBetween('date', [$startDate, $endDate])
                                ->get()
                                ->keyBy(fn($log) => $log->date->format('Y-m-d')); // Jadikan tanggal sebagai key

            $llau_chart_labels = [];
            $llau_series_pesawat = [];
            $llau_series_penumpang = [];
            $llau_series_bagasi = [];
            $llau_series_kargo = [];

            // Loop 7 hari dari $startDate sampai $endDate
            for ($i = 0; $i < 7; $i++) {
                $date = $startDate->copy()->addDays($i);
                $dateString = $date->format('Y-m-d');
                
                // Tambahkan label (misal: "10 Nov")
                $llau_chart_labels[] = $date->translatedFormat('d M');
                
                // Cek apakah ada data di tanggal ini
                $logForDay = $logs->get($dateString);

                if ($logForDay) {
                    // Jika ada data, jumlahkan arrival + departure
                    $llau_series_pesawat[] = $logForDay->aircraft_arrival + $logForDay->aircraft_departure;
                    $llau_series_penumpang[] = $logForDay->passenger_arrival + $logForDay->passenger_departure;
                    $llau_series_bagasi[] = $logForDay->baggage_arrival + $logForDay->baggage_departure;
                    $llau_series_kargo[] = $logForDay->cargo_arrival + $logForDay->cargo_departure;
                } else {
                    // Jika tidak ada data di tanggal itu, isi dengan 0
                    $llau_series_pesawat[] = 0;
                    $llau_series_penumpang[] = 0;
                    $llau_series_bagasi[] = 0;
                    $llau_series_kargo[] = 0;
                }
            }

            // Masukkan data yang sudah diformat ke $data
            $data['llau_7day_chart'] = [
                'labels' => $llau_chart_labels,
                'series' => [
                    ['name' => 'Pesawat', 'data' => $llau_series_pesawat],
                    ['name' => 'Penumpang', 'data' => $llau_series_penumpang],
                    ['name' => 'Bagasi (Kg)', 'data' => $llau_series_bagasi],
                    ['name' => 'Kargo (Kg)', 'data' => $llau_series_kargo],
                ]
            ];
            // === AKHIR LOGIKA BARU ===
        }

        // 4. Hitung total pengajuan layanan yang "Diajukan"
        // 4. Hitung pengajuan layanan yang "Diajukan" (secara terpisah)
        if ($permissions->contains('Manajemen Tenant')) {
            $data['pending_tenant_count'] = Tenant::where('submission_status', 'Diajukan')->count();
        }
        if ($permissions->contains('Manajemen Sewa')) {
            $data['pending_rental_count'] = Rental::where('submission_status', 'Diajukan')->count();
        }
        if ($permissions->contains('Manajemen Extend Advance')) {
            $data['pending_extend_advance_count'] = ExtendAdvance::where('submission_status', 'Diajukan')->count();
        }
        if ($permissions->contains('Manajemen Slot Charter')) {
            $data['pending_slot_charter_count'] = Slot::where('submission_status', 'Diajukan')->count();
        }
        if ($permissions->contains('Manajemen Perijinan Usaha')) {
            $data['pending_license_count'] = License::where('submission_status', 'Diajukan')->count();
        }
        if ($permissions->contains('Manajemen Pengiklanan')) {
            $data['pending_ad_count'] = Ad::where('submission_status', 'Diajukan')->count();
        }
        if ($permissions->contains('Manajemen Field Trip')) {
            $data['pending_fieldtrip_count'] = Fieldtrip::where('submission_status', 'Diajukan')->count();
        }
        if ($permissions->contains('Manajemen Lelang')) {
            $data['pending_lelang_count'] = Lelang::where('submission_status', 'Diajukan')->count();
        }
        // ... Tambahkan query untuk layanan lain di sini ...
        
        if ($permissions->contains('Manajemen Kinerja Keuangan')) {
            $data['finance_years'] = Finance::selectRaw('YEAR(date) as year')
                                    ->distinct()->orderBy('year', 'desc')->pluck('year')->toArray();

            // Ambil Pemasukan
            $pemasukan = Finance::where('flow_type', 'in')
                ->select(DB::raw('YEAR(date) as year'), DB::raw('MONTH(date) as month'), DB::raw('SUM(amount) as total'))
                ->groupBy('year', 'month')
                ->get();
            
            // Ambil Anggaran & Belanja
            $anggaran = Finance::with('budgetExpenses')->where('flow_type', 'budget')
                ->select(DB::raw('YEAR(date) as year'), DB::raw('MONTH(date) as month'), DB::raw('SUM(amount) as total_anggaran'))
                ->groupBy('year', 'month')
                ->get();

            // Hitung total belanja per bulan/tahun
            $belanja = Finance::where('flow_type', 'budget')
                ->join('budget_expenses', 'finances.id', '=', 'budget_expenses.finance_id')
                ->select(DB::raw('YEAR(finances.date) as year'), DB::raw('MONTH(finances.date) as month'), DB::raw('SUM(budget_expenses.amount) as total_belanja'))
                ->groupBy('year', 'month')
                ->get();

            // Format data untuk chart
            $pemasukanChartData = [];
            $anggaranChartData = [];
            $belanjaChartData = [];

            foreach ($data['finance_years'] as $year) {
                $pemasukanChartData[$year] = array_fill(0, 12, 0);
                $anggaranChartData[$year] = array_fill(0, 12, 0);
                $belanjaChartData[$year] = array_fill(0, 12, 0);
            }
            $pemasukanChartData['all'] = array_fill(0, 12, 0);
            $anggaranChartData['all'] = array_fill(0, 12, 0);
            $belanjaChartData['all'] = array_fill(0, 12, 0);

            // Isi data Pemasukan
            foreach ($pemasukan as $item) {
                $monthIndex = $item->month - 1;
                if (isset($pemasukanChartData[$item->year])) {
                    $pemasukanChartData[$item->year][$monthIndex] = $item->total;
                }
                $pemasukanChartData['all'][$monthIndex] += $item->total;
            }

            // Isi data Anggaran
            foreach ($anggaran as $item) {
                $monthIndex = $item->month - 1;
                if (isset($anggaranChartData[$item->year])) {
                    $anggaranChartData[$item->year][$monthIndex] = $item->total_anggaran;
                }
                $anggaranChartData['all'][$monthIndex] += $item->total_anggaran;
            }

            // Isi data Belanja
            foreach ($belanja as $item) {
                $monthIndex = $item->month - 1;
                if (isset($belanjaChartData[$item->year])) {
                    $belanjaChartData[$item->year][$monthIndex] = $item->total_belanja;
                }
                $belanjaChartData['all'][$monthIndex] += $item->total_belanja;
            }
            
            $data['pemasukan_chart_data'] = $pemasukanChartData;
            $data['anggaran_chart_data'] = $anggaranChartData;
            $data['belanja_chart_data'] = $belanjaChartData;
        }
        
        // 5. Ambil data Inventaris (jika punya izin)
        if ($permissions->contains('Manajemen Inventaris')) {
            $data['total_inventory'] = Inventory::count();
            $data['maintenance_inventory_count'] = Inventory::where('status', 'Pemeliharaan')->count();
        }

        // 6. Ambil data Program Kerja (jika punya izin)
        if ($permissions->contains('Manajemen Program Kerja')) {
            
            // 1. Dapatkan kategori yang dimiliki user dari RoleWorkCategory
            $userCategories = [];
            foreach ($user->roles as $role) {
                // Ambil semua kategori yang terkait dengan role ini
                $roleCategories = RoleWorkCategory::where('role_id', $role->id)->pluck('category_name')->toArray();
                $userCategories = array_merge($userCategories, $roleCategories);
            }
            $userCategories = array_unique($userCategories);

            // 2. Query Dasar Program Kerja (Filter Kategori)
            $workProgramQuery = WorkProgram::query();
            
            // Jika user memiliki kategori spesifik, filter.
            // Jika user adalah 'Super Admin' atau 'Admin', mungkin kita ingin tampilkan semua (opsional).
            // Di sini kita asumsikan filter ketat berdasarkan kategori yang dimiliki.
            if (!empty($userCategories)) {
                $workProgramQuery->whereIn('category', $userCategories);
            } elseif (!$user->hasRole('Super Admin') && !$user->hasRole('Admin')) {
                // Jika tidak punya kategori dan bukan admin, jangan tampilkan apa-apa
                $workProgramQuery->whereRaw('1 = 0'); 
            }

            // Hitung Total Program Kerja (sesuai kategori)
            $data['total_work_programs'] = $workProgramQuery->count();

            // 3. Hitung Tugas (Tasks)
            // Kita perlu filter tugas yang induk program kerjanya sesuai kategori user
            
            // Cek apakah user punya hak verifikasi (Permission: 'Verifikasi Program Kerja')
            // ATAU cek flag 'can_verify' di tabel role_work_categories (jika Anda menggunakan logika itu)
            // Di sini kita gunakan permission 'Verifikasi Program Kerja' sebagai penentu utama apakah dia verifikator.
            
            $isVerifier = $user->hasPermissionTo('Verifikasi Program Kerja');

            if ($isVerifier) {
                // Jika Verifikator: Hitung tugas yang 'Menunggu Verifikasi'
                // HANYA untuk program kerja yang kategorinya dimiliki user DAN user punya hak verifikasi di kategori itu
                
                // Ambil kategori di mana user punya hak verifikasi (can_verify = 1)
                $verifyCategories = [];
                foreach ($user->roles as $role) {
                    $cats = RoleWorkCategory::where('role_id', $role->id)
                                            ->where('can_verify', true)
                                            ->pluck('category_name')
                                            ->toArray();
                    $verifyCategories = array_merge($verifyCategories, $cats);
                }
                $verifyCategories = array_unique($verifyCategories);

                if (!empty($verifyCategories)) {
                     $data['tasks_awaiting_verification'] = Task::whereHas('workProgram', function($q) use ($verifyCategories) {
                        $q->whereIn('category', $verifyCategories);
                    })->where('status', 'Menunggu Verifikasi')->count();
                } else {
                    $data['tasks_awaiting_verification'] = 0;
                }

            } else {
                // Jika Staf Biasa: Hitung tugas yang 'Revisi Diperlukan'
                // Filter berdasarkan semua kategori yang dia miliki (akses view/edit)
                if (!empty($userCategories)) {
                    $data['tasks_needing_revision'] = Task::whereHas('workProgram', function($q) use ($userCategories) {
                        $q->whereIn('category', $userCategories);
                    })->where('status', 'Revisi Diperlukan')->count();
                } else {
                    $data['tasks_needing_revision'] = 0;
                }
            }
        }
        // ==================================================================== //


        return view('user_staff2.dashboard.index', [
            'permissions' => $permissions,
            'data' => $data
        ]);
    }
}