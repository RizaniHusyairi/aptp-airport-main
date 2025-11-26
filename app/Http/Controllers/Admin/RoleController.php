<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Validation\Rule;
use App\Models\RoleWorkCategory; // Pastikan model ini ada
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    private $coreRoles = [
        'Kepala Bandara', 'Kepala Subbagian Keuangan dan Tata Usaha',
        'Kepala Seksi Keamanan Penerbangan dan Pelayanan Darurat',
        'Kepala Seksi Pelayanan dan Kerjasama', 'Kepala Seksi Teknik dan Operasi',
    ];

    // Daftar kategori statis (bisa juga dari DB jika mau lebih dinamis)
    private $workCategories = [
        'Alat-alat Besar',
        'Fasilitas Sisi Udara',
        'Fasilitas Sisi Darat',
        'Elektronika Bandara',
        'Listrik',
        'Mekanikal',
        'Fasilitas Keamanan Penerbangan',
        'PKPPK'
    ];

    public function index(Request $request)
    {
        $roles = Role::with('permissions')->get();
        return view('admin2.roles.index', [
            'roles' => $roles,
            'coreRoles' => $this->coreRoles
        ]);
    }

    public function create(Request $request)
    {
        $permissions = Permission::all(['id', 'permission_name']);
        return view('admin2.roles.create', [
            'permissions' => $permissions,
            'workCategories' => $this->workCategories // Kirim daftar kategori
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['required', 'array', 'min:1'], // Permission dasar wajib ada
            'permissions.*' => ['exists:permissions,id'],
            
            // Validasi input baru kita
            'work_categories' => 'nullable|array', // Harus berupa array
            'work_categories.*' => 'string', // Isinya string
            'assign_verifier_permission' => 'nullable' // Checkbox toggle (bisa ada/tidak)
        ]);

        DB::transaction(function () use ($request) {
            // 2. Buat Role Baru
            $role = Role::create(['name' => $request->name]);
            
            // Ambil permission yang dipilih dari form
            $permissionsToSync = $request->permissions;

            // 3. LOGIKA OTOMATIS: Permission Verifikasi
            // Cek apakah toggle 'Izinkan Verifikasi' dicentang?
            $canVerify = $request->has('assign_verifier_permission');

            if ($canVerify) {
                // Cari ID permission 'Verifikasi Program Kerja' di database
                $verifierPermission = Permission::where('permission_name', 'Verifikasi Program Kerja')->first();
                
                if ($verifierPermission) {
                    // Masukkan ID tersebut ke dalam array permission yang akan disimpan
                    // Jadi admin tidak perlu centang manual di daftar permission yang panjang
                    $permissionsToSync[] = $verifierPermission->id;
                }
            }

            // Simpan semua permission ke role (sync memastikan tidak ada duplikat)
            $role->permissions()->sync(array_unique($permissionsToSync));

            // 4. Simpan Kategori Program Kerja (Jika Ada)
            if ($request->has('work_categories')) {
                foreach ($request->work_categories as $category) {
                    RoleWorkCategory::create([
                        'role_id' => $role->id,
                        'category_name' => $category,
                        // Kolom ini menandakan di kategori ini dia berhak memverifikasi atau tidak
                        'can_verify' => $canVerify ? 1 : 0 
                    ]);
                }
            }
        });

        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat beserta konfigurasi program kerjanya!');
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();
        $isCoreRole = in_array($role->name, $this->coreRoles);
        
        // Ambil kategori yang sudah tersimpan untuk role ini
        $selectedCategories = RoleWorkCategory::where('role_id', $role->id)->pluck('category_name')->toArray();
        $canVerify = RoleWorkCategory::where('role_id', $role->id)->where('can_verify', true)->exists();
// === PERBAIKAN DI SINI: Ambil ID permission yang dimiliki role ===
        $rolePermissions = $role->permissions->pluck('id')->toArray();


        return view('admin2.roles.edit', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
            'isCoreRole' => $isCoreRole,
            'workCategories' => $this->workCategories,
            'selectedCategories' => $selectedCategories,
            'canVerify' => $canVerify
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $isCoreRole = in_array($role->name, $this->coreRoles);

        $validated = $request->validate([
            'name' => $isCoreRole ? 'nullable|string' : ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
            'permissions' => 'required|array|min:1',
            // Validasi input baru
            'work_categories' => 'nullable|array',
            'work_categories.*' => 'string',
            'assign_verifier_permission' => 'nullable'
        ]);

        DB::transaction(function () use ($request, $role, $isCoreRole, $validated) {
            // 1. Update Nama Role (Jika bukan core role)
            if (!$isCoreRole) {
                $role->update(['name' => $validated['name']]);
            }
            
            // 2. Kelola Permissions
            $permissionsToSync = $request->permissions;
            $canVerify = $request->has('assign_verifier_permission');

            // Logika Otomatis Permission Verifikasi
            $verifierPermission = Permission::where('permission_name', 'Verifikasi Program Kerja')->first();
            
            if ($verifierPermission) {
                if ($canVerify) {
                    // Jika dicentang, TAMBAHKAN permission verifikasi
                    if (!in_array($verifierPermission->id, $permissionsToSync)) {
                        $permissionsToSync[] = $verifierPermission->id;
                    }
                } else {
                    // Jika TIDAK dicentang, HAPUS permission verifikasi dari array (jika ada)
                    // Ini penting: user mungkin manual mencentang di daftar permission, kita override sesuai toggle
                    $permissionsToSync = array_diff($permissionsToSync, [$verifierPermission->id]);
                }
            }

            // Simpan permission (Sync akan menghapus yang tidak ada di array ini)
            $role->permissions()->sync($permissionsToSync);

            // 3. Kelola Kategori Program Kerja
            // Strategi: Hapus semua kategori lama milik role ini, lalu buat baru
            RoleWorkCategory::where('role_id', $role->id)->delete();
            
            if ($request->has('work_categories')) {
                foreach ($request->work_categories as $category) {
                    RoleWorkCategory::create([
                        'role_id' => $role->id,
                        'category_name' => $category,
                        'can_verify' => $canVerify ? 1 : 0 // Set status verifikasi untuk kategori ini
                    ]);
                }
            }
        });

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        if (in_array($role->name, $this->coreRoles)) {
            return back()->with('error', 'Role inti tidak dapat dihapus.');
        }
        if ($role->users()->count() > 0) {
             return back()->with('error', 'Role masih digunakan oleh user.');
        }
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus!');
    }
}