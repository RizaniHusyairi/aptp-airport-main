<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Complaint; // Model untuk Pengaduan
use App\Models\PublicInformation; // Model untuk Ajuan Informasi Publik
use App\Models\AirTrafficLog; // Model untuk LLAU
use App\Models\Tenant; // Model untuk Tenant
use App\Models\Rental; // Model untuk Sewa
use App\Models\ExtendAdvance; // Model untuk Extend Advance
use App\Models\Slot; // Model untuk Slot Charter
use App\Models\Inventory;  // <<< TAMBAHKAN MODEL INVENTARIS
use App\Models\WorkProgram; // <<< TAMBAHKAN MODEL PROGRAM KERJA
use App\Models\Task;       // <<< TAMBAHKAN MODEL TUGAS
use App\Models\License;    // <-- TAMBAHKAN
use App\Models\Ad;        // <-- TAMBAHKAN
use App\Models\Fieldtrip; // <-- TAMBAHKAN
use App\Models\Lelang;    // <-- TAMBAHKAN
use Carbon\Carbon;

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
        
        
        // 5. Ambil data Inventaris (jika punya izin)
        if ($permissions->contains('Manajemen Inventaris')) {
            $data['total_inventory'] = Inventory::count();
            $data['maintenance_inventory_count'] = Inventory::where('status', 'Pemeliharaan')->count();
        }

        // 6. Ambil data Program Kerja (jika punya izin)
        if ($permissions->contains('Manajemen Program Kerja')) {
            $data['total_work_programs'] = WorkProgram::count();
            
            // Definisikan role Kanit
            $kanitRoles = [
                'Kanit'
                // Tambahkan role Kepala Subbagian jika perlu
            ];

            if ($user->hasRole($kanitRoles)) {
                // Jika Kanit, tampilkan tugas yang perlu diverifikasi
                $data['tasks_awaiting_verification'] = Task::where('status', 'Menunggu Verifikasi')->count();
            } else {
                // Jika Staf biasa, tampilkan tugas yang perlu direvisi
                // Note: Ini akan menampilkan *semua* tugas revisi. 
                // Untuk menampilkan hanya yang dibuat olehnya, diperlukan relasi 'user_id' di WorkProgram/Task.
                $data['tasks_needing_revision'] = Task::where('status', 'Revisi Diperlukan')->count();
            }
        }

        return view('user_staff2.dashboard.index', [
            'permissions' => $permissions,
            'data' => $data
        ]);
    }
}