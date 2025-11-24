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
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['exists:permissions,id'],
            // Validasi kategori (opsional, hanya jika permission manajemen program kerja dipilih)
            'work_categories' => 'nullable|array',
            'can_verify' => 'nullable|boolean'
        ]);

        DB::transaction(function () use ($request) {
            $role = Role::create(['name' => $request->name]);
            
            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }

            // Simpan Kategori Program Kerja
            if ($request->has('work_categories')) {
                foreach ($request->work_categories as $category) {
                    RoleWorkCategory::create([
                        'role_id' => $role->id,
                        'category_name' => $category,
                        'can_verify' => $request->has('can_verify') ? 1 : 0
                    ]);
                }
            }
        });

        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat!');
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();
        $isCoreRole = in_array($role->name, $this->coreRoles);
        
        // Ambil kategori yang sudah tersimpan untuk role ini
        $selectedCategories = RoleWorkCategory::where('role_id', $role->id)->pluck('category_name')->toArray();
        $canVerify = RoleWorkCategory::where('role_id', $role->id)->where('can_verify', true)->exists();

        return view('admin2.roles.edit', [
            'role' => $role,
            'permissions' => $permissions,
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
            'work_categories' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $role, $isCoreRole, $validated) {
            if (!$isCoreRole) {
                $role->update(['name' => $validated['name']]);
            }
            
            $role->permissions()->sync($request->permissions);

            // Update Kategori: Hapus lama, simpan baru
            RoleWorkCategory::where('role_id', $role->id)->delete();
            
            if ($request->has('work_categories')) {
                foreach ($request->work_categories as $category) {
                    RoleWorkCategory::create([
                        'role_id' => $role->id,
                        'category_name' => $category,
                        'can_verify' => $request->has('can_verify') ? 1 : 0
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