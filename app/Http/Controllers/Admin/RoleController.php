<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{

    private $coreRoles = [
        'Kepala Bandara',
        'Kepala Subbagian Keuangan dan Tata Usaha',
        'Kepala Seksi Keamanan Penerbangan dan Pelayanan Darurat',
        'Kepala Seksi Pelayanan dan Kerjasama',
        'Kepala Seksi Teknik dan Operasi',
        'Kanit'
    ];

    public function index(Request $request)
    {
        
        $roles = Role::with('permissions')->get();
        $coreRoles = $this->coreRoles;
        

        return view('admin2.roles.index', compact('roles','coreRoles'));

    }

    public function create(Request $request){
        $permissions = Permission::all(['id', 'permission_name']);

        return view('admin2.roles.create', compact('permissions'));
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['exists:permissions,id'],
        ], [
            'name.required' => 'Nama role wajib diisi.',
            'name.unique' => 'Nama role sudah digunakan.',
            'permissions.required' => 'Pilih setidaknya satu izin.',
            'permissions.min' => 'Pilih setidaknya satu izin.',
            'permissions.*.exists' => 'Izin yang dipilih tidak valid.',
        ]);

        // Proteksi tambahan: jangan izinkan membuat role dengan nama yang sudah dilindungi
        if (in_array($validated['name'], $this->coreRoles)) {
            return back()->withInput()->withErrors(['name' => 'Nama role ini dilindungi oleh sistem dan tidak dapat dibuat ulang.']);
        }
        

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->permissions()->attach($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat!');
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id); // Menyertakan permissions yang dimiliki role
        $permissions = Permission::all(); // Mengambil semua permissions
        $roles = Role::orderBy('name')->get();
        // Cek apakah role yang sedang diedit adalah core role
        $isCoreRole = in_array($role->name, $this->coreRoles);

        return view('admin2.roles.edit', compact('role', 'permissions','roles','isCoreRole'));
    }

    public function update(Request $request, Role $role)
    {

        $isCoreRole = in_array($role->name, $this->coreRoles);

        $validated = $request->validate([
            // Validasi 'name' hanya jika BUKAN core role
            'name' => $isCoreRole 
                        ? 'nullable|string' 
                        : ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // === INI ADALAH LOGIKA PERBAIKANNYA ===
        if ($isCoreRole) {
            // Jika ini adalah core role, JANGAN perbarui nama,
            // $roleName diambil dari data yang sudah ada, BUKAN dari request
            $roleName = $role->name; 
        } else {
            // Jika bukan core role, baru perbarui namanya dari request
            $roleName = $validated['name'];
        }

        // Perbarui database dengan nama yang dijamin tidak null
        $role->update(['name' => $roleName]);
        $role->permissions()->sync($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');

    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // === Logika Pengaman di Backend ===
        if (in_array($role->name, $this->coreRoles)) {
            return back()->with('error', 'Role inti sistem (' . $role->name . ') tidak dapat dihapus.');
        }
        
        if ($role->users()->count() > 0) {
             return back()->with('error', 'Role ini tidak dapat dihapus karena masih digunakan oleh user.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus!');
    }
}
