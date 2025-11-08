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
        }

        // 4. Hitung total pengajuan layanan yang "Diajukan"
        $pending_submissions = 0;
        if ($permissions->contains('Manajemen Tenant')) {
            $pending_submissions += Tenant::where('submission_status', 'Diajukan')->count();
        }
        if ($permissions->contains('Manajemen Sewa')) {
            $pending_submissions += Rental::where('submission_status', 'Diajukan')->count();
        }
        if ($permissions->contains('Manajemen Extend Advance')) {
            $pending_submissions += ExtendAdvance::where('submission_status', 'Diajukan')->count();
        }
        if ($permissions->contains('Manajemen Slot Charter')) {
            // Asumsi model 'Slot' memiliki kolom 'submission_status'
            $pending_submissions += Slot::where('submission_status', 'Diajukan')->count();
        }
        // ... Tambahkan query untuk layanan lain di sini ...
        
        $data['pending_submissions_count'] = $pending_submissions;
        
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